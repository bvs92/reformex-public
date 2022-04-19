<template>
  <div class="card text-white mb-0" :class="selectedClass">
        <div class="card-header">
            <h3 class="card-title">{{ announcement.title }}</h3>
            <div class="card-options">
                <button @click="modalShow = !modalShow" class="btn btn-primary btn-sm ml-2">Detalii anunț</button>
            </div>
        </div>

        <b-modal v-model="modalShow" ok-only :title="announcement.title">
            <div v-html="announcement.description"></div>    
        </b-modal>

    </div>
</template>

<script>
export default {
    name: "SingleAnnouncement",

    data(){
        return {
            modalShow: false
        }
    },

    computed: {
        selectedClass: function(){
            if(this.announcement.type == 'verde'){
                return 'bg-success';
            } else if(this.announcement.type == 'galben'){
                return 'bg-warning';
            } else {
                return 'bg-info';
            }
        }
    },

    props: [
        'announcement'
    ]
}
</script>

<style>

</style>