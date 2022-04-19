import axios from 'axios';

export default {
    namespaced: true, 


    state: {
       
    },

    getters: {
     

    },


    actions: {
        deleteCode: async function(context, _uuid){
            await axios.post(`/api/phone/delete/code/${_uuid}`).then(response => {
            });
        },
    },


    mutations: {
        
    }
}