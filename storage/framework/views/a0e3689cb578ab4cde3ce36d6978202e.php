<footer class="glass-footer mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-4">

        <div class="footer-brand mb-3 mb-md-0">
            <span class="text-white-50 small">© <?php echo e(date('Y')); ?></span>
            <span class="fw-bold text-white ms-1">AndRPaid</span>
            <span class="text-white-50 small ms-1">| All rights reserved.</span>
        </div>

        <?php if (\Illuminate\Support\Facades\Blade::check('notadmin')): ?>
            <div>
                <button type="button" class="btn btn-glass-feedback d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#feedbackModal">
                    <div class="icon-circle">
                        <i class="bi bi-chat-heart-fill"></i>
                    </div>
                    <span>Feedback & Reports</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</footer>

<?php if (\Illuminate\Support\Facades\Blade::check('notadmin')): ?>
    <?php
        $reportTypes = \App\Models\ReportType::all();
    ?>

    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="horizon-card">
                    <div class="horizon-sidebar">
                        <div class="horizon-sidebar-icon">
                            <i class="bi bi-chat-heart-fill"></i>
                        </div>
                        <div>
                            <h4 class="horizon-sidebar-title">We Listen</h4>
                            <p class="horizon-sidebar-text">Your feedback directly shapes the future of AndRPaid.</p>
                        </div>
                    </div>

                    <div class="horizon-main">

                        <button type="button" class="horizon-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <h3 class="fw-bold text-dark mb-4">Share Thoughts</h3>

                        <form action="/report/submit" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="horizon-label">Topic</label>
                                <select class="horizon-input" name="type" required>
                                    <option value="" selected disabled>Select...</option>
                                    <?php $__currentLoopData = $reportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reportType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($reportType->reportTypeId); ?>"><?php echo e($reportType->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="horizon-label">Message</label>
                                <textarea class="horizon-input" name="description" rows="3" placeholder="What's on your mind?" required></textarea>
                            </div>

                            <button type="submit" class="horizon-submit-btn">
                                Send Feedback
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('successReport')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('successReport')); ?></p>

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
<?php endif; ?>
<?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/partials/footer.blade.php ENDPATH**/ ?>