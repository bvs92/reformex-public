<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ owner.complete_name }}</a><span> ati facut o <span class="badge badge-success mr-1 mb-1 mt-1">propunere</span> catre <strong>{{ pro.complete_name }}</strong> pentru <strong v-if="demand_uuid">cererea (#{{ demand_uuid }})</strong><strong v-else><i class="side-menu__icon ti-na"></i> cererea a fost eliminata</strong>.</span></h2>

            <div v-if="the_conversation.status == 0">
                <p class="ml-4 text-disabled"><span class="badge badge-warning mr-1 mb-1 mt-1">IN CURS</span> In asteptarea unui raspuns.</p>
            </div>

            <div v-if="the_conversation.status == 1">
                <p class="ml-4 text-disabled"><span class="badge badge-success mr-1 mb-1 mt-1">ACCEPTAT</span> {{ pro.complete_name }} a acceptat propunerea dumneavoastra. <span v-if="the_conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(the_conversation.response) }}</span></p>
            </div>

            <div v-if="the_conversation.status == 2">
                <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">REFUZAT</span> {{ pro.complete_name }} a refuzat propunerea dumneavoastra. <span v-if="the_conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(the_conversation.response) }}</span></p>
            </div>

            <div v-if="the_conversation.status == 4">
                <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">ANULAT</span> {{ owner.complete_name }}, ati anulat aceasta propunere si castigatorul. <span class="text-muted">Data: {{ formatElementUpdatedMethod(the_conversation) }}</span></p>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ProspectComponent",

    props: {
        owner: Object,
        pro: Object,
        the_conversation: Object,
        demand_uuid: String
    },

    methods: {
        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },

        formatElementUpdatedMethod: function(element){
            return moment(element.updated_at).format("DD-MM-YYYY, HH:mm");
        },
    }
}
</script>