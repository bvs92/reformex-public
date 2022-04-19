<template>
<div v-if="type == 'images'">
    <div class="row mt-6" v-if="the_files" @mouseover="options = true" @mouseleave="options = false">
        <div class="col-lg-12 my-2">
            <h5>Imagini</h5>
        </div>
        <div class="col-lg-3 col-md-6 col-6" v-for="file in the_files" :key="file.id" >
           
           <div v-if="file">
            <template v-if="file.mime_type.includes('image/jpeg') || file.mime_type.includes('image/png') || file.mime_type.includes('image/webp') || file.mime_type.includes('image/apng') || file.mime_type.includes('image/avif') || file.mime_type.includes('image/gif') || file.mime_type.includes('image/tiff')">
                <template v-if="the_type_path">
                    <a :href="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/'+ the_type_path +'/' + file.name" data-lightbox="photos">
                    <img class="img-fluid img-thumbnail fixed-size mt-4" :src="'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/'+ the_type_path +'/' + file.name" />
                </a>
                </template>
            </template>

            <!-- <div class="d-flex justify-content-center">
                <a :href="file.name" @click.prevent="download(the_type_path, file)" v-show="options" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a>
            </div> -->

           </div>

            
            
        </div>

      </div>
</div>
<div v-else>
    <div class="row mt-6" v-if="the_files" @mouseover="options = true" @mouseleave="options = false">
        <div class="col-lg-12 my-2">
            <h5>Fișiere</h5>
        </div>
        <div class="col-lg-3 col-md-6 col-6" v-for="file in the_files" :key="file.id" >
           
           <div v-if="file">
   
            <template v-if="file.mime_type == 'application/pdf'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/csv'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/msword'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.ms-excel'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else-if="file.mime_type == 'text/plain'">
                <template v-if="the_type_path">
                    <a @click.prevent="download_file(the_type_path, file)" class="icon-file" style="font-size:10px;">
                    <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <template v-else>
                <template v-if="the_type_path">
                    <a v-if="this.the_type_path" :href="'/storage/'+ this.the_type_path +'/' + file.name" style="font-size:10px;">
                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{  file.initial_name || file.name }}
                </a>
                </template>
            </template>

            <div class="d-flex justify-content-center">
                <!-- <a :href="`/file/download/${the_type_path}/${file.name}`"  v-show="options" class="btn btn-sm btn-blue mx-2">D</a> -->
                <a :href="file.name" @click.prevent="download_file(the_type_path, file)"  v-show="options" class="btn btn-sm btn-blue mx-2"><i class="ti-import"></i></a>
            </div>

           </div>

            
            
        </div>

      </div>
</div>

</template>

<script>
export default {
    name: "ListDemandFiles",

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
        type: String
    },

    methods: {
     
        // download: function(type, file){
        //     axios({
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
                    
        //         }
        //     });
        // },

        download_file: function(the_type, file){
            axios({
                url: `/api/files/${the_type}/${file.name}/download`,
                method: "POST",
                responseType: 'blob'
            }).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', file.initial_name || file.name);
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

<style scoped>
i.fa {
    display:block;margin:0 auto; text-align: center;
}

.icon-file {
display: block;
text-align: center;
}
.icon-file > i.fa {
    display: block;
    text-align: center;
    font-size: 50px!important;
    padding: 10px;
}

img.fixed-size {
min-height: 120px;
max-height: 120px;
height: 120px;
overflow: hidden;
}

</style>