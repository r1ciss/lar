<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$defaultFruits = ['banana', 'mango', 'apple'];

$normalizeFruits = function (?array $fruits) use ($defaultFruits): array {
    $fruits ??= $defaultFruits;

    return array_map(function (string|array $fruit): array {
        if (is_string($fruit)) {
            return ['id' => (string) Str::uuid(), 'name' => $fruit];
        }

        $fruit['id'] ??= (string) Str::uuid();

        return $fruit;
    }, $fruits);
};

Route::get('/', function (Request $request) use ($normalizeFruits) {
    $entries = $request->session()->get('feedback_entries', []);

    $entries = array_map(function (array $entry): array {
        $entry['id'] ??= (string) Str::uuid();

        return $entry;
    }, $entries);

    $request->session()->put('feedback_entries', $entries);

    $fruits = $normalizeFruits($request->session()->get('fruits'));
    $request->session()->put('fruits', $fruits);

    return view('welcome', [
        'entries' => $entries,
        'fruits' => $fruits,
    ]);
})->name('home');

Route::post('/feedback', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'feedback' => ['required', 'string', 'max:2000'],
    ]);

    $entries = $request->session()->get('feedback_entries', []);
    array_unshift($entries, [
        'id' => (string) Str::uuid(),
        ...$validated,
    ]);
    $request->session()->put('feedback_entries', array_slice($entries, 0, 20));

    return redirect()
        ->route('home')
        ->with('status', 'Paldies! Jūsu atsauksme ir pievienota.');
})->name('feedback.store');

Route::get('/feedback/{id}/delete', function (Request $request, string $id) {
    $entries = $request->session()->get('feedback_entries', []);
    $entry = collect($entries)->firstWhere('id', $id);

    abort_if($entry === null, 404);

    return view('feedback-confirm', [
        'entry' => $entry,
    ]);
})->name('feedback.confirm');

Route::delete('/feedback/{id}', function (Request $request, string $id) {
    $entries = $request->session()->get('feedback_entries', []);

    $entries = array_values(array_filter(
        $entries,
        fn (array $entry): bool => ($entry['id'] ?? null) !== $id,
    ));

    $request->session()->put('feedback_entries', $entries);

    return redirect()
        ->route('home')
        ->with('status', 'Atsauksme ir izdzēsta.');
})->name('feedback.destroy');

Route::post('/fruits', function (Request $request) use ($normalizeFruits) {
    $validated = $request->validate([
        'fruit' => ['required', 'string', 'max:255'],
    ]);

    $fruits = $normalizeFruits($request->session()->get('fruits'));
    array_unshift($fruits, [
        'id' => (string) Str::uuid(),
        'name' => $validated['fruit'],
    ]);
    $request->session()->put('fruits', array_slice($fruits, 0, 20));

    return redirect()
        ->route('home')
        ->with('status', 'Auglis ir pievienots.');
})->name('fruits.store');

Route::delete('/fruits/{id}', function (Request $request, string $id) use ($normalizeFruits) {
    $fruits = $normalizeFruits($request->session()->get('fruits'));

    $fruits = array_values(array_filter(
        $fruits,
        fn (array $fruit): bool => $fruit['id'] !== $id,
    ));

    $request->session()->put('fruits', $fruits);

    return redirect()
        ->route('home')
        ->with('status', 'Auglis ir izdzēsts.');
})->name('fruits.destroy');

Route::get('/form-recieve', function (Request $request) {
    return view('form-receive', [
        'name' => $request->string('name'),
        'feedback' => $request->string('feedback'),
    ]);
})->name('form.receive');
