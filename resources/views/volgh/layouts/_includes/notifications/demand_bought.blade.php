<a href="{{ route('notifications.show', $notification->id) }}">

    <div class="list d-flex align-items-center border-bottom p-2">

        {{-- <div class="">
            <span class="avatar bg-primary brround avatar-md">{{ \App\User::find($notification->data['user_id'])->getName()}}</span>
        </div> --}}
        <div class="wrapper w-100 ml-3" style="margin:10px auto;">

            <div class="row mt-1">
                <div class="col-lg-2">
                    @if(\App\User::find($notification->data['user_id'])->hasProfilePhoto())
                        <img src="{{ asset(\App\User::find($notification->data['user_id'])->getFullProfilePhoto()) }}" class="avatar avatar-sm rounded-circle mt-2">
                    @else
                        <img src="{{URL::asset('assets/images/users/10.jpg')}}" class="avatar avatar-sm rounded-circle mt-2">
                    @endif
                </div>
                <div class="col-lg-10">
                    <p class="mt-3">
                        @if(\App\User::find($notification->data['user_id'])->isPro())
                            {{ \App\User::find($notification->data['user_id'])->professional->getName() }}
                        @else
                            {{ \App\User::find($notification->data['user_id'])->getName() }}
                        @endif
                    </p>
                </div>
            </div><!-- end row -->


            <p class="mb-0 d-flex">
                <b><strong>{{ \App\User::find($notification->data['user_id'])->professional->getName()}}</strong> a interactionat cu cererea (#{{ $notification->data['demand_id'] }})</b>
            </p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-clock text-muted mr-1"></i>
                    {{-- <small class="text-muted ml-auto">{{ formatCarbonDate($notification->created_at) }}</small> --}}
                    <small class="text-muted ml-auto">{{ carbonDateToRo($notification->created_at) }}</small>
                    <p class="mb-0"></p>
                </div>
            </div>
        </div>
    </div><!-- LIST END -->
    </a>