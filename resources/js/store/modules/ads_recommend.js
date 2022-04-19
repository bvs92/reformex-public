import axios from 'axios';

export default {

    namespaced: true,

    state: {
        ads: null,
        ad: null,
        total: 0,
        errors: []
    },

    getters: {
        getAds: function(state){
            return state.ads;
        },

        getAd: function(state){
            return state.ad;
        },

        getTotalAds: function(state){
            return state.total;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        loadAds: async function({commit}, type){
            await axios.get('/api/ads_recommend/load/' + type).then(async response => {

                if(response.data.ads){
                    let ads = Object.values(response.data.ads);

                    await commit('set_ads', ads);
                    await commit('set_total_ads', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        loadProcessingAds: async function({commit}){
            await axios.get('/api/ads_recommend/processing/get').then(async response => {

                if(response.data.ads){
                    let ads = Object.values(response.data.ads);

                    await commit('set_ads', ads);
                    await commit('set_total_ads', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        getSingle: async function({commit}, uuid){
            await axios.get('/api/ads_recommend/single/' + uuid).then(async response => {
                // console.log('get single ad', response.data.ad);
                if(response.data.ad){
                    await commit('set_ad', response.data.ad);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        // all

        allAds: async function({commit}){
            await axios.get('/api/ads_recommend/personal/get/all').then(async response => {

                if(response.data.ads){
                    let ads = Object.values(response.data.ads);

                    await commit('set_ads', ads);
                    await commit('set_total_ads', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },
    },

    mutations: {
        set_ads: function(state, _ads){
            state.ads = _ads;
        },
        set_ad: function(state, _ad){
            state.ad = _ad;
        },
        set_total_ads: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        }
    }
}