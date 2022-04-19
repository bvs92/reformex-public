<!-- SIDE-BAR -->
			<div class="sidebar sidebar-right sidebar-animate">
			   <div class="p-4 border-bottom">
			   		<span class="fs-17">Notificări</span>
					<a href="#" class="sidebar-icon text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x"></i></a>
				</div>

				<a href="{{ route('notifications.all') }}" class="btn btn-sm btn-default m-4 d-flex justify-content-center">Vezi tot</a>

				@if(auth()->user()->unreadNotifications && auth()->user()->unreadNotifications->count() > 0)
					@foreach(auth()->user()->unreadNotifications as $notification)

					@if($notification->type == 'App\Notifications\DemandBought')
						@include('volgh.layouts._includes.notifications.demand_bought')
					@elseif($notification->type == 'App\Notifications\TimelineAction')
						@include('volgh.layouts._includes.notifications.timeline_action')
					@elseif($notification->type == 'App\Notifications\TicketNotification')
						@include('volgh.layouts._includes.notifications.ticket_notification')
					@elseif($notification->type == 'App\Notifications\ReportDemandNotification')
						@include('volgh.layouts._includes.notifications.demand_reported')
					@elseif($notification->type == 'App\Notifications\ResponseForReportedDemandNotification')
						@include('volgh.layouts._includes.notifications.response_demand_reported')
					@endif

					
					@endforeach
				@endif

			</div>
<!-- SIDE-BAR CLOSED -->
