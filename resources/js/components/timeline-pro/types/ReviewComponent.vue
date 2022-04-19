<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="client">Recenzia lasata de catre {{ client.complete_name }}.</h3>
                </div>
                <div class="card-body">
                    <h5><span>Rating: {{ conversation.rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                    <br>
                    <p>
                        {{conversation.message }}
                    </p>
                </div>
            </div> <!-- end card -->

        </div>
    </div>
</div>
</template>


<script>


export default {
    name: "ReviewComponent",


    data(){
        return {
            // key_name: 'proposal'
        }
    },

    props: {
        conversation: Object,
        client: Object,

    },
}
</script>