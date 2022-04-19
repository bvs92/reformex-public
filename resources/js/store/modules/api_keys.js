import axios from 'axios';

export default {
    namespaced: true,

    state: {
        api_keys: [],
        total_api_keys: 0,
    },

    getters: {
        getApiKeys: function(state){
            return state.api_keys;
        },

        getTotalApiKeys: function(state){
            return state.total_api_keys;
        },
    },

    actions: {
        initApiKeys: async function({commit}){
            await axios.get(`/api/keys`).then(response => {
                if(response.data.keys){
                    commit('set_api_keys', response.data.keys);
                    commit('set_total_api_keys', response.data.keys.length);
                }
            });
        },

        delete: async function({commit, state}, _id){
            await axios.post(`/api/keys/delete/` + _id).then(response => {
                if(response.data.result == true){
                    let result_items = state.api_keys.filter(item => {
                        if(item.id != _id){
                            return item;
                        } 
                    });

                    commit('set_api_keys', result_items);
                    commit('set_total_api_keys', result_items.length);
                }
            });
        },

        create_token: async function({commit}){
            await axios.post(`/api/keys/store`).then(response => {
                if(response.data.result == true){
                    commit('set_api_keys', response.data.keys);
                    commit('set_total_api_keys', response.data.keys.length);
                }
            }).catch(error => {
                console.log('error');
            });
        },
        
    },

    mutations: {
        set_api_keys: function(state, _api_keys){
            state.api_keys = _api_keys;
        },
        set_total_api_keys: function(state, _total_api_keys){
            state.total_api_keys = _total_api_keys;
        }
    }
}