@extends('layouts.admin')

@section('title', $budget->reference)

@section('page-title', $budget->reference)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('budgets.index') }}">Presupuestos</a></li>
    <li class="breadcrumb-item active">{{ $budget->reference }}</li>
@endsection

@section('content')
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
        $items = is_string($budget->items) ? json_decode($budget->items, true) : ($budget->items ?? []);
    @endphp

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver al listado
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
            <a href="{{ route('budgets.edit', $budget) }}" class="btn btn-warning">
                <i class="fas fa-pencil-alt mr-1"></i> Editar
            </a>
            <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('&iquest;Est&aacute;s seguro de que quieres eliminar este presupuesto?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

    {{-- Budget Info --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Informaci&oacute;n del Presupuesto</h5>
            <span class="badge badge-{{ $statusColors[$budget->status] ?? 'secondary' }}">
                {{ $statusLabels[$budget->status] ?? ucfirst($budget->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">Referencia</strong>
                    <span>{{ $budget->reference }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">Cliente</strong>
                    @if($budget->client)
                        <a href="{{ route('clients.show', $budget->client) }}">
                            {{ $budget->client->name }}
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">Oportunidad</strong>
                    @if($budget->opportunity)
                        <a href="{{ route('opportunities.show', $budget->opportunity) }}">
                            {{ $budget->opportunity->title }}
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">Asignado a</strong>
                    <span>{{ $budget->assignedTo->name ?? '-' }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">V&aacute;lido Hasta</strong>
                    <span>{{ $budget->valid_until ? \Carbon\Carbon::parse($budget->valid_until)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <strong class="text-muted d-block mb-1">Fecha de Creaci&oacute;n</strong>
                    <span>{{ $budget->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($budget->notes)
                    <div class="col-12">
                        <strong class="text-muted d-block mb-1">Notas</strong>
                        <p class="mb-0">{{ $budget->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">L&iacute;neas del Presupuesto</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 50%">Descripci&oacute;n</th>
                            <th class="text-right" style="width: 15%">Cantidad</th>
                            <th class="text-right" style="width: 15%">Precio Unitario</th>
                            <th class="text-right" style="width: 15%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td>{{ $item['description'] ?? '-' }}</td>
                                <td class="text-right">{{ $item['quantity'] ?? 0 }}</td>
                                <td class="text-right">{{ number_format($item['unit_price'] ?? 0, 2, ',', '.') }} &euro;</td>
                                <td class="text-right">{{ number_format($item['total'] ?? (($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)), 2, ',', '.') }} &euro;</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No hay l&iacute;neas en este presupuesto.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($items) > 0)
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Subtotal:</td>
                                <td class="text-right">{{ number_format($budget->subtotal ?? 0, 2, ',', '.') }} &euro;</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">IVA (21%):</td>
                                <td class="text-right">{{ number_format($budget->tax_amount ?? 0, 2, ',', '.') }} &euro;</td>
                            </tr>
                            <tr class="table-dark">
                                <td colspan="4" class="text-right font-weight-bold">TOTAL:</td>
                                <td class="text-right font-weight-bold">{{ number_format($budget->total ?? 0, 2, ',', '.') }} &euro;</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Documents Section --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-paperclip mr-2"></i>Documentos</h5>
        </div>
        <div class="card-body">
            {{-- Upload Form --}}
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <input type="hidden" name="documentable_type" value="App\Models\Budget">
                <input type="hidden" name="documentable_id" value="{{ $budget->id }}">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="document" name="document" required>
                        <label class="custom-file-label" for="document">Seleccionar archivo...</label>
                    </div>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i> Subir
                        </button>
                    </div>
                </div>
                <small class="form-text text-muted">M&aacute;x. 10MB. Formatos: pdf, doc, docx, xls, xlsx, jpg, png, txt, zip</small>
            </form>

            {{-- Documents List --}}
            @if(isset($budget->documents) && $budget->documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Archivo</th>
                                <th>Tama&ntilde;o</th>
                                <th>Subido por</th>
                                <th>Fecha</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budget->documents as $document)
                                <tr>
                                    <td>
                                        <i class="fas fa-file mr-1"></i>
                                        {{ $document->filename }}
                                    </td>
                                    <td>{{ $document->sizeForHumans() }}</td>
                                    <td>{{ $document->user->name ?? '-' }}</td>
                                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-info" title="Descargar">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('&iquest;Eliminar este documento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No hay documentos adjuntos.</p>
            @endif
        </div>
    </div>

    {{-- Activity Timeline --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Actividad Reciente</h5>
        </div>
        <div class="card-body">
            @if(isset($budget->activities) && $budget->activities->count() > 0)
                <div class="timeline">
                    @foreach($budget->activities as $activity)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 mr-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fas fa-clock fa-sm"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $activity->description }}</div>
                                <small class="text-muted">
                                    {{ $activity->created_at->diffForHumans() }}
                                    @if($activity->user)
                                        &middot; {{ $activity->user->name }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No hay actividad registrada para este presupuesto.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.custom-file-input')?.addEventListener('change', function () {
                var fileName = this.files[0]?.name || 'Seleccionar archivo...';
                this.nextElementSibling.textContent = fileName;
            });
        });
    </script>
@endpush
