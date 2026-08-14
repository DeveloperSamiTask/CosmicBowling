const guests = document.getElementById("l-guests"),
    lpLane = document.getElementById("lp-lane"),
    ldiscount = document.getElementById("l-discount"),
    lpGuests = document.getElementById("lp-guests"),
    lTotal = document.getElementById("l-total"),
    lHour1 = document.getElementById("l-hour1"),
    lHour2 = document.getElementById("l-hour2"),
    cHour1 = document.getElementById("hour1"),
    cHour2 = document.getElementById("hour2"),
    xwyz = document.getElementById("xwyz").getAttribute("data-id"),
    btnCoupon = document.getElementById("btnCoupon"),
    inputCoupon = document.getElementById("couponCode"),
    csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    inputGuests = document.getElementById("c-guests"),
    limitRound = "",
    labelLine = document.getElementById("n-lane"),
    preloader = document.getElementById("preloader");

localStorage.setItem("limit", "");
sessionStorage.clear();

const radioHours = document.querySelectorAll('input[name="c-hour"]'),
    typeLane = xwyz == 4 ? "" : "Alquiler Calzado";
let selectedButtonId = null,
    selectedPrice,
    priceShoe = xwyz == "4" ? 0 : holiday,
    line = 1,
    globalDiscount = 0,
    globalDiscountType = "",
    plane = "",
    currentCalendarItems = [],
    guestsRequestId = 0;
insurancePrice = 0;

generateRadioButtons(calendarItems);

function priceLeft(lane, shoe, cPrice, cShoe, lines) {
    document.getElementById("tabBilling").classList.add("disabled");
    document.getElementById("tabPayment").classList.add("disabled");

    const laneCount = Number(lines);
    const safeLaneCount = Number.isFinite(laneCount) ? laneCount : line;
    const laneHours = Number(lane) || 0;
    const lanePrice = Number(cPrice) || 0;
    const guestCount = Number(shoe) || 0;
    const shoePrice = Number(cShoe) || 0;

    plane = laneHours * lanePrice * safeLaneCount;
    const pShoe = guestCount * shoePrice;

    let discountAmount = 0;

    if (globalDiscountType == "percentage") {
        discountAmount = (plane * globalDiscount) / 100;
    } else if (globalDiscountType == "fixed") {
        discountAmount = globalDiscount;
    }

    discountAmount = discountAmount.toFixed(2);
    const pTotal = (plane - discountAmount + pShoe + insurancePrice).toFixed(2);

    ldiscount.innerHTML = `- S/. ${discountAmount}`;
    lpLane.innerHTML = `S/. ${plane.toFixed(2)}`;
    lpGuests.innerHTML = `S/. ${pShoe.toFixed(2)}`;
    lTotal.innerHTML = `S/. ${pTotal}`;

    // ACTUALIZAR SEGURO EN TODOS LOS RESUMENES
    updateInsuranceDisplay(pTotal);
}

function updateInsuranceDisplay(pTotal) {
    const allLsure = document.querySelectorAll("#l-sure");
    const allLpsure = document.querySelectorAll("#lp-sure");
    const allLiSure = document.querySelectorAll("#li-sure");
    const allLtotal = document.querySelectorAll("#l-total");

    // Actualizar TODOS los totales
    allLtotal.forEach((total) => {
        total.innerHTML = `S/. ${pTotal}`;
    });

    // Actualizar TODOS los seguros
    if (insurancePrice > 0) {
        allLsure.forEach((el) => (el.innerHTML = "Seguro de Cambio"));
        allLpsure.forEach((el) => (el.innerHTML = "S/. 10.00"));
        allLiSure.forEach((el) => (el.style.display = "flex"));
    } else {
        allLsure.forEach((el) => (el.innerHTML = ""));
        allLpsure.forEach((el) => (el.innerHTML = ""));
        allLiSure.forEach((el) => (el.style.display = "none"));
    }
}

function getNextHalfHours(timeString) {
    const [hour, minutes] = timeString.split(":").slice(0, 2);
    const intHour = parseInt(hour);
    const intMinutes = parseInt(minutes);

    const now = new Date();
    now.setHours(intHour);
    now.setMinutes(intMinutes);
    now.setSeconds(0);

    const oneHalfHourLater = new Date(now);
    const twoHalfHoursLater = new Date(now);
    const threeHalfHoursLater = new Date(now);

    oneHalfHourLater.setMinutes(now.getMinutes() + 30);

    // Manejar el desbordamiento de los minutos
    if (oneHalfHourLater.getMinutes() >= 60) {
        oneHalfHourLater.setHours(now.getHours() + 1);
        oneHalfHourLater.setMinutes(now.getMinutes() - 30);
    }

    // Agregar 1 hora y 30 minutos para obtener 2 horas después
    twoHalfHoursLater.setHours(now.getHours() + 1);

    // Manejar el desbordamiento de los minutos para twoHalfHoursLater
    if (twoHalfHoursLater.getMinutes() >= 60) {
        twoHalfHoursLater.setHours(now.getHours() + 1);
    }

    // Agregar 1 hora y 30 minutos para obtener 2 horas después
    threeHalfHoursLater.setHours(now.getHours() + 1);
    threeHalfHoursLater.setMinutes(now.getMinutes() + 30);
    // Manejar el desbordamiento de los minutos para twoHalfHoursLater
    if (threeHalfHoursLater.getMinutes() >= 60) {
        threeHalfHoursLater.setHours(now.getHours() + 1);
        threeHalfHoursLater.setMinutes(now.getMinutes() + 30);
    }

    const formattedOneHalfHourLater = formatTime24Hours(
        oneHalfHourLater.toTimeString().split(" ")[0]
    );
    const formattedTwoHalfHoursLater = formatTime24Hours(
        twoHalfHoursLater.toTimeString().split(" ")[0]
    );
    const formattedThreeHalfHoursLater = formatTime24Hours(
        threeHalfHoursLater.toTimeString().split(" ")[0]
    );

    return [
        formattedOneHalfHourLater,
        formattedTwoHalfHoursLater,
        formattedThreeHalfHoursLater,
    ];
}

