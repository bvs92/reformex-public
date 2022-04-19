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
                    placeholder="Cauta tichet"
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
            :current-page="current_page"
            striped
            bordered
            :items="tickets"
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

            <template #cell(uuid)="data">
                {{ data.item.uuid }} <b-badge v-if="data.item.total_unread > 0" href="#" variant="info">{{ data.item.total_unread }}</b-badge>
            </template>

            <template #cell(email)="data">
                <p class="text-info">{{ data.item.email }} <a :href="'/users/admin/show/' + data.item.user_id" target="_blank"><i class="ti-new-window"></i></a></p>
            </template>


            <template #cell(status)="data">
                <b class="text-info">{{ formatStatus(data.item.status) }}</b>
            </template>

            <template #cell(department_id)="data">
                <b class="text-info">{{ formatDepartment(data.item.department_id) }}</b>
            </template>


            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Detalii
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="total > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="total" 
                :per-page="perPage"
                aria-controls="coupons-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>



export default {
    name: "TicketsTableComponent",

    data() {
      return {
   
        perPage: 20,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        // isBusy: false,
        sortBy: 'created_at',
        sortDesc: true,
        fields: [
          { key: 'uuid', sortable: true, label: 'ID' },
          { key: 'email', label: 'Utilizator', sortable: true },
          { key: 'status', label: 'Status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Data' },
          { key: 'department_id', label: 'Departament', sortable: true },
          { key: 'total_resolvers', label: 'Moderatori', sortable: true },
          { key: 'actions', sortable: false, label: 'Actiuni' },
    
        ],
        items: []
      }
    },

    props: {
        total: Number,
        tickets: Array,
        isBusy: Boolean,
        current_page: Number
    },

    computed: {

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Data creare';
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
                return 'Inchis';
            } else if(status == 0){
                return 'Deschis';
            }
        },

        formatDepartment: function(department){
            if(department == 0){
                return 'General';
            } else if(department == 1){
                return 'Comercial';
            } else if(department == 2){
                return 'Tehnic';
            }
        },


        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/tichete/detalii/id/' + _item.item.uuid;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

    },

    created: function(){
     
    }
}
</script>

<style>

</style>