import axios from 'axios';


export default {
    state: {
        notifications: [],
        unread_notifications: 0,

        current_page: null,
        from: null,
        last_page: null,
        per_page: null,
        total: null
    },


    getters: {
        personalNotifications: function(state){
            return state.notifications;
        },

        unreadNotifications: function(state){
            return state.unread_notifications;
        },

        getCurrentPage: function(state){
            return state.current_page;
        },
        getFrom: function(state){
            return state.from;
        },
        getLastPage: function(state){
            return state.last_page;
        },
        getPerPage: function(state){
            return state.per_page;
        },
        getTotal: function(state){
            return state.total;
        },
    },


    actions: {
        // initialize notifications
        async getNotifications({commit, state}) {
            await axios.get("/api/notifications/personal").then(async (response) => {
                // console.log('Suntem in VUEX, aici', response.data);
                let the_notifications = await response.data.personalNotifications.map(item => {
                    item.isSelected = false;
                    return item;
                });
                
                await commit('initialize_personal_notifications', the_notifications);
                await commit('initialize_unread_notifications', response.data.unreadNotifications);

                await commit('set_current_page', response.data.paginate_result.current_page);
                await commit('set_from', response.data.paginate_result.from);
                await commit('set_last_page', response.data.paginate_result.last_page);
                await commit('set_per_page', response.data.paginate_result.per_page);
                await commit('set_total', response.data.paginate_result.total);
            }).catch(function(error){
                console.error(error);
            });
        },


        async getNotificationsFromPage({commit}, _page) {
            await axios.get("/api/notifications/personal?page=" + _page).then(async (response) => {
                // console.log('s-a executat asta - getNotificationsFromPage');
                // console.log('Suntem in VUEX, aici', response.data);
                let the_notifications = await response.data.personalNotifications.map(item => {
                    item.isSelected = false;
                    return item;
                });
                
                await commit('initialize_personal_notifications', the_notifications);
                await commit('initialize_unread_notifications', response.data.unreadNotifications);

                await commit('set_current_page', response.data.paginate_result.current_page);
                await commit('set_from', response.data.paginate_result.from);
                await commit('set_last_page', response.data.paginate_result.last_page);
                await commit('set_per_page', response.data.paginate_result.per_page);
                await commit('set_total', response.data.paginate_result.total);
            }).catch(function(error){
                console.error(error);
            });
        },

        async loadNotifications({commit}, params) {
            await axios.get("/api/notifications/load/personal?from=" + params.from + "&limit=" + params.limit).then(async (response) => {
                // console.log('s-a executat asta - loadNotifications');
                // console.log('Suntem in VUEX, aici', response.data);

                let the_notifications = await response.data.personalNotifications.map(item => {
                    item.isSelected = false;
                    return item;
                });

                // if(getters.personalNotifications){
                //     console.log('getters.personalNotifications', getters.personalNotifications);
                // }

                // let total_notifications = (getters.personalNotifications && getters.personalNotifications.length > 0) ? [...getters.personalNotifications, ...the_notifications] : the_notifications;

                // console.log('total_notifications', total_notifications);
                
                await commit('load_personal_notifications', the_notifications);
                await commit('initialize_unread_notifications', response.data.unreadNotifications);
                await commit('set_total', response.data.unreadNotifications);
            }).catch(function(error){
                console.error(error);
            });
        },

        async markAsRead({commit, state}, _id){
            
            await axios.post(`/api/notifications/markAsRead/${_id}`)
            .then(async (response) => {
                // console.log('markAsRead in vuex', _id)

                if(response.data.result == true){
                    let new_notifications = state.notifications.filter(item => {
                        if(item.id != _id){
                            return item;
                        }
                    }); 

                    await commit('initialize_personal_notifications', new_notifications);
                }
            });
        },

        async deleteNotification({commit, state}, _id){
            await axios.post(`/api/notifications/destroy/${_id}`)
            .then(async (response) => {
                // console.log('delete notification in vuex', _id)

                if(response.data.result == true){
                    let new_notifications = state.notifications.filter(item => {
                        if(item.id != _id){
                            return item;
                        }
                    }); 

                    await commit('initialize_personal_notifications', new_notifications);
                }
            });
        },


        async markAsReadSelectedNotifications({dispatch}, _ids){
            // console.log('mark as read selected - in vuex', _ids);
            await axios.post(`/api/notifications/markAsReadSelected`, {_ids}).then(async response => {
                if(response.data.success){
                    
                    await dispatch('getNotifications');

                    // console.log('raspunsul este', response);
                } 
            });
        },

        async deleteSelectedNotifications({dispatch}, _ids){
            // console.log('delete selected - in vuex', _ids);

            await axios.post(`/api/notifications/deleteSelected`, {_ids}).then(async response => {
                if(response.data.success){
                    
                    await dispatch('getNotifications');

                    // console.log('raspunsul este', response.data);
                } 
            });

        },
        
    },


    mutations: {
        initialize_personal_notifications: function(state, _notifications){
            state.notifications = _notifications;
        },

        initialize_unread_notifications: function(state, _unread_notifications){
            state.unread_notifications = _unread_notifications;
        },

        load_personal_notifications: function(state, _notifications){
            state.notifications = _notifications;
            // state.notifications = state.notifications.map(item => {
            //     item.isSelected = false;
            //     return item;
            // });
        },


        set_current_page: function(state, _current_page){
            state.current_page = _current_page;
        },

        set_from: function(state, _from){
            state.from = _from;
        },

        set_per_page: function(state, _per_page){
            state.per_page = _per_page;
        },

        set_last_page: function(state, _last_page){
            state.last_page = _last_page;
        },
        set_total: function(state, _total){
            state.total = _total;
        },

    }
}