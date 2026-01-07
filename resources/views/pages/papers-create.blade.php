@extends('layouts.app')

@section('title', 'Create New Paper')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/papers.css') }}">
@endsection

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-5 border-bottom pb-3">
                    <h2 class="fw-bold text-dark mb-1">Create a new paper repository</h2>
                    <p class="text-muted">
                        A paper repository contains all your paper informations, and the final PDF version.
                    </p>
                </div>

                <form action="/papers/create-new-paper" method="POST" id="createPaperForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Owner & Paper Title <span
                                class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">

                            <div
                                class="owner-badge d-flex align-items-center gap-2 px-3 py-2 rounded-3 border bg-light text-secondary">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random"
                                    class="rounded-circle" width="20" height="20">
                                <span class="fw-bold small">{{ Auth::user()->name }}</span>
                            </div>

                            <span class="text-muted fs-4">/</span>

                            <div class="flex-grow-1">
                                <input type="text" name="title" class="form-control paper-form-input"
                                    placeholder="e.g. Analysis of AI Transformers" required autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Description <span
                                class="text-muted fw-normal">(Optional)</span></label>
                        <textarea name="description" class="form-control paper-form-input" rows="3"
                            placeholder="Briefly describe what this research is about..."></textarea>
                    </div>

                    <hr class="my-4 text-secondary opacity-25">

                    <div class="mb-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">Paper Type</label>
                                <select name="paperType" class="form-select paper-form-select" required>
                                    <option value="" disabled selected>Select The Paper Type...</option>
                                    @foreach ($paperTypes as $paperType)
                                        <option value="{{ $paperType->paperTypeId }}">{{ $paperType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">
                                    Research Fields <span class="text-danger">*</span>
                                    <span class="text-muted fw-normal small ms-1">(Max 3)</span>
                                </label>

                                <div class="field-selector-wrapper position-relative" id="fieldSelector">

                                    <div class="form-control paper-form-input d-flex flex-wrap align-items-center gap-2"
                                        id="field-visual-box" style="min-height: 45px; cursor: text;">

                                        <input type="text" id="field-search-input"
                                            class="border-0 bg-transparent p-0 m-0"
                                            style="outline: none; flex-grow: 1; min-width: 100px;"
                                            placeholder="Select or search..." autocomplete="off">
                                    </div>

                                    <div class="field-dropdown-menu shadow-sm border rounded-3 mt-1 d-none" id="field-list">
                                    </div>

                                    <div id="hidden-inputs-container">
                                    </div>

                                    <div id="field-error-msg" class="text-danger small mt-1 d-none">
                                        Please select at least one research field.
                                    </div>
                                </div>

                                <script>
                                    window.researchFieldsData = {!! json_encode($researchFields) !!};
                                </script>
                            </div>

                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25">

                    <div class="mb-5">
                        <label class="form-label fw-bold text-dark mb-3">Visibility</label>

                        <div class="visibility-option mb-2">
                            <input type="radio" name="visibility" id="vis-public" value="public" checked>
                            <label for="vis-public" class="d-flex align-items-start gap-3 p-3 rounded-3 border">
                                <div class="vis-icon-box">
                                    <i class="bi bi-globe-americas fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">Public</div>
                                    <div class="small text-muted">Anyone on the internet can see this repository. You choose
                                        who can edit.</div>
                                </div>
                            </label>
                        </div>

                        <div class="visibility-option">
                            <input type="radio" name="visibility" id="vis-private" value="private">
                            <label for="vis-private" class="d-flex align-items-start gap-3 p-3 rounded-3 border">
                                <div class="vis-icon-box">
                                    <i class="bi bi-lock-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">Private</div>
                                    <div class="small text-muted">You choose who can see and edit to this repository.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 border-top pt-4">
                        <button type="submit" class="btn btn-create-repo py-2 px-4">
                            Create Repository
                        </button>
                        <a href="/dashboard" class="btn btn-link text-decoration-none text-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {

                const allFields = window.researchFieldsData || [];
                const wrapper = document.getElementById('fieldSelector');
                const visualBox = document.getElementById('field-visual-box');
                const searchInput = document.getElementById('field-search-input');
                const dropdown = document.getElementById('field-list');
                const hiddenContainer = document.getElementById('hidden-inputs-container');
                const errorMsg = document.getElementById('field-error-msg');

                let selectedIds = [];
                const MAX_SELECTION = 3;

                renderDropdown(allFields);

                visualBox.addEventListener('click', () => {
                    searchInput.focus();
                    showDropdown();
                });

                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase();
                    const filtered = allFields.filter(field =>
                        field.name.toLowerCase().includes(query)
                    );
                    renderDropdown(filtered);
                    showDropdown();
                });

                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) {
                        hideDropdown();
                    }
                });

                const form = document.getElementById('createPaperForm');
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

                        if (isSelected) {
                            div.classList.add('selected');
                        } else if (isFull) {
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

                    visualBox.style.borderColor = '#d0d7de';
                    errorMsg.classList.add('d-none');

                    renderDropdown(allFields);
                }

                function removeSelection(id) {
                    selectedIds = selectedIds.filter(itemId => itemId !== id);
                    renderTags();
                    updateHiddenInputs();
                    renderDropdown(allFields);
                }

                function renderTags() {
                    
                    const tags = visualBox.querySelectorAll('.field-tag');
                    tags.forEach(t => t.remove());

                    selectedIds.forEach(id => {
                        const field = allFields.find(f => String(f.researchFieldId) === id);
                        if (!field) return;

                        const tag = document.createElement('div');
                        tag.className = 'field-tag';
                        tag.innerHTML = `
                ${field.name}
                <span class="remove-tag" onclick="window.removeFieldTag('${id}')">&times;</span>
            `;
                        visualBox.insertBefore(tag, searchInput);
                    });

                    if (selectedIds.length > 0) {
                        searchInput.placeholder = "";
                    } else {
                        searchInput.placeholder = "Select or search...";
                    }
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

                window.removeFieldTag = function(id) {
                    removeSelection(id);
                };

            });
        </script>
    @endpush
@endsection
