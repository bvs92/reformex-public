<template>
<div class="card-options">
    <a @click="modalShow = !modalShow" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adaugă categorie nouă</a>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă o categorie nouă">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                        <label for="name">Denumire categorie</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="name" 
                        placeholder="Acoperiș" name="name"
                        v-model="name"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['name']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['name']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min_value:1" v-slot="{ errors, invalid, passed, touched }">
                        <label for="price">Preț deblocare cerere (in RON)</label>
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
                    <validation-provider v-slot="{ errors, invalid, passed, touched }">
                        <label for="description">Descriere categorie</label>
                        <textarea class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="description" 
                        placeholder="Descrierea categoriei" name="description"
                        v-model="description"
                        rows="4"
                        ></textarea>
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['description']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['description']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
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

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minimă acceptată este {length} caractere.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minimă acceptată este 1.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});


export default {
    name: "AddNewCategoryComponent",

    data(){
        return {
            modalShow: false,
            once: false,
            validation_errors: null,

            isLoading: false,

            name: '',
            price: 10,
            description: ''
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    computed: {},

    methods: {
        onSubmit: async function(){
            this.once = true;
            // console.log('fire!');

            let formData = {
                name: this.name,
                price: this.price,
                description: this.description
            };

            await axios.post('/api/categories/store', formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    await this.$store.commit('categories/insert_category', response.data.category);

                    await this.resetAll();
                    this.modalShow = false;
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });
        },

        resetAll: function(){
            this.name = '';
            this.price = 10;
            this.description = '';
            this.$refs.observer.reset();
        }
    },

    created(){}

}
</script>

<style>

</style>