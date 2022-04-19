@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Cereri raportate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cereri raportate</li>
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
                            <h3 class="card-title ">Cereri raportate</h3>
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
                                                <th scope="col">#</th>
                                                <th scope="col">Denumire (#ID)</th>
                                                <th scope="col">E-mail</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Data publicare</th>
                                                <th scope="col">Actiuni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($demands && $demands->count() > 0)
                                                    @foreach($demands as $demand)
                                                        <tr>
                                                            <th scope="row">{{ $demand->id }}</th>
                                                            <td style="width:40%;">{{ $demand->subject }} (#{{ $demand->id }})</td>
                                                            <td>{{ $demand->email }} ({{ $demand->name }})</td>
                                                            <td>@if($demand->getStatus() == 1) <span class="badge badge-success">Verificata</span> @elseif($demand->getStatus() == 0) <span class="badge badge-default">Neverificata</span> @else <span class="badge badge-danger">Falsa</span> @endif</td>
                                                            <td>{{ formatCarbonDate($demand->created_at) }}</td>
                                                            <td>
                                                                <div class="row">
                                                                    <a href="{{ route('demands.show', $demand->id) }}" class="btn btn-info btn-sm m-1">Vezi cerere</a>
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

@endsection
			
	
	

		