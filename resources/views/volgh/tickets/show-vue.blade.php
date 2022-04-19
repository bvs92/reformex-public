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
                <li class="breadcrumb-item"><a href="{{ route('tickets.list.all') }}">Tichete</a></li>
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
                                <span class="tag tag-green mx-2">Deschis</span>
    
                            @else
                                <span class="tag tag-red mx-2">Inchis</span>
                            @endif


                            @if(!$ticket->isMine())
                                @if($ticket->hasResolvers())
                                    @if($ticket->resolverIsMe())
                                        @if($ticket->isSubscribed())
                                        <span class="tag tag-primary mx-2">Urmaresti tichetul</span>
                                        @else
                                        <span class="tag tag-default mx-2">Nu urmaresti tichetul</span>
                                        @endif
                                    @endif
                                @endif
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

                                    @if(!$ticket->isMine())
                                        @if($ticket->hasResolvers())
                                            @if($ticket->resolverIsMe())
                                                @if($ticket->isSubscribed())
                                                <a onclick="event.preventDefault();document.getElementById('subscribingTicket').submit();" class="dropdown-item"><i class="ti-na"></i> Nu mai urmari</a>
                                                @else
                                                <a onclick="event.preventDefault();document.getElementById('subscribingTicket').submit();" class="dropdown-item"><i class="ti-signal"></i> Urmareste</a>
                                                @endif


                                                <form action="{{ route('tickets.subscribing.single', $ticket->uuid) }}" method="POST" style="display: none;" id="subscribingTicket">
                                                    @csrf
                                                    @method('POST')
                                                    {{-- <button type="submit" class="btn btn-primary btn-sm">Marcare ca 'Terminat'</button> --}}
                                                </form>

                                            @endif
                                        @endif
                                    @endif
                                    
                                    @hasanyrole('admin')
                                    <a onclick="event.preventDefault();deleteSingleTicket();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                    <form 
                                        action="{{ route('tickets.destroy.uuid', $ticket->uuid) }}" 
                                        id="deleteTicket" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    @endhasanyrole

                                    
                                    
                                    
                                    <form action="{{ route('tickets.changeStatus.uuid', $ticket->uuid) }}" method="POST" style="display: none;" id="changeStatus">
                                        @csrf
                                        @method('PUT')
                                        {{-- <button type="submit" class="btn btn-primary btn-sm">Marcare ca 'Terminat'</button> --}}
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

                                    
                                    @hasanyrole('admin')
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

                                @if($ticket->isMine())
                                <br>
                                <hr>

                                <simple-chat-box-component 
                                :ticket_uuid="{{ json_encode($ticket->uuid) }}" 
                                :ticket_id="{{ json_encode($ticket->id) }}" 
                                :current_user="{{ json_encode(auth()->user()->necessary()) }}"
                                :the_ticket_user="{{ json_encode($ticket->user) }}"
                                :the_ticket="{{ json_encode($ticket) }}"
                                :owner="true"
                                :moderator="false"
                                :read_only="false"
                                ></simple-chat-box-component>

                                 {{-- <ticket-chat-component :current_user="{{ json_encode(auth()->user()) }}" :ticket_uuid="{{ json_encode($ticket->uuid) }}" :ticket_id="{{ json_encode($ticket->id) }}"></ticket-chat-component> --}}
                                @else

                                    @if(!$ticket->hasResolvers())
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <p class="text-center">Nu are moderatori. Puteti rezolva acest ticket.</p>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('tickets.resolve', $ticket->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">Rezolva acest tichet</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        @hasrole('admin')
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <p class="text-center">Puteti atribui acest tichet catre un moderator.</p>
                                                <div class="d-flex justify-content-center">
                                                    @foreach($disponible_moderators as $moderator)

                                                    <div class="card" style="width: 18rem;">
                                                        <div class="card-body">
                                                            @if($moderator->hasProfilePhoto())
                                                            <span class="avatar avatar-xxl bradius cover-image d-flex justify-content-center" style="margin: 0 auto;" data-image-src="{{ asset($moderator->getFullProfilePhoto()) }}"></span>
                                                            @else
                                                            <span class="avatar avatar-xxl bradius cover-image d-flex justify-content-center" style="margin: 0 auto;" data-image-src="{{URL::asset('assets/images/users/10.jpg')}}"></span>
                                                            @endif
                                                            <p class="text-center" style="font-size: 12px;">{{ $moderator->getName() }}</p>
                                                        </div>
                                                        <div class="card-footer d-flex justify-content-center">
                                                            <form action="{{ route('tickets.delegate.user', ['ticket_id' => $ticket->id, 'user_id' => $moderator->id]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary">Atribuie tichet</button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                        
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endhasrole
                                    @else
                                        @if($ticket->resolverIsMe())
                                            <br>
                                            <hr>
                                            <simple-chat-box-component 
                                                :ticket_uuid="{{ json_encode($ticket->uuid) }}" 
                                                :ticket_id="{{ json_encode($ticket->id) }}" 
                                                :current_user="{{ json_encode(auth()->user()->necessary()) }}"
                                                :the_ticket_user="{{ json_encode($ticket->user) }}"
                                                :the_ticket="{{ json_encode($ticket) }}"
                                                :owner="false"
                                                :moderator="true"
                                                :read_only="false"
                                            ></simple-chat-box-component>
                                            {{-- <ticket-chat-component :ticket_uuid="{{ json_encode($ticket->uuid) }}" :ticket_id="{{ json_encode($ticket->id) }}" :current_user="{{ json_encode(auth()->user()) }}"></ticket-chat-component> --}}
                                        @else
                                            <div class="row my-4">
                                                <div class="col-lg-12">
                                                    <h5 class="text-center">Moderatorii participanti la acest ticket sunt:</h5>
                                                    <div class="d-flex justify-content-center my-4">
                                                    
                                                        @foreach($ticket->getResolvers() as $resolver)
                                                            <div class="avatar-list">
                                                                @if($resolver->user->hasProfilePhoto())
                                                                <span class="avatar avatar-xxl bradius cover-image" style="margin: 0 auto;" data-image-src="{{ asset($resolver->user->getFullProfilePhoto()) }}"></span>
                                                                @else
                                                                <span class="avatar avatar-xxl bradius cover-image" data-image-src="{{URL::asset('assets/images/users/10.jpg')}}"></span>
                                                                @endif
                                                                <p class="text-center" style="font-size: 12px;">{{ $resolver->user->getName() }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="d-flex justify-content-center my-4">
                                                        <form action="{{ route('tickets.resolve', $ticket->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm">Vreau sa particip</button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    @endif
                                    

                                    @hasanyrole('admin')
                                        @if(!$ticket->resolverIsMe())
                                            <br>
                                            <hr>
                                            <simple-chat-box-component 
                                                :ticket_uuid="{{ json_encode($ticket->uuid) }}" 
                                                :ticket_id="{{ json_encode($ticket->id) }}" 
                                                :current_user="{{ json_encode(auth()->user()->necessary()) }}"
                                                :the_ticket_user="{{ json_encode($ticket->user) }}"
                                                :the_ticket="{{ json_encode($ticket) }}"
                                                :owner="false"
                                                :moderator="true"
                                                :read_only="true"
                                            ></simple-chat-box-component>
                                        @endif
                                    @endhasanyrole

                                @endif
                                

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

@hasanyrole('admin')
<script>

    function deleteSingleTicket(){
        window.Swal.fire({
            title: 'Esti sigur ca vrei sa elimini acest tichet?',
            text: 'Eliminarea acestuia va fi definitiva si vor fi sterse toate mesajele si fisierele asociate.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Da, elimina!',
            cancelButtonText: 'Nu, renunta'
            }).then((result) => {
            if (result.value) {
    
                document.getElementById('deleteTicket').submit();
    
                Swal.fire(
                    'Eliminat!',
                    'Tichetul a fost eliminat.',
                    'success'
                )
            }
            })
    }
</script>
@endhasanyrole
@endsection



			
	
	

		