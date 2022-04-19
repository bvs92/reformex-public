<template>
    <div class="col-md-12" :key="total_timeline" v-if="!loading_timeline">

    
        <div class="d-flex mb-4" v-if="timeline">
            <div v-if="timeline.status == 1" class="col">
                <p class="text-right">Conversatia este inactiva.</p>
            </div>
            <div class="btn-list col d-flex justify-content-end">
                <a class="btn btn-gray btn-sm text-white" @click.once="changeStatusConversation" v-if="timeline.status == 0">
                    <i class="ti-na"></i> Opreste conversatia
                </a>
                <a class="btn btn-success btn-sm text-white" @click="changeStatusConversation" v-else-if="timeline.status == 1">
                    <i class="ti-signal"></i> Permite conversatia
                </a>
                <a class="btn btn-danger btn-sm text-white" @click="deleteConversation">
                    <i class="ti-trash"></i> Elimina conversatie
                </a>           
            </div>
        </div>


        <ul class="cbp_tmtimeline" v-if="timeline">
            <li>
                <time-ago-component :element="timeline"></time-ago-component>
                <div class="cbp_tmicon bg-info"><i class="ti ti-user"></i></div>
                <DemandComponent :demand="demand" :owner="owner" :accessTokenMap="the_accessTokenMap" />
            </li>

            <template v-if="get_conversations">
                <timeline-single-component 
                    :the_current_user="the_current_user" 
                    v-for="(conversation, index) in get_conversations" 
                    :key="conversation.id + '-' + index" 
                    :the_key="key_conversation + '-' + conversation.id" 
                    ref="conversationItem"
                    :the_conversation="conversation" 
                    :the_owner="getOwner" 
                    :the_pro="getPro" 
                    :the_demand_uuid="demand ? demand.uuid : null" 
                    :timeline="incoming_timeline"
                    >
                </timeline-single-component>
            </template>

            <template v-if="getReview == null && getWinner">
            <li v-if="getWinner.professional_id == getPro.professional_id" class="col-lg-9 offset-lg-3 px-0">
                <div class="empty">
                    <create-review :professional="getPro" :timeline="timeline" @review:saved="review_saved"></create-review>
                </div>
            </li>
            </template>

        

            

            <li class="" v-if="getProspectAccepted && !getWinner">
                <!-- <time-ago-component :element="review"></time-ago-component> -->
                
                <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                <ConfirmWinnerComponent :owner="getOwner" :pro="getPro" :prospect_accepted="getProspectAccepted" :demand="demand" :timeline="timeline" />
            </li>
            
          

        </ul>
        <hr>
        <template v-if="incoming_timeline.status == 0">
            <div class="d-flex flex-row my-4" v-if="getProspectsOnHold < 1">
                <span class="py-2">Actiuni: </span>
                <template v-if="getProspectAccepted == null">
                     <a @click="sendProspect" class="btn btn-success text-white" :disabled="isDisabled">Propune intelegere</a>
                </template>
                <template v-if="getWinner && getWinner.professional_id == getPro.professional_id" >
                    <a @click="cancelWinner" class="btn btn-danger text-white" :disabled="isDisabled">Anuleaza castigatorul curent</a>
                </template>
            </div>

        </template>

        <template v-if="getActiveWinner">
            <ActiveWinnerComponent :demand="the_demand" :active_winner="getActiveWinner" :pro="getPro" :timeline="timeline" />
        </template>



        <timeline-create-message-component 
        :the_pro="getPro" 
        :the_timeline="timeline" 
        >
        </timeline-create-message-component>
    </div>

    <div class="col-lg-12 col-md-12 d-flex flex-column" v-else-if="loading_timeline">
        <moon-loader size="30px" color="blue" class="moonLoader"></moon-loader>
        <p class="text-center">Preluam datele din conversatie. Va rugam asteptati...</p>
    </div>
</template>


<script>
import TimelineCreateMessageComponent from "./TimelineCreateMessageComponent";
import TimelineSingleComponent from "./TimelineSingleComponent";
import CreateReview from "./CreateReview";
import TimeAgoComponent from "../TimeAgoComponent";
import MapDemandComponent from "../MapDemandComponent";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';

import DemandComponent from "./types/DemandComponent";
import ActiveWinnerComponent from "./types/ActiveWinnerComponent";
import ConfirmWinnerComponent from "./types/ConfirmWinnerComponent";



import { mapGetters } from 'vuex';

