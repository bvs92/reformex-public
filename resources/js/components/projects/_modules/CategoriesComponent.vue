<template>
  <div v-if="getCategories">
    <validation-provider rules="required" v-slot="{ errors, invalid, passed, touched }">
        <multiselect v-model="value" 
        :class="{'is-invalid' : touched && invalid, 'is-valid': passed}" 
        :options="getCategories" 
        :multiple="true" 
        :close-on-select="false" 
        :clear-on-select="false" 
        :preserve-search="true" 
        :hide-selected="true"
        placeholder="Alege categorii" 
        selectLabel=""
        deselectLabel=""
        :loading="loadingStatus"
        selectedLabel="Selectat"
        label="name" track-by="name" 
        :preselect-first="false"
        open-direction="bottom"
        @select="selectItem" 
        @remove="removeItem"
        >

            <template slot="selection" slot-scope="{ values, isOpen }">
                <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Categorii selectate</span>
            </template>

            <span slot="noOptions">Lista este goala. Nu exista nicio categorie.</span>
            <span slot="noResult">Oops! Nu am gasit niciun element cu acest nume.</span>
            <span slot="maxElements">Numarul maxim de elemente selectate a fost atins.</span>
            <span slot="afterList" class="text-muted p-2"><small>Puteti selecta una sau mai multe categorii.</small></span>

        </multiselect>  
        <span class="small text-danger" v-if="errors && errors.length > 0"><i class="fa fa-info-circle mr-2"></i> {{ errors[0] }}</span>
    </validation-provider>
  <!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->

  </div>
</template>

<script>
import { ValidationProvider, extend, } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Aceasta informatie este obligatorie.'
});


import Multiselect from 'vue-multiselect';
import { mapGetters } from 'vuex';

  // register globally
//   Vue.component('multiselect', Multiselect)

  export default {
    name: "CategoriesComponent",
    // OR register locally
    components: { Multiselect, ValidationProvider },
    data () {
      return {
        value: null,
        loadingStatus: false,
        options: [],
        selected: []
      }
    },

    props: {
        existing_categories: Array,
        incOptions: Array
    },

  computed: {
    ...mapGetters('project_categories', ['getCategories', 'getTotalCategories']),
  },

    methods: {
        async initializeCategories(){
            this.loadingStatus = true;
   
            await this.$store.dispatch('project_categories/initCategories').finally(() => {
              this.loadingStatus = false;
            });
        },

        selectItem(item){
            // console.log('selectat item', item);

            this.selected.push(item);

            // console.log('this.selected este, la adaugare', this.selected);
            this.$emit('categories:selected', this.selected.flat());
    
        },
        removeItem(item){
            // console.log('eliminat item', item);
            this.selected = this.selected.filter(elem => elem.id != item.id);

            // console.log('this.selected este, la eliminare', this.selected);
            this.$emit('categories:selected', this.selected.flat());
        },

        resetAll: function(){
            this.selected = [];
            this.value = null;
        }
    },

    created(){
        this.initializeCategories();

        if(Object.keys(this.existing_categories).length !== 0) {
            this.selected = this.existing_categories;
            this.value = this.existing_categories;
        }
    }
  }
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
