<template>
<div class="alert alert-info">
    <strong>Activare modul PRO</strong>
    <p>Obține mai mulți clienți, deblochează proiectele din platformă. <a href="#" class="btn btn-success btn-sm" @click.prevent="activateAccount">Activare cont</a></p>
</div>
</template>

<script>
export default {
    name: "ActivateProAccountComponent",

    methods: {
        activateAccount: function(){
            console.log('fire');

            this.$swal({
                title: 'Activare cont de profesionist',
                text: "Ești în punctul de a deveni un profesionist REFORMEX. Acest lucru îți permite să deblochezi proiectele din platformă și să contactezi clienții..",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    axios.post(`/api/professionals/activate/account`).then(response => {
                        if(response.data.success){
                            this.$swal({
                                title: 'Felicitări!',
                                text: "Ai activat contul cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });
                            
                            location.reload();

                        } else if(response.data.errors) {
                            Vue.$toast.open({
                                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                                type: 'error',
                                duration: 6000
                            });
                        }
                    }).catch(err => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    });
                }
            });

            
        }
    }
}
</script>

<style>

</style>