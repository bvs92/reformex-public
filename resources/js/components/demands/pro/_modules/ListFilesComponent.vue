<template>
    <div class="row" v-if="the_files" @mouseover="options = true" @mouseleave="options = false">
        <div class="col-lg-3 col-md-6 col-6" v-for="file in the_files" :key="file.id" >
           
           <div v-if="file">
            <template v-if="file.mime_type == 'image/jpeg' || file.mime_type == 'image/png' || file.mime_type == 'image/webp'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" data-lightbox="photos">
                    <img class="img-fluid img-thumbnail mt-4" :src="'/storage/'+ the_type_path +'/' + file.name" />
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/pdf'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/csv'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/msword'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.ms-excel'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/plain'">
                <template v-if="the_type_path">
                    <a :href="'/storage/'+ the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <template v-else>
                <template v-if="the_type_path">
                    <a v-if="this.the_type_path" :href="'/storage/'+ this.the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
                </a>
                </template>
            </template>

            <div class="d-flex">
                <a :href="file.name" @click.prevent="download(the_type_path, file)" v-show="options" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a>
            </div>

           </div>

            
            
        </div>

      </div>

      

</template>

<script>
export default {
    name: "ListFilesComponent",

    data(){
      return {
        options: false,
        files: null,
        isLoading: false
      }
    },
    
    props: {
        the_type_path: String,
        the_files: Array,
    },

    methods: {
     
        download: function(type, file){
            axios({
                url: `/storage/${type}/${file.name}`,
                method: "GET",
                responseType: 'blob'
            }).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', file.name);
                document.body.appendChild(link);
                link.click();
            }).catch(error => {
                if(error.response.status == 404){
                    // console.log("fisierul nu mai exista.");
                }
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