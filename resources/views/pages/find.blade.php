@extends('layouts.app')

@section('title', 'Find Researchers')

@section('additionalCSS')
    <style>
        .search-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 60px 0;
            margin-bottom: 40px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .researcher-card {
            border: 1px solid #eee;
            border-radius: 12px;
            background: #fff;
            transition: all 0.2s ease-in-out;
            height: 100%;
        }
        
        .researcher-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: #8e2de2;
        }

        .card-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .field-tag {
            font-size: 0.75rem;
            padding: 4px 10px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 20px;
            color: #6c757d;
        }

        .btn-connect {
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    @auth
        @include('partials.navbarProfile')
    @endauth

    <div class="search-header text-center">
        <div class="container">
            <h1 class="fw-bold text-dark mb-3">Connect with Researchers</h1>
            <p class="text-muted mb-4 fs-5">Discover experts, collaborators, and mentors across Indonesia.</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="/find" method="GET">

                        @if(request('region')) <input type="hidden" name="region" value="{{ request('region') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control border-0" placeholder="Search by name..." value="{{ request('q') }}">
                            <button class="btn btn-primary px-5 fw-bold" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(request()->hasAny(['q', 'region', 'sort']))
                <div class="mt-3">
                    <a href="/find" class="text-decoration-none text-danger small fw-bold">
                        <i class="bi bi-x-circle"></i> Clear All Filters
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-dark mb-0">
                Found {{ $lecturers->total() }} Researchers
            </h5>
            
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i> {{ request('region') ? 'Region: Filtered' : 'All Regions' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="max-height: 300px; overflow-y: auto;">
                        <li>
                            <a class="dropdown-item {{ !request('region') ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['region' => null]) }}">
                               All Regions
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($provinces as $prov)
                            <li>
                                <a class="dropdown-item {{ request('region') == $prov->id ? 'active' : '' }}" 
                                   href="{{ request()->fullUrlWithQuery(['region' => $prov->id]) }}">
                                   {{ $prov->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-sort-down"></i> Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li>
                            <a class="dropdown-item {{ !request('sort') ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => null]) }}">
                               Relevance (Default)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('sort') == 'newest' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">
                               Newest Joined
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('sort') == 'name_asc' ? 'active' : '' }}" 
                               href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}">
                               Name (A-Z)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse ($lecturers as $lecturer)
                <div class="col">
                    <div class="researcher-card p-4 d-flex flex-column h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $lecturer->user->name }}&background=random&size=128" 
                                 class="card-avatar">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">
                                    <a href="/{{ $lecturer->user->profileId }}/overview" class="text-decoration-none text-dark">
                                        {{ $lecturer->user->name }}
                                    </a>
                                </h5>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">Lecturer</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted small mb-2">
                                <i class="bi bi-building me-2"></i>
                                <span class="text-truncate">
                                    {{ $lecturer->affiliation->university->user->name ?? 'Independent' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>{{ $lecturer->province->name ?? 'Indonesia' }}</span>
                            </div>
                        </div>
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-file-text me-1"></i> 
                                {{ $lecturer->papers->count() }} Papers
                            </small>
                            <a href="/{{ $lecturer->user->profileId }}/overview" class="btn btn-outline-primary btn-sm btn-connect px-3">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-search display-1 opacity-25"></i>
                        <h4 class="mt-3">No researchers found</h4>
                        <p>Try adjusting your search or filters.</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {{ $lecturers->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection