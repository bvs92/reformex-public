<template>
    <div class="cbp_tmlabel">

        <div class="row">
            <div class="col-lg-12">
                <h2><a href="javascript:void(0);" class="font-weight-bold" style="font-size:14px;">{{ pro.complete_name }}</a> <span> ati trimis un mesaj.</span></h2>
            </div>
        </div>
        
        <p class="text-sm">
            {{ conversation.message }}
        </p>

        <template v-if="conversation.files && conversation.files.length > 0">
            <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
            <list-files 
            :conversation_type="conversation.type" 
            :the_type_path="'quotes'" 
            :the_attached_files="quote_files"
            ></list-files>
        </template>
    </div>
</template>

<script>
import ListFiles from "../../ListFiles";

export default {
    name: "QuoteComponent",

    components: {
        'list-files': ListFiles
    },

    data(){
        return {
            quote_files: null
        }
    },

    props: {
        conversation: Object,
        pro: Object
    },

    created() {
        this.quote_files = this.conversation.files.filter(item => {
            if(item.type == 'QuoteFile'){
                console.log('QuoteFile, merge?');
                return item;
            }
        });
    }


}
</script>