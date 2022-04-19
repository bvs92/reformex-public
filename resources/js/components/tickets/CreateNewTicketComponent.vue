<template>
  <div>
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
        <form id="register_ticket" @submit.prevent="handleSubmit(onSubmit)">

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="name">Subiect tichet</label>
                        <validation-provider :rules="{ required: true, min:2 }" v-slot="{ errors }">
                            <input type="text" class="form-control" id="name" placeholder="Subiect tichet" name="subject" v-model="subject">
                            <span class="small text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('subject')">{{ validation_errors.subject[0] }}</span>
                        </validation-provider>
                    </div>
                </div>

                

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="priority">Prioritate</label>
                        <validation-provider :rules="{ required: true }" v-slot="{ errors }">
                            <select name="priority" class="form-control" id="priority" v-model="priority">
                                <option value="1">Urgent</option>
                                <option value="2">Important</option>
                                <option value="3">Normal</option>
                            </select>
                            <span class="small text-danger">{{ errors[0] }}</span>
                            <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('priority')">{{ validation_errors.priority[0] }}</span>
                        </validation-provider>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="department">Departament</label>
                        <validation-provider :rules="{ required: true }" v-slot="{ errors }">
                        <select name="department" class="form-control" id="department" v-model="department">
                            <option value="0">General</option>
                            <option value="1">Comercial</option>
                            <option value="2">Tehnic</option>
                        </select>
                        <span class="small text-danger">{{ errors[0] }}</span>
                        <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('department')">{{ validation_errors.department[0] }}</span>
                        </validation-provider>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label for="message">Mesaj tichet</label>
                <validation-provider :rules="{ required: true, min:2 }" v-slot="{ errors }">
                    <textarea class="form-control" name="message" id="message" cols="30" rows="10" v-model="message"></textarea>
                    <span class="small text-danger">{{ errors[0] }}</span>
                    <span class="text-danger" v-if="validation_errors && validation_errors.hasOwnProperty('message')">{{ validation_errors.message[0] }}</span>
                </validation-provider>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12">
                <files-upload-timeline @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent"></files-upload-timeline>
            </div>

            <div class="form-group row">
                <div class="col-sm-10">
                <button class="btn btn-primary btn-loading" disabled="disabled" v-if="btnLoading"><i class="fa fa-send"></i> Incarcare</button>
                <button type="submit" class="btn btn-primary" v-else><i class="fa fa-send"></i> Deschide tichet</button>
                </div>
            </div>

        </form>
    </ValidationObserver>
</div>
</template>

<script>
import FilesUploadTimeline from "../FilesUploadTimeline";

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});



export default {
    name: "CreateNewTicketComponent",

    data(){
        return {
            department: 0,
            priority: 3,
            subject: '',
            message: '',

            files: null,
            validation_errors: null,
            btnLoading: false
        }
    },

    components: {
        FilesUploadTimeline,
        ValidationProvider,
        ValidationObserver
    },

    methods: {
        filesSelected(event){
            this.files = event;
            console.log('fisierele selectate', this.files);
        },

        filesRemoved(){
            this.files = null;
            console.log('fisierele ramase', this.files);
        },

        resetAll: function(){
            this.subject = '';
            this.message = '';
            this.priority = 3;
            this.department = 0;
            this.validation_errors = null;
            this.files = null;
            this.$refs.uploadFileComponent.resetAll();
        },


        onSubmit: function(){


            this.btnLoading = true;

            if(this.validation_errors){
                this.validation_errors = null;
            }


            let formData = new FormData();
            formData.append('subject', this.subject);
            formData.append('message', this.message);
            formData.append('priority', this.priority);
            formData.append('department', this.department);

            if(this.files){
                for(let file of this.files){
                    formData.append('the_files[]', file);
                }
            }


            const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/tickets/store/new`, formData, config).then(response => {
                if(response.data.success){
                  
                    Vue.$toast.open({
                        message: 'Felicitari! Tichetul a fost trimis.',
                        type: 'success',
                        duration: 6000
                    });
                    
                    formData.delete('the_files[]');
                    this.resetAll();
                    this.$refs.observer.reset();
                    
                } else if(response.data.validation_errors) {
                    // open toatr 
                    Vue.$toast.open({
                        message: 'Oups! Am intampinat erori. Verificati informatiile din formular.',
                        type: 'error',
                        duration: 6000
                    });
                    this.validation_errors = response.data.validation_errors;
                } else {
                    Vue.$toast.open({
                        message: 'Oups! Am intampinat erori. Ceva nu a functionat bine.',
                        type: 'error',
                        duration: 6000
                    });
                }

            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am intampinat erori. Ceva nu a functionat bine.',
                    type: 'error',
                    duration: 6000
                });
            })
            .finally(() => {
                this.btnLoading = false;
            });

        }
    }
}
</script>

<style>

</style>