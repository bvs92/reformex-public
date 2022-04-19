/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


window.Swal = require('sweetalert2');



// import { info } from 'toastr';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('main-sidebar-notification-component', require('./components/MainSidebarNotificationComponent.vue').default);


// Alerts
Vue.component('alert-company-information', require('./components/alerts/professional/AlertCompanyInformation.vue').default);
Vue.component('alert-pro-information', require('./components/alerts/professional/AlertProInformation.vue').default);


// Advertising
Vue.component('add-new-period-component', require('./components/advertising/periods/AddNewPeriodComponent.vue').default);
Vue.component('list-periods-component', require('./components/advertising/periods/ListPeriodsComponent.vue').default);



//  -- banners
Vue.component('add-new-banner-component', require('./components/advertising/banners/AddNewBannerComponent.vue').default);
Vue.component('edit-banner-component', require('./components/advertising/banners/EditBannerComponent.vue').default);
Vue.component('list-banners-component', require('./components/advertising/banners/ListBannersComponent.vue').default);


// banners all users
Vue.component('add-new-personal-banner-component', require('./components/advertising/banners_all/AddNewPersonalBannerComponent.vue').default);
Vue.component('list-personal-banners-component', require('./components/advertising/banners_all/ListPersonalBannersComponent.vue').default);
Vue.component('show-single-personal-banner-component', require('./components/advertising/banners_all/ShowSinglePersonalBannerComponent.vue').default);
Vue.component('payment-banner-component', require('./components/advertising/banners_all/PaymentBannerComponent.vue').default);


//  -- ads recommend company
Vue.component('add-new-ad-recommend-component', require('./components/advertising/ads/AddNewAdRecommendComponent.vue').default);
Vue.component('edit-ad-recommend-component', require('./components/advertising/ads/EditAdRecommendComponent.vue').default);
Vue.component('list-ads-recommend-component', require('./components/advertising/ads/ListAdsRecommendComponent.vue').default);


// ads recommend company all users
Vue.component('add-new-personal-ad-recommend-component', require('./components/advertising/ads_all/AddNewPersonalAdRecommendComponent.vue').default);
Vue.component('list-personal-ads-recommend-component', require('./components/advertising/ads_all/ListPersonalAdsRecommendComponent.vue').default);
Vue.component('show-single-personal-ad-recommend-component', require('./components/advertising/ads_all/ShowSinglePersonalAdRecommendComponent.vue').default);
Vue.component('payment-ad-recommend-component', require('./components/advertising/ads_all/PaymentAdRecommendComponent.vue').default);




// Announcements
Vue.component('admin-list-announcements-component', require('./components/announcements/AdminListAnnouncementsComponent.vue').default);
Vue.component('admin-create-announcement-component', require('./components/announcements/AdminCreateAnnouncementComponent.vue').default);
Vue.component('list-announcements-component', require('./components/announcements/ListAnnouncementsComponent.vue').default);


// Sidebar
Vue.component('sidebar-component', require('./components/SidebarComponent.vue').default);

// Categories
Vue.component('list-categories-component', require('./components/categories/ListCategoriesComponent.vue').default);
Vue.component('add-new-category-component', require('./components/categories/AddNewCategoryComponent.vue').default);
Vue.component('single-category-component', require('./components/categories/SingleCategoryComponent.vue').default);

// Professional
Vue.component('activate-pro-account-component', require('./components/professional/ActivateProAccountComponent.vue').default);
Vue.component('pro-username-component', require('./components/professional/ProUsernameComponent.vue').default);


// COmpanies - pros
Vue.component('list-pending-pros-registration-component', require('./components/companies/ListPendingProsRegistrationComponent.vue').default);
Vue.component('decide-registration-pro-component', require('./components/companies/DecideRegistrationProComponent.vue').default);
Vue.component('create-company-component', require('./components/companies/CreateCompanyComponent.vue').default);

Vue.component('company-information-component', require('./components/companies/CompanyInformationComponent.vue').default);
Vue.component('categories-company-component', require('./components/companies/CategoriesCompanyComponent.vue').default);
Vue.component('categories-component', require('./components/CategoriesComponent.vue').default);



// Company reviews
Vue.component('admin-list-company-reviews-component', require('./components/company_reviews/AdminListCompanyReviewsComponent.vue').default);


// Company Questions
Vue.component('company-profile-questions', require('./components/company_questions/CompanyProfileQuestions.vue').default);

// Company Card
Vue.component('company-card-component', require('./components/company_card/CompanyCardComponent.vue').default);

// for admin
Vue.component('company-status-component', require('./components/companies/CompanyStatusComponent.vue').default);

