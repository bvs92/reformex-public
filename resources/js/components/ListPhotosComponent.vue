<template>
    <div class="row" v-if="the_files" @mouseover="options = true" @mouseleave="options = false">
        <div class="col-lg-2 col-md-3 col-sm-6 p-2" v-for="file in the_files" :key="file.id" >
           
           <div v-if="file">
            <template v-if="file.mime_type == 'image/jpeg' || file.mime_type == 'image/png' || file.mime_type == 'image/webp'">
                <template v-if="the_type_path">
                    <a :href="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/'+ the_type_path +'/' + file.name" data-lightbox="photos">
                    <img class="img-fluid mt-4" :src="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/'+ the_type_path +'/' + file.name" />
                </a>
                </template>
            </template>

            <div class="d-flex justify-content-around" v-if="options">
                <!-- <a :href="file.name" @click.prevent="download(the_type_path, file)" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a> -->
                <button @click.prevent="delete_file('work-projects-photos', file)" class="btn btn-sm btn-danger mx-2" v-if="!isLoadingDelete"><i class="ti-trash"></i></button>
                <button class="btn btn-sm btn-danger mx-2" disabled="disabled" v-else><i class="ti-trash"></i></button>
            </div>

           </div>

            
            
        </div>

      </div>

      

</template>

<script>
export default {
    name: "ListPhotosComponent",

    data(){
      return {
        options: false,
        files: null,
        isLoading: false,
        isLoadingDelete: false,
      }
    },
    
    props: {
        the_type_path: String,
        the_files: Array,
    },

    methods: {
     
        // download: function(type, file){
        //     axios({
        //         // url: `/storage/${type}/${file.name}`,
        //         url: `https://ams3.digitaloceanspaces.com/reformex.ro/uploads/${type}/${file.name}`,
        //         method: "GET",
        //         responseType: 'blob'
        //     }).then(response => {
        //         const url = window.URL.createObjectURL(new Blob([response.data]));
        //         const link = document.createElement('a');
        //         link.href = url;
        //         link.setAttribute('download', file.name);
        //         document.body.appendChild(link);
        //         link.click();
        //     }).catch(error => {
        //         if(error.response.status == 404){
        //             console.log("fisierul nu mai exista.");
        //         }
        //     });
        // },

        delete_file: function(type, file){
            this.isLoadingDelete = true;

            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
            axios.post(`/api/${type}/delete/${file.id}`).then(response => {
            // console.log('rezultat', response.data);

                if(response.data.result){
                    // if success => reseteaza cerere.
                    Vue.$toast.open({
                        message: 'Fișier eliminat!',
                        type: 'success',
                        duration: 6000
                    });
                    
                    // this.files = this.files.filter(item => {
                    //     if(item.id !== file.id){
                    //         return item;
                    //     }
                    // });

                    this.$emit('photo:deleted', file.id);

                } else {
                    // open toatr 
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
            })
            .finally(() => {
                this.isLoadingDelete = false;
                
            });

        }
    },

    created(){
      this.files = this.the_files;
    }
}
</script>

<style>

</style>