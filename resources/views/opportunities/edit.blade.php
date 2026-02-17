@extends('layouts.admin')

@section('title', 'Editar Oportunidad')

@section('page-title', 'Editar Oportunidad')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('opportunities.index') }}">Oportunidades</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Editar Oportunidad: {{ $opportunity->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('opportunities.update', $opportunity) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title">T&iacute;tulo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $opportunity->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="client_id">Cliente <span class="text-danger">*</span></label>
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $opportunity->client_id) == $client->id ? 'selected' : '' }}>{{ $client->company }} - {{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="value">Valor (&euro;) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $opportunity->value) }}" step="0.01" min="0" required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="probability">Probabilidad (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('probability') is-invalid @enderror" id="probability" name="probability" value="{{ old('probability', $opportunity->probability) }}" min="0" max="100" required>
                            @error('probability')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stage">Etapa <span class="text-danger">*</span></label>
                            <select class="form-control @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                                <option value="">Seleccionar etapa...</option>
                                @php
                                    $stages = [
                                        'prospecting' => 'Prospecci&oacute;n',
                                        'qualification' => 'Cualificaci&oacute;n',
                                        'proposal' => 'Propuesta',
                                        'negotiation' => 'Negociaci&oacute;n',
                                        'closed_won' => 'Ganada',
                                        'closed_lost' => 'Perdida',
                                    ];
                                @endphp
                                @foreach($stages as $stageKey => $stageLabel)
                                    <option value="{{ $stageKey }}" {{ old('stage', $opportunity->stage) == $stageKey ? 'selected' : '' }}>{{ $stageLabel }}</option>
                                @endforeach
                            </select>
                            @error('stage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="assigned_to">Asignado a</label>
                            <select class="form-control @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                <option value="">Seleccionar usuario...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $opportunity->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="expected_close_date">Fecha de Cierre Prevista</label>
                            <input type="date" class="form-control @error('expected_close_date') is-invalid @enderror" id="expected_close_date" name="expected_close_date" value="{{ old('expected_close_date', $opportunity->expected_close_date ? \Carbon\Carbon::parse($opportunity->expected_close_date)->format('Y-m-d') : '') }}">
                            @error('expected_close_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description">Descripci&oacute;n</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $opportunity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('opportunities.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Actualizar Oportunidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Documents Section --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-paperclip mr-2"></i>Documentos</h5>
            </div>
            <div class="card-body">
                {{-- Upload Form --}}
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    <input type="hidden" name="documentable_type" value="App\Models\Opportunity">
                    <input type="hidden" name="documentable_id" value="{{ $opportunity->id }}">
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
                @if($opportunity->documents && $opportunity->documents->count() > 0)
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
                                @foreach($opportunity->documents as $document)
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
