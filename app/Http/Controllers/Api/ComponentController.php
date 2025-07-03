<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('subscribed');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'landing_page_id' => 'required|exists:landing_pages,id',
            'order' => 'required|array',
            'order.*' => 'exists:components,id',
        ]);

        $landingPage = LandingPage::findOrFail($request->landing_page_id);
        $this->authorize('view', $landingPage->brand);

        foreach ($request->order as $index => $componentId) {
            Component::where('id', $componentId)
                ->where('landing_page_id', $landingPage->id)
                ->update(['order' => $index]);
        }

        return response()->json(['message' => 'Component order updated']);
    }

    public function update(Request $request)
    {
        $landingPageId = $request->input('landing_page_id');
        $landingPage = LandingPage::findOrFail($landingPageId);
        $this->authorize('view', $landingPage->brand);

        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'text_')) {
                $componentId = str_replace('text_', '', $key);
                Component::where('id', $componentId)
                    ->where('landing_page_id', $landingPageId)
                    ->update(['text' => $value]);
            }
            if (str_starts_with($key, 'media_') && $request->hasFile($key)) {
                $componentId = str_replace('media_', '', $key);
                $file = $request->file($key);
                if ($file->isValid() && in_array($file->extension(), ['png', 'jpg', 'jpeg', 'svg'])) {
                    $path = $file->store("components/{$landingPageId}", env('FILESYSTEM_DISK', 'local'));
                    Component::where('id', $componentId)
                        ->where('landing_page_id', $landingPageId)
                        ->update(['media_path' => $path]);
                }
            }
        }

        return response()->json(['message' => 'Components updated']);
    }
}