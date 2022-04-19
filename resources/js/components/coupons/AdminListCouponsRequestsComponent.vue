<template>
  <div>
      <div class="row my-2 justify-content-md-center">
          <div class="col-lg-6">
              <div class="form-group">
                    <label class="form-label">Preia date</label>
                    <div class="row gutters-xs">
                        <div class="col">
                            <div class="form-group ">
                                <select v-model="selected_search" @change="select_search($event)" class="form-control select2 custom-select select2-hidden-accessible" data-placeholder="Selecteaza tip cupon" tabindex="-1" aria-hidden="true">
                                    <option label="Selecteaza">
                                    </option>
                                    <option value="pending">În curs de validare</option>
                                    <option value="accepted">Validate</option>
                                    <option value="refused">Refuzate</option>
                                    <option value="all">Toate</option>
                                </select>
                            </div>
                        </div>
                        <span class="col-auto">
                            <button class="btn btn-primary" type="button" @click.prevent="getData"><i class="fe fe-search"></i>Preia date</button>
                        </span>
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
                    placeholder="Caută solicitare"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>

        <b-table
            id="coupons-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getAllCouponsRequests"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :busy.sync="isBusy"
            responsive="sm"
            show-empty
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

            <template #empty="scope">
                <p class="text-center">Niciun rezultat.</p>
            </template>


            <template #cell(status)="data">
                <b class="text-info">{{ formatStatus(data.item.status) }}</b>
            </template>


            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="showDetails(row)">
                Detalii
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalAllCouponsRequests > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalAllCouponsRequests" 
                :per-page="perPage"
                aria-controls="coupons-table"
                align="center"></b-pagination>
            </div>
        </div>

        <template v-if="selected_request">
            <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Detalii solicitare">
                <ul class="list-group my-2">
                    <li class="list-group-item">Solicitant: <strong>{{ selected_request.email }} (ID: {{ selected_request.id }})</strong></li>
                    <li class="list-group-item">Dată: <strong>{{ formatElementTimeMethod(selected_request) }}</strong></li>
                    <li class="list-group-item">Status: <strong>{{ formatStatus(selected_request.status) }}</strong></li>
                </ul>

                <ul class="list-group my-4" v-if="selected_request.coupon">
                    <li class="list-group-item">ID cupon: <strong>#{{ selected_request.coupon.id }}</strong></li>
                    <li class="list-group-item">Valoare cupon: <strong>{{ selected_request.coupon.amount / 100 }} RON</strong></li>
                    <li class="list-group-item">Dată: <strong>{{ formatElementTimeMethod(selected_request.coupon.created_at) }}</strong></li>
                    <li class="list-group-item">Status: <strong>{{ formatCouponStatus(selected_request.coupon.used) }}</strong></li>
                </ul>

                <div class="row my-2 justify-content-md-center" v-if="selected_request.status == null">
                    <div class="col-lg-12">
                        <ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
                        <form @submit.prevent="handleSubmit(sendCoupon)">
                        <div class="form-group">
                                <label class="form-label">Acțiuni solicitare</label>
                                <div class="row gutters-xs">
                                    <div class="col">
                                        <div class="form-group ">
                                        <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
                                            <select 
                                            @change="select_coupon($event)"
                                            v-model="selected_coupon"
                                            class="form-control select2 custom-select select2-hidden-accessible" 
                                            data-placeholder="Selectează valoare cupon" tabindex="-1" aria-hidden="true">
                                                <option label="Selectează">
                                                </option>
                                                <option value="50">50 RON</option>
                                                <option value="100">100 RON</option>
                                                <option value="200">200 RON</option>
                                                <option value="300">300 RON</option>
                                                <option value="400">400 RON</option>
                                                <option value="500">500 RON</option>
                                            </select>
                                            <span class="small text-danger">{{ errors[0] }}</span>
                                            <template v-if="validation_errors">
                                                <template v-if="validation_errors['coupon']">
                                                    <span class="small text-danger" v-for="(error, index) in validation_errors['coupon']" :key="'error-' + index">{{ error }}</span>
                                                </template>
                                            </template>
                                        </validation-provider>
                                        </div>
                                    </div>
                                    <span class="col-auto">
                                        <button v-if="once_execution" class="btn btn-success" type="button" disabled="disabled"><i class="fe fe-search"></i>Trimite cupon</button>
                                        <button v-else class="btn btn-success" type="submit"><i class="fe fe-search"></i>Trimite cupon</button>
                                    </span>
                                </div>
                            </div>
                         </form>
                        </ValidationObserver>
                        <div class="form-group">
                            <div class="row gutters-xs">
                                <div class="col">
                                    <p class="text-center">SAU</p>
                                    <button v-if="once_execution" class="btn btn-danger btn-block" disabled="disabled" type="button">Refuză solicitare cupon</button>
                                    <button v-else class="btn btn-danger btn-block" type="button" @click.prevent="refuseCoupon">Refuză solicitare cupon</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </b-modal>
        </template>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';

