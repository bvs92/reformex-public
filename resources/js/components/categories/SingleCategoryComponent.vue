<template>
  <div class="card">
    <div class="card-header ">
        <h3 class="card-title ">Detalii categorie</h3>
    </div>
    <div class="card-body">
        <div class="grid-margin row mb-4">
            <div class="col-lg-6" v-if="category">
              
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Denumire: {{ category.name }}</li>
                    <li class="list-group-item">URL (slug): {{ category.slug }}</li>
                    <li class="list-group-item">Preț: {{ category.price }} RON</li>
                    <li class="list-group-item">Dată creare: {{ formatElementTimeMethod(category.created_at) }}</li>
                    <li class="list-group-item">Ultima modificare: {{ formatElementTimeMethod(category.updated_at) }}</li>
                    <template v-if="category.description">
                    <li class="list-group-item">Descriere</li>
                    <li class="list-group-item">{{ category.description }}</li>
                </template>
                </ul>
            </div>

            <div class="col-lg-6">
                <ul class="list-group list-group-flush" v-if="category">
                    <li class="list-group-item">Număr cereri: {{ category.total_demands }}</li>
                </ul>

                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title ">Acțiuni categorie</h3> 
                        <div class="card-options">
                            <b-dropdown size="sm" text="Acțiuni" variant="primary">
                                <b-dropdown-item disabled v-if="once">Editează</b-dropdown-item>
                                <b-dropdown-item href="#" @click="modalShow = !modalShow" v-else>Editează</b-dropdown-item>
                                <b-dropdown-item disabled v-if="once_delete">Elimină</b-dropdown-item>
                                <b-dropdown-item href="#" @click="deleteCategory" v-else>Elimină</b-dropdown-item>
                            </b-dropdown>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-4">
            <h3 class="my-3">Cereri</h3>
            <div class="row my-2">
                <div class="col-lg-6 my-1">
                    Sortare după: <b>{{ getSortBy }}</b>, Mod:
                    <b>{{ sortDesc ? 'Descendent' : 'Ascendent' }}</b>
                </div>

                <b-col lg="6" class="my-1">
                    <b-form-group>
                    <b-input-group size="sm">
                        <b-form-input
                        id="filter-input"
                        v-model="filter"
                        type="search"
                        placeholder="Caută cerere"
                        ></b-form-input>

                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                        </b-input-group-append>
                    </b-input-group>
                    </b-form-group>
                </b-col>
            </div>

                <b-table
                id="demands-table"
                :per-page="perPage"
                :current-page="currentPage"
                striped
                bordered
                :items="getDemands"
                :fields="fields"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :busy.sync="isBusy"
                responsive="sm"

                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
            >
                <template #table-busy>
                    <div class="text-center text-success my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Se încarcă datele...</strong>
                    </div>
                </template>

                <template #cell(status)="data">
                    <b class="text-info">{{ formatState(data.item.state) }}</b>
                </template>

                <template #cell(created_at)="data">
                    <b class="text-info">{{ formatElementTimeMethod(data.item.created_at) }}</b>
                </template>

                <template #cell(actions)="row">
                    <!-- <b-button size="sm" variant="info" @click="goToPage(row)">
                    Vezi
                    </b-button> -->
                    <a :href="'/admin/demands/show/' + row.item.uuid" class="btn-sm btn-info">Detalii</a>
                </template>

            </b-table>

            <div class="overflow-auto" v-if="getTotalDemands > perPage">
                <div class="mt-3">
                    <b-pagination 
                    v-model="currentPage" 
                    pills 
                    :total-rows="getTotalDemands" 
                    :per-page="perPage"
                    aria-controls="roles-table"
                    align="center"></b-pagination>
                </div>
            </div>
        </div>



        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Adaugă o categorie nouă">
            <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                            <label for="name">Denumire categorie</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="name" 
                            placeholder="Acoperis" name="name"
                            v-model="category.name"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['name']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['name']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>
                    
                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min:3" v-slot="{ errors, invalid, passed, touched }">
                            <label for="slug">URL categorie (fără diacritice)</label>
                            <input type="text" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="slug" 
                            placeholder="acoperis" name="slug"
                            v-model="category.slug"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['slug']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['slug']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider rules="required|min_value:1" v-slot="{ errors, invalid, passed, touched }">
                            <label for="price">Preț deblocare cerere (RON)</label>
                            <input type="number" class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="price" 
                            placeholder="10" name="price"
                            v-model="category.price"
                            >
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['price']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['price']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-12 my-2">
                        <validation-provider v-slot="{ errors, invalid, passed, touched }">
                            <label for="description">Descriere categorie</label>
                            <textarea class="form-control" 
                            :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                            id="description" 
                            placeholder="Descrierea categoriei" name="description"
                            v-model="category.description"
                            rows="4"
                            ></textarea>
                            <span class="small text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['description']">
                                <span class="small text-danger" v-for="(error, index) in validation_errors['description']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                    </div>



                    <div class="col-lg-12 my-2">
                        <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                        <b-button variant="info" disabled v-else>
                            <b-spinner small></b-spinner>
                            <span class="sr-only">Salvăm...</span>
                        </b-button>
                        <button class="btn btn-default" @click.prevent="resetAll" :disabled="once">Resetează</button>
                    </div>
                </form>
            </ValidationObserver>

        </b-modal>


    </div>
