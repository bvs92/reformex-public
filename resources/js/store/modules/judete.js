import axios from 'axios';

export default {
    namespaced: true,

    state: {
        user_judete: []
    },

    getters: {
        getUserJudete: function(state){
            return state.user_judete;
        }
    },

    actions: {

        initUserJudete: async function({commit}){
            await axios.get(`/api/judete/personal`).then(response => {
                // console.log('judete personale ', response.data);
                if(response.data.judete){
                    commit('set_personal_judete', response.data.judete);
                }
            });
        },

        deleteJudet: function({commit, state}, judet_id){
            axios.post(`/api/judete/personal/delete/`, {
                id: judet_id
            }).then(response => {
                if(response.data.success){
                    let new_judete = state.user_judete.filter(element => {
                        if(element.id != judet_id){
                            return element;
                        }
                    });
                    commit('set_personal_judete', new_judete);
                }
            });

        }
        
    },

    mutations: {
        set_personal_judete: function(state, _judete){
            state.user_judete = _judete;
        }
    }
}