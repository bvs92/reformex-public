@extends('layouts.app')



@section('content')

<div class="col-lg-12 my-2">
    <a href="{{ route('roles.create') }}" class="btn btn-primary float-right mb-4">Adauga rol</a>
</div>

<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nume</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($roles && $roles->count() > 0)
            @foreach($roles as $role)
                <tr>
                    <th scope="row">{{ $role->id }}</th>
                    <td style="width:30%;">{{ $role->name }}</td>
                    <td style="width:40%;">
                        <div class="row">
                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                          {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                          <button onclick="event.preventDefault();document.getElementById('deleteRole_{{ $role->id }}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                          <form 
                            action="{{ route('roles.destroy', $role->id) }}" 
                            id="deleteRole_{{ $role->id }}" 
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