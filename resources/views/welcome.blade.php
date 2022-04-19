@extends('volgh.layouts.master-normal')
@section('css')
<link href="{{ URL::asset('assets/plugins/single-page/css/main.css')}}" rel="stylesheet">
<style>
    ul.list-here {
        list-style-type: disc!important;
        margin-left: 10px!important;
    }

    .list-here li {
        font-size: 16px;
        padding-top: 6px;
    }

    .equal {
  min-height: 220px;
}

p.leading-normal {
    font-size:15px;
}

@media only screen and (max-width: 600px) {
    ul.navbar-nav {
        display: flex;
        flex-direction: column!important;
    }

    ul.navbar-nav > li{
        margin-left: 20px;
        margin-top: 10px;
        padding: 5px;
    }

}
</style>
@endsection

@section('title-page')
<title>Platforma REFORMEX - varianta Beta</title>
@endsection

@section('content')



<nav class="navbar navbar-expand-lg navbar-light" style="background-color: white;">
    <a class="navbar-brand" href="/" >
        <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" style="height: 3.2rem" class="header-brand-img desktop-logo" alt="reformex">
    </a><!-- LOGO -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/" style="font-size: 14px;"> Pagina de start</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="https://www.reformex.ro" style="font-size: 14px;"> Site  principal</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/lansare/cerere" style="font-size: 14px;"> <i class="fa fa-rocket mr-2"></i>Lansează o cerere</a>
          </li>
      </ul>

      <span class="navbar-text">
        @if (Route::has('login'))
                @auth
                <a class="nav-link" style="font-size: 14px;" href="{{ url('/start') }}">
                    <i class="fa fa-laptop mr-2"></i> Intră în platformă
                </a>
                @else
                <a class="nav-link" style="font-size: 14px;" href="{{ route('login') }}">
                    <i class="fe fe-user mr-2"></i> Autentificare
                </a>
                @endauth
            @endif
      </span>
    </div>
  </nav>



  <div class="row my-8">
    <div class="col-lg-12 d-flex justify-content-center">
        <h3 style="text-align: center; font-weight: bold;padding: 10px;line-height: 3rem;">Platformă dedicată firmelor și profesioniștilor din domeniul construcțiilor.</h3>
    </div>
    {{-- <div class="col-lg-12 d-flex justify-content-center">
        <a href="/lansare/cerere" class="btn btn-info"><i class="fa fa-rocket mr-2"></i> Lansează o cerere în platformă.</a>
    </div> --}}
</div>

<!-- ROW-1 OPEN -->
<div class="row mt-8">
    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3 ">
        <div class="card service equal">
            <div class="card-body">
                <div class="item-box text-center">
                    <div class=" text-center  mb-4 text-primary"><i class="fa fa-search"></i></div>
                    <div class="item-box-wrap">
                        <h5 class="mb-2">Caută proiecte</h5>
                        <p class="text-muted mb-0">Sute de proiecte care așteaptă să fie deblocate. Un mijloc simplu și rapid de conectare cu clienții. Introdu locația sau domeniul de lucru și descoperă proiectele.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
        <div class="card service equal">
            <div class="card-body">
                <div class="item-box text-center">
                    <div class=" text-center text-danger-gradient mb-4"><i class="fa fa-unlock-alt"></i></div>
                    <div class="item-box-wrap">
                        <h5 class="mb-2">Deblochează cereri</h5>
                        <p class="text-muted mb-0">Ai găsit deja mai multe proiecte în aria ta de lucru? Deblochează cererile pe rând și intră în contact cu clienții. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
        <div class="card service equal">
            <div class="card-body">
                <div class="item-box text-center">
                    <div class=" text-center text-success mb-4"><i class="fa fa-phone-square"></i></div>
                    <div class="item-box-wrap">
                        <h5 class="mb-2">Contactează clienți</h5>
                        <p class="text-muted mb-0">Odată deblocate cererile, un singur lucru rămâne de bifat: ofertarea clienților în funcție de caracteristicile proiectelor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
        <div class="card service equal">
            <div class="card-body">
                <div class="item-box text-center">
                    <div class="text-center text-warning-gradient mb-4"><i class="fa fa-check-square"></i></div>
                    <div class="item-box-wrap">
                        <h5 class="mb-2">Execută proiectul</h5>
                        <p class="text-muted mb-0">Oferta pe care ai transmis-o a fost acceptată? Felicitări! Nu îți rămâne decât să muți munca din online pe șantier și să te apuci de treabă. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-1 CLOSE -->


<div class="row my-8">
    <div class="col-lg-12">
        <h2 style="text-align: center; font-weight: bold;padding: 10px;line-height: 3rem;">Oferit spre utilizare <span style="color: #26A599;font-weight: bold;">gratuit</span> pentru o perioadă <span style="color: #141414;
            font-weight: bold;
            background: #ffc800;
            padding: 3px 13px;
            border-radius: 5px;">limitată de timp!</span></h2>
    </div>
