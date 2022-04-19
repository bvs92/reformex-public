<template>
<div class="form-group">
    <validation-provider rules="required" v-slot="{ errors}">
    <input type="text" 
    class="form-control" 
    id="autocomplete" 
    placeholder="Caută și selectează locația sau orașul." 
    ref="location"
    @change="changeLocation($event)"
    >
    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
    </validation-provider>
</div>
</template>

<script>

import { ValidationProvider, extend, } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Această informație este obligatorie.'
});

export default {
    name: "GoogleLocation",

    components: {
        ValidationProvider
    },

    data(){
        return {
            selected_palce: null,
            selected_location: null,
            lat_location: null,
            lng_location: null,
        }
    },

    computed: {
    },

    methods: {
        changeLocation: function(event){
            // console.log('selected_palce', this.selected_palce);
        }
    },


    mounted(){
        var location = document.getElementById('autocomplete');
        const autocomplete_result = new google.maps.places.Autocomplete(
            location
        )

        autocomplete_result.setComponentRestrictions({
            country: ["ro"],
        });

        autocomplete_result.setFields(['geometry', 'name', 'formatted_address']);

        autocomplete_result.addListener("place_changed", () => {
   

        const place = autocomplete_result.getPlace();

        if (!place.geometry || !place.geometry.location) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("Nu am găsit nimic pentru: '" + place.name + "'. Încearcă o altă căutare.");
                this.$refs.location.value = '';
                return;
            }


            // console.log(place)
            // infowindowContent.children["place-name"].textContent = place.name;
            // infowindowContent.children["place-address"].textContent =
            // console.log(place.formatted_address);
            var lat = place.geometry.location.lat(), lng = place.geometry.location.lat();
            // console.log('lat:', lat, 'lng:', lng);

            this.selected_place = place;
            this.selected_location = place.formatted_address;
            this.lat_location = place.geometry.location.lat();
            this.lng_location = place.geometry.location.lat();

            let location_complete = {
                value: place.formatted_address,
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
                complete: place
            }

            this.$emit('location:selected', location_complete);
            
        });

    }
}
</script>

<style>
.pac-container {
    line-height: 1rem;
    z-index: 1200;
}
</style>