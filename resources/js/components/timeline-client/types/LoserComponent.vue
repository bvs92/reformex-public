<template>
<div class="cbp_tmlabel empty"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3 class="card-title" v-if="owner">{{ owner.complete_name }},  ati ales un alt castigator pentru aceasta cerere.</h3>
                </div>
                <div class="card-body">
                    <h5 v-if="owner">Acest profesionist este acum refuzat pentru cererea <span v-if="demand_uuid">#{{ demand_uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h5>
                <br>
                <p>Va dorim success si spor in executia proiectului dumneavoastra.</p>
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
        owner: Object,
        demand_uuid: String
    },
}
</script>