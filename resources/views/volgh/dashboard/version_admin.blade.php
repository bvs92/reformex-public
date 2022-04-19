@extends('volgh.layouts.master')
@section('css')
@endsection


@section('title-page')
<title>Panou de control - REFORMEX</title>
@endsection

@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Panou de control</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Acasă</a></li>
            <li class="breadcrumb-item active" aria-current="page">Panou de control</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')


<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title mb-0">Cereri de înscriere profesioniști</h3>
            </div>
            <div class="card-body">
                <div class="grid-margin">
                    <div class="">
                        <div class="table-responsive">
                            <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                <thead class="">
                                    <tr>
                                        <th>Identificator</th>
                                        <th>Nume</th>
                                        <th>Email</th>
                                        <th>Dată</th>
                                        <th>Acțiuni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pending_users && $pending_users->count() > 0)
                                    @foreach($pending_users as $pending_user)
                                    <tr>
                                        <td class="text-sm font-weight-600">{{ $pending_user->id }}</td>
                                        <td>{{ $pending_user->getName() }}</td>
                                        <td>{{ $pending_user->email }}</td>
                                        
                                        <td>{{ formatCarbonDate($pending_user->created_at) }}</td>
                                        <td><a href="/users/admin/show/{{$pending_user->id}}" class="btn btn-sm btn-info">Vezi detalii</a></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5"><p class="text-center">Nu există nicio cerere de înscriere.</p></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div><!-- ROW-5 END -->


    <!-- ROW-5 -->
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Profesioniști înscriși recent</h3>
                </div>
                <div class="card-body">
                    <div class="grid-margin">
                        <div class="">
                            <div class="table-responsive">
                                <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                    <thead class="">
                                        <tr>
                                            <th>Identificator</th>
                                            <th>Nume</th>
                                            <th>Email</th>
                                            <th>Dată</th>
                                            <th>Acțiuni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($professionals && $professionals->count() > 0)
                                        @foreach($professionals as $professional)
                                        <tr>
                                            <td class="text-sm font-weight-600">{{ $professional->user_id }}</td>
                                            <td>{{ $professional->user->getName() }}</td>
                                            <td>{{ $professional->user->email }}</td>
                                            
                                            <td>{{ formatCarbonDate($professional->created_at) }}</td>
                                            <td><a href="/users/admin/show/{{$professional->user->id}}" class="btn btn-sm btn-info">Vezi detalii</a></td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5"><p class="text-center">Nu există niciun utilizator înscris.</p></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
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

{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}

<script>
    // Echo.private('tickets-channel')
    //     .listen('.ticket-event', (e) => {
    //         console.log(e);
    //     });
</script>

@endsection


			
	
	

		