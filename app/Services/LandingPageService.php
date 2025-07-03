<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\LandingPage;
use App\Models\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LandingPageService
{
    public function generate(Brand $brand, array $input): ?LandingPage
    {
        $components = $input['components'] ?? ['hero', 'features', 'footer'];
        $aiDescription = $input['ai_description'] ?? "A landing page for {$brand->name}";
        $limit = auth()->check() ? (auth()->user()->subscription->plan_id === 'premium' ? 3 : 1) : 1;

        $landingPage = LandingPage::create([
            'brand_id' => $brand->id,
            'deploy_status' => 'draft',
        ]);

        foreach ($components as $index => $type) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
            ])->post('https://openrouter.ai/api/v1/generate', [
                'prompt' => "Generate HTML for a {$type} component: {$aiDescription}",
                'max_tokens' => 500,
            ]);

            $html = $response->json()['choices'][0]['text'] ?? '<div>Missing content</div>';

            Component::create([
                'landing_page_id' => $landingPage->id,
                'type' => $type,
                'html' => $html,
                'order' => $index,
            ]);
        }

        $zipPath = $this->createZip($landingPage);
        $landingPage->update(['zip_path' => $zipPath]);

        return $landingPage;
    }

    protected function createZip(LandingPage $landingPage): string
    {
        $zipPath = "landings/{$landingPage->id}/archive.zip";
        $zip = new ZipArchive;
        $fullPath = Storage::disk(env('FILESYSTEM_DISK', 'local'))->path($zipPath);

        if ($zip->open($fullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $html = '<!DOCTYPE html><html><head><title>' . $landingPage->brand->name . '</title></head><body>';
            $html .= $landingPage->components->pluck('html')->implode('');
            $html .= '</body></html>';

            $zip->addFromString('index.html', $html);
            $zip->close();
        }

        return $zipPath;
    }
}