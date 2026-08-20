@extends('frontend/layouts/app')

@section('content')
    @php
        $card = $client->loyaltyCard;
        $pendingRewards = $card ? $card->rewards->where('status', 'pending') : collect();
        $usedRewards = $card ? $card->rewards->where('status', 'used') : collect();
    @endphp

    <section class="container pt-5 mt-5 pb-5" style="min-height: 75vh;">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <span class="text-body-secondary">Hola,</span>
                <h1 class="h3 mb-1">{{ $client->names_client }}</h1>
                <p class="text-body-secondary mb-0">
                    {{ $client->sunatTypedoc?->name_doc ?? $client->document_id }} {{ $client->number_doc }}
                </p>
            </div>
            <form method="POST" action="{{ route('loyalty.portal.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="ai-logout me-2"></i>Salir
                </button>
            </form>
        </div>

        @if ($card)
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="card border-0 text-white overflow-hidden h-100 loyalty-public-card">
                        <div class="card-body position-relative p-4 p-sm-5">
                            <div class="d-flex justify-content-between align-items-start mb-5">
                                <div>
                                    <small class="text-white-50">COSMIC BOWLING</small>
                                    <h2 class="h4 text-white mt-1 mb-0">Tarjeta de fidelización</h2>
                                </div>
                                <i class="ai-award fs-1"></i>
                            </div>
                            <p class="fs-sm text-white-50 mb-1">Número de tarjeta</p>
                            <p class="fs-4 fw-semibold mb-4">{{ $card->card_number }}</p>
                            <div class="d-flex justify-content-between">
                                <div><small class="text-white-50 d-block">Checks actuales</small><strong class="fs-4">{{ $card->current_checks }} / 10</strong></div>
                                <div class="text-end"><small class="text-white-50 d-block">Ciclo</small><strong class="fs-4">{{ $card->current_cycle }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="h5">Tu progreso</h3>
                            <div class="progress mb-3" style="height: 12px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $card->current_checks * 10 }}%"
                                    aria-valuenow="{{ $card->current_checks }}" aria-valuemin="0" aria-valuemax="10"></div>
                            </div>
                            <div class="row text-center g-3">
                                <div class="col-4"><strong class="fs-4 d-block">{{ $card->current_checks }}</strong><small class="text-body-secondary">Actuales</small></div>
                                <div class="col-4"><strong class="fs-4 d-block">{{ $card->total_checks }}</strong><small class="text-body-secondary">Históricos</small></div>
                                <div class="col-4"><strong class="fs-4 d-block">{{ $pendingRewards->count() }}</strong><small class="text-body-secondary">Premios</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><h3 class="h5 mb-0">Premios disponibles</h3></div>
                        <div class="card-body">
                            @forelse ($pendingRewards as $reward)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>{{ $reward->reward_name }}</strong>
                                        <span class="badge bg-warning-subtle text-warning">Pendiente</span>
                                    </div>
                                    <p class="small text-body-secondary mb-0 mt-2">{{ $reward->reward_description }}</p>
                                </div>
                            @empty
                                <p class="text-body-secondary mb-0">No tienes premios pendientes.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent"><h3 class="h5 mb-0">Premios utilizados</h3></div>
                        <div class="card-body">
                            @forelse ($usedRewards as $reward)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>{{ $reward->reward_name }}</strong>
                                        <span class="badge bg-success-subtle text-success">Utilizado</span>
                                    </div>
                                    <p class="small text-body-secondary mb-0 mt-2">
                                        {{ $reward->redeemed_at?->format('d/m/Y H:i') ?? 'Fecha no registrada' }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-body-secondary mb-0">Todavía no tienes premios utilizados.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Tu cliente está registrado, pero todavía no cuenta con una tarjeta de fidelización.
            </div>
        @endif
    </section>
@endsection

@section('styles')
    <style>
        .loyalty-public-card {
            background: linear-gradient(135deg, #6f2cff 0%, #251252 55%, #11101d 100%);
            box-shadow: 0 1.25rem 3rem rgba(78, 36, 145, .3);
        }
    </style>
@endsection