</div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha, min_value, integer } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});

extend('alpha', {
  ...alpha,
  message: 'Sunt acceptate doar litere.'
});

extend('min', {
  ...min,
  message: 'Lungimea minima acceptata este {length} caractere.'
});

extend('min_value', {
  ...min_value,
  message: 'Valoarea minima acceptata este 1.'
});

extend('integer', {
  ...integer,
  message: 'Doar numere sunt acceptate.'
});

export default {
    name: "SingleCategoryComponent",

    data(){
        return {
            category: null,

            editing: false,
            validation_errors: null,
            demands: [],

            perPage: 25,
            currentPage: 1,

            filter: null,
            filterOn: [],

            // table
            isBusy: false,
            sortBy: 'created_at',
            sortDesc: true,
            fields: [
                { key: 'uuid', sortable: true, label: 'ID' },
                { key: 'subject', sortable: true, label: 'Subiect' },
                { key: 'email', sortable: true },
                { key: 'state', sortable: true, label: 'Stare' },
                { key: 'created_at', sortable: true, label: 'Înregistrare' },
                { key: 'actions', sortable: false, label: 'Acțiuni' },
            ],

            modalShow: false,
            once: false,
            once_delete: false

        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },


    props: {
        the_category: Object
    },

    computed: {
        formatPrice: function(){
            return this.category.price / 100;
        },

        getDemands: function(){
            if(this.category.demands && this.category.demands.length > 0){
                return this.category.demands;
            } else {
                return [];
            }
        },

        getTotalDemands: function(){
            return this.category.demands.length;
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată înregistrare';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            } else if (this.sortBy == 'state'){
                return 'Stare';
            }
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("DD.MM.YYYY, HH:mm");
        },

        formatState: function(state){
            if(state == 1){
                return 'Activă';
            } else if(state == 0){
                return 'Inactivă';
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/demands/id/' + _item.item.uuid;
            window.location = '/admin/demands/show/' + _item.item.uuid;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        deleteCategory: function(){
            // console.log('te execut...');
            this.once_delete = true;

            this.$swal({
                title: 'Eliminare categorie',
                text: "Ești sigur că dorești să elimini această categorie? Actiunea este ireversibilă.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunță'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // console.log('stergem...');
                    axios.post(`/api/categories/delete/${this.category.id}`).then(async response => {

                        if(response.data.success){

                            this.$swal({
                                title: 'Succes',
                                text: "Acțiune executată cu succes.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok.',
                            });

                            // redirect to users.
                            window.location="/categorii/listare"

                        } else if(response.data.errors){
                            Vue.$toast.open({
                                message: 'Oups! Am întampinat erori. Reăncearcă în cateva minute.',
                                type: 'error',
                                duration: 6000
                            });
                        }

                    }).catch((error) => {
                        Vue.$toast.open({
                            message: 'Oups! Am întampinat erori. Reăncearcă în cateva minute.',
                            type: 'error',
                            duration: 6000
                        });
                    });
                }
            }).finally(() => {
                this.once_delete = false;
            });
        },

        onSubmit: async function(){
            this.once = true;
            // console.log('fire!');

            let formData = {
                name: this.category.name,
                slug: this.category.slug,
                price: this.category.price,
                description: this.category.description
            };

            await axios.post(`/api/categories/update/${this.category.id}`, formData).then(async response => {
                // console.log(response.data);

                if(response.data.success){
                    // adauga user in lista.
                    this.category = response.data.category;

                    await this.resetAll();
                    this.modalShow = false;
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întampinat erori. Reăncearcă în cateva minute.',
                        type: 'error',
                        duration: 6000
                    });
                }
            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Reăncearcă în cateva minute.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });
        },

        resetAll: function(){
            this.category = this.the_category;
            this.$refs.observer.reset();
        }

    },

    created(){
        this.category = this.the_category;
    }
}
</script>

<style>

</style>