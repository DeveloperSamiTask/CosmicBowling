<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO meta tags -->
    <title>Cosmic Bowling | {{ $title }}</title>
    <meta name="description"
        content="¿Buscas un plan de otro planeta? Juega boliche en un ambiente glow, con música moderna y mucha, pero mucha buena vibra">
    <meta name="keywords" content="bowling, perú, bolos, glow, ambiente, cosmicbowling, cosmic, juego de bolos">
    <meta name="author" content="Cosmic Bowling">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Cosmic Bowling - Un Plan de Otro Planeta">
    <meta property="og:description"
        content="¿Buscas un plan de otro planeta? Juega boliche en un ambiente glow, con música moderna y mucha, pero mucha buena vibra">
    <meta property="og:image" content="https://reservascosmicbowling.com.pe/frontend/img/app-icons/logo200x200.png">
    <!-- Replace with the actual image URL -->
    <meta property="og:url" content="https://reservascosmicbowling.com.pe/"> <!-- Replace with your website URL -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_PE"> <!-- Set locale to Spanish for Peru -->

    <!-- Webmanifest + Favicon / App icons -->
    <link rel="manifest" href="{{ asset('frontend/manifest.json') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/app-icons/favicon.png') }}">
    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('frontend/js/theme-switcher.js') }}"></script>

    <!-- Import Google font (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="{{ asset('frontend/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap') }}" rel="stylesheet"
        id="google-font">

    <!-- Vendor styles -->
    <link rel="stylesheet" media="screen" href="{{ asset('frontend/vendor/aos/dist/aos.css') }}">
    <link rel="stylesheet" media="screen"
        href="{{ asset('frontend/vendor/lightgallery/css/lightgallery-bundle.min.css') }}">

    <!-- Font icons -->
    <link rel="stylesheet" href="{{ asset('frontend/icons/around-icons.min.css') }}">

    <!-- Theme styles + Bootstrap -->
    <link rel="stylesheet" media="screen" href="{{ asset('frontend/css/theme.min.css') }}">

    <!-- Customizer generated styles -->
    <style id="customizer-styles"></style>

    <!-- Page loading styles -->
    <style>
        .page-loading {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            -webkit-transition: all .4s .2s ease-in-out;
            transition: all .4s .2s ease-in-out;
            background-color: #fff;
            opacity: 0;
            visibility: hidden;
            z-index: 9999;
        }

        [data-bs-theme="dark"] .page-loading {
            background-color: #121519;
        }

        .page-loading.active {
            opacity: 1;
            visibility: visible;
        }

        .page-loading-inner {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            text-align: center;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            -webkit-transition: opacity .2s ease-in-out;
            transition: opacity .2s ease-in-out;
            opacity: 0;
        }

        .page-loading.active>.page-loading-inner {
            opacity: 1;
        }

        .page-loading-inner>span {
            display: block;
            font-family: "Inter", sans-serif;
            font-size: 1rem;
            font-weight: normal;
            color: #6f788b;
        }

        [data-bs-theme="dark"] .page-loading-inner>span {
            color: #fff;
            opacity: .6;
        }

        .page-spinner {
            display: inline-block;
            width: 2.75rem;
            height: 2.75rem;
            margin-bottom: .75rem;
            vertical-align: text-bottom;
            background-color: #d7dde2;
            border-radius: 50%;
            opacity: 0;
            -webkit-animation: spinner .75s linear infinite;
            animation: spinner .75s linear infinite;
        }

        header.navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .page-spinner {
            background-color: rgba(255, 255, 255, .25);
        }

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                opacity: 1;
                -webkit-transform: none;
                transform: none;
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                opacity: 1;
                -webkit-transform: none;
                transform: none;
            }
        }
    </style>


    @yield('styles')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F0PK219TL7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-F0PK219TL7');
    </script>

    <!-- Page loading scripts -->
    <script>
        (function() {
            window.onload = function() {
                const preloader = document.querySelector('.page-loading')
                preloader.classList.remove('active')
                setTimeout(function() {
                    preloader.remove()
                }, 1500)
            }
        })()
    </script>

</head>


<!-- Body -->

