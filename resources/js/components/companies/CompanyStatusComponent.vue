<template>
<div class="mb-3">
    <div class="card mb-3" v-if="user">
        <div class="card-header">
            <h3 class="card-title ">Status firmă</h3>
        </div>

        <div class="card-body">
            <div v-if="!user.badge">
                <div class="row">
                    <div class="col-lg-6">
                        <p class="text-center" style="font-size: 16px;padding: 10px;"><i class="fa fa-times-circle-o pr-1" style="color:grey;"></i>Firmă neverificată.</p>
                    </div>
                    <div class="col-lg-6">
                        <template v-if="!once">
                            <button class="btn btn-success" @click.prevent="verifyStatus">Marcare ca verificată</button>
                        </template>
                        <template v-else>
                            <button class="btn btn-success btn-loading" disabled="disabled">Marcare ca verificată</button>
                        </template>
                    </div>
                </div>
            </div>
            <div v-else>
                <div v-if="user.badge.verified">
                    <div class="row">
                        <div class="col-lg-6">
                            <p  class="text-center" style="font-size: 16px;padding: 10px;"><i class="fa fa-check-circle pr-1" style="color:green;"></i>Firma verificată.</p>
                        </div>
                        <div class="col-lg-6">
                            <template v-if="!once">
                                <button class="btn btn-danger" @click.prevent="unverifyStatus">Marcare ca neverificată</button>
                            </template>
                            <template v-else>
                                <button class="btn btn-danger btn-loading" disabled="disabled">Marcare ca neverificată</button>
                            </template>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="text-center" style="font-size: 16px;padding: 10px;"><i class="fa fa-times-circle-o pr-1" style="color:grey;"></i>Firma neverificată.</p>
                        </div>
                        <div class="col-lg-6">
                            <template v-if="!once">
                                <button class="btn btn-success" @click.prevent="verifyStatus">Marcare ca verificată</button>
                            </template>
                            <template v-else>
                                <button class="btn btn-success btn-loading" disabled="disabled">Marcare ca verificată</button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
export default {
    name: "CompanyStatusComponent",

    data(){
        return {
            once: false,
            user: null
        }
    },

    props: {
        the_user: Object
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        verifyStatus: function(){


            this.once = true;

            axios.post(`/api/companies/verify/${this.user.id}`).then(response => {
                // console.log('raspuns', response.data);
                if(response.data.success){
                    this.$swal({
                        title: 'Firmă verificată',
                        text: "Felicitări. Această firmă a fost verificată cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });

                    this.user.badge = response.data.badge;

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întampinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).finally(() => {
                this.once = false;
            });
        },

        unverifyStatus: function(){


            this.once = true;

            axios.post(`/api/companies/unverify/${this.user.id}`).then(response => {
                // console.log('raspuns', response.data);
                if(response.data.success){
                    this.$swal({
                        title: 'Firmă neverificată',
                        text: "Această firmă a fost marcată ca neverificată.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });

                    this.user.badge = response.data.badge;

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).finally(() => {
                this.once = false;
            });
        },

 
    },

    created(){
        this.user = this.the_user;
    }
}
</script>

<style>

</style>