<?php

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    \DB::table('redirects')->insert([
        'url' => $url,
        'hash' => 'abc123',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

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
    $redirect = Redirect::create(['url' => 'https://example.com']);
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
    $url1 = 'https://example.com';
    $url2 = 'https://example2.com';

    \DB::table('redirects')->insert([
        'url' => $url1,
        'hash' => 'uniquehash',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    try {
        \DB::table('redirects')->insert([
            'url' => $url2,
            'hash' => 'uniquehash',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } catch (\Illuminate\Database\QueryException $exception) {
        $this->assertStringContainsString('UNIQUE constraint failed', $exception->getMessage());
        return;
    }

    $this->fail('Expected QueryException not thrown');
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
