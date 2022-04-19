@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Detalii rol</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <div class="btn-group btn-sm float-right">
          <button type="button btn-sm" class="btn btn-secondary">Actiuni</button>
          <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
          <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Editeaza</a>
            <a class="dropdown-item" onclick="event.preventDefault();document.getElementById('deleteRole_{{ $role->id }}').submit();">Elimina</a>
                <form 
                action="{{ route('roles.destroy', $role->id) }}" 
                id="deleteRole_{{ $role->id }}" 
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
                <p>Denumire rol: {{ $role->name }}</p>
                <hr>
                <p>Guard: {{ $role->guard_name }}</p>
            </div>
        </div>
    </div>


    <div class="col-lg-12 mt-4">
        <h2>Permisiuni</h2>
        <div class="card">
            <div class="card-body">
                @if($permissions && $permissions->count() > 0)
                <form method="POST" action="{{ route('roles.permissions', $role->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="permissionsNames">Selecteaza permisiuni.</label>
                                    <select class="form-control" id="permissionsNames" name="name[]" multiple="multiple">
                                        @foreach($permissions as $permission)
                                            <option 
                                            value="{{ $permission->id }}"
                                            @if($role->permissions->contains($permission)) selected="selected" @endif
                                            >{{ $permission->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <p class="small text-danger">{{ $message }}</p>
                                    @enderror

                            </div><!-- end form-group -->
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary mb-2 float-left">Salveaza modificari</button>
                            <button class="btn btn-danger mb-2 float-right" onclick="event.preventDefault();document.getElementById('resetPermissionsForm').submit();">Elimina permisiuni</button>
                        </div>
                    </div><!-- end row -->
                </form>

                <form id="resetPermissionsForm" action="{{ route('roles.permissions.reset', $role->id) }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endif
            </div>
        </div>
    </div><!-- end permisiuni -->

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

                @if($role && $role->users()->count() > 0)
                    @foreach($role->users as $user)
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

    </div>



</div> <!-- end row -->

@endsection