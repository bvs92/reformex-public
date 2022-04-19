<template>
<div class="">
    <div class="row my-4 announcement" v-for="ad in getAds" :key="ad.id">
        <div class="col-lg-12 py-4">
            <div class="pl-2">
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <span v-if="ad.status == 1" class="badge badge-success mr-1">Activ</span>
                        <span v-else class="badge badge-danger mr-1">Inactiv</span> <span class="badge badge-warning mr-1" v-if="ad.processing == 1">În analiză</span>
                    </div>
                    <div class="col-lg-12 my-3" v-if="ad.rejected == 1">
                        <span class="badge badge-danger mr-1">Refuzat</span> Verifică informațiile și retrimite pentru validare.
                    </div>
                </div>
                <p>Firmă: {{ ad.name }}</p>
                <p>Dată creare: {{ formatElementTimeMethod(ad.created_at) }} <span v-if="checkValidDate(ad.ends_at)">, Dată expirare: {{ formatElementTimeMethod(ad.ends_at) }}</span></p>
                <p></p>
                <p>E-mail: {{ ad.email }}</p>
                <div v-if="ad.categories">
                    Categorii: <span class="badge badge-default mr-1" v-for="category in ad.categories" :key="category.id">{{ category.name }}</span>
                </div>
            </div>
            <br>
            <div class="mt-2">
                <a :href="'/publicitate/anunturi-recomandate/detalii/' + ad.uuid" class="btn btn-info">Vezi detalii</a>
            </div>
        </div>
    </div>
       
    
</div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    name: "ListPersonalAdsRecommendComponent",

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
        ...mapGetters('ads_recommend', ['getAds', 'getTotalAds']),

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

        loadAds: async function(){
            this.isBusy = true;
            await this.$store.dispatch('ads_recommend/allAds').finally(() => {
                this.isBusy = false;
            });
        }
    },

    created: async function(){
        await this.loadAds();
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