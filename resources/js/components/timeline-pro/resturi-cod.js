<div class="cbp_tmlabel">

                <div class="row">
                    <div class="col-lg-10">
                        <h2><a href="javascript:void(0);" class="font-weight-bold" style="font-size:14px;">{{ pro.complete_name }}</a> <span> ati trimis un mesaj.</span></h2>
                    </div>
                    <div class="col-lg-2">
                        
                        <div class="dropdown float-right">
                            <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti-more"></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                
                                <a class="dropdown-item" @click="deleteQuote(conversation.id)"><i class="ti-trash"></i> Elimina</a>

                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="text-sm">
                    {{ conversation.message }}
                </p>

                <template v-if="conversation.files && conversation.files.length > 0">
                    <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
                    <list-files 
                    :current_user="the_current_user" 
                    :conversation_type="conversation.type" 
                    :the_type_path="'quotes'" 
                    :the_attached_files="conversation.files"
                    >
                    </list-files>
                </template>
            </div>














            
























<div class="cbp_tmlabel empty" v-if="demand">
                    <div class="py-2">
                        <h2 v-if="getClient"><a href="javascript:void(0);" class="font-weight-bold">{{ getClient.complete_name }}</a> <span>a inceput un nou proiect si are nevoie de un profesionist.</span></h2>
                    </div> 
                    <ul class="demo-accordion accordionjs m-0" data-active-index="false">
                        <!-- SECTION 1 -->
                        <li>
                            <div><h3>Afisati detalii complete despre cererea <span v-if="demand">#{{ demand.uuid }}</span><span v-else><i class="side-menu__icon ti-na"></i> Oups, cerere eliminata</span>.</h3></div>
                            <div>
                                <div class="row justify-content-center p-4">
                                    <div class="col-md-12 py-4" style="background: white;">

                                        <h4>Subiect: {{ demand.subject }}</h4>
                                        <hr>
                                        

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="py-2"><i class="side-menu__icon ti-time"></i> {{ formatElementTimeMethod(demand) }}</p>
                                                <p class="py-2" v-if="demand.categories"><i class="fa fa-tags" aria-hidden="true"></i> <strong v-for="(category, index) in demand.categories" :key="category.id">{{ category.name }}<template v-if="index != demand.categories.length - 1">, </template></strong></p>
                                                <p class="py-2"><i class="fa fa-at"></i> {{ demand.email }}</p>
                                                <!-- <p class="text-danger py-2"><i class="side-menu__icon ti-bolt"></i> Urgent</p> -->
                                            </div>
                                            <div class="col-lg-6">
                                                <p class="py-2" v-if="getClient"><i class="fa fa-user"></i> {{ getClient.complete_name }}</p>
                                                <p class="py-2"><i class="side-menu__icon ti-location-pin"></i> {{ demand.city }}</p>
                                                <p class="py-2"><i class="fa fa-phone"></i> <a :href="'tel:' + demand.phone" rel="nofollow">{{ demand.phone }}</a></p>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- <div id="mapid"></div> -->
                                        <map-demand-component v-if="demand" :accessTokenMap="the_accessTokenMap" :lat="demand.lat" :lng="demand.lng"></map-demand-component>

                                        <hr>

                                
                                        <div>
                                            <h5>Descriere cerere</h5>
                                            {{ demand.message }}
                                        </div>
                                        
                                    
                                        <br>
                                    </div><!-- end col-lg-12 -->
                                </div><!-- end row -->
                            </div>
                        </li>
                    </ul>
                </div>





<!-- demand -->










<template v-if="conversation.type == 'Winner'">
            <template v-if="conversation.professional_id == pro.professional_id">
            <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
            <div class="cbp_tmlabel empty"> 
                <div class="row">
                    <div class="col-lg-12">
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
                </div>
            </div>
            </template>

            <template v-else>
                <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
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

        </template>