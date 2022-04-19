@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">



<style>
    .cbp_tmtimeline>li .cbp_tmlabel h2 {font-weight:100!important;}

    .cbp_tmtime > span:nth-child(1) {
        font-size:12px!important;
        font-weight:100!important;
    }

    .cbp_tmtime > span:nth-child(2) {
        font-size:12px!important;
        font-weight:400!important;
    }


.success-box {
  margin:50px 0;
  padding:10px 10px;
  border:1px solid #eee;
  background:#f9f9f9;
}

.success-box img {
  margin-right:10px;
  display:inline-block;
  vertical-align:top;
}

.success-box > div {
  vertical-align:top;
  display:inline-block;
  color:#888;
}



/* Rating Star Widgets Style */
.rating-stars ul {
  list-style-type:none;
  padding:0;
  
  -moz-user-select:none;
  -webkit-user-select:none;
}
.rating-stars ul > li.star {
  display:inline-block;
  
}

/* Idle State of the stars */
.rating-stars ul > li.star > i.fa {
  font-size:2.5em; /* Change the size of the stars */
  color:#ccc; /* Color on idle state */
}

/* Hover state of the stars */
.rating-stars ul > li.star.hover > i.fa {
  color:#FFCC36;
}

/* Selected state of the stars */
.rating-stars ul > li.star.selected > i.fa {
  color:#FF912C;
}


.img-thumbnail {
    height: 60px;
}

