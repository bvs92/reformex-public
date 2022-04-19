<template>
 
    <div v-if="getReady">
        <b-progress :value="currentStep" max="4" show-value class="mb-3"></b-progress>
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }" >
        <form id="register_demand" @submit.prevent="handleSubmit(onSubmit)" class="mt-6">
        <!-- <form id="register_demand" @submit.prevent="onSubmit"> -->

            
            <ValidationObserver ref="step1" v-if="currentStep === 1" v-slot="{  }">

            <div class="form-group d-flex justify-content-center my-8">
                <!-- <label for="city">Oras</label>
                <input type="search" id="address-input" class="form-control" placeholder="Care este orasul proiectului?" name="city" required>
                <p class="small text-danger" id="city-error-form"></p> -->
                <div class="col-lg-8">
                    <p class="h4"><i class="fa fa-caret-right"></i> Indică locația</p>
                    <PlacesComponent ref="PlacesComponent" @location:selected="selectedLocation" @location:cached="cachedLocation" :cached="cached_location" />
                    <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Adaugă locația proiectului: oraș, localitate sau cod poștal.</small></p>
                </div>
            </div>
            

            <div class="form-group d-flex justify-content-center my-8">
                <!-- <label for="city">Oras</label>
                <input type="search" id="address-input" class="form-control" placeholder="Care este orasul proiectului?" name="city" required>
                <p class="small text-danger" id="city-error-form"></p> -->
                <div class="col-lg-8">
                    <p class="h4"><i class="fa fa-caret-right"></i> Selectează categoriile</p>
                    
                    <CategoriesComponent ref="CategoriesComponent" @categories:selected="selectedCategories" @categories:cached="cachedCategories" :cached="cached_categories" />
                    <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Selectează minim o categorie în care se încadrează proiectul tău.</small></p>
                </div>
            </div>

            <!-- <div class="col-lg-12 d-flex justify-content-center"> -->
                <!-- <a v-if="currentStep !== 1" type="button" class="btn btn-sm btn-default" @click="goStepBack()">Inapoi</a> -->
                <!-- <a v-if="currentStep !== 4" :disabled="invalid" class="btn btn-sm btn-primary" @click="goStepForward()">Continua</a> -->
            <!-- </div> -->

            </ValidationObserver>
            

            
            <ValidationObserver ref="step2" v-if="currentStep === 2">

            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                <label for="complete_name"><i class="fa fa-caret-right"></i> Numele și Prenume</label>
                <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                    <input type="text" 
                    class="form-control" 
                    :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                    id="complete_name" 
                    v-model="complete_name"
                    placeholder="Nume și Prenume" 
                    name="complete_name">
                    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                </validation-provider>
                </div>
            </div>
            

            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                <label for="email"><i class="fa fa-caret-right"></i> Adresă de e-mail</label>
                <validation-provider rules="required|email" v-slot="{ errors, invalid, passed, touched }">
                    <input type="email" class="form-control" 
                    :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                    id="email" 
                    placeholder="exemplu@email.com" name="email"
                    v-model="email"
                    >
                    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                </validation-provider>
                <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Folosește o adresă de e-mail corectă pentru a primi informații cu privire la starea proiectului.</small></p>
                </div>
            </div>

            <!-- <div v-if="block_information" class="block-infos">
                <div class="d-flex justify-content-center mt-5">
                    <button class="btn btn-default btn-sm" @click.prevent="modifyStep2">Modifica telefon</button>
                </div>
            </div> -->
        
            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                <label for="phone">
                    <i class="fa fa-caret-right"></i> Număr de telefon
                    <button v-if="block_information" class="btn btn-default btn-sm" @click.prevent="modifyStep2">Modifică telefon</button>
                </label>
                <validation-provider :rules="{ required: true, phone_rule: !phone_error }" v-slot="{ errors, valid }">
                <VuePhoneNumberInput v-model="phoneNumber" 
                    :default-country-code="'RO'" 
                    :translations="translations" 
                    :valid-color="'yellowgreen'" 
                    :error-color="'orangered'"
                    :required="false"
                    :error="!phone_error"
                    @update="getPhoneEvent($event)" 
                    :disabled="block_information"
                />
                <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Introdu un număr de telefon valid. Confirmă prin codul primit.</small></p>
                <hr>
                <div v-show="phone_error">
                    <PhoneVerificationComponent ref="phoneVerification" 
                    :phone_number="formatted_number" 
                    :code_request="code_request" 
                    :phone_verified="phone_verified" 
                    @phone:verified="phoneVerified" 
                    @block:infos="blockInformation" 
                    @code:request="codeRequest" />
                </div>
                </validation-provider>
                </div>
            </div>
            </ValidationObserver>



            
            <ValidationObserver ref="step3" v-if="currentStep === 3">
            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                <label for="subject"><i class="fa fa-caret-right"></i> Subiect cerere</label>
                <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                    <input type="text" 
                    v-model="subject" 
                    class="form-control" 
                    :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                    id="subject" placeholder="Subiect cerere" name="subject">
                    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                </validation-provider>
                <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Atenție: nu adăuga numărul de telefon sau adresa de e-mail în această secțiune.</small></p>
                </div>
            </div>
            

            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                <label for="message"><i class="fa fa-caret-right"></i> Descrie cât mai clar proiectul</label>
                <validation-provider rules="required|min:10" v-slot="{ errors, passed }">
                    <textarea 
                    class="form-control" 
                    :class="{'is-invalid' : !passed, 'is-valid': passed}"
                    name="message" id="message" 
                    v-model="description"
                    cols="30" rows="10"
                    ></textarea>
                    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                </validation-provider>
                <p class="text-muted"><small><i class="fa fa-info-circle mr-2"></i> Atenție: nu adăuga numărul de telefon sau adresa de e-mail în această secțiune.</small></p>
                </div>
            </div>


            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                    <files-upload-public-demand @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent"></files-upload-public-demand>
                </div>
            </div>

            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                    <AttachmentsUploadComponent @files:selected="attachmentsSelected" @files:removed="attachmentsRemoved" ref="attachmentsUploadComponent"/>
                </div>
            </div>
            
      
            </ValidationObserver>    

            <ValidationObserver ref="step5" v-if="currentStep === 4">
            <div class="form-group d-flex justify-content-center my-8">
                <div class="col-lg-8">
                    <h4 class="h4 my-3">Previzualizare informații cerere</h4>
                    <p>Nume: <strong>{{ complete_name }}</strong></p>
                    <p>Număr telefon: <strong>{{ phone.phoneNumber }}</strong></p>
                    <p>Adresă e-mail: <strong>{{ email }}</strong></p>
                    <hr>
                    <p>Locație: <strong>{{ _location.value }}</strong></p>
                    <p>Categorii: <strong v-for="(category, index) in _selectedCategories" :key="'categorie-' + category.id">{{ category.name }} <span v-if="index != _selectedCategories.length - 1"> | </span></strong></p>
                    <p>Subiect cerere: <strong>{{ subject }}</strong></p>
                    <p>Descriere proiect</p>
                    <p>{{ description }}</p>

                    <template v-if="files && files.length > 0">
                        <hr>
                        <SelectedFilesPreview :type="'images'" />
                    </template>

                    <template v-if="attachments && attachments.length > 0">
                        <hr>
                        <SelectedFilesPreview :type="'attachments'" />
                    </template>
                </div>
            </div>
        

            <div class="form-group d-flex justify-content-center my-4">
                <div class="col-lg-8">
                <validation-provider rules="required" v-slot="{ errors, passed }">
                    <b-form-checkbox
                        id="checkbox-1"
                        v-model="accept_terms"
                        name="checkbox-1"
                    >
                    Acceptă <a @click.prevent="showTermsModal = !showTermsModal" class="terms-class">termenele și condițiile Reformex</a> pentru a trimite cererea.
                    </b-form-checkbox>
                    <span class="small text-danger">{{ errors[0] }}</span>
                </validation-provider>
                </div>
            </div>
            
        
            <div class="form-group d-flex justify-content-center">
                <div class="col-lg-8">
                <button v-if="!btnLoading" type="submit" class="btn btn-success btn-block" :disabled="!accept_terms"><i class="fa fa-send-o"></i> Trimite cererea</button>
                <button v-else type="button" class="btn btn-success btn-loading btn-block" disabled="disabled">În curs de trimitere</button>
                </div>
            </div>

            <b-modal v-model="showTermsModal" id="modal-scrollable" ok-only scrollable title="Termene și condiții Reformex">
                <p class="my-4">
                Acceptand termenii si conditiile REFORMEX va dati acordul ca datele introduse in formularul curent sa fie utilizate de catre REFORMEX pentru a gasi profesionisti pentru proiectul dvs.
                Profesionistii din platforma REFORMEX sunt firme verificate din domeniul constructiilor si vor avea acces la informatiile dvs. prin intermediul platformei noastre.
                </p>
                <p class="my-4">
                REFORMEX nu va folosi datele dvs. in alte scopuri ci doar pentru activitatile sus mentionate. De asemenea, aveti posibilitatea de a elimina cererea lansata oricand credeti ca se cuvine.
                </p>
                <p class="my-4">
                In mod automat, cererea dvs. va fi marcata ca inactiva atunci cand un numar de maxim 3 utilizatori tip PROFESIONIST va vor accesa cererea. Acesti profesionisti vor fi listati in pagina cu cererea dvs. care va fi generata automat dupa trimiterea mesajului.
                </p>
            </b-modal>
            </ValidationObserver> 

            

        </form>

        </ValidationObserver>

        <div class="row mb-6">
            <!-- <div class="col-lg-12 d-flex justify-content-center py-4">
                <h4>Pasul {{ currentStep }}</h4>
            </div> -->

            <div class="col-lg-12 d-flex justify-content-around" v-if="!btnLoading">
                <a v-if="currentStep !== 1" class="btn btn-lg btn-default" @click.prevent="goStepBack()"><i class="fa fa-arrow-left"></i> Înapoi</a>
                <a v-if="currentStep == 2 && phone_verified" class="btn btn-lg btn-info text-white" @click.prevent="goStepForward()">Continuă <i class="fa fa-arrow-right"></i></a>
                <!-- <a v-if="currentStep == 2 && phone_verified == false" class="btn btn-lg btn-info" disabled="disabled">Continua</a> -->
                <a v-if="currentStep !== 4 && currentStep !== 2" class="btn btn-lg btn-info text-white" @click.prevent="goStepForward()">Continuă <i class="fa fa-arrow-right"></i></a>
            </div>

            <div v-if="validation_errors && validation_errors.length > 0" class="col-lg-8 my-6 offset-2">
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true" @click.prevent="deleteValidationErrors">×</button>
                    <h4 class="alert-heading"><strong>Corectează urmatoarele erori pentru a continua. Du-te înapoi.</strong></h4>
                    <ul>
                        <li v-for="(error, index) in validation_errors" :key="'error-' + index">
                            {{ error[0] }}
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>

        
    </div>
    <div class="text-center" v-else>
    <b-spinner label="Spinning"></b-spinner>
    </div>


