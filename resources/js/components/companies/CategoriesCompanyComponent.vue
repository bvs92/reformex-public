<template>
    <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
    </div>
    <div v-else>
        <div class="tags" v-if="getSelectedCategories">
            <span class="tag" v-for="(category, index) in getSelectedCategories" :key="category.id + '-' + index" style="font-size: 14px;">
                {{ category.name }}
                <a href="javascript:void(0)" @click.prevent="deleteCategory(category.id)" class="tag-addon"><i class="fe fe-x"></i></a>
            </span>
        </div>
    </div>
</template>

<script>

import { mapGetters } from 'vuex'

export default {
    name: "CategoriesCompanyComponent",

    data(){
        return {
            loadingStatus: false
        }
    },

    computed: {
    ...mapGetters('pro_module', [
      'getSelectedCategories',
      // ...
    ])
  },

  methods: {
      deleteCategory: function(_id){
        //   console.log('eliminam', _id);
          this.$store.dispatch('pro_module/deleteCategory', _id);
      }
  },

  created(){
    this.loadingStatus = true;

    this.$store.dispatch('pro_module/initSelectedCategories').finally(() => {
        this.loadingStatus = false;
    });
  }
}

</script>