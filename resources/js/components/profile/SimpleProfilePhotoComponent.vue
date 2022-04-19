<template>

<div> 
              
    <img :src="getAvatarPath" alt="" class="avatar avatar-xxl bradius cover-image" v-if="getHasAvatar">
    
    <img src="/images/avatars/default-photo.png" v-else class="avatar avatar-xxl bradius cover-image"> 
    
</div>

</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "SimpleProfilePhotoComponent",

    computed: {
      ...mapGetters('avatar', ['getHasAvatar', 'getAvatarPath'])
    },
}
</script>

<style>

</style>