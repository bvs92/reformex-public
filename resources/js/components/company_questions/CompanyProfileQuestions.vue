<template>
<div class="card">
    <div class="card-header">
        <div class="card-title">Întrebări profil public</div>
        <div class="card-options">
           <a href="#" @click.prevent="addQuestion" class="btn btn-primary btn-sm">Adaugă întrebare</a>
        </div>
    </div>
    <div class="card-body">
        <div v-if="getCompanyQuestions && getCompanyQuestions.length > 0">
            <div v-for="(question, index) in getCompanyQuestions" :key="question.id" v-b-toggle="`collapse-${index}`">
                <div>
                    <div class="card border-primary card-collapsed">
                        <div class="card-header">
                                <h5 class="card-title">{{ question.title }}</h5>
                                <div class="card-options">
                                    <a href="#" class="card-options-collapse" style="display:block;" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                </div>
                        </div>
                      
                        <div class="card-body">
                            <div class="card-text">{{ question.text }}</div>
                            <!-- <b-collapse :id="`collapse-${index}`" class="mt-4">
                            </b-collapse> -->
                            <br/>
                            <button class="btn btn-sm btn-danger" @click.prevent="deleteQuestion(question.id)" :disabled="deleting"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            <button class="btn btn-sm btn-warning" @click.prevent="editQuestion(question)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center" v-else>Nicio întrebare adăugată. Îți recomandăm să adaugi întrebări și răspunsuri pe care clienții le pot avea despre serviciile tale.</p>
    </div>

    <b-modal v-model="modalShow" title="Adaugă o întrebare cu răspuns." hide-footer centered>
        <p class="text-small text-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Recomandăm scrierea folosind diacritice.</p>
        <SaveQuestionForm />
    </b-modal>

    <b-modal v-model="modalShowEdit" title="Editează întrebare." hide-footer centered>
        <p class="text-small text-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Recomandăm scrierea folosind diacritice.</p>
        <EditQuestionForm :question="selected_question" @closeModal="closeEditModal" />
    </b-modal>

</div>
</template>

<script>

import SaveQuestionForm from "./SaveQuestionForm";
import EditQuestionForm from "./EditQuestionForm";
import {mapGetters} from 'vuex';

export default {
    name: "CompanyProfileQuestions",

    components: {
        SaveQuestionForm,
        EditQuestionForm,
    },

    data() {
      return {
        modalShow: false,
        modalShowEdit: false,
        deleting: false,
        selected_question: null
      }
    },

    computed: {
        ...mapGetters('company_questions', ['getCompanyQuestions'])
    },

    methods: {
        addQuestion: function(){
            // this.resetData();
            this.modalShow = !this.modalShow;
        },

        editQuestion: function(question){
            this.selected_question = question;
            this.modalShowEdit = !this.modalShowEdit;
        },

        closeEditModal: function(){
            this.modalShowEdit = false;
            this.selected_question = null;
        },
        

        deleteQuestion: async function(id){
            this.deleting = true;

            await this.$swal({
                title: 'Eliminare întrebare!',
                text: "Ești sigur că vrei să ștergi această întrebare?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            })
            .then(async (result) => {
                if (result.isConfirmed) {
                    await axios.post(`/api/company_questions/delete/${id}`)
                    .then(async response => {
                        // console.log('refuzare', response.data);
                        if(response.data.result == true){
                            // arata toast success. centru
                            Vue.$toast.open({
                                message: 'Întrebare eliminată cu succes!',
                                type: 'success',
                                duration: 6000,
                                position: 'bottom'
                            });

                            await this.$store.dispatch('company_questions/initCompanyQuestions');
                        
                        } else {
                            // ceva nu a functionat. arata toast eroare. centru
                            Vue.$toast.open({
                                message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                                type: 'error',
                                duration: 6000,
                                position: 'bottom'
                            });
                        }
                    })
                    .catch(error => {
                        // ceva nu a functionat. arata toast eroare. centru
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                            type: 'error',
                            duration: 6000,
                            position: 'bottom'
                        });
                    });

                }})
                .finally(() => {
                    this.deleting = false;
                });
        }
    },

    created(){
        this.$store.dispatch('company_questions/initCompanyQuestions');
    }
}
</script>

<style>

</style>