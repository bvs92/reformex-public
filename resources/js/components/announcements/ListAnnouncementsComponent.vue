<template>
<div class="card" v-if="getTotalActiveAnnouncements > 0">
    <div class="card-header">
        <div class="card-title">Anunțuri</div>
    </div>

    <SingleAnnouncement v-for="(announcement, index) in getActiveAnnouncements" :key="index + '-' + announcement.id" :announcement="announcement" />
</div>
</template>

<script>
import SingleAnnouncement from "./SingleAnnouncement.vue";
import { mapGetters } from 'vuex';

export default {
    name: "ListAnnouncementsComponent",

    components: {
        SingleAnnouncement
    },

    computed: {
        ...mapGetters('announcements', ['getActiveAnnouncements', 'getTotalActiveAnnouncements'])
    },

    created(){
        this.$store.dispatch('announcements/initActiveAnnouncements');
    }
}
</script>

<style>

</style>