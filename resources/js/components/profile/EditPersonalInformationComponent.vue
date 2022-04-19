<template>
<div class="">
<div class="card-header">
    <h3 class="card-title">Informații personale</h3>
    <div class="card-options">
        <a @click.prevent="openModal" class="btn btn-primary btn-sm">Modifică</a>
    </div>
</div>
<div class="card-body">
  <p class="card-text">Nume și Prenume: <strong>{{ user.last_name }} {{ user.first_name }}</strong></p>
  <p class="card-text">Adresă e-mail: <strong>{{ user.email }}</strong></p>

   <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Modifică informații personale">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="last_name">Nume</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="last_name" 
                        ref="last_name"
                        placeholder="" name="last_name"
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
                    <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                        <label for="first_name">Prenume</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="first_name" 
                        ref="first_name"
                        placeholder="" name="first_name"
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
                    <validation-provider rules="required|min:4|email" v-slot="{ errors, invalid, passed, touched }">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="email" 
                        ref="email"
                        placeholder="" name="email"
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
import { required, min, confirmed, email } from 'vee-validate/dist/rules';


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


extend('email', {
  ...email,
  message: 'Introduceti o adresa de e-mail valida.'
});

export default {
    name: "EditPersonalInformationComponent",

    data(){
        return {
            modalShow: false,
            user: null,
            once: false,
            validation_errors: null,
            last_name: '',
            first_name: '',
            email: '',
        }
    },

    props: {
      the_user: Object
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
            this.validation_errors = [];
            this.last_name = this.user.last_name;
            this.first_name = this.user.first_name;
            this.email = this.user.email;
        },

        onSubmit: async function(){
            this.once = true;

            let formData = {
                last_name: this.last_name,
                first_name: this.first_name,
                email: this.email,
            };

            await axios.post(`/api/users/update/personal/information`, formData).then(async response => {
                if(response.data.success){
                  this.user = response.data.user;
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
            
            this.first_name = '';
            this.last_name = '';
            this.email = '';
            this.$refs.observer.reset();
        },
    },

    created(){
      this.user = this.the_user;
    }
}
</script>

<style>

</style>