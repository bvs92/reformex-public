@extends('volgh.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/tabs/tabs.css')}}" rel="stylesheet">
{{-- <link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet"> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">



<style>
    .cbp_tmtimeline>li .cbp_tmlabel h2 {font-weight:100!important;}

    .cbp_tmtime > span:nth-child(1) {
        font-size:12px!important;
        font-weight:100!important;
    }

    .cbp_tmtime > span:nth-child(2) {
        font-size:12px!important;
        font-weight:400!important;
    }


    .success-box {
    margin:50px 0;
    padding:10px 10px;
    border:1px solid #eee;
    background:#f9f9f9;
    }

    .success-box img {
    margin-right:10px;
    display:inline-block;
    vertical-align:top;
    }

    .success-box > div {
    vertical-align:top;
    display:inline-block;
    color:#888;
    }



    /* Rating Star Widgets Style */
    .rating-stars ul {
    list-style-type:none;
    padding:0;
    
    -moz-user-select:none;
    -webkit-user-select:none;
    }
    .rating-stars ul > li.star {
    display:inline-block;
    
    }

    /* Idle State of the stars */
    .rating-stars ul > li.star > i.fa {
    font-size:2.5em; /* Change the size of the stars */
    color:#ccc; /* Color on idle state */
    }

    /* Hover state of the stars */
    .rating-stars ul > li.star.hover > i.fa {
    color:#FFCC36;
    }

    /* Selected state of the stars */
    .rating-stars ul > li.star.selected > i.fa {
    color:#FF912C;
    }


    .img-thumbnail {
        height: 60px;
    }

</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Conversatie</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Acasa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Conversatie</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
            <!-- ROW-1 OPEN -->
            <div class="row">
                <!-- vue js component -->
                <!-- trimite in Component doar timeline. Preia Cererea din component -->
                <timeline-client-component 
                  :the_accessTokenMap="{{ json_encode(config('services.mapbox.api_key')) }}" 
                  :the_demand="{{ json_encode($demand) }}" 
                  :the_current_user="{{ json_encode(auth()->user()) }}" 
                  :incoming_timeline="{{ $timeline }}"
                >
                </timeline-client-component>
                <!-- end vue js component -->
            </div>
            <!-- ROW-1 CLOSED -->
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
</div>
@endsection
@section('js')

<script async src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
<script async src="{{ URL::asset('assets/plugins/accordion/accordion.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tabs/tab-content.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>

<script>

    $(document).ready(function(){
  
  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
    
    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    var msg = "";
    if (ratingValue > 1) {
        msg = "Multumim! Ne-ati oferit " + ratingValue + " stele.";
    }
    else {
        msg = "Facem tot posibilul sa ne imbunatatim serviciile. Ne pare rau, sincer, ca ne-ati oferit doar " + ratingValue + " stele.";
    }
    responseMessage(msg);
    // $rating_stars_value = $('#rating-stars-value');
    document.getElementById('rating-stars-value').setAttribute('value', ratingValue);
    // $rating_stars_value.val(ratingValue);
    
  });
  
  
});


function responseMessage(msg) {
  $('.success-box').fadeIn(200);  
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
}

</script>
@endsection