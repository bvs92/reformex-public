<template>
    <div class="row" v-if="location">
        <div class="col-lg-6">
            <h4>Locație: {{ location.city }}</h4>
        </div>
        <div class="col-lg-6">
            <h4>Rază de intervenție: {{ getCalculatedRange }} km.</h4>
        </div>
        <div class="col-lg-12">
            <hr>
            <map-location-component  :accessTokenMap="accessTokenMap" :lat="parseFloat(getLat)" :lng="parseFloat(getLng)" :range="parseFloat(location.range)"></map-location-component>
        </div>
    </div>
</template>

<script>
import MapLocationComponent from "./MapLocationComponent"

export default {
    name: "ExistingLocationComponent",

    props: {
        location: Object,
        accessTokenMap: String
    },

    components: {
        MapLocationComponent
    },


    computed: {
        getCalculatedRange(){
            return this.location.range / 1000;
        },

        getLat(){
            return this.the_location.lat;
        },

        getLng(){
            return this.the_location.lng;
        },
    },

    created(){
        this.the_location = this.location;
    }
}
</script>

<style scoped>

</style>