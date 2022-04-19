<template>
  <div>
      <button class="btn btn-info" @click.prevent="openModal"><i class="fa fa-plus"></i> Adaugă anunț</button>

      <b-modal v-model="modalShow" ok-only hide-footer title="Adaugă anunț nou">
            <div>

                <div v-if="getErrors" class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Eroare!</strong> {{ getErrors }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <pre v-if="getValidationErrors">
                    {{ getValidationErrors }}
                </pre>
                <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }" >
                <form @submit.prevent="handleSubmit(createAnnouncement)">
                    <div class="form-group">
                        <label for="labe-title">Titlu</label>
                        <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                        <input type="text" class="form-control" id="labe-title" placeholder="Titlu anunt" v-model="title" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}">
                        <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="getValidationErrors">
                            <template v-if="getValidationErrors['title']">
                                <span class="small text-danger" v-for="(error, index) in getValidationErrors['title']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="form-group">
                        <label for="labe-status">Tip / Culoare</label>
                        <select class="form-control" id="labe-status" v-model="type">
                        <option value="albastru">Albastru</option>
                        <option value="verde">Verde</option>
                        <option value="galben">Galben</option>
                        </select>
                        <template v-if="getValidationErrors">
                            <template v-if="getValidationErrors['type']">
                                <span class="small text-danger" v-for="(error, index) in getValidationErrors['type']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="form-group">
                        <label for="labe-status">Status</label>
                        <select class="form-control" id="labe-status" v-model="status">
                        <option value="0">Inactiv</option>
                        <option value="1">Activ</option>
                        </select>
                        <template v-if="getValidationErrors">
                            <template v-if="getValidationErrors['status']">
                                <span class="small text-danger" v-for="(error, index) in getValidationErrors['status']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>
                    <div class="form-group">
                        <label for="label-description">Descriere anunț</label>
                        <validation-provider rules="required|min:2" v-slot="{ errors, invalid, passed, touched }">
                        <vue-editor v-model="description" :editor-toolbar="customToolbar" :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" />
                        <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="getValidationErrors">
                            <template v-if="getValidationErrors['description']">
                                <span class="small text-danger" v-for="(error, index) in getValidationErrors['description']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                        <!-- <textarea class="form-control" id="label-description" rows="3" v-model="description"></textarea> -->
                    </div>

                    <b-button variant="primary" disabled v-if="isBusyAnnounce">
                        <b-spinner small type="grow"></b-spinner>
                        Se execută...
                    </b-button>
                    <button type="submit" class="btn btn-primary" v-else>Salvează anunț</button>
                </form>
                </ValidationObserver>
            </div>
        </b-modal>

  </div>
</template>

<script>

import { mapGetters } from 'vuex';
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Informatia este obligatorie.'
});


import { VueEditor } from "vue2-editor";
export default {
    name: "AdminCreateAnnouncementComponent",

    data(){
        return {
            isBusyAnnounce: false,
            modalShow: false,
            title: '',
            status: 0,
            description: '',
            type: 'albastru',

            customToolbar: [
                [{ header: [false, 2, 3, 4, 5, 6] }],
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["code-block"]
            ]
        }
    },

    components: { 
        VueEditor,
        ValidationProvider,
        ValidationObserver,
        },

    computed: {
        ...mapGetters('announcements', ['getErrors', 'getValidationErrors']),
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
        },

        createAnnouncement: function(){
            this.isBusyAnnounce = true;

            let formData = {
                title: this.title,
                status: this.status,
                description: this.description,
                type: this.type
            };

            this.$store.dispatch('announcements/createAnnouncement', formData).then(() => {
                
                if(this.geErrors == null && this.getValidationErrors == null){
                    this.$refs.observer.reset();
                    this.resetFields();
                    this.modalShow = !this.modalShow;
                }
            }).finally(() => {
                this.isBusyAnnounce = false;
            });
        },

        resetFields: function(){
            this.title = '';
            this.description = '';
            this.status = 0;
            this.type = 'albastru';
        }
    }
}
</script>

<style>

</style>