import { ValidationProvider, extend, ValidationObserver} from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

export default {
    name: "AdminListCouponsRequestsComponent",

    data() {
      return {

        once_execution: false,

        validation_errors: null,

          // coupons
        selected_coupon: null,



        modalShow: false,
        selected_request: null,
        // search
        selected_search: null,
        // pagination
        // rows: this.getTotalPersonalCoupons,
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
          { key: 'email', label: 'Solicitant', sortable: true },
          { key: 'status', label: 'Status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Dată solicitare' },
          { key: 'actions', sortable: false, label: 'Actiuni' },
    
        ],
        items: []
      }
    },

    components: { ValidationProvider, ValidationObserver },

    computed: {
        ...mapGetters('coupons_requests', ['getAllCouponsRequests', 'getTotalAllCouponsRequests']),

        getTheCoupons: function(){
            return this.getAllCouponsRequests.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată creare';
            } else if (this.sortBy == 'status'){
                return 'Status';
            } 
        }
    },

    methods: {
        // coupons
        select_coupon: function(event){
            if(event.target.value){
                this.selected_coupon = event.target.value;
            } else {
                this.selected_coupon = null;
            }
        },

        sendCoupon: function(){
            if(this.selected_request){
                if(this.selected_coupon){
                    this.once_execution = true;

                    let formData = new FormData();
                    formData.append('coupon', this.selected_coupon);
                    axios.post(`/api/coupons/requests/accept/${this.selected_request.id}`, formData)
                    .then(response => {
                        // console.log('acceptare', response.data);
                        if(response.data.success){
                            
                            this.$store.dispatch('coupons_requests/filterAllCouponsRequests', this.selected_request.id);



                            // this.isBusy = true;
                            // this.$store.dispatch('coupons_requests/initAllCouponsRequests', this.selected_search).finally(() => {
                            //     this.isBusy = false;
                            // });

                            this.modalShow = false;
                            this.selected_coupon = null;
                            this.selected_request = null;

                        } else if(response.data.errors){
                            
                        } else if(response.data.validation_errors){
                            this.validation_errors = response.data.validation_errors;
                        }
                    }).finally(() => {
                        this.once_execution = false;
                    });

                    // this.$store.dispatch('coupons_requests/sendCoupon', this.selected_request.id, this.select_coupon)
                    // .then(() => {
                    //     this.modalShow = false;
                    // })
                    // .finally(() => {
                    //     this.once_execution = false;
                    // });
                }
            }
        },

        refuseCoupon: function(){
            if(this.selected_request){
                this.once_execution = true;
                this.$store.dispatch('coupons_requests/refuseCoupon', this.selected_request.id)
                .then(() => {
                    this.modalShow = false;
                })
                .finally(() => {
                    this.once_execution = false;
                });
            }
        },

        //
        select_search: function(event){
            if(event.target.value){
                this.selected_search = event.target.value;
            } else {
                this.selected_search = null;
            }
        },
        getData: function(){
            if(!this.selected_search){
                return;
            }

            // console.log('getData', this.selected_search);

            this.isBusy = true;


            this.$store.dispatch('coupons_requests/initAllCouponsRequests', this.selected_search).finally(() => {
                this.isBusy = false;
            });
        },

        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("lll");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Acceptat';
            } else if(status == 0){
                return 'Refuzat';
            } else {
                return 'În curs de verificare';
            }
        },

        formatCouponStatus: function(status){
            if(status == 1){
                return 'Folosit';
            } else if(status == 0){
                return 'Nefolosit';
            }
        },

        showDetails: function(_item){
            // console.log('s-a dat click', _item);
            this.modalShow = !this.modalShow;
            this.selected_request = _item.item;
            // window.location = '/coupons/detalii/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        
        this.selected_search = 'pending';
        this.isBusy = true;
        this.$store.dispatch('coupons_requests/initAllCouponsRequests', this.selected_search).finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>