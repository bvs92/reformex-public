import axios from 'axios';

export default {
    namespaced: true,

    state: {
        hasAvatar: false,
        avatarPath: null
    },

    getters: {
        getHasAvatar: function(state){
            return state.hasAvatar;
        },
        getAvatarPath: function(state){
            return state.avatarPath;
        },
    },
    
    actions: {
        initAvatar: async function({commit}){
            await axios.get(`/api/users/avatar/get`).then(response => {
                if(response.data.success){
                    // let avatarPath = '/' + response.data.avatar;
                    let avatarPath = response.data.avatar;
                    commit('set_has_avatar', true);
                    commit('set_avatar_path', avatarPath);
                } else {
                    commit('set_has_avatar', false);
                    commit('set_avatar_path', null);
                }
              });
        }
    },


    mutations: {
        set_has_avatar: function(state, _has_avatar){
            state.hasAvatar = _has_avatar;
        },
        set_avatar_path: function(state, _avatar){
            state.avatarPath = _avatar;
        }
    },

}