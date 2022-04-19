@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Balanta</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Charges fiscale</li>
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
                            <h3 class="card-title ">Charges fiscale</h3>
                            <div class="card-options">
                                {{-- <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga credit</a> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="table-responsive">

                                        <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                            <thead>
                                              <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">ID</th>
                                                <th scope="col">Suma</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Data</th>
                                                <th scope="col"></th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                @if($charges)
                                                    @if(count($charges) > 0)
                                                        @foreach($charges as $charge)
                                                        <tr>
                                                            <th scope="row">#</th>
                                                            <td>{{ $charge->id }}</td>
                                                            <td>+{{ $charge->amount / 100 }} RON</td>
                                                            <td>
                                                                @if($charge->status == 'succeeded')
                                                                    <span class="badge badge-success  mr-1 mb-1 mt-1">Platit</span>
                                                                @elseif($charge->status == 'failed')
                                                                    <span class="badge badge-danger  mr-1 mb-1 mt-1">Neplatit</span>
                                                                @elseif($charge->status == 'pending')
                                                                    <span class="badge badge-warning  mr-1 mb-1 mt-1">In asteptare</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ formatCarbonDate(\Carbon\Carbon::createFromTimestamp($charge->created)) }}</td>
                                                            <td>
                                                                <a href="{{ route('charges.show', $charge->id) }}" class="btn btn-sm btn-cyan">Vezi</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4">
                                                                <p class="text-center">Nu exista tranzactii inregistrate.</p>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->


                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
@endsection
			
	
	

		