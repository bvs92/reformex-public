<template>
  <PeriodPriceComponent :uuid="uuid" />
</template>

<script>
import PeriodPriceComponent from "./payment/PeriodPriceComponent.vue";

export default {
    name: "PaymentBannerComponent",

    components: {
        PeriodPriceComponent
    },

    props: ["uuid"]
}
</script>

<style>

</style>