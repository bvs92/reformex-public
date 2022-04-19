<template>
<div class="form-group" v-if="type == 'images'">

<div v-if="getselectedFilesPreview.length > 0">
    <div class="row">
        <!-- <div class="col-lg-3 col-sm-3" v-for="image in getselectedFilesPreview" :key="image">
            <img class="img-fluid rounded img-thumbnail" :src="image">
        </div> -->
        <div class="col-lg-12">
            <h4>Imagini selectate</h4>
        </div>
        <div class="col-lg-2" v-for="file in getselectedFilesPreview" :key="file[1]">
            <template v-if="file.type.includes('image/jpeg') || file.type.includes('image/png') || file.type.includes('image/webp') || file.type.includes('image/apng') || file.type.includes('image/avif') || file.type.includes('image/gif') || file.type.includes('image/tiff')">
                <img class="img-fluid rounded img-thumbnail" :src="file.src" style="height: 120px;display: block;margin: 0 auto;" />
            </template>
        </div>

    </div>
</div>
</div>

<div class="form-group" v-else>

<div v-if="getAttachmentsPreview.length > 0">
    <div class="row">
        <!-- <div class="col-lg-3 col-sm-3" v-for="image in getAttachmentsPreview" :key="image">
            <img class="img-fluid rounded img-thumbnail" :src="image">
        </div> -->
        <div class="col-lg-12">
            <h4>Fișiere selectate</h4>
        </div>
        <div class="col-lg-2" v-for="file in getAttachmentsPreview" :key="file[1]">
      
            <template v-if="file.type == 'application/pdf'">
                    <i class="fa fa-file-pdf-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'text/csv'">
                    <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'application/msword'">
                    <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                    <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'application/vnd.ms-excel'">
                    <i class="fa fa-file-excel-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                    <i class="fa fa-file-excel-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else-if="file.type == 'text/plain'">
                    <i class="fa fa-file-text-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>

            <template v-else>
                    <i class="fa fa-file-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span>
            </template>
            
        </div>

    </div>
</div>
</div>
</template>


<script>
import {mapGetters} from 'vuex';


export default {
    name: "SelectedFilesPreview",
    data(){
        return {
            // selectedFilesArray: null,
            // selectedFilesPreview: new Array,
            // isSelected: false
        }
    },

    props: {
        // props: ['bus'],
        // resetAll: Boolean
        type: String
    },

    computed: {
        ...mapGetters('files_upload', ['getselectedFilesPreview']),
        ...mapGetters('attachments_upload', ['getAttachmentsPreview']),
    },


    methods: {

    },

    created(){
        // this.bus.$on('reset', this.deleteSelected);
    }
}
</script>

<style scoped>
    .display-none {
        /* display: none; */
    }
</style>