<template>
  <div>
    <vue-range-slider v-model="value" @change="valueChanged" :min=10 :max=1000></vue-range-slider>
  </div>
</template>



<script>
import VueRangeSlider from 'vue-range-component'
import 'vue-range-component/dist/vue-range-slider.css'

export default {
  name: "RangeSlider",
  data() {
    return {
      value: 10
    }
  },
  components: {
    VueRangeSlider
  },


  methods: {
      valueChanged: function(){
          console.log('valoare schimbara: ' + this.value);
      }
  }
}
</script>