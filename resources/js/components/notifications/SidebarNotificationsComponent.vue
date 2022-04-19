<template>
<!-- <div>
    <div class="d-flex justify-content-between" id="my-notifications">
        <template>
        <a v-if="!all_selected" @click.prevent="selectAll" class="btn btn-sm btn-default">Selecteaza tot</a>
        <a v-else @click.prevent="deselectAll" class="btn btn-sm btn-default">Deselecteaza tot</a>
        </template>
        <template>
        <a v-if="isBlocked" class="btn btn-sm btn-loading btn-primary disabled">Marcare citit</a>
        <a v-else @click.prevent="markAsReadSelected" class="btn btn-sm btn-primary" :class="{'disabled' : !isAnySelected}">Marcare citit</a>

        <a v-if="isBlocked" class="btn btn-sm btn-danger btn-loading disabled">Elimina selectie</a>
        <a v-else @click.prevent="deleteSelected" class="btn btn-sm btn-danger" :class="{'disabled' : !isAnySelected}">Elimina selectie</a>
        </template>
    </div>
    <div v-for="notification in getNotifications" :key="notification.id">
        <NotificationComponent :notification="notification" @notification:selected="oneSelected" />
    </div>

    <b-pagination style="position: fixed; bottom: 0px; background: white; width: 100%;"
    class="justify-content-center"
      v-model="currentPage"
      :total-rows="getTotal"
      :per-page="getPerPage"
      aria-controls="my-notifications"
      :pills="true"
      :hide-goto-end-buttons="false"
      @change="pageClicked"
    ></b-pagination>


</div> -->

    <b-sidebar 
    id="sidebar-right" title="Notificari" right
    backdrop-variant="dark"
    backdrop
    shadow
    >
      <div class="px-3 py-2"  v-if="getTotal > 0">
        <div class="d-flex justify-content-between" id="my-notifications">
            <template>
            <a v-if="!all_selected" @click.prevent="selectAll" class="btn btn-sm btn-default"><i class="fa fa-check-square-o"></i></a>
            <a v-else @click.prevent="deselectAll" class="btn btn-sm btn-default"><i class="fa fa-square-o"></i></a>
            </template>
            <template>
            <a v-if="isBlocked" class="btn btn-sm btn-loading btn-primary disabled"><i class="fa fa-check-square"></i></a>
            <a v-else @click.prevent="markAsReadSelected" class="btn btn-sm btn-primary" :class="{'disabled' : !isAnySelected}"><i class="fa fa-check-square"></i></a>

            <a v-if="isBlocked" class="btn btn-sm btn-danger btn-loading disabled"><i class="fa fa-trash"></i></a>
            <a v-else @click.prevent="deleteSelected" class="btn btn-sm btn-danger" :class="{'disabled' : !isAnySelected}"><i class="fa fa-trash"></i></a>
            </template>
        </div>
        
        
            <div v-for="notification in getNotifications" :key="notification.id">
                <NotificationComponent :notification="notification" @notification:selected="oneSelected" />
            </div>


            <b-pagination style="position: fixed; bottom: 10px; background: white; width: 100%;" v-if="getTotal > getPerPage"
                class="justify-content-center disabled"
                v-model="currentPage"
                :total-rows="getTotal"
                :per-page="getPerPage"
                aria-controls="my-notifications"
                :pills="true"
                :hide-goto-end-buttons="false"
                @change="pageClicked"
                >
            </b-pagination>
            </div>
            <div class="px-3 py-2" v-else>
                <p class="text-center">Nimic de afișat.</p>
            </div>
        
       
    </b-sidebar>

</template>

<script>
import { mapGetters } from 'vuex';
import NotificationComponent from './notificationComponent';



export default {
    name: "SidebarNotificationsComponent",

    components: {
        NotificationComponent,
    
    },

    data(){
        return {
            all_selected: false,
            isBlocked: false,
            isBlockedDelete: false,

            currentPage: 1,
            isLoading: false
        }
    },

    computed: {
        ...mapGetters({
            // map `this.doneCount` to `this.$store.getters.doneTodosCount`
            getNotifications: 'personalNotifications',
            getCurrentPage: 'getCurrentPage',
            getFrom: 'getFrom',
            getLastPage: 'getLastPage',
            getPerPage: 'getPerPage',
            getTotal: 'getTotal',
            // personalNotifications: 'personalNotifications',
            // getUnreadNotificationsNumber: 'unreadNotifications'
        }),

        // getNotifications: function(){
        //     return this.personalNotifications.map(item => {
        //         item.isSelected = false;
        //         return item;
        //     });
        // }

        isAnySelected: function(){
            let result = this.getNotifications.filter(item => {
                if(item.isSelected == true){
                    return item;
                }
            });

            return result.length > 0 ? true : false;
        }
    },

    methods: {
        pageClicked: function(page){
            // console.log('current page', this.currentPage);
            // console.log('s-a dat click pe pagina', page);

            this.isLoading = true;
            this.$store.dispatch('getNotificationsFromPage', page).finally(() => {
                this.isLoading = false;
            });
            this.currentPage = this.getCurrentPage;
        },


        selectAll(){
            // console.log('selecting all.');
            // console.log(this.getNotifications)
            // this.getNotifications.forEach(item => {
            //     if(!item.isSelected){
            //         item.isSelected = true;
            //     }
            // });
            this.getNotifications.forEach(item => item.isSelected = true);

            this.all_selected = true;
        },

        deselectAll(){
            this.getNotifications.forEach(item => item.isSelected = false);

            this.all_selected = false;
        },

        oneSelected(notification){
            // console.log('one selected', notification);
            this.getNotifications.forEach(item => {
                    if(notification.id == item.id){
                        item.isSelected = !item.isSelected;
                    }
                });

            // console.log('after', this.getNotifications);
        },

        markAsReadSelected(){
            
            // console.log('markAsReadSelected in component');
            let result = this.getNotifications.filter(item => {
                    if(item.isSelected == true){
                        return item;
                    }
            }).map(item => item.id);
            
            // console.log(result);

            if(result.length > 0){
                this.isBlocked = true;
                this.$store.dispatch('markAsReadSelectedNotifications', result).finally(() => {
                    this.isBlocked = false;
                    this.all_selected = false;
                });
            }
        },

        deleteSelected(){
            // console.log('deleteAll - in component');
            let result = this.getNotifications.filter(item => {
                    if(item.isSelected == true){
                        return item;
                    }
            }).map(item => item.id);

            // console.log(result);
            if(result.length > 0){
                this.isBlocked = true;
                this.$store.dispatch('deleteSelectedNotifications', result).finally(() => {this.isBlocked = false; this.all_selected = false;});
            }
        }
    },


    created(){
        // console.log('getNotifications este', this.getNotifications);


        this.currentPage = this.getCurrentPage;
    }

}
</script>