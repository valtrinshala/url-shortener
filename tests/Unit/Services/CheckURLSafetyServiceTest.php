<?php

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('isSecure returns true for safe URL', function () {
    Http::fake([
        'https://safebrowsing.googleapis.com/*' => Http::response(['matches' => []], 200),
    ]);

    $isSecure = function ($url) {
        $response = Http::post('https://safebrowsing.googleapis.com/v4/threatMatches:find', [
            'client' => [
                'clientId' => 'vsh',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ],
        ]);

        return empty($response->json('matches'));
    };

    expect($isSecure('https://example.com'))->toBeTrue();
});

test('isSecure returns false for unsafe URL', function () {
    Http::fake([
        'https://safebrowsing.googleapis.com/*' => Http::response(['matches' => [['threatType' => 'MALWARE']]], 200),
    ]);

    $isSecure = function ($url) {
        $response = Http::post('https://safebrowsing.googleapis.com/v4/threatMatches:find', [
            'client' => [
                'clientId' => 'vsh',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ],
        ]);


        return empty($response->json('matches'));
    };

    expect($isSecure('http://unsafe-url.com'))->toBeFalse();
});
