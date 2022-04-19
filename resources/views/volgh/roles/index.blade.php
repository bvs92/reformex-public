@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Roluri</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasă</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roluri</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
			

            <!-- start new users list -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header ">
                            <h3 class="card-title ">Roluri</h3>
                            <add-new-role-component></add-new-role-component>
                        </div>

                        <div class="card-body">
                            <roles-component></roles-component>
                        </div>
                
                    </div>
                    
                </div><!-- Users List - COL-END -->
            </div><!-- end new users list -->

        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')

@endsection
			
	
	

		