@extends('layouts.app')


@section('content')

<h2 style="text-align:center;">Activitate, Credit si Tranzactii</h2>
<hr>

<div class="row">

    <div class="col-lg-12 mt-4">
        <p>Balanta curenta: <strong>{{ $amount }} RON</strong></p>
        <hr>
    </div>
</div> <!-- end row -->

<form action="{{ route('credits.add') }}" method="POST">
    @csrf


    <div class="row">
        <div class="col">
        <input type="text" class="form-control @error('credit') has-error @enderror" id="credit" placeholder="Suma in ron: 100" name="credit" value="{{ old('credit') }}">
        @error('credit')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
        </div>
        <div class="col">
        <button type="submit" class="btn btn-primary">Fake alimentare cont</button>
        </div>
    </div>
</form>

<br>
<br>
<br>

<!-- plati -->

<div class="row my-4">
    <div class="col mx-2">
        <div class="row rounded" style="background: #f1f1f1;padding: 16px 0px;">
            <div class="col float-left">
                <h5>Total plati</h5>
            </div>
            <div class="col">
                <span class="float-right">{{ $total_amount }} RON</span>
            </div>
        </div>
    </div><!-- / left side --> 
    <div class="col mx-2">
        <div class="rounded" style="background: #f1f1f1;padding: 16px 0px;">
            <div class="row px-1" style="border:bottom: 1px solid black;">
                <div class="col float-left">
                    <h5>Costuri totale</h5>
                </div>
                <div class="col">
                    <span class="float-right">-{{ $total_cost }} RON</span>
                </div>
                {{-- <div style="height: 1px;
                background: black;
                display: block;
                width: 90%;
                margin: 10px auto;"></div> --}}
            </div>

            {{-- <div class="row px-1">
                <ul class="list-group list-group-flush" style="    width: 90%;
                margin: 0 auto;">
                    <li class="list-group-item">
                        <span class="float-left">Taxa contact #122332</span>
                        <span class="float-right">-8 RON</span>
                    </li>

                    <li class="list-group-item">
                        <span class="float-left">Taxa contact #122333</span>
                        <span class="float-right">-6 RON</span>
                    </li>
                </ul>
            </div> --}}

        </div>
    </div> <!-- / right-side -->
</div> <!-- / plati -->


<br><br>

<div class="row">
    <h3>Tranzactii</h3>
    <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Data</th>
            <th scope="col">Tip</th>
            <th scope="col">Suma</th>
          </tr>
        </thead>
        <tbody>
        @if($transactions)
            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                <tr>
                    <th scope="row">#{{ $transaction->id }}</th>
                    <td>{{ $transaction->created_at->diffForHumans() }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>+{{ $transaction->amount }} RON</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">
                        <p class="text-center">Nu exista tranzactii inregistrate.</p>
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
      </table>
</div><!-- / tranzactii -->

<br>
<br>
<br>

<div class="row">
    <h3>Activitate</h3>
    <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Data</th>
            <th scope="col">Tip</th>
            <th scope="col">Cerere</th>
            <th scope="col">Suma</th>
          </tr>
        </thead>
        <tbody>
            @if($activities)
            @if($activities->count() > 0)
                @foreach($activities as $activity)
                <tr>
                    <th scope="row">#{{ $activity->id }}</th>
                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                    <td>{{ $activity->description }}</td>
                    <td><a href="{{ route('demands.show', $activity->demand_id) }}">#{{ $activity->demand_id }}</a></td>
                    <td>-{{ $activity->amount }} RON</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        <p class="text-center">Nu exista tranzactii inregistrate.</p>
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
      </table>
</div><!-- / activitate -->


@endsection