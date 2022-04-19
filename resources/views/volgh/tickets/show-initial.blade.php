@extends('volgh.layouts.master')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">


@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Tichete</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tichete</a></li>
                @if($ticket->hasUUID())
                <li class="breadcrumb-item active" aria-current="page">Tichet #{{ $ticket->getUUID() }}</li>
                @else
                <li class="breadcrumb-item active" aria-current="page">Tichet #{{ $ticket->id }}</li>
                @endif
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-5 -->
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header ">
                        <h2 class="card-title ">Numar ordine tichet: #{{ $ticket->getDisponibleId() }} @if($ticket->status == 1) (<i class="ti-lock"></i> Inchis) @endif</h2>
                        <div class="card-options">
                            @if($ticket->status == 0)
                                <span class="tag tag-green mx-4">Deschis</span>
    
                            @else
                                <span class="tag tag-red mx-4">Inchis</span>
                            @endif

                            <div class="dropdown float-right">
                                <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-more"></i>
                                </a>

                                @if($ticket->hasUUID())
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    @if($ticket->status == 0)
                                        <a onclick="event.preventDefault();document.getElementById('changeStatus').submit();" class="dropdown-item"><i class="ti-lock"></i> Inchide tichet</a>
                                    @else
                                        <a onclick="event.preventDefault();document.getElementById('changeStatus').submit();" class="dropdown-item"><i class="ti-unlock"></i> Deschide tichet</a>
                                    @endif

                                    
                                    <a onclick="event.preventDefault();document.getElementById('deleteTicket').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>

                                    <form action="{{ route('tickets.changeStatus.uuid', $ticket->uuid) }}" method="POST" style="display: none;" id="changeStatus">
                                        @csrf
                                        @method('PUT')
                                        {{-- <button type="submit" class="btn btn-primary btn-sm">Marcare ca 'Terminat'</button> --}}
                                    </form>


                                    
                                    <form 
                                        action="{{ route('tickets.destroy.uuid', $ticket->uuid) }}" 
                                        id="deleteTicket" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>

                                </div>
                                @else
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    @if($ticket->status == 0)
                                        <a onclick="event.preventDefault();document.getElementById('changeStatus').submit();" class="dropdown-item"><i class="ti-lock"></i> Inchide tichet</a>
                                    @else
                                        <a onclick="event.preventDefault();document.getElementById('changeStatus').submit();" class="dropdown-item"><i class="ti-unlock"></i> Deschide tichet</a>
                                    @endif

                                    <form action="{{ route('tickets.changeStatus', $ticket->id) }}" method="POST" style="display: none;" id="changeStatus">
                                        @csrf
                                        @method('PUT')
                                        {{-- <button type="submit" class="btn btn-primary btn-sm">Marcare ca 'Terminat'</button> --}}
                                    </form>

                                    
                                    @hasanyrole('admin|moderator')
                                    <a onclick="event.preventDefault();document.getElementById('deleteTicket').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                    
                                    <form 
                                        action="{{ route('tickets.destroy', $ticket->id) }}" 
                                        id="deleteTicket" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    @endhasanyrole

                                </div>
                                @endif


                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <div class="">
                                <div class="row justify-content-center p-4">
                                    <div class="col-md-12 p-4" style="background: white;">                                        

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($ticket->created_at) }}</p>
                                            </div>
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon ti-user"></i> {{ $ticket->user->getName() }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p><i class="fa fa-inbox" aria-hidden="true"></i> <strong>{{ $ticket->user->email }}</strong></p>
                                            </div>
                                            <div class="col-lg-6">
                                                @if($ticket->priority == 0)
                                                    <p><i class="side-menu__icon ti-timer"></i> <span class="tag tag-gray">Nesetat</span></p>
                                                @elseif($ticket->priority == 1)
                                                    <p><i class="side-menu__icon ti-timer"></i> <span class="tag tag-red">Urgent</span></p>
                                                @elseif($ticket->priority == 2)
                                                    <p><i class="side-menu__icon ti-timer"></i> <span class="tag tag-orange">Important</span></p>
                                                @elseif($ticket->priority == 3)
                                                    <p><i class="side-menu__icon ti-timer"></i> <span class="tag tag-blue">Normal</span></p>
                                                @endif
                                                
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p>Status: 
                                                    @if($ticket->status == 0)
                                                        <span class="tag tag-green">Deschis</span>
                            
                                                    @else
                                                        <span class="tag tag-red">Inchis</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-lg-6">
                                                @if($ticket->department_id == 0)
                                                    <p>Departament: <span>General</span></p>
                                                @elseif($ticket->department_id == 1)
                                                    <p>Departament: <span>Comercial</span></p>
                                                @elseif($ticket->department_id == 2)
                                                    <p>Departament: <span>Tehnic</span></p>
                                                @endif
                                                
                                            </div>
                                        </div>

                                        <hr>
                                        <h4><strong>Subiect:</strong> {{ $ticket->subject }}</h4>
                                        <br/>

                                        
                                        <div>
                                            <h5><strong>Descriere:</strong></h5>
                                            {{ $ticket->message }}
                                        </div>

                                        @if($ticket->files && $ticket->files->count() > 0)
                                        <br>
                                            <div class="mt-6">
                                                <h5><strong>Atasamente:</strong></h5>
                                                <div class="row">
                                                    @foreach($ticket->files as $ticket_file)
                                                    
                                                    <div class="col-2">
                                                        @if($ticket_file->mime_type == 'image/jpeg' || $ticket_file->mime_type == 'image/png' || $ticket_file->mime_type == 'image/webp')
                                                        <a href="{{asset('storage/tickets/' . $ticket_file->name)}}" data-lightbox="photos">
                                                            <img class="img-fluid rounded img-thumbnail" src="{{asset('storage/tickets/' . $ticket_file->name)}}" alt="{{ $ticket_file->name }}">
                                                        </a>
                                                        @elseif($ticket_file->mime_type == 'application/pdf')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @elseif($ticket_file->mime_type == 'text/csv')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @elseif($ticket_file->mime_type == 'application/msword')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>

                                                        @elseif($ticket_file->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @elseif($ticket_file->mime_type == 'application/vnd.ms-excel')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @elseif($ticket_file->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @elseif($ticket_file->mime_type == "text/plain")
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @else
                                                            <a href="{{URL::asset('storage/tickets/' . $ticket_file->name)}}" style="font-size:10px;">
                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $ticket_file->name }}
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        
{{--                                         
                                        <hr>
                                        <p>Localizare: 20 KM de locatia dvs (Bucuresti)</p>
                                        <img src="{{ asset('images/staticmap.png') }}" alt=""> --}}
                                    
                                        <br>
                                    </div><!-- end col-lg-12 -->
                                
                                </div><!-- end row -->

                                <div class="row my-4">
                                    <ticket-chat-component></ticket-chat-component>
                                </div>


                                <div class="row mt-4">
                                    @if($responses)
                                    <div class="col-lg-12 mt-4">
                                       
                                        
                                            <h3 class="my-4">Conversatie</h3>
                                            <hr>

                                            
                                            <div class="chat">
            
                                                <!-- MSG CARD-BODY OPEN -->
                                                <div class="card-body msg_card_body" style="overflow-y: initial;">
                                                    <div class="chat-box-single-line">
                                                        <abbr class="timestamp">Deschis: {{ formatCarbonDate($ticket->created_at) }}</abbr>
                                                    </div>
                                                    
                                                    @foreach($ticket->responses as $response)
                                                        @if($ticket->user->id == $response->user_id)
                                                        <div class="row">
                                                            <div class="col-lg-12 d-flex justify-content-start">
                                                                <div class="img_cont_msg">
                                                                    @if($response->user->hasProfilePhoto())
                                                                        <img src="{{ asset($response->user->getFullProfilePhoto()) }}" alt="{{ $response->user->getName() }}" class="rounded-circle user_img_msg avatar-md">
                                                                    @else
                                                                    <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ $response->user->getName() }}" class="rounded-circle user_img_msg avatar-md">
                                                                    @endif
                                                                </div>
                                                                <div class="msg_cotainer col-lg-8">
                                                                    {{ $response->message }}
                                                                    <span class="msg_time">{{ carbonDateToRo($response->created_at) }}</span>
                                                                </div>
                                                            </div>

                                                            @if($response->responseFiles)
                                                                <div class="col-lg-12 mb-6 ml-8">
                                                                    @foreach($response->responseFiles as $response_file)
                                                                    
                                                                    <div class="col-2">
                                                                        @if($response_file->mime_type == 'image/jpeg' || $response_file->mime_type == 'image/png' || $response_file->mime_type == 'image/webp')
                                                                        <a href="{{asset('storage/tickets/' . $response_file->name)}}" data-lightbox="photos">
                                                                            <img class="img-fluid rounded img-thumbnail" src="{{asset('storage/tickets/' . $response_file->name)}}" alt="{{ $response_file->name }}">
                                                                        </a>
                                                                        @elseif($response_file->mime_type == 'application/pdf')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'text/csv')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/msword')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>

                                                                        @elseif($response_file->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/vnd.ms-excel')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == "text/plain")
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-text-o" style="color:gray;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @else
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        </div>
                                                        @else
                                                        <div class="row">
                                                            <div class="col-lg-12 d-flex justify-content-end">
                                                                <div class="msg_cotainer_send col-lg-8">
                                                                    {{ $response->message }}
                                                                    <span class="msg_time_send float-right">{{ carbonDateToRo($response->created_at) }}</span>
                                                                </div>

                                                                <div class="img_cont_msg">
                                                                    @if($response->user->hasProfilePhoto())
                                                                        <img src="{{ asset($response->user->getFullProfilePhoto()) }}" alt="{{ $response->user->getName() }}" class="rounded-circle user_img_msg avatar-md">
                                                                    @else
                                                                    <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ $response->user->getName() }}" class="rounded-circle user_img_msg avatar-md">
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            @if($response->responseFiles)
                                                                <div class="col-lg-12 mb-6 ml-8 d-flex justify-content-end">
                                                                    @foreach($response->responseFiles as $response_file)
                                                                    
                                                                    <div class="col-3">
                                                                        @if($response_file->mime_type == 'image/jpeg' || $response_file->mime_type == 'image/png' || $response_file->mime_type == 'image/webp')
                                                                        <a href="{{asset('storage/tickets/' . $response_file->name)}}" data-lightbox="photos">
                                                                            <img class="img-fluid rounded img-thumbnail" src="{{asset('storage/tickets/' . $response_file->name)}}" alt="{{ $response_file->name }}">
                                                                        </a>
                                                                        @elseif($response_file->mime_type == 'application/pdf')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'text/csv')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/msword')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>

                                                                        @elseif($response_file->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/vnd.ms-excel')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @elseif($response_file->mime_type == "text/plain")
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @else
                                                                            <a href="{{URL::asset('storage/tickets/' . $response_file->name)}}" style="font-size:10px;">
                                                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $response_file->name }}
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        </div>

                                                        @endif
                                                    @endforeach

                                                    @if($ticket->status == '1')
                                                        <div class="chat-box-single-line">
                                                            <abbr class="timestamp"><i class="ti-lock"></i> Inchis: {{ $ticket->updated_at }}</abbr>
                                                        </div>
                                                    @endif


                                                    {{-- <div class="d-flex justify-content-end">
                                                        <div class="msg_cotainer_send">
                                                            But I must explain to you how all this mistaken  born and I will give some images below
                                                            <span class="msg_time_send">9:10 AM, Today</span>
                                                            <div class="row mt-2">
                                                                <div class="col-4">
                                                                    <img class="img-fluid rounded" src="{{URL::asset('assets/images/users/8.jpg')}}" alt="banner image">
                                                                </div>
                                                                <div class="col-4">
                                                                    <img class="img-fluid rounded" src="{{URL::asset('assets/images/media/9.jpg')}} " alt="banner image">
                                                                </div>
                                                                <div class="col-4">
                                                                    <img class="img-fluid rounded" src="{{URL::asset('assets/images/media/10.jpg')}}" alt="banner image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="{{URL::asset('assets/images/users/15.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                        </div>
                                                    </div> --}}
                                                    
                                                </div>
                                                <!-- MSG CARD-BODY END -->
                                            </div>
                                        <!-- COL END CHAT -->

                                    </div>
                                    @endif
                                </div> <!-- end here -->

                                @if($ticket->status == 0)
                                    <br>
                                    <hr>
                                    <h3>Trimite mesaj</h3>
                                    <br>
                                    @if($ticket->hasUUID())
                                    <form method="POST" action="{{ route('tickets.respond.many.uuid', $ticket->uuid) }}" enctype="multipart/form-data">
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
                                                        {{-- <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response"> --}}
                                                        <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response[]" multiple="multiple">
                                                        <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                        <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                    </div>

                                                    @error('file_response')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
        
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block mt-4"><i class="fa fa-send"></i> Trimite raspuns </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        

                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('tickets.respond.many', $ticket->id) }}" enctype="multipart/form-data">
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
                                                        {{-- <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response"> --}}
                                                        <input type="file" class="form-control custom-file-input @error('file_response') is-invalid @enderror" class="custom-file-input" name="file_response[]" multiple="multiple">
                                                        <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                        <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p>
                                                    </div>

                                                    @error('file_response')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
        
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block mt-4"><i class="fa fa-send"></i> Trimite raspuns </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        

                                    </form>
                                    @endif
                          
                            @endif
                            <!-- end message writing -->
                                    



                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
        </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>


{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}
@endsection
			
	
	

		