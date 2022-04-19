<template>
    <div>
        <div class="row" v-if="company_info">
            <div class="col-lg-6">
                <p>Denumire firma: <strong v-if="company_name">{{ company_name }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Infiintare firma: <strong v-if="year_made">{{ year_made }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Cod Unic de Inregistrare: <strong v-if="cui">{{ cui }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Numar inregistrare firma: <strong v-if="register_number">{{ register_number }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Numar de telefon: <strong v-if="phone">{{ phone }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Numar de angajati: <strong v-if="workers">{{ workers }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Judet: <strong v-if="administrative_company">{{ administrative_company }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Oras: <strong v-if="city_company">{{ city_company }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Adresa: <strong v-if="address_company">{{ address_company }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-6">
                <p>Site internet: <strong v-if="website">{{ website }}</strong><strong v-else>-</strong></p>
            </div>
            <div class="col-lg-12">
                <button class="btn btn-info" @click.prevent="openModal">Modifica informatii</button>
            </div>
            
        </div>
        <div v-else>
            <p class="text-center">Nu aveti nicio informatie despre firma. Va recomandam sa completati acest profil de firma. <button class="btn btn-info btn-sm" @click.prevent="openModal">Completeaza informatii</button></p>
        </div>

        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Informatii firma">

            <div class="alert alert-info" role="alert">
                <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> Nota. Asigurati-va ca <strong>datele introduse</strong> in formular sunt corecte.
            </div>
    
            <GetCompanyByCIF @getting_data_by_cif="getting_data_by_cif" />

            <div class="mt-5">
                <h4>Informatii despre companie</h4>
                <ValidationObserver v-slot="{ handleSubmit, invalid }">
                <form @submit.prevent="handleSubmit(onSubmit)" :key="form_key">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="company_name">Denumire firma</label>
                            <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed }">
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : invalid, 'is-valid': passed}" v-model="__company_name">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="year_made">An infiintare</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="number" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="year_made" name="year_made" placeholder="2002" v-model="__year_made">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('year_made')">{{ validationErrors.year_made[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                        <label>Numar de telefon</label>
                            <VuePhoneNumberInput v-model="__phone" 
                            :default-country-code="'RO'" 
                            :translations="translations" 
                            :valid-color="'yellowgreen'" 
                            :error-color="'orangered'"
                            :required="true"
                            :error="phone_error"
                            @update="getPhoneEvent($event)" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="workers">Numar angajati</label>
                            <validation-provider rules="required|integer|min:0" v-slot="{ errors, invalid, passed }">
                                <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="workers" name="workers" placeholder="10" v-model="__workers">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('workers')">{{ validationErrors.workers[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="cui">Cod Unic de Inregistrare (CUI)</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="12345678" v-model="__cui">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="register_number">Numar Inmatriculare</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J28/123/2008" v-model="__register_number">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('register_number')">{{ validationErrors.register_number[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="administrative_company">Judet</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="administrative_company" name="administrative_company" placeholder="Olt" v-model="__administrative_company">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('administrative')">{{ validationErrors.administrative[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="city_company">Oras</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="city_company" name="city_company" placeholder="Corabia" v-model="__city_company">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('city')">{{ validationErrors.city[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="address_company">Adresa</label>
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                                <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" v-model="__address_company">
                                <span class="text-danger">{{ errors[0] }}</span>
                                <span class="text-danger" v-if="validationErrors.hasOwnProperty('address')">{{ validationErrors.address[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="website" class="form-label">Site internet</label>
                            <input type="text" class="form-control" name="website" placeholder="www.website.ro" v-model="__website">
                        </div>
                    </div>
                </div>

                


                <div class="card-footer">
                    <button class="btn btn-success mt-1 btn-disabled" disabled="disabled" v-if="invalid">Salveaza informatii</button>
                    <button type="submit" class="btn btn-success mt-1" v-else>Salveaza informatii</button>
                </div>
            </form>
            </ValidationObserver>
        </div>

        </b-modal>
    
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


export default {
    name: "CifCompanyInformation",

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
            form_key: 'form_key',
            modalShow: false,
            cif: null,
 
            __company_name:null,
            __year_made:null,
            __phone:null,
            __workers:null,
            __cui:null,
            __register_number:null,
            __administrative_company:null,
            __city_company:null,
            __address_company:null,
            __bio:null,
            __website:null,

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
        company_info: Object
    },


    methods: {

        openModal: function(){
            this.modalShow = !this.modalShow;

            this.__company_name = this.company_name;
            this.__year_made = this.year_made;
            this.__phone = this.phone;
            this.__workers = this.workers;
            this.__cui = this.cui;
            this.__register_number = this.register_number;
            this.__administrative_company = this.administrative_company;
            this.__city_company = this.city_company;
            this.__address_company = this.address_company;
            this.__bio = this.bio;
            this.__website = this.website;

        },

        getPhoneEvent(event){
            console.log('Eveniment telefon: ', event);
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },



        



        onSubmit(){

            // create the object
            let information = {
                name: this.__company_name,
                year_made: this.__year_made,
                phone: this.__phone,
                workers: this.__workers,
                cui: this.__cui,
                register_number: this.__register_number,
                administrative: this.__administrative_company,
                city: this.__city_company,
                address: this.__address_company,
                bio: this.__bio,
                website: this.__website
            }

            // call axios

            let self = this;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/profile/company/save', information).then(response => {
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

                this.$swal(
                    'Datele au fost salvate.',
                    'Informatiile despre firma au fost salvate cu succes.',
                    'success'
                );

                this.modalShow = false;
            }).catch(error => {
                // this.error_status = true;

                // setTimeout(function(){
                //     self.closeErrorAlert();
                // }, 40000)

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
       
                this.__company_name = inc_company.denumire;
                this.__cui = inc_company.cif;
                this.__register_number = inc_company.numar_reg_com;
    
                this.__phone = inc_company.telefon;
                // if(inc_company.telefon){
                //     // this.__valid_phone = true;
                // }
    
    
                this.__year_made = this.extract_data(inc_company.stare);
                // this.__year_made = inc_company.stare;
                this.__address_company = inc_company.adresa;
                this.extract_city(this.__address_company);
                this.__administrative_company = inc_company.judet;
                this.__postal_code = inc_company.cod_postal;

                this.cif = null;

                this.form_key += 1;

            }
        }
    }
}
</script>

