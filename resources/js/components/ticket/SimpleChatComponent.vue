<template>
<!-- chatul -->
<div class="col-md-12 chat" :class="moderator ? 'col-lg-7  col-xl-8' : 'col-lg-12  col-xl-12'" v-if="the_is_loaded">
    <div class="">


        <!-- MSG CARD-BODY OPEN -->
        <div class="card-body msg_card_body" v-if="the_is_loaded">
            <div class="chat-box-single-line">
                <abbr class="timestamp">Deschis: {{ calculateTicketStartTime }} </abbr>
            </div>

            <div class="chat-box-single-line my-4" v-if="getTotalResponses > getGetTotalResponses">
                    <div class="text-center" v-if="get_more">
                        <p class="text-center">Se incarca mesaje mai vechi...</p>
                        <b-spinner label="Spinning"></b-spinner>
                    </div>
                <!-- <button class="btn btn-default btn-sm btn-loading" v-if="get_more">Se incarca mesaje mai vechi</button> -->
                <button class="btn btn-default btn-sm" @click.prevent="getTicketResponses()" v-else>Incarca mesaje mai vechi</button>
            </div>

            <template v-for="response in getResponses">
                <simple-chat-single-component 
                :theResponse="response" 
                :theTicket="the_ticket" 
                :key="response.id"
                @response:deleted="responseDeleted"
                :current_user="current_user"
            ></simple-chat-single-component>
            </template>



            <div id="last_elem" class="last_elem" ref="last_elem" style="text-indent:-9999;"></div>
            <div class="chat-box-single-line" v-if="the_ticket.status !== 0">
                <abbr class="timestamp">Inchis: {{ calculateTicketClosedTime }} </abbr>
            </div>

            <div class="row" v-if="typingUser">
                <div class="col-lg-12">
                    <p class="small text-disabled">{{ typingUser }} scrie...</p>
                </div>
            </div>
            

        </div>
        <!-- MSG CARD-BODY END -->
        <!-- CREATE MESSAGE -->
        <template v-if="read_only == false">
        <SimpleChatMessageComponent 
            :theTicket="the_ticket" 
            @response:saved="responseSaved" 
            @typing:user="userIsTyping"
            :isLoaded="the_is_loaded" 
        />
        </template>
        <!-- CREATE MESSAGE END -->
    </div>
</div><!-- end chatul -->

<div class="col-lg-12" v-else>
    <p class="text-center">Se incarca...</p>
    <div class="text-center">
        <b-spinner label="Spinning"></b-spinner>
    </div>
</div>

</template>


<script>
import SimpleChatSingleComponent from "./SimpleChatSingleComponent";
import SimpleChatMessageComponent from "./SimpleChatMessageComponent";
// import MoonLoader from 'vue-spinner/src/MoonLoader.vue';
import {mapGetters} from 'vuex';

