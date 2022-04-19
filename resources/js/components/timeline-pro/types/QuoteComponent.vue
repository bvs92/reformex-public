<template>
<div class="cbp_tmlabel">

    <div class="row">
        <div class="col-lg-10">
            <h2><a href="javascript:void(0);" class="font-weight-bold" style="font-size:14px;">{{ pro.complete_name }}</a> <span> ati trimis un mesaj.</span></h2>
        </div>
        <div class="col-lg-2">
            
            <div class="dropdown float-right">
                <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-more"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    
                    <a class="dropdown-item" @click="deleteQuote(conversation.id)"><i class="ti-trash"></i> Elimina</a>

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
            :the_type_path="'quotes'" 
            :the_attached_files="quote_files"
            :timeline="timeline"
        >
        </list-files>
    </template>
</div>
</template>


<script>
import ListFiles from "../../ListFiles"

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
        pro: Object,
        current_user: Object,
        timeline: Object
    },

    methods: {
        deleteQuote(id){
            this.$store.dispatch('removeQuote', id);
        },
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