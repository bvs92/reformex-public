import axios from 'axios';

export default {
    namespaced: true,

    state: {
        user_activities: [],
        total_user_activities: 0,
        personal_activities: [],
        total_personal_activities: 0,
    },

    getters: {
        getUserActivities: function(state){
            return state.user_activities;
        },

        getTotalUserActivities: function(state){
            return state.total_user_activities;
        },

        getPersonalActivities: function(state){
            return state.personal_activities;
        },

        getTotalPersonalActivities: function(state){
            return state.total_personal_activities;
        },
    },

    actions: {
        initUserActivities: async function({commit}, _id){
            await axios.get(`/api/activities/user/${_id}`).then(response => {
                // console.log('activitate user ', response.data);
                if(response.data.activities){
                    commit('set_user_activities', response.data.activities);
                    commit('set_total_user_activities', response.data.total_activities);
                }
            });
        },

        initPersonalActivities: async function({commit}){
            await axios.get(`/api/activities/personal/`).then(response => {
                // console.log('activitate personala ', response.data);
                if(response.data.activities){
                    commit('set_personal_activities', response.data.activities);
                    commit('set_total_personal_activities', response.data.total_activities);
                }
            });
        },
        
    },

    mutations: {
        set_user_activities: function(state, _activities){
            state.user_activities = _activities;
        },
        set_total_user_activities: function(state, _total_activities){
            state.total_user_activities = _total_activities;
        },

        set_personal_activities: function(state, _activities){
            state.personal_activities = _activities;
        },
        set_total_personal_activities: function(state, _total_activities){
            state.total_personal_activities = _total_activities;
        },
    }
}