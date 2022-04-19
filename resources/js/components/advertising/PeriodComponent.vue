<template>
  <div>
    <p>Valabilitate anunț. <span v-if="selected">Anunțul va fi activ timp de {{ valability }}.</span></p>
    <validation-provider rules="required" v-slot="{ errors }">
      <b-form-select v-model="selected" :options="options" @change="emitSelected"></b-form-select>
      <span class="small text-danger">{{ errors[0] }}</span>
    </validation-provider>
    
    
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { ValidationProvider, extend } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

  export default {
    components: {
      ValidationProvider
    },
    
    data() {
      return {
        selected: null,
        options: [],

        isBusy: false
      }
    },

    computed: {
      valability: function(){
        if(this.selected < 7){
          return this.selected + ' zile'
        }

        let result = parseInt(this.selected / 7);

        if(result == 1){
          return 'o săptămână';
        } else {
          return result + ' săptămâni';
        }
      },

      ...mapGetters('periods', ['getPeriods', 'getTotalPeriods']),

      
    },

    methods: {
      emitSelected: async function(){
        await this.$emit('selectedPeriod', this.selected)
      },

      reset: function(){
        this.selected = null;
      },

      loadPeriods: async function(){
            this.isBusy = true;
            await this.$store.dispatch('periods/all').finally(() => {
                this.isBusy = false;
            });
        }
    },

    watch: {
      selected: async function(value){
          // await this.$emit('selectedPeriod', value);
          // console.log(value);
        }
    },

    created: async function(){
        await this.loadPeriods();
        // await console.log(this.getPeriods);

        
        this.options = await this.getPeriods.map(item => {
          let elem = {
            value: item.days,
            text: item.days + ' zile'
          }

          return elem;
        });

        this.options.unshift({
          value: null,
          text: "Selectează perioada"
        });
  

    }

  }
</script>