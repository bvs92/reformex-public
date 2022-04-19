<template>
  <div class="card-options">
    <a @click="modalShow = !modalShow" id="add__new__period" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adaugă perioadă nouă</a>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă o perioadă nouă">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:1|integer" v-slot="{ errors, invalid, passed, touched }">
                        <label for="days">Număr zile</label>
                        <input type="number" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="days" 
                        placeholder="7" name="days"
                        v-model="days"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['days']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['days']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:1|integer" v-slot="{ errors, invalid, passed, touched }">
                        <label for="price">Preț (in RON)</label>
                        <input type="number" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="price" 
                        placeholder="10" name="price"
                        v-model="price"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['price']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['price']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <b-form-checkbox
                        id="checkbox-form"
                        v-model="isVisible"
                        name="checkbox-form"
                        :value="true"
                        unchecked-value="false"
                    >
                        Vizibil clientului?
                    </b-form-checkbox>
                </div>


                <div class="col-lg-12 my-2">
                    <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                    <b-button variant="info" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Salvăm...</span>
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
import { required, min, alpha, min_value, integer } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});

extend('min', {
  ...min,
  message: 'Minim acceptat 1.'
});

export default {
    name: "AddNewPeriodComponent",

    data(){
        return {
            isVisible: true,
            days: 7,
            price: 30,
            modalShow: false,
            once: false,
            validation_errors: null,

            isLoading: false,
        }
    },

     components: {
        ValidationObserver,
        ValidationProvider
    },

    methods: {
        onSubmit: async function(){
            this.once = true;
            // console.log('fire!');

            let formData = {
                days: this.days,
                price: this.price,
                isVisible: this.isVisible == true ? true : false
            };

            await axios.post('/api/periods/store', formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    // await this.$store.commit('periods/insert_category', response.data.category);
                    await this.$store.dispatch('periods/all');

                    await this.resetAll();
                    this.modalShow = false;
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori de validare. Verifică datele introduse în formular.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
                this.once = false;
            });
        },

        resetAll: function(){
            this.price = 30;
            this.days = 7;
            this.isVisible = true;
            this.$refs.observer.reset();
        }
    },
}
</script>

<style>

</style>