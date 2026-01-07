@extends('layouts.app')

@section('title', 'Methodology - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                    class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Workspace
                </a>
                <div class="d-flex align-items-center gap-3" style="margin-top: 20px;">
                    <div class="module-icon bg-info bg-opacity-10 text-info"
                        style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mt-2 mb-0">Research Methodology</h3>

                    @if ($paper->methodology_finalized)
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mt-2">
                            <i class="bi bi-lock-fill me-1"></i> Finalized
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border mt-2">Draft Mode</span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div id="statusMessage" class="text-success small fw-bold" style="opacity: 0; transition: opacity 0.5s;">
                    <i class="bi bi-check-circle-fill me-1"></i> Saved
                </div>

                @if ($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-methodology" method="POST">
                        @csrf
                        @if ($paper->methodology_finalized)
                            <button type="submit" class="btn btn-outline-success btn-sm" title="Click to Reopen">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="bi bi-check2-circle me-1"></i> Finalize Diagram
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        <div class="editor-container">
            @if (!$canEdit || $paper->methodology_finalized)
                <div class="read-only-overlay" title="Read Only Mode (Finalized)"></div>
            @endif

            <iframe id="drawioFrame"
                src="https://embed.diagrams.net/?embed=1&ui=atlas&spin=1&proto=json&configure=1&saveAndExit=0&noSaveBtn=0"></iframe>
        </div>
    </div>

    <div class="row mt-5 g-4" style="margin-bottom: 50px; margin-left: 50px; margin-right: 50px;">
        <div class="col-lg-6">
            <div class="methodology-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-database-fill me-2 text-primary"></i>Data Sources
                    </h5>
                    @if ($canEdit && !$paper->methodology_finalized)
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addDatasetModal">
                            <i class="bi bi-plus-lg"></i> Add Dataset
                        </button>
                    @endif
                </div>

                @if (empty($paper->datasets))
                    <div class="text-center py-4 text-muted small bg-light rounded">
                        No datasets defined.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($paper->datasets as $ds)
                            <div class="border rounded p-3 position-relative bg-white">
                                <div class="row">
                                    @if (!empty($ds['image_path']))
                                        <div class="col-4">
                                            <a href="{{ asset('storage/' . $ds['image_path']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $ds['image_path']) }}"
                                                    class="dataset-preview-img" alt="Sample">
                                            </a>
                                        </div>
                                        <div class="col-8">
                                        @else
                                            <div class="col-12">
                                    @endif

                                    <h6 class="fw-bold mb-1">{{ $ds['name'] }}</h6>

                                    @if (!empty($ds['link']))
                                        <a href="{{ $ds['link'] }}" target="_blank"
                                            class="small text-primary text-decoration-none mb-2 d-inline-block">
                                            <i class="bi bi-link-45deg"></i> Link to source
                                        </a>
                                    @else
                                        <span class="badge bg-light text-secondary border small mb-2">Manual
                                            Collection</span>
                                    @endif

                                    <p class="small text-muted mb-0">{{ $ds['description'] }}</p>
                                </div>
                            </div>

                            @if ($canEdit && !$paper->methodology_finalized)
                                <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                    <button class="btn btn-link text-secondary p-0 small"
                                        onclick='openEditDatasetModal(@json($ds))'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form
                                        action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-dataset"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $ds['id'] }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 small"
                                            onclick="return confirm('Remove this dataset?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="methodology-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calculator-fill me-2 text-info"></i>Formulas & Models
                </h5>
                @if ($canEdit && !$paper->methodology_finalized)
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#addFormulaModal">
                        <i class="bi bi-plus-lg"></i> Add Formula
                    </button>
                @endif
            </div>
            @if (empty($paper->formulas))
                <div class="text-center py-4 text-muted small bg-light rounded">
                    No formulas added.
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach ($paper->formulas as $form)
                        <div class="border rounded p-3 position-relative bg-white">
                            <div id="formula-display-{{ $form['id'] }}" class="mb-2 text-center py-3 bg-light rounded"
                                style="min-height: 50px; font-size: 1.2rem; overflow-x: auto;">
                            </div>

                            <p class="small text-muted mb-1">{{ $form['description'] }}</p>

                            @php
                                $refTitle = 'Unknown Reference';
                                if (!empty($paper->references_data)) {
                                    foreach ($paper->references_data as $ref) {
                                        if (($ref['id'] ?? '') == $form['reference_id']) {
                                            $refTitle = $ref['author'] . ' (' . $ref['year'] . ')';
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if (!empty($form['reference_id']))
                                <div class="small fw-bold text-secondary">
                                    <i class="bi bi-journal-bookmark me-1"></i> Source: {{ $refTitle }}
                                </div>
                            @endif

                            @if ($canEdit && !$paper->methodology_finalized)
                                <form
                                    action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-formula"
                                    method="POST" class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $form['id'] }}">
                                    <button type="submit" class="btn btn-link text-danger p-0 small"
                                        onclick="return confirm('Remove this formula?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-12" style="margin-top: 50px;">
        <div class="methodology-card shadow-sm border-top-0" style="border-top: 4px solid #F7931E;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-code-slash me-2"
                            style="color: #F7931E;"></i>Implementation Code</h5>
                    <p class="text-muted small mb-0">Embed notebooks from Google Colab, GitHub Gists, or other
                        repositories.</p>
                </div>
                @if ($canEdit && !$paper->methodology_finalized)
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addCodeModal">
                        <i class="bi bi-plus-lg"></i> Embed Code
                    </button>
                @endif
            </div>

            @if (empty($paper->code_blocks))
                <div class="text-center py-5 bg-light rounded">
                    <i class="bi bi-file-earmark-code" style="font-size: 2rem; color: #ccc;"></i>
                    <p class="text-muted small mt-2">No code snippets or notebooks embedded.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($paper->code_blocks as $code)
                        <div class="col-lg-6">
                            <div class="code-card">
                                <div class="code-header">
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($code['platform'] == 'colab')
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Colaboratory_SVG_Logo.svg"
                                                width="20">
                                        @elseif($code['platform'] == 'github')
                                            <i class="bi bi-github"></i>
                                        @else
                                            <i class="bi bi-code-square"></i>
                                        @endif
                                        <span class="fw-bold small">{{ $code['title'] }}</span>
                                    </div>
                                    @if ($canEdit && !$paper->methodology_finalized)
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-code"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $code['id'] }}">
                                            <button type="submit"
                                                class="btn btn-link text-secondary p-0 small hover-white">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="code-content">

                                    <div class="ratio ratio-16x9">
                                        {!! $code['embed_code'] !!}
                                    </div>
                                    <div class="mt-2 text-muted small border-top pt-2">
                                        {{ $code['description'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    </div>

    @if ($canEdit)
        <div class="modal fade" id="addDatasetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-dataset"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Data Source</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Dataset Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sample Image (Optional)</label>
                                <input type="file" name="sample_image" class="form-control" accept="image/*">
                                <div class="form-text">Upload a screenshot of the data structure or a sample image.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link (Optional)</label>
                                <input type="url" name="link" class="form-control" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add Dataset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDatasetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/update-dataset"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="item_id" id="edit_ds_id">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Source</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Dataset Name</label>
                                <input type="text" name="name" id="edit_ds_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Update Image (Optional)</label>
                                <input type="file" name="sample_image" class="form-control" accept="image/*">
                                <div class="form-text">Leave blank to keep the current image.</div>
                                <div id="current_image_preview" class="mt-2 d-none">
                                    <small class="text-muted">Current Image:</small><br>
                                    <img src="" id="edit_ds_img_preview"
                                        style="height: 60px; border-radius: 4px; border: 1px solid #ddd;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link (Optional)</label>
                                <input type="url" name="link" id="edit_ds_link" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit_ds_desc" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addFormulaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-formula"
                        method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Formula</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Formula (LaTeX)</label>
                                <input type="text" name="latex" id="latexInput" class="form-control font-monospace"
                                    placeholder="e.g. a^2 + b^2 = c^2" required oninput="renderPreview()">
                                <div id="latexPreview" class="latex-preview mt-2">Preview will appear here</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Reference (From Literature Review)</label>
                                <select name="reference_id" class="form-select">
                                    <option value="">-- No specific reference --</option>
                                    @if (!empty($paper->references_data))
                                        @foreach ($paper->references_data as $ref)
                                            <option value="{{ $ref['id'] ?? '' }}">
                                                {{ $ref['author'] }} ({{ $ref['year'] }}) -
                                                {{ Str::limit($ref['title'], 30) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" class="form-control"
                                    placeholder="e.g. Pythagorean theorem calculation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add Formula</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCodeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-code"
                        method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Embed Code</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="e.g. Data Preprocessing Notebook" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Platform</label>
                                <select name="platform" class="form-select">
                                    <option value="colab">Google Colab</option>
                                    <option value="github">GitHub Gist</option>
                                    <option value="generic">Other / Generic</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Embed Code / Iframe</label>
                                <textarea name="embed_code" class="form-control font-monospace" rows="4"
                                    placeholder="<script src='...'>
                                        or < iframe src = '...' > "></textarea> <
                                            div class = "form-text" > Paste the full embed code provided by the platform(Gist script or Colab iframe). <
                                            /div> <
                                            /div> <
                                            div class = "mb-3" >
                                            <
                                            label class = "form-label" > Description < /label> <
                                            input type = "text"
                                        name = "description"
                                        class = "form-control"
                                        required >
                                            <
                                            /div> <
                                            /div> <
                                            div class = "modal-footer" >
                                            <
                                            button type = "submit"
                                        class = "btn btn-dark" > Embed Code < /button> <
                                            /div> <
                                            /form> <
                                            /div> <
                                            /div> <
                                            /div>
                                        @endif

                                        @if (session('success'))
                                            <
                                            div class = "modal fade custom-modal-backdrop"
                                            id = "statusModal"
                                            tabindex = "-1"
                                            aria - hidden = "true" >
                                                <
                                                div class = "modal-dialog modal-dialog-centered" >

                                                <
                                                div class = "modal-content custom-modal-content type-success text-center p-4" >

                                                <
                                                div class = "modal-body px-4 py-4" >

                                                <
                                                div class = "modal-icon-wrapper mb-4 mx-auto" >
                                                <
                                                i class = "bi bi-check-lg custom-icon" > < /i> <
                                                /div>

                                                <
                                                h4 class = "fw-bold mb-3 heading-text" > Success! < /h4> <
                                                p class = "text-muted mb-4 fs-5" > {{ session('success') }} < /p>

                                                <
                                                button type = "button"
                                            class = "btn btn-custom w-100 py-3 fw-bold shadow-sm"
                                            data - bs - dismiss = "modal" >
                                                CONTINUE <
                                                /button> <
                                                /div>

                                                <
                                                /div> <
                                                /div> <
                                                /div>

                                            @push('scripts')
                                                <
                                                script type = "module" >
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
<script>
    const iframe = document.getElementById('drawioFrame');
    const existingXml = @json($paper->methodology_xml);

    // JS Logic: User can edit ONLY if authorized AND not finalized
    const isFinalized = @json($paper->methodology_finalized);
    const userCanEdit = @json($canEdit);
    const canInteract = userCanEdit && !isFinalized;

    const configuration = {
        compressXml: false,
        ui: 'atlas'
    };

    window.addEventListener('message', function(event) {
        if (event.source !== iframe.contentWindow) return;
        const msg = JSON.parse(event.data);

        if (msg.event === 'configure') {
            iframe.contentWindow.postMessage(JSON.stringify({
                action: 'configure',
                config: configuration
            }), '*');
        } else if (msg.event === 'init') {
            iframe.contentWindow.postMessage(JSON.stringify({
                action: 'load',
                autosave: 0,
                xml: existingXml || ''
            }), '*');
        } else if (msg.event === 'save' || msg.event === 'autosave') {
            // Block saving if not allowed
            if (!canInteract) return;
            saveToBackend(msg.xml);
        }
    });

    function saveToBackend(xmlData) {
        fetch('/{{ $user->profileId }}/paper/{{ $paper->paperId }}/save-methodology', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    xml: xmlData
                })
            })
            .then(response => response.json())
            .then(data => {
                const statusMsg = document.getElementById('statusMessage');
                statusMsg.style.opacity = '1';
                setTimeout(() => {
                    statusMsg.style.opacity = '0';
                }, 3000);
            })
            .catch(error => console.error('Error saving diagram:', error));
    }

    document.addEventListener("DOMContentLoaded", function() {

        // 1. Get Formula Data from PHP
        const formulas = @json($paper->formulas ?? []);

        // 2. Render each formula explicitly
        formulas.forEach(form => {
            const element = document.getElementById('formula-display-' + form.id);

            if (element) {
                // Safety: Remove $$ delimiters if user typed them in the input
                // This ensures we get pure LaTeX: "a^2 + b^2" instead of "$$ a^2 + b^2 $$"
                let rawLatex = form.latex || "";
                rawLatex = rawLatex.replaceAll('$$', '').replaceAll('$', '');

                try {
                    katex.render(rawLatex, element, {
                        throwOnError: false,
                        displayMode: true // This centers it and makes fonts correct size
                    });
                } catch (e) {
                    console.error(e);
                    element.innerHTML = "<span class='text-danger small'>Invalid Formula Format</span>";
                }
            }
        });
    });

    // Render Preview in Modal (Input field logic)
    function renderPreview() {
        const input = document.getElementById('latexInput').value;
        const preview = document.getElementById('latexPreview');

        // Clean input for preview as well
        let cleanInput = input.replaceAll('$$', '').replaceAll('$', '');

        preview.innerHTML = '';

        try {
            katex.render(cleanInput, preview, {
                throwOnError: false,
                displayMode: true
            });
        } catch (e) {
            preview.innerText = "Invalid LaTeX";
        }
    }

    function openEditDatasetModal(dataset) {
        document.getElementById('edit_ds_id').value = dataset.id;
        document.getElementById('edit_ds_name').value = dataset.name;
        document.getElementById('edit_ds_link').value = dataset.link || '';
        document.getElementById('edit_ds_desc').value = dataset.description;

        const previewDiv = document.getElementById('current_image_preview');
        const imgTag = document.getElementById('edit_ds_img_preview');

        if (dataset.image_path) {
            previewDiv.classList.remove('d-none');
            imgTag.src = "/storage/" + dataset.image_path;
        } else {
            previewDiv.classList.add('d-none');
        }

        new bootstrap.Modal(document.getElementById('editDatasetModal')).show();
    }
</script>
@endpush
