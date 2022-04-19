<template>
    <time class="cbp_tmtime">
        <!-- <span v-if="theTimeInMinutes() > 60">
            <template v-if="the_time_is">Timp: {{ the_time_is }}</template>
        </span> -->
        <span class="text-muted">{{ formatElementTimeMethod() }}</span>
    </time>

</template>

<script>
export default {
    name: "TimeAgoComponent",

    data(){
        return {
            // nowTimes: null
            the_time_is: null,
            interval: null
        }
    },

    props: {
        element: Object
    },

    computed: {
        calculateElementTime: function(){
            return this.calculateElementTimeMethod();
        }
    },

    methods: {
        nowTimes(){
            // auto update time 
            // console.log('INVERVAL...');
            this.the_time_is = this.calculateElementTimeMethod();
            // console.log(moment().format('YYYY MM DD, HH:mm:ss'));
            
            var time_to_compare = this.theTimeInMinutes();
            // only if time under 24H
            // if(this.theTimeInMinutes > -1440)
            if(time_to_compare > -61){
                this.interval = setInterval(this.nowTimes, 60*1000); // update la 5 minute
                // console.log('TIMP IN MINUTE...');
                // console.log(this.theTimeInMinutes());
            } else if(time_to_compare <= -61 && time_to_compare >= -1440){
                this.interval = setInterval(this.nowTimes, 30*60*1000); // update la 30 minute
                // console.log('TIMP IN ORE...');
                // console.log(this.theTimeInMinutes());
            } else if(time_to_compare <= -1441) {
                this.interval = setInterval(this.nowTimes, 300*60*1000); // update la 300 minute
                // console.log('TIMP IN ZILE...');
                // console.log(this.theTimeInMinutes());
            }

        },

        theTimeInMinutes: function(){
            var currentTime = moment().format('YYYY MM DD, HH:mm');
            this.element.created_at = moment(this.element.created_at).format("YYYY MM DD, HH:mm");
            var startTime = moment(this.element.created_at, 'YYYY MM DD, HH:mm a');
            var endTime = moment(currentTime, 'YYYY MM DD, HH:mm a');
            var resultTime = startTime.diff(endTime, 'minutes');
            return resultTime;
        },
        calculateElementTimeMethod: function(){
            var resultTime = this.theTimeInMinutes();
            // console.log('timp in minute');
            // console.log(resultTime);
            var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
            return asHuman;
        },

        formatElementTimeMethod: function(){
            return moment(this.element.created_at).format("lll");
        },


    },


    created(){
        // console.log("TIME AGO COMPONENT");


        this.nowTimes();
    },


    destroyed(){
        clearInterval(this.interval);
    }

}
</script>

<style scoped>

</style>