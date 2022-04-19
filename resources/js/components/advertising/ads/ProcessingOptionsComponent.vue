<template>
  <div class="col-lg-12" v-if="ad.processing == 1">
        <div class="card border-primary mb-3">
            <div class="card-header justify-content-center"><span class="badge badge-warning">Anunț în analiză</span></div>
            <div class="card-body text-primary d-flex justify-content-around">
                <a href="#" @click.prevent="activateAd" class="btn btn-success" v-if="!activateStatus">Activează</a>
                <b-button variant="success" disabled v-else>
                    <b-spinner small type="grow"></b-spinner>
                    Se execută...
                </b-button>
                <a href="#" @click.prevent="openRejectModal" class="btn btn-danger">Respinge</a>
            </div>
        </div>

        <b-modal v-model="rejectModal" hide-footer title="Respinge anunț">
            <b-form-textarea
                id="textarea"
                v-model="message"
                placeholder="Motivul respingerii..."
                rows="3"
                max-rows="6"
            ></b-form-textarea>

            <a href="#" @click.prevent="rejectAd" class="btn btn-danger mx-auto d-block my-2" v-if="!rejectStatus">Respinge</a>
            <b-button block variant="danger" class="my-2" disabled v-else>
                    <b-spinner small type="grow"></b-spinner>
                    Se execută...
                </b-button>
        </b-modal>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "ProcessingOptionsComponent",

    data(){
        return {
            activateStatus: false,
            rejectStatus: false,
            rejectModal: false,
            message: null
        }
    },

    props: ["ad"],

    methods: {
        activateAd: async function(){
            this.activateStatus = true;

            await axios
                .post('/api/ads_recommend/activate/' + this.ad.uuid)
                .then(async response => {
                    if(response.data.success){
                        Vue.$toast.open({
                            message: 'Anunț activat cu succes.',
                            type: 'success',
                            duration: 6000,
                            position: 'bottom'
                        });
        
                        this.$emit('activate_ad', true);

                    } else if(response.data.errors){
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                            type: 'error',
                            duration: 6000,
                            position: 'bottom'
                        });
                    }
                }).finally(() => {
                    this.activateStatus = false;
                });


        },

        openRejectModal: function(){
            this.rejectModal = !this.rejectModal;
        },

        rejectAd: async function(){
            this.rejectStatus = true;

            let formData = {
                message: this.message
            };

            await axios
                .post('/api/ads_recommend/reject/' + this.ad.uuid, formData)
                .then(async response => {
                    if(response.data.success){
                        Vue.$toast.open({
                            message: 'Anunț respins cu succes.',
                            type: 'success',
                            duration: 6000,
                            position: 'bottom'
                        });

                        this.message = null;
                        this.rejectModal = !this.rejectModal;

                    } else if(response.data.errors){
                        Vue.$toast.open({
                            message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                            type: 'error',
                            duration: 6000,
                            position: 'bottom'
                        });
                    }
                }).finally(() => {
                    this.rejectStatus = false;
                });


        }
    }
}
</script>

<style>

</style>