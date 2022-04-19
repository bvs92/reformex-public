import axios from 'axios';


export default {

    namespaced: true,

    state: {
        users: null,
        total: 0,
        errors: null
    },

    getters: {
        getUsers: function(state){
            return state.users;
        },
        getTotalUsers: function(state){
            return state.total;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        initProsUsers: async function({commit}){
            await axios.get('/api/companies/get/inactive').then(async response => {
                console.log('companies/get/inactive', response.data);
                if(response.data.users){
                    let users = Object.values(response.data.users);

                    await commit('set_users', users);
                    await commit('set_total_users', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

    },

    mutations: {
        set_users: function(state, _users){
            state.users = _users;
        },

        set_total_users: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        }
    }
}