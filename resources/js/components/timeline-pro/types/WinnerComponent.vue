<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12" v-if="type == true">
            <div class="card shadow-none">
                <div class="card-header">
                <h3 class="card-title" v-if="client">Felicitari! Proprietarul cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span> v-a desemnat castigator final.</h3>
            </div>
            <div class="card-body">
                <h5 v-if="client">{{ pro.complete_name }}, echipa noastra va ureaza spor si succes in proiectele dumneavoastra.</h5>
                <br>
                <p>Va dorim success si spor in executia proiectului.</p>
                <p>Ceilalti participanti la cerere au fost respinsi automat. Puteti continua comunicarea prin intermediul platformei noastre sau direct cu profesionistul.</p>
            </div>
            </div> <!-- end card -->

        </div>

        <div class="col-lg-12" v-else>
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="client">{{ client.complete_name }} a anulat intelegerea cu dumneavoastra.</h3>
                </div>
                <div class="card-body">
                    <h5><span>Intelegerea stabilita cu clientul pentru cererea #{{ demand_uuid }} a fost anulata.</span>. <span class="text-muted">Data: {{ formatElementUpdatedMethod(conversation) }}</span></h5>
                    <p>Va dorim success in proiectele viitoare.</p>
                </div>
            </div>

        </div>
    </div>
</div>
</template>


<script>


export default {
    name: "WinnerComponent",

    props: {
        pro: Object,
        client: Object,
        demand_uuid: String,
        conversation: Object,
        type: Boolean // 0 = loser, 1 = winner
    },

    methods: {
        formatElementUpdatedMethod: function(element){
            return moment(element.updated_at).format("DD-MM-YYYY, HH:mm");
        },
    }
}
</script>