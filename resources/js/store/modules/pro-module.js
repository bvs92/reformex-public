import axios from 'axios';


export default {

    namespaced: true,

    state: {
        selected_categories: [],
        existing_location: null
    },

    getters: {
        getSelectedCategories: function(state){
            return state.selected_categories;
        },

        getExistingLocation: function(state){
            return state.existing_location;
        }
    },

    actions: {
       initSelectedCategories: async function({commit}){
           // get selected categories
           await axios.get(`/api/pro/categories/selected`).then(async response => {
               if(response.data.success){
                   await commit('set_selected_categories', response.data.categories);
               }
           });
       },

       initExistingLocation: async function({commit}){
        // get selected categories
        await axios.get(`/api/pro/existing/location`).then(async response => {
            if(response.data.success){
                await commit('set_existing_location', response.data.location);
            }
        });
    },

    deleteCategory: function({commit, state}, category_id){
        axios.post(`/api/categories/user/delete/`, {
            id: category_id
        }).then(response => {
            if(response.data.success){
                let new_categories = state.selected_categories.filter(element => {
                    if(element.id != category_id){
                        return element;
                    }
                });
                commit('set_selected_categories', new_categories);
            }
        });

    }

    


    },

    mutations: {
        set_selected_categories: function(state, _categories){
            state.selected_categories = _categories;
        },
        set_existing_location: function(state, _location){
            state.existing_location = _location;
        },
    }
}