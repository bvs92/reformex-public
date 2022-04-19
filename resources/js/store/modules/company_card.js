import axios from 'axios';


export default {

    namespaced: true,

    state: {
        company_card: null,
        loading: false
    },

    getters: {
        getCompanyCard: function(state){
            return state.company_card;
        },
        getLoading: function(state){
            return state.loading;
        }
    },

    actions: {
        initCompanyCard: async function({commit}){
            await commit('set_loading', true);
            await axios.get(`/api/company_card/get`).then(async response => {
                if(response.data.card != null){
                    await commit('set_company_card', response.data.card);
                }
            }).finally(() => {
                commit('set_loading', false);
            });
        }
        
      
    },

    mutations: {
        set_company_card: function(state, _company_card){
            state.company_card = _company_card;
        },
        set_loading: function(state, _loading){
            state.loading = _loading;
        }
    }
}