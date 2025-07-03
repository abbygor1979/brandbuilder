<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Logo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LogoGenerationService
{
    public function generate(Brand $brand, array $input): array
    {
        $style = $input['style'] ?? 'modern';
        $font = $input['font'] ?? 'Roboto';
        $aiDescription = $input['ai_description'] ?? "A {$style} logo for {$brand->name}";
        $limit = auth()->check() ? (auth()->user()->subscription->plan_id === 'premium' ? 5 : 3) : 2;

        $logos = [];
        for ($i = 0; $i < $limit; $i++) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.dalle.api_key'),
            ])->post('https://api.openai.com/v1/images/generations', [
                'prompt' => $aiDescription,
                'n' => 1,
                'size' => '512x512',
            ]);

            $imageUrl = $response->json()['data'][0]['url'] ?? null;
            if ($imageUrl) {
                $path = "logos/{$brand->id}/logo_{$i}.png";
                $imageContent = Http::get($imageUrl)->body();
                Storage::disk(env('FILESYSTEM_DISK', 'local'))->put($path, $imageContent);

                $logos[] = Logo::create([
                    'brand_id' => $brand->id,
                    'path' => $path,
                    'style' => $style,
                    'font' => $font,
                    'ai_description' => $aiDescription,
                ]);
            }
        }

        return $logos;
    }
}