<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ client.complete_name }}</a><span> vrea sa fiti <span class="badge badge-success  mr-1 mb-1 mt-1">castigatorul</span> <strong v-if="demand_uuid">cererii (#{{ demand_uuid }})</strong><strong v-else><i class="side-menu__icon ti-na"></i> cererea a fost eliminata</strong>.</span></h2>
            <div v-if="conversation.status == 0">
                <p class="ml-4 text-disabled"><span class="badge badge-warning mr-1 mb-1 mt-1">IN CURS</span> {{ client.complete_name }} asteapta un raspuns. Selectati mai jos un raspuns.</p>
                <div class="d-flex flex-row">
                    <a class="btn btn-success btn-sm text-white m-2" @click="accept_proposal">Accepta propunerea</a>
                    <a class="btn btn-danger btn-sm text-white m-2" @click="refuse_proposal">Refuza propunerea</a>
                </div>
            </div>
            <div v-if="conversation.status == 1">
                <p class="ml-4 text-disabled"><span class="badge badge-success mr-1 mb-1 mt-1">ACCEPTAT</span> {{ pro.complete_name }}, ati acceptat propunerea trimisa de catre {{ client.complete_name }}. <span v-if="conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(conversation.response) }}</span></p>
            </div>
            <div v-if="conversation.status == 2">
                <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">REFUZAT</span> {{ pro.complete_name }}, ati refuzat propunerea trimisa de catre {{ client.complete_name }}. <span v-if="conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(conversation.response) }}</span></p>
            </div>
        </div>
    </div>
</div>
</template>


<script>
import ListFiles from "../../ListFiles"

export default {
    name: "ProspectComponent",

    components: {
        'list-files': ListFiles
    },

    data(){
        return {
            // key_name: 'proposal'
        }
    },

    props: {
        conversation: Object,
        client: Object,
        pro: Object,
        demand_uuid: String
    },

    methods: {
        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },


        accept_proposal: async function (){
            console.log('accept proposal...');

            await this.$store.dispatch('acceptProposal', this.conversation.id).then(response => {
                this.$store.dispatch('initConversation');
            });
        },


        refuse_proposal: async function(){
            await this.$store.dispatch('refuseProposal', this.conversation.id);
        }
    }
}
</script>