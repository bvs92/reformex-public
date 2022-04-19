<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="pro">Recenzia lasata profesionistului {{ pro.complete_name }}.</h3>
                </div>
                <div class="card-body">
                    <h5><span>Rating: {{ conversation.rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                    <br>
                    <p>Mesaj: </p>
                    <div>{{conversation.message }}</div>
                </div>
            </div> <!-- end card -->

        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ReviewComponent",

    props: {
        pro: Object,
        conversation: Object
    }
}
</script>