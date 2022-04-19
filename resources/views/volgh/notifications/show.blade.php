@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Notificari</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notifications.all') }}">Notificari</a></li>
                <li class="breadcrumb-item active" aria-current="page">Notificare #{{ $notification->id }}</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            @if($notification->type == 'App\Notifications\DemandBought')
                @include('volgh.notifications.types.demand')
            @elseif($notification->type == 'App\Notifications\TimelineAction')
                @include('volgh.notifications.types.demand')
            @elseif($notification->type == 'App\Notifications\TimelineMessageNotification')
                @include('volgh.notifications.types.message-timeline')
            @elseif($notification->type == 'App\Notifications\TicketNotification')
            {{-- @else --}}
                @include('volgh.notifications.types.ticket-2')
            @elseif($notification->type == 'App\Notifications\ReportDemandNotification')
                @include('volgh.notifications.types.demand')
            @elseif($notification->type == 'App\Notifications\ResponseForReportedDemandNotification')
                @include('volgh.notifications.types.demand')
            @endif
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
			
	
	

		