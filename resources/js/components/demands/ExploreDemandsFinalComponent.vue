<template>

<div class="my-8">
    <h3 class="lead" style="text-align: center;font-weight: bold;padding: 10px">Caută cereri în platformă</h3>
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(onSubmit)" class="row" :id="isLoading ? 'disabledInput' : ''">
            <div class="col-lg-4 col-sm-12">
                <div class="form-group" :class="{'disabled': isLoading}">
                    <label>Oraș / Cod poștal / Localitate</label>
                    <PlacesComponent ref="PlacesComponent" 
                    @location:selected="selectedLocation" 
                    @location:cached="cachedLocation" 
                    :cached="existing_location" 
                    :the_app_id="APP_ID"
                    :the_api_key="API_KEY"
                    />
                    <span class="small text-danger" v-if="validation_errors">
                        <template v-if="'location' in validation_errors">
                        {{ validation_errors['location'][0] }}
                        </template>
                    </span>
                </div>
            </div>
            

            <div class="col-lg-2 col-sm-12">
                <div class="form-group">
                    <validation-provider rules="required|integer" v-slot="{ errors, invalid }">
                        <label>Raza de acțiune în KM</label>
                        <input type="numeric" 
                        class="form-control" :class="{'is-invalid' : invalid, 'disabled': isLoading}" id="range" 
                        name="range" placeholder="Raza în kilometri" v-model="range"
                        @change="selectedRange"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <span class="small text-danger" v-if="validation_errors">
                        <template v-if="'range' in validation_errors">
                        {{ validation_errors['range'][0] }}
                        </template>
                    </span>
                </div>
            </div>

            <div class="col-lg-3 col-sm-12">
                <div class="form-group" :class="{'disabled': isLoading}">
                <label>Categorii proiecte</label>
                <CategoriesComponent ref="CategoriesComponent" @categories:selected="selectedCategories" @categories:cached="cachedCategories" :cached="the_selectedCategories" />
                    <span class="small text-danger" v-if="validation_errors">
                        <template v-if="'categories' in validation_errors">
                        {{ validation_errors['categories'][0] }}
                        </template>
                    </span>
                </div>
            </div>
 

            <div class="col-lg-3 col-sm-12 d-flex justify-content-center align-items-center">
                <button v-if="!isLoading" type="submit" id="search_projects" class="btn btn-md btn-primary btn-block" style="margin-top: 8px;"><i class="fa fa-search"></i> Caută</button>
                <button v-else type="button" class="btn btn-primary btn-loading btn-block" disabled="disabled" style="margin-top: 8px;">Se caută cereri</button>
            </div>
            <!-- <p class="mx-4"><a href="" @click.prevent="showAll = !showAll">
                <template v-if="!showAll">
                    <i class="fa fa-plus"></i> Arata alte filtre.
                </template>
                <template v-else>
                    <i class="fa fa-minus"></i> Ascunde alte filtre.
                </template>
                </a>
            </p> -->
        </form>
    </ValidationObserver>

    <div>
    <p class="text-center my-4" style="font-size:16px;">SAU</p>
    <form @submit.prevent="getAllDemands" class="row">
        <div class="col-lg-12 col-sm-12 d-flex justify-content-center">
            <button v-if="!isLoading" type="submit" id="search_projects" class="btn btn-md btn-info "><i class="fa fa-search"></i> Listează toate cererile disponibile</button>
            <button v-else type="button" class="btn btn-primary btn-loading" disabled="disabled">Se preiau cererile</button>
        </div>
    </form>
    </div>

    <p v-if="last_search" class="last_search my-4">Ultima căutare: <strong>{{ last_search }}</strong> <button class="btn btn-default btn-sm" @click.prevent="resetLastSearch"><i class="fa fa-close"></i></button></p>

    <!-- <ResultsOnMapComponent  
    :range="parseInt(range) * 1000" :demands="getDemandsTotal"  
    /> -->
    <!-- <pre>{{ getDemandsTotal }}</pre> -->

    <ResultListDemandsComponent
        ref="resultList" 
        :available_demands="getDemandsTotal" 
        :total_rows="getTotalRows" 
        :isLoading="isLoading" 
        :search_made="search_made"
        :location="searchedIn" 
        @page:changed="pageChanged" 
        @get:forPage="getForPage" 
        @filter:categories="filterCategories" 
        @reset:categories_filter="resetCategoriesFilter" 
        @time:filter="filterByTime"
        @reset:time_filter="resetTimeFilter"
        :total_found="getTotalRows"
    />

