@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Detalii utilizator</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <div class="btn-group btn-sm float-right">
          <button type="button btn-sm" class="btn btn-secondary">Actiuni</button>
          <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
          <a class="dropdown-item" href="{{ route('users.edit', $user) }}">Editeaza</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
          </div>
        </div>
      </div>

    <div class="col-lg-12">
        <p>Nume: {{ $user->getName() }}</p>
        <p>Abonament: {{ $user->activeSubscription() }}</p>
    </div>
    <div class="col-lg-6">
        <p>E-mail: {{ $user->email }}</p>
    </div>


    <div class="col-lg-12 mt-4">

      <br><br>
      <h4>Modifica parola cu caractere random? Click pe buton si trimitere email catre utilizator?</h4>
    </div>


    <div class="col-lg-12 mt-4">
      <h2>Roluri</h2>
      <div class="card">
          <div class="card-body">
              @if($roles && $roles->count() > 0)
              <form method="POST" action="{{ route('users.roles', $user->id) }}">
                  @csrf
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                              <label for="rolesNames">Selecteaza roluri.</label>
                                  <select class="form-control" id="rolesNames" name="name[]" multiple="multiple">
                                      @foreach($roles as $role)
                                          <option 
                                          value="{{ $role->id }}"
                                          @if($user->roles->contains($role)) selected="selected" @endif
                                          >{{ $role->name }}
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
                          <button class="btn btn-danger mb-2 float-right" onclick="event.preventDefault();document.getElementById('resetRolesForm').submit();">Elimina roluri</button>
                      </div>
                  </div><!-- end row -->
              </form>

              <form id="resetRolesForm" action="{{ route('users.roles.reset', $user->id) }}" method="POST" style="display: none;">
                  @csrf
              </form>
              @endif
          </div>
      </div>
  </div><!-- end roles -->


    <div class="col-lg-12 mt-4">
      <h2>Permisiuni</h2>
      <div class="card">
          <div class="card-body">
              @if($permissions && $permissions->count() > 0)
              <form method="POST" action="{{ route('users.permissions', $user->id) }}">
                  @csrf
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                              <label for="permissionsNames">Selecteaza permisiuni.</label>
                                  <select class="form-control" id="permissionsNames" name="name[]" multiple="multiple">
                                      @foreach($permissions as $permission)
                                          <option 
                                          value="{{ $permission->id }}"
                                          @if($user->permissions->contains($permission)) selected="selected" @endif
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

              <form id="resetPermissionsForm" action="{{ route('users.permissions.reset', $user->id) }}" method="POST" style="display: none;">
                  @csrf
              </form>
              @endif
          </div>
      </div>
  </div><!-- end permissions -->



</div> <!-- end row -->



<br>
<h3>De adaugat Roluri si alte functionalitati.</h3>

@endsection