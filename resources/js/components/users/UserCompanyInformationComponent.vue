<template>
  <div>
    <!-- <h4 class="my-4">Informatii despre firma.</h4> -->
    <!-- <div class="tab_wrapper first_tab my-4">
        <ul class="tab_list">
            <li class="active" rel="tab_1_1">Denumire</li>
            <li rel="tab_1_2" class="">CIF</li>
        </ul>

        <div class="content_wrapper">
            <div class="tab_content first tab_1_1 active" title="tab_1_1" style="display: block;">
              <GetCompanyByName @getting_company_information="getting_company_information" />
            </div>

            <div class="tab_content tab_1_2" title="tab_1_2" style="display: none;">
               <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" />
            </div>

        </div>
    </div> -->

    <!-- <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" /> -->





    <div class="mt-5" v-if="!isLoading">
            <h3>Informații despre companie</h3>
            <div class="d-flex justify-content-center mb-3" v-if="hasCompany != true || hasCompany == null">
                <b-alert show variant="danger">
                    <p class="text-center">Informațiile despre firmă sunt importante. Te rugăm să le completezi. </p>
                </b-alert>
            </div>
            <ValidationObserver v-slot="{ handleSubmit }" >
            <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <label for="company_type">Formă organizare</label>
                    <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                    <b-form-select v-model="company_type" :options="organization_types"></b-form-select>
                    <span class="text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('company_type')">{{ validationErrors.company_type[0] }}</span>
                    </validation-provider>
                    
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="type">Denumire firmă</label>
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : invalid, 'is-valid': passed}" v-model="company_name">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span>
                        </validation-provider>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                    <label>Număr de telefon</label>
                        <VuePhoneNumberInput v-model="phone" 
                        :default-country-code="'RO'" 
                        :translations="translations" 
                        :valid-color="'yellowgreen'" 
                        :error-color="'orangered'"
                        :required="true"
                        :error="phone_error"
                        @update="getPhoneEvent($event)" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div v-if="company_location !== null">
                    <div class="form-group">
                    <label>Adresă</label>
                    <p style="font-size: 16px;margin-top: 6px;"><i class="fa fa-map-marker"></i> {{ company_location.value }} <button class="btn btn-sm btn-warning" @click.prevent="editLocationModal">Modifica</button></p>
                    </div>
                    </div>
                    <div class="form-group" v-else>
                    <label>Adresă</label>
                    <!-- <company-location-component  :cached="{}" @location:selected="location_selected"></company-location-component> -->
                    <google-location @location:selected="location_selected"></google-location>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="cui">Cod Unic de Inregistrare (CUI)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="12345678" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Număr Înmatriculare (Registrul Comerțului)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J28/123/2008" v-model="register_number">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('register_number')">{{ validationErrors.register_number[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

           
            
            <div class="card-footer">
                <button type="submit" class="btn btn-success mt-1">Salvează</button>
                <!-- <a class="btn btn-default mt-1" @click="reset_information">Reseteaza datele</a> -->
            </div>
        </form>
            </ValidationObserver>
    </div>

    <div class="d-flex justify-content-center mb-3" v-else>
        <b-spinner label="Loading..."></b-spinner>
    </div>

    <b-modal v-model="locationModal" hide-footer id="modal-center" centered title="Adresa companie">
    <div>
        <label for="">Modificare adresă companie</label>
        <!-- <company-location-component :cached="{}" @location:selected="location_selected"></company-location-component> -->
        <google-location @location:selected="location_selected"></google-location>
        <button class="btn btn-success btn-block my-4" @click.prevent="locationModal = !locationModal">Ok</button>
    </div>
    </b-modal>
    
    </div>
</template>

<script>
import GoogleLocation from '../location/GoogleLocation.vue';
// import CompanyLocationComponent from '../companies/CompanyLocationComponent.vue';
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';


import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';

import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

import GetCompanyByCIF from "../profile/Components/GetCompanyByCIF";
import GetCompanyByName from "../profile/Components/GetCompanyByName";


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
  message: 'Lungimea minima acceptata este 3 caractere.'
});

extend('length', {
  ...length,
  message: 'Lungimea acceptata este {length} caractere.'
});


export default {
 name: "UserCompanyInformationComponent",
 components: {
        VuePhoneNumberInput,
        ValidationProvider,
        ValidationObserver,
        MoonLoader,
        GetCompanyByName,
        GetCompanyByCIF,
        // CompanyLocationComponent,
        GoogleLocation
    },


    data(){
        return {
            locationModal: false,
            company_type: null,
            company_location: null,
            isLoading: false,
            hasCompany: false,
            cif: null,
            // company_name: null,
            // year_made: null,
            // phone: null,
            // workers: null,
            // cui: null,
            // register_number: null,
            // administrative_company: null,
            // city_company: null,
            // address_company: null,
            // bio: null,
            // website: null,

            company_name: '',
            year_made: '',
            phone: '',
            workers: '',
            cui: '',
            register_number: '',
            administrative_company: '',
            city_company: '',
            address_company: '',
            bio: '',
            website: '',


            error_status: false,
            success_status: false,

            validationErrors: '',
            general_error: null,

            valid_phone: false,
            phone_error: false, // culoare
            show_spinner: false,

            translations: {
                countrySelectorLabel: 'Codul tarii',
                countrySelectorError: 'Selectari tara',
                phoneNumberLabel: 'Numarul de telefon',
                example: 'Exemplu: '
            },

            only_countries: ['RO', 'BE', 'DE', 'FR', 'IT', 'GB', 'AT', 'BG', 'MD', 'ES', 'GR', 'IS', 'LU', 'NL', 'PT', 'LE', 'SI', 'SK'],

            organization_types: [
                {value: 'PFA', text: 'Persoană Fizică Autorizată'},
                {value: 'II', text: 'Întreprindere Individuală'},
                {value: 'IF', text: 'Întreprindere Familială'},
                {value: 'SRL', text: 'Societate cu Răspundere Limitată'},
                {value: 'SRL-D', text: 'Societate cu Răspundere Limitată - Debutant'},
                {value: 'SNC', text: 'Societate în Nume Colectiv'},
                {value: 'SA', text: 'Societate pe Acțiuni'},
                {value: 'SCS', text: 'Societate în Comandită Simplă'},
                {value: 'SCA', text: 'Societate în Comandită pe Acțiuni'},
                {value: 'SE', text: 'Societate Europeană'}
            ]
        }
    },


    props: {
        user_id: Number
    },


    methods: {

        editLocationModal: function(){
            this.locationModal = !this.locationModal;
        },

        location_selected: function(inc_location){
            console.log('in component company information', inc_location);
            this.company_location = inc_location;
        },

        getPhoneEvent(event){
            // console.log('Eveniment telefon: ', event);
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },


        reset_information(){
            this.company_name = '';
            this.year_made = '';
            this.phone = '';
            this.workers = '';
            this.cui = '';
            this.register_number = '';
            this.administrative_company = '';
            this.city_company = '';
            this.address_company = '';
            this.bio = '';
            this.website = '';
        },

        



        onSubmit(){

            // create the object
            

            let information = {
                name: this.company_name,
       
                phone: this.phone,
    
                cui: this.cui,
                register_number: this.register_number,
   
                company_type: this.company_type,
                company_location: this.company_location
            }

            // call axios

            let self = this;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/admin/users/${this.user_id}/profile/company/save`, information).then(response => {
                // this.success_status = true;
                // setTimeout(function(){
                //     self.closeSuccessAlert();
                // }, 40000)

                if(response.data.success){
                    this.$swal(
                        'Datele au fost salvate.',
                        'Informațiile despre firmă au fost salvate cu succes.',
                        'success'
                    );

                    this.hasCompany = true;
                } else if (response.data.errors) {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                } else if (resposne.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                }


            }).catch(error => {
                this.$swal(
                    'Eroare',
                    'Am întâmpinat erori. Reîncearcă mai târziu.',
                    'error'
                );
            });
        },


        async getCompanyData(){
            // folosim open api
            let API_KEY = "";
            let URL_SERVER = "";
            // let URL_SERVER = "https://api.openapi.ro/api/companies"; when in production

            this.general_error = null;

            this.show_spinner = true;

            axios.defaults.headers.common = {
                'x-api-key': API_KEY,
                // 'Access-Control-Allow-Origin': '*',
            }

            await axios.get(`${URL_SERVER}/${this.cif}`)
            .then(response => {
                console.log(response.data);
                // let {company} = response.data;

                this.company_name = response.data.denumire;
                this.cui = response.data.cif;
                this.register_number = response.data.numar_reg_com;

                this.phone = response.data.telefon;
                // if(response.data.telefon){
                //     // this.valid_phone = true;
                // }


                this.year_made = this.extract_data(response.data.stare);
                // this.year_made = response.data.stare;
                this.address_company = response.data.adresa;
                this.extract_city(this.address_company);
                this.administrative_company = response.data.judet;
                this.postal_code = response.data.cod_postal;

                this.show_spinner = false;
            })
            .catch(error => {
                this.general_error = error.response.data.error;

                this.show_spinner = false;
            });


            console.log('apelat...');
        },


        extract_data(register_data){
            let samples = register_data.split("INREGISTRAT din data ");
            return samples[1];
        },


        extract_city(address){
            let parts = address.split('-,');
            this.city_company = parts[1];
        },


        getting_company_information(inc_company){
            this.company_name = inc_company.denumire;
            this.cui = inc_company.cif;
            this.register_number = inc_company.numar_reg_com;

            this.phone = inc_company.telefon;
            // if(inc_company.telefon){
            //     // this.valid_phone = true;
            // }


            this.year_made = this.extract_data(inc_company.stare);
            // this.year_made = inc_company.stare;
            this.address_company = inc_company.adresa;
            this.extract_city(this.address_company);
            this.administrative_company = inc_company.judet;
            this.postal_code = inc_company.cod_postal;
        },

        getting_data_by_cif(inc_company){
            if(inc_company){
                this.company_name = inc_company.denumire;
                this.cui = inc_company.cif;
                this.register_number = inc_company.numar_reg_com;
    
                this.phone = inc_company.telefon;
                // if(inc_company.telefon){
                //     // this.valid_phone = true;
                // }
    
    
                this.year_made = this.extract_data(inc_company.stare);
                // this.year_made = inc_company.stare;
                this.address_company = inc_company.adresa;
                this.extract_city(this.address_company);
                this.administrative_company = inc_company.judet;
                this.postal_code = inc_company.cod_postal;
            }
        },

        initCompanyInformation: function(){
            this.isLoading = true;
            // axios
            axios.get(`/api/admin/users/get/company/${this.user_id}`).then(response => {
                        // console.log('/api/admin/users/get/company este', response.data);

                        if(response.data.company){

                            this.company_name = response.data.company.name;
                            this.company_type = response.data.company.company_type;
                            // this.year_made = response.data.company.year_made;
                            this.phone = response.data.company.phone;
                            // this.workers = response.data.company.workers;
                            this.cui = response.data.company.cui;
                            this.register_number = response.data.company.register_number;
                            if(response.data.company.location){
                                // console.log('location', response.data.company.location);
                                this.company_location = response.data.company.location;
                            }
                            this.hasCompany = true;

                        } else {
                            this.hasCompany = false;
                        }

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    }).finally(() => {
                        this.isLoading = false;
                    });

        }
    },

    created(){
        // console.log('initCompanyInformation este ------------------');
        // console.log('user_id este', this.user_id);
        this.initCompanyInformation();
    }

}
</script>