@extends('layouts.app')

@section('title', 'Master Data')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/admin-masterData.css') }}">
@endsection

@section('content')
    @include('partials.navbarAdmin')

    @if ($type === 'researchFields')
        <div class="container master-data-container">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="master-data-card">
                        <div class="master-data-header">
                            <div>
                                <h4 class="fw-bold mb-1">Research Fields</h4>
                                <p class="text-muted small mb-0">Manage publication categories and identifiers.</p>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="master-data-search-wrapper">
                                    <i class="bi bi-search master-data-search-icon"></i>
                                    <input type="text" id="searchInput" class="form-control master-data-search-input"
                                        placeholder="       Search fields...">
                                </div>

                                <button class="btn btn-primary d-flex align-items-center gap-2 px-4 fw-medium"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="bi bi-plus-lg"></i> New Field
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table master-data-table mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Research Field Name</th>
                                        <th>Research Field ID</th>
                                        <th>Created</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($researchFields as $index => $field)
                                        <tr>
                                            <td class="text-muted font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark search-target">{{ $field->name }}</span>
                                            </td>
                                            <td>
                                                <span class="master-data-badge">{{ $field->researchFieldId }}</span>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $field->created_at ? $field->created_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-edit me-1 edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $field->id }}" data-name="{{ $field->name }}"
                                                    data-slug="{{ $field->researchFieldId }}">
                                                    <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                                                </button>

                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-delete delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $field->id }}" data-name="{{ $field->name }}">
                                                    <i class="bi bi-trash-fill" style="font-size: 0.9rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted opacity-25 mb-3">
                                                    <i class="bi bi-inbox-fill" style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h6 class="fw-bold text-secondary">No Data Available</h6>
                                                <p class="text-muted small">Start by adding a new research field.</p>
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="noResultsRow" class="d-none">
                                        <td colspan="5" class="text-center py-5">
                                            <h6 class="fw-bold text-secondary">No matching results found</h6>
                                            <button class="btn btn-link btn-sm text-decoration-none"
                                                onclick="document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('keyup'));">Clear
                                                Search</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form method="POST" action="/admin-panel/master-data/research-fields/create">
                        @csrf
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">Create New Field</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">Display Name</label>
                                <input type="text" class="form-control form-control-lg" id="createName" name="name"
                                    placeholder="e.g. Artificial Intelligence" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">Research Field ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">#</span>
                                    <input type="text" class="form-control bg-light border-start-0 text-muted"
                                        id="createSlug" name="slug" placeholder="artificial-intelligence" readonly>
                                </div>
                                <div class="form-text small">Automatically generated from name. Used for system
                                    identification.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Create Field</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form id="editForm" method="POST" action="/admin-panel/master-data/research-fields/update">
                        @csrf

                        <input type="hidden" id="editId" name="id">

                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">Edit Field</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">Display Name</label>
                                <input type="text" class="form-control form-control-lg" id="editName" name="name"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">Research Field ID</label>
                                <input type="text" class="form-control bg-light text-muted" id="editSlug"
                                    name="slug" readonly>
                                <div class="form-text small">Research Field ID cannot be changed to preserve system
                                    integrity.</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content master-data-modal-content text-center p-4">
                    <form id="deleteForm" method="POST" action="/admin-panel/master-data/research-fields/delete">
                        @csrf

                        <input type="hidden" id="deleteId" name="id">

                        <div class="mb-3 text-danger bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; background: #fff5f5;">
                            <i class="bi bi-trash3-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Delete this field?</h5>
                        <p class="text-muted small mb-4">
                            You are about to delete <span id="deleteNamePlaceholder" class="fw-bold text-dark"></span>.
                            This might affect existing papers associated with it.
                        </p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Yes, Delete It</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const tableRows = document.querySelectorAll('#dataTable tbody tr:not(#noResultsRow):not(#emptyRow)');
                    const noResultsRow = document.getElementById('noResultsRow');

                    searchInput.addEventListener('keyup', function(e) {
                        const term = e.target.value.toLowerCase();
                        let hasResults = false;

                        tableRows.forEach(row => {
                            const nameText = row.querySelector('.search-target').textContent.toLowerCase();

                            if (nameText.includes(term)) {
                                row.classList.remove('d-none');
                                hasResults = true;
                            } else {
                                row.classList.add('d-none');
                            }
                        });

                        if (!hasResults && tableRows.length > 0) {
                            noResultsRow.classList.remove('d-none');
                        } else {
                            noResultsRow.classList.add('d-none');
                        }
                    });

                    const createNameInput = document.getElementById('createName');
                    const createSlugInput = document.getElementById('createSlug');

                    createNameInput.addEventListener('input', function() {
                        const val = this.value;

                        const slug = val.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '_');
                        createSlugInput.value = slug;
                    });

                    const editButtons = document.querySelectorAll('.edit-btn');
                    const editForm = document.getElementById('editForm');
                    const editNameInput = document.getElementById('editName');
                    const editSlugInput = document.getElementById('editSlug');
                    const editIdInput = document.getElementById('editId');

                    editNameInput.addEventListener('input', function() {
                        const val = this.value;

                        const slug = val.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '_');
                        editSlugInput.value = slug;
                    });

                    editButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');
                            const slug = this.getAttribute('data-slug');

                            editIdInput.value = id;
                            editNameInput.value = name;
                            editSlugInput.value = slug;
                        });
                    });

                    const deleteButtons = document.querySelectorAll('.delete-btn');
                    const deleteForm = document.getElementById('deleteForm');
                    const deleteNamePlaceholder = document.getElementById('deleteNamePlaceholder');
                    const deleteIdInput = document.getElementById('deleteId');

                    deleteButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');

                            deleteIdInput.value = id;
                            deleteNamePlaceholder.textContent = name;
                        });
                    });

                });
            </script>
        @endpush
    @endif

    @if ($type === 'paperTypes')
        <div class="container master-data-container">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="master-data-card">
                        <div class="master-data-header">
                            <div>
                                <h4 class="fw-bold mb-1">Paper Types</h4>
                                <p class="text-muted small mb-0">Manage the definitions of paper categories (e.g. Journal,
                                    Thesis).</p>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="master-data-search-wrapper">
                                    <i class="bi bi-search master-data-search-icon"></i>
                                    <input type="text" id="searchInput" class="form-control master-data-search-input"
                                        placeholder="       Search types...">
                                </div>

                                <button class="btn btn-primary d-flex align-items-center gap-2 px-4 fw-medium"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="bi bi-plus-lg"></i> New Type
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table master-data-table mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Paper Type Name</th>
                                        <th>Paper Type ID</th>
                                        <th>Created</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paperTypes as $index => $item)
                                        <tr>
                                            <td class="text-muted font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark search-target">{{ $item->name }}</span>
                                            </td>
                                            <td>
                                                <span class="master-data-badge">{{ $item->paperTypeId }}</span>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-edit me-1 edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-slug="{{ $item->paperTypeId }}">
                                                    <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                                                </button>

                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-delete delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                    <i class="bi bi-trash-fill" style="font-size: 0.9rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted opacity-25 mb-3">
                                                    <i class="bi bi-file-earmark-text-fill"
                                                        style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h6 class="fw-bold text-secondary">No Paper Types Found</h6>
                                                <p class="text-muted small">Start by adding a new paper type.</p>
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="noResultsRow" class="d-none">
                                        <td colspan="5" class="text-center py-5">
                                            <h6 class="fw-bold text-secondary">No matching results found</h6>
                                            <button class="btn btn-link btn-sm text-decoration-none"
                                                onclick="document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('keyup'));">
                                                Clear Search
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form method="POST" action="/admin-panel/master-data/paper-types/create">
                        @csrf
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">Create New Paper Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">Display Name</label>
                                <input type="text" class="form-control form-control-lg" id="createName"
                                    name="name" placeholder="e.g. Journal" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">Paper Type ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">#</span>
                                    <input type="text" class="form-control bg-light border-start-0 text-muted"
                                        id="createSlug" name="slug" placeholder="journal" readonly>
                                </div>
                                <div class="form-text small">Automatically generated from name. Used for system
                                    identification.</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Create Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form id="editForm" method="POST" action="/admin-panel/master-data/paper-types/update">
                        @csrf

                        <input type="hidden" id="editId" name="id">

                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">Edit Paper Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">Display Name</label>
                                <input type="text" class="form-control form-control-lg" id="editName" name="name"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">Paper Type ID</label>
                                <input type="text" class="form-control bg-light text-muted" id="editSlug"
                                    name="slug" readonly>
                                <div class="form-text small">Paper Type ID cannot be changed to preserve system integrity.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content master-data-modal-content text-center p-4">
                    <form id="deleteForm" method="POST" action="/admin-panel/master-data/paper-types/delete">
                        @csrf

                        <input type="hidden" id="deleteId" name="id">

                        <div class="mb-3 text-danger bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; background: #fff5f5;">
                            <i class="bi bi-trash3-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Delete this type?</h5>
                        <p class="text-muted small mb-4">
                            You are about to delete <span id="deleteNamePlaceholder" class="fw-bold text-dark"></span>.
                            This might affect existing papers associated with it.
                        </p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Yes, Delete It</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const tableRows = document.querySelectorAll('#dataTable tbody tr:not(#noResultsRow):not(#emptyRow)');
                    const noResultsRow = document.getElementById('noResultsRow');

                    searchInput.addEventListener('keyup', function(e) {
                        const term = e.target.value.toLowerCase();
                        let hasResults = false;

                        tableRows.forEach(row => {
                            const nameText = row.querySelector('.search-target').textContent.toLowerCase();
                            if (nameText.includes(term)) {
                                row.classList.remove('d-none');
                                hasResults = true;
                            } else {
                                row.classList.add('d-none');
                            }
                        });

                        if (!hasResults && tableRows.length > 0) {
                            noResultsRow.classList.remove('d-none');
                        } else {
                            noResultsRow.classList.add('d-none');
                        }
                    });

                    const createNameInput = document.getElementById('createName');
                    const createSlugInput = document.getElementById('createSlug');

                    createNameInput.addEventListener('input', function() {
                        const val = this.value;
                        const slug = val.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '_');
                        createSlugInput.value = slug;
                    });

                    const editButtons = document.querySelectorAll('.edit-btn');
                    const editNameInput = document.getElementById('editName');
                    const editSlugInput = document.getElementById('editSlug');
                    const editIdInput = document.getElementById('editId');

                    editNameInput.addEventListener('input', function() {
                        const val = this.value;
                        const slug = val.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '_');
                        editSlugInput.value = slug;
                    });

                    editButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');
                            const slug = this.getAttribute('data-slug');

                            editIdInput.value = id;
                            editNameInput.value = name;
                            editSlugInput.value = slug;
                        });
                    });

                    const deleteButtons = document.querySelectorAll('.delete-btn');
                    const deleteNamePlaceholder = document.getElementById('deleteNamePlaceholder');
                    const deleteIdInput = document.getElementById('deleteId');

                    deleteButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');

                            deleteIdInput.value = id;
                            deleteNamePlaceholder.textContent = name;
                        });
                    });
                });
            </script>
        @endpush
    @endif

    @if (session('success'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @push('scripts')
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        @endpush
    @endif

    @if (session('error'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-error text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-x-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Error!</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('error') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @push('scripts')
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        @endpush
    @endif
@endsection
