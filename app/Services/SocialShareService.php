<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Http;

class SocialShareService
{
    public function share(Brand $brand, array $platforms): array
    {
        $results = [];
        $message = "Check out my new brand: {$brand->name}! {$brand->slogan}";

        foreach ($platforms as $platform) {
            $apiKey = config("services.{$platform}.api_key");
            $endpoint = config("services.{$platform}.share_endpoint");

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
            ])->post($endpoint, [
                'message' => $message,
                'link' => $brand->landingPages->first()->deploy_url ?? route('brands.show', $brand),
            ]);

            $results[$platform] = $response->successful() ? 'Shared' : 'Failed';
        }

        return $results;
    }
}