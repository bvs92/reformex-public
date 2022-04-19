<template>
<div class="card" v-if="demand.buyers && demand.buyers.length > 0">
    <div class="card-header">
        <h3 class="card-title">Cumparători ({{ demand.buyers.length }})</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <div class="card" v-for="buyer in demand.buyers" :key="'buyer_id_' + buyer.id">
            
        <div class="card-header">
            <h4 class="card-title">{{ buyer.user.full_name }} </h4>
            <div class="card-options">
                <template v-if="buyer.user.user_name_profile">
                    <a :href="'/public/profil/profesionist/' + buyer.user.user_name_profile.username" class="btn btn-sm btn-info">Profil</a>
                </template>
                <template v-else>
                    <a :href="'/public/profil/profesionist/' + buyer.user.username" class="btn btn-sm btn-info">Profil</a>
                </template>

                <!-- <a :href="'/users/admin/show/' + buyer.user.id" class="btn btn-sm btn-info">Profil</a> -->
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            </div>
        </div>
        <div class="card-body">
            <p>E-mail: {{ buyer.user.email }} <span class="float-right">Plată cerere: {{ getBuyerCost(buyer) }} RON</span></p>
            <p class="card-text"><small class="text-muted">Dată cumpărare: {{ formatElementTimeMethod(buyer.created_at) }}</small></p>
        </div>
        <div class="card-footer">
            <a v-if="once_delete" class="btn btn-sm btn-danger" disabled>Elimină</a>
            <a href="#" v-else @click.prevent="deleteBuyer(buyer.id)" class="btn btn-sm btn-danger">Elimină</a>
        </div>
        </div>

    </div>
    <div class="card-footer">
        <div class="row">
        <div class="col-lg-6">
            <p>Total: <strong>{{ getTotalRevenue }} RON</strong></p>
        </div>
        <div class="col-lg-6">
        </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ListDemandBuyers",

    props: {
        demand: Object
    },

    data(){
        return {
            once_delete: false
        }
    },

    computed: {
        // buyers
        getTotalRevenue: function(){
            return this.demand.total_price * this.demand.buyers.length;
        },
    },

    methods: {
        formatElementTimeMethod: function(element){
            // return moment(element).format("DD-MM-YYYY, HH:mm");
            return moment(element).format("lll");
        },

        getBuyerCost: function(buyer){
            return parseFloat(buyer.amount_paid / 100);
        },

        deleteBuyer: function(buyer_id){
            this.once_delete = true;
            this.$swal({
                title: 'Eliminare cumpărător',
                text: "Ești sigur că vrei eliminarea acestui cumpărător? Acțiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {
                 
                    await axios.post(`/api/admin/demands/buyer/${buyer_id}/delete`).then(async response => {

                        if(response.data.success){

                            // emit event to parent
                            await this.$emit('buyer:deleted');

                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            // redirect to users.
                            // window.location="/users/all"

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
        }
    }
}
</script>

<style>

</style>