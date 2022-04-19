@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				

            <!-- ROW-4 -->
            {{-- <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-body pb-0">
                            <div class="">
                                <div class="d-flex">
                                    <h6 class="mb-3">Numar cereri</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">#</h3>
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
                                    <h6 class="mb-3">Cotatii trimise</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">##</h3>
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
                                    <h6 class="mb-3">Cereri Inregistrate azi</h6>
                                    <div class="ml-auto">
                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                    </div>
                                </div>
                                <h3 class="number-font mb-1">##</h3>
                                <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>20.8%</span></span><span class="text-muted ml-2">From Last Month</span>
                                <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="widgetChart3" class="chart-dropshadow-success"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- ROW-4 END -->

           <!-- ROW-5 -->
           <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header ">
                        <h3 class="card-title ">Cererile mele</h3>
                        <div class="card-options">
                            <a href="{{ route('demands.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Lanseaza cerere noua</a>
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
                                            <th scope="col">Data</th>
                                            <th scope="col">Numar oferte</th>
                                            <th scope="col">Stare</th>
                                            <th scope="col">@</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @if($demands && $demands->count() > 0)
                                                @foreach($demands as $demand)
                                                    <tr>
                                                        <th scope="row">{{ $demand->getDisponibleId() }}</th>
                                                        <td>{{ $demand->user->email }}</td>
                                                        <td>{{ formatCarbonDate($demand->created_at) }}</td>
                                                        <td>
                                                           {{ $demand->professionals->count() }}
                                                        </td>
                                                        <td>
                                                            @if($demand->isActive())
                                                                <span class="tag tag-green">Activa</span>
                                                            @else
                                                                <span class="tag tag-red">Inactiva</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="dropdown float-right">
                                                                    <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Actiuni
                                                                    </a>
                                                                    
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                        @if($demand->hasUUID())
                                                                        <a href="{{ route('demands.show.owner.uuid', $demand->uuid) }}" class="dropdown-item"><i class="ti-eye"></i> Vezi</a>
                                                                        @else
                                                                        <a href="{{ route('demands.show.owner', $demand->id) }}" class="dropdown-item"><i class="ti-eye"></i> Vezi</a>
                                                                        @endif
                                                                        {{-- <a href="{{ route('demands.edit', $demand) }}" class="dropdown-item"><i class="ti-pencil-alt"></i> Editeaza</a> --}}
                                                                        <a onclick="event.preventDefault();document.getElementById('deleteDemand-{{$demand->id}}').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                                                        <form 
                                                                            action="{{ route('demands.destroy', $demand->id) }}" 
                                                                            id="deleteDemand-{{ $demand->id }}" 
                                                                            method="POST" 
                                                                            style="display: none;">
                                                                            @csrf
                                                                            <input type="hidden" name="_method" value="DELETE">
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                    
                                                    </tr>
                                                @endforeach
                                          @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {{ $demands->links() }}
                                    </div>
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
			
	
	

		