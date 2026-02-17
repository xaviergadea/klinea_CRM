@extends('layouts.admin')

@section('title', 'Leads')

@section('page-title', 'Leads')

@section('breadcrumb')
    <li class="breadcrumb-item active">Leads</li>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('leads.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nuevo</option>
                                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contactado</option>
                                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Cualificado</option>
                                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Propuesta</option>
                                        <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Ganado</option>
                                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Perdido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="source">Fuente</label>
                                    <select name="source" id="source" class="form-control">
                                        <option value="">-- Todas --</option>
                                        <option value="web" {{ request('source') == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>Referencia</option>
                                        <option value="linkedin" {{ request('source') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                        <option value="cold_call" {{ request('source') == 'cold_call' ? 'selected' : '' }}>Llamada en frío</option>
                                        <option value="event" {{ request('source') == 'event' ? 'selected' : '' }}>Evento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search mr-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Leads --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Leads</h3>
                    <div class="card-tools">
                        <a href="{{ route('leads.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Nuevo Lead
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="leadsTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Empresa</th>
                                <th>Email</th>
                                <th>Fuente</th>
                                <th>Estado</th>
                                <th>Asignado a</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                                <tr>
                                    <td>{{ $lead->name }}</td>
                                    <td>{{ $lead->company ?? '-' }}</td>
                                    <td>{{ $lead->email ?? '-' }}</td>
                                    <td>
                                        @php
                                            $sourceBadges = [
                                                'web' => 'info',
                                                'referral' => 'success',
                                                'linkedin' => 'primary',
                                                'cold_call' => 'warning',
                                                'event' => 'secondary',
                                            ];
                                            $sourceLabels = [
                                                'web' => 'Web',
                                                'referral' => 'Referencia',
                                                'linkedin' => 'LinkedIn',
                                                'cold_call' => 'Llamada en frío',
                                                'event' => 'Evento',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $sourceBadges[$lead->source] ?? 'secondary' }}">
                                            {{ $sourceLabels[$lead->source] ?? ucfirst($lead->source) }}
                                        </span>
                                    </td>
                                    <td>
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
                                        @endphp
                                        <span class="badge badge-{{ $statusBadges[$lead->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$lead->status] ?? ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $lead->assignedTo->name ?? 'Sin asignar' }}</td>
                                    <td>{{ $lead->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning" title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                                  onsubmit="return confirm('¿Está seguro de que desea eliminar este lead?');"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Eliminar">
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
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#leadsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            order: [[6, 'desc']],
            columnDefs: [
                { orderable: false, targets: [7] }
            ],
            pageLength: 25
        });
    });
</script>
@endpush
