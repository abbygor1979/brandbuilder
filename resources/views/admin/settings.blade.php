<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings - BrandBuilder</title>
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
        <h1>Settings <i class="bi bi-gear"></i></h1>
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="openrouter_api_key" class="form-label">OpenRouter API Key</label>
                <input type="text" name="openrouter_api_key" id="openrouter_api_key" class="form-control" value="{{ $settings['openrouter_api_key'] ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="dalle_api_key" class="form-label">DALL-E API Key</label>
                <input type="text" name="dalle_api_key" id="dalle_api_key" class="form-control" value="{{ $settings['dalle_api_key'] ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="pika_api_key" class="form-label">Pika API Key</label>
                <input type="text" name="pika_api_key" id="pika_api_key" class="form-control" value="{{ $settings['pika_api_key'] ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="domainsdb_api_key" class="form-label">DomainsDB API Key</label>
                <input type="text" name="domainsdb_api_key" id="domainsdb_api_key" class="form-control" value="{{ $settings['domainsdb_api_key'] ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label for="affiliate_domain_provider" class="form-label">Affiliate Domain Provider</label>
                <input type="url" name="affiliate_domain_provider" id="affiliate_domain_provider" class="form-control" value="{{ $settings['affiliate_domain_provider'] ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Settings</button>
        </form>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>