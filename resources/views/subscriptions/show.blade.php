@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Detalii tip abonament</h2>
<hr>

<h3>Nume: {{ $subscription->name }}</h3>

<br>
<br>
<br>

<h3>Utilizatori</h3>
<br>
<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nume</th>
        <th scope="col">E-mail</th>
        <th scope="col">Data Start</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($subscription && $subscription->users()->count() > 0)
            @foreach($subscription->users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td style="width:20%;">{{ $user->name }}</td>
                    <td style="width:20%;">{{ $user->email }}</td>
                    <td style="width:30%;">{{ $user->pivot->created_at }}</td>
                    <td style="width:30%;">
                        <div class="row">
                          <a href="#" class="btn btn-primary btn-sm m-1">Vezi</a>
                          <a href="#" class="btn btn-warning btn-sm m-1">Editeaza</a>
                          <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button>
                          {{-- <button type="button" class="btn btn-danger btn-sm m-1">Elimina</button> --}}
                        </div>
                    </td>
                </tr>
            @endforeach
      @endif
    </tbody>
  </table>

<br>
<br>
<br>
<h3>De adaugat Roluri si alte functionalitati.</h3>

@endsection