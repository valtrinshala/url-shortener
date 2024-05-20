<?php

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('hash is generated and stored correctly', function () {
    $redirect = Redirect::factory()->create(['url' => 'https://example.com']);

    expect($redirect->hash)->toBeString()->and(strlen($redirect->hash))->toBe(6);

    $this->assertDatabaseHas('redirects', ['hash' => $redirect->hash]);
});
