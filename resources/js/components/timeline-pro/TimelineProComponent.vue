<template>
    <div class="col-md-12" v-if="!loading_timeline">
        <ul class="cbp_tmtimeline" v-if="getTheTimeline">



            <li :key="getTheTimeline.uuid" v-if="the_demand">
                <!-- <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatTimelineComponentTimeMethod() }}</span>
                <span>{{ calculateTimelineTime }}</span></time> -->
                <time-ago-component :element="getTheTimeline"></time-ago-component>
                <div class="cbp_tmicon bg-info"><i class="ti ti-user"></i></div>


                <demand-component 
                    v-if="the_demand"
                    :demand="the_demand" 
                    :accessTokenMap="the_accessTokenMap"
                    :getClient="getClient"
                ></demand-component>
                
                <div class="cbp_tmlabel empty" v-else>
                    <div class="py-2">
                        <h4 class="text-danger"><i class="side-menu__icon ti-na"></i>Cererea corespunzatoare acestei conversatii a fost eliminata.</h4>
                    </div> 
                </div>
                
            </li>


            <template v-if="unlocked_demand">
                <timeline-demand-unlocked-component 
                :the_unlocked_demand="unlocked_demand" 
                :the_demand_uuid="demand ? demand.uuid : null" 
                :the_demand_cost="the_demand_cost_calcul"
                >
                </timeline-demand-unlocked-component>
            </template>
            

            <template v-if="getTheConversation && getTheConversation.length">
                <timeline-single-component 
                    :the_current_user="the_current_user" 
                    v-for="(conversation, index) in getTheConversation" 
                    :key="'conversation_' + conversation.id + '_' + index" 
                    :the_conversation="conversation" 
                    :the_client="getClient" :the_pro="getPro" 
                    :the_demand_uuid="demand ? demand.uuid : null" 
                    :timeline="incoming_timeline"
                >
                </timeline-single-component>
            </template>
        </ul>

        <template v-if="getTheTimeline">
            <timeline-create-message-component 
                :key="create_component_key" 
                :the_client="getClient" 
                :conversation_status="getTheTimeline.status" 
                :the_timeline="getTheTimeline" 
            ></timeline-create-message-component>
        </template>
    </div>

    <div class="col-lg-12 col-md-12 d-flex flex-column" v-else-if="loading_timeline">
        <moon-loader size="30px" color="blue" class="moonLoader"></moon-loader>
        <p class="text-center">Preluam datele din conversatie. Va rugam asteptati...</p>
    </div>

</template>

<script>
import TimelineSingleComponent from "./TimelineSingleComponent";
import TimelineDemandUnlockedComponent from "./TimelineDemandUnlockedComponent";
import TimelineCreateMessageComponent from "./TimelineCreateMessageComponent";
import TimeAgoComponent from "../TimeAgoComponent";
// import MapDemandComponent from "../MapDemandComponent";
import DemandComponent from "./DemandComponent";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue';


import { mapGetters, mapActions } from 'vuex';

