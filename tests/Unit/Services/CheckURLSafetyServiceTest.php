<?php

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('isSecure returns true for safe URL', function () {
    Http::fake([
        'https://safebrowsing.googleapis.com/*' => Http::response(['matches' => []], 200),
    ]);

    $isSecure = (new \App\Services\URL\CheckURLSafetyService())->isSecure('https://example.com');

    expect($isSecure)->toBeTrue();
});

test('isSecure returns false for unsafe URL', function () {
    Http::fake([
        'https://safebrowsing.googleapis.com/*' => Http::response(['matches' => [['threatType' => 'MALWARE']]], 200),
    ]);

    $isSecure = (new \App\Services\URL\CheckURLSafetyService())->isSecure('http://unsafe-url.com');

    expect($isSecure)->toBeFalse();
});
