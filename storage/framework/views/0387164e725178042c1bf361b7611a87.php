<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <style>
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            height: 100%;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .project-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            background: #fff;
            margin-bottom: 15px;
            transition: all 0.2s;
        }
        .project-card:hover {
            border-color: #8e2de2;
            box-shadow: 0 5px 15px rgba(142, 45, 226, 0.05);
        }

        .sidebar-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .user-avatar-sm { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; }
        .avatar-group { display: flex; padding-left: 10px; }
        .avatar-group .user-avatar-sm { margin-left: -10px; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', ['navbarProfileData' => $navbarProfileData, 'user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                <p class="text-muted mb-0">
                    Welcome back, <span class="fw-bold text-dark"><?php echo e($user->name); ?></span>. 
                    You have <span class="text-primary fw-bold"><?php echo e($pendingRequestsCount); ?> pending request</span>.
                </p>
            </div>
            <div>
                <a href="/papers/create" class="btn btn-dark shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>New Project
                </a>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1">Active Projects</div>
                            <h2 class="fw-bold mb-0 text-dark"><?php echo e($activeProjectsCount); ?></h2>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                            <i class="bi bi-check-circle me-1"></i> On track
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <?php
                    if (isset($isUniversity) && $isUniversity) {
                        $link = url("/" . $user->profileId . "/university/requests");
                        $cardTitle = "Affiliation Requests";
                    } else {
                        $link = url("/" . $user->profileId . "/grants");
                        $cardTitle = "Pending Grants";
                    }
                ?>

                <a href="<?php echo e($link); ?>" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small fw-bold text-uppercase mb-1">
                                    <?php echo e($cardTitle); ?>

                                </div>
                                <h2 class="fw-bold mb-0 text-dark"><?php echo e($pendingRequestsCount); ?></h2>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <?php if($pendingRequestsCount > 0): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">
                                    Status Update
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">View opportunities</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Citations</div>
                            <h2 class="fw-bold mb-0 text-dark"><?php echo e(number_format($citations)); ?></h2>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-quote"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-success small fw-bold">
                        <i class="bi bi-arrow-up-short"></i> 12% this month
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1">Inbox</div>
                            <h2 class="fw-bold mb-0 text-dark"><?php echo e($messageCount); ?></h2>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">
                            <?php echo e($unreadMessages); ?> Unread
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">
                <h5 class="fw-bold text-dark mb-4">Active Collaborations</h5>

                <?php if($activePapers->count() > 0): ?>
                    <?php $__currentLoopData = $activePapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $phase = 'Phase 1: Literature Review';
                            $phaseColor = 'primary';
                            
                            if($paper->lit_review_finalized) {
                                $phase = 'Phase 2: Methodology';
                                $phaseColor = 'info';
                            }
                            if($paper->methodology_finalized) {
                                $phase = 'Phase 3: Results & Analysis';
                                $phaseColor = 'warning';
                            }
                            if($paper->results_finalized) {
                                $phase = 'Phase 4: Conclusion';
                                $phaseColor = 'danger';
                            }
                            if($paper->conclusion_finalized) {
                                $phase = 'Phase 5: Publication';
                                $phaseColor = 'success';
                            }
                        ?>

                        <div class="project-card position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-secondary border">
                                    <?php echo e($paper->paperType->name ?? 'Research'); ?>

                                </span>
                                <small class="text-muted">Updated <?php echo e($paper->updated_at->diffForHumans(null, true, true)); ?> ago</small>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">
                                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/workspace" class="text-decoration-none text-dark stretched-link">
                                    <?php echo e($paper->title); ?>

                                </a>
                            </h5>
                            <p class="text-muted small mb-3 text-truncate">
                                <?php echo e(Str::limit($paper->description, 120)); ?>

                            </p>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                        
                                <span class="badge bg-<?php echo e($phaseColor); ?> bg-opacity-10 text-<?php echo e($phaseColor); ?> border border-<?php echo e($phaseColor); ?> border-opacity-25 rounded-pill">
                                    <?php echo e($phase); ?>

                                </span>

                                <div class="avatar-group">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e($paper->lecturer->user->name); ?>&background=random" 
                                         class="user-avatar-sm shadow-sm" 
                                         title="Owner: <?php echo e($paper->lecturer->user->name); ?>">
                                    
                                    <div class="user-avatar-sm bg-light text-secondary d-flex align-items-center justify-content-center small fw-bold shadow-sm" style="font-size: 0.7rem;">+2</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-5 border rounded bg-light">
                        <i class="bi bi-folder-x text-muted mb-3" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No active projects found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                
                <div class="sidebar-card">
                    <h6 class="fw-bold text-dark mb-3">Recommended for You</h6>
                    
                    <div class="d-flex flex-column gap-3">
                        <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                if ($rec instanceof \App\Models\User) {
                                    $recUser = $rec;
                                    $displayName = $rec->name;
                                    $subText = "University • " . ($rec->university->location ?? 'Indonesia');
                                    $title = ""; 
                                } else {
                                    $recUser = $rec->user;
                                    $displayName = $recUser->name;
                                    
                                    $uniName = $rec->affiliation->university->user->name ?? 'Independent';
                                    $subText = "Computer Science • " . Str::limit($uniName, 20);
                                    $title = $rec->title ?? "";
                                }
                            ?>

                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e($displayName); ?>&background=random" class="rounded-circle" width="40" height="40">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate small">
                                        <a href="/<?php echo e($recUser->profileId); ?>/overview" class="text-dark text-decoration-none">
                                            <?php echo e($title); ?> <?php echo e($displayName); ?>

                                        </a>
                                    </h6>

                                    <p class="text-muted small mb-0 text-truncate">
                                        <?php echo e($subText); ?>

                                    </p>
                                    
                                </div>
                                <button class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="/find" class="text-decoration-none small fw-bold">View all suggestions</a>
                    </div>
                </div>

                <div class="sidebar-card bg-primary bg-opacity-10 border border-primary border-opacity-10">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-award-fill text-primary"></i>
                        <h6 class="fw-bold text-primary mb-0">New Grant Available</h6>
                    </div>
                    <h6 class="fw-bold text-dark small mb-1">NSF Interdisciplinary Grant for AI Safety</h6>
                    <p class="text-muted small mb-3">Deadline: Oct 15, 2026</p>
                    <button class="btn btn-primary btn-sm w-100">View Details</button>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>