const updateHourLabelsAndStatus = (
    buttonNumber,
    oneHourLater,
    twoHoursLater,
    threeHoursLater
) => {
    const isOneHourLaterAvailable = checkRadioButtonStatus(oneHourLater),
        isTwoHoursLaterAvailable = checkRadioButtonStatus(twoHoursLater),
        isThreeHoursLaterAvailable = checkRadioButtonStatus(threeHoursLater),
        hour1Label = document.getElementById("hour1"),
        hour2Label = document.getElementById("hour2");

    // Update for one hour later
    if (isOneHourLaterAvailable) {
        lHour1.innerHTML = formatHourRange(buttonNumber, 1);
        cHour1.setAttribute("data-one", buttonNumber);
        cHour1.setAttribute("data-two", oneHourLater);
        hour1Label.checked = true;
        hour1Label.disabled = false;
    } else {
        lHour1.innerHTML = `<del>${formatHourRange(buttonNumber, 1)}</del>`;
        hour1Label.disabled = true;
        lHour2.innerHTML = `<del>${formatHourRange(oneHourLater, 2)}</del>`;
        hour2Label.disabled = true;
    }

    // Update for two hours later
    if (isTwoHoursLaterAvailable && isOneHourLaterAvailable) {
        lHour2.innerHTML = formatHourRange(buttonNumber, 2);
        cHour2.setAttribute("data-one", buttonNumber);
        cHour2.setAttribute("data-two", oneHourLater);
        cHour2.setAttribute("data-three", twoHoursLater);
        cHour2.setAttribute("data-four", threeHoursLater);
        hour2Label.disabled = false;
    } else {
        lHour2.innerHTML = `<del>${formatHourRange(buttonNumber, 2)}</del>`;
        hour2Label.disabled = true;
    }

    // Update for three hours later
    if (
        isTwoHoursLaterAvailable &&
        isOneHourLaterAvailable &&
        isThreeHoursLaterAvailable
    ) {
        lHour2.innerHTML = formatHourRange(buttonNumber, 2);
        hour2Label.disabled = false;
    } else {
        lHour2.innerHTML = `<del>${formatHourRange(buttonNumber, 2)}</del>`;
        hour2Label.disabled = true;
    }
};

function handleRadioChange(selectedRadio) {
    const selectedDate = document.getElementById("c-date").value;

    selectedButtonId = selectedRadio.id;
    const buttonNumber = selectedRadio.value;
    selectHour = 1;
    sessionStorage.setItem("time", buttonNumber);
    sessionStorage.setItem("hours", 1);

    selectedPrice = selectedRadio.getAttribute("data-price");
    const dateTime = formatDateTime(selectedDate, buttonNumber);

    const [oneHourLater, twoHoursLater, threeHoursLater] =
        getNextHalfHours(buttonNumber);

    updateHourLabelsAndStatus(
        buttonNumber,
        oneHourLater,
        twoHoursLater,
        threeHoursLater
    );

    updateGuests(
        selectedDate,
        buttonNumber,
        oneHourLater,
        null,
        null
    );

    const selectGuests = Math.max(1, parseInt(inputGuests.value, 10) || 1);
    inputGuests.value = selectGuests;
    line = getLaneCount(selectGuests);
    labelLine.innerHTML = line;
    document.getElementById("l-date").innerHTML = dateTime;
    document.getElementById("l-hours").innerHTML = " X 1 hora:";
    document.getElementById(
        "l-guests"
    ).innerHTML = ` ${typeLane} <strong> X ${selectGuests} Invitados </strong>`;

    sessionStorage.setItem("guests", selectGuests);
    priceLeft(1, selectGuests, selectedPrice, priceShoe, line);
}

function checkRadioButtonStatus(value) {
    const interval = currentCalendarItems.find((item) => item.hour == value);

    return Boolean(interval && Number(interval.available) > 0);
}

// ----------------///
function generateRadioButtons(calendarItems) {
    const container = document.getElementById("radioContainer");
    container.innerHTML = "";
    currentCalendarItems = calendarItems;

    if (calendarItems.length == 0) {
        showNoScheduleCard(container);
    }

    // El último intervalo es un bloque auxiliar de disponibilidad:
    // permite completar la última reserva de una hora, pero no debe
    // mostrarse como una hora desde la que el cliente pueda empezar.
    const filteredItems = calendarItems.slice(0, -1);

    filteredItems.forEach((item) => {
        const formattedTime = formatTime(item.hour);

        const radioInput = document.createElement("input");
        radioInput.type = "radio";
        radioInput.className = "btn-check";
        radioInput.id = `time${item.hour}`;
        radioInput.name = "time";
        radioInput.value = item.hour;
        radioInput.setAttribute("data-price", item.price);
        radioInput.setAttribute("data-subcategory", item.subcategory);
        if (item.available == 0) radioInput.disabled = true;

        const radioLabel = document.createElement("label");
        radioLabel.htmlFor = `time${item.hour}`;
        radioLabel.className = "btn btn-outline-secondary btn-lg btn-custom";
        radioLabel.innerHTML =
            item.available == 0
                ? `<del>${formattedTime}</del>`
                : `<span>${formattedTime}</span>`;

        const colDiv = document.createElement("div");
        colDiv.className =
            "col-12 col-md-6 radio-container d-flex justify-content-center";

        radioInput.addEventListener("change", function () {
            handleRadioChange(this);
        });

        colDiv.appendChild(radioInput);
        colDiv.appendChild(radioLabel);
        container.appendChild(colDiv);
    });
}

