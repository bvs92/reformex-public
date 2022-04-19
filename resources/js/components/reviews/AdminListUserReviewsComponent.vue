<template>
<div class="text-center" v-if="loading">
    <b-spinner label="Spinning"></b-spinner>
</div>
  <div v-else>
      <div>
        <b-table striped hover :items="all_reviews" :fields="fields" :busy.sync="isBusy">
            <template #table-busy>
                <div class="text-center text-success my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Se incarca datele...</strong>
                </div>
            </template>

            <template #cell(is_reported)="data">
                <b class="text-info">{{ formatStatus(data.item.is_reported) }}</b>
            </template>

            <template #cell(created_at)="data">
                <b class="text-info">{{ formatElementTimeMethod(data.item) }}</b>
                <!-- <b class="text-info">{{ data.item.created_at }}</b> -->
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" variant="info" @click="openModal(row)">
                Vezi
                </b-button>
            </template>
        </b-table>

        <div class="col-lg-12">
            <b-pagination style="background: white; width: 100%;" v-if="getTotal > getPerPage"
                class="justify-content-center"
                v-model="currentPage"
                :total-rows="getTotal"
                :per-page="getPerPage"
                aria-controls=""
                :pills="true"
                :hide-goto-end-buttons="false"
                @change="pageClicked"
            ></b-pagination>
        </div>

    </div>

    <b-modal v-model="modalShow" hide-footer id="modal-center" centered title="Detalii complete recenzie">
        <ul class="list-group list-group-flush" v-if="selected_item">
        <li class="list-group-item">De la: <strong>{{ selected_item.name }}</strong></li>
        <li class="list-group-item">Nota: <strong>{{ selected_item.rating }} stele</strong></li>
        <li class="list-group-item text-muted">Data: <strong>{{ formatElementTimeMethod(selected_item.created_at) }}</strong></li>
        <li class="list-group-item">{{ selected_item.message }}</li>
        <li class="list-group-item">Reclamat:
            <span v-if="selected_item.is_reported">Da</span>
            <span v-else>Nu</span>
        </li>

        <li class="list-group-item" v-if="selected_item.is_reported">
            <ul class="list-group list-group-flush" v-if="selected_item.report">
                <li class="list-group-item" v-if="selected_item.report.full_name">
                    Reclamant: <strong>{{ selected_item.report.full_name }}</strong>
                </li>
                <li class="list-group-item text-muted">Data: <strong>{{ formatElementTimeMethod(selected_item.report.created_at) }}</strong></li>
                <li class="list-group-item">
                    {{ selected_item.report.description }}
                </li>
            </ul>
        </li>
        <li class="list-group-item">
            Actiuni: <button class="btn btn-sm btn-danger" @click.prevent="deleteReview(selected_item.id)" v-if="!once_delete">Elimina</button>
            <button class="btn btn-sm btn-danger btn-loading" disabled="disabled" v-else>Se elimina...</button>
        </li>
        </ul>
    </b-modal>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "AdminListUserReviewsComponent",

    data(){
        return {
            loading: false,
            currentPage: 1,
            modalShow: false,
            validation_errors: null,
            once_send: false,
            once_delete: false,
            description: null,
            selected_item: null,
            all_reviews: null,

            isBusy: false,

            fields: [
                {
                    key: 'id',
                    sortable: false,
                    label: "ID"
                },
                {
                    key: 'name',
                    sortable: false,
                    label: "De la"
                },
                {
                    key: 'user_name',
                    sortable: false,
                    label: "Catre"
                },
                {
                    key: 'rating',
                    sortable: false,
                    label: "Nota"
                },
                {
                    key: 'created_at',
                    sortable: false,
                    label: "Data"
                },
                {
                    key: 'is_reported',
                    sortable: false,
                    label: "Raportat"
                },
                {
                    key: 'actions',
                    sortable: false,
                    label: "Actiuni"
                },
            ]
        }
    },

    components: {
    },

    props: {
        user_id: Number
    },

    computed: {
        ...mapGetters('reviews', ['getAllUserReviews', 'getCurrentPage', 'getFrom', 'getLastPage', 'getPerPage', 'getTotal',]),
    },

    methods: {
        formatElementTimeMethod: function(item){
            return moment(item).format("lll");
        },

        pageClicked: async function(page){
            this.isBusy = true;
            await this.$store.dispatch('reviews/getAllUserReviewsFromPage', this.user_id, page).then(() => {
                this.all_reviews = this.getAllUserReviews;
            }).finally(() => {
                this.isBusy = false;
            });
            this.currentPage = this.getCurrentPage;
        },

        openModal: function(item){
            console.log(item.item);
            this.modalShow = !this.modalShow;
            this.selected_item = item.item;
            // this.reset();
        },

        reset: function(){
            this.validation_errors = null;
            this.description = null;
        },

        formatElementTimeMethod: function(item){
            return moment(item.created_at).format("lll");
        },

        formatStatus: function(status){
            if(status == 1){
                return 'Da';
            } else if(status == 0){
                return 'Nu';
            }
        },

        deleteReview: async function(id){
            this.once_delete = true;
            await this.$store.dispatch('reviews/deleteUserReview', id).then(() => {
                this.all_reviews = this.getAllUserReviews;
                this.modalShow = false;
                this.selected_item = null;
            }).finally(() => {
                this.once_delete = false;
            });
        }

    },

    created(){
        // this.loading = true;
        this.isBusy = true;
        this.$store.dispatch('reviews/getAllUserReviewsFromPage', this.user_id, this.currentPage).then(() => {
            this.all_reviews = this.getAllUserReviews;
        }).finally(() => {
            // this.loading = false;
            this.isBusy = false;
        });
    }
}
</script>

<style>

</style>