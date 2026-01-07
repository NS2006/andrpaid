<?php $__env->startSection('title', 'Drafts'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarInbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-file-earmark me-2"></i>Drafts
                </h5>
                <span class="text-muted small"><?php echo e($draftInboxes->total()); ?> drafts</span>
            </div>

            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $draftInboxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $draft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mail-item read position-relative"
                        onclick="location.href='/inboxes/compose/<?php echo e($draft->inboxId); ?>'">

                        <div class="text-muted fs-5 ps-1 pe-2">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <?php if($draft->toUser): ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo e($draft->toUser->name); ?>&background=random&color=fff"
                                alt="Avatar" class="mail-avatar">
                        <?php else: ?>
                            <div
                                class="mail-avatar bg-light d-flex align-items-center justify-content-center text-muted border">
                                <i class="bi bi-question-lg"></i>
                            </div>
                        <?php endif; ?>

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="draft-recipient">
                                    <span class="prefix">To:</span>
                                    <?php echo e($draft->toUser->name ?? 'No Recipient'); ?>

                                </span>

                                <span class="mail-date">
                                    <?php echo e($draft->updated_at->diffForHumans()); ?>

                                </span>
                            </div>

                            <div class="mail-body-preview">
                                <span class="text-danger small fw-bold me-2">[Draft]</span>
                                <span class="mail-subject text-dark fw-medium">
                                    <?php echo e($draft->subject ?? '(No Subject)'); ?>

                                </span>
                                <span class="mx-1 text-muted">-</span>
                                <span>
                                    <?php echo e($draft->body ? Str::limit(strip_tags($draft->body), 60) : 'No content...'); ?>

                                </span>
                            </div>
                        </div>

                        <button type="button"
                                class="btn-delete-draft"
                                title="Discard Draft"
                                onclick="event.stopPropagation(); confirmDeleteDraft('/inboxes/compose/<?php echo e($draft->inboxId); ?>/delete-draft')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <div class="empty-icon text-muted">
                            <i class="bi bi-file-earmark-x"></i>
                        </div>
                        <h5>No saved drafts</h5>
                        <p class="small text-muted">Started messages that you haven't sent will appear here.</p>
                        <a href="/inboxes/compose" class="btn btn-outline-primary btn-sm mt-2 rounded-pill">
                            Compose New
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($draftInboxes->hasPages()): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <?php echo e($draftInboxes->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="modal fade" id="deleteDraftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-trash3-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Discard Draft?</h5>
                    <p class="text-muted small mb-4">This action cannot be undone. The draft will be permanently deleted.</p>
                    <form id="deleteDraftForm" method="POST" action="">
                        <?php echo csrf_field(); ?>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">Yes, Discard</button>
                            <button type="button" class="btn btn-light text-muted fw-bold" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function confirmDeleteDraft(deleteUrl) {
                const form = document.getElementById('deleteDraftForm');
                form.action = deleteUrl;

                const myModal = new bootstrap.Modal(document.getElementById('deleteDraftModal'));
                myModal.show();
            }
        </script>
    <?php $__env->stopPush(); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/inboxes-draft.blade.php ENDPATH**/ ?>