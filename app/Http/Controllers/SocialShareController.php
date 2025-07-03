<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Services\SocialShareService;
use Illuminate\Http\Request;

class SocialShareController extends Controller
{
    protected $socialShareService;

    public function __construct(SocialShareService $socialShareService)
    {
        $this->socialShareService = $socialShareService;
        $this->middleware('auth');
    }

    public function share(Request $request, Brand $brand)
    {
        $platforms = $request->input('platforms', []);
        $results = $this->socialShareService->share($brand, $platforms);

        $success = array_filter($results, fn($status) => $status === 'Shared');
        if (!empty($success)) {
            return redirect()->back()->with('success', 'Brand shared on ' . implode(', ', array_keys($success)));
        }

        return redirect()->back()->with('error', 'Failed to share brand');
    }
}