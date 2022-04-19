<template>
<div class="text-center" v-if="loadingCategories">
  <b-spinner label="Spinning"></b-spinner>
</div>
<div v-else>

    <div class="row d-flex justify-content-end my-4">
        <div class="col-lg-4">
            <button class="btn btn-sm btn-default float-right" disabled="disabled" v-if="once_click">Deselectează toate categoriile</button>
            <button class="btn btn-sm btn-default float-right" @click.prevent="deselectCategories" v-else>Deselectează toate categoriile</button>
        </div>
    </div>


    <form @submit.prevent="save_categories">
    
        <ul class="list-group payment_methods_list" v-if="existing_categories" :key="categories_key">
                
                <li v-for="category in existing_categories" :key="category.id" class="list-group-item" style="font-size:16px;">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="categories[]" :value="category.id" :checked="category_exists(category)" @change="check($event)" ref="list_checkbox">
                        <span class="custom-control-label">{{ category.name }}</span>
                    </label>
                    
                </li>
                
            </ul>

        <div class="form-group my-4">
            <!-- <button type="submit" class="btn btn-primary mb-2 float-right" :disabled="get_selected_categories <= 0">Salveaza categoriile</button> -->
            <button class="btn btn-primary mb-2 float-right" disabled="disabled" v-if="once_click">Salvează categoriile</button>
            <button type="submit" class="btn btn-primary mb-2 float-right" v-else>Salvează categoriile</button>
        </div>
    </form>

</div>
</template>


<script>
import {mapGetters} from 'vuex';

export default {
    name: "CategoriesProfileComponent",

    data(){
        return {
            existing_categories: 0,
            selected_categories: [],
            validationError: '',
            validationErrors: '',
            success_status: false,
            error_status: false,

            loadingCategories: false,
            categories_key: 'categories-key',
            once_click: false
        }
    },

    props: {
        inc_categories: Array,
        my_categories: Array
    },


    computed: {
        ...mapGetters('pro_module', ['getSelectedCategories']),

        get_selected_categories: function(){
            return this.selected_categories.length;
        }
    },


    methods: {
        check: function(event){
            this.selected_categories = this.$refs.list_checkbox
                                        .filter(element => element.checked == true).map(element => {
                                            return element.defaultValue;
                                        });
           
            // this.$store.commit('pro_module/set_selected_categories', selected_categories);
        },

        category_exists: function(elem){
            let result = this.selected_categories.filter(function(element){
                if(element.id == elem.id) {
                    return true;
                }
            });

            return result.length > 0 ? true : false;
        },


        save_categories: function(){

            this.once_click = true;

            let categories = {
                'categories': this.selected_categories
            };
            

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/profile/categories/save', categories).then(response => {
                // this.success_status = true;
                
                this.$swal({
                    title: 'Acțiune executată cu succes.',
                    text: "Categoriile au fost salvate cu succes.",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok.',
                });

            }).catch(error => {
                // this.error_status = true;

                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori.',
                    type: 'error',
                    duration: 6000
                });


                if (error.response.status == 401){
                    this.validationError = error.response.data.error;
                    this.validationErrors = error.response.data.errors;
                    console.log(this.validationError);
                }
            }).finally(() => {
                this.once_click = false;
            });


        },



        closeSuccessAlert(){
            this.success_status = false;
        },

        closeErrorAlert(){
            this.error_status = false;
        },


        deselectCategories: function(){
            this.once_click = true;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/profile/categories/eliminate').then(response => {
                this.$swal({
                    title: 'Acțiune executată cu succes.',
                    text: "Categoriile au fost salvate cu succes.",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok.',
                });


                this.selected_categories = [];
                this.$store.commit('pro_module/set_selected_categories', []);
                this.categories_key += 1
                // this.$refs.list_checkbox.reset();
           
                // console.log(response.data);
            }).catch(error => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(()=> {
                this.once_click = false;
            });
        },


    },


    created(){

        this.existing_categories = this.inc_categories;

        this.loadingCategories = true;
        this.$store.dispatch('pro_module/initSelectedCategories').then(() => {
            this.selected_categories = this.getSelectedCategories;
        }).finally(() => {
            this.loadingCategories = false;
        });
    }
}
</script>

<style>
div.mask-form {
    display: block;
    width: 100%;
    height: 100%;
    background: red;
}
</style>