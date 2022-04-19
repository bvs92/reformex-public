@extends('layouts.app')



@section('content')

<div class="col-lg-12 my-2">
  <a href="#" class="btn btn-primary float-right mb-4">Adauga utilizator</a>
</div>

<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nume</th>
        <th scope="col">E-mail</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($users && $users->count() > 0)
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td style="width:30%;">{{ $user->getName() }}</td>
                    <td style="width:30%;">{{ $user->email }}</td>
                    <td style="width:40%;">
                        <div class="row">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                          {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                          <button onclick="event.preventDefault();document.getElementById('deleteSubscription_{{ $user->id }}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                          <form 
                            action="{{ route('users.destroy', $user) }}" 
                            id="deleteSubscription_{{ $user->id }}" 
                            method="POST" 
                            style="display: none;">
                              @csrf
                              <input type="hidden" name="_method" value="DELETE">
                          </form>
                        </div>
                    </td>

                </tr>
            @endforeach
      @endif
    </tbody>
  </table>




@endsection