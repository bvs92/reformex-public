<template>
<b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Editare utilizator">
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
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, email } from 'vee-validate/dist/rules';

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

export default {
    name: "PersonalInformationModal",

    data(){
        return {
            once: false,
            validation_errors: null,
            first_name: '',
            last_name: '',
            // email: '',
        }
    },

}
</script>