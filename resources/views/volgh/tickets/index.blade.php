@extends('volgh.layouts.master')
@section('css')
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
			
            <div class="row">
                <div class="col-12 col-sm-12">
                        @hasanyrole('admin|editor|moderator')
                            @include('volgh.tickets._includes.moderator')
                        @else
                            @include('volgh.tickets._includes.user')
                        @endhasanyrole
                </div>
            </div>

            
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')


<script>

function deleteTicket(ticket_uuid){
    window.Swal.fire({
        title: 'Esti sigur ca vrei sa elimini acest tichet?',
        text: 'Eliminarea acestuia va fi definitiva si vor fi sterse toate mesajele si fisierele asociate.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Da, elimina!',
        cancelButtonText: 'Nu, renunta'
        }).then((result) => {
        if (result.value) {

            document.getElementById('deleteTicket-' + ticket_uuid).submit();

            Swal.fire(
                'Eliminat!',
                'Tichetul a fost eliminat.',
                'success'
            )
        }
        })
}
</script>


{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}
@endsection
			
	
	

		