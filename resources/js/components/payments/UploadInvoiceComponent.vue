<template>
    <div class="form-group">

        <template v-if="isSelected">
            <div v-if="selectedFilePreview">
                <div class="row">
                    <!-- <div class="col-lg-3 col-sm-3" v-for="image in getselectedFilesPreview" :key="image">
                        <img class="img-fluid rounded img-thumbnail" :src="image">
                    </div> -->

                    <div v-if="selectedFilePreview.type == 'application/pdf'" class="col-lg-4 offset-4 my-2">
                            <i style="color:darkred;font-size:40px;" class="fa fa-file-pdf-o d-flex justify-content-center"></i> <span class="text-sm text-muted d-block text-center">{{  selectedFilePreview.name }}</span>
                    </div>


                    <div v-else-if="selectedFilePreview.type == 'application/msword'" class="col-lg-4 offset-4 my-2">
                            <i class="fa fa-file-word-o d-flex justify-content-center" style="color:blue;font-size:40px;"></i> <span class="text-sm text-muted d-block text-center">{{  selectedFilePreview.name }}</span>
                    </div>

                    <div v-else-if="selectedFilePreview.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'" class="col-lg-4 offset-4 my-2">
                            <i class="fa fa-file-word-o d-flex justify-content-center" style="color:blue;font-size:40px;"></i> <span class="text-sm text-muted d-block text-center">{{  selectedFilePreview.name }}</span>
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-lg-between">
                <b-button variant="info" disabled class="btn btn-info" v-if="once">
                    <b-spinner small type="grow"></b-spinner>
                    Încarcă...
                </b-button>

                <button class="btn btn-info" @click="uploadSelected" v-else>Încarcă fișier.</button>
                <button class="btn btn-default" @click="deleteSelected">Renunță la fișier.</button>
            </div>

            <!-- <div class="d-flex justify-content-lg-center" v-else>
                <button class="btn btn-info" @click="deleteFile">Elimină fișier.</button>
            </div> -->
        </template>

        <div v-else>
            <div class="form-label">Atașează factură</div>
            <div class="custom-file">
                <input type="file" 
                @change="filesSelected" ref="filesUploadChild" 
                class="form-control custom-file-input custom-file-input" 
                name="invoice"
                accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                >
                <label class="custom-file-label"><i class="ti-link"></i> Selectează fișier</label>
                <p class="text-small text-gray">Selectează factura. Fișiere acceptate: PDF, Document Word.</p>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';


export default {
    name: "UploadInvoiceComponent",
    data(){
        return {
            once: false,
            selectedFile: null,
            selectedFilePreview: null,
            isSelected: false,
            validation_errors: [],
            uploadSuccess: false

        }
    },

    props: ["payment_uuid"],

    computed: {
        ...mapGetters('files_upload', ['getSelectedFilesArray', 'getselectedFilesPreview', 'getIsSelected']),
    },


    methods: {
        filesSelected(e){

            this.validation_errors = [];

            if(e.target.files.length > 0){
              this.selectedFile = e.target.files[0];
              this.isSelected = true;

              let reader = new FileReader();
              let item = this.selectedFile;
              reader.readAsDataURL(item);
              reader.onload = e => {
                  let elem = {
                      name: item.name,
                      type: item.type, 
                      src: e.target.result
                  };
                //   console.log(elem);
                  this.selectedFilePreview = elem;
              };
            }


            // this.$store.commit('files_upload/set_selected_files_array', e.target.files);
            // this.$store.commit('files_upload/set_is_selected', true);

            // // itereaza obiectul FileList.
            // for(let item of this.getSelectedFilesArray){
            //     let reader = new FileReader();
            //     reader.readAsDataURL(item);
            //     reader.onload = e => {

            //         let elem = {
            //             name: item.name,
            //             type: item.type, 
            //             src: e.target.result
            //         };
        
            //         this.$store.commit('files_upload/set_add_files_array_preview', elem);
            //     };
            // }

            // this.$emit('files:selected', this.getSelectedFilesArray);

        },

        deleteSelected: async function (){
            // await this.$store.commit('files_upload/set_reset_files');
            this.selectedFilePreview = null;
            this.selectedFile = null;
            this.isSelected = false;
            this.validation_errors = [];
        },

        uploadSelected: function(){
            // console.log(this.payment_uuid);
            this.once = true;
            let formData = new FormData();
            formData.append('invoice', this.selectedFile);
            console.log('>> formData >> ', formData);


            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
            axios.post(`/api/invoices/upload/payment/${this.payment_uuid}`, formData, config).then(response => {
                if(response.data.success){
      

                  Vue.$toast.open({
                        message: 'Factură încărcată cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                //  this.$store.dispatch('avatar/initAvatar');
                  this.deleteSelected();
                  this.$emit('upload:success', true);
                    
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }).finally(() => {
                this.once = false;
            });

        },



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