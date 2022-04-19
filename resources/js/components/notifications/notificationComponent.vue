<template>
    <div class="row my-1 bordered">
        
        <div class="col-lg-1 justify-content-center">
            <div class="form-check" style="margin: 0 auto; margin-top: 20px;">
                <input class="form-check-input position-static" type="checkbox" id="" value="" @change="changeCheck(notification)" :checked="notification.isSelected ? true : false" >
            </div>
            <!-- <input class="form-check-input m-auto" type="checkbox" value="" id=""> -->
        </div>
        
        <div class="col-lg-11" @mouseover="showActions = true" @mouseleave="showActions = false" >
            <!-- <template v-if="notification.type.includes('TicketNotification')"> 
                <a @click="markAsReadOnVisit(notification.id)" :href="notification.data.type.includes('ticket_deleted') ? '/tickets/list' : '/tickets/get/id/' + notification.data.ticket_uuid">
                    <TicketNotificationComponent :notification="notification" />            
                </a>
            </template> -->


            <!-- <a @click="markAsReadOnVisit(notification.id)" :href="'/tickets/get/id/' + notification.data.ticket_uuid" v-if="notification.type.includes('TicketChatActionNotification')">
                <TicketChatActionNotificationComponent :notification="notification" />
            </a> -->

            <!-- <a @click="markAsReadOnVisit(notification.id)" :href="'/tickets/get/id/' + notification.data.ticket_uuid" v-if="notification.type.includes('TicketMessageInactiveUserNotification')">
                <TicketMessageInactiveUserNotification :notification="notification" />
            </a> -->


            <a @click="markAsReadOnVisit(notification.id)" :href="'/cupoane/toate/solicitari'" v-if="notification.type.includes('RequestCouponNotification')">
                <CouponRequest :notification="notification" />
            </a>

            <a @click="markAsReadOnVisit(notification.id)" :href="'/cupoane/personal'" v-if="notification.type.includes('SendCouponToUserNotification')">
                <CouponsComponent :notification="notification" />
            </a>

            <a @click="markAsReadOnVisit(notification.id)" :href="'/cupoane/solicitari'" v-if="notification.type.includes('ResponseCouponRequestNotification')">
                <CouponsRequestsComponent :notification="notification" />
            </a>

            <!-- <a @click="markAsReadOnVisit(notification.id)" :href="'/demands/id/' + notification.data.demand_uuid" v-if="notification.type.includes('DemandBought')">
                <DemandBoughtComponent :notification="notification" />
            </a> -->

            <a @click="markAsReadOnVisit(notification.id)" :href="'/admin/demands/show/' + notification.data.demand_uuid" v-if="notification.type.includes('ReportDemandNotification')">
                <ReportDemandNotificationComponent :notification="notification" />
            </a>

            <a @click="markAsReadOnVisit(notification.id)" :href="'/cereri/pro/detalii/' + notification.data.demand_uuid" v-if="notification.type.includes('ResponseForReportedDemandNotification')">
                <ResponseForReportedDemandNotificationComponent :notification="notification" />
            </a>

            <!-- admin to user -->
            <a @click="markAsReadOnVisit(notification.id)" href="#" v-if="notification.type.includes('AdminChangeUserProAccount')">
                <AdminChangeUserProAccountNotificationComponent :notification="notification" />
            </a>

            <!-- <a @click="markAsReadOnVisit(notification.id)" :href="theUrl" v-if="notification.type.includes('TimelineAction')">
                <TimelineActionComponent :notification="notification" />
            </a> -->

            <!-- <div class="actions d-inline-flex justify-content-center my-1" v-if="showActions">
                <a class="btn btn-sm btn-primary mx-1" @click.prevent="marAsNotificationRead(notification.id)"><i class="ti-check"></i></a>
                <a class="btn btn-sm btn-danger mx-1" @click.prevent="deleteNotification(notification.id)"><i class="ti-trash"></i></a>
            </div> -->

        </div>

        <div class="text-center" v-if="isBlocked">
            <b-spinner label="Spinning"></b-spinner>
        </div>
        
        <!-- <div v-show="isBlocked" class="the_spinner">
            <div class="spinner1 d-flex justify-content-center">
                <bounce-loader size="20px" color="blue"></bounce-loader>  
            </div>
        </div> -->
        

    </div>
</template>


<script>

import TimeAgoSidebarComponent from '../TimeAgoComponent.vue';

import TicketNotificationComponent from "./_module/TicketNotificationComponent";
import TicketChatActionNotificationComponent from "./_module/TicketChatActionNotificationComponent";
import TicketMessageInactiveUserNotification from "./_module/TicketMessageInactiveUserNotification";
import DemandBoughtComponent from "./_module/DemandBoughtComponent";
import CouponsComponent from "./_module/CouponsComponent";
import CouponRequest from "./_module/CouponRequest";
import CouponsRequestsComponent from "./_module/CouponsRequestsComponent";
import TimelineActionComponent from "./_module/TimelineActionComponent";
import ReportDemandNotificationComponent from "./_module/ReportDemandNotificationComponent";
import ResponseForReportedDemandNotificationComponent from "./_module/ResponseForReportedDemandNotificationComponent";
import AdminChangeUserProAccountNotificationComponent from "./_module/AdminChangeUserProAccountNotificationComponent";

import BounceLoader from 'vue-spinner/src/BounceLoader.vue';

export default {
    name: "NotificationComponent",

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent,
        TicketNotificationComponent,
        TicketChatActionNotificationComponent,
        TicketMessageInactiveUserNotification,
        DemandBoughtComponent,
        TimelineActionComponent,
        BounceLoader,
        ReportDemandNotificationComponent,
        ResponseForReportedDemandNotificationComponent,
        AdminChangeUserProAccountNotificationComponent,
        CouponsComponent,
        CouponsRequestsComponent,
        CouponRequest
    },

    data() {
        return {
            theUrl: "",
            showActions: false,
            isBlocked: false
        }
    },

    props: {
        notification: Object
    },

    methods: {
        generateUrl: function(){
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


        marAsNotificationRead: async function(_id){
            this.isBlocked = true;
            await this.$store.dispatch('markAsRead', _id).finally(() => {this.isBlocked = false;});
        },

        markAsReadOnVisit: function(_id){
            this.$store.dispatch('markAsRead', _id);
        },

        deleteNotification: async function(_id){
            this.isBlocked = true;
            await this.$store.dispatch('deleteNotification', _id).finally(() => {this.isBlocked = false;});
        },

        changeCheck: function(notification) {
            // console.log('changeCheck', notification);
            this.$emit('notification:selected', notification);
        }
    },

    created(){
      this.generateUrl();
    }
}
</script>


<style scoped>
.actions {
    width: 100%;
    /* background:#f3f3f3; */
}

.color-grey {
    /* background-color: #f3f3f3!important; */
}

.bordered {
    border-bottom: 1px solid #e3e3e3;
}

.the_spinner {
        z-index: 999;
    /* position: absolute; */
    /* display: block; */
    width: 100%;
    /* top: auto; */
    background: white;
    margin-top: -130px;
    height: 150px;
    opacity: 0.5;
}
</style>

