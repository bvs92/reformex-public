<template>
  <div class="dropdown d-md-flex message">
    <a class="nav-link icon text-center" data-toggle="dropdown" v-if="getNumberUnreadMessages >= 0">
        <i class="fe fe-mail"></i>
        <span class="nav-unread badge badge-danger badge-pill" v-if="getNumberUnreadMessages > 0">{{ getNumberUnreadMessages }}</span>
    </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
         
           
            <div class="message-menu" v-if="getTickets">
            
                <div v-for="ticket of getTickets" :key="ticket.uuid">
                    <SingleUnreadMessageComponent v-if="ticket" :message="ticket" :the_last="ticket.last_response" :unreadResponsesNumber="ticket.unread_responses"></SingleUnreadMessageComponent>
                </div>
                
            
        
                <div class="dropdown-divider" v-if="getNumberUnreadMessages > 0"></div>
                <a href="/tickets/list" class="dropdown-item text-center" v-if="getNumberUnreadMessages > 0">Vezi toate tichetele</a>
        
                <p class="my-2 text-center" v-else>Niciun mesaj nou.</p>
            
            </div>
            
    </div><!-- MESSAGE-BOX -->
  </div>
</template>

<script>
import SingleUnreadMessageComponent from './SingleUnreadMessageComponent';

