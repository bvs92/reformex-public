<template>
 <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
    <places
        v-model="form.country.label"
        placeholder="Orașul, localitatea sau codul poștal."
        @change="valueChanged($event)"
        :options="options">
    </places>
    <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
 </validation-provider>
</template>

<script>

import { ValidationProvider, extend, } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Aceasta informatie este obligatorie.'
});

import Places from 'vue-places';

export default {
    name: "PlacesComponent",

    components: {
        Places,
        ValidationProvider
    },

    data(){
        return {
            options: {
                appId: null,
                apiKey: null,
                countries: ['RO']
            },
            form: {
                country: {
                    label: null,
                    data: {},
                }
            },
        }
    },

    props: {
        cached: Object,
        the_app_id: String,
        the_api_key: String
    },

    methods: {
        valueChanged: function(event){
            // console.log(event);
            this.form.country.data = event;
            // console.log(this.form.country);

            this.$emit('location:selected', this.form.country.data);
            this.$emit('location:cached', this.form);
        },

        valueReset: function(){
            this.form.country.data = {};
            this.form.country.label = null;
            this.$emit('location:selected', this.form.country.data);
        },

        resetAll: function(){
            this.form.country.data = {};
            this.form.country.label = null;
        }
    },

    created(){
        this.appId = this.the_app_id;
        this.apiKey = this.the_api_key;
        // if(Object.keys(this.cached).length !== 0) {
        //     this.form = this.cached;
        // }
    }

    

}
</script>