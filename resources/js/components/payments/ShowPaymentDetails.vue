<template>
<b-card v-if="loading">
  <b-skeleton animation="wave" width="85%"></b-skeleton>
  <b-skeleton animation="wave" width="55%"></b-skeleton>
  <b-skeleton animation="wave" width="70%"></b-skeleton>
</b-card>

<div v-else>

    <div v-if="payment">
        <p>ID plată: #{{ payment.uuid }}</p>
        <p style="font-size: 10px;">Checkout ID: #{{ payment.checkout_id }}</p>
        <p style="font-size: 10px;">Stripe Plata ID: #{{ payment.payment_intent }}</p>
        <p>
            Status plată: <span v-if="payment.payment_status == 'paid'" class="tag tag-green">Plătit</span>
            <span v-else-if="payment.payment_status == 'unpaid'" class="tag tag-red">Neplătit</span>
            <span class="tag tag-gray" v-else>Necunoscut</span>
            </p>
        <hr>
        <h4>Detalii produs</h4>
        <div v-if="payment.paymentable">
            <p>Produs: {{ payment.paymentable_type }} </p>
            <p>ID produs: #{{ payment.paymentable.uuid }} <a :href="`/publicitate/admin/banner/detalii/${payment.paymentable.uuid}`" class="btn btn-sm btn-info">Vezi produs</a></p>
        </div>
        <p>Denumire: {{ payment.name }}</p>
        <p>Sumă: {{ payment.amount_total / 100 }} RON</p>
        <p>Dată: {{ formatElementTimeMethod(payment.created_at) }}</p>
        <p>Utilizator: {{ payment.user.email }}</p>
        <p>ID utilizator: {{ payment.user.id }}</p>
        <div v-if="payment.user.invoice_information" class="row mt-5">
            <div class="col-lg-12">
                <h4>Date de facturare</h4>
            </div>

            <div class="col-lg-6" v-if="payment.user.invoice_information.last_name">
                <p>Nume: {{ payment.user.invoice_information.last_name }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.first_name">
                <p>Prenume: {{ payment.user.invoice_information.first_name }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.type">
                <p>Mod facturare: <span v-if="payment.user.invoice_information.type == 'company'">firmă</span><span v-else>persoană fizică</span></p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.company_type">
                <p>Tip firmă: {{ payment.user.invoice_information.company_type }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.phone">
                <p>Telefon: {{ payment.user.invoice_information.phone }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.address">
                <p>Adresă: {{ payment.user.invoice_information.address }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.cui">
                <p>CUI: {{ payment.user.invoice_information.cui }}</p>
            </div>
            <div class="col-lg-6" v-if="payment.user.invoice_information.number">
                <p>Număr înmatriculare: {{ payment.user.invoice_information.number }}</p>
            </div>
        </div>
        <hr>
            <div v-if="payment.invoice">
                <div class="row">
                    <div class="col-lg-6">
                        <div v-if="payment.invoice.mime_type == 'application/pdf'" class="my-2">
                                <i style="color:darkred;font-size:60px;" class="fa fa-file-pdf-o d-flex justify-content-center"></i>
                        </div>


                        <div v-else-if="payment.invoice.mime_type == 'application/msword'" class="my-2">
                                <i class="fa fa-file-word-o d-flex justify-content-center" style="color:blue;font-size:60px;"></i>
                        </div>

                        <div v-else-if="payment.invoice.mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'" class="my-2">
                                <i class="fa fa-file-word-o d-flex justify-content-center" style="color:blue;font-size:60px;"></i>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center d-flex flex-column">
                            <b-button variant="info" disabled class="btn btn-info btn-sm my-2" v-if="isDownloading">
                                <b-spinner small type="grow"></b-spinner>
                                Descarcă...
                            </b-button>

                            <a href="#" class="btn btn-info btn-sm my-2" @click.prevent="download" v-else>Descarcă</a>
                            
                            <b-button variant="danger" disabled class="btn btn-danger btn-sm my-2" v-if="isDeleting">
                                <b-spinner small type="grow"></b-spinner>
                                Elimină...
                            </b-button>

                            <a href="#" class="btn btn-danger btn-sm my-2" @click.prevent="deleteInvoice" v-else>Elimină</a>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <b-button variant="info" disabled class="btn btn-info btn-block my-2" v-if="isSending">
                                <b-spinner small type="grow"></b-spinner>
                                Se trimite...
                            </b-button>

                        <a href="#" class="btn btn-info btn-block my-2" @click.prevent="sendToUser" v-else>Anunță și trimite pe e-mail</a>
                    </div>

                    

                </div>
            </div>
            <div v-else>
                <UploadInvoiceComponent :payment_uuid="payment.uuid" @upload:success="successUpload" />
            </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
import UploadInvoiceComponent from './UploadInvoiceComponent.vue';

  export default {
    name: "ShowPaymentDetails",
    components: {
        UploadInvoiceComponent
    },

    computed: {
        ...mapGetters('payments', ['getPayment']),
    },

    data() {
      return {
        
        payment: null,
        loading: false,
        isDownloading: false,
        isDeleting: false,
        isSending: false,
      }
    },

    props: ["payment_uuid"],

    methods: {
        toggleModal: function(){
            this.modalShow = !this.modalShow;
        },

        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        initPayment: async function(){

            this.loading = true;

            await axios.get('/api/payments/single/' + this.payment_uuid).then(async response => {

                if(response.data.payment){
                    this.payment = response.data.payment;
                } 
            }).finally(() => {
                this.loading = false;
            });

        },

        successUpload: async function(){
            await this.initPayment();
        },

        download: async function(){
            this.isDownloading = true;
            await this.$store.dispatch('invoices/download', this.payment.invoice.id).finally(() => {
                this.isDownloading = false;
            });
        },

        deleteInvoice: async function(){
            this.isDeleting = true;
            await axios.post(`/api/invoices/delete/${this.payment.invoice.id}`)
            .then(async response => {
                if(response.data.success){
                    await this.initPayment(); 
                }
            }).catch(error => {
                if(error.response.status == 404){
                    Vue.$toast.open({
                        message: 'Factura nu există.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else if(error.response.status == 403 || error.response.status == 401){
                    Vue.$toast.open({
                        message: 'Am întâmpinat erori.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).finally(() => {
                this.isDeleting = false;
            });
        },

        sendToUser: async function(){
            this.isSending = true;
            await axios.post(`/api/invoices/send/${this.payment.invoice.id}`)
            .then(async response => {
                console.log(response.data);
                if(response.data.success){
                    Vue.$toast.open({
                        message: 'Trimis cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).catch(error => {
                if(error.response.status == 404){
                    Vue.$toast.open({
                        message: 'Factura nu există.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else if(error.response.status == 403 || error.response.status == 401){
                    Vue.$toast.open({
                        message: 'Am întâmpinat erori.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).finally(() => {
                this.isSending = false;
            });
        }
    },

    async created(){
        await this.initPayment();
    }
  }
</script>