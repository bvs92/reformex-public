import axios from 'axios';


export default {

    namespaced: true,

    state: {
        announcements: null,
        total_announcements: 0,
        single_announcement: null,
        errors: null,
        validation_errors: null,
        active_announcements: null,
        total_active_announcements: 0
    },

    getters: {
        getAnnouncements: function(state){
            return state.announcements;
        },
        getTotalAnnouncements: function(state){
            return state.total_announcements;
        },

        getActiveAnnouncements: function(state){
            return state.active_announcements;
        },
        getTotalActiveAnnouncements: function(state){
            return state.total_active_announcements;
        },
        
        getSingleAnnouncement: function(state){
            return state.single_announcement;
        },

        getErrors: function(state){
            return state.errors;
        },

        getValidationErrors: function(state){
            return state.validation_errors;
        },
    },

    actions: {
        initAnnouncements: async function({commit}){
            await axios.get(`/api/announcements/index`).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.announcements){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_announcements', announcements);
                    await commit('set_total_announcements', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },

        initActiveAnnouncements: async function({commit}){
            await axios.get(`/api/announcements/get/active`).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.announcements){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_active_announcements', announcements);
                    await commit('set_total_active_announcements', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },


        // initSingleAnnouncement: async function({commit, state}, payload){
        //     state.single_announcement = null;
        //     await axios.get(`/api/announcements/${payload}`).then(async response => {
        //         // console.log('preluam announcements', response.data);
        //         if(response.data.announcement){
        //             let announcement = Object.values(response.data.announcement);
        //             // return announcement;
        //             await commit('set_single_announcement', announcement);
        //         } else if(response.data.errors){
        //             await commit('set_errors', Object.values(response.data.errors));
        //         }
        //     });
        // },


        deleteAnnouncement: async function({commit}, id){
            await axios.post(`/api/announcements/delete/${id}`).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.success){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_announcements', announcements);
                    await commit('set_total_announcements', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },

        toggleStatus: async function({commit}, id){
            await axios.post(`/api/announcements/toggleStatus/${id}`).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.success){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_announcements', announcements);
                    await commit('set_total_announcements', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },

        changeType: async function({commit}, payload){
            let formData = new FormData();
            formData.append('type', payload.type);
            await axios.post(`/api/announcements/changeType/${payload.id}`, formData).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.success){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_announcements', announcements);
                    await commit('set_total_announcements', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                }
            });
        },

        createAnnouncement: async function({commit}, payload){
            await commit('set_errors', null);
            await commit('set_validation_errors', null);

            await axios.post(`/api/announcements/store`, payload).then(async response => {
                // console.log('preluam announcements', response.data);
                if(response.data.success){
                    let announcements = Object.values(response.data.announcements);

                    await commit('set_announcements', announcements);
                    await commit('set_total_announcements', response.data.total);
                  
                } else if(response.data.errors){
                    await commit('set_errors', response.data.errors);
                    
                } else if(response.data.validation_errors){
                    await commit('set_validation_errors', response.data.validation_errors);
                   
                }
            });
        },

        
      
    },

    mutations: {
        set_errors: function(state, _errors){
            state.errors = _errors;
        },
        set_validation_errors: function(state, _errors){
            state.validation_errors = _errors;
        },
        set_announcements: function(state, _announcements){
            state.announcements = _announcements;
        },
        
        set_total_announcements: function(state, _total){
            state.total_announcements = _total;
        },

        set_active_announcements: function(state, _announcements){
            state.active_announcements = _announcements;
        },
        
        set_total_active_announcements: function(state, _total){
            state.total_active_announcements = _total;
        },

        set_single_announcement: function(state, _announcement){
            state.single_announcement = _announcement;
        },
        
    }
}