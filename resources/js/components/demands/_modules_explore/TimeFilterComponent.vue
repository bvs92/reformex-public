<template>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Perioada</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div>

        <b-form-group label="Selectează o opțiune" v-slot="{ ariaDescribedby }">
        <b-form-radio-group
            v-model="selected"
            :options="options"
            :aria-describedby="ariaDescribedby"
            name="radios-stacked"
            stacked
        ></b-form-radio-group>
        </b-form-group>


        <a @click="filterByTime" v-if="selected != 'all'" class="btn btn-primary btn-sm">Aplică</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Aplică</a>
        <a @click="resetFilter" v-if="applied" class="btn btn-warning btn-sm">Resetare</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Resetare</a>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "TimeFilterComponent",

    data() {
      return {
        selected: 'all',
        options: [
          { text: 'Toată perioada', value: 'all' },
          { text: 'Astăzi', value: 'today' },
          { text: 'Ultimele 5 zile', value: 5 },
          { text: 'Ultimele 10 zile', value: 10 },
          { text: 'Ultimele 30 zile', value: 30 },
        ],
        applied: false
      }
    },


    methods: {

        filterByTime: function(){
            // console.log('filterByTime...');
            this.applied = true;
            this.$emit('time:filter', this.selected);
            localStorage.filter_time_selected = JSON.stringify(this.selected);
            localStorage.filter_time_applied = JSON.stringify(this.applied);
        },

        resetFilter: function(){
            // console.log('resetFilter');
            this.selected = 'all';
            this.applied = false;
            this.$emit('reset:time_filter');

            localStorage.removeItem('filter_time_selected');
            localStorage.removeItem('filter_time_applied');
        }
    },

    created(){


        if(localStorage.filter_time_selected){
            this.selected = JSON.parse(localStorage.filter_time_selected);
        }

        if(localStorage.filter_time_applied){
            this.applied = localStorage.filter_time_applied;
        }
    }
}
</script>