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
                            <h3 class="card-title ">Rol <u>{{ $role->name }}</u></h3>
                            @if($users->count() < 1)
                                <div class="card-options">
                                    <a onclick="event.preventDefault();document.getElementById('deleteRole').submit();" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i> Elimină rol</a>
                                </div>
                                <form 
                                    action="{{ route('roles.destroy', $role->id) }}" 
                                    id="deleteRole" 
                                    method="POST" 
                                    style="display: none;">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                            @endif
                        </div>

                        <div class="card-body">
                            <single-role-component :the_users="{{ json_encode($users) }}" :the_role="{{ json_encode($role) }}"></single-role-component>
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
			
	
	

		