<template>
    <div>
    <button @click="modalShow = !modalShow" id="add__new__user" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adaugă utilizator nou</button>
    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă un nou utilizator">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3|alpha" v-slot="{ errors, invalid, passed, touched }">
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
                        <validation-provider rules="required|min:3|alpha" v-slot="{ errors, invalid, passed, touched }">
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
                            <label for="last_name">E-mail</label>
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
                        <b-form-group
                        label="Selecteaza minim un rol"
                        v-slot="{ roles }"
                        >
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <b-form-checkbox-group
                                v-model="selected"
                                :options="getTheRoles"
                                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}"
                                :aria-describedby="roles"
                                name="roles"
                                stacked
                            ></b-form-checkbox-group>
                        <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        </b-form-group>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['roles']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['roles']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>

                    </div>

                    <!-- <div class="col-lg-10 ml-6 my-2">
                        <b-form-group
                        v-slot="{ welcome_email }"
                        >
                            <b-form-checkbox v-model="welcome_user" name="check-button" switch :aria-describedby="welcome_email">
                            Trimite e-mail de bun venit?
                            </b-form-checkbox>
                        </b-form-group>
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
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, email } from 'vee-validate/dist/rules';

import {mapGetters} from 'vuex';

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

export default {
    name: "AddNewUserComponent",

    data(){
        return {
            modalShow: false,
            once: false,
            validation_errors: null,

            isLoading: false,

            first_name: '',
            last_name: '',
            email: '',
            type: 'info',

            // roles
            selected: [], // Must be an array reference!
            // options: [
            //     { text: 'Administrator', value: 'admin' },
            //     { text: 'editor', value: 'editor' },
            //     { text: 'moderator', value: 'moderator' },
            // ],

            // welcome_user: true
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    computed: {
        ...mapGetters('roles', ['getRoles']),

        getTheRoles: function(){
            return this.getRoles.map(item => {
                let one = {
                    text: item.name[0].toUpperCase() + item.name.slice(1),
                    value: item.name
                }

                return one;
            });
        }
    },

    methods: {
        onSubmit: async function(){
            this.once = true;
            console.log('fire!');

            let formData = {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                roles: this.selected,
                // welcome_user: this.welcome_user,
            };

            await axios.post('/api/users/admin/store', formData).then(async response => {
                console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    await this.$store.commit('users/insert_user', response.data.user);

                    await this.resetAll();
                    this.modalShow = false;
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
            
            this.first_name = '';
            this.last_name = '';
            this.email = '';
            this.selected = [];
            // this.welcome_user = true;
            this.$refs.observer.reset();
        }
    },

    created(){
        this.isLoading = true;
        this.$store.dispatch('roles/initRoles').finally(() => {
            this.isLoading = false;
        });
    }
}
</script>