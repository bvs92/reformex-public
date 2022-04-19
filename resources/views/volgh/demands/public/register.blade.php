@extends('volgh.layouts.master-public')
@section('css')

@endsection

@section('head-scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script> --}}
@endsection


@section('title-page')
<title>Contact - Înregistrare cerere în platforma REFORMEX
@endsection

@section('content')

            <!-- ROW-5 -->
            <div class="row" style="padding :20px;">
                <div class="col-lg-12 col-sm-12">
                    <div class="row mb-5">
                        <div class="col-lg-4">
                            <a href="https://www.reformex.ro">
                                <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" alt="reformex" style="width: 180px; max-width: 180px;">
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <p style="font-size: 24px;padding-top: 15px;">Lansează o cerere în <strong>4 pași simpli</strong>. Obține minim <strong>3 oferte</strong>.</p>
                        </div>

                        <div class="col-lg-2 py-4">
                            <quit-public-demand-component></quit-public-demand-component>
                        </div>
                    </div>
                    <register-demand-component></register-demand-component>
                    {{-- <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Adauga o noua cerere</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                
                            </div>
                        </div>
                    </div> --}}
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection


	

		