<template>
<div class="">
    <div class="row my-4 announcement" v-for="banner in getBanners" :key="banner.id">
        <div class="col-lg-3 p-2">
            <img :src="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/banners/' + banner.image" class="fit-image" />
        </div>
        <div class="col-lg-7 py-4">
            <div class="pl-2">
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <span v-if="banner.status == 1" class="badge badge-success mr-1">Activ</span>
                        <span v-else class="badge badge-danger mr-1">Inactiv</span> <span class="badge badge-warning mr-1" v-if="banner.processing == 1">În analiză</span>
                    </div>
                    <div class="col-lg-12 my-3" v-if="banner.rejected == 1">
                        <span class="badge badge-danger mr-1">Refuzat</span> Verifică informațiile și retrimite pentru validare.
                    </div>
                </div>
                <p>Firmă: {{ banner.name }}</p>
                <p>Dată creare: {{ formatElementTimeMethod(banner.created_at) }} <span v-if="checkValidDate(banner.ends_at)">, Dată expirare: {{ formatElementTimeMethod(banner.ends_at) }}</span></p>
                <p></p>
                <p>E-mail: {{ banner.email }}</p>
                <div v-if="banner.categories">
                    Categorii: <span class="badge badge-default mr-1" v-for="category in banner.categories" :key="category.id">{{ category.name }}</span>
                </div>
            </div>
            <br>
            <div class="mt-2">
                <a :href="'/publicitate/banner/detalii/' + banner.uuid" class="btn btn-info">Vezi detalii</a>
            </div>
        </div>
    </div>
        <!-- <div class="row my-2">
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
      </div> -->


        <!-- <b-table
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
                <a class="btn btn-sm btn-info" :href="'/publicitate/banner/detalii/' + row.item.uuid">
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

        </b-table> -->

        <!-- <div class="overflow-auto" v-if="getTotalBanners > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalBanners" 
                :per-page="perPage"
                aria-controls="banners-table"
                align="center"></b-pagination>
            </div>
        </div> -->

    
</div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "ListPersonalBannersComponent",

    data() {
      return {

          // select type
        selected_type: null,
        options: [
          { value: null, text: 'Selectează' },
          { value: 'active', text: 'Active' },
          { value: 'inactive', text: 'Inactive' },
          { value: 'expired', text: 'Expirate' },
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
          { key: 'id', sortable: true, label: 'ID' },
          { key: 'email', sortable: true, label: 'E-mail' },
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
        checkValidDate: function(date_elem){
            if(date_elem == null || date_elem == 'null'){
                return false;
            }
            return true;
        },

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
            this.isBusy = true;
            await this.$store.dispatch('banners/allBanners').finally(() => {
                this.isBusy = false;
            });
        }
    },

    created: async function(){
        await this.loadBanners();
    }

}
</script>

<style scoped>
.fit-image {
    width: 100%;height: 250px!important;max-height: 250px!important;
    object-fit: cover;
    border-radius: 5px;;
}

.announcement {
        background: #f6f8fd;
        border-radius: 5px;
}
</style>