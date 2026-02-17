@extends('layouts.admin')

@section('title', 'Oportunidad: ' . $opportunity->title)

@section('page-title', $opportunity->title)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('opportunities.index') }}">Oportunidades</a></li>
<li class="breadcrumb-item active">{{ $opportunity->title }}</li>
@endsection

@section('content')
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

{{-- Opportunity Info Card --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Información de la Oportunidad
                    <span class="badge bg-{{ $stageBadges[$opportunity->stage] ?? 'secondary' }} ms-2">{{ $stageLabels[$opportunity->stage] ?? $opportunity->stage }}</span>
                </h5>
                <div>
                    <a href="{{ route('opportunities.edit', $opportunity) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <form action="{{ route('opportunities.destroy', $opportunity) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta oportunidad?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Título:</strong>
                        <p class="mb-0">{{ $opportunity->title }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Cliente:</strong>
                        <p class="mb-0">
                            @if($opportunity->client)
                                <a href="{{ route('clients.show', $opportunity->client) }}">{{ $opportunity->client->company }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Asignado a:</strong>
                        <p class="mb-0">{{ $opportunity->assignedTo->name ?? 'Sin asignar' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Valor:</strong>
                        <p class="mb-0 fs-5 fw-bold text-success">{{ number_format($opportunity->value, 2, ',', '.') }} &euro;</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Probabilidad:</strong>
                        <p class="mb-0">
                            <span class="fs-5 fw-bold">{{ $opportunity->probability }}%</span>
                        </p>
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $opportunity->probability }}%"
                                 aria-valuenow="{{ $opportunity->probability }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Valor Ponderado:</strong>
                        <p class="mb-0 fs-5">{{ number_format($opportunity->value * $opportunity->probability / 100, 2, ',', '.') }} &euro;</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Etapa:</strong>
                        <p class="mb-0">
                            <span class="badge bg-{{ $stageBadges[$opportunity->stage] ?? 'secondary' }} fs-6">{{ $stageLabels[$opportunity->stage] ?? $opportunity->stage }}</span>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Fecha de Cierre Prevista:</strong>
                        <p class="mb-0">{{ $opportunity->expected_close_date ? \Carbon\Carbon::parse($opportunity->expected_close_date)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Fecha de Creación:</strong>
                        <p class="mb-0">{{ $opportunity->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($opportunity->description)
                <div class="row">
                    <div class="col-12 mb-3">
                        <strong>Descripción:</strong>
                        <p class="mb-0">{{ $opportunity->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Related Budgets --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Presupuestos Relacionados</h5>
                <a href="{{ route('budgets.create', ['opportunity_id' => $opportunity->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Nuevo Presupuesto
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opportunity->budgets ?? [] as $budget)
                            <tr>
                                <td><a href="{{ route('budgets.show', $budget) }}">{{ $budget->reference }}</a></td>
                                <td>{{ number_format($budget->total, 2, ',', '.') }} &euro;</td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'draft' => 'secondary',
                                            'sent' => 'info',
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Borrador',
                                            'sent' => 'Enviado',
                                            'accepted' => 'Aceptado',
                                            'rejected' => 'Rechazado',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusBadges[$budget->status] ?? 'secondary' }}">{{ $statusLabels[$budget->status] ?? $budget->status }}</span>
                                </td>
                                <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('budgets.show', $budget) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay presupuestos relacionados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Activity Timeline --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actividad Reciente</h5>
            </div>
            <div class="card-body">
                @if(isset($activities) && $activities->count() > 0)
                <div class="timeline">
                    @foreach($activities as $activity)
                    <div class="timeline-item mb-3 d-flex">
                        <div class="timeline-icon me-3">
                            <span class="badge bg-primary rounded-circle p-2">
                                <i class="fas fa-{{ $activity->icon ?? 'clock' }}"></i>
                            </span>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-1"><strong>{{ $activity->description }}</strong></p>
                            <small class="text-muted">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                                @if($activity->user)
                                    - {{ $activity->user->name }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">No hay actividad registrada</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
