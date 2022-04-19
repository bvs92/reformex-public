import axios from 'axios';


export default {
    namespaced: true, 


    state: {
        numberUnreadMessages: 0,
        accessToken: null,
        tickets: null,
        loading: false
    },

    getters: {
        getNumberUnreadMessages: function(state){
            return parseInt(state.numberUnreadMessages);
        },

        getTickets: function(state){
            return state.tickets;
        },

        getLoading: function(state){
            return state.loading;
        }

    },


    actions: {
        initNumberUnreadMessages: async function({commit}){

            await axios.get(`/api/messages/get/unread`).then(async response => {
                if(response.status >= 200 && response.status < 300){
                    await commit('set_number_unread_messages', response.data.unread_messages);  
                }
            })
        },


        initNumberUnreadMessagesSecond: async function({dispatch, commit, state, rootGetters}){
            console.log('atins');
            console.log('rootGetters', rootGetters['user/getAccessToken']);

            // commit('set_loading', true);

            
            // get unread notification
            axios.defaults.headers.common = {'Authorization': `bearer ${state.accessToken}`};


            // await axios.get(`/api/messages/get/unread`).then(async response => {
            //     if(response.status >= 200 && response.status < 300){
            //         await commit('set_number_unread_messages', response.data.unread_messages);

            //         if(response.data.unread_messages > 0){
            //             await dispatch('initTickets'); 
            //         }
            //     } 

            //     console.log('ce dracu', response);
            // }).catch(error => {
            //     console.error('plm error', error);
            // }).finally(function(){
               
            // });
        },


        initTickets: async function({commit}){
            await axios.get("/api/tickets/personal").then(async (response) => {
                let tickets = response.data.tickets;
                await commit('set_tickets', tickets);

            });
        }
    },


    mutations: {
        set_access_token: function(state, _token){
            state.accessToken = _token;
        },

        set_number_unread_messages: function(state, _number){
            state.numberUnreadMessages = _number;
        },

        set_tickets: function(state, _tickets){
            state.tickets = _tickets;
        },

        set_loading: function(state, _status){
            state.loading = _status;
        },

        set_subtract_number_unread_messages: function(state, _number){
            state.numberUnreadMessages -= _number;
        },
    }
}