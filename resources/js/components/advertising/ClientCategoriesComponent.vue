<template>
  <div>
        <p>Selectează categorii pentru anunț. Permite selecții multiple.</p>
        <validation-provider rules="required" v-slot="{ errors }">
            <b-form-select v-model="selected" :options="options" multiple :select-size="6" @change="emitSelected"></b-form-select>
            <span class="small text-danger">{{ errors[0] }}</span>
        </validation-provider>
        <div class="mt-3">
            <template v-if="selected_names && selected_names.length > 0">
            Categorii: <span class="tag mr-1 mb-1" v-for="cat in selected_names" :key="cat.value">{{ cat.text }}</span>
            </template>
            <template v-else>0 categorii selectate.</template>
        </div>
  </div>
</template>

<script>
import {mapGetters} from 'vuex';
import { ValidationProvider, extend } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Acest câmp este obligatoriu.'
});

  export default {
    name: "ClientCategoriesComponent",

    components: {
      ValidationProvider
    },

    computed: {
        ...mapGetters('categories_explore', ['getCategories']),

        selected_names: function(){
            let categories_selected = this.options.filter(item => {
                if(this.selected.includes(item.value)){
                    return item;
                }
            });
            return categories_selected;
        }
    },

    data() {
        return {
            selected: [], // Array reference
            options: [],

            isBusy: false,
        }
    },

    props: ["existing_categories"],

    methods: {
      emitSelected: async function(){
        await this.$emit('selectedCategories', this.selected)
      },

      reset: function(){
        this.selected = [];
      }
    },

    created: async function(){
        this.isBusy = true;


        await this.$store.dispatch('categories_explore/initCategories').then(() => {
            this.getCategories.forEach(item => {
                let elem = {
                    value: item.id,
                    text: item.name
                }
                this.options.push(elem);
            })
        }).finally(() => {
            this.isBusy = false;
            if(this.existing_categories){
              this.selected = this.existing_categories;
            }
        });
    }
  }
</script>