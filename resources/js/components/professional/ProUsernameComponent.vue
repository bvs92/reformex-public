<template>
<div>
    <div>
        <div class="card-header">
            <h3 class="card-title">Nume de utilizator</h3>
            <div class="card-options">
                <a @click.prevent="openModal" class="btn btn-primary btn-sm">Modifică</a>
            </div>
        </div>
        <div class="card-body">
            <template v-if="data_user_name_profile">
            <p>Nume utilizator: {{ data_user_name_profile.username.toLowerCase() }}</p>
            <p><a :href="'https://firme.reformex.ro/detalii-firma/' + data_user_name_profile.username.toLowerCase()" target="_blank">https://firme.reformex.ro/detalii-firma/<strong>{{data_user_name_profile.username.toLowerCase()}}</strong></a></p>
            </template>
            <template v-else>
                <p>Nume utilizator: {{ user.username }}</p>
                <p><a :href="'https://firme.reformex.ro/detalii-firma/' + user.username.toLowerCase()" target="_blank">https://firme.reformex.ro/detalii-firma/<strong>{{user.username.toLowerCase()}}</strong></a></p>
            </template>
        </div>
    </div>
    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Schimbare nume de utilizator">
        <div class="alert alert-info" role="alert">
        <p class="text-small"><small>Este acceptat și recomandat nume scurt de tipul: firma-mea. Sunt acceptate caractere alfa-numerice și semnul -</small></p>
        </div>
        <ValidationObserver v-slot="{ handleSubmit }" ref="observer">
            <form class="mt-1 px-4" @submit.prevent="handleSubmit(onSubmit)">
                <div class="row">
                    <div class="col-lg-12 my-4">
                        <p v-if="data_user_name_profile">Nume utilizator curent: {{ data_user_name_profile.username }}</p>
                        <p v-else>Nume utilizator curent: {{ user.username }}</p>
                    </div>
                    <div class="col-lg-8">
                        <validation-provider rules="required|min:3|alpha_dash" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" placeholder="Nume de utilizator" :class="{'is-invalid' : touched && invalid, 'is-valid': touched && passed}" v-model="username">
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary mb-2">Verifică</button>
                    </div>
                </div>
            </form>
            </ValidationObserver>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert" v-if="feedback == 2">
                    Numele de utilizator nu este disponibil. Încearcă alt nume de utilizator.
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert" v-if="feedback == 3">
                    Numele de utilizator salvat.
                    </div>
                </div>
            </div>

            <div class="row" v-if="is_available">
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                    Numele de utilizator este disponibil. Salvează modificările.
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success btn-block mb-2" @click="saveUsername">Salvează modificările</button>
                </div>
            </div>

            <div class="row" v-if="validation_errors">
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                    <p v-for="(error, index) in validation_errors" :key="'error-' + index">
                        <span class="small text-danger" >{{ error[0] }}</span>
                    </p>
                    </div>
                </div>
            </div>
        </b-modal>
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha_dash } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este 3 caractere.'
});

extend('alpha_dash', {
  ...alpha_dash,
  message: 'Sunt acceptate doar litere, cifre si semnul -.'
});

export default {
    name: "ProUsernameComponent",

    components: {
        ValidationProvider,
        ValidationObserver,
    },

    props: {
        user: Object,
        user_name_profile: Object,
    },

    data(){
        return {
            data_user_name_profile: null,
            modalShow: false,
            username: '',
            btn_type: 1,
            feedback: 0,
            is_available: false,
            validation_errors: null
        }
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
            this.feedback = 0;
            this.is_available = false;
            this.username = '';
            this.validation_errors = null;

        },
        onSubmit: function(){
            // console.log('fire here');
            this.feedback = 0;
            this.is_available = false;
            this.validation_errors = null;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.get('/api/users/check/username', {
                params: {
                    user_id: this.user.id,
                    user_name: this.username.toLowerCase()
                }
            }).then((response) => {
                if(response.data.success){
                    // console.log('este ok');
                    // this.feedback = 1;
                    // this.btn_type = 2;
                    this.is_available = true;
                } else if(response.data.errors) {
                    // console.log('nu este ok');
                    this.feedback = 2;
                    // this.btn_type = 1;
                    this.is_available = false;
                }
            }).catch((error) => {
                // console.log(error);
            });
        },

        saveUsername: function(){
            // console.log('fire saveUsername');

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post('/api/users/set/username', {
                username: this.username.toLowerCase()
            }).then((response) => {
                if(response.data.success){
                    // console.log('este ok');
                    this.modalShow = !this.modalShow;
                    this.feedback = 3;
                    this.btn_type = 1;
                    this.is_available = false;
                    this.data_user_name_profile.username = this.username.toLowerCase();
                    this.username = '';
                    this.validation_errors = null;
                    this.$refs.observer.reset();

                    Vue.$toast.open({
                        message: 'Setările au fost salvate! Pagina va fi actualizată.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    location.reload();
                } 
            }).catch((error) => {
                this.validation_errors = error.response.data.validation_errors;

                Vue.$toast.open({
                        message: 'Ceva nu a funcționat corect. Verifică informațiile introduse.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
            });
        }
    },

    created(){
        // console.log('ProUsernameComponent ', this.user);

        this.data_user_name_profile = this.user_name_profile;
    }
}
</script>