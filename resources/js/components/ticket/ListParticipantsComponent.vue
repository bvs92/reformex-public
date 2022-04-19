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
                        <div class="user_info">
                            <h6 class="mt-1 mb-0 ">{{resolver.details.first_name}} {{resolver.details.last_name}}</h6>
                            <span class="mr-3 text-muted">{{ resolver.details.email }}</span>
                        </div>
                        <div class="float-right text-right ml-auto mt-auto mb-auto">
                            <span class="dot-label bg-success" v-if="resolver.user_id == current_user.id"></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>

</template>


<script>
// import BounceLoader from 'vue-spinner/src/BounceLoader.vue';

import {mapGetters} from 'vuex';

export default {
    name: "ListParticipantsComponent",

    data(){
        return {
            isLoaded: false
        }
    },

    components: {
        // BounceLoader
    },

    props: {
        resolvers: Array,
        moderators: Array,
        current_user: Object,
        ticket_id: Number
    },


    computed: {
        // ...mapGetters('moderators', ['get_moderators']),
        ...mapGetters('resolvers', ['get_resolvers']),

        resolvers_list: function(){
            return this.get_resolvers.map(item => {
                return item.user_id;
            });
        }


    },

    methods: {
      
    },


    created(){
        let self = this;


        self.$store.dispatch('resolvers/initResolvers', self.ticket_id).then(() => {
            // console.log('created - resolvers este ', self.resolvers);
            // console.log('created - get_resolvers este ', self.get_resolvers);
        }).finally(function(){
            setTimeout(function(){ 
                self.isLoaded = true;
            }, 2000);
        });

    }
}
</script>