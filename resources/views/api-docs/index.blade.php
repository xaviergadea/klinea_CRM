@extends('layouts.admin')

@section('title', 'Documentación API')

@section('page-title', 'Documentación API')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Documentación API</li>
@endsection

@push('styles')
<style>
    .api-method-badge {
        display: inline-block;
        min-width: 70px;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 4px;
        margin-right: 10px;
    }
    .api-url {
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
        font-size: 0.95rem;
        color: #333;
    }
    .json-block {
        background-color: #1e1e1e;
        color: #d4d4d4;
        padding: 1rem;
        border-radius: 6px;
        font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
        font-size: 0.85rem;
        overflow-x: auto;
        white-space: pre;
    }
    .endpoint-card .card-header {
        cursor: pointer;
    }
    .endpoint-card .card-header:hover {
        background-color: #f4f6f9;
    }
</style>
@endpush

@section('content')
    {{-- Authentication Section --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-lock mr-2"></i>Autenticaci&oacute;n</h3>
        </div>
        <div class="card-body">
            <p>
                La API utiliza autenticaci&oacute;n mediante <strong>Bearer Token</strong>. Todas las peticiones
                deben incluir el token de acceso en la cabecera <code>Authorization</code>.
            </p>
            <div class="json-block">Authorization: Bearer {api_token}</div>
            <div class="callout callout-info mt-3">
                <h5><i class="fas fa-info-circle mr-1"></i> Obtener un token</h5>
                <p class="mb-1">
                    Para obtener un token de acceso, env&iacute;a una petici&oacute;n <code>POST</code> a
                    <code>/api/auth/token</code> con las credenciales del usuario:
                </p>
                <div class="json-block">{
    "email": "usuario@ejemplo.com",
    "password": "tu_contrasenya"
}</div>
                <p class="mt-2 mb-0">
                    La respuesta incluir&aacute; el <code>api_token</code> que deber&aacute;s usar en las siguientes peticiones.
                </p>
            </div>
        </div>
    </div>

    {{-- Rate Limiting Section --}}
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-tachometer-alt mr-2"></i>L&iacute;mite de Peticiones (Rate Limiting)</h3>
        </div>
        <div class="card-body">
            <p>La API tiene un l&iacute;mite de <strong>60 peticiones por minuto</strong> por usuario autenticado.</p>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Cabecera</th>
                        <th>Descripci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>X-RateLimit-Limit</code></td>
                        <td>N&uacute;mero m&aacute;ximo de peticiones permitidas por minuto</td>
                    </tr>
                    <tr>
                        <td><code>X-RateLimit-Remaining</code></td>
                        <td>N&uacute;mero de peticiones restantes en el periodo actual</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Endpoints Section --}}
    <h4 class="mb-3"><i class="fas fa-plug mr-2"></i>Endpoints</h4>

    {{-- ================================================================== --}}
    {{-- LEADS API --}}
    {{-- ================================================================== --}}
    <div class="card card-outline card-success">
        <div class="card-header" data-toggle="collapse" data-target="#leadsApi" aria-expanded="false">
            <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>Leads</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool"><i class="fas fa-chevron-down"></i></button>
            </div>
        </div>
        <div id="leadsApi" class="collapse">
            <div class="card-body p-0">

                {{-- GET /api/v1/leads --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#leads-list">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/leads</span>
                        <span class="ml-3 text-muted">Listar leads</span>
                    </div>
                    <div id="leads-list" class="collapse mt-2">
                        <p>Devuelve un listado paginado de todos los leads.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": [
        {
            "id": 1,
            "name": "Juan P&eacute;rez",
            "email": "juan@ejemplo.com",
            "phone": "+34 612 345 678",
            "company": "Empresa S.L.",
            "status": "new",
            "source": "web",
            "assigned_to": 2,
            "created_at": "2026-01-15T10:30:00.000000Z",
            "updated_at": "2026-01-15T10:30:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 68 }
}</div>
                    </div>
                </div>

                {{-- GET /api/v1/leads/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#leads-show">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/leads/{id}</span>
                        <span class="ml-3 text-muted">Obtener lead</span>
                    </div>
                    <div id="leads-show" class="collapse mt-2">
                        <p>Devuelve los datos de un lead espec&iacute;fico por su ID.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Juan P&eacute;rez",
        "email": "juan@ejemplo.com",
        "phone": "+34 612 345 678",
        "company": "Empresa S.L.",
        "status": "new",
        "source": "web",
        "assigned_to": 2,
        "created_at": "2026-01-15T10:30:00.000000Z",
        "updated_at": "2026-01-15T10:30:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- POST /api/v1/leads --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#leads-store">
                        <span class="api-method-badge badge badge-primary">POST</span>
                        <span class="api-url">/api/v1/leads</span>
                        <span class="ml-3 text-muted">Crear lead</span>
                    </div>
                    <div id="leads-store" class="collapse mt-2">
                        <p>Crea un nuevo lead en el sistema.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "name": "Mar&iacute;a Garc&iacute;a",
    "email": "maria@ejemplo.com",
    "phone": "+34 698 765 432",
    "company": "Tech Solutions S.A.",
    "status": "new",
    "source": "referral",
    "assigned_to": 3
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (201):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 2,
        "name": "Mar&iacute;a Garc&iacute;a",
        "email": "maria@ejemplo.com",
        "phone": "+34 698 765 432",
        "company": "Tech Solutions S.A.",
        "status": "new",
        "source": "referral",
        "assigned_to": 3,
        "created_at": "2026-02-17T09:00:00.000000Z",
        "updated_at": "2026-02-17T09:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- PUT /api/v1/leads/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#leads-update">
                        <span class="api-method-badge badge badge-warning">PUT</span>
                        <span class="api-url">/api/v1/leads/{id}</span>
                        <span class="ml-3 text-muted">Actualizar lead</span>
                    </div>
                    <div id="leads-update" class="collapse mt-2">
                        <p>Actualiza los datos de un lead existente.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "name": "Juan P&eacute;rez Actualizado",
    "status": "contacted",
    "phone": "+34 612 000 111"
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Juan P&eacute;rez Actualizado",
        "email": "juan@ejemplo.com",
        "phone": "+34 612 000 111",
        "company": "Empresa S.L.",
        "status": "contacted",
        "source": "web",
        "assigned_to": 2,
        "created_at": "2026-01-15T10:30:00.000000Z",
        "updated_at": "2026-02-17T11:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- DELETE /api/v1/leads/{id} --}}
                <div class="p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#leads-delete">
                        <span class="api-method-badge badge badge-danger">DELETE</span>
                        <span class="api-url">/api/v1/leads/{id}</span>
                        <span class="ml-3 text-muted">Eliminar lead</span>
                    </div>
                    <div id="leads-delete" class="collapse mt-2">
                        <p>Elimina un lead del sistema.</p>
                        <strong>Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "message": "Lead eliminado correctamente."
}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- CLIENTS API --}}
    {{-- ================================================================== --}}
    <div class="card card-outline card-info">
        <div class="card-header" data-toggle="collapse" data-target="#clientsApi" aria-expanded="false">
            <h3 class="card-title"><i class="fas fa-building mr-2"></i>Clientes</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool"><i class="fas fa-chevron-down"></i></button>
            </div>
        </div>
        <div id="clientsApi" class="collapse">
            <div class="card-body p-0">

                {{-- GET /api/v1/clients --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#clients-list">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/clients</span>
                        <span class="ml-3 text-muted">Listar clientes</span>
                    </div>
                    <div id="clients-list" class="collapse mt-2">
                        <p>Devuelve un listado paginado de todos los clientes.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": [
        {
            "id": 1,
            "name": "Empresa ABC S.L.",
            "email": "contacto@abc.com",
            "phone": "+34 911 222 333",
            "address": "Calle Mayor 10, Madrid",
            "city": "Madrid",
            "tax_id": "B12345678",
            "assigned_to": 2,
            "created_at": "2026-01-10T08:00:00.000000Z",
            "updated_at": "2026-01-20T14:30:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 3, "per_page": 15, "total": 42 }
}</div>
                    </div>
                </div>

                {{-- GET /api/v1/clients/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#clients-show">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/clients/{id}</span>
                        <span class="ml-3 text-muted">Obtener cliente</span>
                    </div>
                    <div id="clients-show" class="collapse mt-2">
                        <p>Devuelve los datos de un cliente espec&iacute;fico por su ID.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Empresa ABC S.L.",
        "email": "contacto@abc.com",
        "phone": "+34 911 222 333",
        "address": "Calle Mayor 10, Madrid",
        "city": "Madrid",
        "tax_id": "B12345678",
        "assigned_to": 2,
        "created_at": "2026-01-10T08:00:00.000000Z",
        "updated_at": "2026-01-20T14:30:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- POST /api/v1/clients --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#clients-store">
                        <span class="api-method-badge badge badge-primary">POST</span>
                        <span class="api-url">/api/v1/clients</span>
                        <span class="ml-3 text-muted">Crear cliente</span>
                    </div>
                    <div id="clients-store" class="collapse mt-2">
                        <p>Crea un nuevo cliente en el sistema.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "name": "Nueva Empresa S.A.",
    "email": "info@nuevaempresa.com",
    "phone": "+34 933 444 555",
    "address": "Avda. Diagonal 200, Barcelona",
    "city": "Barcelona",
    "tax_id": "A87654321",
    "assigned_to": 1
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (201):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 2,
        "name": "Nueva Empresa S.A.",
        "email": "info@nuevaempresa.com",
        "phone": "+34 933 444 555",
        "address": "Avda. Diagonal 200, Barcelona",
        "city": "Barcelona",
        "tax_id": "A87654321",
        "assigned_to": 1,
        "created_at": "2026-02-17T09:15:00.000000Z",
        "updated_at": "2026-02-17T09:15:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- PUT /api/v1/clients/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#clients-update">
                        <span class="api-method-badge badge badge-warning">PUT</span>
                        <span class="api-url">/api/v1/clients/{id}</span>
                        <span class="ml-3 text-muted">Actualizar cliente</span>
                    </div>
                    <div id="clients-update" class="collapse mt-2">
                        <p>Actualiza los datos de un cliente existente.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "name": "Empresa ABC Actualizada S.L.",
    "phone": "+34 911 000 999"
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Empresa ABC Actualizada S.L.",
        "email": "contacto@abc.com",
        "phone": "+34 911 000 999",
        "address": "Calle Mayor 10, Madrid",
        "city": "Madrid",
        "tax_id": "B12345678",
        "assigned_to": 2,
        "created_at": "2026-01-10T08:00:00.000000Z",
        "updated_at": "2026-02-17T12:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- DELETE /api/v1/clients/{id} --}}
                <div class="p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#clients-delete">
                        <span class="api-method-badge badge badge-danger">DELETE</span>
                        <span class="api-url">/api/v1/clients/{id}</span>
                        <span class="ml-3 text-muted">Eliminar cliente</span>
                    </div>
                    <div id="clients-delete" class="collapse mt-2">
                        <p>Elimina un cliente del sistema.</p>
                        <strong>Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "message": "Cliente eliminado correctamente."
}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- OPPORTUNITIES API --}}
    {{-- ================================================================== --}}
    <div class="card card-outline card-purple">
        <div class="card-header" data-toggle="collapse" data-target="#opportunitiesApi" aria-expanded="false">
            <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>Oportunidades</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool"><i class="fas fa-chevron-down"></i></button>
            </div>
        </div>
        <div id="opportunitiesApi" class="collapse">
            <div class="card-body p-0">

                {{-- GET /api/v1/opportunities --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#opps-list">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/opportunities</span>
                        <span class="ml-3 text-muted">Listar oportunidades</span>
                    </div>
                    <div id="opps-list" class="collapse mt-2">
                        <p>Devuelve un listado paginado de todas las oportunidades.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": [
        {
            "id": 1,
            "name": "Proyecto web corporativa",
            "client_id": 1,
            "amount": 15000.00,
            "stage": "proposal",
            "probability": 60,
            "expected_close_date": "2026-03-15",
            "assigned_to": 2,
            "created_at": "2026-01-20T10:00:00.000000Z",
            "updated_at": "2026-02-10T16:00:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 2, "per_page": 15, "total": 25 }
}</div>
                    </div>
                </div>

                {{-- GET /api/v1/opportunities/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#opps-show">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/opportunities/{id}</span>
                        <span class="ml-3 text-muted">Obtener oportunidad</span>
                    </div>
                    <div id="opps-show" class="collapse mt-2">
                        <p>Devuelve los datos de una oportunidad espec&iacute;fica por su ID.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Proyecto web corporativa",
        "client_id": 1,
        "amount": 15000.00,
        "stage": "proposal",
        "probability": 60,
        "expected_close_date": "2026-03-15",
        "assigned_to": 2,
        "created_at": "2026-01-20T10:00:00.000000Z",
        "updated_at": "2026-02-10T16:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- POST /api/v1/opportunities --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#opps-store">
                        <span class="api-method-badge badge badge-primary">POST</span>
                        <span class="api-url">/api/v1/opportunities</span>
                        <span class="ml-3 text-muted">Crear oportunidad</span>
                    </div>
                    <div id="opps-store" class="collapse mt-2">
                        <p>Crea una nueva oportunidad en el sistema.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "name": "Redise&ntilde;o e-commerce",
    "client_id": 2,
    "amount": 25000.00,
    "stage": "qualification",
    "probability": 30,
    "expected_close_date": "2026-04-30",
    "assigned_to": 3
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (201):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 2,
        "name": "Redise&ntilde;o e-commerce",
        "client_id": 2,
        "amount": 25000.00,
        "stage": "qualification",
        "probability": 30,
        "expected_close_date": "2026-04-30",
        "assigned_to": 3,
        "created_at": "2026-02-17T09:30:00.000000Z",
        "updated_at": "2026-02-17T09:30:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- PUT /api/v1/opportunities/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#opps-update">
                        <span class="api-method-badge badge badge-warning">PUT</span>
                        <span class="api-url">/api/v1/opportunities/{id}</span>
                        <span class="ml-3 text-muted">Actualizar oportunidad</span>
                    </div>
                    <div id="opps-update" class="collapse mt-2">
                        <p>Actualiza los datos de una oportunidad existente.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "stage": "negotiation",
    "probability": 80,
    "amount": 18000.00
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "name": "Proyecto web corporativa",
        "client_id": 1,
        "amount": 18000.00,
        "stage": "negotiation",
        "probability": 80,
        "expected_close_date": "2026-03-15",
        "assigned_to": 2,
        "created_at": "2026-01-20T10:00:00.000000Z",
        "updated_at": "2026-02-17T14:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- DELETE /api/v1/opportunities/{id} --}}
                <div class="p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#opps-delete">
                        <span class="api-method-badge badge badge-danger">DELETE</span>
                        <span class="api-url">/api/v1/opportunities/{id}</span>
                        <span class="ml-3 text-muted">Eliminar oportunidad</span>
                    </div>
                    <div id="opps-delete" class="collapse mt-2">
                        <p>Elimina una oportunidad del sistema.</p>
                        <strong>Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "message": "Oportunidad eliminada correctamente."
}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- BUDGETS API --}}
    {{-- ================================================================== --}}
    <div class="card card-outline card-orange">
        <div class="card-header" data-toggle="collapse" data-target="#budgetsApi" aria-expanded="false">
            <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-2"></i>Presupuestos</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool"><i class="fas fa-chevron-down"></i></button>
            </div>
        </div>
        <div id="budgetsApi" class="collapse">
            <div class="card-body p-0">

                {{-- GET /api/v1/budgets --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#budgets-list">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/budgets</span>
                        <span class="ml-3 text-muted">Listar presupuestos</span>
                    </div>
                    <div id="budgets-list" class="collapse mt-2">
                        <p>Devuelve un listado paginado de todos los presupuestos.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": [
        {
            "id": 1,
            "reference": "PRE-2026-0001",
            "client_id": 1,
            "opportunity_id": 1,
            "subtotal": 12500.00,
            "tax_rate": 21.00,
            "tax_amount": 2625.00,
            "total": 15125.00,
            "status": "sent",
            "valid_until": "2026-03-01",
            "created_at": "2026-02-01T09:00:00.000000Z",
            "updated_at": "2026-02-05T11:00:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 2, "per_page": 15, "total": 18 }
}</div>
                    </div>
                </div>

                {{-- GET /api/v1/budgets/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#budgets-show">
                        <span class="api-method-badge badge badge-success">GET</span>
                        <span class="api-url">/api/v1/budgets/{id}</span>
                        <span class="ml-3 text-muted">Obtener presupuesto</span>
                    </div>
                    <div id="budgets-show" class="collapse mt-2">
                        <p>Devuelve los datos de un presupuesto espec&iacute;fico por su ID.</p>
                        <strong>Ejemplo de respuesta:</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "reference": "PRE-2026-0001",
        "client_id": 1,
        "opportunity_id": 1,
        "subtotal": 12500.00,
        "tax_rate": 21.00,
        "tax_amount": 2625.00,
        "total": 15125.00,
        "status": "sent",
        "valid_until": "2026-03-01",
        "notes": "Incluye mantenimiento 12 meses.",
        "created_at": "2026-02-01T09:00:00.000000Z",
        "updated_at": "2026-02-05T11:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- POST /api/v1/budgets --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#budgets-store">
                        <span class="api-method-badge badge badge-primary">POST</span>
                        <span class="api-url">/api/v1/budgets</span>
                        <span class="ml-3 text-muted">Crear presupuesto</span>
                    </div>
                    <div id="budgets-store" class="collapse mt-2">
                        <p>Crea un nuevo presupuesto en el sistema.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "client_id": 2,
    "opportunity_id": 2,
    "subtotal": 20000.00,
    "tax_rate": 21.00,
    "status": "draft",
    "valid_until": "2026-04-15",
    "notes": "Presupuesto inicial para redise&ntilde;o."
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (201):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 2,
        "reference": "PRE-2026-0002",
        "client_id": 2,
        "opportunity_id": 2,
        "subtotal": 20000.00,
        "tax_rate": 21.00,
        "tax_amount": 4200.00,
        "total": 24200.00,
        "status": "draft",
        "valid_until": "2026-04-15",
        "notes": "Presupuesto inicial para redise&ntilde;o.",
        "created_at": "2026-02-17T10:00:00.000000Z",
        "updated_at": "2026-02-17T10:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- PUT /api/v1/budgets/{id} --}}
                <div class="border-bottom p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#budgets-update">
                        <span class="api-method-badge badge badge-warning">PUT</span>
                        <span class="api-url">/api/v1/budgets/{id}</span>
                        <span class="ml-3 text-muted">Actualizar presupuesto</span>
                    </div>
                    <div id="budgets-update" class="collapse mt-2">
                        <p>Actualiza los datos de un presupuesto existente.</p>
                        <strong>Ejemplo de petici&oacute;n:</strong>
                        <div class="json-block mt-2">{
    "status": "accepted",
    "subtotal": 22000.00,
    "tax_rate": 21.00
}</div>
                        <strong class="d-block mt-3">Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "data": {
        "id": 1,
        "reference": "PRE-2026-0001",
        "client_id": 1,
        "opportunity_id": 1,
        "subtotal": 22000.00,
        "tax_rate": 21.00,
        "tax_amount": 4620.00,
        "total": 26620.00,
        "status": "accepted",
        "valid_until": "2026-03-01",
        "created_at": "2026-02-01T09:00:00.000000Z",
        "updated_at": "2026-02-17T15:00:00.000000Z"
    }
}</div>
                    </div>
                </div>

                {{-- DELETE /api/v1/budgets/{id} --}}
                <div class="p-3 endpoint-card">
                    <div class="d-flex align-items-center mb-2" data-toggle="collapse" data-target="#budgets-delete">
                        <span class="api-method-badge badge badge-danger">DELETE</span>
                        <span class="api-url">/api/v1/budgets/{id}</span>
                        <span class="ml-3 text-muted">Eliminar presupuesto</span>
                    </div>
                    <div id="budgets-delete" class="collapse mt-2">
                        <p>Elimina un presupuesto del sistema.</p>
                        <strong>Ejemplo de respuesta (200):</strong>
                        <div class="json-block mt-2">{
    "message": "Presupuesto eliminado correctamente."
}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Response Codes --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list-ol mr-2"></i>C&oacute;digos de Respuesta</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>C&oacute;digo</th>
                        <th>Descripci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>200</code></td><td>Petici&oacute;n exitosa</td></tr>
                    <tr><td><code>201</code></td><td>Recurso creado correctamente</td></tr>
                    <tr><td><code>401</code></td><td>No autenticado - Token inv&aacute;lido o ausente</td></tr>
                    <tr><td><code>403</code></td><td>No autorizado - Sin permisos suficientes</td></tr>
                    <tr><td><code>404</code></td><td>Recurso no encontrado</td></tr>
                    <tr><td><code>422</code></td><td>Error de validaci&oacute;n - Datos inv&aacute;lidos</td></tr>
                    <tr><td><code>429</code></td><td>Demasiadas peticiones - L&iacute;mite excedido</td></tr>
                    <tr><td><code>500</code></td><td>Error interno del servidor</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
