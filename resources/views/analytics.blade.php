<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics - BrandBuilder</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    @include('layouts.app')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1>Analytics <i class="bi bi-graph-up"></i></h1>
        <canvas id="brandAnalytics" width="400" height="200"></canvas>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const ctx = document.getElementById('brandAnalytics').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Brands', 'Logos', 'Landing Pages', 'Videos'],
                datasets: [{
                    label: 'Count',
                    data: [{{ auth()->user()->brands()->count() }}, {{ auth()->user()->brands()->withCount('logos')->get()->sum('logos_count') }}, {{ auth()->user()->brands()->withCount('landingPages')->get()->sum('landing_pages_count') }}, {{ auth()->user()->brands()->withCount('videos')->get()->sum('videos_count') }}],
                    backgroundColor: ['#007bff'],
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>