import axios from 'axios';


export default {

    namespaced: true,

    state: {
        users: null,
        total: 0,
        demands: null,
        total_demands: 0,
        unlocked_demands: null,
        total_unlocked_demands: 0,
    },

    getters: {
        getUsers: function(state){
            return state.users;
        },
        getTotalUsers: function(state){
            return state.total;
        },

        getDemands: function(state){
            return state.demands;
        },
        getTotalDemands: function(state){
            return state.total_demands;
        },
        
        getUnlockedDemands: function(state){
            return state.unlocked_demands;
        },
        getTotalUnlockedDemands: function(state){
            return state.total_unlocked_demands;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        initUsers: async function({commit}){
            await axios.get('/api/users/get/all').then(async response => {
                // console.log('preluam users', response.data);
                if(response.data.users){
                    let users = Object.values(response.data.users);

                    await commit('set_users', users);
                    await commit('set_total_users', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },
        initProsUsers: async function({commit}){
            await axios.get('/api/users/get/all/pros').then(async response => {
                // console.log('preluam users pros', response.data);
                if(response.data.users){
                    let users = Object.values(response.data.users);

                    await commit('set_users', users);
                    await commit('set_total_users', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initDemands: async function({commit}, _id){
            await axios.get(`/api/users/${_id}/get/demands`).then(async response => {
                // console.log('preluam user demands', response.data);
                if(response.data.demands){
                    let demands = Object.values(response.data.demands);

                    await commit('set_demands', demands);
                    await commit('set_total_demands', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initUnlockedDemands: async function({commit}, _id){
            await axios.get(`/api/users/${_id}/get/unlocked/demands`).then(async response => {
                // console.log('preluam user unlocked demands', response.data);
                if(response.data.demands){
                    let demands = Object.values(response.data.demands);

                    await commit('set_unlocked_demands', demands);
                    await commit('set_total_unlocked_demands', response.data.total);
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
        insert_user: function(state, _user){
            state.users = [_user, ...state.users];
        },
        set_total_users: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_demands: function(state, _demands){
            state.demands = _demands;
        },
        set_total_demands: function(state, _total){
            state.total_demands = _total;
        },
        set_unlocked_demands: function(state, _demands){
            state.unlocked_demands = _demands;
        },
        set_total_unlocked_demands: function(state, _total){
            state.total_unlocked_demands = _total;
        },
    }
}