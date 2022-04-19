@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorii</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorie nouă</li>
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
                            <h3 class="card-title ">Adaugă o nouă categorie</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div>
                                        <form action="{{ route('categories.store') }}" method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label for="name">Denumire</label>
                                                        <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele categoriei" name="name" value="{{ old('name') }}">
                                                        @error('name')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label for="price">Pret</label>
                                                        <input type="text" 
                                                        class="form-control @error('price') has-error @enderror" 
                                                        id="price" 
                                                        placeholder="Pretul cererilor din categorie" 
                                                        name="price" value="{{ old('price') }}">
                                                        @error('price')
                                                            <p class="small text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Descriere categorie</label>
                                                <textarea class="form-control @error('description') has-error @enderror" name="description" id="description" cols="30" rows="10">
                                                    {{ old('description') }}
                                                </textarea>
                                                @error('description')
                                                    <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                <button type="submit" class="btn btn-success">Salveaza categoria</button>
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
			
	
	

		