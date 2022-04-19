<template>
  <div class="col-lg-12 my-5">

      <div class="card" :class="{'card-collapsed' : has_invoice_information}">
        <div class="card-header">
            <h3 class="card-title" v-if="has_invoice_information">Date de facturare.</h3>
            <h3 class="card-title" v-else>Completează datele de facturare.</h3>
            <div class="card-options">
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="mt-5" v-if="!isLoading">

                <div class="alert alert-info" role="alert">
                    <i class="fa fa-exclamation mr-2" aria-hidden="true"></i> Notă. Completează datele de facturare. Asigură-te că <strong>informațiile introduse</strong> în formular sunt corecte.
                </div>

                <div>
                    <b-form-group label="Selectează modul de facturare" v-slot="{ ariaDescribedby }">
                        <b-form-radio-group
                            v-model="selected_mode"
                            :options="options_mode"
                            :aria-describedby="ariaDescribedby"
                            name="invoice-mode"
                            stacked
                        ></b-form-radio-group>
                    </b-form-group>
                </div>

                <ValidationObserver v-slot="{ handleSubmit }" ref="observer" v-if="selected_mode == 'company'">
                    <form @submit.prevent="handleSubmit(onSubmitCompany)">

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="last_name">Nume</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" required class="form-control" id="last_name" name="last_name" placeholder="Popescu" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="last_name">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('last_name')">{{ validationErrors.name[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="first_name">Prenume</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" required class="form-control" id="first_name" name="first_name" placeholder="Andrei" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="first_name">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('first_name')">{{ validationErrors.name[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                        </div>

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
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="company_name">
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
                                <p style="font-size: 16px;margin-top: 6px;"><i class="fa fa-map-marker"></i> {{ company_location }} <button class="btn btn-sm btn-warning" @click.prevent="editLocationModal">Modifică</button></p>
                                </div>
                                </div>
                                <div class="form-group" v-else>
                                <label>Adresă firmă (sediu)</label>
                                <!-- <company-location-component  :cached="{}" @location:selected="location_selected"></company-location-component> -->
                                <google-location @location:selected="location_selected"></google-location>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="cui">Cod Unic de Înregistrare (CUI)</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="numeric" class="form-control" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" id="cui" name="cui" placeholder="RO00000" v-model="cui">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('cui')">{{ validationErrors.cui[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="register_number">Număr Înmatriculare (Registrul Comerțului)</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" class="form-control" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" id="register_number" name="register_number" placeholder="J00/000/2020" v-model="register_number">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('register')">{{ validationErrors.register[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                        </div>


                        
                        <div class="">
                            <button type="submit" class="btn btn-info mt-1" v-if="!savingInfo">Salvează informații</button>
                            <b-button variant="info" disabled v-else>
                                <b-spinner type="grow"></b-spinner>
                                Se salvează...
                            </b-button>
                            <!-- <a class="btn btn-default mt-1" @click="reset_information">Reseteaza datele</a> -->
                        </div>
                    </form>
                </ValidationObserver>

                <ValidationObserver v-slot="{ handleSubmit }" ref="observer" v-else>
                    <form @submit.prevent="handleSubmit(onSubmitIndividual)">

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="last_name">Nume</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" required class="form-control" id="last_name" name="last_name" placeholder="Popescu" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="last_name">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('last_name')">{{ validationErrors.name[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="first_name">Prenume</label>
                                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                                        <input type="text" required class="form-control" id="first_name" name="first_name" placeholder="Andrei" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" v-model="first_name">
                                        <span class="text-danger">{{ errors[0] }}</span>
                                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('first_name')">{{ validationErrors.name[0] }}</span>
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
                                <label>Adresă</label>
                                <p style="font-size: 16px;margin-top: 6px;"><i class="fa fa-map-marker"></i> {{ company_location }} <button class="btn btn-sm btn-warning" @click.prevent="editLocationModal">Modifică</button></p>
                                </div>
                                </div>
                                <div class="form-group" v-else>
                                <label>Adresă</label>
                                <!-- <company-location-component  :cached="{}" @location:selected="location_selected"></company-location-component> -->
                                <google-location @location:selected="location_selected"></google-location>
                                </div>
                            </div>
                        </div>
                        
                        <div class="">
                            <button type="submit" class="btn btn-info mt-1" v-if="!savingInfo">Salvează informații</button>
                            <b-button variant="info" disabled v-else>
                                <b-spinner type="grow"></b-spinner>
                                Se salvează...
                            </b-button>
                        </div>
                    </form>
                </ValidationObserver>
            </div>

            <div class="d-flex justify-content-center mb-3" v-else>
                <b-card>
                    <b-skeleton animation="wave" width="85%"></b-skeleton>
                    <b-skeleton animation="wave" width="55%"></b-skeleton>
                    <b-skeleton animation="wave" width="70%"></b-skeleton>
                </b-card>
            </div>
        </div><!-- end card body -->
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


import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';


import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';


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
 name: "InvoiceInformationComponent",
 components: {
        VuePhoneNumberInput,
        ValidationProvider,
        ValidationObserver,
        GoogleLocation
    },


    data(){
        return {

            has_invoice_information: false,

            selected_mode: 'company',
            options_mode: [
                { text: 'Persoană juridică', value: 'company' },
                { text: 'Persoană fizică', value: 'individual' },
            ],

            savingInfo: false,
            locationModal: false,
            isLoading: false,
            hasCompany: false,
            cif: null,

            first_name: '',
            last_name: '',

            company_name: '',
            year_made: '',
            phone: '',
            workers: '',
            cui: '',
            register_number: '',
            administrative_company: '',
            city_company: '',
            address_company: '',
     

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
            this.valid_phone = event.isValid;
            this.phone_error = !event.isValid;
        },


        location_selected: function(inc_location){
            this.company_location = inc_location.value;
        },



        onSubmitCompany(){
            this.savingInfo = true;
            // create the object
            let information = {
                type: this.selected_mode,
                first_name: this.first_name,
                last_name: this.last_name,
                phone: this.phone,
                company_name: this.company_name,
                company_type: this.company_type,
                cui: this.cui,
                number: this.register_number,
                address: this.company_location
            }



            // axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            // axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/invoice/information/save/company', information).then(response => {

                Vue.$toast.open({
                    message: 'Datele de facturare au fost salvate cu succes.',
                    type: 'success',
                    duration: 6000,
                    position: 'bottom'
                });

                this.$refs.observer.reset();
                this.validationErrors = [];
                this.has_invoice_information = true;

             
            }).catch(error => {

                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });


                if (error.response.status == 401){
                    this.validationErrors = error.response.data.errors;
                    // console.log(error.response.data.errors);
                }

                this.has_invoice_information = false;
            }).finally(() => {
                this.savingInfo = false;
                this.$emit('has_invoice_information', this.has_invoice_information);
            });
        },

        onSubmitIndividual(){
            this.savingInfo = true;
            // create the object
            let information = {
                type: this.selected_mode,
                first_name: this.first_name,
                last_name: this.last_name,
                phone: this.phone,
                address: this.company_location
            }



            // axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            // axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/invoice/information/save/individual', information).then(response => {

                Vue.$toast.open({
                    message: 'Datele de facturare au fost salvate cu succes.',
                    type: 'success',
                    duration: 6000,
                    position: 'bottom'
                });

                this.$refs.observer.reset();
                this.validationErrors = [];
                this.has_invoice_information = true;

             
            }).catch(error => {

                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });


                if (error.response.status == 401){
                    this.validationErrors = error.response.data.errors;
                    // console.log(error.response.data.errors);
                }

                this.has_invoice_information = false;
            }).finally(() => {
                this.savingInfo = false;
                this.$emit('has_invoice_information', this.has_invoice_information);
            });
        },



        initInvoiceInformation: async function(){
            this.isLoading = true;
            // axios
            await axios.get('/api/invoice/information/get/current').then(response => {
                        // console.log('/api/admin/users/get/company este', response.data);

                        if(response.data.information == null || response.data.information == 'null') {
                            this.has_invoice_information = false;
                        } else if(response.data.information){
                            this.has_invoice_information = true;
                            this.selected_mode = response.data.information.type;
                            this.first_name = response.data.information.first_name;
                            this.last_name = response.data.information.last_name;
                            this.company_type = response.data.information.company_type;
                            this.company_name = response.data.information.name;
                            this.phone = response.data.information.phone;
                            this.cui = response.data.information.cui;
                            this.register_number = response.data.information.number;
                            this.company_location = response.data.information.address;
                        } 

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat.',
                            type: 'error',
                            duration: 6000,
                            position: 'bottom'
                        });
                        this.has_invoice_information = false;
                    }).finally(() => {
                        this.isLoading = false;
                        this.$emit('has_invoice_information', this.has_invoice_information);
                    });

        }
    },

    created(){

        this.initInvoiceInformation();
    }

}
</script>