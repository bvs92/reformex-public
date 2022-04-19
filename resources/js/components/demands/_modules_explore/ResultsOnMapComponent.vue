<template>
    <div>
        <!-- <div id="mapid"></div> -->
        <l-map style="height: 350px" :zoom="zoom" :center="center">
            <l-tile-layer :url="url"></l-tile-layer>
            <!-- <l-circle v-for="demand in demands" :key="demand.id"
            :lat-lng="[demand.lat, demand.lng]"
            :radius="1"
            :color="circle.color"
            /> -->

            <template v-if="demands && demands.length > 0">
                <l-marker v-for="demand in demands" :key="demand.id" :lat-lng="[demand.lat, demand.lng]" />
            </template>

        </l-map>
    </div>
</template>

<script>
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LCircle } from 'vue2-leaflet';
export default {
    name: "ResultsOnMapComponent",

    components: {
        LMap,
        LTileLayer,
        LMarker,
        LCircle
    },

    data(){
        return {
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            zoom: 6,
            center: [44.4361, 26.1027],
            // circle: {
            //     center: [this.coords.lat, this.coords.lng],
            //     radius: 4500,
            //     color: 'blue'
            // }
        }
    },

    props: {
        // lat: Number,
        // lng: Number,
        // accessTokenMap: String,
        demands: Array,
        // coords: Object,
        // range: Number
    },

    computed: {
      calculateZoom: function(){
          if(this.range < 2000){
              return 10;
          } else if(this.range > 2000 && this.range < 5000){
              return 8;
          }
          else if(this.range > 5000){
              return 7;
          } else if (this.range > 10000 && this.range < 20000){
              return 3;
          } else if(this.range > 20000) {
              return 2;
          }
      }
    },

    methods: {},


    created(){},

    mounted() {},

}
</script>

<style scoped>
</style>

