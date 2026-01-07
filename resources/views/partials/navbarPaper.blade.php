@php
    $profileId = $paper->lecturer->user->profileId;
@endphp

<div class="paper-context-header border-bottom bg-white pt-3 pb-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2 text-truncate">
                <i class="bi bi-journal-bookmark-fill text-muted fs-5"></i>

                <a href="/{{ $profileId }}/papers" class="paper-breadcrumb-link text-muted">
                    {{ $paper->lecturer->user->name }}
                </a>

                <span class="text-muted opacity-50">/</span>

                <a href="/{{ $profileId }}/paper/{{ $paper->paperId }}/overview"
                    class="paper-breadcrumb-link fw-bold text-dark">
                    {{ $paper->title }}
                </a>

                <span class="badge rounded-pill ms-2 {{ $paper->visibility === 'public' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }} border">
                    {{ ucfirst($paper->visibility) }}
                </span>

                @if($paper->openCollaboration)
                    <span class="badge rounded-pill ms-1 bg-primary bg-opacity-10 text-primary border border-primary-subtle"
                          title="This project is accepting new researchers">
                        <i class="bi bi-people-fill me-1"></i> Open Collab
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="paper-navbar-sticky border-bottom bg-white sticky-top">
    <div class="container">
        <div class="paper-nav-scroller">
            <nav class="nav paper-nav-underline">
                <a class="nav-link {{ request()->is('*/overview') ? 'active' : '' }}"
                    href="/{{ $profileId }}/paper/{{ $paper->paperId }}/overview">
                    <i class="bi bi-columns-gap me-2"></i>Overview
                </a>

                <a class="nav-link {{ request()->is('*/workspace*') ? 'active' : '' }}"
                    href="/{{ $profileId }}/paper/{{ $paper->paperId }}/workspace">
                    <i class="bi bi-book me-2"></i>Workspace
                </a>

                <a class="nav-link {{ request()->is('*/collaborations') ? 'active' : '' }}"
                    href="/{{ $profileId }}/paper/{{ $paper->paperId }}/collaborations">
                    <i class="bi bi-people me-2"></i>Collaborations

                    @if ($paper->joinRequests_count > 0 && Auth::id() == $paper->lecturer->user->id)
                        <span class="badge bg-danger rounded-pill ms-1"
                            style="font-size: 0.6rem;">{{ $paper->joinRequests_count }}</span>
                    @endif
                </a>

                @if (Auth::user()->isLecturer() && $paper->lecturer->id === Auth::user()->lecturer->id)
                    <a class="nav-link {{ request()->is('*/settings') ? 'active' : '' }}"
                        href="/{{ $profileId }}/paper/{{ $paper->paperId }}/settings">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                @endif
            </nav>
        </div>
    </div>
</div>
