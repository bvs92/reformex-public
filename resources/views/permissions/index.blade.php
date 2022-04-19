@extends('layouts.app')



@section('content')

<div class="col-lg-12 my-2">
    <a href="{{ route('permissions.create') }}" class="btn btn-primary float-right mb-4">Adauga permisiune</a>
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
        @if($permissions && $permissions->count() > 0)
            @foreach($permissions as $permission)
                <tr>
                    <th scope="row">{{ $permission->id }}</th>
                    <td style="width:30%;">{{ $permission->name }}</td>
                    <td style="width:40%;">
                        <div class="row">
                            <a href="{{ route('permissions.show', $permission->id) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                          {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                          <button onclick="event.preventDefault();document.getElementById('deletePermission_{{ $permission->id }}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                          <form 
                            action="{{ route('permissions.destroy', $permission->id) }}" 
                            id="deletePermission_{{ $permission->id }}" 
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