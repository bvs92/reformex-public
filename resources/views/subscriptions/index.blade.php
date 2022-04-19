@extends('layouts.app')



@section('content')

<div class="col-lg-12 my-2">
  <a href="{{ route('subscriptions.create') }}" class="btn btn-primary float-right mb-4">Adauga tip abonament</a>
</div>

<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Denumire</th>
        <th scope="col">Utilizatori</th>
        <th scope="col">Actiuni</th>
      </tr>
    </thead>
    <tbody>
        @if($subscriptions && $subscriptions->count() > 0)
            @foreach($subscriptions as $subscription)
                <tr>
                    <th scope="row">{{ $subscription->id }}</th>
                    <td style="width:40%;">{{ $subscription->name }}</td>
                    <td style="width:10%;">{{ $subscription->users()->count() }}</td>
                    <td style="width:40%;">
                        <div class="row">
                          <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-primary btn-sm m-1">Vezi</a>
                          <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning btn-sm m-1">Editeaza</a>
                          {{-- <button type="button" class="btn btn-danger btn-sm m-1">Sterge</button> --}}
                          <button onclick="event.preventDefault();document.getElementById('deleteSubscription_{{$subscription->id}}').submit();" type="button" class="btn btn-danger btn-sm m-1">Elimina</button>
                          <form 
                            action="{{ route('subscriptions.destroy', $subscription) }}" 
                            id="deleteSubscription_{{ $subscription->id }}" 
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