Vue.component('test-image-component', require('./components/TestImageComponent.vue').default);




// CompanyReviewComponent
Vue.component('company-review-component', require('./components/companies/CompanyReviewComponent.vue').default);

// Profile
Vue.component('edit-personal-information-component', require('./components/profile/EditPersonalInformationComponent.vue').default);
Vue.component('edit-password-component', require('./components/profile/EditPasswordComponent.vue').default);
Vue.component('edit-social-profiles-component', require('./components/profile/EditSocialProfilesComponent.vue').default);
Vue.component('edit-profile-photo-component', require('./components/profile/EditProfilePhotoComponent.vue').default);
// Vue.component('delete-account-component', require('./components/profile/DeleteAccountComponent.vue').default);
Vue.component('deactivate-personal-account-component', require('./components/profile/DeactivatePersonalAccountComponent.vue').default);
Vue.component('simple-profile-photo-component', require('./components/profile/SimpleProfilePhotoComponent.vue').default);
Vue.component('sidebar-top-profile-photo-component', require('./components/profile/SidebarTopProfilePhotoComponent.vue').default);



Vue.component('list-reviews-component', require('./components/profile/ListReviewsComponent.vue').default);

// Reviews
Vue.component('list-all-reviews-component', require('./components/reviews/ListAllReviewsAdminComponent.vue').default);
Vue.component('list-reported-reviews-component', require('./components/reviews/ListReportedReviewsAdminComponent.vue').default);
Vue.component('admin-list-user-reviews-component', require('./components/reviews/AdminListUserReviewsComponent.vue').default);


Vue.component('logout-component', require('./components/LogoutComponent.vue').default);
Vue.component('ticket-chat-component', require('./components/ticket/TicketChatComponent.vue').default);
Vue.component('ticket-single-response-component', require('./components/ticket/TicketSingleResponseComponent.vue').default);
Vue.component('unread-messages-notifications-component', require('./components/messages/UnreadMessagesNotificationsComponent.vue').default);

Vue.component('timeline-pro-component', require('./components/timeline-pro/TimelineProComponent.vue').default);
Vue.component('timeline-client-component', require('./components/timeline-client/TimelineClientComponent.vue').default);


Vue.component('card-payment-component', require('./components/payments/CardPaymentComponent.vue').default);


Vue.component('company-profile-component', require('./components/profile/CompanyProfileComponent.vue').default);
Vue.component('categories-profile-component', require('./components/profile/CategoriesProfileComponent.vue').default);
Vue.component('profile-places-component', require('./components/profile/ProfilePlacesComponent.vue').default);

Vue.component('automatic-company-information', require('./components/profile/AutomaticCompanyInformation.vue').default);
Vue.component('cif-company-information', require('./components/profile/CifCompanyInformation.vue').default);
Vue.component('inactive-user-company-information', require('./components/profile/InactiveUserCompanyInformation.vue').default);


Vue.component('unread-notifications-header', require('./components/notifications/UnreadNotificationsHeader.vue').default);
// Vue.component('sidebar-notifications-component', require('./components/notifications/SidebarNotificationsComponent.vue').default);
Vue.component('sidebar-notifications-component', require('./components/notifications/SidebarNotificationsComponentSecond.vue').default);

Vue.component('chat-box-component', require('./components/ticket/ChatBoxComponent.vue').default);

Vue.component('simple-chat-box-component', require('./components/ticket/SimpleChatBoxComponent.vue').default);

Vue.component('notifications-table-component', require('./components/notifications/NotificationsTable.vue').default);


// NotificationSettings
Vue.component('notification-settings-component', require('./components/notifications/NotificationSettingsComponent.vue').default);



// Projets
Vue.component('work-projects-personal-component', require('./components/projects/WorkProjectsPersonalComponent.vue').default);
Vue.component('add-new-work-projects-component', require('./components/projects/AddNewWorkProjectsComponent.vue').default);
Vue.component('show-single-project-component', require('./components/projects/ShowSingleProjectComponent.vue').default);
Vue.component('delete-single-project-component', require('./components/projects/DeleteSingleProjectComponent.vue').default);
Vue.component('edit-single-project-component', require('./components/projects/EditSingleProjectComponent.vue').default);

// Projects - admin side
Vue.component('admin-show-single-project-component', require('./components/projects/admin/ShowSingleProjectComponent.vue').default);
Vue.component('admin-delete-single-project-component', require('./components/projects/admin/DeleteSingleProjectComponent.vue').default);
// Vue.component('admin-edit-single-project-component', require('./components/projects/admin/EditSingleProjectComponent.vue').default);


