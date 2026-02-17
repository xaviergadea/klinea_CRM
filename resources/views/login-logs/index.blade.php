@extends('layouts.admin')

@section('title', 'Registro de Accesos')

@section('page-title', 'Registro de Accesos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Registro de Accesos</li>
@endsection

@section('content')
    {{-- KPI Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalLogins) }}</h3>
                    <p>Accesos Exitosos</p>
                </div>
                <div class="icon"><i class="fas fa-sign-in-alt"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($failedLogins) }}</h3>
                    <p>Accesos Fallidos</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $uniqueIps }}</h3>
                    <p>IPs Únicas</p>
                </div>
                <div class="icon"><i class="fas fa-network-wired"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $todayLogins }}</h3>
                    <p>Accesos Hoy</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-day"></i></div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header py-2">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Accesos por Día</h3>
                </div>
                <div class="card-body py-2">
                    <canvas id="dailyChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header py-2">
                    <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Distribución por Hora</h3>
                </div>
                <div class="card-body py-2">
                    <canvas id="hourChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('login-logs.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user_id">Usuario</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">-- Todos --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">-- Todos --</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Exitoso</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">Desde</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">Hasta</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Filtrar</button>
                                <a href="{{ route('login-logs.index') }}" class="btn btn-secondary ml-1"><i class="fas fa-times mr-1"></i> Limpiar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Historial de Accesos</h3>
            <div class="card-tools">
                <span class="badge badge-info">{{ $logs->total() }} registros</span>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>IP</th>
                        <th>Navegador</th>
                        <th>Plataforma</th>
                        <th>Duración</th>
                        <th>Cierre Sesión</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="{{ $log->status === 'failed' ? 'table-danger' : '' }}">
                            <td>
                                <span title="{{ $log->logged_in_at->format('d/m/Y H:i:s') }}">
                                    {{ $log->logged_in_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-user-circle mr-1 text-muted"></i>
                                {{ $log->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Exitoso</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Fallido</span>
                                @endif
                            </td>
                            <td><code>{{ $log->ip_address }}</code></td>
                            <td>
                                @php
                                    $browserIcon = match(true) {
                                        str_contains($log->browser ?? '', 'Chrome') => 'fab fa-chrome',
                                        str_contains($log->browser ?? '', 'Firefox') => 'fab fa-firefox',
                                        str_contains($log->browser ?? '', 'Safari') => 'fab fa-safari',
                                        str_contains($log->browser ?? '', 'Edge') => 'fab fa-edge',
                                        default => 'fas fa-globe',
                                    };
                                @endphp
                                <i class="{{ $browserIcon }} mr-1"></i>{{ $log->browser ?? '-' }}
                            </td>
                            <td>
                                @php
                                    $platformIcon = match(true) {
                                        str_contains($log->platform ?? '', 'Windows') => 'fab fa-windows',
                                        str_contains($log->platform ?? '', 'macOS') => 'fab fa-apple',
                                        str_contains($log->platform ?? '', 'Android') => 'fab fa-android',
                                        str_contains($log->platform ?? '', 'iOS') => 'fab fa-apple',
                                        default => 'fas fa-desktop',
                                    };
                                @endphp
                                <i class="{{ $platformIcon }} mr-1"></i>{{ $log->platform ?? '-' }}
                            </td>
                            <td>
                                @if($log->logged_out_at && $log->status === 'success')
                                    {{ $log->duration }}
                                @elseif($log->status === 'success')
                                    <span class="text-muted"><i class="fas fa-clock mr-1"></i>Sesión expirada</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($log->logged_out_at)
                                    {{ $log->logged_out_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay registros de acceso.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $logs->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Accesos por Día - Line
    const dailyData = @json($loginsPerDay);
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.day.substring(5)),
            datasets: [{
                label: 'Accesos',
                data: dailyData.map(d => d.total),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    // Distribución por Hora - Bar
    const hourData = @json($loginsPerHour);
    const allHours = Array.from({length: 24}, (_, i) => i);
    const hourMap = {};
    hourData.forEach(h => hourMap[h.hour] = h.total);
    new Chart(document.getElementById('hourChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: allHours.map(h => h + ':00'),
            datasets: [{
                label: 'Accesos',
                data: allHours.map(h => hourMap[h] || 0),
                backgroundColor: allHours.map(h => (h >= 8 && h <= 18) ? '#28a745' : '#6c757d'),
                borderRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
