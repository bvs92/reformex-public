<template>
    
<div class="col-lg-12 my-2"> 

    <div class="card border-dark mb-3">
        <div class="card-header">Perioadă</div>
        <div class="card-body text-dark">
            <p class="card-text">
                Stare: <span v-if="valabilityComputed" class="badge badge-success">Rulează</span>
                <span v-else class="badge badge-danger">Oprit</span>
            </p>


            <div v-if="ad.ends_at" class="card-text">
                <p>Dată expirare: {{ expiration_time }}</p>
            </div>

            <div v-if="recent_period" class="card-text">
                <p>Ultima perioadă selectată: {{ recent_period.days }} zile.</p>
            </div>


            
        </div>
    </div><!-- end card -->

        <div class="card border-dark mb-3">
            <div class="card-header">Categorii</div>
                <div class="card-body text-dark">
                    <div v-if="ad.categories && !change_categories">
                        <p>Categorii selectate</p>
                    <div>
                        <span v-for="category in listing_categories" :key="category.id" class="badge badge-info mr-2 mb-2">{{ category.name }}</span>
                    </div>
                </div>

                <template v-if="ad.rejected == 0">
                <div v-if="!change_categories">
                    <button class="btn btn-primary btn-sm my-2" @click.prevent="modifyCategories">Modifică categorii</button>
                </div>

                <div v-else>
                    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                    <form class="col-lg-12" @submit.prevent="handleSubmit(updateCategories)">
                        <categories-component 
                        @selectedCategories="selectedCategories" 
                        ref="categories"
                        :existing_categories="existing_categories"
                        ></categories-component>
                        <template v-if="validation_errors" style="width: 100%; display: block;">
                            <template v-if="validation_errors['categories']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['categories']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                        <div class="d-flex justify-content-around">
                            <button class="btn btn-success my-2" type="submit">Salvează</button>
                            <button class="btn btn-default my-2" @click.prevent="change_categories = !change_categories">Înapoi</button>
                        </div>
                    </form>
                    </ValidationObserver>
                </div>   

                </template>                 
            </div>
        </div><!-- end card -->

        <div class="card border-dark mb-3">
            <div class="card-header">Opțiuni</div>
                <div class="card-body text-dark">
                    <ValidationObserver ref="observer_options" v-slot="{ handleSubmit, invalid }">
                    <form class="col-lg-12" @submit.prevent="handleSubmit(updateOptions)">
                        <b-form-checkbox
                            id="checkbox-form"
                            v-model="hasForm"
                            name="checkbox-form"
                            :value="true"
                            unchecked-value="false"
                        >
                            Formular de contact atașat anunțului? (Vizitatorii vor trimite mesaje pe e-mail.)
                        </b-form-checkbox>
                        <br />
                        <div>
                            <b-form-checkbox
                            id="checkbox-show-email"
                            v-model="show_email"
                            name="checkbox-show-email"
                            :value="true"
                            unchecked-value="false"
                            >
                            Afișează adresa de e-mail în anunț?
                            </b-form-checkbox>
                        </div>
                        <br />   
                        <div class="mt-4">
                            <button class="btn btn-success" type="submit" v-if="!loading_options">Salvează</button>
                            <b-button variant="primary" disabled v-else>
                                <b-spinner small type="grow"></b-spinner>
                                Salvez...
                            </b-button>
                        </div>
                    </form>
                    </ValidationObserver>
                
            </div>
        </div><!-- end card -->

    
            

</div>
</template>

<script>
import axios from 'axios';
import PeriodComponent from "../../PeriodComponent.vue";
import CategoriesComponent from "../../CategoriesComponent.vue";

import { ValidationProvider, ValidationObserver } from 'vee-validate';

export default {
    name: "UpdateOptionsComponent",

    watch: {
        hasForm(value) {
            this.hasFormElement = value;
        }
    },

    components: {
        PeriodComponent,
        CategoriesComponent,
        ValidationObserver,
        ValidationProvider,
    },

    props: ["ad"],

    data(){
        return {
            once: false,
            validation_errors: null,
            period: null,
            ends_at: null,
            loading_period: false,
            

            // options
            hasForm: true,
            hasFormElement: true,
            show_email: true,
            status: true,
            loading_options: false,

            // categories
            categories: null,
            extend: false,
            change_categories: false,
            existing_categories: null,
            loading_categories: false,
            listing_categories: null,
            recent_period: null,
        }
    },

    computed: {
        UpdateImageComponentsFormComputed: function(){
            return this.hasForm ? true : false;
        },

        valabilityComputed: function(){
            let result;
            if(moment().isBefore(this.ends_at) && this.status){
                result = true;
            } else {
                result = false;
            }

            return result;
        },

        expiration_time: function(){
            return this.formatElementTimeMethod(this.ends_at);
        },
    },

    methods: {
        modifyCategories: function(){
            this.categories = this.existing_categories;
            this.change_categories = !this.change_categories;
        },


        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        getExistingCategories: function(incoming){
            this.existing_categories = incoming.map(item => {
                return item.id;
            })
        },

        hasFormValue: function(){
            this.hasForm = !this.hasForm;
            // console.log('has form is', this.hasForm);
        },

        selectedPeriod: function(period){
            this.period = period;
        },

        selectedCategories: function(categories){
            this.categories = categories;
            // console.log('categorii selectate', categories);
        },

        updateOptions: async function(){
            this.loading_options = true;
            let formData = new FormData();
            formData.append('has_form',  this.hasForm);
            formData.append('show_email',  this.show_email);

            await axios.post('/api/ads_recommend/personal/update_announce_options/' + this.ad.id, formData)
            .then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    Vue.$toast.open({
                        message: 'Opțiuni salvate cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
          
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
               this.loading_options = false;
            });

        },


        updateCategories: async function(){
            this.validation_errors = null;
            this.loading_categories = true;
            let formData = new FormData();
            formData.append('categories',  this.categories);
          
            await axios.post('/api/ads_recommend/personal/update_announce_categories/' + this.ad.id, formData)
            .then(async response => {
                console.log(response.data);
                if(response.data.success){
                    // adauga user in lista.
                    Vue.$toast.open({
                        message: 'Opțiuni salvate cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    this.listing_categories = response.data.categories;
                    this.change_categories = !this.change_categories;
                    this.getExistingCategories(response.data.categories);
                    
          
                } else if(response.data.validation_errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori de validare. Verifică categoriile selectate.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
               this.loading_categories = false;
            });

        }
    },

    created(){
        this.ends_at = this.ad.ends_at;
        this.recent_period = this.ad.recent_period;
        this.hasForm = this.ad.has_form == 1 ? true : false;
        this.show_email = this.ad.show_email == 1 ? true : false;
        this.status = this.ad.status == 1 ? true : false;

        if(this.ad.categories){
            this.listing_categories = this.ad.categories;
            this.getExistingCategories(this.ad.categories);
        }
    }
}
</script>