</div>



<div class="row my-8" style="padding: 10px; border-radius: 5px;">
    <div class="col-lg-6 col-sm-12 mb-9 px-6">
        <h2 style="font-size: 1.4rem; font-weight: bold; color: rgb(61, 61, 61);">Ai deja un cont?</h2>
        <h2 style="font-weight: bold;">Accesează proiectele din platformă <span style="color: #26A599;font-weight: bold;">gratuit!<span></h2>
        <ul class="list-here">
            <li>Sute de cereri care așteaptă să fie deblocate</li>
            <li>Acces gratuit în platformă</li>
            <li>Modernizarea și simplificarea procesului de găsire al clienților</li>
        </ul>

        <a href="{{ route('login') }}" class="btn btn-info mt-4 text-center" style="">Autentificare aici</a>
    </div>

    <div class="col-lg-6 col-sm-12 mb-9 px-6">
        <h2 style="font-size: 1.4rem; font-weight: bold; color: rgb(61, 61, 61);">Nu ai încă un cont?</h2>
        <h2 style="font-weight: bold;">Înregistrează-te și folosește platforma <span style="color: #26A599;font-weight: bold;">gratuit!<span></h2>
        <ul class="list-here">
            <li>Găsești cereri local sau din toată România</li>
            <li>Vei face parte dintr-o comunitate de profesioniști</li>
            <li>Vei avea un profil personalizat cu informații despre firmă și serviciile pe care le oferi</li>
        </ul>

        <a href="https://www.reformex.ro/inscriere-profesionist/" class="btn btn-info mt-4 text-center" style="">Inregistrare aici</a>
    </div>

    {{-- <div class="col-lg-6 col-sm-12"> --}}


        {{-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formular de inscriere in platforma</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Denumire firma</label>
                    <input type="text" class="form-control" name="example-text-input" placeholder="Text..">
                </div>

                <div class="form-group">
                    <label class="form-label">CUI firma</label>
                    <input type="text" class="form-control" name="example-text-input" placeholder="Text..">
                </div>

                <div class="form-group">
                    <label class="form-label">Numar telefon</label>
                    <input type="text" class="form-control" name="example-text-input" placeholder="Text..">
                </div>

                <div class="form-group">
                    <label class="form-label">Adresa e-mail</label>
                    <input type="text" class="form-control" name="example-text-input" placeholder="Text..">
                </div>

                <div class="form-group">
                    <label class="form-label">Adresa firma</label>
                    <input type="text" class="form-control" name="example-text-input" placeholder="Text..">
                </div>

                <div class="form-group">
                    <label class="form-label">Categorii de lucru</label>
                    <select name="country" id="select-countries" class="form-control custom-select">
                        <option value="br" data-data="{&quot;image&quot;: &quot;https://laravel.spruko.com/volgh/ltr/assets/images/flags/br.svg&quot;}">Brazil</option>
                        <option value="cz" data-data="{&quot;image&quot;: &quot;https://laravel.spruko.com/volgh/ltr/assets/images/flags/cz.svg&quot;}">Czech Republic</option>
                        <option value="de" data-data="{&quot;image&quot;: &quot;https://laravel.spruko.com/volgh/ltr/assets/images/flags/de.svg&quot;}">Germany</option>
                        <option value="pl" data-data="{&quot;image&quot;: &quot;https://laravel.spruko.com/volgh/ltr/assets/images/flags/pl.svg&quot;}" selected="">Poland</option>
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-success">Vreau sa ma inregistrez</button>
                </div>

            </div>
        </div> --}}
    {{-- </div> --}}
</div><!-- end row 2 -->


