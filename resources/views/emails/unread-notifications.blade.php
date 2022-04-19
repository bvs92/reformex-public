<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notificari necitite.</title>
</head>
<body>
    <h1 class="text-center">Notificari necitite</h1>
<h2>Salutare, {{ $user->getTheName() }}</h2>
<h3>Aveti <strong>{{ $notifications }}</strong> necitite.</h3>
</body>
</html>