<template>
<div class="">
  

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
                    placeholder="Caută categorie proiect"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>


        <b-table
            id="categories-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getCategories"
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

            <template #cell(actions)="row">
                <a :href="'/categorii-proiecte/' + row.item.slug" class="btn btn-sm btn-info">
                Vezi
                </a>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalCategories > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalCategories" 
                :per-page="perPage"
                aria-controls="categories-table"
                align="center"></b-pagination>
            </div>
        </div>

    
</div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "ListProjectCategoriesComponent",

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
        sortBy: 'name',
        sortDesc: false,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
          { key: 'name', sortable: true, label: 'Nume categorie' },
          { key: 'total_projects', sortable: true, label: 'Număr proiecte' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ]
      }
    },

    computed: {
        ...mapGetters('project_categories', ['getCategories', 'getTotalCategories']),

        getTheCategories: function(){
            return this.getCategories.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'name'){
                return 'Nume';
            } else if(this.sortBy == 'total_projects'){
                return 'Număr proiecte';
            }
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("lll");
            // return moment(item.created_at).format("DD-MM-YYYY");
        },


        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/categories/show/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('project_categories/initCategories').finally(() => {
            this.isBusy = false;
        });
    }

}
</script>