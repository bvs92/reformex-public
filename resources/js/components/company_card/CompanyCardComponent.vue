<template>
  <div class="card">
    <div class="card-header">
        <div class="card-title">Fotografie card</div>
    </div>
    <div class="text-center" v-if="getLoading">
        <b-spinner label="Spinning"></b-spinner>
    </div>
    <div class="card-body" v-else>
        <div>
            <div class="row">
                <div class="col-lg-6">
                    
                    <div class="text-center">
                    <div class="userprofile">
                        <div class="userpic-normal" style="width:100%;height:100%;"> 
                        
                            <template v-if="getCompanyCard != null && statusImage">
                                <img :src="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/cards/' + getCompanyCard.image" alt="getImage" class="img-thumbnail">
                            </template>
                        
                            <!-- <img src="/images/default-card.jpg" v-else>  -->
                            <img :src="base_url + '/images/default-card.jpg'" v-else alt="default"> 
                            <!-- <img :src="require('http://127.0.0.1:8000/images/default-card.jpg')" v-else>  -->
                        
                        </div>
                        <div class="text-center my-2" v-if="getCompanyCard">
                            <a class="btn btn-danger btn-sm mt-1 mb-1 text-light btn-loading" v-if="once"><i class="fe fe-trash mr-1"></i> Se elimină</a>
                            <a class="btn btn-danger btn-sm mt-1 mb-1 text-light" @click="deleteImage()" v-else><i class="fe trash mr-1"></i> Elimină</a>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-success" @click.prevent="openModal">Alege fotografie card</a>
                    </div>
                </div>
            </div><!-- end profile photo -->
            
            <p class="text-center mt-5" v-if="!getCompanyCard">Nicio fotografie adăugată. Îți recomandăm să adaugi o fotografie pentru a personaliza anunțul tău în rezultatele de căutare.</p>
        </div>
    </div>


<b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Setează o fotografie pentru card">
     <template v-if="isSelected">
          <p class="text-center">Poză selectată</p>
          <div v-if="selectedFilePreview">
              <div class="row">
                  <div class="col-lg-12">
                      <template v-if="selectedFilePreview.type.includes('image/jpeg') || selectedFilePreview.type.includes('image/png') || selectedFilePreview.type.includes('image/webp')">
                          <img class="img-fluid rounded img-thumbnail" :src="selectedFilePreview.src" /> 
                          <!-- <span class="text-sm text-muted">{{  selectedFilePreview.name }}</span> -->
                      </template>
                      <template v-else>
                        <p class="small text-center">Sunt acceptate doar imagini.</p>
                      </template>
                  </div>

                  <div class="col-lg-12">
                    <hr>
                    <div class="d-flex justify-content-center">
                      <button class="btn btn-sm btn-default" @click="deleteSelected">Elimină fotografie.</button>
                    </div>
                  </div>
              </div>
          </div>
      </template>
      
        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
            <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                <div class="col-lg-12 my-2" v-if="!isSelected">
                   
                        <input type="file" 
                        @change="fileSelection" 
                        accept="image/*"
                        ref="profilePhotoUpload" class="form-control custom-file-input custom-file-input" 
                        name="the_file">
                        <label class="custom-file-label"><i class="ti-link"></i> Selectează</label>
                        <p class="text-small text-gray">Selectează o fotografie.</p>
                        
             
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['image']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['image']" :key="'error-' + index">{{ error }}</span>
                        </template>
                    </template>
                </div>


                <div class="col-lg-12 my-2" v-else>
                    <button class="btn btn-success btn-block" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                    <b-button variant="info" disabled v-else class="btn-block">
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Se salvează...</span>
                    </b-button>
                    <!-- <button class="btn btn-default" @click.prevent="cancel" :disabled="once">Renunta</button> -->
                </div>
            </form>
        </ValidationObserver>
    </b-modal>

</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, image } from 'vee-validate/dist/rules';

