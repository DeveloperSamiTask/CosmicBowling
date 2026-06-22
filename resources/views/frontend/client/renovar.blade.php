@extends('frontend/layouts/app_socios')

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgclub/favicon.ico">
    <title>Socios Granja Villa</title>
</head>

@section('content')
    {{-- Estilos --}}
    <style>
        body {
            background: url("{{ asset('frontend/img/socios/fondo_socios.png') }}") no-repeat center center fixed;
            background-size: cover;
        }

        .main-container-socio {
            margin-top: 200px;
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
    </style>

    <!-- Page container -->
    <div class="container main-container-socio">
        <div class="row justify-content-center">
            <!-- Columna de Información General -->
            <div class="col-lg-7 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            {{-- <img src="{{ asset('imgclub/socios.png') }}" height="40" width="40" class="me-3"> --}}
                            <h4 class="textotitulo mb-0">RENUEVA TU MEMBRESIA</h4>
                        </div>
                        <p class="add">
                            Renueva tu membresía Para renovar tu código de socio, debes depositar* S/ 25.00 nuevos soles por
                            cada código de socio a renovar. Tenemos la siguiente cuenta en soles a su disposición para
                            efectuar el pago: -- Es importante recordar que
                            toda renovación se realiza con 48 horas de anticipación de ser un depósito o transferencia, de
                            esta manera podremos hacer las verificaciones correspondientes con el área contable. Luego de
                            realizar el abono deberá ingresar la fecha de abono, el número de operación y enviar el voucher
                            de pago escaneado al siguiente correo: --
                            (*) Para todos aquellos depósitos y/o transferencias realizados desde provincia por concepto de
                            renovación de membresía el cliente deberá asumir los cargos adicionales que genere la
                            transferencia a cuenta de --
                        </p>

                    </div>
                </div>
            </div>

            <!-- Columna de Información del Socio -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow border-primary card-custom-bg overflow-hidden" style="border-radius: 15px;">
                    <img src="{{ asset('frontend/img/socios/deposito_2.png') }}" alt="Imagen del socio" class="img-fluid"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>


        </div>
    </div>


    <button class="d-lg-none btn btn-sm fs-sm btn-primary w-100 rounded-0 fixed-bottom" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#sidebarAccount">
        <i class="ai-menu me-2"></i>
        Menú de Cuenta
    </button>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
@endsection()

@section('styles')
@endsection()

@section('scripts')
@endsection
