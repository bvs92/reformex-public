<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title ">Roluri utilizator</h3>
                <div class="card-options">
                    <button class="btn btn-sm btn-info" @click="modalFunc">Modifică</button>
                </div>
            </div>

            <div class="card-body text-dark">
                <ul class="list-group list-group-flush" v-if="user.roles && user.roles.length > 0">
                    <li class="list-group-item" v-for="role in user.roles" :key="'role-' + role.id"><i class="fa fa-check-circle-o mr-2"></i>{{ role.name }}</li>
                </ul>
            </div>

        </div>

        <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Modifica roluri utilizator">
            <template v-if="!isLoading">
            <div>
                <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                    <form class="row" @submit.prevent="handleSubmit(onSubmit)">
                        <b-form-group
                            label=""
                            v-slot="{ ariaDescribedby }"
                            class="col-lg-12"
                            >
                            <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                            <b-form-checkbox-group
                                v-model="selected"
                                :options="getPreparedRoles"
                                :aria-describedby="ariaDescribedby"
                                name="user-roles"
                                stacked
                                @change="changeFunc"
                            ></b-form-checkbox-group>
                            <span class="small text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </b-form-group>
                        <template v-if="validation_errors">
                            <template v-if="validation_errors['roles']">
                                <span class="col-lg-12 small text-danger" v-for="(error, index) in validation_errors['roles']" :key="'error-' + index">{{ error }}</span>
                            </template>
                        </template>
                        <div class="col-lg-12 my-2">
                            <button class="btn btn-success" :class="{'disabled': invalid}" :disabled="invalid" v-if="!once">Salvează</button>
                            <b-button variant="info" disabled v-else>
                                <b-spinner small></b-spinner>
                                <span class="sr-only">Se salvează...</span>
                            </b-button>
                            <button class="btn btn-default" @click.prevent="setInitial" :disabled="once">Resetează</button>
                        </div>
                        
                    </form>
                </ValidationObserver>
                
                
            </div>
            </template>
            <div class="text-center" v-else>
                <b-spinner label="Spinning"></b-spinner>
            </div>
        </b-modal>
    </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';
import {mapGetters} from 'vuex';


extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


export default {
    name: "UserRolesComponent",

    data(){
        return {
            user: {},
            modalShow: false,
            isLoading: false,
            selected: [], // Must be an array reference!
            validation_errors: null,
            once: false
        }
    },

    components: {
        ValidationObserver,
        ValidationProvider
    },

    props: {
        the_user: Object
    },

    computed: {
        ...mapGetters('roles', ['getRoles']),

        getPreparedRoles: function(){
            return this.getRoles.map(item => {
                let element = {
                    text: item.name.charAt(0).toUpperCase() + item.name.slice(1),
                    value: item.id
                };

                return element;
            });
        },

        getUserRolesIds: function(){
            return this.user.roles.map(item => {
                return item.id;
            });
        },
    },


    methods: {
        modalFunc: function(){
            this.modalShow = !this.modalShow;
            this.selected = this.getUserRolesIds;
        },

        onSubmit: async function(){

            let formData = {
                roles: this.selected
            };

            await axios.post(`/api/admin/users/${this.user.id}/update/roles`, formData).then(async response => {

                if(response.data.success){
                    this.user = response.data.user;
                    // await this.resetAll();
                    this.modalShow = false;
                } else if(response.data.validation_errors){
                    this.validation_errors = response.data.validation_errors;
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                        type: 'error',
                        duration: 6000
                    });
                }

            }).catch((error) => {
                Vue.$toast.open({
                    message: 'Oups! Am întâmpinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
            }).finally(() => {
                this.once = false;
            });
        },

        setInitial: function(){
            this.selected = this.getUserRolesIds;
        },

        changeFunc: function(checked){
            this.validation_errors = null;
            this.selected = checked;
        }
    },

    created(){
        this.user = this.the_user;

        // console.log('are roluri?', this.user);


        // initRoles.
        this.isLoading = true;
        this.$store.dispatch('roles/initRoles').finally(() => {
            this.isLoading = false;
        });

        

    }
}
</script>