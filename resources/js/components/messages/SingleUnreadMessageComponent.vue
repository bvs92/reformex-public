<template>

<a class="dropdown-item d-flex pb-3" :href="'/tickets/get/id/' + message.uuid" v-if="getUnreadMessages > 0">
    
        <!-- <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="/assets/images/users/10.jpg"></span> -->
    
<p><span class="badge badge-danger badge-pill">{{ getUnreadMessages }}</span></p>
    <div>
        <strong>Tichet #{{ message.uuid }}</strong> 
        <!-- <span class="nav-unread badge badge-danger badge-pill">{{ unreadMessage.unread_responses }}</span> -->
        <!-- <div class="small text-muted" v-if="last_response">
            {{ calculateMessageTime }} <span class="tag tag-gray" style="font-size:9px;"></span>
        </div> -->
    </div>
</a>

</template>

<script>

    export default {
        name: "SingleUnreadMessageComponent",
        data: function() {
            return {
                unreadMessage: {},
                unreadResponses: null,
                last_response: null,
                currentTime: null
            }
        },
        props: {
            message: Object,
            the_last: Object,
            unreadResponsesNumber: Number
        },

        computed: {
            getUnreadMessages: function () {
                if(window.location.pathname == '/tickets/id/' + this.unreadMessage.uuid){
                    this.setNewDataUnreadMessages()
                } else {
                    this.setNewData();
                }
                return this.unreadResponses
            },

            calculateMessageTime: function(){
                return this.calculateMessageTimeMethod()
            }
        },

        methods: {
            setNewData: function(){
                this.unreadMessage = this.message;
                this.unreadResponses = this.unreadResponsesNumber;
                this.last_response = this.the_last;
            },

            setNewDataUnreadMessages: function(){
                this.unreadMessage = this.message;
                this.unreadResponses = 0;
            },

            setNowTimes(){
                this.calculateMessageTimeMethod();
            },

            calculateMessageTimeMethod: function(){
                // console.log('se executa...');
                this.currentTime = moment().format('YYYY MM DD, HH:mm');
                // this.unreadMessageTime = moment(this.last_response.created_at).format("YYYY MM DD, HH:mm");
                this.unreadMessageTime = moment(this.last_response.created_at).format("YYYY MM DD, HH:mm");
                var startTime = moment(this.unreadMessageTime, 'YYYY MM DD, HH:mm a');
                var endTime = moment(this.currentTime, 'YYYY MM DD, HH:mm a');
                var resultTime = startTime.diff(endTime, 'minutes');
                var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
                return asHuman;
                // return 'hehehe';
            }


        },

        created() {
            this.unreadMessage = this.message;
            this.unreadResponses = this.unreadResponsesNumber;
            // this.last_response = this.message.last_response;
            this.last_response = this.the_last;
            let self = this;
            


            // preia ultimul raspuns necitit al tiketului - eroare 429, prea multe cereri
            // axios.get(`/api/tickets/${this.unreadMessage.id}/responses/last/unread`).then(response => {
            //    if(response.data.response){
            //         this.last_response = response.data.response;
            //         this.theInterval = setInterval(this.setNowTimes,60*1000); // every minute
            //    }
        
            // }).catch(
            //     function(error){
            //         console.error(error);
            //     }
            // ); 
            // end axios get last unread message

            

            // // marcare mesaje ca citite.
            if(window.location.pathname == '/tickets/get/id/' + this.unreadMessage.uuid){
                if(this.unreadMessage.unread_responses && this.unreadMessage.unread_responses > 0){

                    axios.post(`/api/tickets/${this.unreadMessage.id}/responses/markAsRead`).then(async response => {
                        await this.$store.dispatch('messages/initNumberUnreadMessages');
                        await this.$store.dispatch('messages/initTickets');
                    }).catch(
                        function(error){
                            console.error(error);
                        }
                    ); 
                }
            }


        },

        destroyed() {
            // clearInterval(this.theInterval);
        },

        update(){
            // this.unreadResponses = this.unreadResponsesNumber;
        }

    }
</script>


<style scoped>
    
</style>