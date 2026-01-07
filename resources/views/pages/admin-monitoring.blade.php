@extends('layouts.app')

@section('title', 'Monitoring - Activity Logs')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/admin-monitoring.css') }}">
@endsection

@section('content')
    @include('partials.navbarAdmin')

    @if ($type === 'activityLogs')
        <div class="container monitoring-container">
            <div class="row g-4 mb-5">
                <div class="col-md-8">
                    <div class="mon-card">
                        <div class="mon-card-header">
                            <h6 class="fw-bold mb-0 text-uppercase text-muted small">
                                Activity Volume
                                @if (request('date_from') || request('date_to'))
                                    <span class="badge bg-primary ms-2">Filtered Range</span>
                                @else
                                    <span class="badge bg-light text-dark border ms-2">Last 7 Days</span>
                                @endif
                            </h6>
                        </div>
                        <div class="p-3">
                            <canvas id="activityVolumeChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mon-card">
                        <div class="mon-card-header">
                            <h6 class="fw-bold mb-0 text-uppercase text-muted small">Event Distribution</h6>
                        </div>
                        <div class="p-3 position-relative"
                            style="height: 250px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="filter-sidebar">
                        <div class="mon-card p-4">
                            <h5 class="fw-bold mb-4"><i class="bi bi-funnel-fill me-2"></i>Filter Logs</h5>
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="filter-group mb-3">
                                    <label>User</label>
                                    <select class="form-select form-select-custom" name="user_id">
                                        <option value="">All Users</option>
                                        @foreach ($users as $u)
                                            <option value="{{ $u->id }}"
                                                {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="filter-group mb-3">
                                    <label>Event Type</label>
                                    <select class="form-select form-select-custom" name="type">
                                        <option value="">All Events</option>
                                        <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Login
                                        </option>
                                        <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Logout
                                        </option>
                                    </select>
                                </div>

                                <div class="filter-group mb-3">
                                    <label>From Date</label>
                                    <input type="date" class="form-control form-control-custom" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>

                                <div class="filter-group mb-4">
                                    <label>To Date</label>
                                    <input type="date" class="form-control form-control-custom" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold">Apply Filters</button>
                                    <a href="{{ url()->current() }}" class="btn btn-light text-muted">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="mon-card">
                        <div class="mon-card-header">
                            <div>
                                <h5 class="fw-bold mb-1">Activity Feed</h5>
                                <span class="text-muted small">Showing {{ $activityLogs->firstItem() ?? 0 }} -
                                    {{ $activityLogs->lastItem() ?? 0 }} of {{ $activityLogs->total() }} events</span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            @if ($activityLogs->count() > 0)
                                <div class="timeline-wrapper">
                                    @foreach ($activityLogs as $log)
                                        @php
                                            $typeClass = match ($log->type) {
                                                'login' => 'type-login',
                                                'logout' => 'type-logout',
                                                default => 'type-default',
                                            };

                                            $iconClass = match ($log->type) {
                                                'login' => 'bi-box-arrow-in-right',
                                                'logout' => 'bi-box-arrow-right',
                                                default => 'bi-circle-fill',
                                            };

                                            $badgeClass = match ($log->type) {
                                                'login' => 'badge-login',
                                                'logout' => 'badge-logout',
                                                default => 'badge-default',
                                            };
                                        @endphp

                                        <div class="timeline-item {{ $typeClass }}">
                                            <div class="timeline-icon">
                                                <i class="bi {{ $iconClass }}"></i>
                                            </div>

                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $log->user->name }}&background=random"
                                                            alt="{{ $log->user->name }}" class="user-avatar-small">

                                                        <span class="fw-bold text-dark me-2">{{ $log->user->name }}</span>

                                                        <span
                                                            class="log-badge {{ $badgeClass }}">{{ ucfirst($log->type) }}</span>
                                                    </div>
                                                    <span class="log-time" title="{{ $log->created_at }}">
                                                        {{ $log->created_at->diffForHumans() }}
                                                    </span>
                                                </div>

                                                <p class="mb-0 text-muted small">
                                                    @if ($log->type == 'login')
                                                        User successfully logged into the system.
                                                    @elseif($log->type == 'logout')
                                                        User logged out securely.
                                                    @else
                                                        Performed action: {{ $log->type }}
                                                    @endif
                                                </p>
                                                <div class="text-end mt-1">
                                                    <small class="text-muted"
                                                        style="font-size: 0.7rem;">{{ $log->created_at->format('d M Y, H:i:s') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    {{ $activityLogs->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="text-muted opacity-50 mb-3">
                                        <i class="bi bi-clipboard-data" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted">No Logs Found</h5>
                                    <p class="text-muted small">Try adjusting your filters.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    const rawData = @json($chartData);

                    const labels = [...new Set(rawData.map(item => item.date))].sort();

                    const loginData = labels.map(date => {
                        const entry = rawData.find(r => r.date === date && r.type === 'login');
                        return entry ? entry.count : 0;
                    });

                    const logoutData = labels.map(date => {
                        const entry = rawData.find(r => r.date === date && r.type === 'logout');
                        return entry ? entry.count : 0;
                    });

                    const volumeCtx = document.getElementById('activityVolumeChart').getContext('2d');

                    if (labels.length > 0) {
                        new Chart(volumeCtx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Logins',
                                        data: loginData,
                                        borderColor: '#198754',
                                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        pointRadius: 3, 
                                        pointHoverRadius: 5
                                    },
                                    {
                                        label: 'Logouts',
                                        data: logoutData,
                                        borderColor: '#ffc107',
                                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        pointRadius: 3,
                                        pointHoverRadius: 5
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            borderDash: [2, 4]
                                        },
                                        ticks: {
                                            precision: 0 
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }

                    const totalLogins = loginData.reduce((a, b) => a + b, 0);
                    const totalLogouts = logoutData.reduce((a, b) => a + b, 0);

                    if ((totalLogins + totalLogouts) > 0) {
                        const distCtx = document.getElementById('distributionChart').getContext('2d');
                        new Chart(distCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Logins', 'Logouts'],
                                datasets: [{
                                    data: [totalLogins, totalLogouts],
                                    backgroundColor: ['#198754', '#ffc107'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                },
                                cutout: '70%'
                            }
                        });
                    }
                });
            </script>
        @endpush
    @endif




    @if ($type === 'globalStatistics')
        <link rel="stylesheet" href="{{ asset('libs/leaflet/leaflet.css') }}">

        <div class="container monitoring-container">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Universities</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['universities']) }}</h2>
                        </div>
                        <div class="stats-icon-box bg-blue-soft"><i class="bi bi-building"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Lecturers</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['lecturers']) }}</h2>
                        </div>
                        <div class="stats-icon-box bg-green-soft"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Papers</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['papers']) }}</h2>
                        </div>
                        <div class="stats-icon-box bg-purple-soft"><i class="bi bi-journal-text"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="mon-card p-1 h-100">
                        <div class="card-body p-2 position-relative">
                            <div id="adminMap"></div>
                            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2" style="z-index: 500;">
                                <button class="map-filter-btn active" onclick="setRoleFilter('all')">All</button>
                                <button class="map-filter-btn" onclick="setRoleFilter('university')">Universities</button>
                                <button class="map-filter-btn" onclick="setRoleFilter('lecturer')">Lecturers</button>
                            </div>

                            <div class="position-absolute bottom-0 start-0 m-4 bg-white p-3 rounded-3 shadow-sm"
                                style="z-index: 500; min-width: 250px;">
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Selected Region</span>
                                <h5 class="fw-bold text-primary mb-2" id="selectedRegionName">All Indonesia</h5>
                                <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetMap()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mon-card p-3 h-100">
                        <div class="user-list-wrapper">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-geo-alt me-2"></i>User Directory</h6>
                                <span class="badge bg-primary rounded-pill" id="listCountBadge">0 Found</span>
                            </div>

                            <div class="user-list-container" id="userListGrid">
                            </div>

                            <div id="emptyListState" class="text-center py-5 d-none">
                                <div class="text-muted opacity-50 mb-2"><i class="bi bi-people"
                                        style="font-size: 2rem;"></i></div>
                                <h6 class="fw-bold text-muted small">No users found</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-card h-100">
                        <div class="chart-header">
                            <div>
                                <h5 class="fw-bold mb-1">Growth Trends</h5>
                                <p class="text-muted small mb-0">Registrations & Paper submissions</p>
                            </div>

                            <form action="{{ url()->current() }}" method="GET"
                                class="d-flex gap-2 align-items-center">
                                <input type="hidden" name="type" value="globalStatistics">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">From</span>
                                    <input type="date" class="form-control border-start-0 ps-1" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">To</span>
                                    <input type="date" class="form-control border-start-0 ps-1" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">Filter</button>
                                @if (request('date_from'))
                                    <a href="{{ url()->current() }}?type=globalStatistics"
                                        class="btn btn-light btn-sm border">Reset</a>
                                @endif
                            </form>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="growthTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="chart-card h-100">
                        <div class="chart-header">
                            <h5 class="fw-bold mb-0">User Composition</h5>
                        </div>
                        <div style="height: 250px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="userCompositionChart"></canvas>
                        </div>
                        <div class="text-center mt-3 pt-3 border-top">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h5 class="fw-bold mb-0 text-primary">{{ $stats['universities'] }}</h5>
                                    <small class="text-muted">Universities</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="fw-bold mb-0 text-success">{{ $stats['lecturers'] }}</h5>
                                    <small class="text-muted">Lecturers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('libs/leaflet/leaflet.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const allUsers = @json($mapData);
                const geoJsonUrl = "{{ asset('data/indonesia-prov.geojson') }}";
                let state = {
                    role: 'all',
                    selectedProvince: null
                };

                const gridContainer = document.getElementById('userListGrid');
                const countBadge = document.getElementById('listCountBadge');
                const emptyState = document.getElementById('emptyListState');
                const regionLabel = document.getElementById('selectedRegionName');

                const map = L.map('adminMap').setView([-2.5489, 118.0149], 5);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap',
                    subdomains: 'abcd',
                    maxZoom: 19
                }).addTo(map);

                let geojsonLayer;
                let currentMarkers = [];

                function normalize(str) {
                    if (!str) return '';
                    let name = str.toLowerCase().trim().replace(/\./g, '');
                    const prefixes = ['provinsi ', 'propinsi ', 'daerah istimewa ', 'daerah khusus ibukota ', 'di ',
                        'dki '
                    ];
                    prefixes.forEach(p => {
                        if (name.startsWith(p)) name = name.substring(p.length);
                    });
                    name = name.replace(/nusatenggara/g, 'nusa tenggara');
                    if (name.includes('bangka') && name.includes('belitung')) return 'bangka belitung';
                    if (name.includes('yogya') || name.includes('jogja')) return 'yogyakarta';
                    if (name.includes('jakarta')) return 'jakarta';
                    return name.trim();
                }

                window.applyMapFilters = function() {
                    const filteredData = allUsers.filter(user => {
                        if (state.role !== 'all' && user.role !== state.role) return false;

                        if (state.selectedProvince) {
                            const userProv = normalize(user.province);
                            const targetProv = normalize(state.selectedProvince);
                            if (!userProv.includes(targetProv) && !targetProv.includes(userProv))
                                return false;
                        }
                        return true;
                    });
                    renderMapMarkers(filteredData);
                    renderList(filteredData);
                }

                window.setRoleFilter = function(role) {
                    state.role = role;

                    document.querySelectorAll('.map-filter-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    const clickedButton = event.target.closest('.map-filter-btn');
                    if (clickedButton) {
                        clickedButton.classList.add('active');
                    }

                    if (typeof applyMapFilters === "function") {
                        applyMapFilters();
                    } else if (typeof applyFilters === "function") {
                        applyFilters();
                    }
                }

                window.resetMap = function() {
                    map.setView([-2.5489, 118.0149], 5);
                    state.selectedProvince = null;
                    regionLabel.innerText = "All Indonesia";
                    if (geojsonLayer) geojsonLayer.eachLayer(layer => geojsonLayer.resetStyle(layer));
                    applyMapFilters();
                }

                function onProvinceClick(feature, layer) {
                    const name = getProvinceName(feature);
                    if (name) {
                        state.selectedProvince = name;
                        regionLabel.innerText = name;
                        map.fitBounds(layer.getBounds());
                        applyMapFilters();
                    }
                }

                function renderMapMarkers(filteredData) {
                    currentMarkers.forEach(m => map.removeLayer(m));
                    currentMarkers = [];
                    const counts = {};
                    filteredData.forEach(user => {
                        const pName = normalize(user.province);
                        counts[pName] = (counts[pName] || 0) + 1;
                    });

                    if (geojsonLayer) {
                        geojsonLayer.eachLayer(layer => {
                            const name = getProvinceName(layer.feature);
                            const normName = normalize(name);
                            const count = counts[normName] || 0;

                            layer.setStyle({
                                fillColor: count > 0 ? '#0d6efd' : '#e9ecef',
                                fillOpacity: count > 0 ? 0.2 + (Math.min(count, 50) / 100) : 0.1,
                                color: 'white',
                                weight: 1
                            });

                            if (state.selectedProvince && normalize(state.selectedProvince) === normName) {
                                layer.setStyle({
                                    weight: 2,
                                    color: '#666',
                                    fillOpacity: 0.5
                                });
                            }

                            if (name) layer.bindTooltip(`<strong>${name}</strong><br>${count} Users`, {
                                sticky: true
                            });

                            if (count > 0) {
                                const center = layer.getBounds().getCenter();
                                const icon = L.divIcon({
                                    className: 'count-icon',
                                    html: `<span>${count}</span>`,
                                    iconSize: [30, 30]
                                });
                                const marker = L.marker(center, {
                                    icon: icon
                                }).addTo(map);
                                marker.on('click', (e) => {
                                    L.DomEvent.stopPropagation(e);
                                    onProvinceClick(layer.feature, layer);
                                });
                                currentMarkers.push(marker);
                            }
                        });
                    }
                }

                function renderList(data) {
                    gridContainer.innerHTML = '';
                    countBadge.innerText = data.length + ' Found';
                    if (data.length === 0) {
                        emptyState.classList.remove('d-none');
                        return;
                    }
                    emptyState.classList.add('d-none');

                    const displayData = data.slice(0, 50);
                    displayData.forEach(user => {
                        const avatar =
                            `https://ui-avatars.com/api/?name=${user.name}&background=random&size=64`;
                        let roleBadge = user.role === 'university' ?
                            `<span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1" style="font-size:0.7rem">Univ</span>` :
                            `<span class="badge bg-success-subtle text-success border border-success-subtle me-1" style="font-size:0.7rem">Lecturer</span>`;

                        const html = `
                        <div class="card border-0 shadow-sm mb-2">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <img src="${avatar}" class="rounded-circle border" width="40" height="40">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold mb-1 text-truncate" style="font-size: 0.9rem;">
                                        <a href="/${user.profile_id}/overview" class="text-decoration-none text-dark stretched-link">${user.name}</a>
                                    </h6>
                                    <div class="mb-1">${roleBadge}</div>
                                    <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>${user.province}</div>
                                </div>
                            </div>
                        </div>`;
                        gridContainer.innerHTML += html;
                    });
                    if (data.length > 50) gridContainer.innerHTML +=
                        `<div class="text-center py-2 small text-muted">And ${data.length - 50} more...</div>`;
                }

                fetch(geoJsonUrl).then(res => res.json()).then(data => {
                    geojsonLayer = L.geoJson(data, {
                        style: {
                            fillColor: '#e9ecef',
                            weight: 1,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.1
                        },
                        onEachFeature: function(feature, layer) {
                            layer.on({
                                click: function(e) {
                                    onProvinceClick(feature, e.target);
                                }
                            });
                        }
                    }).addTo(map);
                    applyMapFilters();
                });

                function getProvinceName(feature) {
                    if (feature && feature.properties) return feature.properties.Propinsi || feature.properties
                        .PROVINSI || feature.properties.name || null;
                    return null;
                }


                const chartDataRaw = @json($chartData);
                const totalStats = @json($stats);

                const allDates = new Set([
                    ...chartDataRaw.universities.map(d => d.date),
                    ...chartDataRaw.lecturers.map(d => d.date),
                    ...chartDataRaw.papers.map(d => d.date)
                ]);
                const labels = Array.from(allDates).sort();

                const getDataSeries = (source) => labels.map(date => {
                    const found = source.find(d => d.date === date);
                    return found ? found.count : 0;
                });

                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#64748b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#94a3b8',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#f1f5f9',
                                borderDash: [4, 4],
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    size: 11
                                },
                                maxTicksLimit: 7
                            }
                        }
                    }
                };

                const ctxGrowth = document.getElementById('growthTrendChart').getContext('2d');

                let gradUniv = ctxGrowth.createLinearGradient(0, 0, 0, 300);
                gradUniv.addColorStop(0, 'rgba(13, 110, 253, 0.1)');
                gradUniv.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

                let gradPaper = ctxGrowth.createLinearGradient(0, 0, 0, 300);
                gradPaper.addColorStop(0, 'rgba(111, 66, 193, 0.1)');
                gradPaper.addColorStop(1, 'rgba(111, 66, 193, 0.0)');

                new Chart(ctxGrowth, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Universities',
                                data: getDataSeries(chartDataRaw.universities),
                                borderColor: '#0d6efd',
                                backgroundColor: gradUniv,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 0,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Lecturers',
                                data: getDataSeries(chartDataRaw.lecturers),
                                borderColor: '#198754',
                                backgroundColor: 'transparent',
                                borderDash: [5, 5],
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 0,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Papers',
                                data: getDataSeries(chartDataRaw.papers),
                                borderColor: '#6f42c1',
                                backgroundColor: gradPaper,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#6f42c1'
                            }
                        ]
                    },
                    options: commonOptions
                });

                const ctxComp = document.getElementById('userCompositionChart').getContext('2d');
                new Chart(ctxComp, {
                    type: 'doughnut',
                    data: {
                        labels: ['Universities', 'Lecturers'],
                        datasets: [{
                            data: [totalStats.universities, totalStats.lecturers],
                            backgroundColor: ['#0d6efd', '#198754'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: commonOptions.plugins.tooltip
                        },
                        cutout: '75%'
                    }
                });

            });
        </script>
    @endif
@endsection
