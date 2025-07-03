<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Brand - BrandBuilder</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h1>Create a New Brand <i class="bi bi-building"></i></h1>
        <form action="{{ auth()->check() ? route('brands.store') : route('brands.preview') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords</label>
                <input type="text" name="keywords" id="keywords" class="form-control" required data-bs-toggle="tooltip" title="Enter brand keywords">
            </div>
            <div class="mb-3">
                <label for="niches" class="form-label">Niches</label>
                <select name="niches[]" id="niches" class="selectpicker form-control" multiple data-bs-toggle="tooltip" title="Select niches">
                    @foreach ($niches as $niche)
                        <option value="{{ $niche->id }}">{{ $niche->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="name_description" class="form-label">Name Description</label>
                <textarea name="name_description" id="name_description" class="form-control" data-bs-toggle="tooltip" title="Describe the desired brand name"></textarea>
            </div>
            <div class="mb-3">
                <label for="language" class="form-label">Language</label>
                <select name="language" id="language" class="form-select">
                    <option value="en">English</option>
                    <option value="ru">Russian</option>
                    <option value="es">Spanish</option>
                    <option value="multi">Multilingual</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="name_type" class="form-label">Name Type</label>
                <select name="name_type" id="name_type" class="form-select">
                    <option value="neologism">Neologism</option>
                    <option value="compound">Compound</option>
                    <option value="prefix_suffix">Prefix/Suffix</option>
                    <option value="short_catchy">Short & Catchy</option>
                    <option value="long_descriptive">Long & Descriptive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="max_length" class="form-label">Max Length</label>
                <input type="number" name="max_length" id="max_length" class="form-control" min="5" max="20" value="15">
            </div>
            <div class="mb-3">
                <label for="domain_zones" class="form-label">Domain Zones</label>
                <select name="domain_zones[]" id="domain_zones" class="selectpicker form-control" multiple data-bs-toggle="tooltip" title="Select domain zones">
                    @foreach ($domainZones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->zone }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-rocket-takeoff"></i> {{ auth()->check() ? 'Create Brand' : 'Preview Names' }}</button>
        </form>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $('.selectpicker').selectpicker();
    </script>
</body>
</html>