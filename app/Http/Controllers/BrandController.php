<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandGenerationRequest;
use App\Jobs\ProcessBrandGeneration;
use App\Models\Brand;
use App\Services\BrandNameService;
use App\Services\DeployService;
use App\Services\DomainCheckService;
use App\Services\LandingPageService;
use App\Services\LogoGenerationService;
use App\Services\VideoGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    protected $brandNameService;
    protected $domainCheckService;
    protected $landingPageService;
    protected $logoGenerationService;
    protected $videoGenerationService;

    public function __construct(
        BrandNameService $brandNameService,
        DomainCheckService $domainCheckService,
        LandingPageService $landingPageService,
        LogoGenerationService $logoGenerationService,
        VideoGenerationService $videoGenerationService
    ) {
        $this->brandNameService = $brandNameService;
        $this->domainCheckService = $domainCheckService;
        $this->landingPageService = $landingPageService;
        $this->logoGenerationService = $logoGenerationService;
        $this->videoGenerationService = $videoGenerationService;
        $this->middleware('auth')->except(['index', 'preview']);
        $this->middleware('subscribed')->only(['analytics', 'dashboard']);
        $this->middleware('throttle:5,1440')->only('preview');
    }

    public function index()
    {
        return view('brands.create', [
            'niches' => \App\Models\Niche::all()->remember(60),
            'domainZones' => \App\Models\DomainZone::all()->remember(60),
        ]);
    }

    public function preview(BrandGenerationRequest $request)
    {
        $names = $this->brandNameService->generate($request->validated());
        return response()->json(['names' => $names]);
    }

    public function create()
    {
        return view('brands.create', [
            'niches' => \App\Models\Niche::all()->remember(60),
            'domainZones' => \App\Models\DomainZone::all()->remember(60),
        ]);
    }

    public function store(BrandGenerationRequest $request)
    {
        $brand = Brand::create([
            'user_id' => Auth::id(),
            'keywords' => $request->keywords,
            'name_description' => $request->name_description,
        ]);

        $brand->niches()->sync($request->niches);
        ProcessBrandGeneration::dispatch($brand, $request->validated());

        return redirect()->route('brands.show', $brand)->with('success', 'Brand creation started! Check back soon.');
    }

    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        $brand->load(['niches', 'logos', 'landingPages.components', 'videos', 'domains.domainZone']);
        return view('brands.show', compact('brand'));
    }

    public function download(\App\Models\LandingPage $landingPage)
    {
        $this->authorize('view', $landingPage->brand);
        return Storage::disk(env('FILESYSTEM_DISK', 'local'))->download($landingPage->zip_path);
    }

    public function analytics()
    {
        return view('analytics');
    }

    public function dashboard()
    {
        $brands = Auth::user()->brands()->with(['logos', 'landingPages', 'videos'])->get();
        return view('dashboard.index', compact('brands'));
    }

    public function deploy(Request $request, Brand $brand)
    {
        $this->authorize('view', $brand);
        $landingPage = $brand->landingPages()->firstOrFail();
        $service = $request->input('deploy_service');
        $result = app(DeployService::class)->deploy($landingPage, $service);

        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', 'Deployed to ' . $service . ': ' . $result['url']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}