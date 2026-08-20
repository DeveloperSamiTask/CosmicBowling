@extends('frontend/layouts/app')

@section('content')
    @php
        $card = $client->loyaltyCard;
        $pendingRewards = $card ? $card->rewards->where('status', 'pending') : collect();
        $usedRewards = $card ? $card->rewards->where('status', 'used') : collect();
        $progress = $card ? min(100, max(0, $card->current_checks * 10)) : 0;
    @endphp

    <section class="container loyalty-portal pt-5 mt-5 pb-5">
        <div class="loyalty-shell mx-auto">
            <header class="d-flex justify-content-between align-items-center gap-3 mb-4">
                <div class="min-w-0">
                    <span class="small text-body-secondary">Hola,</span>
                    <h1 class="h4 text-truncate mb-1">{{ $client->names_client }}</h1>
                    <p class="small text-body-secondary mb-0">
                        {{ $client->sunatTypedoc?->name_doc ?? $client->document_id }} {{ $client->number_doc }}
                    </p>
                </div>
                <form method="POST" action="{{ route('loyalty.portal.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="ai-logout me-1"></i>Salir
                    </button>
                </form>
            </header>

            @if ($card)
                <nav class="loyalty-nav" aria-label="Secciones de fidelización">
                    <a href="#mi-tarjeta"><i class="ai-award"></i><span>Tarjeta</span></a>
                    <a href="#premios-pendientes">
                        <i class="ai-gift"></i><span>Pendientes</span>
                        @if ($pendingRewards->isNotEmpty())
                            <span class="loyalty-nav-count">{{ $pendingRewards->count() }}</span>
                        @endif
                    </a>
                    <a href="#premios-utilizados"><i class="ai-clock"></i><span>Historial</span></a>
                </nav>

                <div id="mi-tarjeta" class="loyalty-section">
                    <article class="loyalty-card mx-auto text-white">
                        <div class="loyalty-orbit loyalty-orbit-one"></div>
                        <div class="loyalty-orbit loyalty-orbit-two"></div>
                        <div class="position-relative h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="loyalty-brand">COSMIC BOWLING</span>
                                    <h2 class="h5 text-white mt-1 mb-0">Tarjeta de fidelización</h2>
                                </div>
                                <span class="loyalty-card-icon"><i class="ai-award"></i></span>
                            </div>

                            <div>
                                <p class="small text-white-50 mb-1">Número de tarjeta</p>
                                <p class="loyalty-card-number mb-4">{{ $card->card_number }}</p>
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <small class="text-white-50 d-block">Checks actuales</small>
                                        <strong class="fs-3">{{ $card->current_checks }} / 10</strong>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-white-50 d-block">Premios disponibles</small>
                                        <strong class="fs-3">{{ $pendingRewards->count() }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div class="card border-0 shadow-sm loyalty-progress-card mx-auto mt-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Tu progreso</strong>
                                <span class="small text-body-secondary">{{ $card->current_checks }} de 10 checks</span>
                            </div>
                            <div class="progress loyalty-progress mb-4" role="progressbar"
                                aria-label="Progreso de checks" aria-valuenow="{{ $card->current_checks }}"
                                aria-valuemin="0" aria-valuemax="10">
                                <div class="progress-bar" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="row text-center g-2">
                                <div class="col-4"><strong class="fs-4 d-block">{{ $card->current_checks }}</strong><small class="text-body-secondary">Actuales</small></div>
                                <div class="col-4 border-start border-end"><strong class="fs-4 d-block">{{ $card->total_checks }}</strong><small class="text-body-secondary">Históricos</small></div>
                                <div class="col-4"><strong class="fs-4 d-block">{{ $card->current_cycle }}</strong><small class="text-body-secondary">Ciclo</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="premios-pendientes" class="loyalty-section">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div><span class="small text-primary fw-semibold">TUS BENEFICIOS</span><h2 class="h5 mb-0">Premios pendientes</h2></div>
                        <span class="badge rounded-pill text-bg-primary">{{ $pendingRewards->count() }}</span>
                    </div>
                    <div class="row g-3">
                        @forelse ($pendingRewards as $reward)
                            <div class="col-md-6">
                                <article class="card border-0 shadow-sm h-100 reward-card reward-card-pending">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between gap-3 mb-2">
                                            <span class="reward-icon"><i class="ai-gift"></i></span>
                                            <span class="badge bg-warning-subtle text-warning align-self-start">Disponible</span>
                                        </div>
                                        <h3 class="h6 mb-2">{{ $reward->reward_name }}</h3>
                                        <p class="small text-body-secondary mb-0">{{ $reward->reward_description }}</p>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="loyalty-empty text-center">
                                    <i class="ai-gift d-block mb-2"></i>
                                    <p class="mb-0">Todavía no tienes premios pendientes.</p>
                                    <small class="text-body-secondary">Sigue acumulando checks en tus próximas compras.</small>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="premios-utilizados" class="loyalty-section">
                    <div class="mb-3"><span class="small text-primary fw-semibold">TU ACTIVIDAD</span><h2 class="h5 mb-0">Premios utilizados</h2></div>
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="list-group list-group-flush">
                            @forelse ($usedRewards as $reward)
                                <div class="list-group-item border-0 px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="reward-icon reward-icon-used flex-shrink-0"><i class="ai-check"></i></span>
                                        <div class="flex-grow-1 min-w-0">
                                            <strong class="d-block text-truncate">{{ $reward->reward_name }}</strong>
                                            <small class="text-body-secondary">Utilizado el {{ $reward->redeemed_at?->format('d/m/Y H:i') ?? 'Fecha no registrada' }}</small>
                                        </div>
                                        <span class="badge bg-success-subtle text-success d-none d-sm-inline">Utilizado</span>
                                    </div>
                                </div>
                            @empty
                                <div class="loyalty-empty text-center">
                                    <i class="ai-clock d-block mb-2"></i>
                                    <p class="mb-0">Todavía no tienes premios utilizados.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info shadow-sm border-0">Tu cliente está registrado, pero todavía no cuenta con una tarjeta de fidelización.</div>
            @endif
        </div>
    </section>
@endsection

@section('styles')
    <style>
        html { scroll-behavior: smooth; }
        .loyalty-portal { min-height: 80vh; }
        .loyalty-shell { max-width: 920px; }
        .min-w-0 { min-width: 0; }
        .loyalty-section { scroll-margin-top: 7rem; margin-bottom: 3.5rem; }
        .loyalty-nav { display: flex; justify-content: center; gap: .35rem; width: fit-content; margin: 0 auto 2rem; padding: .4rem; border: 1px solid var(--ar-border-color); border-radius: 999px; background: var(--ar-body-bg); box-shadow: 0 .5rem 1.5rem rgba(30,20,55,.08); }
        .loyalty-nav a { display: flex; align-items: center; gap: .45rem; padding: .6rem .9rem; color: var(--ar-body-color); font-size: .875rem; font-weight: 600; text-decoration: none; border-radius: 999px; }
        .loyalty-nav a:hover { color: var(--ar-primary); background: rgba(111,44,255,.08); }
        .loyalty-nav-count { display: inline-grid; place-items: center; min-width: 1.25rem; height: 1.25rem; padding: 0 .25rem; color: #fff; background: var(--ar-primary); border-radius: 999px; font-size: .7rem; }
        .loyalty-card { position: relative; overflow: hidden; width: 100%; max-width: 560px; min-height: 315px; padding: 2rem; border-radius: 1.5rem; background: linear-gradient(135deg,#7c32ff 0%,#3c1879 48%,#151020 100%); box-shadow: 0 1.5rem 3.5rem rgba(71,32,137,.32); }
        .loyalty-brand { font-size: .72rem; font-weight: 700; letter-spacing: .16em; color: rgba(255,255,255,.7); }
        .loyalty-card-number { font-size: clamp(1.1rem,4vw,1.5rem); font-weight: 600; letter-spacing: .08em; }
        .loyalty-card-icon { display: inline-grid; place-items: center; width: 3rem; height: 3rem; border: 1px solid rgba(255,255,255,.2); border-radius: 50%; background: rgba(255,255,255,.1); font-size: 1.45rem; }
        .loyalty-orbit { position: absolute; border: 1px solid rgba(255,255,255,.12); border-radius: 50%; }
        .loyalty-orbit-one { width: 280px; height: 280px; top: -150px; right: -80px; }
        .loyalty-orbit-two { width: 220px; height: 220px; right: -110px; bottom: -120px; }
        .loyalty-progress-card { max-width: 560px; border-radius: 1rem; }
        .loyalty-progress { height: .65rem; border-radius: 999px; }
        .loyalty-progress .progress-bar { border-radius: inherit; background: linear-gradient(90deg,#7c32ff,#d33dff); }
        .reward-card { border-radius: 1rem; }
        .reward-card-pending { border-left: 3px solid #f5b942 !important; }
        .reward-icon { display: inline-grid; place-items: center; width: 2.6rem; height: 2.6rem; color: var(--ar-primary); background: rgba(111,44,255,.1); border-radius: .8rem; font-size: 1.15rem; }
        .reward-icon-used { color: #168b56; background: rgba(22,139,86,.1); }
        .loyalty-empty { padding: 2.5rem 1rem; border: 1px dashed var(--ar-border-color); border-radius: 1rem; }
        .loyalty-empty > i { color: var(--ar-primary); font-size: 1.75rem; }
        @media (max-width: 767.98px) {
            .loyalty-portal { padding-bottom: 6.5rem !important; }
            .loyalty-section { scroll-margin-top: 6rem; margin-bottom: 2.75rem; }
            .loyalty-card { min-height: 265px; padding: 1.4rem; border-radius: 1.25rem; }
            .loyalty-card-icon { width: 2.6rem; height: 2.6rem; }
            .loyalty-nav { position: fixed; right: 1rem; bottom: max(1rem, env(safe-area-inset-bottom)); left: 1rem; z-index: 1030; justify-content: space-around; width: auto; max-width: 430px; margin: 0 auto; padding: .45rem; }
            .loyalty-nav a { position: relative; flex: 1; flex-direction: column; gap: .1rem; padding: .4rem .25rem; font-size: .7rem; }
            .loyalty-nav a i { font-size: 1.15rem; }
            .loyalty-nav-count { position: absolute; top: .1rem; margin-left: 1.25rem; }
        }
        @media (max-width: 370px) {
            .loyalty-card { min-height: 245px; padding: 1.15rem; }
            .loyalty-card h2 { font-size: .95rem; }
            .loyalty-card-number { margin-bottom: 1rem !important; }
        }
    </style>
@endsection
