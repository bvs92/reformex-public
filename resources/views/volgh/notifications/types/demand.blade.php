<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-header ">
                @if($notification->type == 'App\Notifications\DemandBought')
                <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                @elseif($notification->type == 'App\Notifications\TimelineAction')
                <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['timeline_id'] }}) | 
                    <span class="small">
                    @if($notification->data['type'] == 'proposition') 
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a trimis o Propunere cerere
                    @elseif($notification->data['type'] == 'accept')
                    {{ \App\User::find($notification->data['user_id'])->professional->getName()}} a acceptat cererea cu
                    @elseif($notification->data['type'] == 'refuse')
                    {{ \App\User::find($notification->data['user_id'])->professional->getName()}} a refuzat cererea cu
                    @elseif($notification->data['type'] == 'confirm_winner')
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a ales castigator pentru cererea
                    @elseif($notification->data['type'] == 'refuse_winner')
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a refuzat ca si castigator pentru cererea
                    @endif numar #{{ $notification->data['timeline_id'] }}
                    </span>
                @elseif($notification->type == 'App\Notifications\ReportDemandNotification')
                <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                @elseif($notification->type == 'App\Notifications\ResponseForReportedDemandNotification')
                <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                @endif
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
                                    <div class="col-lg-12">
                                        <p><strong>{{ $demand->subject }}</strong></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($demand->created_at) }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon fa fa-user"></i> {{ $demand->name}}</p>
                                    </div>

                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon fa fa-at"></i> {{ $demand->email}}</p>
                                    </div>

                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon fa fa-phone"></i> {{ $demand->phone}}</p>
                                    </div>

                                    <div class="col-lg-6">
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>{{ ucfirst($demand->city) }}</strong> </p>
                                    </div>
                                    
                                    <hr>
                                    <div class="col-lg-12">
                                        <p>Mesaj cerere:</p>
                                        <p><strong>{{ $demand->message }}</strong></p>
                                    </div>

                                    <div class="col-lg-12">
                                        <hr>
                                        <a href="{{ route('demands.show', $demand) }}" class="btn btn-sm btn-info">Detalii complete despre cerere</a>
                                    </div>

                                </div>

                              

                            </div><!-- end col-lg-8 -->
                        
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
                                        @if($notification->type == 'App\Notifications\DemandBought')
                                        <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                                        @elseif($notification->type == 'App\Notifications\TimelineAction')
                                        <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['timeline_id'] }})</h2>
                                            <p class="small">
                                            @if($notification->data['type'] == 'proposition') 
                                            {{ $user->getName()}} v-a trimis o Propunere pentru cererea
                                            @elseif($notification->data['type'] == 'accept')
                                            {{ $user->professional->getName()}} (<span class="badge badge-success  mr-1 mb-1 mt-1"><i class="ti-thumb-up"></i></span>) a acceptat propunerea pentru cererea cu
                                            @elseif($notification->data['type'] == 'refuse')
                                            {{ $user->professional->getName()}} (<span class="badge badge-danger  mr-1 mb-1 mt-1"><i class="ti-thumb-down"></i></span>) a refuzat propunerea pentru cererea cu
                                            @elseif($notification->data['type'] == 'confirm_winner')
                                            {{ $user->getName()}} (<span class="badge badge-success  mr-1 mb-1 mt-1"><i class="ti-thumb-up"></i></span>) v-a ales castigator pentru cererea
                                            @elseif($notification->data['type'] == 'refuse_winner')
                                            {{ $user->getName()}} (<span class="badge badge-danger  mr-1 mb-1 mt-1"><i class="ti-thumb-down"></i></span>) v-a refuzat ca si castigator pentru cererea
                                            @endif numar #{{ $notification->data['timeline_id'] }}
                                            </p>
                                        @elseif($notification->type == 'App\Notifications\ReportDemandNotification')
                                        <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                                        @elseif($notification->type == 'App\Notifications\ResponseForReportedDemandNotification')
                                        <h2 class="card-title ">Tip notificare: {{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</h2>
                                        @endif
                                    </div>

                                    <div class="col-lg-12">
                                        @if($notification->type == 'App\Notifications\TimelineAction') 
                                            @if($user_notification->isPro())
                                                <p class="my-2">Detalii conversatie #{{ $notification->data['timeline_id'] }}: <a href="{{ route('timeline.show.pro', $notification->data['timeline_id']) }}" class="btn btn-sm btn-info">Vezi detalii</a></p>
                                            @else
                                                <p class="my-2">Detalii conversatie #{{ $notification->data['timeline_id'] }}: <a href="{{ route('timeline.show.client', $notification->data['timeline_id']) }}" class="btn btn-sm btn-info">Vezi detalii</a></p>
                                            @endif
                                        @endif
                                        <br>
                                    </div>

                                    @if($notification->type == 'App\Notifications\ReportDemandNotification') 
                                    <div class="col-lg-12">
                                        <p class="my-2">Detalii despre raportare #{{ $report->id }} <a href="{{ route('demands_reports.show', $report->id) }}" class="btn btn-sm btn-info">Vezi detalii</a></p>
                                    </div>
                                    @endif


                                    @if($notification->type == 'App\Notifications\ResponseForReportedDemandNotification') 
                                    <div class="col-lg-12">
                                        @if($notification->data['status'] == 'is_true')
                                        <p class="my-2"><strong>{{ $user->getName() }}</strong> a marcat cererea numar #{{ $demand->id }} ca fiind <span class="badge badge-success">Corecta</span>.</p>
                                        @else
                                        <p class="my-2"><strong>{{ $user->getName() }}</strong> a marcat cererea numar #{{ $demand->id }} ca fiind <span class="badge badge-danger">Falsa</span>.</p>
                                        @endif
                                        <p class="my-3">Detalii despre raportare #{{ $report->id }} <a href="{{ route('demands_reports.show', $report->id) }}" class="btn btn-sm btn-info">Vezi detalii</a></p>
                                    </div>
                                    @endif



                                    <div class="col-lg-6">
                                        <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate($notification->created_at) }}</p>
                                        <p>ID utilizator: {{ $user->id }}</p>
                                        @if($user->hasRoles())
                                        <p>Rol: {{ ucfirst($user->getFirstRole()->name) }}</p>
                                        @endif
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
                                            <div class="wideget-user-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $user->professional->getRating())
                                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                                    @else
                                                        <a href="#"><i class="fa fa-star-o text-warning mr-1"></i></a> 
                                                    @endif
                                                @endfor
                                                <span>{{ $user->professional->getRating() }} din 5 stele ({{ $user->professional->reviews->count() }} @if($user->professional->reviews->count() == 1) Recenzie @else Recenzii @endif)</span>
                                            </div>
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


                                </div>

                                
                            </div><!-- end col-lg-6 -->
                    </div><!-- end row -->

                    </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div><!-- ROW-5 END -->