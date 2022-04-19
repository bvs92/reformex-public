<template>
<div>
<!-- <BannerPhotoComponent /> -->



<ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
    <form class="row" @submit.prevent="handleSubmit(onSubmit)">

        <div class="col-lg-12 my-2"> 
      

            <categories-component @selectedCategories="selectedCategories" ref="categories"></categories-component>
            <template v-if="validation_errors">
                <template v-if="validation_errors['categories']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['categories']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>

            <hr>
            <HasFormComponent @hasFormValue="hasFormValue" ref="hasForm" />
            <br />
            <div>
                <b-form-checkbox
                id="checkbox-show-email"
                v-model="show_email"
                name="checkbox-show-email"
                :value="true"
                unchecked-value="false"
                >
                Afișează adresa de e-mail în anunț?
                </b-form-checkbox>
            </div>
            <br />
            <!-- <div>
                <b-form-checkbox
                id="checkbox-status"
                v-model="status"
                name="checkbox-status"
                :value="true"
                unchecked-value="false"
                >
                Anunțul va fi activ?
                </b-form-checkbox>
            </div> -->

        </div>
   

        <div class="col-lg-12 my-2 mt-5">
            <br></br>
            <h4>Detalii anunț</h4>
            <hr>
        </div>

        <div class="col-lg-6 col-sm-12 my-2">
            <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                <label for="name">Denumire companie</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="name" 
                placeholder="Nume companie" name="name"
                v-model="name"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['name']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['name']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>

        <div class="col-lg-6 col-sm-12 my-2">
            <validation-provider rules="" v-slot="{ errors, invalid, passed, touched }">
                <label for="cui">Cod Unic de Înregistrare (CUI)</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="cui" 
                placeholder="1234567" name="cui"
                v-model="cui"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['cui']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['cui']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>

        <div class="col-lg-6 col-sm-12 my-2">
            <validation-provider rules="required|min:10" v-slot="{ errors, invalid, passed, touched }">
                <label for="phone">Număr telefon</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="phone" 
                placeholder="0722112233" name="phone"
                v-model="phone"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['phone']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['phone']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>

        <div class="col-lg-6 col-sm-12 my-2" >
            <validation-provider rules="required|email" v-slot="{ errors, invalid, passed, touched }" >
                <label for="email">Adresă e-mail</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="email" 
                placeholder="email@email.com" name="email"
                v-model="email"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['email']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['email']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>

        <div class="col-lg-6 col-sm-12 my-2">
            <validation-provider rules="" v-slot="{ errors, invalid, passed, touched }">
                <label for="location">Locație (oraș / adresă)</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="location" 
                placeholder="București" name="location"
                v-model="location"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['location']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['location']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>

        <div class="col-lg-6 col-sm-12 my-2">
            <validation-provider rules="" v-slot="{ errors, invalid, passed, touched }">
                <label for="website">Site / URL banner</label>
                <input type="text" class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="website" 
                placeholder="www.website.ro" name="website"
                v-model="website"
                >
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['website']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['website']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>


        <div class="col-lg-12 my-2">
            <validation-provider rules="required|max:250" type="text" v-slot="{ errors, invalid, passed, touched }">
                <label for="description">Descriere anunț</label>
                <textarea class="form-control" 
                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                id="description" 
                placeholder="Anunț" name="description"
                v-model="description"
                rows="4"
                ></textarea>
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
            <template v-if="validation_errors">
                <template v-if="validation_errors['description']">
                    <span class="small text-danger" v-for="(error, index) in validation_errors['description']" :key="'error-' + index">{{ error }}</span>
                </template>
            </template>
        </div>



        <div class="col-lg-12 my-2">
            <!-- <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button> -->
            <button class="btn btn-success" :class="{'disabled': invalid}" v-if="!once">Salvează anunț</button>
            <b-button variant="info" disabled v-else>
                <b-spinner small></b-spinner>
                <span class="sr-only">Se salvează...</span>
            </b-button>
            <!-- <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button> -->
        </div>
    </form>
</ValidationObserver>
</div>
</template>

<script>
import HasFormComponent from "../HasFormComponent.vue";
// import PeriodComponent from "../PeriodComponent.vue";
import CategoriesComponent from "../ClientCategoriesComponent.vue";
// import BannerPhotoComponent from "./BannerPhotoComponent.vue";
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, min_value, integer, max, email } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minimă acceptată este {length} caractere.'
});

extend('email', {
  ...email,
  message: 'Te rugăm să introduci o adresă de e-mail validă.'
});

extend('max', {
  ...max,
  message: 'Sunt acceptate maxim {length} caractere.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minimă acceptată este 1.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});


export default {
    name: "AddNewPersonalAdRecommendComponent",

    data(){
        return {
            once: false,
            validation_errors: null,

            isLoading: false,

            name: '',
            cui: '',
            phone: '',
            email: '',
            location: '',
            website: '',
            description: '',
            // period: null,
            categories: null,
            hasForm: null,
            hasFormElement: true,

            show_email: true,
            // status: true,
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider,
        // BannerPhotoComponent,
        // DatePickerComponent
        // PeriodComponent,
        CategoriesComponent,
        HasFormComponent
    },

    computed: {
        hasFormComputed: function(){
            return this.hasForm ? true : false;
        }
    },

    watch: {
        hasForm(value) {
            this.hasFormElement = value;
        }
    },

    methods: {

        hasFormValue: function(){
            this.hasForm = !this.hasForm;
            // console.log('has form is', this.hasForm);
        },

        // selectedPeriod: function(period){
        //     this.period = period;
        // },

        selectedCategories: function(categories){
            this.categories = categories;
            // console.log('categorii selectate', categories);
        },


        toggleModal: function(){
            this.modalShow = !this.modalShow;
            this.resetData();
        },

        resetData(){
            this.name = '';
            this.cui = '';
            this.phone = '';
            this.email = '';
            this.show_email = true;
            // this.status = true;
            this.location = '';
            this.website = '';
            this.description = '';
            // this.period = null;
            this.categories = null;
            // this.$refs.period.reset();
            this.$refs.categories.reset();
            this.$refs.hasForm.reset();
        },

        onSubmit: async function(){
            this.once = true;
            // console.log('fire!');

            let formData = new FormData();

            formData.append('name', this.name);
            formData.append('cui', this.cui);
            formData.append('phone', this.phone);
            formData.append('email', this.email);
            formData.append('location', this.location);
            formData.append('website', this.website);
            formData.append('description', this.description);
            formData.append('has_form',  this.hasForm);
            formData.append('show_email',  this.show_email);
            // formData.append('status',  this.status);
            // formData.append('period', this.period);
            formData.append('categories', this.categories);
           

            await axios.post('/api/ads_recommend/personal/store', formData).then(async response => {
                console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    // await this.$store.commit('categories/insert_category', response.data.category);

                    await this.resetData();
                    await this.$refs.observer.reset();

                    Vue.$toast.open({
                        message: 'Anunț creat cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    location.href = '/publicitate/anunturi-recomandate/detalii/' + response.data.ad_uuid; 
          
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori de validare. Verifică datele introduse.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
                this.once = false;
            });
        },
    },

    created(){
        this.hasForm = true;
    }

}
</script>

<style scoped>
.fit-image {
    width: 100%;max-width: 440px;height: 480px!important;max-height: 480px!important;
    object-fit: cover;
}
</style>