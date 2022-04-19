<template>
  <div>
        <div class="row my-2">
            <div class="col-lg-6 my-1">
                Sortare după: <b>{{ getSortBy }}</b>, Mod:
                <b>{{ sortDesc ? 'Descendent' : 'Ascendent' }}</b>
            </div>

            <b-col lg="6" class="my-1">
                <b-form-group>
                <b-input-group size="sm">
                    <b-form-input
                    id="filter-input"
                    v-model="filter"
                    type="search"
                    placeholder="Caută tranzacție"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
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
            :items="getTheUserCharges"
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
                <strong>Se încarcă datele...</strong>
                </div>
            </template>

            <template #empty="scope">
                <p class="text-center">Niciun rezultat.</p>
            </template>


            <template #cell(status)="data">
                <b class="text-info">{{ formatStatus(data.item.status) }}</b>
            </template>
            
            <template #cell(amount)="data">
                <b class="text-info">{{ data.item.amount / 100 }} RON</b>
            </template>

            <template #cell(created)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item.created) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>


            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalUserCharges > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalUserCharges" 
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
    name: "UserChargesComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalUserCharges,
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
          { key: 'amount',label: 'Valoare', sortable: true },
          { key: 'status', label: 'Status', sortable: true },
          { key: 'created', sortable: true, label: 'Dată' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ],
        items: []
      }
    },

    computed: {
        ...mapGetters('charges', ['getUserCharges', 'getTotalUserCharges']),

        getTheUserCharges: function(){
            return this.getUserCharges.map(item => {
                this.formatElementTimeMethod(item.created);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created'){
                return 'Dată creare';
            } else if(this.sortBy == 'amount'){
                return 'Valoare';
            } else if (this.sortBy == 'status'){
                return 'Status';
            }
        }
    },

    props: {
        user_id: Number
    },

    methods: {
        formatElementTimeMethod: function(item){
            // return moment(item).format("DD-MM-YYYY");
            return moment(item).format("lll");
        },

        formatStatus: function(status){
            if(status == 'succeeded'){
                return 'Succes';
            } else if(status == 'failed'){
                return 'Eșuat';
            } else if (status == 'pending'){
                return 'În așteptare'
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/charges/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('charges/initUserCharges', this.user_id).finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>