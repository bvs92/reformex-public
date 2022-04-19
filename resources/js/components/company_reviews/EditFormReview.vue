<template>
  <form @submit.prevent="saveReview">
        <p>Numărul de stele</p>
        <b-form-rating v-model="rating_value" variant="warning" class="mb-2"></b-form-rating>
        <template v-if="validation_errors">
            <span v-if="validation_errors['rating']" class="error-text">
                <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Alege între 1 și 5 stele.
            </span>
        </template>
        <br/>
        <p>Mesajul</p>
        <b-form-textarea
            id="textarea-state"
            v-model="message"
            :state="message.length >= 60 && message.length <= 186"
            placeholder="Scrie o recenzie de minim 60 de caractere, maxim 186."
            rows="5"
        ></b-form-textarea>
        <template v-if="validation_errors">
            <span v-if="validation_errors['message']" class="error-text">
                <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Scrie un mesaj de minim 60 de caractere, maxim 186.
            </span>
        </template>

        <div class="mt-2">
            <b-button variant="warning" @click.prevent="cancelEdit">Renunță</b-button>
            <b-button variant="success" type="submit" v-if="!savingStatus">Salvează recenzia</b-button>
            <b-button variant="info" disabled v-else>
                <b-spinner small type="grow"></b-spinner>
                Se salvează...
            </b-button>
        </div>
    </form>
</template>

<script>
export default {
    name: "EditFormReview",

    props: ["review"],

    data(){
        return {
            // review details
            rating_value: 0,
            message: null,
            validation_errors: [],
            savingStatus: false
        }
    },

    methods: {
        saveReview: async function(){
            let newReview = {
                rating: this.rating_value,
                message: this.message
            }

            this.savingStatus = true;
            if(this.validation_errors.length > 0){
                this.validation_errors = [];
            }
            
            let formData = new FormData();
            formData.append('rating', parseInt(this.rating_value));
            formData.append('message', this.message);


            await axios.post(`/api/company_reviews/save/${this.review.id}`, formData).then(async response => {
                // console.log('refuzare', response.data);
                if(response.data.result == true){
                    // arata toast success. centru
                    Vue.$toast.open({
                        message: 'Recenzia a fost modificată cu succes',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    this.$emit('reviewEdited', newReview);
                
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
                this.savingStatus = false;
            });
        },


        cancelEdit: function(){
            this.$emit('cancelEdit')
        }
    },

    created(){
        this.rating_value = this.review.rating,
        this.message = this.review.message;
    }
}
</script>

<style>

</style>