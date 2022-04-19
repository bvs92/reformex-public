import axios from 'axios';

export default {
    namespaced: true,

    state: {
        charges: [],
        total: 0,
        personal_charges: [],
        total_personal_charges: 0,

        last_payment: null
    },

    getters: {
        getUserCharges: function(state){
            return state.charges;
        },
        getTotalUserCharges: function(state){
            return state.total;
        },

        getPersonalCharges: function(state){
            return state.personal_charges;
        },
        getTotalPersonalCharges: function(state){
            return state.total_personal_charges;
        },
        getLastPayment: function(state){
            return state.last_payment;
        }
    },

    actions: {
        initUserCharges: async function({commit}, _id){
            await axios.get(`/api/charges/user/${_id}`).then(response => {
                // console.log('charges is', response.data);
                if(response.data.charges){
                    commit('set_charges', response.data.charges);
                    commit('set_total_charges', response.data.total);
                }
            });
        },

        initPersonalCharges: async function({commit}){
            await axios.get(`/api/charges/personal`).then(response => {
                // console.log('charges personal is', response.data);
                if(response.data.charges){
                    commit('set_personal_charges', response.data.charges);
                    commit('set_total_personal_charges', response.data.total);
                }
            });
        },

        initUserLastPayment: async function({commit}, _id){
            await axios.get(`/api/charges/user/${_id}/last/payment`).then(response => {
                // console.log('last payment is', response.data);
                if(response.data.last_payment){
                    commit('set_last_payment', response.data.last_payment);
                }
            });
        }
    },

    mutations: {
        set_charges: function(state, _charges){
            state.charges = _charges;
        },

        set_total_charges: function(state, _total_charges){
            state.total = _total_charges;
        },

        set_personal_charges: function(state, _charges){
            state.personal_charges = _charges;
        },

        set_total_personal_charges: function(state, _total_charges){
            state.total_personal_charges = _total_charges;
        },

        set_last_payment: function(state, _last){
            state.last_payment = _last;
        },
    }
}