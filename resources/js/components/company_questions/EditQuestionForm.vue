<template>

    <form @submit.prevent="saveQuestion">
        <p>Titlul întrebare</p>
        <input type="text" 
        class="form-control" 
        placeholder="Cât costă serviciul X?" 
        required
        v-model="title">
        <template v-if="validation_errors">
            <span v-if="validation_errors['title']" class="error-text">
                <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Minim 5 caractere.
            </span>
        </template>

        <br/>
        <p>Răspuns întrebare</p>
        <b-form-textarea
            id="textarea-state"
            v-model="text"
            :state="text.length >= 60 && text.length <= 300"
            placeholder="Scrie răspuns la întrebare de minim 60 de caractere, maxim 300."
            rows="6"
        ></b-form-textarea>
        <template v-if="validation_errors">
            <span v-if="validation_errors['text']" class="error-text">
                <i class="fa fa-times" aria-hidden="true"></i> Această informație este obligatorie. Scrie un răspuns la întrebare de minim 60 de caractere, maxim 300.
            </span>
        </template>

        <div class="mt-2">
            <b-button variant="success" type="submit" v-if="!saving">Salvează întrebare</b-button>
            <b-button variant="info" disabled v-else>
                <b-spinner small type="grow"></b-spinner>
                Se salvează...
            </b-button>
        </div>
    </form>
</template>

<script>
export default {
    name: "SaveQuestionForm",

    data() {
      return {
        // modalShow: false,
        saving: false,
        title: '',
        text: '',

        validation_errors: []
      }
    },

    props: ["question"],

    methods: {
        resetData: function(){
            this.title = '';
            this.text = '';
            this.saving = false;
            this.validation_errors = [];
        },

        saveQuestion: async function(){
            this.saving = true;
            if(this.validation_errors.length > 0){
                this.validation_errors = [];
            }

            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('text', this.text);

            await axios.post(`/api/company_questions/edit/${this.question.id}`, formData).then(async response => {
                // console.log('refuzare', response.data);
                if(response.data.result == true){
                    // arata toast success. centru
                    Vue.$toast.open({
                        message: 'Întrebare salvată cu succes!',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                    await this.$store.dispatch('company_questions/initCompanyQuestions');
                    this.$emit('closeModal');
                
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
                this.saving = false;
            });
        },
    },

    created(){
        // this.resetData();
        // this.modalShow = this.modal_show;
        this.title = this.question.title;
        this.text = this.question.text;
    }
}
</script>

<style>

</style>