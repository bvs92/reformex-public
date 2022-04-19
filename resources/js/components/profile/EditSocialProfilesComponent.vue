<template>
<div class="text-center" v-if="loading">
  <b-spinner label="Spinning"></b-spinner>
</div>
<div class="" v-else>
<div class="card-header">
    <h3 class="card-title">Rețele sociale</h3>
    <div class="card-options">
        <a @click.prevent="openModal" class="btn btn-primary btn-sm">Modifică</a>
    </div>
</div>
<div class="card-body">
  <template>
  <p class="card-text" v-if="social_profiles.facebook_profile"><i class="fa fa-facebook-square"></i> <strong>{{ social_profiles.facebook_profile }}</strong> <a :href="'https://www.facebook.com/' +  social_profiles.facebook_profile" target="_blank"><i class="fa fa-external-link"></i> Verifică</a></p>
  <p class="card-text" v-if="social_profiles.instagram_profile"><i class="fa fa-instagram"></i> <strong>{{ social_profiles.instagram_profile }}</strong> <a :href="'https://www.instagram.com/' + social_profiles.instagram_profile" target="_blank"><i class="fa fa-external-link"></i> Verifică</a></p>
  <p class="card-text" v-if="social_profiles.youtube_profile"><i class="fa fa-youtube"></i> <strong>{{ social_profiles.youtube_profile }}</strong> <a :href="'https://www.youtube.com/' + social_profiles.youtube_profile" target="_blank"><i class="fa fa-external-link"></i> Verifică</a></p>
  <p class="card-text" v-if="social_profiles.twitter_profile"><i class="fa fa-twitter-square"></i> <strong>{{ social_profiles.twitter_profile }}</strong> <a :href="'https://www.twitter.com/' + social_profiles.twitter_profile" target="_blank"><i class="fa fa-external-link"></i> Verifică</a></p>
  <!-- <p class="card-text" v-if="social_profiles.tiktok_profile"><i class="fa fa-tiktok"></i> <strong>{{ social_profiles.tiktok_profile }}</strong> <a :href="'https://www.tiktok.com/@' + social_profiles.tiktok_profile" target="_blank"><i class="fa fa-external-link"></i> Verifica</a></p> -->
  </template>
  <p class="text-center">Completează rețelele sociale. <a @click.prevent="openModal" class="btn btn-success btn-sm">Adaugă rețelele sociale.</a></p>


   <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Modifică rețele sociale">
        <h5>Adaugă doar numele de utilizator</h5>
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="facebook">Facebook</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="facebook" 
                        ref="facebook"
                        placeholder="www.facebook.com/" name="facebook"
                        v-model="facebook"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['facebook']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['facebook']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <validation-provider rules="min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="instagram">Instagram</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="instagram" 
                        ref="instagram"
                        placeholder="www.instagram.com/" name="instagram"
                        v-model="instagram"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['instagram']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['instagram']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>


                <div class="col-lg-12 my-2">
                    <validation-provider rules="min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="youtube">Youtube</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="youtube" 
                        ref="youtube"
                        placeholder="www.youtube.com/" name="youtube"
                        v-model="youtube"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['youtube']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['youtube']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <validation-provider rules="min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="twitter">Twitter</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="twitter" 
                        ref="twitter"
                        placeholder="www.twitter.com/" name="twitter"
                        v-model="twitter"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['twitter']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['twitter']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <!-- <div class="col-lg-12 my-2">
                    <validation-provider rules="min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="tiktok">Tik Tok</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="tiktok" 
                        ref="tiktok"
                        placeholder="" name="tiktok"
                        v-model="tiktok"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['tiktok']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['tiktok']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div> -->



                <div class="col-lg-12 my-2">
                    <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                    <b-button variant="info" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Se salvează...</span>
                    </b-button>
                    <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button>
                </div>
            </form>
        </ValidationObserver>
    </b-modal>
</div>
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';


extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});



export default {
    name: "EditSocialProfilesComponent",

    data(){
        return {
            modalShow: false,
            once: false,
            loading: false,
            validation_errors: null,
            social_profiles: {},
            facebook: '',
            instagram: '',
            youtube: '',
            twitter: '',
            // tiktok: '',
        }
    },

    props: {
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
            this.validation_errors = [];
            this.facebook = this.social_profiles.facebook_profile || '';
            this.youtube = this.social_profiles.youtube_profile || '';
            this.instagram = this.social_profiles.instagram_profile || '';
            this.twitter = this.social_profiles.twitter_profile || '';
            // this.tiktok = this.social_profiles.tiktok_profile || '';
        },

        onSubmit: async function(){
            this.once = true;

            let formData = {
                facebook: this.facebook,
                instagram: this.instagram,
                youtube: this.youtube,
                twitter: this.twitter,
                // tiktok: this.tiktok,
            };

            await axios.post(`/api/users/social/profiles/update`, formData).then(async response => {
                if(response.data.success){
                    this.social_profiles = response.data.social_profiles;
                    this.modalShow = false;
                    this.resetAll();

                    this.$swal({
                        title: 'Succes.',
                        text: "Informații modificate cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });
                    
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
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu..',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });


            // setTimeout(() => {this.once = false; this.messageModalShow = !this.messageModalShow;}, 2000);
        },

        resetAll: function(){
            this.facebook = '';
            this.youtube = '';
            this.instagram = '';
            this.twitter = '';
            // this.tiktok = '';
            this.$refs.observer.reset();
        },

        initSocialProfiles: function(){
          this.loading = true;
          axios.get(`/api/users/social/profiles`).then(response => {
            if(response.data.success){
              this.social_profiles = response.data.social_profiles;
            }
          }).finally(() => {
            this.loading = false;
          });
        }
    },

    created(){
      this.initSocialProfiles();
    }
}
</script>

<style>

</style>