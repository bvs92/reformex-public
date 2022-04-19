<template>
        <div class="row">
            <div class="col-lg-12">
                <h5>Preluare informatii dupa CUI</h5>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <input type="numeric" class="form-control" id="cui" name="cui" placeholder="CUI firma" v-model="cif">
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <button class="btn btn-primary" @click="getCompanyData()" v-if="!show_spinner">Preia datele</button>
                <div style="height: 100%;" v-else>
                    <moon-loader size="24px" color="blue" class="moonLoader"></moon-loader>
                    <p class="text-loader">Preluam datele despre firma...</p>
                </div>
            </div>


        
            <div class="col-lg-12" v-if="general_error">
                <div class="alert alert-danger text-center" role="alert" v-if="general_error.additional_info.cif_valid == false">
                    Nu am gasit compania indicata. Sunteti sigur ca identificatorul <strong>{{ cif }}</strong> este corect?
                </div>
            </div>
           


        </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, integer, min_value, min, length } from 'vee-validate/dist/rules';
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

        
export default {

    name: "GetCompanyByCIF",


    components: {
        ValidationProvider,
        ValidationObserver,
        MoonLoader,
      
    },


    data(){
        return {
            cif: null,
          
            general_error: null,
            show_spinner: false,
        }
    },


    methods: {
        async getCompanyData(){

            this.general_error = null;

            this.show_spinner = true;

            axios.defaults.headers.common = {'Authorization': `bearer ${Vue.cookie.get(document.cookie.token_access).token_access}`};
            axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

            await axios.get(`/api/company/data/getbycui/${this.cif}`, {crossDomain: true})
            .then(async response => {
                console.log(response.data);
                // let {company} = response.data;
                if(response.data){
                    await this.$emit('getting_data_by_cif', response.data);
                }
            })
            .catch(error => {
                this.general_error = error.response.data.error;

                
            }).finally(() => {
                this.show_spinner = false;
            });


            console.log('apelat...');
        },
    }
}
</script>