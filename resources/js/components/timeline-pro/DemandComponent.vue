<template>
<div class="cbp_tmlabel empty" v-if="demand">
    <div class="py-2">
        <h2 v-if="getClient"><a href="javascript:void(0);" class="font-weight-bold">{{ getClient.complete_name }}</a> <span>a inceput un nou proiect si are nevoie de un profesionist.</span></h2>
    </div> 
    <ul class="demo-accordion accordionjs m-0" data-active-index="false">
        <!-- SECTION 1 -->
        <li>
            <div><h3>Afisati detalii complete despre cererea <span v-if="demand">#{{ demand.uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h3></div>
            <div>
                <div class="row justify-content-center p-4">
                    <div class="col-md-12 py-4" style="background: white;">

                        <h4>Subiect: {{ demand.subject }}</h4>
                        <hr>
                        

                        <div class="row">
                            <div class="col-lg-6">
                                <p class="py-2"><i class="side-menu__icon ti-time"></i> {{ formatElementTimeMethod(demand) }}</p>
                                <p class="py-2" v-if="demand.categories"><i class="fa fa-tags" aria-hidden="true"></i> <strong v-for="(category, index) in demand.categories" :key="category.id">{{ category.name }}<template v-if="index != demand.categories.length - 1">, </template></strong></p>
                                <p class="py-2"><i class="fa fa-at"></i> {{ demand.email }}</p>
                                <!-- <p class="text-danger py-2"><i class="side-menu__icon ti-bolt"></i> Urgent</p> -->
                            </div>
                            <div class="col-lg-6">
                                <p class="py-2" v-if="getClient"><i class="fa fa-user"></i> {{ getClient.complete_name }}</p>
                                <p class="py-2"><i class="side-menu__icon ti-location-pin"></i> {{ demand.city }}</p>
                                <p class="py-2"><i class="fa fa-phone"></i> <a :href="'tel:' + demand.phone" rel="nofollow">{{ demand.phone }}</a></p>
                            </div>
                        </div>

                        <hr>

                        <!-- <div id="mapid"></div> -->
                        <map-demand-component v-if="demand" :accessTokenMap="accessTokenMap" :lat="demand.lat" :lng="demand.lng"></map-demand-component>

                        <hr>

                
                        <div>
                            <h5>Descriere cerere</h5>
                            {{ demand.message }}
                        </div>
                        
                    
                        <br>
                    </div><!-- end col-lg-12 -->
                </div><!-- end row -->
            </div>
        </li>
    </ul>
</div>
</template>

<script>
import MapDemandComponent from "../MapDemandComponent";

export default {
    name: "DemandComponent",

    props: {
        demand: Object,
        getClient: Object,
        accessTokenMap: String
    },

    components: {
        "map-demand-component": MapDemandComponent,
    },


    methods: {
        formatElementTimeMethod: function(element){
            return moment(element.created_at).format("DD-MM-YYYY, HH:mm");
        },
    }
}
</script>