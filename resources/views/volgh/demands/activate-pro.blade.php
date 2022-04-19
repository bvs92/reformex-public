@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>
@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Exploreaza Cereri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exploreaza Cereri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')

@if(!auth()->user()->isPro())
<div class="row">

    <div class="card">
        <div class="card-body text-center row justify-content-center">
            <h2>Activati-va modulul de firma pentru a putea vizualiza si debloca cereri lansate de catre ceilalti utilizatori.</h2>
            <form action="{{ route('professionals.activate', auth()->user()->id) }}" method="POST" class="col-lg-8">
                @csrf
                {{-- <div class="form-group">
                    <label for="name">Denumire firma</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Denumire firma">
                </div> --}}
        
                <button class="btn btn-primary">Activeaza modul firma</button>
            </form>
        </div>
    </div>
</div><!-- end of is PRO -->
@endif


        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
            

@section('footer-scripts')

@endsection
	
	

		