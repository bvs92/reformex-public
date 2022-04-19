@extends('volgh.layouts.master-public')
@section('css')

@endsection

@section('head-scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script> --}}
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
                        <div class="col-lg-8">
                            <p style="font-size: 24px;padding-top: 15px;">Lansati cererea in <strong>4 pasi simpli</strong>. Obtineti pana la <strong>5 oferte</strong>.</p>
                        </div>
                    </div>
                    <test-image-component></test-image-component>
                    
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection


	

		