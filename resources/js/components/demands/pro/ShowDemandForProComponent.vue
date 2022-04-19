<template>
  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informații despre proiect</h3>
        </div>
        <div class="card-body">
          <h4>Subiect cerere: {{ demand.subject }}</h4>
          <hr>
          <p>Dată lansare: <i class="fa fa-clock-o" aria-hidden="true"></i> {{ formatElementTimeMethod(demand.created_at) }} <span>({{ calculateResponseTimeMethod(demand.created_at) }})</span></p>
          <hr>
          <p class="small">Categorii:</p>
          <div v-if="demand.categories && demand.categories.length">
            <span class="badge badge-info mx-1" v-for="category in demand.categories" :key="'category_' + category.id">{{ category.name }}</span>
          </div>
          <hr>
          <p>Locație: {{ demand.city }}<span v-if="demand.administrative && demand.administrative !== 'undefined'">, {{ demand.administrative }}</span> <button @click.prevent="showLocation = !showLocation" class="btn btn-sm btn-default mx-2">Vezi pe hartă</button></p>
          <MapDemandComponent :lat="getLat" :lng="getLng" :key="mapKey" v-if="showLocation" />
          <hr>
          <p>Mesaj:</p>
          <div>{{ demand.message }}</div>

          <!-- Files -->
          <template v-if="!demand.is_bought">
          <div v-if="demand.files && demand.files.length > 0">
            <hr>
            <p>Cererea conține fotografii. Deblochează cererea pentru a fi vizibile.</p>
            <!-- <ListFilesComponent :the_files="demand.files" :the_type_path="'demands'" :key="filesKey" /> -->
          </div>

          <div v-if="demand.attachments && demand.attachments.length > 0">
            <hr>
            <p>Cererea conține fisiere. Deblochează cererea pentru a fi vizibile.</p>
            <!-- <ListFilesComponent :the_files="demand.files" :the_type_path="'demands'" :key="filesKey" /> -->
          </div>
          </template>
          <template v-else>
              <div v-if="demand.files && demand.files.length > 0">
                <hr>
                <ListDemandFiles :the_files="demand.files" :the_type_path="'demands'" :key="filesKey" :type="'images'" />
            </div>

            <div v-if="demand.attachments && demand.attachments.length > 0">
              <hr>
              <ListDemandFiles :the_files="demand.attachments" :the_type_path="'demands'" :key="filesKey" :type="'attachments'" />
            </div>
          </template>
        </div>
        <div class="card-footer">
          <!-- <div class="row">
            <div class="col-lg-6">
              <p>Stare: 
                  <span class="badge badge-success" v-if="demand.state == 1">Activa</span>
                  <span class="badge badge-danger" v-else>Inactiva</span>
              </p>
            </div>
            <div class="col-lg-6">
            </div>
          </div> -->
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
          <p style="font-size: 16px;">Telefon: {{ demand.phone }} <a v-if="demand.is_bought" :href="'tel:' + demand.phone" class="btn btn-success btn-md m-2"><i class="fa fa-phone"></i> Apelează</a></p>
        </div>
        <div class="card-footer" v-if="!demand.is_bought">
          <div class="row">
            <div class="col-lg-6">
            </div>
            <div class="col-lg-6">
              <a v-if="!btnLoading" href="#" class="btn btn-info ml-2 float-right" @click.prevent="unlockDemand"> <i class="ti-unlock"></i> Deblocare cerere (Preț: {{ demand.price }} RON)</a>
              <a v-else href="#" class="btn btn-info ml-2 float-right btn-loading"> <i class="ti-unlock"></i> Deblocare cerere</a>
            </div>
          </div>
        </div>
      </div>

      <RaportDemandComponent v-if="demand.is_bought" :the_demand="demand" />
      <!-- <RaportDemandComponent :the_demand="demand" /> -->


    </div>
  </div>
</template>

<script>
// import ListFilesComponent from "./_modules/ListFilesComponent.vue";
import ListDemandFiles from "../public/ListDemandFiles.vue";

import MapDemandComponent from "./_modules/MapDemandComponent.vue";
import RaportDemandComponent from "./_modules/RaportDemandComponent.vue";


export default {
    name: "ShowDemandForProComponent",

    data(){
      return {
        btnLoading: false,
        mapKey: 'map-component',
        filesKey: 'files-component',
        demand: null,
        showLocation: false
      }
    },

    components: {
      ListDemandFiles,
      MapDemandComponent,
      RaportDemandComponent
    },

    props: {
      the_demand: Object,
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
        // return moment(element).format("DD-MM-YYYY, HH:mm");
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

      unlockDemand: function(){
          // console.log('unlockDemand', this.demand.uuid);
          this.btnLoading = true;


          axios
          .post(`/api/pro/demands/${this.demand.uuid}/unlock`)
          .then(response => {
              if(response.data.success){
                // console.log('raspuns cerere', response.data.demand);

                this.$swal({
                    title: 'Succes',
                    text: "Cererea a fost deblocată cu succes.",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok.',
                });
                
                this.demand = response.data.demand;
                
                if(localStorage.demands){
                  let new_demand_list = JSON.parse(localStorage.demands);
                  new_demand_list = new_demand_list.filter(item => {
                    if(item.id != this.demand.id){
                      return item;
                    }
                  });
  
                  localStorage.setItem('demands', JSON.stringify(new_demand_list));
                }

              } else if(response.data.errors){
                  Vue.$toast.open({
                        message: 'Oups! Am întampinat erori. Incearcă mai târziu.',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
              } else if(response.data.credit_errors){
                  Vue.$toast.open({
                        message: 'Oups! Credit insuficient! Reîncarcă contul pentru a debloca cereri. Solicită un cupon și folosește platforma gratuit! <u><strong><a href="/cupoane/solicitari" class="text-white">Solicită cupon</a></strong></u>',
                        type: 'error',
                        duration: 6000,
                        position: 'bottom'
                    });
              }
          })
          .catch(error => {
              Vue.$toast.open({
                    message: 'Oups! Am întampinat erori. Incearcă mai târziu.',
                    type: 'error',
                    duration: 6000,
                    position: 'bottom'
                });
          })
          .finally(() => {
              this.btnLoading = false;
          });
      },

      
    },

    created(){
      this.demand = this.the_demand;
    }
}
</script>

<style>

</style>