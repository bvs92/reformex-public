<template>
    <div class="row" v-if="attached_files" @mouseover="options = true" @mouseleave="options = false">
        <template v-if="!loading_files">
        <div class="col-lg-3 col-md-6 col-6" v-for="file in attached_files" :key="file.id" >
           
           <div v-if="file">
            <template v-if="file.mime_type == 'image/jpeg' || file.mime_type == 'image/png' || file.mime_type == 'image/webp'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" data-lightbox="photos">
                    <img class="img-fluid img-thumbnail mt-4" :src="'/storage/'+ the_type_path +'/' + file.name" />
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/pdf'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/csv'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/msword'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.ms-excel'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/plain'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else>
                <template v-if="the_type_path">
                    <a v-if="this.the_type_path" :href="'/storage/'+ this.the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <div class="d-flex" v-if="conversation_type == 'Quote'">
                <a :href="file.name" @click.prevent="download('quotes', file)" v-show="options" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a>
                <template v-if="current_user && current_user.id == file.user_id">
                    <a :href="file.name" @click.prevent="deleteQuoteFile(file)" v-show="options" class="btn btn-sm btn-danger mx-2"><i class="ti-trash"></i></a>
                </template>
            </div>

            <div class="d-flex" v-else-if="conversation_type == 'ClientMessage'">
                <a :href="file.name" @click.prevent="download('client_messages', file)" v-show="options" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a>
                <template v-if="current_user && current_user.id == file.user_id">
                    <a :href="file.name" @click.prevent="deleteClientFile(file)" v-show="options" class="btn btn-sm btn-danger mx-2"><i class="ti-trash"></i></a>
                </template>
            </div>

           </div>

            
            
        </div>
        </template>
        <div class="col-lg-12 col-md-12 d-flex flex-column" v-else-if="loading_files">
            <moon-loader size="20px" color="blue" class="moonLoader"></moon-loader>
            <p>Preluam fisierele. Va rugam asteptati...</p>
        </div>

      </div>

      

</template>

<script>
import { numeric } from 'vee-validate/dist/rules';
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';
import { mapGetters } from 'vuex';


export default {
    name: "ListFiles",

    components: {
        MoonLoader
    },


    data(){
        return {
            type_path: null,
            options: false,
            attached_files: [],
            loading_files: true
        }
    },

    computed: {
        ...mapGetters('files', ['getAttachedFiles']),
        ...mapGetters('timeline_client', ['getConversation']),
    },

    props: {
        the_type_path: String,
        the_attached_files: Array,
        conversation_type: String,
        current_user: Object,
        conv_id: Number,
        timeline: Object
    },

    methods: {
        deleteQuoteFile: function(file){
            axios.post(`/api/files/${file.id}/quote/delete`).then(response => {
                if(response.data.result == 'ok'){
                    this.attached_files = this.attached_files.filter(item => {
                        if(item.id != file.id){
                            return item;
                        }
                    });
                }
            });
            // this.$store.dispatch('files/deleteQuoteFile', file).then(response => {
            //     this.$store.dispatch('initConversation', this.timeline);
            // });
        },


        deleteClientFile: function(file){
            axios.post(`/api/files/${file.id}/client_message/delete`).then(response => {
                if(response.data.result == 'ok'){
                    this.attached_files = this.attached_files.filter(item => {
                        if(item.id != file.id){
                            return item;
                        }
                    });
                }
            });
        },


        download: function(type, file){
            axios({
                url: `/storage/${type}/${file.name}`,
                method: "GET",
                responseType: 'blob'
            }).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', file.name);
                document.body.appendChild(link);
                link.click();
            }).catch(error => {
                if(error.response.status == 404){
                    console.log("fisierul nu mai exista.");
                }
            });
        }

    },

    created(){
        console.log('this.the_attached_files', this.the_attached_files);
        this.type_path = this.the_type_path;
        this.attached_files = this.the_attached_files;

    
        // this.$store.commit('files/set_attached_files', this.the_attached_files);

        setTimeout(() => {
            this.loading_files = false;
        }, 1000);
    }
}
</script>