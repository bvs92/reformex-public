@extends('volgh.layouts.master')
@section('css')
@endsection

@section('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2"></script>


@endsection

@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorii</li>
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
                            <h3 class="card-title ">Adauga o noua cerere</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <form id="register_demand">
                                        @csrf
                                        <div class="form-group">
                                            <label for="subject">Subiect cerere</label>
                                            <input type="text" class="form-control @error('subject') has-error @enderror" id="subject" placeholder="Subiect cerere" name="subject" value="{{ old('subject') }}" required minlength="2">
                                            <p class="small text-danger" id="subject-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="name">Nume</label>
                                            <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele dvs" name="name" value="{{ old('name') }}" required minlength="2">
                                            <p class="small text-danger" id="name-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" class="form-control @error('email') has-error @enderror" id="email" placeholder="Numar telefon" name="email" value="{{ old('email') }}" required>
                                            <p class="small text-danger" id="email-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="phone">Telefon</label>
                                            <input type="text" class="form-control @error('phone') has-error @enderror" id="phone" placeholder="Numar telefon" name="phone" value="{{ old('phone') }}" required minlength="10">
                                            <p class="small text-danger" id="phone-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="city">Oras</label>
                                            <input type="search" id="address-input" class="form-control @error('city') has-error @enderror" placeholder="Care este orasul proiectului?" name="city" value="{{ old('city') }}" required>
                                            <p class="small text-danger" id="city-error-form"></p>

                                            <input type="hidden" id="lat" name="lat" />
                                            <input type="hidden" id="lng" name="lng" />
                                        </div>
                                    
                                    
                                        <div class="form-group">
                                            <label for="categories">Categorii</label>
                                            @if($categories && $categories->count() > 0)
                                                <select name="categories[]" class="form-control @error('categories') has-error @enderror" id="categories" multiple required>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                    
                                            <p class="small text-danger" id="categories-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="message">Descrieti cat mai clar cererea</label>
                                            <textarea class="form-control 
                                            @error('message') has-error @enderror" 
                                            name="message" id="message" cols="30" rows="10" required minlength="3">{{ old('message') }}</textarea>
                                            <p class="small text-danger" id="message-error-form"></p>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                            <button type="submit" class="btn btn-success">Inregistreaza cerere</button>
                                            </div>
                                        </div>
                                    
                                    
                                    </form>
                                </div>


                                <div id="form-response" class="alert alert-dismissible fade hide" role="alert">
                                    <span class="message-response"></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
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
@endsection


@section('footer-scripts')
<script src="{{ asset('js/validation/jquery.validate.min.js') }}"></script>
<script>

    // De preluat mai multe informatii din algolia? Cod postal, lat, long? Tabel separat doar pentru aceste informatii?
    // var placesAutocomplete = places({
    //   appId: '{{ config('services.algolia.appId') }}',
    //   apiKey: '{{ config('services.algolia.apiKey') }}',
    //   container: document.querySelector('#address-input')
      
    // });

    const fixedOptions = {
        appId: '{{ config('services.algolia.appId') }}',
        apiKey: '{{ config('services.algolia.apiKey') }}',
        container: document.querySelector('#address-input')
    };

    const reconfigurableOptions = {
        language: 'ro', 
        countries: ['ro'], 
        type: 'city', 
        aroundLatLngViaIP: false ,
        getRankingInfo: true
    };
    const placesInstance = places(fixedOptions).configure(reconfigurableOptions);

    placesInstance.on('change', function(e){
        console.log(e.suggestion.hit._rankingInfo);
        console.log(e.suggestion);
        document.getElementById('lat').value = e.suggestion.latlng.lat;
        document.getElementById('lng').value = e.suggestion.latlng.lng;
        
    })



    // $form_register = document.getElementById('register_demand');

    $(document).ready(function(){

        $form_register_demand = $("#register_demand");
        $__form_response = $('#form-response');

        __name = document.getElementById('name');
        __subject = document.getElementById('subject');
        __phone = document.getElementById('phone');
        __city = document.getElementById('address-input');
        __lat = document.getElementById('lat');
        __lng = document.getElementById('lng');
        __email = document.getElementById('email');
        __message = document.getElementById('message');
        __categories = document.getElementById('categories');

        let __categoriesSelectedValues = Array.from(__categories.selectedOptions).map(option => option.value);

        var validator = $form_register_demand.validate();

        
            $form_register_demand.submit(function(event) {
            console.log( "Handler for .submit() called." );
            console.log($form_register_demand.valid());

            // Validate form
            // $(this).validate();


            event.preventDefault();

            if($form_register_demand.valid()){

                url = 'http://127.0.0.1:8000/api/api-demands/';
                data = {
                    'subject': __subject.value,
                    'name'  : __name.value,
                    'phone': __phone.value,
                    'email': __email.value,
                    'city': __city.value,
                    'lat': __lat.value,
                    'lng': __lng.value,
                    'message': __message.value,
                    'categories': __categoriesSelectedValues = Array.from(__categories.selectedOptions).map(option => option.value)
                };

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data
                }).done(function(response){
                    console.log(data);
                    console.log('okokoko');
                    console.log(response);
                    // console.log(__categories);

                    if(response['success']){
                        $__form_response.find('span.message-response').text(response['success']);
                        $__form_response.removeClass('hide').addClass('alert-success show');

                        // reset form
                        document.getElementById("register_demand").reset()
                    }

                }).fail(function(response){
                    // console.log(response.responseJSON.errors);

                    for (const [key, value] of Object.entries(response.responseJSON.errors)) {
                        console.log(`${key}: ${value}`);
                        $(`p#${key}-error-form`).text(value);
                    }

                    if(response['error']){
                        $__form_response.find('span.message-response').text(response['error']);
                        $__form_response.removeClass('hide').addClass('alert-danger show');
                    }
                });

            } // end valid

            

        });
       

    });

    
    
  </script>

{{-- <script src="{{ asset('js/validation/jquery.validate.min.js') }}"></script> --}}

{{-- <script>
    $("#register_demand").validate({
        submitHandler: function(form) {

            form.preventDefault();
            console.log('aici');

                url = 'http://127.0.0.1:8000/api/api-demands/';
                data = {
                    'subject': __subject.value,
                    'name'  : __name.value,
                    'phone': __phone.value,
                    'email': __email.value,
                    'city': __city.value,
                    'lat': __lat.value,
                    'lng': __lng.value,
                    'message': __message.value,
                    'categories': __categoriesSelectedValues = Array.from(__categories.selectedOptions).map(option => option.value)
                };

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data
                }).done(function(response){
                    console.log(data);
                    console.log('okokoko');
                    console.log(response);
                    // console.log(__categories);
                }).fail(function(response){
                    // console.log(response.responseJSON.errors);

                    for (const [key, value] of Object.entries(response.responseJSON.errors)) {
                        console.log(`${key}: ${value}`);
                    }
                });
          
        }
    });


</script> --}}

@endsection
			
	
	

		