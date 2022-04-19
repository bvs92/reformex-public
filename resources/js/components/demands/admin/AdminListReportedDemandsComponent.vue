<template>
   <div class="mt-4">
        <h3 class="my-3">Cereri</h3>
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
                    placeholder="Caută cerere"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>

            <div class="col-lg-6">
                
             
                
            </div>
        </div>

            <b-table
            id="demands-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getTheDemands"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :busy.sync="isBusy"
            responsive="sm"

            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
            show-empty
            >
            <template #table-busy>
                <div class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Se încarcă datele...</strong>
                </div>
            </template>

            <template #empty>
                <p class="text-center">Niciun element disponibil.</p>
            </template>

            <template #cell(status)="data">
                <b class="text-info">{{ formatState(data.item.state) }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item.created_at) }}</b>
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click.prevent="goToPage(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalReportedDemands > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalReportedDemands" 
                :per-page="perPage"
                aria-controls="demands-table"
                align="center"></b-pagination>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "AdminListReportedDemandsComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalUsers,
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],
        sortDirection: 'asc',

        // table
        isBusy: false,
        sortBy: 'created_at',
        sortDesc: true,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
          { key: 'email', sortable: true },
          { key: 'status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Înregistrare' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ]
      }
    },

    computed: {
        ...mapGetters('admin_demands', ['getReportedDemands', 'getTotalReportedDemands']),

        getTheDemands: function(){
            if(this.getTotalReportedDemands > 0){
                return this.getReportedDemands.map(item => {
                    this.formatElementTimeMethod(item.created_at);
                    return item;
                })
            } else {
                return [];
            }
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată înregistrare';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            } else if (this.sortBy == 'status'){
                return 'Status';
            }
        }
    },

    props: {},

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("DD-MM-YYYY");
        },

        formatState: function(state){
            if(state == 1){
                return 'Activa';
            } else if(state == 0){
                return 'Inactiva';
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/demands/id/' + _item.item.uuid;
            window.location = '/admin/demands/show/' + _item.item.uuid;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            console.log('filtered item este', filteredItems);
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('admin_demands/initReportedDemands').finally(() => {
            this.isBusy = false;
        });
    }



}
</script>