import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "UnreadMessagesNotificationsComponent",

        components: {
            SingleUnreadMessageComponent
        },

        data: function() {
            return {
                // userPersonalTickets: null,
                // totalUnreadMessages: 0,
                ticketsWithMessages: null,
                access_token: Vue.cookie.get(document.cookie.token_access).token_access ?? null
            }
        },

        props: {
            
        },

        computed: {
        //    getUserPersonalTickets: function(){
        //        return this.userPersonalTickets;
        //    },

        //    getUnreadMessagesNumber: function(){
        //        return totalUnreadMessages;
        //     //    this.getTotalUnreadMessages();
        //    },

           ...mapGetters('messages', ['getNumberUnreadMessages', 'getTickets', 'getLoading']),
           ...mapGetters('user', ['getAccessToken'])
        },

        methods: {

            ...mapActions({
                initializeNotifications: 'getNotifications' // map `this.initializeNotifications()` to `this.$store.dispatch('getNotifications')`
            }),

            subtractReadMessages: function(readMessages){
                // console.log('substract: ' + readMessages);
                let newTotal = this.getNumberUnreadMessages - readMessages;
                // this.totalUnreadMessages -= readMessages;
                this.$store.commit('messages/set_number_unread_messages', newTotal);
            },
          
          getTotalUnreadMessages: function(){
              if(this.userPersonalTickets)
                this.userPersonalTickets.forEach(item => {
                    this.totalUnreadMessages = item.unread_responses;
                });
          },

           getTheTickets: function(){
              
              let self = this;
               
                
            //    let userTicketsChatPromise = new Promise((resolve, reject) => {

            //     // axios.defaults.headers.common = {'Authorization': `bearer ${this.access_token}`}

            //     // asociaza numarul de mesaje necitite.
            //     axios.get("/api/tickets/personal").then((response) => {
            //         // this.userPersonalTickets = [...response.data.tickets];

            //         // this.$store.commit('messages/set_number_unread_messages', response.data.tickets);


            //         // this.userPersonalTickets.sort(function(a, b){
            //         //     return b.unread_responses - a.unread_responses;
            //         // });


            //         // console.log('aiciiii ', response.data.tickets);
            //         resolve(response.data.tickets);



            //     }).catch(function(error){
            //         // console.error(error);
            //         reject(error);
            //     });


            // });



            let initilizeAll = new Promise((resolve, reject) => {

                console.log('window.location.pathname', window.location.pathname);

                this.$store.commit('messages/set_access_token', this.access_token);
                this.$store.dispatch('messages/initNumberUnreadMessages').then(async response => {
                    // await resolve(true);

                    await self.getTickets.forEach(item => {
                    let self = this;

                    console.log('item este:', item);
                

                    Echo.private('user-tickets-messages-channel.' + item.id)
                    .listen('UserTicketsMessageEvent', (ticket) => {
                        console.log('asta merge? user-tickets-messages-channel - UserTicketsMessageEvent');
                        
                        if(window.location.pathname != '/tickets/get/id/' + item.uuid){
                            self.$store.commit('messages/set_access_token', self.access_token);
                            self.$store.dispatch('messages/initNumberUnreadMessagesSecond');
                        }

                    }).listen('TicketStatusChangedEvent', (ticket) => {
                         console.log('asta merge? - TicketStatusChangedEvent');

                         if(window.location.pathname == '/tickets/get/id/' + item.uuid){
                             let status_ticket = ticket.status == '0' ? 'deschis' : 'inchis';
                             console.log('se executa si asta....');

                            this.$swal({
                                title: 'Status tichet schimbat',
                                text: "Acest tichet a fost marcat ca " + status_ticket + ".",
                                icon: 'info',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Da, confirm.',
                                // cancelButtonText: 'Nu, renunta'
                            }).then(async (result) => {
                                location.reload();
                            });
                        }
                        
                        // get notifications
                        self.initializeNotifications();
                    }).listen('TicketDeletedEvent', (ticket) => {
                        console.log('asta merge? - TicketDeletedEvent', ticket);

                        if(window.location.pathname == '/tickets/get/id/' + item.uuid){

                            this.$swal({
                                title: 'Tichet eliminat',
                                text: "Acest tichet a fost eliminat si nu mai este disponibil. Parasiti aceasta pagina.",
                                icon: 'danger',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Da, confirm.',
                                // cancelButtonText: 'Nu, renunta'
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/tickets';
                                }
                            });
                        }
                        // get notifications
                        self.initializeNotifications();

                    });
                });

                }).catch(error => {
                    reject(error);
                });


            });


            // initilizeAll.then(async (data) => {
            //     console.log('this.getTicketsthis.getTicketsthis.getTickets', this.getTickets);
            //     await self.getTickets.forEach(item => {
            //         let self = this;

            //         console.log('item este:', item);
                

            //         Echo.private('user-tickets-messages-channel.' + item.id)
            //         .listen('UserTicketsMessageEvent', (ticket) => {
            //             console.log('asta merge? - UserTicketsMessageEvent');
                        
            //             self.$store.commit('messages/set_access_token', self.access_token);
            //             self.$store.dispatch('messages/initNumberUnreadMessages');

            //         });
            //     });
            // });


            // userTicketsChatPromise.then((data) => {

            //     this.totalUnreadMessages = 0;
            //     this.userPersonalTickets.forEach(item => {
            //         // console.log("itrem id is: " + item.id);


            //         this.totalUnreadMessages += item.unread_responses;

            //         Echo.private('user-tickets-messages-channel.' + item.id)
            //         .listen('UserTicketsMessageEvent', (ticket) => {
            //             // console.log('SUNTEM AICI -- asteptam raspuns cu numar mesaje necitite');
            //             // console.log(ticket);
            //             // this.ticketsWithMessages.push(ticket); // irelevant - doar test
            //             // this.totalUnreadMessages = ticket.unread_messages; // irelevant - doar test

            //             // preia iarasi tichetele cu mesaje necitite
            //             // axios.defaults.headers.common = {'Authorization': `bearer ${this.accessToken}`}
            //             // asociaza numarul de mesaje necitite.
            //                 axios.get("/api/tickets/personal").then((response) => {
            //                     // console.log('IAR SE EXECUTRA userPersonalTickets (raspuns)');
            //                     // console.log(response.data.tickets);
            //                     this.userPersonalTickets = [...response.data.tickets];

            //                     // sort descending based on unread_responses
            //                     this.userPersonalTickets.sort(function(a, b){
            //                         return b.unread_responses - a.unread_responses;
            //                     });
                                
            //                     // resolve(response.data.tickets);

            //                     this.totalUnreadMessages = 0;
            //                     this.userPersonalTickets.forEach(item => {
            //                         // console.log("IAR SE EXECUTRA itrem id is: " + item.id);
            //                         this.totalUnreadMessages += item.unread_responses;
            //                     });


            //                 }).catch(function(error){
            //                     console.error(error);
            //                 });


            //         });
            //     });



            // });
           },




           echoMessages: async function(){

               

               if(this.getTickets){
                   await this.getTickets.forEach(item => {
                       let self = this;

                       console.log('item este:', item);
                    
    
                        Echo.private('user-tickets-messages-channel.' + item.id)
                        .listen('UserTicketsMessageEvent', (ticket) => {
                            console.log('asta merge? 1 -- user-tickets-messages-channel - UserTicketsMessageEvent');
                            
                            self.$store.commit('messages/set_access_token', self.access_token);
                            self.$store.dispatch('messages/initNumberUnreadMessages');
    
                        }).listen('TicketStatusChangedEvent', (ticket) => {
                            console.log('asta merge? - TicketStatusChangedEvent');
                            
                            // get notifications
                            self.initializeNotifications();
                        }).listen('TicketDeletedEvent', (ticket) => {
                            console.log('second - asta merge? - TicketDeletedEvent', ticket);

                            if(window.location.pathname == '/tickets/get/id/' + ticket.ticket_uuid){
                                window.location.href = '/tickets';
                            }
                            // get notifications
                            self.initializeNotifications();

                        });

                        
                    });
               }
           }


        },

        created() {

            this.$store.dispatch('messages/initNumberUnreadMessages');
            this.$store.dispatch('messages/initTickets');
            
        },

        mounted(){},

        destroyed() {},

    }
</script>


<style scoped>
    
</style>