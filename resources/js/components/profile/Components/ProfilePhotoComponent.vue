<template>
    <div class="form-group">

        <template v-if="getIsSelected">
            <p class="text-center">Poză selectată.</p>
            <div v-if="getselectedFilesPreview.length > 0">
                <div class="row">
                    <!-- <div class="col-lg-3 col-sm-3" v-for="image in getselectedFilesPreview" :key="image">
                        <img class="img-fluid rounded img-thumbnail" :src="image">
                    </div> -->

                    <div class="col-lg-2" v-for="file in getselectedFilesPreview" :key="file[1]">
                        <template v-if="file.type.includes('image/jpeg') || file.type.includes('image/png') || file.type.includes('image/webp')">
                            <img class="img-fluid rounded img-thumbnail" :src="file.src" /> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>
                    </div>

                </div>
            </div>
            <hr>
            <button class="btn btn-sm btn-default" @click="deleteSelected">Renunță la poză.</button>
        </template>

        <div class="display-none">

        <div class="custom-file">
            <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_file">
            <label class="custom-file-label"><i class="ti-link"></i> Selecteaza</label>
            <p class="text-small text-gray">Slectează o poză de profil.</p>
        </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';


export default {
    name: "ProfilePhotoComponent",
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
    },

    computed: {
        // ...mapGetters('files_upload', ['getSelectedFilesArray', 'getselectedFilesPreview', 'getIsSelected'])
    },


    methods: {
        filesSelected(e){

            console.log('suntem aici... e.target.files');
            // console.log(e.target.files);

            // this.selectedFilesArray = e.target.files;
            // this.selectedFilesArray = e.target.files;
            // this.isSelected = true;

            
            this.$store.commit('files_upload/set_selected_files_array', e.target.files);
            this.$store.commit('files_upload/set_is_selected', true);

            // itereaza obiectul FileList.
            for(let item of this.getSelectedFilesArray){
                let reader = new FileReader();
                reader.readAsDataURL(item);
                reader.onload = e => {
                    // let result = e.target.result.split(',');
                    // console.log(result[0].includes('image/jpeg'));
                    let elem = {
                        name: item.name,
                        type: item.type, 
                        src: e.target.result
                    };
                    // console.log(elem);
                    // this.selectedFilesPreview.push(elem);
                    // this.selectedFilesPreview.push(elem);
                    this.$store.commit('files_upload/set_add_files_array_preview', elem);
                    // this.selectedFilesPreview.push(e.target.result);
                };
            }

            this.$emit('files:selected', this.getSelectedFilesArray);
            // console.log('suntem aici...this.getSelectedFilesArray', this.getSelectedFilesArray);

            // reset?
            // this.selectedFilesArray = null;
            // this.$store.commit('files_upload/set_selected_files_array', null);
        },

        deleteSelected: async function (){
            // console.log('suntem aici... stergem');
            // this.isSelected = false;
            // this.selectedFilesArray = null;
            // this.selectedFilesPreview = [];
            // this.$emit('files:removed');

            await this.$store.commit('files_upload/set_reset_files');
        }
    },

    created(){
        // this.bus.$on('reset', this.deleteSelected);
    }
}
</script>

<style scoped>
    .display-none {
        display: none;
    }
</style>