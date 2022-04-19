<template>
<div class="list d-flex align-items-center border-bottom color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <div class="mt-3">
                    <div class="mt-3">
                        Mesaj nou in tichet #{{ notification.data.ticket_uuid }} 
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
    name: "TicketMessageInactiveUserNotification",

    props: {
        notification: Object
    },

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },
}
</script>