// Project Categories
Vue.component('list-project-categories-component', require('./components/projects-categories/ListProjectCategoriesComponent.vue').default);
Vue.component('list-projects-by-category-component', require('./components/projects-categories/ListProjectsByCategoryComponent.vue').default);
Vue.component('add-new-project-category-component', require('./components/projects-categories/AddNewProjectCategoryComponent.vue').default);
Vue.component('edit-single-project-category-component', require('./components/projects-categories/EditSingleProjectCategoryComponent.vue').default);
Vue.component('delete-single-project-category-component', require('./components/projects-categories/DeleteSingleProjectCategoryComponent.vue').default);



// demands
Vue.component('register-demand-component', require('./components/demands/RegisterDemandComponent.vue').default);
Vue.component('quit-public-demand-component', require('./components/demands/QuitPublicDemandComponent.vue').default);
Vue.component('explore-demands-component', require('./components/demands/ExploreDemandsComponent.vue').default);
Vue.component('explore-demands-final-component', require('./components/demands/ExploreDemandsFinalComponent.vue').default);
Vue.component('explore-demands-algolia', require('./components/demands/ExploreDemandsAlgolia.vue').default);

// Public
Vue.component('public-single-demand-component', require('./components/demands/public/PublicSingleDemandComponent.vue').default);

// * admin
Vue.component('admin-list-demands-component', require('./components/demands/admin/AdminListDemandsComponent.vue').default);
Vue.component('admin-list-reported-demands-component', require('./components/demands/admin/AdminListReportedDemandsComponent.vue').default);
Vue.component('admin-show-demand-component', require('./components/demands/admin/AdminShowDemandComponent.vue').default);

// * pro
Vue.component('show-demand-for-pro-component', require('./components/demands/pro/ShowDemandForProComponent.vue').default);
Vue.component('pro-list-reported-demands-component', require('./components/demands/pro/ProListReportedDemandsComponent.vue').default);
Vue.component('pro-list-unlocked-demands-component', require('./components/demands/pro/ProListUnlockedDemandsComponent.vue').default);

// pro activities
Vue.component('personal-activities-component', require('./components/activity/PersonalActivitiesComponent.vue').default);

// pro charges
Vue.component('personal-charges-component', require('./components/charges/PersonalChargesComponent.vue').default);


// COupons
Vue.component('list-coupons-component', require('./components/coupons/ListCouponsComponent.vue').default);
Vue.component('list-personal-coupons-component', require('./components/coupons/ListPersonalCouponsComponent.vue').default);
Vue.component('list-personal-coupons-requests-component', require('./components/coupons/ListPersonalCouponsRequestsComponent.vue').default);
Vue.component('add-new-coupon-component', require('./components/coupons/AddNewCouponComponent.vue').default);
Vue.component('request-coupon-component', require('./components/coupons/RequestCouponComponent.vue').default);
Vue.component('show-coupon-details-component', require('./components/coupons/ShowCouponDetailsComponent.vue').default);
Vue.component('show-coupon-details-pro-component', require('./components/coupons/ShowCouponDetailsProComponent.vue').default);
Vue.component('admin-list-user-coupons-component', require('./components/coupons/AdminListUserCouponsComponent.vue').default);
Vue.component('admin-list-coupons-requests-component', require('./components/coupons/AdminListCouponsRequestsComponent.vue').default);


// Users
Vue.component('list-users-component', require('./components/users/ListUsersComponent.vue').default);
Vue.component('list-users-pros-component', require('./components/users/ListUsersProsComponent.vue').default);
Vue.component('list-user-demands-component', require('./components/users/ListUserDemandsComponent.vue').default);
Vue.component('list-user-unlocked-demands-component', require('./components/users/ListUserUnlockedDemandsComponent.vue').default);
Vue.component('add-new-user-component', require('./components/users/AddNewUserComponent.vue').default);
Vue.component('user-personal-information-component', require('./components/users/UserPersonalInformationComponent.vue').default);
Vue.component('change-user-password-component', require('./components/users/ChangeUserPasswordComponent.vue').default);
Vue.component('user-roles-component', require('./components/users/UserRolesComponent.vue').default);
Vue.component('user-account-settings-component', require('./components/users/UserAccountSettingsComponent.vue').default);
Vue.component('user-pro-account-component', require('./components/users/UserProAccountComponent.vue').default);
Vue.component('user-company-information-component', require('./components/users/UserCompanyInformationComponent.vue').default);
Vue.component('user-company-simple-information-component', require('./components/users/UserCompanySimpleInformationComponent.vue').default);
Vue.component('admin-list-user-credit-component', require('./components/users/AdminListUserCreditComponent.vue').default);
Vue.component('admin-list-user-activity-component', require('./components/users/AdminListUserActivityComponent.vue').default);

