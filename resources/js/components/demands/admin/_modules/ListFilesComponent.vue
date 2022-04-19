<template>
    <div
        class="row"
        v-if="the_files"
        @mouseover="options = true"
        @mouseleave="options = false"
    >
        <div
            class="col-lg-3 col-md-6 col-6"
            v-for="file in the_files"
            :key="file.id"
        >
            <div v-if="file">
                <template
                    v-if="
                        file.mime_type == 'image/jpeg' ||
                            file.mime_type == 'image/png' ||
                            file.mime_type == 'image/webp' ||
                            file.mime_type == 'image/apng' ||
                            file.mime_type == 'image/avif' ||
                            file.mime_type == 'image/tiff'
                    "
                >
                    <template v-if="the_type_path">
                        <a 
                            :href="
                                'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/' + the_type_path + '/' + file.name
                            "
                            data-lightbox="photos"
                        >
                            <img
                                class="img-fluid img-thumbnail mt-4 fixed-size"
                                :src="
                                    'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/' +
                                        the_type_path +
                                        '/' +
                                        file.name
                                "
                            />
                        </a>
                    </template>
                </template>

                <template v-else-if="file.mime_type == 'application/pdf'">
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-pdf-o"
                                style="color:darkred;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template v-else-if="file.mime_type == 'text/csv'">
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-word-o"
                                style="color:blue;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template v-else-if="file.mime_type == 'application/msword'">
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-word-o"
                                style="color:blue;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template
                    v-else-if="
                        file.mime_type ==
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    "
                >
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-word-o"
                                style="color:blue;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template
                    v-else-if="file.mime_type == 'application/vnd.ms-excel'"
                >
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-excel-o"
                                style="color:green;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template
                    v-else-if="
                        file.mime_type ==
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    "
                >
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-excel-o"
                                style="color:green;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template v-else-if="file.mime_type == 'text/plain'">
                    <template v-if="the_type_path">
                        <a
                            @click.prevent="download_file('demands', file)"
                            class="icon-file"
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-text-o"
                                style="color:gray;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <template v-else>
                    <template v-if="the_type_path">
                        <a
                            v-if="this.the_type_path"
                            :href="
                                'https://ams3.digitaloceanspaces.com/reformex.ro/uploads/' +
                                    this.the_type_path +
                                    '/' +
                                    file.name
                            "
                            style="font-size:10px;"
                        >
                            <i
                                class="fa fa-file-o"
                                style="color:gray;font-size:40px;"
                            ></i>
                            {{ file.initial_name || file.name }}
                        </a>
                    </template>
                </template>

                <div class="d-flex">
                    <template
                        v-if="
                            file.mime_type == 'image/jpeg' ||
                                file.mime_type == 'image/png' ||
                                file.mime_type == 'image/webp' ||
                                file.mime_type == 'image/apng' ||
                                file.mime_type == 'image/avif' ||
                                file.mime_type == 'image/tiff'
                        "
                    >
                        <!-- <a
                            :href="file.name"
                            @click.prevent="download_file('demands', file)"
                            v-show="options"
                            class="btn btn-sm btn-blue mx-2"
                            ><i class="ti-import"></i
                        ></a> -->
                    </template>
                    <template v-else>
                        <a
                            :href="file.name"
                            @click.prevent="download_file('demands', file)"
                            v-show="options"
                            class="btn btn-sm btn-blue mx-2"
                            ><i class="ti-import"></i
                        ></a>
                    </template>
                    <template v-if="type == 'images'">
                        <a
                            v-if="!isLoading"
                            :href="file.name"
                            @click.prevent="deleteFile(file)"
                            v-show="options"
                            class="btn btn-sm btn-danger mx-2"
                            ><i class="ti-trash"></i
                        ></a>
                        <a
                            v-else
                            class="btn btn-sm btn-danger mx-2"
                            disabled="disabled"
                            ><i class="ti-trash"></i
                        ></a>
                    </template>
                    <template v-else>
                        <a
                            v-if="!isLoading"
                            :href="file.name"
                            @click.prevent="deleteAttachment(file)"
                            v-show="options"
                            class="btn btn-sm btn-danger mx-2"
                            ><i class="ti-trash"></i
                        ></a>
                        <a
                            v-else
                            class="btn btn-sm btn-danger mx-2"
                            disabled="disabled"
                            ><i class="ti-trash"></i
                        ></a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListFilesComponent",

    data() {
        return {
            options: false,
            files: null,
            isLoading: false
        };
    },

    props: {
        the_type_path: String,
        the_files: Array,
        type: String,
    },

    methods: {
        deleteFile: async function(file) {
            this.isLoading = true;
            await axios
                .post(`/api/files/${file.id}/demands/delete`)
                .then(response => {
                    if (response.data.result == "ok") {
                        this.$emit("file:deleted", file.id);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        deleteAttachment: async function(file) {
            this.isLoading = true;
            await axios
                .post(`/api/attachments/${file.id}/demands/delete`)
                .then(response => {
                    if (response.data.result == "ok") {
                        this.$emit("attachment:deleted", file.id);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        download: function(the_type, file) {
            axios({
                url: `/storage/${the_type}/${file.name}`,
                method: "GET",
                responseType: "blob"
            })
                .then(response => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", file.name);
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        // console.log("fisierul nu mai exista.");
                    }
                });
        },

        download_file: function(the_type, file) {
            // console.log("file is", file);
            axios({
                url: `/api/files/${the_type}/${file.name}/download`,
                method: "POST",
                responseType: "blob"
            })
                .then(response => {
                    // console.log("response este", response);
                    // console.log(
                    //     "response headers este",
                    //     response.headers["content-type"]
                    // );
                    // console.log("response data type", response.data.type);
                    let blob = new Blob([response.data], {
                        type: response.headers["content-type"]
                    });
                    // console.log("blob is ", blob);
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", file.initial_name || file.name);
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(error => {
                    // console.log("error este", error);
                    if (error.response.status == 404) {
                        // console.log("fisierul nu mai exista.");
                    }
                });
        }
    },

    created() {
        this.files = this.the_files;
    }
};
</script>

<style scoped>
.icon-file {
display: block;
text-align: center;
}
.icon-file > i.fa {
    display: block;
    text-align: center;
}

img.fixed-size {
min-height: 120px;
max-height: 120px;
height: 120px;
overflow: hidden;
}

</style>
