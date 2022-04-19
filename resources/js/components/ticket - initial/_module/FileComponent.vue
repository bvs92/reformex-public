<template>
<div class="d-inline-flex" :class="content_align" >
  <div class="col-lg-2" v-for="file in attached_files" :key="file.id">
    <template v-if="file.mime_type == 'image/jpeg' || file.mime_type == 'image/png' || file.mime_type == 'image/webp'">
        <a :href="'/storage/tickets/' + file.name" data-lightbox="photos">
            <img class="img-fluid rounded img-thumbnail" :src="'/storage/tickets/' + file.name" />
        </a>
    </template>

    <template v-else-if="file.mime_type == 'application/pdf'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'text/csv'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'application/msword'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'application/vnd.ms-excel'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else-if="file.mime_type == 'text/plain'">
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>

    <template v-else>
        <a :href="'/storage/tickets/' + file.name" style="font-size:10px;">
            <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{  file.name }}
        </a>
    </template>
    
</div>
</div>
</template>

<script>
export default {
    name: "FileComponent",

    props: {
        attached_files: Array,
        content_align: String
    }
}
</script>