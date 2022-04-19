@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Tichete</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tichete</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tichet nou</li>
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
                            <h3 class="card-title ">Creeaza tichet nou</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div>
                                        <form action="{{ route('tickets.store.many') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label for="name">Subiect tichet</label>
                                                        <input type="text" class="form-control @error('subject') has-error @enderror" id="name" placeholder="Subiect tichet" name="subject" value="{{ old('subject') }}">
                                                        @error('subject')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                

                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label for="priority">Prioritate</label>
                                                        <select name="priority" class="form-control @error('priority') has-error @enderror" id="priority">
                                                            <option value="1">Urgent</option>
                                                            <option value="2">Important</option>
                                                            <option value="3">Normal</option>
                                                        </select>

                                                        @error('priority')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="file_ticket">Atasati fisier (optional)</label>
                                                        <div class="custom-file">
                                                            {{-- <input type="file" class="form-control custom-file-input @error('file_ticket') is-invalid @enderror" class="custom-file-input" name="file_ticket"> --}}
                                                            <input type="file" class="form-control custom-file-input @error('file_ticket') is-invalid @enderror" class="custom-file-input" name="file_ticket[]" multiple="multiple">
                                                            <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                                                            {{-- <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p> --}}
                                                        </div>
    
                                                        @error('file_ticket')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label for="department">Departament</label>
                                                        <select name="department" class="form-control @error('department') has-error @enderror" id="department">
                                                            <option value="0">General</option>
                                                            <option value="1">Comercial</option>
                                                            <option value="2">Tehnic</option>
                                                        </select>

                                                        @error('department')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="message">Mesaj tichet</label>
                                                <textarea class="form-control @error('message') has-error @enderror" name="message" id="message" cols="30" rows="10">
                                                    {{ old('message') }}
                                                </textarea>
                                                @error('message')
                                                    <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Trimite tichet</button>
                                                </div>
                                            </div>
                                        
                                        
                                        </form>
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
			
	
	

		