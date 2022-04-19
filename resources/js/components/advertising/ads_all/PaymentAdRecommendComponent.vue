<template>
  <PeriodPriceComponent :uuid="uuid" />
</template>

<script>
import PeriodPriceComponent from "./payment/PeriodPriceComponent.vue";

export default {
    name: "PaymentAdRecommendComponent",

    components: {
        PeriodPriceComponent
    },

    props: ["uuid"]
}
</script>

<style>

</style>