<template>
<ValidationObserver ref="observer" v-slot="{ handleSubmit, invalid }">
    <form id="register_demand" @submit.prevent="handleSubmit(onSubmit)">
        <div class="form-group">
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

                    <span slot="noOptions">Lista este goală. Nu există nicio categorie.</span>
                    <span slot="noResult">Oops! Nu am găsit niciun element cu acest nume.</span>
                    <span slot="maxElements">Numărul maxim de elemente selectate a fost atins.</span>
                    <span slot="afterList" class="text-muted p-2"><small>Poți selecta una sau mai multe categorii.</small></span>

                </multiselect>  
                <span class="small text-danger">{{ errors[0] }}</span>
            </validation-provider>
        </div>

        <div class="form-group d-flex justify-content-center">
            <div class="col-lg-8">
            <button v-if="!btnLoading" type="submit" class="btn btn-success btn-block" :disabled="invalid">Salvează</button>
            <button v-else type="button" class="btn btn-success btn-loading btn-block" disabled="disabled">În curs de salvare</button>
            </div>
        </div>

    <!-- <pre class="language-json"><code>{{ value  }}</code></pre> -->

    </form>
</ValidationObserver>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend, } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest camp este obligatoriu.'
});


  import Multiselect from 'vue-multiselect'

  import { mapGetters } from 'vuex'

  // register globally
//   Vue.component('multiselect', Multiselect)

  export default {
    name: "CategoriesComponent",
    // OR register locally
    components: { Multiselect, ValidationProvider, ValidationObserver },
    data () {
      return {
        value: null,
        loadingStatus: false,
        btnLoading: false,
        options: [],
        selected: []
      }
    },

    props: {
        cached: Array,
        incOptions: Array,
        existing: Array,
        demand_id: Number
    },

  computed: {
    ...mapGetters('categories_explore', [
      'getCategories',
      // ...
    ])
  },

    methods: {
        initializeCategories(){
            this.loadingStatus = true;
            // axios.get(`/api/categories/get/all`).then(response => {
            //     // console.log('categoriile sunt', response.data);
            //     this.options = response.data.categories;
            // }).finally(() => {
            //     this.loadingStatus = false;
            // });

            this.$store.dispatch('categories_explore/initCategories').then(() => {
                
                if(this.existing && this.existing.length > 0){
                    // console.log('existing este', this.existing);
                    this.value = this.existing.flat();
                    this.selected = this.existing.flat();
                }
            }).finally(() => {
              this.loadingStatus = false;
            });
        },

        selectItem(item){
            // console.log('selectat item', item);

            this.selected.push(item);
            // console.log('this.selecte este', this.selected);
            // console.log('this.value este', this.value);

            // this.$emit('categories:selected', this.selected.flat());
            // this.$emit('categories:cached', this.selected.flat());
        },
        removeItem(item){
            // console.log('eliminat item', item);
            this.selected = this.selected.filter(elem => elem.id != item.id);

            // console.log('this.selected este', this.selected);
            // this.$emit('categories:selected', this.selected.flat());
            // this.$emit('categories:cached', this.selected.flat());
        },

        resetAll: function(){
            this.selected = [];
            this.value = null;
        },

        onSubmit: async function(){
          this.btnLoading = true;
          // console.log('fire');
          // console.log('categories', this.selected.flat());

          let categories_array = [...this.selected.flat().map(item => item.id)];
          let formData = new FormData();
          formData.append('categories', categories_array);

          await axios.post(`/api/admin/demands/${this.demand_id}/update/categories`, formData).then(async response => {
            
            if(response.data.success){
              await this.$emit('categories:changed', this.selected.flat()); 
              this.resetAll();
              this.$refs.observer.reset();
            } else if(resposne.data.errors){
              Vue.$toast.open({
                  message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                  type: 'error',
                  duration: 6000
              });
            }
          }).catch(error => {
            Vue.$toast.open({
                message: 'Oups! Am întâmpinat erori. Încearcă mai târziu.',
                type: 'error',
                duration: 6000
            });
          }).finally(() => {
            this.btnLoading = false;
          });
        }
    },

    created(){
        this.initializeCategories();
        // this.options = this.incOptions;
        
    }
  }
</script>

<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
