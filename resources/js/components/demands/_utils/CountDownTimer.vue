<template>
    <span>Obține un alt cod în <strong>{{ timerCount }} secunde.</strong></span>
</template>

<script>

export default {
    name: "CountDownTimer",

    data() {
        return {
            timerCount: 35
        }
    },

    watch: {

        timerCount: {
            handler(value) {

                if (value > 0) {
                    setTimeout(() => {
                        this.timerCount--;
                    }, 1000);
                } else {
                    this.$emit('resend:code');
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        }

    }
}

</script>