<template>
<div class="">
  
        <div class="row mb-5 p-5" style="background: lightgrey;">
            <div class="col-lg-6">
                <b-form-select v-model="selected_type" :options="options"></b-form-select>
            </div>
            <div class="col-lg-6 d-flex justify-content-center">
                <button class="btn btn-info mx-auto" @click.prevent="loadBanners">Preluare anunțuri</button>
            </div>
        </div>
        <br><br>
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
                    placeholder="Caută anunț"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>


        <b-table
            id="banners-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getBanners"
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
                <a class="btn btn-sm btn-info" :href="'/publicitate/admin/banner/detalii/' + row.item.uuid">
                Vezi
                </a>
            </template>

            <template #cell(created_at)="row">
                {{ formatElementTimeMethod(row.item.created_at) }}
            </template>

            <template #cell(ends_at)="row">
                {{ formatElementTimeMethod(row.item.ends_at) }}
            </template>

            <template #cell(status)="row">
                {{ row.item.status == '1' ? 'Activ' : 'Inactiv' }}
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalBanners > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalBanners" 
                :per-page="perPage"
                aria-controls="banners-table"
                align="center"></b-pagination>
            </div>
        </div>

    
</div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "ListBannersComponent",

    data() {
      return {

          // select type
        selected_type: null,
        options: [
          { value: null, text: 'Selectează' },
          { value: 'active', text: 'Active' },
          { value: 'inactive', text: 'Inactive' },
          { value: 'expired', text: 'Expirate' },
          { value: 'processing', text: 'Propuse' },
        ],

        // pagination
        // rows: this.getTotalUsers,
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        isBusy: false,
        sortBy: 'email',
        sortDesc: true,
        fields: [
          { key: 'uuid', sortable: true, label: 'ID' },
          { key: 'created_at', sortable: true, label: 'Dată creare' },
          { key: 'ends_at', sortable: true, label: 'Dată expirare' },
          { key: 'status', sortable: true, label: 'Status' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ]
      }
    },

    computed: {
        ...mapGetters('banners', ['getBanners', 'getTotalBanners']),

        getTheCategories: function(){
            return this.getCategories.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată creare';
            } else if(this.sortBy == 'ends_at'){
                return 'Dată expirare';
            } else if(this.status) {
                return 'Status';
            }
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },


        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/categories/show/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        loadBanners: async function(){
            if(this.selected_type !== null){

                if(this.selected_type == 'processing'){
                    this.isBusy = true;
                    await this.$store.dispatch('banners/loadProcessingBanners').finally(() => {
                        this.isBusy = false;
                    });
                }
                
                this.isBusy = true;
                await this.$store.dispatch('banners/loadBanners', this.selected_type).finally(() => {
                    this.isBusy = false;
                });
            }
        }
    },

    created: function(){
        
    }

}
</script>