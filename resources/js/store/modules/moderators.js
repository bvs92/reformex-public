import axios from 'axios';

export default {

    namespaced: true,

    state: {
        moderators: []
    },

    getters: {
        get_moderators: function(state){
            return state.moderators;
        }
    },

    actions: {
        initModerators: async function({commit}){
            await axios.get('/api/users/moderators/get')
            .then(response => {
                console.log("MODERATOR PRELUAT", response.data);
                commit('set_moderators', response.data.moderators);
                // this.moderators = response.data.moderators;
            }).catch(error => {
                // console.log(error);
            }).finally(function(){});
        },
    },

    mutations: {
        set_moderators: function(state, _moderators){
            state.moderators = _moderators;
        }
    }
}