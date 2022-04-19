import axios from 'axios';

export default {
    namespaced: true,

    state: {
        selectedFilesArray: null,
        selectedFilesPreview: new Array,
        isSelected: false
    },


    getters: {

        getSelectedFilesArray: function(state){
            return state.selectedFilesArray;
        },
        getselectedFilesPreview: function(state){
            return state.selectedFilesPreview;
        },
        getAttachmentsPreview: function(state){
            return state.selectedFilesPreview;
        },
        getIsSelected: function(state){
            return state.isSelected;
        }
    },


    actions: {},


    mutations: {

        set_selected_files_array: function(state, _files){
            state.selectedFilesArray = _files;
        },

        set_files_array_preview: function(state, _files){
            state.selectedFilesPreview = _files;
        },

        set_add_files_array_preview: function(state, _file){
            state.selectedFilesPreview = [...state.selectedFilesPreview, _file];
        },

        set_is_selected: function(state, _bool){
            state.isSelected = _bool;
        },

        set_reset_files: function(state){
            state.selectedFilesArray = null;
            state.selectedFilesPreview = [];
            state.isSelected = false;
        },

        remove_item: function(state, name){
           
            state.selectedFilesArray = state.selectedFilesArray.filter(item => {
                if(item.name != name){
                    return item;
                }
            });

          
            state.selectedFilesPreview = state.selectedFilesPreview.filter(item => {
                if(item.name != name){
                    return item;
                }
            });
        }
    }
}