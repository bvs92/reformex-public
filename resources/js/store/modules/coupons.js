import axios from 'axios';

export default {
    namespaced: true,

    state: {
        coupons: [],
        personal_coupons: [],
     
        user_coupons: [],
        user_activated_coupons: [],
        coupon: null,
        personal_coupon: null,
        total: 0,
        total_personal: 0,
      
        total_user_coupons: 0,
        total_user_activated_coupons: 0,
        users: [],
        loadingStatus: false,
    },

    getters: {
        getCoupons: function(state){
            return state.coupons;
        },

        getPersonalCoupons: function(state){
            return state.personal_coupons;
        },


        getUserCoupons: function(state){
            return state.user_coupons;
        },

        getUserActivatedCoupons: function(state){
            return state.user_activated_coupons;
        },

        getCoupon: function(state){
            return state.coupon;
        },

        getPersonalCoupon: function(state){
            return state.personal_coupon;
        },

        getTotalCoupons: function(state){
            return state.total;
        },

        getTotalPersonalCoupons: function(state){
            return state.total_personal;
        },


        getTotalUserCoupons: function(state){
            return state.total_user_coupons;
        },

        getTotalUserActivatedCoupons: function(state){
            return state.total_user_activated_coupons;
        },

        getUsers: function(state){
            return state.users;
        },
        getLoadingStatus: function(state){
            return state.loadingStatus;
        }
    },

    actions: {
        initCoupons: async function({commit}){
            await axios.get('/api/coupons/initialize').then(async response => {
                // console.log('preluam coupons', response.data);
                if(response.data.coupons){
                    let coupons = Object.values(response.data.coupons);

                    await commit('set_coupons', coupons);
                    await commit('set_total_coupons', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initPersonalCoupons: async function({commit}){
            await axios.get('/api/coupons/initialize/personal').then(async response => {
                // console.log('preluam personal coupons', response.data);
                if(response.data.coupons){
                    let coupons = Object.values(response.data.coupons);

                    await commit('set_personal_coupons', coupons);
                    await commit('set_total_personal_coupons', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },


        initCoupon: async function({commit}, __id){
            await axios.get(`/api/coupons/single/${__id}`).then(async response => {
                // console.log('preluam coupon', response.data);
                if(response.data.coupon){
                    // let coupon = Object.values(response.data.coupon);
                    let coupon = response.data.coupon;

                    await commit('set_coupon', coupon);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initPersonalCoupon: async function({commit}, __id){
            await axios.get(`/api/coupons/personal/single/${__id}`).then(async response => {
                // console.log('preluam coupon', response.data);
                if(response.data.coupon){
                    // let coupon = Object.values(response.data.coupon);
                    let coupon = response.data.coupon;

                    await commit('set_personal_coupon', coupon);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initUserCoupons: async function({commit}, __id){
            await axios.get(`/api/coupons/user/${__id}`).then(async response => {
                // console.log('preluam coupons', response.data);
                if(response.data.coupons){
                    // let coupon = Object.values(response.data.coupon);
                    let user_coupons = response.data.coupons;

                    await commit('set_user_coupons', user_coupons);
                    await commit('set_total_user_coupons', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initUserActivatedCoupons: async function({commit}, __id){
            await axios.get(`/api/coupons/user/${__id}/activated`).then(async response => {
                // console.log('preluam coupons', response.data);
                if(response.data.coupons){
                    // let coupon = Object.values(response.data.coupon);
                    let user_coupons = response.data.coupons;

                    await commit('set_user_activated_coupons', user_coupons);
                    await commit('set_total_user_activated_coupons', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initUsers: async function({commit}){
            commit('set_loading_status', true);
            await axios.get(`/api/coupons/get/users`).then(response => {
                // console.log('users sunt urmatorii', response.data);
                commit('set_users', response.data.users);
            }).finally(() => {
                commit('set_loading_status', false);
            });
        }
    },

    mutations: {
        set_coupons: function(state, _coupons){
            state.coupons = _coupons;
        },

        set_personal_coupons: function(state, _coupons){
            state.personal_coupons = _coupons;
        },

     

        set_user_coupons: function(state, _coupons){
            state.user_coupons = _coupons;
        },

        set_user_activated_coupons: function(state, _coupons){
            state.user_activated_coupons = _coupons;
        },

        set_coupon: function(state, _coupon){
            state.coupon = _coupon;
        },
        
        set_personal_coupon: function(state, _coupon){
            state.personal_coupon = _coupon;
        },

        set_total_coupons: function(state, _total_coupons){
            state.total = _total_coupons;
        },

        set_total_personal_coupons: function(state, _total_coupons){
            state.total_personal = _total_coupons;
        },

        set_total_user_coupons: function(state, _total_coupons){
            state.total_user_coupons = _total_coupons;
        },

        set_total_user_activated_coupons: function(state, _total_coupons){
            state.total_user_activated_coupons = _total_coupons;
        },

        set_users: function(state, _users){
            state.users = _users;
        },
        set_loading_status: function(state, _status){
            state.loadingStatus = _status;
        }
    },


}