</div>

</template>

<script>
import PlacesComponent from './_modules/PlacesComponentSearch.vue';
import CategoriesComponent from './_modules/CategoriesComponentSearch.vue';
import ResultListDemandsComponent from "./_modules_explore/ResultListDemandsComponent.vue";
// import ResultsOnMapComponent from "./_modules_explore/ResultsOnMapComponent.vue";

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min} from 'vee-validate/dist/rules';

import { mapGetters } from 'vuex';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


extend('integer', {
  ...integer,
  message: 'Sunt acceptate doar valori numerice intregi.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minima acceptata este 20.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('length', {
  ...length,
  message: 'Lungimea acceptata este {length} caractere.'
});

export default {
    name: "ExploreDemandsFinalComponent",

    data(){
        return {
            range: 10, // 10 km
            // cached_range: null,
            // cachedthe_location: localStorage.location || {},
            the_location: {},
            existing_location: {},
            // cached_categories: localStorage.selectedCategories || [],
            the_selectedCategories: [],

            demands: [],
            chunk_demands: [],
            // cached_results: [],

            isLoading: false,

            validation_errors: null,
            searchedIn: null,

            filtered_demands: null,
            filtered_demands_by_category: null,
            filtered_demands_by_time: null,
            page_is: 1,

            perPage: 20,

            administratives: null,
            filtered_demands_by_administrative: null,

            search_made: false,
            searched_in_all: false,
            last_search: null,

            showAll: false,

            APP_ID: '',
            API_KEY: ''
        }
    },

    props: {
        app_id: String,
        api_key: String
    },

    computed: {
        getDisabled(){
            return this.isLoading ? true : false;
        },

        getDemandsTotal: function(){
            if(this.filtered_demands){
                return this.filtered_demands.slice((this.page_is - 1) * this.perPage, this.page_is * this.perPage);
            } else {
                return this.demands.slice((this.page_is - 1) * this.perPage, this.page_is * this.perPage);
            }
        },

        getTotalRows: function(){
            if(this.filtered_demands){
                return this.filtered_demands.length;
            } else {
                return this.demands.length;
            }
        },

        theSearchCoord: function(){
            return this.the_location ? this.the_location.latlng : {lat: 44.4361, lng: 26.1027};
        },

        getLng: function(){
            return this.the_location ? this.the_location.latlng.lng : null;
        },

        ...mapGetters('user', ['getCurrentUser'])

       
    },

    components: {
        PlacesComponent,
        CategoriesComponent,
        ValidationProvider,
        ValidationObserver,
        ResultListDemandsComponent,
        // ResultsOnMapComponent
    },

    methods: {

        formatElementTimeMethod: function(element){
            // return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
            return moment(element.created_at).format("lll");
        },

        cachedLocation(selectedData){
            // this.cachedthe_location = selectedData;
            // console.log('selectedData este', selectedData);
            // selectedData.country.data.query = this.the_location.value;
            selectedData.country.label = this.the_location.value;
            // localStorage.location.label = this.the_location.value;
            localStorage.location = JSON.stringify(selectedData);
        },

        selectedLocation: function(_incLocation){
            // console.log('_incLocation', _incLocation);
            this.the_location = _incLocation;
            // console.log('the_location este', this.the_location);
            // localStorage.location = _incLocation;
        },

        cachedCategories(selectedData){
            // this.cached_categories = selectedData;
            localStorage.selectedCategories = JSON.stringify(selectedData);
        },

        selectedCategories: function(_categories){
            // console.log('selectedCategories', _categories);
            // console.log('selectedCategories_ids', _categories.map(item => item.id));
            this.the_selectedCategories = _categories;
        },

        selectedRange: function(){
            localStorage.range = this.range;
        },

        pageChanged: function(page){
            this.page_is = page;
            // this.chunk_demands = this.demands.slice((page - 1) * 2, page * 2);
            // localStorage.used_demands = JSON.stringify(this.chunk_demands);
        },

        getForPage: function(page){
            this.page_is = page;
            // this.chunk_demands = this.demands.slice((page - 1) * 2, page * 2);
            // localStorage.used_demands = JSON.stringify(this.chunk_demands);
        },

        resetSearch: function(){
            this.range = 10;
            this.the_location = {};
            this.existing_location = {};
            // cached_categories: localStorage.selectedCategories || [],
            this.the_selectedCategories = [];

            this.demands = [];
            this.chunk_demands = [];
            this.validation_errors = null;


            this.$refs.PlacesComponent.resetAll();
            // this.$refs.PlacesComponent.reset();
            this.$refs.CategoriesComponent.resetAll();
            // this.$refs.CategoriesComponent.reset();

            this.$refs.observer.reset();
        },

        resetCached: function(full = true){
            if(full){
                localStorage.removeItem('location');
                localStorage.removeItem('selectedCategories');
                localStorage.removeItem('range');
            }

            localStorage.removeItem('currentPageDemandsExplore');
            localStorage.removeItem('demands');
            localStorage.removeItem('filtered_demands');
            localStorage.removeItem('filtered_demands_by_time');
            localStorage.removeItem('filtered_demands_by_category');
            localStorage.removeItem('searchedIn');
            localStorage.removeItem('searched_in_all');

        },

        resetLastSearch: function(){
            this.last_search = null;
            this.search_made = false;
            this.searched_in_all = false;

            if(localStorage.search_made){
                localStorage.removeItem('search_made');
            }

            if(localStorage.searched_in_all){
                localStorage.removeItem('searched_in_all');
            }

            this.resetSearch();
            this.resetCached();
        },


        onSubmit: function(){
            // console.log('s-a tras');

            // this.coords = this.the_location.latlng;


            this.isLoading = true;
            this.search_made = true;
            localStorage.search_made = true;
            this.validation_errors = null;
            this.searchedIn = this.the_location.value;
            let self = this;

            this.resetCached(false);

            this.resetCategoriesFilter();
            this.$refs.resultList.$refs.categoriesFilter.resetFilter();
            this.$refs.resultList.$refs.timeFilterComponent.resetFilter();
            localStorage.removeItem('currentPageDemandsExplore');

            // let total_length = this.the_selectedCategories.length;
            this.last_search = this.searchedIn + ' (' + this.range + ' KM în jur). Categorii: ' + this.the_selectedCategories.map((item, i) => {
                return item.name;
                // if(i + 1 === total_length){
                //     return item.name;
                // } else {
                //     return item.name + ' ';
                // }
            });
            

            // create the formData or the Object
            let params = {
                'categories': this.the_selectedCategories.map(item => item.id),
                'location': this.the_location,
                'range': this.range
            }

            // call axios w/ post action
            axios.post(`/api/demands/explore`, params).then(async response => {
                if(response.data.demands){
                    let temp_demands = Object.values(response.data.demands);
                    
                    let sorted_demands = temp_demands.sort(function(a, b) {
                        return moment(b.created_at) - moment(a.created_at);
                    });

                    this.demands = sorted_demands;
                    // this.demands = Object.values(response.data.demands);
                    // this.chunk_demands = this.demands.slice(0, 2);
                    localStorage.demands = JSON.stringify(sorted_demands);
                    localStorage.searchedIn = this.searchedIn;


                } else if (response.data.validation_errors){
          
                    this.validation_errors = response.data.validation_errors;
       
                }
            }).finally(() => {
                this.isLoading = false;
            });

            // salveaza cautarea - tip cache
            // localStorage.location = JSON.stringify(this.the_location);
            localStorage.range = this.range;
            // localStorage.selectedCategories = JSON.stringify(this.the_selectedCategories);
            // this.cached_categories = this.the_selectedCategories;
            // this.cachedthe_location = this.the_location;
            // this.cached_range = this.range;

            // salveaza lista de rezultate - tip cache


            this.resetSearch();
            
        },

        getAllDemands: function(){
            // console.log('getAllDemands');

            // this.coords = {lat: 44.4361, lng: 26.1027};

            this.resetLastSearch();

            this.resetCategoriesFilter();

            // this.resetCached(true);

            this.$refs.resultList.$refs.categoriesFilter.resetFilter();
            this.$refs.resultList.$refs.timeFilterComponent.resetFilter();
            localStorage.removeItem('currentPageDemandsExplore');


            this.isLoading = true;
            this.searchedIn = 'România';

            this.searched_in_all = true;
            this.search_made = true;
            localStorage.search_made = true;
            
        
            


            axios.get(`/api/demands/explore/all`).then(async response => {
                if(response.data.demands){
                    // console.log('aici este get all', response.data);
                    this.demands = Object.values(response.data.demands);
                    // console.log('DECI, aici este demands FINAL', response.data);
                    // this.demands = response.data.demands;
                    // this.chunk_demands = this.demands.slice(0, 2);
                    localStorage.demands = JSON.stringify(Object.values(response.data.demands));
                    localStorage.searchedIn = this.searchedIn;
                    localStorage.searched_in_all = true;
                    this.last_search = 'România; Toate categoriile.';

                }
            }).finally(() => {
                this.isLoading = false;
            });

        },


        filterCategories: function(_categories){

            let working_demands = [];
            

            this.resetAllFilters();

            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
                working_demands = this.filtered_demands;
            }
            
            if(!localStorage.filter_time_selected){
                working_demands = this.demands;
            }



            this.applyFilterCategories(_categories, working_demands);
        },

        applyFilterCategories: function(_categories, working_demands){


            // console.log('working_demands este', working_demands);

            this.filtered_demands_by_category = working_demands.filter(item => {

                const found = _categories.some(r=> item.categories_ids.includes(r));
                
                if(found){
                    return item;
                }
            });

            // this.filtered_demands = filtered.slice(0, 2);
            this.filtered_demands = this.filtered_demands_by_category;

            localStorage.filtered_demands = JSON.stringify(this.filtered_demands);
            localStorage.filtered_demands_by_category = JSON.stringify(this.filtered_demands_by_category);
        },

        resetCategoriesFilter: function(){

            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
            localStorage.removeItem('filtered_demands_by_category');

            // reaplicare filtru time
            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                // this.filterByTime(_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
            }

     
        },


        filterByTime: function(_selected){

            // working_demands = this.by_time_ifs();
            let working_demands = [];
            this.resetAllFilters();

            if(localStorage.filter_category_selected){
                let _categories = JSON.parse(localStorage.filter_category_selected);
                let existing_filter = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterCategories(_categories, existing_filter);
                working_demands = this.filtered_demands;
            }
            

            if(!localStorage.filter_category_selected){
                working_demands = this.demands;
            }

            this.applyFilterByTime(_selected, working_demands);

        },

        applyFilterByTime: function(_selected, working_demands){

            // this.resetAllFilters();

            if(_selected == 'all'){
                this.filtered_demands_by_time = null;
                this.filtered_demands = null;
            } else if(_selected == 'today') {
                const today = moment().endOf('day');
                const yesterday = moment().add(-1, 'day').endOf('day');


                this.filtered_demands = working_demands.filter(item => {
                    let item_time = moment(item.created_at);
          

                    if(item_time.isAfter(yesterday) && item_time.isSameOrBefore(today)){
                        return item;
                    }
                });

                this.filtered_demands_by_time = this.filtered_demands;
                localStorage.filtered_demands_by_time = JSON.stringify(this.filtered_demands_by_time);

            }
            else {
                let _days = parseInt(_selected);

                this.filtered_demands = working_demands.filter(item => {
                    let item_time = moment(item.created_at);
                    let the_period = moment().day(-_days);
                    // console.log('current - 5', the_period);

                    if(item_time.isAfter(the_period)){
                        return item;
                    }
                });

                this.filtered_demands_by_time = this.filtered_demands;
                localStorage.filtered_demands_by_time = JSON.stringify(this.filtered_demands_by_time);

            }

        },

        resetTimeFilter: function(){

            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
            localStorage.removeItem('filtered_demands_by_time');

            // reaplicare filtru time
            if(localStorage.filter_category_selected){
                let _categories = JSON.parse(localStorage.filter_category_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterCategories(_categories, the_demands);
            }
        },

        

        resetAllFilters: function(){
            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
        
            localStorage.removeItem('filtered_demands_by_category');
            localStorage.removeItem('filtered_demands_by_time');
        }

        

    },

    created(){


        this.APP_ID = this.app_id;
        this.API_KEY = this.api_key;


        if (localStorage.location) {
            this.existing_location = JSON.parse(localStorage.location);
            this.the_location = this.existing_location.country.data;
        }

        // if(this.the_location){
        //     this.coords = this.the_location.latlng;
        // } else {
        //     this.coords = {lat: 44.4361, lng: 26.1027};
        // }


        if (localStorage.searchedIn) {
            this.searchedIn = localStorage.searchedIn;
        }

        let last_range;
        if (localStorage.range) {
            last_range = localStorage.range;

            this.last_search += ' (' + last_range + ' KM în jur).';
        }

        if (localStorage.selectedCategories) {
            this.the_selectedCategories = JSON.parse(localStorage.selectedCategories);

            this.last_search += ' Categorii: ' + this.the_selectedCategories.map((item, i) => {
                return item.name;
            });

        }

        if(localStorage.search_made){
            this.search_made = true;
            this.last_search = '';

            if (this.searchedIn) {
                this.last_search += this.searchedIn;
            }

            if (last_range) {
                this.last_search += ' (' + last_range + ' KM în jur).';
            }

            if (this.selectedCategories) {
                // let selectedCategories = JSON.parse(selectedCategories);
                this.last_search += ' Categorii: ' + this.the_selectedCategories.map((item, i) => {
                    return item.name;
                });

            }

        }

        if(localStorage.searched_in_all){
            this.last_search = 'România; Toate categoriile.'
        }

        

        if (localStorage.demands) {
            this.demands = JSON.parse(localStorage.demands);
            // this.chunk_demands = this.demands.slice(0, 2);
        }

        if (localStorage.filtered_demands) {
            this.filtered_demands = JSON.parse(localStorage.filtered_demands);
            // this.chunk_demands = this.demands.slice(0, 2);
        }
        if (localStorage.filtered_demands_by_category) {
            this.filtered_demands_by_category = JSON.parse(localStorage.filtered_demands_by_category);
            this.filtered_demands = this.filtered_demands_by_category;
            // this.chunk_demands = this.demands.slice(0, 2);
        }
        if (localStorage.filtered_demands_by_time) {
            this.filtered_demands_by_time = JSON.parse(localStorage.filtered_demands_by_time);
            this.filtered_demands = this.filtered_demands_by_time;
            // this.chunk_demands = this.demands.slice(0, 2);
        }


        // console.log('localStorage este', localStorage);
    }
}
</script>

<style scoped>
.text-off {
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    color: #afafaf;
}

.last_search {
    font-size: 16px!important;
}
</style>