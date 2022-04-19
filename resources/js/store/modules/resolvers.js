import axios from 'axios';


export default {
    namespaced: true,

    state: {
        resolvers: []
    },

    getters: {
        get_resolvers: function(state){
            return state.resolvers;
        }
    },

    actions: {
        initResolvers: async function({commit}, ticket_id){
            await axios.get(`/api/ticket/${ticket_id}/existing/resolvers/get`)
            .then(response => {
                console.log("RESOLVERS EXISTENTI PRELUATI", response.data);
                commit('set_resolvers', response.data.resolvers);
                // this.moderators = response.data.moderators;
            }).catch(error => {
                // console.log(error);
            }).finally(function(){});
        }
    },

    mutations: {
        set_resolvers: function(state, _resolvers){
            state.resolvers = _resolvers;
        }
    }
}