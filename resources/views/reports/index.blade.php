@extends('layouts.admin')

@section('title', 'Informes')

@section('page-title', 'Informes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Informes</li>
@endsection

@section('content')
    {{-- Row 1: KPI Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase mb-1">Tasa de Conversi&oacute;n</h6>
                    <p class="display-6 fw-bold mb-0">{{ number_format($conversionRate, 1, ',', '.') }}%</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase mb-1">Oportunidades Ganadas</h6>
                    <p class="display-6 fw-bold mb-0">{{ $winLossRatio['wins'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase mb-1">Oportunidades Perdidas</h6>
                    <p class="display-6 fw-bold mb-0">{{ $winLossRatio['losses'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase mb-1">Ratio Win/Loss</h6>
                    <p class="display-6 fw-bold mb-0">{{ number_format($winLossRatio['ratio'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Pipeline Summary --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-funnel-dollar me-2"></i>Resumen del Pipeline</h5>
                </div>
                <div class="card-body p-0">
                    @php
                        $stageColors = [
                            'prospecting' => 'secondary',
                            'qualification' => 'info',
                            'proposal' => 'primary',
                            'negotiation' => 'warning',
                            'closed_won' => 'success',
                            'closed_lost' => 'danger',
                        ];
                        $stageLabels = [
                            'prospecting' => 'Prospección',
                            'qualification' => 'Cualificación',
                            'proposal' => 'Propuesta',
                            'negotiation' => 'Negociación',
                            'closed_won' => 'Ganada',
                            'closed_lost' => 'Perdida',
                        ];
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Etapa</th>
                                    <th class="text-center">N&ordm; Oportunidades</th>
                                    <th class="text-end">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCount = 0; $totalValue = 0; @endphp
                                @foreach($pipelineData as $stage)
                                    @php
                                        $totalCount += $stage->count;
                                        $totalValue += $stage->total;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $stageColors[$stage->stage] ?? 'secondary' }}">
                                                {!! $stageLabels[$stage->stage] ?? ucfirst($stage->stage) !!}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $stage->count }}</td>
                                        <td class="text-end">{{ number_format($stage->total, 2, ',', '.') }} &euro;</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-dark fw-bold">
                                    <td>TOTAL</td>
                                    <td class="text-center">{{ $totalCount }}</td>
                                    <td class="text-end">{{ number_format($totalValue, 2, ',', '.') }} &euro;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Charts --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Ingresos por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Pipeline por Etapa</h5>
                </div>
                <div class="card-body">
                    <canvas id="pipelineChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Top Performers --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top Comerciales</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 80px">Posici&oacute;n</th>
                                    <th>Comercial</th>
                                    <th class="text-center">Oportunidades Ganadas</th>
                                    <th class="text-end">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPerformers as $index => $performer)
                                    <tr class="{{ $index === 0 ? 'table-warning' : '' }}">
                                        <td class="text-center">
                                            @if($index === 0)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="fas fa-crown me-1"></i>1
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark fs-6">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ $performer->name }}</td>
                                        <td class="text-center">{{ $performer->won_count }}</td>
                                        <td class="text-end">{{ number_format($performer->total_value, 2, ',', '.') }} &euro;</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Monthly Revenue Bar Chart
            const monthlyData = @json($monthlyRevenue);
            const monthLabels = monthlyData.map(function (item) { return item.month; });
            const monthTotals = monthlyData.map(function (item) { return item.total; });

            new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Ingresos (\u20AC)',
                        data: monthTotals,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });
                                }
                            }
                        }
                    }
                }
            });

            // Pipeline Doughnut Chart
            const pipelineData = @json($pipelineData);
            const stageLabels = {
                'prospecting': 'Prospecci\u00f3n',
                'qualification': 'Cualificaci\u00f3n',
                'proposal': 'Propuesta',
                'negotiation': 'Negociaci\u00f3n',
                'closed_won': 'Ganada',
                'closed_lost': 'Perdida'
            };
            const stageChartColors = {
                'prospecting': '#6c757d',
                'qualification': '#0dcaf0',
                'proposal': '#0d6efd',
                'negotiation': '#ffc107',
                'closed_won': '#198754',
                'closed_lost': '#dc3545'
            };

            const pipelineLabels = pipelineData.map(function (item) {
                return stageLabels[item.stage] || item.stage;
            });
            const pipelineTotals = pipelineData.map(function (item) { return item.total; });
            const pipelineColors = pipelineData.map(function (item) {
                return stageChartColors[item.stage] || '#adb5bd';
            });

            new Chart(document.getElementById('pipelineChart'), {
                type: 'doughnut',
                data: {
                    labels: pipelineLabels,
                    datasets: [{
                        data: pipelineTotals,
                        backgroundColor: pipelineColors,
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || '';
                                    var value = context.parsed.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' });
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
