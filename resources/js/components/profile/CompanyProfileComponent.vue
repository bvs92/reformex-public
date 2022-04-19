<template>
<!-- start card company -->
<div class="">
    <div class="card-header">
        <h3 class="card-title">Informatii firmă</h3>
    </div>
    <div class="card-body">
    <ValidationObserver v-slot="{ handleSubmit }">
    <form @submit.prevent="handleSubmit(onSubmit)">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="company_name">Denumire firmă</label>
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed }">
                        <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="company_name" name="company_name" placeholder="Firma Mea SRL" v-model="company_name">
                        <span class="text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span>
                    </validation-provider>

                    <!-- <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Firma Mea SRL" v-model="company_name">
                    <span class="text-danger" v-if="validationErrors.hasOwnProperty('name')">{{ validationErrors.name[0] }}</span> -->
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="year_made">An infiintare</label>
                    <validation-provider rules="required|integer" v-slot="{ errors, invalid, passed }">
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
                    <label for="phone">Numar de telefon</label>
                    <validation-provider rules="required|length:10" v-slot="{ errors, invalid, passed }">
                        <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="phone" name="phone" placeholder="0740000000" v-model="phone">
                        <span class="text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validationErrors.hasOwnProperty('phone')">{{ validationErrors.phone[0] }}</span>
                    </validation-provider>
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
                    <validation-provider rules="required|length:7" v-slot="{ errors, invalid, passed }">
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
            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                <textarea name="bio" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" rows="6" placeholder="Scurta descriere a companiei..." v-model="bio"></textarea>
                <span class="text-danger">{{ errors[0] }}</span>
                <span class="text-danger" v-if="validationErrors.hasOwnProperty('bio')">{{ validationErrors.bio[0] }}</span>
            </validation-provider>
        </div>

        <div class="form-group">
            <label for="website" class="form-label">Site internet</label>
            <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" name="website" placeholder="www.website.ro" v-model="website">
                <span class="text-danger">{{ errors[0] }}</span>
                <span class="text-danger" v-if="validationErrors.hasOwnProperty('website')">{{ validationErrors.website[0] }}</span>
            </validation-provider>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-success mt-1">Salveaza informatii firma</button>
        </div>
    </form>
    </ValidationObserver>
        
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="success_status">
                <p class="text-center">Modificarile au fost efectuate cu succes.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="closeSuccessAlert()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>


    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="error_status">
                <p class="text-center">Am intampinat erori.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="closeErrorAlert()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    
</div><!-- end card company -->
</template>

<script>

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';

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
    name: "CompanyProfileComponent",

    data(){
        return {
            company_name: this.company_info.name,
            year_made: this.company_info.year_made,
            phone: this.company_info.phone,
            workers: this.company_info.workers,
            cui: this.company_info.cui,
            register_number: this.company_info.register_number,
            administrative_company: this.company_info.administrative,
            city_company: this.company_info.city,
            address_company: this.company_info.address,
            bio: this.company_info.bio,
            website: this.company_info.website,

            error_status: false,
            success_status: false,

            validationErrors: ''
        }
    },

    props: {
        company_info: Object
    },

    components: {
        ValidationProvider,
        ValidationObserver
    },

    methods: {
        onSubmit() {
            // alert('Submitted');

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
        },


        closeSuccessAlert(){
            this.success_status = false;
        },

        closeErrorAlert(){
            this.error_status = false;
        },
    },

    mounted() {
        console.log('Component mounted.')
        console.log(this.company_info)
    }
}
</script>