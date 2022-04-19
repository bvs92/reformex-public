<template>
<div class="card">
    <div class="card-header ">
        <h3 class="card-title ">Tichete</h3>
        <div class="card-options">
            <a href="/tichete/inregistrare/nou" id="add__new__category" class="btn btn-md btn-primary " v-if="getUserStatus == 1"><i class="fa fa-plus"></i> Tichet nou</a>
            <a href="/tichete/creare/nou" id="add__new__category" class="btn btn-md btn-primary" v-else><i class="fa fa-plus"></i> Tichet nou</a>
        </div>
    </div>
    <div class="card-body">
        <TicketsTablePersonalComponent :tickets="getAllTickets" :total="getTotalTickets" :isBusy="busy" :userStatus="getUserStatus" />
    </div>
</div>
</template>

<script>
import TicketsTablePersonalComponent from "./_modules/TicketsTablePersonalComponent";
import {mapGetters} from 'vuex';

export default {
    name: "ListPersonalTicketsComponent",

    components: {
        TicketsTablePersonalComponent
    },

    computed: {
        ...mapGetters('tickets', ['getAllTickets', 'getTotalTickets', 'getUserStatus']),
    },

    data(){
        return {
            tickets: [],
            total: 0,
            busy: false,

            selected_search: null,
        }
    },

    methods: {
       
    },

    created(){
        this.busy = true;
        this.$store.dispatch('tickets/initPersonalTickets').finally(() => {
            this.busy = false;

            console.log('getAllTickets este', this.getAllTickets);
        });
    }
}
</script>

<style>

</style>