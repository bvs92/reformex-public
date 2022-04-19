@extends('layouts.app')


@section('content')

<div class="row">
    
</div>

<div>
    

    <div class="row p-3">
        <div class="col-lg-8" style="border-bottom:1px solid #f1f1f1;">
            <p style="padding: 10px;font-size: 16px;margin-top: 10px;">Ordonare: <a href="#">Cele mai recente</a> | <a href="#">Urgenta</a> | <a href="#">Distanta</a></p>
        </div>

        <div class="col-lg-4">
            <a href="{{ route('demands.create') }}" class="btn btn-primary float-right">Adauga cerere</a>
        </div>

    </div>
        @if($demands)
            @foreach($demands as $demand)   
                <div class="card mt-2" style="border:none;border-bottom:1px solid #f1f1f1;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title mb-5">
                                    {{ $demand->subject }}
                                    @if($demand->hasProfessional(auth()->user()->professional))
                                        <span class="badge badge-success" style="font-weight:100;">Deblocata</span>
                                    @endif
                                </h4>
                                <p class="small">Publicat: {{ $demand->showPublishDate() }}</p>
                                <p>Oras: <strong>{{ ucfirst($demand->city) }}</strong> </p>
                                <p>Categorie: <strong>{{ $demand->firstCategory() }}</strong>
                            </div> <!-- col-md-8 -->
                            <div class="col-md-4">
                                <div style="color:gray;font-size:14px;text-align:right;margin:20px 0px;">
                                    {{-- <p class="mb-0">{{ $demand->name }}</p>
                                    <p class="mb-0">{{ $demand->email }}</p>
                                    <p class="mb-0">{{ $demand->phone }}</p> --}}
                                </div>
                                
                                <a href="{{ route('demands.show', $demand) }}" class="btn btn-primary float-right">Vezi detalii complete</a>
                            </div>
                           
                        </div>
                        {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
                    </div>
                </div>
            @endforeach

            @else
                <p class="text-center m-5">Nu exista cereri inregistrate.</p>
            @endif
</div>
@endsection