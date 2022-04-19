import axios from 'axios';

export default {
    namespaced: true,

    state: {
        attached_files: []
    },

    getters: {
        getAttachedFiles: function(state, {_id, type}){
            console.log('wtf, se executa', state.attached_files.flat());
            return state.attached_files.flat();
            // if(type == 'quotes'){
            //     return state.attached_files.map(item => {
            //         if(item.quote_id == _id){
            //             return item;
            //         }
            //     });
            // } else {
            //     return state.attached_files.map(item => {
            //         if(item.client_message_id == _id){
            //             return item;
            //         }  
            //     });
            // }
         
        }
    },
    
    actions: {
        initAttachedFiles: async function({commit}, quote){
            await axios.get(`/api/get/files/${quote.id}`).then(response => {
                commit('set_attached_files', response.data);
            });
        },

        deleteQuoteFile: async function({commit}, file){
            await axios.post(`/api/files/${file.id}/quote/delete`).then(async response => {
                if(response.data.result == 'ok'){
                    await commit('delete_quote_file', file.id);
                }
            });
        },

        getAttachedFilesMessage: async function({state}, {_id, type}){
            if(type == 'quotes'){
                return state.attached_files.map(item => {
                    if(item.quote_id == _id){
                        return item;
                    }
                });
            } else {
                return state.attached_files.map(item => {
                    if(item.client_message_id == _id){
                        return item;
                    }  
                });
            }
        },
    },
    
    mutations: {
        set_attached_files: function(state, _files){
            state.attached_files =  _files;
            // state.attached_files = [...state.attached_files, _files];
        },

        delete_quote_file: function(state, _id){
            state.attached_files = state.attached_files.filter(item => {
                if(item.id != _id){
                    return item;
                }
            });
        }
    }
}