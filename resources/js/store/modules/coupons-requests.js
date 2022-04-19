import axios from 'axios';

export default {
    namespaced: true,

    state: {

        personal_coupons_requests: [],
        all_coupons_requests: [],
        personal_pending_coupons_requests: [],
 
        total_personal_requests: 0,
        total_all_requests: 0,
        total_personal_pending_requests: 0,

        validation_errors: [],
   
        loadingStatus: false,
    },

    getters: {


        getPersonalCouponsRequests: function(state){
            return state.personal_coupons_requests;
        },

        getPersonalPendingCouponsRequests: function(state){
            return state.personal_pending_coupons_requests;
        },

        getAllCouponsRequests: function(state){
            return state.all_coupons_requests;
        },



        getTotalPersonalCouponsRequests: function(state){
            return state.total_personal_requests;
        },

        getTotalPersonalPendingCouponsRequests: function(state){
            return state.total_personal_pending_requests;
        },

        getTotalAllCouponsRequests: function(state){
            return state.total_all_requests;
        },

        getValidationErrors: function(state){
            return state.validation_errors;
        },

        getLoadingStatus: function(state){
            return state.loadingStatus;
        }
    },

    actions: {
   

        initPersonalCouponsRequests: async function({commit}){
            await axios.get('/api/coupons/requests/initialize/personal').then(async response => {
                // console.log('preluam personal coupons requests', response.data);
                if(response.data.requests){
                    let requests = Object.values(response.data.requests);

                    await commit('set_personal_coupons_requests', requests);
                    await commit('set_total_personal_coupons_requests', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initAllCouponsRequests: async function({commit}, _type){
     
            await axios.get(`/api/coupons/requests/initialize/all?type=${_type}`).then(async response => {
                // console.log('preluam all coupons requests', response.data);
                if(response.data.requests){
                    let requests = Object.values(response.data.requests);

                    await commit('set_all_coupons_requests', requests);
                    await commit('set_total_all_coupons_requests', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        initPersonalPendingCouponsRequests: async function({commit}){
            await axios.get('/api/coupons/requests/get/pending/personal').then(async response => {
                if(response.data.requests){
                    let requests = Object.values(response.data.requests);

                    await commit('set_personal_pending_coupons_requests', requests);
                    await commit('set_total_personal_pending_coupons_requests', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        // actions
        refuseCoupon: async function({commit, state}, _request_id){
            await axios.post(`/api/coupons/requests/refuse/${_request_id}`).then(async response => {
                // console.log('refuzare', response.data);
                if(response.data.success){
                    // map
                    state.all_coupons_requests = state.all_coupons_requests.map((item) => {
                        if(item.id == _request_id){
                            item.status = 0;
                        }

                        return item;
                    });
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        filterAllCouponsRequests: async function({commit, state}, _request_id){
            let all_coupons_requests = state.all_coupons_requests.map((item) => {
                if(item.id == _request_id){
                    item.status = 1;
                }

                return item;
            });

            await commit('set_all_coupons_requests', all_coupons_requests);
        }

        // sendCoupon: async function({commit, state}, _request_id, _coupon){
        //     let formData = new FormData();
        //     formData.append('coupon', _coupon);
        //     await axios.post(`/api/coupons/requests/accept/${_request_id}`, formData)
        //     .then(async response => {
        //         console.log('acceptare', response.data);
        //         if(response.data.success){
        //             // map
        //             state.all_coupons_requests = state.all_coupons_requests.map((item) => {
        //                 if(item.id == _request_id){
        //                     item.status = 1;
        //                 }

        //                 return item;
        //             });
        //         } else if(response.data.errors){
        //             await commit('set_errors', Object.values(response.data.errors));
        //         } else if(response.data.validation_errors){
        //             await commit('set_validation_errors', Object.values(response.data.validation_errors));
        //         }
        //     });
        // }


    },

    mutations: {
    

        set_personal_coupons_requests: function(state, _coupons){
            state.personal_coupons_requests = _coupons;
        },

        set_personal_pending_coupons_requests: function(state, _coupons){
            state.personal_pending_coupons_requests = _coupons;
        },

        set_all_coupons_requests: function(state, _coupons){
            state.all_coupons_requests = _coupons;
        },


        set_total_personal_coupons_requests: function(state, _total_coupons){
            state.total_personal_requests = _total_coupons;
        },

        set_total_personal_pending_coupons_requests: function(state, _total_coupons){
            state.total_personal_pending_requests = _total_coupons;
        },

        set_total_all_coupons_requests: function(state, _total_coupons){
            state.total_all_requests = _total_coupons;
        },

        set_validation_errors: function(state, _errors){
            state.validation_errors = _errors;
        },

  
        set_loading_status: function(state, _status){
            state.loadingStatus = _status;
        }
    },


}