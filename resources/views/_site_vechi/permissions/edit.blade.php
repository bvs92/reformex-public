@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Editeaza permisiune</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <h3>Modifica informatii profil</h3>
        <form method="POST" action="{{ route('permissions.update', $permission->id) }}"> 
                    @csrf
                    @method('PUT')
            
                    <div class="form-row">
                        <div class="col">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="editor" value="{{ old('name') ?? $permission->name }}">
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


@endsection