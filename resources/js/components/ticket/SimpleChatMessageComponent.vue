<template>
<form @submit="sendForm" v-if="theTicket.status == '0'">
<div class="card-footer" v-if="isLoaded">
    <div class="my-2">
        <textarea class="form-control" placeholder="Scrieti mesajul aici..." id="message" name="message" rows="10" v-model="message" @keydown="typingUser" @keypress.enter="sendForm"></textarea>
        
    </div>
    <div class="row">
        <div class="col-lg-8">
            <files-upload 
            @files:selected="filesSelected" 
            @files:removed="filesRemoved" 
            ref="uploadFileComponent" 
            ></files-upload>

            <!-- <span class="input-group-text attach_btn" @click="openFiles()">
                <i class="fe fe-paperclip mr-2"></i> Atasare fisiere
            </span> -->
        </div>
        <div class="col-lg-4 my-6">
            <button type="submit" class="btn btn-primary float-right" v-if="!once_send"><i class="fa fa-paper-plane-o"></i> Trimite mesaj</button>
            <button class="btn btn-primary float-right" v-else disabled="disabled"><i class="fa fa-paper-plane-o"></i> Se trimite</button>
        </div>
    </div>
    
</div>
</form>
<div class="card-footer" v-else>
    <div class="msb-reply d-flex justify-content-center">
        <p class="text-muted text-center">Comunicarea este dezactivata pentru ca tichetul este inchis.</p>
    </div>
</div>
</template>

<script>
import FilesUploadTicket from "../FilesUploadTicket";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

    export default {
        name: "SimpleChatMessageComponent",

        components: {
            'files-upload': FilesUploadTicket,
            MoonLoader
        },
        data: function() {
            return {
                message: '',
                ticket: {},
                files: null,
                // bus: new Vue(), // to reset data in FilesUpload
                resetAll: false,
                once_send: false
            }
        },
        
        props: {
            theTicket: Object,
            isLoaded: Boolean
        },

        computed: {
            filesStatus: function(){
                return (this.files && this.files.length > 0) ? true : false;
            },

            messageStatus: function(){
                return (this.message.trim().length > 0) ? true : false;
            }
        },

        methods: {

            openFiles: async function(){
                if(this.files && this.files.length > 0){
                    this.files = null;
                    await this.$store.commit('files_upload/set_reset_files');
                    // this.resetAll = true;
                    // this.bus.$emit('changeFileField', true);
                }
                await this.$refs.uploadFileComponent.$refs.filesUploadChild.click();
            },

            sendForm: async function(event){
                event.preventDefault();
                

                if(this.theTicket.status == '1'){
                    Vue.$toast.open({
                        message: 'Nu puteti comunica. Tichetul este inchis.',
                        type: 'error',
                        duration: 9000
                    });
                    return;
                }

                if(this.filesStatus || this.messageStatus){
                    console.log('tot merge');

                    

                    let newResponse = {
                        message: this.message,
                        // ticket_id: this.theTicket.id,
                    };
    
                    let formData = new FormData();
                    formData.append('message', this.message);
    
                    if(this.files){
                        for(let file of this.files){
                            formData.append('the_files[]', file);
                        }
                    }

                    this.once_send = true;
    
                    const config = { headers: { 'Content-Type': 'multipart/form-data', 'Accept': 'application/json' } };
                    axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
                    await axios.post(`/api/tickets/${this.theTicket.id}/response/store`, formData, config).then(async response => {
                        let savedResponse = response.data;
                        this.message = '';
                        
    
                        // emit event to parent $emit-> send new created object
                        await this.$emit('response:saved', savedResponse);
   
                        await this.$store.commit('files_upload/set_reset_files');
    
                        // event.target.reset();
                        this.files = null;
    
                    }).catch(error => {
                        console.log(error);
                    }).finally(() => {
                        this.once_send = false;
                    });
                }



                
            },

            filesSelected(event){
                this.files = event;
            },

            filesRemoved(){
                this.files = null;
            },

            typingUser: function(){
                // console.log('typing user ...');
                this.$emit('typing:user');
            }
        },

        created() {
            this.ticket = this.theTicket;
        },

        destroyed() {},

    }
</script>


<style scoped>
    div.isNotLoaded{
        width: 95%;
        display: block;
        /* height: 94%; */
        background: #e9e9e9;
        z-index: 2;
        position: absolute;
        opacity: 0.5;
    }

    .display-none {
        display: none;
    }

    .chat .msb-reply textarea {
        height: auto;
        margin-left: 0px;
    }
</style>