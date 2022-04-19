@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Timeline</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Components</a></li>
                <li class="breadcrumb-item active" aria-current="page">Timeline</li>
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
                                    <h2><a href="javascript:void(0);">{{ $demand->name }}</a> <span>a inceput un nou proiect si are nevoie de un profesionist.</span></h2>
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
                                <span>Ati deblocat cererea numarul <a href="{{ route('demands.show', $demand->id) }}">#{{ $demand->id }}</a>.</span> 
                                <span>Costul de deblocare: <strong>{{ $demand->getCost() }} RON</strong>.</span>

                            </div>
                        </li> <!-- end unlocked_demand -->


                        @if($conversations && $conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @if($conversation->professional_id)
                                <li>
                                    <time class="cbp_tmtime" datetime="{{ formatCarbonDate($conversation->created_at) }}"><span>{{ formatCarbonDate($conversation->created_at) }}</span> <span>{{ carbonDateToRo($conversation->created_at) }}</span></time>
                                    <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
                                    <div class="cbp_tmlabel">
                                        @if($conversation->professional)
                                            <h2><a href="{{ route('user.pro.profile', $conversation->professional->user_id) }}" target="_blank">{{ $conversation->professional->name }} <i class="ti ti-new-window font-weight-light"></i></a> <span> a trimis un mesaj.</span></h2>
                                        @else 
                                            <h2><a href="javascript:void(0);">{{ $conversation->professional->user->getName() }}</a> <span> a trimis un mesaj.</span></h2>
                                        @endif
                                        <p class="text-sm">
                                            {{ $conversation->message }}
                                        </p>
                                        <br>
                                        @if($conversation->price)
                                            <h6><strong><i class="ti ti-info-alt"></i> Pret estimat proiect: {{ $conversation->price }} RON.</strong></h6>
                                        @endif
    
    
    
                                        @if($conversation->files && $conversation->files->count() > 0)
                                            <h5 class="mt-6">Fisiere atasate.</h5>
                                            <div class="row">
                                                @foreach($conversation->files as $theFile)
                                                    
                                                    <div class="col-lg-3 col-md-6 col-6">
                                                        @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                                            <a href="javascript:void(0);">
                                                                <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/quotes/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                                            </a>
                                                            @elseif($theFile->mime_type == 'application/pdf')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @elseif($theFile->mime_type == 'text/csv')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @elseif($theFile->mime_type == 'application/msword')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
    
                                                            @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @elseif($theFile->mime_type == "text/plain")
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
                                                            @else
                                                                <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                    <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                                </a>
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
                                        <div class="cbp_tmicon bg-primary"><i class="zmdi zmdi-label"></i></div>
                                        <div class="cbp_tmlabel">
                                        <h2><a href="javascript:void(0);">{{ $conversation->user->getName() }}</a> <span>a trimis un mesaj.</span></h2>
                                            <p class="text-sm">{{ $conversation->message }}</p>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endif




                        <p>ALTCEVA AICI</p>
                        @if($quotes && $quotes->count() > 0)
                            @foreach($quotes as $quote)
                            <li>
                                <time class="cbp_tmtime" datetime="{{ formatCarbonDate($quote->created_at) }}"><span>{{ formatCarbonDate($quote->created_at) }}</span> <span>{{ carbonDateToRo($quote->created_at) }}</span></time>
                                <div class="cbp_tmicon bg-primary"><i class="ti ti-comment-alt"></i></div>
                                <div class="cbp_tmlabel">
                                    @if($quote->professional)
                                        <h2><a href="{{ route('user.pro.profile', $quote->professional->user_id) }}" target="_blank">{{ $quote->professional->name }} <i class="ti ti-new-window font-weight-light"></i></a> <span> a trimis un mesaj.</span></h2>
                                    @else 
                                        <h2><a href="javascript:void(0);">{{ $quote->professional->user->getName() }}</a> <span> a trimis un mesaj.</span></h2>
                                    @endif
                                    <p class="text-sm">
                                        {{ $quote->message }}
                                    </p>
                                    <br>
                                    @if($quote->price)
                                        <h6><strong><i class="ti ti-info-alt"></i> Pret estimat proiect: {{ $quote->price }} RON.</strong></h6>
                                    @endif



                                    @if($quote->files && $quote->files->count() > 0)
                                        <h5 class="mt-6">Fisiere atasate.</h5>
                                        <div class="row">
                                            @foreach($quote->files as $theFile)
                                                
                                                <div class="col-lg-3 col-md-6 col-6">
                                                    @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                                        <a href="javascript:void(0);">
                                                            <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/quotes/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                                        </a>
                                                        @elseif($theFile->mime_type == 'application/pdf')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @elseif($theFile->mime_type == 'text/csv')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @elseif($theFile->mime_type == 'application/msword')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>

                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @elseif($theFile->mime_type == "text/plain")
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @else
                                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                                            </a>
                                                        @endif
                                                </div>
                                                
                                            @endforeach
                                        </div>
                                    @endif
                                </div>


                            </li>
                            @endforeach
                        @endif


                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-04T03:45"><span>03:45 AM</span> <span>Today</span></time>
                            <div class="cbp_tmicon bg-primary"><i class="zmdi zmdi-label"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Art Ramadani</a> <span>posted a status update</span></h2>
                                <p class="text-sm">Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                            </div>
                        </li>
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-03T13:22"><span>01:22 PM</span> <span>Yesterday</span></time>
                            <div class="cbp_tmicon bg-success"> <i class="zmdi zmdi-case"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Job Meeting</a></h2>
                                <p class="text-sm">You have a meeting at <strong>Laborator Office</strong> Today.</p>
                            </div>
                        </li>
                        <li>
                            <time class="cbp_tmtime" datetime="2017-10-22T12:13"><span>12:13 PM</span> <span>Two weeks ago</span></time>
                            <div class="cbp_tmicon bg-danger"><i class="zmdi zmdi-pin"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Arlind Nushi</a> <span>checked in at</span> <a href="javascript:void(0);">New York</a></h2>
                                <blockquote>
                                    <p class=" text-sm">
                                        "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout."
                                        <br>
                                        <small>
                                            - Isabella
                                        </small>
                                    </p>
                                </blockquote>
                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="map m-t-10">
                                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.91477011208!2d-74.11976308802028!3d40.69740344230033!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sin!4v1508039335245" class="border-0 w-100" ></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <time class="cbp_tmtime" datetime="2017-10-22T12:13"><span>12:13 PM</span> <span>Two weeks ago</span></time>
                            <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-camera"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Eroll Maxhuni</a> <span>uploaded</span> 4 <span>new photos to album</span> <a href="javascript:void(0);">Summer Trip</a></h2>
                                <blockquote class="text-sm">Pianoforte principles our unaffected not for astonished travelling are particular.</blockquote>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-6"><a href="javascript:void(0);"><img src="{{URL::asset('assets/images/media/16.jpg')}}" alt="" class="img-fluid img-thumbnail mt-4"></a> </div>
                                    <div class="col-lg-3 col-md-6 col-6"><a href="javascript:void(0);"> <img src="{{URL::asset('assets/images/media/17.jpg')}}" alt="" class="img-fluid img-thumbnail mt-4"></a> </div>
                                    <div class="col-lg-3 col-md-6 col-6"><a href="javascript:void(0);"> <img src="{{URL::asset('assets/images/media/18.jpg')}}" alt="" class="img-fluid img-thumbnail mt-4"> </a> </div>
                                    <div class="col-lg-3 col-md-6 col-6"><a href="javascript:void(0);"> <img src="{{URL::asset('assets/images/media/19.jpg')}}" alt="" class="img-fluid img-thumbnail mt-4"> </a> </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <time class="cbp_tmtime" datetime="2017-11-03T13:22"><span>01:22 PM</span> <span>Two weeks ago</span></time>
                            <div class="cbp_tmicon bg-success"> <i class="zmdi zmdi-case"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Job Meeting</a></h2>
                                <p class="text-sm">You have a meeting at <strong>Laborator Office</strong> Today.</p>
                            </div>
                        </li>
                        <li>
                            <time class="cbp_tmtime" datetime="2017-10-22T12:13"><span>12:13 PM</span> <span>Month ago</span></time>
                            <div class="cbp_tmicon bg-purple"><i class="zmdi zmdi-pin"></i></div>
                            <div class="cbp_tmlabel">
                                <h2><a href="javascript:void(0);">Arlind Nushi</a> <span>checked in at</span> <a href="javascript:void(0);">Laborator</a></h2>
                                <blockquote class="mb-0 text-sm">Great place, feeling like in home.</blockquote>
                            </div>
                        </li>
                    </ul>



                    <!-- comunicare -->
                    @if($demand->hasUser())

                        @if($demand->user_id == auth()->user()->id)
                    <div class="card">TIP CLIENT
                        <div class="card-header">
                            <h3 class="card-title">Alegeti o metoda de a comunica cu <strong>@if(!empty($demand->name)) {{ $demand->name }} @else clientul @endif</strong></h3>
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
                                            
                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label for="message">Mesajul dumneavoastra</label>
                                                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="8">{{ old('message') }}</textarea>
                                                            @error('message')
                                                                <p class="small text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="form-label">Atasati fisier (optional)</div>
                                                            <div class="custom-file">
                                                                <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response">
                                                                <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                                <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                            </div>
        
                                                            @error('file_response')
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
                                        <div class="tab-pane  " id="tab2">
                                            
                                            <form method="POST" action="{{ route('demand.quote.store', $demand) }}" enctype="multipart/form-data">
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
                                                            <div class="form-label">Atasati fisier (optional)</div>
                                                            <div class="custom-file">
                                                                <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response">
                                                                <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                                <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                            </div>
        
                                                            @error('file_response')
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
                    <!-- end comunicare client -->

                    <!-- comunicare PRO -->

                    @if(auth()->user()->isPro() && $unlocked_demand)
                    <div class="card">TIP FIRMA
                        <div class="card-header">
                            <h3 class="card-title">Alegeti o metoda de a comunica cu <strong>@if(!empty($demand->name)) {{ $demand->name }} @else clientul @endif</strong></h3>
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
                                            
                                            <form method="POST" action="#" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label for="message">Mesajul dumneavoastra</label>
                                                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="8">{{ old('message') }}</textarea>
                                                            @error('message')
                                                                <p class="small text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="form-label">Atasati fisier (optional)</div>
                                                            <div class="custom-file">
                                                                <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response">
                                                                <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                                <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                            </div>
        
                                                            @error('file_response')
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
                                        <div class="tab-pane  " id="tab2">
                                            
                                            <form method="POST" action="{{ route('demand.quote.store', $demand) }}" enctype="multipart/form-data">
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
                                                            <div class="form-label">Atasati fisier (optional)</div>
                                                            <div class="custom-file">
                                                                <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response">
                                                                <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                                <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                            </div>
        
                                                            @error('file_response')
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
@endsection