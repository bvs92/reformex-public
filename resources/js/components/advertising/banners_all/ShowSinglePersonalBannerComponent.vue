<template>
<div>
<!-- <BannerPhotoComponent /> -->

    <div class="d-flex justify-content-center mb-3" v-if="isLoading">
        <b-card>
            <b-skeleton animation="wave" width="85%"></b-skeleton>
            <b-skeleton animation="wave" width="55%"></b-skeleton>
            <b-skeleton animation="wave" width="70%"></b-skeleton>
        </b-card>
    </div>

    <div v-else>
    <b-alert show dismissible variant="danger" class="mb-3 col-lg-12" v-if="getBanner && getBanner.rejected == 1">
        <i class="fa fa-exclamation-circle mr-2 "></i>Anunț respins. <span v-if="getBanner.rejectMessage">Motiv: <strong>{{ getBanner.rejectMessage.message }}</strong></span>
    </b-alert>
    <b-alert show dismissible variant="info" class="mb-3 col-lg-12" v-if="getBanner && getBanner.rejected == 1">
        <i class="fa fa-info-circle mr-2 "></i>Modifică informațiile conform specificațiilor și trimite anunțul pentru validare. 
        <button class="btn btn-success" @click.prevent="sendForValidation" v-if="!isLoadingValidation">Trimite pentru validare</button>
        <b-button variant="success" disabled v-else>
            <b-spinner small type="grow"></b-spinner>
            Se executaă...
        </b-button>
    </b-alert>
        <NonEditBannerComponent v-if="!edit_mode" :banner="getBanner" />
        <edit-banner-component :uuid="getBanner.uuid" v-else></edit-banner-component>
    </div>

    <div class="row mt-5">
        <div class="col-lg-12">

        </div>
    </div>

</div>
</template>

<script>
import { mapGetters } from 'vuex';
import NonEditBannerComponent from './NonEditBannerComponent.vue';
import EditBannerComponent from './EditBannerComponent.vue';


export default {
    name: "ShowSinglePersonalBannerComponent",

    data(){
        return {
            once: false,
            isLoading: false,
            edit_mode: false,
            isLoadingValidation: false,
        }
    },

    props: ["uuid"],

    components: {
        EditBannerComponent,
        NonEditBannerComponent
    },

    computed: {
        ...mapGetters('banners', ['getBanner']),
    },

    methods: {
        getTheBanner: async function(){
            this.isLoading = true;
            await this.$store.dispatch('banners/getSingle', this.uuid)
            .finally(() => {
                this.edit_mode = this.getBanner.editable == '1' ? true : false;
                this.isLoading = false;
            });
        },

        sendForValidation: async function(){
            this.isLoadingValidation = true;

            await axios.post(`/api/banners/personal/request/validation/${this.uuid}`)
            .then(async response => {

                if(response.data.success){
                    await this.getTheBanner();

                    Vue.$toast.open({
                        message: 'Trimis spre validare cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                    
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat corect.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            })
            .catch(error => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Ceva nu a funcționat corect.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
            })
            .finally(() => {
                this.isLoadingValidation = false;
            });

        }
    },

    async created(){
        await this.getTheBanner();
    }
}
</script>

