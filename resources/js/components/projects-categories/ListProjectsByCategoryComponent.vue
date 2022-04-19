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
                    placeholder="Caută proiect"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>

        <b-table
            id="projects-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getProjects"
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


            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <a class="btn btn-sm btn-info" :href="'/proiecte-lucrari/' + row.item.uuid">
                Vezi
                </a>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalProjects > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalProjects" 
                :per-page="perPage"
                aria-controls="projects-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "ListProjectsByCategoryComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalProjects,
        perPage: 20,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        isBusy: false,
        sortBy: 'created_at',
        sortDesc: true,
        fields: [
          { key: 'uuid', sortable: true, label: 'ID' },
          { key: 'title', label: 'Titlu', sortable: false },
          { key: 'user_email', label: 'Utilizator', sortable: true },
          { key: 'number_photos',label: 'Numar fotografii', sortable: true },
          { key: 'created_at', sortable: true, label: 'Data creare' },
          { key: 'actions', sortable: false, label: 'Actiuni' },
        ],
        items: []
      }
    },

    props: {
        category: Object
    },

    computed: {
        ...mapGetters('projects', ['getProjects', 'getTotalProjects']),

        getTheCoupons: function(){
            return this.getProjects.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Data creare';
            } else if(this.sortBy == 'title'){
                return 'Titlu';
            } else if (this.sortBy == 'number_photos'){
                return 'Numar fotografii';
            } else if (this.sortBy == 'user_email'){
                return 'Utilizator';
            }
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("lll");
        },


        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/coupons/detalii/' + _item.item.uuid;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('projects/initProjectsByCategory', this.category.uuid).finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>

