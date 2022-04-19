@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Editeaza rol</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <h3>Modifica informatii profil</h3>
        <form method="POST" action="{{ route('roles.update', $role->id) }}"> 
                    @csrf
                    @method('PUT')
            
                    <div class="form-row">
                        <div class="col">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="editor" value="{{ old('name') ?? $role->name }}">
                                @error('name')
                                <p class="small text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="col">
                                <button type="submit" class="btn btn-primary mb-2">Salveaza modificari</button>
                        </div>
                    </div>
            </form>
        </div>

</div> <!-- end row -->

<br>
<h2>Modificare permisiuni.</h2>

@endsection