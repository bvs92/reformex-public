import axios from 'axios';

export default {
    namespaced: true,

    state: {
        roles: [],
        errors: [],
        validation_errors: [],
        total: 0
    },

    getters: {
        getRoles: function(state){
            return state.roles;
        },

        getTotalRoles: function(state){
            return state.total;
        },

        getErrros: function(state){
            return state.errors;
        },

        getValidationErrros: function(state){
            return state.validation_errors;
        }
    },

    actions: {
        initRoles: async function({commit}){
            await axios.get('/api/roles/get/all').then(async response => {
                // console.log('rolurile sunt', response.data);
                if(response.data.roles){
                    await commit('set_roles', response.data.roles);
                    await commit('set_total', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },

        // createRole: async function({commit}, _payload){
        //     await axios.post('/api/roles/store', _payload).then(async response => {
        //         if(response.data.success){
        //             await commit('insert_role', response.data.role);
        //         } else if(response.data.validation_errors){
        //             await commit('set_validation_errors', response.data.validation_errors);
        //         } else if(response.data.errors){
        //             await commit('set_errors', response.data.errors);
        //         }
        //     });
        // }
    },

    mutations: {
        set_roles: function(state, _roles){
            state.roles = _roles;
        },
        insert_role: function(state, _role){
            state.roles = [_role, ...state.roles];
        },
        set_total: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_validation_errors: function(state, _errors){
            state.validation_errors = _errors;
        },
    }
}