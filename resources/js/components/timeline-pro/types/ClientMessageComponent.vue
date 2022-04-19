<template>
<div class="cbp_tmlabel">
    <div class="row">
        <div class="col-lg-12">
            <h2><a href="javascript:void(0);" class="text-default font-weight-bold" style="font-size:14px;">{{ client.complete_name }}</a><span> a trimis un mesaj.</span></h2>
        </div>
        
    </div>

    <p class="text-sm">
        {{ conversation.message }}
    </p>

    <template v-if="conversation.files && conversation.files.length > 0">
        <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
        <list-files 
        :conversation_type="conversation.type" 
        :the_type_path="'client_messages'" 
        :the_attached_files="client_files"
        ></list-files>
    </template>
</div>
</template>


<script>
import ListFiles from "../../ListFiles"

export default {
    name: "ClientMessageComponent",

    components: {
        'list-files': ListFiles
    },

    data(){
        return {
            client_files: null
        }
    },

    props: {
        conversation: Object,
        client: Object,
    },

    methods: {
    },

    created() {
        this.client_files = this.conversation.files.filter(item => {
            if(item.type == 'ClientMessageFile'){
                console.log('1. ClientMessageFile, merge?');
                return item;
            }
        });
    }
}
</script>