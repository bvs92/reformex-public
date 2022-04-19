import axios from 'axios';

export default {

    namespaced: true,

    state: {
        
    },

    getters: {
        
    },

    actions: {
      

        download: async function({commit}, id){
            await axios({
                url: `/api/invoices/download/${id}`,
                method: "POST",
                responseType: 'blob'
            }).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'factura-reformex-' + id + '.pdf');
                document.body.appendChild(link);
                link.click();
            }).catch(error => {
                if(error.response.status == 404){
                    // console.log("fisierul nu mai exista.");
                    Vue.$toast.open({
                        message: 'Factura nu există.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                } else if(error.response.status == 403 || error.response.status == 401){
                    // console.log("fisierul nu mai exista.");
                    Vue.$toast.open({
                        message: 'Am întâmpinat erori la descărcare.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
                }
            });


        },

        


    },

    mutations: {
        
    }
}