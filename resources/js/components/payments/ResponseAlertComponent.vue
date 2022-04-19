<template>
    <div class="row d-flex justify-content-center" ref="alert_success">
        <div class="col-lg-8">
            <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="response_status">
                <p class="text-center">{{ response_success }}</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="closeAlert()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ResponseAlertComponent",

    data(){
        return {
            response_status: false,
            response_success: null
        }
    },

    props: {
        _response_status: Boolean,
        _response_success: String
    },

    methods: {
        closeAlert(){
            this.response_status = false;
            this.response_success = null;
        },

        loadData(){
            this.response_status = this._response_status;
            this.response_success = this._response_success;
        }
    },

    created(){
        this.loadData();
    }
}
</script>