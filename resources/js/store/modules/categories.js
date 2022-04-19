import axios from 'axios';

export default {

    namespaced: true,

    state: {
        categories: null,
        total: 0,
        errors: []
    },

    getters: {
        getCategories: function(state){
            return state.categories;
        },
        getTotalCategories: function(state){
            return state.total;
        },

        getErrors: function(state){
            return state.errors;
        }
    },

    actions: {
        initCategories: async function({commit}){
            await axios.get('/api/categories/list/get/all').then(async response => {

                if(response.data.categories){
                    let categories = Object.values(response.data.categories);

                    await commit('set_categories', categories);
                    await commit('set_total_categories', response.data.total);
                } else if(response.data.errors){
                    await commit('set_errors', Object.values(response.data.errors));
                }
            });
        }
    },

    mutations: {
        set_categories: function(state, _categories){
            state.categories = _categories;
        },
        insert_category: function(state, _category){
            state.categories = [_category, ...state.categories];
        },
        set_total_categories: function(state, _total){
            state.total = _total;
        },
        set_errors: function(state, _errors){
            state.errors = _errors;
        }
    }
}