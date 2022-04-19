<template>
  <div>
      <div class="row">
          <div class="col-lg-6">
              <p>Număr total notificări: <strong>{{ totalNotifications }}</strong></p>
          </div>
          <div class="col-lg-6">
              <div>
                    Sortare după: <b>{{ getSortBy }}</b>, Mod:
                    <b>{{ sortDesc ? 'Descendent' : 'Ascendent' }}</b>
               </div>
          </div>
      </div>
    <p v-if="!blockBtns">
      <b-button size="sm" variant="info" @click="selectAllRows">Selectează tot</b-button>
      <b-button size="sm" variant="secondary" @click="clearSelected" :class="{'disabled' : !isSelected}" :disabled="!isSelected ? true : false">Deselectează tot</b-button>
      <!-- <b-button size="sm" variant="info" @click="selectThirdRow">Select 3rd row</b-button>
      <b-button size="sm" variant="secondary" @click="unselectThirdRow" :class="{'disabled' : !isSelected}" :disabled="!isSelected ? true : false">Unselect 3rd row</b-button> -->
      <b-button size="sm" @click="deleteSelectedRows" variant="danger" :class="{'disabled' : !isSelected}" :disabled="!isSelected ? true : false"><b-icon-trash></b-icon-trash> Elimină selecție</b-button>
      <b-button size="sm" @click="markAsReadSelectedRows" variant="primary" :class="{'disabled' : !isSelectedUnread}" :disabled="!isSelectedUnread ? true : false"><b-icon-trash></b-icon-trash> Marcare ca citit</b-button>
    </p>

    <p v-else>
      <b-button size="sm" variant="info" class="disabled" disabled="disabled">Selectează tot</b-button>
      <b-button size="sm" variant="secondary" class="disabled" disabled="disabled">Deselectează tot</b-button>
      <b-button size="sm" variant="danger" class="disabled" disabled="disabled"><b-icon-trash></b-icon-trash> Elimină selecție</b-button>
      <b-button size="sm" variant="primary" class="disabled" disabled="disabled"><b-icon-trash></b-icon-trash> Marcare ca citit</b-button>
    </p>

    <b-table
    id="notifications-table"
    :per-page="perPage"
      :items="items"
      :fields="fields"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      sort-icon-left
      :select-mode="selectMode"
      responsive="sm"
      ref="selectableTable"
      bordered
      striped
      selectable
      @row-selected="onRowSelected"
      :busy="isBusy"
    >
    <template #table-busy>
    <div class="text-center text-success my-2">
        <b-spinner class="align-middle"></b-spinner>
        <strong>Se preiau datele...</strong>
    </div>
    </template>
    
    <!-- Example scoped slot for select state illustrative purposes -->
      <template #cell(selected)="{ rowSelected }">
        <template v-if="rowSelected">
          <span aria-hidden="true">&check;</span>
          <span class="sr-only">Selectat</span>
        </template>
        <template v-else>
          <span aria-hidden="true">&nbsp;</span>
          <span class="sr-only">Neselectat</span>
        </template>
      </template>

      <template #cell(actions)="row">
        <!-- <a :href="'/notifications/' + row.item.id" class="btn btn-default btn-sm">Detalii</a> -->
        <!-- <b-button size="sm" variant="info" @click="goToPage(row)">
          Vezi
        </b-button> -->

        <!-- <a :href="'/tickets/list'" v-if="row.item.type.includes('TicketNotification')" class="btn btn-default btn-sm">
                Detalii          
            </a> -->


            <!-- <a class="btn btn-default btn-sm" :href="'/tickets/get/id/' + row.item.data.ticket_uuid" v-if="row.item.type.includes('TicketChatActionNotification')">
                Detalii
            </a> -->

            <!-- <a class="btn btn-default btn-sm" :href="'/tickets/get/id/' + row.item.data.ticket_uuid" v-if="row.item.type.includes('TicketMessageInactiveUserNotification')">
                Detalii
            </a> -->


            <a class="btn btn-default btn-sm" :href="'/cupoane/personal'" v-if="row.item.type.includes('SendCouponToUserNotification')">
                Detalii
            </a>

            <a class="btn btn-default btn-sm" :href="'/cupoane/toate/solicitari'" v-if="row.item.type.includes('RequestCouponNotification')">
                Detalii
            </a>

            <a class="btn btn-default btn-sm" :href="'/cupoane/solicitari'" v-if="row.item.type.includes('ResponseCouponRequestNotification')">
                Detalii
            </a>

            <!-- <a class="btn btn-default btn-sm" :href="'/demands/id/' + row.item.data.demand_uuid" v-if="row.item.type.includes('DemandBought')">
                Detalii
            </a> -->

            <a class="btn btn-default btn-sm" :href="'/admin/demands/show/' + row.item.data.demand_uuid" v-if="row.item.type.includes('ReportDemandNotification')">
                Detalii
            </a>

            <a class="btn btn-default btn-sm" :href="'/cereri/pro/detalii/' + row.item.data.demand_uuid" v-if="row.item.type.includes('ResponseForReportedDemandNotification')">
                Detalii
            </a>

            <!-- admin to user -->
            <a class="btn btn-default btn-sm" href="#" v-if="row.item.type.includes('AdminChangeUserProAccount')">
                Detalii
            </a>

            <!-- <a class="btn btn-default btn-sm"  :href="theUrl" v-if="row.item.type.includes('TimelineAction')">
                Detalii
            </a> -->

      </template>

      <template #cell(read)="row">
        <b-icon-circle-fill v-if="!row.item.read" variant="info" />
        <b-icon-circle v-else variant="info" />
      </template>


    </b-table>

    <!-- <p class="mt-3">Pagina curenta: {{ currentPage }}</p> -->

    <b-pagination
        class="justify-content-center"
        v-model="currentPage"
        :total-rows="totalRows"
        :per-page="perPage"
        aria-controls="notifications-table"
        @change="pageClicked"
    ></b-pagination>

    
    <!-- <p>
      Selected Rows:<br>
      {{ selected }}
    </p> -->
  </div>
