<div class="dropdown d-md-flex message">
    <a class="nav-link icon text-center" data-toggle="dropdown">
        <i class="fe fe-mail"></i>
        {{-- @if(auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TicketMessageNotification')->count() > 0)
        <span class="nav-unread badge badge-danger badge-pill">{{ auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TicketMessageNotification')->count() }}</span>
        @endif --}}
        {{-- <span class="nav-unread badge badge-danger badge-pill">@{{ unreadNotifications }}</span> --}}
        @if($unreadMessageNotifications->count() > 0)
        <span class="nav-unread badge badge-danger badge-pill">{{ $unreadMessageNotifications->count() }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <div class="message-menu">

            @foreach($unreadMessageNotifications as $notification_msg)
                @if($notification_msg->type == 'App\Notifications\TicketMessageNotification')

                <a class="dropdown-item d-flex pb-3" href="{{ route('tickets.show', $notification_msg->data['ticket_id']) }}">
                    {{-- <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{URL::asset('assets/images/users/1.jpg')}}"></span> --}}
                    @if(\App\User::find($notification_msg->data['user_id'])->hasProfilePhoto())
                    <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{ asset(\App\User::find($notification_msg->data['user_id'])->getFullProfilePhoto()) }}"></span>
                    @else
                        <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{URL::asset('assets/images/users/10.jpg')}}"></span>
                    @endif

                    <div>
                        <strong>{{ \App\User::find($notification_msg->data['user_id'])->getTheName() }}</strong> {{ str_limit($notification_msg->data['response_ticket'], 20) }}
                        <div class="small text-muted">
                            {{ carbonDateToRo($notification_msg->created_at) }} <span class="tag tag-gray" style="font-size:9px;">Tichet #{{ $notification_msg->data['ticket_id'] }}</span>
                        </div>
                    </div>
                </a>
                @elseif($notification_msg->type == 'App\Notifications\TimelineMessageNotification')
                {{-- <a class="dropdown-item d-flex pb-3" href="{{ route('tickets.show', $notification_msg->data['timeline_id']) }}"> --}}
                    @if($notification_msg->data['type'] == 'pro')
                        <a class="dropdown-item d-flex pb-3" href="{{ route('timeline.show.pro', $notification_msg->data['timeline_id']) }}">
                    @else
                        <a class="dropdown-item d-flex pb-3" href="{{ route('timeline.show.client', $notification_msg->data['timeline_id']) }}">
                    @endif

                    {{-- <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{URL::asset('assets/images/users/1.jpg')}}"></span> --}}
                    @if(\App\User::find($notification_msg->data['user_id'])->hasProfilePhoto())
                    <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{ asset(\App\User::find($notification_msg->data['user_id'])->getFullProfilePhoto()) }}"></span>
                    @else
                        <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="{{URL::asset('assets/images/users/10.jpg')}}"></span>
                    @endif

                    <div>
                        <strong>{{ \App\User::find($notification_msg->data['user_id'])->getTheName() }}</strong> {{ str_limit($notification_msg->data['response_timeline'], 20) }}
                        <div class="small text-muted">
                            {{ carbonDateToRo($notification_msg->created_at) }} <span class="tag tag-blue" style="font-size:9px;">Conversatie #{{ $notification_msg->data['timeline_id'] }}</span>
                        </div>
                    </div>
                    </a>
                @endif
            @endforeach
        </div>

        @if(auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TicketMessageNotification')->count() > 0)
            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.messages') }}" class="dropdown-item text-center">Vezi toate mesajele...</a>
        @else
            <a href="#" class="dropdown-item text-center">Nu exista niciun mesaj nou.</a>
        @endif
    </div>
    </div><!-- MESSAGE-BOX -->
