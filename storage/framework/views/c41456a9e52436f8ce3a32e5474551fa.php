<nav class="profile-subnav sticky-subnav">
    <div class="container">
        <ul class="nav profile-tabs">

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('*/overview') ? 'active' : ''); ?>" href="/<?php echo e($navbarProfileData["profileId"]); ?>/overview">
                    <i class="bi bi-book me-2"></i>Overview
                </a>
            </li>

            <?php if($user->isLecturer()): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('*/papers') ? 'active' : ''); ?>" href="/<?php echo e($navbarProfileData["profileId"]); ?>/papers">
                    <i class="bi bi-journal-code me-2"></i>Papers
                    <span class="badge-counter"><?php echo e($navbarProfileData["papersCount"]); ?></span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->is('*/stars') ? 'active' : ''); ?>" href="/<?php echo e($navbarProfileData["profileId"]); ?>/stars">
                    <i class="bi bi-star me-2"></i>Stars
                    <span class="badge-counter" id="navbarProfileStarsCount"><?php echo e($navbarProfileData["starsCount"]); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if($user->isUniversity()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->is('*/papers') ? 'active' : ''); ?>" href="/<?php echo e($navbarProfileData["profileId"]); ?>/papers">
                        <i class="bi bi-journal-text me-2"></i>Publications
                        <span class="badge-counter"><?php echo e($navbarProfileData["papersCount"] ?? 0); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->is('*/researchers') ? 'active' : ''); ?>" href="/<?php echo e($navbarProfileData["profileId"]); ?>/researchers">
                        <i class="bi bi-people-fill me-2"></i>Researchers
                        <span class="badge-counter"><?php echo e($navbarProfileData["researchersCount"] ?? 0); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            
        </ul>
    </div>
</nav>
<?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/partials/navbarProfile.blade.php ENDPATH**/ ?>