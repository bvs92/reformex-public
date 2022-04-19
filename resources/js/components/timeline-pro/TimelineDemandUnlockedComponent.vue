<template>
    <li v-if="unlocked_demand">
        <!-- <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatElementTimeMethod() }}</span><span>{{ calculateElementTime }}</span></time> -->
        <time-ago-component :element="unlocked_demand"></time-ago-component>
        <div class="cbp_tmicon bg-success"><i class="ti ti-unlock"></i></div>
        <div class="cbp_tmlabel empty"> 
            <span>Ati deblocat cererea numarul #{{ demand_uuid }}.</span> 
            <span>Costul de deblocare: <strong>{{ demand_cost }} RON</strong>.</span>

        </div>
    </li>
</template>

<script>
import TimeAgoComponent from "../TimeAgoComponent";


export default {
    name: "TimelineDemandUnlockedComponent",

    components: {
        "time-ago-component": TimeAgoComponent
    },

    data(){
        return {
            unlocked_demand: null,
            demand_uuid: null,
            demand_cost: null
        }
    },


    props: {
        the_unlocked_demand: Object,
        the_demand_uuid: String,
        the_demand_cost: Number
    },


    computed: {
        
    },


    methods: {
        
    },


    created(){
        this.unlocked_demand = this.the_unlocked_demand;
        this.demand_uuid = this.the_demand_uuid;
        this.demand_cost = this.the_demand_cost;

       
    }
}
</script>

<style scoped>

</style>