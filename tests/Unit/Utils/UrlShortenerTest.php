<?php

test('generateHash returns a 6 character string', function () {
    $url = 'https://example.com';
    $hash = substr(md5($url), 0, 6);

    expect($hash)->toBeString()
        ->and(strlen($hash))->toBe(6);
});
