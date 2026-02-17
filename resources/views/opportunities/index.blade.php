@extends('layouts.admin')

@section('title', 'Oportunidades')

@section('page-title', 'Oportunidades')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Oportunidades</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"></h4>
            <a href="{{ route('opportunities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Oportunidad
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="opportunitiesTable" class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Cliente</th>
                                <th>Valor (&euro;)</th>
                                <th>Probabilidad (%)</th>
                                <th>Etapa</th>
                                <th>Asignado a</th>
                                <th>Cierre Previsto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($opportunities as $opportunity)
                            <tr>
                                <td>{{ $opportunity->title }}</td>
                                <td>
                                    @if($opportunity->client)
                                        <a href="{{ route('clients.show', $opportunity->client) }}">{{ $opportunity->client->company }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ number_format($opportunity->value, 2, ',', '.') }} &euro;</td>
                                <td>{{ $opportunity->probability }}%</td>
                                <td>
                                    @php
                                        $stageBadges = [
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
                                    <span class="badge bg-{{ $stageBadges[$opportunity->stage] ?? 'secondary' }}">{{ $stageLabels[$opportunity->stage] ?? $opportunity->stage }}</span>
                                </td>
                                <td>{{ $opportunity->assignedTo->name ?? 'Sin asignar' }}</td>
                                <td>{{ $opportunity->expected_close_date ? \Carbon\Carbon::parse($opportunity->expected_close_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('opportunities.show', $opportunity) }}" class="btn btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('opportunities.edit', $opportunity) }}" class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('opportunities.destroy', $opportunity) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta oportunidad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#opportunitiesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            order: [[6, 'asc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    });
</script>
@endpush
