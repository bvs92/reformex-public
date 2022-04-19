@extends('volgh.layouts.master')

@section('head-scripts')
@endsection


@section('css')
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}
@endsection
@section('page-header')
						<!-- PAGE-HEADER -->
							<div>
								<h1 class="page-title">Creare utilizator nou</h1>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Acasa</a></li>
									<li class="breadcrumb-item active" aria-current="page">Create utilizator</li>
								</ol>
							</div>							
						<!-- PAGE-HEADER END -->
@endsection
@section('content')
						<!-- ROW-1 OPEN -->
						<div class="row">
							<div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
								<!-- start card --><div class="card">
									<div class="card-header">
										<h3 class="card-title">Editare informatii personale</h3>
									</div>
									<div class="card-body">

                                            <form method="POST" action="{{ route('users.admin.create.new') }}">
												@csrf
										
												<div class="form-row">
													<div class="col">
														<input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ $user->first_name ?? old('first_name') }}">
														@error('first_name')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
													<div class="col">
														<input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ $user->last_name ?? old('last_name') }}">
														@error('last_name')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
												</div>
												<br>
												<div class="form-row">
													<div class="col">
														<input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ $user->email ?? old('email') }}">
														@error('email')
														<p class="small text-danger">{{ $message }}</p>
														@enderror
													</div>
													<div class="col">
														<button type="submit" class="btn btn-primary mb-2 float-right">Creeaza utilizator</button>
													</div>
												</div>
										    </form>
									</div>
								</div><!-- end card -->
							</div>
						</div>
						<!-- ROW-1 CLOSED -->

					</div>
				</div>
				<!--CONTAINER CLOSED -->
			</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script> --}}
@endsection

@section('footer-scripts')



@endsection