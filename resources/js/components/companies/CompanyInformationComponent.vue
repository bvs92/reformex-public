<template>
  <div>
    <!-- <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" /> -->





    <div class="mt-5" v-if="!isLoading">
            <div class="d-flex justify-content-center mb-3" v-if="hasCompany != true || hasCompany == null">
                <b-alert show variant="danger">
                    <p class="text-center">Informațiile despre firmă sunt importante. Te rugăm să completezi profilul.</p>
                </b-alert>
            </div>

            <div class="alert alert-info" role="alert" v-else>
                <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> Notă. Asigură-te că <strong>datele introduse</strong> în formular sunt corecte.
            </div>

            <ValidationObserver v-slot="{ handleSubmit }" ref="observer">
            <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <label for="company_type">Formă organizare (tip firma)</label>
                    <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                    <b-form-select v-model="company_type" :options="organization_types"></b-form-select>
                    <span class="text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('company_type')">{{ validationErrors.company_type[0] }}</span>
                    </validation-provider>
                    
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="company_name">Denumire firmă</label>
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
                    <validation-provider :rules="{ required: true, phone_rule: phone_error }" v-slot="{ errors, valid }">
                        <VuePhoneNumberInput v-model="phone" 
                        :default-country-code="'RO'" 
                        :translations="translations" 
                        :valid-color="'yellowgreen'" 
                        :error-color="'orangered'"
                        :required="true"
                        :error="phone_error"
                        @update="getPhoneEvent($event)" />
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div v-if="company_location !== null">
                    <div class="form-group">
                    <label>Adresă firmă (sediu)</label>
                    <p style="font-size: 16px;margin-top: 6px;"><i class="fa fa-map-marker"></i> {{ company_location.formatted_address }} <button class="btn btn-sm btn-warning" @click.prevent="editLocationModal">Modifică</button></p>
                    </div>
                    </div>
                    <div class="form-group" v-else>
                    <label>Adresă firmă (sediu)</label>
                    <!-- <company-location-component  :cached="{}" @location:selected="location_selected"></company-location-component> -->
                    <google-location @location:selected="location_selected"></google-location>
                    </div>
                </div>

                <!-- <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="workers">Numar angajati</label>
                        <validation-provider rules="required|integer|min:0" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="workers" name="workers" placeholder="10" v-model="workers">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('workers')">{{ validationErrors.workers[0] }}</span>
                        </validation-provider>
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="cui">Cod Unic de Înregistrare (CUI)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Număr Înmatriculare (Registrul Comerțului)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J00/000/2020" v-model="register_number">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('register_number')">{{ validationErrors.register_number[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="administrative_company">Judet</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="administrative_company" name="administrative_company" placeholder="Olt" v-model="administrative_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('administrative')">{{ validationErrors.administrative[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="city_company">Oras</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="city_company" name="city_company" placeholder="Corabia" v-model="city_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('city')">{{ validationErrors.city[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <!-- <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="address_company">Adresa</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" v-model="address_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('address')">{{ validationErrors.address[0] }}</span>
                        </validation-provider>
                    </div>
                </div> -->


                <!-- <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="website" class="form-label">Site internet</label>
                        <input type="text" class="form-control" name="website" placeholder="www.website.ro" v-model="website">
                    </div>
                </div> -->

                <!-- <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="year_made">An infiintare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="year_made" name="year_made" placeholder="2002" v-model="year_made">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('year_made')">{{ validationErrors.year_made[0] }}</span>
                        </validation-provider>
                    </div>
                </div> -->

                
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
        <label for="">Modifică adresă firmă</label>
        <!-- <company-location-component :cached="{}" @location:selected="location_selected"></company-location-component> -->
        <google-location @location:selected="location_selected"></google-location>
        <button class="btn btn-success btn-block my-4" @click.prevent="locationModal = !locationModal">Ok</button>
    </div>
    </b-modal>
    
    </div>
</template>

<script>

// import CompanyLocationComponent from './CompanyLocationComponent.vue';

import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';


import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';

import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

// import GetCompanyByCIF from "../profile/Components/GetCompanyByCIF";

// import GetCompanyByName from "../profile/Components/GetCompanyByName";
import GoogleLocation from '../location/GoogleLocation.vue';


extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

extend('integer', {
  ...integer,
  message: 'Sunt acceptate doar valori numerice întregi.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minimă acceptată este 20.'
});

extend('min', {
  ...min,
  message: 'Lungimea minimă acceptată este 3 caractere.'
});

extend('length', {
  ...length,
  message: 'Lungimea acceptată este {length} caractere.'
});

extend('phone_rule', {
    message: "Număr de telefon invalid.",
    validate: value => {
        return "Numărul de telefon nu este valid."
    }
});


export default {
 name: "CompanyInformationComponent",
 components: {
        VuePhoneNumberInput,
        ValidationProvider,
        ValidationObserver,
        MoonLoader,
        // GetCompanyByName,
        // GetCompanyByCIF,
        // CompanyLocationComponent
        GoogleLocation
    },


    data(){
        return {
            locationModal: false,
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

            company_location: null,


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

            company_type: null,
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

        getPhoneEvent(event){
            // console.log('Eveniment telefon: ', event);
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },


        reset_information(){
            this.company_name = '';
            // this.year_made = '';
            this.phone = '';
            // this.workers = '';
            this.cui = '';
            this.register_number = '';
            // this.administrative_company = '';
            // this.city_company = '';
            // this.address_company = '';
            this.company_type = null;
            // this.bio = '';
            // this.website = '';
        },

        



        location_selected: function(inc_location){
            // console.log('in component company information', inc_location);
            this.company_location = inc_location;
        },



        onSubmit(){

                // create the object
            let information = {
                name: this.company_name,
                // year_made: this.year_made,
                phone: this.phone,
                // workers: this.workers,
                cui: this.cui,
                register_number: this.register_number,
                // administrative: this.administrative_company,
                // city: this.city_company,
                // address: this.address_company,
                // bio: this.bio,
                // website: this.website,
                company_type: this.company_type,
                company_location: this.company_location
            }

            let self = this;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/profile/company/save', information).then(response => {
                // this.success_status = true;
                // setTimeout(function(){
                //     self.closeSuccessAlert();
                // }, 40000)

                // this.company_name = response.data.company.name;
                // this.year_made = response.data.company.year_made;
                // this.phone = response.data.company.phone;
                // this.workers = response.data.company.workers;
                // this.cui = response.data.company.cui;
                // this.register_number = response.data.company.register_number;
                // this.administrative_company = response.data.company.administrative;
                // this.city_company = response.data.company.city;
                // this.address_company = response.data.company.address;
                // this.bio = response.data.company.bio;
                // this.website = response.data.company.website;

                this.$swal(
                    'Datele au fost salvate.',
                    'Informațiile despre firmă au fost salvate cu succes.',
                    'success'
                );

                this.$refs.observer.reset();
                this.validationErrors = [];

             
            }).catch(error => {
                // this.error_status = true;

                // setTimeout(function(){
                //     self.closeErrorAlert();
                // }, 40000)

                this.$swal(
                    'Am întampinat erori.',
                    'Ceva nu a funcționat bine, reîncearcă mai târziu.',
                    'error'
                );


                if (error.response.status == 401){
                    this.validationErrors = error.response.data.errors;
                    // console.log(error.response.data.errors);
                }
            });
        },


        extract_data(register_data){
            let samples = register_data.split("INREGISTRAT din data ");
            return samples[1];
        },


        extract_city(address){
            let parts = address.split('-,');
            this.city_company = parts[1];
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
    
    
                // this.year_made = this.extract_data(inc_company.stare);
                // this.year_made = inc_company.stare;
                // this.address_company = inc_company.adresa;
                // this.extract_city(this.address_company);
                // this.administrative_company = inc_company.judet;
                // this.postal_code = inc_company.cod_postal;
            }
        },

        initCompanyInformation: function(){
            this.isLoading = true;
            // axios
            axios.get(`/api/admin/users/get/company/${this.user_id}`).then(response => {
                        // console.log('/api/admin/users/get/company este', response.data);

                        if(response.data.company){

                            this.company_type = response.data.company.company_type;
                            this.company_name = response.data.company.name;
                            // this.year_made = response.data.company.year_made;
                            this.phone = response.data.company.phone;
                            // this.workers = response.data.company.workers;
                            this.cui = response.data.company.cui;
                            this.register_number = response.data.company.register_number;
                            // this.administrative_company = response.data.company.administrative;
                            // this.city_company = response.data.company.city;
                            // this.address_company = response.data.company.address;
                            // this.bio = response.data.company.bio;
                            // this.website = response.data.company.website;
                            this.hasCompany = true;
                            if(response.data.company.location){
                                this.company_location = JSON.parse(response.data.company.location.details);
                                // console.log('locatie companie este ------------------ ', this.company_location);
                            }

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