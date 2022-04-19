@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Notificari</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Notificari</li>
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
                            <h3 class="card-title ">Notificari</h3>
                            <div class="card-options">
                                {{-- <a href="{{ route('categories.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga categorie noua</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                <th scope="col">(#ID)</th>
                                                <th scope="col">Tip</th>
                                                <th scope="col">Utilizator</th>
                                                <th scope="col">Interactiune</th>
                                                <th scope="col">Actiuni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($notifications && $notifications->count() > 0)
                                                    @foreach($notifications as $notification)
                                                        <tr>
                                                            <th scope="row" data-toggle="tooltip" data-placement="top" data-original-title="{{ $notification->id }}">#@if($notification->read_at == null)<span class="badge badge-success badge-pill mx-1">NOU</span>@endif</th>
                                                            @if($notification->type == 'App\Notifications\DemandBought')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\TimelineAction')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (Conversatie #{{ $notification->data['timeline_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\TimelineMessageNotification')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (Conversatie #{{ $notification->data['timeline_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\TicketNotification')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (#{{ $notification->data['ticket_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\TicketMessageNotification')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (Tichet #{{ $notification->data['ticket_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\ReportDemandNotification')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</td>
                                                            @elseif($notification->type == 'App\Notifications\ResponseForReportedDemandNotification')
                                                            <td style="width:40%;">{{ $notification->data['subject'] }} (#{{ $notification->data['demand_id'] }})</td>
                                                            @endif
                                                            <td>
                                                                {{ \App\User::find($notification->data['user_id'])->getTheName()}}
                                                            </td>
                                                            <td>{{ formatCarbonDate($notification->created_at) }}</td>
                                                            <td>
                                                                <div class="row">
                                                                    <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-info btn-sm m-1">Vezi</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            </table>

                                            {{ $notifications->links() }}
                                    </div>
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

@endsection
			
	
	

		