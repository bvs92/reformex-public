<template>
<form @submit="sendForm">
    <div class="row" style="margin-top: 100px;" v-if="isLoaded">
        
        <div class="col-lg-8">
            <div class="form-group">
                <label for="message">Mesajul dumneavoastra</label>
                <textarea class="form-control" id="message" name="message" rows="4" v-model="message" @keydown="typingUser"></textarea>
            </div>

            <files-upload @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent"></files-upload>
        </div>
        <div class="col-lg-4 my-4">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block mt-4"><i class="fa fa-send"></i> Trimite mesaj </button>
            </div>
        </div>
        
    </div>
    <!-- <div v-else class="isNotLoaded">
        <moon-loader size="20px" color="blue" class="m-1 d-flex justify-content-center"></moon-loader>
    </div> -->
</form>
</template>

<script>
import FilesUpload from "../FilesUpload";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

    export default {
        name: "TicketCreateMessageComponent",

        components: {
            'files-upload': FilesUpload,
            MoonLoader
        },
        data: function() {
            return {
                message: '',
                ticket: {},
                files: null,
                // bus: new Vue() // to reset data in FilesUpload
            }
        },
        
        props: {
            theTicket: Object,
            isLoaded: Boolean
        },

        computed: {},

        methods: {
            sendForm: function(event){
                event.preventDefault();
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
                axios.post(`/api/tickets/${this.theTicket.id}/response/store`, formData, config).then(response => {
                    let savedResponse = response.data;
                    this.message = '';
                    
                    // console.log('ce este asta?');
                    // console.log(response.data);

                    // emit event to parent $emit-> send new created object
                    this.$emit('response:saved', savedResponse);
                    // this.bus.$emit('reset');


                    // reset child component
                    // this.$refs.uploadFileComponent.value = null;
                    const ref = this.$refs.uploadFileComponent;
                    Object.assign(ref.$data, ref.$options.data());
                    console.log('reset ref data', ref.$options.data());

                    // event.target.reset();
                    this.files = null;

                }).catch(error => {
                    console.log(error);
                });

                
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
        display:none;
    }
</style>