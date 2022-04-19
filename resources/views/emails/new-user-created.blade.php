<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Utilizator nou creat</title>
</head>
<body>
    <h1 class="text-center">Utilizator nou creat</h1>
<h2>Salutare, {{ $user->getTheName() }}</h2>
<h3>Un cont nou a fost creat pentru adresa de email: {{ $user->email }}.</h3>
<h3>Va rugam sa verificati adresa de email.</h3>
<h3>Informatiile de autentificare:</h3>
<ul>
    <li>E-mail: {{ $user->email }}</li>
    <li>Parola: {{ $temporary_password }}</li>
</ul>

<p>Parola este aleatorie. Va rugam sa modificati parola dupa prima autentificare.</p>
</body>
</html>