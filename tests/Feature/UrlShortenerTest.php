<?php

use App\Models\MalwareDomain;
use App\Models\Redirect;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('shortens a URL', function () {
    $response = $this->postJson('/api/v1/url/shorten', [
        'url' => 'https://example.com'
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'url',
        'short_url'
    ]);

    $this->assertDatabaseHas('redirects', [
        'url' => 'https://example.com'
    ]);
});

it('returns existing short URL for duplicate', function () {
    $url = 'https://example.com';

    Redirect::factory()
        ->state([
            'url' => $url,
            'hash' => 'abc123'
        ])
        ->create();

    $response = $this->postJson('/api/v1/url/shorten', [
        'url' => $url
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment(['short_url' => url('abc123')]);
});

it('does not shorten an unsafe URL', function () {
    $this->mock(\App\Services\URL\CheckURLSafetyService::class, function ($mock) {
        $mock->shouldReceive('isSecure')->andReturn(false);
    });

    $response = $this->postJson('/api/v1/url/shorten', [
        'url' => 'http://unsafe-url.com'
    ]);

    $response->assertStatus(400);
    $response->assertJsonFragment(['message' => 'The URL is not safe!']);
});

it('redirects to original URL', function () {
    $redirect = Redirect::factory()->customUrl('https://example.com')->create();
    $hash = $redirect->hash;

    $response = $this->get("/$hash");

    $response->assertStatus(302);
    $response->assertRedirect('https://example.com');
});

it('returns error if URL parameter is missing', function () {
    $response = $this->postJson('/api/v1/url/shorten', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['url']);
});

it('returns error for invalid URL format', function () {
    $response = $this->postJson('/api/v1/url/shorten', [
        'url' => 'invalid-url'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['url']);
});

it('ensures hash is unique', function () {
    $hash = '123456';

    Redirect::factory()->state([
        'url' => 'https://example.com',
        'hash' => $hash
    ])->create();

    $this->expectException(QueryException::class);

    Redirect::factory()->state([
        'url' => 'https://example2.com',
        'hash' => $hash,
    ])->create();
});

it('shortens URLs with different schemes', function () {
    $schemes = ['http', 'https', 'ftp'];

    foreach ($schemes as $scheme) {
        $url = "{$scheme}://example.com";

        $response = $this->postJson('/api/v1/url/shorten', [
            'url' => $url
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('redirects', [
            'url' => $url
        ]);
    }
});

it('isSecure function correctly identifies malicious URLs with query parameters', function () {
    $maliciousUrl = 'http://malware.testing.google.test/testing/malware/';
    $maliciousUrlWithQuery = 'http://malware.testing.google.test/testing/malware/?1';

    MalwareDomain::create(['url' => $maliciousUrl]);
    MalwareDomain::create(['url' => $maliciousUrlWithQuery]);

    $checkService = new \App\Services\URL\CheckURLSafetyService();


    $this->assertFalse($checkService->isSecure($maliciousUrl));
    $this->assertFalse($checkService->isSecure($maliciousUrlWithQuery));

    Http::fake([
        'https://safebrowsing.googleapis.com/*' => Http::response(['matches' => []], 200),
    ]);

    $this->assertTrue($checkService->isSecure('https://example.com'));
});
