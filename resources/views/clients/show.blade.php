@extends('layouts.admin')

@section('title', 'Cliente: ' . $client->company)

@section('page-title', $client->company)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
<li class="breadcrumb-item active">{{ $client->company }}</li>
@endsection

@section('content')
{{-- Client Info Card --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Información del Cliente</h5>
                <div>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este cliente?')">
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
                        <strong>Nombre de contacto:</strong>
                        <p class="mb-0">{{ $client->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0"><a href="mailto:{{ $client->email }}">{{ $client->email }}</a></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Teléfono:</strong>
                        <p class="mb-0">{{ $client->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Empresa:</strong>
                        <p class="mb-0">{{ $client->company }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>NIF/CIF:</strong>
                        <p class="mb-0">{{ $client->nif ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Sitio Web:</strong>
                        <p class="mb-0">
                            @if($client->website)
                                <a href="{{ $client->website }}" target="_blank">{{ $client->website }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Dirección:</strong>
                        <p class="mb-0">{{ $client->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Ciudad:</strong>
                        <p class="mb-0">{{ $client->city ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Código Postal:</strong>
                        <p class="mb-0">{{ $client->postal_code ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>País:</strong>
                        <p class="mb-0">{{ $client->country ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Sector:</strong>
                        <p class="mb-0">{{ $client->sector ? ucfirst($client->sector) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Asignado a:</strong>
                        <p class="mb-0">{{ $client->assignedTo->name ?? 'Sin asignar' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Opportunities & Budgets --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Oportunidades</h5>
                <a href="{{ route('opportunities.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Valor</th>
                                <th>Etapa</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->opportunities ?? [] as $opportunity)
                            <tr>
                                <td><a href="{{ route('opportunities.show', $opportunity) }}">{{ $opportunity->title }}</a></td>
                                <td>{{ number_format($opportunity->value, 2, ',', '.') }} &euro;</td>
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
                                <td>{{ $opportunity->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay oportunidades</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Presupuestos</h5>
                <a href="{{ route('budgets.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->budgets ?? [] as $budget)
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay presupuestos</td>
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
