import axios from 'axios';

export default {

    namespaced: true,

    state: {
        payments: null,
        total: 0
    },

    getters: {
        getPayments: function(state){
            return state.payments;
        },

        getPayment: function(state){
            return state.payment;
        },

        getTotalPayments: function(state){
            return state.total;
        },
    },

    actions: {
        personal: async function({commit}){
            await axios.get('/api/payments/personal').then(async response => {

                if(response.data.payments){
                    // let payments = Object.values(response.data.payments);
                    await commit('set_payments', response.data.payments);
                } 
            });
        },

        allPayments: async function({commit}){
            await axios.get('/api/payments/all').then(async response => {

                if(response.data.payments){
                    await commit('set_payments', response.data.payments);
                    await commit('set_total_payments', response.data.total);
                } 
            });
        },

        allPaymentsInvoices: async function({commit}){
            await axios.get('/api/payments/all/invoices').then(async response => {

                if(response.data.payments){


                    await commit('set_payments', Object.values(response.data.payments));
                    await commit('set_total_payments', response.data.total);
                } 
            });
        },

        single: async function({commit}, _uuid){
            await axios.get('/api/payments/single/' + _uuid).then(async response => {

                if(response.data.payment){
                    // let payments = Object.values(response.data.payments);
                    await commit('set_payment', response.data.payment);
                } 
            });
        }

        


    },

    mutations: {
        set_payments: function(state, _payments){
            state.payments = _payments;
        },

        set_payment: function(state, _payment){
            state.payment = _payment;
        },

        set_total_payments: function(state, _total){
            state.total = _total;
        },
    }
}