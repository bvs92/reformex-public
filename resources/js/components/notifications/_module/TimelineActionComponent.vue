<template>
<div class="list d-flex align-items-center border-bottom color-grey">
    <div class="wrapper w-100 ml-3" style="margin:0px auto;">

        <div class="row mt-1">
            <div class="col-lg-10">
                <!-- <div class="mt-3" v-if="notification.data.type.includes('proposition')"> -->
                <div class="mt-3" v-if="notification.data.type == 'proposition'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-primary">v-a facut o propunere</span> in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'accept'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-success">a acceptat</span> propunerea in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'refuse'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-danger">a refuzat</span> propunerea in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'confirm_winner'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-success">v-a ales castigator</span> in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'refuse_winner'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-danger">a respins</span> intelegerea in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'review_created'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-info">a lasat o recenzie</span> in conversatia #{{ notification.data.timeline_uuid }}.
                </div>

                <div class="mt-3" v-if="notification.data.type == 'cancel_winner'">
                    {{ notification.user_details.last_name + ' ' + notification.user_details.first_name }} ({{ notification.user_details.email }}) 
                    <span class="badge badge-danger">a anulat</span> intelegerea in conversatia #{{ notification.data.timeline_uuid }}.
                </div>
            </div>
        </div>


        <p class="mb-0 d-flex">
            <!-- <span>Numar tichet #{{ notification.data.ticket_uuid }}</span> -->
        </p>

        

        <time-ago-sidebar-component :element="notification"></time-ago-sidebar-component>
    </div>
</div><!-- LIST END -->
</template>

<script>
import TimeAgoSidebarComponent from '../../TimeAgoComponent.vue';

export default {
    name: "TimelineActionComponent",

    components: {
        "time-ago-sidebar-component": TimeAgoSidebarComponent
    },

    props: {
        notification: Object
    }

}
</script>
