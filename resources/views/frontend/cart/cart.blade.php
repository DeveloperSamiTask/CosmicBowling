@extends('frontend/layouts/app')

@section('content')
    <div id="preloader" class="preloader hidden">
        <div class="section-center">
            <div class="section-path">
                <div class="globe">
                    <div class="wrapper">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container py-5 mt-4 mt-5 mt-lg-5 mb-lg-4 my-xl-5">
        <h2 class="h1 text-center pt-2 pt-sm-3">COMIENCE SU RESERVA DE BOWLING</h2>
        <p class="text-center pb-3 mb-3 mb-lg-4" id="xwyz" data-id={{ $subcategory->id_subcategory }}>
            {{ $subcategory->name_subcategory }}</p>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs flex-nowrap overflow-auto text-nowrap align-content-center w-100 mx-auto pb-3 mb-3 mb-lg-4 fs-5"
            role="tablist" style="max-width: 600px;">
            <li class="nav-item mb-0">
                <a id="tabReservation" class="nav-link active" href="#beginners" data-bs-toggle="tab" role="tab">
                    <i class="ai-calendar-plus me-2"></i> Reserva de Carril
                </a>
            </li>
            <li class="nav-item mb-0">
                <a id="tabBilling" class="nav-link disabled" href="#stretching" data-bs-toggle="tab" role="tab">
                    <i class="ai-card me-2"></i> Facturación
                </a>
            </li>
            <li class="nav-item mb-0">
                <a id="tabPayment" class="nav-link disabled" href="#fly-yoga" data-bs-toggle="tab" role="tab">
                    <i class="ai-wallet me-2"></i> Pago
                </a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content">

            <!-- Beginers -->
            <div class="tab-pane fade show active" id="beginners" role="tabpanel">
                <form class="needs-validation container position-relative z-2 pt-5 pb-lg-5 pb-md-4 pb-2" novalidate="">
                    <div class="row">
                        <div class="col-lg-7">
                            <h1 class="h2">QUIERO UNA RESERVA</h1>
                            <div class="alert alert-info d-flex mb-4">
                                <i class="ai-circle-info fs-xl me-2"></i>
                                <p class="mb-0">Disfrute de bolos, comida y cócteles, un lugar garantizado en las pistas y
                                    la experiencia de check-in más rápida.</a></p>
                            </div>

                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                <h4 class=" ms-1">1. Selecciona una fecha</h4>
                            </h3>

                            <div class="position-relative">
                                <input class="form-control date-picker pe-5  form-control-lg" type="text"
                                    placeholder="Choose date and time"
                                    data-datepicker-options='
                                    {"altInput": true, "altFormat": "F j, Y", "dateFormat": "Y-m-d", "defaultDate": "today", "minDate": "today", "locale" : "es"}'
                                    id="c-date">
                                <i class="ai-calendar position-absolute top-50 end-0 translate-middle-y me-3"></i>
                            </div>

                            <div class="alert d-flex alert-secondary text-center mt-3" role="alert">
                                <div class="w-100"><i class="ai-time fs-xl pe-1 me-2"></i> ¿Quieres reservar con más de 8
                                    días de antelación?
                                    <a href="#" class="alert-link text-primary">RESERVAR AHORA ></a>
                                </div>
                            </div>

                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                <h4 class="ms-1">2. Selecciona la hora</h4>
                            </h3>

                            <div class="container">
                                <div class="row" id="radioContainer">
                                </div>
                            </div>

                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                <h4 class=" ms-1">3. Selecciona el tiempo de juego </h4>
                            </h3>

                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-md-6 radio-container d-flex justify-content-center">
                                        <input type="radio" class="btn-check" id="hour1" name="c-hour" value="1"
                                            disabled>
                                        <label for="hour1" class="btn btn-outline-secondary btn-lg btn-custom">
                                            <span id="l-hour1">1 HORA</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-6 radio-container d-flex justify-content-center">
                                        <input type="radio" class="btn-check" id="hour2" name="c-hour" value="2"
                                            disabled>
                                        <label for="hour2" class="btn btn-outline-secondary btn-lg btn-custom">
                                            <span id=l-hour2>2 HORAS</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                <h4 class=" ms-1">4. Selecciona los integrantes</h4>
                                <span class="text-body-secondary">
                                    Añade hasta {{ $subcategory->limit_subcategory }} jugadores por
                                    {{ $subcategory->id_subcategory == 4 ? 'mesa' : 'pista' }}.
                                    Proporcione un recuento exacto de invitados. No podemos garantizar espacios para cambios
                                    en el tamaño del grupo.
                                    @if ($subcategory->id_subcategory == 4)
                                        <br><strong>Solo se aceptan personas mayores de edad para el uso de billar.</strong>
                                    @endif
                                </span>

                                <div class="count-input bg-gray rounded-3">
                                    <button class="btn btn-icon btn-lg fs-xl" type="button" data-decrement=""
                                        id="decrement-btn">-</button>
                                    <input class="form-control" type="number" value="1" id="c-guests">
                                    <button class="btn btn-icon btn-lg fs-xl" type="button" data-increment=""
                                        id="increment-btn">+</button>
                                </div>
                            </h3>
                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                <h4 class=" ms-1">5. Observación </h4>
                                <div class="input-group">
                                    <span class="input-group-text align-self-start mt-1">
                                        <i class="ai-message"></i>
                                    </span>
                                    <textarea id="observation" class="form-control" rows="3" maxlength="1000"
                                        placeholder="Observaciones"></textarea>
                                </div>
                                <small id="observation-counter" class="d-block text-end text-body-secondary mt-1">
                                    0 / 1000 caracteres
                                </small>
                            </h3>
                        </div>

                        <!-- Order summary -->
                        <div id="reservationDetailsStep1" class="col-lg-4 offset-lg-1 pt-1">
                            <div class="position-md-sticky top-0 ps-md-4 ps-lg-5 ps-xl-0">
                                <div class="d-none d-md-block" style="padding-top: 90px;"></div>
                                <h2 class="pb-2 pt-md-2 my-4">
                                    Resumen de la reserva
                                </h2>

                                <!-- Item -->
                                <div class="d-sm-flex align-items-center border-top py-4">
                                    <div class="w-100 pt-1 ps-sm-4">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <h3 class="h4 mb-2">
                                                    Fecha de reserva :
                                                </h3>
                                                <div class="d-sm-flex flex-wrap">
                                                    <div class="text-body-secondary  me-3">
                                                        <span class="text-ligth fs-5 fw-light" id="l-date"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top pt-4 mt-2 mb-4">
                                    <div class="input-group input-group-sm border-dashed" style="max-width: 410px;">
                                        <input class="form-control text-uppercase" type="text"
                                            placeholder="Código promocional" id="couponCode" name="couponCode">
                                        <button class="btn btn-secondary" type="button" id="btnCoupon" disabled>APLICAR
                                            CUPÓN</button>
                                    </div>
                                </div>
                                <ul class="list-unstyled py-3 mb-0">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span><strong id="n-lane"></strong> {{ $subcategory->name_subcategory }}
                                            <strong id='l-hours'></strong></span>
                                        <span class="fw-semibold ms-2" id="lp-lane">S/. 0.00</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Descuento de Cupón</span>
                                        <span class="fw-semibold ms-2" id="l-discount">- S/. 0.00</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span id="l-guests"></span> <span class="fw-semibold ms-2" id="lp-guests">S/.
                                            0.00</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span id="l-sure"></span> <span class="fw-semibold ms-2" id="lp-sure"></span>
                                    </li>
                                </ul>
                                <div class="d-flex align-items-center justify-content-between border-top fs-xl pt-4">
                                    Total:<span class="fs-3 fw-semibold text-dark ms-2" id="l-total">S/. 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-none d-lg-block pt-5 mt-n3">
                        <button id="btnNext" class="btn btn-lg btn-primary btnNext" type="button">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">SIGUIENTE: FACTURACIÓN</font>
                            </font>
                        </button>
                    </div>


                    <!-- Place an order button visible on screens < 992px -->
                    <div class="d-lg-none pb-2 mt-2 mt-lg-0 pt-4 pt-lg-5">
                        <button class="btn btn-lg btn-primary btnNext" type="button">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">SIGUIENTE: FACTURACIÓN</font>
                            </font>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stretching -->
            <div class="tab-pane fade " id="stretching" role="tabpanel">
                <div class="container position-relative z-2 pt-5 pb-lg-5 pb-md-4 pb-2">
                    <div class="row">
                        <div class="col-lg-7">
                            <h1 class="h2 pb-3">DATOS DE FACTURACIÓN</h1>
                            <!-- Checkout form fields -->
                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">
                                1.<span class="text-decoration-underline ms-1">Información de Facturación</span>
                            </h3>
                            <div class="row g-4 pb-4 pb-md-5 mb-3 mb-md-1">
                                <div class="col-sm-6">
                                    <label class="form-label fs-base" for="c-fn">Apellidos</label>
                                    <input class="form-control form-control-lg" type="text" placeholder="Apellidos"
                                        required="" disabled id="c-ln">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fs-base" for="c-ln">Nombres</label>
                                    <input class="form-control form-control-lg" type="text" placeholder="Nombres"
                                        required="" disabled id="c-fn">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fs-base" for="c-email">Correo Electrónico</label>
                                    <div class="position-relative"><i
                                            class="ai-mail fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                        <input class="form-control form-control-lg ps-5" type="email"
                                            placeholder="Correo Electrónico" required="" disabled id="c-email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fs-base" for="c-phone">Nº Celular</label>
                                    <div class="position-relative"><i
                                            class="ai-phone fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                        <input class="form-control form-control-lg ps-5" type="tel"
                                            data-format='{"numericOnly": true, "delimiters": ["+51 ", " ", " "], "blocks": [0, 3, 3, 3]}'
                                            placeholder="___ ___ __" disabled required="" name="c-phone"
                                            id="c-phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fs-base" for="c-email">Documento de Identidad</label>
                                    <div class="position-relative"><i
                                            class="ai-dashboard fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                        <input class="form-control form-control-lg ps-5" type="text"
                                            placeholder="Documento de Identidad" required="" disabled id="c-document">
                                    </div>
                                </div>
                            </div>
                            <h3 class="fs-base fw-normal text-body text-uppercase pb-2 pb-sm-3">2.<span
                                    class="text-decoration-underline ms-1">Tipo de Documento</span></h3>
                            <div class="row g-4 pb-4 pb-md-5 mb-3 mb-md-1">
                                <div class="d-flex">
                                    <div class="col-sm-6">
                                        <div class="me-3">
                                            <input type="radio" class="btn-check" id="weight1" name="weight"
                                                value="B" checked>
                                            <label for="weight1" class="btn btn-outline-secondary px-2">
                                                <span class="mx-1">Boleta Electrónica</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="me-3">
                                        <input type="radio" class="btn-check" id="weight2" name="weight"
                                            value="F">
                                        <label for="weight2" class="btn btn-outline-secondary px-2">
                                            <span class="mx-1">Factura Electrónica</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="collapse" id="collapseExample">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fa fa-university"></i> Ingrese datos para la facturación
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="mb-3">
                                                        <label for="rsocial" class="form-label">Razón Social</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-user"></i></span>
                                                            <input type="text" class="form-control" id="rsocial"
                                                                name="rsocial" placeholder="Razón Social" required
                                                                minlength="3">
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            Este campo es obligatorio
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <label for="ruc" class="form-label">RUC</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-ticket"></i></span>
                                                            <input type="text" class="form-control" id="ruc"
                                                                name="ruc" placeholder="RUC" required maxlength="20">
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            Por favor ingrese un valor correcto.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="dir" class="form-label">Dirección</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fa fa-location-arrow"></i></span>
                                                            <input type="text" class="form-control" id="dir"
                                                                name="dir" placeholder="Dirección" required
                                                                minlength="2">
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            Este campo es obligatorio
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input check-input" type="checkbox" id="save-info">
                                <label class="form-check-label" for="save-info-1">
                                    <span class="text-body-secondary">Su información personal se utilizará para procesar su
                                        reserva y para respaldar su experiencia en este sitio.
                                </label>
                            </div>


                        </div>

                        <div id="reservationDetailsStep2" class="col-lg-4 offset-lg-1 pt-1">

                        </div>
                    </div>

                    <div class="d-none d-lg-block pt-5 mt-n3">

                        <button class="btn btn-lg btn-primary btnBilling" type="button">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">PAGO RESERVA</font>
                            </font>
                        </button>
                    </div>

                    <div class="d-lg-none pb-2 mt-2 mt-lg-0 pt-4 pt-lg-5">

                        <button class="btn btn-lg btn-primary btnBilling" type="button">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">PAGO RESERVA</font>
                            </font>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Fly-yoga -->
            <div class="tab-pane fade" id="fly-yoga" role="tabpanel">
                <div class="row align-items-lg-center">
                    <div class="col-md-6 pb-4 pb-md-0 mb-2 mb-md-0">
                        <h1 class="h2 pb-3">DATOS DE FACTURACIÓN</h1>

                        <div class="card-body">
                            <div class="d-flex align-items-center mt-sm-n1 mb-0 mb-xl-3">
                                <i class="ai-wallet text-primary lead pe-1 me-2"></i>
                                <h2 class="h4 mb-0">Información de facturación</h2>
                            </div>
                            <div class="d-md-flex align-items-center">
                            </div>
                            <div class="row py-4 mb-2 mb-sm-3">
                                <div class="col-md-12 mb-4 mb-md-0">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0" id="typeDoc">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0" id="numberDoc">
                                                </td>
                                                <td class="border-0 text-dark fw-medium py-1 ps-1" id="numberDocLabel">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0" id="nameDoc">
                                                </td>
                                                <td class="border-0 text-dark fw-medium py-1 ps-1" id="nameDocLabel"></td>
                                            </tr>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0">
                                                    DIRECCION</td>
                                                <td class="border-0 text-dark fw-medium py-1 ps-1" id="addressDocLabel">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0">
                                                    NUMERO CELULAR</td>
                                                <td class="border-0 text-dark fw-medium py-1 ps-1" id="phoneDocLabel">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-0 text-body-secondary py-1 px-0">
                                                    CORREO ELECTRONICO</td>
                                                <td class="border-0 text-dark fw-medium py-1 ps-1" id="mailDocLabel"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Colocar opcion de seguro-->

                            <div class="pb-2 mt-2 mt-lg-0 pt-4 pt-lg-5">
                                <div class="form-check mb-4 cursor-pointer">
                                    <input class="form-check-input" type="checkbox" id="sure-btn">
                                    <label class="form-check-label fs-5" for="check-payment-desktop">
                                        <span class=" font-weight-bold">Seguro de Cambio de Fecha y/o Horario (+ S/
                                            10.00)</span>
                                    </label>
                                    <label for="form-check-label fs-6">
                                        <span>Al adquirir este seguro, el cliente podrá reprogramar su reserva una (1) sola
                                            vez.
                                            La reprogramación deberá solicitarse con un mínimo de 24 horas de anticipación,
                                            comunicándose al número <a href="https://wa.me/51995953955"
                                                target="_blank">+51 995 953 955</a>.</span>
                                    </label>
                                    <label for="form-check-label fs-6">
                                        El cambio será válido únicamente para el mismo día de la semana en el que fue
                                        adquirida originalmente la reserva y estará sujeto a disponibilidad de horarios.
                                    </label>
                                </div>
                            </div>


                            <div class="d-none d-lg-block pt-5 mt-n3">
                                <div class="form-check mb-4 open-modal cursor-pointer">
                                    <input class="form-check-input check-payment" type="checkbox"
                                        id="check-payment-desktop" disabled>
                                    <label class="form-check-label fs-6" for="check-payment-desktop">
                                        <span class="text-body-secondary">Acepto los</span>
                                        <a class="fw-medium" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalScroll">Términos y Condiciones</a>
                                    </label>
                                </div>
                            </div>

                            <div class="d-lg-none pb-2 mt-2 mt-lg-0 pt-4 pt-lg-5">
                                <div class="form-check mb-4 open-modal cursor-pointer">
                                    <input class="form-check-input check-payment" type="checkbox"
                                        id="check-payment-mobile" disabled>
                                    <label class="form-check-label fs-6" for="check-payment-mobile">
                                        <span class="text-body-secondary">Acepte los</span>
                                        <a class="fw-medium" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalScroll">Términos y Condiciones</a>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-2 mt-n3" id="visaNetContainer"></div>
                        </div>

                    </div>
                    <!-- Order summary -->
                    <div id="reservationDetailsStep3" class="col-lg-4 offset-lg-1 pt-1">

                    </div>


                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-login" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center w-100">
                    <h4 class="modal-title w-100">Ingresar a tu cuenta</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="pb-3 mb-3 mb-lg-4">
                        ¿Aún no tienes una cuenta?&nbsp;&nbsp;<a href="javascript:void(0)" id="btnRegister">Regístrate
                            aquí!</a></p>

                    <form id="form-login" class="needs-validation" novalidate="">
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <i class="ai-dashboard fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                <input id="number_login" name="number_login" class="form-control form-control-lg ps-5"
                                    type="text" placeholder="Número de Documento" required="">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="position-relative">
                                <i
                                    class="ai-lock-closed fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                <div class="password-toggle">
                                    <input id="password_login" name="password_login"
                                        class="form-control form-control-lg ps-5" type="password"
                                        placeholder="Contraseña" required="">
                                    <label class="password-toggle-btn" aria-label="Show/hide password">
                                        <input class="password-toggle-check" type="checkbox"><span
                                            class="password-toggle-indicator"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between pb-4">
                            <div class="form-check my-1">
                                <input class="form-check-input" type="checkbox" id="keep-signedin">
                                <label class="form-check-label ms-1" for="keep-signedin">Recuérdame</label>
                            </div>
                            <a class="fs-sm fw-semibold text-decoration-none my-1" href="javascript:void(0)"
                                id="btnRecover">
                                He olvidado mi contraseña</a>
                        </div>

                        <button id="login-button" class="btn btn-lg btn-primary w-100 mb-4"
                            type="submit">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrarse</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="pb-3 mb-3 mb-lg-4">Ya tienes una cuenta?&nbsp;&nbsp;<a href="javascript:void(0)"
                            id="btnLogin">Iniciar sesión
                            aquí!</a></p>
                    <div id="alert-container"></div>

                    <form class="needs-validation" id="form-register" novalidate="">
                        <div class="row row-cols-1 row-cols-sm-3">
                            <div class="col mb-4">
                                <input class="form-control form-control-lg" name="paternal" type="text"
                                    placeholder="Apellido Paterno" required="">
                            </div>
                            <div class="col mb-4">
                                <input class="form-control form-control-lg" name="maternal" type="text"
                                    placeholder="Apellido Materno" required="">
                            </div>
                            <div class="col mb-4">
                                <input class="form-control form-control-lg" name="firstname" type="text"
                                    placeholder="Nombres" required="">
                            </div>
                        </div>
                        <div class="row row-cols-1">
                            <div class="col-md-3 mb-4">
                                <select class="form-select form-select-lg" name="type_doc" id="select-input">
                                    <option value="01">DNI.</option>
                                    <option value="04">CARNET EXT.</option>
                                    <option value="07">PASAPORTE</option>
                                    <option value="00">OTROS</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-4">
                                <input id="number_doc" class="form-control form-control-lg numeric-input"
                                    name="number_doc" type="text" placeholder="Número de Documento" required="">
                                <div class="invalid-tooltip">Ingrese número de documento.</div>

                            </div>
                            <div class="col-md-5 mb-4">
                                <input id="mail_user" class="form-control form-control-lg" name="mail_user"
                                    type="email" placeholder="Correo Electrónico" required="">
                                <div class="invalid-tooltip">Ingrese correo electronico.</div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input class="form-control form-control-lg" name="birthday_user" type="text"
                                    data-format='{"date": true, "delimiter": "/", "datePattern": ["d", "m", "Y"]}'
                                    placeholder="Fec. Nacimiento (Dia/Mes/Año)" id="dateFormat" required="">
                                <div class="invalid-tooltip">Ingrese Fecha de Nacimiento.</div>

                            </div>
                            <div class="col mb-6">
                                <input class="form-control form-control-lg numeric-input" name="phone_user"
                                    type="text" placeholder="Número de Celular" required="">
                                <div class="invalid-tooltip">Ingrese Número de Celular.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <input class="form-control form-control-lg" name="district_user" type="text"
                                    placeholder="Distrito" required="">
                                <div class="invalid-tooltip">Ingrese Distrito.</div>

                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-2">
                            <div class="password-toggle mb-4">
                                <input class="form-control form-control-lg" name="current_password" type="password"
                                    placeholder="Contraseña" required="" minlength="8">
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox">
                                    <span class="password-toggle-indicator"></span>
                                </label>
                            </div>
                            <div class="password-toggle mb-4">
                                <input class="form-control form-control-lg" type="password"
                                    placeholder="Confirmar contraseña" required="" minlength="8">
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox">
                                    <span class="password-toggle-indicator"></span>
                                </label>
                            </div>
                            <div class="pb-4">
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="invalidCheck01" required>
                                    <label class="form-check-label ms-1" for="invalidCheck01">Acepto los <a
                                            href="#">Términos y condiciones</a></label>
                                </div>
                            </div>
                        </div>
                        <button id="register-button" class="btn btn-lg btn-primary w-100 mb-4"
                            type="submit">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-recover" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        ¿Olvidaste tu contraseña?</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="pb-2">Cambie su contraseña en tres sencillos pasos. Esto ayuda a mantener segura su nueva
                        contraseña.</p>
                    <ul class="list-unstyled pb-2 pb-lg-0 ">
                        <li class="d-flex mb-2">
                            <span class="text-primary fw-semibold me-2">1.</span>
                            Complete su dirección de correo electrónico a continuación.
                        </li>
                        <li class="d-flex mb-2">
                            <span class="text-primary fw-semibold me-2">2.</span>
                            Le enviaremos un código temporal por correo electrónico.
                        </li>
                        <li class="d-flex mb-2">
                            <span class="text-primary fw-semibold me-2">3.</span>
                            Utilice el código para cambiar su contraseña en nuestro sitio web seguro.
                        </li>
                    </ul>
                    <div class="card border-0">
                        <form class="card-body needs-validation" id="form-recover">
                            <div class="mb-4">
                                <div class="position-relative">
                                    <i
                                        class="ai-mail fs-lg position-absolute top-50 start-0 translate-middle-y text-light opacity-80 ms-3"></i>
                                    <input class="form-control form-control-lg ps-5" type="email" name="email_recover"
                                        placeholder="Ingresar correo electrónico" required="">
                                </div>
                            </div>
                            <button class="btn btn-light" type="submit" id="recover-button">Restablecer
                                contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalScroll" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Términos y Condiciones</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-sm">

                    <p><strong>En cuanto al pago y código de reserva:</strong></p>

                    <ul>
                        <li>La transacción se realiza a través de Niubiz, lo que asegura la autenticidad y confiabilidad del
                            procedimiento. Al confirmar las identidades de los titulares de las tarjetas, se reducen las
                            transacciones de comercio electrónico fraudulentas o en disputa, eliminando las inquietudes del
                            consumidor con respecto a las compras en línea.</li>

                        <li>Luego de realizar la compra, recibirá la reserva electrónica en el correo registrado. El cliente
                            acepta no divulgar ni compartir la información contenida en este correo por ningún medio, ya que
                            podría afectar su reserva.</li>

                        <li>Chaxra S.A.C. no se hace responsable por copias de la reserva electrónica que pudieran haber
                            utilizado previa o paralelamente, ya que la entera responsabilidad por la confidencialidad del
                            mismo, corresponde al cliente. En caso de generarse duplicados de la reserva electrónica, el
                            servicio de verificación de reservas sólo registrará la primera reserva, siendo todos los demás
                            considerados copias divulgadas por el cliente.</li>
                        <li>Es indispensable que el titular de la compra presente su DNI en físico el día de su reserva. Así
                            mismo, debe presentar el código enviado por mail después de la compra.</li>
                        <li>Las reservas se realizan con 24 h de anticipación, como mínimo. Son intransferibles, ni
                            revendibles. </li>
                        <li>Para realizar cualquier pago dentro del local con tarjeta de crédito es indispensable presentar
                            su DNI. En el caso de ser extranjero, debe presentar su pasaporte o carnet de extranjería.</li>
                        <li>No se realizan anulaciones ni devoluciones de compra. </li>
                        <li>Si no asiste a la reserva, esta se perderá. No se permiten las reprogramaciones. </li>


                    </ul>
                    <p><strong>En cuanto a su reserva:</strong></p>
                    <ul>

                        <li>Las pistas se activan a la hora de la reserva. Si llega tarde, podrá hacer uso del tiempo
                            restante.</li>
                        <li>Se recomienda estar en el local 20 minutos antes de la reserva.</li>
                        <li>La ubicación y asignación del número de la pista se asigna automáticamente por nuestro sistema
                            Brunswick. No se puede asignar una pista específica a solicitud del cliente. </li>
                        <li>Si los participantes son menores de edad, deben estar acompañados y supervisados por un adulto
                            responsable. </li>
                        <li>Ingresan máximo 5 personas por pista. Está normativa incluye bebés no caminantes, niños y
                            adultos. </li>
                        <li>Si son más de 5 personas (y menos de 10), tendrá que alquilar 2 pistas. </li>
                        <li>Una vez terminada la hora de bowling, los jugadores deberán salir de la zona de pistas y
                            devolver los zapatos antideslizantes. </li>
                        <li>Si realiza más de una reserva con diferentes datos, no se garantiza que las pistas sean
                            paralelas. Para tal caso, deberá indicar las pistas necesarias en una sola reserva. </li>
                        <li>Si desea agregar más tiempo de juego, deberá avisar con anticipación (antes de terminar su
                            reserva) a nuestro personal y se respetará la tarifa del día. El tiempo se agregará de acuerdo a
                            la disponibilidad del local. </li>
                        <li>Las reservas no incluyen la organización de eventos especiales, como cumpleaños o reuniones
                            grupales. No está permitido utilizar estas reservas para emitir invitaciones con nuestra marca o
                            coordinar celebraciones dentro del local.</li>
                        <li>El alquiler de equipos adicionales, como zapatos y/o rampas, está limitado al tiempo de uso de
                            la pista reservada. Al finalizar el juego, los equipos deberán ser devueltos de inmediato. No
                            está permitido el uso de estos fuera del tiempo reservado, ni en su salida del local. </li>
                        <li>En caso se presenten fallas técnicas de la pista, el establecimiento podrá modificar la reserva
                            y/o pista en coordinación con el cliente.</li>
                        <li>La reserva de pistas no incluye el acceso a las demás zonas de juego del establecimiento (como
                            la zona de billar o arcade).</li>
                        <li>Nuestro personal realizará una inspección de todas las mochilas, bolsos, morrales y canguros que
                            ingresen a nuestro establecimiento. Esto nos garantiza una estadía segura para todos. </li>
                        <li>Por seguridad, no está permitido el ingreso de alimentos, bebidas, mascotas, armas, pelotas,
                            globos, inflables, colores, cigarros, instrumentos musicales, juegos de propulsión a chorro de
                            agua, megáfonos, triciclos, bicicletas, scooters, skates, patines, carritos, envases de vidrio,
                            objetos que generen fuego o sean punzocortantes.</li>
                        <li>Si porta algún objeto que no ingrese al local, debe guardarlo en los lockers de ingreso al
                            precio de S/1.00. </li>
                        <li>Conforme a las recomendaciones para la prevención y cuidado de la salud dentro de nuestras
                            instalaciones, no se permite el ingreso de personas que demuestren signos de una enfermedad
                            eruptiva o infectocontagiosa, en virtud de preservar la salud de nuestra comunidad y del propio
                            cliente. Cabe indicar que el Ministerio de Salud cuenta con programas de vacunación gratuita y
                            que las recomendaciones de aislamiento temporal responden a condiciones médicas generales, a
                            efectos de evitar la transmisión y difusión del virus.</li>
                        <li>Por la privacidad de terceras personas, no se permiten las fotografías o grabaciones a personas
                            que no sean de su círculo amical o familiar.</li>
                        <li>No se permite la venta de ningún tipo de mercadería dentro de nuestras instalaciones. </li>
                    </ul>

                    <p><strong>IMPORTANTE:</strong></p>

                    <ul>
                        <li>Al realizar la reserva, el cliente se compromete a respetar la guía de seguridad del
                            establecimiento. Si vulnera alguna de las indicaciones dadas por la guía de seguridad o por
                            nuestro personal, el cliente será retirado del local sin lugar a reclamo ni devolución. Para más
                            detalle dale click aquí:<a href="https://cosmicbowling.com.pe/Guia-de-Seguridad"
                                target="_blank"> https://cosmicbowling.com.pe/Guia-de-Seguridad</a></li>
                        <li>Cualquier daño causado a nuestros equipos o instalaciones será responsabilidad del usuario, por
                            lo que deberá asumir el costo de reparación. </li>
                        <li>El uso de zapatos antideslizantes es obligatorio para todos los jugadores, así como portar
                            medias de algodón altas. No se permiten medias nylon, panties, ni tobilleras. </li>
                        <li>No se permite el intercambio ni el reemplazo de jugadores. Las personas fuera de la zona de
                            juego (espectadores) no pueden ingresar. </li>
                        <li>Los niños en la zona de espectadores deberán estar a cargo de un adulto responsable.</li>
                        <li>Es deber del cliente informarnos de alguna condición especial que alguno de los jugadores
                            presente. </li>
                        <li>Si alguno de los jugadores presenta signos de embriaguez o un comportamiento violento, será
                            retirado del local sin lugar a reclamo. El juego se finalizará sin reembolso ni reprogramación.
                        </li>
                    </ul>

                    <p><strong>Cumplir las normativas no es discriminación.</strong></p>

                    <p>Ante cualquier consulta, puede comunicarse al <a
                            href="https://api.whatsapp.com/send?phone=51995953955" target="_blank">(+51) 995 953 955</a> o
                        envíanos un correo a: <a href="mailto:cosmicbowling@gmail.com"
                            target="_blank">cosmicbowling@gmail.com</a></p>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary w-100 w-sm-auto mb-3 mb-sm-0" type="button"
                        data-bs-dismiss="modal">No Acepto</button>
                    <button class="btn btn-primary w-100 w-sm-auto ms-sm-3" type="button"
                        id="btn-accept-terms">Acepto</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="300">
            <div class="toast-header bg-success text-white">
                <i class="ai-circle-check fs-lg me-2"></i>
                <span class="me-auto">Reservas Cosmic Bowling</span>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body text-success" id="subjectToast"></div>
        </div>
    </div>

    <div class="position-fixed bottom-0 start-0 p-3" style="z-index: 11">
        <div id="warningToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="300">
            <div class="toast-header bg-warning text-black">
                <i class="ai-circle-check fs-lg me-2"></i>
                <span class="me-auto">Reservas Cosmic Bowling</span>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body text-black" id="subjectWarning"></div>
        </div>
    </div>
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('frontend/vendor/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/loader.css') }}">
    <style>
        .form-check-label {
            opacity: 1 !important;
            /* Asegura que el texto no se vuelva opaco */
            color: inherit;
            /* Mantiene el color original del texto */
        }

        .btn-custom {
            padding: 20px;
            font-size: 18px;
            width: 100%;
            text-align: center;
        }

        .radio-container {
            margin-bottom: 15px;
        }

        .btn-custom:hover {
            background-color: var(--bs-primary);
            color: #fff;
        }
    </style>
@endsection()

@section('scripts')
    <script src="{{ asset('frontend/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/flatpickr/lang/es.js') }}"></script>
    <script>
        const holiday = @json($isHoliday);

        const calendarItems = @json($hours);
    </script>
    <script src="{{ asset('frontend/js/pages/cart.js') }}?v={{ filemtime(public_path('frontend/js/pages/cart.js')) }}"></script>
@endsection