import {mapGetters} from 'vuex';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('image', {
  ...image,
  message: 'Sunt acceptate doar imagini.'
});



export default {
    name: "CompanyCardComponent",

    computed: {
        ...mapGetters('company_card', ['getCompanyCard', 'getLoading']),

        getFinalImage: function(){

            return `${this.base_url}/storage/cards/${this.getCompanyCard.image}`;

            // let self = this;
            // this.getImage(this.getCompanyCard.image).then(result => {
            //      if(result == true){
            //         self.imageOk = true;
            //     } else {
            //         self.imageOk = false;
            //     }
            // })

            // if(imageOk == true){
            //     return `${this.base_url}/storage/cards/${this.getCompanyCard.image}`;
            // } else {
            //     return `${this.base_url}/images/default-card.jpg`;
            // }

        }
    },

    props: ["base_url"],

    data(){
        return {
            modalShow: false,
            isLoading: false,
            imageOk: false,
            statusImage: false,

            once: false,
            validation_errors: null,
            hasAvantar: false,
            avatarPath: null,
            loading: false,

            selectedFilePreview: null,
            selectedFile: null,
            isSelected: false
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;
            this.validation_errors = [];

            this.selectedFilePreview = null;
            this.selectedFile = null;
            this.isSelected = false;        
        },

        // CHECK IF IMAGE EXISTS
        checkIfImageExists(url) {
            url = this.base_url + '/storage/cards/' + url;
            const img = new Image();
            img.src = url;
            
            if (img.complete) {
                // callback(true);
                return url;
            } else {
                img.onload = () => {
                    // callback(true);
                };
                
                img.onerror = () => {
                    // callback(false);
                };

                return this.base_url + '/images/default-card.jpg'; 
            }
        },

        checkImage: async function(){
            let path_url = 'cards';
            let name_url = this.getCompanyCard.image;

            // axios.defaults.httpsAgent = new https.Agent({
            //     rejectUnauthorized: false,
            // });
            
            let config = {
                headers: {'Access-Control-Allow-Origin': "*"},
                mode: 'no-cors',
            };

            await axios.get(this.base_url + '/api/files/check/' + path_url + '/' + name_url, config).then(response => {
                console.log(response.data)
                this.statusImage = Boolean(response.data);
                console.log('status check image');
            });
        },

        getImage: function(image_name){
            let result = fetch(`${this.base_url}/storage/cards/${image_name}`, { method: 'HEAD' })
            .then(res => {
                if (res.ok) {
                    console.log('exista');
                    return true;
                } else {
                    console.log('nu exista');
                   return false;
                }
            }).catch(err => {
                return false;
            });
            return result;
        },

        onSubmit: async function(){
            this.once = true;
            let formData = new FormData();
            formData.append('image', this.selectedFile);


            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
            await axios.post(`/api/company_card/update/image`, formData, config).then(async response => {
                if(response.data.success){

                    await this.$store.dispatch('company_card/initCompanyCard');
                    await this.deleteSelected();
                    this.statusImage = true;
                    this.modalShow = false;

                    Vue.$toast.open({
                        message: 'Fotografie card încarcată cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                    
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
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


        fileSelection: function(e){
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
                  // console.log(elem);
                  this.selectedFilePreview = elem;
              };
            }
            
        },

        deleteSelected: function(){
          // console.log('eroare? - delete?');
          this.selectedFilePreview = null;
          this.selectedFile = null;
          this.isSelected = false;
          this.validation_errors = [];
        },

        
        deleteImage: async function(){
          this.once = true;
          await axios.post(`/api/company_card/delete/image`).then(async response => {
            if(response.data.success){
              await this.$store.commit('company_card/set_company_card', null);
            } else {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            }
          }).finally(() => {
            this.once = false;
          });
        }
    },


    async created(){
        await this.$store.dispatch('company_card/initCompanyCard');

        if(this.getCompanyCard){
            await this.checkImage();
        }
    }

}
</script>

<style>

</style>