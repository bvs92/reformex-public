export default {
    namespaced: true,

    state: {
        categories: [],
        loadingStatus: false,
    }, // reset page

    getters: {
        getCategories: function(state){
            return state.categories;
        },
        getLoadingStatus: function(state){
            return state.loadingStatus;
        }
    },

    actions: {
        initCategories: async function({commit, state}){
            await commit('set_loading_status', true);
            await axios.get(`/api/categories/get/all/local`).then(async response => {
                if(response.data.categories){
                    // console.log('categories_explore', response.data.categories);
                    await commit('set_categories', response.data.categories);
                    // if(Array.isArray(response.data.companies)){
                    // } else {
                    //     await commit('set_categories',  [response.data.categories[Object.keys(response.data.categories)[0]]]);
                    // }
                } else {
                    await commit('set_categories', []);
                }
            }).finally(() => {
                commit('set_loading_status', false);
            });
        }
    },

    mutations: {
        set_categories: function(state, _categories){
            state.categories = _categories;
        },
        set_loading_status: function(state, _status){
            state.loadingStatus = _status;
        }
    }
    
}