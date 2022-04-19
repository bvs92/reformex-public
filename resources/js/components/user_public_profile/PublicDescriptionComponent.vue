<template>
<div class="col-lg-12 mt-6">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Descriere firmă</div>
            <div class="card-options">
                <a href="#" class="btn btn-primary btn-sm" @click.prevent="openModal">Modifică descriere</a>
                <!-- <judete-component></judete-component> -->
            </div>
        </div>
        <div class="card-body">
            <div v-if="loadingStatus" class="text-center">
                <b-spinner small label="Small Spinner"></b-spinner>
            </div>
            <div v-else>
                <div v-if="getUserPublicDescription">
                    <div v-html="getUserPublicDescription" class="public-description"></div>
                </div>
            </div>
        </div>
    </div>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugp descrierea firmei">
    <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
    </div>
    <div class="row" v-else>
        <div class="col-md-12">
            <div class="form-group my-2">
                <label class="form-label">Descriere</label>
                <vue-editor v-model="description" :editor-toolbar="customToolbar" />
                <!-- <textarea class="form-control" name="example-textarea-input" rows="15" placeholder="Descrierea firmei sau a dvs." v-model="description"></textarea> -->
            </div>
            <div class="form-group mb-2">
                <button class="btn btn-success" @click.prevent="saveDescription">Salvează descriere</button>
            </div>
        </div>
    </div>
    </b-modal>

</div>

</template>

<script>
import { mapGetters } from 'vuex';
import { VueEditor } from "vue2-editor";

export default {
    name: "PublicDescriptionComponent",

    data(){
        return {
            modalShow: false,
            description: '',
            loadingStatus: false,

            customToolbar: [
                [{ header: [false, 2, 3, 4, 5, 6] }],
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["code-block"]
            ]
        }
    },

    components: { VueEditor },

    computed: {
        ...mapGetters('public_description', [
            'getUserPublicDescription',
        ])
    },

    methods: {
        openModal: function(){
            this.modalShow = !this.modalShow;

            this.description = this.getUserPublicDescription;
        },

        saveDescription: function(){
            // console.log('salvam', this.description);

            this.loadingStatus = true;
            this.$store.dispatch('public_description/saveUserPublicDescription', {
                description: this.description
            }).then(() => {
                this.modalShow = false;
            }).finally(() => {
                this.loadingStatus = false;
            });
        }
    },

    created(){
        this.loadingStatus = true;
        this.$store.dispatch('public_description/initUserPublicDescription').finally(() => {
            this.loadingStatus = false;
        });

        
    }
}
</script>

<style>
div.public-description ul {
    list-style-type: disc;
    margin-left: 15px;
}

div.public-description p, div.public-description ul, div.public-description ol, div.public-description blockquote {
    margin-bottom: 0.3em;;
}
</style>