<template>
<div>
<!-- <BannerPhotoComponent /> -->

<div class="d-flex justify-content-center mb-3" v-if="isLoading">
    <b-spinner label="Loading..."></b-spinner>
</div>

<div v-else>
    <div class="row">
        <processing-options-component :ad="getAd" @activate_ad="setupAd"></processing-options-component>

        <div class="col-lg-12 my-3">
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-danger mr-3" @click.prevent="deleteAd" v-if="!deleteStatus">Elimină ad</button>
                <b-button variant="danger btn-sm mr-3" disabled v-else>
                    <b-spinner small type="grow"></b-spinner>
                    Se elimină...
                </b-button>
            </div>
            <update-options-component :ad="getAd" v-if="getAd"></update-options-component>
        </div>
    </div>

    <ValidationObserver ref="observer_announce" v-slot="{ handleSubmit, invalid }">
        <form class="row mt-5" @submit.prevent="handleSubmit(updateAnnounce)">

            <div class="col-lg-12 my-2 mt-5">
                <br><br>
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
                    <label for="cui">CUI</label>
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
                    <label for="website">Site / URL anunț</label>
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
                <button class="btn btn-success" :class="{'disabled': invalid}" v-if="!once">Salvează</button>
                <b-button variant="info" disabled v-else>
                    <b-spinner small></b-spinner>
                    <span class="sr-only">Salvăm...</span>
                </b-button>
                <!-- <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button> -->
            </div>
        </form>
    </ValidationObserver>


    <!-- ad payments -->
    <br><br><br>
    <AdsRecommendPaymentsComponent :payments="getAd.payments" />

    </div>
</div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';



// import BannerPhotoComponent from "./BannerPhotoComponent.vue";
import AdsRecommendPaymentsComponent from './AdsRecommendPaymentsComponent.vue';
import ProcessingOptionsComponent from './ProcessingOptionsComponent.vue';
// import UpdateImageComponent from './update/UpdateImageComponent.vue';
import UpdateOptionsComponent from './update/UpdateOptionsComponent.vue';
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
    name: "EditAdRecommendComponent",

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
            deleteStatus: false,
            
           
            
        }
    },

    props: ["uuid"],

    components: {
        ValidationObserver,
        ValidationProvider,
        AdsRecommendPaymentsComponent,
        // BannerPhotoComponent,
        // DatePickerComponent
        

        // UpdateImageComponent,
        UpdateOptionsComponent,
        ProcessingOptionsComponent
    },

    computed: {
        
        

        ...mapGetters('ads_recommend', ['getAd'])
    },

    

    methods: {

        



        updateAnnounce: async function(){
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
    

            await axios.post('/api/ads_recommend/update_announce/' + this.getAd.id, formData).then(async response => {
                console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    // await this.$store.commit('categories/insert_category', response.data.category);


                    Vue.$toast.open({
                        message: 'Informații modificate cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
          
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

        deleteAd: async function(){

  
            this.deleteStatus = true;

            this.$swal({
                title: 'Eliminare ad',
                text: "Ești sigur că dorești să elimini aceast anunț? Actiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios.delete('/api/ads_recommend/delete/' + this.uuid)
                        .then(async response => {
                            if(response.data.success){
                                Vue.$toast.open({
                                    message: 'Executat cu succes.',
                                    type: 'success',
                                    duration: 6000,
                                    position: 'bottom'
                                });
                                
                                location.replace('/publicitate/admin/anunturi-recomandate')
                                

                            } else if(response.data.errors){
                                Vue.$toast.open({
                                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                                    type: 'error',
                                    duration: 6000,
                                    position: 'bottom'
                                });
                            }
                        });
                }
            }).finally(() => {
                this.deleteStatus = false;
            });



            
        },

        setupAd: async function(){
            this.isLoading = true;
            await this.$store.dispatch('ads_recommend/getSingle', this.uuid)
            .finally(() => {
                    this.isLoading = false;
            });

            if(this.getAd) {
                this.name = this.getAd.name;
                this.email = this.getAd.email;
                this.cui = this.getAd.cui;
                this.location = this.getAd.location;
                this.description = this.getAd.description;
                this.phone = this.getAd.phone;
                this.website = this.getAd.website;
                this.status = this.getAd.status == '1' ? true : false;
                this.hasForm = this.getAd.has_form == '1' ? true : false;
                this.show_email = this.getAd.show_email == '1' ? true : false;
                // this.image = this.getAd.image;

                // if(this.image){
                //     this.show_existing_image = true;
                // }

                if(this.getAd.categories)
                    this.categories = this.getAd.categories.map(item => {
                        return item.id;
                    })

                    this.existing_categories = this.categories;
                }
        },

        
    },

    async created(){
        await this.setupAd();
    }
}


</script>

<style scoped>
.fit-image {
    width: 100%;max-width: 440px;height: 480px!important;max-height: 480px!important;
    object-fit: cover;
}
</style>