<div class="row my-4 py-6 px-4">
    <div class="col-md-12 col-lg-6 pl-0">
        <div class="card">
            <div class="card-body about-con pabout">
                <h1 class="mb-4 font-weight-semibold">Despre proiect</h1>
                <h4 class="leading-normal">Aceasta este o versiune Beta.</h4>
                <p class="leading-normal">Dacă am zice că vrem să punem în legătură clienții cu profesioniștii din domeniul construcțiilor vi s-ar părea oare prea banal? Sperăm că nu și că chiar vă surâde ideea.</p> 
                <p class="leading-normal">În mod cert, principiul de bază al proiectului este cel de a intermedia cererea cu oferta. Cum? Prin intermediul prezentei platforme și a câtorva click-uri. Mutăm, astfel, telefoanele și recomandările din offline, căutările de recenzii pe rețelele de căutare și/sau socializare într-un proces simplu și modern în online – toate în același loc.</p>
                <p class="leading-normal">Începem cu varianta beta a proiectului, moment în care orice feedback despre funcționalitate și utilizare contează. V-am fi recunoscători dacă ne-ați împărtăși orice părere.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body about-con pabout">
                <h1 class="mb-4 font-weight-semibold">Cum funcționează?</h1>
                <h4 class="leading-normal">Găsești clienți oriunde în țară.</h4>
                <p class="leading-normal">Patru pași simpli și rapizi, am zice noi. Primul pas constă în crearea unui profil, fie acesta de utilizator sau firmă/liber profesionist.</p>
                <p class="leading-normal">Profilul este extrem de important, practic cartea de vizită în online, acordați atenție suficientă creării acestuia. Continuăm, apoi, cu pasul următor, în care, pe baza preferințelor de zonă și domeniului de lucru deja completate la pasul anterior, începeți să vedeți cererile din categoriile respective. Este un pas de selecție a proiectelor.</p> 
                <p class="leading-normal">Pasul trei este unul de ofertare, acesta fiind momentul în care trebuie să deblocați cererile care vă interesează și să începeți o comunicare cu clienții spre a îi oferta. Ce înseamnă o deblocare de cerere? Deblocarea datelor de contact. Neapăsând pe butonul de deblocare, datele de contact și comunicarea cu clienții nu va fi posibilă. Pasul final este unul decisiv, de obținere al proiectului. Stabiliți împreună cu clientul detaliile necesare execuției proiectului și bifați astfel un alt proiect săvârșit și client fericit.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-body about-con pabout">
                <h1 class="mb-4 font-weight-semibold">De ce să te înscrii?</h1>
                <h4 class="leading-normal">Niciun cost ascuns. E gratuit.</h4>
                <p class="leading-normal">Pentru că e o metodă simplă, gratuită și modernă de găsire a clienților? Să le luăm pe rând. Online-ul este un spațiu vast, unde este nevoie de multă muncă pentru a deveni vizibil. Ca în orice, de altfel. Noi te ajutăm la capitolul acesta, Reformex fiind accesat de +50.000 de utilizatori lunar, de moment.</p>
                <p class="leading-normal">Profilul pe care îl vei crea o să fie cartea ta de vizită, dar noi o să ne ocupăm de promovarea acesteia astfel încât clienții să intre permanent în platformă. Te ajutăm cu vizibilitatea în online și comunicarea cu clienți interesați în mod exclusiv de domeniul construcțiilor. Bifăm, așadar, unul dintre cele mai importante aspecte: accesul într-un spațiu online activ.</p> 
                <p class="leading-normal">Nu monetizăm în niciun fel serviciul de înscriere în platformă, totul fiind gratuit. De ce nu ai încerca? Este o oportunitate pe care merită să o încerci. Dacă îți place, rămâi cu noi, dacă nu, ne lași măcar un feedback, să știm ce trebuie să îmbunătățim. Plus că totul se întâmplă online? Te conectezi, verifici cererile, le deblochezi pe cele pe care le consideri potrivite ție, intri în contact cu clientul, vezi ce alte informații îți oferă, îi lași o ofertă, o acceptă și iei proiectul. Atât de simplu, câțiva pași pe care îi poți face de oriunde și oricând. </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body about-con pabout">
                <h1 class="mb-4 font-weight-semibold">Ce ne propunem?</h1>
                <h4 class="leading-normal">Îți aducem clienții la un click distanță.</h4>
                <p class="leading-normal">Reformex este doar un intermediar între client și profesionist, serviciul pe care îl oferă fiind doar cel de intermediere, care se încheie în momentul în care clientul acceptă oferta profesionistului. Orice urmează nu este în datoria noastră, ci a clientului, care trebuie să se asigure de ceea ce stabilește cu firma/profesionistul și a firmei/profesionistului care trebuie să dea dovadă de profesionalism și să execute lucrările în mod calitativ, respectând condițiile discutate în prealabil cu clientul atât din punct de vedere al plății, timpului de execuție, garanției și a altor detalii similare. </p>
            </div>
        </div>
    </div>
</div><!-- end row 2 -->

<div class="row my-8">
    <div class="col-lg-12">
        <h2 class="text-center" style="font-weight: bold;">Comunitatea noastră este formată doar din firme verificate.</h2>
        <h3 class="text-center" style="font-weight: bold; font-size: 16px; color: rgb(159, 159, 159);">Atenție! Aceasta este varianta BETA și de aceea este gratuită. Varianta oficială va fi lansată în 2022 și va cuprinde o formă de abonament sau credite.</h3>
    </div>
</div>


{{-- <div class="flex-center position-ref full-height">
  

    <div class="content">
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" class="header-brand-img light-logo1" alt="reformex" style="width: 200px;">
            </div>
        </div>

        <div class="links">
            <a href="{{ route('public.demands.register') }}">Lanseaza cerere</a>
            <a href="/contact">Contact</a>
        </div>
    </div>
</div> --}}


@endsection
@section('js')
@endsection