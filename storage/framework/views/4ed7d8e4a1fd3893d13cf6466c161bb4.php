

<?php $__env->startSection('title', 'Affiliation Requests'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Affiliation Requests</h3>
                        <p class="text-muted">Manage lecturers requesting to join your institution.</p>
                    </div>
                    <a href="<?php echo e(route('dashboard', ['profileId' => $user->profileId])); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>

                
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <?php if($requests->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3">Lecturer</th>
                                            <th class="py-3">Date Requested</th>
                                            <th class="py-3">Status</th>
                                            <th class="pe-4 py-3 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?php echo e($req->lecturer->user->name); ?>&background=random" 
                                                             class="rounded-circle me-3" width="40" height="40">
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">
                                                                <a href="/<?php echo e($req->lecturer->user->profileId); ?>/overview" class="text-dark text-decoration-none">
                                                                    <?php echo e($req->lecturer->user->name); ?>

                                                                </a>
                                                            </h6>
                                                            <small class="text-muted"><?php echo e($req->lecturer->province->name ?? 'Unknown Location'); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo e($req->created_at->format('M d, Y')); ?>

                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending Review</span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        
                                                        <form action="/<?php echo e($user->profileId); ?>/university/request/reject" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="affiliation_id" value="<?php echo e($req->id); ?>">
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Reject this request?')">
                                                                Reject
                                                            </button>
                                                        </form>

                                                        
                                                        <form action="/<?php echo e($user->profileId); ?>/university/request/approve" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="affiliation_id" value="<?php echo e($req->id); ?>">
                                                            <button type="submit" class="btn btn-success btn-sm text-white">
                                                                <i class="bi bi-check-lg me-1"></i> Approve
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted opacity-50">
                                    <i class="bi bi-inbox display-1"></i>
                                </div>
                                <h5 class="text-muted">No pending requests</h5>
                                <p class="small text-muted">Lecturers asking to join your university will appear here.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($requests->links()); ?>

                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/university/requests.blade.php ENDPATH**/ ?>