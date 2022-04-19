<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Open Sans', sans-serif
        }
        h2 {
            text-align: center;
        }

        h3 {
            text-align: center;
        }
        p {
            text-align: center;
        }

        .btn-action {
            font-size: 16px;
            text-align: center;
            display: block;
            width: fit-content;
            margin: 0 auto;
            font-size: 16px;
            background: rgb(25, 153, 233);
            color: white;
            width: fit-content;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: black;
        }

        .logo span {
            color: #1fc2b3;
        }
    </style>
</head>
<body>
 <h1 class="logo">REFORM<span>EX</span></h1>
 <hr>
 <br>
<h2>Salutare, {{ $user->getTheName() }}</h2>
<h3>Tocmai a fost lansată o nouă cerere ce corespunde categoriilor tale de lucru.</h3>
<p>Detalii cerere:</p>
<p>Locație: {{$demand->city}}</p>
<p>Subiect: {{$demand->subject}}</p>
@if($demand->categories)
    <p>Categorii: 
        @foreach($demand->categories as $category)
            {{ $category->name }} |
        @endforeach
    </p>
@endif
<a class="btn-action" href="{{ route('demands.show.pro', $demand->uuid)}}">Vezi detalii complete</a>

<br>
<br>
<br>
<br>
<p style="text-align: center;font-size: 12px;color: grey;">Acesta este un email automat. Nu răspunde.</p>
<p style="text-align: center;font-size: 12px;color: grey;">Poți dezactiva această notificare din contul tău. Accesează pagina cu Setări notificări.</p>
</body>
</html>