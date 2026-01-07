<?php $__env->startSection('title', 'Stars'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/profile.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div style="background-color: #0d1117; padding-top: 2rem;">
        <div class="container text-white pb-3">
            <h3><?php echo e($user->name); ?>'s Starred Papers</h3>
            <p><?php echo e($papers->count() ?? '0'); ?> Starred Paper<?php echo e($papers->count() == 1 ? '' : 's'); ?></p>
        </div>
    </div>

    <div class="container py-5">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-4">

            <div class="paper-showcase-control-bar d-flex flex-grow-1 gap-2 w-90 p-2">

                <div class="position-relative flex-grow-1">
                    <i
                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary opacity-75"></i>
                    <input type="text" id="clientSearchInput" class="form-control paper-showcase-search-input ps-5"
                        placeholder="Search starred titles or authors..." name="search" value="<?php echo e(request('search')); ?>">
                </div>

                <button class="btn paper-showcase-filter-trigger d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                    <i class="bi bi-sliders2"></i>
                    <span class="d-none d-md-inline">Filters</span>
                </button>
            </div>
        </div>


        <div class="offcanvas offcanvas-end paper-filter-offcanvas" tabindex="-1" id="filterOffcanvas">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold">Refine Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-4">
                <form id="filterForm" action="<?php echo e(url()->current()); ?>" method="GET">

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Sort By</label>
                        <select class="form-select filter-modern-select" name="sort">
                            <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest First</option>
                            <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest First</option>
                            <option value="stars" <?php echo e(request('sort') == 'stars' ? 'selected' : ''); ?>>Most Stars</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Status</label>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="status-draft" name="status[]" value="draft"
                                <?php echo e(in_array('draft', request('status', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="status-draft">
                                <i class="bi bi-pencil-square me-1"></i> Draft
                            </label>

                            <input type="checkbox" class="btn-check" id="status-finalized" name="status[]" value="finalized"
                                <?php echo e(in_array('finalized', request('status', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="status-finalized">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Visibility</label>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="vis-public" name="visibility[]" value="public"
                                <?php echo e(in_array('public', request('visibility', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="vis-public">
                                <i class="bi bi-globe-americas me-1"></i> Public
                            </label>

                            <input type="checkbox" class="btn-check" id="vis-private" name="visibility[]" value="private"
                                <?php echo e(in_array('private', request('visibility', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="vis-private">
                                <i class="bi bi-lock-fill me-1"></i> Private
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Open Collaboration</label>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="collab-yes" name="collab[]" value="1"
                                <?php echo e(in_array('1', request('collab', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="collab-yes">Yes</label>

                            <input type="checkbox" class="btn-check" id="collab-no" name="collab[]" value="0"
                                <?php echo e(in_array('0', request('collab', [])) ? 'checked' : ''); ?>>
                            <label class="btn btn-outline-secondary btn-sm rounded-pill" for="collab-no">No</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Paper Type</label>
                        <div class="multi-select-wrapper" id="paper-type-wrapper">
                            <div class="multi-select-box">
                                <input type="text" class="search-input-tag" placeholder="Search types..."
                                    autocomplete="off">
                            </div>
                            <div class="multi-select-dropdown"></div>
                            <div class="hidden-inputs"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label fw-bold mb-2">Research Field</label>
                        <div class="multi-select-wrapper" id="research-field-wrapper">
                            <div class="multi-select-box">
                                <input type="text" class="search-input-tag" placeholder="Search fields..."
                                    autocomplete="off">
                            </div>
                            <div class="multi-select-dropdown"></div>
                            <div class="hidden-inputs"></div>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                window.paperTypesData = <?php echo json_encode($paperTypes->map(fn($t) => ['id' => $t->paperTypeId, 'name' => $t->name]), 512) ?>;
                window.researchFieldsData = <?php echo json_encode($researchFields->map(fn($f) => ['id' => $f->researchFieldId, 'name' => $f->name]), 512) ?>;
            </script>

            <div class="offcanvas-footer p-3 border-top bg-light">
                <div class="d-flex gap-2">
                    <a href="<?php echo e(url()->current()); ?>"
                        class="btn btn-light flex-grow-1 border fw-bold text-decoration-none text-center pt-2">
                        Clear
                    </a>
                    <button type="button"
                        class="btn paper-showcase-create-action flex-grow-1 w-100 justify-content-center"
                        onclick="document.getElementById('filterForm').submit();">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">

            <?php $__empty_1 = true; $__currentLoopData = $papers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="paper-showcase-card p-4">
                    <div class="d-flex justify-content-between align-items-start">

                        <div class="d-flex flex-column gap-2 col-md-10">

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="mb-0 fw-bold paper-showcase-title me-2">
                                    <a href="#" class="text-decoration-none stretched-link"><?php echo e($paper->title); ?></a>
                                </h5>

                                <span class="paper-status-badge <?php echo e($paper->visibility); ?>">
                                    <i class="bi bi-globe-americas me-1"></i> <?php echo e($paper->visibility); ?>

                                </span>

                                <span class="paper-status-badge <?php echo e($paper->status); ?>">
                                    <i class="bi bi-check-circle-fill me-1"></i> <?php echo e($paper->status); ?>

                                </span>

                                <?php if($paper->openCollaboration): ?>
                                    <span class="paper-status-badge collab-open"
                                        title="This author is looking for collaborators">
                                        <i class="bi bi-people-fill me-1"></i> Open Collab
                                    </span>
                                <?php endif; ?>
                            </div>

                            <p class="paper-showcase-description mb-1">
                                <?php echo e($paper->description); ?>

                            </p>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="small text-muted fw-bold text-uppercase"
                                    style="font-size: 0.7rem; letter-spacing: 0.05em;">Fields:</span>
                                <?php $__currentLoopData = $paper->researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $researchField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span
                                        class="badge bg-light text-secondary border fw-bold"><?php echo e($researchField->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="row align-items-center mt-1 border-top pt-3 gy-2">

                                <div class="col-5 col-md-4 col-lg-3">
                                    <span
                                        class="paper-type-pill <?php echo e(Str::replace('_', '-', $paper->paperType->paperTypeId)); ?> d-block w-100">
                                        <span class="dot"></span> <?php echo e($paper->paperType->name); ?>

                                    </span>
                                </div>

                                <div class="col-auto position-relative z-2 d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e($paper->lecturer->user->name); ?>&background=random&size=20"
                                        class="rounded-circle">
                                    <a href="/<?php echo e($paper->lecturer->user->profileId); ?>/overview"
                                        class="text-decoration-none author-hover-link fw-bold text-dark small">
                                        <?php echo e($paper->lecturer->user->name); ?>

                                    </a>
                                </div>

                                <div class="col-auto">
                                    <span class="paper-meta-text text-dark fw-medium">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span
                                            id="star-count-<?php echo e($paper->paperId); ?>"><?php echo e($paper->paperStars->count()); ?></span>
                                    </span>
                                </div>

                                <div class="col-auto">
                                    <span class="paper-meta-text text-muted">
                                        Updated <?php echo e($paper->updated_at->diffForHumans()); ?>

                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="position-relative z-2 ms-3">
                            <?php
                                $isStarred = Auth::check() && $paper->paperStars->contains('user_id', Auth::user()->id);
                            ?>

                            <button class="btn <?php echo e($isStarred ? 'btn-warning' : 'paper-action-star-btn'); ?>"
                                id="star-btn-<?php echo e($paper->paperId); ?>" onclick="toggleStar(`<?php echo e($paper->paperId); ?>`)"
                                title="Unstar this paper">

                                <i class="bi <?php echo e($isStarred ? 'bi-star-fill' : 'bi-star'); ?>"
                                    id="star-icon-<?php echo e($paper->paperId); ?>"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php
                    $isCurrentUser = $user->id === Auth::user()->id;
                ?>

                <div class="paper-empty-state text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="empty-state-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Starred Papers Yet</h4>
                    <?php if($isCurrentUser): ?>
                        <p class="text-muted mb-4 col-md-8 mx-auto" style="font-size: 0.95rem; line-height: 1.6;">
                            You haven't starred any papers yet (or no papers match your filters).
                        </p>
                        <a href="/" class="btn paper-showcase-create-action d-flex align-items-center gap-2">
                            <i class="bi bi-compass"></i>
                            Explore Papers
                        </a>
                    <?php else: ?>
                        <p class="text-muted mb-4 col-md-8 mx-auto" style="font-size: 0.95rem; line-height: 1.6;">
                            This user hasn't starred any papers yet (or no papers match your filters).
                        </p>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <div id="no-search-results" class="text-center py-5 d-none">
                <div class="text-muted opacity-50 mb-2">
                    <i class="bi bi-search" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold text-muted">No matching papers found</h5>
                <p class="text-muted small">Try adjusting your search terms.</p>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('clientSearchInput');
                const paperCards = document.querySelectorAll('.paper-showcase-card');
                const noResultsMsg = document.getElementById('no-search-results');

                if (searchInput && paperCards.length > 0) {
                    searchInput.addEventListener('input', function(e) {
                        const query = e.target.value.toLowerCase().trim();
                        let visibleCount = 0;

                        paperCards.forEach(card => {
                            const titleEl = card.querySelector('.paper-showcase-title');
                            const titleText = titleEl ? titleEl.innerText.toLowerCase() : '';

                            const authorEl = card.querySelector('.author-hover-link');
                            const authorText = authorEl ? authorEl.innerText.toLowerCase() : '';

                            if (titleText.includes(query) || authorText.includes(query)) {
                                card.classList.remove('d-none');
                                visibleCount++;
                            } else {
                                card.classList.add('d-none');
                            }
                        });

                        if (visibleCount === 0) {
                            noResultsMsg.classList.remove('d-none');
                        } else {
                            noResultsMsg.classList.add('d-none');
                        }
                    });
                }
            });
        </script>

        
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                function initMultiSelect(wrapperId, data, inputName) {
                    const wrapper = document.getElementById(wrapperId);
                    if (!wrapper) return;

                    const visualBox = wrapper.querySelector('.multi-select-box');
                    const searchInput = wrapper.querySelector('.search-input-tag');
                    const dropdown = wrapper.querySelector('.multi-select-dropdown');
                    const hiddenContainer = wrapper.querySelector('.hidden-inputs');

                    let selectedIds = [];

                    renderDropdown(data);

                    visualBox.addEventListener('click', () => {
                        searchInput.focus();
                        dropdown.classList.add('show');
                    });

                    document.addEventListener('click', (e) => {
                        if (!wrapper.contains(e.target)) {
                            dropdown.classList.remove('show');
                        }
                    });

                    searchInput.addEventListener('input', (e) => {
                        const query = e.target.value.toLowerCase();
                        const filtered = data.filter(item => item.name.toLowerCase().includes(query));
                        renderDropdown(filtered);
                        dropdown.classList.add('show');
                    });

                    searchInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && searchInput.value === '' && selectedIds.length > 0) {
                            removeSelection(selectedIds[selectedIds.length - 1]);
                        }
                    });

                    function renderDropdown(items) {
                        dropdown.innerHTML = '';
                        if (items.length === 0) {
                            dropdown.innerHTML = '<div class="p-2 text-muted small text-center">No results</div>';
                            return;
                        }

                        items.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'dropdown-option';
                            div.textContent = item.name;

                            if (selectedIds.includes(String(item.id))) {
                                div.classList.add('selected');
                            }

                            div.addEventListener('click', (e) => {
                                e.stopPropagation();
                                addSelection(item);
                                searchInput.value = '';
                                searchInput.focus();
                                renderDropdown(data);
                            });

                            dropdown.appendChild(div);
                        });
                    }

                    function addSelection(item) {
                        const id = String(item.id);
                        if (selectedIds.includes(id)) return;
                        selectedIds.push(id);
                        updateUI();
                    }

                    function removeSelection(id) {
                        selectedIds = selectedIds.filter(i => i !== id);
                        updateUI();
                    }

                    function updateUI() {
                        const existingTags = visualBox.querySelectorAll('.selected-tag');
                        existingTags.forEach(t => t.remove());

                        selectedIds.forEach(id => {
                            const item = data.find(d => String(d.id) === id);
                            if (item) {
                                const tag = document.createElement('div');
                                tag.className = 'selected-tag';
                                tag.innerHTML = `${item.name} <span class="remove-tag">&times;</span>`;
                                tag.querySelector('.remove-tag').addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    removeSelection(id);
                                });
                                visualBox.insertBefore(tag, searchInput);
                            }
                        });

                        hiddenContainer.innerHTML = '';
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = inputName;
                            input.value = id;
                            hiddenContainer.appendChild(input);
                        });
                        renderDropdown(data);
                    }
                }

                if (window.paperTypesData) {
                    initMultiSelect('paper-type-wrapper', window.paperTypesData, 'paper_type_id[]');
                }
                if (window.researchFieldsData) {
                    initMultiSelect('research-field-wrapper', window.researchFieldsData, 'research_field_id[]');
                }
            });
        </script>

        
        <script>
            async function toggleStar(paperId) {
                const btn = document.getElementById(`star-btn-${paperId}`);
                const icon = document.getElementById(`star-icon-${paperId}`);
                const countSpan = document.getElementById(`star-count-${paperId}`);
                const navbarProfileStarsCount = document.getElementById(`navbarProfileStarsCount`)

                try {
                    const response = await fetch(`/papers/${paperId}/star`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    });

                    const data = await response.json();

                    if (!data.is_starred) {
                        window.location.reload();
                    }
                    countSpan.innerText = data.new_count;

                } catch (error) {
                    console.error('Error toggling star:', error);
                }
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/stars.blade.php ENDPATH**/ ?>