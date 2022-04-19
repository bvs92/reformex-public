import axios from 'axios';

export default {
    namespaced: true,

    state: {
        credit: 0
    },

    getters: {
        getCredit: function(state){
            return state.credit;
        },
    },
    
    actions: {
        initUserCredit: async function({commit}, __id){
            await axios.get(`/api/credits/user/${__id}`).then(async response => {
                if(response.data.success){
                    await commit('set_credit', response.data.credit);
                }
            });
        }
    },


    mutations: {
        set_credit: function(state, _credit){
            state.credit = _credit;
        }
    },

}