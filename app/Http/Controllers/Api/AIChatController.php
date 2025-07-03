<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\LandingPage;
use App\Services\LandingPageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('subscribed');
    }

    public function handle(Request $request)
    {
        $request->validate([
            'input' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $brand = Brand::findOrFail($request->brand_id);
        $this->authorize('view', $brand);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
        ])->post('https://openrouter.ai/api/v1/generate', [
            'prompt' => "Generate HTML for a landing page based on: {$request->input}",
            'max_tokens' => 1000,
        ]);

        $html = $response->json()['choices'][0]['text'] ?? '<div>Missing content</div>';

        $landingPage = app(LandingPageService::class)->generate($brand, [
            'components' => ['custom'],
            'ai_description' => $request->input,
        ]);

        $landingPage->components()->create([
            'type' => 'custom',
            'html' => $html,
            'order' => 0,
        ]);

        return response()->json(['response' => 'Landing page created']);
    }
}