<template>
    <div class="card">
        <div class="card-header" v-if="the_client">
            <h3 class="card-title">Comunicati cu <strong>{{ the_client.complete_name }}</strong></h3>
        </div>
        <div class="card-body p-6">
            <form @submit="sendForm">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="message_one">Mesajul dumneavoastra</label>
                            <textarea class="form-control" id="message" name="message" v-model="message" rows="3"></textarea>
                        </div>

                        <files-upload @files:selected="filesSelected" @files:removed="filesRemoved" ref="uploadFileComponent"></files-upload>

                    </div>
                    <div class="col-lg-4">

                        <div class="form-group">
                            <button type="submit" class="btn btn-azure btn-block mt-4" :disabled="conversation_status !== 0"><i class="fa fa-send"></i> Trimite mesaj </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="inactive-conversation" v-if="conversation_status !== 0">
            <h2>Conversatia este marcata ca inactiva.</h2>
        </div>
    </div><!-- TABS-END -->
</template>

<script>
import FilesUploadTimeline from "../FilesUploadTimeline";

export default {
    name: "TimelineCreateMessageComponent",

    components: {
        'files-upload': FilesUploadTimeline
    },

    data(){
        return {
           message: '',
            timeline: {},
            files: null,
        }
    },

    props: {
        the_client: Object,
        the_timeline: Object,
        conversation_status: Number
    },

    methods:{
        sendForm: function(event){
            event.preventDefault();

            let formData = new FormData();
            formData.append('message', this.message);


            if(this.files){
                for(let file of this.files){
                    formData.append('the_files[]', file);
                }
            }

            if(this.conversation_status == 0){
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                axios.post(`/api/timelines/${this.the_timeline.uuid}/response/storeQuote`, formData, config).then(async response => {
                    let savedResponse = response.data[0];
                    this.message = '';
                    
                    // console.log('ce este asta?');
                    // console.log(response.data[0]);
    
                    // emit event to parent $emit-> send new created object
                    // this.$emit('response:saved', savedResponse);

                    // await this.$store.commit('push_response', savedResponse);
                    await this.$store.dispatch('initConversation', this.the_timeline); //merge
                    // this.bus.$emit('reset');
    
    
                    // reset child component
                    const ref = this.$refs.uploadFileComponent;
                    Object.assign(ref.$data, ref.$options.data());
                    this.files = null;
                    formData.delete('the_files[]');
    
                    // event.target.reset();
                    // this.files = null;
    
                }).catch(error => {
                    console.log(error);
                }).finally(() => {
                    // this.$store.commit('files_upload/set_selected_files_array', null);
                    this.$store.commit('files_upload/set_reset_files');
                });
            } else {
                this.$swal(
                    'Conversatie inactiva!',
                    'Conversatia a fost marcata de catre client ca inactiva. Nu puteti comunica deocamdata.',
                    'info'
                );
                
            }

            
        },

        filesSelected(event){
            this.files = event;
        },

        filesRemoved(){
            this.files = null;
        },
        
    },

    mounted(){
       this.timeline = this.the_timeline;
       console.log("this.timeline ---------");
       console.log(this.timeline);
       console.log("this.the_timeline ---------");
       console.log(this.the_timeline);
    }
}
</script>

<style scoped>
div.inactive-conversation {
    width: 100%;
    display: block;
    height: 100%;
    background: #e9e9e9;
    z-index: 2;
    position: absolute;
    opacity: 0.5;
}

div.inactive-conversation h2 {
    text-align: center;
    margin-top: 10%;
    color: red;
}

</style>