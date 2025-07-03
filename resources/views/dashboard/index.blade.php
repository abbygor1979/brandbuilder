<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - BrandBuilder</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        <h1>Dashboard <i class="bi bi-speedometer2"></i></h1>
        <a href="{{ route('brands.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Create New Brand</a>
        <div class="row">
            @foreach ($brands as $brand)
                <div class="col-md-4 mb-3">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body">
                            <h5 class="card-title">{{ $brand->name }}</h5>
                            <p><strong>Slogan:</strong> {{ $brand->slogan }}</p>
                            <p><strong>Logos:</strong> {{ $brand->logos->count() }}</p>
                            <p><strong>Landing Pages:</strong> {{ $brand->landingPages->count() }}</p>
                            <p><strong>Videos:</strong> {{ $brand->videos->count() }}</p>
                            <a href="{{ route('brands.show', $brand) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>