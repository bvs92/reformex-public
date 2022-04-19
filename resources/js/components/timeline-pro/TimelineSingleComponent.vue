<template>
    <li v-if="conversation">
        <!-- <time class="cbp_tmtime"><span>{{ formatElementTimeMethod() }}</span> <span>{{ calculateElementTime }}</span></time> -->
        <time-ago-component :element="conversation"></time-ago-component>

        <template v-if="conversation.type=='Quote'">
            <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
            <QuoteComponent :conversation="conversation" :current_user="the_current_user" :pro="the_pro" :timeline="timeline" />
        </template>

        <template v-if="conversation.type=='ClientMessage'">
            <div class="cbp_tmicon bg-info"><i class="ti ti-comment-alt"></i></div>
            <ClientMessageComponent :conversation="conversation" :client="the_client" />
        </template>

        <template v-if="conversation.type=='Prospect'">
            <div class="cbp_tmicon bg-secondary"><i class="ti ti-heart"></i></div>
            <!-- <ProspectComponent :conversation="conversation" :pro="the_pro" :client="the_client" :demand_uuid="the_demand_uuid" /> -->
            <div class="cbp_tmlabel empty"> 
                <div class="row">
                    <div class="col-lg-12">
                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ client.complete_name }}</a><span> vrea sa fiti <span class="badge badge-success  mr-1 mb-1 mt-1">castigatorul</span> <strong v-if="demand_uuid">cererii (#{{ demand_uuid }})</strong><strong v-else><i class="side-menu__icon ti-na"></i> cererea a fost eliminata</strong>.</span></h2>
                        <div v-if="conversation.status == 0">
                            <p class="ml-4 text-disabled"><span class="badge badge-warning mr-1 mb-1 mt-1">IN CURS</span> {{ client.complete_name }} asteapta un raspuns. Selectati mai jos un raspuns.</p>
                            <div class="d-flex flex-row">
                                <a class="btn btn-success btn-sm text-white m-2" @click="accept_proposal(conversation.id)">Accepta propunerea</a>
                                <a class="btn btn-danger btn-sm text-white m-2" @click="refuse_proposal(conversation.id)">Refuza propunerea</a>
                            </div>
                        </div>
                        <div v-if="conversation.status == 1">
                            <p class="ml-4 text-disabled"><span class="badge badge-success mr-1 mb-1 mt-1">ACCEPTAT</span> {{ pro.complete_name }}, ati acceptat propunerea trimisa de catre {{ client.complete_name }}. <span v-if="conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(conversation.response) }}</span></p>
                        </div>
                        <div v-if="conversation.status == 2">
                            <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">REFUZAT</span> {{ pro.complete_name }}, ati refuzat propunerea trimisa de catre {{ client.complete_name }}. <span v-if="conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(conversation.response) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-if="conversation.type == 'Review' && conversation.professional_id == pro.professional_id">
            <!-- <template v-if="conversation.professional_id == pro.professional_id"> -->
            <div class="cbp_tmicon bg-warning"><i class="ti ti-star"></i></div>
            <ReviewComponent :conversation="conversation" :client="client" />
            <!-- <div class="cbp_tmlabel empty"> 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-none">
                            <div class="card-header">
                                <h3 class="card-title" v-if="client">Recenzia lasata de catre {{ client.complete_name }}.</h3>
                            </div>
                            <div class="card-body">
                                <h5><span>Rating: {{ conversation.rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                                <br>
                                <p>
                                    {{conversation.message }}
                                </p>
                            </div>
                        </div> 

                    </div>
                </div>
            </div> -->
            <!-- </template> -->
        </template>


        


        <template v-if="the_conversation && the_conversation.type == 'Winner'">
            <template v-if="conversation.professional_id == pro.professional_id">
                <template v-if="parseInt(the_conversation.status) == 1">
                    <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                    <WinnerComponent :client="the_client" :pro="the_pro" :demand_uuid="the_demand_uuid" :conversation="conversation" :type="true" />
                    <!-- <div class="cbp_tmlabel empty"> 
                        <div class="row" :key="'winner-' + conversation.status">
                            <div class="col-lg-12">
                                <div class="card shadow-none">
                                    <div class="card-header">
                                    <h3 class="card-title" v-if="client">Felicitari! Proprietarul cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span> v-a desemnat castigator final.</h3>
                                </div>
                                <div class="card-body">
                                    <h5 v-if="client">{{ pro.complete_name }}, echipa noastra va ureaza spor si succes in proiectele dumneavoastra.</h5>
                                    <br>
                                    <p>Va dorim success si spor in executia proiectului.</p>
                                    <p>Ceilalti participanti la cerere au fost respinsi automat. Puteti continua comunicarea prin intermediul platformei noastre sau direct cu profesionistul.</p>
                                </div>
                                </div>

                            </div>
                        </div>
                    </div> -->
                </template>
                <template v-else-if="parseInt(the_conversation.status) == 2">
                    <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                    <WinnerComponent :client="the_client" :pro="the_pro" :demand_uuid="the_demand_uuid" :conversation="conversation" :type="false" />
                </template>
            </template>

            <template v-else>
                <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                <LoserComponent :pro="the_pro" :client="the_client" :demand_uuid="the_demand_uuid" />
                <!-- <div class="cbp_tmlabel empty"> 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title" v-if="client">Ne pare rau! Proprietarul cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span> a ales un alt castigator.</h3>
                                </div>
                                <div class="card-body">
                                    <h5 v-if="client">{{ pro.complete_name }}, echipa noastra va ureaza spor si succes in proiectele viitoare.</h5>
                                    <br>
                                    <p>Din pacate, acest proiect a fost castigat de un alt profesionist.</p>
                                    <p>Prin intermediul platformei noastre puteti gasi rapid proiecte pentru viitor. Va dorim succes!</p>
                                </div>
                            </div> 

                        </div>
                    </div>
                </div> -->
            </template>

        </template>
        
    </li>
</template>


<script>
import ListFiles from "../ListFiles"
import TimeAgoComponent from "../TimeAgoComponent";
import QuoteComponent from "./types/QuoteComponent";
import ClientMessageComponent from "./types/ClientMessageComponent";
import ProspectComponent from "./types/ProspectComponent";
import ReviewComponent from "./types/ReviewComponent";
import LoserComponent from "./types/LoserComponent";
import WinnerComponent from "./types/WinnerComponent";

export default {
    name: "TimelineSingleComponent",

    components: {
        'list-files': ListFiles,
        "time-ago-component": TimeAgoComponent,
        QuoteComponent,
        ClientMessageComponent,
        ProspectComponent,
        ReviewComponent,
        LoserComponent,
        WinnerComponent,
    },

    data(){
        return {
            conversation: null,
            client: null,
            pro: null,
            demand_uuid: null
        }
    },

    props: {
        the_conversation: Object,
        the_client: Object,
        the_pro: Object,
        the_demand_uuid: String,
        the_current_user: Object,
        timeline: Object
    },

    computed: {},

    methods: {

        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },

        formatElementUpdatedMethod: function(element){
            return moment(element.updated_at).format("DD-MM-YYYY, HH:mm");
        },


        // deleteQuote(id){
        //     // console.log('the conversation id is: ' + id);
        //     // this.$emit('quote:removed', id);
        //     this.$store.dispatch('removeQuote', id);
        // },


        // accept proposal
        accept_proposal(prospect_id){
            console.log('accept proposal...');

            // 1. axios to register record response_proposal: accept
            axios.post(`/api/prospects/${prospect_id}/accept`).then(response => {
                if(response.data.response){
                    // console.log(response.data.response);
                    this.conversation.status = '1';
                } else if(response.data.error){
                    console.log(response.data.error);
                }
            }).catch(error => {
                console.error(error);
            });
        },


        refuse_proposal(prospect_id){
            // console.log('refuse proposal');

            // 1. axios to register record response_proposal: refuse
            axios.post(`/api/prospects/${prospect_id}/refuse`).then(response => {
                if(response.data.response){
                    // console.log(response.data.response);
                    this.conversation.status = '2';
                } else if(response.data.error){
                    console.log(response.data.error);
                }
            }).catch(error => {
                console.error(error);
            });
        }
    },


    created(){
        
        console.log("SUNTEM IN CONVERSATIE?");
        console.log(this.the_conversation);
        console.log(this.the_pro);
        this.conversation = this.the_conversation;
        this.client = this.the_client;
        this.pro = this.the_pro;
        this.demand_uuid = this.the_demand_uuid;
    }
}
</script>


<style scoped>

</style>