<template>
   <div v-if="!hasReview">
       <div class="card">
           <div class="card-header">
            Lasă-ne o recenzie. Vei primi un cupon în valoare de 300 de RON.
        </div>
        <div class="card-body">
            <form @submit.prevent="sendReview">
                <p>Alege numărul de stele</p>
                <b-form-rating v-model="rating_value" variant="warning" class="mb-2"></b-form-rating>
                <template v-if="validation_errors">
                    <span v-if="validation_errors['rating']" class="error-text">
                        <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Alege între 1 și 5 stele.
                    </span>
                </template>
                <br/>
                <p>Scrie mesajul</p>
                <b-form-textarea
                    id="textarea-state"
                    v-model="message"
                    :state="message.length >= 60 && message.length <= 186"
                    placeholder="Scrie o recenzie de minim 60 de caractere, maxim 186."
                    rows="3"
                ></b-form-textarea>
                <template v-if="validation_errors">
                    <span v-if="validation_errors['message']" class="error-text">
                        <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Scrie un mesaj de minim 60 de caractere, maxim 186.
                    </span>
                </template>

                <div class="mt-2">
                    <b-button variant="success" type="submit" v-if="!sending">Trimite recenzia</b-button>
                    <b-button variant="info" disabled v-else>
                        <b-spinner small type="grow"></b-spinner>
                        Se trimite...
                    </b-button>
                </div>
            </form>
            <p>Ne rezervăm dreptul de a modifica recenzia atunci când considerăm necesar. Nu vom modifica esența mesajului, doar forma acestuia. Multumim!</p>

        </div>
       </div>
   </div>
</template>

<script>
import axios from 'axios';


export default {
    name: "CompanyReviewComponent",

    data() {
      return {
        hasReview: true,
        rating_value: 0,
        message: '',
        sending: false,
        validation_errors: []
      }
    },

    computed: {
        // checkHasReview: function(){
        //     this.hasReview ? true : false;
        // }
    },

    methods: {

        checkHasReview: function(){
            this.hasReview ? true : false;
        },

        resetData: function(){
            this.message = '';
            this.rating_value = 0;
            this.sending = false;
            this.validation_errors = [];
        },

        sendReview: async function(){
            this.sending = true;
            if(this.validation_errors.length > 0){
                this.validation_errors = [];
            }

            let formData = new FormData();
            formData.append('rating', parseInt(this.rating_value));
            formData.append('message', this.message);

            await axios.post('/api/company_reviews/store', formData).then(async response => {
                // console.log('refuzare', response.data);
                if(response.data.result == true){
                    // arata toast success. centru
                    Vue.$toast.open({
                        message: 'Recenzia a fost trimisă cu succes. Îți mulțumim!',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    // resetare data.
                    await this.resetData()

                    // ascunde formular review
                    this.hasReview = true;
                    await this.checkHasReview();
                
                } else if(response.data.validation_errors){
                    // ceva nu a functionat. arata toast eroare. centru
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Verifică datele din formular și retrimite.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });

                    this.validation_errors = response.data.validation_errors;

                } else {
                    // ceva nu a functionat. arata toast eroare. centru
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch(error => {
                // ceva nu a functionat. arata toast eroare. centru
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
                this.sending = false;
            });

        },

        checkUserHasReview: async function(){
            await axios.get('/api/company_reviews/user/has/review').then(response => {
                if(response.data.result == true){
                    this.hasReview = true;
                } else {
                    this.hasReview = false;
                }
            });
            
        }
    },


    async created(){
        // executa functie, vezi daca user a lasat deja review. return true sau false.
        // this.hasReview = false;
        await this.checkUserHasReview();
    }
  }
</script>

<style>
.error-text {
    color: red;
}
</style>