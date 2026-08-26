@extends('admin/layouts/layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" id="loyaltyPage"
        data-search-url="{{ url('/be/fidelizacion/clientes') }}"
        data-store-url="{{ url('/be/fidelizacion/compras') }}">
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-1">Tarjeta de fidelización</h5>
                <p class="text-muted mb-0">Busca al cliente por tipo y número de documento antes de registrar su compra.</p>
            </div>
            <div class="card-body pt-4">
                <form id="searchLoyaltyClientForm" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="loyaltyDocumentType" required>
                                @foreach ($documentTypes as $documentType)
                                    <option value="{{ $documentType->id_doc }}" @selected($documentType->id_doc === '01')>{{ $documentType->name_doc }}</option>
                                @endforeach
                            </select>
                            <label for="loyaltyDocumentType">Tipo de documento</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="loyaltyDocumentNumber"
                                maxlength="20" autocomplete="off" placeholder="Número de documento">
                            <label for="loyaltyDocumentNumber">Número de documento</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary" id="searchLoyaltyClient">
                            <i class="mdi mdi-account-search-outline me-1"></i> Buscar cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-none" id="loyaltyClientResult">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <span class="badge bg-label-success mb-2">Cliente encontrado</span>
                        <h5 class="mb-1" id="loyaltyClientName"></h5>
                        <p class="text-muted mb-0">
                            <span id="loyaltyClientDocumentType"></span>: <span id="loyaltyClientDocumentNumber"></span>
                            <span class="mx-2">·</span>
                            Teléfono: <span id="loyaltyClientPhone"></span>
                        </p>
                    </div>
                    <button type="button" class="btn btn-primary align-self-md-center" id="openExistingPurchaseModal">
                        Registrar nueva compra
                    </button>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-sm-6 col-lg-3"><div class="border rounded p-3 h-100"><small class="text-muted">Tarjeta</small><h6 class="mb-0" id="loyaltyCardNumber">Sin tarjeta</h6></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="border rounded p-3 h-100"><small class="text-muted">Checks del ciclo</small><h6 class="mb-0"><span id="loyaltyCurrentChecks">0</span> / 10</h6></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="border rounded p-3 h-100"><small class="text-muted">Checks históricos</small><h6 class="mb-0" id="loyaltyTotalChecks">0</h6></div></div>
                    <div class="col-sm-6 col-lg-3"><div class="border rounded p-3 h-100"><small class="text-muted">Premios pendientes</small><h6 class="mb-0" id="loyaltyPendingRewards">0</h6></div></div>
                </div>

                <div class="alert alert-warning mt-3 mb-0 d-none" id="loyaltyAutomaticReward">
                    <h6 class="alert-heading mb-1">Premio automático para esta compra</h6>
                    <strong id="loyaltyAutomaticRewardName"></strong>
                    <p class="mb-0" id="loyaltyAutomaticRewardDescription"></p>
                    <small>ATC debe aplicar este beneficio en Wally. Al confirmar la compra en BW se marcará como utilizado.</small>
                </div>
            </div>
        </div>

        <div class="alert alert-info d-none" id="loyaltyClientNotFound" role="alert">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div><h6 class="alert-heading mb-1">El documento no está registrado</h6><span>Registra al cliente junto con su primera compra.</span></div>
                <button type="button" class="btn btn-primary" id="openNewClientModal">Registrar cliente</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loyaltyPurchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div><h5 class="modal-title" id="loyaltyModalTitle">Registrar compra</h5><small class="text-muted">Cada hora de juego equivale a un check, sin importar la cantidad de pistas.</small></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="loyaltyPurchaseForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="document_id" id="purchaseDocumentType">
                        <input type="hidden" name="number_doc" id="purchaseDocumentNumber">
                        <div id="newLoyaltyClientFields" class="d-none">
                            <h6 class="mb-3">Datos del nuevo cliente</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="text" class="form-control" id="lastnamePat" name="lastname_pat" maxlength="255" placeholder="Apellido paterno"><label for="lastnamePat">Apellido paterno</label></div></div>
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="text" class="form-control" id="lastnameMat" name="lastname_mat" maxlength="255" placeholder="Apellido materno"><label for="lastnameMat">Apellido materno</label></div></div>
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="text" class="form-control" id="namesClient" name="names_client" maxlength="255" placeholder="Nombres"><label for="namesClient">Nombres</label></div></div>
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="tel" class="form-control" id="phoneClient" name="phone_client" maxlength="20" placeholder="Teléfono"><label for="phoneClient">Teléfono</label></div></div>
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="date" class="form-control" id="birthdayClient" name="birthday_client"><label for="birthdayClient">Fecha de nacimiento</label></div></div>
                                <div class="col-md-4"><div class="form-floating form-floating-outline"><input type="text" class="form-control" id="addressClient" name="address_client" maxlength="255" placeholder="Dirección"><label for="addressClient">Dirección</label></div></div>
                            </div>
                            <hr>
                        </div>

                        <h6 class="mb-3">Datos de la compra</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" id="subcategoryId" name="subcategory_id" required>
                                        <option value="">Selecciona un juego</option>
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id_subcategory }}">{{ $subcategory->name_subcategory }}</option>
                                        @endforeach
                                    </select>
                                    <label for="subcategoryId">Tipo de juego</label>
                                </div>
                            </div>
                            <div class="col-md-3"><div class="form-floating form-floating-outline"><input type="number" class="form-control loyalty-check-input" id="quantityLane" name="quantity_lane" min="1" max="10" step="1" required placeholder="Pistas"><label for="quantityLane">Cantidad de pistas</label></div></div>
                            <div class="col-md-3"><div class="form-floating form-floating-outline"><input type="number" class="form-control loyalty-check-input" id="quantityHours" name="quantity_hours" min="1" max="12" step="1" required placeholder="Horas"><label for="quantityHours">Cantidad de horas</label></div></div>
                            <div class="col-md-2"><div class="border rounded p-3 h-100 text-center"><small class="text-muted">Checks</small><h5 class="mb-0" id="purchaseChecksPreview">0</h5></div></div>
                            <div class="col-12"><div class="form-floating form-floating-outline"><textarea class="form-control" id="purchaseNotes" name="notes" maxlength="1000" style="height: 58px" placeholder="Observación"></textarea><label for="purchaseNotes">Observación (opcional)</label></div></div>
                        </div>

                        <div class="alert mt-3 mb-3" id="purchaseRewardNotice">
                            <h6 class="alert-heading mb-1" id="purchaseRewardTitle"></h6>
                            <p class="mb-0" id="purchaseRewardText"></p>
                        </div>

                        <div class="alert alert-info mb-0">Confirma esta operación solamente después de completar el cobro en Wally. Por el momento no se solicitará el número de boleta.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="saveLoyaltyPurchase">Confirmar compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pages/loyalty.js') }}"></script>
@endsection
