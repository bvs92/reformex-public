<template>
  <div>
        <div class="row my-2">
            <div class="col-lg-6 my-1">
                Sortare dupa: <b>{{ getSortBy }}</b>, Mod:
                <b>{{ sortDesc ? 'Descendent' : 'Ascendent' }}</b>
            </div>

            <b-col lg="6" class="my-1">
                <b-form-group>
                <b-input-group size="sm">
                    <b-form-input
                    id="filter-input"
                    v-model="filter"
                    type="search"
                    placeholder="Cauta activitate"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Sterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>

        <b-table
            id="coupons-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getTheUserActivities"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :busy.sync="isBusy"
            responsive="sm"
            show-empty
            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
        >
            <template #table-busy>
                <div class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Se incarca datele...</strong>
                </div>
            </template>

            <template #empty="scope">
                <p class="text-center">Niciun rezultat.</p>
            </template>


            <template #cell(description)="data">
                <b class="text-info">{{ data.item.description }}</b>
            </template>
            
            <template #cell(amount)="data">
                <b class="text-info">{{ data.item.amount / 100 }} RON</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item.created_at) }}</b>
            </template>


            <!-- <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Vezi
                </b-button>
            </template> -->

        </b-table>

        <div class="overflow-auto" v-if="getTotalPersonalActivities > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalPersonalActivities" 
                :per-page="perPage"
                aria-controls="coupons-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "PersonalActivitiesComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalUserActivities,
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        isBusy: false,
        sortBy: 'created',
        sortDesc: true,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
        //   { key: 'email', sortable: true },
          { key: 'demand_uuid', label: 'Cerere', sortable: true },
          { key: 'description', sortable: true, label: 'Tip' },
          { key: 'created_at', sortable: true, label: 'Data' },
          { key: 'amount', label: 'Cost', sortable: true },
        //   { key: 'actions', sortable: false, label: 'Actiuni' },
        ],
        items: []
      }
    },

    computed: {
        ...mapGetters('activity', ['getPersonalActivities', 'getTotalPersonalActivities']),

        getTheUserActivities: function(){
            return this.getPersonalActivities.map(item => {
                this.formatElementTimeMethod(item.created_at);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Data creare';
            } else if(this.sortBy == 'amount'){
                return 'Valoare';
            } else if (this.sortBy == 'demand_uuid'){
                return 'Cerere';
            }
        }
    },

    props: {
        user_id: Number
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/charges/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('activity/initPersonalActivities').finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>