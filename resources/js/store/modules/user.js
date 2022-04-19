import axios from 'axios';


export default {

    namespaced: true,

    state: {
        user: null,
        accessToken: null
    },

    getters: {
        getCurrentUser: function(state){
            return state.user;
        },

        getAccessToken: function(state){
            return state.accessToken;
        }
    },

    actions: {

        initCurrentUser: async function({commit, state}){
            // console.log('am atins la user');
            // get unread notification
            axios.defaults.headers.common = {'Authorization': `bearer ${state.accessToken}`}

            await axios.get("/api/users/get/current").then(response => {
                window.current_user = response.data.auth_user;
                commit('set_current_user', response.data.auth_user);
                
                // console.log("window.current_user este", window.current_user);
            });
        }
    },

    mutations: {
        set_access_token: function(state, _token){
            state.accessToken = _token;
        },

        set_current_user: function(state, _user){
            state.user = _user;
        }
    }
}