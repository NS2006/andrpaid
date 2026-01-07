@extends('layouts.app')

@section('title', 'Research Grants & Affiliations')

@section('content')
    @include('partials.navbarProfile')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Research Grants & Affiliations</h3>
                        <p class="text-muted">Apply for university affiliation to unlock research funding and resources.</p>
                    </div>
                    <a href="{{ route('dashboard', ['profileId' => $user->profileId]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>

                @if($currentAffiliation)
                    <div class="card border-0 shadow-sm rounded-3 mb-5">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Current Status</h5>
                            
                            <div class="d-flex align-items-center p-3 rounded-3 bg-light border">
                                <div class="me-3">
                                    @if($currentAffiliation->status === 'accepted')
                                        <i class="bi bi-check-circle-fill text-success display-5"></i>
                                    @elseif($currentAffiliation->status === 'pending')
                                        <i class="bi bi-hourglass-split text-warning display-5"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger display-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $currentAffiliation->university->user->name }}</h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $currentAffiliation->status === 'accepted' ? 'bg-success' : 'bg-warning text-dark' }} text-uppercase">
                                            {{ $currentAffiliation->status }}
                                        </span>
                                        <span class="text-muted small">Requested on {{ $currentAffiliation->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($currentAffiliation->status === 'accepted')
                                <div class="alert alert-success mt-3 mb-0">
                                    <i class="bi bi-info-circle me-2"></i> You have full access to this university's grant resources.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if(!$currentAffiliation || $currentAffiliation->status === 'rejected')
                    <h5 class="fw-bold mb-3">Available Institutions</h5>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach($universities as $uni)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://ui-avatars.com/api/?name={{ $uni->name }}&background=random" 
                                                 class="rounded-circle me-3" width="50" height="50">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $uni->name }}</h6>
                                                <small class="text-muted">{{ $uni->university->location ?? 'Indonesia' }}</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">
                                            Apply for affiliation to access research facilities, digital libraries, and funding opportunities provided by {{ $uni->name }}.
                                        </p>
                                        
                                        <form action="/{{ $user->profileId }}/grants/apply" method="POST">
                                            @csrf
                                            <input type="hidden" name="university_id" value="{{ $uni->university->id }}">
                                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold">
                                                Request Affiliation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($currentAffiliation->status === 'pending')
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-lock display-4 opacity-25"></i>
                        <p class="mt-3">You cannot apply to other universities while a request is pending.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection