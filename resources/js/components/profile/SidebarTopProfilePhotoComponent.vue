<template>

<div class="user-pic" v-if="type == 'sidebar'">
 <img :src="getAvatarPath" alt="" class="avatar-xl rounded-circle" v-if="getHasAvatar">
    
    <img src="/images/avatars/default-photo.png" v-else class="avatar-xl rounded-circle"> 
</div>

<div v-else> 
              
    <img :src="getAvatarPath" alt="" class="avatar profile-user brround cover-image" v-if="getHasAvatar">
    
    <img src="/images/avatars/default-photo.png" v-else class="avatar profile-user brround cover-image"> 
    
</div>

</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: "SimpleProfilePhotoComponent",

    computed: {
      ...mapGetters('avatar', ['getHasAvatar', 'getAvatarPath'])
    },

    props: {
        type: String
    }
}
</script>

<style>

</style>