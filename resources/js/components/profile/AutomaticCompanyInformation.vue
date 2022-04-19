<template>
    <div>
    <h4 class="my-4">Cautati firma folosind una din cele 2 metode: Denumire sau CIF.</h4>
    <div class="tab_wrapper first_tab my-4">
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
    </div>





        <div class="mt-5">
            <h3>Informatii despre companie</h3>
            <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="company_name">Denumire firma</label>
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : invalid, 'is-valid': passed}" v-model="company_name">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="year_made">An infiintare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="year_made" name="year_made" placeholder="2002" v-model="year_made">
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
                        <validation-provider rules="required|integer|min:0" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="workers" name="workers" placeholder="10" v-model="workers">
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
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="numeric" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="12345678" v-model="cui">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="register_number">Numar Inmatriculare</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J28/123/2008" v-model="register_number">
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
            </div>

            <div class="form-group">
                <label for="address_company">Adresa</label>
                <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                    <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="address_company" name="address_company" placeholder="Strada Exemplului Numar 10" v-model="address_company">
                    <span class="text-danger">{{ errors[0] }}</span>
                    <span class="text-danger" v-if="validationErrors.hasOwnProperty('address')">{{ validationErrors.address[0] }}</span>
                </validation-provider>
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Descriere companie</label>
                <textarea name="bio" class="form-control" rows="6" placeholder="Scurta descriere a companiei..." v-model="bio"></textarea>
            </div>

            <div class="form-group">
                <label for="website" class="form-label">Site internet</label>
                <input type="text" class="form-control" name="website" placeholder="www.website.ro" v-model="website">
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-success mt-1">Salveaza informatii</button>
                <a class="btn btn-default mt-1" @click="reset_information">Reseteaza datele</a>
            </div>
        </form>
        </ValidationObserver>
    </div>
    
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
    name: "AutomaticCompanyInformation",

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
            cif: null,
            company_name: null,
            year_made: null,
            phone: null,
            workers: null,
            cui: null,
            register_number: null,
            administrative_company: null,
            city_company: null,
            address_company: null,
            bio: null,
            website: null,

            // company_name: this.company_info.name,
            // year_made: this.company_info.year_made,
            // phone: this.company_info.phone,
            // workers: this.company_info.workers,
            // cui: this.company_info.cui,
            // register_number: this.company_info.register_number,
            // administrative_company: this.company_info.administrative,
            // city_company: this.company_info.city,
            // address_company: this.company_info.address,
            // bio: this.company_info.bio,
            // website: this.company_info.website,


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

        getPhoneEvent(event){
            console.log('Eveniment telefon: ', event);
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },


        reset_information(){
            this.company_name = this.company_info.name;
            this.year_made = this.company_info.year_made;
            this.phone = this.company_info.phone;
            this.workers = this.company_info.workers;
            this.cui = this.company_info.cui;
            this.register_number = this.company_info.register_number;
            this.administrative_company = this.company_info.administrative;
            this.city_company = this.company_info.city;
            this.address_company = this.company_info.address;
            this.bio = this.company_info.bio;
            this.website = this.company_info.website;
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
            axios.post('/api/profile/company/save', information).then(response => {
                // this.success_status = true;
                // setTimeout(function(){
                //     self.closeSuccessAlert();
                // }, 40000)

                this.$swal(
                    'Datele au fost salvate.',
                    'Informatiile despre firma au fost salvate cu succes.',
                    'success'
                );

                console.log(response.data);
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


            console.log('formular trimis.');
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
                'Access-Control-Allow-Origin': '*',
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
        }
    },


    created(){
        this.company_name = this.company_info.name;
        this.year_made = this.company_info.year_made;
        this.phone = this.company_info.phone;
        this.workers = this.company_info.workers;
        this.cui = this.company_info.cui;
        this.register_number = this.company_info.register_number;
        this.administrative_company = this.company_info.administrative;
        this.city_company = this.company_info.city;
        this.address_company = this.company_info.address;
        this.bio = this.company_info.bio;
        this.website = this.company_info.website;
    }
}
</script>

