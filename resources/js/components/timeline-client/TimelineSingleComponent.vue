<template>
    <li v-if="conversation" :key="the_key">
        <!-- <time class="cbp_tmtime">
            <span>{{ formatElementTimeMethod() }}</span> 
            <span><time-ago-component :element="conversation"></time-ago-component></span>
        </time> -->
        <time-ago-component :element="conversation"></time-ago-component>
        

        <template v-if="conversation.type=='Quote'">
            <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
            <QuoteComponent :conversation="conversation" :pro="pro" />

            <!-- <div class="cbp_tmlabel">

                <div class="row">
                    <div class="col-lg-12">
                        <h2><a href="javascript:void(0);" class="font-weight-bold" style="font-size:14px;">{{ pro.complete_name }}</a> <span> ati trimis un mesaj.</span></h2>
                    </div>
                </div>
                
                <p class="text-sm">
                    {{ conversation.message }}
                </p>

                <template v-if="conversation.files && conversation.files.length > 0">
                    <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
                    <list-files :conversation_type="conversation.type" :the_type_path="'quotes'" :the_attached_files="conversation.files"></list-files>
                </template>
            </div> -->
        </template>

        <template v-if="conversation.type=='ClientMessage'">
            <div class="cbp_tmicon bg-info"><i class="ti ti-comment-alt"></i></div>
            <ClientMessageComponent :conversation="conversation" :owner="owner" :current_user="the_current_user" :timeline="timeline" />

            <!-- <div class="cbp_tmlabel">
                <div class="row">
                    <div class="col-lg-10">
                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold" style="font-size:14px;">{{ owner.complete_name }}</a><span> a trimis un mesaj.</span></h2>
                    </div>

                    <div class="col-lg-2">
                        
                        <div class="dropdown float-right">
                            <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti-more"></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                
                                <a class="dropdown-item" @click="deleteMessage(conversation.id)"><i class="ti-trash"></i> Elimina</a>

                            </div>
                        </div>
                    </div>
                    
                </div>

                <p class="text-sm">
                    {{ conversation.message }}
                </p>

                <template v-if="conversation.files && conversation.files.length > 0">
                    <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
                    <list-files :current_user="the_current_user" :conversation_type="conversation.type" :the_type_path="'client_messages'" :the_attached_files="conversation.files"></list-files>
                </template>
            </div> -->


        </template>

        <template v-if="the_conversation.type=='Prospect'">
            <div class="cbp_tmicon bg-secondary"><i class="ti ti-heart"></i></div>
            <ProspectComponent :owner="owner" :pro="pro" :the_conversation="the_conversation" :demand_uuid="demand_uuid" />
            <!-- <div class="cbp_tmlabel empty"> 
                <div class="row">
                    <div class="col-lg-12">
                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ owner.complete_name }}</a><span> ati facut o <span class="badge badge-success mr-1 mb-1 mt-1">propunere</span> catre <strong>{{ pro.complete_name }}</strong> pentru <strong v-if="demand_uuid">cererea (#{{ demand_uuid }})</strong><strong v-else><i class="side-menu__icon ti-na"></i> cererea a fost eliminata</strong>.</span></h2>

                        <div v-if="the_conversation.status == 0">
                            <p class="ml-4 text-disabled"><span class="badge badge-warning mr-1 mb-1 mt-1">IN CURS</span> In asteptarea unui raspuns.</p>
                        </div>

                        <div v-if="the_conversation.status == 1">
                            <p class="ml-4 text-disabled"><span class="badge badge-success mr-1 mb-1 mt-1">ACCEPTAT</span> {{ pro.complete_name }} a acceptat propunerea dumneavoastra. <span v-if="the_conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(the_conversation.response) }}</span></p>
                        </div>

                        <div v-if="the_conversation.status == 2">
                            <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">REFUZAT</span> {{ pro.complete_name }} a refuzat propunerea dumneavoastra. <span v-if="the_conversation.response" class="text-muted">Data: {{ formatElementTimeMethod(the_conversation.response) }}</span></p>
                        </div>

                        <div v-if="the_conversation.status == 4">
                            <p class="ml-4 text-disabled"><span class="badge badge-danger mr-1 mb-1 mt-1">ANULAT</span> {{ owner.complete_name }}, ati anulat aceasta propunere si castigatorul. <span class="text-muted">Data: {{ formatElementUpdatedMethod(the_conversation) }}</span></p>
                        </div>
                    </div>
                </div>
            </div> -->
        </template>

        <template v-if="conversation.type == 'Review'">
            <template v-if="conversation.professional_id == pro.professional_id">
            <div class="cbp_tmicon bg-warning"><i class="ti ti-star"></i></div>
            <ReviewComponent :pro="pro" :conversation="conversation" />
            <!-- <div class="cbp_tmlabel empty"> 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-none">
                            <div class="card-header">
                                <h3 class="card-title" v-if="pro">Recenzia lasata profesionistului {{ pro.complete_name }}.</h3>
                            </div>
                            <div class="card-body">
                                <h5><span>Rating: {{ conversation.rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                                <br>
                                <p>Mesaj: </p>
                                <div>{{conversation.message }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> -->
            </template>
        </template>


        <template v-if="the_conversation && the_conversation.type == 'Winner'">
            <template v-if="conversation.professional_id == pro.professional_id">
                <template v-if="parseInt(the_conversation.status) == 1">
                    <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
  
                    <WinnerComponent :owner="owner" :pro="pro" :demand_uuid="demand_uuid" :conversation="conversation" :type="true" />
                    <!-- <div class="card shadow-none">
                        <div class="card-header">
                            <h3 class="card-title" v-if="pro">Felicitari! Profesionistul {{ pro.complete_name }} este castigatorul final.</h3>
                        </div>
                        <div class="card-body">
                            <h5 v-if="owner">{{ owner.complete_name }}, l-ati ales pe profesionistul {{ pro.complete_name }} ca fiind castigatorul final al cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h5>
                            <br>
                            <p>Va dorim success si spor in executia proiectului dumneavoastra.</p>
                            <p>Ceilalti participanti la cerere au fost respinsi automat. Puteti continua comunicarea prin intermediul platformei noastre sau direct cu profesionistul.</p>
                        </div>
                    </div>  -->

                </template>
                <template v-else-if="parseInt(the_conversation.status) == 2">
                    <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                   
                    <WinnerComponent :owner="owner" :pro="pro" :demand_uuid="demand_uuid" :conversation="conversation" :type="false" />
                    <!-- <div class="card shadow-none">
                        <div class="card-header">
                            <h3 class="card-title">{{ owner.complete_name }}. Ati anulat anulat acest castigator.</h3>
                        </div>
                        <div class="card-body">
                            <h5><span>Profesionistului <strong>{{ pro.complete_name }}</strong> i-a fost anulat statusul de castigator al cererii #{{ demand_uuid }}</span>. <span class="text-muted">Data: {{ formatElementUpdatedMethod(conversation) }}</span></h5>
                            
                        </div>
                    </div> -->
                </template>
            </template>

            <template v-else>
                <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                <LoserComponent :owner="owner" :demand_uuid="demand_uuid" />
                <!-- <div class="cbp_tmlabel empty"> 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-none">
                                <div class="card-header">
                                    <h3 class="card-title" v-if="owner">{{ owner.complete_name }},  ati ales un alt castigator pentru aceasta cerere.</h3>
                                </div>
                                <div class="card-body">
                                   <h5 v-if="owner">Acest profesionist este acum refuzat pentru cererea <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h5>
                                <br>
                                <p>Va dorum success si spor in executia proiectului dumneavoastra.</p>
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
        WinnerComponent
    },

    data(){
        return {
            conversation: null,
            owner: null,
            pro: null,
            demand_uuid: null
        }
    },

    props: {
        the_conversation: Object,
        the_owner: Object,
        the_pro: Object,
        the_demand_uuid: String,
        the_current_user: Object,
        the_key: String,
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


        // async deleteMessage(id){
        //     // this.$emit('client_message:removed', id);
        //     await this.$store.dispatch('timeline_client/removeMessage', id);
        // }
    },


    // watch: {
    //     'conversation': function(newVal, oldVal) {
    //         console.log('value changed from old to new', oldVal, newVal);
    //     }
    // },


    created(){
        this.conversation = this.the_conversation;
        this.owner = this.the_owner;
        this.pro = this.the_pro;
        this.demand_uuid = this.the_demand_uuid;


        // Echo.private('pro-quotes-channel.' + this.the_conversation.id)
        // .listen('QuoteFileDeletedEvent', (the_result) => {
        //     // this.getQuote();
        //     console.log('QuoteFileDeletedEvent - se executa.');
        //     console.log(the_result);
        // });

        // this.$nextTick(() => {
        //     // Okay, now that everything is destroyed, lets build it up again
        //     this.conversation = this.the_conversation;
        //     console.log('nexttick working?', this.conversation, this.the_conversation);
        // });

        
    }
}
</script>


<style scoped>

</style>