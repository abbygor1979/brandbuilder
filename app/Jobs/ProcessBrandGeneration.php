<?php

namespace App\Jobs;

use App\Models\Brand;
use App\Services\BrandNameService;
use App\Services\DomainCheckService;
use App\Services\LandingPageService;
use App\Services\LogoGenerationService;
use App\Services\VideoGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBrandGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $brand;
    protected $input;

    public function __construct(Brand $brand, array $input)
    {
        $this->brand = $brand;
        $this->input = $input;
        $this->queue = 'brand-generation';
    }

    public function handle(
        BrandNameService $brandNameService,
        LogoGenerationService $logoGenerationService,
        LandingPageService $landingPageService,
        VideoGenerationService $videoGenerationService,
        DomainCheckService $domainCheckService
    ) {
        $names = $brandNameService->generate($this->input);
        $this->brand->update([
            'name' => $names[0] ?? $this->brand->name,
            'slogan' => $this->input['slogan'] ?? 'Your Brand, Your Future',
            'motto' => $this->input['motto'] ?? 'Empowering Brands',
        ]);

        $logoGenerationService->generate($this->brand, $this->input);
        $landingPageService->generate($this->brand, $this->input);
        $domainCheckService->check($this->brand, $this->input);

        if (auth()->check() && auth()->user()->subscription->plan_id === 'premium') {
            $videoGenerationService->generate($this->brand, $this->input);
        }
    }
}