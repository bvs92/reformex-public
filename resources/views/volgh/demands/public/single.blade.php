@extends('volgh.layouts.master-public')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
@endsection

@section('head-scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script> --}}
@endsection

@section('title-page')
<title>Cerere #{{$demand->uuid}}</title>
@endsection

@section('content')

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12 m-4">
                    @if($demand)
                        <public-single-demand-component :the_demand="{{ json_encode($demand) }}" :unique="{{ json_encode($unique) }}"></public-single-demand-component>
                    @else
                    <div class="card">
                        <div class="card-header ">
                            {{-- <h3 class="card-title ">Detalii cerere</h3> --}}
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <h4>Cererea nu există.</h4>
                            </div>
                        </div>
                    </div>

                        
                    @endif
                    
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
@endsection
	

		