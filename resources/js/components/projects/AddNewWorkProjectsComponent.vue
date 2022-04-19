<template>
  <div class="">
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }" >
        <form enctype="multipart/form-data" @submit.prevent="handleSubmit(onSubmit)">

            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label for="subject">Titlu</label>
                        <validation-provider rules="required|min:2" v-slot="{ errors }">
                            <input type="text" class="form-control" id="title" placeholder="Titlu proiect" name="title" v-model="title">
                            <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                        </validation-provider>
                    </div>

                </div>
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <p>Selectează minim o categorie</p>
                        <CategoriesComponent ref="CategoriesComponent" @categories:selected="selectedCategories" :existing_categories="[]" />
                    </div>
                </div>
            </div>

        
            <div class="form-group">
                <label for="description">Descriere proiect</label>
                <validation-provider rules="required|min:2" v-slot="{ errors }">
                    <textarea class="form-control" 
                    name="description" id="description" cols="30" rows="5" v-model="description"></textarea>
                    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                </validation-provider>
            </div>

            <!-- <validation-provider rules="required" v-slot="{ errors }"> -->
                <FilesUploadComponent @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent" :maximum_images="15" />
                 <!-- <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
            </validation-provider> -->
        
            <div class="form-group row my-6">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success" v-if="!btnLoading">Salvează proiect</button>
                    <button class="btn btn-success btn-loading" v-else>Se salvează proiectul</button>
                </div>
            </div>
        
        
        </form>
    </ValidationObserver>
</div>
</template>

<script>

import FilesUploadComponent from './_modules/FilesUploadComponent.vue';
import CategoriesComponent from './_modules/CategoriesComponent.vue';

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Informatia este obligatorie.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

export default {
    name: "AddNewWorkProjectsComponent",

    data(){
        return {
            files: null,
            title: '',
            description: '',
            btnLoading: false,
            validation_errors: null,
            isBusy: false,
            _selectedCategories: null
        }
    },

    computed: {
        
    },

    components: {
        FilesUploadComponent,
        ValidationProvider,
        ValidationObserver,
        CategoriesComponent
    },

    methods: {
        onSubmit: function(){

            this.btnLoading = true;


            let categories_array = [...this._selectedCategories.map(item => item.id)];

            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('categories', categories_array);


            if(this.files){
                for(let file of this.files){
                    formData.append('the_files[]', file);
                }
            }

            

            const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

            // console.log('s-a tras', formData);

            axios.post(`/api/work-projects/store`, formData, config).then(response => {

                if(response.data.result == true){
                    // if success => reseteaza cerere.
                    Vue.$toast.open({
                        message: 'Felicitări! Proiectul a fost salvat!',
                        type: 'success',
                        duration: 6000
                    });
                    
                    if(this.files){
                        this.files = null;
                        formData.delete('the_files[]');
                        this.$store.commit('files_upload/set_reset_files');
                    }
                    this.title = '';
                    this.description = '';
                    this._selectedCategories = null;
                    this.$refs.observer.reset();
                    this.$refs.CategoriesComponent.resetAll();
                    this.$refs.uploadFileComponent.resetAll();

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

        filesSelected(event){
            this.files = event;
            // console.log('fisierele selectate', this.files);
        },

        filesRemoved(){
            this.files = null;
            // console.log('fisierele ramase', this.files);
        },
    },


    created(){

        
    }
}
</script>

<style>

</style>