<?php

namespace App\Services\URL;

use App\Models\MalwareDomain;
use Illuminate\Support\Facades\Http;

class CheckURLSafetyService
{
    public function isSecure(string $url): bool
    {
        if (MalwareDomain::query()->where('url', $url)->exists()) {
            return false;
        }

        $key = config('services.google.safe_browsing_api_key');

        $matches = Http::post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$key}", [
            'client' => $this->client(),
            'threatInfo' => $this->threatInfo($url)
        ])
            ->throw()
            ->json('matches');

        $isMalware = filled($matches);

        if ($isMalware) {
            MalwareDomain::query()->create(['url' => $url]);
        }

        return !$isMalware;
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
