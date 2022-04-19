@extends('volgh.layouts.master')

@section('head-scripts')

@endsection


@section('css')

@endsection
@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Recenzii</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Acasa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recenzii</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Recenzii</h2>
                            <div class="card-options">
                              
                            </div>
                        </div>

                        <div class="card-body">
                            
                         
                            <list-all-reviews-component></list-all-reviews-component>
                          
                        </div>
                    </div>
                </div>
              
            </div>
            <!-- ROW-1 CLOSED -->


        </div>
    </div>
    <!--CONTAINER CLOSED -->
</div>
@endsection
@section('js')
{{-- <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script> --}}
@endsection

@section('footer-scripts')



@endsection