export default {
    name: "SimpleChatComponent",

    components: {
        // TicketCreateMessageComponent,
        // MoonLoader,
        SimpleChatSingleComponent,
        SimpleChatMessageComponent
    },

    data: function() {
            return {
              _ticket_uuid: '',
              _ticket_id: '',
              ticket: {},
            //   ticket_user: null,
              ticketStartTime: null,
              ticketClosedTime: null,
              currentTime: null,
              responses: [],
              resolvers: null,
              isTyping: false,
              typingUser: false,
              unreadMessages: null,
              isLoaded: false,

              get_more: false
            }
        },
        props: {
            ticket_uuid: String,
            ticket_id: Number,
            current_user: Object,
            owner: Boolean,
            moderator: Boolean,
            ticket_user: Object,
            the_ticket: Object,
            the_responses: Array,
            the_resolvers: Array,
            the_is_loaded: Boolean,
            read_only: Boolean
        },

        computed: {
            calculateTicketStartTime: function(){
                return this.calculateTicketStartTimeMethod()
            },

            calculateTicketClosedTime: function(){
                return this.calculateTicketClosedTimeMethod()
            },

            getIsLoaded: function(){
                return this.isLoaded ? true : false;
            },

            getTicketUser: function(){
                return this.ticket_user ? this.ticket_user : null;
            },

            ...mapGetters('tickets', ['getSingleTicket', 'getResponses', 'getResolvers', 'getTicketUser', 'getUnreadMessages', 'getGetTotalResponses', 'getTotalResponses']),
        },

        methods: {

            // scrollToElement() {
            //     const el = document.getElementById('last_elem');
            //     console.log('element', el);

            //     if (el) {
            //         // Use el.scrollIntoView() to instantly scroll to the element
            //         el.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
            //         console.log('element - nu da scroll?');
            //     }
            // },

            // getTicket: async function(){
            //     this.isLoaded = false;
            //     self = this;
                                         
            //     axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
            //     await axios.get('/api/tickets/get/' + this.ticket_uuid)
            //     .then(response => {
            //         console.log("TICKET PRELUAT", response.data);
            //         // await resolve(response.data); DE PRELUAT DOAR RESPONSES???

            //         console.log('aici este data', response.data.user);
            //         this.ticket = response.data.ticket;
            //         this.responses = response.data.responses;
            //         this.resolvers = response.data.resolvers;
            //         this.ticket_user = response.data.user;
            //         this.unreadMessages = response.data.unread_messages;

            //         console.log('ce resolveri are', this.resolvers);
            //         console.log('user ticket', this.ticket_user);

            //         if(window.location.pathname == '/tickets/get/id/' + this.ticket.uuid){
            //             this.marksMessagesAsRead();
            //         }

            //     }).catch(error => {
            //         // console.log(error);
            //         // reject();
            //     }).finally(function(){
            //         self.isLoaded = true;
                    
            //     });
            // },

            getTicket1: async function(){
                    this.isLoaded = false;
                    self = this;
                    let getTicketPromise = new Promise(async function(resolve, reject){
                        
                        axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
                        await axios.get('/api/tickets/get/' + self.ticket_uuid)
                        .then(async response => {
                            console.log("TICKET PRELUAT", response.data);
                            await resolve(response.data);
                        }).catch(error => {
                            // console.log(error);
                            reject();
                        }).finally(function(){
                            self.isLoaded = true;
                            
                        });
                });

                await getTicketPromise.then(function(data){

                    console.log('aici este data', data.user);
                    self.ticket = data.ticket;
                    self.responses = data.responses;
                    self.ticket_user = data.user;
                    self.unreadMessages = data.unread_messages;

                    console.log('ce plm de user', self.ticket_user);

                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        self.marksMessagesAsRead();
                    }
                });
            },

            nowTimes(){
                this.calculateTicketStartTimeMethod();
                this.calculateTicketClosedTimeMethod();
                this.interval = setInterval(this.nowTimes,300*1000); // update la 5 minute
            },

            calculateTicketStartTimeMethod: function(){
                this.currentTime = moment().format('YYYY MM DD, HH:mm');
                this.ticketStartTime = moment(this.the_ticket.created_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.ticketStartTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
            },

            calculateTicketClosedTimeMethod: function(){
                this.currentTime = moment().format('YYYY MM DD, HH:mm');
                this.ticketClosedTime = moment(this.the_ticket.updated_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.ticketClosedTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
            },


            responseSaved: async function(responseSaved){
                // data from TicketChatMessageComponent (form)

                await this.$emit('response:saved', responseSaved);
            },

            responseDeleted: async function(response_id){
                await this.$emit('response:deleted', response_id);
                // this.responses = await this.responses.filter(item => {
                //     if(item.id != response_id){
                //         return item;
                //     }
                // });
            

                // await this.scrollToElement();
            },

            userIsTyping: function(){
                // Echo.private('tickets-channel.' + this.ticket_id)
                //     .whisper('typing', {
                //         name: window.current_user.complete_name
                //     });
            },


            // marksMessagesAsRead: async function(){
            //         console.log('SUNTEM AICI: ' + window.location.pathname);

            //         await axios.post(`/api/tickets/${self.ticket.id}/responses/markAsRead`).then(async response => {
            //             // console.log('Rezultat update MarkAsRead');
            //             // console.log(response);
            //             await this.$store.dispatch('messages/initNumberUnreadMessagesSecond');
            //         }).catch(
            //             function(error){
            //                 console.error(error);
            //             }
            //         ); // end axios markAsRead
            // }



        getTicketResponses: function(){
            console.log('click for next.');

            this.get_more = true;
            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
            this.$store.dispatch('tickets/initTicketResponses', this.the_ticket.uuid)
            .finally(() => {
                this.get_more = false;
                // self.scrollToElement();
            });

        },
        },

        mounted() {
            this._ticket_uuid = this.ticket_uuid;
            this._ticket_id = this.ticket_id;
            self = this;
            this.ticket = this.the_ticket;
            this.resolvers = this.the_resolvers;



           // whisper
                // Echo.private('tickets-channel.' + this.ticket_id).listenForWhisper('typing', (e) => {
                //     this.typingUser = e.name;
                //     if(this.isTyping){
                //         clearTimeout(this.isTyping);
                //     }

                //     this.isTyping = setTimeout(() => { 
                //             this.typingUser = false;
                //         }, 3000);
                //     });



            // this.getTicket()
            // .then(() => {
            //     // listen to this channel

            //     console.log('ce inseamna asta?/??/');


            //     Echo.private('tickets-channel.' + self.ticket.id)
            //     .listen('TicketMessageEvent', (ticket) => {
            //         console.log('LISTEN FOR MESSAGE.', ticket);
            //         if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
            //             self.marksMessagesAsRead();
            //             this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

            //             // get all responses for current ticket
            //             axios.get(`/api/tickets/${self.ticket.id}/responses`).then((response) => {
            //                 self.responses = response.data.responses;
            //                 console.log('AM ATINS AICI. :))');
            //                 console.log(self.responses);
            //             }).catch(function(error){
            //                 console.error(error);
            //             })
            //         }


                  
            //     }).listen('TicketSingleEvent', ticket => {
            //         if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
            //             self.marksMessagesAsRead();
            //             this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

            //             // get all responses for current ticket
            //             axios.get(`/api/tickets/${self.ticket.id}/responses`).then((response) => {
            //                 self.responses = response.data.responses;
            //                 console.log('AM ATINS AICI - dupa stergere. :))');
            //                 console.log(self.responses);
            //             }).catch(function(error){
            //                 console.error(error);
            //             })
            //         }
            //     });

            //     // listen to whisper
            //     Echo.private('tickets-channel.' + self.ticket.id).listenForWhisper('typing', (e) => {
            //         this.typingUser = e.name;
            //         // console.log('LISTEN WHISPER.');
            //         // console.log('self.isTyping: ' + self.isTyping);
            //         // console.log('self.typingUser: ' + self.typingUser);
            //         if(this.isTyping){
            //             clearTimeout(this.isTyping);
            //         }

            //         this.isTyping = setTimeout(() => { 
            //                 this.typingUser = false;
            //          }, 3000);
            //             // console.log(e.name);
            //             // self.typingUser = e.name;
            //         });
            // }).finally(function(){
            //     // this.isLoaded = true;
            //     self.scrollToElement();
            // });



            // console.log('asa arata user din PROP', this.ticket_user);

            

            

        },
}
</script>


<style scoped>
.moonLoader {
    height: 24px;
    width: 24px;
    border-radius: 100%;
    margin: 0 auto;
    margin-top: 20%;
}

.chat .card {height: auto;}

</style>