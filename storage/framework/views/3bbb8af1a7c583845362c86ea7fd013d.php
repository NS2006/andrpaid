<nav class="navbar navbar-expand-md navbar-dark modern-navbar sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('dashboard', ['profileId' => Auth::user()->profileId])); ?>">
            <div class="brand-logo-container me-2">
                <img src="<?php echo e(asset('images/logo.jpeg')); ?>" alt="Logo" class="brand-logo">
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
                    <a class="nav-link px-3 <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard', ['profileId' => Auth::user()->profileId])); ?>">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 <?php echo e(request()->is('find') ? 'active' : ''); ?>" href="/find">
                        <i class="bi bi-search me-1"></i> Find
                    </a>
                </li>

                

                <?php if (\Illuminate\Support\Facades\Blade::check('admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?php echo e(request()->is('admin-panel*') ? 'active' : ''); ?>" href="/admin-panel">
                            <i class="bi bi-shield-lock-fill me-1"></i> Admin Panel
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2">
                    <a class="nav-link position-relative p-2 <?php echo e(request()->is('inboxes*') ? 'text-white active' : ''); ?>" href="/inboxes" title="Inbox">
                        <i class="bi bi-inbox-fill" style="font-size: 1.3rem;"></i>

                        <?php
                            $unreadInboxCount = \App\Models\Inbox::where("to_user_id", Auth::user()->id)->where("marked_read", false)->get()->count();
                        ?>

                        <?php if($unreadInboxCount != 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-container">
                            <?php if (\Illuminate\Support\Facades\Blade::check('university')): ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(Auth::user()->name); ?>&background=0d6efd&color=fff"
                                    alt="Profile" class="avatar-img">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(Auth::user()->name); ?>&background=28a745&color=fff"
                                    alt="Profile" class="avatar-img">
                            <?php endif; ?>
                            <span class="status-indicator"></span>
                        </div>
                        <span class="fw-medium"><?php echo e(Auth::user()->name); ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown mt-3 animate slideIn">
                        <li>
                            <h6 class="dropdown-header text-uppercase fw-bold">
                                <?php if (\Illuminate\Support\Facades\Blade::check('university')): ?>
                                    Organization Profile
                                <?php else: ?>
                                    Personal Profile
                                <?php endif; ?>
                            </h6>
                        </li>

                        <?php if (\Illuminate\Support\Facades\Blade::check('university')): ?>
                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/overview">
                                    <i class="bi bi-building me-3"></i> Overview
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/papers">
                                    <i class="bi bi-journal-text me-3"></i> Publications
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/researchers">
                                    <i class="bi bi-people me-3"></i> Researchers
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (\Illuminate\Support\Facades\Blade::check('lecturer')): ?>
                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/overview">
                                    <i class="bi bi-person me-3"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/papers">
                                    <i class="bi bi-journal-code me-3"></i> Papers
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="/<?php echo e(Auth::user()->profileId); ?>/stars">
                                    <i class="bi bi-star me-3"></i> Stars
                                </a>
                            </li>
                        <?php endif; ?>

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
                                <?php echo csrf_field(); ?>
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
<?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/partials/navbar.blade.php ENDPATH**/ ?>