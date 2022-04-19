@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Detalii utilizator</h2>
<hr>

<div class="row">
    <div class="col-lg-12">
        <h3>Modifica informatii profil</h3>
            <form method="POST" action="{{ route('users.admin.updateProfile', $user) }}"> 
                    @csrf
                    @method('PUT')
            
                    <div class="form-row">
                            <div class="col">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Popescu" value="{{ old('first_name') ?? $user->first_name }}">
                                    @error('first_name')
                                    <p class="small text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div class="col">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Andrei" value="{{ old('last_name') ?? $user->last_name}}">
                                    @error('last_name')
                                    <p class="small text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                    </div>
                    <br>
                    <div class="form-row">
                            <div class="col">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nume@email.com" value="{{ old('email') ?? $user->email }}">
                                    @error('email')
                                    <p class="small text-danger">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div class="col">
                                    <button type="submit" class="btn btn-primary mb-2 float-right">Salveaza modificari</button>
                            </div>
                    </div>
            </form>
        </div>


    <div class="col-lg-12 mt-4">
        <h3>Modifica parola (pentru Admin)</h3>
        <form method="POST" action="{{ route('users.admin.ChangePassword', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-12">
                        <label for="password">Noua Parola</label>
                        <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <p class="small text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-lg-12">
                        <div class="align-bottom">
                            <button type="submit" class="btn btn-primary my-2">Modifica parola</button>
                        </div>
                    </div>
                </div>
              
        </form>

        <br><br>
        <p class="my-4">Modifica parola cu caractere random? Click pe buton si trimitere email catre utilizator?</p>
        </div>


<br>
<br>
<br>

        <div class="col-lg-12">
            <h3>Abonament</h3>
            <p>Abonament curent: <strong>{{ $user->activeSubscription() }}</strong></p>


            <form method="POST" action="{{ route('users.admin.updateSubscription', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-group">
                                <label for="subscription">Abonament</label>
                                @if($subscriptions && $subscriptions->count() > 0)
                                        <select class="form-control  @error('subscription') is-invalid @enderror" id="subscription" name="subscription">
                                            @foreach($subscriptions as $sub)   
                                                @if($user->firstSubscription())
                                                        <option value="{{ $sub->id }}" @if($sub->id == $user->firstSubscription()->id) selected="selected" @endif>{{ $sub->name }}</option>
                                                @else
                                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('subscription')
                                                <p class="small text-danger">{{ $message }}</p>
                                        @enderror
                                @endif
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="align-bottom">
                            <button type="submit" class="btn btn-primary my-2">Modifica abonament</button>
                        </div>
                    </div>
                </div>
          </form>
        </div>




</div> <!-- end row -->



<br>
<h3>De adaugat Roluri si alte functionalitati.</h3>

@endsection