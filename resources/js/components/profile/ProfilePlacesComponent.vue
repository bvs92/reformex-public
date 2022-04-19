<template>
<div class="text-center" v-if="loading">
  <b-spinner label="Spinning"></b-spinner>
</div>
<div v-else>
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-end">
            <button class="btn btn-sm btn-warning" @click.prevent="edit_location" v-if="show_edit">Editează locație</button>
            <button class="btn btn-sm btn-default" @click.prevent="cancel_edit_location" v-else>Renunță la modificări</button>
        </div>
    </div>


    <div v-if="existing_location">
        <ExistingLocationComponent :location="incoming_location" :accessTokenMap="the_accessTokenMap" ref="existing_location" />
    </div>

    <div v-show="!existing_location">

        <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(saveDetails)">

        <div class="row my-4">
            <div class="col-lg-6 my-4">
                <h4>Selectează orașul de intervenție</h4>
                <places
                    v-model="form.country.label"
                    placeholder="Indică orașul sau localitatea"
                    @change="valueChanged($event)"
                    :options="options">
                </places>
                <span class="text-danger" v-if="validationErrors && validationErrors.hasOwnProperty('city')">{{ validationErrors.city[0] }}</span>
            </div>
        

     
            <div class="col-lg-6 my-4">
                <h4>Selectează raza de intervenție</h4>
                <validation-provider rules="required|integer|min_value:10|max_value:1000" v-slot="{ errors, invalid, passed }">
                    <input type="numeric" style="width: 100%" v-model="range" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" name="range" />
                    <span class="text-danger">{{ errors[0] }}</span>
                    <span class="text-danger" v-if="validationErrors && validationErrors.hasOwnProperty('range')">{{ validationErrors.range[0] }}</span>
                </validation-provider>
            </div>
        </div>


        <div class="col-lg-12 d-flex justify-content-end">
            <button class="btn btn-primary float-right mx-2" disabled="disabled" v-if="once">Salvează informațiile</button>
            <button class="btn btn-primary float-right mx-2" type="submit" :disabled="location_selected" v-else>Salveaza informațiile</button>
            <button class="btn btn-default float-right mx-2" @click="valueReset" :disabled="location_selected">Reseteaza informațiile</button>
        </div>
        </form>
        </ValidationObserver>

    </div>

</div>
</template>

<script>

import Places from 'vue-places';


import {mapGetters} from 'vuex';
import ExistingLocationComponent from "./Components/ExistingLocationComponent";


import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, max_value } from 'vee-validate/dist/rules';

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
  message: 'Valoarea minima acceptata este 10.'
});

extend('max_value', {
  ...max_value,
  message: 'Valoarea maxima acceptata este 1000.'
});


export default {
    name: "ProfilePlacesComponent",

    data(){
        return {
            options: {
                appId: this.appid,
                apiKey: this.apikey,
                countries: ['RO']
            },
            form: {
                country: {
                label: null,
                data: {},
                }
            },

            // range: parseInt(this.location.range / 1000) || 10,
            range: 10,
            success_status: false,
            error_status: false,
            validationErrors: null,
            validationError: '',


            existing_location: false,
            incoming_location: null,
            show_edit: true,

            range_error: true,
            once: false,
            loading: false

        }
    },


    props: {
        appid: String,
        apikey: String,
        location: Object,
        the_accessTokenMap: String
    },


    components: {
        Places,
        ExistingLocationComponent,

        ValidationProvider,
        ValidationObserver
    },

    computed: {
        ...mapGetters('pro_module', ['getExistingLocation']),
        location_selected: function(){
            if(Object.keys(this.form.country.data).length !== 0)
                return false;
            else 
                return true;
        },

        range_provided: function(){
            if(this.range < 10 && this.range > 1000){
                return false;
            } else {
                return true;
            }
        }


    },


    methods: {
        valueChanged: function(e){
            // console.log(e);
            this.form.country.data = e;
            // console.log(this.form.country);
            this.validationErrors = null;
        },

        valueReset: function(){
            this.form.country.data = {};
            this.form.country.label = null;
            this.validationErrors = null;
        },

        edit_location: function(){
            this.existing_location = false;
            this.show_edit = false;

            this.valueReset();
            this.range = 10;
        },

        cancel_edit_location: function(){
            this.existing_location = true;
            this.show_edit = true;
            this.validationErrors = null;
        },

        range_ok: function(passed){
            if(passed){
                this.range_error = false;
            } else {
                this.range_error = true;
            }
        },



        saveDetails: function(){
            this.validationErrors = null;
            this.once = true;

            let self = this;

            // if object not null
            if(Object.keys(this.form.country.data).length !== 0){
                let details = {
                    city: this.form.country.data.name,
                    administrative: this.form.country.data.administrative ? this.form.country.data.administrative : this.form.country.data.county,
                    postal_code: this.form.country.data.postcode,
                    lat: this.form.country.data.latlng.lat,
                    lng: this.form.country.data.latlng.lng,
                    range: this.range
                };

                axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
                axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
                axios.post('/api/profile/location/save', details).then(response => {

                    if(response.data.success){
                        this.existing_location = true;
                        this.show_edit = true;
    
                        this.$swal(
                            'Datele au fost salvate.',
                            'Informațiile specificate au fost salvate cu succes.',
                            'success'
                        );
    
                        this.$set(this.incoming_location, 'city', details.city);
                        this.$set(this.incoming_location, 'lat', details.lat);
                        this.$set(this.incoming_location, 'lng', details.lng);
                        this.$set(this.incoming_location, 'postal_code', details.postal_code);
                        this.$set(this.incoming_location, 'range', details.range * 1000);
    
                        this.form.country.data = {};
                    } else if(response.data.errors){
                        this.validationErrors = Object.assign([], response.data.errors);
                        console.log(this.validationErrors);
                    }
                }).catch(error => {
                    


                    this.$swal(
                        'Eroare.',
                        'Am întâmpinat erori. Reîncearcă mai târziu.',
                        'error'
                    );


                    if (error.response.status == 401){
                        this.validationError = error.response.data.error;
                        this.validationErrors = error.response.data.errors;
                        // console.log(this.validationErrors);
                    }
                }).finally(() => {
                    this.once = false;
                });
    
                // console.log(details);
            }


        },


        closeSuccessAlert(){
            this.success_status = false;
        },

        closeErrorAlert(){
            this.error_status = false;
        },

    },


    created(){
 

        // get location
        this.loading = true;
        this.$store.dispatch('pro_module/initExistingLocation').then(() => {
            this.incoming_location = this.getExistingLocation;

            if(this.incoming_location.lat || this.incoming_location.lng || this.incoming_location.name){
                this.existing_location = true;
            }
        }).finally(() => {
            this.loading = false;
        });
        
    }

}
</script>

<style scoped>

</style>