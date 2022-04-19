<template>
    <div class="form-group">

        <template v-if="getIsSelected">
            <p class="text-center">Au fost selectate următoarele fișiere.</p>
            <div v-if="getselectedFilesPreview.length > 0">
                <div class="row">

                    <div class="col-lg-2" v-for="file in getselectedFilesPreview" :key="file[1]">

                        <template v-if="file.type == 'application/pdf'">
                                <i class="fa fa-file-pdf-o" style="display:block;margin:0 auto; text-align: center;color:darkred;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'text/csv'">
                                <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:blue;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'application/msword'">
                                <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:blue;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                                <i class="fa fa-file-word-o" style="display:block;margin:0 auto; text-align: center;color:blue;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.ms-excel'">
                                <i class="fa fa-file-excel-o" style="display:block;margin:0 auto; text-align: center;color:green;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                                <i class="fa fa-file-excel-o" style="display:block;margin:0 auto; text-align: center;color:green;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else-if="file.type == 'text/plain'">
                                <i class="fa fa-file-text-o" style="display:block;margin:0 auto; text-align: center;color:gray;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>

                        <template v-else>
                                <i class="fa fa-file-o" style="display:block;margin:0 auto; text-align: center;color:gray;font-size:40px;"></i> <span class="text-sm text-muted" style="display: block; margin: 0 auto; text-align: center;">{{  file.name }}</span> <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file.name)"><i class="ti-trash"></i></button>
                        </template>
                        
                    </div>
           

                    <!-- <div class="col-lg-2 my-4" v-for="(file, index) in getselectedFilesPreview" :key="index + '-' + file.name">
                        <template v-if="file.type.includes('image/jpeg') || file.type.includes('image/png') || file.type.includes('image/webp') || file.type.includes('image/apng') || file.type.includes('image/avif') || file.type.includes('image/gif') || file.type.includes('image/tiff')">
                            <img class="img-fluid rounded img-thumbnail" :src="file.src" style="height: 120px;display: block;margin: 0 auto;" />
                            <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file)"><i class="ti-trash"></i></button>
                        </template>
                    </div> -->

                </div>

                <div class="row mt-2">
                    <div class="col-lg-6 mt-6">
                        <div v-if="getselectedFilesPreview.length < 5">
                            <div class="custom-file">
                                <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_files[]" multiple="multiple">
                                <label class="custom-file-label"><i class="ti-link"></i> Adaugă fișiere</label>
                                <p class="text-small text-gray">Poți adăuga încă {{ 5 - parseInt(getselectedFilesPreview.length) }} fișiere.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-6">
                        <button class="btn btn-block btn-default" @click="deleteSelected">Renunță la fișiere.</button>
                    </div>
                    
                </div>
            </div>
            
        </template>

        <div class="display-none" v-else>
        <div class="form-label">Adaugă fișiere (opțional)</div>
        <div class="custom-file">
            <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_files[]" multiple="multiple">
            <label class="custom-file-label"><i class="ti-link"></i> Adaugă fișiere</label>
            <p class="text-small text-gray">Adaugă până la 5 fișiere. Fișiere acceptate: PDF, Word, Excel sau text.</p>
        </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';


export default {
    name: "AttachmentsUploadComponent",
    data(){
        return {
            // selectedFilesArray: null,
            // selectedFilesPreview: new Array,
            // isSelected: false
            files: []
        }
    },

    props: {
        // props: ['bus'],
        // resetAll: Boolean
    },

    computed: {
        ...mapGetters('attachments_upload', ['getSelectedFilesArray', 'getselectedFilesPreview', 'getIsSelected'])
    },


    methods: {
        checkFile: function(item){
            if(item.type == 'application/pdf' || 
            item.type == 'text/csv' || 
            item.type == 'application/msword' || 
            item.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || 
            item.type == 'application/vnd.ms-excel' || 
            item.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || 
            item.type == 'text/plain'){
                return true;
            } else {
                return false;
            }
        },

        checkSize: function(item){
            let size = 10000000; // 10 MB
            if(item.size > size){
                Vue.$toast.open({
                    message: `'${item.name}' este mai mare de 10 Mb. Acesta nu a fost încărcat.`,
                    type: 'error',
                    duration: 4000
                });

                return false;
            } else {
                return true;
            }
        },

        filesSelected(e){

            this.$store.commit('attachments_upload/set_files_array_preview', []);



            let selected_files = Object.values(e.target.files);
            console.log(selected_files);
            if(selected_files && selected_files.length > 0){
                selected_files.forEach(item => {
                    if(this.checkFile(item)){
                        if(this.checkSize(item)){
                            this.files.push(item);
                        }
                    }
                });
            }

            // this.$store.commit('files_upload/set_selected_files_array', e.target.files);
            
            if(this.files.length > 5){
                this.files = this.files.slice(0, 5);
                this.$store.commit('attachments_upload/set_selected_files_array', this.files);
            } else {
                this.$store.commit('attachments_upload/set_selected_files_array', this.files);
            }
            
            if(this.files.length > 0){
                this.$store.commit('attachments_upload/set_is_selected', true);
            }

            // itereaza obiectul FileList.
            for(let item of this.getSelectedFilesArray){
                // console.log('item este', item);
                
                    let reader = new FileReader();
                    reader.readAsDataURL(item);
                    reader.onload = e => {
                        let elem = {
                     
                            name: item.name,
                            type: item.type, 
                            src: e.target.result
                        };

                        this.$store.commit('attachments_upload/set_add_files_array_preview', elem);
                        // this.selectedFilesPreview.push(e.target.result);
                    };
              
            }

            this.$emit('files:selected', this.getSelectedFilesArray);

        },

        deleteSelected: async function (){
            // console.log('suntem aici... stergem');
            // this.isSelected = false;
            // this.selectedFilesArray = null;
            // this.selectedFilesPreview = [];

            this.$emit('files:removed');
            await this.$store.commit('attachments_upload/set_reset_files');
            this.files = [];
        },

        resetAll: function(){
            this.$store.commit('attachments_upload/set_reset_files');
            this.files = [];
        },

        deleteItem: function(name){
            this.$store.commit('attachments_upload/remove_item', name);
            this.files = this.files.filter(item => {
                if(item.name != name)
                    return item;
            });

            if(this.files.length > 0){
                this.$store.commit('attachments_upload/set_is_selected', true);
            } else {
                this.$store.commit('attachments_upload/set_is_selected', false);
            }
        }
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