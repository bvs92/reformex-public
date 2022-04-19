<template>
<!-- ROW-1 OPEN -->
<div class="row chatbox" v-if="isLoaded">

    <!-- stanga - lista utilizatori -->
    <list-users-component v-if="moderator"
        :resolvers="get_resolvers"
        :current_user="current_user" 
        :ticket_id="ticket_id"
    ></list-users-component>
    <!-- COL END -->


    <chat-component 
    :ticket_uuid="ticket_uuid" 
    :ticket_id="ticket_id" 
    :current_user="current_user" 
    :ticket_user="ticket_user" 
    :the_ticket="ticket"
    :owner="owner"
    :moderator="moderator"
    :the_responses="responses"
    :the_resolvers="get_resolvers"
    :the_is_loaded="isLoaded"
    @response:saved="response_saved"
    @response:deleted="response_deleted"
    :read_only="read_only"
    ></chat-component>
</div>

<div class="col-lg-12" v-else>
    <p class="text-center">Se incarca...</p>
    <moon-loader size="20px" color="blue" class="m-1 d-flex justify-content-center"></moon-loader>
</div>

<!-- ROW-1 CLOSED -->
</template>


<script>
import ChatComponent from "./ChatComponent";
import ListUsersComponent from './ListUsersComponent.vue';
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';
import {mapGetters} from 'vuex';

export default {
    name: "ChatBoxComponent",

    data(){
        return {
            _ticket_uuid: '',
            _ticket_id: '',
            ticket_user: null,
            ticketStartTime: null,
            ticketClosedTime: null,
            currentTime: null,
            ticket: {},
            responses: [],
            resolvers: [],
            moderators: [],
            unreadMessages: null,
            isLoaded: false
        }
    },

    components: {
        ChatComponent,
        ListUsersComponent,
        MoonLoader
    },

    props: {
        ticket_uuid: String,
        ticket_id: Number,


        current_user: Object,
        the_ticket_user: Object,
        the_ticket: Object,
        owner: Boolean,
        moderator: Boolean,
        read_only: Boolean
    },


    computed: {
        ...mapGetters('resolvers', ['get_resolvers']),
    },


    methods: {
            getTicket: async function(){
                this.isLoaded = false;
                self = this;
                                         
                axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
                await axios.get('/api/tickets/get/' + this.ticket_uuid)
                .then(response => {
                    console.log("TICKET PRELUAT", response.data);
                    // await resolve(response.data); DE PRELUAT DOAR RESPONSES???

                    console.log('aici este data', response.data.user);
                    this.ticket = response.data.ticket;
                    this.responses = response.data.responses.sort(function(a,b){
                        if(a.created_at > b.created_at)
                            return 1;
                        else
                            return -1;
                    });
                    // this.resolvers = response.data.resolvers;
                    this.ticket_user = response.data.user;
                    this.unreadMessages = response.data.unread_messages;

                    console.log('ce resolveri are', this.resolvers);
                    console.log('user ticket', this.ticket_user);

                    if(window.location.pathname == '/tickets/get/id/' + this.ticket.uuid){
                        this.marksMessagesAsRead();
                    }

                }).catch(error => {
                    // console.log(error);
                    // reject();
                }).finally(function(){
                    self.isLoaded = true;
                    
                });
            },

            marksMessagesAsRead: async function(){
                    console.log('SUNTEM AICI: ' + window.location.pathname);

                    await axios.post(`/api/tickets/${self.ticket.id}/responses/markAsRead`).then(async response => {
                        // console.log('Rezultat update MarkAsRead');
                        // console.log(response);
                        await this.$store.dispatch('messages/initNumberUnreadMessagesSecond');
                    }).catch(
                        function(error){
                            console.error(error);
                        }
                    ); // end axios markAsRead
            },

            scrollToElement: function() {
                const el = document.getElementById('last_elem');
                console.log('element', el);

                if (el) {
                    // Use el.scrollIntoView() to instantly scroll to the element
                    el.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
                    console.log('element - nu da scroll?');
                }
            },

            response_saved: async function(responseSaved){
                console.log('Aiciii');
                console.log(responseSaved);
                await this.responses.push(responseSaved);
                
                await this.scrollToElement();
            },

            response_deleted: async function(response_id){
                this.responses = await this.responses.filter(item => {
                    if(item.id != response_id){
                        return item;
                    }
                });
            },

            getModerators: async function(){
                await axios.get('/api/users/moderators/get')
                .then(response => {
                    console.log("MODERATOR PRELUAT", response.data);

                    this.moderators = response.data.moderators;
     

                }).catch(error => {
                    // console.log(error);
                }).finally(function(){});
            },

            getAllResponses: function(){
                let self = this;
                axios.get(`/api/tickets/${self.ticket.id}/responses`).then((response) => {
                    self.responses = response.data.responses.sort(function(a,b){
                        if(a.created_at > b.created_at)
                            return 1;
                        else
                            return -1;
                    });
                    
                    console.log('iar - AM ATINS AICI. :))');
                    console.log(self.responses);
                }).catch(function(error){
                    console.error(error);
                });
            }

    },


    created() {
        // this.current_user = window.current_user;
        let self = this;

        self.$store.dispatch('resolvers/initResolvers', self.ticket_id).then(() => {
                // console.log('resolvers_list este ', self.resolvers);
            console.log('get_resolvers este ', self.get_resolvers);
            console.log('resolvers este - de eliminat ', self.resolvers);
        });


        this.getTicket()
            .then(() => {
                // listen to this channel

                // console.log('ce inseamna asta?/??/');


                Echo.private('tickets-channel.' + self.ticket.id)
                .listen('TicketMessageEvent', (ticket) => {
                    console.log('LISTEN FOR MESSAGE.', ticket);
                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
                        self.marksMessagesAsRead();
                        this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

                        // get all responses for current ticket
                        self.getAllResponses();
                    }


                  
                }).listen('TicketSingleEvent', ticket => {
                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
                        self.marksMessagesAsRead();
                        this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

                        // get all responses for current ticket
                        self.getAllResponses();
                    }
                }).listen('ModeratorJoinTicketEvent', (ticket) => {
                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
                        self.$store.dispatch('resolvers/initResolvers', self.ticket_id).then(() => {
                            // console.log('resolvers_list este ', self.resolvers);
                            console.log('LISTEN reinit - get_resolvers este ', self.get_resolvers);
                            console.log('LISTEN resolvers este ', self.resolvers);
                        }).finally(function(){
                            // setTimeout(function(){ 
                            //     self.isLoaded = true;
                            // }, 2000);
                        });

                        // get all responses for current ticket
                        self.getAllResponses();
         


                    }
                });

            }).finally(function(){
                // this.isLoaded = true;
                self.scrollToElement();
            });

        // this.getModerators();

    }

}
</script>