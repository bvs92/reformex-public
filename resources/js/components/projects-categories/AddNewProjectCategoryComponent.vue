<template>
<div class="card-options">
    <a @click="modalShow = !modalShow" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adaugă categorie nouă</a>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă o categorie nouă">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2">
                    <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                        <label for="name">Nume categorie</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="name" 
                        placeholder="Categorie" name="name"
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
                    <validation-provider rules="required|min:3|alpha_dash" v-slot="{ errors, invalid, passed, touched }">
                        <label for="url">URL categorie</label>
                        <input type="text" class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="url" 
                        placeholder="categorie" name="url"
                        v-model="url"
                        >
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['url']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['url']" :key="'error-' + index">{{ error }}</span>
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
    name: "AddNewProjectCategoryComponent",

    data(){
        return {
            modalShow: false,
            once: false,
            validation_errors: null,

            isLoading: false,

            name: '',
            url: '',
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
            this.validation_errors = null;


            let formData = {
                name: this.name,
                url: this.url,
            };

            await axios.post('/api/work-project-categories/store', formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    await this.$store.commit('project_categories/insert_category', response.data.category);

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
            this.name = '';
            this.url = '';
            this.validation_errors = null;
            this.$refs.observer.reset();
        }
    },

    created(){}

}
</script>

<style>

</style>