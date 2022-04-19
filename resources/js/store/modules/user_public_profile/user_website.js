import axios from 'axios';

export default {
    namespaced: true,

    state: {
        user_website: ''
    },

    getters: {
        getUserWebsite: function(state){
            return state.user_website;
        }
    },

    actions: {

        initUserWebsite: async function({commit}){
            await axios.get(`/api/public/user/website`).then(response => {
                // 
                if(response.data.website){
                    commit('set_user_website', response.data.website);
                }
            });
        },

        saveUserWebsite: async function({commit}, payload){
            // console.log('payload', payload);
            await axios.post(`/api/public/user/website`, {
                website: payload.website
            }).then(response => {
                // console.log('rezultat save website ', response.data);
                if(response.data.success){
                    commit('set_user_website', response.data.website);
                }
            });
        },
        
    },

    mutations: {
        set_user_website: function(state, _user_website){
            state.user_website = _user_website;
        }
    }
}