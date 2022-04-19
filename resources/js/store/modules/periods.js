import axios from 'axios';

export default {

    namespaced: true,

    state: {
        periods: null,
        client_periods: null,
        period: null,
        total_client: 0,
        total: 0,
        errors: []
    },

    getters: {
        getPeriods: function(state){
            return state.periods;
        },

        getClientPeriods: function(state){
            return state.client_periods;
        },

        getPeriod: function(state){
            return state.period;
        },

        getTotalPeriods: function(state){
            return state.total;
        },

        getTotalClientPeriods: function(state){
            return state.total_client;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        all: async function({commit}){
            await axios.get('/api/periods/all').then(async response => {

                if(response.data.periods){
                    let periods = Object.values(response.data.periods);

                    await commit('set_periods', periods);
                    await commit('set_total_periods', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        allClient: async function({commit}){
            await axios.get('/api/periods/get/client').then(async response => {

                if(response.data.periods){
                    let periods = Object.values(response.data.periods);

                    await commit('set_client_periods', periods);
                    await commit('set_total_client_periods', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        getSingle: async function({commit}, id){
            await axios.get('/api/periods/single/' + id).then(async response => {
                // console.log('get single period', response.data.period);
                if(response.data.period){
                    await commit('set_period', response.data.period);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        // delete: async function({commit, state}, id){
        //     await axios.delete('/api/periods/delete/' + id).then(async response => {
        //         // console.log('get single period', response.data.period);
        //         if(response.data.success){
        //             let newPeriods = state.periods.filter((item) => {
        //                 if(item.id !== id){
        //                     return item;
        //                 }
        //             });
        //             await commit('set_periods', newPeriods);
        //             await commit('set_total_periods', newPeriods.length);
        //         } else if(response.data.errors){
        //             await commit('set_errors', Object.values(response.data.errors));
        //         }
        //     });
        // }
    },

    mutations: {
        set_periods: function(state, _periods){
            state.periods = _periods;
        },
        set_client_periods: function(state, _periods){
            state.client_periods = _periods;
        },
        set_period: function(state, _period){
            state.period = _period;
        },
        set_total_periods: function(state, _total){
            state.total = _total;
        },

        set_total_client_periods: function(state, _total){
            state.total_client = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        }
    }
}