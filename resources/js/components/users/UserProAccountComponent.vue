<template>
    <div>
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title ">Setări cont profesionist</h3>
        <!-- <div class="card-options">
            <button class="btn btn-sm btn-info" @click="modalFunc">Modifica</button>
        </div> -->
    </div>

    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item" v-if="user.is_pro == 1"><i class="fa fa-cog text-info" aria-hidden="true"></i> Contul de profesionist este <span class="tag tag-green">activ</span>
                <button class="btn btn-sm btn-warning float-right" @click="desactivateAccount" v-if="!once">Dezactivează</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else><b-spinner small type="grow"></b-spinner>Prelucrare...</button>
            </li>
            <li class="list-group-item" v-else><i class="fa fa-cog text-danger" aria-hidden="true"></i> Contul de profesionist este <span class="tag tag-red">inactiv</span>
                <button class="btn btn-sm btn-info float-right" @click="activateAccount" v-if="!once">Activează</button>
                <button class="btn btn-sm btn-default float-right" disabled="disabled" v-else><b-spinner small type="grow"></b-spinner>Prelucrare...</button>
            </li>
    
        </ul>
    </div>

    <div class="card-body">
        <template v-if="user.user_name_profile">
        <p>Nume utilizator: {{ user.user_name_profile.username }}</p>
        <p><a :href="'https://firme.reformex.ro/detalii-firma/' + user.user_name_profile.username" target="_blank">https://firme.reformex.ro/detalii-firma/<strong>{{user.user_name_profile.username}}</strong></a></p>
        </template>
        <template v-else>
            <p>Nume utilizator: {{ user.username }}</p>
            <p><a :href="'https://firme.reformex.ro/detalii-firma/' + user.username" target="_blank">https://firme.reformex.ro/detalii-firma/<strong>{{user.username}}</strong></a></p>
        </template>
    </div>

</div>
</div>
</template>

<script>
export default {
    name: "UserProAccountComponent",

    props: {
        the_user: Object
    },

    data(){
        return {
            once: false,
            user: {}
        }
    },

    methods: {
        activateAccount: function(){
            this.once = true;

            axios.post(`/api/admin/users/${this.user.id}/activate/pro`).then(async response => {

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

        desactivateAccount: function(){
    

            this.once = true;

            this.$swal({
                title: 'Eliminare cont profesionist',
                text: "Ești sigur că vrei eliminarea contului de profesionist? Setările contului de profesionist vor fi pierdute.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță.'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    axios.post(`/api/admin/users/${this.user.id}/desactivate/pro`).then(async response => {

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
                    });
                }
            }).finally(() => {
                this.once = false;
            });


            

        }
    },

    created(){
        this.user = this.the_user;
    }
}
</script>