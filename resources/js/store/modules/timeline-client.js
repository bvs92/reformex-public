import axios from 'axios';

export default {
    namespaced: true,
    state: {
        timeline: null,
        owner: null,
        pro: null,
        review: null,
        winner: null,
        conversation: [],
        prospects: null,
        prospects_on_hold: null,
        prospect_accepted: null,
        active_winner: null
    },

    getters: {
        getTimeline: function(state){
            return state.timeline;
        },

        getConversation: function(state){
            return state.conversation;
        },

        getOwner: function(state){
            return state.owner;
        },

        getPro: function(state){
            return state.pro;
        },

        getReview: function(state){
            return state.review;
        },

        getWinner: function(state){
            return state.winner;
        },

        getActiveWinner: function(state){
            return state.active_winner;
        },

        getTheProspects: function(state){
            console.log('prospects din vuex', state.prospects);
            return state.prospects;
        },

        getProspectsOnHold: function(state){
            console.log('prospects_on_hold din vuex', state.prospects_on_hold);
            return state.prospects_on_hold;
        },

        getProspectAccepted: function(state){
            console.log('prospect_accepted din vuex', state.prospect_accepted);
            return state.prospect_accepted;
        },
    },

    actions: {
        initTimeline: async function({commit}, timeline_uuid){
            console.log('psss, test vuex', timeline_uuid);
            await axios.get(`/api/timelines/get/${timeline_uuid}`).then(async response => {
                if(response.status == 200){
                    await commit('set_timeline', response.data);
                    console.log('1. response timeline, vuex', response.data);
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
                commit('set_owner', response.data.client);
                commit('set_pro', response.data.pro);

                if(response.data.review){
                    commit('set_review', response.data.review);
                }

                if(response.data.winner){
                    commit('set_winner', response.data.winner);
                    console.log('1. winner from axios - vuex', response.data.winner);
                } else {
                    commit('set_winner', null);
                }

                

                console.log('2. CLIENT GET CONVERSATION, vuex', response.data.client, response.data.pro);
                console.log('3.All CONVERSATION, vuex', result);
            });


        },

        getProspects: async function({commit}, timeline){
            // get all the prospects
            await axios.get(`/api/timelines/${timeline.uuid}/prospects/get`).then(response => {
                console.log('prospects din vuex', timeline, response);
                commit('set_prospects', response.data.prospects);
                commit('set_prospects_on_hold', response.data.prospects_on_hold);
                commit('set_prospect_accepted', response.data.prospect_accepted);
            });
            // check if any prospect i IN HOLD => if yes, disable send prospect button.
        },


        removeMessage: async function({commit}, id){
            await axios.delete(`/api/timelines/client_message/${id}/delete`).then(async response => {
                if(response.status == 200){
                    await commit('remove_message', id);
                }
            });
        },


        changeWinner: async function({commit}, {demand, pro}){
            await axios.post(`/api/demands/${demand.uuid}/winners/change/to/pro/${pro.professional_id}`)
            .then(async response => {
                console.log('raspuns winner change -- suntem chiar in functie');
                console.log(response.data);
                // await this.getTimeline();
                // await this.get_conversation();

                // if(response.data.error){
                // } else if(response.data.winner){
                //     // console.log(response.data.winner);
                //     this.get_conversation();
                // }
            })
            .catch(error => {
                console.error(error);
            });
        },


        confirmWinner: async function({commit}, timeline){
            await axios.post(`/api/winners/${timeline.uuid}/confirm`)
            .then(response => {
                // if(response.data.winner){
                // // console.log(response.data.winner);
                // this.get_conversation();
            })
            .catch(error => {
                console.error(error);
            });
        },

        cancelWinner: async function({commit}, timeline){
            await axios.post(`/api/winners/${timeline.uuid}/cancel`)
            .then(async response => {
                console.log('cancel winner - vuex');
            })
            .catch(error => {
                console.error(error);
            });
        },

        initActiveWinner: async function({commit}, timeline){
            await axios.get(`/api/demands/${timeline.demand_id}/winner/active`)
            .then(response => {
                commit('set_active_winner', response.data.active_winner);
                console.log('active winner', response.data.active_winner);
            });
        }
    },

    mutations: {
        set_timeline: function(state, timeline){
            state.timeline = timeline;
        },

        set_owner: function(state, owner){
            state.owner = owner;
        },

        set_pro: function(state, pro){
            state.pro = pro;
        },

        set_winner: function(state, winner){
            state.winner = winner;
        },

        set_active_winner: function(state, _winner){
            state.active_winner = _winner ? _winner : null;
        },

        set_review: function(state, review){
            state.review = review;
        },

        set_conversation: function(state, conversation){
            state.conversation = conversation;
        },

        push_response: function(state, _response){
            state.conversation.push(_response);
        },

        remove_message: async function(state, _id){
            state.conversation = await state.conversation.filter(item => {
                if(item.id !== _id)
                    return item;
            });
        },

        set_prospects: function(state, _prospects){
            state.prospects = _prospects;
        },

        set_prospects_on_hold: function(state, _prospects){
            state.prospects_on_hold = _prospects;
        },

        set_prospect_accepted: function(state, _prospects){
            state.prospect_accepted = _prospects;
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
    }
}