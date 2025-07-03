<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $brand->name }} - BrandBuilder</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
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

        <h1>{{ $brand->name }} <i class="bi bi-eye"></i></h1>
        <p><strong>Slogan:</strong> {{ $brand->slogan }}</p>
        <p><strong>Motto:</strong> {{ $brand->motto }}</p>

        @if ($brand->status === 'processing')
            <div class="progress mb-3" id="generationProgress">
                <div class="progress-bar" role="progressbar" style="width: {{ $brand->progress }}%;" aria-valuenow="{{ $brand->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $brand->progress }}%</div>
            </div>
        @elseif ($brand->status === 'failed')
            <div class="alert alert-danger" role="alert">
                Brand generation failed. Please try again or contact support.
            </div>
        @endif

        <h2>Logos <i class="bi bi-image"></i></h2>
        @if ($brand->logos->isNotEmpty())
            <div id="logoCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($brand->logos as $index => $logo)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($logo->path) }}" class="d-block w-100" alt="{{ $brand->name }} Logo">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#logoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#logoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @else
            <p>No logos available.</p>
        @endif

        <h2>Landing Pages <i class="bi bi-globe"></i></h2>
        @if ($brand->landingPages->isNotEmpty())
            <div class="list-group mb-3" id="landingPages">
                @foreach ($brand->landingPages as $landingPage)
                    <div class="list-group-item" data-id="{{ $landingPage->id }}">
                        <h5>{{ $landingPage->title }}</h5>
                        <p>{{ $landingPage->description }}</p>
                        <a href="{{ route('brands.download', $landingPage) }}" class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Download</a>
                        @if ($landingPage->deployed_url)
                            <a href="{{ $landingPage->deployed_url }}" class="btn btn-success btn-sm" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View Deployed</a>
                        @else
                            <form action="{{ route('brands.deploy', $brand) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="landing_page_id" value="{{ $landingPage->id }}">
                                <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-cloud-upload"></i> Deploy</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p>No landing pages available.</p>
        @endif

        <h2>Videos <i class="bi bi-play-btn"></i></h2>
        @if ($brand->videos->isNotEmpty())
            <div id="videoCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($brand->videos as $index => $video)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <video class="d-block w-100" controls>
                                <source src="{{ Storage::url($video->path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @else
            <p>No videos available. Upgrade to a premium plan to generate videos.</p>
        @endif

        <h2>Domains <i class="bi bi-link"></i></h2>
        @if ($brand->domains->isNotEmpty())
            <ul class="list-group mb-3">
                @foreach ($brand->domains as $domain)
                    <li class="list-group-item">
                        {{ $domain->name }}
                        @if ($domain->available)
                            <a href="{{ config('services.affiliate.domain_provider') }}?domain={{ $domain->name }}" class="btn btn-primary btn-sm" target="_blank"><i class="bi bi-cart"></i> Buy</a>
                        @else
                            <span class="badge bg-danger">Taken</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p>No domains available.</p>
        @endif

        <h2>Share <i class="bi bi-share"></i></h2>
        <form action="{{ route('brands.share', $brand) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="platform" class="at">Share on</label>
                <select name="platform" id="platform" class="form-select">
                    <option value="telegram">Telegram</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="x">X</option>
                    <option value="facebook">Facebook</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-share"></i> Share</button>
        </form>

        <h2>AI Assistant <i class="bi bi-chat-dots"></i></h2>
        <div class="card mb-3">
            <div class="card-body">
                <form id="aiChatForm">
                    <div class="mb-3">
                        <label for="aiPrompt" class="form-label">Ask the AI Assistant</label>
                        <textarea id="aiPrompt" class="form-control" rows="4" placeholder="e.g., Suggest improvements for my landing page"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Send</button>
                </form>
                <div id="aiResponse" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize SortableJS for landing pages
            const landingPages = document.getElementById('landingPages');
            if (landingPages) {
                new Sortable(landingPages, {
                    animation: 150,
                    onEnd: function (evt) {
                        const ids = Array.from(evt.to.children).map(item => item.dataset.id);
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            return;
                        }
                        fetch('{{ route('components.order') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                ids: ids,
                                type: 'landing_page'
                            })
                        })
                        .then(response => response.json())
                        .then(data => console.log('Order updated:', data))
                        .catch(error => console.error('Error updating order:', error));
                    }
                });
            }

            // AI Chat
            const aiChatForm = document.getElementById('aiChatForm');
            if (aiChatForm) {
                aiChatForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const prompt = document.getElementById('aiPrompt').value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (!csrfToken) {
                        document.getElementById('aiResponse').innerHTML = '<div class="alert alert-danger">CSRF token not found</div>';
                        return;
                    }
                    if (!prompt.trim()) {
                        document.getElementById('aiResponse').innerHTML = '<div class="alert alert-warning">Please enter a prompt</div>';
                        return;
                    }
                    fetch('{{ route('landing-page.ai-chat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            prompt: prompt,
                            brand_id: @json($brand->id)
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('aiResponse').innerHTML = `<div class="alert alert-info">${data.response || 'No response received'}</div>`;
                    })
                    .catch(error => {
                        document.getElementById('aiResponse').innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                    });
                });
            }
        });
    </script>
</body>
</html>