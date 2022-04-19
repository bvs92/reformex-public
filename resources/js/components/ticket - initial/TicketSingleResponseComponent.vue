<template>
<div class="row" @mouseover="show_options = true" @mouseleave="show_options = false">
  <div class="col-lg-12 d-flex" :class="response.user_id == current_user.id ? 'justify-content-end' : 'justify-content-start'">
    <template v-if="response.user_id == current_user.id">
        <div class="col-lg-2" v-if="show_options">
            <div class="btn-group mt-2 mb-2">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    Actiuni <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item btn-sm" @click.prevent="deleteSingleMessage(response.id)"><i class="ti-trash"></i>Elimina</button>
                </div>
            </div>
        </div>

        <div class="msg_cotainer w-auto py-1 px-3">
            <p>{{ response.message }}</p>
            <span class="msg_time" style="min-width: 140px; text-align: right;">{{ this.calculateResponseTime }}</span>
            <!-- <span class="msg_time_send float-right">{{ finalResponseTime }}</span> -->
        </div>


        <div class="img_cont_msg mx-2">
            
            <img :src="'/' + response.user_details.profile_photo" alt="" class="rounded-circle user_img_msg avatar-md">
            {{ response_user.complete_name }}
        </div>

    </template>

    <template v-else>

        <div class="img_cont_msg mx-2">
            {{ response_user.complete_name }}
            <img :src="'/' + response.user_details.profile_photo" alt="" class="rounded-circle user_img_msg avatar-md">
        </div>

        <div class="msg_cotainer_send w-auto py-1 px-3 right">
            {{ response.message }}
            <!-- <span class="msg_time_send float-right">{{ finalResponseTime }}</span> -->
        <span class="msg_time_send float-right" style="min-width: 140px;text-align: left;">{{ this.calculateResponseTime }}</span>
        </div>

        
    </template>
  </div>


  <div class="row" 
  
  v-if="response.attached_files">
      <FileComponent :attached_files="response.attached_files" :content_align="response.user_id == current_user.id ? 'justify-content-end' : 'justify-content-start'" />
  </div>

</div>
</template>

<script>
import FileComponent from "./_module/FileComponent";

import { mapGetters } from 'vuex';

    export default {
        name: "TicketSingleResponseComponent",
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
    
</style>