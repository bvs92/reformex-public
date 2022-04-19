<template>
    <div>
        <!-- <div id="mapid"></div> -->
        <l-map style="height: 250px" :zoom="zoom" :center="center">
            <l-tile-layer :url="url"></l-tile-layer>
    
            <l-circle
            :lat-lng="circle.center"
            :radius="circle.radius"
            :color="circle.color"
            />
        </l-map>
    </div>
</template>

<script>
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LCircle } from 'vue2-leaflet';
export default {
    name: "MapDemandComponent",

    components: {
        LMap,
        LTileLayer,
        LMarker,
        LCircle
    },

    data(){
        return {
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            zoom: 10,
            center: [this.lat, this.lng],
            circle: {
                center: [this.lat, this.lng],
                radius: 4500,
                color: 'blue'
            }
        }
    },

    props: {
        lat: Number,
        lng: Number,
   
    },

    methods: {},


    created(){},

    mounted() {},

}
</script>

<style scoped>
</style>