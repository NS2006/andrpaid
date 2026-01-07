<?php $__env->startSection('title', 'Sent Messages'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarInbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-send me-2"></i>Sent
                </h5>
                <span class="text-muted small"><?php echo e($sentInboxes->total()); ?> messages</span>
            </div>

            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $sentInboxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mail-item sent position-relative"
                         onclick="location.href='/inboxes/<?php echo e($sent->inboxId); ?>'">

                        <img src="https://ui-avatars.com/api/?name=<?php echo e($sent->toUser->name); ?>&background=random&color=fff"
                             alt="Avatar" class="mail-avatar">

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="sent-recipient">
                                    <span class="prefix">To:</span>
                                    <?php echo e($sent->toUser->name ?? 'Unknown Recipient'); ?>

                                </span>

                                <span class="mail-date">
                                    <?php echo e($sent->created_at->format('d M Y')); ?>

                                </span>
                            </div>

                            <div class="mail-body-preview">
                                <span class="mail-subject text-dark fw-medium">
                                    <?php echo e($sent->subject ?? '(No Subject)'); ?>

                                </span>
                                <span class="mx-1 text-muted">-</span>
                                <span>
                                    <?php echo e($sent->body ? Str::limit(strip_tags($sent->body), 60) : 'No content...'); ?>

                                </span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <div class="empty-icon text-muted">
                            <i class="bi bi-send-x"></i>
                        </div>
                        <h5>No sent messages</h5>
                        <p class="small text-muted">Messages you send will appear here.</p>
                        <a href="/inbox/compose" class="btn btn-outline-primary btn-sm mt-2 rounded-pill">
                            Compose New
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($sentInboxes->hasPages()): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <?php echo e($sentInboxes->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/inboxes-sent.blade.php ENDPATH**/ ?>