function showNoScheduleCard(container) {
    const cardHTML = `
    <a href="javascript:void(0)" class="card card-hover-primary bg-secondary border-0 h-100 text-decoration-none">
      <div class="card-body text-center">
        <i class="ai-clock h1 fw-normal d-block mb-4"></i>
        <h3>No hay horarios disponibles</h3>
        <p class="card-text">Actualmente no hay horarios disponibles. Por favor, intenta más tarde.</p>
      </div>
    </a>
    `;

    // Insertamos el contenido de la tarjeta en el contenedor
    container.innerHTML = cardHTML;
}

const getProductCalendar = async (subcategory, date) => {
    try {
        const response = await fetch(`/updateUI/${subcategory}/${date}`);
        if (!response.ok) {
            throw new Error("No se pudo obtener la data");
        }
        const data = await response.json();
        preloader.classList.add("hidden");
        console.log(data);

        return data;
    } catch (error) {
        console.error("Error al obtener la data:", error);
    }
};

const updateUI = async () => {
    try {
        guestsRequestId++;
        selectedButtonId = null;
        selectHour = 1;
        line = 1;
        inputGuests.value = Math.max(1, parseInt(inputGuests.value, 10) || 1);
        inputGuests.removeAttribute("max");
        sessionStorage.removeItem("time");
        sessionStorage.removeItem("hours");
        sessionStorage.removeItem("guests");
        preloader.classList.remove("hidden");
        const selectedDate = document.getElementById("c-date").value;
        sessionStorage.setItem("date", selectedDate);
        document.getElementById("l-date").innerHTML = "----------------";

        priceLeft(0, 0, 0, 0, 1);

        lHour1.innerHTML = "1 Hora";
        cHour1.checked = false;
        cHour1.disabled = true;

        lHour2.innerHTML = "2 Horas";
        cHour2.checked = false;
        cHour2.disabled = true;

        const hours = await getProductCalendar(xwyz, selectedDate);

        priceShoe = xwyz == "4" ? 0 : hours["isHoliday"];

        generateRadioButtons(hours["hours"]);
    } catch (error) {
        console.error("Error al actualizar la interfaz de usuario:", error);
    }
};

const updateGuests = async (date, one, two, three, four) => {
    const requestId = ++guestsRequestId;

    try {
        const response = await fetch(`/updateGuests`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ date, one, two, three, four }),
        });

        if (!response.ok) {
            throw new Error("No se pudo obtener la data");
        }

        const data = await response.json();
        if (requestId !== guestsRequestId) {
            return data;
        }

        localStorage.setItem("limit", data.limit);
        inputGuests.max = data.calculated;

        if (parseInt(inputGuests.value) > data.calculated) {
            inputGuests.value = data.calculated;
            guests.innerHTML = ` ${typeLane} <strong> X ${data.calculated} Invitados </strong>`;
            line = getLaneCount(data.calculated);
            labelLine.innerHTML = line;

            console.log(line);

            const selectedPrice = document
                .getElementById(selectedButtonId)
                .getAttribute("data-price");

            priceLeft(
                selectHour,
                data.calculated,
                selectedPrice,
                priceShoe,
                line
            );
        }
        return data;
    } catch (error) {
        console.error("Error al obtener la data:", error);
    }
};

const getCoupon = async (code) => {
    if (!code) {
        console.error("El código de cupón no está definido.");
        return;
    }

    try {
        const response = await fetch(`/api/coupon/${code}`);

        if (!response.ok) {
            if (response.status == 404) {
                console.error("Cupón no encontrado.");
                alert("Cupón no encontrado.");
                return { error: "Cupón no encontrado." };
            } else {
                throw new Error("Error en la respuesta de la API.");
            }
        }

        const data = await response.json();

        // Verificar si el cupón está activo
        if (!data.is_active) {
            alert("El cupón no está activo.");
            return { error: "El cupón no está activo." };
        }

        // Verificar la validez de la fecha
        const inputDate = new Date(document.getElementById("c-date").value);
        const startDate = new Date(data.valid_from);
        const endDate = new Date(data.valid_until);

        if (inputDate < startDate || inputDate > endDate) {
            alert("La fecha no está dentro del rango de validez del cupón.");
            return { error: "La fecha no es válida." };
        }

        // Verificar si el cupón ha alcanzado su límite de uso
        if (data.usage_limit !== null && data.used_count >= data.usage_limit) {
            alert("Este cupón ya ha sido usado el número máximo de veces.");
            return { error: "Límite de uso alcanzado." };
        }

        const xwyzNumber = parseInt(xwyz, 10); // Convertir a número
        if (!data.subcategory_ids.includes(xwyzNumber)) {
            alert("Este cupón no es válido para este producto.");
            return { error: "Cupón no válido para este producto." };
        }

        // Devolver el tipo de descuento y el monto
        const discountType = data.discount_type; // "percentage"
        const discountAmount = data.discount_amount; // "20.00"

        return { discountType, discountAmount };
    } catch (error) {
        console.error("Error al obtener la data:", error);
        alert("Error al obtener los datos del cupón.");
        return { error: "Error al obtener los datos del cupón." };
    }
};

function getLaneCount(guestCount) {
    const parsedGuestCount = Number(guestCount) || 0;
    const guestLimit = Number(localStorage.getItem("limit"));

    if (parsedGuestCount <= 0) {
        return 0;
    }

    return guestLimit > 0 ? Math.ceil(parsedGuestCount / guestLimit) : 1;
}

const LabelGuests = () => {
    const selectGuests = parseInt(inputGuests.value) || 0; // Asegúrate de que sea un número
    sessionStorage.setItem("guests", selectGuests);
    guests.innerHTML = ` ${typeLane} <strong> X ${selectGuests} Invitados</strong>`;

    if (selectedButtonId) {
        const selectedPrice = document
            .getElementById(selectedButtonId)
            .getAttribute("data-price");
        line = getLaneCount(selectGuests);
        labelLine.innerHTML = line;
        priceLeft(selectHour, selectGuests, selectedPrice, priceShoe, line); // Calcula el precio
    } else {
        console.error("No se ha seleccionado ningún botón de tiempo.");
    }
};

