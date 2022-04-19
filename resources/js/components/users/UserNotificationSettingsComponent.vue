<template>
<div v-if="getting">
    <div class="text-center">
    <b-spinner label="Spinning"></b-spinner>
    </div>
</div>
<div v-else>
    <div v-if="!isMissing">
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form @submit.prevent="handleSubmit(onSubmit)">
                <b-list-group>
                    
                    <div class="row">
                        <div class="col">
                            Notificari prin e-mail
                        </div>
                        <div class="col">
                            <validation-provider rules="required" v-slot="{ errors, touched }" class="">
                                <b-form-checkbox v-model="checkEmail" name="check-email" switch class="float-right">
                                    {{getEmailStatus}}
                                </b-form-checkbox>
                                <span class="small text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            Notificari prin SMS
                        </div>
                        <div class="col">
                            <validation-provider rules="required" v-slot="{ errors, touched }" class="">
                                 <b-form-checkbox v-model="checkPhone" name="check-phone" switch class="float-right">
                                    {{getPhoneStatus}}
                                </b-form-checkbox>
                                <span class="small text-danger">{{ errors[0] }}</span>
                            </validation-provider>

                           
                        </div>
                    </div>
                </b-list-group>

                <hr>
                <div class="col-lg-12 my-2">
                    <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once_save">Salvează</button>
                    <b-button variant="info btn-loading" disabled v-else>
                        Se salvează...
                    </b-button>
                </div>
                
            </form>
        </ValidationObserver>
        
        
    </div>
    <div v-else>
        <p class="text-center">Setările cu notificări sunt dezactivate.</p>
        <div class="d-flex justify-content-center">
            <button class="btn btn-info btn-loading" v-if="once">În curs</button>
            <button class="btn btn-info" v-else @click="activateNotifications()">Activează</button>
        </div>
    </div>
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


export default {
    name: "UserNotificationSettingsComponent",

    props: {
        // the_user: Object
    },

    data(){
        return {
            once: false,
            once_save: false,
            checkEmail: false,
            checkPhone: false,
            isMissing: false,
            getting: false,
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    computed: {
        getEmailStatus: function(){
            return this.checkEmail ? 'Activat' : 'Dezactivat';
        },
        getPhoneStatus: function(){
            return this.checkPhone ? 'Activat' : 'Dezactivat';
        }
    },

    methods: {
        activateNotifications: async function(){
            this.once = true;
            let self = this;

            await axios.post(`/api/users/settings/notifications/activate`).then(response => {

                if(response.data.success){
                    self.checkEmail = Boolean(response.data.notifications.email);
                    self.checkPhone = Boolean(response.data.notifications.phone);
                    self.isMissing = false;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                    self.isMissing = true;
                }

            }).catch(error => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });
        },

        getNotificationSettings: async function(){
            this.getting = true;
            await axios.post(`/api/users/settings/notifications`).then(async response => {

                if(response.data.exists){
                    this.checkEmail = Boolean(response.data.notifications.email);
                    this.checkPhone = Boolean(response.data.notifications.phone);
                } else if(response.data.missing){
                    this.isMissing = true;
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
                this.getting = false;
            });
        },

        saveNotificationSettings: async function(){
            this.once_save = true;

            let formData = new FormData();
            formData = {
                email: this.checkEmail,
                phone: this.checkPhone
            };

            await axios.post(`/api/users/settings/notifications/update`, formData).then(async response => {

                if(response.data.success){
                    this.checkEmail = Boolean(response.data.notifications.email);
                    this.checkPhone = Boolean(response.data.notifications.phone);
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
                this.once_save = false;
            });
        },

        onSubmit: function(){
            console.log('fire');
            // this.once_save = true;
            // setTimeout(() => {
            //     this.once_save = false;
            // }, 2000);
            this.saveNotificationSettings();
        }

     
    },

    created(){
        this.getNotificationSettings();
    }
}
</script>