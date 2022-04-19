<template>

<div class="">
    <div class="col-lg-12 d-flex justify-content-center" v-if="!code_request">
        <button class="btn btn-info mt-2" @click.prevent="getCode">Verifică numărul de telefon pentru a continua.</button>
    </div>
    
    <template v-else>
        <div class="col-lg-12" v-if="phone_verified">
            <div class="alert alert-success" role="alert">
                <i class="fa fa-check-circle-o mr-2" aria-hidden="true"></i> Felicitări! Numărul de telefon a fost verificat cu succes. Poți continua.
            </div>
            
        </div>

        <template v-else>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <p class="text-center">Introdu PIN-ul primit prin SMS în câmpul de mai jos.</p>
                </div>
                <div class="col-8">
                    <validation-provider rules="required|min:4" v-slot="{ errors, invalid, passed, touched }">
                        <input type="text" 
                        v-model="pin" 
                        class="form-control" 
                        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                        id="subject" placeholder="1234" name="pin">
                        <span class="small text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                    <p v-if="resend_code">Nu ai primit încă codul? <a href="#" @click.prevent="getCode">Obține un alt cod.</a></p>
                    <CountDownTimer v-else @resend:code="resend_code = true" :key="countdown_key" />
                </div>
                <div class="col-4">
                    <button class="btn btn-info btn-block" @click.prevent="verifyCode" v-if="verifyPin">Verifică</button>
                    <button class="btn btn-info btn-block" disabled="disabled" v-else>Verifică</button>
                </div>
            </div>
        </template>

    </template>
    
</div>
</template>

<script>
import CountDownTimer from '../_utils/CountDownTimer.vue';

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

extend('min', {
  ...min,
  message: 'Lungimea minimă acceptată este {length} caractere.'
});


export default {
    name: "PhoneVerificationComponent",

    components: {
        CountDownTimer,
        ValidationProvider
    },

    computed: {
        verifyPin: function(){
            if(this.pin) {
                if(this.pin.length == 4){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    },

    props: [
        "phone_number",
        "code_request",
        "phone_verified"
    ],

    data(){
        return {
            pin: null, // pin verificare
            verification_uuid: null,
            // code_request: false,
            // phone_verified: false,
            resend_code: false,
            countdown_key: 'count_down_key',
        }
    },

    methods: {
        getCode: async function(){
            this.resend_code = false;
            // this.code_request = true;
            // console.log('fire');
            this.$emit('block:infos');
            this.$emit('code:request', true);


            let formData = new FormData();
            formData.append('phone', this.phone_number);

            await axios.post(`/api/phone/send/code`, formData).then(response => {
                // console.log('response.data', response.data);
                if(response.data.success){
                    this.verification_uuid = response.data.request_uuid;
                    
                } else if(response.data.errors) {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat bine. Generează un alt cod de verificare.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch(err => {
                Vue.$toast.open({
                    message: 'Oups! Am intampinat erori. Reîncearcă.',
                    type: 'error',
                    duration: 6000
                });
            });
        },
        
        verifyCode: async function(){
            // console.log('fire verify');
            // console.log('this.verification_uuid', this.verification_uuid);
            // console.log('this.pin', this.pin);

            let formData = new FormData();
            formData.append('uuid', this.verification_uuid);
            formData.append('code', this.pin);
                

            await axios.post(`/api/phone/verify/code`, formData).then(response => {
                // console.log('response.data - verify', response.data);
                if(response.data.success){
                    this.$store.dispatch('phone_verification/deleteCode', this.verification_uuid);
                    this.verification_uuid = null;
                    this.pin = null;
                    // this.phone_verified = true;
                    this.$emit('phone:verified');
                    
                } else if(response.data.errors) {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat bine. Generează un alt cod de verificare.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch(err => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă.',
                    type: 'error',
                    duration: 6000
                });
            });

        },

        

        resetAll: function(){
            // this.code_request = false;
            // this.phone_verified = false;
            this.resend_code = false;
        }
    }
}
</script>

<style>

</style>