@extends('volgh.layouts.master')
@section('css')
@endsection


@section('title-page')
<title>Adaugă un proiect în portofoliu</title>
@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Proiecte</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('work-projects.personal') }}">Proiecte</a></li>
                <li class="breadcrumb-item active" aria-current="page">Adaugă proiect</li>
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
                            <h3 class="card-title ">Adaugă proiect nou îm portofoliu</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <add-new-work-projects-component></add-new-work-projects-component>
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

@endsection


@section('footer-scripts')

@endsection
			
	
	

		