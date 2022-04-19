<template>
  <div>
    <b-form-checkbox
      id="checkbox-form"
      v-model="status"
      name="checkbox-form"
      :value="true"
      unchecked-value="false"
      @change="emitEvent"
    >
      Formular de contact atașat anunțului? (Vizitatorii vor trimite mesaje pe e-mail.)
    </b-form-checkbox>
  </div>
</template>

<script>
  export default {
    name: "HasFormComponent",
    data() {
      return {
        status: true
      }
    },

  
    methods: {
        emitEvent: function(){
            this.$emit('hasFormValue', this.status);
        },

        reset: function(){
            this.status = true;
        }
    },

    created(){
     
    }
  }
</script>