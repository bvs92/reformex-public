import axios from 'axios';

export default {

    namespaced: true,

    state: {
        banners: null,
        banner: null,
        total: 0,
        errors: []
    },

    getters: {
        getBanners: function(state){
            return state.banners;
        },

        getBanner: function(state){
            return state.banner;
        },

        getTotalBanners: function(state){
            return state.total;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        loadBanners: async function({commit}, type){
            await axios.get('/api/banners/load/' + type).then(async response => {

                if(response.data.banners){
                    let banners = Object.values(response.data.banners);

                    await commit('set_banners', banners);
                    await commit('set_total_banners', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        loadProcessingBanners: async function({commit}){
            await axios.get('/api/banners/processing/get').then(async response => {

                if(response.data.banners){
                    let banners = Object.values(response.data.banners);

                    await commit('set_banners', banners);
                    await commit('set_total_banners', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        getSingle: async function({commit}, uuid){
            await axios.get('/api/banners/single/' + uuid).then(async response => {
                // console.log('get single banner', response.data.banner);
                if(response.data.banner){
                    await commit('set_banner', response.data.banner);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },

        // all

        allBanners: async function({commit}){
            await axios.get('/api/banners/personal/get/all').then(async response => {

                if(response.data.banners){
                    let banners = Object.values(response.data.banners);

                    await commit('set_banners', banners);
                    await commit('set_total_banners', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        },
    },

    mutations: {
        set_banners: function(state, _banners){
            state.banners = _banners;
        },
        set_banner: function(state, _banner){
            state.banner = _banner;
        },
        set_total_banners: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        }
    }
}