<template>
<div class="row">
    <div class="col-lg-6">
        <p>Elimină cererea</p>
    </div>
    <div class="col-lg-6">
        <a href="#" class="btn btn-danger btn-sm ml-2 float-right" @click.prevent="deleteDemand"> <i class="ti-trash"></i> Elimină</a>
    </div>
</div>
</template>

<script>
export default {
    name: "DeleteDemandComponent",

    data(){
        return {
            data: null,
            once_delete: false
        }
    },

    props: {
        the_demand: Object
    },

    methods: {

        deleteDemand: function(){
            this.once_delete = true;
            this.$swal({
                title: 'Elimină cererea',
                text: "Ești sigur că vrei eliminarea acestei cereri? Această acțiune este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {

                
                    axios.post(`/api/admin/demands/${this.demand.id}/delete/`).then(async response => {

                        if(response.data.success){

                            // this.$emit('delete:demand', this.demand.id);

                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            window.location = '/admin/demands/all';


                        } else if(response.data.errors){
                            Vue.$toast.open({
                                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                                type: 'error',
                                duration: 6000
                            });
                        }

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                            type: 'error',
                            duration: 6000
                        });
                    });
                }
            }).finally(() => {
                this.once_delete = false;
            });
        },
    },

    created(){
        this.demand = this.the_demand;
    }
}
</script>

<style>

</style>