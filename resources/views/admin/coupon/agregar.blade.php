@extends('admin/layouts/layout')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="py-3 mb-4">
            Agregar Cupones
        </h4>

        {{-- /*
            debe copiar parte de esto
        */ --}}

        <style>
            .main-container-socio {
                margin-top: 80px;
                /* Esto empuja el contenido hacia abajo del navbar */
                min-height: 80vh;
                /* Asegura que ocupe espacio hacia abajo */
            }

            .card {
                background-color: rgba(255, 255, 255, 0.9);
                /* Un poco de transparencia para que se vea el fondo */
                border: none;
                border-radius: 15px;
            }

            .card-custom-bg {
                /* Fondo blanco con 90% de opacidad */
                background-color: rgba(255, 255, 255, 0.9) !important;
                /* Un ligero desenfoque al fondo para que se vea más moderno (opcional) */
                backdrop-filter: blur(5px);
                border-radius: 15px;
                border: none;
            }

            /* Para que los textos resalten mejor sobre el blanco */
            .info-list p {
                color: #333 !important;
            }

            .img-cupon-container {
                height: 300px;
                /* Aquí controlas el tamaño estándar (puedes cambiarlo a 250px, 400px, etc.) */
                width: 100%;
                overflow: hidden;
                /* Corta lo que sobre para que no se desborde */
                border-radius: 10px;
                margin-bottom: 1rem;
            }

            .img-cupon-container img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                /* ¡Aquí está el truco! */
                object-position: center;
                background-color: transparent;
            }
        </style>

        <!-- Page container -->
        <div class="container main-container-socio">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-custom-bg p-4">
                        <h4 class="mb-4">Subir Cupones del Mes</h4>

                        <form action="{{ route('coupons.subirCupon') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                @php
                                    // Definimos los servicios tal cual están en tu BD
                                    $servicios = [
                                        1 => 'Pistas Generales',
                                        2 => 'Pista VIP',
                                        3 => 'Pista Easy Duo VIP',
                                        4 => 'Billar',
                                    ];
                                @endphp

                                @foreach ($servicios as $id => $nombre)
                                    <div class="col-md-6 mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <label class="form-label fw-bold">Cupón para: {{ $nombre }} <span
                                                        class="text-danger">*</span></label>

                                                <input type="file" name="cupones[{{ $id }}]"
                                                    class="form-control" accept="image/*" required>

                                                {{-- Vista previa dinámica --}}
                                                @if ($cuponesActuales && isset($cuponesActuales[$id]))
                                                    <div class="mt-2 text-center">
                                                        <small class="text-muted d-block mb-1">Cupón actual de este
                                                            mes:</small>
                                                        <img src="{{ url($cuponesActuales[$id]->path_cupon) }}"
                                                            class="img-fluid rounded border" style="max-height:150px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                                <i class="mdi mdi-upload me-1"></i> Guardar Todos los Cupones
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection()

    @section('styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    @endsection()

    @section('scripts')
        <!-- Page JS -->
        <script src="{{ asset('vendor/libs/autosize/autosize.js') }}"></script>
        <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
        <script src="{{ asset('js/pages/coupon.js') }}"></script>
    @endsection
