<?php $__env->startSection('title', 'Compose Message'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes-compose.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarInbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="/inboxes/compose/<?php echo e($inbox->inboxId); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="compose-card">
                        <div class="compose-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-pencil-fill me-2 text-primary"></i>Message
                            </h5>
                            <a href="/inboxes" class="btn-close" aria-label="Close"></a>
                        </div>

                        <div class="compose-body">
                            <div class="mb-4">
                                <label for="email" class="form-label-custom">Recipient (Email)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-custom border-start-0"
                                           placeholder="user@university.edu"
                                           value="<?php echo e(old('email', optional($inbox->toUser ?? null)->email)); ?>">
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label for="subject" class="form-label-custom">Subject</label>
                                <input type="text" name="subject" id="subject"
                                       class="form-control form-control-custom"
                                       placeholder="Briefly describe your topic"
                                       value="<?php echo e(old('subject', $inbox->subject)); ?>">
                            </div>

                            <div class="mb-2">
                                <label for="body" class="form-label-custom">Message Body</label>
                                <textarea name="body" id="body" rows="8"
                                          class="form-control form-control-custom"
                                          placeholder="Type your message here..."
                                          style="resize: none;"><?php echo e(old('body', $inbox->body)); ?></textarea>
                            </div>

                            <div class="btn-action-group">
                                <button type="submit" name="action" value="draft"
                                        class="btn btn-light text-muted fw-medium border">
                                    <i class="bi bi-file-earmark me-2"></i>Save Draft
                                </button>

                                <div class="d-flex gap-2">
                                    <button type="button"
                                            class="btn btn-outline-danger border-0"
                                            onclick="confirmDeleteDraft('/inboxes/compose/<?php echo e($inbox->inboxId); ?>/delete-draft')">
                                        Discard
                                    </button>

                                    <button type="submit" name="action" value="send"
                                            class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                        <i class="bi bi-send-fill me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
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

    
    <?php if(session('error')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-error text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-x-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Error!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('error')); ?></p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/inboxes-compose.blade.php ENDPATH**/ ?>