<?php $__env->startSection('title', 'Workspace - ' . $paper->title); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/paper.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarPaper', ['paper' => $paper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">Research Workspace</h3>
                <p class="text-muted mb-0">Select a module to begin writing or editing.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/lit-review" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                            <?php
                                $refs = $paper->references_data;
                                if(is_string($refs)) $refs = json_decode($refs, true);
                                $refCount = is_array($refs) ? count($refs) : 0;
                            ?>

                            <?php if($paper->lit_review_finalized): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> Finalized
                                </span>
                            <?php elseif($refCount > 0): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">In Progress</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Draft</span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Literature Review</h5>
                        <p class="text-muted small mb-4">Manage references, key points, and synthesize your theoretical framework.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <?php
                                $refs = $paper->references_data;
                                if(is_string($refs)) $refs = json_decode($refs, true);
                                $refCount = is_array($refs) ? count($refs) : 0;
                            ?>

                            <span class="small text-muted"><?php echo e($refCount); ?> References</span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/methodology" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-diagram-3"></i>
                            </div>

                            <?php if($paper->methodology_finalized): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> Finalized
                                </span>
                            <?php elseif(!empty($paper->methodology_xml)): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">In Progress</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Empty</span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Methodology</h5>
                        <p class="text-muted small mb-4">Design your research flow, diagram your process, and define variables.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">
                                <?php if(!empty($paper->methodology_xml)): ?>
                                    Diagram available
                                <?php else: ?>
                                    No diagrams yet
                                <?php endif; ?>
                            </span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>

                            <?php
                                $items = $paper->results_data ?? [];
                                $hasItems = !empty($items);
                            ?>

                            <?php if($paper->results_finalized): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> Finalized
                                </span>
                            <?php elseif($hasItems): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">In Progress</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Empty</span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Results & Analysis</h5>
                        <p class="text-muted small mb-4">Visualize your data using charts and tables, and interpret the findings.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <?php
                                $chartCount = 0; $tableCount = 0;
                                if(is_array($items)) {
                                    foreach($items as $item) {
                                        if($item['type'] === 'chart') $chartCount++;
                                        if($item['type'] === 'table') $tableCount++;
                                    }
                                }
                            ?>
                            <span class="small text-muted">
                                <?php echo e($tableCount); ?> Tables, <?php echo e($chartCount); ?> Charts
                            </span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/conclusion" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-all"></i>
                            </div>

                            <?php
                                $hasContent = !empty($paper->conclusion_summary);
                            ?>

                            <?php if($paper->conclusion_finalized): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> Finalized
                                </span>
                            <?php elseif($hasContent): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">In Progress</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Draft</span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Conclusion</h5>
                        <p class="text-muted small mb-4">Summarize findings, limitations, and propose future research directions.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">
                                <?php echo e($hasContent ? 'Draft started' : 'Not started'); ?>

                            </span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/paper-workspace.blade.php ENDPATH**/ ?>