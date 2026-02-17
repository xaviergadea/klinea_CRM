@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    {{-- Row 1: KPI Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalLeads }}</h3>
                    <p>Total Leads</p>
                </div>
                <div class="icon">
                    <i class="fas fa-funnel-dollar"></i>
                </div>
                <a href="{{ route('leads.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeClients }}</h3>
                    <p>Clientes Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('clients.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $openOpportunities }}</h3>
                    <p>Oportunidades Abiertas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <a href="{{ route('opportunities.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>€{{ number_format($revenuePipeline, 0, ',', '.') }}</h3>
                    <p>Pipeline Ingresos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <a href="{{ route('opportunities.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Row 2: Charts --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Oportunidades por Etapa</h3>
                </div>
                <div class="card-body">
                    <canvas id="stageChart" style="min-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Ingresos Mensuales</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Tables --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Actividad Reciente</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Asunto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td title="{{ $activity->created_at->format('d/m/Y H:i') }}">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                    <td>{{ $activity->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $typeBadges = [
                                                'call' => 'info',
                                                'email' => 'primary',
                                                'meeting' => 'success',
                                                'note' => 'warning',
                                                'task' => 'secondary',
                                                'status_change' => 'dark',
                                            ];
                                            $badge = $typeBadges[$activity->type] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $activity->type)) }}</span>
                                    </td>
                                    <td>{{ $activity->subject }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay actividades recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy mr-1"></i> Top Oportunidades</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Valor</th>
                                <th>Etapa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topOpportunities as $opportunity)
                                <tr>
                                    <td>{{ $opportunity->title }}</td>
                                    <td>€{{ number_format($opportunity->value, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($opportunity->stage) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay oportunidades.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Oportunidades por Etapa - Bar Chart
        const stageData = @json($stageData);
        const stageColors = [
            '#007bff', '#28a745', '#ffc107', '#dc3545',
            '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
        ];

        new Chart(document.getElementById('stageChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: stageData.map(item => item.stage),
                datasets: [{
                    label: 'Oportunidades',
                    data: stageData.map(item => item.count),
                    backgroundColor: stageData.map((item, i) => stageColors[i % stageColors.length]),
                    borderColor: stageData.map((item, i) => stageColors[i % stageColors.length]),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Ingresos Mensuales - Line Chart
        const monthlyRevenue = @json($monthlyRevenue);

        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyRevenue.map(item => item.month),
                datasets: [{
                    label: 'Ingresos (€)',
                    data: monthlyRevenue.map(item => item.total),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#007bff',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value.toLocaleString('es-ES');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
