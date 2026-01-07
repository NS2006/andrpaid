@extends('layouts.app')

@section('title', 'Settings - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/paper-settings.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container settings-container py-5" style="padding-bottom: 40vh;">
        <div class="mb-5 border-bottom pb-4">
            <h2 class="fw-bold text-dark">Paper Settings</h2>
            <p class="text-muted mb-0">Manage details, visibility, and configuration for your research paper.</p>
        </div>

        <div class="row g-5">
            <div class="col-md-3 d-none d-md-block">
                <div class="settings-nav">
                    <small class="text-uppercase text-muted fw-bold ps-3 mb-2 d-block"
                        style="font-size: 0.7rem;">Configuration</small>
                    <a href="#general" class="nav-link-settings">
                        <i class="bi bi-sliders"></i> General
                    </a>
                    <a href="#publishing" class="nav-link-settings">
                        <i class="bi bi-globe-americas"></i> Publishing & Privacy
                    </a>

                    <div class="my-4 border-top"></div>

                    <small class="text-uppercase text-muted fw-bold ps-3 mb-2 d-block"
                        style="font-size: 0.7rem;">Administrative</small>
                    <a href="#danger" class="nav-link-settings danger-link">
                        <i class="bi bi-exclamation-triangle"></i> Danger Zone
                    </a>
                </div>
            </div>

            <div class="col-md-9">

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/settings/update-paper" method="POST"
                    id="updatePaperForm">
                    @csrf

                    <div id="general" class="mb-5 scroll-margin">
                        <h4 class="section-title"><i class="bi bi-sliders text-primary"></i> General Settings</h4>

                        <div class="settings-card">
                            <div class="mb-4">
                                <label for="title" class="form-label">Paper Title</label>
                                <input type="text" class="form-control form-control-lg fw-bold" id="title"
                                    name="title" value="{{ $paper->title }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="abstract" class="form-label">Description</label>
                                <textarea class="form-control" id="abstract" name="description" rows="5">{{ $paper->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="type" class="form-label">Research Type</label>
                                    <select class="form-select" id="type" name="type">
                                        @foreach ($paperTypes as $paperType)
                                            <option value="{{ $paperType->id }}"
                                                {{ $paper->paperType->name == $paperType->name ? 'selected' : '' }}>
                                                {{ $paperType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark mb-1">
                                        Research Fields <span class="text-danger">*</span>
                                        <span class="text-muted fw-normal small ms-1">(Max 3)</span>
                                    </label>

                                    <div class="field-selector-wrapper position-relative" id="fieldSelector">
                                        <div class="form-control d-flex flex-wrap align-items-center gap-2"
                                            id="field-visual-box" style="min-height: 45px; cursor: text;">
                                            <input type="text" id="field-search-input"
                                                class="border-0 bg-transparent p-0 m-0"
                                                style="outline: none; flex-grow: 1; min-width: 100px;"
                                                placeholder="Select or search..." autocomplete="off">
                                        </div>
                                        <div class="field-dropdown-menu shadow-sm border rounded-3 mt-1 d-none"
                                            id="field-list"></div>
                                        <div id="hidden-inputs-container"></div>
                                        <div id="field-error-msg" class="text-danger small mt-1 d-none">
                                            Please select at least one research field.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="publishing" class="mb-5 scroll-margin">
                        <h4 class="section-title"><i class="bi bi-globe-americas text-primary"></i> Publishing & Privacy
                        </h4>

                        <div class="settings-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">Public Visibility</h6>
                                    <p class="text-muted small mb-0">
                                        When enabled, this paper is visible on your public profile and search results.
                                    </p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_public"
                                        {{ $paper->is_public ?? true ? 'checked' : '' }}
                                        style="width: 3em; height: 1.5em;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="position-sticky bottom-0 bg-white border-top py-3 d-flex justify-content-between align-items-center"
                        style="z-index: 1000; margin: 0 -1rem; padding: 0 1rem;">
                        <span class="text-muted small">Unsaved changes will be lost.</span>
                        <button type="submit" class="btn btn-primary btn-save shadow">Save Changes</button>
                    </div>
                </form>

                <div id="danger" class="mt-5 scroll-margin">
                    <h4 class="section-title text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Danger Zone</h4>
                    <div class="settings-card danger-zone-card">
                        <div class="danger-item">
                            <div>
                                <h6 class="fw-bold text-danger mb-1">Delete this paper</h6>
                                <p class="text-muted small mb-0">Once you delete a paper, there is no going back.</p>
                            </div>
                            <button class="btn btn-danger fw-bold" data-bs-toggle="modal"
                                data-bs-target="#deletePaperModal">Delete Paper</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.researchFieldsData = {!! json_encode($researchFields) !!};
        window.currentPaperFields = {!! json_encode($paper->researchFields->pluck('researchFieldId')) !!};
    </script>

    <div class="modal fade" id="deletePaperModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">Are you absolutely sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-dark">
                        This action cannot be undone. Permanently delete <strong
                            class="text-dark">{{ $paper->title }}</strong>?
                    </p>

                    <div class="alert alert-warning border-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <span class="small">Type <strong>{{ $paper->title }}</strong> to confirm.</span>
                    </div>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/settings/delete-paper"
                        method="POST">
                        @csrf

                        <input type="text" class="form-control mb-3" name="confirm_title" id="deleteConfirmInput"
                            placeholder="Type paper title here..." required autocomplete="off">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold py-2" id="deleteConfirmBtn" disabled>
                                Delete Paper
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allFields = window.researchFieldsData || [];
            let selectedIds = (window.currentPaperFields || []).map(String);

            const wrapper = document.getElementById('fieldSelector');
            const visualBox = document.getElementById('field-visual-box');
            const searchInput = document.getElementById('field-search-input');
            const dropdown = document.getElementById('field-list');
            const hiddenContainer = document.getElementById('hidden-inputs-container');
            const errorMsg = document.getElementById('field-error-msg');
            const MAX_SELECTION = 3;

            renderTags();
            updateHiddenInputs();
            renderDropdown(allFields);

            visualBox.addEventListener('click', () => {
                searchInput.focus();
                showDropdown();
            });

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                const filtered = allFields.filter(field => field.name.toLowerCase().includes(query));
                renderDropdown(filtered);
                showDropdown();
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) hideDropdown();
            });

            const form = document.getElementById('updatePaperForm');
            form.addEventListener('submit', (e) => {
                if (selectedIds.length === 0) {
                    e.preventDefault();
                    visualBox.style.borderColor = '#dc3545';
                    errorMsg.classList.remove('d-none');
                    visualBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            function showDropdown() {
                dropdown.classList.remove('d-none');
            }

            function hideDropdown() {
                dropdown.classList.add('d-none');
            }

            function renderDropdown(fields) {
                dropdown.innerHTML = '';
                if (fields.length === 0) {
                    dropdown.innerHTML = '<div class="p-2 text-muted small text-center">No fields found</div>';
                    return;
                }
                fields.forEach(field => {
                    const div = document.createElement('div');
                    div.className = 'field-option';
                    div.textContent = field.name;
                    const isSelected = selectedIds.includes(String(field.researchFieldId));
                    const isFull = selectedIds.length >= MAX_SELECTION;

                    if (isSelected) div.classList.add('selected');
                    else if (isFull) {
                        div.classList.add('disabled');
                        div.title = "Maximum 3 fields allowed";
                    }

                    if (!isSelected && !isFull) {
                        div.addEventListener('click', (e) => {
                            e.stopPropagation();
                            addSelection(field);
                            searchInput.value = '';
                            renderDropdown(allFields);
                            searchInput.focus();
                        });
                    }
                    dropdown.appendChild(div);
                });
            }

            function addSelection(field) {
                if (selectedIds.length >= MAX_SELECTION) return;
                const id = String(field.researchFieldId);
                if (selectedIds.includes(id)) return;
                selectedIds.push(id);
                renderTags();
                updateHiddenInputs();
                visualBox.style.borderColor = '#dee2e6';
                errorMsg.classList.add('d-none');
                renderDropdown(allFields);
            }

            window.removeFieldTag = function(id) {
                selectedIds = selectedIds.filter(itemId => itemId !== String(id));
                renderTags();
                updateHiddenInputs();
                renderDropdown(allFields);
            };

            function renderTags() {
                const tags = visualBox.querySelectorAll('.field-tag');
                tags.forEach(t => t.remove());
                selectedIds.forEach(id => {
                    const field = allFields.find(f => String(f.researchFieldId) === id);
                    if (!field) return;
                    const tag = document.createElement('div');
                    tag.className = 'field-tag';
                    tag.innerHTML =
                        `${field.name} <span class="remove-tag" onclick="window.removeFieldTag('${id}')">&times;</span>`;
                    visualBox.insertBefore(tag, searchInput);
                });
                if (selectedIds.length > 0) searchInput.placeholder = "";
                else searchInput.placeholder = "Select or search...";
            }

            function updateHiddenInputs() {
                hiddenContainer.innerHTML = '';
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'category_ids[]';
                    input.value = id;
                    hiddenContainer.appendChild(input);
                });
            }

            const deleteInput = document.getElementById('deleteConfirmInput');
            const deleteBtn = document.getElementById('deleteConfirmBtn');
            const expectedTitle = {!! json_encode($paper->title) !!};

            if (deleteInput && deleteBtn) {
                deleteInput.addEventListener('input', function() {
                    if (this.value === expectedTitle) {
                        deleteBtn.removeAttribute('disabled');
                    } else {
                        deleteBtn.setAttribute('disabled', 'disabled');
                    }
                });

                const deleteModalEl = document.getElementById('deletePaperModal');
                deleteModalEl.addEventListener('hidden.bs.modal', function() {
                    deleteInput.value = '';
                    deleteBtn.setAttribute('disabled', 'disabled');
                });
            }
        });
    </script>
@endsection
