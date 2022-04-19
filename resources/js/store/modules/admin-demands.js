import axios from 'axios';


export default {

    namespaced: true,

    state: {
        demands: null,
        total_demands: 0,
        reported_demands: null,
        total_reported_demands: 0,
        unlocked_demands: null,
        total_unlocked_demands: 0,
    },

    getters: {
        getDemands: function(state){
            return state.demands;
        },
        getTotalDemands: function(state){
            return state.total_demands;
        },
        getReportedDemands: function(state){
            return state.reported_demands;
        },
        getTotalReportedDemands: function(state){
            return state.total_reported_demands;
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
        initDemands: async function({commit}){
            await axios.get(`/api/admin/demands/get/all`).then(async response => {
                console.log('preluam demands', response.data);
                if(response.data.demands){
                    let demands = Object.values(response.data.demands);

                    await commit('set_demands', demands);
                    await commit('set_total_demands', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },
        
        initReportedDemands: async function({commit}){
            await axios.get(`/api/admin/reported/demands/get/all`).then(async response => {
                console.log('preluam reported demands', response.data);
                if(response.data.demands){
                    let demands = Object.values(response.data.demands);

                    await commit('set_reported_demands', demands);
                    await commit('set_total_reported_demands', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initUnlockedDemands: async function({commit}){
            await axios.get(`/api/admin/demands/get/unlocked/all`).then(async response => {
                console.log('preluam unlocked demands', response.data);
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
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_demands: function(state, _demands){
            state.demands = _demands;
        },
        set_total_demands: function(state, _total){
            state.total_demands = _total;
        },
        set_reported_demands: function(state, _demands){
            state.reported_demands = _demands;
        },
        set_total_reported_demands: function(state, _total){
            state.total_reported_demands = _total;
        },
        set_unlocked_demands: function(state, _demands){
            state.unlocked_demands = _demands;
        },
        set_total_unlocked_demands: function(state, _total){
            state.total_unlocked_demands = _total;
        },
    }
}