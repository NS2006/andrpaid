<?php $__env->startSection('title', 'Results & Analysis - ' . $paper->title); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/paper.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarPaper', ['paper' => $paper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">

        <?php
            $isLocked = $paper->results_finalized;
            $canInteract = $canEdit && !$isLocked;
        ?>

        <div class="mb-4">
            <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/workspace"
                class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Workspace
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-warning bg-opacity-10 text-warning"
                        style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Results & Analysis</h3>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php
                        $items = $paper->results_data ?? [];
                        $chartCount = 0;
                        $tableCount = 0;
                        foreach ($items as $item) {
                            if ($item['type'] === 'chart') {
                                $chartCount++;
                            }
                            if ($item['type'] === 'table') {
                                $tableCount++;
                            }
                        }
                    ?>
                    <p class="text-muted mb-0 ms-1">
                        <?php echo e($chartCount); ?> Charts • <?php echo e($tableCount); ?> Tables
                    </p>

                    <?php if($isLocked): ?>
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-2">
                            <i class="bi bi-lock-fill me-1"></i> Finalized
                        </span>
                    <?php else: ?>
                        <span class="badge bg-light text-secondary border ms-2">Draft Mode</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <?php if($canEdit): ?>
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/finalize-results" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if($isLocked): ?>
                            <button type="submit" class="btn btn-outline-success btn-sm me-2" title="Click to Reopen">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-check2-circle me-1"></i> Finalize Results
                            </button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

                <?php if($canInteract): ?>
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/add-table" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-table me-1"></i> Create Table
                        </button>
                    </form>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChartModal">
                        <i class="bi bi-image me-1"></i> Insert Chart
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php if(empty($paper->results_data)): ?>
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-bar-chart-steps empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">No Results Added</h5>
                        <p class="text-muted small mb-0">Insert charts or create tables to document your findings.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $paper->results_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="result-item-card" id="item-<?php echo e($item['id']); ?>">
                            <div class="result-header">
                                <span class="fw-bold text-uppercase small text-muted">
                                    <i class="bi <?php echo e($item['type'] == 'chart' ? 'bi-image' : 'bi-table'); ?> me-2"></i>
                                    <?php echo e($item['type']); ?>

                                </span>
                                <?php if($canInteract): ?>
                                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/delete"
                                        method="POST" onsubmit="return confirm('Delete this item?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="item_id" value="<?php echo e($item['id']); ?>">
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <div class="result-body">
                                <div class="mb-3">
                                    <?php if($canInteract): ?>
                                        <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/update"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="item_id" value="<?php echo e($item['id']); ?>">
                                            <div class="input-group">
                                                <input type="text" name="title"
                                                    class="form-control fw-bold fs-5 border-0 shadow-none px-0"
                                                    value="<?php echo e($item['title']); ?>" style="background: transparent;"
                                                    onblur="this.form.submit()">
                                                <button class="btn btn-link text-muted" type="submit"><i
                                                        class="bi bi-pencil small"></i></button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <h5 class="fw-bold mb-3"><?php echo e($item['title']); ?></h5>
                                    <?php endif; ?>
                                </div>

                                <?php if($item['type'] === 'chart'): ?>
                                    <div class="text-center bg-light p-3 rounded mb-4">
                                        <img src="<?php echo e(asset('storage/' . $item['content'])); ?>" alt="Chart"
                                            class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                    </div>
                                <?php elseif($item['type'] === 'table'): ?>
                                    <div class="custom-table-wrapper">
                                        <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/update"
                                            method="POST" id="form-table-<?php echo e($item['id']); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="item_id" value="<?php echo e($item['id']); ?>">
                                            <input type="hidden" name="table_content"
                                                id="input-table-<?php echo e($item['id']); ?>">

                                            <table class="custom-table" id="table-<?php echo e($item['id']); ?>">
                                                <?php $__currentLoopData = $item['content']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowIndex => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colIndex => $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td contenteditable="<?php echo e($canInteract ? 'true' : 'false'); ?>"
                                                                class="editable-cell"><?php echo e($cell); ?></td>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </table>
                                        </form>
                                    </div>

                                    <?php if($canInteract): ?>
                                        <div class="d-flex gap-2 mb-4">
                                            <button class="btn btn-sm btn-light border"
                                                onclick="tableAddRow('<?php echo e($item['id']); ?>')">+ Row</button>
                                            <button class="btn btn-sm btn-light border"
                                                onclick="tableAddCol('<?php echo e($item['id']); ?>')">+ Col</button>
                                            <button class="btn btn-sm btn-light border"
                                                onclick="saveTable('<?php echo e($item['id']); ?>')"><i class="bi bi-save"></i>
                                                Save Table</button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="analysis-box">
                                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-lightbulb me-2"></i>Key Findings
                                        & Analysis</h6>

                                    <ul class="bullet-list ps-3 mb-3">
                                        <?php if(!empty($item['analysis'])): ?>
                                            <?php $__currentLoopData = $item['analysis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="d-flex justify-content-between">
                                                    <span><?php echo e($point); ?></span>
                                                    <?php if($canInteract): ?>
                                                        <form
                                                            action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/update"
                                                            method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="item_id"
                                                                value="<?php echo e($item['id']); ?>">
                                                            <input type="hidden" name="remove_point_index"
                                                                value="<?php echo e($index); ?>">
                                                            <button type="submit"
                                                                class="btn btn-link py-0 px-1 text-danger small"><i
                                                                    class="bi bi-x"></i></button>
                                                        </form>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <li class="text-muted fst-italic">No key points added yet.</li>
                                        <?php endif; ?>
                                    </ul>

                                    <?php if($canInteract): ?>
                                        <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/update"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="item_id" value="<?php echo e($item['id']); ?>">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="new_point" class="form-control"
                                                    placeholder="Add a key finding (bullet point)..." required>
                                                <button class="btn btn-dark" type="submit">Add</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($canInteract): ?>
        <div class="modal fade" id="addChartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/results/add-chart" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Insert Chart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Chart Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="e.g., Respondent Demographics" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Image</label>
                                <input type="file" name="chart_image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Upload Chart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

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

<?php $__env->startPush('scripts'); ?>
    <script>
        function tableAddRow(id) {
            const table = document.getElementById('table-' + id);
            const colCount = table.rows[0].cells.length;
            const row = table.insertRow(-1);
            for (let i = 0; i < colCount; i++) {
                const cell = row.insertCell(i);
                cell.contentEditable = "true";
                cell.classList.add('editable-cell');
                cell.innerText = "Data";
            }
        }

        function tableAddCol(id) {
            const table = document.getElementById('table-' + id);
            for (let i = 0; i < table.rows.length; i++) {
                const cell = table.rows[i].insertCell(-1);
                cell.contentEditable = "true";
                cell.classList.add('editable-cell');
                cell.innerText = i === 0 ? "Header" : "Data";
            }
        }

        function saveTable(id) {
            const table = document.getElementById('table-' + id);
            let data = [];

            for (let i = 0; i < table.rows.length; i++) {
                let rowData = [];
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                    rowData.push(table.rows[i].cells[j].innerText);
                }
                data.push(rowData);
            }

            document.getElementById('input-table-' + id).value = JSON.stringify(data);
            document.getElementById('form-table-' + id).submit();
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/results.blade.php ENDPATH**/ ?>