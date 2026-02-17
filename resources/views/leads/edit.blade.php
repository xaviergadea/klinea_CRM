@extends('layouts.admin')

@section('title', 'Editar Lead')

@section('page-title', 'Editar Lead')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Editar Lead: {{ $lead->name }}</h3>
                </div>
                <form action="{{ route('leads.update', $lead) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $lead->name) }}" required placeholder="Nombre del lead">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $lead->email) }}" placeholder="correo@ejemplo.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $lead->phone) }}" placeholder="+34 600 000 000">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Empresa --}}
                        <div class="form-group">
                            <label for="company">Empresa</label>
                            <input type="text" name="company" id="company"
                                   class="form-control @error('company') is-invalid @enderror"
                                   value="{{ old('company', $lead->company) }}" placeholder="Nombre de la empresa">
                            @error('company')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Fuente --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="source">Fuente</label>
                                    <select name="source" id="source"
                                            class="form-control @error('source') is-invalid @enderror">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="web" {{ old('source', $lead->source) == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Referencia</option>
                                        <option value="linkedin" {{ old('source', $lead->source) == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                        <option value="cold_call" {{ old('source', $lead->source) == 'cold_call' ? 'selected' : '' }}>Llamada en frío</option>
                                        <option value="event" {{ old('source', $lead->source) == 'event' ? 'selected' : '' }}>Evento</option>
                                    </select>
                                    @error('source')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror">
                                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>Nuevo</option>
                                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contactado</option>
                                        <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Cualificado</option>
                                        <option value="proposal" {{ old('status', $lead->status) == 'proposal' ? 'selected' : '' }}>Propuesta</option>
                                        <option value="won" {{ old('status', $lead->status) == 'won' ? 'selected' : '' }}>Ganado</option>
                                        <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Perdido</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Asignado a --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="assigned_to">Asignado a</label>
                                    <select name="assigned_to" id="assigned_to"
                                            class="form-control @error('assigned_to') is-invalid @enderror">
                                        <option value="">-- Sin asignar --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="form-group">
                            <label for="notes">Notas</label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Notas adicionales sobre el lead...">{{ old('notes', $lead->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
