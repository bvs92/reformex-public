<template>
    <b-card v-if="isLoading">
    <b-skeleton animation="wave" width="85%"></b-skeleton>
    <b-skeleton animation="wave" width="55%"></b-skeleton>
    <b-skeleton animation="wave" width="70%"></b-skeleton>
    </b-card>
  <div class="row my-2" v-else>
      <div class="col-lg-12">
          <h3>Plăți banner</h3>
      </div>
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
                placeholder="Caută plată"
                ></b-form-input>

                <b-input-group-append>
                    <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                </b-input-group-append>
            </b-input-group>
            </b-form-group>
        </b-col>

        <div class="col-lg-12">
            <b-table
                id="categories-table"
                :per-page="perPage"
                :current-page="currentPage"
                striped
                bordered
                :items="payments"
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

                <template #cell(created_at)="row">
                    {{ formatElementTimeMethod(row.item.created_at) }}
                </template>

                <template #cell(invoice)="row">
                    <span v-if="row.item.invoice">Da</span>
                    <span v-else>Nu</span>
                </template>

                <template #cell(payment_status)="row">
                    <span class="tag tag-green" v-if="row.item.payment_status == 'paid'">Plătit</span>
                    <span class="tag tag-red" v-else-if="row.item.payment_status == 'unpaid'">Neplătit</span>
                    <span class="tag tag-gray" v-else>{{ row.item.payment_status }}</span>
                </template>

                <template #cell(actions)="row">
                    <a class="btn btn-sm btn-info text-white" :href="`/plati/detalii/${row.item.uuid}`">
                    Detalii
                    </a>
                    <!-- <a class="btn btn-sm btn-info text-white" @click.prevent="openPayment(row.item.uuid)">
                    Detalii
                    </a> -->
                </template>

            </b-table>

            <div class="overflow-auto" v-if="totalPayments > perPage">
                <div class="mt-3">
                    <b-pagination 
                    v-model="currentPage" 
                    pills 
                    :total-rows="totalPayments" 
                    :per-page="perPage"
                    aria-controls="categories-table"
                    align="center"></b-pagination>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';


export default {
    name: "BannerPaymentsComponent",

    components: {
        
    },

    computed: {
       

        getThePayments: function(){
            return this.getPayments.map(item => {
                this.formatElementTimeMethod(item.created_at);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'name'){
                return 'Denumire';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            }
        }

    },

    props: ["payments"],

    data(){
        return {
            totalPayments: 0,
            modalShow: false,
            isLoading: false,
            isDownloading: false,

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
                { key: 'created_at', sortable: true, label: 'Dată' },
                { key: 'name', sortable: true, label: 'Denumire' },
                { key: 'payment_status', sortable: true, label: 'Status' },
                { key: 'invoice', sortable: true, label: 'Factura' },
                { key: 'actions', sortable: false, label: 'Acțiuni' },
            ],

            selectedPayment: null
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        download: async function(uuid){
            this.isDownloading = true;
            await this.$store.dispatch('invoices/download', uuid).finally(() => {
                this.isDownloading = false;
            });
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },


    },

    async created(){
        if(this.payments){
            this.totalPayments = this.payments.length
        }
        // await console.log(this.getPayments);
    }
}
</script>

<style scoped>
.bkd-grey {
    background: rgb(247, 247, 247);
}

.radius {
    border-radius: 5px;
}
</style>