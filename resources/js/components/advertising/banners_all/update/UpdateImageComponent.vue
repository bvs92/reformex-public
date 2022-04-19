<template>
    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
    <form class="row" @submit.prevent="handleSubmit(saveImage)">

        <div class="col-lg-12" v-if="image && show_existing_image">
            <div>
                <img class=" fit-image" :src="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/banners/' + image" style="display: block; margin: 0 auto;" /> 
            </div>
           
            <div class="d-flex justify-content-center mt-2">
                <button class="btn btn-sm btn-info" @click="show_existing_image = !show_existing_image">Schimbă imaginea</button>
            </div>
         
        </div>
        <div class="col-lg-12" v-else>
            <div v-if="selectedFilePreview">
                <div class="row">
                    <div class="col-lg-12">
                        <template v-if="selectedFilePreview.type.includes('image/jpeg') || selectedFilePreview.type.includes('image/png') || selectedFilePreview.type.includes('image/webp')">
                            <img class=" fit-image" :src="selectedFilePreview.src" style="display: block; margin: 0 auto;" /> 
                            <!-- <span class="text-sm text-muted">{{  selectedFilePreview.name }}</span> -->
                        </template>
                        <template v-else>
                        <p class="small text-center">Sunt acceptate doar imagini.</p>
                        </template>
                    </div>

                    <div class="col-lg-12 mt-5">
                        <div class="d-flex justify-content-around">
                            <button type="submit" class="btn btn-success">Salvează imagine</button>
                            <button class="btn btn-danger" @click="deleteSelected">Elimină imagine</button>
                        </div>
                    </div>
                </div><!-- end row -->

            </div> <!-- end selected file preview -->
            
            <div v-else>
                <img src="/images/default-banner.jpg" width="440" height="480" style="display: block; margin: 0 auto;">
                
            </div>

            <div class="col-lg-12 my-2" v-if="!isSelected"> 
    
                <input type="file" 
                    @change="fileSelection" 
                    required
                    accept="image/*"
                    ref="profilePhotoUpload" class="form-control custom-file-input custom-file-input" 
                    name="the_file">
                <label class="custom-file-label"><i class="ti-link"></i> Selectează imagine</label>
                <p class="text-center">Dimensiune recomandată: 440 lățime X 480 înălțime.</p> 

                <div class="d-flex justify-content-center mt-2" v-if="!show_existing_image">
                    <button class="btn btn-sm btn-default" @click="show_existing_image = !show_existing_image">Renunță, păstrează imaginea inițială</button>
                </div>


                <template v-if="validation_errors">
                    <template v-if="validation_errors['photo']">
                        <span class="small text-danger" v-for="(error, index) in validation_errors['photo']" :key="'error-' + index">{{ error }}</span>
                    </template>
                </template>
            </div>
            
        </div> <!-- end image col -->
    </form>
</ValidationObserver>
</template>

<script>
import axios from 'axios';
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, min_value, integer, max, email } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minimă acceptată este {length} caractere.'
});

extend('email', {
  ...email,
  message: 'Te rugăm să introduci o adresă de e-mail validă.'
});

extend('max', {
  ...max,
  message: 'Sunt acceptate maxim {length} caractere.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minimă acceptată este 1.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});

export default {
    name: "UpdateImageComponent",

    components: {
        ValidationObserver,
        ValidationProvider
    },

    data(){
        return {
            validation_errors: null,
            once: false,

            image: null,
            show_existing_image: null,

            selectedFilePreview: null,
            selectedFile: null,
            isSelected: false,
            
        }
    },

    props: ["banner"],

    methods: {
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
                //   console.log('selectedFile', this.selectedFile);
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

        saveImage: async function(){
            this.once = true;
            // console.log('fire!');

            let formData = new FormData();

            formData.append('photo', this.selectedFile);
            const config = { headers: { 'Content-Type': 'multipart/form-data' } };
    

            await axios.post('/api/banners/personal/update_announce_image/' + this.banner.id, formData, config).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    // await this.$store.commit('categories/insert_category', response.data.category);

                    await this.$refs.observer.reset();
                    this.deleteSelected()
                    this.image = response.data.image;
                    this.show_existing_image = true;


                    Vue.$toast.open({
                        message: 'Imagine modificată cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
          
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori de validare. Verifică imaginea.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
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
        this.image = this.banner.image;

        if(this.image){
            this.show_existing_image = true;
        }
        // console.log(this.banner);
    }
}
</script>

<style scoped>
.fit-image {
    width: 100%;max-width: 440px;height: 480px!important;max-height: 480px!important;
    object-fit: cover;
}
</style>