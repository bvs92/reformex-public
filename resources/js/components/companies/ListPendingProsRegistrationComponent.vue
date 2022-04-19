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
                    placeholder="Caută utilizator"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>

        <b-table
            id="users-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getUsers"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :busy.sync="isBusy"
            responsive="sm"

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

            <template #cell(status)="data">
                <b class="text-info">{{ formatStatus(data.item.status) }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalUsers > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalUsers" 
                :per-page="perPage"
                aria-controls="users-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "ListPendingProsRegistrationComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalUsers,
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],

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
        ],
        items: [
        //   { isActive: true, age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
        //   { isActive: false, age: 21, first_name: 'Larsen', last_name: 'Shaw' },
        //   { isActive: false, age: 89, first_name: 'Geneva', last_name: 'Wilson' },
        //   { isActive: true, age: 38, first_name: 'Jami', last_name: 'Carney' }
        ]
      }
    },

    computed: {
        ...mapGetters('companies', ['getUsers', 'getTotalUsers']),

        getTheUsers: function(){
            return this.getUsers.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
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

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("lll");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Activ';
            } else if(status == 0){
                return 'Inactiv';
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/companii/detalii/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('companies/initProsUsers').finally(() => {
            this.isBusy = false;
        });
    }



}
</script>

