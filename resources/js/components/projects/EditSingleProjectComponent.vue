<template>
<div>
  <button @click.prevent="editModal" class="btn btn-sm btn-warning mx-2 float-right">Modifică</button>

  <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Editare proiect">
      <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <label for="title">Titlu</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="title" 
                            placeholder="Titlu proiect" name="title"
                            v-model="title"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['title']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['title']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <label for="description">Descriere</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="6" v-model="description" placeholder="Descriere proiect" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}"></textarea>
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['description']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['description']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <p>Selectati minim o categorie</p>
                            <CategoriesComponent ref="CategoriesComponent" @categories:selected="selectedCategories" :existing_categories="project.categories" />
                        </div>
                    </div>



                    <div class="col-lg-12 my-2">
                        <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!btnLoading">Salvează</button>
                        <b-button variant="info" disabled v-else>
                            <b-spinner small></b-spinner>
                            <span class="sr-only">Se salvează...</span>
                        </b-button>
                    </div>
                </form>
            </ValidationObserver>
  </b-modal>
</div>
</template>

<script>
import CategoriesComponent from './_modules/CategoriesComponent.vue';
import { ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

export default {
    name: "EditSingleProjectComponent",

    components: { ValidationProvider, ValidationObserver, CategoriesComponent },

    data(){
      return {
        btnLoading: false,
        modalShow: false,
        project: null,
        title: '',
        description: '',
        validation_errors: null,
        isBusy: false,
        _selectedCategories: null
      }
    },

    props: {
      the_project: Object
    },

    methods: {
        editModal: function(){
            this.modalShow = !this.modalShow;
            this.title = this.the_project.title;
            this.description = this.the_project.description;
            this.validation_errors = null;
        },

        onSubmit: function(){
            this.btnLoading = true;

            let categories_array = [...this._selectedCategories.map(item => item.id)];
            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('categories', categories_array);

            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/work-projects/update/${this.project.uuid}`, formData).then(response => {
            // console.log('rezultat', response.data);

                if(response.data.result){
                    // if success => reseteaza cerere.
                    Vue.$toast.open({
                        message: 'Felicitări! Proiectul a fost eliminat!',
                        type: 'success',
                        duration: 6000
                    });

                    let modified = {
                        title: this.title,
                        description: this.description,
                        categories: this._selectedCategories
                    };
                    
                    this.$emit('update:project', modified);
                    this.validation_errors = null;
                    this.$refs.observer.reset();
                    this.modalShow = false;
                    // this.$store.dispatch('projects/initProject', this.the_project.uuid);

                } else {
                    // open toatr 
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });

                    if (response.data.validation_errors !== null){
                        // console.log('erori de validare', response.data.validation_errors);
                        this.validation_errors = response.data.validation_errors;
                    }
                }

            }).catch((error) => {
                Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
            })
            .finally(() => {
                this.btnLoading = false;
                
            });
      },

      selectedCategories: function(_categories){
            this._selectedCategories = _categories;
            // console.log('this._selectedCategories este', this._selectedCategories);
        },

    },


    created(){
        this.project = this.the_project;

        if(this.project.categories && this.project.categories.length > 0){
            this._selectedCategories = this.project.categories;
        }
        
    }
}
</script>

<style>

</style>