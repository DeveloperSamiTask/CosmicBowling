@extends('admin/layouts/layout')

@section('content')
    <!-- Content -->


    <div class="container-xxl flex-grow-1 container-p-y">


        <div class="card app-calendar-wrapper">
            <div class="row g-0">
                <!-- Calendar Sidebar -->
                <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                    <div class="p-3 pb-2 my-sm-0 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas"
                                data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                <i class="mdi mdi-plus me-1"></i>
                                <span class="align-middle">Agregar Horario</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- inline calendar (flatpicker) -->
                        <div class="inline-calendar"></div>

                        <hr class="container-m-nx my-4">

                        <!-- Filter -->
                        <div class="mb-4">
                            <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                        </div>

                        <div class="form-check form-check-secondary mb-3">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all"
                                checked="">
                            <label class="form-check-label" for="selectAll">Todos</label>
                        </div>
                        <div class="form-check form-check-success mb-3">
                            <input class="form-check-input input-filter" type="checkbox" id="select-holiday"
                                data-value="holiday" checked="">
                            <label class="form-check-label" for="select-holiday">Pista General</label>
                        </div>
                        <div class="app-calendar-events-filter">
                            <div class="form-check form-check-danger mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-personal"
                                    data-value="personal" checked="">
                                <label class="form-check-label" for="select-personal">Pista VIP</label>
                            </div>

                            <!-- <div class="form-check mb-3">
                                                                                                                                            <input class="form-check-input input-filter" type="checkbox" id="select-business"
                                                                                                                                                data-value="business" checked="">
                                                                                                                                            <label class="form-check-label" for="select-business">Business</label>
                                                                                                                                        </div>-->

                            <div class="form-check form-check-warning mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-family"
                                    data-value="family" checked="">
                                <label class="form-check-label" for="select-family">Pista Duo VIP</label>
                            </div>

                            <div class="form-check form-check-info">
                                <input class="form-check-input input-filter" type="checkbox" id="select-etc"
                                    data-value="etc" checked="">
                                <label class="form-check-label" for="select-etc">Billares</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar Sidebar -->

                <!-- Calendar & Modal -->
                <div class="col app-calendar-content">
                    <div class="card shadow-none border-0 ">
                        <div class="card-body pb-0">
                            <!-- FullCalendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->
                    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                        aria-labelledby="addEventSidebarLabel">
                        <div class="offcanvas-header border-bottom">
                            <h5 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                                @csrf
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventTitle" name="eventTitle"
                                        placeholder="Event Title">
                                    <label for="eventTitle">Titulo</label>
                                </div>
                                @php
                                    $prefixes = [
                                        1 => 'PG',
                                        2 => 'PV',
                                        3 => 'PEDV',
                                        4 => 'BILLAR',
                                    ];
                                @endphp
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="select2 select-event-label form-select" id="eventLabel"
                                        name="eventLabel">
                                        <option value="">-----</option>
                                        @foreach ($subcategories as $row)
                                            <option data-label={{ $row->color_subcategory }}
                                                data-id={{ $row->id_subcategory }} data-monday={{ $row->price_monday }}
                                                data-tuesday={{ $row->price_tuesday }}
                                                data-wednesday={{ $row->price_wednesday }}
                                                data-thursday={{ $row->price_thursday }}
                                                data-friday={{ $row->price_friday }}
                                                data-saturday={{ $row->price_saturday }}
                                                data-sunday={{ $row->price_sunday }} value={{ $row->extend_subcategory }}
                                                data-prefix="{{ $prefixes[$row->id_subcategory] ?? 'DEFAULT' }}">
                                                {{ $row->name_subcategory }}</option>
                                        @endforeach
                                    </select>
                                    <label for="eventLabel">Grupos</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventStartDate" name="eventStartDate"
                                        placeholder="Start Date">
                                    <label for="eventStartDate">Hora Inicio</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventEndDate" name="eventEndDate"
                                        placeholder="End Date">
                                    <label for="eventEndDate">Último turno</label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventPrice" name="eventPrice"
                                        placeholder="00.00">
                                    <label for="eventTitle">Precio</label>
                                </div>

                                <div class="form-floating form-floating-outline mb-4">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i
                                                class="mdi mdi-numeric-9-plus-box-multiple-outline"></i></span>

                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="quantity" name="quantity"
                                                placeholder="" value="0">
                                            <label for="quantity">Cantidad de Pistas Disponibles</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4 gap-2">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary btn-add-event me-sm-2 me-1">
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-cancel me-sm-0 me-1"
                                            data-bs-dismiss="offcanvas">Cancel</button>
                                    </div>
                                    <button class="btn btn-outline-danger btn-delete-event d-none">Borrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Calendar & Modal -->
            </div>
        </div>
    </div>

    <!-- Content -->
@endsection()

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/libs/fullcalendar/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/app-calendar.css') }}">
@endsection()

@section('scripts')
    <script src="{{ asset('vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('js/app-calendar-events.js') }}"></script>
    <script src="{{ asset('js/app-calendar.js') }}"></script>
@endsection
