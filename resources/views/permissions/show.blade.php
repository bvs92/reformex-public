@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Detalii permisiune</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <div class="btn-group btn-sm float-right">
          <button type="button btn-sm" class="btn btn-secondary">Actiuni</button>
          <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
          <a class="dropdown-item" href="{{ route('permissions.edit', $permission->id) }}">Editeaza</a>
            <a class="dropdown-item" onclick="event.preventDefault();document.getElementById('deletePermission_{{ $permission->id }}').submit();">Elimina</a>
                <form 
                action="{{ route('permissions.destroy', $permission->id) }}" 
                id="deletePermission_{{ $permission->id }}" 
                method="POST" 
                style="display: none;">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            <a class="dropdown-item" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
          </div>
        </div>
      </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <p>Denumire: <strong>{{ $permission->name }}</strong></p>
                <hr>
                {{-- <p>Guard: {{ $role->guard_name }}</p> --}}
            </div>
        </div>
    </div>


    <div class="col-lg-12 mt-4">
        <h2>Roluri</h2>
        <table class="table">
            <thead class="thead-light">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nume</th>
                <th scope="col">Actiuni</th>
              </tr>
            </thead>
            <tbody>

                @if($permission && $permission->roles()->count() > 0)
                    @foreach($permission->roles as $role)
                        <tr>
                            <th scope="row">{{ $role->id }}</th>
                            <td style="width:30%;">{{ $role->name }}</td>
                            <td style="width:40%;">
                                <div class="row">
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                                  {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                                  <button onclick="event.preventDefault();document.getElementById('deleteSubscription_{{ $role->id }}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                                  <form 
                                    action="{{ route('roles.destroy', $role) }}" 
                                    id="deleteSubscription_{{ $role->id }}" 
                                    method="POST" 
                                    style="display: none;">
                                      @csrf
                                      <input type="hidden" name="_method" value="DELETE">
                                  </form>
                                </div>
                            </td>

                        </tr>
                    @endforeach

                @else
                        <tr>
                            <td colspan="3" style="text-align: center;">Nu exista utilizatori asociati cu acest rol.</td>
                        </tr>
                @endif
                
            </tbody>
          </table>

    </div><!-- end roles -->


    
    <div class="col-lg-12 mt-4">
        <h2>Utilizatori</h2>
        <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nume</th>
                <th scope="col">Actiuni</th>
              </tr>
            </thead>
            <tbody>

                @if($permission && $permission->users()->count() > 0)
                    @foreach($permission->users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td style="width:30%;">{{ $user->getName() }}</td>
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

                @else
                        <tr>
                            <td colspan="3" style="text-align: center;">Nu exista utilizatori asociati cu acest rol.</td>
                        </tr>
                @endif
                
            </tbody>
          </table>

    </div><!-- end utilizatori -->


</div> <!-- end row -->

@endsection