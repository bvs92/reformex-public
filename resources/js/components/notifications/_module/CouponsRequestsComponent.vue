<template>
<div class="list d-flex align-items-center border-bottom color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <div class="mt-3" v-if="notification.data.type == 'accept'">
                    Solicitare de cupon aprobată.
                </div>
                <div class="mt-3" v-else>
                    Solicitare de cupon respinsă.
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
    name: "CouponsRequestsComponent",

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },

    props: {
        notification: Object
    }
}
</script>
