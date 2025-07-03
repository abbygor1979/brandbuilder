<?php

namespace App\Services;

use App\Models\LandingPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DeployService
{
    public function deploy(LandingPage $landingPage, string $service): array
    {
        $zipPath = $landingPage->zip_path;
        if (!Storage::disk(env('FILESYSTEM_DISK', 'local'))->exists($zipPath)) {
            return ['status' => 'failed', 'message' => 'ZIP file not found'];
        }

        $fileSize = Storage::disk(env('FILESYSTEM_DISK', 'local'))->size($zipPath);
        if ($fileSize > 100 * 1024 * 1024) {
            return ['status' => 'failed', 'message' => 'File size exceeds limit'];
        }

        $apiKey = config("services.{$service}.api_key");
        $endpoint = $service === 'netlify' 
            ? 'https://api.netlify.com/api/v1/sites'
            : 'https://api.render.com/v1/services';

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
        ])->attach('file', Storage::disk(env('FILESYSTEM_DISK', 'local'))->get($zipPath), 'archive.zip')
          ->post($endpoint, [
              'name' => "brandbuilder-{$landingPage->brand_id}-" . time(),
              'type' => 'static_site',
          ]);

        if ($response->successful()) {
            $url = $response->json()['url'] ?? "https://{$service}.com/brandbuilder-{$landingPage->id}";
            $landingPage->update([
                'deploy_status' => $service,
                'deploy_url' => $url,
            ]);
            return ['status' => 'success', 'url' => $url];
        }

        $landingPage->update(['deploy_status' => 'failed']);
        return ['status' => 'failed', 'message' => $response->json()['error'] ?? 'Deployment failed'];
    }
}