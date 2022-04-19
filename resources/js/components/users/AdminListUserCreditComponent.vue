<template>
  <div class="row">
    <div class="col-lg-6 my-4">
        <h4 v-if="getCredit">Buget: <strong>{{ getCredit }} RON</strong></h4>
   
    </div>
    <!-- <div class="col-lg-6 my-4">
        <p v-if="getLastPayment">Ultima plata: {{ formatPayment(getLastPayment) }}</p>
  
    </div> -->


    <div class="col-lg-12 my-4">
        <hr>
        <h4>Cupoane activate</h4>
        <UsedCouponsComponent :user_id="user_id" />

        <hr>
    </div>

    <!-- <div class="col-lg-12 my-4">
        <h4>Tranzactii</h4>
        <UserChargesComponent :user_id="user_id" />
    </div> -->



  </div>
</template>

<script>
import {mapGetters} from 'vuex';
import UsedCouponsComponent from './tables/UsedCouponsComponent.vue';
import UserChargesComponent from './tables/UserChargesComponent.vue';

export default {
    name: "AdminListUserCreditComponent",

    props: {
        user_id: Number
    },

    components: {
        UsedCouponsComponent,
        UserChargesComponent
    },

    computed: {
        ...mapGetters('credit', ['getCredit']),
        // ...mapGetters('charges', ['getLastPayment']),
        // ...mapGetters('coupons', ['getUserActivatedCoupons']),
    },

    methods: {
        formatPayment: function(item){
            if(!item){
                return 'Indisponibil.';
            }

            return item.amount / 100 + ' RON, ' + this.formatElementTimeMethod(item.created_at);
        },

        formatElementTimeMethod: function(item){
            return moment(item).format("DD-MM-YYYY, HH:mm:ss");
        },
    },

    created() {
        this.$store.dispatch('credit/initUserCredit', this.user_id);
        this.$store.dispatch('charges/initUserLastPayment', this.user_id);
        // this.$store.dispatch('coupons/initUserActivatedCoupons', this.user_id);
    }
}
</script>

<style>

</style>