<template>
    <div v-if="loadingStatus" class="text-center">
        <b-spinner small label="Small Spinner"></b-spinner>
    </div>
    <div v-else>
        <div class="tags" v-if="getUserJudete">
            <span class="tag" v-for="(judet, index) in getUserJudete" :key="judet.id + '-' + index" style="font-size: 14px;">
                {{ judet.name }}
                <a href="javascript:void(0)" @click.prevent="deleteJudet(judet.id)" class="tag-addon"><i class="fe fe-x"></i></a>
            </span>
        </div>
    </div>
</template>

<script>

import { mapGetters } from 'vuex'

export default {
    name: "UserJudeteComponent",

    data(){
        return {
            loadingStatus: false
        }
    },

    computed: {
    ...mapGetters('judete', [
      'getUserJudete',
      // ...
    ])
  },

  methods: {
      deleteJudet: function(_id){
        //   console.log('eliminam', _id);
          this.$store.dispatch('judete/deleteJudet', _id);
      }
  },

  created(){
    this.loadingStatus = true;

    this.$store.dispatch('judete/initUserJudete').finally(() => {
        this.loadingStatus = false;
    });
  }
}

</script>