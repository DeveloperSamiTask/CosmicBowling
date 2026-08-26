@extends('frontend/layouts/app')

@section('content')
    <section class="container d-flex align-items-center justify-content-center pt-5 mt-5" style="min-height: 75vh;">
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white mb-3"
                            style="width: 64px; height: 64px;">
                            <i class="ai-award fs-2"></i>
                        </span>
                        <h1 class="h3 mb-2">Mi tarjeta</h1>
                        <p class="text-body-secondary mb-0">Ingresa tu documento para consultar tus checks y premios.</p>
                    </div>

                    <form method="POST" action="{{ route('loyalty.portal.authenticate') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" for="loyaltyDocument">Número de documento</label>
                            <input type="text" id="loyaltyDocument" name="document"
                                class="form-control form-control-lg text-uppercase @error('document') is-invalid @enderror"
                                value="{{ old('document') }}" maxlength="255" autocomplete="off" autofocus
                                placeholder="DNI, RUC o pasaporte">
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Consultar mi tarjeta
                        </button>
                    </form>

                    <p class="small text-body-secondary text-center mt-4 mb-0">
                        Este acceso permite únicamente consultar información de fidelización.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