// User Notifications
Vue.component('user-notification-settings-component', require('./components/users/UserNotificationSettingsComponent.vue').default);


// roles
Vue.component('roles-component', require('./components/roles/RolesComponent.vue').default);
Vue.component('single-role-component', require('./components/roles/SingleRoleComponent.vue').default);
Vue.component('add-new-role-component', require('./components/roles/AddNewRoleComponent.vue').default);



// tickets
Vue.component('list-tickets-for-admin-component', require('./components/tickets/ListTicketsForAdminComponent.vue').default);
Vue.component('list-personal-tickets-component', require('./components/tickets/ListPersonalTicketsComponent.vue').default);
Vue.component('create-new-ticket-component', require('./components/tickets/CreateNewTicketComponent.vue').default);


// Map
Vue.component('map-demand-component', require('./components/demands/pro/_modules/MapDemandComponent.vue').default);


// MultiSelect
Vue.component('multi-select-component', require('./components/MultiSelectComponent.vue').default);


// Judete
Vue.component('judete-component', require('./components/JudeteComponent.vue').default);
Vue.component('user-judete-component', require('./components/judete/UserJudeteComponent.vue').default);

// Public Description
Vue.component('user-public-description-component', require('./components/user_public_profile/PublicDescriptionComponent.vue').default);
Vue.component('user-website-component', require('./components/user_public_profile/UserWebsiteComponent.vue').default);


// Google Location
Vue.component('google-location-component', require('./components/location/GoogleLocation.vue').default);


// API KEYS 
Vue.component('api-keys-component', require('./components/api_keys/ApiKeysComponent.vue').default);


// payments
Vue.component('payments-personal-component', require('./components/payments/PaymentsPersonalComponent.vue').default);
Vue.component('all-payments-component', require('./components/payments/AllPaymentsComponent.vue').default);
Vue.component('show-payment-details-component', require('./components/payments/ShowPaymentDetails.vue').default);


// Invoices - admin
Vue.component('invoices-users-component', require('./components/invoices/InvoicesUsersComponent.vue').default);


// invoice information - date facturare
Vue.component('invoice-information-component', require('./components/payments/InvoiceInformationComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


 import { BootstrapVue } from 'bootstrap-vue'

 // Import Bootstrap an BootstrapVue CSS files (order is important)
//  import 'bootstrap/dist/css/bootstrap.css'
//  import 'bootstrap-vue/dist/bootstrap-vue.css'
 
 // Make BootstrapVue available throughout your project
 Vue.use(BootstrapVue)
 // Optionally install the BootstrapVue icon components plugin


 import InstantSearch from 'vue-instantsearch';
Vue.use(InstantSearch);


import UUID from "vue-uuid";
 
Vue.use(UUID);








import Cookie from 'vue-js-cookie';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// leaflet map
import 'leaflet/dist/leaflet.css';

// store
import store from './store';





Vue.use(VueToast);
Vue.use(Cookie);
Vue.use(VueSweetalert2);

// moment JS 
const moment = require('moment')
window.moment = moment;
require('moment/locale/ro')
 
Vue.use(require('vue-moment'), {
    moment
})

import { mapActions } from 'vuex';
import Echo from 'laravel-echo';

Vue.config.productionTip = false;

const app = new Vue({
    store,
    el: '#app',
    data: {
        unreadNotifications: 0,
        personalNotifications: [],
        accessToken: '',
        personalDemands: [],
        clientPersonalTimelines: [],
        proPersonalTimelines: [],
        userPersonalTickets: []
    },


    computed: {},

    methods: {

        refresh_token: function(){
            axios.post();
        },

        ...mapActions(['getNotifications']),
        
    },

    created(){
        // ger the bearer token from the cookie for current auth user.
        this.accessToken = Vue.cookie.get(document.cookie.token_access).token_access ?? null;
        
        // // get unread notification
        axios.defaults.headers.common = {'Authorization': `bearer ${this.accessToken}`}

        this.$store.commit('user/set_access_token', this.accessToken);
        this.$store.dispatch('user/initCurrentUser');
        


        this.$store.dispatch('avatar/initAvatar');


        // Deblocari cereri
        // let _self = this;

        // axios.get("/api/demands/personal").then((response) => {
        //     console.log('ce e asta????', response.data);
        //     _self.personalDemands = response.data.demands;

        // }).catch(function(error){
        // });
        



    }
});

