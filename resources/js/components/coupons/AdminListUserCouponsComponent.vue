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
                    placeholder="Caută cupon"
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
            :items="getTheCoupons"
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


            <template #cell(used)="data">
                <b class="text-info">{{ formatStatus(data.item.used) }}</b>
            </template>
            
            <template #cell(amount)="data">
                <b class="text-info">{{ data.item.amount }} RON</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="goToPage(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalUserCoupons > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalUserCoupons" 
                :per-page="perPage"
                aria-controls="coupons-table"
                align="center"></b-pagination>
            </div>
        </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "AdminListUserCouponsComponent",

    data() {
      return {
        // pagination
        // rows: this.getTotalUserCoupons,
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
          { key: 'code', label: 'Cod', sortable: false },
          { key: 'amount',label: 'Valoare', sortable: true },
          { key: 'used', label: 'Status',sortable: true },
          { key: 'created_at', sortable: true, label: 'Dată creare' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ],
        items: []
      }
    },

    computed: {
        ...mapGetters('coupons', ['getUserCoupons', 'getTotalUserCoupons']),

        getTheCoupons: function(){
            return this.getUserCoupons.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată creare';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            } else if (this.sortBy == 'used'){
                return 'Status';
            } else if(this.sortBy == 'amount'){
                return 'Valoare';
            }
        }
    },

    props: {
        user_id: Number
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("DD-MM-YYYY");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Utilizat';
            } else if(status == 0){
                return 'Neutilizat';
            }
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            window.location = '/coupons/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('coupons/initUserCoupons', this.user_id).finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>