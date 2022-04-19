

@if(!auth()->user()->isAdmin())


    @if(!auth()->user()->isPro())
    <div class="row">
        <div class="col-lg-12 mb-2">

            {{-- <activate-pro-account-component></activate-pro-account-component> --}}


            {{-- <div class="alert alert-info">
                <strong>Activare modul PRO</strong>
                <p>Activati modulul PRO pentru a debloca cereri.</p>
                <form action="{{ route('professionals.activate', auth()->user()->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm">Activeaza modul firma</button>
                </form>
            </div> --}}
        </div>
    </div><!-- end of is PRO -->
    @endif

    <div class="row">
        @if(auth()->user()->company)
            @if(!auth()->user()->company->isCompleted())
            <div class="col-lg-12 mb-2">
                <div class="alert alert-info">
                    <strong><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Completează informații companie</strong>
                    <hr class="message-inner-separator">
                    <p>Oups, informațiile despre companie sunt incomplete. Forma de organizare, denumire, număr de telefon, adresă, CUI, Numar înmatriculare sunt obligatorii. Verifică și completează. <a href="/profil/setari/companie" class="btn btn-info btn-sm">Completează informațiile</a></p>
                </div>
            </div>
            @endif
        @endif

        @if(auth()->user()->isPro())
            @if(!auth()->user()->public_profile || !auth()->user()->public_profile->isCompleted() || !auth()->user()->user_name_profile || !auth()->user()->user_name_profile->isCompleted() || !auth()->user()->judets || auth()->user()->judets->count() < 1 || !auth()->user()->professional->categories || auth()->user()->professional->categories->count() < 1)
                <div class="col-lg-12 mb-2">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h3 class="card-title"><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Informații profil incomplete! <a href="/profil/setari/profesionist" class="btn btn-info btn-sm text-white">Completează informațiile</a></h3>
                                <div class="card-options">
                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(!auth()->user()->public_profile || !auth()->user()->public_profile->isCompleted())
                                <p><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Completează site, descrierea companiei. </p>
                                @endif
                                @if(!auth()->user()->user_name_profile || !auth()->user()->user_name_profile->isCompleted())
                                <p><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Selectează un nume de utilizator pentru profilul public. </p>
                                @endif
                                @if(!auth()->user()->judets || auth()->user()->judets->count() < 1)
                                <p><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Adaugă zone de lucru în profil. </p>
                                @endif
                                @if(!auth()->user()->professional->categories || auth()->user()->professional->categories->count() < 1)
                                <p><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Adaugă categorii de lucru în profil. </p>
                                @endif
                            </div>
                        </div>

                    
                    </div>
                @endif
        @endif


        @if(auth()->user()->isPro())
            @if(auth()->user()->projects->count() < 2)
            <div class="col-lg-12 mb-2">
                <div class="alert alert-info">
                    <strong><i aria-hidden="true" class="fa fa-exclamation mr-2"></i> Adaugă proiecte executate</strong>
                    <hr class="message-inner-separator">
                    <p>Pentru un profil autentic și vizibilitate în rândul clienților, îți recomandăm să adaugi minim 5 proiecte executate. <a href="/proiecte-lucrari/personal" class="btn btn-info btn-sm">Adaugă proiecte</a></p>
                </div>
            </div>
            @endif
        @endif


        {{-- @if(auth()->user()->isPro())
            @if(!auth()->user()->professional->isProCompleted())
            <div class="col-lg-12 mb-2">
                <alert-pro-information></alert-pro-information>
            </div>
            @endif
        @endif --}}
    </div><!-- end of is PRO -->

@endif