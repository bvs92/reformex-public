<template>
<ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
    <form id="register_demand" @submit.prevent="handleSubmit(onSubmit)">
        <div class="form-group">
        <validation-provider rules="required" v-slot="{ errors }">
            <places
                v-model="form.country.label"
                placeholder="Indică orașul, localitatea sau codul poștal."
                @change="valueChanged($event)"
                :options="options">
            </places>
            <span class="small text-danger">{{ errors[0] }}</span>
        </validation-provider>
        </div>

        <div class="form-group d-flex justify-content-center">
            <div class="col-lg-8">
            <button v-if="!btnLoading" type="submit" class="btn btn-success btn-block" :disabled="invalid">Salvează</button>
            <button v-else type="button" class="btn btn-success btn-loading btn-block" disabled="disabled">În curs de salvare</button>
            </div>
        </div>

    </form>
</ValidationObserver>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

import Places from 'vue-places';

export default {
    name: "LocationComponent",

    components: {
        Places,
        ValidationProvider,
        ValidationObserver
    },

    data(){
        return {
            btnLoading: false,
            options: {
                appId: this.app_id,
                apiKey: this.api_key,
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
        demand_id: Number,
        app_id: String,
        api_key: String,
    },

    methods: {
        valueChanged: function(event){
            // console.log(event);
            this.form.country.data = event;
            // console.log(this.form.country);

            // this.$emit('location:selected', this.form.country.data);
            // this.$emit('location:cached', this.form);
        },

        valueReset: function(){
            this.form.country.data = {};
            this.form.country.label = null;
            // this.$emit('location:selected', this.form.country.data);
        },

        resetAll: function(){
            this.form.country.data = {};
            this.form.country.label = null;
        },

        onSubmit: async function(){
            // console.log('salveaza locatia', this.form.country.data);
            // this.$emit('location:changed', this.form.country.data);
            this.btnLoading = true;

            let formData = new FormData();
            formData.append('city', this.form.country.data.name);
            formData.append('administrative', this.form.country.data.administrative);
            formData.append('lat', this.form.country.data.latlng.lat);
            formData.append('lng', this.form.country.data.latlng.lng);

            await axios.post(`/api/admin/demands/${this.demand_id}/update/location`, formData).then(response => {
                if(response.data.success){
                    this.$swal({
                        title: 'Succes',
                        text: "Actiune executată cu succes.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok.',
                    });
                    this.$emit('location:changed', this.form.country.data);
                } else {
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch(err => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.btnLoading = false;
            });
            
        }
    },

    created(){
        // if(Object.keys(this.cached).length !== 0) {
        //     this.form = this.cached;
        // }
    }

    

}
</script>