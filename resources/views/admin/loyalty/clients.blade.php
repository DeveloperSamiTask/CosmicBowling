@extends('admin/layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" id="loyaltyClientsPage"
        data-list-url="{{ url('/be/fidelizacion/listado') }}"
        data-detail-url="{{ url('/be/fidelizacion/detalle') }}">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-1">Clientes y premios</h5>
                <p class="text-muted mb-0">Clientes que ya cuentan con tarjeta de fidelización.</p>
            </div>
            <div class="card-body pt-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="form-floating form-floating-outline">
                            <input type="search" class="form-control" id="loyaltyClientsSearch"
                                placeholder="Buscar cliente">
                            <label for="loyaltyClientsSearch">Buscar por nombre, documento o tarjeta</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="loyaltyOriginFilter">
                                <option value="">Todos</option>
                                <option value="atc">Registrados por ATC</option>
                                <option value="web">Registrados por web</option>
                            </select>
                            <label for="loyaltyOriginFilter">Origen</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pendingRewardsOnly">
                            <label class="form-check-label" for="pendingRewardsOnly">Solo con premios pendientes</label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Origen</th>
                                <th>Checks actuales</th>
                                <th>Total histórico</th>
                                <th>Premio 5</th>
                                <th>Premio 10</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="loyaltyClientsTableBody">
                            <tr><td colspan="7" class="text-center py-4">Cargando clientes...</td></tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-0 mt-3" id="loyaltyClientsCount"></p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loyaltyClientDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="detailClientName">Detalle de fidelización</h5>
                        <small class="text-muted" id="detailClientIdentity"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-lg-3"><div class="border rounded p-3"><small class="text-muted">Tarjeta</small><h6 class="mb-0" id="detailCardNumber"></h6></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="border rounded p-3"><small class="text-muted">Checks actuales</small><h6 class="mb-0"><span id="detailCurrentChecks"></span> / 10</h6></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="border rounded p-3"><small class="text-muted">Total histórico</small><h6 class="mb-0" id="detailTotalChecks"></h6></div></div>
                        <div class="col-sm-6 col-lg-3"><div class="border rounded p-3"><small class="text-muted">Ciclo actual</small><h6 class="mb-0" id="detailCurrentCycle"></h6></div></div>
                    </div>

                    <h6>Historial de movimientos</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead><tr><th>Fecha</th><th>Origen/compra</th><th>Checks</th><th>Saldo</th><th>Registrado por</th></tr></thead>
                            <tbody id="loyaltyMovementsBody"></tbody>
                        </table>
                    </div>

                    <h6>Premios</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Premio</th><th>Hito</th><th>Ciclo</th><th>Estado</th><th>Obtenido</th><th>Utilizado</th></tr></thead>
                            <tbody id="loyaltyRewardsBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pages/loyalty-clients.js') }}"></script>
@endsection
