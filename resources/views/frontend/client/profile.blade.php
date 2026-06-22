@extends('frontend/layouts/app')

@section('content')
    <!-- Page container -->
    <div class="container py-5 mt-4 mt-lg-5 mb-lg-4 my-xl-5">
        <div class="row pt-sm-2 pt-lg-0">

            <!-- Sidebar (offcanvas on sreens < 992px) -->
            <aside class="col-lg-3 pe-lg-4 pe-xl-5 mt-n3">
                <div class="position-lg-sticky top-0">
                    <div class="d-none d-lg-block" style="padding-top: 105px;"></div>
                    <div class="offcanvas-lg offcanvas-start" id="sidebarAccount">
                        <button class="btn-close position-absolute top-0 end-0 mt-3 me-3 d-lg-none" type="button"
                            data-bs-dismiss="offcanvas" data-bs-target="#sidebarAccount" aria-label="Close"></button>
                        <div class="offcanvas-body">
                           <div class="pb-2 pb-lg-0 mb-4 mb-lg-5">
                                <img class="d-block rounded-circle mb-2" src="{{ asset('frontend/img/avatar/51.jpg') }}"
                                    width="80" alt="{{ $client->names_client }} {{$client->lastname_pat}}">
                                <h3 class="h5 mb-1">{{ $client->names_client }} {{$client->lastname_pat}}</h3>
                                <p class="fs-sm text-body-secondary mb-0">{{ $client->email_client }}</p>
                            </div>
                            <nav class="nav flex-column pb-2 pb-lg-4 mb-3">
                                <h4 class="fs-xs fw-medium text-body-secondary text-uppercase pb-1 mb-2">Cuenta</h4>
                                <a class="nav-link fw-semibold py-2 px-0 active" href="account-overview.html">
                                    <i class="ai-user-check fs-5 opacity-60 me-2"></i>
                                    Perfil
                                </a>
                                <!--<a class="nav-link fw-semibold py-2 px-0 disabled" href="account-settings.html">
                                    <i class="ai-settings fs-5 opacity-60 me-2"></i>
                                    Configuración
                                </a>-->
                            </nav>

                            <nav class="nav flex-column">
                                <a class="nav-link fw-semibold py-2 px-0" href="javascript:void(0)" id="logoutbtn">
                                    <i class="ai-logout fs-5 opacity-60 me-2"></i>
                                    Cerrar sesión
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </aside>


            <!-- Page content -->
            <div class="col-lg-9 pt-4 pb-2 pb-sm-4">
                <h1 class="h2 mb-4 mt-4">Perfil</h1>

                <!-- Basic info -->
                <section class="card border-0 py-1 p-md-2 p-xl-3 p-xxl-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mt-sm-n1 pb-4 mb-0 mb-lg-1 mb-xl-3">
                            <i class="ai-user text-primary lead pe-1 me-2"></i>
                            <h2 class="h4 mb-0">Información básica</h2>
                            <!-- <a class="btn btn-sm btn-secondary ms-auto" href="account-settings.html">
                                                                                <i class="ai-edit ms-n1 me-2"></i>
                                                                                Editar Información
                                                                            </a>-->
                        </div>
                        <div class="d-md-flex align-items-center">
                            <div class="d-sm-flex align-items-center">
                                <div class="rounded-circle bg-size-cover bg-position-center flex-shrink-0"
                                    style="width: 80px; height: 80px; background-image: url(frontend/img/avatar/51.jpg);">
                                </div>
                                <div class="pt-3 pt-sm-0 ps-sm-3">
                                    <h3 class="h5 mb-2">{{ $client->names_client }}<i
                                            class="ai-circle-check-filled fs-base text-success ms-2"></i></h3>
                                    <div
                                        class="text-body-secondary fw-medium d-flex flex-wrap flex-sm-nowrap align-iteems-center">
                                        <div class="d-flex align-items-center me-3">
                                            <i class="ai-mail me-1"></i>
                                            {{ $client->email_client }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row py-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="border-0 text-body-secondary py-1 px-0">Nº Celular</td>
                                            <td class="border-0 text-dark fw-medium py-1 ps-3">{{ $client->phone_client }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- Orders -->
                <section class="card border-0 py-1 p-md-2 p-xl-3 p-xxl-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mt-sm-n1 pb-4 mb-0 mb-lg-1 mb-xl-3">
                            <i class="ai-cart text-primary lead pe-1 me-2"></i>
                            <h2 class="h4 mb-0">Reservas realizadas</h2>
                            <!-- <a class="btn btn-sm btn-secondary ms-auto" href="account-orders.html">Ver Todo</a> -->
                        </div>

                        @foreach ($reservations as $index => $reservation)
                            <!-- Order -->
                            <div class="accordion-item border-top mb-0">
                                <div class="accordion-header">
                                    <a class="accordion-button d-flex fs-4 fw-normal text-decoration-none py-3 collapsed"
                                        href="#order{{ $index }}" data-bs-toggle="collapse" aria-expanded="false"
                                        aria-controls="order{{ $index }}">
                                        <div class="d-flex justify-content-between w-100" style="max-width: 440px;">
                                            <div class="me-3 me-sm-4">
                                                <div class="fs-sm text-body-secondary">#{{ $reservation->reservation_code }}
                                                </div>
                                                <span class="badge bg-info bg-opacity-10 text-success fs-xs">
                                                    Reservado
                                                </span>
                                            </div>
                                            <div class="me-3 me-sm-4">
                                                <div class="d-none d-sm-block fs-sm text-body-secondary mb-2">Día/Hora
                                                    Reservado
                                                </div>
                                                <div class="d-sm-none fs-sm text-body-secondary mb-2">Date</div>
                                                <div class="fs-sm fw-medium text-dark">{{ $reservation->date_reserved }}
                                                    {{ date('g:i A', strtotime($reservation->hour_init)) }}
                                                </div>
                                            </div>
                                            <div class="me-3 me-sm-4">
                                                <div class="fs-sm text-body-secondary mb-2">Total</div>
                                                <div class="fs-sm fw-medium text-dark">S/. {{ $reservation->amount }}</div>
                                            </div>
                                        </div>
                                        <div class="accordion-button-img d-none d-sm-flex ms-auto">
                                            <div class="mx-1">
                                                <img src="{{ Storage::url('subcategory/' . $reservation->subcategory->img_subcategory) }}"
                                                    width="48" alt="Product">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="order{{ $index }}"
                                    data-bs-parent="#orders">
                                    <div class="accordion-body">
                                        <div class="table-responsive pt-1">
                                            <table class="table align-middle w-100" style="min-width: 450px;">
                                                <tbody>
                                                    <tr>
                                                        <td class="border-0 py-1 px-0">
                                                            <div class="d-flex align-items-center">
                                                                <a class="d-inline-block flex-shrink-0 bg-secondary rounded-1 p-md-2 p-lg-3"
                                                                    href="javascript:void(0)l">
                                                                    <img src="{{ Storage::url('subcategory/' . $reservation->subcategory->img_subcategory) }}"
                                                                        width="110" alt="Product">
                                                                </a>
                                                                <div class="ps-3 ps-sm-4">
                                                                    <h4 class="h6 mb-2">
                                                                        <a
                                                                            href="javascript:void(0)l">{{ $reservation->description }}</a>
                                                                    </h4>
                                                                    <div class="text-body-secondary fs-sm me-3">
                                                                        Integrantes:
                                                                        <span
                                                                            class="text-dark fw-medium">{{ $reservation->quantity_guests }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="border-0 py-1 pe-0 ps-3 ps-sm-4">
                                                            <div class="fs-sm text-body-secondary mb-2">Horas</div>
                                                            <div class="fs-sm fw-medium text-dark">
                                                                {{ $reservation->quantity_hours }}</div>
                                                        </td>
                                                        <td class="border-0 py-1 pe-0 ps-3 ps-sm-4">
                                                            <div class="fs-sm text-body-secondary mb-2">Hora Reservada
                                                            </div>
                                                            <div class="fs-sm fw-medium text-dark">
                                                                {{ date('g:i A', strtotime($reservation->hour_init)) }}
                                                            </div>
                                                        </td>
                                                        <td class="border-0 text-end py-1 pe-0 ps-3 ps-sm-4">
                                                            <div class="fs-sm text-body-secondary mb-2">Total</div>
                                                            <div class="fs-sm fw-medium text-dark">
                                                                S/. {{ $reservation->amount }}</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </section>
            </div>
        </div>
    </div>

    <button class="d-lg-none btn btn-sm fs-sm btn-primary w-100 rounded-0 fixed-bottom" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#sidebarAccount">
        <i class="ai-menu me-2"></i>
        Menú de Cuenta
    </button>
@endsection()

@section('styles')
@endsection()

@section('scripts')
@endsection
