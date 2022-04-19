<template>
  <div class="row" v-if="demand">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informații despre proiect</h3>
        </div>
        <div class="card-body">
          <h4>Subiect: {{ demand.subject }}</h4>
          <hr>
          <p>Dată lansare: <i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatElementTimeMethod(demand.created_at) }} <span>({{ calculateResponseTimeMethod(demand.created_at) }})</span></p>
          <hr>
          <p class="small">Categorii:</p>
          <div v-if="demand.categories && demand.categories.length">
            <span class="badge badge-info mx-1" v-for="category in demand.categories" :key="'category_' + category.id">{{ category.name }}</span>
          </div>
          <hr>
          <p>Locație: {{ demand.city }}<span v-if="demand.administrative && demand.administrative !== 'undefined'">, {{ demand.administrative }}</span></p>
          <MapDemandComponent :lat="getLat" :lng="getLng" :key="mapKey" />
          <hr>
          <p>Mesaj:</p>
          <div>{{ demand.message }}</div>

          <div v-if="demand.files && demand.files.length > 0">are fișiere.
            <hr>
            <!-- <ListFilesComponent :the_files="demand.files" :the_type_path="'demands'" :key="filesKey" /> -->
            <ListDemandFiles :the_files="demand.files" :the_type_path="'demands'" :key="filesKey" :type="'images'" />
            <ListDemandFiles :the_files="demand.attachments" :the_type_path="'demands'" :key="filesKey" :type="'attachments'" />
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-lg-6">
              <p>Stare: 
                  <span class="badge badge-success" v-if="demand.state == 1">Activă</span>
                  <span class="badge badge-danger" v-else>Inactivă</span>
              </p>
            </div>
            <div class="col-lg-6">
            </div>
          </div>
        </div>

      </div>

      
    </div>
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalii de contact</h3>
        </div>
        <div class="card-body">
          <p>Nume: {{ demand.name }}</p>
          <p>E-mail: {{ demand.email }}</p>
          <p>Telefon: {{ demand.phone }}</p>
          <hr>
          <p v-if="demand.detail">Oferte permise: {{ demand.detail.offers }}</p>
          <template v-if="demand.buyers">
            <p>Participanți: {{ demand.buyers.length }}</p>
            <!-- <pre>{{ demand.buyers }}</pre> -->
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="buyer in demand.buyers" :key="'buyer-' + buyer.id">{{ buyer.complete_name }}  <div class="float-right"><a :href="'/public/profil/profesionist/' + buyer.user_name" class="btn btn-sm btn-default">Profil</a></div></li>
            </ul>
          </template>
        </div>
      </div>
      <div class="row">
          <div class="col-lg-6">
              <div class="d-flex justify-content-center" v-if="demand.state == 0">
                <button class="btn btn-info" @click.prevent="relaunchDemand" v-if="!btnLoading">Relansează cerere (+3 oferte)</button>
                <button class="btn btn-info btn-loading" disabled="disabled" v-else>Relansează cerere (+3 oferte)</button>
            </div>
          </div>
          <div class="col-lg-6">
              <div class="d-flex justify-content-center">
                <button class="btn btn-danger" @click.prevent="deleteDemand" v-if="!btnDeleting">Elimină cerere</button>
                <button class="btn btn-danger btn-loading" disabled="disabled" v-else>ELimină cerere</button>
            </div>
          </div>
      </div>
    </div>
  </div>
  <div class="row" v-else>
      <div class="col-lg-12 align-self-center">
          <div class="alert alert-danger" role="alert">
            Cererea nu există.
        </div>
      </div>
  </div>
</template>

<script>
import ListFilesComponent from "../pro/_modules/ListFilesComponent.vue";
import MapDemandComponent from "../pro/_modules/MapDemandComponent.vue";
import ListDemandFiles from "./ListDemandFiles.vue";

export default {
    name: "PublicSingleDemandComponent",

    data(){
      return {
        btnLoading: false,
        btnDeleting: false,
        mapKey: 'map-component',
        filesKey: 'files-component',
        demand: null,
      }
    },

    components: {
      ListFilesComponent,
      MapDemandComponent,
      ListDemandFiles
    },

    props: {
      the_demand: Object,
      unique: String
    },

    computed: {
      getLat: function(){
        return this.demand.lat;
      },
      getLng: function(){
        return this.demand.lng;
      },
    },

    methods: {


      formatElementTimeMethod: function(element){
        return moment(element).format("lll");
      },
      calculateResponseTimeMethod: function(element_time){
          let currentTime = moment().format('YYYY MM DD, HH:mm');
          let responseTime = moment(element_time).format("YYYY MM DD, HH:mm");
          var startTime = moment(responseTime, 'YYYY MM DD, HH:mm a');
          var endTime = moment(currentTime, 'YYYY MM DD, HH:mm a');
          var resultTime = startTime.diff(endTime, 'minutes');
          var asHuman = moment.duration(resultTime, 'minutes').humanize(true);
          return asHuman;
          // return 'hehehe';
      },

     relaunchDemand: function(){
          this.btnLoading = true;

          axios.post(`/api/public/demands/relaunch/${this.demand.uuid}/${this.unique}`).then(response => {
              if(response.data.success){
                this.demand = response.data.demand;

                this.$swal(
                    'Cerere relansată!',
                    'Felicitări! Cererea a fost relansată si reactivată. I s-au adăugat +3 oferte noi.',
                    'success'
                );
              } else if(response.data.errors){
                Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
              }
          }).catch(err => {
              Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
          }).finally(() => {
              this.btnLoading = false;
          });
      },

    deleteDemand: function(){
        this.btnDeleting = true;

        axios.post(`/api/public/demands/delete/${this.demand.uuid}/${this.unique}`).then(response => {
              if(response.data.success){
                this.demand = null;

                this.$swal(
                    'Cerere eliminată!',
                    'Cererea a fost eliminată.',
                    'success'
                );
              } else if(response.data.errors){
                Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
              }
          }).catch(err => {
              Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Reîncearcă mai târziu.',
                    type: 'error',
                    duration: 6000
                });
          }).finally(() => {
              this.btnDeleting = false;
          });
    }

      
    },

    created(){
      this.demand = this.the_demand;
    }
}
</script>

<style>

</style>