<template>
  <div>
      <div class="row mt-4">

        <div class="col-lg-12 mt-4">
            
            
                <h3 class="my-4">Conversatie (VUE)</h3>
                <hr>

                
                <div class="chat" v-if="isLoaded">

                    <!-- MSG CARD-BODY OPEN -->
                    <div class="card-body msg_card_body" style="overflow-y: inherit; overflow-x: hidden;">
                        <div class="chat-box-single-line">
                            <abbr class="timestamp">Deschis: {{ this.ticketStartTime }} ({{ this.calculateTicketStartTime }}) </abbr>
                        </div>
                        
                      
                                <template v-for="response in responses">
                                    <ticket-single-response-component 
                                    :theResponse="response" 
                                    :theTicket="ticket" 
                                    :key="response.id"
                                    @response:deleted="responseDeleted"
                                    :current_user="current_user"
                                    ></ticket-single-response-component>
                                    <!-- <ticket-single-response-component :theResponse="response" :theTicketUser="ticket_user" :theTicket="ticket" :key="response.id"></ticket-single-response-component> -->
                                <!-- <div class="col-lg-12 d-flex justify-content-start" v-if="response.user_id == ticket.user_id" :key="response.id">
                                    <div class="img_cont_msg">
                                        
                                        <img src="/assets/images/users/10.jpg" alt="" class="rounded-circle user_img_msg avatar-md">
                                        User id: {{ response.user_id }}
                                    </div>
                                    <div class="msg_cotainer col-lg-8">
                                        {{ response.message }}
                                        <span class="msg_time">{{ response.created_at }}</span>
                                    </div>
                                    
                                </div> -->
                                
                                <!-- <div v-else class="col-lg-12 d-flex justify-content-end" :key="response.id">
                                    <div class="msg_cotainer_send col-lg-8 right">
                                        {{ response.message }}
                                        <span class="msg_time_send float-right">{{ response.created_at }}</span>
                                    </div>

                                    <div class="img_cont_msg">
                                        <img src="/assets/images/users/10.jpg" alt="" class="rounded-circle user_img_msg avatar-md">
                                        User id: {{ response.user_id }}
                                    </div>
                                </div> -->
                                </template>

                            <div id="last_elem" class="last_elem" ref="last_elem" style="text-indent:-9999;"></div>
                            
                            

                       
                            <div class="chat-box-single-line" v-if="ticket.status !== 0">
                                <abbr class="timestamp"><i class="ti-lock"></i> Inchis: {{ this.ticketClosedTime }} ({{ this.calculateTicketClosedTime }}) </abbr>
                            </div>
                       
                        
                    </div>
                    <!-- MSG CARD-BODY END -->
                </div>
                <div class="col-lg-12" v-else>
                    <p class="text-center">Se incarca...</p>
                    <moon-loader size="20px" color="blue" class="m-1 d-flex justify-content-center"></moon-loader>
                </div>

        </div>
    </div> 
    <div class="row" v-if="typingUser">
        <div class="col-lg-12">
            <p class="small text-disabled">{{ typingUser }} scrie...</p>
        </div>
    </div>

    <TicketCreateMessageComponent 
        :theTicket="ticket" 
        @response:saved="responseSaved" 
        @typing:user="userIsTyping"
        :isLoaded="getIsLoaded">
    </TicketCreateMessageComponent>
  </div>
</template>

<script>
import TicketCreateMessageComponent from './TicketCreateMessageComponent';
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

    export default {
        name: "TicketChatComponent",
        data: function() {
            return {
              _ticket_uuid: '',
              _ticket_id: '',
              ticket: {},
              ticket_user: {},
              ticketStartTime: null,
              ticketClosedTime: null,
              currentTime: null,
              responses: [],
              isTyping: false,
              typingUser: false,
              unreadMessages: null,
              isLoaded: false
            }
        },
        props: {
            ticket_uuid: String,
            ticket_id: Number,
            current_user: Object
        },

        components: {
            TicketCreateMessageComponent,
            MoonLoader
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
            }

        },

        methods: {

            scrollToElement() {
                const el = document.getElementById('last_elem');
                console.log('element', el);

                if (el) {
                    // Use el.scrollIntoView() to instantly scroll to the element
                    el.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
                    console.log('element - nu da scroll?');
                }
            },

            

            getTicket: async function(){
                    this.isLoaded = false;
                    self = this;
                    let getTicketPromise = new Promise(function(resolve, reject){
                        
                        axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
                        axios.get('/api/tickets/get/' + self.ticket_uuid)
                        .then(response => {
                            
                            resolve(response.data);
                        }).catch(error => {
                            // console.log(error);
                            reject();
                        }).finally(function(){
                            self.isLoaded = true;
                            
                        });
                });

                await getTicketPromise.then(function(data){
                    self.ticket = data.ticket;
                    self.responses = data.responses;
                    self.ticket_user = data.user;
                    self.unreadMessages = data.unread_messages;

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
                this.ticketStartTime = moment(this.ticket.created_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.ticketStartTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
            },

            calculateTicketClosedTimeMethod: function(){
                this.currentTime = moment().format('YYYY MM DD, HH:mm');
                this.ticketClosedTime = moment(this.ticket.updated_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.ticketClosedTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
            },


            responseSaved: async function(responseSaved){
                // data from TicketChatMessageComponent (form)
                console.log('Aiciii');
                console.log(responseSaved);
                await this.responses.push(responseSaved);
                
                await this.scrollToElement();
            },

            responseDeleted: async function(response_id){
                this.responses = await this.responses.filter(item => {
                    if(item.id != response_id){
                        return item;
                    }
                });

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
            }


        },

        mounted() {
            this._ticket_uuid = this.ticket_uuid;
            this._ticket_id = this.ticket_id;
            self = this;


            this.getTicket()
            .then(() => {
                // listen to this channel

                console.log('ce inseamna asta?/??/');


                Echo.private('tickets-channel.' + self.ticket.id)
                .listen('TicketMessageEvent', (ticket) => {
                    console.log('LISTEN FOR MESSAGE.', ticket);
                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
                        self.marksMessagesAsRead();
                        this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

                        // get all responses for current ticket
                        axios.get(`/api/tickets/${self.ticket.id}/responses`).then((response) => {
                            self.responses = response.data.responses;
                            console.log('AM ATINS AICI. :))');
                            console.log(self.responses);
                        }).catch(function(error){
                            console.error(error);
                        })
                    }


                  
                }).listen('TicketSingleEvent', ticket => {
                    if(window.location.pathname == '/tickets/get/id/' + self.ticket.uuid){
                        
                        self.marksMessagesAsRead();
                        this.$store.dispatch('messages/initNumberUnreadMessagesSecond');

                        // get all responses for current ticket
                        axios.get(`/api/tickets/${self.ticket.id}/responses`).then((response) => {
                            self.responses = response.data.responses;
                            console.log('AM ATINS AICI - dupa stergere. :))');
                            console.log(self.responses);
                        }).catch(function(error){
                            console.error(error);
                        })
                    }
                });

                // listen to whisper
                Echo.private('tickets-channel.' + self.ticket.id).listenForWhisper('typing', (e) => {
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
            }).finally(function(){
                // this.isLoaded = true;
                self.scrollToElement();
            });

            

            

        },

        destroyed() {
        },

    }
</script>


<style scoped>
.msg_cotainer_send.col-lg-8.right {
    text-align: right;
}

.chat {
        height: 500px;
overflow: scroll;
}


.moonLoader {
    height: 24px;
    width: 24px;
    border-radius: 100%;
    margin: 0 auto;
    margin-top: 20%;
}

</style>