<template>
<div class="">
<div class="card-header">
    <h3 class="card-title">Poză de profil</h3>
</div>
<div class="text-center" v-if="loading">
  <b-spinner label="Spinning"></b-spinner>
</div>
<div class="card-body" v-else>
  <!-- start profile photo -->
  <div class="row">
      <div class="col-lg-6">
        
        <div class="text-center">
          <div class="userprofile">
            <div class="userpic-normal" style="width:100%;height:100%;"> 
              
                <img :src="getAvatarPath" alt="" class="img-thumbnail" v-if="getHasAvatar">
             
                <img src="/images/avatars/default-photo.png" v-else> 
              
            </div>
            <div class="text-center my-2" v-if="getHasAvatar">
                <a class="btn btn-danger btn-sm mt-1 mb-1 text-light btn-loading" v-if="once"><i class="fe fe-trash mr-1"></i> Se elimină</a>
                <a class="btn btn-danger btn-sm mt-1 mb-1 text-light" @click="deleteAvatar()" v-else><i class="fe trash mr-1"></i> Elimină poza</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="d-flex justify-content-center">
          <a href="#" class="btn btn-success" @click.prevent="openModal">Schimbă poza de profil</a>
        </div>
      </div>
    </div><!-- end profile photo -->

   <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Modifică poză de profil">
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
                      <button class="btn btn-sm btn-default" @click="deleteSelected">Elimină poză.</button>
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
                        <p class="text-small text-gray">Selectează o poză de profil.</p>
                        
             
                    <template v-if="validation_errors">
                        <template v-if="validation_errors['photo']">
                            <span class="small text-danger" v-for="(error, index) in validation_errors['photo']" :key="'error-' + index">{{ error }}</span>
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
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, image } from 'vee-validate/dist/rules';
import { mapGetters } from 'vuex';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('image', {
  ...image,
  message: 'Sunt acceptate doar imagini.'
});



export default {
    name: "EditProfilePhotoComponent",

    data(){
        return {
            modalShow: false,
           
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

    props: {
     
    },

    computed: {
      ...mapGetters('avatar', ['getHasAvatar', 'getAvatarPath'])
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

        onSubmit: function(){
            this.once = true;
            let formData = new FormData();
            formData.append('photo', this.selectedFile);


            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
            axios.post(`/api/users/avatar/update/photo`, formData, config).then(response => {
                if(response.data.success){
                  
                  // this.avatarPath = '/' + response.data.avatar;
                  // this.hasAvantar = true;
                  this.modalShow = false;

                  this.$swal({
                      title: 'Succes.',
                      text: "Informații modificate cu succes.",
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonColor: '#3085d6',
                      confirmButtonText: 'Ok.',
                  });

                 this.$store.dispatch('avatar/initAvatar');
                  this.deleteSelected();
                    
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });

        },

        cancel: function(){
            // this.$refs.observer.reset();
            // this.openModal = false;
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

        

        // initAvatar: async function(){
        //   this.loading = true;
        //   await axios.get(`/api/users/avatar/get`).then(response => {
        //     if(response.data.success){
        //       this.hasAvantar = true;
        //       this.avatarPath = '/' + response.data.avatar;
        //     }
        //   }).finally(() => {
        //     this.loading = false;
        //   });
        // },

        deleteAvatar: function(){
          this.once = true;
          axios.get(`/api/users/avatar/delete`).then(response => {
            // console.log('profile photo avatar is', response.data);
            if(response.data.success){
              // this.hasAvantar = false;
              // this.avatarPath = null;
              this.$store.dispatch('avatar/initAvatar');
            }
          }).finally(() => {
            this.once = false;
          });
        }
    },

    created(){
      // get profile photo

      // this.initAvatar();
      // this.$store.dispatch('avatar/initAvatar');
    }
}
</script>

<style>

</style>