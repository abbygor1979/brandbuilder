<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Domain;
use App\Models\DomainZone;
use Illuminate\Support\Facades\Http;

class DomainCheckService
{
    public function check(Brand $brand, array $input): array
    {
        $domainZones = $input['domain_zones'] ?? DomainZone::where('is_popular', true)->pluck('id')->toArray();
        $limit = auth()->check() ? (auth()->user()->subscription->plan_id === 'premium' ? 20 : 10) : 5;

        $domains = [];
        foreach ($domainZones as $zoneId) {
            $zone = DomainZone::findOrFail($zoneId)->zone;
            $domain = "{$brand->name}.{$zone}";

            $response = Http::get('https://api.domainsdb.info/v1/domains/search', [
                'domain' => $domain,
                'api_key' => config('services.domainsdb.api_key'),
            ]);

            $isAvailable = $response->json()['is_available'] ?? false;
            $affiliateLink = config('services.affiliate.domain_provider') . "?domain={$domain}";

            $domains[] = Domain::create([
                'brand_id' => $brand->id,
                'domain_zone_id' => $zoneId,
                'domain' => $domain,
                'is_available' => $isAvailable,
                'affiliate_link' => $affiliateLink,
            ]);
        }

        return array_slice($domains, 0, $limit);
    }
}