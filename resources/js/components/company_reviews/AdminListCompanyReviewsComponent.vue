<template>
   <div class="mt-4">
        <h3 class="my-3">Recenzii</h3>
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
                    placeholder="Cauta recenzie"
                    ></b-form-input>

                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''" variant="info">Șterge</b-button>
                    </b-input-group-append>
                </b-input-group>
                </b-form-group>
            </b-col>

            <div class="col-lg-6">
                
             
                
            </div>
        </div>

            <b-table
            id="demands-table"
            :per-page="perPage"
            :current-page="currentPage"
            striped
            bordered
            :items="getTheCompanyReviews"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :busy.sync="isBusy"
            responsive="sm"

            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
            show-empty
            >
            <template #table-busy>
                <div class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Se incarcă datele...</strong>
                </div>
            </template>

            <template #empty>
                <p class="text-center">Niciun element disponibil.</p>
            </template>

            <template #cell(status)="data">
                <b class="text-info">{{ formatState(data.item.status) }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item.created_at) }}</b>
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click.prevent="showDetails(row)">
                Vezi
                </b-button>
            </template>

        </b-table>

        <div class="overflow-auto" v-if="getTotalCompanyReviews > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalCompanyReviews" 
                :per-page="perPage"
                aria-controls="demands-table"
                align="center"></b-pagination>
            </div>
        </div>


        <b-modal v-model="modalShow" ok-only title="Detalii recenzie">
            <div class="text-center" v-if="editTrue">
                <EditFormReview :review="singleReview" @cancelEdit="cancelEdit" @reviewEdited="reviewEdited" />
            </div>
            <div v-else>
                <div v-if="singleReview">
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">#{{ singleReview.id }}</li>
                    <li class="list-group-item">Creat de: {{ singleReview.user.email }}</li>
                    <li class="list-group-item">Rating: {{ singleReview.rating }}</li>
                    <li class="list-group-item public-description" v-html="singleReview.message"></li>
                    <li class="list-group-item">Dată creare: {{ formatElementTimeMethod(singleReview.created_at) }}</li>
                    <li class="list-group-item">Status: {{ formatState(singleReview.status) }}
                         <b-button variant="info" class="float-right btn-sm" disabled v-if="modifyStatus">
                            <b-spinner small type="grow"></b-spinner>
                            Se execută...
                        </b-button>
                         <button class="btn btn-sm btn-info float-right" v-else @click.prevent="toggleStatus(singleReview.id)">Schimbă</button>
                         </li>
                    <li class="list-group-item">Acțiuni: 

                        <button @click.prevent="editTrue = !editTrue" class="btn btn-sm btn-warning mx-1">Editează</button>

                        <b-button variant="danger" class="btn-sm" disabled v-if="actionStatusDelete">
                            <b-spinner small type="grow"></b-spinner>
                            Se execută...
                        </b-button>
                        <button @click.prevent="deleteReview(singleReview.id)" class="btn btn-sm btn-danger mx-1" v-else>Elimină</button>
                    </li>
                    </ul>
                </div>
            </div>
            
        </b-modal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import EditFormReview from './EditFormReview.vue'

export default {
    name: "AdminListCompanyReviewsComponent",

    components: {
        EditFormReview
    },

    data() {
      return {
        // pagination
        // rows: this.getTotalUsers,
        perPage: 25,
        currentPage: 1,

        filter: null,
        filterOn: [],
        sortDirection: 'asc',

        // table
        isBusy: false,
        sortBy: 'created_at',
        sortDesc: true,
        fields: [
          { key: 'id', sortable: true, label: 'ID' },
          { key: 'user.email', sortable: true, label: 'Creat de' },
          { key: 'company_name', sortable: true, label: 'Firmă' },
          { key: 'status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Dată creare' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ],


        editTrue: false,
        modalShow: false,
        isBusyAnnounce: false,
        singleReview: null,
        actionStatusEdit: false,
        actionStatusDelete: false,
        modifyStatus: false,


        
      }
    },

    computed: {
        ...mapGetters('company_reviews', ['getCompanyReviews', 'getTotalCompanyReviews']),

        getTheCompanyReviews: function(){
            if(this.getTotalCompanyReviews > 0){
                return this.getCompanyReviews.map(item => {
                    this.formatElementTimeMethod(item.created_at);
                    return item;
                })
            } else {
                return [];
            }
        },

        getSortBy: function(){
            if(this.sortBy == 'created_at'){
                return 'Dată creare';
            } else if(this.sortBy == 'email'){
                return 'E-mail';
            } else if (this.sortBy == 'status'){
                return 'Status';
            }
        }
    },

    props: {},

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        formatState: function(state){
            if(state == 1){
                return 'Activ';
            } else if(state == 0){
                return 'Inactiv';
            }
        },

        showDetails: function(_item){
            // this.$store.commit('announcements/set_single_announcement', null);
            this.modalShow = !this.modalShow;
            // this.getSingleAnnounce(_item.item);

            this.singleReview = _item.item;
            
         
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            // console.log('filtered item este', filteredItems);
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },


        deleteReview: async function(id){

            this.actionStatusDelete = true;
            await axios.post(`/api/company_reviews/delete/${id}`).then(async response => {
                if(response.data.success){
                    await this.getReviews();

                    this.modalShow = !this.modalShow;
                    Vue.$toast.open({
                        message: 'Recenzie eliminată cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).finally(() => {
                this.actionStatusDelete = false;
            });

        },


        toggleStatus: async function(id){
            this.modifyStatus = true;

            await axios.post(`/api/company_reviews/toggleStatus/${id}`).then(async response => {
                if(response.data.success){
                    this.singleReview.status = !this.singleReview.status;
                    Vue.$toast.open({
                        message: 'Status recenzie modificat cu succes.',
                        type: 'success',
                        duration: 6000,
                        position: 'bottom'
                    });

                } else if(response.data.errors){
                    Vue.$toast.open({
                        message: 'Oups! Am întâmpinat erori.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            }).finally(() => {
                this.modifyStatus = false;
            });

            // this.$store.dispatch('company_reviews/toggleStatus', id).then(() => {
            //     // this.modalShow = !this.modalShow;
            //     this.singleReview.status = !this.singleReview.status;

                
            // }).finally(() => {
            //     this.modifyStatus = false;
            // });
        },

        cancelEdit: function(){
            this.editTrue = false;
        },

        reviewEdited: function(newReview){
            this.singleReview.rating = newReview.rating;
            this.singleReview.message = newReview.message;
            this.editTrue = false;
        },

        getReviews: function(){
            this.isBusy = true;


            this.$store.dispatch('company_reviews/initCompanyReviews').finally(() => {
                this.isBusy = false;
            });
        }
    },

    created: async function(){
        await this.getReviews();
    }



}
</script>

<style scoped>

.public-description {
    background: #f7f7f7;
}

.public-description ul {
    list-style-type: disc;
    margin-left: 15px;
}

.public-description p, .public-description ul, .public-description ol, .public-description blockquote {
    margin-bottom: 0.3em;;
}
</style>