<template>
   <div class="mt-4">
        <h3 class="my-3">Anunțuri</h3>
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
                    placeholder="Cauta anunt"
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
            :items="getTheAnnouncements"
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

        <div class="overflow-auto" v-if="getTotalAnnouncements > perPage">
            <div class="mt-3">
                <b-pagination 
                v-model="currentPage" 
                pills 
                :total-rows="getTotalAnnouncements" 
                :per-page="perPage"
                aria-controls="demands-table"
                align="center"></b-pagination>
            </div>
        </div>


        <b-modal v-model="modalShow" ok-only title="Detalii anunț">
            <div class="text-center" v-if="isBusyAnnounce">
                <b-spinner label="Spinning"></b-spinner>
            </div>
            <div v-else>
                <div v-if="singleAnnounce">
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">#{{ singleAnnounce.id }}</li>
                    <li class="list-group-item">Creat de: {{ singleAnnounce.user.email }}</li>
                    <li class="list-group-item">Titlu: {{ singleAnnounce.title }}</li>
                    <li class="list-group-item public-description" v-html="singleAnnounce.description"></li>
                    <li class="list-group-item">Dată creare: {{ formatElementTimeMethod(singleAnnounce.created_at) }}</li>
                    <li class="list-group-item">Status: {{ formatState(singleAnnounce.status) }}
                        <b-button variant="info" class="float-right btn-sm" disabled v-if="modifyStatus">
                            <b-spinner small type="grow"></b-spinner>
                            Se execută...
                        </b-button>
                         <button class="btn btn-sm btn-info float-right" v-else @click.prevent="toggleStatus(singleAnnounce.id)">Modifică</button>
                         </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-8">
                                    <select class="form-control" id="labe-status" v-model="selectedType">
                                        <option value="albastru">Albastru</option>
                                        <option value="verde">Verde</option>
                                        <option value="galben">Galben</option>
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <b-button variant="info" class="float-right btn-sm" disabled v-if="modifyType">
                                        <b-spinner small type="grow"></b-spinner>
                                        Se execută...
                                    </b-button>
                                    <button class="btn btn-sm btn-info float-right" v-else  @click.prevent="changeType(singleAnnounce.id)">Modifică</button>
                                </div>
                            </div>
                        </li>
                    <li class="list-group-item">Acțiuni: 
                        <b-button variant="danger" class="float-right btn-sm" disabled v-if="actionStatus">
                            <b-spinner small type="grow"></b-spinner>
                            Se execută...
                        </b-button>
                        <button @click.prevent="deleteAnnouncement(singleAnnounce.id)" class="btn btn-sm btn-danger float-right" v-else>Elimină</button>
                        </li>
                    </ul>
                </div>
            </div>
            
        </b-modal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';


export default {
    name: "AdminListAnnouncementsComponent",

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
          { key: 'email', sortable: true, label: 'Creat de' },
          { key: 'title', sortable: true, label: 'Titlu' },
          { key: 'status', sortable: true },
          { key: 'created_at', sortable: true, label: 'Dată creare' },
          { key: 'actions', sortable: false, label: 'Acțiuni' },
        ],



        modalShow: false,
        isBusyAnnounce: false,
        singleAnnounce: null,
        actionStatus: false,
        modifyStatus: false,
        modifyType: false,
        selectedType: null,
      }
    },

    computed: {
        ...mapGetters('announcements', ['getAnnouncements', 'getTotalAnnouncements', 'getSingleAnnouncement']),

        getTheAnnouncements: function(){
            if(this.getTotalAnnouncements > 0){
                return this.getAnnouncements.map(item => {
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

            this.singleAnnounce = _item.item;
            this.selectedType = this.singleAnnounce.type;
            
         
        },

        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            // console.log('filtered item este', filteredItems);
            this.totalRows = filteredItems.length
            this.currentPage = 1
        },

        // getSingleAnnounce: function(item){
        //     this.isBusyAnnounce = true;
        //     this.$store.dispatch('announcements/initSingleAnnouncement', item.id).finally(() => {
        //         this.isBusyAnnounce = false;
        //     });
        // }

        deleteAnnouncement: function(id){
            this.actionStatus = true;
            this.$store.dispatch('announcements/deleteAnnouncement', id).then(() => {
                this.modalShow = !this.modalShow;
            }).finally(() => {
                this.actionStatus = false;
            });
        },

        toggleStatus: function(id){
            this.modifyStatus = true;
            this.$store.dispatch('announcements/toggleStatus', id).then(() => {
                // this.modalShow = !this.modalShow;
                this.singleAnnounce.status = !this.singleAnnounce.status;

                
            }).finally(() => {
                this.modifyStatus = false;
            });
        },

        changeType: function(id){
            this.modifyType = true;
            let payload = {
                id,
                type: this.selectedType
            };
            this.$store.dispatch('announcements/changeType', payload).then(() => {
                // this.modalShow = !this.modalShow;
                this.singleAnnounce.type = this.selectedType;

                
            }).finally(() => {
                this.modifyType = false;
            });
        }
    },

    created: function(){
        this.isBusy = true;


        this.$store.dispatch('announcements/initAnnouncements').finally(() => {
            this.isBusy = false;
        });
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