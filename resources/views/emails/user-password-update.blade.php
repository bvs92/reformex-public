<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parola modificata</title>
</head>
<body>
    <h1 class="text-center">Parola a fost modificata</h1>
<h2>Salutare, {{ $user->getTheName() }}</h2>
<h3>Parola asociata adresei de email: {{ $user->email }} a fost modificata.</h3>

<h3>Informatiile de autentificare:</h3>
<ul>
    <li>E-mail: {{ $user->email }}</li>
    <li>Parola: {{ $temporary_password }}</li>
</ul>

<p>Va rugam sa modificati parola dupa autentificare.</p>
</body>
</html>