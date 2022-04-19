<template>
<div class="row p-4" :class="content_align">
    <template v-if="attached_files">
    <div class="col-lg-3" v-for="file in attached_files" :key="file.id">
        <template v-if="file.mime_type == 'image/jpeg' || file.mime_type == 'image/png' || file.mime_type == 'image/webp'">
            <a :href="'/storage/tickets/' + file.name" data-lightbox="photos">
                <img class="img-fluid rounded img-thumbnail" :src="'/storage/tickets/' + file.name" />
            </a>
        </template>

        <template v-else-if="file.mime_type == 'application/pdf'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'text/csv'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'application/msword'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'application/vnd.ms-excel'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else-if="file.mime_type == 'text/plain'">
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>

        <template v-else>
            <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
                <span class="quick"><i class="fa fa-file-o" style="color:gray;font-size:40px;"></i></span> <span class="quick">{{  limit_string(file.name) }}</span>
            </a>
        </template>
        
    </div>
    </template>
    <!-- <template v-else>
        <bounce-loader size="20px" color="blue" class="m-1 d-flex justify-content-center"></bounce-loader>
    </template> -->
</div>
</template>

<script>
import BounceLoader from 'vue-spinner/src/BounceLoader.vue';

export default {
    name: "FileComponent",

    data(){
        return {
            isLoaded: false
        }
    },

    computed: {
        get_is_loaded: function(){
            if(this.attached_files){
                if(this.attached_files.length < 2){
                    setTimeout(function(){ 
                        this.isLoaded = true;
                    }, 1000);
                } else if(this.attached_files.length > 2 && this.attached_files.length < 5) {
                   setTimeout(function(){ 
                        this.isLoaded = true;
                    }, 2000); 
                } else if(this.attached_files.length > 5){
                    setTimeout(function(){ 
                        this.isLoaded = true;
                    }, 4000);
                }
            }

            return this.isLoaded;
        }
    },

    components: {
        BounceLoader
    },

    props: {
        attached_files: Array,
        content_align: String
    },

    methods: {
        limit_string: function(_incoming){
            if ( _incoming.length > 10 ) {
                return _incoming.substring(0,10) + '...';
            } else {
                return _incoming;
            }
        }
    }
}
</script>

<style scoped>
.quick {
    display: block;
    width: 100%;
    text-align: center;
}
</style>