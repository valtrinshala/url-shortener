<?php

namespace App\Http\Controllers\Api\V1\Url;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Url\ShortenRequest;
use App\Models\Redirect;
use App\Services\URL\CheckURLSafetyService;

class ShortenController extends Controller
{
    function __invoke(ShortenRequest $request, CheckURLSafetyService $service)
    {
        $url = $request->get('url');

        if(! $service->isSecure($url)){
            return response()->json(['message' => 'The URL is not safe!'], 400);
        }

        $redirect = Redirect::query()->firstOrCreate(['url' => $url]);

        return [
            'url' => $url,
            'short_url' => url($redirect->hash),
        ];
    }
}
