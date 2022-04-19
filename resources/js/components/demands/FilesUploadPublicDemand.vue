<template>
    <div class="form-group">

        <template v-if="getIsSelected">
            <p class="text-center">Au fost selectate următoarele imagini.</p>
            <div v-if="getselectedFilesPreview.length > 0">
                <div class="row">
                    <!-- <div class="col-lg-3 col-sm-3" v-for="image in getselectedFilesPreview" :key="image">
                        <img class="img-fluid rounded img-thumbnail" :src="image">
                    </div> -->

                    <div class="col-lg-2 my-4" v-for="(file, index) in getselectedFilesPreview" :key="index + '-' + file.name">
                        <template v-if="file.type.includes('image/jpeg') || file.type.includes('image/png') || file.type.includes('image/webp') || file.type.includes('image/apng') || file.type.includes('image/avif') || file.type.includes('image/tiff')">
                            <img class="img-fluid rounded img-thumbnail" :src="file.src" style="height: 120px;display: block;margin: 0 auto;" />
                            <button class="btn btn-sm btn-danger" style="margin: 0 auto;display:block;" @click.prevent="deleteItem(file)"><i class="ti-trash"></i></button>
                        </template>
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-lg-6 mt-6">
                        <div v-if="getselectedFilesPreview.length < 5">
                            <div class="custom-file">
                                <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_files[]" multiple="multiple">
                                <label class="custom-file-label"><i class="ti-link"></i> Selectează imagini</label>
                                <p class="text-small text-gray">Poți adăuga încă {{ 5 - parseInt(getselectedFilesPreview.length) }} imagini.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-6">
                        <button class="btn btn-block btn-default" @click="deleteSelected">Renunță la imagini.</button>
                    </div>
                    
                </div>
            </div>
            
        </template>

        <div class="display-none" v-else>
        <div class="form-label">Adaugă imagini (opțional)</div>
        <div class="custom-file">
            <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_files[]" multiple="multiple">
            <label class="custom-file-label"><i class="ti-link"></i> Selectează imagini</label>
            <p class="text-small text-gray">Selectează până la 5 imagini.</p>
        </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';


export default {
    name: "FilesUploadPublicDemand",
    data(){
        return {
            // selectedFilesArray: null,
            // selectedFilesPreview: new Array,
            // isSelected: false
            final_files: []
        }
    },

    props: {
        // props: ['bus'],
        // resetAll: Boolean
    },

    computed: {
        ...mapGetters('files_upload', ['getSelectedFilesArray', 'getselectedFilesPreview', 'getIsSelected'])
    },


    methods: {
        checkImage: function(item){
            if(item.type == 'image/jpeg' || item.type == 'image/png' || item.type == 'image/webp' || item.type == 'image/avif' || item.type == 'image/apng' || item.type == 'image/tiff'){
                return true;
            } else {
                return false;
            }
        },

        filesSelected(e){

            // this.$store.commit('files_upload/set_selected_files_array', []);
            this.$store.commit('files_upload/set_files_array_preview', []);

            let selected_files = Object.values(e.target.files);
            if(selected_files && selected_files.length > 0){
                selected_files.forEach(item => {
                    if(this.checkImage(item)){
                       
                        this.final_files.push(item);
                    
                    }
                });
            }

            // this.$store.commit('files_upload/set_selected_files_array', e.target.files);
            
            if(this.final_files.length > 5){
                this.final_files = this.final_files.slice(0, 5);
                this.$store.commit('files_upload/set_selected_files_array', this.final_files);
            } else {
                this.$store.commit('files_upload/set_selected_files_array', this.final_files);
            }
            
            if(this.final_files.length > 0){
                this.$store.commit('files_upload/set_is_selected', true);
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

                        this.$store.commit('files_upload/set_add_files_array_preview', elem);
                        // this.selectedFilesPreview.push(e.target.result);
                    };
              
            }

            this.$emit('files:selected', this.getSelectedFilesArray);

        },

        deleteSelected: async function (){
            this.$emit('files:removed');
            await this.$store.commit('files_upload/set_reset_files');
            this.final_files = [];
        },

        resetAll: function(){
            this.$store.commit('files_upload/set_reset_files');
            this.final_files = [];
        },

        deleteItem: function(file){
            this.$store.commit('files_upload/remove_item', file.name);
            this.final_files = this.final_files.filter(item => {
                if(item.name != file.name)
                    return item;
            });

            if(this.final_files.length > 0){
                this.$store.commit('files_upload/set_is_selected', true);
            } else {
                this.$store.commit('files_upload/set_is_selected', false);
            }

            

            // console.log('suntem aici...this.getselectedFilesPreview', this.getselectedFilesPreview);
            // console.log('suntem aici...this.getSelectedFilesArray', this.getSelectedFilesArray);
            // console.log('final_files list', this.final_files);
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