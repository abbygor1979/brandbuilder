<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Video;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class VideoGenerationService
{
    public function generate(Brand $brand, array $input): ?Video
    {
        if (auth()->user()->subscription->plan_id !== 'premium') {
            return null;
        }

        $text = $input['text'] ?? "Introducing {$brand->name}!";
        $voice = $input['voice'] ?? 'neutral';
        $aiDescription = $input['ai_description'] ?? "A dynamic 9:16 social media video for {$brand->name}";
        $duration = $input['duration'] ?? 30;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.pika.api_key'),
        ])->post('https://api.pika.ai/v1/videos', [
            'prompt' => $aiDescription,
            'text' => $text,
            'voice' => $voice,
            'duration' => $duration,
        ]);

        $videoUrl = $response->json()['data']['url'] ?? null;
        if ($videoUrl) {
            $path = "videos/{$brand->id}/video.mp4";
            $videoContent = Http::get($videoUrl)->body();
            Storage::disk(env('FILESYSTEM_DISK', 'local'))->put($path, $videoContent);

            return Video::create([
                'brand_id' => $brand->id,
                'path' => $path,
                'text' => $text,
                'voice' => $voice,
                'ai_description' => $aiDescription,
                'meta_data' => ['format' => 'MP4', 'duration' => $duration],
            ]);
        }

        return null;
    }
}