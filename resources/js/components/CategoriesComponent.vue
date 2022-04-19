<template>
  <div>

      <!-- <MultiSelectComponent :incOptions="categories" :type="'categories'" @categories:selected="categoriesSelected" /> -->
    <button class="btn btn-primary btn-sm" @click.prevent="openModal">Alegerea categorii</button>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Alege categorii de lucru">
        <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
        </div>
        <template v-else>
            <div class="tags" v-if="categories && categories.length > 0">
                <template v-for="(category, index) in categories">
                    <span v-if="category.selected == false" class="tag px-4 py-2 m-2"  :key="category.name + '-' + index" @click="selectingCategories(category)">{{ category.name }}</span>
                    <span v-else class="tag tag-azure px-4 py-2 m-2"  :key="category.name + '-' + index" @click="deselectingCategories(category)">{{ category.name }}</span>
                </template>
            </div>

            <div class="row mt-6">
                <div class="col-lg-12">
                    <button class="btn btn-success btn-block" @click.prevent="saveCategories">Salvează</button>
                </div>
            </div>
        </template>
        

    </b-modal>

  <!-- <pre class="language-json"><code>{{ categories  }}</code></pre> -->

  </div>
</template>

<script>
// import MultiSelectComponent from './MultiSelectComponent.vue';
// import { ValidationProvider, extend, } from 'vee-validate';
// import { required } from 'vee-validate/dist/rules';

// extend('required', {
//   ...required,
//   message: 'Aceasta informatie este obligatorie.'
// });


import { mapGetters } from 'vuex'

  export default {
    name: "CategoriesComponent",
    // OR register locally

    components: {
        // MultiSelectComponent
    },

    data () {
      return {
        value: null,
        loadingStatus: false,
        categories: [],
        selected_categories: [],
        modalShow: false
      }
    },

    computed: {
    ...mapGetters('pro_module', [
      'getSelectedCategories',
      // ...
    ])
  },

    props: {},


    methods: {
        initializeCategories(){
            this.loadingStatus = true;
            axios.get(`/api/categories/get/all`).then(response => {
                
                this.categories = response.data.categories;
                this.categories = this.categories.map(element => {
                    element.selected = false;
                    return element;
                });
                // console.log('categoriesle sunt -------------', this.categories);
            }).finally(() => {
                this.loadingStatus = false;
            });

            // this.$store.dispatch('categories_explore/initCategories').finally(() => {
            //   this.loadingStatus = false;
            // });
        },

        categoriesSelected: function(payload){
            // console.log('suntem in categories component', payload);
        },

        openModal: function(){
            this.resetCategories();
            this.modalShow = !this.modalShow;
            this.categories = this.categories.map(element => {
                if (this.getSelectedCategories.some(e => e.id == element.id)) {
                    /* vendors contains the element we're looking for */
                    element.selected = true;
                }
                return element;
            });

            this.selected_categories = this.categories.filter(element => {
                if(element.selected == true){
                    return element;
                }
            });
            // this.validation_errors = [];
        },

        selectingCategories: function(category){

            this.categories = this.categories.map(element => {
                if(element.id == category.id){
                    element.selected = true;
                }
                return element;
            });

            this.selected_categories.push(category);
            // console.log(this.selected_categories);
        },

        deselectingCategories: function(category){

            this.categories = this.categories.map(element => {
                if(element.id == category.id){
                    element.selected = false;
                }
                return element;
            });

            this.selected_categories = this.selected_categories.filter(element => {
                if(element.id != category.id){
                    return element;
                }
            });
            // console.log(this.selected_categories);
        },

        resetCategories: function(){
            this.categories = this.categories.map(element => {
                element.selected = false;
                return element;
            });

            this.selected_categories = [];
        },


        saveCategories: function(){
            

            this.selected_categories = this.selected_categories.map(element => {
                delete element.selected;
                return element;
            });

            // console.log('salvam categoriesle', this.selected_categories);

            this.loadingStatus = true;
            axios.post(`/api/categories/user/save`, {
                categories: this.selected_categories
            }).then(response => {

                if(response.data.success){
                    this.loadingStatus = true;

                    this.$store.dispatch('pro_module/initSelectedCategories').finally(() => {
                        this.loadingStatus = false;
                    });
                    this.modalShow = false;
                    this.resetCategories();
                }
            }).finally(() => {
                this.loadingStatus = false;
            });
        }

       

      
    },

    created(){
        this.initializeCategories();
        // this.options = this.incOptions;

    }
  }
</script>