</template>

<script>
 import { IconsPlugin } from 'bootstrap-vue'
// import { mapGetters } from 'vuex';
// Import Bootstrap an BootstrapVue CSS files (order is important)
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
// Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)



  export default {
    name: "NotificationsTable",


    components: {
        // BootstrapVue,
        IconsPlugin
    },

    props: {
        notifications: Array
    },

    computed: {
        isSelected: function(){
            return this.selected.length > 0 ? true : false;
        },

        isSelectedUnread: function(){
            let local_items = this.selected.filter(item => {
                if(item.read !== true){
                    return item;
                }
            });
            return local_items.length > 0 ? true : false;
        },

        totalRows: function(){
            return this.totalNotifications;
        },

        getSortBy: function(){
            if(this.sortBy == 'created_time'){
                return 'Dată creare';
            } else if(this.sortBy == 'subject'){
                return 'Subiect';
            } else if (this.sortBy == 'read'){
                return 'Stare';
            }
        }
    },


    data() {
      return {
        blockBtns: false,
        isBusy: false,
        perPage: 25,
        currentPage: 1,
        totalNotifications: 0,
        sortBy: 'created_time',
        sortDesc: true,
        fields: [
        //   { key: 'id', sortable: true },
          { key: 'subject', label: 'Tip notificare', sortable: true },
        //   { key: 'notifiable_id', sortable: true },
          { key: 'created_time', label: 'Dată creare', sortable: true },
          { key: 'read', label: 'Citit', sortable: true },
          { key: 'actions', label: '', sortable: false },
        ],
        items: [
        //   { isActive: true, age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
        //   { isActive: false, age: 21, first_name: 'Larsen', last_name: 'Shaw' },
        //   { isActive: false, age: 89, first_name: 'Geneva', last_name: 'Wilson' },
        //   { isActive: true, age: 38, first_name: 'Jami', last_name: 'Carney' }
        ],
        modes: ['multi', 'single', 'range'],
        selectMode: 'multi',
        selected: []

      }
    },

    methods: {

    pageClicked: async function(page) {
        this.isBusy = true;
        let self = this;
        try {
            this.blockBtns = true;
            await axios.get(`/api/notifications/total_personal_paginated/${page}`).then(response => {
                this.items =  response.data.paginate_result.data;
                this.totalNotifications = response.data.paginate_result.total;

                // console.log('se preia pagina', page);
                // console.log('response este', response.data);


                this.items = this.prepareItems(this.items);


            }).finally(() => {
                this.isBusy = false;
                this.blockBtns = false;
            });
            
            // this.isBusy = false;
            // self.items =  response.items;
        } catch (error) {
            this.isBusy = false;
            this.blockBtns = false;
            // return []
        }
    },

    toggleBusy() {
        this.isBusy = !this.isBusy
    },

    formatElementTimeMethod: function(item){
        return moment(item.created_at).format("lll");
    },

    goToPage(row){
    //   console.log(row);
        window.location = '/notifications/' + row.item.id;
    },

      async deleteSelectedRows(){
          // console.log('stergem selectie', this.selected, this.selected.length);

          let _ids = this.selected.map(item => {
                return item.id;
            });

            // console.log('selected este', this.selected);
            // console.log('_ids este', _ids);

            if(_ids.length > 0){
                this.blockBtns = true;
                await axios.post(`/api/notifications/deleteSelected`, {_ids}).then(async response => {
                    if(response.data.success){
                        
                        // preia notificari
                        await this.pageClicked(1);
                        this.selected = [];
    
                        // console.log('delete selected este', response);
                    } 
                }).finally(() => {
                    this.blockBtns = false;
                });
            }

      },

      onRowSelected(items) {
        this.selected = items
      },

      selectAllRows() {
        this.$refs.selectableTable.selectAllRows();
        // console.log('selecte row:', this.selected.length);
      },

      clearSelected() {
        this.$refs.selectableTable.clearSelected()
      },

      selectThirdRow() {
        // Rows are indexed from 0, so the third row is index 2
        this.$refs.selectableTable.selectRow(2)
      },

      unselectThirdRow() {
        // Rows are indexed from 0, so the third row is index 2
        this.$refs.selectableTable.unselectRow(2)
      },

      async markAsReadSelectedRows() {
        // Rows are indexed from 0, so the third row is index 2
            // console.log('mark as read');

            let _ids = this.selected.map(item => {
                if(item.read == false)
                    return item.id;
            });

            // console.log('selected este', this.selected);
            // console.log('_ids este', _ids);

            if(_ids.length > 0){
                this.blockBtns = true;
                await axios.post(`/api/notifications/markAsReadSelected`, {_ids}).then(async response => {
                    if(response.data.success){
                        
                        // preia notificari
                        await this.pageClicked(1);
                        this.selected = [];
    
                        // console.log('raspunsul este', response);
                    } 
                }).finally(() => {
                    this.blockBtns = false;
                });
            }
      },

      prepareItems: function(items){
        return items.map(item => {
                if(item.type.includes("TimelineAction")){
                    item['subject'] = item.data.subject + ' #' + item.data.timeline_uuid;
                } else if (item.type.includes("TicketNotification")){

                  if(item.data.type.includes('ticket_created')){
                    item['subject'] = 'Tichet nou creat: #' + item.data.ticket_uuid;
                  } else if(item.data.type.includes('ticket_deleted')){
                    item['subject'] = 'Tichet eliminat: #' + item.data.ticket_uuid;
                  } else if(item.data.type.includes('ticket_status_changed')){
                    if(item.data.ticket_status == '0'){
                      item['subject'] = 'Tichet #' + item.data.ticket_uuid + ' marcat ca deschis.';
                    } else {
                      item['subject'] = 'Tichet #' + item.data.ticket_uuid + ' marcat ca închis.';
                    }
                  }
                } else if (item.type.includes('TicketChatActionNotification')){
                    item['subject'] = item.data.subject + ' #' + item.data.ticket_uuid;
                } else if (item.type.includes('DemandBought')){
                    item['subject'] = item.data.subject + ' #' + item.data.demand_uuid;
                } else if (item.type.includes('ReportDemandNotification')){
                    item['subject'] = item.data.subject + ' #' + item.data.demand_id;
                } else if (item.type.includes('AdminChangeUserProAccount')){
                    item['subject'] = `Cont ${item.data.status == 1 ? 'activat' : 'dezactivat'}`;
                } else if (item.type.includes('ResponseForReportedDemandNotification')){
                    item['subject'] = 'Răspuns la reclamația cererii #' + item.data.demand_uuid;
                } else if (item.type.includes('RequestCouponNotification')){
                    item['subject'] = 'Solicitare cupon de la utilizator cu id ' + item.data.user_id;
                }else if (item.type.includes('SendCouponToUserNotification')){
                    item['subject'] = 'Ai primit un cupon ('+ item.data.coupon_code +') de ' + item.data.amount + ' RON.';
                } else if (item.type.includes('ResponseCouponRequestNotification')){
                    if(item.data.type == 'accept') {
                      item['subject'] = 'Solicitare cupon aprobată.';
                    } else {
                      item['subject'] = 'Solicitare cupon respinsă.';
                    }
                }

                item['read'] = (item.read_at !== null || item.read_at == '') ? true : false;
                item['created_time'] = this.formatElementTimeMethod(item);

                return item;
            });
      }
    },

    created(){
        
        this.isBusy = true;
        this.blockBtns = true;

         axios.get("/api/notifications/total_personal").then((response) => {
            // console.log('Suntem in VUEX, table aici', response.data);
            // console.log('response.data este', response.data);
            this.items =  response.data.paginate_result.data;
            this.totalNotifications = response.data.paginate_result.total;


            this.items = this.prepareItems(this.items);
            
           
        }).catch(function(error){
            console.error(error);
        }).finally(() => {
            this.isBusy = false;
            this.blockBtns = false;
        });

        // console.log('notifications ITEMS inainte', this.items);

        // console.log('notifications ITEMS dupa', this.items);
    }

  }
</script>