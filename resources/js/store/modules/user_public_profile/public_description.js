import axios from 'axios';

export default {
    namespaced: true,

    state: {
        user_public_description: ''
    },

    getters: {
        getUserPublicDescription: function(state){
            return state.user_public_description;
        }
    },

    actions: {

        initUserPublicDescription: async function({commit}){
            await axios.get(`/api/public/user/description`).then(response => {
                // 
                if(response.data.description){
                    commit('set_user_public_description', response.data.description);
                }
            });
        },

        saveUserPublicDescription: async function({commit}, payload){
            // console.log('payload', payload);
            await axios.post(`/api/public/user/description`, {
                description: payload.description
            }).then(response => {
                // console.log('rezultat save description ', response.data);
                if(response.data.success){
                    commit('set_user_public_description', response.data.description);
                }
            });
        },
        
    },

    mutations: {
        set_user_public_description: function(state, _public_description){
            state.user_public_description = _public_description;
        }
    }
}