<template>

    <form id="logout-form" ref="logoutForm" action="/logout" method="POST" @submit.prevent="submitForm">
    <input v-if="csrf" type="hidden" name="_token" :value="csrf">
        <button class="dropdown-item">
            <i class="dropdown-icon mdi  mdi-logout-variant"></i> Delogare
        </button>
    </form>
</template>

<script>
export default {
    name: "LogoutComponent",

    data(){
        return {
            csrf: null,
            accessToken: null
        }
    },

    methods: {
        submitForm: function(form){
            // console.log(form);

            // invaldiate the current token

            axios.post(`/api/logout`).then(response => {
                // console.log(response.data)
                localStorage.removeItem('searched_in_all');
                localStorage.removeItem('searchedIn');
                localStorage.removeItem('demands');
                localStorage.removeItem('search_made');
                localStorage.removeItem('location');
                localStorage.removeItem('selectedCategories');
                localStorage.removeItem('range');
            });

            // send with axios
            this.$refs.logoutForm.submit()
        }
    },

    beforeDestroy () {
    },

    created(){
        this.csrf = $('meta[name="csrf-token"]').attr('content');

        this.accessToken = Vue.cookie.get(document.cookie.token_access).token_access;
        // get unread notification
        axios.defaults.headers.common = {'Authorization': `bearer ${this.accessToken}`}
    }

}
</script>

<style scoped>
#logout-form {}
</style>