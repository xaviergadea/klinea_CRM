@extends('layouts.admin')

@section('title', $lead->name)

@section('page-title', $lead->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">{{ $lead->name }}</li>
@endsection

@section('content')
    @php
        $statusBadges = [
            'new' => 'info',
            'contacted' => 'primary',
            'qualified' => 'success',
            'proposal' => 'warning',
            'won' => 'dark',
            'lost' => 'danger',
        ];
        $statusLabels = [
            'new' => 'Nuevo',
            'contacted' => 'Contactado',
            'qualified' => 'Cualificado',
            'proposal' => 'Propuesta',
            'won' => 'Ganado',
            'lost' => 'Perdido',
        ];
        $sourceLabels = [
            'web' => 'Web',
            'referral' => 'Referencia',
            'linkedin' => 'LinkedIn',
            'cold_call' => 'Llamada en frío',
            'event' => 'Evento',
        ];
    @endphp

    <div class="row">
        {{-- Información del Lead --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user mr-1"></i> Información del Lead</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $statusBadges[$lead->status] ?? 'secondary' }} badge-lg" style="font-size: 0.9rem;">
                            {{ $statusLabels[$lead->status] ?? ucfirst($lead->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">Nombre</th>
                            <td>{{ $lead->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                @if($lead->email)
                                    <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>
                                @if($lead->phone)
                                    <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <td>{{ $lead->company ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fuente</th>
                            <td>{{ $sourceLabels[$lead->source] ?? ucfirst($lead->source ?? '-') }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                <span class="badge badge-{{ $statusBadges[$lead->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$lead->status] ?? ucfirst($lead->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de creación</th>
                            <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última actualización</th>
                            <td>{{ $lead->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($lead->notes)
                            <tr>
                                <th>Notas</th>
                                <td>{!! nl2br(e($lead->notes)) !!}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel lateral --}}
        <div class="col-md-4">
            {{-- Asignado a --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Asignado a</h3>
                </div>
                <div class="card-body text-center">
                    @if($lead->assignedTo)
                        <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                        <h5>{{ $lead->assignedTo->name }}</h5>
                        <p class="text-muted">{{ $lead->assignedTo->email }}</p>
                    @else
                        <i class="fas fa-user-slash fa-3x text-muted mb-2"></i>
                        <p class="text-muted">Sin asignar</p>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs mr-1"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-pencil-alt mr-1"></i> Editar Lead
                    </a>
                    <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                          onsubmit="return confirm('¿Está seguro de que desea eliminar este lead? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block mb-2">
                            <i class="fas fa-trash mr-1"></i> Eliminar Lead
                        </button>
                    </form>
                    <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline de Actividades --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-stream mr-1"></i> Historial de Actividades</h3>
                </div>
                <div class="card-body">
                    @if($lead->activities && $lead->activities->count() > 0)
                        <div class="timeline">
                            @php
                                $activityIcons = [
                                    'call' => ['fas fa-phone', 'bg-info'],
                                    'email' => ['fas fa-envelope', 'bg-primary'],
                                    'meeting' => ['fas fa-calendar-check', 'bg-success'],
                                    'note' => ['fas fa-sticky-note', 'bg-warning'],
                                    'task' => ['fas fa-tasks', 'bg-secondary'],
                                    'status_change' => ['fas fa-exchange-alt', 'bg-dark'],
                                ];
                                $currentDate = null;
                            @endphp

                            @foreach($lead->activities->sortByDesc('created_at') as $activity)
                                @php
                                    $activityDate = $activity->created_at->format('d/m/Y');
                                    $iconData = $activityIcons[$activity->type] ?? ['fas fa-circle', 'bg-secondary'];
                                @endphp

                                @if($currentDate !== $activityDate)
                                    @php $currentDate = $activityDate; @endphp
                                    <div class="time-label">
                                        <span class="bg-primary">{{ $activityDate }}</span>
                                    </div>
                                @endif

                                <div>
                                    <i class="{{ $iconData[0] }} {{ $iconData[1] }}"></i>
                                    <div class="timeline-item">
                                        <span class="time">
                                            <i class="fas fa-clock"></i> {{ $activity->created_at->format('H:i') }}
                                        </span>
                                        <h3 class="timeline-header">
                                            <strong>{{ $activity->subject }}</strong>
                                            <span class="text-muted ml-2">- {{ $activity->user->name ?? 'Sistema' }}</span>
                                        </h3>
                                        @if($activity->description)
                                            <div class="timeline-body">
                                                {!! nl2br(e($activity->description)) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No hay actividades registradas para este lead.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
