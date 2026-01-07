<?php
    $inboxCount = \App\Models\Inbox::where("to_user_id", Auth::user()->id)->where("is_sent", true)->get()->count();
    $sentCount = \App\Models\Inbox::where("from_user_id", Auth::user()->id)->where("is_sent", true)->get()->count();
    $draftCount = \App\Models\Inbox::where("from_user_id", Auth::user()->id)->where("is_sent", false)->get()->count();
?>

<div class="inbox-subnav border-bottom bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <nav class="nav nav-underline pb-0 gap-4">
                <a class="nav-link <?php echo e(request()->is('inboxes') ? 'active' : ''); ?>" href="/inboxes">
                    <i class="bi bi-inbox me-2"></i>Inbox
                    <?php if($inboxCount != 0): ?>
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;"><?php echo e($inboxCount); ?></span>
                    <?php endif; ?>
                </a>

                <a class="nav-link <?php echo e(request()->is('inboxes/sent') ? 'active' : ''); ?>" href="/inboxes/sent">
                    <i class="bi bi-send me-2"></i>Sent
                    <?php if($sentCount != 0): ?>
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;"><?php echo e($sentCount); ?></span>
                    <?php endif; ?>
                </a>

                <a class="nav-link <?php echo e(request()->is('inboxes/drafts') ? 'active' : ''); ?>" href="/inboxes/drafts">
                    <i class="bi bi-file-earmark me-2"></i>Drafts
                    <?php if($draftCount != 0): ?>
                        <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem;"><?php echo e($draftCount); ?></span>
                    <?php endif; ?>
                </a>
            </nav>

            <div class="py-2">
                <a href="/inboxes/compose" class="btn btn-primary btn-sm fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-pencil-square me-2"></i>Compose
                </a>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/partials/navbarInbox.blade.php ENDPATH**/ ?>