document.getElementById("increment-btn").addEventListener("click", () => {
    let currentValue = parseInt(inputGuests.value) || 1; // Cambiado a 1 como valor inicial
    const maxGuests = parseInt(inputGuests.max) || 100; // Obtener el máximo actual
    if (currentValue < maxGuests) {
        inputGuests.value = currentValue + 1; // Aumenta el valor en 1
        LabelGuests(); // Llama a LabelGuests para actualizar la UI
    }
});

// Decrementar el valor del input
document.getElementById("decrement-btn").addEventListener("click", () => {
    let currentValue = parseInt(inputGuests.value) || 1; // Cambiado a 1 como valor inicial
    if (currentValue > 1) {
        // Asegúrate de que el valor no baje de 1
        inputGuests.value = currentValue - 1;
        LabelGuests(); // Llama a LabelGuests para actualizar la UI
    }
});

// Validar el valor del input directamente
inputGuests.addEventListener("input", () => {
    let currentValue = parseInt(inputGuests.value) || 1; // Cambiado a 1 como valor inicial
    const maxGuests = parseInt(inputGuests.max) || 100;
    if (currentValue > maxGuests) {
        inputGuests.value = maxGuests; // Restablecer al máximo si se excede
    } else if (currentValue < 1) {
        // Cambiado a 1 como mínimo
        inputGuests.value = 1; // No permitir valores menores a 1
    }
    LabelGuests(); // Llama a LabelGuests para actualizar la UI
});

document.getElementById("c-date").addEventListener("change", updateUI);

let selectHour = 1;

radioHours.forEach((hours) => {
    hours.addEventListener("input", function () {
        const selectedDate = document.getElementById("c-date").value,
            oneHour = this.getAttribute("data-one"),
            twoHour = this.getAttribute("data-two"),
            threeHour = this.getAttribute("data-three"),
            fourHour = this.getAttribute("data-four");

        updateGuests(selectedDate, oneHour, twoHour, threeHour, fourHour);

        if (this.checked) {
            selectHour = parseInt(this.value);
            sessionStorage.setItem("hours", selectHour);

            const pluralSuffix = selectHour > 1 ? "s" : "";
            document.getElementById(
                "l-hours"
            ).innerHTML = ` X ${selectHour} Hora${pluralSuffix}`;

            if (this.checked) {
                const selectGuests = parseInt(inputGuests.value);

                const pluralSuffix = selectHour > 1 ? "s" : "";
                document.getElementById(
                    "l-hours"
                ).innerHTML = ` X ${selectHour} Hora${pluralSuffix}`;

                if (selectedButtonId) {
                    const selectedPrice = document
                        .getElementById(selectedButtonId)
                        .getAttribute("data-price");

                    // Calcular el número de pistas basado en la cantidad de invitados
                    line = getLaneCount(selectGuests);

                    // Actualizar el número de pistas en el DOM
                    labelLine.innerHTML = line;

                    // Calcular el precio
                    priceLeft(
                        selectHour,
                        selectGuests,
                        selectedPrice,
                        priceShoe,
                        line
                    ); // Llamar a la función con el número de pistas
                } else {
                    console.error(
                        "No se ha seleccionado ningún botón de tiempo."
                    );
                }
            }
        }
    });
});

btnCoupon.addEventListener("click", async () => {
    const couponData = await getCoupon(inputCoupon.value);

    if (couponData && !couponData.error) {
        globalDiscountType = couponData.discountType;
        globalDiscount = parseFloat(couponData.discountAmount);
        sessionStorage.setItem("coupon", inputCoupon.value);
        LabelGuests();
    } else {
        sessionStorage.removeItem("coupon");
        globalDiscount = 0;
        LabelGuests();
        console.error("Error al obtener el cupón:", couponData.error);
    }
});

inputCoupon.addEventListener("input", function () {
    if (this.value.length > 5) {
        btnCoupon.disabled = false;
    } else {
        btnCoupon.disabled = true;
    }
});

/**
 * Converts a time string in HH:MM format to 12-hour format with AM/PM.
 * @param {string} timeString - The time string in HH:MM format.
 * @returns {string} - The formatted time in 12-hour format with AM/PM.
 */
function formatTime(timeString) {
    const [hour, minutes] = timeString.split(":").map(Number);
    const period = hour >= 12 ? "PM" : "AM";
    const formattedHour = hour % 12 == 0 ? 12 : hour % 12;
    return `${formattedHour}:${minutes.toString().padStart(2, "0")} ${period}`;
}

/**
 * Converts a time string in HH:MM:SS format to 12-hour format with AM/PM.
 * @param {string} time24h - The time string in HH:MM:SS format.
 * @returns {string} - The formatted time in 12-hour format with AM/PM.
 */
function formatTime12h(time24h) {
    const [hours, minutes] = time24h.split(":").map(Number);
    const period = hours >= 12 ? "PM" : "AM";
    const adjustedHours = hours % 12 || 12;
    return `${adjustedHours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")} ${period}`;
}

/**
 * Converts a time string in HH:MM format to 24-hour format with seconds.
 * @param {string} timeString - The time string in HH:MM format.
 * @returns {string} - The formatted time in 24-hour format with seconds.
 */
function formatTime24Hours(timeString) {
    const [hour, minutes] = timeString.split(":").map(Number);
    return `${hour.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:00`;
}

/**
 * Converts given hours and minutes to 12-hour format with AM/PM.
 * @param {number} hours - The hour in 24-hour format.
 * @param {number} [minutes=0] - The minutes.
 * @returns {string} - The formatted time in 12-hour format with AM/PM.
 */
function formatTime12Hours(hours, minutes = 0) {
    const period = hours >= 12 ? "PM" : "AM";
    const formattedHour = hours % 12 == 0 ? 12 : hours % 12;
    return `${formattedHour}:${minutes.toString().padStart(2, "0")} ${period}`;
}

