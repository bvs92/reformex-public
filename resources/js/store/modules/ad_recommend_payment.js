import axios from 'axios';
import { method } from 'lodash';

export default {

    namespaced: true,

    state: {
       cost: null
    },

    getters: {
       getCost: function(state){
           return state.cost;
       }
    },

    actions: {
        calculateCost: async function({commit}, payload){
            await axios.post('/api/ads_recommend/personal/calculate/' + payload.uuid + '/' + payload.period).then(async response => {
                // console.log(response.data);
                if(response.data.cost){
                    let cost = response.data.cost;
                    await commit('set_cost', cost);
                } 
            });
        },

        // payForAnnounce: async function({commit}, payload){
        //     await axios(
        //         '/api/banners/personal/plata/' + payload.uuid + '/' + payload.period,
        //         {
        //             method: 'POST',
        //             mode: 'no-cors',
        //             headers: {
        //                 'Access-Control-Allow-Origin' : '*',
        //             }
        //         }
        //     ).then(async response => {
        //         console.log(response.data);
        //         if(response.data.success){
        //             console.log('success');
        //         } else {
        //             console.log('error');
        //         }
        //     });
        // },

    },

    mutations: {
        set_cost: function(state, _cost){
            state.cost = _cost;
        }
    }
}