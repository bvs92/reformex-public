<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title ">Parolă</h3>
                <div class="card-options">
                    <button class="btn btn-sm btn-info" @click="modalFunc">Editare</button>
                </div>
            </div>
        </div>

        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Schimbă parola">
            <div class="card my-3">
                <button @click="generatePassword" class="btn btn-info">Generează automat</button>
            </div>
            <h4 class="text-muted text-center my-3">SAU</h4>
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:10" v-slot="{ errors, invalid, passed, touched }">
                            <label for="password">Parolă nouă</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="password" 
                            ref="password"
                            placeholder="" name="password"
                            v-model="password"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['password']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['password']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>



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

        <b-modal v-model="messageModalShow" hide-footer id="modal-center" centered title="Schimba parola">
            <p class="text-center my-3">Parola a fost schimbată.</p>
        </b-modal>
    </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, confirmed } from 'vee-validate/dist/rules';


extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('confirmed', {
  ...confirmed,
  message: 'Valoarea introdusa nu este identica cu parola.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

export default {
    name: "ChangeUserPasswordComponent",

    data(){
        return {
            user_id: '',
            modalShow: false,
            messageModalShow: false,
            once: false,
            validation_errors: null,
            password: '',
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    props: {
        the_user_id: Number
    },


    methods: {
        modalFunc: function(){
            this.modalShow = !this.modalShow;

            this.validation_errors = [];

            console.log('this.user_id este ', this.user_id);
            
            // if(this.$refs.observer){
            //     this.$refs.observer.reset();
            // }
        },

        onSubmit: async function(){
            console.log('fire');
            this.once = true;

            let formData = {
                password: this.password
            };

            await axios.post(`/api/admin/users/${this.user_id}/change/password`, formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                
                    this.modalShow = false;
                    this.resetAll();

                    this.$swal({
                        title: 'Parolă modificată cu succes.',
                        text: "Parola a fost modificată cu succes.",
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
            
            this.password = '';
            this.$refs.observer.reset();
        },

        generatePassword: async function(){
            console.log('generatePassword');
            this.modalShow = !this.modalShow;

            await axios.post(`/api/admin/users/${this.user_id}/generate/password`).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    // await this.$store.commit('users/insert_user', response.data.user);

                    this.$swal({
                        title: 'Parolă modificată cu succes.',
                        text: "Parola a fost modificată cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });
                    this.modalShow = false;
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
        }
    },

    created(){
        this.user_id = this.the_user_id;
    }
}
</script>