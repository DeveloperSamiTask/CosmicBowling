@extends('frontend/layouts/app')

@section('content')
    <!-- Hero -->
    <section class="position-relative pt-sm-3 pt-md-5 mb-xl-3 mb-xxl-5">
        <div class="position-absolute top-0 end-0 overflow-hidden mt-n5 mt-md-0">
            <svg class="d-block mt-n5 mt-md-0 me-md-n5 me-xxl-0" width="1207" height="894" viewbox="0 0 1207 894"
                fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="color: var(--ar-gray-100);">
                <circle cx="639" cy="255" r="639"></circle>
            </svg>

        </div>
        <div class="container pt-5 pb-lg-4 pb-xl-5 mt-5">
            <div class="row pt-xl-4">
                <!-- Sticky image -->
                <div class="col-md-5 col-lg-6 offset-xxl-1 order-md-2 position-relative mb-3 mb-md-0 row pt-xl-4"
                    style="margin-top: -230px;">
                    <div class="d-none d-md-block position-absolute bottom-0 end-0 text-uppercase fw-bold lh-1 pb-5 mb-4 pe-3"
                        style="color: var(--ar-gray-100); font-size: 180px; transform: translate3d(0,0,0);">
                        <div class="d-flex mb-lg-3" style="padding-left: 160px;">
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="150">C
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="200">O
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="250">S
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="300">M
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="350">I
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="400" data-aos-delay="400">C
                            </div>
                        </div>
                        <div class="d-flex">
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="150">B
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="200">O
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="250">W
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="300">L
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="350">I
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="350">N
                            </div>
                            <div data-aos="flip-right" data-aos-duration="700" data-aos-offset="300" data-aos-delay="350">G
                            </div>
                        </div>
                    </div>
                    <div class="position-sticky z-3 top-0" style="padding-top: 115px;">
                        <div class="px-5 px-md-0 ps-xl-5 ms-lg-3">
                            <img src="{{ asset('frontend/img/app-icons/logo.svg') }}" width="695"
                                alt="Descripción del SVG">
                        </div>
                    </div>
                </div>
                <!-- Text -->
                @if ($errors->any())
                    <script>
                        alert("{{ $errors->first('msg') }}");
                    </script>
                @endif
                <div
                    class="col-md-7 col-lg-6 col-xxl-5 order-md-1 position-relative z-3 pb-sm-3 pb-md-5 pt-4 mb-md-5 mt-2 row pt-xl-4 align-items-center">
                    <form method="POST" action="{{ route('client.login.process') }}" target="_parent" name="form_login"
                        class="form">
                        @csrf
                        <h2 class="mb-3">Ingrese N° de Tarjeta</h2>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="txt_usuario" id= "txt_usuario"
                                placeholder="N° Tarjeta" required />
                        </div>

                        <h2 class="mb-3">Fecha de nacimiento</h2>
                        <div class="row mb-3">
                            <div class="col-4">
                                <input name="txt_anio" type="text" id="txt_anio" class="form-control"
                                    placeholder="Ingrese el año" required" />
                            </div>
                            <div class="col-4">
                                <input name="txt_mes" type="text" id="txt_mes" class="form-control"
                                    placeholder="Ingrese el mes"
                                    required " />
                                        </div>
                                        <div class="col-4">
                                            <input name="txt_dia" type="text" id="txt_dia" class="form-control" placeholder="Ingrese el dia" required" />
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">INGRESAR</button>

                            <button type="button" class="btn btn-block" data-bs-toggle="modal"
                                data-bs-target="#myModal"
                                style="
                                color: #fff;
                                background-color: #282727;
                                border-color: #282727;">Términos
                                y Condiciones
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content body">
                            <div class="modal-header center-both">
                                <h4 class="modal-title responsive-title w-100 text-center " id="exampleModalCenterTitle">
                                    CLUB COSMIC BOWLING
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">
                                    <strong style="font-size:medium;">¡ÚNETE A NUESTRO CLUB Y DISFRUTA DE GRANDES
                                        BENEFICIOS!
                                        🌟</strong>
                                </p>
                                <p>
                                    La afiliación es para todo un año. Aquí te contamos cómo puedes
                                    inscribirte y aprovechar nuestras promociones especiales:

                                </p>

                                <h6><strong>Beneficios del Socio:</strong></h6>
                                <ul>
                                    <li>
                                        🎉 <strong>Entrada GRATIS por tu cumpleaños:</strong> ¡Celebra con nosotros! Obtén
                                        tu
                                        entrada gratis en el mismo día de tu cumpleaños, ó 6 días antes ó 6 días después.
                                    </li>
                                    <li>
                                        🌟️ <strong>Entrada GRATIS en tu Semana de Socio:</strong> Cada
                                        año, podrás disfrutar de una entrada gratis en uno de los
                                        siguientes meses: Febrero, Mayo, Julio u Octubre, según el
                                        último dígito de tu código de socio, este será “tu número mágico”.

                                    </li>
                                    <li>
                                        🔔 <strong>Promociones exclusivas:</strong> Con tu código de socio,
                                        accede a promociones especiales y podrás visitarnos con tus amigos
                                        o familia.

                                    </li>
                                </ul>

                                <h6><strong>¿Qué incluye la entrada?</strong></h6>
                                <p>La entrada incluye, ingreso ilimitado a:</p>
                                {{-- <ul>
                                    <li>🎢 Juegos Mecánicos</li>
                                    <li>💧 Juegos Acuáticos</li>
                                    <li>🐐 Granja Interactiva</li>
                                    <li>🦚 Bosque de las Aves</li>
                                    <li>🌾 Biohuerto y Vivero</li>
                                    <li>🦕 Zona de Dinosaurios</li>
                                    <li>👶 Juegos para bebés</li>
                                </ul> --}}
                                {{-- <p>Disfruta de 35 mil metros de FULL DIVERSIÓN !! 🌟😊</p>
                                <p><strong>En verano, podrás también disfrutar de:</strong></p>
                                <ul>
                                    <li>🌊 Piscina con olas</li>
                                    <li>💦 Piscinas dinámicas para niños y adultos</li>
                                </ul> --}}

                                <h6><strong>¿Cómo Afiliarte?</strong></h6>
                                <p>Para convertirte en socio del “Club de Cosmic Bowling” solo
                                    tienes que seguir estos sencillos pasos:</p>
                                <ol>
                                    <li>
                                        <strong>Afíliate</strong> en nuestras instalaciones o envía un correo electrónico a:
                                        {{-- <strong>atencionalcliente@lagranjavilla.com</strong> --}}
                                    </li>
                                    <li><strong>Costo de afiliación:</strong> S/. 25 al año, lo que
                                        te da acceso a todos los beneficios durante ese periodo.</li>
                                    <small>(El costo del dispositivo inteligente no está incluido en la afiliación).</small>
                                </ol>


                                <h6><strong>¿Cuándo puedes empezar a disfrutar de los beneficios?</strong></h6>
                                <p>Una vez te afilies, podrás acceder a todos los beneficios 48 horas después de tu
                                    afiliación.
                                </p>

                                <h6><strong>¿Cómo utilizar tus beneficios?</strong></h6>
                                <p>El día de tu visita, recuerda traer:</p>
                                <ul>
                                    <li>Tu DNI físico.</li>
                                    <li>Código de socio vigente.</li>
                                    <li>
                                        Dispositivo inteligente (Sino cuentas con dispositivo inteligente
                                        lo puedes comprar en el mismo parque).

                                    </li>
                                </ul>

                                <p>
                                    ¡Y listo! Podrás disfrutar de todas las ventajas que ofrece ser parte de nuestro club.
                                    🤗
                                </p>
                            </div>

                            {{-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    style="
                                    color: #fff;
                                    background-color: #206219;
                                    border-color: #206219;
                                    ">
                                    Ya soy soci@
                                </button>
                                <a href="mailto:atencionalcliente@lagranjavilla.com?subject=QUIERO%20SER%20SOCIO"
                                    class="btn btn-secondary"
                                    style="
                                    color: #fff;
                                    background-color: #eab705;
                                    border-color: #eab705;">
                                    Quiero ser soci@
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script language="javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Verificar si ya se mostró antes
            // if (!localStorage.getItem('modalMostrado')) {
            // Seleccionamos el modal usando JS puro
            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            myModal.show();
            localStorage.setItem('modalMostrado', 'true');
            // }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const formulario = document.querySelector('form[name="form_login"]');

            formulario.addEventListener('submit', function(event) {
                const tarjeta = document.querySelector('input[name="txt_usuario"]').value;
                const dia = document.querySelector('input[name="txt_dia"]').value;
                const mes = document.querySelector('input[name="txt_mes"]').value;
                const anio = document.querySelector('input[name="txt_anio"]').value;

                const soloNumeros = /^\d+$/;

                if (!soloNumeros.test(tarjeta) || !soloNumeros.test(dia) || !soloNumeros.test(mes) || !
                    soloNumeros.test(anio)) {
                    alert("Error: Solo se permiten números en todos los campos.");
                    event.preventDefault();
                    return false;
                }
                // Si llega aquí, el formulario se envía solo porque el botón es type="submit"
            });
        });

        function iSubmitEnter(oEvento, oFormulario) {
            var iAscii;
            if (oEvento.keyCode) iAscii = oEvento.keyCode;
            else if (oEvento.which) iAscii = oEvento.which;
            else return false;

            if (iAscii == 13) { // Si presionan Enter (código 13)
                oFormulario.submit();
            }
            return true;
        }
    </script>

    {{-- Librerias --}}
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </main>
@endsection()

@section('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }

        .sin-salto {
            margin: 1px 0;
        }
    </style>
@endsection()

@section('scripts')
@endsection
