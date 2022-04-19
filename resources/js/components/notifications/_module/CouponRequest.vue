<template>
<div class="list d-flex align-items-center color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <div class="mt-3">
                    Solicitare cupon de la {{ notification.user_details.email }}.
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
    name: "CouponRequest",

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },

    props: {
        notification: Object
    }
}
</script>
