<?php $__env->startSection('title', 'Inbox'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarInbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Inbox</h5>
                <span class="text-muted small"><?php echo e($inboxes->total()); ?> messages</span>
            </div>

            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $inboxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inbox): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mail-item <?php echo e($inbox->marked_read ? 'read' : 'unread'); ?>"
                        onclick="location.href='/inboxes/<?php echo e($inbox->inboxId); ?>'">

                        <img src="https://ui-avatars.com/api/?name=<?php echo e($inbox->fromUser->name ?? 'System'); ?>&background=random&color=fff"
                            alt="Avatar" class="mail-avatar">

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="mail-sender">
                                    <?php echo e($inbox->fromUser->name ?? 'System Notification'); ?>

                                </span>
                                <span class="mail-date">
                                    <?php echo e($inbox->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                            <div class="mail-body-preview">
                                <span class="mail-subject"><?php echo e($inbox->subject ?? '(No Subject)'); ?></span>
                                <span class="mx-1 text-muted">-</span>
                                <span><?php echo e(Str::limit(strip_tags($inbox->body ?? 'No content...'), 60)); ?></span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h5>Your inbox is empty</h5>
                        <p class="small">Messages from other researchers will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($inboxes->hasPages()): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <?php echo e($inboxes->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('success')); ?></p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                            CONTINUE
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <?php $__env->startPush('scripts'); ?>
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/inboxes.blade.php ENDPATH**/ ?>