<template>

<div class="my-8">
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(onSubmit)" class="d-flex justify-content-center" :id="isLoading ? 'disabledInput' : ''">
            <div class="col-lg-4">
                <div class="form-group" :class="{'disabled': isLoading}">
                    <PlacesComponent ref="PlacesComponent" @location:selected="selectedLocation" @location:cached="cachedLocation" :cached="existing_location" />
                    <span class="small text-danger" v-if="validation_errors">
                        <template v-if="'location' in validation_errors">
                        {{ validation_errors['location'][0] }}
                        </template>
                    </span>
                </div>
            </div>
            

            <div class="col-lg-2">
                <div class="form-group">
                    <validation-provider rules="required|integer" v-slot="{ errors, invalid }">
                        <input type="numeric" 
                        class="form-control" :class="{'is-invalid' : invalid, 'disabled': isLoading}" id="range" 
                        name="range" placeholder="Raza in kilometri" v-model="range"
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

            <div class="col-lg-3">
                <div class="form-group" :class="{'disabled': isLoading}">
                <CategoriesComponent ref="CategoriesComponent" @categories:selected="selectedCategories" @categories:cached="cachedCategories" :cached="the_selectedCategories" />
                    <span class="small text-danger" v-if="validation_errors">
                        <template v-if="'categories' in validation_errors">
                        {{ validation_errors['categories'][0] }}
                        </template>
                    </span>
                </div>
            </div>
 

            <div class="col-lg-2">
                <button v-if="!isLoading" type="submit" id="search_projects" class="btn btn-md btn-primary "><i class="fa fa-search"></i> Exploreaza cereri</button>
                <button v-else type="button" class="btn btn-primary btn-loading btn-block" disabled="disabled">Cautam cereri</button>
            </div>
        </form>
    </ValidationObserver>

    <p class="text-off text-center">sau</p>

    <form @submit.prevent="getAllDemands" class="d-flex justify-content-center">
        <div class="col-lg-2">
            <button v-if="!isLoading" type="submit" id="search_projects" class="btn btn-md btn-primary "><i class="fa fa-search"></i> Toate cererile disponibile</button>
            <button v-else type="button" class="btn btn-primary btn-loading btn-block" disabled="disabled">Preluam cererile</button>
        </div>
    </form>


    <ResultListDemandsComponent
        ref="resultList" 
        :available_demands="getDemandsTotal" 
        :total_rows="getTotalRows" 
        :isLoading="isLoading" 
        :search_made="search_made"
        :location="searchedIn" 
        :administratives="getAdministratives"
        @page:changed="pageChanged" 
        @get:forPage="getForPage" 
        @filter:categories="filterCategories" 
        @reset:categories_filter="resetCategoriesFilter" 
        @time:filter="filterByTime"
        @reset:time_filter="resetTimeFilter"
        @filter:administrative="filterAdministrative"
        @reset:administrative="resetFilterAdministrative"
    />

</div>

</template>

