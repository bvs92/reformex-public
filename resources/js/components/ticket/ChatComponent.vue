<template>
<!-- chatul -->
<div class="col-md-12 chat" :class="moderator ? 'col-lg-7  col-xl-8' : 'col-lg-12  col-xl-12'" v-if="the_is_loaded">
    <div class="card">
        <!-- ACTION HEDAER OPEN -->
        <div class="action-header clearfix" v-if="current_user && ticket_user">
            <div class="float-left hidden-xs d-flex ml-2" v-if="owner">
                <template v-if="the_resolvers && the_resolvers.length == 1">
                <div class="img_cont mr-3" v-if="the_resolvers[0].details">
                    <img :src="'/' + the_resolvers[0].details.profile_photo" class="rounded-circle user_img" alt="img">
                </div>
                </template>

                <template v-else-if="the_resolvers && the_resolvers.length > 1">
                    <!-- <img src="/assets/images/users/10.jpg" alt="img" class="rounded-circle user_img"> -->
                    <div class="avatar-list">
                        <template v-for="resolver in the_resolvers">       
                            <img 
                            :key="'group-image-key-'+resolver.id" 
                            :id="'group-image-id-' + resolver.id" 
                            class="avatar avatar-md brround cover-image" 
                            :src="'/' + resolver.details.profile_photo"
                            v-b-tooltip="resolver.details.first_name + ' ' + resolver.details.last_name" variant="primary"
                            />

                            <!-- <b-tooltip 
                            :key="'tooltip-' + resolver.id" 
                            :target="'group-image-id-' + resolver.id" 
                            variant="success"
                            >{{resolver.first_name}} {{resolver.last_name}}
                            </b-tooltip> -->
                        </template>
                    </div>
                </template>

                <div class="align-items-center mt-2" v-if="the_resolvers && the_resolvers.length > 0">
                    <template v-if="the_resolvers.length == 1">
                        <h4 class="mb-0 font-weight-semibold" :key="'name-key-'+the_resolvers[0].id">{{ the_resolvers[0].details.first_name }} {{ the_resolvers[0].details.last_name }} {{ the_resolvers[0].details.complete_name }}</h4>
                        <span class="mr-3" :key="'email-key-'+the_resolvers[0].id">{{ the_resolvers[0].details.email }}</span>
                    </template>
                    <!-- <template v-else>
                        <template v-for="resolver in resolvers">
                            <h4 class="mb-0 font-weight-semibold" :key="'name-key-'+resolver.id">{{ resolver.details.first_name }} {{ resolver.details.last_name }} {{ resolver.details.complete_name }}</h4>
                            <span class="mr-3" :key="'email-key-'+resolver.id">{{ resolver.details.email }}</span>
                        </template>
                    </template> -->
                </div>
                <div class="align-items-center mt-2" v-else>
                    <h4 class="mb-0 font-weight-semibold text-muted">Niciun agent in chat</h4>
                    <span class="mr-3 text-muted">in asteptarea unui agent</span>
                </div>
            </div>

            <div class="float-left hidden-xs d-flex ml-2" v-else>
                <div class="img_cont mr-3" v-if="ticket_user">
                    <img :src="'/' + ticket_user.profile_photo" class="rounded-circle user_img" alt="profile photo">
                </div>
                <div class="align-items-center mt-2">
                    <h4 class="mb-0 font-weight-semibold">{{ ticket_user.first_name }} {{ ticket_user.last_name }}</h4>
                    <span class="mr-3">{{ ticket_user.email }}</span>
                </div>
            </div>



            <ul class="ah-actions actions align-items-center">
                <li class="call-icon">
                    <a href="" class="d-done d-md-flex">
                        <i class="icon icon-phone"></i>
                    </a>
                </li>
                <li class="video-icon">
                    <a href="" class="d-done d-md-flex">
                        <i class="icon icon-camrecorder"></i>
                    </a>
                </li>
                <li class="video-icon">
                    <a href="" class="d-done d-md-flex">
                        <i class="icon icon-user-follow"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="" data-toggle="dropdown" aria-expanded="true">
                        <i class="icon icon-options-vertical"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><i class="fa fa-user-circle"></i> View profile</li>
                        <li><i class="fa fa-users"></i> Add to close friends</li>
                        <li><i class="fa fa-plus"></i> Add to group</li>
                        <li><i class="fa fa-ban"></i> Block</li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- ACTION HEADER END -->

        <!-- MSG CARD-BODY OPEN -->
        <div class="card-body msg_card_body" v-if="the_is_loaded">
            <div class="chat-box-single-line">
                <abbr class="timestamp">Deschis: {{ calculateTicketStartTime }} </abbr>
            </div>


            <template v-for="response in the_responses">
                <chat-single-component 
                :theResponse="response" 
                :theTicket="the_ticket" 
                :key="response.id"
                @response:deleted="responseDeleted"
                :current_user="current_user"
            ></chat-single-component>
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
        <ChatMessageComponent 
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
    <moon-loader size="20px" color="blue" class="m-1 d-flex justify-content-center"></moon-loader>
</div>

</template>


<script>
import ChatSingleComponent from "./ChatSingleComponent";
import ChatMessageComponent from "./ChatMessageComponent";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

export default {
    name: "ChatComponent",

    components: {
        // TicketCreateMessageComponent,
        MoonLoader,
        ChatSingleComponent,
        ChatMessageComponent
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
              isLoaded: false
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
            }
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
                // console.log('user typing mai sus.');
                // console.log('Ticket este: ' + self.ticket.id);
                // console.log(self.ticket_user);
                Echo.private('tickets-channel.' + this.ticket_id)
                    .whisper('typing', {
                        name: window.current_user.complete_name
                    });
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


        },

        mounted() {
            this._ticket_uuid = this.ticket_uuid;
            this._ticket_id = this.ticket_id;
            self = this;
            this.ticket = this.the_ticket;
            this.resolvers = this.the_resolvers;



           // whisper
                Echo.private('tickets-channel.' + this.ticket_id).listenForWhisper('typing', (e) => {
                    this.typingUser = e.name;
                    // console.log('LISTEN WHISPER.');
                    // console.log('self.isTyping: ' + self.isTyping);
                    // console.log('self.typingUser: ' + self.typingUser);
                    if(this.isTyping){
                        clearTimeout(this.isTyping);
                    }

                    this.isTyping = setTimeout(() => { 
                            this.typingUser = false;
                        }, 3000);
                        // console.log(e.name);
                        // self.typingUser = e.name;
                    });



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

</style>