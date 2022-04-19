<template>
<form @submit="sendForm" v-if="theTicket.status == '0'">
<div class="card-footer" v-if="isLoaded">
    <div class="msb-reply d-flex">
        <span class="input-group-text attach_btn">
            <i class="fe fe-paperclip mr-2" @click="openFiles()"></i>
            <!-- <i class="fe fe-mic mr-2"></i> -->
            <i class="fa fa-smile-o"></i>
        </span>
        <textarea placeholder="Scrieti mesajul aici..." id="message" name="message" rows="4" v-model="message" @keydown="typingUser" @keypress.enter="sendForm"></textarea>
        <button type="submit"><i class="fa fa-paper-plane-o"></i></button>
    </div>
    <files-upload 
    @files:selected="filesSelected" 
    @files:removed="filesRemoved" 
    ref="uploadFileComponent" 
    :class="files && files.length > 0 ? '' : 'display-none'"
    ></files-upload>
</div>
</form>
<div class="card-footer" v-else>
    <div class="msb-reply d-flex justify-content-center">
        <p class="text-muted text-center">Comunicarea este dezactivata pentru ca tichetul este inchis.</p>
    </div>
</div>
</template>

<script>
import FilesUpload from "../FilesUpload";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

    export default {
        name: "ChatMessageComponent",

        components: {
            'files-upload': FilesUpload,
            MoonLoader
        },
        data: function() {
            return {
                message: '',
                ticket: {},
                files: null,
                // bus: new Vue(), // to reset data in FilesUpload
                resetAll: false
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

            sendForm: function(event){
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
    
                    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                    axios.post(`/api/tickets/${this.theTicket.id}/response/store`, formData, config).then(async response => {
                        let savedResponse = response.data;
                        this.message = '';
                        
                        // console.log('ce este asta?');
                        // console.log(response.data);
    
                        // emit event to parent $emit-> send new created object
                        await this.$emit('response:saved', savedResponse);
                        // this.bus.$emit('reset');
    
    
                        // reset child component
                        // this.$refs.uploadFileComponent.value = null;
                        // const ref = this.$refs.uploadFileComponent;
                        // Object.assign(ref.$data, ref.$options.data());
                        // console.log('reset ref data', ref.$options.data());
                        await this.$store.commit('files_upload/set_reset_files');
    
                        // event.target.reset();
                        this.files = null;
    
                    }).catch(error => {
                        console.log(error);
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
</style>