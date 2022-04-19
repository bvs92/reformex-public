<template>
<div class="">
 
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
                    placeholder="Caută perioadă"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>
      </div>


        <b-table
            id="periods-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getPeriods"
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

            <template #cell(actions)="row">
                <a class="btn btn-sm btn-info text-white" @click.prevent="openPeriod(row.item)">
                Detalii
                </a>
            </template>

            <template #cell(created_at)="row">
                {{ formatElementTimeMethod(row.item.created_at) }}
            </template>

            <template #cell(visible)="row">
                {{ row.item.visible == 1 ? 'Vizibil' : 'Ascuns' }}
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalPeriods > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalPeriods" 
                :per-page="perPage"
                aria-controls="periods-table"
                align="center"></b-pagination>
            </div>
        </div>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Detalii perioadă">
        <template v-if="!edit_mode">
        <div class="card border-dark mb-3" v-if="selected_period">
            <div class="card-body text-dark">
                <h5 class="card-title">ID: #{{ selected_period.id }}</h5>
                <h5 class="card-title">Număr zile: {{ selected_period.days }}</h5>
                <p class="card-text" v-if="selected_period.price"> Preț în RON: {{ selected_period.price }}</p>
                <p class="card-text">Vizibil client: {{ selected_period.visible == true ? 'Da' : 'Nu' }}</p>
                <div class="d-flex justify-content-lg-between">
                    <button class="btn btn-sm btn-info" @click.prevent="editPeriod(selected_period)">Editează</button>
                    <button class="btn btn-sm btn-danger" @click.prevent="deletePeriod(selected_period)" v-if="!deleteStatus">Elimină</button>
                    <b-button variant="danger btn-sm" disabled v-else>
                        <b-spinner small></b-spinner>
                        <span class="sr-only">Loading...</span>
                    </b-button>
                </div>
            </div>
        </div>
        </template>
        <template v-else>
            <edit-form-component :period="selected_period" @cancelEdit="edit_mode = !edit_mode" @saved="modalShow = !modalShow"></edit-form-component>
        </template>
        
    </b-modal>
</div>
</template>

<script>
import axios from 'axios';
import {mapGetters} from 'vuex';
import EditFormComponent from './EditFormComponent.vue';

export default {
    name: "ListPeriodsComponent",

    components: {
        EditFormComponent
    },

    data() {
      return {

        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        isBusy: false,
        sortBy: 'days',
        sortDesc: true,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
          { key: 'days', sortable: true, label: 'Zile' },
          { key: 'price', sortable: true, label: 'Preț (RON)' },
          { key: 'created_at', sortable: true, label: 'Dată creare' },
          { key: 'visible', sortable: true, label: 'Vizibil client' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ],

        modalShow: false,
        selected_period: null,
        edit_mode: false,
        deleteStatus: false
      }
    },

    computed: {
        ...mapGetters('periods', ['getPeriods', 'getTotalPeriods']),

        getTheCategories: function(){
            return this.getCategories.map(item => {
                this.formatElementTimeMethod(item);
                return item;
            })
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată creare';
            } else if(this.sortBy == 'days'){
                return 'Zile';
            } else if(this.visible) {
                return 'Vizibilitate';
            } else if(this.days) {
                return 'Zile';
            }
        }
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },


        openPeriod: function(_item){
            this.modalShow = !this.modalShow;
            this.selected_period = _item;
            this.edit_mode = false;
            // window.location = '/categories/show/' + _item.item.id;
        },

        editPeriod: function(period){
            this.selected_period = period;
            this.edit_mode = !this.edit_mode;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        loadPeriods: async function(){
            this.isBusy = true;
            await this.$store.dispatch('periods/all').finally(() => {
                this.isBusy = false;
            });
        },

        deletePeriod: async function(period){
            this.deleteStatus = true;


            await axios.delete('/api/periods/delete/' + period.id)
            .then(async response => {
                // console.log('get single period', response.data.period);
                if(response.data.success){
                    Vue.$toast.open({
                        message: 'Executat cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                    
                    await this.loadPeriods();
                    this.modalShow = !this.modalShow;
                    

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori. Reîncearcă în câteva minute.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).finally(() => {
                this.deleteStatus = false;
            });
     

        }
    },

    created: async function(){
        await this.loadPeriods();
    }

}
</script>