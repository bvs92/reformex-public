import axios from 'axios';


export default {

    namespaced: true,

    state: {
        company_questions: null,
        total_company_questions: 0,
        errors: null,
        validation_errors: null,
    },

    getters: {
        getCompanyQuestions: function(state){
            return state.company_questions;
        },

        getTotalCompanyQuestions: function(state){
            return state.total_company_questions;
        },


        getErrors: function(state){
            return state.errors;
        },

        getValidationErrors: function(state){
            return state.validation_errors;
        },
    },

    actions: {
        initCompanyQuestions: async function({commit}){
            await axios.get(`/api/company_questions/get/all`).then(async response => {
                // console.log('preluam company_questions', response.data);
                if(response.data.questions){
                    let company_questions = Object.values(response.data.questions);

                    await commit('set_company_questions', company_questions);
                    await commit('set_total_company_questions', company_questions.length);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        }
        
      
    },

    mutations: {
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_validation_errors: function(state, _errors){
            state.validation_errors = _errors;
        },
        set_company_questions: function(state, _company_questions){
            state.company_questions = _company_questions;
        },
        
        set_total_company_questions: function(state, _total){
            state.total_company_questions = _total;
        },

        
    }
}