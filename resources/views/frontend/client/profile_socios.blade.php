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
                            <h4 class="textotitulo mb-0">Zona Socios de Cosmic Bowling</h4>
                        </div>
                        <p class="mt-3" style="font-size: 0.95rem; text-align: justify; line-height: 1.6;">
                            Uno de nuestros mayores logros, es observar que las familias que nos visitan...
                            <br><br>
                        <p class="add">Zona Socios de Cosmic Bowling
                            Uno de nuestros mayores logros, es observar que las familias que nos visitan, no solo se
                            limitan a observar como disfrutan sus hijos o ellos mismos en los diferentes juegos, lo
                            motivador es ver a padres e hijos recoger la cesta de alimentos para los animales de la
                            granja juntos y lograr entablar todo un proceso de interacción con sus niños, guiándolos por
                            la granja y enseñándoles la forma de alimentar a cada especie. <br>Conocemos la importancia
                            de que los niños compartan con sus padres y familias cercanas un día de entretenimiento para
                            lograr una mayor integración familiar, para ello ofrecemos nuestras instalaciones, donde
                            pueden organizar actividades en fechas tan importantes para toda la familia. (*) Para
                            acceder a los beneficios del socio tendrá que activar su Código de Socio con 48 horas de
                            anticipación, sea renovación o afiliación.

                            <br><strong class="add">ES NECESARIO PRESENTAR SU DNI Y CÓDIGO DE SOCIO VIGENTE PARA ACCEDER
                                A TODOS LOS BENEFICIOS</strong><br><strong class="add">CLUB COSMIC BOWLING</strong>
                            <br><strong class="add">Beneficios del Socio*:</strong>
                            <br>
                            <span class="add">- Membresía válida por un año.<br>
                                - Una entrada gratis por cumpleaños del socio. La puedes obtener el mismo día del
                                onomástico o 6 días antes o 6 días después.
                                <br>
                                - Con tu código de socio, ingresa a la web “Club Cosmic Bowling” e imprime tus vales de
                                descuento todos los meses del año (tener en cuenta las indicaciones del cupón).
                                <br>
                                - Una entrada gratis para asistir a la Semana del Socio que te corresponde. De acuerdo a
                                tu número mágico, tendrás una fecha para utilizar esta entrada. Al año, habilitamos 4
                                semanas para nuestros afiliados, los cuales son chocolateados de acuerdo al último
                                número de su código de socio.
                                <br>
                                (*) Los beneficios son –únicamente- para el titular de la membresía.
                                <br>
                                (*) Es obligatorio presentar el código de socio vigente online y DNI en físico para
                                acceder a los beneficios correspondientes.
                                <br>
                                (*) Si tu código de socio venció, está por vencer o se perdió, puedes renovarlo. Costo:
                                S/25.00 (no incluye dispositivo inteligente)
                                <br>
                                (*) Los beneficios del socio se activan 48 horas después de realizada la afiliación o
                                renovación.
                                <br>
                                (*) La membresía puede adquirirse para niños y adultos. No hay edad límite.
                                <br>
                                (*) Los beneficios del socio no incluyen el dispositivo inteligente (pulsera o tarjeta).

                                <br>

                                Solo en COSMIC BOWLING .<span>
                        </p>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Columna de Información del Socio -->
            <div class="col-lg-4 mb-4">
                <div class="shadow border-primary card-custom-bg">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            {{-- <img src="{{ asset('imgclub/pen.png') }}" height="40" width="40" class="me-3"> --}}
                            <h5 class="textotitulo2 mb-0">Mi Información</h5>
                        </div>

                        <div class="info-list">
                            <label class="small text-muted fw-bold d-block mb-0">Nombre del socio:</label>
                            <p class="mb-3 fw-bold text-dark">{{ $socio->names_client }} {{ $socio->lastname_pat }}</p>

                            <label class="small text-muted fw-bold d-block mb-0">Dirección:</label>
                            <p class="mb-3 text-dark">{{ $socio->address_client }}</p>

                            <label class="small text-muted fw-bold d-block mb-0">Teléfono:</label>
                            <p class="mb-3 text-dark">{{ $socio->phone_client }}</p>

                            <label class="small text-muted fw-bold d-block mb-0">Correo Electrónico:</label>
                            <p class="mb-3 text-dark">{{ $socio->email_client }}</p>

                            <label class="small text-muted fw-bold d-block mb-0">Código de Socio:</label>
                            <p class="mb-0 text-primary fw-bold" style="font-size: 1.2rem;">
                                {{ $socio->partner->nTarjNumb ?? '00000000' }}</p>
                        </div>
                    </div>
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
