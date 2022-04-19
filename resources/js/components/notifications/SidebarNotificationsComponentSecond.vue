<template>

    <b-sidebar 
    id="sidebar-right" :title="'Notificări (' + getTotal + ')'" right
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
                <a v-if="isBlocked" class="btn btn-sm btn-loading btn-default disabled"><i class="fa fa-check-square"></i> Marcare citit</a>
                <a v-else @click.prevent="markAsReadSelected" class="btn btn-sm btn-default" :class="{'disabled' : !isAnySelected}"><i class="fa fa-check-square"></i> Marcare citit</a>

                <a v-if="isBlocked" class="btn btn-sm btn-default btn-loading disabled"><i class="fa fa-trash"></i> Elimină</a>
                <a v-else @click.prevent="deleteSelected" class="btn btn-sm btn-default" :class="{'disabled' : !isAnySelected}"><i class="fa fa-trash"></i> Elimină</a>
                </template>
            </div>
            
            
                <div v-for="notification in totalNotifications" :key="notification.id">
                    <NotificationComponent :notification="notification" @notification:selected="oneSelected" />
                </div>

                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button v-if="isLoading" class="btn btn-default btn-loading" disabled="disabled">Încarcă mai multe</button>
                        <button v-else class="btn btn-default" @click.prevent="loadMore" :disabled="isDisabled">Încarcă mai multe</button>
                    </div>
                
                    <div class="col-lg-12 d-flex justify-content-center">
                        <a href="/notificari/personal">Vezi toate notificările</a>
                    </div>
                </div>

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
    name: "SidebarNotificationsComponentSecond",

    components: {
        NotificationComponent,
    
    },

    data(){
        return {
            all_selected: false,
            isBlocked: false,
            isBlockedDelete: false,

            currentPage: 1,
            isLoading: false,

            from: 0,
            limit: 10,

            totalNotifications: null
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
            if(this.totalNotifications){
                let result = this.totalNotifications.filter(item => {
                    if(item.isSelected == true){
                        return item;
                    }
                });
    
                return result.length > 0 ? true : false;
            }
        },

        isDisabled: function(){
            if(this.from > this.getTotal){
                return true;
            } else {
                return false;
            }
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

        loadMore: function(){
            // console.log('click pe load');
            // console.log('from este', this.from);
            // console.log('limit este', this.limit);
            // console.log('s-a dat click pe pagina', page);

            let params = {
                from: this.from,
                limit: this.limit
            };

            this.isLoading = true;
            this.$store.dispatch('loadNotifications', params).then(() => {
                this.totalNotifications = this.totalNotifications && this.totalNotifications.length > 0 ? this.totalNotifications.concat(this.getNotifications) : this.getNotifications;
                this.from += this.limit;
            }).finally(() => {
                this.isLoading = false;
            });
        },


        selectAll(){
            // console.log('selecting all.');
            // console.log(this.getNotifications)
            // this.getNotifications.forEach(item => {
            //     if(!item.isSelected){
            //         item.isSelected = true;
            //     }
            // });
            // this.getNotifications.forEach(item => item.isSelected = true);
            this.totalNotifications.forEach(item => item.isSelected = true);

            this.all_selected = true;
        },

        deselectAll(){
            // this.getNotifications.forEach(item => item.isSelected = false);
            this.totalNotifications.forEach(item => item.isSelected = false);

            this.all_selected = false;
        },

        oneSelected(notification){
            // console.log('one selected', notification);
            // this.getNotifications.forEach(item => {
            //         if(notification.id == item.id){
            //             item.isSelected = !item.isSelected;
            //         }
            //     });
            this.totalNotifications.forEach(item => {
                    if(notification.id == item.id){
                        item.isSelected = !item.isSelected;
                    }
                });

            // console.log('after', this.getNotifications);
        },

        markAsReadSelected(){
            
            // console.log('markAsReadSelected in component');
            let result = this.totalNotifications.filter(item => {
                    if(item.isSelected == true){
                        return item;
                    }
            }).map(item => item.id);
            
            // console.log(result);

            if(result.length > 0){
                this.isBlocked = true;
                this.$store.dispatch('markAsReadSelectedNotifications', result).then(() => {
                    this.totalNotifications = this.totalNotifications.filter(item => {
                        if(!result.includes(item.id)){
                            return item;
                        }
                    });
                }).finally(() => {
                    this.isBlocked = false;
                    this.all_selected = false;
                });
            }
        },

        deleteSelected(){
            // console.log('deleteAll - in component');
            let result = this.totalNotifications.filter(item => {
                    if(item.isSelected == true){
                        return item;
                    }
            }).map(item => item.id);

            // console.log(result);
            if(result.length > 0){
                this.isBlocked = true;
                this.$store.dispatch('deleteSelectedNotifications', result)
                .then(() => {
                    this.totalNotifications = this.totalNotifications.filter(item => {
                        if(!result.includes(item.id)){
                            return item;
                        }
                    });
                })
                .finally(() => {this.isBlocked = false; this.all_selected = false;});
            }
        }
    },


    created(){
        // console.log('getNotifications este', this.getNotifications);
        // this.currentPage = this.getCurrentPage;

       this.loadMore();

    }

}
</script>