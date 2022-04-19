<template>
<div class="col-md-12 col-lg-5 col-xl-4">
    <div class="card" v-if="get_resolvers && get_resolvers.length > 0">

        <div class="card-header">
            <div class="card-title">Moderatori participanti</div>
        </div>
        <div class="contacts_body p-0">
            <ul class="contacts mb-0">
                <li class="active" v-for="resolver in get_resolvers" :key="'resolver-' + resolver.id">
                    <div class="d-flex bd-highlight">
                        <!-- <div class="img_cont">
                            <img v-if="resolver.details.profile_photo" :src="'/' + resolver.details.profile_photo" class="rounded-circle user_img" alt="img">
                            <img v-else src="/assets/images/users/3.jpg" class="rounded-circle user_img" alt="img">
                        </div> -->
                        <div class="user_info">
                            <h6 class="mt-1 mb-0 ">{{resolver.details.first_name}} {{resolver.details.last_name}}</h6>
                            <span class="mr-3 text-muted">{{ resolver.details.email }}</span>
                        </div>
                        <div class="float-right text-right ml-auto mt-auto mb-auto">
                            <!-- <small>{{ resolver.details }}</small> -->
                            <span class="dot-label bg-success" v-if="resolver.user_id == current_user.id"></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>



    <div class="card" v-if="get_moderators && get_moderators.length > 0">
       
            <div class="card-header">
                <div class="card-title">Moderatori disponibili</div>
            </div>

            <template v-if="current_user.is_admin == true">
            <div class="contacts_body p-0" v-if="isLoaded">
                <ul class="contacts mb-0">
                    <template v-for="moderator in get_moderators" >
                    <li :key="'moderator' + moderator.id"  v-if="!resolvers_list.includes(moderator.id)">
                        <div class="d-flex">
                            <!-- <div class="img_cont">
                                <img v-if="moderator.profile_photo" :src="'/' + moderator.profile_photo" class="rounded-circle user_img" alt="img">
                                <img v-else src="/assets/images/users/3.jpg" class="rounded-circle user_img" alt="img">
                            </div> -->
                            <div class="user_info">
                                <h6 class="mt-1 mb-0 ">{{moderator.first_name}} {{moderator.last_name}}</h6>
                                <span class="mr-3 text-muted">{{ moderator.email }}</span>
                            </div>
                            <div class="float-right text-right ml-auto mt-auto mb-auto">
                                <span class="dot-label bg-success" v-if="moderator.id == current_user.id"></span>
                                <div v-else class="item-action dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="true"><i class="fe fe-more-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" >
                                        <!-- <a v-if="current_user.id == moderator.id" @click.prevent="addUserToConv(moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-plus"></i> Alatura-te conversatiei </a> -->
                                        <!-- <a @click.prevent="addUserToConv(moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-plus"></i> Adauga in conversatie </a>
                                        <div class="dropdown-divider"></div> -->
                                        <!-- <a v-if="current_user.id == moderator.id" @click.prevent="delegateToUser(ticket_id, moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Atribuie-ti acest tichet</a> -->
                                        <a @click.prevent="delegateToUser(ticket_id, moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Atribuie acest tichet</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    </template>
                </ul>
            </div>
            <div class="card-body" v-else>
                <div class="dimmer active">
                    <div class="spinner1">
                        <bounce-loader size="20px" color="blue"></bounce-loader>  
                    </div>
                </div>
            </div>
            </template>

            <template v-else>
            <div class="contacts_body p-0" v-if="isLoaded">
                <ul class="contacts mb-0">
                    <template v-for="moderator in get_moderators" >
                    <li :key="'moderator' + moderator.id"  v-if="!resolvers_list.includes(moderator.id)">
                        <div class="d-flex">
                            <div class="img_cont">
                                <img v-if="moderator.profile_photo" :src="'/' + moderator.profile_photo" class="rounded-circle user_img" alt="img">
                                <img v-else src="/assets/images/users/3.jpg" class="rounded-circle user_img" alt="img">
                            </div>
                            <div class="user_info">
                                <h6 class="mt-1 mb-0 ">{{moderator.first_name}} {{moderator.last_name}}</h6>
                                <span class="mr-3 text-muted">{{ moderator.email }}</span>
                            </div>
                            <div class="float-right text-right ml-auto mt-auto mb-auto">
                                <span class="dot-label bg-success" v-if="moderator.id == current_user.id"></span>
                                <div v-else class="item-action dropdown">
                                    <template v-if="resolvers_list.includes(current_user.id)">
                                    <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="true"><i class="fe fe-more-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" >
                                        <!-- <a v-if="current_user.id == moderator.id" @click.prevent="addUserToConv(moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-plus"></i> Alatura-te conversatiei </a> -->
                                        <!-- <a @click.prevent="addUserToConv(moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-plus"></i> Adauga in conversatie </a> -->
                                        <!-- <div class="dropdown-divider"></div> -->
                                        <!-- <a v-if="current_user.id == moderator.id" @click.prevent="delegateToUser(ticket_id, moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Atribuie-ti acest tichet</a> -->
                                        <!-- <a @click.prevent="delegateToUser(ticket_id, moderator)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Atribuie acest tichet</a> -->
                                    </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </li>
                    </template>
                </ul>
            </div>
            <div class="card-body" v-else>
                <div class="dimmer active">
                    <div class="spinner1">
                        <bounce-loader size="20px" color="blue"></bounce-loader>  
                    </div>
                </div>
            </div>
            </template>
     
    </div>

