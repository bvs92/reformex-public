<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12" v-if="type == true">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="pro">Felicitari! Profesionistul {{ pro.complete_name }} este castigatorul final.</h3>
                </div>
                <div class="card-body">
                    <h5 v-if="owner">{{ owner.complete_name }}, l-ati ales pe profesionistul {{ pro.complete_name }} ca fiind castigatorul final al cererii <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h5>
                    <br>
                    <p>Va dorim success si spor in executia proiectului dumneavoastra.</p>
                    <p>Ceilalti participanti la cerere au fost respinsi automat. Puteti continua comunicarea prin intermediul platformei noastre sau direct cu profesionistul.</p>
                </div>
            </div> <!-- end card -->

        </div>

        <div class="col-lg-12" v-else>
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title">{{ owner.complete_name }}. Ati anulat anulat acest castigator.</h3>
                </div>
                <div class="card-body">
                    <h5><span>Profesionistului <strong>{{ pro.complete_name }}</strong> i-a fost anulat statusul de castigator al cererii #{{ demand_uuid }}</span>. <span class="text-muted">Data: {{ formatElementUpdatedMethod(conversation) }}</span></h5>
                    
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
        owner: Object,
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