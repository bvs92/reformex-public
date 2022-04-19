import axios from 'axios';


export default {

    state: {
        timeline: null,
        client: null,
        pro: null,
        conversation: []
    },

    getters: {
        getTimeline: function(state){
            return state.timeline;
        },

        getConversation: function(state){
            return state.conversation;
        },

        getClient: function(state){
            return state.client;
        },

        getPro: function(state){
            return state.pro;
        }
    },

    actions: {
        initTimeline: async function({commit}, timeline_uuid){
            console.log('psss, test vuex', timeline_uuid);
            await axios.get(`/api/timelines/get/${timeline_uuid}`).then(async response => {
                if(response.status == 200){
                    await commit('set_timeline', response.data);
                    console.log('response timeline, vuex', response.data);
                }
            });
        },

        initConversation: async function({commit}, timeline) {
            await axios.get(`/api/timelines/conversation/${timeline.uuid}`).then(response => {
                

                let result = Object.values(response.data.conversation); // from object to array
                result.sort(function(a,b){
                    if(a.created_at > b.created_at)
                        return 1;
                    else
                        return -1;
                });

                commit('set_conversation', result);
                commit('set_client', response.data.client);
                commit('set_pro', response.data.pro);
                console.log('response client & pro, vuex', response.data.client, response.data.pro);
                console.log('cum arata conversatia?', response.data.conversation);
            });
        },

        removeQuote: async function({commit}, id){
            await axios.delete(`/api/timelines/quote/${id}/delete`).then(async response => {
                if(response.status == 200){
                    await commit('remove_quote', id);
                }
            });
        },

        acceptProposal:async function({commit}, _id){
            await axios.post(`/api/prospects/${_id}/accept`).then(response => {
                if(response.data.response){
                    commit('accept_proposal', _id);
                } else if(response.data.error){
                    console.log(response.data.error);
                }
            }).catch(error => {
                console.error(error);
            });
        },

        refuseProposal: async function({commit}, _id){
            await axios.post(`/api/prospects/${_id}/refuse`).then(response => {
                if(response.data.response){
                    commit('refuse_proposal', _id);
                } else if(response.data.error){
                    console.log(response.data.error);
                }
            }).catch(error => {
                console.error(error);
            });
        }


    },

    mutations: {
        set_timeline: function(state, timeline){
            state.timeline = timeline;
        },

        set_client: function(state, client){
            state.client = client;
        },

        set_pro: function(state, pro){
            state.pro = pro;
        },

        set_conversation: function(state, conversation){
            state.conversation = conversation;
        },

        push_response: function(state, _response){
            console.log('suntem in PUSH');
            console.log('_response', _response);
            console.log(state.conversation);
            state.conversation.push(_response);
        },

        remove_quote: function(state, _id){
            state.conversation = state.conversation.filter(item => {
                if(item.id !== _id)
                    return item;
            });
        },

        tweak_conversation_file: function(state, _id){
            state.conversation = state.conversation.map(item => {
                if(item.id == _id){
                    // item.files = [];
                    item.id = '';   
                }
                return item;
            });
        },


        accept_proposal: function(state, _id){
            state.conversation = state.conversation.map(item => {
                if(item.id == _id){
                    item.status = '1';
                    return item;
                }
            });
        },

        refuse_proposal: async function(state, _id){
            state.conversation = await state.conversation.map(item => {
                if(item.id == _id){
                    item.status = '2';
                    return item;
                }
            });
        },

    }
}