export default {
    name: "TimelineClientComponent",

    components:{
        "timeline-create-message-component": TimelineCreateMessageComponent,
        "timeline-single-component": TimelineSingleComponent,
        "create-review": CreateReview,
        "time-ago-component": TimeAgoComponent,
        "map-demand-component": MapDemandComponent,
        MoonLoader,
        DemandComponent,
        ActiveWinnerComponent,
        ConfirmWinnerComponent
    },

    props: {
        incoming_timeline: Object,
        the_current_user: Object,
        the_demand: Object,
        the_accessTokenMap: String
    },

    data(){
        return {
            loading_timeline: false,
            isDisabled: false,
            timeline: null,
            demand: null,
            conversations: null,
            owner: null,
            pro: null,
            prospects: null,
            prospects_on_hold_number: 1,
            prospect_accepted: null,

            review: null,
            winner: null,
            active_winner: null,

            key_conversation: 'conversations_get',
            total_timeline: 'total_timeline'
        }
    },

    computed:{
        ...mapGetters('timeline_client', ['getConversation', 'getOwner', 'getPro', 'getReview', 'getWinner', 'getTheProspects', 'getProspectsOnHold', 'getProspectAccepted', 'getActiveWinner']),

        get_conversations: function(){
            // return this.conversations;
            console.log('suntem in computed, listam conversatia.', this.getConversation);
            return this.getConversation;
        },

        get_conversation_key: function(){
            return this.key_conversation;
        },

        
    },

    methods:{

        initialize_data: async function(){
            this.loading_timeline = true;
            await this.get_conversation()
            .then(response => {
                // await this.get_active_winner();
                // this.loading_timeline = false;
            }).catch(error => {
                // this.loading_timeline = false;
            }).finally(() => {
                this.loading_timeline = false;
            });
        },

        the_conversation(){
            return this.conversations;
        },

        // confirmWinner() {
        // // Use sweetalert2
        //     this.$swal({
        //         title: 'Desemnare castigator final cerere!',
        //         text: "Confirmarea presupune incheierea unei intelegeri cu acest profesionist si dezactivarea cererii pentru ceilalti profesionisti implicati.",
        //         icon: 'success',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Da, confirm.',
        //         cancelButtonText: 'Nu, anuleaza'
        //     }).
        //     then(async (result) => {
        //         if (result.isConfirmed) {
        //             this.$swal(
        //             'Confirmat!',
        //             this.getPro.complete_name + ' este castigatorul final al cererii #' + this.the_demand.uuid,
        //             'success'
        //             );

        //             // executa codul salvare castigator.
        //             await axios.post(`/api/winners/${this.timeline.uuid}/confirm`)
        //             .then(response => {
        //                 if(response.data.error){
        //                     console.log('eroare');
        //                     console.log(response.data.error);
        //                 } else if(response.data.winner){
        //                     // console.log(response.data.winner);
        //                     this.get_conversation();
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error(error);
        //             });
        //         }
        //     });
        // },


        cancelWinner() {
            let self = this;
            this.isDisabled = true;
        // Use sweetalert2
            this.$swal({
                title: 'Anuleaza aceasta intelegerea!',
                text: "Sunteti sigur ca doriti anularea acestei intelegeri? Confirmarea presupune anularea oricarei intelegeri cu acest profesionist.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    // this.winner = null;

                    this.$swal(
                    'Confirmat!',
                    'A fost anulata orice intelegere dintre dumneavoastra si profesionistul ' + this.getPro.complete_name,
                    'success'
                    );

                    // executa codul de anulare castigator
                    // console.log('doar testam');
                    
                    await this.$store.dispatch('timeline_client/cancelWinner', this.timeline).then(async response => {
                        await this.$store.dispatch('timeline_client/initConversation', this.timeline);
                        await this.$store.dispatch('timeline_client/getProspects', this.timeline);
                    });

                    // await axios.post(`/api/winners/${this.timeline.uuid}/cancel`)
                    // .then(async response => {
                    //     // self.key_conversation = 'news';
                    //     // console.log(response.data);
                    //     // await self.getTimeline();
                    //     await self.getProspects();
                    //     await self.get_conversation().then(response => {
                    //         console.log('conversations from state', self.conversations);
                    //     });
                        

                    //     // console.log('winner from state', self.winner);
                    //     // this.conversations = this.the_conversation()
                    //     // this.$forceUpdate();
                    //     // console.log('refs', this.$refs.conversationItem);
                    //     // console.log('vnode', this.$vnode.key = 'changing');
                    // })
                    // .catch(error => {
                    //     console.error(error);
                    // });
                }

                this.isDisabled = false;
            });
        },

        // changeWinner(){
        //     this.$swal({
        //         title: 'Schimbare castigator cerere',
        //         text: "Confirmarea presupune anularea intelegerii cu "+ this.active_winner.company_name +" si stabilirea unei noi intelegeri cu "+ this.pro.complete_name +", dar si dezactivarea cererii pentru ceilalti profesionisti implicati.",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Da, confirm.',
        //         cancelButtonText: 'Nu, anuleaza'
        //     }).
        //     then(async (result) => {
        //         if (result.isConfirmed) {
        //             this.$swal(
        //             'Confirmat!',
        //             this.pro.complete_name + ' este noul castigator al cererii #' + this.demand.uuid,
        //             'success'
        //             );

        //             console.log('okkk  am schimabt castigatorul cu ', this.pro.complete_name);

        //             // executa codul salvare castigator.

        //             await axios.post(`/api/demands/${this.demand.uuid}/winners/change/to/pro/${this.pro.professional_id}`)
        //             .then(async response => {
        //                 console.log('raspuns winner change');
        //                 console.log(response.data);
        //                 await this.getTimeline();
        //                 await this.get_conversation();

        //                 // if(response.data.error){
        //                 // } else if(response.data.winner){
        //                 //     // console.log(response.data.winner);
        //                 //     this.get_conversation();
        //                 // }
        //             })
        //             .catch(error => {
        //                 console.error(error);
        //             });
        //         }
        //     });
        // },

        get_active_winner: async function(){
            // await axios.get(`/api/demands/${this.timeline.demand_id}/winner/active`)
            // .then(response => {
            //     this.active_winner = response.data.active_winner ?? null;
            //     console.log('active winner', this.active_winner);
            // })
            // .catch(error => {
            //     // this.loading_timeline = false;
            // });

            await this.$store.dispatch('timeline_client/initActiveWinner', this.timeline);
        },

        get_conversation: async function() {
            
            // await axios.get(`/api/timelines/conversation/${this.timeline.uuid}`)
            // .then(response => {
            //     this.owner = response.data.client;
            //     this.pro = response.data.pro;
            //     console.log('PRO is ', this.pro);

            //     if(response.data.review){
            //         this.review = response.data.review;
            //     }

            //     if(response.data.winner){
            //         this.winner = response.data.winner;
            //         console.log('winner from axios', response.data.winner);
            //     } else {
            //         this.winner = null;
            //     }

                

            //     let result = Object.values(response.data.conversation); // from object to array
            //     result.sort(function(a,b){
            //         if(a.created_at > b.created_at)
            //             return 1;
            //         else
            //             return -1;
            //     });

            //     this.conversations = result;
                

            //     console.log('conversation aiciii, yes', result);
            // }).then(async response => {
            //     await this.get_active_winner();
            // })
            // .catch(error => {
            //     // this.loading_timeline = false;
            // });


            await this.$store.dispatch('timeline_client/initConversation', this.timeline)
            .then(async response => {
                await this.get_active_winner();
                await this.getProspects();
            });
        },


        get_conversation_without_demand: function() {
            axios.get(`/api/timelines/conversation-no-demand/${this.timeline.uuid}`).then(response => {
                this.owner = response.data.client;
                this.pro = response.data.pro;

                if(response.data.review){
                    this.review = response.data.review;
                }

                if(response.data.winners){
                    this.winners = response.data.winners;
                }

                let result = Object.values(response.data.conversation); // from object to array
                result.sort(function(a,b){
                    if(a.created_at > b.created_at)
                        return 1;
                    else
                        return -1;
                });

                this.conversations = result;
            });
        },



        // remove_message: function(id){
        //     axios.delete(`/api/timelines/client_message/${id}/delete`).then(response => {
        //         if(response){
        //             this.conversations = this.conversations.filter(item => {
        //                 if(item.id !== id)
        //                     return item;
        //             });
        //         }
        //     });
        // },


        // Review

        // getReview: function(){
        //     axios.get(`/api/demands/${this.demand.id}/review`).then(response => {
        //         if(response.data.review){
        //             this.review = response.data.review;
        //         }
        //     });
        // },

        review_saved: function(review){
            this.get_conversation();
        },


        getTimeline: async function(){
            await axios.get(`/api/timelines/get/${this.timeline.uuid}`).then(response => {
                if(response.data){
                    this.timeline = response.data;
                }
            });
        },


        // Actions

        getProspects: async function(){

            await this.$store.dispatch('timeline_client/getProspects', this.timeline);
            // check if any prospect i IN HOLD => if yes, disable send prospect button.
        },

        sendProspect: async function(e){
            e.preventDefault();
            this.isDisabled = true;
            await axios.post(`/api/timelines/${this.timeline.uuid}/prospects/send`).then(async response => {
                // console.log(response.data);
                if(response.data.success){
                    await this.get_conversation();
                    await this.getProspects();
                    this.isDisabled = false;
                }
            }).catch(error => {this.isDisabled = false;});
        },

        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },


        changeStatusConversation: async function(){
            console.log('turn off - conversatie numar', this.timeline.uuid);
            await axios.post(`/api/timelines/${this.timeline.uuid}/change/status`)
            .then(async response => {
                if(response.data.success) {
                    await this.getTimeline();
                    await this.initialize_data();
                    // await this.get_conversation();
                    // await this.getProspects();

                    let _rsp = this.timeline.status == '0' ? 'activa' : 'inactiva';
                    Vue.$toast.open({
                        message: 'Ati marcat conversatia ca ' + _rsp,
                        type: 'info',
                        duration: 9000
                    });
                }
            })
            .catch(error => {
                console.log('Turn off conversation', error);
            });
        },

        deleteConversation: async function(){
            console.log('sterge - conversatie numar', this.timeline.uuid);

            this.$swal({
                title: 'Elimina conversatie',
                text: "Sunteti sigur ca doriti eliminarea acestei conversatii?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Da, confirm.',
                cancelButtonText: 'Nu, renunta'
            }).then(async (result) => {
                if (result.isConfirmed) {

                    this.winner = null;

                    this.$swal(
                    'Confirmat!',
                    'Conversatia a fost eliminata.',
                    'success'
                    );

                    // executa codul de anulare castigator
                    await axios.post(`/api/timelines/${this.timeline.uuid}/delete/client`)
                    .then(async response => {
                        if(response.data.success) {
                            await this.getTimeline();
                            await this.initialize_data();
                            // await this.get_conversation();
                            // await this.getProspects();
                        }
                    })
                    .catch(error => {
                        console.log('delete conversation, client', error);
                    });
                }

                this.isDisabled = false;
            });

            
        },

    },

    created(){

        // console.log('WTFF ssssss -----------');
        // console.log(this.incoming_timeline);

        this.timeline = this.incoming_timeline;
        this.demand = this.incoming_timeline.demand;
        this.demand = this.the_demand;


        if(this.demand){
            console.log('WTFF 1 -----------');
            // this.get_conversation();
            this.initialize_data();
        } else {
            console.log('WTFF 2 -----------');
            this.get_conversation_without_demand();
        }

        this.getProspects();


        // console.log('getProspectsOnHold', this.getProspectsOnHold);
        // console.log('getProspectAccepted', this.getProspectAccepted);


        


        Echo.private('pro-timelines-channel.' + this.timeline.id)
        // Listen - pentru evenimentele lansate de catre PRO -> Client (Accept / refuse proposal). Sterge ClientProposalEvent de aici.
        
        .listen('TimelineMessageProToClientEvent', (timeline) => {
            // listen for messages from PROFESSIONAL of current timeline.
            if(this.demand){
                this.get_conversation();
            } else {
                this.get_conversation_without_demand();
            }

            
        })
        
        .listen('TimelineMessageDeletedProEvent', (timeline) => {
            // listen for messages from PROFESSIONAL of current timeline.
            if(this.demand){
                this.get_conversation();
            } else {
                this.get_conversation_without_demand();
            }
        })

        .listen('TimelineQuoteFileDeletedEvent', (timeline) => {
            // listen for messages from PROFESSIONAL of current timeline.
            console.log('TimelineQuoteFileDeletedEvent -------- ');
            if(this.demand){
                this.get_conversation();
            } else {
                this.get_conversation_without_demand();
            }
        })
        
        .listen('ProResponseForClientProposalEvent', (timeline) => {
            // listen for messages from PROFESSIONAL of current timeline.
            this.getConversation.forEach(item => {
                if(item.type == 'Prospect'){
                    if(item.id == timeline.prospect_id){
                        item.status = timeline.prospect_status;
                    }
                }
            });

            this.getProspects();

            // console.log(this.conversations)
            
        })
        
        .listen('QuoteFileDeletedEvent', (returned_data) => {
            console.log('de ce nu merge?');
            console.log('returned_data', returned_data);

            this.$store.commit('timeline_client/tweak_conversation_file', returned_data.quote_id);

            if(this.demand){
                this.get_conversation();
            } else {
                this.get_conversation_without_demand();
            }

        });

    
        
    }
}
</script>



<style scoped>




.time_ago{
    font-size: 12px!important;
}


.moonLoader {
    /* height: 24px;
    width: 24px; */
    /* border-radius: 100%; */
    margin: 0 auto;
    margin-top: 20px;
}
</style>