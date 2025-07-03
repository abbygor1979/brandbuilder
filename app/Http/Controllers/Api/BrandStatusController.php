<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function status(Request $request, Brand $brand)
    {
        $this->authorize('view', $brand);

        $status = 'pending';
        if ($brand->name && $brand->logos()->exists() && $brand->landingPages()->exists()) {
            $status = 'completed';
        } elseif ($brand->created_at->diffInMinutes(now()) > 30) {
            $status = 'failed';
        }

        return response()->json([
            'status' => $status,
            'progress' => $status === 'completed' ? 100 : ($status === 'failed' ? 0 : 50),
        ]);
    }
}