@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Balanță</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Balanță</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
				


            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Detalii credit și alimentare cont</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="p-2 d-flex justify-content-start">
                                                <h3>Balanta curenta: <strong>{{ $amount }} RON</strong></h3>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                             <div class="p-2 d-flex justify-content-end">
                                             <p>Ultima plata: @if($last_payment) {{ $last_payment->getReadableAmount() }} RON pe {{ formatCarbonDate($last_payment->created_at) }} @else {{ $last_payment }}@endif.</p>
                                             </div>
                                        </div>

                                    </div> <!-- end row -->

                                    
                                    <hr>
                                    <form action="{{ route('credits.add') }}" method="POST">
                                        @csrf
                                    
                                    
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control @error('credit') has-error @enderror" id="credit" placeholder="Suma in ron: 100" name="credit" value="{{ old('credit') }}">
                                                @error('credit')
                                                    <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Fake alimentare cont</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->



            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">

                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Tranzactii</h3>
                            <div class="card-options">
                                <a href="{{ route('users.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga credit</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <personal-charges-component></personal-charges-component>
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
			
	
	

		