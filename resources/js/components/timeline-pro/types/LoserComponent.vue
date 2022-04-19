<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="client">Ne pare rau! Proprietarul cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span> a ales un alt castigator.</h3>
                </div>
                <div class="card-body">
                    <h5 v-if="client">{{ pro.complete_name }}, echipa noastra va ureaza spor si succes in proiectele viitoare.</h5>
                    <br>
                    <p>Din pacate, acest proiect a fost castigat de un alt profesionist.</p>
                    <p>Prin intermediul platformei noastre puteti gasi rapid proiecte pentru viitor. Va dorim succes!</p>
                </div>
            </div> <!-- end card -->

        </div>
    </div>
</div>
</template>


<script>


export default {
    name: "LoserComponent",

    props: {
        pro: Object,
        client: Object,
        demand_uuid: String
    },
}
</script>