</div>

</template>


<script>
import BounceLoader from 'vue-spinner/src/BounceLoader.vue';

import {mapGetters} from 'vuex';

export default {
    name: "ListUsersComponent",

    data(){
        return {
            isLoaded: false
        }
    },

    components: {
        BounceLoader
    },

    props: {
        resolvers: Array,
        moderators: Array,
        current_user: Object,
        ticket_id: Number
    },


    computed: {
        ...mapGetters('moderators', ['get_moderators']),
        ...mapGetters('resolvers', ['get_resolvers']),

        resolvers_list: function(){
            return this.get_resolvers.map(item => {
                return item.user_id;
            });
        }

        // get_moderators: function(){
        //     if(this.resolvers && this.resolvers.length > 0){
        //         let self = this;
        //         if(this.moderators && this.moderators.length > 0){
        //             console.log('wtf resolvers before', self.resolvers);
        //             let new_moderators = this.moderators.map(item => {
        //                 console.log('wtf resolvers', self.resolvers);
        //                 let res = self.resolvers.filter(function(el) {
        //                     console.log('el este', el);
        //                     return el.user_id != item.id;
        //                 }); 

        //                 console.log('res este', res);

        //                 if(res.length > 0)
        //                     return item;
        //                 // if(self.resolvers.includes(item))
        //             });

        //             console.log('new_moderators este', new_moderators);
        //             return new_moderators;
        //         } else {
        //             return [];
        //         }
        //     } else {
        //         return this.moderators;
        //     }
        // }
    },

    methods: {
        // check_exist: function(moderator){
        //     if(this.resolvers){
        //         this.resolvers.forEach(item => {
        //             if(item.id == moderator.id){
        //                 return true;
        //             } else {
        //                 return false;
        //             }
        //         });
        //     } else {
        //         return false;
        //     }
        // },


        addUserToConv: function(moderator){
            console.log('adaugare moderator in conversatie', moderator);

            let self = this;

            axios.post(`/api/tickets/${this.ticket_id}/add/user/${moderator.id}`).then(response => {
                console.log('response.data este ', response.data);

                if(response.data.success){ 
                    

                    self.$store.dispatch('resolvers/initResolvers', this.ticket_id).then(() => {
                        // console.log('resolvers_list este ', self.resolvers);
                        console.log('reinit - get_resolvers este ', self.get_resolvers);
                    }).finally(function(){
                        setTimeout(function(){ 
                            self.isLoaded = true;
                        }, 2000);
                    });


                    Vue.$toast.open({
                        message: 'Ati adaugat utilizatorul in conversatie.',
                        type: 'success',
                        duration: 9000
                    });

                } else if(response.data.error){
                    Vue.$toast.open({
                        message: 'Am intampinat erori. Incercati mai tarziu.',
                        type: 'error',
                        duration: 9000
                    });
                }
            });
        },

        delegateToUser: function(ticket_id, moderator){
            console.log('delegam ticket_id', ticket_id);
            console.log('catre utilizator', moderator);
            let self = this;

            axios.post(`/api/tickets/${ticket_id}/delegate/to/user/${moderator.id}`).then(response => {
                if(response.data.success){ 
                    

                    self.$store.dispatch('resolvers/initResolvers', this.ticket_id).then(() => {
                        // console.log('resolvers_list este ', self.resolvers);
                        console.log('reinit - suntem la delegare ', self.get_resolvers);
                    }).finally(function(){
                        setTimeout(function(){ 
                            self.isLoaded = true;
                        }, 2000);
                    });


                    Vue.$toast.open({
                        message: 'Ati delegat tichetul utilizatorului ' + moderator.first_name + ' ' + moderator.last_name,
                        type: 'success',
                        duration: 9000
                    });

                } else if(response.data.error){
                    Vue.$toast.open({
                        message: 'Am intampinat erori. Incercati mai tarziu.',
                        type: 'error',
                        duration: 9000
                    });
                }
            });
        }
    },


    created(){
        console.log('resolvers_list, current_user', this.current_user);
        console.log('resolvers_list este', this.resolvers_list);
        let self = this;

        this.$store.dispatch('moderators/initModerators').finally(function(){
            setTimeout(function(){ 
                self.isLoaded = true;
             }, 2000);
        });


        self.$store.dispatch('resolvers/initResolvers', self.ticket_id).then(() => {
            // console.log('created - resolvers este ', self.resolvers);
            console.log('created - get_resolvers este ', self.get_resolvers);
        }).finally(function(){
            setTimeout(function(){ 
                self.isLoaded = true;
            }, 2000);
        });

        // if(this.moderators){
        //     this.moderators.forEach(item => {
        //         if(this.resolvers_list.includes(item.id)){
        //             console.log(item.id + ' exista in array.');
        //         }
        //     });
        // }
    }
}
</script>