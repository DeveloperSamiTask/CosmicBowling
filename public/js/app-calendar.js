"use strict";
let direction = "ltr";
isRtl && (direction = "rtl"),
    document.addEventListener("DOMContentLoaded", function () {
        {
            const v = document.getElementById("calendar"),
                m = document.querySelector(".app-calendar-sidebar"),
                p = document.getElementById("addEventSidebar"),
                f = document.querySelector(".app-overlay"),
                g = {
                    Business: "primary",
                    Holiday: "success",
                    Personal: "danger",
                    Family: "warning",
                    ETC: "info",
                },
                b = document.querySelector(".offcanvas-title"),
                h = document.querySelector(".btn-toggle-sidebar"),
                y = document.querySelector('button[type="submit"]'),
                S = document.querySelector(".btn-delete-event"),
                L = document.querySelector(".btn-cancel"),
                E = document.querySelector("#eventTitle"),
                k = document.querySelector("#eventStartDate"),
                w = document.querySelector("#eventEndDate"),
                q = $("#eventLabel"),
                M = document.querySelector("#eventPrice"),
                Z = document.querySelector("#quantity"),
                A = document.querySelector(".select-all"),
                F = [].slice.call(document.querySelectorAll(".input-filter")),
                Y = document.querySelector(".inline-calendar");
            let a,
                l = events,
                r = !1,
                e,
                fv;
            const C = new bootstrap.Offcanvas(p);
            function t(e) {
                return e.id
                    ? "<span class='badge badge-dot bg-" +
                          $(e.element).data("label") +
                          " me-2'> </span>" +
                          e.text
                    : e.text;
            }
            function n(e) {
                return e.id
                    ? "<div class='d-flex flex-wrap align-items-center'><div class='avatar avatar-xs me-2'><i class='" +
                          $(e.element).data("icon") +
                          "'></i> </div>" +
                          e.text +
                          "</div>"
                    : e.text;
            }
            var d, o;
            function s() {
                var e = document.querySelector(".fc-sidebarToggle-button");
                for (
                    e.classList.remove("fc-button-primary"),
                        e.classList.add("d-lg-none", "d-inline-block", "ps-0");
                    e.firstChild;

                )
                    e.firstChild.remove();
                e.setAttribute("data-bs-toggle", "sidebar"),
                    e.setAttribute("data-overlay", ""),
                    e.setAttribute("data-target", "#app-calendar-sidebar"),
                    e.insertAdjacentHTML(
                        "beforeend",
                        '<i class="mdi mdi-menu mdi-24px text-body"></i>'
                    );
            }
            q.length &&
                (select2Focus(q),
                q.wrap('<div class="position-relative"></div>').select2({
                    placeholder: "Seleccionar Subcategoria",
                    dropdownParent: q.parent(),
                    templateResult: t,
                    templateSelection: t,
                    minimumResultsForSearch: -1,
                    escapeMarkup: function (e) {
                        return e;
                    },
                })),
                k &&
                    (d = k.flatpickr({
                        enableTime: !0,
                        altFormat: "Y-m-dTH:i:S",
                        onReady: function (e, t, n) {
                            n.isMobile &&
                                n.mobileInput.setAttribute("step", null);
                        },
                    })),
                w &&
                    (o = w.flatpickr({
                        enableTime: !0,
                        altFormat: "Y-m-dTH:i:S",
                        onReady: function (e, t, n) {
                            n.isMobile &&
                                n.mobileInput.setAttribute("step", null);
                        },
                    })),
                Y &&
                    (e = Y.flatpickr({
                        monthSelectorType: "static",
                        inline: !0,
                    }));
            q.on("input", function () {
                let option = $(this).find("option:selected"),
                    prices = [
                        option.data("sunday"), // Domingo (0)
                        option.data("monday"), // Lunes (1)
                        option.data("tuesday"), // Martes (2)
                        option.data("wednesday"), // Miércoles (3)
                        option.data("thursday"), // Jueves (4)
                        option.data("friday"), // Viernes (5)
                        option.data("saturday"), // Sábado (6)
                    ];

                E.value = option.data("prefix");
                let selectedDate = new Date(k.value);
                let dayOfWeek = selectedDate.getDay(); // Obtiene el día de la semana (0 es domingo, 6 es sábado)

                blockUI(); // Bloquea la interfaz de usuario

                let promise = Promise.resolve(prices[dayOfWeek]);

                // Verifica si es feriado y ajusta el precio si es necesario
                promise = promise.then((price) => {
                    let dayF = moment(k.value);

                    return checkHoliday(k.value).then((isHoliday) => {
                        if (isHoliday) {
                            // Si es feriado, establecer la hora en 1:30 PM y usar el precio del domingo
                            k.value = dayF
                                .hour(13)
                                .minute(30)
                                .second(0)
                                .format("YYYY-MM-DD HH:mm");
                            return prices[0]; // Precio del domingo
                        }
                        return price; // Si no es feriado, devolver el precio actual
                    });
                });

                // Asigna el precio final o maneja errores
                promise
                    .then((price) => {
                        M.value = price; // Asigna el precio al campo M
                    })
                    .catch((error) => {
                        console.error(
                            "Error al verificar el día o feriado:",
                            error
                        );
                    })
                    .finally(() => {
                        $.unblockUI(); // Desbloquea la interfaz de usuario
                    });
            });

            function isWeekend(dayOfWeek) {
                return dayOfWeek === 5 || dayOfWeek === 6 || dayOfWeek === 0;
            }

            let i = new Calendar(v, {
                initialView: "dayGridMonth",
                events: function (e, t) {
                    let n = (function () {
                        let t = [],
                            e = [].slice.call(
                                document.querySelectorAll(
                                    ".input-filter:checked"
                                )
                            );
                        return (
                            e.forEach((e) => {
                                t.push(e.getAttribute("data-value"));
                            }),
                            t
                        );
                    })();
                    t(
                        l.filter(function (e) {
                            return n.includes(
                                e.extendedProps.calendar.toLowerCase()
                            );
                        })
                    );
                },
                plugins: [
                    dayGridPlugin,
                    interactionPlugin,
                    listPlugin,
                    timegridPlugin,
                ],
                editable: !0,
                dragScroll: !0,
                dayMaxEvents: 2,
                eventResizableFromStart: !0,
                customButtons: { sidebarToggle: { text: "Sidebar" } },
                headerToolbar: {
                    start: "sidebarToggle, prev,next, title",
                    end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
                },
                locale: "es",
                firstDay: 1,
                direction: direction,
                initialDate: new Date(),
                navLinks: !0,
                eventClassNames: function ({ event: e }) {
                    return ["fc-event-" + g[e._def.extendedProps.calendar]];
                },
                dateClick: function (e) {
                    const date = moment(e.date);
                    const formattedDate = date.format("YYYY-MM-DD");
                    const dayOfWeek = date.day();

                    // Mapeo de días de la semana a horas con minutos en 30
                    const defaultHours = {
                        0: "13:30", // Domingo
                        1: "15:30", // Lunes
                        2: "15:30", // Martes
                        3: "15:30", // Miércoles
                        4: "15:30", // Jueves
                        5: "15:00", // Viernes
                        6: "13:30", // Sábado
                    };

                    const defaultFinshHours = {
                        0: "22:00", // Domingo
                        1: "22:00", // Lunes
                        2: "22:00", // Martes
                        3: "22:00", // Miércoles
                        4: "22:00", // Jueves
                        5: "22:30", // Viernes
                        6: "22:30", // Sábado
                    };

                    const defaultHour = defaultHours[dayOfWeek];
                    const defaultFinsh = defaultFinshHours[dayOfWeek];

                    // Descomponer la hora y minutos de defaultHour
                    const [hour, minute] = defaultHour.split(":").map(Number);
                    const [finshHour, finshMinute] = defaultFinsh
                        .split(":")
                        .map(Number);

                    u();
                    C.show();
                    if (b) b.innerHTML = "Agregar Horario";
                    y.innerHTML = "Agregar";
                    y.classList.remove("btn-update-event");
                    y.classList.add("btn-add-event");
                    S.classList.add("d-none");

                    // Usar la hora predeterminada
                    k.value = date
                        .hour(hour)
                        .minute(minute)
                        .second(0)
                        .format("YYYY-MM-DD HH:mm");

                    // Usar la hora de finalización predeterminada
                    w.value = date
                        .hour(finshHour)
                        .minute(finshMinute)
                        .second(0)
                        .format("YYYY-MM-DD HH:mm");
                },
                eventClick: function (e) {
                    (e = e),
                        (a = e.event).url &&
                            (e.jsEvent.preventDefault(),
                            window.open(a.url, "_blank")),
                        C.show(),
                        b && (b.innerHTML = "Actualizar Horario"), // DOM Update OffCanvas
                        (y.innerHTML = "Actualizar"),
                        y.classList.add("btn-update-event"),
                        y.classList.remove("btn-add-event"),
                        S.classList.remove("d-none"),
                        (E.value = a.title.replace(/\s*\(\d+\)\s*$/, "")),
                        d.setDate(a.start, !0, "Y-m-d H:i"),
                        null !== a.end
                            ? o.setDate(a.end, !0, "Y-m-d H:i")
                            : o.setDate(a.start, !0, "Y-m-d H:i"),
                        q.val(a.extendedProps.calendar).trigger("change"),
                        void 0 !== a.extendedProps.price &&
                            (M.value = a.extendedProps.price),
                        void 0 !== a.extendedProps.quantity &&
                            (Z.value = a.extendedProps.quantity);
                },
                datesSet: function () {
                    s();
                },
                viewDidMount: function () {
                    s();
                },
            });
            i.render(), s();
            var c = document.getElementById("eventForm");
            function u() {
                fv.resetForm();
                q.val(0).trigger("change");
                w.value = "";
                k.value = "";
                E.value = "";
                M.value = "";
                Z.value = 0;
            }

            (fv = FormValidation.formValidation(c, {
                fields: {
                    eventTitle: {
                        validators: {
                            notEmpty: {
                                message: "Obligatorio ingresar un titulo",
                            },
                        },
                    },
                    eventStartDate: {
                        validators: {
                            notEmpty: {
                                message: "Please enter start date ",
                            },
                        },
                    },
                    eventEndDate: {
                        validators: {
                            notEmpty: { message: "Please enter end date " },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: "",
                        rowSelector: function (e, t) {
                            return ".mb-4";
                        },
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    autoFocus: new FormValidation.plugins.AutoFocus(),
                },
            })
                .on("core.form.valid", function () {
                    r = !0;
                })
                .on("core.form.invalid", function () {
                    r = !1;
                })),
                h &&
                    h.addEventListener("click", (e) => {
                        L.classList.remove("d-none");
                    }),
                y.addEventListener("click", async (e) => {
                    e.preventDefault();

                    const validationStatus = await fv.validate();
                    if (validationStatus !== "Valid") {
                        return;
                    }

                    if (y.classList.contains("btn-add-event")) {
                        try {
                            const newId = await insert();
                            const newEvent = {
                                id: newId,
                                title: `${E.value} (${Z.value})`,
                                start: k.value,
                                end: w.value,
                                startStr: k.value,
                                endStr: w.value,
                                display: "block",
                                extendedProps: {
                                    calendar: q.val(),
                                    price: M.value,
                                    quantity: Z.value,
                                },
                            };
                            l.push(newEvent);
                            i.refetchEvents();
                            C.hide();
                        } catch (error) {
                            // insert() ya muestra el mensaje de error.
                        }
                    } else {
                        try {
                            await update(a.id);
                            const updatedEvent = {
                                id: a.id,
                                title: `${E.value} (${Z.value})`,
                                start: k.value,
                                end: w.value,
                                extendedProps: {
                                    calendar: q.val(),
                                    price: M.value,
                                    quantity: Z.value,
                                },

                                display: "block",
                                allDay: "0",
                            };
                            updatedEvent.id = parseInt(updatedEvent.id);
                            l[
                                l.findIndex(
                                    (event) => event.id === updatedEvent.id
                                )
                            ] = updatedEvent;
                            i.refetchEvents();
                            C.hide();
                        } catch (error) {
                            // update() ya muestra el mensaje de error.
                        }
                    }
                }),
                S.addEventListener("click", (e) => {
                    if (
                        !window.confirm(
                            "¿Seguro que deseas borrar este horario y todos sus intervalos?"
                        )
                    ) {
                        return;
                    }

                    const eventId = parseInt(a.id);
                    removeEvent(eventId).then(() => {
                        l = l.filter(function (e) {
                            return e.id != eventId;
                        });
                        i.refetchEvents();
                        C.hide();
                    }).catch(() => {});
                }),
                p.addEventListener("hidden.bs.offcanvas", function () {
                    u();
                }),
                h.addEventListener("click", (e) => {
                    // DOM Insert OffCanvas
                    b && (b.innerHTML = "Agregar Evento"),
                        (y.innerHTML = "Agregar"),
                        y.classList.remove("btn-update-event"),
                        y.classList.add("btn-add-event"),
                        S.classList.add("d-none"),
                        m.classList.remove("show"),
                        f.classList.remove("show");
                }),
                A &&
                    A.addEventListener("click", (e) => {
                        e.currentTarget.checked
                            ? document
                                  .querySelectorAll(".input-filter")
                                  .forEach((e) => (e.checked = 1))
                            : document
                                  .querySelectorAll(".input-filter")
                                  .forEach((e) => (e.checked = 0)),
                            i.refetchEvents();
                    }),
                F &&
                    F.forEach((e) => {
                        e.addEventListener("click", () => {
                            document.querySelectorAll(".input-filter:checked")
                                .length <
                            document.querySelectorAll(".input-filter").length
                                ? (A.checked = !1)
                                : (A.checked = !0),
                                i.refetchEvents();
                        });
                    }),
                e.config.onChange.push(function (e) {
                    i.changeView(
                        i.view.type,
                        moment(e[0]).format("YYYY-MM-DD")
                    ),
                        s(),
                        m.classList.remove("show"),
                        f.classList.remove("show");
                });
        }
        let csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        function insert() {
            blockUI();
            return new Promise((resolve, reject) => {
                let eventLabelSelect = document.getElementById("eventLabel"),
                    dataId =
                        eventLabelSelect.selectedOptions[0].getAttribute(
                            "data-id"
                        );

                let formData = new FormData(c);
                formData.append("data-id", dataId);

                fetch("insertHours", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => {
                        return response.json().then((data) => {
                            if (!response.ok) {
                                throw new Error(
                                    data.message ||
                                        "Hubo un problema al procesar el formulario."
                                );
                            }
                            return data;
                        });
                    })
                    .then((data) => {
                        console.log("Fetch exitoso", data);
                        if (data.status === "success") {
                            Toast.fire({
                                icon: data.status,
                                title: "Horario ingresado",
                            });
                            resolve(data.calendar_id);
                        } else {
                            Toast.fire({
                                icon: data.status,
                                title: data.message,
                            });
                            throw new Error(
                                data.message ||
                                    "Hubo un problema con la respuesta del servidor."
                            );
                        }
                    })
                    .catch((error) => {
                        Toast.fire({
                            icon: "warning",
                            title: error.message,
                        });
                        console.error("Error:", error.message);
                        reject(error);
                    })
                    .finally(() => {
                        $.unblockUI();
                    });
            });
        }
        function update(id) {
            blockUI();

            const eventLabelSelect = document.getElementById("eventLabel");
            const dataId =
                eventLabelSelect.selectedOptions[0].getAttribute("data-id");
            const formData = new FormData(c);
            formData.append("data-id", dataId);

            return fetch(`updateHours/${id}`, {
                method: "POST",
                body: formData,
            })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(
                            data.message || "No se pudo actualizar el horario."
                        );
                    }
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                    });
                    return data;
                })
                .catch((error) => {
                    Toast.fire({
                        icon: "warning",
                        title: error.message,
                    });
                    throw error;
                })
                .finally(() => {
                    $.unblockUI();
                });
        }

        function removeEvent(id) {
            blockUI();

            return fetch(`deleteHours/${id}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(
                            data.message || "No se pudo eliminar el horario."
                        );
                    }
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                    });
                    return data;
                })
                .catch((error) => {
                    Toast.fire({
                        icon: "warning",
                        title: error.message,
                    });
                    throw error;
                })
                .finally(() => {
                    $.unblockUI();
                });
        }

        function checkHoliday(date) {
            return new Promise((resolve, reject) => {
                fetch("validateHoliday", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({ date: date }),
                })
                    .then((response) => {
                        if (!response.ok) {
                            return response.json().then((errorData) => {
                                throw new Error(
                                    errorData.message || "Error en la solicitud"
                                );
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        resolve(data);
                    })
                    .catch((error) => {
                        console.error("Error:", error.message);
                        reject(error);
                    });
            });
        }

        function blockUI() {
            $.blockUI({
                message:
                    '<div class="sk-wave mx-auto"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div>',
                css: { backgroundColor: "transparent", border: "0" },
                overlayCSS: { opacity: 0.5 },
            });
        }
        const Toast = Swal.mixin({
            toast: true,
            position: "top",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
    });
