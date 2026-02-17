@extends('layouts.admin')

@section('title', 'Presupuestos')

@section('page-title', 'Presupuestos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Presupuestos</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Listado de Presupuestos</h4>
        <a href="{{ route('budgets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nuevo Presupuesto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="budgetsTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Referencia</th>
                            <th>Cliente</th>
                            <th>Total (&euro;)</th>
                            <th>Estado</th>
                            <th>V&aacute;lido Hasta</th>
                            <th>Asignado a</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>
                                    <a href="{{ route('budgets.show', $budget) }}" class="fw-semibold text-decoration-none">
                                        {{ $budget->reference }}
                                    </a>
                                </td>
                                <td>{{ $budget->client->name ?? '-' }}</td>
                                <td class="text-end">{{ number_format($budget->total, 2, ',', '.') }} &euro;</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'sent' => 'info',
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                            'expired' => 'dark',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Borrador',
                                            'sent' => 'Enviado',
                                            'accepted' => 'Aceptado',
                                            'rejected' => 'Rechazado',
                                            'expired' => 'Expirado',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$budget->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$budget->status] ?? ucfirst($budget->status) }}
                                    </span>
                                </td>
                                <td>{{ $budget->valid_until ? \Carbon\Carbon::parse($budget->valid_until)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $budget->assignedTo->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('budgets.show', $budget) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('budgets.edit', $budget) }}" class="btn btn-outline-warning" title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('&iquest;Est&aacute;s seguro de que quieres eliminar este presupuesto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay presupuestos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#budgetsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [6] }
                ]
            });
        });
    </script>
@endpush
