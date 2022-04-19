<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title">Hei, {{ owner.complete_name }}. Acum poti selecta castigatorul final al cererii. PROSPECT ID: {{ prospect_accepted.id }}</h3>
                </div>
                <div class="card-body">
                    <h5><span>Profesionistul <strong>{{ pro.complete_name }}</strong> poate fi castigatorul final al cererii #{{ demand.uuid }}</span></h5>
                    <br>
                    <div class="d-flex flex-row my-4">
                        <a class="btn btn-success text-white mx-2" @click="confirmWinner" >Desemneaza castigator final cerere.</a>
                        <!-- <a class="btn btn-gray text-white mx-2" @click="cancelWinner">Anuleaza aceasta intelegerea.</a> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ConfirmWinnerComponent",
    
    props: {
        owner: Object,
        pro: Object,
        demand: Object,
        prospect_accepted: Object,
        timeline: Object
    },

    methods: {
        confirmWinner() {
        // Use sweetalert2
            this.$swal({
                title: 'Desemnare castigator final cerere!',
                text: "Confirmarea presupune incheierea unei intelegeri cu acest profesionist si dezactivarea cererii pentru ceilalti profesionisti implicati.",
                icon: 'success',
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
                        this.pro.complete_name + ' este castigatorul final al cererii #' + this.demand.uuid,
                        'success'
                    );

                    console.log('executam confirm din component');

                    this.$store.dispatch('timeline_client/confirmWinner', this.timeline).then(response => {
                        this.$store.dispatch('timeline_client/initConversation', this.timeline);
                    });

                    // executa codul salvare castigator.
                    // await axios.post(`/api/winners/${this.timeline.uuid}/confirm`)
                    // .then(response => {
                    //     if(response.data.error){
                    //         console.log('eroare');
                    //         console.log(response.data.error);
                    //     } else if(response.data.winner){
                    //         // console.log(response.data.winner);
                    //         this.get_conversation();
                    //     }
                    // })
                    // .catch(error => {
                    //     console.error(error);
                    // });
                }
            });
        },
    }
}
</script>