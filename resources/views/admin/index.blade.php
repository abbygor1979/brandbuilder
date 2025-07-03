<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - BrandBuilder</title>
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
        <h1>Admin Panel <i class="bi bi-gear"></i></h1>
        <h2>Add Niche</h2>
        <form action="{{ route('admin.niches.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Niche Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i> Add Niche</button>
        </form>
        <h2>Add Domain Zone</h2>
        <form action="{{ route('admin.domain_zones.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="zone" class="form-label">Zone</label>
                <input type="text" name="zone" id="zone" class="form-control" required>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular">
                    <label class="form-check-label" for="is_popular">Popular</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i> Add Domain Zone</button>
        </form>
        <h2>Settings</h2>
        <a href="{{ route('admin.settings') }}" class="btn btn-primary"><i class="bi bi-gear"></i> Manage Settings</a>
        <h2>Users</h2>
        <a href="{{ route('admin.users') }}" class="btn btn-primary"><i class="bi bi-people"></i> Manage Users</a>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>