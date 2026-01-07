<nav class="navbar navbar-expand-md navbar-dark modern-navbar sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard', ['profileId' => Auth::user()->profileId]) }}">
            <div class="brand-logo-container me-2">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="brand-logo">
            </div>
            <span class="brand-text">AndRPaid</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['profileId' => Auth::user()->profileId]) }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->is('find') ? 'active' : '' }}" href="/find">
                        <i class="bi bi-search me-1"></i> Find
                    </a>
                </li>

                {{-- @notadmin
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->is('messages') ? 'active' : '' }}" href="/messages">
                            <i class="bi bi-chat-dots-fill me-1"></i> Messages
                        </a>
                    </li>
                @endnotadmin --}}

                @admin
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->is('admin-panel*') ? 'active' : '' }}" href="/admin-panel">
                            <i class="bi bi-shield-lock-fill me-1"></i> Admin Panel
                        </a>
                    </li>
                @endadmin
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2">
                    <a class="nav-link position-relative p-2 {{ request()->is('inboxes*') ? 'text-white active' : '' }}" href="/inboxes" title="Inbox">
                        <i class="bi bi-inbox-fill" style="font-size: 1.3rem;"></i>

                        @php
                            $unreadInboxCount = \App\Models\Inbox::where("to_user_id", Auth::user()->id)->where("marked_read", false)->get()->count();
                        @endphp

                        @if ($unreadInboxCount != 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-container">
                            @university
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0d6efd&color=fff"
                                    alt="Profile" class="avatar-img">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=28a745&color=fff"
                                    alt="Profile" class="avatar-img">
                            @enduniversity
                            <span class="status-indicator"></span>
                        </div>
                        <span class="fw-medium">{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown mt-3 animate slideIn">
                        <li>
                            <h6 class="dropdown-header text-uppercase fw-bold">
                                @university
                                    Organization Profile
                                @else
                                    Personal Profile
                                @enduniversity
                            </h6>
                        </li>

                        @university
                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/overview">
                                    <i class="bi bi-building me-3"></i> Overview
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/papers">
                                    <i class="bi bi-journal-text me-3"></i> Publications
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/researchers">
                                    <i class="bi bi-people me-3"></i> Researchers
                                </a>
                            </li>
                        @enduniversity

                        @lecturer
                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/overview">
                                    <i class="bi bi-person me-3"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/papers">
                                    <i class="bi bi-journal-code me-3"></i> Papers
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="/{{ Auth::user()->profileId }}/stars">
                                    <i class="bi bi-star me-3"></i> Stars
                                </a>
                            </li>
                        @endlecturer

                        <li>
                            <hr class="dropdown-divider my-2">
                        </li>

                        <li>
                            <a class="dropdown-item py-2" href="/settings">
                                <i class="bi bi-gear me-3"></i> Settings
                            </a>
                        </li>

                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                                    <i class="bi bi-box-arrow-right me-3"></i> Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
