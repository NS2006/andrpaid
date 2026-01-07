@extends('layouts.app')

@section('title', 'Collaboration - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper')

    <div class="container py-5">
        @if (!$isOwner && $myPendingInvite)
            <div class="card border-0 shadow-sm mb-5 overflow-hidden border-start border-4 border-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-envelope-paper-heart-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">You have been invited!</h5>
                                <p class="text-muted mb-0">
                                    <span class="fw-bold text-dark">{{ $myPendingInvite->fromLecturer->user->name }}</span>
                                    has invited you to join this paper as a
                                    <span class="badge bg-info text-dark bg-opacity-25 border border-info px-2">
                                        {{ $myPendingInvite->collaboration->role }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2 w-100 w-md-auto justify-content-end">
                            <form
                                action="/{{ $paper->lecturer->user->profileId }}/paper/{{ $paper->paperId }}/collaborations/accept-invitation"
                                method="POST">
                                @csrf
                                <input type="hidden" name="invitation_id" value="{{ $myPendingInvite->id }}">
                                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="bi bi-check-lg me-2"></i> Accept
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger fw-bold px-4 rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#rejectInviteModal">
                                Decline
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectInviteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Decline Invitation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted small">Are you sure you want to decline this invitation? You can leave a
                                message explaining why.</p>

                            <form
                                action="/{{ $paper->lecturer->user->profileId }}/paper/{{ $paper->paperId }}/collaborations/reject-invitation"
                                method="POST">
                                @csrf
                                <input type="hidden" name="invitation_id" value="{{ $myPendingInvite->id }}">

                                <div class="mb-3">
                                    <textarea name="message" class="form-control bg-light" rows="3" placeholder="Optional: Reason for declining..."></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-danger fw-bold">Decline Invitation</button>
                                    <button type="button" class="btn btn-light text-muted"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isOwner)
            <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            class="rounded-circle p-3 {{ $paper->openCollaboration ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                            <i class="bi {{ $paper->openCollaboration ? 'bi-unlock-fill' : 'bi-lock-fill' }} fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Collaboration is {{ $paper->openCollaboration ? 'Open' : 'Closed' }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{ $paper->openCollaboration
                                    ? 'Other researchers can request to join.'
                                    : 'Other researchers can not request to join.' }}
                            </p>
                        </div>
                    </div>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/toggle-collaboration"
                        method="POST">
                        @csrf
                        @if ($paper->openCollaboration)
                            <button class="btn btn-outline-danger fw-bold rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i> Close Collaboration
                            </button>
                        @else
                            <button class="btn btn-success fw-bold rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i> Open Collaboration
                            </button>
                        @endif
                    </form>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar {{ $paper->openCollaboration ? 'bg-success' : 'bg-secondary' }}"
                        style="width: 100%"></div>
                </div>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3" id="roles">
            <div>
                <h4 class="fw-bold text-dark mb-1">Project Roles & Members</h4>
                <p class="text-muted mb-0">Manage the structure of your research team.</p>
            </div>

            @if ($isOwner)
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                    data-bs-target="#addRoleModal">
                    <i class="bi bi-plus-lg me-2"></i> Add Role / Slot
                </button>
            @endif
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card collab-card h-100 rounded-4 border-primary border-opacity-25">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0 text-uppercase small" style="letter-spacing: 1px;">Lead
                                Researcher</h6>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="member-avatar flex-shrink-0" style="width: 50px; height: 50px;">
                                <img src="https://ui-avatars.com/api/?name={{ $paper->lecturer->user->name }}&background=0d6efd&color=fff"
                                    class="shadow-sm" alt="">
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $paper->lecturer->user->name }}</h6>
                                @if ($paper->lecturer->affiliation)
                                    <small class="text-muted text-truncate d-block" style="max-width: 150px;">
                                        {{ $paper->lecturer->affiliation->university->user->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        <p class="small text-muted mb-0">Owner of the research paper.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4 pt-0">
                        <a href="/{{ $paper->lecturer->user->profileId }}/overview"
                            class="btn btn-outline-light text-dark border-secondary border-opacity-25 btn-sm w-100 rounded-pill">View
                            Profile</a>
                    </div>
                </div>
            </div>

            @foreach ($slots as $slot)
                <div class="col-md-6 col-lg-4">
                    @if ($slot->lecturer)
                     
                        <div class="card collab-card h-100 rounded-4">
                            <div class="card-header bg-light border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">{{ $slot->role }}</h6>
                                    @if ($isOwner)
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li>
                                                    <button class="dropdown-item small edit-role-trigger"
                                                        data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                        data-id="{{ $slot->id }}" data-role="{{ $slot->role }}"
                                                        data-description="{{ $slot->description }}">
                                                        Edit Role
                                                    </button>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <button class="dropdown-item text-danger small remove-member-trigger"
                                                        type="button" data-bs-toggle="modal"
                                                        data-bs-target="#removeMemberModal" data-id="{{ $slot->id }}"
                                                        data-name="{{ $slot->lecturer->user->name }}">
                                                        Remove Member
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="member-avatar flex-shrink-0" style="width: 50px; height: 50px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $slot->lecturer->user->name }}&background=0d6efd&color=fff"
                                            class="shadow-sm" alt="">
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $slot->lecturer->user->name }}</h6>
                                        <small class="text-muted">{{ $slot->lecturer->affiliation }}</small>
                                    </div>
                                </div>
                                <p class="small text-muted mb-0">{{ $slot->description }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-4 pt-0">
                                <a href="/{{ $slot->lecturer->user->profileId }}/overview"
                                    class="btn btn-outline-light text-dark border-secondary border-opacity-25 btn-sm w-100 rounded-pill">View
                                    Profile</a>
                            </div>
                        </div>
                    @else
                      
                        <div class="card vacant-card h-100 rounded-4">
                            <div class="card-header bg-transparent border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-muted mb-0">{{ $slot->role }}</h6>
                                    @if ($isOwner)
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-role"
                                            method="POST">
                                            @csrf

                                            <input type="hidden" name="roleId" value="{{ $slot->id }}">

                                            <button class="btn btn-link text-danger p-0" title="Delete Slot"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                <div class="mb-3">
                                    <div class="bg-white border rounded-circle d-inline-flex align-items-center justify-content-center text-muted"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-plus fs-4"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Vacant Position</h6>
                                <p class="small text-muted mb-3">{{ $slot->description }}</p>

                                @if ($isOwner)
                                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold invite-trigger"
                                        data-bs-toggle="modal" data-bs-target="#inviteModal"
                                        data-slot-id="{{ $slot->id }}">
                                        Invite
                                    </button>
                                @else
                                    @if ($paper->openCollaboration)
                                        @php
                                        $isRequested = false;

                                        if (Auth::check() && Auth::user()->lecturer) {
                                            $isRequested = \App\Models\CollaborationRequest::where("collaboration_id", $slot->id)
                                                ->where("from_lecturer_id", Auth::user()->lecturer->id)
                                                ->exists();
                                        }
                                    @endphp

                                    @if ($isRequested)
                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold" disabled>
                                            <i class="bi bi-check-circle-fill me-1"></i> Requested
                                        </button>
                                    @else
                                        <button type="button"
                                            class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold join-request-trigger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#joinRequestModal"
                                            data-slot-id="{{ $slot->id }}"
                                            data-role-name="{{ $slot->role }}">
                                            Apply for Role
                                        </button>
                                    @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <span id="invitations"></span>
        @if ($isOwner && $invitations->count() != 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-send-fill text-secondary me-2"></i> Sent Invitations
                        <span class="badge bg-secondary rounded-pill ms-2">{{ count($invitations) }}</span>
                    </h5>

                    @php
                        $hasFinalized = $invitations->whereIn('status', ['accepted', 'rejected'])->isNotEmpty();
                    @endphp

                    @if ($hasFinalized)
                        <form
                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/clear-invitation-history"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none btn-sm fw-bold">
                                <i class="bi bi-trash3 me-1"></i> Clear History
                            </button>
                        </form>
                    @endif
                </div>

                <div
                    class="list-group shadow-sm rounded-3 overflow-hidden border-0 border-start border-4 border-secondary">
                    @foreach ($invitations as $inv)
                        <div class="list-group-item p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                <div class="d-flex align-items-start gap-3 w-100">
                                    <div class="member-avatar flex-shrink-0" style="width: 56px; height: 56px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $inv->toLecturer->user->name }}&background=random&color=fff"
                                            alt="Profile" class="rounded-circle border">
                                    </div>

                                    <div class="w-100">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="fw-bold mb-0 text-dark fs-5">{{ $inv->toLecturer->user->name }}
                                            </h6>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-3 text-muted small mb-2">
                                            @if ($inv->toLecturer->affiliation)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $inv->toLecturer->affiliation->university->user->name }}
                                                </div>
                                            @endif
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-briefcase me-1"></i>
                                                Invited as: <strong
                                                    class="text-dark ms-1">{{ $inv->collaboration->role }}</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $inv->updated_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        @if ($inv->status === 'rejected' && $inv->message)
                                            <div
                                                class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 p-3 mt-2">
                                                <div class="d-flex gap-2">
                                                    <i class="bi bi-info-circle-fill text-danger mt-1"></i>
                                                    <div>
                                                        <small class="fw-bold text-danger text-uppercase"
                                                            style="font-size: 0.7rem;">Reason for decline</small>
                                                        <p class="mb-0 text-dark small fst-italic">"{{ $inv->message }}"
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2 mt-2 mt-md-0">
                                    <div class="text-end">
                                        @if ($inv->status === 'pending')
                                            <span
                                                class="badge bg-warning text-dark bg-opacity-25 border border-warning rounded-pill px-3">
                                                <i class="bi bi-hourglass-split me-1"></i> Pending
                                            </span>
                                        @elseif($inv->status === 'accepted')
                                            <span
                                                class="badge bg-success text-success bg-opacity-10 border border-success rounded-pill px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i> Accepted
                                            </span>
                                        @elseif($inv->status === 'rejected')
                                            <span
                                                class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill px-3">
                                                <i class="bi bi-x-circle-fill me-1"></i> Declined
                                            </span>
                                        @endif
                                    </div>

                                    <form
                                        action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/cancel-invitation"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="invitation_id" value="{{ $inv->id }}">

                                        @if ($inv->status === 'pending')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm fw-bold px-3 rounded-pill"
                                                title="Cancel Invitation">
                                                <i class="bi bi-x-lg me-1"></i> Cancel
                                            </button>
                                        @else
                                            <button type="submit"
                                                class="btn btn-light text-muted btn-sm border fw-bold px-3 rounded-pill"
                                                title="Remove from list">
                                                <i class="bi bi-trash me-1"></i> Remove
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <div class="mt-3 d-block d-md-none">
                                @if ($inv->status === 'pending')
                                    <span
                                        class="badge bg-warning text-dark bg-opacity-25 border border-warning rounded-pill w-100 py-2">Pending</span>
                                @elseif($inv->status === 'rejected')
                                    <span
                                        class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill w-100 py-2">Declined</span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($isOwner && $requests->count() != 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-inbox-fill text-primary me-2"></i> All Requests
                        <span class="badge bg-primary rounded-pill ms-2">{{ count($requests) }}</span>
                    </h5>

                    @php
                        $hasFinalizedRequests = $requests->whereIn('status', ['accepted', 'rejected'])->isNotEmpty();
                    @endphp

                    @if ($hasFinalizedRequests)
                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/clear-request-history" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none btn-sm fw-bold">
                                <i class="bi bi-trash3 me-1"></i> Clear History
                            </button>
                        </form>
                    @endif
                </div>

                <div class="list-group shadow-sm rounded-3 overflow-hidden border-0 border-start border-4 border-primary">
                    @foreach ($requests as $req)
                        <div class="list-group-item p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                <div class="d-flex gap-3 w-100">
                                    <div class="member-avatar flex-shrink-0" style="width: 48px; height: 48px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $req->fromLecturer->user->name }}&background=random&color=fff"
                                            alt="Profile" class="rounded-3">
                                    </div>

                                    <div class="w-100">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $req->fromLecturer->user->name }}</h6>
                                            <span class="text-muted small">&bull; {{ $req->created_at->diffForHumans() }}</span>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            @if ($req->toLecturer->affiliation)
                                                <span class="small text-muted">
                                                    <i class="bi bi-building me-1"></i> {{ $req->toLecturer->affiliation->university->user->name }}
                                                </span>
                                            @endif

                                            <span class="badge bg-light text-dark border fw-normal small">
                                                Applying for: <strong>{{ $req->collaboration->role }}</strong>
                                            </span>
                                        </div>

                                        <div class="bg-light p-3 rounded-3 fst-italic small text-dark mb-2">
                                            "{{ $req->message }}"
                                        </div>

                                        @if ($req->status === 'rejected')
                                            <div class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 p-3 mt-2">
                                                <div class="d-flex gap-2">
                                                    <i class="bi bi-info-circle-fill text-danger mt-1"></i>
                                                    <div>
                                                        <small class="fw-bold text-danger text-uppercase" style="font-size: 0.7rem;">You declined this request</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2 mt-2 mt-md-0">
                                    @if ($req->status === 'accepted')
                                        <span class="badge bg-success text-success bg-opacity-10 border border-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> Accepted
                                        </span>
                                    @elseif ($req->status === 'rejected')
                                        <span class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i> Declined
                                        </span>
                                    @endif

                                    @if ($req->status === 'pending')
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/accept-request" method="POST" class="w-100">
                                            @csrf
                                            <input type="hidden" name="requestId" value="{{ $req->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 w-100 mb-2">Accept</button>
                                        </form>

                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm fw-bold px-3 w-100 reject-request-trigger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectRequestModal"
                                            data-request-id="{{ $req->id }}"
                                            data-applicant-name="{{ $req->toLecturer->user->name }}">
                                            Reject
                                        </button>
                                    @else
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-request" method="POST">
                                            @csrf
                                            <input type="hidden" name="requestId" value="{{ $req->id }}">
                                            <button type="submit" class="btn btn-light text-muted btn-sm border fw-bold px-3 rounded-pill" title="Remove from list">
                                                <i class="bi bi-trash me-1"></i> Remove
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="joinRequestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Apply for Position</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex gap-3 align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-briefcase-fill small"></i>
                        </div>
                        <div class="small text-muted">
                            You are applying for the role of <strong id="applyRoleName" class="text-dark"></strong>.
                            The lead researcher will review your application.
                        </div>
                    </div>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/apply-for-role"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="applySlotId">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea class="form-control bg-light" name="message" rows="4"
                                placeholder="Briefly explain your expertise and how you plan to contribute to this specific role..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2">
                                Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Role Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/edit-role"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="editSlotId">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role Title</label>
                            <input type="text" class="form-control" name="role" id="editRoleTitle" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" rows="3" name="description" id="editRoleDesc" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">Remove Researcher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-member"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="removeSlotId">

                        <div class="mb-4">
                            <p class="text-dark mb-1">
                                Are you sure you want to remove <strong id="removeMemberName"
                                    class="text-primary"></strong> from this project?
                            </p>
                            <p class="small text-muted mb-0">
                                This action will move them out of the team and make the slot vacant again.
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted"
                                style="letter-spacing: 0.5px;">
                                Reason for removal (Optional)
                            </label>
                            <textarea class="form-control bg-light border-0" name="reason" rows="3"
                                placeholder="e.g., Contribution phase completed, Changed research focus..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold py-2">
                                Confirm Removal
                            </button>
                            <button type="button" class="btn btn-light text-muted fw-bold py-2" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/create-new-role"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="role">Role Title</label>
                            <input type="text" class="form-control" placeholder="e.g. Data Analyst, Reviewer"
                                name="role" id="role" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="description">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Describe what this person will do..." name="description"
                                id="description" required></textarea>
                        </div>
                        <div class="alert alert-light border small text-muted">
                            <i class="bi bi-info-circle me-1"></i> This will create a vacant card on the board. You can
                            invite a researcher to this slot afterwards.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Create Slot</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inviteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Assign Researcher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="targetSlotId">

                    <p class="text-muted small mb-3">Search for a researcher to fill this role.</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="userSearchInput" class="form-control border-start-0 bg-light"
                            placeholder="Search by name or email..." autocomplete="off">
                    </div>

                    <div id="userSearchResults" class="list-group mb-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @push('scripts')
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        @endpush
    @endif

    <div class="modal fade" id="rejectRequestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">Decline Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/reject-request" method="POST">
                        @csrf
                        <input type="hidden" name="requestId" id="rejectRequestId">

                        <div class="mb-3">
                            <p class="text-dark mb-1">
                                You are declining the request from <strong id="rejectApplicantName" class="text-dark"></strong>.
                            </p>
                            <p class="small text-muted">
                                Please provide a reason. This will be visible to the applicant.
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Reason for rejection</label>
                            <textarea class="form-control bg-light"
                                    name="message"
                                    rows="3"
                                    placeholder="e.g. Expertise does not match..."
                                    required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold py-2">Confirm Rejection</button>
                            <button type="button" class="btn btn-light text-muted fw-bold py-2" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearchInput');
            const resultsContainer = document.getElementById('userSearchResults');
            const targetSlotIdInput = document.getElementById('targetSlotId');
            const paperId = "{{ $paper->paperId }}";
            const ownerId = "{{ $paper->lecturer->user->profileId }}";
            const currentUserId = {{ Auth::id() }};
            let timeout = null;

            const inviteModal = document.getElementById('inviteModal');
            inviteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const slotId = button.getAttribute('data-slot-id');
                targetSlotIdInput.value = slotId;

                searchInput.value = '';
                resultsContainer.innerHTML = '';
            });

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;

                resultsContainer.innerHTML = '';

                if (query.length < 2) return;

                timeout = setTimeout(() => {
                    fetchUsers(query);
                }, 300);
            });

            function fetchUsers(query) {
                fetch(`{{ route('api.users.search.lecturer') }}?q=${query}`)
                    .then(response => response.json())
                    .then(users => {
                        renderResults(users);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function renderResults(users) {
                const filteredUsers = users.filter(user => user.id != currentUserId);

                if (filteredUsers.length === 0) {
                    resultsContainer.innerHTML =
                        '<div class="text-muted small text-center p-3">No users found.</div>';
                    return;
                }

                const slotId = targetSlotIdInput.value;

                const html = filteredUsers.map(user => `
        <form action="/${ownerId}/paper/${paperId}/collaborations/invite" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="user_id" value="${user.id}">
            <input type="hidden" name="slot_id" value="${slotId}">

            <button type="submit" class="list-group-item list-group-item-action d-flex align-items-center gap-3 border-0 bg-light rounded-3 mb-2 w-100 text-start">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random&color=fff" class="rounded-circle" width="32">
                <div class="w-100">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 small fw-bold text-dark">${user.name}</h6>
                        <span class="badge bg-primary rounded-pill small">Invite</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">${user.email}</small>
                </div>
            </button>
        </form>
    `).join('');

                resultsContainer.innerHTML = html;
            }

            const removeMemberModal = document.getElementById('removeMemberModal');
            if (removeMemberModal) {
                removeMemberModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');

                    document.getElementById('removeSlotId').value = id;
                    document.getElementById('removeMemberName').textContent = name;
                });
            }

            const editRoleModal = document.getElementById('editRoleModal');
            if (editRoleModal) {
                editRoleModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const id = button.getAttribute('data-id');
                    const role = button.getAttribute('data-role');
                    const desc = button.getAttribute('data-description');

                    document.getElementById('editSlotId').value = id;
                    document.getElementById('editRoleTitle').value = role;
                    document.getElementById('editRoleDesc').value = desc;
                });
            }

            const joinRequestModal = document.getElementById('joinRequestModal');
            if (joinRequestModal) {
                joinRequestModal.addEventListener('show.bs.modal', function(event) {
                    // 1. Get the button that triggered the modal
                    const button = event.relatedTarget;

                    // 2. Extract data
                    const slotId = button.getAttribute('data-slot-id');
                    const roleName = button.getAttribute('data-role-name');

                    // 3. Update Modal Content
                    document.getElementById('applySlotId').value = slotId;
                    document.getElementById('applyRoleName').textContent = roleName;
                });
            }

            const rejectRequestModal = document.getElementById('rejectRequestModal');
            if (rejectRequestModal) {
                rejectRequestModal.addEventListener('show.bs.modal', function(event) {
                    // 1. Get the button that triggered the modal
                    const button = event.relatedTarget;

                    // 2. Extract info from data attributes
                    const requestId = button.getAttribute('data-request-id');
                    const applicantName = button.getAttribute('data-applicant-name');

                    // 3. Update the modal content
                    document.getElementById('rejectRequestId').value = requestId;
                    document.getElementById('rejectApplicantName').textContent = applicantName;
                });
            }
        });
    </script>
@endsection
