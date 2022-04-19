@extends('layouts.app')


@section('content')


            <div class="row">
                <div class="col-lg-4">
                    <a href="{{ route('demands.index') }}" class="btn btn-xs btn-secondary">Inapoi</a>
                </div>
            </div>


            <h1 style="text-align:center;">Detalii cerere</h1>
            <hr>

                <h2>Editeaza cotatia de pret</h2>
                <br>
                <form method="POST" action="{{ route('quotes.update', $quote) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="price">Estimare Pret total</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="20.000" value="{{ $quote->price ?? old('price') }}">
                        @error('price')
                            <p class="small text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Descrieti cotatia dvs</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3">{{ $quote->message ?? old('message') }}</textarea>
                        @error('message')
                            <p class="small text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Editeaza cotatia de pret</button>
                        </div>
                    </div>

                </form>
    
@endsection