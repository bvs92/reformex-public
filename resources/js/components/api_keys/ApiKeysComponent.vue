<template>
  <div>
      <div class="row">
          <div class="col-lg-12">
              Adaugă o cheie. <button class="btn btn-info" @click.prevent="createKey">Adaugă</button>
          </div>
      </div>
        <div class="row my-2">
            <div class="col-lg-6 my-1">
                Sortare dupa: <b>{{ getSortBy }}</b>, Mod:
                <b>{{ sortDesc ? 'Descendent' : 'Ascendent' }}</b>
            </div>

            <b-col lg="6" class="my-1">
                <b-form-group>
                <b-input-group size="sm">
                    <b-form-input
                    id="filter-input"
                    v-model="filter"
                    type="search"
                    placeholder="Caută cheie"
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
            :items="getApiKeys"
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
                <strong>Se incarca datele...</strong>
                </div>
            </template>

            <template #empty="scope">
                <p class="text-center">Niciun rezultat.</p>
            </template>


            <template #cell(description)="data">
                <b class="text-info">{{ data.item.key }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item.created_at) }}</b>
            </template>


            <template #cell(actions)="row">
                <b-button size="sm" variant="danger" @click.prevent="deleteKey(row.item)">
                Elimină
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalApiKeys > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalApiKeys" 
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
    name: "ApiKeysComponent",

    data() {
      return {
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],

        // table
        isBusy: false,
        sortBy: 'created',
        sortDesc: true,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
        //   { key: 'email', sortable: true },
          { key: 'key', label: 'Cheie', sortable: true },
          { key: 'created_at', sortable: true, label: 'Data' },
          { key: 'actions', sortable: false, label: 'Acțiune' },
        ],
        items: []
      }
    },

    computed: {
        ...mapGetters('api_keys', ['getApiKeys', 'getTotalApiKeys']),
        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Data creare';
            }
        }
    },

    props: {
        user_id: Number
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        goToPage: function(_item){
            // console.log('s-a dat click', _item);
            // window.location = '/charges/' + _item.item.id;
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        deleteKey: function(item){
            this.$store.dispatch('api_keys/delete', item.id);
        },

        createKey: function(){
            this.$store.dispatch('api_keys/create_token');
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('api_keys/initApiKeys').finally(() => {
            this.isBusy = false;
        });
    }

}
</script>

<style>

</style>