export default {
    name: "TimelineProComponent",

    components: {
        "timeline-single-component": TimelineSingleComponent,
        "timeline-demand-unlocked-component": TimelineDemandUnlockedComponent,
        'timeline-create-message-component': TimelineCreateMessageComponent,
        "time-ago-component": TimeAgoComponent,
        // "map-demand-component": MapDemandComponent,
        "demand-component": DemandComponent,
        MoonLoader
    },

    data(){
        return {
            loading_timeline: false,
            demand: null,
            unlocked_demand: null,
            cost: null,


            timeline: null,
            conversations: null,
            client: null,
            pro: null,


            create_component_key: 'create-component-key-timeline'
        }
    },

    props: {
        incoming_timeline: Object,
        demand_cost: Number,
        demand_unlocked: Object,
        the_current_user: Object,
        the_demand: Object,
        the_accessTokenMap: String
    },

    computed: {

        ...mapGetters({
            getTheTimeline: 'getTimeline',
            getPro: 'getPro',
            getClient: 'getClient',
            getTheConversation: 'getConversation'
        }),

        the_conversations: function(){
            return this.conversations;
        },

        the_demand_cost_calcul: function(){
            return this.cost ? this.cost / 100 : "Indisponibil";
        },

        calculateTimelineTime: function(){
            return this.calculateTimelineComponentTimeMethod()
        }
        
    },

    methods: {

        ...mapActions({
            getTimeline: 'initTimeline',
            get_conversation: 'initConversation'
        }),


        // de mutat in vuex si de corectat. la fel si in controller laravel.
        get_conversation_without_demand_pro: function() {
            axios.get(`/api/timelines/conversation-no-demand/${this.timeline.uuid}`).then(response => {
                this.client = response.data.client;
                this.pro = response.data.pro; // change to set with mutation

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

        // getTimeline: async function(){
        //     await axios.get(`/api/timelines/get/${this.timeline.uuid}`).then(response => {
        //         if(response.status == 200){
        //             this.timeline = response.data;
        //         }
        //     });
        // },

        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },

        initialize_data: async function(){
            // this.timeline = this.incoming_timeline;
            this.loading_timeline = true;
            await this.$store.commit('set_timeline', this.incoming_timeline)
            this.demand = this.incoming_timeline.demand;
            this.unlocked_demand = this.demand_unlocked;
            this.cost = this.demand_cost;
            this.demand = this.the_demand;
        },

        get_conversation_by_demand: async function(){
            if(this.demand){
                await this.get_conversation(this.incoming_timeline);
            } else {
                await this.get_conversation_without_demand_pro();
            }
        }

    },


    created(){
        this.initialize_data().then(async response => {
            await this.get_conversation_by_demand();
        }).finally(() => {this.loading_timeline = false;});

        // get the complete conversation
        

        const _self = this;


        Echo.private('client-timelines-channel.' + this.getTheTimeline.id)
        // Listen - pentru evenimentele lansate de catre PRO -> Client (Accept / refuse proposal). Sterge ClientProposalEvent de aici.
        
        .listen('TimelineMessageClientToProEvent', (timeline) => {

            this.get_conversation_by_demand();
        })

        .listen('TimelineProposalClientToProEvent', (timeline) => {

            this.get_conversation_by_demand();
            
        })
        
        .listen('TimelineMessageDeletedClientEvent', (timeline) => {

            this.get_conversation_by_demand();
        })
        
        .listen('TimelineClientMessageFileDeletedEvent', (returned_data) => {
            
            // temporar. pana implementam vuex pentru files.
            // let new_conversations = this.getTheConversation.map(item => {
            //     // console.log(item);
            //     if(item.id == returned_data.message_id){
            //         // item.files = [];
            //         item.id = '';   
            //     }
            //     return item;
            // });

            this.$store.commit('tweak_conversation_file', returned_data.message_id);


            this.get_conversation_by_demand();
            
        })

        .listen('ClientReviewEvent', (timeline) => {
            // listen for messages from PROFESSIONAL of current timeline.
            // console.log('SUNTEM AICI -= ClientReviewEvent');

            this.get_conversation_by_demand();

            // get latest quote added.

            Vue.$toast.open({
                message: this.client.complete_name + " lasat o recenzie in conversatia #" + timeline.uuid + '.',
                type: 'info',
                style: { backgroundColor: "#FFEFD5", fontSize: "14px"},
                progressbar: true,
                classNames: ["animated", "zoomInUp"],
                duration: 9000,
                onClick: function(){
                    window.location.href = "/timeline/pro/id/" + timeline.uuid;
                }
            });
            
        })
        
        .listen('WinnerDemandEvent', async (timeline) => {

            await this.getTimeline(timeline.uuid);

            //this.get_conversation();
            this.get_conversation_by_demand();


            let the_response = this.getPro.professional_id == timeline.winner.professional_id ? true : false;
            let _msg = the_response ? 'v-a ales castigator in' : 'a refuzat'
            Vue.$toast.open({
                message: timeline.user + " " + _msg  +" intelegerea de la conversatia #" + timeline.uuid + '.',
                type: the_response ? 'success' : 'error',
                duration: 9000,
                onClick: function(){
                    window.location.href = "/timeline/pro/id/" + timeline.uuid;
                }
            });

        })

        .listen('TimelineCancelWinner', (timeline) => {

            this.get_conversation_by_demand();

            Vue.$toast.open({
                message: timeline.user.last_name + ' ' + timeline.user.first_name + " v-a anulat intelegerea de la conversatia #" + timeline.uuid + '.',
                type: 'error',
                duration: 9000,
                onClick: function(){
                    window.location.href = "/timeline/pro/id/" + timeline.uuid;
                }
            });

        })
        
        .listen('TimelineClientTurnOnConversationEvent', (resulting_data) => {

            this.$swal(
                'Conversatia a fost activata',
                'Clientul a marcat aceasta conversatie ca activa.',
                'success'
            );

            this.$store.dispatch('initTimeline', _self.getTheTimeline.uuid);
        })
        
        .listen('TimelineClientTurnOffConversationEvent', (resulting_data) => {

            this.$swal(
                'Conversatia a fost dezactivata',
                'Clientul a dezactivat aceasta conversatie.',
                'error'
            );

            this.$store.dispatch('initTimeline', _self.getTheTimeline.uuid);
        });

    }
}
</script>

<style scoped>
.moonLoader {
    /* height: 24px;
    width: 24px; */
    /* border-radius: 100%; */
    margin: 0 auto;
    margin-top: 20px;
}
</style>