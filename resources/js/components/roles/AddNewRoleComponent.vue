<template>
    <div class="card-options">
        <button @click="modalShow = !modalShow" id="add__new__role" class="btn btn-md btn-primary"><i class="fa fa-plus"></i> Adaugă rol</button>
        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adauga un nou rol">
            <p class="my-4 text-sm text-center">Numele trebuie să fie diferit de cele existente.</p>
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-8">
                        <validation-provider rules="required|min:3|alpha" v-slot="{ errors, invalid, passed, touched }">
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="name" 
                            placeholder="Exemplu: administrator" name="name"
                            v-model="role_name"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <span class="small text-danger" v-for="(error, index) in validation_errors" :key="'error-' + index">{{ error[0] }}</span>
                        </template>
                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" >Salvează</button>
                    </div>
                </form>
            </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha } from 'vee-validate/dist/rules';

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

import {mapGetters} from 'vuex';

export default {
    name: "AddNewRoleComponent",

    data() {
      return {
        modalShow: false,
        role_name: null,
        validation_errors: null
      }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    computed: {
        ...mapGetters('roles', ['getValidationErrros', 'getTotalRoles']),
    },

    methods: {
        onSubmit: async function(){
            console.log('onSubmit');

            this.validation_errors = null;

            let formData = {
                name: this.role_name
            };

            // this.$store.dispatch('roles/createRole', formData).then(response => {
                
            // });

          
            await axios.post('/api/roles/store', formData).then(async response => {
                if(response.data.success){
                    await this.$store.commit('roles/insert_role', response.data.role);
                    await this.$store.commit('roles/set_total', this.getTotalRoles + 1);
                    this.role_name = null;
                    this.modalShow = !this.modalShow;
                } else if(response.data.validation_errors){
                    // await this.$store.commit('roles/set_validation_errors', response.data.validation_errors);
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    await this.$store.commit('roles/set_errors', response.data.errors);
                }
            });
        
        }
    },

    
}
</script>