</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Conversatie</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversatie</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-md-12">
                    <ul class="cbp_tmtimeline">

                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->created_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->created_at) }}</span></time>
                            <div class="cbp_tmicon bg-info"><i class="ti ti-user"></i></div>
                            <div class="cbp_tmlabel empty">
                                <div class="py-2">
                                <h2><a href="javascript:void(0);" class="font-weight-bold">{{ $demand->user->getName() }}</a><span>, ati inceput un nou proiect si ati lansat urmatoarea cerere (@if($demand->hasUUID()) <a href="{{ route('demands.show.uuid', $demand->uuid) }}">#{{ $demand->getDisponibleId() }}</a> @else <a href="{{ route('demands.show', $demand->id) }}">#{{ $demand->getDisponibleId() }}</a> @endif).</span></h2>
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
                        </li>

                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($unlocked_demand->pivot->created_at) }}</span>
                            <span>{{ carbonDateToRo($unlocked_demand->pivot->created_at) }}</span></time>
                            <div class="cbp_tmicon bg-success"><i class="ti ti-unlock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <h2><a class="font-weight-bold" href="{{ route('user.pro.profile', $professional->user_id) }}" target="_blank">{{ $professional->user->company ? $professional->user->company->name : $professional->user->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> <span> a interactionat cu cererea dumneavoastra.</span></h2>
                            </div>
                        </li> <!-- end unlocked_demand -->


                        @if($conversations && $conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @if($conversation->professional_id)
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($conversation->created_at) }}"><span>{{ formatCarbonDate($conversation->created_at) }}</span> <span>{{ carbonDateToRo($conversation->created_at) }}</span></time>
                                    <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
                                    <div class="cbp_tmlabel">
                                        @if($conversation->professional->user->company)
                                            <h2><a href="{{ route('user.pro.profile', $conversation->professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $conversation->professional->user->company->name }} <i class="ti ti-new-window font-weight-light"></i></a> <span> v-a trimis un mesaj.</span></h2>
                                        @else 
                                            <h2><a href="javascript:void(0);" class="text-primary font-weight-bold">{{ $conversation->professional->user->getName() }}</a> <span> v-a trimis un mesaj.</span></h2>
                                        @endif
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
                                                <div class="col-lg-10">
                                                    <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $conversation->user->getName() }}</a><span>, ati trimis un mesaj catre {{ $professional->name }}.</span></h2>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="dropdown float-right">
                                                        <a class="btn btn-default btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ti-more"></i>
                                                        </a>
                        
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                           
                                                            <a onclick="event.preventDefault();document.getElementById('deleteClientMessage-{{$conversation->id}}').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                                           
                                                            <form 
                                                                action="{{ route('clientMessages.destroy', $conversation->id) }}" 
                                                                id="deleteClientMessage-{{$conversation->id}}" 
                                                                method="POST" 
                                                                style="display: none;">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="DELETE">
                                                            </form>
                        
                                                        </div>
                        
                        
                                                    </div>
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


                        @if($timeline->demand->isCompleted())
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->demand->detail->updated_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->demand->detail->updated_at) }}</span></time>
                            <div class="cbp_tmicon bg-danger"><i class="ti ti-lock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->demand->user->getName() }}</a><span>, ati marcat cererea ca fiind terminata.</span></h2>
                                    </div>
                                </div>
                            </div>
                        </li> <!-- end unlocked_demand -->
                        @endif


                        @if($timeline->isCompleted())
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->updated_at) }}</span>
                            <span>{{ carbonDateToRo($timeline->updated_at) }}</span></time>
                            <div class="cbp_tmicon bg-gray"><i class="ti ti-lock"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->user->getName() }}</a><span>, ati marcat aceasta conversatie ca fiind terminata.</span></h2>
                                    </div>
                                </div>
                            </div>
                        </li> <!-- end unlocked_demand -->
                        @endif


                        @if($timeline->prospect) 
                            @if($timeline->prospect->professional_id == $professional->id)
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->created_at) }}"><span>{{ formatCarbonDate($timeline->prospect->created_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->prospect->created_at) }}</span></time>
                                    <div class="cbp_tmicon bg-secondary"><i class="ti ti-heart"></i></div>
                                    <div class="cbp_tmlabel empty"> 
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2><a href="javascript:void(0);" class="text-default font-weight-bold">{{ $timeline->user->getName() }}</a><span>, i-ati propus profesionistului <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> sa fie <span class="badge badge-success  mr-1 mb-1 mt-1">castigatorul</span> cererii curente.</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </li> <!-- end unlocked_demand -->
                            @endif

                            @if($timeline->prospect->isOnHold())
                            <li>
                                {{-- <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ formatCarbonDate($timeline->demand->winner->created_at) }}</span> --}}
                                {{-- <span>{{ carbonDateToRo($timeline->demand->winner->created_at) }}</span></time> --}}
                                <div class="cbp_tmicon bg-warning"><i class="ti ti-time"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><span>Raspunsul <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> pentru castigarea cererii #{{ $timeline->demand->id }} este in asteptare.</span></h2>
                                        </div>
                                    </div>
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
                                            <h2><a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> <span>a acceptat propunerea dumneavoastra. Cererea #{{ $timeline->demand->id }} poate fi castigata de catre acest profesionist.</h2>
                                                <br>
                                                <a onclick="event.preventDefault();document.getElementById('confirmProspect').submit();" class="btn btn-success text-white"><i class="ti-thumb-up"></i> Confirma</a>
                                                <a onclick="event.preventDefault();document.getElementById('refuseProspect').submit();" class="btn btn-danger text-white"><i class="ti-thumb-down"></i> Refuza</a>
                                    
                                                <form 
                                                    action="{{ route('demands.winner.confirm', ['demand_id' => $timeline->demand->id, 'prospect_id' => $timeline->prospect->id, 'professional_id' => $timeline->professional_id]) }}" 
                                                    id="confirmProspect" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form>

                                                <form 
                                                    action="{{ route('demands.prospect.refuse', ['demand_id' => $timeline->demand->id, 'prospect_id' => $timeline->prospect->id]) }}" 
                                                    id="refuseProspect" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
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
                                            <h2><span><a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> a respins propunerea dumneavoastra pentru cererea #{{ $demand->id }}. Alegeti un alt profesionist cu care puteti stabili o intelegere. Mult succes!</span></h2></div>
                                    </div>
                                </div>
                            </li> 

                            {{-- @elseif($timeline->prospect->isConfirmedByClient())
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><span>Felicitari! L-ati ales pe profesionistul <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> castigatorul final al cererii #{{ $timeline->demand->id }}. Succes si spor in executia proiectului.</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </li> --}}
                            @elseif($timeline->prospect->isRefusedByClient())
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                    <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                <div class="cbp_tmicon bg-gray"><i class="ti ti-na"></i></div>
                                <div class="cbp_tmlabel empty"> 
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2><span>Ati refuzat profesionistul <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a>. Conversatia este acum inactiva. 
                                                @if($demand->hasWinner())
                                                Cererea curenta a fost castigata de profesionistul <a href="{{ route('user.pro.profile', $demand->winner->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $demand->winner->professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a>.</span></h2>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </li> 
                            
                            @endif
                        @endif



                        {{-- @else
                        <li>
                            <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                            <div class="cbp_tmicon bg-danger"><i class="ti ti-na"></i></div>
                            <div class="cbp_tmlabel empty"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h2><span>In asteptarea unui raspuns...</span></h2>
                                    </div>
                                </div>
                            </div>
                        </li>  --}}





                        @if($timeline->demand->hasWinner()) 
                            @if($timeline->demand->winner->professional_id == $professional->id)
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($timeline->prospect->updated_at) }}"><span>{{ formatCarbonDate($timeline->prospect->updated_at) }}</span>
                                        <span>{{ carbonDateToRo($timeline->prospect->updated_at) }}</span></time>
                                    <div class="cbp_tmicon bg-success"><i class="ti ti-check"></i></div>
                                    <div class="cbp_tmlabel empty"> 
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2><span>Felicitari! L-ati ales pe profesionistul <a href="{{ route('user.pro.profile', $professional->user_id) }}" class="text-primary font-weight-bold" target="_blank">{{ $professional->getName() }} <i class="ti ti-new-window font-weight-light"></i></a> castigatorul final al cererii #{{ $timeline->demand->id }}. Succes si spor in executia proiectului.</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @if(!$timeline->demand->hasReview())
                                <li class="col-lg-9 offset-lg-3 px-0">
                                    <div class="empty"> 
                                    
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fa fa-star"></i> Lasati-i un feedback profesionistului {{ $professional->getName() }}.</h3>
                                            </div>
                                            <div class="card-body">
                                            <form action="{{ route('reviews.save', $timeline->demand->id) }}" method="POST">
                                                @csrf
                                                {{-- <input type="text" disabled="disabled" class="form-control text-center col-lg-6 offset-lg-3 my-2 bg-white" name="rating-stars-value" id="rating-stars-value" value='1'> --}}
                                                <input type="number" class="form-control text-center col-lg-6 offset-lg-3 my-2 bg-white" name="rating-stars-value" id="rating-stars-value" value>
                                                <input type="hidden" name="professional_id" id="professional_id" value="{{ $timeline->professional_id }}">
                                                <input type="hidden" name="timeline_id" id="timeline_id" value="{{ $timeline->id }}">
                                                <div class='rating-stars text-center'>
                                                    <ul id='stars'>
                                                        <li class='star' title='Poor' data-value='1'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Fair' data-value='2'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Good' data-value='3'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Excellent' data-value='4'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='WOW!!!' data-value='5'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                    </ul>
                                                    @error('rating-stars-value')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class='success-box'>
                                                <div class='clearfix'></div>
                                                <i class='fa fa-check'></i>
                                                <div class='text-message'></div>
                                                <div class='clearfix'></div>
                                                </div>
                                                <br>
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label for="message_review">Cum vi s-a parut colaborarea cu acest profesionist? Lasati un feedback cu cateva cuvinte despre experienta dumneavoastra...</label>
                                                            <textarea class="form-control @error('message_review') is-invalid @enderror" id="message_review" name="message_review" rows="8">{{ old('message_review') }}</textarea>
                                                            @error('message_review')
                                                                <p class="small text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-azure btn-block mt-4"><i class="fa fa-star"></i> Trimite feedback </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            </div>
                                        </div>
                                    

                                        
                                    </div>
                                </li>
                                @endif {{-- end review--}}
                            @endif
                        @endif {{-- end hasWinner --}}


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
                                                    <h3 class="card-title">Feedback-ul lasat pentru {{ $professional->getName() }}.</h3>
                                                </div>
                                                <div class="card-body">
                                                    <h5><span>Rating: {{ $timeline->review->rating }} <i class="fa fa-star text-yellow"></i></span></h5>
                                                    <br>
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



                    <!-- comunicare -->
                    @if($demand->belongsToMe())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Alegeti o metoda de a comunica cu <strong>@if(!empty($timeline->professional->name)) <a href="{{ route('user.pro.profile', $timeline->professional->user_id) }}" target="_blank">{{ $timeline->professional->name }} <i class="ti ti-new-window font-weight-light"></i></a> @else {{ $timeline->professional->user->getName() }} @endif</strong></h3>
                            <div class="card-options">
                                {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                                
                                {{-- Daca nu exista in PRospects, arata buton --}}
                                {{-- @if($timeline->prospect->professional_id != $timeline->professional_id)  --}}
                                @if(!$demand->hasProspect($timeline->professional->id))
                                    <a onclick="event.preventDefault();document.getElementById('prospectDemand').submit();" class="btn btn-sm btn-success text-white mx-2"><i class="fa fa-check"></i> Desemneaza castigator </a>
                                    <form 
                                        action="{{ route('demands.prospect', ['demand_id' => $timeline->demand->id, 'professional_id' => $professional->id, 'timeline_id' => $timeline->id]) }}" 
                                        id="prospectDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                {{-- @else
                                    
                                    @if($timeline->demand->winner->status == '2' && $timeline->demand->winner->professional_id != $timeline->professional_id)
                                    <a onclick="event.preventDefault();document.getElementById('winnerNewDemand').submit();" class="btn btn-sm btn-success text-white mx-2"><i class="fa fa-check"></i> Desemneaza nou castigator </a>
                                    <form 
                                        action="{{ route('demands.winner.new', ['demand_id' => $timeline->demand->id, 'professional_id' => $professional->id]) }}" 
                                        id="winnerNewDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    @endif --}}
                                @endif


                                <div class="dropdown float-right">
                                    <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti-more"></i>
                                    </a>
    
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                       
                                        <a onclick="event.preventDefault();document.getElementById('completeDemand').submit();" class="dropdown-item">
                                            @if($demand->isActive())
                                                <i class="ti-check"></i> Marcheaza cerere ca terminata
                                            @else
                                                <i class="ti-check"></i> Marcheaza cerere ca activa
                                            @endif
                                        </a>
                                       
                                        <form 
                                            action="{{ route('demands.changeStatus', $demand->id) }}" 
                                            id="completeDemand" 
                                            method="POST" 
                                            style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>

                                        <a onclick="event.preventDefault();document.getElementById('statusTimeline').submit();" class="dropdown-item">
                                            @if($timeline->isActive())
                                                <i class="ti-check"></i> Marcheaza conversatie ca terminata
                                            @else
                                                <i class="ti-check"></i> Marcheaza conversatie ca activa
                                            @endif
                                        </a>
                                       
                                        <form 
                                            action="{{ route('timeline.change.status', $timeline->id) }}" 
                                            id="statusTimeline" 
                                            method="POST" 
                                            style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
    
                                    </div>
    
    
                                </div>
    
                            </div>
                        </div>

                        <div class="card-body p-6">
                            <div class="panel panel-primary">
                                @if($timeline->isActive())
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu ">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li ><a href="#tab1" class="active" data-toggle="tab"><i class="ti ti-comment-alt"></i> Mesaj</a></li>
                                            <li><a href="#tab3" data-toggle="tab"><i class="ti ti-image"></i> Fotografii</a></li>
                                            <li><a href="#tab4" data-toggle="tab"><i class="ti ti-info-alt"></i> Alta metoda</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active " id="tab1">
                                            
                                            <form method="POST" action="{{ route('client.messages.store.many', $timeline->id) }}" enctype="multipart/form-data">
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
                                                            <div class="form-label">Atasati fisier (optional)</div>
                                                            <div class="custom-file">
                                                                {{-- <input type="file" class="form-control custom-file-input @error('files_client') is-invalid @enderror" class="custom-file-input" name="files_client"> --}}
                                                                <input type="file" class="form-control custom-file-input @error('files_client') is-invalid @enderror" class="custom-file-input" name="files_client[]" multiple="multiple">
                                                                <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                                {{-- <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p> --}}
                                                            </div>
            
                                                            @error('files_client')
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
                                @else
                                <p class="text-center text-muted">Conversatia este marcata ca inactiva.</p>
                                @endif
                            </div>
                        </div>

                    </div><!-- TABS-END -->
                        
                    @endif
                    <!-- end comunicare client -->


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

<script>

    $(document).ready(function(){
  
  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
    
    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    var msg = "";
    if (ratingValue > 1) {
        msg = "Multumim! Ne-ati oferit " + ratingValue + " stele.";
    }
    else {
        msg = "Facem tot posibilul sa ne imbunatatim serviciile. Ne pare rau, sincer, ca ne-ati oferit doar " + ratingValue + " stele.";
    }
    responseMessage(msg);
    // $rating_stars_value = $('#rating-stars-value');
    document.getElementById('rating-stars-value').setAttribute('value', ratingValue);
    // $rating_stars_value.val(ratingValue);
    
  });
  
  
});


function responseMessage(msg) {
  $('.success-box').fadeIn(200);  
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
}

</script>
@endsection