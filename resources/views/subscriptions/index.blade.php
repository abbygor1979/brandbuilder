<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscriptions - BrandBuilder</title>
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
        <h1>Subscriptions <i class="bi bi-credit-card"></i></h1>
        <form action="{{ route('subscriptions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="plan_id" class="form-label">Select Plan</label>
                <select name="plan_id" id="plan_id" class="form-select">
                    <option value="basic">Basic Plan</option>
                    <option value="premium">Premium Plan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <input type="text" name="payment_method" id="payment_method" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Subscribe</button>
        </form>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>