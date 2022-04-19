<template>
  <button class="btn btn-default" @click.prevent="quit"><i class="fa fa-times"></i> Părăsește pagina</button>
</template>

<script>
export default {
    name: "QuitPublicDemandComponent",

    methods: {
        quit: function(){
            this.$swal({
                title: 'Ieșire din pagină',
                text: "Vrei să părăsești această pagină?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'https://www.reformex.ro';
                }
            });
        }
    }
}
</script>

<style>

</style>