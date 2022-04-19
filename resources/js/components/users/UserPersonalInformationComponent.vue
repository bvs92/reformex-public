<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title ">Informații personale</h3>
                <div class="card-options">
                    <button class="btn btn-sm btn-info" @click="modalFunc">Editare</button>
                </div>
            </div>
            <div class="card-body text-dark">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Nume: {{ user.last_name }}</li>
                    <li class="list-group-item">Prenume: {{ user.first_name }}</li>
                    <li class="list-group-item">E-mail: {{ user.email }}</li>
                    <li class="list-group-item">Utilizator: {{ user.username }}</li>
                    <li class="list-group-item">Dată înregistrare: {{ formatElementTimeMethod(user) }}</li>
                    <li class="list-group-item">Ultima modificare: {{ formatElementTimeMethod(user) }}</li>
                    <li class="list-group-item">Status: {{ formatStatus(user.status) }}</li>
                    <li class="list-group-item" v-if="user.is_pro == 1">Cont Profesionist: {{ formatPro(user.is_pro) }}</li>
                    <li class="list-group-item" v-if="user.stripe_id">Id Stripe: {{ user.stripe_id }}</li>
                    <li class="list-group-item" v-if="user.card_brand">Card Brand: {{ user.card_brand }}</li>
                    <li class="list-group-item" v-if="user.card_last_four">Card (4 cifre): {{ user.card_last_four }}</li>
                </ul>
            </div>
        </div>

        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Editare utilizator">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3|alpha_spaces" v-slot="{ errors, invalid, passed, touched }">
                            <label for="last_name">Nume</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="last_name" 
                            placeholder="Popescu" name="last_name"
                            v-model="last_name"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['last_name']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['last_name']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3|alpha_spaces" v-slot="{ errors, invalid, passed, touched }">
                            <label for="first_name">Prenume</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="first_name" 
                            placeholder="Marian" name="first_name"
                            v-model="first_name"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['first_name']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['first_name']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3|email" v-slot="{ errors, invalid, passed, touched }">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="email" 
                            placeholder="popescu@gmail.com" name="email"
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

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3|alpha_dash" v-slot="{ errors, invalid, passed, touched }">
                            <label for="username">Nume utilizator</label>
                            <input type="username" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="username" 
                            placeholder="popescu@gmail.com" name="username"
                            v-model="username"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['username']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['username']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>


                    <div class="col-lg-12 my-2">
                        <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                        <b-button variant="info" disabled v-else>
                            <b-spinner small></b-spinner>
                            <span class="sr-only">Se salvează...</span>
                        </b-button>
                        <!-- <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button> -->
                    </div>
                </form>
            </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, email, alpha_dash, alpha_spaces } from 'vee-validate/dist/rules';

// import {mapGetters} from 'vuex';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('email', {
  ...email,
  message: 'Adresa de e-mail invalida.'
});

extend('alpha_dash', {
  ...alpha_dash,
  message: 'Sunt permise doar litere, cifre si caracterele "-_".'
});

extend('alpha_spaces', {
  ...alpha_spaces,
  message: 'Sunt permise doar litere si spatii.'
});

export default {
    name: "UserPersonalInformationComponent",

    data(){
        return {
            user: {},
            modalShow: false,
            once: false,
            validation_errors: null,
            first_name: '',
            last_name: '',
            username: '',
            email: ''
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    props: {
        the_user: Object
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("DD.MM.YYYY, HH:mm");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Activ';
            } else if(status == 0){
                return 'Inactiv';
            }
        },
        formatPro: function(status){
            if(status == 1){
                return 'Da';
            } else if(status == 0){
                return 'Nu';
            }
        },

        modalFunc: function(){
            this.modalShow = !this.modalShow;
            this.first_name = this.user.first_name;
            this.last_name = this.user.last_name;
            this.username = this.user.username;
            this.email = this.user.email;

            this.validation_errors = [];
            
            if(this.$refs.observer){
                this.$refs.observer.reset();
            }
        },

        onSubmit: async function(){
            // /api/users/admin/update/{}

            this.once = true;

            let formData = {
                first_name: this.first_name,
                last_name: this.last_name,
                username: this.username,
                email: this.email
            };

            await axios.post(`/api/users/admin/update/${this.user.id}`, formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    this.user = response.data.user;

                    await this.resetAll();
                    this.modalShow = false;

                    setTimeout(() => {
                        this.$swal({
                            title: 'Succes.',
                            text: "Informațiile au fost modificate cu succes.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                        });
                    }, 300);

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
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });

        },

        resetAll: function(){
            
            this.first_name = this.user.first_name;
            this.last_name = this.user.last_name;
            this.username = this.user.username;
            this.email = this.user.email;
            this.$refs.observer.reset();
        }

    },

    created(){
        this.user = this.the_user;
    }
}
</script>