<script>
import PlacesComponent from './_modules/PlacesComponent.vue';
import CategoriesComponent from './_modules/CategoriesComponent.vue';
import ResultListDemandsComponent from "./_modules_explore/ResultListDemandsComponent.vue";

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min} from 'vee-validate/dist/rules';

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
    name: "ExploreDemandsComponent",

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

            perPage: 5,

            administratives: null,
            filtered_demands_by_administrative: null,

            search_made: false
        }
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

        getAdministratives: function(){
            if(!this.administratives){
                return [];
            }

            let admns = this.administratives.filter(item => {
                if(item !== null || item != ''){
                    return item;
                }
            });

            return [...new Set(admns)]; // get unique values
        }
    },

    components: {
        PlacesComponent,
        CategoriesComponent,
        ValidationProvider,
        ValidationObserver,
        ResultListDemandsComponent
    },

    methods: {
        cachedLocation(selectedData){
            // this.cachedthe_location = selectedData;
            console.log('selectedData este', selectedData);
            // selectedData.country.data.query = this.the_location.value;
            selectedData.country.label = this.the_location.value;
            // localStorage.location.label = this.the_location.value;
            localStorage.location = JSON.stringify(selectedData);
        },

        selectedLocation: function(_incLocation){
            // console.log('_incLocation', _incLocation);
            this.the_location = _incLocation;
            console.log('the_location este', this.the_location);
            // localStorage.location = _incLocation;
        },

        cachedCategories(selectedData){
            // this.cached_categories = selectedData;
            localStorage.selectedCategories = JSON.stringify(selectedData);
        },

        selectedCategories: function(_categories){
            console.log('selectedCategories', _categories);
            console.log('selectedCategories_ids', _categories.map(item => item.id));
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

            // You should call it on the next frame
            // requestAnimationFrame(() => {
            //     this.$refs.observer.reset();
            // });
            // console.log(this.$refs.PlacesComponent);
            this.$refs.PlacesComponent.resetAll();
            this.$refs.CategoriesComponent.resetAll();
        },

        resetCached: function(full = true){
            if(full){
                localStorage.removeItem('location');
                localStorage.removeItem('selectedCategories');
                localStorage.removeItem('range');
            }

            localStorage.removeItem('currentPageDemandsExplore');
            localStorage.removeItem('demands');
            localStorage.removeItem('demands_administratives');
            localStorage.removeItem('filtered_demands');
            localStorage.removeItem('filtered_demands_by_time');
            localStorage.removeItem('filtered_demands_by_category');
            localStorage.removeItem('searchedIn');
        },

        // resetCachedSecond: function(){
        //     localStorage.removeItem('currentPageDemandsExplore');
        //     localStorage.removeItem('demands');
        //     localStorage.removeItem('filtered_demands');
        //     localStorage.removeItem('searchedIn');
        // },


        onSubmit: function(){
            console.log('s-a tras');
            this.isLoading = true;
            this.search_made = true;
            localStorage.search_made = true;
            this.validation_errors = null;
            this.searchedIn = this.the_location.value;

            this.resetCached(false);

            this.resetCategoriesFilter();
            this.$refs.resultList.$refs.categoriesFilter.resetFilter();
            this.$refs.resultList.$refs.timeFilterComponent.resetFilter();
            this.$refs.resultList.$refs.administrativeFilterComponent.resetFilter();
            localStorage.removeItem('currentPageDemandsExplore');
            

            // create the formData or the Object
            let params = {
                'categories': this.the_selectedCategories.map(item => item.id),
                'location': this.the_location,
                'range': this.range
            }

            // call axios w/ post action
            axios.post(`/api/demands/explore`, params).then(async response => {
                if(response.data.demands){
                    console.log(response.data);
                    this.demands = Object.values(response.data.demands);
                    // this.chunk_demands = this.demands.slice(0, 2);
                    localStorage.demands = JSON.stringify(Object.values(response.data.demands));
                    localStorage.searchedIn = this.searchedIn;

                    this.administratives = await this.demands.map(item => {
                        if(item.administrative != null || item.administrative != '' || item.administrative != 'null'){
                            return item.administrative;
                        }
                    });
                    localStorage.demands_administratives = JSON.stringify(this.administratives);

                } else if (response.data.validation_errors){
                    console.log('erori de validare', response.data.validation_errors);
                    this.validation_errors = response.data.validation_errors;
                    console.log('erori de validare', response.data.validation_errors['location']);
                    console.log('erori de validare', response.data.validation_errors['location'][0]);
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
            
        },

        getAllDemands: function(){
            console.log('getAllDemands');
            this.isLoading = true;
            this.searchedIn = 'Romania';

            this.search_made = true;
            localStorage.search_made = true;
            this.resetSearch();
            this.resetCached();


            this.resetCategoriesFilter();
        
            this.$refs.resultList.$refs.categoriesFilter.resetFilter();
            this.$refs.resultList.$refs.timeFilterComponent.resetFilter();
            this.$refs.resultList.$refs.administrativeFilterComponent.resetFilter();
            localStorage.removeItem('currentPageDemandsExplore');

            
            // localStorage.removeItem('demands');
            // localStorage.removeItem('currentPageDemandsExplore');

            axios.get(`/api/demands/explore/all`).then(async response => {
                if(response.data.demands){
                    console.log(response.data);
                    this.demands = Object.values(response.data.demands);
                    // this.chunk_demands = this.demands.slice(0, 2);
                    localStorage.demands = JSON.stringify(Object.values(response.data.demands));
                    localStorage.searchedIn = this.searchedIn;

                    this.administratives = await this.demands.map(item => {
                        if(item.administrative != null || item.administrative != '' || item.administrative != 'null'){
                            return item.administrative;
                        }
                    });
                    localStorage.demands_administratives = JSON.stringify(this.administratives);
                }
            }).finally(() => {
                this.isLoading = false;
            });

        },


        filterCategories: function(_categories){
            // this.chunk_demands = this.demands.filter(item => {
            //    if(_categories.includes(item.id)){
            //        return item;
            //    } 
            // });

            console.log('_categories este ', _categories);
            console.log('inainte de filter - demands', this.demands);
            console.log('inainte de filter - filtered_demands', this.filtered_demands);

            let working_demands = [];
            

            this.resetAllFilters();

            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
                working_demands = this.filtered_demands;
            }
            
            if(localStorage.filter_administrative_selected){
                let _administratives = JSON.parse(localStorage.filter_administrative_selected);
                let existing_administrative = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterAdministrative(_administratives, existing_administrative);
                working_demands = this.filtered_demands;
            }

            if(!localStorage.filter_administrative_selected && !localStorage.filter_time_selected){
                working_demands = this.demands;
            }



            this.applyFilterCategories(_categories, working_demands);
        },

        applyFilterCategories: function(_categories, working_demands){


            console.log('working_demands este', working_demands);

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
            // if(this.filtered_demands_by_time){
            //     this.filtered_demands = this.filtered_demands_by_time;
            // } else {
            //     }

            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
            this.filtered_demands_by_administrative = null;
            localStorage.removeItem('filtered_demands_by_category');

            // reaplicare filtru time
            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                // this.filterByTime(_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
            }

            if(localStorage.filter_administrative_selected){
                let _administratives = JSON.parse(localStorage.filter_administrative_selected);
                // this.filterAdministrative(_administratives);
                let existing_administrative = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterAdministrative(_administratives, existing_administrative);
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
            
            if(localStorage.filter_administrative_selected){
                let _administratives = JSON.parse(localStorage.filter_administrative_selected);
                let existing_filter = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterAdministrative(_administratives, existing_filter);
                working_demands = this.filtered_demands;
            }

            if(!localStorage.filter_administrative_selected && !localStorage.filter_category_selected){
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
            this.filtered_demands_by_administrative = null;
            localStorage.removeItem('filtered_demands_by_time');

            // reaplicare filtru time
            if(localStorage.filter_category_selected){
                let _categories = JSON.parse(localStorage.filter_category_selected);
                // this.filterCategories(_categories);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterCategories(_categories, the_demands);
            }

            if(localStorage.filter_administrative_selected){
                let _administratives = JSON.parse(localStorage.filter_administrative_selected);
                // this.filterAdministrative(_administratives);
                let existing_administrative = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterAdministrative(_administratives, existing_administrative);
            }

        },

        filterAdministrative: function(_administratives){
            console.log('selected este', _administratives);
            // this.resetAllFilters();
            let working_demands = [];

            this.resetAllFilters();

            if(localStorage.filter_category_selected){
                let _categories = JSON.parse(localStorage.filter_category_selected);
                let existing_filter = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterCategories(_categories, existing_filter);
                working_demands = this.filtered_demands;
            }
            
            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
                working_demands = this.filtered_demands;
            }

            if(!localStorage.filter_time_selected && !localStorage.filter_category_selected){
                working_demands = this.demands;
            }

            
            this.applyFilterAdministrative(_administratives, working_demands);

        },

        applyFilterAdministrative: function(_administratives, working_demands){
            this.filtered_demands_by_administrative = working_demands.filter(item => {

                if(_administratives.includes(item.administrative)){
                    return item;
                }
            });

            // this.filtered_demands = filtered.slice(0, 2);
            this.filtered_demands = this.filtered_demands_by_administrative;

            localStorage.filtered_demands = JSON.stringify(this.filtered_demands);
            localStorage.filtered_demands_by_administrative = JSON.stringify(this.filtered_demands_by_administrative);
        },

        resetFilterAdministrative: function(){
            console.log('resetam');
            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
            this.filtered_demands_by_administrative = null;
            localStorage.removeItem('filtered_demands_by_administrative');

            // reaplicare filtru time
            if(localStorage.filter_category_selected){
                let _categories = JSON.parse(localStorage.filter_category_selected);
                // this.filterCategories(_categories);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterCategories(_categories, the_demands);
            } 
            
            if(localStorage.filter_time_selected){
                let _period = JSON.parse(localStorage.filter_time_selected);
                // this.filterByTime(_selected);
                let the_demands = this.filtered_demands ? this.filtered_demands : this.demands;
                this.applyFilterByTime(_period, the_demands);
            }

        },

        resetAllFilters: function(){
            this.filtered_demands = null;
            this.filtered_demands_by_time = null;
            this.filtered_demands_by_category = null;
            this.filtered_demands_by_administrative = null;
            localStorage.removeItem('filtered_demands_by_administrative');
            localStorage.removeItem('filtered_demands_by_category');
            localStorage.removeItem('filtered_demands_by_time');
        }

        

    },

    created(){
        if (localStorage.location) {
            this.existing_location = JSON.parse(localStorage.location);
            this.the_location = this.existing_location.country.data;
        }

        if(localStorage.search_made){
            this.search_made = true;
        }

        if (localStorage.range) {
            this.range = localStorage.range;
        }

        if (localStorage.searchedIn) {
            this.searchedIn = localStorage.searchedIn;
        }

        if (localStorage.selectedCategories) {
            this.the_selectedCategories = JSON.parse(localStorage.selectedCategories);
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


        console.log('localStorage este', localStorage);
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
</style>