<template>
<div class="card" v-if="getAdministratives && getAdministratives.length > 0">
    <div class="card-header">
        <h3 class="card-title">Județe</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div v-if="!loadingStatus">
        <b-form-group v-if="getAdministratives.length > 0"
            label="Filtrare în funcție de județ"
            v-slot="{ ariaDescribedby }"
            >
            <b-form-checkbox-group
                v-model="selected"
                :options="getAdministratives"
                :aria-describedby="ariaDescribedby"
                name="flavour-2a"
                stacked
            ></b-form-checkbox-group>
        </b-form-group>
        <a @click="filterByAdministrative" v-if="selected.length > 0" class="btn btn-primary btn-sm">Aplică</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Aplică</a>
        <a @click="resetFilter" v-if="applied" class="btn btn-warning btn-sm">Resetare</a>
        <a v-else class="btn btn-default disabled btn-sm" disabled="disabled">Resetare</a>
        </div>
        <div v-else class="d-flex justify-content-center mb-3">
            <b-spinner variant="info" small label="Se încarcă filtrarea"></b-spinner>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "AdministrativesComponent",

    computed: {
        getAdministratives: function(){
            if(this.options && this.options.length > 0)
                return this.options;
            else if(this.administratives && this.administratives.length > 0)
                return this.administratives;
            else
                return [];
        }
    },

    data() {
      return {
        loadingStatus: false,
        selected: [], // Must be an array reference!
        options: [
        //   { text: 'Red', value: 'red' },
        //   { text: 'Green', value: 'green' },
        //   { text: 'Yellow (disabled)', value: 'yellow', disabled: true },
        //   { text: 'Blue', value: 'blue' }
        ],
        applied: false
      }
    },

    props: {
        administratives: Array
    },

    methods: {
        filterByAdministrative: function(){
            // console.log(this.selected);
            this.$emit('filter:administrative', this.selected);
            this.applied = true;
            localStorage.filter_administrative_selected = JSON.stringify(this.selected);
            localStorage.filter_administrative_applied = this.applied;

        },
        resetFilter: function(){
            this.$emit('reset:administrative');
            this.selected = [];
            this.applied = false;
            localStorage.removeItem('filter_administrative_selected');
            localStorage.removeItem('filter_administrative_applied');
        }
    },

    created(){
        // this.options = this.administratives;
        if(localStorage.demands_administratives){
            let existing = JSON.parse(localStorage.demands_administratives);
            this.options = existing.filter(item => {
                if(item != '' || item != null || item != 'null'){
                    return item;
                }
            });
        }

        if(localStorage.filter_administrative_selected){
            this.selected = JSON.parse(localStorage.filter_administrative_selected);
        }

        if(localStorage.filter_administrative_applied){
            this.applied = localStorage.filter_administrative_applied;
        }

    }
}
</script>