</template>

<script>
import AttachmentsUploadComponent from "./AttachmentsUploadComponent.vue";
import FilesUploadPublicDemand from "./FilesUploadPublicDemand.vue";

import PlacesComponent from './_modules/PlacesComponent.vue';
import CategoriesComponent from './_modules/CategoriesComponent.vue';

import PhoneVerificationComponent from './_modules/PhoneVerificationComponent.vue';
import SelectedFilesPreview from './_modules/SelectedFilesPreview.vue';

import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, email, integer, min_value, min, length, image, size } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Informația este obligatorie.'
});

extend('image', {
  ...image,
  message: 'Sunt acceptate doar imagini.'
});

extend('size', {
  ...size,
  message: 'Fișierul nu trebuie sa depășească 10MB.'
});

extend('email', {
  ...email,
  message: 'Adresa de e-mail este invalidă.'
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
  message: 'Lungimea minimă acceptată este {length} caractere.'
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
    name: "RegisterDemandComponent",

    components: {
        PlacesComponent,
        CategoriesComponent,
        VuePhoneNumberInput,
        ValidationProvider,
        ValidationObserver,
        FilesUploadPublicDemand,
        AttachmentsUploadComponent,

        PhoneVerificationComponent,
        SelectedFilesPreview
    },

    data(){
        return {
            getReady: false,
            accept_terms: false,
            showTermsModal: false,


            phone_verified: false,
            block_information: false,
            code_request: false,
            cached_location: {},
            cached_categories: [],
            currentStep: 1,
            validation_errors: null,

            files: null,
            attachments: null,

            demand: {},
            description: '',
            complete_name: '',
            subject: '',
            email: null,
            _selectedCategories: [],
            _location: {},
            
            phoneNumber: null,
            formatted_number: null,
            phone: {},

            phone_error: null,
            valid_phone: false,

            btnLoading: false,

            translations: {
                countrySelectorLabel: 'Codul tarii',
                countrySelectorError: 'Selectari tara',
                phoneNumberLabel: 'Numarul de telefon',
                example: 'Exemplu: '
            },

            only_countries: ['RO', 'BE', 'DE', 'FR', 'IT', 'GB', 'AT', 'BG', 'MD', 'ES', 'GR', 'IS', 'LU', 'NL', 'PT', 'LE', 'SI', 'SK']
        }
    },




    

    methods: {

        scrollToTop: function(){
            window.scrollTo(0,0);
        },

        phoneVerified: function(){
            this.phone_verified = true;
        },

        blockInformation: function(){
            this.block_information = true;
        },

        codeRequest: function(_incoming){
            this.code_request = _incoming;
        },

        cachedLocation(selectedData){
            this.cached_location = selectedData;
        },

        cachedCategories(selectedData){
            this.cached_categories = selectedData;
        },

        modifyStep2: function(){
            this.block_information = false;
            this.phone_verified = false;
            this.code_request = false;
            this.$refs.phoneVerification.resetAll();
        },

        goStepForward () {
            // if (this.currentStep === 3) {
            //     alert('Form submitted!');
            //     return;
            // }

            if (this.currentStep === 1) {
                this.$refs.step1.validate().then(success => {
                    if (!success) {
                        return;
                    }
                    this.currentStep++;
                    // this.$refs.step2.reset();
                    // this.$refs.observer.reset();
                });
            } else if (this.currentStep === 2){
                this.$refs.step2.validate().then(success => {
                    if (!success) {
                        return;
                    }

                    this.currentStep++;
                    this.scrollToTop();
                    // this.$refs.step3.reset();
                    // this.$refs.observer.reset();
                });
            } else if (this.currentStep === 3){
                this.$refs.step3.validate().then(success => {
                    if (!success) {
                        return;
                    }
                    this.currentStep++;
                    this.scrollToTop();
                    // this.$refs.step4.reset();
                    // this.$refs.observer.reset();
                });
            } else if (this.currentStep === 4){
                this.$refs.step4.validate().then(success => {
                    if (!success) {
                        return;
                    }
                    this.scrollToTop();
                    // this.$refs.step5.reset();
                    // this.$refs.observer.reset();
                    // this.currentStep++;
                });
            } 

        },

        goStepBack: function(){
            this.currentStep--;
            this.scrollToTop();
        },

        getPhoneEvent: function(event){
            // console.log(event);
            this.phone_error = event.isValid;

            if(event.isValid){
                this.phone = event;
            }

            this.formatted_number = event.countryCallingCode + event.nationalNumber;
        },

        selectedCategories: function(_categories){
            // console.log('selectedCategories', _categories);
            // console.log('selectedCategories_ids', _categories.map(item => item.id));
            this._selectedCategories = _categories;
        },

        selectedLocation: function(_incLocation){
            // console.log('_incLocation', _incLocation);
            this._location = _incLocation;
        },

        filesSelected(event){
            this.files = event;
            // console.log('fisierele selectate', this.files);
        },

        filesRemoved(){
            this.files = null;
            // console.log('fisierele ramase', this.files);
        },

        attachmentsSelected(event){
            this.attachments = event;
            // console.log('fisierele selectate', this.files);
        },

        attachmentsRemoved(){
            this.attachments = null;
            // console.log('fisierele ramase', this.files);
        },

        deleteValidationErrors: function(){
            this.validation_errors = null;
        },

        resetAll: function(){
            this.validation_errors = null,

            // this.demand = {};
            this.description = '';
            this.complete_name = '';
            this.subject = '';
            this.email = null;
            this._selectedCategories = [];
            this._location = {};
            
            this.phoneNumber = null;
            this.phone = {};

            this.phone_error = null;
            this.valid_phone = false;

            this.btnLoading = false;
            this.accept_terms = false;

            // this.$refs.step1.reset();
            // this.$refs.CategoriesComponent.resetAll();
            // this.$refs.PlacesComponent.resetAll();
            // this.$refs.CategoriesComponent.resetAll();

            // reset files component
            // console.log('this.$refs', this.$refs);
            // Object.assign(ref.$data, ref.$options.data());
            // this.files = null;

            this.currentStep = 1;
            this.cached_location = {};
            this.cached_categories = [];

            this.phone_verified = false;
            this.code_request = false;
            this.block_information = false;

            // this.$refs.observer.reset();
            // this.$nextTick(() => {
            // });
            // formData.delete('the_files[]');
        },

        onSubmit: function(){

            // this.demand.description = this.description;
            // this.demand.subject = this.subject;
            // this.demand.name = this.complete_name;
            // this.demand.email = this.email;
            // this.demand.phone = this.phone;
            // this.demand.categories = this._selectedCategories;
            // this.demand.location = this._location;



            // axios - inregistrare cerere

            // let final_demand = {
            //     name: this.name,
            //     email: this.email,
            //     phone: this.phone.phoneNumber,
            //     subject: this.subject,
            //     message: this.description,
            //     categories: this._selectedCategories.map(item => item.id),
            //     city: this._location.name,
            //     lat: this._location.latlng.lat,
            //     lng: this._location.latlng.lng,
            // }

            this.btnLoading = true;

            if(this.validation_errors){
                this.validation_errors = null;
            }

            let categories_array = [...this._selectedCategories.map(item => item.id)];
            // console.log('categories_array, type of si continut', typeof(categories_array), categories_array);

            let formData = new FormData();
            formData.append('name', this.complete_name);
            formData.append('email', this.email);
            formData.append('subject', this.subject);
            formData.append('phone', this.phone.phoneNumber);
            formData.append('categories', categories_array);
            formData.append('city', this._location.name);
            formData.append('administrative', this._location.administrative);
            formData.append('lat', this._location.latlng.lat);
            formData.append('lng', this._location.latlng.lng);
            formData.append('message', this.description);


            if(this.files){
                for(let file of this.files){
                    formData.append('the_files[]', file);
                }
            }

            if(this.attachments){
                for(let attachment of this.attachments){
                    formData.append('the_attachments[]', attachment);
                }
            }

            

            const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/public/demands/store`, formData, config).then(response => {
                // console.log('rezultat', response.data);
                

                if(response.data.result == true){
                    // if success => reseteaza cerere.
                    // Vue.$toast.open({
                    //     message: 'Felicitari! Cereera a fost trimisa!',
                    //     type: 'success',
                    //     duration: 6000
                    // });
                    this.$swal(
                        'Cerere trimisă cu succes!',
                        'Cererea a fost înregistrată în platforma Reformex. Profesioniștii înscriși în platformă vor putea să vizualizeze informațiile și să vă contacteze. Multumim pentru încrederea acordată!',
                        'success'
                    );

                    

                    // ruleaza un spinner si reseteaza formularul.
                    // requestAnimationFrame(() => {
                    //     this.$refs.observer.reset();
                    // });
                    
                    // 
                    if(this.files){
                        this.files = null;
                        formData.delete('the_files[]');
                        this.$store.commit('files_upload/set_reset_files');
                    }

                    if(this.attachments){
                        this.attachments = null;
                        formData.delete('the_attachments[]');
                        this.$store.commit('attachments_upload/set_reset_files');
                    }

                    this.resetAll();
                    this.$refs.observer.reset();
                    // this.$refs.uploadFileComponent.resetAll();

                    

                    

                    
                } else {
                    // open toatr 
                    Vue.$toast.open({
                        message: 'Oups! Am întampinat erori. Verifică informațiile introduse în formular.',
                        type: 'error',
                        duration: 6000
                    });

                    if (response.data.validation_errors !== null){
                        // console.log('erori de validare', response.data.validation_errors);
                        this.validation_errors = response.data.validation_errors;
                    }

                }

            }).catch((error) => {
                // console.log('erori de validare in catch', error);
                // return Promise.reject(error.response)

                if(error || error !== 'undefined') {
                    Vue.$toast.open({
                        message: 'Oups! Am întampinat erori.',
                        type: 'error',
                        duration: 6000
                    });
                }

            })
            .finally(() => {
                this.btnLoading = false;
                
            });

            // resetare formular

            // console.log('onSubmit apasat', this.demand);
        }
    },

    created: function () {
        setTimeout(() => {
             this.getReady = true;
        }, 2000);
    }
}
</script>

<style scoped>
.block-infos {
    /* display: block; */
    /* position: absolute; */
    /* width: 95%; */
    /* height: 100%; */
    /* background: black; */
    /* z-index: 9999; */
    /* height: 200px; */
    /* margin: 0 auto; */
    /* opacity: 0.2; */
}

.terms-class {
    font-weight: bold;
    color: #35a796;
    text-decoration: underline;
    cursor: pointer;
}
</style>