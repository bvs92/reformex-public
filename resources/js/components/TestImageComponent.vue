<template>
<form @submit.prevent="sendForm">
    <div class="form-group">

        <template v-if="getIsSelected">
            <p class="text-center">Au fost selectate urmatoarele fisiere.</p>
            <div v-if="getselectedFilesPreview.length > 0">
                <div class="row">
                    <!-- <div class="col-lg-3 col-sm-3" v-for="image in getselectedFilesPreview" :key="image">
                        <img class="img-fluid rounded img-thumbnail" :src="image">
                    </div> -->

                    <div class="col-lg-2" v-for="file in getselectedFilesPreview" :key="file[1]">
                        <template v-if="file.type.includes('image/jpeg') || file.type.includes('image/png') || file.type.includes('image/webp')">
                            <img class="img-fluid rounded img-thumbnail" :src="file.src" /> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'application/pdf'">
                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'text/csv'">
                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'application/msword'">
                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.ms-excel'">
                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else-if="file.type == 'text/plain'">
                                <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>

                        <template v-else>
                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> <span class="text-sm text-muted">{{  file.name }}</span>
                        </template>
                        
                    </div>

                </div>
            </div>
            <hr>
            <button class="btn btn-sm btn-default" @click="deleteSelected">Renunta la fisiere.</button>
        </template>

        <div class="display-none" v-else>
        <div class="form-label">Atasati fisiere (optional)</div>
        <div class="custom-file">
            <input type="file" @change="filesSelected" ref="filesUploadChild" class="form-control custom-file-input custom-file-input" name="the_files[]" multiple="multiple">
            <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
            <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
        </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">trimite</button>
</form>
</template>


<script>
import { FormDatepickerPlugin } from 'bootstrap-vue';
import {mapGetters} from 'vuex';


export default {
    name: "TestImageComponent",
    data(){
        return {
            // selectedFilesArray: null,
            // selectedFilesPreview: new Array,
            // isSelected: false
            validation_errors: []
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
        sendForm: function(){
            console.log(this.getSelectedFilesArray);

            let formData = new FormData();
            if(this.getSelectedFilesArray){
                for(let file of this.getSelectedFilesArray){
                    formData.append('the_files[]', file);
                }
            }

            const config = { headers: { 'Content-Type': 'multipart/form-data', 'Access-Control-Allow-Origin' : '*' } };
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/public/resize/image`, formData, config).then(response => {
                console.log('rezultat', response.data);
                

                if(response.data.result == true){
                    this.$swal(
                        'Cererea trimisa!',
                        'Cererea dumneavoastra a fost inregistrata in platforma Reformex. Profesionistii nostri vor putea sa vizualizeze informatiile si sa va contacteze. Va multumim pentru incredere!',
                        'success'
                    );


                    

                    

                    
                } else {
                    // open toatr 
                    Vue.$toast.open({
                        message: 'Oups! Am intampinat erori. Incercati in cateva minute',
                        type: 'error',
                        duration: 6000
                    });

                    if (response.data.validation_errors !== null){
                        console.log('erori de validare', response.data.validation_errors);
                        this.validation_errors = response.data.validation_errors;
                    }

                }

            });

        },
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
            console.log('suntem aici...this.getSelectedFilesArray', this.getSelectedFilesArray);

            // reset?
            // this.selectedFilesArray = null;
            // this.$store.commit('files_upload/set_selected_files_array', null);
        },

        deleteSelected: async function (){
            console.log('suntem aici... stergem');
            // this.isSelected = false;
            // this.selectedFilesArray = null;
            // this.selectedFilesPreview = [];

            this.$emit('files:removed');
            await this.$store.commit('files_upload/set_reset_files');
        },

        resetAll: function(){
            this.$store.commit('files_upload/set_reset_files');
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