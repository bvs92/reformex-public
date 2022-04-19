<template>
<div class="mt-6">
    
        <div class="card-header">
            <h3 class="card-title">Site internet</h3>
            <div class="card-options">
                <a @click.prevent="openModal" class="btn btn-primary btn-sm">Modifică adresă site</a>
            </div>
        </div>
        <div class="card-body">
            <template v-if="getUserWebsite">
            <p>Site internet: {{ getUserWebsite }}</p>
            </template>
            <template v-else>
                <p>Site internet: inexistent</p>
            </template>
        </div>
   


    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă site-ul firmei sau al tău.">
    <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
    </div>
    <div v-else>
        <ValidationObserver v-slot="{ handleSubmit }" ref="observer">
            <form @submit.prevent="handleSubmit(onSubmit)" class="row">
        
            <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label for="website">Adresă site internet</label>
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed }">
                            <input type="text" class="form-control" :class="{'is-invalid' : invalid, 'is-valid': passed}" id="website" name="website" placeholder="www.site.ro" v-model="website">
                            <span class="text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validationErrors.hasOwnProperty('website')">{{ validationErrors.website[0] }}</span>
                        </validation-provider>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-2">
                        <button class="btn btn-success" type="submit">Salvează</button>
                    </div>
                </div>

   
            </form>
        </ValidationObserver>
    </div>
    </b-modal>

</div>

</template>

<script>
import { mapGetters } from 'vuex';
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

export default {
    name: "UserWebsiteComponent",

    data(){
        return {
            modalShow: false,
            website: '',
            loadingStatus: false,
            validationErrors: []

         
        }
    },

    components: {
        ValidationProvider,
        ValidationObserver
    },


    computed: {
        ...mapGetters('user_website', [
            'getUserWebsite',
        ])
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;

            this.website = this.getUserWebsite || '';
        },

        onSubmit: function(){
            // console.log('salvam', this.description);

            this.loadingStatus = true;
            this.$store.dispatch('user_website/saveUserWebsite', {
                website: this.website
            }).then(() => {
                this.modalShow = false;
            }).finally(() => {
                this.loadingStatus = false;
            });
        }
    },

    created(){
        this.loadingStatus = true;
        this.$store.dispatch('user_website/initUserWebsite').finally(() => {
            this.loadingStatus = false;
        });

        
    }
}
</script>

<style>
div.public-description ul {
    list-style-type: disc;
    margin-left: 15px;
}

div.public-description p, div.public-description ul, div.public-description ol, div.public-description blockquote {
    margin-bottom: 0.3em;;
}
</style>