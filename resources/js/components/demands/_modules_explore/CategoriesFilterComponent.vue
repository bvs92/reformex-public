<template>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Categorie</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div v-if="!getLoadingStatus">
        <b-form-group v-if="getCompletedCategories.length > 0"
            label="Filtrare în funcție de categorie"
            v-slot="{ ariaDescribedby }"
            >
            <b-form-checkbox-group
                v-model="selected"
                :options="getCompletedCategories"
                :aria-describedby="ariaDescribedby"
                name="flavour-2a"
                stacked
            ></b-form-checkbox-group>
        </b-form-group>
        <a @click="filterByCategory" v-if="selected.length > 0" class="btn btn-primary btn-sm">Aplică</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Aplică</a>
        <a @click="resetFilter" v-if="applied" class="btn btn-warning btn-sm">Resetare</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Resetare</a>
        </div>
        <div v-else class="d-flex justify-content-center mb-3">
            <b-spinner variant="info" small label="Se încarcă filtrarea"></b-spinner>
        </div>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: "CategoriesFilterComponent",

    data() {
      return {
        loadingStatus: false,
        selected: [], // Must be an array reference!
        options: [
        //   { text: 'Red', value: 'red' },
        //   { text: 'Green', value: 'green' },
        //   { text: 'Yellow (disabled)', value: 'yellow', disabled: true },
        //   { text: 'Blue', value: 'blue' }
        ],
        applied: false
      }
    },

    props: {
        incCategories: Array
    },

    computed: {
        ...mapGetters('categories_explore', [
        'getCategories',
        'getLoadingStatus'
        // ...
        ]),

        getCompletedCategories: function(){
            return this.getCategories.map(item => {
                    let newItem = {
                        text: item.name,
                        value: item.id,
                        disabled: false
                    }
                    return newItem;
                });
        }
    },

    methods: {
        async initializeCategories(){ // externalizare?
            this.loadingStatus = true;
            let self = this;
            await axios.get(`/api/categories/get/all`).then(async response => {
                // console.log('categoriile din filtru sunt sunt', response.data);
                // console.log('categoriile curente', self.options);
                self.options = await response.data.categories.map(item => {
                    let newItem = {
                        text: item.name,
                        value: item.id,
                        disabled: false
                    }
                    return newItem;
                });
                
                // console.log('categoriile din filtru sunt sunt', self.options);
            }).finally(() => {
                this.loadingStatus = false;
            });
        },

        filterByCategory: function(){
            // console.log('filterByCategory...');
            // console.log('categorii selected', this.selected);
            this.$emit('filter:category', this.selected);
            this.applied = true;

            localStorage.filter_category_selected = JSON.stringify(this.selected);
            localStorage.filter_category_applied = this.applied;
        },

        resetFilter: function(){
            // console.log('resetFilter');
            this.selected = [];
            this.applied = false;
            this.$emit('reset:categories_filter');

            localStorage.removeItem('filter_category_selected');
            localStorage.removeItem('filter_category_applied');
        }
    },

    created(){


        // initialize categories.
        // this.initializeCategories();

        if(localStorage.filter_category_selected){
            this.selected = JSON.parse(localStorage.filter_category_selected);
        }

        if(localStorage.filter_category_applied){
            this.applied = localStorage.filter_category_applied;
        }
    }
}
</script>