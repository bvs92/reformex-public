<template>
    <b-card v-if="isLoading">
    <b-skeleton animation="wave" width="85%"></b-skeleton>
    <b-skeleton animation="wave" width="55%"></b-skeleton>
    <b-skeleton animation="wave" width="70%"></b-skeleton>
    </b-card>
  <div class="row" v-else>
      <div class="col-lg-12 d-lg-flex justify-content-between my-2 py-4 bkd-grey radius" v-for="payment in getPayments" :key="payment.id">
          <div class="my-2">#{{ payment.uuid }}</div>
          <div class="my-2">{{ formatElementTimeMethod(payment.created_at) }}</div>
          <div class="my-2">{{ payment.name }}</div>
          <div class="my-2">{{ payment.amount_total / 100 }} RON</div>
          <div class="my-2">
              <span class="badge badge-success" v-if="payment.payment_status == 'paid'">Plătit</span>
              <span class="badge badge-danger" v-else>Neplătit</span>
          </div>
          <div class="my-2">
              <template v-if="payment.invoice">
                <b-button variant="info" disabled class="btn btn-info btn-sm" v-if="isDownloading">
                    <b-spinner small type="grow"></b-spinner>
                    Descarcă...
                </b-button>
                <a v-else @click.prevent="download(payment)" class="btn btn-radius btn-info btn-sm text-white">Descarcă factură</a>
              </template>
              <span class="badge badge-default" v-else>
                  <template v-if="payment.payment_status == 'paid'">
                      Factură în așteptare
                  </template>
                  <template v-else>
                      Factură indisponibilă
                  </template>
              </span>
          </div>
      </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "PaymentsPersonalComponent",

    computed: {
        ...mapGetters('payments', ['getPayments']),
    },

    data(){
        return {
            isLoading: false,
            isDownloading: false,
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },


        download: async function(payment){
            this.isDownloading = true;
            await this.$store.dispatch('invoices/download', payment.invoice.id).finally(() => {
                this.isDownloading = false;
            });
        },
    },

    async created(){
        this.isLoading = true;
        await this.$store.dispatch('payments/personal').finally(() => {
            this.isLoading = false;
        });
    }
}
</script>

<style scoped>
.bkd-grey {
    background: rgb(247, 247, 247);
}

.radius {
    border-radius: 5px;
}
</style>