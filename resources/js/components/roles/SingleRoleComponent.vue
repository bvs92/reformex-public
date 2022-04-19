<template>
<div cla>
    <div class="row mb-4 mt-2">
        <div class="col-lg-6" >
            <div class="card" style="min-height: 160px;">
                <div class="card-header">
                    <h4 class="card-title">Detalii</h4>
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Număr utilizatori {{ getTotalUsers }}</h6>
                    <p class="card-text">Creat la data: {{ formatElementTimeMethod(role) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6" >
            <div class="card" style="min-height: 160px;">
                <div class="card-header">
                    <h4 class="card-title">Acțiuni</h4>

                    
                    <div class="card-options" v-if="getTotalUsers < 1">
                        <a href="#" @click="editing = true" v-if="!editing" class="btn btn-info btn-sm">Editează</a>
                        <a href="#" v-else @click="editing = false" class="btn btn-default btn-sm">Renunță</a>
                    </div>
                </div>
                <div class="card-body" v-if="editing">
                    <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                    <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                        <div class="col-lg-10">
                            <validation-provider rules="required|min:3|alpha" v-slot="{ errors, invalid, passed, touched }">
                                <input type="text" class="form-control" 
                                :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
                                id="name" 
                                placeholder="administrator" name="name"
                                v-model="role.name"
                                >
                                <span class="small text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                            <template v-if="validation_errors">
                                <span class="small text-danger" v-for="(error, index) in validation_errors" :key="'error-' + index">{{ error[0] }}</span>
                            </template>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" >Salvează</button>
                        </div>
                    </form>
                    </ValidationObserver>
                </div>
                <div class="card-body" v-else>
                    <h6 class="card-subtitle mb-2 text-muted">Denumire rol: {{ role.name }}</h6>
                    <p class="card-text text-danger" v-if="getTotalUsers < 1">Atenție! Nu editați denumirea dacă rolul este folosit de utilizatori!</p>
                </div>
            </div>
        </div>
    </div>
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
                placeholder="Caută utilizator"
                ></b-form-input>

                <b-input-group-append>
                    <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                </b-input-group-append>
            </b-input-group>
            </b-form-group>
        </b-col>
    </div>

        <b-table
            id="roles-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getUsers"
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
                <b class="text-info">{{ formatStatus(data.item.status) }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalUsers > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalUsers" 
                :per-page="perPage"
                aria-controls="roles-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, min, alpha } from 'vee-validate/dist/rules';

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

export default {
    name: "SingleRoleComponent",

    components: {
        ValidationObserver,
        ValidationProvider
    },

    data(){
        return {
            editing: false,
            validation_errors: null,
            role: null,
            users: [],

            perPage: 25,
            currentPage: 1,

            filter: null,
            filterOn: [],

            // table
            isBusy: false,
            sortBy: 'created_at',
            sortDesc: true,
            fields: [
            { key: 'id', sortable: true, label: 'ID' },
            { key: 'email', sortable: true },
            { key: 'status', sortable: true },
            { key: 'created_at', sortable: true, label: 'Dată înregistrare' },
            { key: 'actions', sortable: false, label: 'Acțiuni' },
            ],
            
        }
    },

    props: {
        the_users: Array,
        the_role: Object
    },

    computed: {
        getUsers: function(){
            return this.users;
        },
        getTotalUsers: function(){
            return this.users.length;
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată înregistrare';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            } else if (this.sortBy == 'status'){
                return 'Status';
            }
        }
    },

    methods: {

        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("DD-MM-YYYY");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Activ';
            } else if(status == 0){
                return 'Inactiv';
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/users/admin/show/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        onSubmit: function(){
            // console.log('s-a dat click');

            // reset form
            this.validation_errors = null;

            let formData = {
                name: this.role.name
            };

            axios.post('/api/roles/update/' + this.role.id, formData)
            .then(response => {
                console.log(response);
                if(response.data.success){
                    // all is ok
                    this.editing = false;
                    this.$refs.observer.reset();
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                }
            }).catch((error) => {
                console.log(error);
                Vue.$toast.open({
                    message: 'Eroare. Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            });
        }
    },

    created(){
        this.users = this.the_users
        this.role = this.the_role;
    }
}
</script>

<style scoped>

</style>