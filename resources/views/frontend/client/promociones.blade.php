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
            /* O un color de fondo si quieres */
        }
    </style>

    <!-- Page container -->
    <div class="container main-container-socio">
        <div class="row justify-content-center">

            <!-- Cupón 1 -->
            <div class="col-lg-10 mb-5">
                <div class="card card-custom-bg p-3">
                    {{-- public\img\cupones\cupon_1.jpg --}}
                    <div class="img-cupon-container">
                        <img src="{{ url('img/cupones/cupon_1.jpg') }}" alt="Cupón 1">
                    </div>

                    @if (in_array(1, $cuponesImpresos))
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            Ya imprimiste este cupón
                        </button>
                    @else
                        <button class="btn btn-primary btn-lg w-100"
                            onclick="imprimir(1, '{{ url('img/cupones/cupon_1.jpg') }}')">
                            Imprimir Cupón
                        </button>
                    @endif
                </div>
            </div>

            <!-- Cupón 2 -->
            <div class="col-lg-10 mb-5">
                <div class="card card-custom-bg p-3">
                    <div class="img-cupon-container">
                        <img src="{{ url('img/cupones/cupon_2.jpg') }}" alt="Cupón 1">
                    </div>

                    @if (in_array(2, $cuponesImpresos))
                        <button class="btn btn-secondary btn-lg w-100 " disabled>
                            Ya imprimiste este cupón
                        </button>
                    @else
                        <button class="btn btn-primary btn-lg w-100"
                            onclick="imprimir(2, '{{ url('img/cupones/cupon_2.jpg') }}')">
                            Imprimir Cupón
                        </button>
                    @endif
                </div>
            </div>

            {{-- Datos ocultos para la impresión --}}

            <input type="hidden" id="names_cli" value="{{ $client->names_client }} {{ $client->lastname_pat }}">



            <input type="hidden" id="code_cli" value="{{ $client->number_doc ?? '' }}">


            <div class="col-lg-10 mb-5 text-white">
                <strong>
                    * Revise su impresora antes de imprimir, recuerde seguir todas las indicaciones detalladas en cada cupón
                    para que puedan ser validados el día de su visita, si el cupón carece de fecha de vencimiento o no está
                    impreso en forma completa, no tendrá validez.
                </strong>
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

    <script>
        function imprimir(cuponId, imgUrl) {
            var ventana = window.open('', 'PRINT', 'height=400,width=600');
            var names = document.getElementById('names_cli').value;
            var code = document.getElementById('code_cli').value;

            // Agregamos estilos CSS específicos para la impresión
            var estilos = `
            <style>
                body { text-align: center; font-family: sans-serif; margin: 0; padding: 10px; }
                img { max-width: 70%; height: auto; display: block; margin: 0 auto; }
                h2 { font-size: 20px; margin: 15px 0; }
                @media print {
                    @page { size: auto; margin: 5mm; }
                    body { margin: 0; }
                }
            </style>
        `;
            ventana.document.write('<html><head>' + estilos + '</head><body>');
            ventana.document.write('<img src="' + imgUrl + '">');
            ventana.document.write('<br>');
            ventana.document.write('<h2>Nombre y Apellido: ' + names + '</h2>');
            ventana.document.write('<br>');
            ventana.document.write('<h2>N° de Documento: ' + code + '</h2>');
            ventana.document.write('</body></html>');

            ventana.document.close();
            ventana.focus();

            // Usamos un pequeño timeout para asegurar que la imagen cargó antes de imprimir
            ventana.onload = function() {
                setTimeout(function() {
                    ventana.print();
                    ventana.close(); // Se cierra sola después de imprimir
                }, 500);
            }

            // Tu lógica de fetch sigue igual...
            fetch("{{ route('client.imprimir') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cupon_id: cuponId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.icon === 'success') location.reload();
                });
        }
    </script>
@endsection()

@section('styles')
@endsection()

@section('scripts')
@endsection
