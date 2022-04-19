@extends('layouts.app')



@section('content')

<div class="col-lg-12 my-2">
  <a href="{{ route('categories.create') }}" class="btn btn-primary float-right mb-4">Adauga categotrie</a>
</div>

<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Denumire</th>
        <th scope="col">Cereri</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($categories && $categories->count() > 0)
            @foreach($categories as $category)
                <tr>
                    <th scope="row">{{ $category->id }}</th>
                    <td style="width:40%;">{{ $category->name }}</td>
                    <td style="width:10%;">{{ $category->demands()->count() }}</td>
                    <td style="width:40%;">
                        <div class="row">
                          <a href="{{ route('categories.show', $category) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                          <button type="button" class="btn btn-warning btn-sm m-1">Editeaza</button>
                          <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button>
                          <button type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                        </div>
                    </td>
                </tr>
            @endforeach
      @endif
    </tbody>
  </table>


@endsection