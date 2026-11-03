<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dzēst atsauksmi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Dzēst atsauksmi?</h1>

    <p><strong>{{ $entry['name'] }}</strong>: {{ $entry['feedback'] }}</p>

    <form action="{{ route('feedback.destroy', $entry['id']) }}" method="post">
        @csrf
        @method('DELETE')

        <button type="submit">Jā, dzēst</button>
        <a href="{{ route('home') }}">Atcelt</a>
    </form>
</body>
</html>
