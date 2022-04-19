<template>

<div v-if="response.class_type == 'TicketAction'">
    <template v-if="response.type == 'joins'">
        <p v-if="response.sender_id == response.user_id">{{response.user_name}} s-a <span class="badge badge-success">alaturat</span> conversatiei.</p>
        <p v-else>{{response.sender_name}} l-a <span class="badge badge-success">adaugat</span> in conversatie pe {{ response.user_name }}.</p>
    </template>

    <template v-else-if="response.type == 'leaves'">
        <p v-if="response.sender_id == response.user_id">{{response.user_name}} a <span class="badge badge-default">parasit</span> conversatia.</p>
        <p v-else>{{response.sender_name}} l-a <span class="badge badge-default">eliminat</span> din conversatie pe {{response.user_name}}.</p>
    </template>
</div>

<div v-else 
    class="d-flex mb-6" 
    :class="response.user_id == current_user.id ? '' : 'justify-content-start'" 
    @mouseover="show_options = true" 
    @mouseleave="show_options = false"
    >

    <template v-if="response.user_id == current_user.id">
        
        <div class="col-10 msg_cotainer_send">
            <p style="font-weight: bold;">{{ response.user_details.complete_name }}</p>
            <p>{{ response.message }}</p>
            <span class="msg_time">{{ this.calculateResponseTime }}</span>
            <div class="mt-2" v-if="response.attached_files && response.attached_files.length > 0">
                <FileComponent :attached_files="response.attached_files" :content_align="response.user_id == current_user.id ? '' : 'justify-content-start'" />
            </div>

            

        </div>

        <div>
            <div class="btn-group" v-if="show_options">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item btn-sm" @click.prevent="deleteSingleMessage(response.id)"><i class="ti-trash"></i>Elimina</button>
                </div>
            </div>
        </div>

    </template>

    <template v-else>

        <div class="msg_cotainer col-10">
            <p style="font-weight: bold;">{{ response_user.complete_name }}</p>
            {{ response.message }}
            <span class="msg_time">{{ this.calculateResponseTime }}</span>
            <div class="mt-2" v-if="response.attached_files && response.attached_files.length > 0">
                <FileComponent :attached_files="response.attached_files" :content_align="response.user_id == current_user.id ? '' : 'justify-content-start'" />
            </div>

        </div>
    </template>
</div>
</template>

<script>
import FileComponent from "./_module/FileComponent";

import { mapGetters } from 'vuex';

    export default {
        name: "SimpleChatSingleComponent",
        data: function() {
            return {
              
            //   finalResponseTime: null,
            show_options: false,
            responseTime: null,
            currentTime: null,
            response: {},
            response_user: {},
            ticket: {},
            theInterval: false,
            // currentUser: null
            //   ticket_user: {}
            }
        },

        components: {
            FileComponent
        },

        props: {
            theResponse: Object,
            theTicket: Object,
            theTicketUser: Object,
            current_user: Object
        },

        computed: {
            calculateResponseTime: function(){
                return this.calculateResponseTimeMethod()
            },

            ...mapGetters('user', ['getCurrentUser'])
        },

        methods: {
            getUser: function(){
                //     let getTicketPromise = new Promise(function(resolve, reject){
                //     axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`}
                //     axios.get('http://127.0.0.1:8000/api/tickets/get/1552c683 ').then(response => {
                //         resolve(response.data);
                //     }).catch(error => {
                //         console.log(error);
                //         reject();
                //     });
                // });

                // getTicketPromise.then(function(data){
                //     self.ticket = data.ticket;
                //     self.responses = data.responses;
                //     console.log('in promise');
                //     console.log(self.ticket);
                //     console.log(self.responses);
                // });
            },

            setNowTimes(){
                // this.finalResponseTime = this.calculateResponseTimeMethod();
                this.calculateResponseTimeMethod();
                // console.log( "Ce e asta: ??  " + this.calculateResponseTimeMethod());
                // this.theInterval = setInterval(this.setNowTimes,20*1000); // update la 1 minut
            },

            calculateResponseTimeMethod: function(){
                this.currentTime = moment().format('YYYY MM DD, HH:mm');
                this.responseTime = moment(this.response.created_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.responseTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
                // return 'hehehe';
            },


            deleteSingleMessage: function(message_id){
                console.log(message_id);

                axios.post('/api/tickets/responses/delete/' + message_id)
                    .then(response => {
                        if(response.data.result == true){
                            this.$emit('response:deleted', message_id);
                        } else {
                            Vue.$toast.open({
                                message: 'Oups! Am intampinat erori.',
                                type: 'error',
                                duration: 6000
                            });
                        }
                    }).catch(error => {
                        Vue.$toast.open({
                            message: 'Oups! Am intampinat erori.',
                            type: 'error',
                            duration: 6000
                        });
                    });
            }

           


        },

        created() {
            const self = this;
            this.ticket = this.theTicket;
            // this.ticket_user = this.theTicketUser;
            this.response = this.theResponse;
            this.response_user = this.theResponse.user_details;

            // console.log('getCurrentUser este', this.current_user);

            // this.finalResponseTime = this.calculateResponseTimeMethod();
             this.theInterval = setInterval(this.setNowTimes,60*1000); // every minute
        },

        destroyed() {
            // clearInterval(this.theInterval);
        },

    }
</script>


<style scoped>
    .chat .msg_cotainer {
        margin-left: 0px;
    }
</style>