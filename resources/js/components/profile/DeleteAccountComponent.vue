<template>
<div class="card-header">
    <h3 class="card-title">CONT</h3>
    <div class="card-options">
        <a @click.prevent="openModal" class="btn btn-danger text-white btn-sm">Eliminare cont</a>
    </div>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Eliminare cont">
        <div class="col-lg-12 my-2">
            <button class="btn btn-danger btn-block text-white" v-if="!once" @click="deleteAccount">Elimina cont</button>
            <b-button variant="danger" disabled v-else>
                <b-spinner small></b-spinner>
                <span class="sr-only">Se elimină contul...</span>
            </b-button>
        </div>
    </b-modal>
</div>
</template>

<script>



export default {
    name: "DeleteAccountComponent",

    data(){
        return {
            modalShow: false,
            once: false,
        }
    },


    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
        },

        deleteAccount: function(){
            this.once = true;

            this.$swal({
                title: 'Eliminare cont utilizator',
                text: "Ești sigur că vrei eliminarea acestui cont? Acțiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    await axios.post(`/api/users/account/delete`).then(response => {

                        if(response.data.success){

                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            // redirect to users.
                            window.location="/login"

                        } else if(response.data.errors){
                            Vue.$toast.open({
                                message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                                type: 'error',
                                duration: 6000
                            });
                        }

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    });
                }
            }).finally(() => {
                this.once = false;
            });
        },

       
    }
}
</script>

<style>

</style>