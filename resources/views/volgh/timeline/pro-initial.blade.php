@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">


<!-- map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   
    <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
   
   <style>
   #mapid { height: 180px; }

   </style>
<!-- end map -->

<style>
    .cbp_tmtimeline>li .cbp_tmlabel h2 {font-weight:100!important;}

.img-thumbnail {
   height: 60px;
}

.cbp_tmtime > span:nth-child(1) {
    font-size:12px!important;
    font-weight:100!important;
}

.cbp_tmtime > span:nth-child(2) {
    font-size:12px!important;
    font-weight:400!important;
}

</style>

@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            @if($demand)
            <h1 class="page-title">Conversatie cu {{ $demand->name }} | #{{ $timeline->getDisponibleId() }}</h1>
            @else
            <h1 class="page-title">Conversatie #{{ $timeline->getDisponibleId() }}</h1>
            @endif
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversatie #{{ $timeline->getDisponibleId() }}</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="d-flex justify-content-end">
                        <a onclick="event.preventDefault();document.getElementById('deleteByPro').submit();" class="btn btn-danger btn-sm text-white"><i class="ti-trash"></i> Elimina conversatie</a>           
                        <form 
                            action="{{ route('timeline.delete.pro', $timeline->id) }}" 
                            id="deleteByPro" 
                            method="POST" 
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                <div class="col-md-12">
                    <ul class="cbp_tmtimeline">

                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->created_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->created_at) }}</span></time>
                            <div class="cbp_tmicon bg-info"><i class="ti ti-user"></i></div>

                            @if($demand)
                            <div class="cbp_tmlabel empty">
                                <div class="py-2">
                                    <h2><a href="javascript:void(0);" class="font-weight-bold">@if($demand->user) {{ $demand->user->getName() }} @else {{ $demand->name }} @endif</a> <span>a inceput un nou proiect si are nevoie de un profesionist.</span></h2>
                                    {{-- <p class="text-sm">Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p> --}}
                                </div> 
                                <ul class="demo-accordion accordionjs m-0" data-active-index="false">
                                    <!-- SECTION 1 -->
                                    <li>
                                        <div><h3>Afisati detalii complete despre cerere.</h3></div>
                                        <div>
                                            <div class="row justify-content-center p-4">
                                                <div class="col-md-12 py-4" style="background: white;">
            
                                                    <h4>Subiect: {{ $demand->subject }}</h4>
                                                    <hr>
                                                    
            
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <p class="py-2"><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($demand->created_at) }}</p>
                                                            <p class="py-2"><i class="fa fa-tags" aria-hidden="true"></i> <strong>{{ $demand->firstCategory() }}</strong></p>
                                                            <p class="text-danger py-2"><i class="side-menu__icon ti-bolt"></i> Urgent</p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p class="py-2"><i class="fa fa-user"></i> {{ $demand->name }}</p>
                                                            <p class="py-2"><i class="side-menu__icon ti-location-pin"></i> {{ ucfirst($demand->city) }}</p>
                                                            <p class="py-2"><i class="fa fa-at"></i> {{ $demand->email }}</p>
                                                            <p class="py-2"><i class="fa fa-phone"></i> <a href="tel:{{ $demand->phone }}" rel="nofollow">{{ $demand->phone }}</a></p>
                                                        </div>
                                                    </div>
            
                                                    <hr>

                                                    <div id="mapid"></div>

                                                    <hr>
            
                                            
                                                    <div>
                                                        <h5>Descriere cerere</h5>
                                                        {{ $demand->message }}
                                                    </div>
                                                    
                                                
                                                    <br>
                                                </div><!-- end col-lg-12 -->
                                            </div><!-- end row -->
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <div class="cbp_tmlabel empty">
                                <div class="py-2">
                                    {{-- <h2><a href="javascript:void(0);" class="font-weight-bold">@if($demand->user) {{ $demand->user->getName() }} @else {{ $demand->name }} @endif</a> <span>a inceput un nou proiect si are nevoie de un profesionist.</span></h2> --}}
                                    <h4 class="">Cererea aferenta acestei conversatii a fost eliminata.</h4>
                                </div> 
                            </div>
                            @endif
                        </li>

                        {{-- @if($unlocked_demand)
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($unlocked_demand->pivot->created_at) }}</span>
                            <span>{{ carbonDateToRo($unlocked_demand->pivot->created_at) }}</span></time>
                            <div class="cbp_tmicon bg-success"><i class="ti ti-unlock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                @if($demand->hasUUID())
                                <span>Ati deblocat cererea numarul <a href="{{ route('demands.show.uuid', $demand->uuid) }}">#{{ $demand->uuid }}</a>.</span> 
                                @else
                                <span>Ati deblocat cererea numarul <a href="{{ route('demands.show', $demand->id) }}">#{{ $demand->id }}</a>.</span> 
                                @endif
                                <span>Costul de deblocare: <strong>{{ $demand->getCalculatedPriceNormal() }} RON</strong>.</span>

                            </div>
                        </li> <!-- end unlocked_demand -->
                        @endif --}}


                        @if($unlocked_demand)
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ convertFormatShortCarbonDate($unlocked_demand->created_at) }}</span><span>{{ convertCarbonDateToRo($unlocked_demand->created_at) }}</span></time>
                            <div class="cbp_tmicon bg-success"><i class="ti ti-unlock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <span>Ati deblocat cererea numarul #{{ $unlocked_demand->demand_id }}.</span> 
                                <span>Costul de deblocare: <strong>{{ $demand_cost ? $demand_cost->amount / 100 : 'N/A'}} RON</strong>.</span>

                            </div>
                        </li> <!-- end unlocked_demand -->
                        @endif


                        @if($conversations && $conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @if($conversation->professional_id)
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($conversation->created_at) }}"><span>{{ formatCarbonDate($conversation->created_at) }}</span> <span>{{ carbonDateToRo($conversation->created_at) }}</span></time>
                                    <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
                                    <div class="cbp_tmlabel">
                                        <div class="row">
                                            <div class="col-lg-10">
                                                @if($conversation->professional->user->company)
                                                    <h2><a href="{{ route('user.pro.profile', $conversation->professional->user_id) }}" target="_blank" class="font-weight-bold">{{ $conversation->professional->user->company->name }} <i class="ti ti-new-window font-weight-light"></i></a> <span> ati trimis un mesaj.</span></h2>
                                                @else 
                                                    <h2><a href="javascript:void(0);" class="font-weight-bold">{{ $conversation->professional->user->getName() }}</a> <span> ati trimis un mesaj.</span></h2>
                                                @endif
                                            </div>
                                            <div class="col-lg-2">
                                                
                                                <div class="dropdown float-right">
                                                    <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti-more"></i>
                                                    </a>
                    
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        
                                                        <a onclick="event.preventDefault();document.getElementById('deleteProMessage-{{$conversation->id}}').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                                        
                                                        <form 
                                                            action="{{ route('quotes.destroy', $conversation->id) }}" 
                                                            id="deleteProMessage-{{$conversation->id}}" 
                                                            method="POST" 
                                                            style="display: none;">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="DELETE">
                                                        </form>
                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm">
                                            {{ $conversation->message }}
                                        </p>
                                        <br>
                                        @if($conversation->price)
                                            <h6><strong><i class="ti ti-info-alt"></i> Pret estimat proiect: {{ $conversation->price }} RON.</strong></h6>
                                        @endif
    
    
    
                                        @if($conversation->files && $conversation->files->count() > 0)
                                            <h5 class="mt-6" class="font-weight-light">Fisiere atasate.</h5>
                                            <div class="row">
                                                @foreach($conversation->files as $theFile)
                                                    
                                                    <div class="col-lg-3 col-md-6 col-6">
                                                        @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                                            <a href="{{asset('storage/quotes/' . $theFile->name)}}" data-lightbox="photos">
                                                                <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/quotes/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == 'application/pdf')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == 'text/csv')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == 'application/msword')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
    
                                                            @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @elseif($theFile->mime_type == "text/plain")
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @else
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                                <a href="{{ route('files.download',  ['type' => 'quotes', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                            @endif
                                                    </div>
                                                    
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
    
    
                                </li>
                                @else
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($conversation->created_at) }}"><span>{{ formatCarbonDate($conversation->created_at) }}</span> <span>{{ carbonDateToRo($conversation->created_at) }}</span></time>
                                    <div class="cbp_tmicon bg-default"><i class="ti ti-comment-alt"></i></div>
                                    <div class="cbp_tmlabel">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $conversation->user->getName() }}</a><span>, v-a lasat un mesaj.</span></h2>
                                            </div>
                                            
                                        </div>
                                        <p class="text-sm">{{ $conversation->message }}</p>
                                        <br>

                                        @if($conversation->files && $conversation->files->count() > 0)
                                        <h5 class="mt-6 font-weight-light">Fisiere atasate.</h5>
                                        <div class="row">
                                            @foreach($conversation->files as $theFile)
                                                
                                                <div class="col-lg-3 col-md-6 col-6">
                                                    @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                                        {{-- <a href="javascript:void(0);"> --}}
                                                        <a href="{{asset('storage/client_messages/' . $theFile->name)}}" data-lightbox="photos">
                                                            <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/client_messages/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                                        </a>
                                                        <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/pdf')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'text/csv')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/msword')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>

                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @elseif($theFile->mime_type == "text/plain")
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @else
                                                            <a href="{{URL::asset('storage/client_messages/' . $theFile->name)}}" style="font-size:10px;" target="_blank">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                            <a href="{{ route('files.download',  ['type' => 'client_messages', 'file_name' => $theFile->name]) }}"><i class="fa fa-download" style="color:gray;font-size:18px;"></i></a>
                                                        @endif
                                                </div>
                                                
                                            @endforeach
                                        </div>
                                    @endif
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        @endif


                        @if($demand)
                            @if($timeline->demand->isCompleted())
                            <li>
                                <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->demand->detail->updated_at) }}</span>
                                <span>{{ carbonDateToRo($timeline->demand->detail->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-danger"><i class="ti ti-lock"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->demand->user->getName() }}</a><span> a marcat cererea ca fiind terminata.</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </li> <!-- end unlocked_demand -->
                            @endif
                        @endif

                        @if($timeline->isCompleted())
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->updated_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->updated_at) }}</span></time>
                            <div class="cbp_tmicon bg-gray"><i class="ti ti-lock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->user->getName() }}</a><span> a marcat aceasta conversatie ca fiind terminata.</span></h2>
                                    </div>
                                </div>
                            </div>
                        </li> <!-- end unlocked_demand -->
                        @endif

                        @if($timeline->prospect) 
                            @if($demand)
                                @if($timeline->prospect->professional_id == $professional->id)
                                    <li>
                                        <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->created_at) }}"><span>{{ formatCarbonDate($timeline->prospect->created_at) }}</span>
                                        <span>{{ carbonDateToRo($timeline->prospect->created_at) }}</span></time>
                                        <div class="cbp_tmicon bg-secondary"><i class="ti ti-heart"></i></div>
                                        <div class="cbp_tmlabel empty"> 
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->user->getName() }}</a><span> vrea sa fiti <span class="badge badge-success  mr-1 mb-1 mt-1">castigatorul</span> cererii curente.</span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </li> <!-- end unlocked_demand -->
                                @endif
                            @endif

                            @if($timeline->prospect->isOnHold())
                            <li>
                                {{-- <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->demand->winner->created_at) }}</span> --}}
                                {{-- <span>{{ carbonDateToRo($timeline->demand->winner->created_at) }}</span></time> --}}
                                <div class="cbp_tmicon bg-warning"><i class="ti ti-time"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    @if($demand)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><span>Raspunsul dumneavoastra pentru castigarea cererii #{{ $timeline->demand->id }} este in asteptare.</span></h2>
                                            <br>
                                            <a onclick="event.preventDefault();document.getElementById('acceptDemand').submit();" class="btn btn-success text-white"><i class="ti-thumb-up"></i> Accepta propunerea</a>
                                            <a onclick="event.preventDefault();document.getElementById('refuseDemand').submit();" class="btn btn-danger text-white"><i class="ti-thumb-down"></i> Refuza propunerea</a>
                                   
                                            <form 
                                                action="{{ route('demands.pro.accept', ['demand_id' => $timeline->demand->id, 'prospect_id' => $timeline->prospect->id]) }}" 
                                                id="acceptDemand" 
                                                method="POST" 
                                                style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>

                                            <form 
                                                action="{{ route('demands.pro.refuse', ['demand_id' => $timeline->demand->id, 'prospect_id' => $timeline->prospect->id]) }}" 
                                                id="refuseDemand" 
                                                method="POST" 
                                                style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><span class="text-muted">Cererea a fost eliminata.</span><span>Raspunsul dumneavoastra pentru castigarea cererii #{{ $timeline->demand_id }} este in asteptare.</span></h2>
                                            <br>
                                            <a onclick="event.preventDefault();document.getElementById('acceptDemand').submit();" class="btn btn-success text-white"><i class="ti-thumb-up"></i> Accepta propunerea</a>
                                            <a onclick="event.preventDefault();document.getElementById('refuseDemand').submit();" class="btn btn-danger text-white"><i class="ti-thumb-down"></i> Refuza propunerea</a>
                                   
                                            <form 
                                                action="{{ route('demands.pro.accept', ['demand_id' => $timeline->demand_id, 'prospect_id' => $timeline->prospect->id]) }}" 
                                                id="acceptDemand" 
                                                method="POST" 
                                                style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>

                                            <form 
                                                action="{{ route('demands.pro.refuse', ['demand_id' => $timeline->demand_id, 'prospect_id' => $timeline->prospect->id]) }}" 
                                                id="refuseDemand" 
                                                method="POST" 
                                                style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </li> 
                            @elseif($timeline->prospect->isAccepted())
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                        <h2><a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> <span>ati acceptat propunerea de a fi castigatorul cererii #{{ $timeline->demand_id }}. Felicitari!</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </li> 

                            @elseif($timeline->prospect->isRefused())
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-gray"><i class="ti ti-na"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @if($demand)
                                            <h2><span>Ati respins propunerea clientului pentru cererea #{{ $demand->id }} 
                                                @if($demand->hasWinner())
                                                Cererea curenta a fost castigata de un profesionist. Va dorim spor si succes in celelalte proiecte.</span></h2>
                                                @endif
                                            @else
                                            <h2><span>Ati respins propunerea clientului pentru cererea #{{ $timeline->demand_id }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li> 

                            @elseif($timeline->prospect->isRefusedByClient())
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-gray"><i class="ti ti-na"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @if($demand)
                                            <h2><span>Clientul a incheiat deja o intelegere. Conversatia este acum inactiva. 
                                                @if($demand->hasWinner())
                                                Cererea curenta a fost castigata de un profesionist. Va dorim spor si succes in celelalte proiecte.</span></h2>
                                                @endif
                                            @else
                                            <h2><span>Clientul a incheiat deja o intelegere. Conversatia este acum inactiva.</span></h2>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li> 
                            @endif
                        @endif

                        {{-- @if($demand)
                            @if($timeline->demand->hasWinner()) 
                                @if($timeline->demand->winner->professional_id == $professional->id)
                                    <li>
                                        <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                            <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                        <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                        <div class="cbp_tmlabel empty"> 
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h2><span>Felicitari, <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a>, sunteti castigatorul final al cererii #{{ $demand->id }}. Va dorim succes si spor in executia proiectului.</span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @endif --}}

                        {{-- winner --}}
                        @if($demand)
                            @if($winner)
                                @if($winner->professional_id == $professional->id)
                                    <li>
                                        <time class="cbp_tmtime" datetime="{{ formatCarbonDate($winner->updated_at) }}"><span>{{ formatCarbonDate($winner->updated_at) }}</span>
                                            <span>{{ carbonDateToRo($winner->updated_at) }}</span></time>
                                        <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                        <div class="cbp_tmlabel empty"> 
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h2><span>Felicitari, <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a>, sunteti castigatorul final al cererii #{{ $timeline->demand_id }}. Va dorim succes si spor in executia proiectului.</span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @else
                            @if($winner)
                                @if($winner->professional_id == $professional->id)
                                    <li>
                                        <time class="cbp_tmtime" datetime="{{ formatCarbonDate($winner->updated_at) }}"><span>{{ formatCarbonDate($winner->updated_at) }}</span>
                                            <span>{{ carbonDateToRo($winner->updated_at) }}</span></time>
                                        <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                        <div class="cbp_tmlabel empty"> 
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h2><span>Felicitari, <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a>, cererea #{{ $timeline->demand_id }} a fost castigata de dumneavoastra.</span></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @endif

                        @if($timeline->hasReview()) 
                            <li class="">
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->review->created_at) }}"><span>{{ formatCarbonDate($timeline->review->created_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->review->created_at) }}</span></time>
                                <div class="cbp_tmicon bg-warning"><i class="ti ti-star"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card shadow-none">
                                                <div class="card-header">
                                                    @if($demand)
                                                    <h3 class="card-title">Feedback-ul lasat de {{ $timeline->demand->user->getName() }}.</h3>
                                                    @else
                                                    <h3 class="card-title">Feedback-ul lasat de catre client.</h3>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <h5><span>Rating: {{ $timeline->review->rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                                                    <br>
                                                    <h6>Mesaj:</h6>
                                                    <p>
                                                        {{ $timeline->review->message }}
                                                    </p>
                                                </div>
                                            </div> <!-- end card -->

                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif {{--  hasReview --}}

                    </ul>


                    {{-- @else
                    <li>
                        <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                        <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                        <div class="cbp_tmlabel empty"> 
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2><a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a><span> ati refuzat propunerea de a fi castigatorul cererii #{{ $timeline->demand->id }}.</span></h2>
                                </div>
                            </div>
                        </div>
                    </li>  --}}


                    <!-- comunicare PRO -->
                    @if($demand)
                        @if(auth()->user()->isPro() && $unlocked_demand)
                            @if($timeline->isActive())
                        <div class="card">TIP FIRMA
                            <div class="card-header">
                                <h3 class="card-title">Alegeti o metoda de a comunica cu <strong>@if($demand->user) {{ $demand->user->getName() }} @else {{ $demand->name }} @endif</strong></h3>
                            </div>
                            <div class="card-body p-6">
                                <div class="panel panel-primary">
                                    <div class="tab-menu-heading">
                                        <div class="tabs-menu ">
                                            <!-- Tabs -->
                                            <ul class="nav panel-tabs">
                                                <li ><a href="#tab1" class="active" data-toggle="tab"><i class="ti ti-comment-alt"></i> Mesaj</a></li>
                                                <li><a href="#tab2" data-toggle="tab"><i class="ti ti-file"></i> Cotatie de pret</a></li>
                                                <li><a href="#tab3" data-toggle="tab"><i class="ti ti-image"></i> Fotografii</a></li>
                                                <li><a href="#tab4" data-toggle="tab"><i class="ti ti-info-alt"></i> Alta metoda</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body tabs-menu-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active " id="tab1">
                                                
                                                <form method="POST" action="{{ route('demand.quote.store.message.many', [$demand, $timeline]) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div class="form-group">
                                                                <label for="message_one">Mesajul dumneavoastra</label>
                                                                <textarea class="form-control @error('message_one') is-invalid @enderror" id="message_one" name="message_one" rows="10">{{ old('message_one') }}</textarea>
                                                                @error('message_one')
                                                                    <p class="small text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <div class="form-label">Atasati fisiere (optional)</div>
                                                                <div class="custom-file">
                                                                    {{-- <input type="file" class="form-control custom-file-input @error('files_quote') is-invalid @enderror" class="custom-file-input" name="files_quote"> --}}
                                                                    <input type="file" class="form-control custom-file-input @error('files_quote') is-invalid @enderror" class="custom-file-input" name="files_message[]" multiple="multiple">
                                                                    <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisiere</label>
                                                                    <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                                    {{-- <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p> --}}
                                                                </div>
            
                                                                @error('files_message')
                                                                    <p class="small text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                    
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-azure btn-block mt-4"><i class="fa fa-send"></i> Trimite mesaj </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="tab-pane" id="tab2">
                                                
                                                <form method="POST" action="{{ route('demand.quote.store.many', [$demand, $timeline]) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-7">
                                                            <div class="form-group">
                                                                <label for="message">Mesajul dumneavoastra</label>
                                                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="10">{{ old('message') }}</textarea>
                                                                @error('message')
                                                                    <p class="small text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group">
                                                                <label for="price">Estimare Pret total</label>
                                                                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="20.000" value="{{ old('price') }}">
                                                                @error('price')
                                                                    <p class="small text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="form-label">Atasati fisiere (optional)</div>
                                                                <div class="custom-file">
                                                                    {{-- <input type="file" class="form-control custom-file-input @error('files_quote') is-invalid @enderror" class="custom-file-input" name="files_quote"> --}}
                                                                    <input type="file" class="form-control custom-file-input @error('files_quote') is-invalid @enderror" class="custom-file-input" name="files_quote[]" multiple="multiple">
                                                                    <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisiere</label>
                                                                    <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                                    {{-- <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p> --}}
                                                                </div>
            
                                                                @error('files_quote')
                                                                    <p class="small text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                    
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-azure btn-block mt-4"><i class="fa fa-send"></i> Trimite cotatie de pret </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="tab-pane " id="tab3">
                                                <p>over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
                                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et</p>
                                            </div>
                                            <div class="tab-pane  " id="tab4">
                                                <p>page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like</p>
                                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- TABS-END -->
                            @endif
                        @endif
                    @endif {{-- end of $demand --}}

                    <!-- end comunicare -->

                </div><!-- end col-lg-12 -->
            </div>
            <!-- ROW-1 CLOSED -->
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
</div>
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/accordion/accordion.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>


@if($demand)
<script>
    

    $lng = {{ $demand->lng }};
    $lat = {{ $demand->lat }};

    var mymap = L.map('mapid').setView([$lat, $lng], 10);

    
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: "{{ config('services.mapbox.api_key') }}"
    }).addTo(mymap);
    
    var marker = L.marker([$lat, $lng]).addTo(mymap);
    
    var circle = L.circle([$lat, $lng], {
        color: 'green',
        fillColor: 'green',
        fillOpacity: 0.1,
        radius: 8000
    }).addTo(mymap);
    
</script>
@endif

@endsection