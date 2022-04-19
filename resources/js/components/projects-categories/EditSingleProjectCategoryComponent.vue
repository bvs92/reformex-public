<template>
<div class="">
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(onSubmit)">
            <div class="row">
                <div class="col-lg-6 my-2">
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                        <label for="name">Nume categorie</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="name" 
                        placeholder="Categorie" name="name"
                        v-model="category.name"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['name']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['name']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-6 my-2">
                    <validation-provider rules="required|min:3|alpha_dash" v-slot="{ errors, invalid, passed, touched }">
                        <label for="slug">URL categorie</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="slug" 
                        placeholder="categorie" name="slug"
                        v-model="category.slug"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['slug']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['slug']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>

                <div class="col-lg-12 my-2">
                    <button class="btn btn-success float-right" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                    <b-button class="float-right" variant="info" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Se salvează...</span>
                    </b-button>
                </div>
            </div>
        </form>
    </ValidationObserver>
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, min_value, integer, alpha_dash } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('alpha_dash', {
  ...alpha_dash,
  message: 'Sunt acceptate doar litere, - si _. Va recomandam folosirea de -'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minima acceptata este 1.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});

export default {
    name: "EditSingleProjectCategoryComponent",

    props: {
        the_category: Object
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    data(){
        return {
            category: null,
            once: false,
            validation_errors: null
        }
    },

    methods: {
        onSubmit: async function(){
            this.once = true;
            this.validation_errors = null;

            let formData = {
                name: this.category.name,
                slug: this.category.slug,
            };

            await axios.post(`/api/work-project-categories/${this.category.uuid}/update`, formData).then(async response => {

                if(response.data.success){
                    // adauga user in lista.
                    Vue.$toast.open({
                        message: 'Acțiune executată cu succes.',
                        type: 'success',
                        duration: 6000
                    });

                    this.$refs.observer.reset();
                   
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
    },

    created(){
        this.category = this.the_category;

        
    }
}
</script>

<style>

</style>