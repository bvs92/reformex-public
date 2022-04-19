@extends('volgh.layouts.master')
@section('css')
@endsection

@section('title-page')
<title>Panou de control - REFORMEX</title>
@endsection


@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Panou de control</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Acasă</a></li>
            <li class="breadcrumb-item active" aria-current="page">Panou de control</li>
        </ol>
    </div>							
<!-- PAGE-HEADER END -->
@endsection
@section('content')

<div class="row mb-2">
    <div class="col-lg-12">
        <div class="alert alert-info" role="alert">
            <strong>Bun venit în aplicația REFORMEX.</strong> Pentru a explora cererile lansate de clienți, vizitează pagina <a href="/cereri/explorare" class="alert-link">Explorare cereri</a>. Caută cereri în funcție de locație și categorii sau listează toate cererile.
          </div>
    </div>
</div>

@if($credit < 20)
<div class="row mb-2">
    <div class="col-lg-12">
        <div class="alert alert-warning" role="alert">
            <strong>Credit insuficient.</strong> Pentru a răspunde la cererile lansate de clienți, solicită un cupon, gratuit. Detalii pe <a href="/cupoane/solicitari" class="alert-link">solicitare cupon</a>.
          </div>
    </div>
</div>
@endif

<br>
<div class="row my-5">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <iframe width="100%" height="400" src="https://www.youtube.com/embed/6FUT9IIFwy8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <h2>Recomandări utilizare și completare profil</h2>
        <p>Proiect în versiune BETA. Utilizare gratuită pe toată perioada versiunii BETA.</p>
        <ul style="list-style-type: disc;">
            <li style="font-size: 16px; padding: 5px;">Completează profilul public. Clienții vor analiza informațiile prezente în profil pentru a lua o decizie.</li>
            <li style="font-size: 16px; padding: 5px;">Adaugă zonele și categoriile de lucru în profilul public.</li>
            <li style="font-size: 16px; padding: 5px;">Adaugă proiecte executate pentru ca clienții sa vadă lucrările trecute.</li>
            <li style="font-size: 16px; padding: 5px;">Adaugă o fotografie de profil.</li>
            <li style="font-size: 16px; padding: 5px;">Deblochează cererile clienților. Solicită un cupon dacă creditul tău este insuficient. Cuponul este gratuit.</li>
            <li style="font-size: 16px; padding: 5px;">Îți vom oferi cupoane de fiecare dată când rămăi fără credit.</li>
            <li style="font-size: 16px; padding: 5px;">Lasă-ne o recenzie (vezi mai jos) și vei primi cupon de 300 RON.</li>
        </ul>
    </div>
</div>
<br>

<!-- ROW-1 -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                
                <div class="col-lg-4 col-md-12 col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-body text-center statistics-info">
                            <div class="counter-icon bg-success mb-0 box-success-shadow">
                                <i class="fe fe-dollar-sign text-white"></i>
                            </div>
                            <h6 class="mt-4 mb-1">Credit</h6>
                            <h2 class="mb-2  number-font">{{ $credit }} RON</h2>
                            <p class="text-muted">Creditul disponibil pentru deblocarea cererilor.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-body text-center statistics-info">
                            <div class="counter-icon bg-info mb-0 box-info-shadow">
                                <i class="fe fe-minus-square text-white"></i>
                            </div>
                            <h6 class="mt-4 mb-1">Cheltuieli</h6>
                            <h2 class="mb-2  number-font">{{ $total_expenses }} RON</h2>
                            <p class="text-muted">Cheltuieli efectuate pe deblocări de cereri.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-body text-center statistics-info">
                            <div class="counter-icon bg-info mb-0 box-info-shadow">
                                <i class="fe fe-briefcase text-white"></i>
                            </div>
                            <h6 class="mt-4 mb-1">Cupoane primite</h6>
                            <h2 class="mb-2  number-font">{{ $total_coupons }} RON</h2>
                            <p class="text-muted">Sumă totală cupoane primite.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-body text-center statistics-info">
                            <div class="counter-icon bg-success mb-0 box-success-shadow">
                                <i class="fe fe-unlock text-white"></i>
                            </div>
                            <h6 class="mt-4 mb-1">Cereri deblocate</h6>
                            <h2 class="mb-2  number-font">{{ $demands_unlocked }}</h2>
                            <p class="text-muted">Număr de cereri deblocate.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <company-review-component></company-review-component>
        </div>
       
    </div>
    <!-- ROW-1 END -->

    <div class="row">
        <div class="col-lg-12">
            <list-announcements-component></list-announcements-component>
        </div>
    </div>


    <!-- ROW-5 -->
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Ultimele cereri lansate</h3>
                </div>
                <div class="card-body">
                    <div class="grid-margin">
                        <div class="">
                            <div class="table-responsive">
                                <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                    <thead class="">
                                        <tr>
                                            <th>Identificator</th>
                                            <th>Locație</th>
                                            <th>Categorii</th>
                                            <th>Dată lansare</th>
                                            <th>Acțiuni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($demands && $demands->count() > 0)
                                        @foreach($demands as $demand)
                                        <tr>
                                            <td class="text-sm font-weight-600">{{ $demand->uuid }}</td>
                                            <td>{{ $demand->city }}</td>
                                            <td>
                                                <div class="tags">
                                                @if($demand->categories && $demand->categories->count() > 0)
                                                    @foreach($demand->categories as $category)
                                                        <span class="tag tag-blue">{{ $category->name }}</span>
                                                    @endforeach
                                                @endif
                                                </div>
                                            </td>
                                            <td>{{ formatCarbonDate($demand->created_at) }}</td>
                                            <td><a href="/cereri/pro/detalii/{{$demand->uuid}}" class="btn btn-sm btn-info">Vezi detalii</a></td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5"><p class="text-center">Nu există nicio cerere disponibilă.</p></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
{{-- <script src="{{ URL::asset('assets/js/index1.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script> --}}

<script>
    // Echo.private('tickets-channel')
    //     .listen('.ticket-event', (e) => {
    //         console.log(e);
    //     });
</script>

@endsection


			
	
	

		