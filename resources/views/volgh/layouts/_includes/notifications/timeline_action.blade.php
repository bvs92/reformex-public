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
                
                <b>@if($notification->data['type'] == 'proposition') 
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a trimis o Propunere <span class="badge badge-info mr-1 mb-1 mt-1"><i class="fa fa-handshake-o"></i></span> pentru cererea
                    @elseif($notification->data['type'] == 'accept')
                    {{ \App\User::find($notification->data['user_id'])->professional->getName()}} a acceptat <span class="badge badge-success mr-1 mb-1 mt-1"><i class="ti-thumb-up"></i></span> cererea
                    @elseif($notification->data['type'] == 'refuse')
                    {{ \App\User::find($notification->data['user_id'])->professional->getName()}} a refuzat <span class="badge badge-danger mr-1 mb-1 mt-1"><i class="ti-thumb-down"></i></span> cererea
                    @elseif($notification->data['type'] == 'confirm_winner')
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a desemnat <strong>castigator</strong> final <span class="badge badge-success mr-1 mb-1 mt-1"><i class="ti-thumb-up"></i></span> pentru cererea
                    @elseif($notification->data['type'] == 'refuse_winner')
                    {{ \App\User::find($notification->data['user_id'])->getName()}} v-a refuzat <span class="badge badge-danger  mr-1 mb-1 mt-1"><i class="ti-thumb-down"></i></span> ca si <strong>castigator</strong> final pentru cererea
                    @endif #{{ $notification->data['timeline_id'] }}
                </b>
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