<template>
    <!-- <div class="dropdown d-md-flex header-settings">
        <a href="#" class="nav-link icon " data-toggle="sidebar-right" data-target=".sidebar-right">
            <i class="fe fe-bell"></i>
            <span class="nav-unread badge badge-success badge-pill">{{getUnreadNotificationsNumber}}</span>
  
        </a>
    </div> -->

    <div>
    <!-- <b-button v-b-toggle.sidebar-right><i class="fe fe-bell"></i>
        <span class="nav-unread badge badge-success badge-pill">{{getUnreadNotificationsNumber}}</span>
    </b-button> -->

    <a href="#" class="nav-link icon " v-b-toggle.sidebar-right data-target=".sidebar-right">
        <i class="fe fe-bell"></i>
        <span class="nav-unread badge badge-success badge-pill">{{getUnreadNotificationsNumber}}</span>
    </a>

    <SidebarNotificationsComponentSecond />
  </div>

</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import SidebarNotificationsComponentSecond from './SidebarNotificationsComponentSecond';

export default {
    name: "UnreadNotificationsHeader",
    computed: {
        ...mapGetters({
            // map `this.doneCount` to `this.$store.getters.doneTodosCount`
            //getNotifications: 'personalNotifications',
            getUnreadNotificationsNumber: 'unreadNotifications'
        })
    },

    components: {
        SidebarNotificationsComponentSecond
    },


    methods: {

        ...mapActions({
            initializeNotifications: 'getNotifications' // map `this.initializeNotifications()` to `this.$store.dispatch('getNotifications')`
        })
        
    },


    created(){
        // this.initializeNotifications();
    }


}
</script>