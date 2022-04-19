import axios from 'axios';

export default {
    namespaced: true,

    state: {
        personal_reviews: null,
        all_reviews: null,
        all_user_reviews: null,
        reported_reviews: null,

        current_page: null,
        from: null,
        last_page: null,
        per_page: null,
        total: null
    },

    getters: {
        getAllReviews: function(state){
            return state.all_reviews;
        },

        getAllUserReviews: function(state){
            return state.all_user_reviews;
        },

        getReportedReviews: function(state){
            return state.reported_reviews;
        },

        getPersonalReviews: function(state){
            return state.personal_reviews;
        },

        getCurrentPage: function(state){
            return state.current_page;
        },
        getFrom: function(state){
            return state.from;
        },
        getLastPage: function(state){
            return state.last_page;
        },
        getPerPage: function(state){
            return state.per_page;
        },
        getTotal: function(state){
            return state.total;
        },
    },

    actions: {
        initPersonalReviews: async function({commit}){
            await axios.get(`/api/reviews/get/personal`).then(async response => {
               
                await commit('set_personal_reviews', response.data.reviews);
            });
        },

        async getPersonalReviewsFromPage({commit}, _page) {
            await axios.get("/api/reviews/get/personal?page=" + _page).then(async (response) => {
               
                let reviews = response.data.reviews;
                
                await commit('set_personal_reviews', reviews.data);

                await commit('set_current_page', reviews.current_page);
                await commit('set_from', reviews.from);
                await commit('set_last_page', reviews.last_page);
                await commit('set_per_page', reviews.per_page);
                await commit('set_total', reviews.total);
            }).catch(function(error){
                // console.error(error);
            });
        },

        async getAllReviewsFromPage({commit}, _page) {
            await axios.get("/api/reviews/get/all?page=" + _page).then(async (response) => {
               
                let reviews = response.data.reviews;
                
                await commit('set_all_reviews', reviews.data);

                await commit('set_current_page', reviews.current_page);
                await commit('set_from', reviews.from);
                await commit('set_last_page', reviews.last_page);
                await commit('set_per_page', reviews.per_page);
                await commit('set_total', reviews.total);
            });
        },

        async getAllUserReviewsFromPage({commit}, _id, _page) {
            await axios.get(`/api/reviews/user/${_id}/get/all?page=${_page}`).then(async (response) => {
               
                let reviews = response.data.reviews;
                
                await commit('set_all_user_reviews', reviews.data);

                await commit('set_current_page', reviews.current_page);
                await commit('set_from', reviews.from);
                await commit('set_last_page', reviews.last_page);
                await commit('set_per_page', reviews.per_page);
                await commit('set_total', reviews.total);
            });
        },

        async getReportedReviewsFromPage({commit}, _page) {
            await axios.get("/api/reviews/get/reported?page=" + _page).then(async response => {
                console.log('reported reviews', response.data);
               
                let reviews = response.data.reviews;
                
                await commit('set_reported_reviews', reviews.data);

                await commit('set_current_page', reviews.current_page);
                await commit('set_from', reviews.from);
                await commit('set_last_page', reviews.last_page);
                await commit('set_per_page', reviews.per_page);
                await commit('set_total', reviews.total);
            });
        },

        async deleteReview({commit, state}, _id){
            await axios.post(`/api/reviews/delete/${_id}`).then(async response => {
                if(response.data.success){
                    let reviews = state.all_reviews.filter((item) => {
                        if(item.id !== _id){
                            return item;
                        }
                    });

                    await commit('set_all_reviews', reviews);
                }
            });
        },

        async deleteReportedReview({commit, state}, _id){
            await axios.post(`/api/reviews/delete/${_id}`).then(async response => {
                if(response.data.success){
                    let reviews = state.reported_reviews.filter((item) => {
                        if(item.id !== _id){
                            return item;
                        }
                    });

                    await commit('set_reported_reviews', reviews);
                }
            });
        },

        async deleteUserReview({commit, state}, _id){
            await axios.post(`/api/reviews/delete/${_id}`).then(async response => {
                if(response.data.success){
                    let reviews = state.all_user_reviews.filter((item) => {
                        if(item.id !== _id){
                            return item;
                        }
                    });

                    await commit('set_all_user_reviews', reviews);
                }
            });
        },
    },

    mutations: {
        set_all_reviews: function(state, _reviews){
            state.all_reviews = _reviews;
        },

        set_all_user_reviews: function(state, _reviews){
            state.all_user_reviews = _reviews;
        },

        set_reported_reviews: function(state, _reviews){
            state.reported_reviews = _reviews;
        },

        set_personal_reviews: function(state, _reviews){
            state.personal_reviews = _reviews;
        },

        set_current_page: function(state, _current_page){
            state.current_page = _current_page;
        },

        set_from: function(state, _from){
            state.from = _from;
        },

        set_per_page: function(state, _per_page){
            state.per_page = _per_page;
        },

        set_last_page: function(state, _last_page){
            state.last_page = _last_page;
        },
        set_total: function(state, _total){
            state.total = _total;
        },
    }
}