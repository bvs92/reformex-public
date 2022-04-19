<template>
<div>

    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Informații personale</h3>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-6 my-2">
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                        <label for="last_name">Nume</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="last_name" 
                        placeholder="Popescu" name="last_name"
                        v-model="last_name"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('last_name')">{{ validation_errors.last_name[0] }}</span>
                    </validation-provider>
                    <!-- <template v-if="validation_errors">
                        <template v-if="validation_errors['first_name']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['first_name']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template> -->
                </div>

                <div class="col-lg-6 my-2">
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                        <label for="first_name">Prenume</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="first_name" 
                        placeholder="Marian" name="first_name"
                        v-model="first_name"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('first_name')">{{ validation_errors.first_name[0] }}</span>
                    </validation-provider>
                    <!-- <template v-if="validation_errors">
                        <template v-if="validation_errors['last_name']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['last_name']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template> -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 my-2">
                    <validation-provider rules="required|min:3|email" v-slot="{ errors, invalid, passed, touched }">
                        <label for="last_name">E-mail</label>
                        <input type="email" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="email" 
                        placeholder="popescu@gmail.com" name="email"
                        v-model="email"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('email')">{{ validation_errors.email[0] }}</span>
                    </validation-provider>
                    <!-- <template v-if="validation_errors">
                        <template v-if="validation_errors['email']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['email']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template> -->
                </div>
            </div>


            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <label for="company_type">Formă organizare</label>
                    <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                    <b-form-select v-model="company_type" :options="organization_types"></b-form-select>
                    <span class="text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validation_errors.hasOwnProperty('company_type')">{{ validation_errors.company_type[0] }}</span>
                    </validation-provider>
                    
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="company_name">Denumire firmă</label>
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="company_name">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors.hasOwnProperty('name')">{{ validation_errors.name[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                    <label>Număr de telefon</label>
                    <validation-provider :rules="{ required: true, phone_rule: phone_error }" v-slot="{ errors, passed }">
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
                    <label>Adresă firmă</label>
                    <p style="font-size: 16px;margin-top: 6px;"><i class="fa fa-map-marker"></i> {{ company_location.value }} <button class="btn btn-sm btn-warning" @click.prevent="editLocationModal">Modifică</button></p>
                    </div>
                    </div>
                    <div class="form-group" v-else>
                    <label>Adresă firmă</label>
                    <!-- <company-location-component  :cached="{}" @location:selected="location_selected"></company-location-component> -->
                    <google-location @location:selected="location_selected"></google-location>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="cui">Cod Unic de Înregistrare (CUI)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors.hasOwnProperty('cui')">{{ validation_errors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Număr Înmatriculare (Registrul Comerțului)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J00/000/2020" v-model="register_number">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors.hasOwnProperty('register_number')">{{ validation_errors.register_number[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>


            <!-- <div class="row">
                <div class="col-lg-12">
                    <hr>
                    <h3>Informatii despre firma</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" />
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="company_name">Denumire firma</label>
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" v-model="company_name">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('company_name')">{{ validation_errors.company_name[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="year_made">An infiintare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="year_made" name="year_made" placeholder="2002" v-model="year_made">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('year_made')">{{ validation_errors.year_made[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                    <label>Numar de telefon</label>
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
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="workers">Numar angajati</label>
                        <validation-provider rules="required|integer|min:0" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="workers" name="workers" placeholder="10" v-model="workers">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('workers')">{{ validation_errors.workers[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="cui">Cod Unic de Inregistrare (CUI)</label>
                        <validation-provider rules="required|length:8" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="cui" name="cui" placeholder="12345678" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('cui')">{{ validation_errors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Numar Inmatriculare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J28/123/2008" v-model="register_number">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('register_number')">{{ validation_errors.register_number[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="administrative_company">Judet</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="administrative_company" name="administrative_company" placeholder="Olt" v-model="administrative_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('administrative')">{{ validation_errors.administrative[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="city_company">Oras</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="city_company" name="city_company" placeholder="Corabia" v-model="city_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('city')">{{ validation_errors.city[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="address_company">Adresa</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" v-model="address_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('address')">{{ validation_errors.address[0] }}</span>
                        </validation-provider>
                    </div>
                </div>


                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="website" class="form-label">Site internet</label>
                        <input type="text" class="form-control" name="website" placeholder="www.website.ro" v-model="website">
                    </div>
                </div>
            </div> -->

            <hr>

            <div class="col-lg-12 my-2">
                <button class="btn btn-success" v-if="!once">Salvează</button>
                <b-button variant="info" disabled v-else>
                    <b-spinner small></b-spinner>
                    <span class="sr-only">Salvăm...</span>
                </b-button>
                <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button>
            </div>
        </form>
    </ValidationObserver>

    <b-modal v-model="locationModal" hide-footer id="modal-center" centered title="Adresa companie">
    <div>
        <label for="">Modificare adresă firmă</label>
        <google-location @location:selected="location_selected"></google-location>
        <!-- <company-location-component :cached="{}" @location:selected="location_selected"></company-location-component> -->
        <button class="btn btn-success btn-block my-4" @click.prevent="locationModal = !locationModal">Ok</button>
    </div>
    </b-modal>

</div>
</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import CompanyLocationComponent from './CompanyLocationComponent.vue';

import GoogleLocation from '../location/GoogleLocation.vue';

import GetCompanyByCIF from "../profile/Components/GetCompanyByCIF";

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, email } from 'vee-validate/dist/rules';

import {mapGetters} from 'vuex';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('email', {
  ...email,
  message: 'Adresa de e-mail invalida.'
});

export default {
    name: "CreateCompanyComponent",

    data(){
        return {
            locationModal: false,
            company_location: null,
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
            ],
            

            once: false,
            validation_errors: [],

            isLoading: false,

            first_name: '',
            last_name: '',
            email: '',


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


            general_error: null,

            valid_phone: false,
            phone_error: false, // culoare
            show_spinner: false,

            translations: {
                countrySelectorLabel: 'Codul tării',
                countrySelectorError: 'Selectează prefix țară',
                phoneNumberLabel: 'Numărul de telefon',
                example: 'Exemplu: '
            },

            // only_countries: ['RO', 'BE', 'DE', 'FR', 'IT', 'GB', 'AT', 'BG', 'MD', 'ES', 'GR', 'IS', 'LU', 'NL', 'PT', 'LE', 'SI', 'SK']
            only_countries: ['RO']

        }
    },

    components: {
        ValidationObserver,
        ValidationProvider,

        VuePhoneNumberInput,
        GetCompanyByCIF,
        CompanyLocationComponent,

        GoogleLocation
    },

    computed: {
    },

    methods: {
        editLocationModal: function(){
            this.locationModal = !this.locationModal;
        },
        location_selected: function(inc_location){
            console.log('in component company information', inc_location);
            this.company_location = inc_location;
        },

        onSubmit: async function(){
            this.once = true;
            // console.log('fire!');

            this.validation_errors = [];


            let information = {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                company_name: this.company_name,
         
                phone: this.phone,
      
                cui: this.cui,
                register_number: this.register_number,
 
                company_type: this.company_type,
                company_location: this.company_location
            }


            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            // axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            await axios.post('/api/companies/store', information).then(async response => {
                if(response.data.success){
                    // adauga user in lista.

                    this.$swal({
                        title: 'Firmă creată cu succes',
                        text: "Utilizatorul și compania au fost create cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });


                    await this.resetAll();
                   
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });
        },

        resetAll: function(){
            
            this.first_name = '';
            this.last_name = '';
            this.email = '';
            this.cui = '';
            // this.welcome_user = true;

            this.reset_information();

            this.$refs.observer.reset();
        },


        // company

        getPhoneEvent(event){
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
            this.company_type = null;
            this.company_location = null;
            // this.administrative_company = '';
            // this.city_company = '';
            // this.address_company = '';
            // this.bio = '';
            // this.website = '';
        },

        // getting_data_by_cif(inc_company){
        //     if(inc_company){
        //         this.company_name = inc_company.denumire;
        //         this.cui = inc_company.cif;
        //         this.register_number = inc_company.numar_reg_com;
    
        //         this.phone = inc_company.telefon;

        //         this.year_made = this.extract_data(inc_company.stare);
        //         // this.year_made = inc_company.stare;
        //         this.address_company = inc_company.adresa;
        //         this.extract_city(this.address_company);
        //         this.administrative_company = inc_company.judet;
        //         this.postal_code = inc_company.cod_postal;
        //     }
        // },

        extract_data(register_data){
            let samples = register_data.split("INREGISTRAT din data ");
            let only_data = samples[1];
            let result = only_data.split(' ');
            return result[2];
        },


        extract_city(address){
            let parts = address.split('-,');
            this.city_company = parts[1];
        },

    },

    created(){
    
    }
}
</script>

<style scoped>

</style>