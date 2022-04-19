<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-header ">
                
                <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['ticket_id'] }}) 
                    <span class="small">
                    {{-- @if($notification->data['type'] == 'ticket_created') 
                        {{ $user->getTheName()}} a deschis un tichet
                    @elseif($notification->data['type'] == 'ticket_status_changed') 
                        {{ $user->getTheName()}} a marcat statusul tichetului ca @if($notification->data['ticket_status'] == 0) <span class="tag tag-green mx-4">Deschis</span> @else <span class="tag tag-red mx-4">Inchis</span> @endif
                    @else
                        {{ $user->getTheName()}} a eliminat tichetul #{{ $notification->data['ticket_id'] }}
                    @endif --}}
                    </span>
                </h2>
                
                
                <div class="card-options">
                    {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                    <div class="dropdown float-right">
                        <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-more"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                           
                            {{-- <a onclick="event.preventDefault();document.getElementById('relauchDemand').submit();" class="dropdown-item"><i class="ti-reload"></i> Relanseaza</a> --}}
                            {{-- <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a> --}}
                           
                            {{-- <form 
                                action="{{ route('demands.destroy', $demand->id) }}" 
                                id="deleteDemand" 
                                method="POST" 
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form> --}}

                            {{-- <form 
                                action="{{ route('demands.relaunch', $demand->id) }}" 
                                id="relauchDemand" 
                                method="POST" 
                                style="display: none;">
                                @csrf
                                @method('PUT')
                            </form> --}}

                        </div>


                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="grid-margin">
                    <div class="">
                        
                        <div class="row justify-content-center p-4">
                            <div class="col-md-6 p-4" style="background: white;">

                            
                            <div class="row my-2">
                                
                                @if($ticket)
                                <div class="col-lg-12">
                                    <p><strong>{{ $ticket->subject }}</strong></p>
                                </div>
                                <div class="col-lg-6">
                                    <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($ticket->created_at) }}</p>
                                </div>
                                <div class="col-lg-6">
                                    <p>Prioritate: 
                                        @if($ticket->priority == 0)
                                            <i class="side-menu__icon ti-timer"></i> <span class="tag tag-gray">Nesetat</span>
                                        @elseif($ticket->priority == 1)
                                            <i class="side-menu__icon ti-timer"></i> <span class="tag tag-red">Urgent</span>
                                        @elseif($ticket->priority == 2)
                                            <i class="side-menu__icon ti-timer"></i> <span class="tag tag-orange">Important</span>
                                        @elseif($ticket->priority == 3)
                                            <i class="side-menu__icon ti-timer"></i> <span class="tag tag-blue">Normal</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-lg-6">
                                    <p>Status: 
                                    @if($ticket->status == 0)
                                        <span class="tag tag-green mx-4">Deschis</span>
            
                                    @else
                                        <span class="tag tag-red mx-4">Inchis</span>
                                    @endif
                                    </p>
                                </div>
                                
                                <hr>
                                <div class="col-lg-12">
                                    <p>Mesaj tichet:</p>
                                    <p><strong>{{ $ticket->message }}</strong></p>
                                </div>

                                <div class="col-lg-12">
                                    <hr>
                                    {{-- <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-info">Detalii complete despre tichet</a> --}}
                                </div>
                                @else
                                <div class="col-lg-12">
                                    <p>Tichetul <strong>#{{ $notification->data['ticket_id'] }}</strong> nu exista.</p>
                                </div>
                                @endif
                            </div>
                           
                            

                              

                            </div><!-- end col-lg-6 -->



                            <div class="col-md-6 p-4">
                                <div class="row my-2">
                                    <div class="col-lg-6 d-flex justify-content-start">
                                        <p>Status: 
                                            @if($notification->read_at == null)
                                                <span class="badge badge-success mr-1 mb-1 mt-1">Nou</span>
                                            @else
                                                <span class="badge badge-default mr-1 mb-1 mt-1 small">Citit</span>
                                            @endif
                                        </p>
                                    </div>
                                  
                                    <div class="col-lg-6 d-flex justify-content-end">
                                        <div class="dropdown float-right">
                                            <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actiuni
                                            </a>
            
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            
                                                {{-- <a onclick="event.preventDefault();document.getElementById('markComplete').submit();" class="dropdown-item"><i class="ti-check"></i> Marcare ca 'Terminat'</a> --}}
                                                {{-- <a onclick="event.preventDefault();document.getElementById('markClose').submit();" class="dropdown-item"><i class="ti-na"></i> Marcare ca 'Inchis'</a> --}}
                                                <a onclick="event.preventDefault();document.getElementById('deleteNotification').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                            
                                                <form 
                                                    action="{{ route('notifications.delete', $notification->id) }}" 
                                                    id="deleteNotification" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                {{-- <form 
                                                    action="{{ route('demands_reports.close', $report->id) }}" 
                                                    id="markClose" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form> --}}
            
                                                {{-- <form 
                                                    action="{{ route('demands.relaunch', $demand->id) }}" 
                                                    id="relauchDemand" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form> --}}
            
                                            </div>
            
            
                                        </div>
                                    </div>

                                    <div class="col-lg-12 my-4">
                                        @if($notification->type == 'App\Notifications\TicketNotification')
                                        <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['ticket_id'] }})</h2> 
                                        <p class="my-2">Mesaj notificare: 
                                            @if($notification->data['type'] == 'ticket_created') 
                                                <strong>{{ $user->getTheName()}}</strong> a deschis un tichet
                                            @elseif($notification->data['type'] == 'ticket_status_changed') 
                                                <strong>{{ $user->getTheName()}}</strong> a marcat statusul tichetului ca @if($notification->data['ticket_status'] == 0) <span class="tag tag-green mx-4">Deschis</span> @else <span class="tag tag-red mx-4">Inchis</span> @endif
                                            @elseif($notification->data['type'] == 'ticket_deleted') 
                                                <strong>{{ $user->getTheName()}}</strong> a <span class="tag tag-red">eliminat</span> tichetul (#{{ $notification->data['ticket_id'] }})
                                            @endif
                                        </p>

                                        @if($ticket)
                                        <p class="my-2">Detalii tichet #{{ $notification->data['ticket_id'] }}: <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}" class="btn btn-sm btn-info">Vezi detalii</a></p>
                                        @endif


                                        @endif
                                    </div>


                                @if($notification->data['type'] == 'ticket_created')
                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($notification->created_at) }}</p>
                                        <p>ID utilizator: {{ $user->id }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($user->isPro())
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $user->professional->getName()}}</p>
                                        @else
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $user->getName()}}</p>
                                        @endif
                                        <p>E-mail: {{ $user->email }}</p>
                                        @if($user->isCompany())
                                            @if($user->company->phone)
                                            <p>Numar de telefon: {{ $user->company->phone }}</p>
                                            @endif

                                            @if($user->company->website)
                                                <p><strong>Site internet:</strong> {{ $user->company->website }}</p>
                                            @endif

                                            @if($user->company->address)
                                                <p><strong>Adresa:</strong> {{ $user->company->address }}</p>
                                            @endif

                                        @endif
                                        @if(auth()->user()->isPro())
                                            <p>Locatie : {{ auth()->user()->professional->getLocation() }}</p>
                                        @endif
                                    </div>

                                    <div class="col-lg-12">
                                        @if($user->isPro())
                                            @if($user->professional->reviews && $user->professional->reviews->count() > 0)
                                            <div class="media-heading">
                                                <h5><strong>Recenzii firma</strong></h5>
                                            </div>
                                            {{-- <div class="wideget-user-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $user->professional->getRating())
                                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                                    @else
                                                        <a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
                                                    @endif
                                                @endfor
                                                <span>{{ $user->professional->getRating() }} din 5 stele ({{ $user->professional->reviews->count() }} @if($user->professional->reviews->count() == 1) Recenzie @else Recenzii @endif)</span>
                                            </div> --}}
                                            @endif
                                        @endif
                                        <br>
                                    </div>


                                    <div class="col-lg-12">
                                        @if($user->isCompany())
                                            <div class="media-heading">
                                                <h5><strong>Descriere firma</strong></h5>
                                            </div>
                                            <div>
                                                {{ $user->company->bio }}
                                            </div>
                                        @endif
                                    </div>

                                @elseif($notification->data['type'] == 'ticket_status_changed')
                                {{-- schimba $user cu $user_notification? --}}
                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($notification->created_at) }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        @if($user->isPro())
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $user->professional->getName()}}</p>
                                        @else
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $user->getName()}}</p>
                                        @endif
                                        @if($user->roles)
                                        <p>Rol utilizator: {{ $user->roles->first()->name }}</p>
                                        @endif
                                    </div>

                                @elseif($notification->data['type'] == 'ticket_deleted')
                                {{-- schimba $user cu $user_notification? --}}
                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($notification->created_at) }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $user->getTheName()}}</p>
                                        

                                        @if($user->roles)
                                        <p>Rol utilizator: {{ $user->roles->first()->name }}</p>
                                        @endif
                                    </div>

                                @endif
                                </div>

                                
                            </div><!-- end col-lg-6 -->



                        
                            
                    </div><!-- end row -->


                    </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div><!-- ROW-5 END -->