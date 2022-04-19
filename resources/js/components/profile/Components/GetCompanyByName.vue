<template>
    
    <div class="row">
        <div class="col-lg-12">
            <h5>Cauta firma folosind Denumirea si Judetul</h5>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="form-group">
                <input type="text" class="form-control" id="name" name="name" placeholder="Denumire firma" v-model="name">
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="form-group">
                <input type="text" class="form-control" id="judet" name="judet" placeholder="Judet" v-model="judet">
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <button class="btn btn-primary" @click="getCompanyData()" v-if="!show_spinner">Cauta firma</button>
            <div style="height: 100%;" v-else>
                <moon-loader size="24px" color="blue" class="moonLoader"></moon-loader>
                <p class="text-loader">Preluam datele despre firma...</p>
            </div>
        </div>

        
        <div class="col-lg-12" v-if="general_error">
            <div class="alert alert-danger text-center" role="alert">
                <!-- <span v-if="name && name.length > 0">Nu am gasit compania indicata. Sunteti sigur ca identificatorul <strong>{{ name }}</strong> este corect?</span> -->
                <span>Nu am gasit compania. Cautati folosind un nume existent.</span>
            </div>
        </div>

            <template v-if="companies">
                <h3>Rezultate cautare</h3>
                <table class="table" v-if="companies.length > 0">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">CIF</th>
                        <th scope="col">Denumire</th>
                        <th scope="col">Judet</th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="company in companies" :key="company.cif">
                            <th scope="row">{{ company.cif }}</th>
                            <td>{{ company.denumire }}</td>
                            <td>{{ company.judet }}</td>
                            <td><button @click="getCompanyInformationCFI(company.cif)" class="btn btn-sm btn-primary" :disabled="disable_button">Preia datele</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="col-lg-12" v-else>
                    <div class="alert alert-info text-center" role="alert">
                        <span>Nu am gasit compania. Cautati folosind un nume existent.</span>
                    </div>
                </div>

            </template>

            


      
       

    </div>

</template>

<script>
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';


export default {
    name: "GetCompanyByName",

    components: {
        MoonLoader
    },

    data(){
        return {
            name: null,
            judet: null,
            show_spinner: false,
            general_error: null,
            MoonLoader,
            companies: null,
            disable_button: false
        }
    },

    methods: {
        async getCompanyData(){

            let self = this;
            // folosim open api
            let API_KEY = "59FoTuxz62knVDzxVsLsyTa_9BXYgae42rUuEAsjw5BqHKGhgA";
            // let URL_SERVER = "https://private-anon-89e014519d-openapiro.apiary-mock.com/api/companies";
            // let URL_SERVER = "https://private-anon-89e014519d-openapiro.apiary-proxy.com/api/companies/search";
            let URL_SERVER = "https://private-anon-9a56106cc4-openapiro.apiary-proxy.com/api/companies/search";
            // let URL_SERVER = "https://api.openapi.ro/api/companies"; when in production

            this.general_error = null;

            this.show_spinner = true;

            axios.defaults.headers.common = {
                'x-api-key': API_KEY,
                'Access-Control-Allow-Origin': '*',
            }

            let request_url = `${URL_SERVER}/?q=${this.name}`;
            if(this.judet && this.judet.length > 0) {
                request_url += `&judet=${this.judet}`;
            }

            await axios.post(`${request_url}`)
            .then(response => {
                console.log(response.data);

                
                this.companies = response.data.data;
                this.show_spinner = false;
            })
            .catch(error => {
                this.general_error = error.response.data.error;
                this.show_spinner = false;
            });
        },



        async getCompanyInformationCFI(inc_cif){
            // folosim open api
            let API_KEY = "59FoTuxz62knVDzxVsLsyTa_9BXYgae42rUuEAsjw5BqHKGhgA";
            // let URL_SERVER = "https://private-anon-89e014519d-openapiro.apiary-mock.com/api/companies";
            let URL_SERVER = "https://private-anon-9a56106cc4-openapiro.apiary-proxy.com/api/companies";
            // let URL_SERVER = "https://api.openapi.ro/api/companies"; when in production

            this.general_error = null;

            this.show_spinner = true;
            this.disable_button = true;

            axios.defaults.headers.common = {
                'x-api-key': API_KEY,
                'Access-Control-Allow-Origin': '*',
            }

            await axios.get(`${URL_SERVER}/${inc_cif}`)
            .then(response => {
                this.$emit('getting_company_information', response.data);
                this.show_spinner = false;
                this.disable_button = false;
            })
            .catch(error => {
                this.general_error = error.response.data.error;

                this.show_spinner = false;
                this.disable_button = false;
            });
        }
    }
}
</script>