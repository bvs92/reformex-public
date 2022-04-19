<template>
    <b-card v-if="isLoading">
        <b-skeleton animation="wave" width="85%"></b-skeleton>
        <b-skeleton animation="wave" width="55%"></b-skeleton>
        <b-skeleton animation="wave" width="70%"></b-skeleton>
    </b-card>

    <div class="row" v-else>
        <div class="col-lg-12">
            <h4 class="text-center">Selectează perioada de valabilitate anunț.</h4>
        </div>


        <div class="col-sm-6 col-lg-4" v-for="period in getClientPeriods" :key="period.id" @click="setSelected(period)" >
            <div class="card" :class="{'selected' : checkSelected(period)}">
                <div class="card-body text-center">
                    <div class="card-category">Valabilitate anunț</div>
                    <div class="display-5 my-4">{{ period.days }} zile</div>
                    <ul class="list-unstyled leading-loose">
                        <li><strong>Afișări</strong> nelimitate</li>
                        <li><i class="fe fe-check text-success mr-2"></i> Prezent în categoriile selectate.</li>
                        <li><i class="fe fe-check text-success mr-2"></i> Mesaje directe.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-12 my-3">
            <p class="text-center font20" v-if="getCost">
                <span>Cost activare anunț: <strong>{{ totalCost }}</strong></span>
            </p>
        </div>

        <div class="col-lg-12 my-4" v-if="getAd">
            <h4 class="text-center mb-3">Categoriile anunțului</h4>
            <div class="d-flex justify-content-center">
                <span v-for="category in getAd.categories" :key="category.id" class="badge badge-info big mx-2" >
                {{ category.name }}
                </span>
            </div>
        </div>

        <invoice-information-component @has_invoice_information="checkInvoiceInformation"></invoice-information-component>


        <!-- <div class="col-lg-12 my-4">
            <p class="text-center font20" v-if="selected">
                <span>Anunțul va fi activ timp de {{ valability }}.</span>
            </p>
        </div> -->

        <div class="jumbotron col-lg-12">
            <div class="container row">
                <p class="text-center font20 col-lg-12" v-if="getCost">
                    <span>Suma de plată: <strong>{{ totalCost }}</strong></span>
                </p>

                <div class="col-lg-12 my-4" v-if="selected && isVisible">
                    <form :action="`/plata/anunturi-recomandate/checkout/${uuid}/${selected.id}`" method="POST">
                        <p class="text-center font20">
                            <button  class="btn btn-success pay-btn">Plătește</button>
                        </p>
                        <p class="text-center">Folosim Stripe pentru procesarea plăților. Vei fi redirecționat către pagina de plată.</p>
                    </form>
                </div>

                <div class="col-lg-12 my-4" v-else>
                    <form @submit.prevent="notWorkingForm">
                        <p class="text-center font20">
                            <button class="btn btn-success pay-btn">Plătește</button>
                        </p>
                        <p class="text-center">Completează datele de facturare pentru a efectua plata.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import InvoiceInformationComponent from "../../../payments/InvoiceInformationComponent.vue"

export default {
    name: "PeriodPriceComponent",

    data() {
      return {
        selected: null,
        options: [],

        isBusy: false,
        isBusyCost: false,
        isBusyPayment: false,
        isLoading: false,
        loadingCategories: false,

        invoiceInformationStatus: false
      }
    },

    components: {
        InvoiceInformationComponent
    },

    props: ["uuid", "categories"],


    computed: {
      valability: function(){
        return this.selected.days + ' zile'
      },

      totalCost: function(){
          return this.getCost + ' RON';
      },

      ...mapGetters('periods', ['getClientPeriods', 'getTotalClientPeriods']),
      ...mapGetters('ad_recommend_payment', ['getCost']),
      ...mapGetters('ads_recommend', ['getAd']),

      isVisible: function(){
          return this.invoiceInformationStatus ? true : false;
      }
    },


    methods: {
      emitSelected: async function(){
        await this.$emit('selectedPeriod', this.selected)
      },

      checkInvoiceInformation: function(status){
        //   console.log('status', status);
          this.invoiceInformationStatus = status;
      },

      reset: function(){
        this.selected = null;
      },

      setSelected: function(period){
          this.selected = period;
          this.calculateCost();
      },

      checkSelected: function(period){
          if(!this.selected){
              return false;
          }

          return this.selected.id == period.id ? true : false;
      },

      loadPeriods: async function(){
            this.isBusy = true;
            await this.$store.dispatch('periods/allClient').finally(() => {
                this.isBusy = false;
            });
        },

        calculateCost: async function(){
            this.isBusyCost = true;
            let payload = {
                uuid: this.uuid,
                period: this.selected.id
            };
            await this.$store.dispatch('ad_recommend_payment/calculateCost', payload).finally(() => {
                this.isBusyCost = false;
            });
        },

        loadAd: async function(){
            this.loadingCategories = true;
            await this.$store.dispatch('ads_recommend/getSingle', this.uuid).finally(() => {
                this.loadingCategories = false;
            });
        },

        calculateCostIndividual: async function(period_id){
            this.isBusyCost = true;
            let payload = {
                uuid: this.uuid,
                period: period_id
            };
            await this.$store.dispatch('ad_recommend_payment/calculateCost', payload).finally(() => {
                this.isBusyCost = false;
            });
        },

        notWorkingForm: function(){
            Vue.$toast.open({
                message: 'Completează datele de facturare. Verifică dacă ai selectat o perioadă.',
                type: 'info',
                duration: 6000,
                position: 'bottom'
            });
        },


        // payment
        // payAndActivate: async function(){
        //     this.isBusyPayment = true;
        //     let payload = {
        //         uuid: this.uuid,
        //         period: this.selected.id
        //     };
        //     await this.$store.dispatch('banner_payment/payForAnnounce', payload).finally(() => {
        //         this.isBusyPayment = false;
        //     });
        // }
    },


    created: async function(){
        this.isLoading = true;
        await this.loadPeriods()
            .finally(async () => {
                let defaultPeriod = await this.getClientPeriods.filter(item => item.days == '7')
                this.selected = defaultPeriod[0];
                this.isLoading = false;
            });

        await this.loadAd();
        await this.calculateCost();


    }
}
</script>

<style scoped>
.big {
    font-size: 18px;
    padding: 10px;
}

.font20 {
    font-size: 18px;
}

.selected {
    border: 2px solid #00806d;
}

.pay-btn {
    padding: 10px 50px;
}
</style>