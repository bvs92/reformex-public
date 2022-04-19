<template>
  <div>
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
                    placeholder="Caută solicitare cupon"
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
            :items="getPersonalCouponsRequests"
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

        <div class="overflow-auto" v-if="getTotalPersonalCouponsRequests > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalPersonalCouponsRequests" 
                :per-page="perPage"
                aria-controls="coupons-table"
                align="center"></b-pagination>
            </div>
        </div>

        <template v-if="selected_request">
            <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Detalii solicitare">
                <ul class="list-group my-2">
                    <li class="list-group-item">Dată: <strong>{{ formatElementTimeMethod(selected_request) }}</strong></li>
                    <li class="list-group-item">Status: <strong>{{ formatStatus(selected_request.status) }}</strong></li>
                </ul>

                <ul class="list-group my-4" v-if="selected_request.coupon">
                    <li class="list-group-item">ID cupon: <strong>#{{ selected_request.coupon.uuid }}</strong> <a :href="'/cupoane/detalii/' + selected_request.coupon.uuid" class="btn btn-sm btn-info mx-4">Detalii cupon</a></li>
                    <li class="list-group-item">Valoare cupon: <strong>{{ selected_request.coupon.amount / 100 }} RON</strong></li>
                    <li class="list-group-item">Dată: <strong>{{ formatElementTimeMethod(selected_request.coupon.created_at) }}</strong></li>
                    <li class="list-group-item">Status: <strong>{{ formatCouponStatus(selected_request.coupon.used) }}</strong></li>
                </ul>

            </b-modal>
        </template>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "ListPersonalCouponsRequestsComponent",

    data() {
      return {
        modalShow: false,
        selected_request: null,
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
          { key: 'status', label: 'Status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Dată' },
          { key: 'actions', sortable: false, label: 'Actiuni' },
    
        ],
        items: []
      }
    },

    computed: {
        ...mapGetters('coupons_requests', ['getPersonalCouponsRequests', 'getTotalPersonalCouponsRequests']),

        getTheCoupons: function(){
            return this.getPersonalCouponsRequests.map(item => {
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

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/coupons/detalii/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        showDetails: function(_item){
            // console.log('s-a dat click', _item);
            this.modalShow = !this.modalShow;
            this.selected_request = _item.item;
            // console.log('selected_request', this.selected_request);

            // window.location = '/coupons/detalii/' + _item.item.id;
        },
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('coupons_requests/initPersonalCouponsRequests').finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>