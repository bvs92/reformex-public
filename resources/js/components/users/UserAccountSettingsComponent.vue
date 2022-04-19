<template>
<div>
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title ">Setări cont</h3>
        <!-- <div class="card-options">
            <button class="btn btn-sm btn-info" @click="modalFunc">Modifica</button>
        </div> -->
    </div>

    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item" v-if="user.status == 1"><i class="fa fa-cog text-info" aria-hidden="true"></i> Contul este <span class="tag tag-green">activ</span>
                <button class="btn btn-sm btn-info float-right" @click="changeStatus" v-if="!once">Dezactivează</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else><b-spinner small type="grow"></b-spinner>Prelucrare...</button>
            </li>
            <li class="list-group-item" v-else><i class="fa fa-cog text-danger" aria-hidden="true"></i> Contul este <span class="tag tag-red">inactiv</span>
                <button class="btn btn-sm btn-info float-right" @click="changeStatus" v-if="!once">Activează</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else><b-spinner small type="grow"></b-spinner>Prelucrare...</button>
            </li>
            
            <li class="list-group-item"><i class="fa fa-cog text-info" aria-hidden="true"></i> Eliminare utilizator! 
                <button class="btn btn-sm btn-danger float-right" @click="deleteUser" v-if="!once_delete">Elimină</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else>Prelucrare...</button>
            </li>

            <li class="list-group-item" v-if="email_status !== null"><i class="fa fa-cog text-info" aria-hidden="true"></i> Status email (<span>{{ this.email_status ? 'Verificat' : 'Neverificat' }}</span>)
            <template v-if="email_status">
                <button class="btn btn-sm btn-danger float-right" @click="changeUserEmailStatus" v-if="!status_change_email">Marcare neverificat</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else>Prelucrare...</button>
            </template>
            <template v-else>
                <button class="btn btn-sm btn-success float-right" @click="changeUserEmailStatus" v-if="!status_change_email">Marcare verificat</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else>Prelucrare...</button>
            </template>
            </li>
        </ul>
    </div>

</div>
</div>
</template>

<script>
export default {
    name: "UserAccountSettingsComponent",

    data(){
        return {
            user: {},
            once: false,
            once_delete: false,
            status_change_email: false,
            email_status: null
        }
    },

    props: {
        the_user: Object
    },

    methods: {
        changeStatus: async function(){
            this.once = true;
            await axios.post(`/api/admin/users/${this.user.id}/change/status`).then(async response => {

                if(response.data.success){
                    this.user = response.data.user;

                    this.$swal({
                        title: 'Succes',
                        text: "Acțiune executată cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });

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
            }).finally(() => {
                this.once = false;
            });
        },

        deleteUser: function(){
            this.once_delete = true;

            this.$swal({
                title: 'Eliminare utilizator',
                text: "Ești sigur că vrei eliminarea acestui utilizator? Acțiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    axios.post(`/api/admin/users/${this.user.id}/delete`).then(async response => {

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
                            window.location="/users/all"

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
                this.once_delete = false;
            });
        },

        changeUserEmailStatus: async function(){
            this.status_change_email = true;
            await axios.post(`/api/admin/users/${this.user.id}/email/change/status`).then(async response => {

                if(response.data.success){
                    // console.log('noul user este', response.data.user);
                    this.user = response.data.user;
                    this.user.is_email_verified ? (this.email_status = true) : (this.email_status = false);

                    this.$swal({
                        title: 'Succes',
                        text: "Acțiune executată cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });

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
            }).finally(() => {
                this.status_change_email = false;
            });
        }
    },

    created(){
        this.user = this.the_user;
        // console.log("Userul este ---------------------", this.user);


        this.user.is_email_verified ? (this.email_status = true) : (this.email_status = false);
    }
}
</script>