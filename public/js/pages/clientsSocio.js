"use strict";
$(function () {
    let t, a, n;
    n = (
        isDarkStyle
            ? ((t = config.colors_dark.borderColor),
                (a = config.colors_dark.bodyBg),
                config.colors_dark)
            : ((t = config.colors.borderColor),
                (a = config.colors.bodyBg),
                config.colors)
    ).headingColor;
    var e,
        s = $(".datatables-users"),
        i = $(".select2"),
        l = "app-user-view-account.html",
        o = {
            0: { title: "Pending", class: "bg-label-warning" },
            1: { title: "Active", class: "bg-label-success" },
        };
    i.length &&
        ((i = i),
            select2Focus(i),
            i.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select Country",
                dropdownParent: i.parent(),
            })),
        s.length &&
        (e = s.DataTable({
            ajax: "/be/tableClientsSocio",
           columns: [
        { data: "id" },
        { data: "names" },
        { data: "type_doc" },
        { data: "number_doc" },

        { data: "dEmisDate" },
        { data: "dCaduDate" },

        { data: "confirmation_email" },
        { data: "phone_number" },
        { data: "affiliation" },
        { data: "validado" },
    ],
            columnDefs: [
                {
                    className: "control",
                    searchable: !1,
                    orderable: !1,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (e, t, a, n) {
                        return "";
                    },
                },
                {
                    targets: 9,
                    render: function (e, t, a, n) {
                        return e === "Validado"
                            ? "<div class='d-inline-flex' data-bs-toggle='tooltip' data-bs-html='true' title='Validado " +
                            moment(a.verified).format(
                                "DD/MM/YYYY HH:mm:ss"
                            ) +
                            "'>" +
                            '<span class="badge rounded-pill bg-label-success">' +
                            e +
                            "</span>" +
                            "</div>"
                            : "<div class='d-inline-flex' data-bs-toggle='tooltip' data-bs-html='true' title='No validado'>" +
                            '<span class="avatar avatar-sm"> <span class="avatar-initial rounded-circle bg-label-danger"><i class="mdi mdi-alert-outline"></i></span></span>' +
                            "</div>";
                    },
                },
            ],
            order: [[2, "desc"]],
            dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 gap-3"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: {
                sLengthMenu: "Show _MENU_",
                search: "",
                searchPlaceholder: "Search..",
            },
            buttons: [
                {
                    extend: "collection",
                    className:
                        "btn btn-label-secondary dropdown-toggle me-3 waves-effect waves-light",
                    text: '<i class="mdi mdi-export-variant me-1"></i> <span class="d-none d-sm-inline-block">Exportar</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                                (n = ""),
                                                $.each(e, function (e, t) {
                                                    void 0 !== t.classList &&
                                                        t.classList.contains(
                                                            "user-name"
                                                        )
                                                        ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                        : void 0 ===
                                                            t.innerText
                                                            ? (n += t.textContent)
                                                            : (n += t.innerText);
                                                }),
                                                n);
                                    },
                                },
                            },
                            customize: function (e) {
                                $(e.document.body)
                                    .css("color", n)
                                    .css("border-color", t)
                                    .css("background-color", a),
                                    $(e.document.body)
                                        .find("table")
                                        .addClass("compact")
                                        .css("color", "inherit")
                                        .css("border-color", "inherit")
                                        .css("background-color", "inherit");
                            },
                        },

                        // NIVEL 1 → botón Agregar Socio (AL MISMO NIVEL que el collection)

                        {
                            extend: "csv",
                            text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                                (n = ""),
                                                $.each(e, function (e, t) {
                                                    void 0 !== t.classList &&
                                                        t.classList.contains(
                                                            "user-name"
                                                        )
                                                        ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                        : void 0 ===
                                                            t.innerText
                                                            ? (n += t.textContent)
                                                            : (n += t.innerText);
                                                }),
                                                n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "excel",
                            text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                                (n = ""),
                                                $.each(e, function (e, t) {
                                                    void 0 !== t.classList &&
                                                        t.classList.contains(
                                                            "user-name"
                                                        )
                                                        ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                        : void 0 ===
                                                            t.innerText
                                                            ? (n += t.textContent)
                                                            : (n += t.innerText);
                                                }),
                                                n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                                (n = ""),
                                                $.each(e, function (e, t) {
                                                    void 0 !== t.classList &&
                                                        t.classList.contains(
                                                            "user-name"
                                                        )
                                                        ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                        : void 0 ===
                                                            t.innerText
                                                            ? (n += t.textContent)
                                                            : (n += t.innerText);
                                                }),
                                                n);
                                    },
                                },
                            },
                        },
                        {
                            extend: "copy",
                            text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                format: {
                                    body: function (e, t, a) {
                                        var n;
                                        return e.length <= 0
                                            ? e
                                            : ((e = $.parseHTML(e)),
                                                (n = ""),
                                                $.each(e, function (e, t) {
                                                    void 0 !== t.classList &&
                                                        t.classList.contains(
                                                            "user-name"
                                                        )
                                                        ? (n +=
                                                            t.lastChild
                                                                .firstChild
                                                                .textContent)
                                                        : void 0 ===
                                                            t.innerText
                                                            ? (n += t.textContent)
                                                            : (n += t.innerText);
                                                }),
                                                n);
                                    },
                                },
                            },
                        },
                    ],
                },
                // {
                //     text: '<i class="mdi mdi-plus me-1"></i> Agregar Socio',
                //     className: 'btn btn-primary waves-effect waves-light',
                //     attr: {
                //         'data-bs-toggle': 'modal',
                //         'data-bs-target': '#addNewCoupon'
                //     }
                // },
                {
                    text: '<i class="mdi mdi-plus me-1"></i> Renovar Socio',
                    className: 'btn btn-primary waves-effect waves-light',
                    attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#renew-modal'
                    }
                }
                , {
                    text: '<i class="mdi mdi-plus me-1"></i> Editar Socio',
                    className: 'btn btn-primary waves-effect waves-light',
                    attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#edit-modal'
                    }
                }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (e) {
                            return "Details of " + e.data().full_name;
                        },
                    }),
                    type: "column",
                    renderer: function (e, t, a) {
                        a = $.map(a, function (e, t) {
                            return "" !== e.title
                                ? '<tr data-dt-row="' +
                                e.rowIndex +
                                '" data-dt-column="' +
                                e.columnIndex +
                                '"><td>' +
                                e.title +
                                ":</td> <td>" +
                                e.data +
                                "</td></tr>"
                                : "";
                        }).join("");
                        return (
                            !!a &&
                            $('<table class="table"/><tbody />').append(a)
                        );
                    },
                },
            },
            initComplete: function () {
                this.api()
                    .columns(2)
                    .every(function () {
                        var t = this,
                            a = $(
                                '<select id="UserPlan" class="form-select text-capitalize"><option value="">Filtra por documento</option></select>'
                            )
                                .appendTo(".user_plan")
                                .on("change", function () {
                                    var e = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
                                    t.search(
                                        e ? "^" + e + "$" : "",
                                        !0,
                                        !1
                                    ).draw();
                                });
                        t.data()
                            .unique()
                            .sort()
                            .each(function (e, t) {
                                a.append(
                                    '<option value="' +
                                    e +
                                    '">' +
                                    e +
                                    "</option>"
                                );
                            });
                    }),
                    this.api()
                        .columns(7)
                        .every(function () {
                            var t = this,
                                a = $(
                                    '<select id="FilterTransaction" class="form-select text-capitalize"><option value="">Filtra por estado</option></select>'
                                )
                                    .appendTo(".user_status")
                                    .on("change", function () {
                                        var e =
                                            $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );
                                        // Aplicar el filtro según el valor seleccionado
                                        t.search(
                                            e ? "^" + e + "$" : "", // Buscar solo el valor exacto
                                            true, // Usar regex para la búsqueda
                                            false // Ignorar la búsqueda de palabras
                                        ).draw();
                                    });
                            // Agregar opciones al select
                            t.data()
                                .unique()
                                .sort()
                                .each(function (d) {
                                    a.append(
                                        '<option value="' +
                                        d +
                                        '" class="text-capitalize">' +
                                        d +
                                        "</option>"
                                    );
                                });
                        });
            },
        })),
        e.on("draw.dt", function () {
            [].slice
                .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                .map(function (a) {
                    return new bootstrap.Tooltip(a, {
                        boundary: document.body,
                    });
                });
        }),
        setTimeout(() => {
            $(".dataTables_filter .form-control").removeClass(
                "form-control-sm"
            ),
                $(".dataTables_length .form-select").removeClass(
                    "form-select-sm"
                );
        }, 300);


  // ---------------- nueva logica
    function blockUI() {
        $.blockUI({
            message: '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
            css: { backgroundColor: "transparent", border: "0" },
            overlayCSS: { opacity: 0.5 },
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    //  ------------ variables genericas
    let today = new Date();
    let day = String(today.getDate()).padStart(2, "0");
    let month = String(today.getMonth() + 1).padStart(2, "0");
    let year = today.getFullYear();
    let formattedDate = `${day}-${month}-${year}`;

    const f = document.getElementById("partnerForm");

    // ------------ logica para insertar socio
    // insertar validacion
    const fv = FormValidation.formValidation(f, {
        fields: {
            pattername: {
                validators: {
                    notEmpty: { message: "Ingresa el apellido paterno" },
                }
            },
            mattername: {
                validators: {
                    notEmpty: { message: "Ingresa el apellido materno" },
                }
            },
            names: {
                validators: {
                    notEmpty: { message: "Ingresa los nombres" },
                }
            },
            doc: {
                validators: {
                    notEmpty: {
                        message: "Ingresa el Nº documento del socio",
                    },
                },
            },
            birthdate: {
                validators: {
                    notEmpty: {
                        message: "Ingresa la fecha de nacimiento"
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'El formato debe ser Año-Mes-Día (Ej: 1998-03-25)'
                    }
                }
            },
            affiliation: {
                validators: {
                    notEmpty: { message: "Ingresa la ficha de afiliación" }
                }
            },
            initdate: {
                validators: {
                    notEmpty: { message: "Ingresa la fecha de inicio" },
                },
            },
            // enddate: {
            //     validators: {
            //         notEmpty: { message: "Ingresa la fecha de vencimiento" },
            //     },
            // },
            mail: {
                validators: {
                    notEmpty: { message: "Ingresa el e-mail" },
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({ eleValidClass: "is-valid" }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    });

    //insertar
    fv.on('core.form.valid', function () {
        // ESTO SOLO SE EJECUTA SI PASÓ TODAS LAS REGLAS (SIN LETRAS, TODO LLENO)
        console.log("¡Formulario validado por el core! Iniciando Fetch...");

        blockUI(); // Tu función de carga

        const f = document.getElementById("partnerForm");
        let formData = new FormData(f);

        fetch("/be/insertSocio", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: formData,
            // method: 'POST',
            // headers: {
            //     'Content-Type': 'application/json',
            //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            // },
            // body: formData
        })
            .then(response => {
                if (!response.ok) throw response; // Si el back explota, vamos al catch
                return response.json();
            })
            .then(data => {
                $.unblockUI();
                Toast.fire({ icon: data.icon, title: data.message });

                if (data.icon === "success") {
                    $(".modal").modal("hide");
                    f.reset(); // Limpia los inputs
                    fv.resetForm(true); // Limpia los checks verdes/rojos
                }
            })
            .catch(error => {
                $.unblockUI();
                console.error("Error en la petición:", error);
                Toast.fire({
                    icon: "error",
                    title: "Error al comunicar con el servidor"
                });
            });
    });

    let formattedToday =
        today.getDate().toString().padStart(2, "0") +
        "-" +
        (today.getMonth() + 1).toString().padStart(2, "0") +
        "-" +
        today.getFullYear(),
        csrfToken = $('meta[name="csrf-token"]').attr("content");


    $("#initdate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: today,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                let startDate = new Date(selectedDates[0]);
                let endDate = new Date(startDate);

                endDate.setFullYear(endDate.getFullYear() + 1);
                endDate.setDate(endDate.getDate() - 1);

                let day = String(endDate.getDate()).padStart(2, "0");
                let month = String(endDate.getMonth() + 1).padStart(2, "0");
                let year = endDate.getFullYear();

                let formattedEndDate = `${day}-${month}-${year}`;

                // 1. Actualiza el valor del input
                $("#enddate").val(formattedEndDate);

                // 2. IMPORTANTE: Dile a Flatpickr del enddate que cambie su fecha interna
                // o FormValidation pensará que sigue vacío o con la fecha de hoy
                document.querySelector("#enddate")._flatpickr.setDate(formattedEndDate);

                // 3. Revalidar para que FormValidation no se quede con el error de "vacío"
                fv.revalidateField('enddate');
            }
        },
    });

    $("#enddate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: formattedDate,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
    });

    // ------------ logica para renovar un socio
    function formatDateToDMY(dateString) {
        if (!dateString) return '';
        // Si la fecha viene como "2026-03-26"
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Si falla, devuelve lo que sea que llegó

        const day = String(date.getDate() + 1).padStart(2, '0'); // +1 por el desfase de zona horaria de JS
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    let renewInitDatePicker = $("#renewInitdate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: today,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                let renewalDate = selectedDates[0];

                // Calcular fecha de vencimiento (1 año - 1 día)
                let expirationDate = new Date(renewalDate);
                expirationDate.setFullYear(expirationDate.getFullYear() + 1); // +1 año
                expirationDate.setDate(expirationDate.getDate() - 1); // -1 día

                // Extraer día, mes y año
                let day = expirationDate.getDate().toString().padStart(2, "0");
                let month = (expirationDate.getMonth() + 1)
                    .toString()
                    .padStart(2, "0"); // Meses van de 0 a 11
                let year = expirationDate.getFullYear();

                let formattedEndDate = `${day}-${month}-${year}`; // Formato d-m-Y

                // Asignar la fecha de vencimiento
                $("#renewEnddate").val(formattedEndDate);
            }
        },
    });

    $("#renewEnddate").flatpickr({
        dateFormat: "d-m-Y",
        defaultDate: formattedDate,
        locale: {
            firstDayOfWeek: 1,
            rangeSeparator: " Hasta ",
            weekdays: {
                shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                ],
            },
            months: {
                shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                ],
            },
        },
    });

    /*Esto es lo que busca al momendo de renovar creo  */
    $("#btnSearch").on("click", function () {
        blockUI();
        $.ajax({
            url: "searchPartner",
            type: "post",
            data: {
                search: $("#searchInput").val(),
                select: $("#selectSearch").val(),
                _token: csrfToken,
            },
        })
            .done((data) => {


                console.log("Entrando en el search de apoderado");

                try {
                    if (client.birthday_client) {
                        $("#birthdateRenew").val(formatDateToDMY(client.birthday_client));
                    }
                } catch (e) {
                    console.error("Error en la fecha, pero seguimos...", e);
                }

                console.log("Lo que llega del server SEARCH:", data); // <--- MIRA ESTO EN LA CONSOLA (F12)


                if (data.icon) {
                    // Si hay un mensaje de advertencia, mostrarlo y salir
                    Toast.fire({
                        icon: data.icon,
                        title: data.message,
                    });

                    $("#renew-modal").modal("hide");
                    $("#addNewCoupon").modal("show");

                    $.unblockUI();
                    return;
                }

                let client = data;

                $("#hiddenCode").val(client.id_client || '');

                // 2. Mapeo del Socio (QUITAMOS EL [0] y usamos la relación partner)
                // if (client.partner) {
                //     $("#codeRenew").val(client.partner.nTarjNumb);
                // } else {
                //     $("#codeRenew").val("SIN TARJETA");
                // }

                // 3. Mapeo de Nombres (Nombres nuevos de tu tabla)
                $("#namesRenew").val(`${client.lastname_pat} ${client.names_client}`);
                $("#docRenew").val(client.number_doc || '' );

                // 4. Mapeo de Fecha
                // Ojo: Si ya viene en formato Y-m-d, asegúrate que formatDateToDMY lo entienda
                $("#birthdateRenew").val(formatDateToDMY(client.birthday_client || ''));

                // 2. Datos de Socio (Tabla 'client_socio' - Relación 'partner')

                // Mostrar el modal si estaba oculto
                // $("#edit-modal").modal("show");

                $(".btnRenew").prop("disabled", false);
            })
            .fail(() => {
                Toast.fire({
                    icon: "error",
                    title: "Ocurrió un error en la búsqueda.",
                });
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $("#renewForm").on("submit", function (h) {
        h.preventDefault();
        blockUI();

        if ($("#renewAffiliation").val() == "") {
            Toast.fire({
                icon: "error",
                title: "Debe ingresar la ficha de afiliación",
            });
            $.unblockUI();

            return;
        }

        let formData = new FormData(this);
        formData.append("_token", csrfToken);

        fetch("/api/renewPartner", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud");
                }
                return response.json();
            })
            .then((data) => {
                e.ajax.reload();
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });

                $("#renew-modal").modal("hide");
            })
            .catch((error) => {
                Toast.fire({
                    icon: error.icon,
                    title: error.message,
                });
            })
            .finally(() => {
                $.unblockUI();
            });
    });

    $("#renew-modal").on("hidden.bs.modal", function () {
        $("#renewForm")[0].reset();
        fv.resetForm(true);
        $("#selectSearch").val("charClienteDni").trigger("change");
        $(".btnRenew").prop("disabled", true);
        renewInitDatePicker.setDate(formattedToday, true);
    });

    // ------------ logica para editar un socio
    console.log("Entrando al metodo")
    const ef = document.getElementById("editForm");

    const fvEdit = FormValidation.formValidation(ef, {
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({ eleValidClass: "is-valid" }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    });

    fvEdit.on('core.form.valid', function () {
        console.log("Editando socio...");
        blockUI();

        let formData = new FormData(ef);

        fetch("/be/updatePartner", { // Ajusta tu ruta de edición
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "Accept": "application/json"
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                $.unblockUI();
                Toast.fire({ icon: data.icon, title: data.message });
                if (data.icon === "success") {
                    $(".modal").modal("hide");
                    // Aquí puedes recargar tu tabla o actualizar la fila
                }
            })
            .catch(error => {
                $.unblockUI();
                console.error("Error:", error);
            });
    });

    $("#editPartner").on("click", function (h) {
        $("#edit-modal").modal("show");
    });


    // Esto es para buscar al momento de editar
    $("#editBtn").on("click", function () {
        blockUI();
        $.ajax({
            url: "searchPartner",
            type: "post",
            data: {
                search: $("#inputSelect").val(),
                select: $("#editSelect").val(),
                _token: csrfToken,
            },
        })
            .done((data) => {

                console.log("Entrando en el search de editar cliente");
                console.log("Lo que llega del server:", data); // <--- MIRA ESTO EN LA CONSOLA (F12)

                if (!data || data.icon === 'warning') {
                    Toast.fire({ icon: 'warning', title: data.message || 'No se encontró nada' });
                    return;
                }

                let client = Array.isArray(data) ? data[0] : data;

                try {
                    if (client.birthday_client) {
                        $("#birthdateRenew").val(formatDateToDMY(client.birthday_client));
                    }
                } catch (e) {
                    console.error("Error en la fecha, pero seguimos...", e);
                }

                if (!client) {
                    console.error("El objeto client sigue siendo undefined");
                    return;
                }

                if (data.icon) {
                    // Si hay un mensaje de advertencia, mostrarlo y salir
                    Toast.fire({
                        icon: data.icon,
                        title: data.message,
                    });

                    $("#edit-modal").modal("hide");

                    $("#addNewCoupon").modal("show");

                    $.unblockUI();
                    return;
                }

                if (!data || data.icon) {
                    Toast.fire({
                        icon: data.icon || 'error',
                        title: data.message || 'Error desconocido'
                    });
                    return;
                }

                $("#editaffiliation").val(client.partner ? client.partner.affiliation : '' );
                $("#editenddate").val(formatDateToDMY(client.partner ? client.partner.dCaduDate : '' ));
                $("#editinitdate").val(formatDateToDMY(client.partner ? client.partner.dEmisDate : ''));
                $("#editCodeHidden").val(data.id_client);
                $("#editdoc").val(data.number_doc);
                $("#editnames").val(data.names_client);
                $("#editpattername").val(data.lastname_pat);
                $("#editmattername").val(data.lastname_mat);
                $("#editmail").val(data.email_client);
                $("#editphone").val(data.phone_client );
                $("#editaddress").val(data.address_client);

                // Formatear la fecha de nacimiento
                if (data.birthday_client) {
                    // Convertimos el string de la fecha a un objeto Date de JS
                    let date = new Date(data.birthday_client);

                    // Sacamos año, mes y día (añadiendo el 0 al inicio si es necesario)
                    let year = date.getFullYear();
                    let month = String(date.getMonth() + 1).padStart(2, '0');
                    let day = String(date.getDate()).padStart(2, '0');

                    // Lo mandamos al input
                    $("#editbirthdate").val(`${year}-${month}-${day}`);
                }

                // 2. Datos de Socio (Tabla 'client_socio' - Relación 'partner')
                console.log("Logica del apoderado si existe")
                if (data.partner && data.partner.proxy) {
                    console.log("¡Apoderado detectado!");

                    // Asignación directa usando los nombres exactos de tu log
                    $("#editproxyPatter").val(data.partner.proxy.proxy_pattername || '');
                    $("#editproxyMatter").val(data.partner.proxy.proxy_mattername || '');
                    $("#editproxyNames").val(data.partner.proxy.proxy_names || '');
                    $("#editproxyDoc").val(data.partner.proxy.proxy_doc || '');

                    // Abrir el acordeón a la fuerza
                    $("#EditaccordionOne").addClass("show");
                    // Si usas Bootstrap 5, a veces necesita esto:
                    // var bootstrapCollapse = new bootstrap.Collapse(document.getElementById('EditaccordionOne'), { show: true });
                } else {
                    console.log("No hay datos de proxy en la respuesta");
                    $("#EditaccordionOne").removeClass("show");
                }

                // Mostrar el modal si estaba oculto
                // $("#edit-modal").modal("show");
            })
            .always(() => {
                // ESTO ES SAGRADO: Pase lo que pase, quita el loading
                $.unblockUI();
            });

    });

    // boton para realmente editar
    $("#editForm").on("submit", function (e) {
        e.preventDefault(); // Evita que la página se recargue
        blockUI();

        // Recogemos los datos del formulario
        let formData = new FormData(this);
        formData.append("_token", csrfToken);

        $.ajax({
            url: "updatePartner", // <--- Asegúrate de tener esta ruta en Laravel
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
        })
            .done((data) => {
                Toast.fire({
                    icon: data.icon,
                    title: data.message,
                });

                if (data.icon === "success") {
                    $("#edit-modal").modal("hide");
                    // Si usas datatables, recárgala aquí:
                    // table.ajax.reload();
                }
            })
            .fail((xhr) => {
                Toast.fire({
                    icon: "error",
                    title: "Error al actualizar los datos.",
                });
            })
            .always(() => {
                $.unblockUI();
            });
    });

    $("#edit-modal").on("hidden.bs.modal", function () {
        $("#editForm")[0].reset();
        fv.resetForm(true);
    });


});
