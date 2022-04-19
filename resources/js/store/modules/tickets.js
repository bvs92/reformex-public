import axios from 'axios';

export default {
    namespaced: true,

    state: {
        
        allTickets: null,
        totalTickets: 0,
        userStatus: null,


        get_total_responses: 15,
        total_responses: 0,
        singleTicket: null,
        responses: null,
        resolvers: null,
        ticketUser: null,
        unreadMessages: 0,
        
    },

    getters: {
      

        getAllTickets: function(state){
            return state.allTickets;
        },

        getTotalTickets: function(state){
            return state.totalTickets;
        },
        
        getUserStatus: function(state){
            return state.userStatus;
        },

        getGetTotalResponses: function(state){
            return state.get_total_responses;
        },

        getTotalResponses: function(state){
            return state.total_responses;
        },

        getSingleTicket: function(state){
            return state.singleTicket;
        },

        getResponses: function(state){
            return state.responses;
        },
        getResolvers: function(state){
            return state.resolvers;
        },
        getTicketUser: function(state){
            return state.ticketUser;
        },
        getUnreadMessages: function(state){
            return state.unreadMessages;
        },
    },

    actions: {
        initTicketsForAdmin: async function({commit}, {_type, _department}){
            await axios.get(`/api/tickets/initialize/admin?type=${_type}&department=${_department}`).then(response => {
                if(response.data.tickets){
                    commit('set_all_tickets', Object.values(response.data.tickets));
                    commit('set_total_tickets', response.data.total);
                }
            });
        },

        initPersonalTickets: async function({commit}){
            await axios.get(`/api/tickets/initialize/personal`).then(response => {
                if(response.data.tickets){
                    commit('set_all_tickets', Object.values(response.data.tickets));
                    commit('set_total_tickets', response.data.total);
                    commit('set_user_status', response.data.user_status);
                }
            });
        },


        // responses

        initTicketResponses: async function({commit, state}, _uuid){
            
            await axios.get('/api/tickets/' + _uuid + '/get/responses?total=' + state.get_total_responses)
            .then(response => {
                console.log("TICKET PRELUAT", response.data);
        
             
                let responses = response.data.responses.sort(function(a,b){
                    if(a.created_at > b.created_at)
                        return 1;
                    else
                        return -1;
                });


                commit('set_single_ticket', response.data.ticket);
                commit('set_responses', responses);
                commit('set_resolvers', response.data.resolvers);
                commit('set_ticket_user', response.data.user);
                commit('set_unread_messages', response.data.unread_messages);
                commit('set_get_total_responses', state.get_total_responses + 15);
                commit('set_total_responses', response.data.total_responses);

                // console.log('ce resolveri are', this.resolvers);
                // console.log('user ticket', this.ticket_user);

                // if(window.location.pathname == '/tickets/get/id/' + this.ticket.uuid){
                //     this.marksMessagesAsRead();
                // }

            }).catch(error => {
                // console.log(error);
                // reject();
            }).finally(function(){
                // self.isLoaded = true;
                
            });
        }
    },

    mutations: {
    
        set_all_tickets: function(state, _tickets){
            state.allTickets = _tickets;
        },

        set_total_tickets: function(state, _total){
            state.totalTickets = _total;
        },

        set_user_status: function(state, _status){
            state.userStatus = _status;
        },

        set_get_total_responses: function(state, _total){
            return state.get_total_responses = _total;
        },

        set_total_responses: function(state, _total){
            return state.total_responses = _total;
        },

        set_single_ticket: function(state, _ticket){
            return state.singleTicket = _ticket;
        },

        set_responses: function(state, _responses){
            return state.responses = _responses;
        },
        set_resolvers: function(state, _resolvers){
            return state.resolvers = _resolvers;
        },
        set_ticket_user: function(state, _user){
            return state.ticketUser = _user;
        },
        set_unread_messages: function(state, _unread){
            return state.unreadMessages = _unread;
        },

    }
}