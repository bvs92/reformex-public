<template>
<div class="card">
    <div class="card-header ">
        <h3 class="card-title ">Tichete</h3>
        <!-- <div class="card-options">
            <a href="/tickets/create" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Tichet nou</a>
        </div> -->
    </div>
    <div class="card-body">

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <div class="row gutters-xs justify-content-md-center">
                        <div class="col-lg-3">
                            <div class="form-group ">
                                <select v-model="selected_search" @change="select_search($event)" class="form-control select2 custom-select select2-hidden-accessible" data-placeholder="Selecteaza stare tichet" tabindex="-1" aria-hidden="true">
                                    <option label="Selecteaza stare tichete">
                                    </option>
                                    <option value="opened">Deschise</option>
                                    <option value="closed">Inchise</option>
                                    <option value="all">Toate</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group ">
                                <select v-model="selected_department" @change="select_department($event)" class="form-control select2 custom-select select2-hidden-accessible" data-placeholder="Selecteaza departament" tabindex="-1" aria-hidden="true">
                                    <option label="Selecteaza departament">
                                    </option>
                                    <option value="general">General</option>
                                    <option value="commercial">Comercial</option>
                                    <option value="technical">Tehnic</option>
                                    <option value="all">Toate</option>
                                </select>
                            </div>
                        </div>

                        <span class="col-auto">
                            <button class="btn btn-primary" type="button" @click.prevent="getData">Preia tichete</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <TicketsTableComponent :tickets="getAllTickets" :total="getTotalTickets" :isBusy="busy" :current_page="current_page" />
    </div>
</div>
</template>

<script>
import TicketsTableComponent from "./_modules/TicketsTableComponent";
import {mapGetters} from 'vuex';

export default {
    name: "ListTicketsForAdminComponent",

    components: {
        TicketsTableComponent
    },

    computed: {
        ...mapGetters('tickets', ['getAllTickets', 'getTotalTickets']),
    },

    data(){
        return {
            tickets: [],
            total: 0,
            busy: false,

            selected_search: null,
            selected_department: null,

            current_page: 1,
        }
    },

    methods: {
        select_search: function(event){
            if(event.target.value){
                this.selected_search = event.target.value;
            } else {
                this.selected_search = null;
            }
        },

        select_department: function(event){
            if(event.target.value){
                this.selected_department = event.target.value;
            } else {
                this.selected_department = null;
            }
        },

        getData: function(){
            if(!this.selected_search){
                return;
            }

            console.log('getData', this.selected_search);

            this.busy = true;
            this.current_page = 1;
            let payload = {
                _type: this.selected_search,
                _department: this.selected_department
            };
            this.$store.dispatch('tickets/initTicketsForAdmin', payload).finally(() => {
                this.busy = false;
            });
        },
    },

    created(){
        this.selected_search = 'opened';
        this.selected_department = 'all';

        this.current_page = 1;
        this.busy = true;
        let payload = {
            _type: this.selected_search,
            _department: this.selected_department
        };
        this.$store.dispatch('tickets/initTicketsForAdmin', payload).finally(() => {
            this.busy = false;
        });
    }
}
</script>

<style>

</style>