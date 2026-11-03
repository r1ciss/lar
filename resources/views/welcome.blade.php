<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
     <form action="{{ route('feedback.store') }}" method="post" class="form-example">
        @csrf

        <div class="form-example">
            <label for="name">Enter your name: </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required />
        </div>
        <div>
            <label for="feedback">atsauksme: </label>
            <textarea name="feedback" id="feedback">{{ old('feedback') }}</textarea>
        </div>
        <button type="submit">Nosūtīt</button>
    </form>

    @if (count($entries) > 0)
        <ul>
            @foreach ($entries as $entry)
                <li>
                    <strong>{{ $entry['name'] }}</strong>: {{ $entry['feedback'] }}
                    <a href="{{ route('feedback.confirm', $entry['id']) }}">Dzēst</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Vēl nav nevienas atsauksmes.</p>
    @endif
</body>
</html>