<template>
<div class="card" v-if="the_demand.reports && the_demand.reports.length > 0">
    <div class="card-header">
        <h3 class="card-title">Reclamații cereri ({{ the_demand.reports.length }})</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">

        <div class="card" v-for="report in the_demand.reports" :key="'report_id_' + report.id">
            
        <div class="card-header">
            <h4 class="card-title">Id reclamație #{{ report.id }} </h4>
            <div class="card-options">
                <!-- <a :href="'' + report.id" class="btn btn-sm btn-info">Vezi raportare</a> -->
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
            </div>
        </div>
        <div class="card-body">
            <p>Utilizator: <strong>{{ report.user_full_name }} (ID #{{ report.id }})</strong> </p>
            
            <p class="card-text"><small class="text-muted">Dată creare: <strong>{{ formatElementTimeMethod(report.created_at) }}</strong></small></p>
            <p class="card-text">Mesaj:</p>
            <div class="card-text">{{ report.message }}</div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-lg-6">
              <p>Status: 
                  <span class="badge badge-warning" v-if="report.status == 0">În curs</span>
                  <span class="badge badge-danger" v-else-if="report.status == 1">Cerere invalidă</span>
                  <span class="badge badge-success" v-else>Cerere validă</span>
              </p>
            </div>
            <div class="col-lg-6">
            <a v-if="!once_delete" href="#" class="btn btn-danger btn-sm ml-2 float-right" @click.prevent="deleteReport(report.id)"> <i class="ti-trash"></i> Elimină</a>
            <a v-else href="#" class="btn btn-danger btn-sm ml-2 float-right" disabled> <i class="ti-trash"></i> Elimină</a>
        </div>
          </div>
        </div>

        </div>

    </div>

    <div class="card-footer">
     
        <div class="row">
        <div class="col-lg-6">
            <span class="badge badge-warning" v-if="the_demand.status == 0">NEVERIFICATĂ</span>
            <span class="badge badge-success" v-else-if="the_demand.status == 1">Cerere validă</span>
            <span class="badge badge-danger" v-else>Cerere invalidă</span>
        </div>
        <div class="col-lg-6">
            <template v-if="the_demand.status == 0">
                <a href="#" class="btn btn-success btn-sm ml-2 float-right" @click.prevent="demandIsValid"> <i class="ti-reload"></i> Cerere validă</a>
                <a href="#" class="btn btn-danger btn-sm ml-2 float-right" @click.prevent="demandIsInvalid"> <i class="ti-reload"></i> Cerere invalidă</a>
            </template>

            <template v-else-if="the_demand.status == 1">
                <a href="#" class="btn btn-danger btn-sm ml-2 float-right" @click.prevent="demandIsInvalid"> <i class="ti-reload"></i> Cerere invalidă</a>
            </template>

            <template v-else-if="the_demand.status == 2">
                <a href="#" class="btn btn-success btn-sm ml-2 float-right" @click.prevent="remarkDemandValid"> <i class="ti-reload"></i> Cerere validă</a>
            </template>
            
        </div>
        </div>
    </div>

</div>
</template>

<script>
export default {
    name: "ListDemandReports",

    props: {
        the_demand: Object
    },

    data(){
        return {
            once_delete: false,
            once_status: false,
            demand: null
        }
    },

    computed: {
        // reports
    
    },

    methods: {
        formatElementTimeMethod: function(element){
            // return moment(element).format("DD-MM-YYYY, HH:mm");
            return moment(element).format("lll");
        },

        deleteReport: function(report_id){
            this.once_delete = true;
            this.$swal({
                title: 'Eliminare reclamație',
                text: "Ești sigur că vrei să elimini această reclamație? Acțiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    axios.post(`/api/demands/reports/${report_id}/delete`).then(async response => {

                        if(response.data.success){

                            this.$emit('report:deleted', report_id);

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
        },

        demandIsValid: function(){
            this.once_status = true;
            this.$swal({
                title: 'Marcare cerere ca fiind validă.',
                text: "Cererea va fi marcată validă și reclamațiile declarate ca terminate.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    console.log('fire');

                    axios.post(`/api/admin/demands/${this.demand.uuid}/mark/valid`).then(async response => {

                        if(response.data.success){

                            // this.demand.status = 1;
                            // this.demand.reports = this.demand.reports.map(item => {
                            //     item.status = 2;
                            //     return item;
                            // });

                            this.$emit('change:demandStatus');

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
                this.once_status = false;
            });
        },

        remarkDemandValid: function(){
            this.once_status = true;
            this.$swal({
                title: 'Marcare cerere ca fiind validă.',
                text: "Cererea va fi marcată validă și reclamațiile declarate ca terminate.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // console.log('fire');

                    axios.post(`/api/admin/demands/${this.demand.uuid}/remark/valid`).then(async response => {

                        if(response.data.success){

                            this.$emit('change:demandStatus');

                            // this.demand.status = 1;
                            // this.demand.reports = this.demand.reports.map(item => {
                            //     item.status = 2;
                            //     return item;
                            // });

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
                this.once_status = false;
            });
        },

        demandIsInvalid: function(){
            this.once_status = true;
            this.$swal({
                title: 'Marcare cerere va invalidă.',
                text: "Cererea va fi marcată ca invalidă și cumparatorilor li se vor returna costurile de cumpărare.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // console.log('fire');

                    axios.post(`/api/admin/demands/${this.demand.uuid}/mark/invalid`).then(async response => {

                        if(response.data.success){

                            // this.demand.status = 2;
                            // this.demand.reports = this.demand.reports.map(item => {
                            //     item.status = 1;
                            //     return item;
                            // });

                            this.$emit('change:demandStatus');

                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executata cu succes.",
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
                this.once_status = false;
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