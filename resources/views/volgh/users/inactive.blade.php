@extends('volgh.layouts.master-inactive')
@section('css')
@endsection

@section('content')

<!-- ROW-4 -->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

        @if(auth()->user()->status == 0 && !auth()->user()->registration)
        <div class="alert alert-danger" role="alert">
            <i class="fe fe-info" aria-hidden="true"></i> <strong>Cont inactiv.</strong> Trimite un email pentru a discuta cu moderatorii REFORMEX despre situația contului.
        </div>
        @endif

        @if(auth()->user()->status == 0 && auth()->user()->registration)
        <div class="alert alert-danger" role="alert">
            <i class="fe fe-info" aria-hidden="true"></i> <strong>Cont inactiv.</strong> Completează informațiile de mai jos pentru activarea contului.
        </div>
        @endif

        @if(auth()->user()->status == 1 )
        <div class="alert alert-success" role="alert">
            <i class="fe fe-info" aria-hidden="true"></i> <strong>Felicitări.</strong> Contul tău este acum activ.
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Accesează versiunea normală a platformei.</div>
                    </div>
                    <div class="card-body">
                        Începe să folosești platforma REFORMEX. <a href="{{ route('home') }}" class="btn btn-info">Începe aici.</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
              Află informații despre starea contului.
            </div>
            <div class="card-body">
              <h5 class="card-title">Intră în legatură cu echipa REFORMEX.</h5>
              <p class="card-text">Probleme cu contul? Trimite un email pe adresa <strong>suport@reformex.ro</strong> cu detalii despre cont: e-mailul contului, numele, problema semnalată.</p>
            </div>
          </div>
      

        {{-- @if(auth()->user()->registration)
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informatii companie</div>
            </div>
            <div class="card-body">
                <inactive-user-company-information :company_info="{{ json_encode($company) }}" :the_registration="{{ json_encode(auth()->user()->registration) }}"></inactive-user-company-information>
            </div>
        </div>
        @endif --}}
        
    </div>
</div>
<!-- ROW-4 END -->

        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection

			
	
	

		