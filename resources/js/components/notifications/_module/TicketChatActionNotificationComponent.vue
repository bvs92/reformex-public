<template>
<div class="list d-flex align-items-center border-bottom color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <div class="mt-3">
                    <!-- {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    a interactionat cu cererea #{{ notification.data.demand_uuid }}. -->
                    <div class="mt-3" v-if="notification.data.type == 'joins'">
                        {{ notification.data.sender_name }} 
                        <span class="badge badge-success">v-a adaugat</span> in conversatia tichet #{{ notification.data.ticket_uuid }}.
                    </div>

                    <div class="mt-3" v-else-if="notification.data.type == 'delegate'">
                        {{ notification.data.sender_name }} 
                        <span class="badge badge-success">v-a atribuit</span> tichetul #{{ notification.data.ticket_uuid }}.
                    </div>
                </div>
            </div>
        </div>


        <p class="mb-0 d-flex">
            <!-- <span>Numar tichet #{{ this.notification.data.ticket_uuid }}</span> -->
        </p>
        <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
    </div>
</div><!-- LIST END -->
</template>

<script>
import TimeAgoSidebarComponent from '../../TimeAgoComponent.vue';

export default {
    name: "TicketChatActionNotificationComponent",

    props: {
        notification: Object
    },

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },
}
</script>