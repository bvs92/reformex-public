<template>
  <button class="btn btn-sm btn-danger btn-loading" v-if="btnLoading">Elimina proiect</button>
  <button @click.prevent="deleteProject" class="btn btn-sm btn-danger" v-else>Elimina proiect</button>
</template>

<script>
export default {
    name: "DeleteSingleProjectComponent",

    props: {
      project: Object
    },

    data(){
      return {
        btnLoading: false
      }
    },

    methods: {
      deleteProject: function(){
        

        this.$swal({
                title: 'Eliminare proiect!',
                text: "Sunteti sigur ca doriti eliminarea acestui proiect?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {
                  this.btnLoading = true;

                  axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
                  axios.post(`/api/admin/work-projects/delete/${this.project.uuid}`).then(response => {
                    console.log('rezultat', response.data);

                      if(response.data.result){
                          // if success => reseteaza cerere.
                          Vue.$toast.open({
                              message: 'Felicitari! Proiectul a fost eliminat!',
                              type: 'success',
                              duration: 6000
                          });
                          
                          window.location = '/categorii-proiecte/';

                      } else {
                          // open toatr 
                          Vue.$toast.open({
                              message: 'Oups! Am intampinat erori.',
                              type: 'error',
                              duration: 6000
                          });
                      }

                  }).catch((error) => {
                      Vue.$toast.open({
                              message: 'Oups! Am intampinat erori.',
                              type: 'error',
                              duration: 6000
                          });
                  })
                  .finally(() => {
                      this.btnLoading = false;
                      
                  });
                }
            });

        

            


      }
    }
}
</script>

<style>

</style>