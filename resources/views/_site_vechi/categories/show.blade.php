@extends('layouts.app')



@section('content')

<div class="row mb-2">
  <div class="col-lg-4">
      <a href="{{ url()->previous() }}" class="btn btn-xs btn-secondary">Inapoi</a>
  </div>
</div>

<h2>{{ $category->name }}</h2>
<br>

<form action="{{ route('categories.update', $category) }}" method="POST">
  @csrf
  @method('PUT')

  <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
          <label for="name">Nume</label>
          <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele categoriei" name="name" value="{{ old('name') ?? $category->name }}">
          @error('name')
              <p class="small text-danger">{{ $message }}</p>
          @enderror
      </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
          <label for="slug">Slug</label>
          <input type="text" class="form-control @error('slug') has-error @enderror" id="slug" placeholder="Adresa url categorie" name="slug" value="{{ old('slug') ?? $category->slug }}">
          @error('slug')
              <p class="small text-danger">{{ $message }}</p>
          @enderror
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="price">Pret</label>
    <input type="text" 
    class="form-control @error('price') has-error @enderror" 
    id="price" 
    placeholder="Pretul cererilor din categorie" 
    name="price" value="{{ old('price') ?? $category->price }}">
    @error('price')
        <p class="small text-danger">{{ $message }}</p>
    @enderror
</div>

  
  <div class="form-group">
      <label for="description">Descriere categorie</label>
      <textarea class="form-control 
      @error('description') has-error @enderror" 
      name="description" id="description" 
      cols="30" rows="6"
      >{{ old('description') ?? $category->description }}</textarea>

      @error('description')
          <p class="small text-danger">{{ $message }}</p>
      @enderror
  </div>

  <div class="form-group row">
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">Editeaza categorie</button>
      </div>
  </div>


</form>


<br>
<h3>Cereri</h3>
{{-- <table class="table">
    <thead class="thead-gray">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Cerere</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($category->demands && $category->demands->count() > 0)
            @foreach($category->demands as $demand)
                <tr>
                    <th scope="row">{{ $demand->id }}</th>
                    <td style="width:40%;">{{ $demand->subject }}</td>
                    <td style="width:40%;">
                      <div class="row">
                        <a href="{{ route('demands.show', $demand) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                        <button type="button" class="btn btn-warning btn-sm m-1">Editeaza</button>
                        <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button>
                        <button type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                      </div>
                    </td>
                </tr>
            @endforeach
      @endif
    </tbody>
  </table> --}}


  @forelse($category->demands as $demand)   
      <div class="card mt-2 shadow-sm" style="border:none;border-bottom:1px solid #f1f1f1;">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-8">
                      <h4 class="card-title mb-5">{{ $demand->subject }}</h4>
                      <p class="small">Publicat: {{ $demand->showPublishDate() }}</p>
                      <p>Oras: <strong>{{ ucfirst($demand->city) }}</strong> </p>
                      <p>Categorie: <strong>{{ $demand->firstCategory() }}</strong>
                  </div> <!-- col-md-8 -->
                  <div class="col-md-4">
                      <div style="color:gray;font-size:14px;text-align:right;margin:20px 0px;">
                          <p class="mb-0">Conxxxxx</p>
                          <p class="mb-0">gerxxxxx@xxxxxx.xxx</p>
                          <p class="mb-0">627 XXX XXX</p>
                      </div>
                      
                      <a href="{{ route('demands.show', $demand) }}" class="btn btn-lg btn-primary float-right">Aplica</a>
                  </div>
              </div>


              


              {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> --}}
          </div>

          <div class="card-footer text-muted">
            <div class="dropdown float-right">
              <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actiuni
              </a>
            
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{ route('demands.show', $demand) }}">Vezi</a>
                <a class="dropdown-item" href="#">Editeaza</a>
                <a class="dropdown-item" href="#">Elimina</a>
              </div>
            </div>
          </div><!-- end card footer -->

      </div>
  @empty
      <p class="text-center m-5">Nu exista cereri inregistrate.</p>
  @endforelse


@endsection