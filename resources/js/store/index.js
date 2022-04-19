import Vue from 'vue';
import Vuex from 'vuex';


// modules
import api_keys from './modules/api_keys.js';
import avatar from './modules/avatar.js';
import user from './modules/user.js';
import users from './modules/users.js';
import companies from './modules/companies.js';
import notifications from './modules/notifications.js';
import timeline_pro from './modules/timeline-pro.js';
import timeline_client from './modules/timeline-client.js';
import messages from './modules/messages.js';
import files_upload from './modules/files-upload.js';
import attachments_upload from './modules/attachments-upload';
import moderators from './modules/moderators.js';
import resolvers from './modules/resolvers.js';
import categories_explore from './modules/categories_explore.js';
import roles from './modules/roles.js';
import banner_payment from './modules/banner_payment.js';
import banners from './modules/banners.js';
import ads_recommend from './modules/ads_recommend.js';
import ad_recommend_payment from './modules/ad_recommend_payment.js';
import periods from './modules/periods.js';
import categories from './modules/categories.js';
import admin_demands from './modules/admin-demands.js';
import pro_demands from './modules/pro-demands.js';
import pro_module from './modules/pro-module.js';
import phone_verification from './modules/phone-verification.js';
import coupons from './modules/coupons.js';
import coupons_requests from './modules/coupons-requests.js';
import credit from './modules/credit.js';
import charges from './modules/charges.js';
import activity from './modules/activity.js';
import reviews from './modules/reviews.js';
import tickets from './modules/tickets.js';
import projects from './modules/projects.js';
import project_categories from './modules/project-categories.js';
import judete from './modules/judete.js';
import public_description from './modules/user_public_profile/public_description.js';
import user_website from './modules/user_public_profile/user_website.js';
import announcements from './modules/announcements.js';
import company_reviews from './modules/company_reviews.js';
import company_questions from './modules/company_questions.js';
import company_card from './modules/company_card.js';
import payments from './modules/payments.js';
import invoices from './modules/invoices.js';
// import files from './modules/files.js';


// Load Vuex
Vue.use(Vuex);


// Create store

export default new Vuex.Store({
    modules: {
        api_keys,
        avatar,
        user,
        notifications,
        timeline_pro,
        timeline_client,
        messages,
        files_upload,
        attachments_upload,
        moderators,
        resolvers,
        categories_explore,
        users,
        roles,
        banners,
        banner_payment,
        ads_recommend,
        ad_recommend_payment,
        periods,
        categories,
        admin_demands,
        pro_demands,
        pro_module,
        phone_verification,
        coupons,
        coupons_requests,
        credit, // user credit
        charges, // user charges
        activity, // user activities - unlock demands
        // files,
        reviews,
        companies,
        tickets,
        projects,
        project_categories,
        judete,
        public_description,
        user_website,
        announcements,
        company_reviews,
        company_questions,
        company_card,
        payments,
        invoices,
    }
});