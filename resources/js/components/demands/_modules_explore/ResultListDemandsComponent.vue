<template>
<div class="row mt-3" v-if="!isLoading">
    <div class="col-lg-3" v-show="showResultsFilters">
        <div class="card">
            <div class="card-body">
                <a @click="resetGlobalFilter" v-if="appliedForCategory || appliedForTime || appliedForAdministrative" class="btn btn-warning btn-sm">Resetare</a>
                <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Resetare</a>
            </div>
        </div>

        <CategoriesFilterComponent ref="categoriesFilter" @filter:category="filterCategories" @reset:categories_filter="resetCategoriesFilter" />
        <TimeFilterComponent ref="timeFilterComponent" @time:filter="filterByTime" @reset:time_filter="resetTimeFilter" />
        <AdministrativesComponent ref="administrativeFilterComponent" :administratives="administratives" @filter:administrative="filterAdministrative" @reset:administrative="resetFilterAdministrative" />
    </div>

    <div class="card col-sm-12" :class="{'col-lg-9' : showResultsFilters, 'col-lg-12' : !showResultsFilters}" v-if="search_made">
        <div class="card-header ">
            <h2 class="card-title ">{{total_found}} cereri disponibile <template v-if="location">în {{ location }}</template></h2>
            <div class="card-options">
                <button @click.prevent="showResultsFilters = !showResultsFilters" class="btn btn-default btn-sm"><i class="fa fa-sliders"></i> <span>{{ showResultsFilters ? 'Ascunde filtre' : 'Arată filtre' }}</span></button>
            </div>
        </div>
        <div class="card-body">
            <!-- <div class="grid-margin"> -->
                <b-container id="list-demands" :perPage="perPage">
                <div class=""  v-if="available_demands && available_demands.length > 0">

           

                        <div class="card mt-2 bordered" v-for="demand in getDemands" :key="demand.uuid">
                            <div class="card-header ">
                                <h3 class="card-title ">
                                    <i class="side-menu__icon ti-lock text-danger float-left mr-2" aria-hidden="true"></i> {{ demand.subject }}  
                                </h3>
                                <div class="card-options">
                                    
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <p class="small"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatElementTimeMethod(demand) }} <span>({{ calculateResponseTimeMethod(demand) }})</span></p>
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ demand.city }}</strong> </p>
                                        <div class="d-flex justify-content-start" v-if="demand.categories_names && demand.categories_names.length > 0">
                                            <div class="tags">
                                                <span class="tag" v-for="(category, index) in demand.categories_names" :key="category + '-' + index">{{ category }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-start mt-4">
                                            <p>
                                                <span class="badge badge-danger" style="font-weight:100;"><i class="side-menu__icon ti-lock"></i> Blocată</span>
                                            </p>
                                        </div>
                                    </div> <!-- col-md-8 -->
                                    <div class="col-lg-3">
                                        <div class="d-flex justify-content-center mb-2 mt-4" v-if="getCurrentUser">
                                            <a v-if="getCurrentUser.is_admin" :href="'/admin/demands/show/' + demand.uuid" class="btn btn-info float-right"><i class="fa fa-eye" aria-hidden="true"></i> Vezi detalii proiect</a>
                                            <a v-else :href="'/cereri/pro/detalii/' + demand.uuid" class="btn btn-info float-right"><i class="fa fa-eye" aria-hidden="true"></i> Vezi detalii proiect</a>
                                        </div>
                                        
                                    </div>
                                
                                </div>
                            
                            </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-center" v-if="total_rows > perPage">
                            <b-pagination @change="pageChange" v-model="currentPage" pills :total-rows="total_rows" :per-page="perPage" aria-controls="list-demands"></b-pagination>
                        </div>
                   

                    <!-- <div v-else class="d-flex justify-content-center mb-3">
                        <b-spinner label="Loading..."></b-spinner>
                    </div> -->
                        
            
                  
                        <!-- <p class="text-center m-5">Nu exista cereri inregistrate.</p> -->
                  
                </div>
                <div v-else>
                    <h5 class="text-center" style="font-size: 20px; font-weight: bold;margin-top: 20px;">Oups! Niciun rezultat pentru căutare. Încearcă o altă căutare sau listează toate cererile disponibile.</h5>
                    <img class="img-fluid" style="max-width: 200px;margin: 0 auto;display: block;" src="/assets/niciun-rezultat.png" />
                </div>
            <!-- </div> -->
            </b-container>
        </div>
    </div>
    <div class="card col-lg-12 col-sm-12" v-else>
        <div class="card-body">
            <h5 class="text-center" style="font-size: 20px; font-weight: bold;margin-top: 20px;">Caută cereri folosind căutarea personalizată sau listează toate cererile disponibile.</h5>
            <img class="img-fluid" style="max-width: 200px;margin: 0 auto;display: block;" src="/assets/cauta-cereri.png" />
        </div>
    </div>
</div>

<div class="row mt-3" v-else>
    <div class="card col-sm-12 col-lg-12 col-mg-12">
        <div class="card-body">
            <div class="text-center">
                <h4 class="text-center">Se caută cereri...</h4>
                <b-spinner variant="primary" label="Se incarca"></b-spinner>
            </div>
        </div>
    </div>
</div>
</template>


<script>
import { integer, numeric } from 'vee-validate/dist/rules';
import BounceLoader from 'vue-spinner/src/BounceLoader.vue';
// import cautaCereriImagine from '@/components/assets/cauta-cereri.png'
import { mapGetters } from 'vuex';

import CategoriesFilterComponent from "./CategoriesFilterComponent.vue";
import TimeFilterComponent from "./TimeFilterComponent.vue";
import AdministrativesComponent from "./AdministrativesComponent.vue";

export default {
    name: "ResultListDemandsComponent",

    components: {
        BounceLoader,
        CategoriesFilterComponent,
        TimeFilterComponent,
        AdministrativesComponent
    },

    data(){
        return {
            currentPage: 1,
            perPage: 20,
            rows: this.available_demands.length,
            // rows: null,
            used_demands: null,
            local_demands: [],

            categories: [],

            filtered_demands: null,

            appliedForCategory: false,
            appliedForTime: false,
            appliedForAdministrative: false,

            showResultsFilters: false
        }
    },

    computed: {
        getDemands: function(){
            // return this.local_demands.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
            // return this.available_demands.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
            // return this.available_demands;
            // if(this.local_demands.length > 0){
            //     return this.local_demands;
            // } else {
            //     }
            // if(this.filtered_demands){
            //     return this.filtered_demands;
            // } else {
            //     }
            return this.available_demands;
        },

        ...mapGetters('user', ['getCurrentUser'])
    },


    props: {
        available_demands: Array,
        isLoading: Boolean,
        location: String,
        total_rows: Number,
        administratives: Array,
        search_made: Boolean,
        total_found: Number
    },

    methods: {
        formatElementTimeMethod: function(element){
            // return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
            return moment(element.created_at).format("lll");
        },

        calculateResponseTimeMethod: function(element){
            let currentTime = moment().format('YYYY MM DD, HH:mm');
            let responseTime = moment(element.created_at).format("YYYY MM DD, HH:mm");
            var startTime = moment(responseTime, 'YYYY MM DD, HH:mm a');
            var endTime = moment(currentTime, 'YYYY MM DD, HH:mm a');
            var resultTime = startTime.diff(endTime, 'minutes');
            var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
            return asHuman;
            // return 'hehehe';
        },

        pageChange: function(page){
            // console.log('pagina', page);
            this.currentPage = page;
            this.$emit('page:changed', page);
            localStorage.currentPageDemandsExplore = page;
            // let newArr = this.available_demands.slice((page - 1) * this.perPage, this.perPage * page);
            // console.log('slice', newArr);
            // this.used_demands = newArr;
            // localStorage.used_demands = JSON.stringify(this.available_demands);
        },

        initializeCategories(){
            // this.loadingStatus = true;
            axios.get(`/api/categories/get/all`).then(response => {
                // console.log('categoriile sunt', response.data);
                this.categories = response.data.categories;
            }).finally(() => {
                // this.loadingStatus = false;
            });
        },


        // filters
        filterCategories: function(_categories){
            this.resetPage();
    
            this.$emit('filter:categories', _categories);
            // this.filtered_demands = this.available_demands.filter(item => {
            //    if(_categories.includes(item.id)){
            //        return item;
            //    } 
            // });
            this.appliedForCategory = true;
        },

        resetCategoriesFilter: function(){
            this.resetPage();
          
            this.$emit('reset:categories_filter');
            this.appliedForCategory = false;
        },

        filterByTime: function(_selected){
            this.resetPage();
      
            this.$emit('time:filter', _selected);
            this.appliedForTime = true;
        },

        resetTimeFilter: function(){
            this.resetPage();
            this.$emit('reset:time_filter');
            this.appliedForTime = false;
        },

        resetGlobalFilter: function(){
            this.resetPage();
            this.resetTimeFilter();
            this.resetCategoriesFilter();
            this.resetFilterAdministrative();
            this.$refs.categoriesFilter.resetFilter();
            this.$refs.timeFilterComponent.resetFilter();
            this.$refs.administrativeFilterComponent.resetFilter();
        },

        filterAdministrative: function(_selected){
            this.resetPage();
            this.$emit('filter:administrative', _selected);
            this.appliedForAdministrative = true;
        },

        resetFilterAdministrative: function(){
            this.resetPage();
            this.$emit('reset:administrative');
            this.appliedForAdministrative = false;
        },

        resetPage: function(){
            this.currentPage = 1;
            this.$emit('page:changed', 1);
            localStorage.removeItem('currentPageDemandsExplore');
        }
    },

    created(){
      


        if(localStorage.currentPageDemandsExplore){
            // let number_pages = Math.ceil(this.rows / this.perPage);
            if(localStorage.currentPageDemandsExplore > this.total_rows){
                this.currentPage = 1;
                // this.used_demands = [];
                // localStorage.removeItem('used_demands');
            } else {
                this.currentPage = localStorage.currentPageDemandsExplore;
                this.$emit('get:forPage', this.currentPage);
                // this.local_demands = this.available_demands.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
                
                // if(localStorage.used_demands){
                //     this.local_demands = JSON.parse(localStorage.used_demands);
                // }

            }
        }

        if(localStorage.filter_category_applied){
            this.appliedForCategory = true;
        }

        if(localStorage.filter_time_applied){
            this.appliedForTime = true;
        }

        if(localStorage.filter_administrative_applied){
            this.appliedForAdministrative = true;
        }
    }
}
</script>

<style scoped>
.bordered {
    border: 1px solid #0db6f7;
}

h3.card-title {
    width: 100%;
}
</style>