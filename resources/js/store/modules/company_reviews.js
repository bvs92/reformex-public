import axios from 'axios';


export default {

    namespaced: true,

    state: {
        company_reviews: null,
        total_company_reviews: 0,
        errors: null,
        validation_errors: null,
    },

    getters: {
        getCompanyReviews: function(state){
            return state.company_reviews;
        },
        getTotalCompanyReviews: function(state){
            return state.total_company_reviews;
        },


        getErrors: function(state){
            return state.errors;
        },

        getValidationErrors: function(state){
            return state.validation_errors;
        },
    },

    actions: {
        initCompanyReviews: async function({commit}){
            await axios.get(`/api/company_reviews/get/all`).then(async response => {
                console.log('preluam company_reviews', response.data);
                if(response.data.reviews){
                    let company_reviews = Object.values(response.data.reviews);

                    await commit('set_company_reviews', company_reviews);
                    await commit('set_total_company_reviews', company_reviews.length);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },


        // deleteAnnouncement: async function({commit}, id){
        //     await axios.post(`/api/company_reviews/delete/${id}`).then(async response => {
        //         // console.log('preluam company_reviews', response.data);
        //         if(response.data.success){
        //             let company_reviews = Object.values(response.data.company_reviews);

        //             await commit('set_company_reviews', company_reviews);
        //             await commit('set_total_company_reviews', response.data.total);
        //         } else if(response.data.errors){
        //             await commit('set_errors', response.data.errors);
        //         }
        //     });
        // },

        toggleStatus: async function({commit}, id){
            // await axios.post(`/api/company_reviews/toggleStatus/${id}`).then(async response => {
            //     if(response.data.success){
            //         // let company_reviews = Object.values(response.data.company_reviews);

            //         // await commit('set_company_review_status', 1);
            //         // await commit('set_total_company_reviews', response.data.total);
            //     } else if(response.data.errors){
            //         await commit('set_errors', response.data.errors);
            //     }
            // });
        },



        
      
    },

    mutations: {
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_validation_errors: function(state, _errors){
            state.validation_errors = _errors;
        },
        set_company_reviews: function(state, _company_reviews){
            state.company_reviews = _company_reviews;
        },
        
        set_total_company_reviews: function(state, _total){
            state.total_company_reviews = _total;
        },

        set_company_review_status: function(state, _total){
            state.total_company_reviews = _total;
        }
        
    }
}