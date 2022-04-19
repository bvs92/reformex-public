@extends('volgh.layouts.master')

@section('head-scripts')

@endsection


@section('css')

@endsection

@section('title-page')
<title>Setări profil public</title>
@endsection


@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Informații profil public</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Acasa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Informații profil public</li>
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
                            <div class="card-title">Informații profil public</div>
                        </div>

                        {{-- @if($user->user_name_profile)
                        <h5 class="mt-4 px-4">Nume utilizator: reformex.ro/profil/<strong>{{ $user->user_name_profile->username }}</strong></h5>
                        @else
                        <h5 class="mt-4 px-4">Nume utilizator: reformex.ro/profil/<strong>{{ $user->username }}</strong></h5>
                        @endif --}}


                        {{-- <div class="card-body">
                            <cif-company-information :company_info="{{ json_encode($company) }}"></cif-company-information>
                        </div> --}}
                        <div class="card-body">
                            <div role="alert" class="alert alert-info"><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Aceste informații vor fi vizibile în profilul public. Asigură-te că <strong>toate informațiile</strong> sunt completate pentru că profilul tău va fi verificat de clienți atunci când le deblochezi cererile. 
                            </div>

                            <edit-profile-photo-component></edit-profile-photo-component>

                            {{-- <company-information-component :user_id="{{ json_encode(auth()->user()->id) }}"></company-information-component> --}}

                            <pro-username-component :user="{{ json_encode($user) }}" :user_name_profile="{{ json_encode($user->user_name_profile) }}"></pro-username-component>

                            <user-website-component></user-website-component>

                            <!-- start card -->
                            <div class="">
							    <edit-social-profiles-component></edit-social-profiles-component>
							</div><!-- end card Profil SOcial -->

                            <user-public-description-component></user-public-description-component>
                            


                            <div class="col-lg-12 mt-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Zonele de lucru</div>
                                        <div class="card-options">
                                            {{-- <a href="#" class="btn btn-primary btn-sm">Alege judetele de lucru</a> --}}
                                            <judete-component></judete-component>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <user-judete-component></user-judete-component>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Categorii de lucru</div>
                                        <div class="card-options">
                                            {{-- <a href="#" class="btn btn-primary btn-sm">Alege judetele de lucru</a> --}}
                                            <categories-component></categories-component>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <categories-company-component></categories-company-component>
                                    </div>
                                </div>
                                
                            </div>


                            <div class="col-lg-12 mt-6">
                                <company-profile-questions></company-profile-questions>
                            </div>

                            <div class="col-lg-12 mt-6">
                                <company-card-component :base_url="{{ json_encode(url('/')) }}"></company-card-component>
                            </div>

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

@endsection

@section('footer-scripts')


@endsection