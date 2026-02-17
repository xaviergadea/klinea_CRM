@extends('layouts.admin')

@section('title', 'Registro de Actividad')

@section('page-title', 'Registro de Actividad')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Actividades</li>
@endsection

@section('content')
    {{-- Filter Bar --}}
    <div class="card card-outline card-primary mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('activities.index') }}" class="row align-items-end">
                <div class="col-md-3">
                    <label for="type">Tipo</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">-- Todos los tipos --</option>
                        <option value="call" {{ request('type') == 'call' ? 'selected' : '' }}>Llamada</option>
                        <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Reunión</option>
                        <option value="note" {{ request('type') == 'note' ? 'selected' : '' }}>Nota</option>
                        <option value="task" {{ request('type') == 'task' ? 'selected' : '' }}>Tarea</option>
                        <option value="status_change" {{ request('type') == 'status_change' ? 'selected' : '' }}>Cambio de estado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="user_id">Usuario</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">-- Todos los usuarios --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                    <a href="{{ route('activities.index') }}" class="btn btn-default">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Activity Timeline --}}
    <div class="timeline">
        @php $currentDate = null; @endphp

        @forelse($activities as $activity)
            @php
                $activityDate = $activity->created_at->format('Y-m-d');
            @endphp

            @if($currentDate !== $activityDate)
                @php $currentDate = $activityDate; @endphp
                {{-- Date Header --}}
                <div class="time-label">
                    <span class="bg-primary">
                        {{ $activity->created_at->format('d/m/Y') }}
                    </span>
                </div>
            @endif

            @php
                switch($activity->type) {
                    case 'call':
                        $icon = 'fa-phone';
                        $bgClass = 'bg-info';
                        break;
                    case 'email':
                        $icon = 'fa-envelope';
                        $bgClass = 'bg-primary';
                        break;
                    case 'meeting':
                        $icon = 'fa-calendar';
                        $bgClass = 'bg-success';
                        break;
                    case 'note':
                        $icon = 'fa-sticky-note';
                        $bgClass = 'bg-warning';
                        break;
                    case 'task':
                        $icon = 'fa-tasks';
                        $bgClass = 'bg-secondary';
                        break;
                    case 'status_change':
                        $icon = 'fa-exchange-alt';
                        $bgClass = 'bg-dark';
                        break;
                    default:
                        $icon = 'fa-circle';
                        $bgClass = 'bg-gray';
                        break;
                }
            @endphp

            <div>
                <i class="fas {{ $icon }} {{ $bgClass }}"></i>
                <div class="timeline-item">
                    <span class="time">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $activity->created_at->format('H:i') }}
                    </span>
                    <h3 class="timeline-header">
                        <strong>{{ $activity->subject }}</strong>
                    </h3>

                    @if($activity->description)
                        <div class="timeline-body">
                            {{ $activity->description }}
                        </div>
                    @endif

                    <div class="timeline-footer">
                        <small class="text-muted">
                            <i class="fas fa-user mr-1"></i>
                            {{ $activity->user->name ?? 'N/A' }}

                            @if($activity->loggable)
                                <span class="mx-2">|</span>
                                @if($activity->loggable_type === 'App\\Models\\Lead')
                                    <i class="fas fa-user-tie mr-1"></i>
                                    <a href="{{ route('leads.show', $activity->loggable_id) }}">
                                        Lead: {{ $activity->loggable->name ?? $activity->loggable->company ?? 'N/A' }}
                                    </a>
                                @elseif($activity->loggable_type === 'App\\Models\\Client')
                                    <i class="fas fa-building mr-1"></i>
                                    <a href="{{ route('clients.show', $activity->loggable_id) }}">
                                        Cliente: {{ $activity->loggable->name ?? $activity->loggable->company ?? 'N/A' }}
                                    </a>
                                @elseif($activity->loggable_type === 'App\\Models\\Opportunity')
                                    <i class="fas fa-handshake mr-1"></i>
                                    <a href="{{ route('opportunities.show', $activity->loggable_id) }}">
                                        Oportunidad: {{ $activity->loggable->name ?? 'N/A' }}
                                    </a>
                                @elseif($activity->loggable_type === 'App\\Models\\Budget')
                                    <i class="fas fa-file-invoice-dollar mr-1"></i>
                                    <a href="{{ route('budgets.show', $activity->loggable_id) }}">
                                        Presupuesto: {{ $activity->loggable->reference ?? 'N/A' }}
                                    </a>
                                @else
                                    {{ class_basename($activity->loggable_type) }}:
                                    {{ $activity->loggable->name ?? $activity->loggable_id }}
                                @endif
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div>
                <i class="fas fa-info-circle bg-info"></i>
                <div class="timeline-item">
                    <div class="timeline-body text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                        No se han encontrado actividades con los filtros seleccionados.
                    </div>
                </div>
            </div>
        @endforelse

        {{-- Timeline end --}}
        <div>
            <i class="fas fa-clock bg-gray"></i>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $activities->appends(request()->query())->links() }}
    </div>
@endsection
