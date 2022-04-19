<template>
<div>
    <div class="alert alert-warning" role="alert" v-if="registration && registration.status == 1">
        <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> Datele despre firma au fost trimise si sunt in curs de verificare. Veti primi un raspuns imediat ce sunt verificate de un moderator.
    </div>

    <div class="alert alert-danger" role="alert" v-if="registration && registration.status == 2">
        <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> <strong>Respins.</strong> Am constatat ca datele trimise sunt invalide. Corectati datele si retrimiteti pentru activarea contului.
        <p class="text-small pl-3" v-if="registration.message"><strong>Motiv respingere: </strong> {{ registration.message }}</p>
    </div>

    <template v-if="registration && registration.status != 1">
        <div class="alert alert-info" role="alert">
            <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> Nota. Asigurati-va ca <strong>datele introduse</strong> in formular sunt corecte.
        </div>
    <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" />

        <div class="mt-5">
            <h4>Informatii despre companie</h4>
            <hr>
            <ValidationObserver v-slot="{ handleSubmit, invalid }">
            <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="company_name">Denumire firma</label>
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="company_name">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="year_made">An infiintare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="number" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="year_made" name="year_made" placeholder="2002" v-model="year_made">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('year_made')">{{ validationErrors.year_made[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                    <label>Numar de telefon</label>
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
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="workers">Numar angajati</label>
                        <validation-provider rules="required|integer|min:0" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="workers" name="workers" placeholder="10" v-model="workers">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('workers')">{{ validationErrors.workers[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="cui">Cod Unic de Inregistrare (CUI)</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="cui" name="cui" placeholder="12345678" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Numar Inmatriculare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J28/123/2008" v-model="register_number">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('register_number')">{{ validationErrors.register_number[0] }}</span>
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
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('administrative')">{{ validationErrors.administrative[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="city_company">Oras</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="city_company" name="city_company" placeholder="Corabia" v-model="city_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('city')">{{ validationErrors.city[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="address_company">Adresa</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid && touched, 'is-valid': passed}" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" v-model="address_company">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('address')">{{ validationErrors.address[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="website">Site internet</label>
                        <input type="text" class="form-control" name="website" placeholder="www.website.ro" v-model="website">
                    </div>
                </div>
            </div>

            


            <div class="card-footer">
                <!-- <button class="btn btn-success mt-1 btn-disabled" disabled="disabled" v-if="invalid">Salveaza informatii</button> -->
                <button type="submit" class="btn btn-success mt-1">Salveaza informatii</button>
            </div>
        </form>
        </ValidationObserver>
    </div>
    </template>
</div>
</template>


<script>

import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';


import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';

import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

import GetCompanyByCIF from "./Components/GetCompanyByCIF";
import GetCompanyByName from "./Components/GetCompanyByName";


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

extend('phone_rule', {
    message: "Numar de telefon invalid.",
    validate: value => {
        return "Numarul de telefon nu este valid."
    }
});


export default {
    name: "InactiveUserCompanyInformation",

    components: {
        VuePhoneNumberInput,
        ValidationProvider,
        ValidationObserver,
        MoonLoader,
        GetCompanyByName,
        GetCompanyByCIF
    },


    data(){
        return {
            modalShow: false,
            cif: null,

            registration: null,


            company_name: this.company_info ? this.company_info.name : null,
            year_made: this.company_info ? this.company_info.year_made : null,
            phone: this.company_info ? this.company_info.phone : null,
            workers: this.company_info ? this.company_info.workers : null,
            cui: this.company_info ? this.company_info.cui : null,
            register_number: this.company_info ? this.company_info.register_number : null,
            administrative_company: this.company_info ? this.company_info.administrative : null,
            city_company: this.company_info ? this.company_info.city : null,
            address_company: this.company_info ? this.company_info.address : null,
            bio: this.company_info ? this.company_info.bio : null,
            website: this.company_info ? this.company_info.website : null,


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

            only_countries: ['RO', 'BE', 'DE', 'FR', 'IT', 'GB', 'AT', 'BG', 'MD', 'ES', 'GR', 'IS', 'LU', 'NL', 'PT', 'LE', 'SI', 'SK']
        }
    },


    props: {
        company_info: Object,
        the_registration: Object
    },


    methods: {



        getPhoneEvent(event){
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },


        onSubmit(){

            // create the object
            let information = {
                name: this.company_name,
                year_made: this.year_made,
                phone: this.phone,
                workers: this.workers,
                cui: this.cui,
                register_number: this.register_number,
                administrative: this.administrative_company,
                city: this.city_company,
                address: this.address_company,
                bio: this.bio,
                website: this.website
            }

            // call axios

            let self = this;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/profile/company/inactive', information).then(response => {
                // this.success_status = true;
                // setTimeout(function(){
                //     self.closeSuccessAlert();
                // }, 40000)

                this.company_name = response.data.company.name;
                this.year_made = response.data.company.year_made;
                this.phone = response.data.company.phone;
                this.workers = response.data.company.workers;
                this.cui = response.data.company.cui;
                this.register_number = response.data.company.register_number;
                this.administrative_company = response.data.company.administrative;
                this.city_company = response.data.company.city;
                this.address_company = response.data.company.address;
                this.bio = response.data.company.bio;
                this.website = response.data.company.website;

                this.registration = response.data.registration;

                this.$swal(
                    'Datele au fost trimise catre validare.',
                    'Informatiile despre firma vor fi verificate de un moderator. Veti primi un raspuns in momentul acceptarii sau respingerii informatiilor.',
                    'success'
                );

            }).catch(error => {

                this.$swal(
                    'Am intampinat erori.',
                    'Ceva nu a functionat bine, incercati mai tarziu.',
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
            let only_data = samples[1];
            let result = only_data.split(' ');
            return result[2];
        },


        extract_city(address){
            let parts = address.split('-,');
            this.city_company = parts[1];
        },


        getting_data_by_cif: function(inc_company){

            if(inc_company !== null){
       
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
        }
    },

    created(){
        this.registration = this.the_registration;
    }
}
</script>