<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-WKV3GT5" height="0" width="0"
            style="display: none; visibility: hidden;" title="Google Tag Manager"></iframe>
    </noscript>


    <!-- Page loading spinner -->
    <div class="page-loading active">
        <div class="page-loading-inner">
            <div class="page-spinner"></div>
            <span>Cargando...</span>
        </div>
    </div>
    <!-- Page wrapper -->
    <main class="page-wrapper">

        <!-- Navbar. Remove 'fixed-top' class to make the navigation bar scrollable with the page -->
        <header class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <!-- Navbar brand (Logo) -->
                {{--  --}}
                <a class="navbar-brand pe-sm-3">
                    <span class="text-primary flex-shrink-0 me-2">
                        <img src="{{ asset('frontend/img/app-icons/logo.svg') }}" width="95" height="92"
                            alt="Logo Cosmic Bowling">
                    </span>
                    <span class="d-none d-sm-inline"></span>
                </a>
                <!-- Theme switcher -->
                {{-- <div class="form-check form-switch mode-switch order-lg-2 me-3 me-lg-4 ms-auto" data-bs-toggle="mode">
                    <input class="form-check-input" type="checkbox" id="theme-mode">
                    <label class="form-check-label" for="theme-mode">
                        <i class="ai-sun fs-lg"></i>
                    </label>
                    <label class="form-check-label" for="theme-mode">
                        <i class="ai-moon fs-lg"></i>
                    </label>
                </div> --}}

                <div id="account-info" class="nav align-items-center order-lg-3 ms-n1 me-3 me-sm-0">
                    @if (Auth::guard('client')->check())
                        @php
                            $client = Auth::guard('client')->user();
                        @endphp
                        <div class="dropdown nav d-block order-lg-2 ms-auto">
                            <a class="nav-link d-flex align-items-center p-0" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="border rounded-circle" src="{{ asset('frontend/img/avatar/51.jpg') }}"
                                    width="48" alt="{{ $client->names_client }}">
                                <div class="d-none d-sm-block ps-2">
                                    <div class="fs-xs lh-1 opacity-60">Hola,</div>
                                    <div class="fs-sm dropdown-toggle">
                                        {{ explode(' ', trim($client->names_client))[0] }}</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end my-1">
                                <h6 class="dropdown-header fs-xs fw-medium text-body-secondary text-uppercase pb-1">
                                    Cuenta</h6>
                                <a class="dropdown-item" href="{{ route('client.profile') }}"><i
                                        class="ai-user-check fs-lg opacity-70 me-2"></i>Perfil</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('client.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="ai-logout fs-lg opacity-70 me-2"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Mostrar el enlace de cuenta cuando no está autenticado -->
                        <a class="nav-link fs-4 p-2 mx-sm-1 d-none d-sm-flex" href="{{ route('client.login') }}"
                            role="button
                            aria-label="Account">
                            <i class="ai-user"></i>
                        </a>
                    @endif
                </div>

                <!-- Mobile menu toggler (Hamburger) -->
                <button class="navbar-toggler ms-sm-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar collapse (Main navigation) -->
                <nav class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav navbar-nav-scroll me-auto" style="--ar-scroll-height: 220px;">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.profileSocio') }}">Datos del socio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.renovar') }}">Renovar Membresia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.promociones') }}">Promociones</a>
                        </li>
                        @if (Auth::guard('client')->check())
                            @php
                                $client = Auth::guard('client')->user();
                            @endphp
                        @else
                            <li class="nav-item border-top mt-2 py-2 d-sm-none">
                                <!-- Mostrar el enlace de cuenta cuando no está autenticado -->
                                <a class="nav-link" href="{{ route('client.login') }}"
                                    role="button
                                    aria-label="Account">
                                    <i class="ai-user fs-lg me-2"></i> Iniciar Sesión
                                </a>
                            </li>
                        @endif
                    </ul>

                </nav>
            </div>
        </header>

        @yield('content')


    </main>


    {{-- <!-- Footer -->
    <footer class="footer bg-dark position-relative py-5" data-bs-theme="dark">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(255, 255, 255, .03);">
        </div>
        <div class="container position-relative z-2 pb-xl-2 pt-2 pt-sm-3 pt-xl-4 pt-xxl-5">
            <div class="row pb-5 pt-md-3 pt-lg-4 mb-md-3 mb-lg-4">
                <div class="col-md-6 col-xxl-5 pb-3 pb-md-0 mb-2 mb-sm-3 mb-md-0">
                    <div class="h1 pb-3">Somos la mejor opción de <b class="text-primary">entretenimiento
                            familiar.</b>
                    </div>
                    <div class="d-flex flex-wrap pb-4 pb-xl-5 mb-md-2 mb-lg-3">
                        <div class="d-flex pe-3 me-3 mb-2">
                            <i class="ai-check-alt fs-4 mt-n1 me-2 text-primary"></i>
                            <span class="text-body">Adrenalina</span>
                        </div>
                        <div class="d-flex pe-3 me-3 mb-2">
                            <i class="ai-check-alt fs-4 mt-n1 me-2 text-primary"></i>
                            <span class="text-body">Diversión</span>
                        </div>
                        <div class="d-flex mb-2">
                            <i class="ai-check-alt fs-4 mt-n1 me-2 text-primary"></i>
                            <span class="text-body">Música</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-xl-4 offset-md-1 offset-xl-2 offset-xxl-3">
                    <div class="row row-cols-2">
                        <div class="col">
                            <!--  <ul class="nav flex-column pb-4 mb-2 pb-md-5 mb-lg-1">
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Quiénes Somos</a>
                                </li>
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Instalaciones</a>
                                </li>
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Tarifa</a>
                                </li>
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Servicios</a>
                                </li>
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Contacto</a>
                                </li>
                                <li class="nav-item mt-1">
                                    <a class="nav-link py-1 px-0" href="#">Preguntas Frecuentes</a>
                                </li>
                            </ul>
                            <div class="d-flex">
                                <a class="btn btn-icon btn-sm btn-secondary btn-instagram rounded-circle me-3 bg-primary"
                                    href="https://www.instagram.com/cosmic_bowling/profilecard/?igsh=dXJzZGV4eDg5OG1z"
                                    target="_blank" aria-label="Instagram">
                                    <i class="ai-instagram"></i>
                                </a>
                                <a class="btn btn-icon btn-sm btn-secondary rounded-circle me-3 bg-primary"
                                    href="https://www.facebook.com/cosmicbowling1?mibextid=LQQJ4d" target="_blank"
                                    aria-label="Facebook">
                                    <i class="ai-facebook"></i>
                                </a>
                                <a class="btn btn-icon btn-sm btn-secondary btn-primary rounded-circle bg-primary"
                                    href="https://www.tiktok.com/@cosmic_bowling?_t=8pfSQKZBpxO&_r=1" target="_blank"
                                    aria-label="Tik Tok">
                                    <i class="ai-tiktok"></i>
                                </a>
                            </div>-->
                        </div>
                        <div class="col">
                            <br>
                            <ul class="list-unstyled mb-0">
                                <li class="nav flex-nowrap mb-3">
                                    <i class="ai-whatsapp lead text-primary me-2"></i>
                                    <a class="nav-link fw-normal p-0 mt-n1" href="https://wa.link/l9mreh"
                                        target="_blank">Escríbenos por whastapp</a>
                                </li>
                                <li class="nav flex-nowrap mb-3">
                                    <i class="ai-mail lead text-primary me-2"></i>
                                    <a class="nav-link fw-normal p-0 mt-n1" href="mailto:cosmicbowling@gmail.com">Mándanos
                                        un correo</a>
                                </li>
                            </ul>
                            <div class="d-flex">
                                <a class="btn btn-icon btn-sm btn-secondary btn-instagram rounded-circle me-3 bg-primary"
                                    href="https://www.instagram.com/cosmic_bowling/profilecard/?igsh=dXJzZGV4eDg5OG1z"
                                    target="_blank" aria-label="Instagram">
                                    <i class="ai-instagram"></i>
                                </a>
                                <a class="btn btn-icon btn-sm btn-secondary rounded-circle me-3 bg-primary"
                                    href="https://www.facebook.com/cosmicbowling1?mibextid=LQQJ4d" target="_blank"
                                    aria-label="Facebook">
                                    <i class="ai-facebook"></i>
                                </a>
                                <a class="btn btn-icon btn-sm btn-secondary btn-primary rounded-circle bg-primary"
                                    href="https://www.tiktok.com/@cosmic_bowling?_t=8pfSQKZBpxO&_r=1" target="_blank"
                                    aria-label="Tik Tok">
                                    <i class="ai-tiktok"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="nav fs-sm mb-0">
                <span class="text-body-secondary">&copy; All rights reserved. Made by</span>
                <a class="nav-link fw-normal p-0 ms-1" href="https://createx.studio/" target="_blank"
                    rel="noopener">Cosmic Bowling</a>
            </p>
        </div>
    </footer> --}}


    <!-- Back to top button -->
    <a class="btn-scroll-top" href="#top" data-scroll="" aria-label="Scroll back to top">
        <svg viewbox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <circle cx="20" cy="20" r="19" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-miterlimit="10"></circle>
        </svg>
        <i class="ai-arrow-up"></i>
    </a>


    <!-- Vendor scripts: JS libraries and plugins -->
    <script src="{{ asset('frontend/vendor/%40lottiefiles/lottie-player/dist/lottie-player.js') }}"></script>
    <script src="{{ asset('frontend/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/shufflejs/dist/shuffle.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('frontend/vendor/lightgallery/lightgallery.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/lightgallery/plugins/video/lg-video.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/cleave.js/dist/cleave.min.js') }}"></script>

    @yield('scripts')

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('frontend/js/theme.min.js') }}"></script>

</body>

</html>
