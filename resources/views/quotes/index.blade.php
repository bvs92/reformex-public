@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Cotatii trimise</h2>



    @forelse($quotes as $quote)   
        <div class="card mt-2">
            <div class="card-body">
                <p class="card-title mb-5">{{ $quote->demand->subject }}</p>
                {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
            
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <p>Cotatii: <span class="badge badge-secondary ">
                            {{ $quote->demand->quotesNumber() }}    
                        </span></p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <p>Oras: <strong>{{ ucfirst($quote->demand->city) }}</strong> </p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <p>Categorie: <strong>{{ $quote->demand->firstCategory() }}</strong>  
                        </span></p>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-6 col-md-6">
                        <p>Publicat: {{ $quote->demand->showPublishDate() }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <a href="{{ route('demands.show', $quote->demand) }}" class="btn btn-sm btn-primary">Vezi cotatie</a>
                        <a href="{{ route('demands.show', $quote->demand) }}" class="btn btn-sm btn-secondary">Vezi cerere</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center m-5">Nu exista cotatii de pret inregistrate.</p>
    @endforelse
@endsection