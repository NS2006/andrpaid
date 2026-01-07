

<?php $__env->startSection('title', 'Research Grants & Affiliations'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Research Grants & Affiliations</h3>
                        <p class="text-muted">Apply for university affiliation to unlock research funding and resources.</p>
                    </div>
                    <a href="<?php echo e(route('dashboard', ['profileId' => $user->profileId])); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>

                
                <?php if($currentAffiliation): ?>
                    <div class="card border-0 shadow-sm rounded-3 mb-5">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Current Status</h5>
                            
                            <div class="d-flex align-items-center p-3 rounded-3 bg-light border">
                                <div class="me-3">
                                    <?php if($currentAffiliation->status === 'accepted'): ?>
                                        <i class="bi bi-check-circle-fill text-success display-5"></i>
                                    <?php elseif($currentAffiliation->status === 'pending'): ?>
                                        <i class="bi bi-hourglass-split text-warning display-5"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill text-danger display-5"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold"><?php echo e($currentAffiliation->university->user->name); ?></h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge <?php echo e($currentAffiliation->status === 'accepted' ? 'bg-success' : 'bg-warning text-dark'); ?> text-uppercase">
                                            <?php echo e($currentAffiliation->status); ?>

                                        </span>
                                        <span class="text-muted small">Requested on <?php echo e($currentAffiliation->created_at->format('M d, Y')); ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if($currentAffiliation->status === 'accepted'): ?>
                                <div class="alert alert-success mt-3 mb-0">
                                    <i class="bi bi-info-circle me-2"></i> You have full access to this university's grant resources.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if(!$currentAffiliation || $currentAffiliation->status === 'rejected'): ?>
                    <h5 class="fw-bold mb-3">Available Institutions</h5>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo e($uni->name); ?>&background=random" 
                                                 class="rounded-circle me-3" width="50" height="50">
                                            <div>
                                                <h6 class="fw-bold mb-0"><?php echo e($uni->name); ?></h6>
                                                <small class="text-muted"><?php echo e($uni->university->location ?? 'Indonesia'); ?></small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3">
                                            Apply for affiliation to access research facilities, digital libraries, and funding opportunities provided by <?php echo e($uni->name); ?>.
                                        </p>
                                        
                                        <form action="/<?php echo e($user->profileId); ?>/grants/apply" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="university_id" value="<?php echo e($uni->university->id); ?>">
                                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold">
                                                Request Affiliation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php elseif($currentAffiliation->status === 'pending'): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-lock display-4 opacity-25"></i>
                        <p class="mt-3">You cannot apply to other universities while a request is pending.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/lecturer/grants.blade.php ENDPATH**/ ?>