<?php

namespace App\Services\URL;

use Illuminate\Support\Facades\Http;

class CheckURLSafetyService
{
    public function isSecure(string $url): bool
    {
        $key = config('services.google.safe_browsing_api_key');

        $matches = Http::post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$key}", [
            'client' => $this->client(),
            'threatInfo' => $this->threatInfo($url)
        ])
            ->throw()
            ->json('matches');

        return blank($matches);
    }

    protected function threatInfo(string $url): array
    {
        return [
            'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
            'platformTypes' => ['WINDOWS'],
            'threatEntryTypes' => ['URL'],
            'threatEntries' => [
                ['url' => $url]
            ]
        ];
    }

    protected function client(): array
    {
        return [
            'clientId' => 'vsh',
            'clientVersion' => '1.5.2'
        ];
    }
}
