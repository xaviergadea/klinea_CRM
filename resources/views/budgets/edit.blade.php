@extends('layouts.admin')

@section('title', 'Editar Presupuesto')

@section('page-title', 'Editar Presupuesto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('budgets.index') }}">Presupuestos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('budgets.update', $budget) }}" method="POST" id="budgetForm">
                @csrf
                @method('PUT')

                {{-- General Info --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Datos del Presupuesto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="reference" class="form-label">Referencia</label>
                                <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror"
                                       value="{{ old('reference', $budget->reference) }}" placeholder="KLN-2026-XXXX">
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="client_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar cliente --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $budget->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="opportunity_id" class="form-label">Oportunidad</label>
                                <select name="opportunity_id" id="opportunity_id" class="form-select @error('opportunity_id') is-invalid @enderror">
                                    <option value="">-- Ninguna --</option>
                                    @foreach($opportunities as $opportunity)
                                        <option value="{{ $opportunity->id }}" {{ old('opportunity_id', $budget->opportunity_id) == $opportunity->id ? 'selected' : '' }}>
                                            {{ $opportunity->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('opportunity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="assigned_to" class="form-label">Asignado a</label>
                                <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $budget->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Estado</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $budget->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="sent" {{ old('status', $budget->status) == 'sent' ? 'selected' : '' }}>Enviado</option>
                                    <option value="accepted" {{ old('status', $budget->status) == 'accepted' ? 'selected' : '' }}>Aceptado</option>
                                    <option value="rejected" {{ old('status', $budget->status) == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                    <option value="expired" {{ old('status', $budget->status) == 'expired' ? 'selected' : '' }}>Expirado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="valid_until" class="form-label">V&aacute;lido Hasta</label>
                                <input type="date" name="valid_until" id="valid_until" class="form-control @error('valid_until') is-invalid @enderror"
                                       value="{{ old('valid_until', $budget->valid_until ? \Carbon\Carbon::parse($budget->valid_until)->format('Y-m-d') : '') }}">
                                @error('valid_until')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $budget->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">L&iacute;neas del Presupuesto</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addLineBtn">
                            <i class="fas fa-plus me-1"></i> A&ntilde;adir L&iacute;nea
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%">Descripci&oacute;n</th>
                                        <th style="width: 15%">Cantidad</th>
                                        <th style="width: 15%">Precio Unit.</th>
                                        <th style="width: 15%">Total</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    {{-- Rows populated via JS from existing items --}}
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-md-4">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td class="text-end fw-semibold">Subtotal:</td>
                                        <td class="text-end" id="displaySubtotal">0,00 &euro;</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end fw-semibold">IVA (21%):</td>
                                        <td class="text-end" id="displayTax">0,00 &euro;</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td class="text-end fw-bold">TOTAL:</td>
                                        <td class="text-end fw-bold" id="displayTotal">0,00 &euro;</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" name="items" id="itemsJson">
                        <input type="hidden" name="subtotal" id="subtotal">
                        <input type="hidden" name="tax_rate" value="21">
                        <input type="hidden" name="tax_amount" id="taxAmount">
                        <input type="hidden" name="total" id="total">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="{{ route('budgets.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemsBody = document.getElementById('itemsBody');
            const addLineBtn = document.getElementById('addLineBtn');
            let rowIndex = 0;

            // Existing items from the budget
            const existingItems = @json(is_string($budget->items) ? json_decode($budget->items, true) : ($budget->items ?? []));

            function createRow(data = {}) {
                const tr = document.createElement('tr');
                tr.setAttribute('data-row', rowIndex);
                tr.innerHTML = `
                    <td>
                        <input type="text" class="form-control form-control-sm item-description"
                               placeholder="Descripci&oacute;n del producto o servicio"
                               value="${data.description || ''}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-quantity text-end"
                               min="0" step="1" value="${data.quantity || 1}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-price text-end"
                               min="0" step="0.01" value="${data.unit_price || '0.00'}">
                    </td>
                    <td>
                        <span class="item-total text-end d-block fw-semibold">0,00 &euro;</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-line" title="Eliminar l&iacute;nea">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;
                itemsBody.appendChild(tr);
                rowIndex++;
                bindRowEvents(tr);
                recalculate();
            }

            function bindRowEvents(tr) {
                const qtyInput = tr.querySelector('.item-quantity');
                const priceInput = tr.querySelector('.item-price');
                const removeBtn = tr.querySelector('.remove-line');

                qtyInput.addEventListener('input', recalculate);
                priceInput.addEventListener('input', recalculate);
                removeBtn.addEventListener('click', function () {
                    tr.remove();
                    recalculate();
                });
            }

            function formatCurrency(value) {
                return value.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' \u20AC';
            }

            function recalculate() {
                let subtotal = 0;
                const rows = itemsBody.querySelectorAll('tr');

                rows.forEach(function (tr) {
                    const qty = parseFloat(tr.querySelector('.item-quantity').value) || 0;
                    const price = parseFloat(tr.querySelector('.item-price').value) || 0;
                    const rowTotal = qty * price;
                    tr.querySelector('.item-total').textContent = formatCurrency(rowTotal);
                    subtotal += rowTotal;
                });

                const taxRate = 21;
                const taxAmount = subtotal * (taxRate / 100);
                const total = subtotal + taxAmount;

                document.getElementById('displaySubtotal').textContent = formatCurrency(subtotal);
                document.getElementById('displayTax').textContent = formatCurrency(taxAmount);
                document.getElementById('displayTotal').textContent = formatCurrency(total);

                document.getElementById('subtotal').value = subtotal.toFixed(2);
                document.getElementById('taxAmount').value = taxAmount.toFixed(2);
                document.getElementById('total').value = total.toFixed(2);
            }

            addLineBtn.addEventListener('click', function () {
                createRow();
            });

            // Populate existing items
            if (existingItems && existingItems.length > 0) {
                existingItems.forEach(function (item) {
                    createRow(item);
                });
            } else {
                createRow();
            }

            // Serialize items to JSON on form submit
            document.getElementById('budgetForm').addEventListener('submit', function () {
                const rows = itemsBody.querySelectorAll('tr');
                const items = [];

                rows.forEach(function (tr) {
                    const description = tr.querySelector('.item-description').value.trim();
                    const quantity = parseFloat(tr.querySelector('.item-quantity').value) || 0;
                    const unit_price = parseFloat(tr.querySelector('.item-price').value) || 0;
                    const total = quantity * unit_price;

                    if (description !== '') {
                        items.push({
                            description: description,
                            quantity: quantity,
                            unit_price: unit_price,
                            total: total
                        });
                    }
                });

                document.getElementById('itemsJson').value = JSON.stringify(items);
            });
        });
    </script>
@endpush
