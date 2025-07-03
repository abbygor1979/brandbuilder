<?php

namespace App\Services;

use App\Models\SessionName;
use Illuminate\Support\Facades\Http;

class BrandNameService
{
    public function generate(array $input): array
    {
        $keywords = $input['keywords'] ?? '';
        $niches = $input['niches'] ?? [];
        $language = $input['language'] ?? 'en';
        $nameType = $input['name_type'] ?? 'short_catchy';
        $maxLength = $input['max_length'] ?? 15;
        $limit = auth()->check() ? (auth()->user()->subscription->plan_id === 'premium' ? 10 : 5) : 5;

        $usedNames = session()->has('session_id') 
            ? SessionName::where('session_id', session()->getId())->pluck('name')->toArray()
            : [];

        $prompt = "Generate {$limit} unique brand names for a company with keywords: {$keywords}, niches: " . implode(', ', $niches) . 
                 ", language: {$language}, type: {$nameType}, max length: {$maxLength} characters. Exclude: " . implode(', ', $usedNames);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
        ])->post('https://openrouter.ai/api/v1/generate', [
            'prompt' => $prompt,
            'max_tokens' => 100,
        ]);

        $names = $response->json()['choices'][0]['text'] ?? [];
        if (is_string($names)) {
            $names = array_filter(explode(',', $names), fn($name) => !in_array(trim($name), $usedNames));
        }

        if (session()->has('session_id')) {
            foreach ($names as $name) {
                SessionName::create([
                    'session_id' => session()->getId(),
                    'name' => trim($name),
                ]);
            }
        }

        return array_slice($names, 0, $limit);
    }
}