<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jūsu ievade</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Jūsu ievade</h1>

    <p><strong>Vārds:</strong> {{ $name }}</p>
    <p><strong>Atsauksme:</strong> {{ $feedback }}</p>

    <p><a href="{{ route('home') }}">Atgriezties</a></p>
</body>
</html>
