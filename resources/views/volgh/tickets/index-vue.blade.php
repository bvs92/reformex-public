@extends('volgh.layouts.master')
@section('css')
@endsection

@section('title-page')
<title>Tichetele mele</title>
@endsection


@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Tichete</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tichete</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            @hasanyrole('admin|editor|moderator')
                <list-tickets-for-admin-component></list-tickets-for-admin-component>
            @else
                <list-personal-tickets-component></list-personal-tickets-component>
            @endhasanyrole
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')



@endsection
			
	
	

		