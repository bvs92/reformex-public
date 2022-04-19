<template>
<div class="list d-flex align-items-center border-bottom color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <div class="mt-3">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }}
                    <!-- ({{ notification.user_details.email }})  -->
                    <span v-if="notification.type.includes('TicketNotification')">
                        <template v-if="notification.data.type.includes('ticket_created')">
                            a <span class="badge badge-primary">creat</span> un nou tichet <strong>#{{ notification.data.ticket_uuid }}</strong>
                        </template> 

                        <template v-if="notification.data.type.includes('ticket_deleted')">
                            a <span class="badge badge-danger">eliminat</span> tichetul <strong>#{{ notification.data.ticket_uuid }}</strong>
                        </template> 

                            
                        <template v-if="notification.data.type.includes('ticket_status_changed')">
                            a marcat tichetul <strong>#{{ notification.data.ticket_uuid }}</strong> ca 
                            <span class="badge badge-success" v-if="notification.data.ticket_status == '0'">deschis</span> 
                            <span class="badge badge-danger" v-else>inchis</span>
                        </template>   
                    </span>
                </div>
            </div>
        </div>


        <!-- <p class="mb-0 d-flex">
            <span>Numar tichet #{{ notification.data.ticket_uuid }}</span>
        </p> -->
        <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
    </div>
</div><!-- LIST END -->
</template>

<script>
import TimeAgoSidebarComponent from '../../TimeAgoComponent.vue';

export default {
    name: "TicketNotificationComponent",

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },

    props: {
        notification: Object
    }
}
</script>