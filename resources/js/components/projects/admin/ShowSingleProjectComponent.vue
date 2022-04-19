<template>
<div class="grid-margin">
    <div class="" v-if="project">

        <div class="row">
            <div class="col-lg-12">
                <EditSingleProjectComponent :the_project="project" @update:project="updateProject" />
            </div>
            <div class="col-lg-12">
                <p><strong>Titlu proiect</strong></p>
                <p>{{ project.title }}</p>
                <hr>
                <p><strong>Descriere proiect</strong></p>
                <p>{{ project.description }}</p>
                <hr>
                <p><strong>Categorii</strong></p>
                <div class="tags" v-if="project.categories && project.categories.length > 0">
                    <span class="tag" v-for="category in project.categories" :key="category.uuid">{{ category.name }}</span>
                </div>


            </div>
        </div>

        <template>
            <hr>
            <h4>Fotografii proiect ({{ project.photos.length }})</h4>
            <div class="row m-4" v-if="project.photos && project.photos.length > 0">
                <ListPhotosComponent :the_files="project.photos" :the_type_path="'work_projects'" :key="list_files_key" @photo:deleted="photoDeleted" />
            </div>
            <div class="row my-2" v-if="getAllowedNumber > 0">
                <div class="col-lg-12">
                    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }" >
                        <form enctype="multipart/form-data" @submit.prevent="handleSubmit(onSubmit)">
                            <div class="form-group row my-6">
                                <div class="col-lg-12">
                                    <hr>
                                    <p><strong>Puteti adauga inca {{ getAllowedNumber }} fotografii in acest proiect.</strong></p>
                                    <FilesUploadComponent @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent" :maximum_images="getAllowedNumber"  />
                                </div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-success" v-if="!btnLoading">Salveaza proiect</button>
                                    <button class="btn btn-success btn-loading" v-else>Se salveaza proiectul</button>
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
        </template>
    </div>
    <div v-else>
        <p class="text-center">Proiectul nu exista.</p>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex';
import ListPhotosComponent from "../../ListPhotosComponent.vue";
import FilesUploadComponent from '../_modules/FilesUploadComponent.vue';
import EditSingleProjectComponent from './EditSingleProjectComponent.vue';

import {  extend, ValidationObserver } from 'vee-validate';
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
    name: "ShowSingleProjectComponent",

    components: {
        ListPhotosComponent,
        FilesUploadComponent,
        ValidationObserver,
        EditSingleProjectComponent
    },

    data(){
        return {
            files: null,
            btnLoading: false,
            project: null,
            list_files_key: 'list_files_key'
        }
    },

    props: {
        the_project: Object
    },

    computed: {
        getAllowedNumber: function(){
            if(this.project.photos && this.project.photos.length > 0)
                return 15 - this.project.photos.length;
            else
                return 15;
        },

        ...mapGetters('projects', ['getProject'])
    },

    methods: {
        filesSelected(event){
            this.files = event;
            // console.log('fisierele selectate', this.files);
        },

        filesRemoved(){
            this.files = null;
            // console.log('fisierele ramase', this.files);
        },

        photoDeleted: function(id){
            console.log('eliminat', id);

            this.project.photos = this.project.photos.filter(item => {
                        if(item.id !== id){
                            return item;
                        }
                    });
        },

        updateProject: function(elems){
            this.project.title = elems.title;
            this.project.description = elems.description;
            this.project.categories = elems.categories;
        },

        onSubmit: function(){

            this.btnLoading = true;

            let formData = new FormData();
            if(this.files){
                for(let file of this.files){
                    formData.append('the_files[]', file);
                }
            }

            

            const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

            console.log('s-a tras', formData);

            axios.post(`/api/admin/work-projects/${this.project.uuid}/upload/photos`, formData, config).then(response => {

                if(response.data.result == true){
                    // if success => reseteaza cerere.
                    Vue.$toast.open({
                        message: 'Felicitari! Proiectul a fost actualizat!',
                        type: 'success',
                        duration: 6000
                    });

                    this.project = response.data.project;
                    
                    if(this.files){
                        this.files = null;
                        formData.delete('the_files[]');
                        this.$store.commit('files_upload/set_reset_files');
                    }
                    this.$refs.observer.reset();

                } else {
                    // open toatr 
                    Vue.$toast.open({
                        message: 'Oups! Am intampinat erori.',
                        type: 'error',
                        duration: 6000
                    });

                }

            }).catch((error) => {
                Vue.$toast.open({
                        message: 'Oups! Am intampinat erori.',
                        type: 'error',
                        duration: 6000
                    });
            })
            .finally(() => {
                this.btnLoading = false;
                
            });

        },
    },

    created(){
        this.project = this.the_project;
        // this.$store.dispatch('projects/initProject', this.the_project.uuid);
        // this.project = this.getProject;
    }
}
</script>

<style>

</style>