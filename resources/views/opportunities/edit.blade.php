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
                            <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $opportunity->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
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
                            <label for="value" class="form-label">Valor (&euro;) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $opportunity->value) }}" step="0.01" min="0" required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="probability" class="form-label">Probabilidad (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('probability') is-invalid @enderror" id="probability" name="probability" value="{{ old('probability', $opportunity->probability) }}" min="0" max="100" required>
                            @error('probability')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stage" class="form-label">Etapa <span class="text-danger">*</span></label>
                            <select class="form-select @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                                <option value="">Seleccionar etapa...</option>
                                @php
                                    $stages = [
                                        'prospecting' => 'Prospección',
                                        'qualification' => 'Cualificación',
                                        'proposal' => 'Propuesta',
                                        'negotiation' => 'Negociación',
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
                            <label for="assigned_to" class="form-label">Asignado a</label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
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
                            <label for="expected_close_date" class="form-label">Fecha de Cierre Prevista</label>
                            <input type="date" class="form-control @error('expected_close_date') is-invalid @enderror" id="expected_close_date" name="expected_close_date" value="{{ old('expected_close_date', $opportunity->expected_close_date ? \Carbon\Carbon::parse($opportunity->expected_close_date)->format('Y-m-d') : '') }}">
                            @error('expected_close_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $opportunity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('opportunities.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Oportunidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
