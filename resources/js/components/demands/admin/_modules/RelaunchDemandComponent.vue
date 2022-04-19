<template>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stare cerere</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <p>Număr oferte acceptate: {{ demand.offers }}</p>
        <p>Număr oferte primite: {{ demand.buyers.length }}</p>
    </div>

    <div class="card-footer">
     
        <div class="row">
            <div class="col-lg-6">
              <p>Stare: 
                  <span class="badge badge-success" v-if="the_demand.state == 1">Activă</span>
                  <span class="badge badge-danger" v-else>Inactivă</span>
              </p>
            </div>
            <div class="col-lg-6">
              <a href="#" class="btn btn-info btn-sm ml-2 float-right" @click.prevent="relaunchDemand"> <i class="ti-reload"></i> Relansare (+3)</a>
            </div>
        </div>
    </div>

</div>
</template>

<script>
export default {
    name: "RelaunchDemandComponent",

    data(){
        return {
            data: null,
            once_relaunch: false
        }
    },

    props: {
        the_demand: Object
    },

    methods: {

        relaunchDemand: function(){
            this.once_relaunch = true;
            this.$swal({
                title: 'Relansare cerere',
                text: "Această acțiune va adăuga înca 3 oferte la numărul maxim admis și va marca cererea ca activă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {


                    axios.post(`/api/admin/demands/${this.demand.uuid}/relaunch/`).then(async response => {

                        if(response.data.success){

                            this.$emit('relaunch:demand');
                            this.demand.offers += 3;

                            this.$swal({
                                title: 'Succes',
                                text: "Actiune executată cu succes.",
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
                this.once_relaunch = false;
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