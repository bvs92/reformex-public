@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Plati</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Plati</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plata #{{ $payment->id }}</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-5 -->
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header ">
                        <h2 class="card-title ">Numar ordine plata: #{{ $payment->id }}</h2>
                        <div class="card-options">
                            {{-- <a href="{{ route('demands.create') }}" id="add__new__demand" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a> --}}
                            <div class="dropdown float-right">
                                <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-more"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                   
                                    {{-- <a onclick="event.preventDefault();document.getElementById('relauchDemand').submit();" class="dropdown-item"><i class="ti-reload"></i> Relanseaza</a> --}}
                                    <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="dropdown-item"><i class="ti-trash"></i> Elimina</a>
                                   
                                    {{-- <form 
                                        action="{{ route('demands.destroy', $demand->id) }}" 
                                        id="deleteDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form> --}}

                                    {{-- <form 
                                        action="{{ route('demands.relaunch', $demand->id) }}" 
                                        id="relauchDemand" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form> --}}

                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid-margin">
                            <div class="">
                                <div class="row justify-content-center p-4">
                                    <div class="col-md-6 p-4" style="background: white;">


                                        <div class="row my-2">
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon ti-time"></i> {{ formatCarbonDate(\Carbon\Carbon::createFromTimestamp($payment->created)) }}</p>
                                            </div>
                                            <div class="col-lg-6">
                                                <p><i class="side-menu__icon fa fa-money"></i> {{ $payment->amount / 100 }} RON</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                @if(isset($payment_method))
                                                    <p>Metoda plata: {{ ucfirst($payment_method->type) }}</p>
                                                @endif

                                            </div>
                                            <div class="col-lg-6">
                                                <p>Status: 
                                                    @if($payment->status == 'succeeded')
                                                        <span class="badge badge-success  mr-1 mb-1 mt-1">{{ getStatus($payment->status) }}</span>
                                                    @elseif($payment->status == 'canceled')
                                                        <span class="badge badge-danger  mr-1 mb-1 mt-1">{{ getStatus($payment->status) }}</span>
                                                    @elseif($payment->status == 'processing')
                                                        <span class="badge badge-default  mr-1 mb-1 mt-1">{{ getStatus($payment->status) }}</span>
                                                    @else
                                                        <span class="badge badge-warning  mr-1 mb-1 mt-1">{{ getStatus($payment->status) }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                            @if(isset($payment_method))    
                                                @if($payment_method->type == 'card')
                                                    <p><i class="fa fa-credit-card" aria-hidden="true"></i> {{ str_pad($payment_method->card->last4, 14, '*', STR_PAD_LEFT) }} <span class="mx-2">(<strong>{{ucfirst($payment_method->card->brand) }}</strong>)</span> <span class="text-muted mx-2">{{ $payment_method->card->exp_month }}/{{ $payment_method->card->exp_year }}</span></p>
                                                @endif
                                            @endif
                                            </div>
                                            <div class="col-lg-6">
                                                {{-- <p>Firme participante: #</p> --}}
                                            </div>
                                        </div>

                                    </div><!-- end col-lg-8 -->
                                
                                    <div class="col-md-6 p-4">
                                        <ul class="list-group my-2">
                                            <li class="list-group-item">Nume: {{ $customer->getName() }}</span></li>
                                            <li class="list-group-item">E-mail: {{ $customer->email }}</li>
                                            {{-- @if($customer)
                                                <li class="list-group-item">Telefon: {{ $customer }}</li>
                                            @endif --}}
                                        </ul>

                                        @if($payment->status == 'succeeded')
                                        <div class="row">
                                            @if($payment->charges && $payment->charges->total_count > 0)
                                                @foreach($payment->charges as $charge)
                                                    @if($charge->status == 'succeeded' && !$charge->refunded)

                                                        @if(auth()->user()->credit->amount >= $payment->amount_received)
                                                        <div class="col d-flex justify-content-center">
                                                            @if($refunds_demands)
                                                                @if($refunds_demands->count() <= 0)
                                                                <form action="{{ route('refundsdemands.store') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $payment->id }}" name="payment_intent_id">
                                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="ti-back-left"></i> Solicita rambursare credit</button>
                                                                </form>
                                                                @endif {{-- end if for COUNT --}}
                                                            @endif
                                                        </div>

                                                        <div class="col d-flex justify-content-center">
                                                            {{-- <pre>{{$charge}}</pre> --}}
                                                            {{-- <pre>{{$invoices}}</pre> --}}
                                                            <a href="{{ $charge->invoice }}" class="btn btn-primary btn-sm mx-1" target="_blank"><i class="ti-eye"></i> Chitanta plata</a>
                                                            <a href="{{ route('invoices.show', ['222-333', $customer->stripe_id]) }}" class="btn btn-primary btn-sm mx-1" target="_blank"><i class="ti-import"></i> Previzualizare factura</a>
                                                            <a href="{{ route('invoices.show.charge', [$charge->id]) }}" class="btn btn-primary btn-sm mx-1" target="_blank"><i class="ti-import"></i> Previzualizare CHARGE</a>
                                                            <a href="{{ $charge->receipt_url }}" class="btn btn-primary btn-sm mx-1" target="_blank"><i class="ti-import"></i> Descarca factura</a>
                                                            {{-- <form action="">
                                                                @csrf
                                                            </form> --}}
                                                        </div>
                                                        @endif

                                                        <div class="col-lg-12">
                                                            @if($invoices && $invoices->count() > 0)
                                                                @foreach($invoices as $invoice)
                                                                    <a href="{{ route('invoices.show', [$invoice->uuid, $customer->stripe_id]) }}" class="btn btn-primary btn-sm mx-1" target="_blank"><i class="ti-import"></i> Vezi factura (bun)</a>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            
                                        </div>
                                        @endif
                                        
                                    </div><!-- end col-lg-4 -->
                            </div><!-- end row -->

                            @if($payment->charges && $payment->charges->total_count > 0)
                            <div class="row mt-6">
                                <div class="col-lg-6">
                                    <p>Detalii despre plata: </p>
                                    <ul class="list-group">
                                        @foreach($payment->charges as $charge)
                                        <li class="listunorder1" data-toggle="tooltip" data-placement="top" title="ID: {{ $charge->id }}">
                                            Platit <strong>{{ $charge->amount / 100 }} RON</strong> 
                                            @if($charge->status == 'succeeded')
                                                <span class="badge badge-success float-right">{{ getStatus($charge->status) }}</span>
                                            @elseif($charge->status == 'failed')
                                                <span class="badge badge-danger float-right">{{ getStatus($charge->status) }}</span>
                                            @elseif($charge->status == 'pending')
                                                <span class="badge badge-warning float-right">{{ getStatus($charge->status) }}</span>
                                            @endif
                                            <span class="text-small text-muted">({{ formatCarbonDate(\Carbon\Carbon::createFromTimestamp($charge->created)) }})</span> 
                                        </li>
                                        <li><pre>{{$charge}}</pre></li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="col-lg-6">
                                    <p>
                                        Suma restituita: <strong>{{ $charge->amount_refunded / 100 }} RON</strong>
                                        <span class="float-right">Restituire completa: 
                                            @if($charge->refunded)
                                                <span class="badge badge-success  mr-1 mb-1 mt-1">Da</span>
                                            @else
                                                <span class="badge badge-danger  mr-1 mb-1 mt-1">Nu</span>
                                            @endif
                                        </span>
                                    </p>
                                    @if($charge->refunds && $charge->refunds->total_count > 0)
                                    <h5>Rambursari</h5>
                                    <ul class="list-group">
                                        @foreach($charge->refunds as $refund)
                                            <li class="listunorder1" data-toggle="tooltip" data-placement="top" title="ID: {{ $refund->id }}">
                                                {{ $refund->amount / 100 }} RON
                                                @if($refund->status == 'succeeded')
                                                    <span class="badge badge-success float-right">{{ getStatus($refund->status) }}</span>
                                                @elseif($refund->status == 'failed')
                                                    <span class="badge badge-danger float-right">{{ getStatus($refund->status) }}</span>
                                                @elseif($refund->status == 'pending')
                                                    <span class="badge badge-warning float-right">{{ getStatus($refund->status) }}</span>
                                                @endif
                                                <span class="text-small text-muted">({{ formatCarbonDate(\Carbon\Carbon::createFromTimestamp($refund->created)) }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif

                                    @if($refunds_demands && $refunds_demands->count() > 0)
                                    <h5 class="mt-5">Cereri rambursare plata</h5>
                                    <ul class="list-group">
                                        @foreach($refunds_demands as $refund_demand)
                                            <li class="listunorder1">Cerere rambursare numar #{{ $refund_demand->id }}: 
                                                @if($refund_demand->status == '0')
                                                    <span class="badge badge-warning  mr-1 mb-1 mt-1">In curs</span>
                                                @elseif($refund_demand->status == '1')
                                                    <span class="badge badge-success  mr-1 mb-1 mt-1">Acceptat</span>
                                                @else
                                                    <span class="badge badge-danger  mr-1 mb-1 mt-1">Respins</span>
                                                @endif
                                                <span class="text-small text-muted">({{ formatCarbonDate($refund_demand->created_at) }})</span>
                                            </li>
                                        @endforeach 
                                    </ul>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!--start charges -->
                            {{-- <div class="row mt-6">
                                <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                    <thead class="thead-dark">
                                        <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Suma platita</th>
                                        <th scope="col">Suma rambursata</th>
                                        <th scope="col">Ramburs complet</th>
                                        <th scope="col">Rambursari</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actiuni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($payment->charges && $payment->charges->total_count > 0)
                                            @foreach($payment->charges as $charge)
                                                <tr>
                                                    <td>{{ $charge->id }}</td>
                                                    <td style="width:20%;">{{ $charge->amount / 100 }} RON</td>
                                                    <td style="width:20%;">{{ $charge->amount_refunded / 100 }} RON</td>
                                                    <td style="width:20%;">
                                                        @if($charge->refunded)
                                                        <span class="badge badge-success  mr-1 mb-1 mt-1">Da</span>
                                                        @else
                                                            <span class="badge badge-danger  mr-1 mb-1 mt-1">Nu</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($charge->refunds && $charge->refunds->total_count > 0)
                                                        <ul>
                                                            @foreach($charge->refunds as $refund)
                                                                <li>
                                                                    {{ $refund->amount / 100 }}
                                                                    @if($refund->status == 'succeeded')
                                                                        <span class="badge badge-success  mr-1 mb-1 mt-1">{{ getStatus($refund->status) }}</span>
                                                                    @elseif($refund->status == 'failed')
                                                                        <span class="badge badge-danger  mr-1 mb-1 mt-1">{{ getStatus($refund->status) }}</span>
                                                                    @elseif($refund->status == 'pending')
                                                                        <span class="badge badge-warning  mr-1 mb-1 mt-1">{{ getStatus($refund->status) }}</span>
                                                                    @endif
                                                                    <span class="text-small text-muted">{{ formatCarbonDate(\Carbon\Carbon::createFromTimestamp($refund->created)) }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                    <td style="width:10%;">
                                                        @if($charge->status == 'succeeded')
                                                        <span class="badge badge-success  mr-1 mb-1 mt-1">{{ getStatus($charge->status) }}</span>
                                                        @elseif($charge->status == 'failed')
                                                            <span class="badge badge-danger  mr-1 mb-1 mt-1">{{ getStatus($charge->status) }}</span>
                                                        @elseif($charge->status == 'pending')
                                                            <span class="badge badge-warning  mr-1 mb-1 mt-1">{{ getStatus($charge->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="width:10%;">

                                                        <div class="dropdown float-right">
                                                            <a class="btn btn-default btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="ti-more"></i>
                                                            </a>
                            
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <a onclick="event.preventDefault();document.getElementById('##').submit();" class="dropdown-item"><i class="ti-eye"></i> Vezi</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    </table>
                            </div><!-- end charges --> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
        </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}
@endsection
			
	
	

		