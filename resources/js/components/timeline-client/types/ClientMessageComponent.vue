<template>
<div class="cbp_tmlabel">
    <div class="row">
        <div class="col-lg-10">
            <h2><a href="javascript:void(0);" class="text-default font-weight-bold" style="font-size:14px;">{{ owner.complete_name }}</a><span> a trimis un mesaj.</span></h2>
        </div>

        <div class="col-lg-2">
            
            <div class="dropdown float-right">
                <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-more"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    
                    <a class="dropdown-item" @click="deleteMessage(conversation.id)"><i class="ti-trash"></i> Elimina</a>

                </div>
            </div>
        </div>
        
    </div>

    <p class="text-sm">
        {{ conversation.message }}
    </p>

    <template v-if="conversation.files && conversation.files.length > 0">
        <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
        <list-files 
        :current_user="current_user" 
        :conversation_type="conversation.type" 
        :the_type_path="'client_messages'" 
        :the_attached_files="client_files"
        :conv_id="conversation.id"
        :timeline="timeline"
        ></list-files>
    </template>
</div>
</template>

<script>
import ListFiles from "../../ListFiles";


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
        owner: Object,
        conversation: Object,
        current_user: Object,
        timeline: Object
    },

    methods: {
        async deleteMessage(id){
            // this.$emit('client_message:removed', id);
            await this.$store.dispatch('timeline_client/removeMessage', id);
        }
    },


    created() {
        this.client_files = this.conversation.files.filter(item => {
            if(item.type == 'ClientMessageFile'){
                console.log('2. ClientMessageFile, merge?');
                return item;
            }
        });
    }

}
</script>