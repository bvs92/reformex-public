<template>
  <div class="row">

    <div class="col-lg-12 my-4">
        <h4>Activitate</h4>
        <UserActivityComponent :user_id="user_id" />
    </div>


  </div>
</template>

<script>

import UserActivityComponent from './tables/UserActivityComponent.vue';

export default {
    name: "AdminListUserActivityComponent",

    props: {
        user_id: Number
    },

    components: {
        UserActivityComponent
    },

    computed: {
        
    },

    created() {
        
    }
}
</script>

<style>

</style>