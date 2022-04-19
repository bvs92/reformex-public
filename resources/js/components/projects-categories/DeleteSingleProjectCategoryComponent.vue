<template>
  <button class="btn btn-sm btn-danger btn-loading" v-if="btnLoading">Elimină categoria</button>
  <button @click.prevent="deleteCategory" class="btn btn-sm btn-danger" v-else><i class="fa fa-trash"></i> Elimină categoria</button>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "DeleteSingleProjectCategoryComponent",

    props: {
      the_category: Object
    },

    data(){
      return {
        btnLoading: false
      }
    },

    computed: {
        ...mapGetters('projects', ['getProjects', 'getTotalProjects']),
    },

    methods: {
      deleteCategory: function(){
        

        this.$swal({
                title: 'Eliminare categorie!',
                text: "Ești sigur că vrei să elimini aceasstă categorie?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    this.btnLoading = true;

                    if(this.getTotalProjects > 0){
                        Vue.$toast.open({
                                message: 'Categoria nu poate fi eliminată! Motiv: conține proiecte.',
                                type: 'info',
                                duration: 6000
                            });
                        this.btnLoading = false;
                    } else {
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
                        axios.post(`/api/work-project-categories/${this.the_category.uuid}/delete`).then(response => {

                            if(response.data.success){
                                // if success => reseteaza cerere.
                                Vue.$toast.open({
                                    message: 'Felicitări! Categoria a fost eliminată!',
                                    type: 'success',
                                    duration: 6000
                                });
                                
                                  window.location = '/categorii-proiecte';

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
                            this.btnLoading = false;
                            
                        });
                    }
                }
            });

        

            


      }
    }
}
</script>

<style>

</style>