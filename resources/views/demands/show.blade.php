@extends('layouts.single')


@section('content')


<div class="row mb-2">
    <div class="col-lg-4">
        <a href="{{ url()->previous() }}" class="btn btn-xs btn-secondary">Inapoi</a>
    </div>
</div>

<div class="row justify-content-center p-4" style="background: #EDEDED;">
    <div class="col-md-8 p-4" style="background: white;">
        <h4>Subiect: {{ $demand->subject }}</h4>
        <hr>

        <div>
            <h5>Descriere cerere</h5>
            {{ $demand->message }}
        </div>

        <hr>

        <p>Cerere trimisa: {{ $demand->showPublishDate() }}</p>
        <hr>
        <p>Oras: {{ ucfirst($demand->city) }}</p>
        <hr>
        <p>Categorie: <strong>{{ $demand->firstCategory() }}</strong></p>
        <hr>
        <p>Localizare: 20 KM de locatia dvs (Bucuresti)</p>
        <img src="{{ asset('images/staticmap.png') }}" alt="">
    
        <br>
    </div><!-- end col-lg-8 -->

    <div class="col-md-4 p-4">
        <h4>Contactati persoana</h4>
        <hr>
        <div class="card shadow-sm">
            <div class="card-body" style="padding:1rem 1.5rem;">
                <p class="text-muted" style="font-size:18px;"><i class="fa fa-user"></i> {{ $demand->name }}</p>
                <p class="text-muted" style="font-size:18px;"><i class="fa fa-at"></i> {{ $demand->email }}</p>
                <p class="text-muted" style="font-size:18px;"><i class="fa fa-phone"></i> {{ $demand->phone }}</p>
            </div>
        </div>

        @if($demand->hasProfessional(auth()->user()->professional))
            <button type="button" class="btn btn-primary btn-block my-2">Trimite mesaj</button>
        @else
            <form action="{{ route('demands.buy', $demand->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-block my-2">Deblocheaza cererea ({{ $demand->getCalculatedPrice() }} RON)</button>
            </form>
        @endif

    </div><!-- end col-lg-4 -->
</div><!-- end row -->

<br><br>


    <div class="row">
        @if($demand->quotes)
        <div class="col-lg-12">
            @forelse($demand->quotes()->latest()->get() as $quote)
                <div class="card mt-2">
                    <div class="card-header">
                        De la: <strong>{{ $quote->showProName() }}</strong>
                    </div>
                    <div class="card-body">
                        <p>Pret estimativ: {{ $quote->price }}</p>
                        <h5>Descriere cotatie</h5>
                        <div>
                            {{ $quote->message }}
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                Trimis: {{ $quote->showPublishDate() }}
                            </div>


                            @if($quote->professional_id == auth()->user()->professional->id)
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-sm btn-warning float-left">Editeaza</a>
                                <form action="{{ route('quotes.destroy', $quote) }}" method="POST" style="float:left;margin-left:10px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Sterge</a>
                                </form>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <p class="text-center">Nu exista inca cotatii pentru aceasta cerere.</p>
            @endforelse
        </div>
        @endif
    </div>


    @if($demand->hasProfessional(auth()->user()->professional))
        <br>
        <hr>
        <h3>Trimite o cotatie de pret</h3>
        <br>
        <form method="POST" action="{{ route('demand.quote.store', $demand) }}">
            @csrf
            <div class="form-group">
                <label for="price">Estimare Pret total</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="20.000" value="{{ old('price') }}">
                @error('price')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="message">Descrieti cotatia dvs</label>
                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                @error('message')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Trimite cotatia de pret </button>
                </div>
            </div>

        </form>
      @endif

@endsection