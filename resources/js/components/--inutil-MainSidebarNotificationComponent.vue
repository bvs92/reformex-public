<template>
  <div>
        <a :href="'/notifications/' + notification.id" v-if="notification.type.includes('TicketNotification')">

            <div class="list d-flex align-items-center border-bottom p-2">
                <div class="wrapper w-100 ml-3" style="margin:10px auto;">

                    <div class="row mt-1">
                        <div class="col-lg-10">
                            <div class="mt-3">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                <span v-if="notification.type.includes('TicketNotification')">
                                    <template v-if="notification.data.type.includes('ticket_created')">
                                        a creat un nou tichet
                                    </template>   
                                </span>
                            </div>
                        </div>
                    </div>


                    <p class="mb-0 d-flex">
                        <span>Numar tichet #{{ notification.data.ticket_uuid }}</span>
                    </p>
                    <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
                </div>
            </div><!-- LIST END -->
        </a>

        <a :href="'/demands/id/' + notification.data.demand_uuid" v-if="notification.type.includes('DemandBought')">

            <div class="list d-flex align-items-center border-bottom p-2">
                <div class="wrapper w-100 ml-3" style="margin:10px auto;">

                    <div class="row mt-1">
                        <div class="col-lg-10">
                            <div class="mt-3">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                <!-- <span v-if="notification.type.includes('TicketNotification')">
                                    <template v-if="notification.data.type.includes('ticket_created')">
                                        a creat un nou tichet
                                    </template>   
                                </span> -->
                                a interactionat cu cererea #{{ notification.data.demand_uuid }}.
                            </div>
                        </div>
                    </div>


                    <p class="mb-0 d-flex">
                        <!-- <span>Numar tichet #{{ this.notification.data.ticket_uuid }}</span> -->
                    </p>
                    <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
                </div>
            </div><!-- LIST END -->
        </a>

        <template v-if="notification.type.includes('TimelineAction')">
        <a :href="theUrl" >

            <div class="list d-flex align-items-center border-bottom p-2">
                <div class="wrapper w-100 ml-3" style="margin:10px auto;">

                    <div class="row mt-1">
                        <div class="col-lg-10">
                            <!-- <div class="mt-3" v-if="notification.data.type.includes('proposition')"> -->
                            <div class="mt-3" v-if="notification.data.type == 'proposition'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                v-a facut o propunere in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'accept'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                a acceptat propunerea in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'refuse'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                a refuzat propunerea in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'confirm_winner'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                v-a ales castigator in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'refuse_winner'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                a RESPINS intelegerea in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'review_created'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                a lasat o recenzie in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>

                            <div class="mt-3" v-if="notification.data.type == 'cancel_winner'">
                                {{ user.last_name + ' ' + user.first_name }} ({{ user.email }}) 
                                a ANULAT intelegerea in conversatia #{{ notification.data.timeline_uuid }}.
                            </div>
                        </div>
                    </div>


                    <p class="mb-0 d-flex">
                        <!-- <span>Numar tichet #{{ notification.data.ticket_uuid }}</span> -->
                    </p>

                    <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
                </div>
            </div><!-- LIST END -->
        </a>
        </template>

  </div>
</template>

<script>
import TimeAgoSidebarComponent from './TimeAgoSidebarComponent';

    export default {
        data: function() {
            return {
                interval:null,
                user: {},
                currentTime: null,
                notificationTime: null,
                theUrl: ''
            }
        },
        props: {
            notification: Object,
            theUser: Object
        },

        components: {
            "time-ago-sidebar-component": TimeAgoSidebarComponent
        },

        computed: {
        },

        methods: {},

        created() {

            if(this.notification){
                if(this.notification.type.includes('TimelineAction')){
                    if(this.notification.data.type.includes('proposition')){
                        this.theUrl = `/timeline/pro/id/${this.notification.data.timeline_uuid}`;
                    } else if(this.notification.data.type.includes('review_created')) {
                        this.theUrl = `/timeline/pro/id/${this.notification.data.timeline_uuid}`;
                    } else {
                        this.theUrl = `/timeline/client/id/${this.notification.data.timeline_uuid}`;
                    }
                }
            }

            this.user = this.notification.user_details;

            
        },

        destroyed() {},

    }
</script>


