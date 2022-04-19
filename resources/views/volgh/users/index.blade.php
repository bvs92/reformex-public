@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Utilizatori</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Utilizatori</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				

            <!-- ROW-4 -->
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Total utilizatori</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">{{ $users->count() }}</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>+24%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart1" class="chart-dropshadow-info"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Utilizatori Firme</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">{{ $professionals->count() }}</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>90.5%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart2" class="chart-dropshadow-secondary"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Ultimele 7 zile</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">8963</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>20.8%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart3" class="chart-dropshadow-success"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-4 END -->

            <!-- start new users list -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" placeholder="Cauta un utilizator...">
                        <div class="input-group-append ">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card">

                        <div class="card-header ">
                            <h3 class="card-title ">Utilizatori ({{ $total_users }})</h3>
                            <div class="card-options">
                                <a href="{{ route('users.admin.create.new.view') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga utilizator nou</a>
                            </div>
                        </div>

                        <div class="card-header border-bottom-0 p-4">
                            <div class="page-options d-flex float-right">
                                <select class="form-control custom-select w-auto">
                                    <option value="asc">Ascendent</option>
                                    <option value="desc">Descendent</option>
                                </select>
                            </div>
                        </div>

                        <div class="e-table px-5 pb-5">
                            <div class="table-responsive table-lg">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th  class="text-center">

                                            </th>
                                            <th class="text-center"></th>
                                            <th>Nume</th>
                                            <th>Email</th>
                                            <th>Inscris</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($users && $users->count() > 0)
                                        @foreach($users as $user)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top">
                                                    <input class="custom-control-input" id="item-1" type="checkbox"> <label class="custom-control-label" for="item-1"></label>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($user->hasProfilePhoto())
                                                    <img src="{{ asset($user->getFullProfilePhoto()) }}" alt="{{ $user->getName() }}" class="avatar avatar-md rounded-circle">
                                                @else
                                                <img src="{{URL::asset('assets/images/users/10.jpg')}}" alt="{{ $user->getName() }}" class="avatar avatar-md rounded-circle">
                                                @endif
                                            </td>
                                            <td class="text-nowrap align-middle">
                                                {{ $user->email }}
                                            </td>
                                            <td class="text-nowrap align-middle">
                                                {{ $user->getName() }}
                                            </td>
                                            <td class="text-nowrap align-middle"><span>
                                                {{ formatShortCarbonDate($user->created_at) }} 
                                            </span></td>

                                            <td class="text-center align-middle">
                                                <div class="btn-group align-top">
                                                    <a class="btn btn-sm btn-primary badge" href="{{ route('user.default.profile', $user->id) }}">Vezi</a> 
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="mb-5 d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div><!-- Users List - COL-END -->
            </div><!-- end new users list -->

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Utilizatori platforma</h3>
                            <div class="card-options">
                                <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga utilizator nou</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead class="thead-dark">
                                              <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">E-mail</th>
                                                <th scope="col">Nume</th>
                                                <th scope="col">Actiuni</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                @if($users && $users->count() > 0)
                                                    @foreach($users as $user)
                                                        <tr>
                                                            <th scope="row">{{ $user->id }}</th>
                                                            <td style="width:30%;">{{ $user->getName() }}</td>
                                                            <td style="width:30%;">{{ $user->email }}</td>
                                                            <td style="width:40%;">
                                                                <div class="row">
                                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                                                                  {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                                                                  <button onclick="event.preventDefault();document.getElementById('deleteSubscription_{{ $user->id }}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                                                                  <form 
                                                                    action="{{ route('users.destroy', $user) }}" 
                                                                    id="deleteSubscription_{{ $user->id }}" 
                                                                    method="POST" 
                                                                    style="display: none;">
                                                                      @csrf
                                                                      <input type="hidden" name="_method" value="DELETE">
                                                                  </form>
                                                                </div>
                                                            </td>
                                        
                                                        </tr>
                                                    @endforeach
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
@endsection
			
	
	

		