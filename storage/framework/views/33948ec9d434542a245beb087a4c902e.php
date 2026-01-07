<?php $__env->startSection('title', 'Literature Review - ' . $paper->title); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/paper.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarPaper', ['paper' => $paper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="mb-4">
            <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/workspace"
                class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Workspace
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-primary bg-opacity-10 text-primary"
                        style="width: 45px; height: 45px; font-size: 1.2rem;">
                        <i class="bi bi-book"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Literature Review</h3>
                </div>
                <p class="text-muted mb-0 ms-1">Manage your bibliography and synthesize key themes.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm" id="citationStyleSelector" onchange="updateCitations()"
                    style="width: auto; cursor: pointer; font-weight: 500;">
                    <option value="apa">APA Style</option>
                    <option value="mla">MLA Style</option>
                    <option value="harvard">Harvard Style</option>
                    <option value="chicago">Chicago Style</option>
                </select>

                <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/export-bibtex"
                    class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download me-1"></i> Export BibTeX
                </a>

                <?php if($canEdit): ?>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSourceModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Source
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 order-lg-2">
                <div class="workspace-card p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">Synthesis Progress</h6>
                    <div class="mb-4">
                        <?php
                            $totalRefs = !empty($paper->references_data) ? count($paper->references_data) : 0;
                            $analyzedRefs = 0;
                            if ($totalRefs > 0) {
                                foreach ($paper->references_data as $ref) {
                                    if (isset($ref['is_analyzed']) && $ref['is_analyzed']) {
                                        $analyzedRefs++;
                                    }
                                }
                            }
                            $progress = $totalRefs > 0 ? ($analyzedRefs / $totalRefs) * 100 : 0;
                        ?>

                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Sources Analyzed</span>
                            <span class="fw-bold text-primary"><?php echo e($analyzedRefs); ?>/<?php echo e($totalRefs); ?></span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($progress); ?>%">
                            </div>
                        </div>
                    </div>

                    <?php if($canEdit): ?>
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#synthesisModal">
                                <i class="bi bi-pencil-square me-2"></i>Write Synthesis
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="workspace-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Key Themes</h6>
                        <?php if($canEdit): ?>
                            <button class="btn btn-link p-0 text-decoration-none small" onclick="toggleThemeForm()">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <?php if(!empty($paper->themes)): ?>
                            <?php $__currentLoopData = $paper->themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/remove-theme"
                                    method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="theme_name" value="<?php echo e($theme); ?>">
                                    <button type="submit"
                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 text-decoration-none d-flex align-items-center gap-2"
                                        style="cursor: pointer; border: none; background: none;">
                                        <?php echo e($theme); ?>

                                        <i class="bi bi-x-circle-fill opacity-50 hover-opacity-100"
                                            style="font-size: 0.7rem;"></i>
                                    </button>
                                </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span class="text-muted small fst-italic">No themes defined yet.</span>
                        <?php endif; ?>

                        <?php if($canEdit): ?>
                            <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 border-dashed"
                                id="addThemeBtn" style="cursor: pointer;" onclick="toggleThemeForm()">
                                + Add Tag
                            </span>
                        <?php endif; ?>

                        <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/add-theme" method="POST"
                            id="addThemeForm" style="display: none;" class="w-100 mt-2">
                            <?php echo csrf_field(); ?>
                            <div class="input-group input-group-sm">
                                <input type="text" name="theme_name" class="form-control" placeholder="Theme name..."
                                    required autofocus>
                                <button class="btn btn-primary" type="submit"><i class="bi bi-check"></i></button>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleThemeForm()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="workspace-card p-4 mt-4">
                    <h6 class="fw-bold text-dark mb-2">Review Status</h6>
                    <p class="text-muted small mb-3">
                        <?php if($paper->lit_review_finalized): ?>
                            This section is marked as complete. You can reopen it if you need to add more sources.
                        <?php else: ?>
                            Once you have synthesized your sources, mark this section as finalized.
                        <?php endif; ?>
                    </p>

                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/finalize-lit-review" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if($paper->lit_review_finalized): ?>
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Finalized
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="bi bi-check2-circle me-2"></i> Finalize Review
                            </button>
                        <?php endif; ?>
                    </form>
                </div>

            </div>

            <div class="col-lg-8 order-lg-1">
                <?php if(empty($paper->references_data)): ?>
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-journal-bookmark-fill empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">No References Yet</h5>
                        <p class="text-muted small mb-4">Start your research by adding your first source to the board.</p>

                        <?php if($canEdit): ?>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addSourceModal">
                                Add Reference
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php $__currentLoopData = array_reverse($paper->references_data); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="workspace-card reference-card p-4" data-title="<?php echo e($ref['title']); ?>"
                                data-author="<?php echo e($ref['author']); ?>" data-year="<?php echo e($ref['year']); ?>"
                                data-journal="<?php echo e($ref['publication'] ?? ''); ?>" data-url="<?php echo e($ref['url'] ?? ''); ?>">

                                <div class="d-flex gap-3">
                                    <div class="module-icon <?php echo e($ref['is_analyzed'] ? 'bg-success text-success' : 'bg-secondary text-secondary'); ?> bg-opacity-10 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i
                                            class="bi <?php echo e($ref['is_analyzed'] ? 'bi-check-circle-fill' : 'bi-hourglass-split'); ?> fs-5"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="fw-bold text-dark mb-1"><?php echo e($ref['title']); ?></h6>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            <?php echo e($ref['author']); ?> (<?php echo e($ref['year']); ?>)
                                            <?php if(!empty($ref['publication'])): ?>
                                                • <i><?php echo e($ref['publication']); ?></i>
                                            <?php endif; ?>
                                        </p>

                                        <?php if(!empty($ref['key_points'])): ?>
                                            <div class="mt-2 pt-2 border-top">
                                                <span class="small fw-bold text-muted me-2"><i
                                                        class="bi bi-pin-angle-fill me-1"></i>Key Points:</span>
                                                <?php $__currentLoopData = $ref['key_points']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="key-point-badge"><?php echo e($point); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="citation-box">
                                            <span
                                                class="badge bg-secondary position-absolute top-0 start-0 translate-middle ms-3"
                                                style="font-size: 0.6rem;" id="citation-badge">APA</span>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="citation-text fst-italic">Loading citation...</span>
                                                <button class="btn btn-link btn-sm p-0 ms-2 text-muted"
                                                    onclick="copyCitation(this)" title="Copy to clipboard">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if($canEdit): ?>
        <div class="modal fade" id="addSourceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-detective">
                <div class="modal-content">
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/add-reference" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="key_points" id="keyPointsJson">

                        <div class="dossier-container">
                            <div class="dossier-paper">
                                <div class="dossier-title">
                                    <span>Adding Reference</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="dossier-label">Title of Work</label>
                                        <input type="text" name="title" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="dossier-label">Primary Author</label>
                                        <input type="text" name="author" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="dossier-label">Publication Year</label>
                                        <input type="number" name="year" class="dossier-input" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="dossier-label">Journal / Conference</label>
                                        <input type="text" name="journal" class="dossier-input">
                                    </div>
                                    <div class="col-12">
                                        <label class="dossier-label">DOI / Link</label>
                                        <input type="url" name="url" class="dossier-input">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_analyzed"
                                                    id="flexSwitchCheckDefault">
                                                <label class="form-check-label small" for="flexSwitchCheckDefault">Mark as
                                                    "Analyzed"</label>
                                            </div>
                                            <button type="submit" class="btn btn-dark px-4 rounded-pill">
                                                Save Reference <i class="bi bi-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dossier-board">
                                <div class="board-header">
                                    <i class="bi bi-pin-angle-fill text-danger"></i> Key Findings
                                </div>
                                <div class="sticky-note-container" id="stickyContainer"></div>
                                <div class="add-note-wrapper">
                                    <textarea id="noteInput" rows="2" class="note-input" placeholder="Type a key point..."></textarea>
                                    <button type="button" class="btn-pin" onclick="addStickyNote()">
                                        <i class="bi bi-pin-fill me-1"></i> Pin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="synthesisModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/save-synthesis" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="synthesis-container">
                            <div class="synthesis-sources">
                                <h5 class="mb-4"><i class="bi bi-layers-fill me-2"></i>Source Material</h5>
                                <?php if(empty($paper->references_data)): ?>
                                    <p class="text-white-50 small">No references added yet.</p>
                                <?php else: ?>
                                    <?php $__currentLoopData = $paper->references_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($ref['key_points'])): ?>
                                            <div class="source-item">
                                                <div class="source-citation">
                                                    <?php echo e($ref['author']); ?> (<?php echo e($ref['year']); ?>)
                                                </div>
                                                <ul class="ps-3 mb-0 source-points">
                                                    <?php $__currentLoopData = $ref['key_points']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($point); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            <div class="synthesis-editor">
                                <div class="synthesis-header">
                                    <span>Synthesis Draft</span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <textarea name="synthesis_text" class="editor-textarea" placeholder="Start synthesizing your findings here..."><?php echo e($paper->synthesis_text); ?></textarea>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-dark px-4">
                                        <i class="bi bi-save me-1"></i> Save Draft
                                    </button>
                                </div>
                            </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            updateCitations();
        });

        function updateCitations() {
            const style = document.getElementById('citationStyleSelector').value;
            const cards = document.querySelectorAll('.reference-card');

            cards.forEach(card => {
                const data = {
                    title: card.dataset.title,
                    author: card.dataset.author,
                    year: card.dataset.year,
                    journal: card.dataset.journal,
                    url: card.dataset.url
                };

                const textElement = card.querySelector('.citation-text');
                const badgeElement = card.querySelector('#citation-badge');

                badgeElement.innerText = style.toUpperCase();
                textElement.innerHTML = formatCitation(data, style);
            });
        }

        function formatCitation(data, style) {
            const author = data.author || 'Unknown author';
            const year = data.year || 'n.d.';
            const title = data.title || 'Untitled';
            const journal = data.journal || '';
            const url = data.url || '';

            switch (style) {
                case 'apa': {
                    let text = `${author} (${year}). <i>${title}</i>.`;
                    if (journal) text += ` <i>${journal}</i>.`;
                    if (url) text += ` ${url}`;
                    return text;
                }

                case 'mla': {
                    let text = `${author}. "${title}."`;
                    if (journal) text += ` <i>${journal}</i>,`;
                    text += ` ${year}.`;
                    if (url) text += ` ${url}.`;
                    return text;
                }

                case 'harvard': {
                    let text = `${author} (${year}) '${title}'.`;
                    if (journal) text += ` <i>${journal}</i>.`;
                    if (url) text += ` Available at: ${url}.`;
                    return text;
                }

                case 'chicago': {
                    let text = `${author}. "${title}."`;
                    if (journal) text += ` <i>${journal}</i>`;
                    text += ` (${year}).`;
                    if (url) text += ` ${url}.`;
                    return text;
                }

                default:
                    return `${author} (${year}). ${title}.`;
            }
        }

        function copyCitation(btn) {
            event.stopPropagation();

            const text = btn.parentElement.querySelector('.citation-text').innerText;
            navigator.clipboard.writeText(text).then(() => {
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2 text-success"></i>';
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                }, 1500);
            });
        }

        let keyPoints = [];

        function addStickyNote() {
            const input = document.getElementById('noteInput');
            const container = document.getElementById('stickyContainer');
            const text = input.value.trim();

            if (text === '') return;

            keyPoints.push(text);
            updateHiddenInput();

            const note = document.createElement('div');
            note.classList.add('sticky-note');
            note.innerText = text;

            const closeBtn = document.createElement('i');
            closeBtn.classList.add('bi', 'bi-x', 'sticky-close');
            closeBtn.onclick = function() {
                const index = keyPoints.indexOf(text);
                if (index > -1) keyPoints.splice(index, 1);
                updateHiddenInput();
                note.remove();
            };

            note.appendChild(closeBtn);
            container.appendChild(note);

            input.value = '';
            container.scrollTop = container.scrollHeight;
        }

        function updateHiddenInput() {
            document.getElementById('keyPointsJson').value = JSON.stringify(keyPoints);
        }

        function toggleThemeForm() {
            const btn = document.getElementById('addThemeBtn');
            const form = document.getElementById('addThemeForm');

            if (form.style.display === 'none') {
                form.style.display = 'flex';
                btn.style.display = 'none';
                form.querySelector('input').focus();
            } else {
                form.style.display = 'none';
                btn.style.display = 'inline-block';
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/literature-review.blade.php ENDPATH**/ ?>