// Función: Formatea una cadena de fecha y hora a un formato de fecha y hora legible en español.
function formatDateTime(dateString, timeString) {
    const date = new Date(`${dateString}T${timeString}`);
    const daysOfWeek = [
        "domingo",
        "lunes",
        "martes",
        "miércoles",
        "jueves",
        "viernes",
        "sábado",
    ];
    const months = [
        "enero",
        "febrero",
        "marzo",
        "abril",
        "mayo",
        "junio",
        "julio",
        "agosto",
        "septiembre",
        "octubre",
        "noviembre",
        "diciembre",
    ];
    const dayOfWeek = daysOfWeek[date.getDay()];
    const month = months[date.getMonth()];
    const day = date.getDate();
    const year = date.getFullYear();
    const time12h = formatTime12h(timeString);
    return `${
        dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1)
    }, ${day} de ${
        month.charAt(0).toUpperCase() + month.slice(1)
    } de ${year} <br> ${time12h}`;
}

// Función: formatea una cadena de tiempo y una duración en un rango de horas legible en formato de 12 horas con AM/PM
function formatHourRange(timeString, duration) {
    const startTime = new Date(`2000-01-01T${timeString}`);
    const endTime = new Date(startTime.getTime() + duration * 60 * 60 * 1000);

    const startHour = formatTime12Hours(
        startTime.getHours(),
        startTime.getMinutes()
    );
    const endHour = formatTime12Hours(endTime.getHours(), endTime.getMinutes());

    return `${startHour} hasta ${endHour}`;
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("sure-btn").addEventListener("change", function () {
        if (this.checked) {
            insurancePrice = 10;
            sessionStorage.setItem("insurance", "10.00");
        } else {
            insurancePrice = 0;
            sessionStorage.removeItem("insurance");
        }

        LabelGuests(); // Esto recalcula TODO correctamente
    });

    sessionStorage.setItem("product", xwyz);
    sessionStorage.setItem("date", document.getElementById("c-date").value);
    const preloader = document.getElementById("preloader");
    const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        fullDomain = window.location.origin + "/";
    const c_ln = document.getElementById("c-ln"),
        c_fn = document.getElementById("c-fn"),
        c_email = document.getElementById("c-email"),
        c_phone = document.getElementById("c-phone"),
        c_document = document.getElementById("c-document"),
        observation = document.getElementById("observation");
    const loginModalElement = document.getElementById("modal-login");
    const registerModalElement = document.getElementById("modal-register"),
        RecoverModalElement = document.getElementById("modal-recover");
    subjectToast = document.getElementById("subjectToast");

    const loginModal = new bootstrap.Modal(loginModalElement);
    const registerModal = new bootstrap.Modal(registerModalElement);
    const recoverModal = new bootstrap.Modal(RecoverModalElement);
    const wToast = new bootstrap.Toast(
        document.getElementById("warningToast"),
        {
            delay: 5000,
        }
    );
    const observationMaxLength = 1000;
    const observationCounter = document.getElementById("observation-counter");

    const updateObservationCounter = () => {
        const currentLength = observation.value.length;
        observationCounter.textContent = `${currentLength} / ${observationMaxLength} caracteres`;
        observationCounter.classList.toggle(
            "text-danger",
            currentLength >= observationMaxLength
        );
    };

    observation.addEventListener("input", updateObservationCounter);
    updateObservationCounter();

    function handleButtonClick() {
        let sessionArray = [];

        for (let i = 0; i < sessionStorage.length; i++) {
            let key = sessionStorage.key(i);
            let value = sessionStorage.getItem(key);
            sessionArray.push({ key: key, value: value });
        }

        preloader.classList.remove("hidden");
        fetch(`${fullDomain}api/client/check-authentication`)
            .then((response) => response.json())
            .then((data) => {
                if (!data.isAuthenticated) {
                    loginModal.show();
                } else {
                    const storedUser = JSON.parse(localStorage.getItem("user"));

                    // Actualizar los campos del formulario con la información del usuario
                    c_ln.value = `${storedUser.pattername} ${storedUser.mattername}`;
                    c_fn.value = `${storedUser.names}`;
                    c_email.value = `${storedUser.email}`;
                    c_phone.value = `${storedUser.phone}`;
                    c_document.value = `${storedUser.document}`;

                    if (sessionStorage.getItem("time") == null) {
                        wToast.show();
                        document.getElementById("subjectWarning").innerHTML =
                            "Seleccione su hora de ingreso, el tiempo, y la cantidad de integrantes.";
                        return false;
                    }

                    // Enviar los datos de la sesión al backend
                    fetch(`${fullDomain}cartsession`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken, // Asegúrate de que csrfToken está definido
                        },
                        body: JSON.stringify({ sessionArray }), // Enviar los datos de la sesión
                    })
                        .then((response) => {
                            if (!response.ok) {
                                return response.json().then((errorData) => {
                                    throw new Error(
                                        errorData.message ||
                                            "Error en la solicitud"
                                    );
                                });
                            }
                            return response.json();
                        })
                        .then((sessionData) => {
                            if (sessionData.success) {
                                // Clonar el contenedor del resumen y actualizar la interfaz
                                const summaryContainer = document.querySelector(
                                    ".position-md-sticky"
                                );
                                const clonedSummary =
                                    summaryContainer.cloneNode(true);
                                const targetContainer = document.getElementById(
                                    "reservationDetailsStep2"
                                );

                                targetContainer.innerHTML = "";
                                targetContainer.appendChild(clonedSummary);

                                // Habilitar la pestaña de facturación y navegar a ella
                                document
                                    .getElementById("tabBilling")
                                    .classList.remove("disabled");
                                document.getElementById("tabBilling").click();

                                // Desplazarse hacia arriba
                                window.scrollTo({
                                    top: 0,
                                    behavior: "smooth",
                                });
                            } else {
                                console.error(
                                    "Error al guardar los datos en la sesión:",
                                    sessionData.message
                                );
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error.message || error);
                        });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            })
            .finally(() => {
                preloader.classList.add("hidden");
            });
    }

    function updateSummaryAndProceed() {
        if (
            observation.value.length > observationMaxLength ||
            observation.validity.tooLong
        ) {
            document.getElementById("subjectWarning").textContent =
                `La observación no puede superar los ${observationMaxLength} caracteres.`;
            wToast.show();
            observation.focus();
            return;
        }

        const summaryContainer = document.querySelector(".position-md-sticky");
        const clonedSummary = summaryContainer.cloneNode(true);
        const targetContainer = document.getElementById(
                "reservationDetailsStep3"
            ),
            doc = (selectedWeight = document.querySelector(
                'input[name="weight"]:checked'
            ).value),
            boletaRadio = document.getElementById("weight1");
        facturaRadio = document.getElementById("weight2");

        targetContainer.innerHTML = "";
        targetContainer.appendChild(clonedSummary);

        const checkboxes = document.querySelectorAll(".check-input");
        const isChecked = Array.from(checkboxes).some(
            (checkbox) => checkbox.checked
        );

        if (doc == "F") {
            // Lista de IDs de los campos a validar
            const fields = ["rsocial", "ruc", "dir"];

            // Iterar sobre cada campo para verificar su validez
            for (let id of fields) {
                const field = document.getElementById(id);

                // Verificar si el campo está vacío o no es válido según su HTML
                if (
                    !field ||
                    field.value.trim() == "" ||
                    !field.checkValidity()
                ) {
                    // Mostrar mensaje de advertencia
                    wToast.show();
                    document.getElementById("subjectWarning").innerHTML =
                        "Rellene todos los campos para generar su Factura Electrónica.";
                    return false; // Detener el proceso si un campo no es válido
                }
            }
        }

        if (!isChecked) {
            document.getElementById("subjectWarning").textContent =
                "Acepte los términos y condiciones.";
            wToast.show();
            return;
        }

        if (facturaRadio.checked) {
            const rsocial = document.getElementById("rsocial").value;
            const ruc = document.getElementById("ruc").value;
            const dir = document.getElementById("dir").value;

            bodyData = {
                type: "Factura",
                rsocial: rsocial,
                ruc: ruc,
                dir: dir,
                observation: observation.value,
            };
        } else if (boletaRadio.checked) {
            bodyData = {
                type: "Boleta",
                observation: observation.value,
            };
        }

        preloader.classList.remove("hidden");

        fetch(`${fullDomain}billingsession`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(bodyData),
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
            .then((sessionData) => {
                rowB = sessionData.billing;
                rowc = sessionData.cart;

                document
                    .getElementById("tabPayment")
                    .classList.remove("disabled");
                document.getElementById("tabPayment").click();

                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });

                if (rowB.type == "Boleta") {
                    document.getElementById("typeDoc").innerHTML = `BOLETA A: `;
                    document.getElementById(
                        "numberDoc"
                    ).innerHTML = `${rowB.document}`;
                    document.getElementById(
                        "numberDocLabel"
                    ).innerHTML = `${rowB.number_doc}`;
                    document.getElementById(
                        "nameDoc"
                    ).innerHTML = `NOMBRES Y APELLIDOS:`;
                    document.getElementById(
                        "nameDocLabel"
                    ).innerHTML = `${rowB.lastname_pat} ${rowB.lastname_mat} ${rowB.names}`;
                    document.getElementById(
                        "addressDocLabel"
                    ).innerHTML = `${rowB.address}`;
                    document.getElementById(
                        "phoneDocLabel"
                    ).innerHTML = `${rowB.phone}`;
                    document.getElementById(
                        "mailDocLabel"
                    ).innerHTML = `${rowB.email}`;
                    return true;
                }
                if ((rowB.type = "Factura")) {
                    document.getElementById(
                        "typeDoc"
                    ).innerHTML = `FACTURA A: `;
                    document.getElementById("numberDoc").innerHTML = `R.U.C.`;
                    document.getElementById(
                        "numberDocLabel"
                    ).innerHTML = `${rowB.ruc}`;
                    document.getElementById(
                        "nameDoc"
                    ).innerHTML = `RAZON SOCIAL`;
                    document.getElementById(
                        "nameDocLabel"
                    ).innerHTML = `${rowB.rsocial}`;
                    document.getElementById(
                        "addressDocLabel"
                    ).innerHTML = `${rowB.dir}`;
                    document.getElementById(
                        "phoneDocLabel"
                    ).innerHTML = `${rowB.phone}`;
                    document.getElementById(
                        "mailDocLabel"
                    ).innerHTML = `${rowB.email}`;
                    return true;
                }
            })
            .catch((error) => {
                console.error("Error:", error.message || error);
            })
            .finally(() => {
                preloader.classList.add("hidden");
            });
    }

    function getPayment() {
        preloader.classList.remove("hidden");

        // ACTUALIZAR SESIÓN CON SEGURO
        const insurance = sessionStorage.getItem("insurance") || "0";

        fetch(`${fullDomain}updateInsurance`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ insurance: insurance }),
        })
            .then((response) => response.json()) // Procesar la respuesta del updateInsurance
            .then(() => {
                // LUEGO GENERAR BOTÓN DE PAGO
                return fetch(`${fullDomain}getBtnPayment`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                });
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.status) {
                    var form = document.createElement("form");
                    form.id = "frmVisaNet";
                    form.method = "POST";
                    form.action = data.action;

                    // Agregar campo CSRF al formulario
                    var csrfInput = document.createElement("input");
                    csrfInput.type = "hidden";
                    csrfInput.name = "_token";
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    var script = document.createElement("script");
                    script.src = data.jsUrl;
                    script.setAttribute("data-sessiontoken", data.sessionKey);
                    script.setAttribute("data-channel", "web");
                    script.setAttribute("data-merchantid", data.merchantId);
                    script.setAttribute("data-merchantlogo", data.logoUrl);
                    script.setAttribute(
                        "data-purchasenumber",
                        data.purchaseNumber
                    );
                    script.setAttribute("data-amount", data.amount);
                    script.setAttribute("data-expirationminutes", "5");
                    script.setAttribute("data-timeouturl", data.timeoutUrl);
                    script.setAttribute("data-buttonsize", "LARGE");
                    script.setAttribute("data-formbuttoncolor", "#a22769");

                    form.appendChild(script);

                    var container = document.getElementById("visaNetContainer");
                    if (container) {
                        container.innerHTML = "";
                        container.appendChild(form);
                    } else {
                        console.error("Contenedor no encontrado");
                    }
                } else {
                    console.log(data);
                    wToast.show();
                    document.getElementById("subjectWarning").innerText =
                        data.message;
                }
            })
            .catch((error) => {
                console.error("Error:", error.message || error);
            })
            .finally(() => {
                preloader.classList.add("hidden");
            });
    }

    const buttons = document.querySelectorAll(".btnNext");
    const buttonsBilling = document.querySelectorAll(".btnBilling");
    const buttonsPayment = document.querySelectorAll(".check-payment");
    const checkPaymentDesktop = document.getElementById(
        "check-payment-desktop"
    );
    const checkPaymentMobile = document.getElementById("check-payment-mobile");

    buttons.forEach((button) => {
        button.addEventListener("click", handleButtonClick);
    });

    buttonsBilling.forEach((button) => {
        button.addEventListener("click", updateSummaryAndProceed);
    });

    buttonsPayment.forEach((button) => {
        button.addEventListener("click", function () {
            // Verificar si uno de los checkboxes está marcado
            if (checkPaymentDesktop.checked || checkPaymentMobile.checked) {
                // Deshabilitar los checkboxes para que no se puedan desmarcar
                checkPaymentDesktop.disabled = true;
                checkPaymentMobile.disabled = true;

                // Eliminar el formulario anterior si existe
                let existingForm = document.getElementById("frmVisaNet");
                if (existingForm) {
                    existingForm.remove();
                }

                // Ejecutar la función para obtener el pago
                getPayment();
            } else {
                console.log("Debe aceptar los Términos y Condiciones");
            }
        });
    });

    let modalScroll = new bootstrap.Modal(
        document.getElementById("modalScroll")
    );

    // Evento para aceptar los términos
    document
        .getElementById("btn-accept-terms")
        .addEventListener("click", function () {
            // Verificar si el checkbox ya está marcado
            if (!checkPaymentDesktop.checked) {
                // Habilitar y marcar el checkbox solo la primera vez
                checkPaymentDesktop.checked = true;
                checkPaymentMobile.checked = true;

                // Ejecutar la función getPayment solo una vez
                getPayment();
            }

            // Cerrar el modal utilizando la instancia reutilizada
            modalScroll.hide();
        });

    // Evento para abrir el modal
    document.querySelectorAll(".open-modal").forEach(function (label) {
        label.addEventListener("click", function () {
            // Mostrar el modal utilizando la instancia reutilizada
            modalScroll.show();
        });
    });

    document
        .getElementById("btnRegister")
        .addEventListener("click", function () {
            loginModal.hide();
            registerModal.show();
        });

    document
        .getElementById("btnRecover")
        .addEventListener("click", function () {
            loginModal.hide();
            recoverModal.show();
        });

    document
        .getElementById("tabReservation")
        .addEventListener("click", function () {
            location.reload();
        });

    document.getElementById("btnLogin").addEventListener("click", function () {
        registerModal.hide();
        loginModal.show();
    });

    loginModalElement.addEventListener("hidden.bs.modal", function () {
        const form = loginModalElement.querySelector("form");

        if (form) {
            form.reset(); // Llamar al método reset del formulario
            form.classList.remove("was-validated"); // Elimina la clase de validación si existía
        }

        const alert = loginModalElement.querySelector(".alert");
        if (alert) {
            alert.remove(); // Elimina cualquier alerta
        }
    });

    registerModalElement.addEventListener("hidden.bs.modal", function () {
        const form = registerModalElement.querySelector("form");

        if (form) {
            form.reset(); // Llamar al método reset del formulario
            form.classList.remove("was-validated"); // Elimina la clase de validación si existía
        }

        const alert = registerModalElement.querySelector(".alert");
        if (alert) {
            alert.remove(); // Elimina cualquier alerta
        }
    });

    const f = document.getElementById("liveToast");
    const loginForm = document.getElementById("form-login");
    const recoverForm = document.getElementById("form-recover");
    const loginButton = document.getElementById("login-button");
    const recoverButton = document.getElementById("recover-button");

    function setLoadingState(button, text) {
        button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
      ${text}...
        `;
        button.disabled = true;
    }

    var a = new bootstrap.Toast(f, { delay: 3000 });

    function resetButtonState(button, originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }

    function handleServerResponse(response, form, button, originalText) {
        if (!response.ok) {
            throw new Error(response.error);
        }
    }

    function displayErrorMessage(form, message) {
        const existingErrorDiv = form.querySelector(".alert-danger");
        if (existingErrorDiv) {
            existingErrorDiv.remove();
        }

        const errorDiv = document.createElement("div");
        errorDiv.classList.add("alert", "alert-danger");
        errorDiv.textContent = message;
        form.prepend(errorDiv);

        setTimeout(() => {
            errorDiv.remove();
        }, 4000);
    }

    function clearErrorMessage(form) {
        const alert = form.querySelector(".alert-danger");
        if (alert) {
            alert.remove();
        }
    }

    function updateUserMenu(client) {
        const domain = window.location.origin;
        const accountInfoDiv = document.getElementById("account-info");
        accountInfoDiv.innerHTML = `
            <div class="dropdown nav d-block order-lg-2 ms-auto">
                <a class="nav-link d-flex align-items-center p-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="border rounded-circle" src="${client.avatar}" width="48" alt="${client.name}">
                    <div class="d-none d-sm-block ps-2">
                        <div class="fs-xs lh-1 opacity-60">Hola,</div>
                        <div class="fs-sm dropdown-toggle">${client.name}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end my-1">
                    <a class="dropdown-item" href="${domain}/Perfil"><i
                                        class="ai-user-check fs-lg opacity-70 me-2"></i>Perfil</a>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="dropdown-item" onclick=" window.location.reload();">
                        <i class="ai-logout fs-lg opacity-70 me-2"></i>Cerrar Sesión
                    </button>
            </div>
        `;
    }

    async function handleFormSubmit(event) {
        event.preventDefault();

        if (!loginForm.checkValidity()) {
            loginForm.classList.add("was-validated");
            return;
        }

        setLoadingState(loginButton, "Validando");

        const formData = new FormData(loginForm);
        const data = {
            number: formData.get("number_login"),
            password: formData.get("password_login"),
        };

        try {
            const response = await fetch(`${fullDomain}api/client/login`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            if (response.ok && result.user) {
                localStorage.setItem("user", JSON.stringify(result.user));
                updateUserMenu(result.user);
                subjectToast.innerHTML = "Bienvenido, " + result.user.name;
                loginModal.hide();
                resetButtonState(loginButton, "Ingresar");
                a.show();
            } else {
                handleServerResponse(
                    result,
                    loginForm,
                    loginButton,
                    "Ingresar"
                );
            }
        } catch (error) {
            displayErrorMessage(loginForm, error.message);
            resetButtonState(loginButton, "Ingresar");
        }
    }

    async function handleFormRecoverSubmit(event) {
        event.preventDefault();

        if (!recoverForm.checkValidity()) {
            recoverForm.classList.add("was-validated");
            return;
        }

        setLoadingState(recoverButton, "Validando");

        const formData = new FormData(recoverForm);

        const data = {
            email: formData.get("email_recover"),
        };

        try {
            const response = await fetch(`${fullDomain}api/client/recover`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            if (response.ok) {
                subjectToast.innerHTML = result.message;
                recoverModal.hide();
                document.getElementById("recover-email").value = "";
                resetButtonState(recoverButton, "Restablecer contraseña");
                a.show();
            } else {
                handleServerResponse(
                    result,
                    recoverForm,
                    recoverButton,
                    "Restablecer contraseña"
                );
            }
        } catch (error) {
            displayErrorMessage(recoverForm, error.message);
            resetButtonState(loginButton, "Restablecer contraseña");
        }
    }

    function handleServerResponse(result, loginForm, loginButton, buttonText) {
        if (result.error) {
            displayErrorMessage(loginForm, result.error);
        } else {
            clearErrorMessage(loginForm);
        }
        resetButtonState(loginButton, buttonText);
    }

    if (loginForm) {
        loginForm.addEventListener("submit", handleFormSubmit);
    }

    if (recoverForm) {
        recoverForm.addEventListener("submit", handleFormRecoverSubmit);
    }

    const form = document.getElementById("form-register");

    if (form) {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            registerButton = document.getElementById("register-button");

            if (!form.checkValidity()) {
                form.classList.add("was-validated");
                return;
            }
            setLoadingState(registerButton, "Registrando");

            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch(
                    `${fullDomain}api/client/register`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        body: JSON.stringify(data),
                    }
                );

                const responseText = await response.text();
                const result = JSON.parse(responseText);

                if (!response.ok) {
                    const errors = result.errors || {};
                    let errorList = "";

                    // Recorre los errores y acumula los mensajes en una lista
                    for (const [field, messages] of Object.entries(errors)) {
                        const errorItems = messages
                            .map((message) => `<li>${message}</li>`)
                            .join("");
                        errorList += errorItems;
                    }

                    // Solo muestra una alerta si hay errores
                    if (errorList) {
                        const alertContainer =
                            document.getElementById("alert-container");
                        if (alertContainer) {
                            // Limpia el contenedor de alertas
                            alertContainer.innerHTML = `
                                <div class="alert d-flex alert-danger" role="alert">
                                    <i class="ai-circle-x fs-xl pe-1 me-2"></i>
                                    <div>
                                        <strong>Corregir los siguientes campos :</strong>
                                        <ul>${errorList}</ul>
                                    </div>
                                </div>
                            `;
                        }
                    }

                    resetButtonState(registerButton, "Registrarse");

                    return;
                }

                // Mostrar el alert de éxito
                const alertContainer =
                    document.getElementById("alert-container");
                if (alertContainer) {
                    alertContainer.innerHTML = `
                        <div class="alert d-flex alert-success" role="alert">
                            <i class="ai-circle-check fs-xl pe-1 me-2"></i>
                            <div>Registro exitoso. Se ha enviado un código de validación a su correo. en instantes se mostrara el formulario de inicio de sesión.</div>
                        </div>
                    `;
                }

                resetButtonState(registerButton, "Registrarse");
                registerButton.disabled = true;

                setTimeout(() => {
                    registerModal.hide();
                    loginModal.show();
                }, 5000);
            } catch (error) {
                // Mostrar el alert de error
                const alertContainer =
                    document.getElementById("alert-container");
                if (alertContainer) {
                    alertContainer.innerHTML = `
                        <div class="alert d-flex alert-danger" role="alert">
                            <i class="ai-circle-x fs-xl pe-1 me-2"></i>
                            <div>Error: ${error.message}</div>
                        </div>
                    `;
                }
            }
        });
    }

    const weight1 = document.getElementById("weight1");
    const weight2 = document.getElementById("weight2");
    const collapseExample = new bootstrap.Collapse(
        document.getElementById("collapseExample"),
        {
            toggle: false,
        }
    );

    if (weight2.checked) {
        collapseExample.show();
    } else {
        collapseExample.hide();
    }

    // Escuchar cambios en los radio buttons
    weight1.addEventListener("change", function () {
        if (this.checked) {
            collapseExample.hide();
        }
    });

    weight2.addEventListener("change", function () {
        if (this.checked) {
            collapseExample.show();
        }
    });
});
