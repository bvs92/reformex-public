<template>
<div class="d-flex flex-row my-4" v-if="active_winner.professional_id != pro.professional_id">
    <div class="alert" role="alert">
        <span class="py-2">Castigatorul cererii curente (<strong>#{{ active_winner.demand_uuid }}</strong>) este  {{ active_winner.company_name }}</span>
        <span>Actiuni: <a @click="changeWinner" class="btn btn-success btn-sm text-white">Desemneaza-l castigator pe {{ pro.complete_name }}</a></span>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "ActiveWinnerComponent",

    props: {
        active_winner: Object,
        pro: Object,
        demand: Object,
        timeline: Object
    },

    computed: {
        ...mapGetters('timeline_client', ['getConversation', 'getTimeline']),
    },

    methods: {
        changeWinner(){
            this.$swal({
                title: 'Schimbare castigator cerere',
                text: "Confirmarea presupune anularea intelegerii cu "+ this.active_winner.company_name +" si stabilirea unei noi intelegeri cu "+ this.pro.complete_name +", dar si dezactivarea cererii pentru ceilalti profesionisti implicati.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, anuleaza'
            }).
            then(async (result) => {
                if (result.isConfirmed) {
                        this.$swal(
                        'Confirmat!',
                        this.pro.complete_name + ' este noul castigator al cererii #' + this.demand.uuid,
                        'success'
                    );

                    await this.$store.dispatch('timeline_client/changeWinner', {'demand': this.demand,'pro': this.pro}).then(async response => {
                        await this.$store.dispatch('timeline_client/initConversation', this.timeline);
                        await this.$store.dispatch('timeline_client/initActiveWinner', this.timeline);
                        await this.$store.dispatch('timeline_client/getProspects', this.timeline);
                    });
                }
            });
        },
    }
}
</script>