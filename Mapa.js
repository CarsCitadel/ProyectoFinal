const container_Mapa = document.querySelector(".container_Mapa");
const Espacios = document.querySelectorAll(".row .Espacio:not(.sold)");
const count = document.getElementById("count");
const total = document.getElementById("total");
const movieSelect = document.getElementById("vehicleType");

let ticketPrice = +movieSelect.value;

// Save selected movie index and price
function setMovieData(movieIndex, moviePrice) {
  localStorage.setItem("selectedMovieIndex", movieIndex);
  localStorage.setItem("selectedMoviePrice", moviePrice);
}

// Update total and count
function updateSelectedCount() {
  const selectedEspacios = document.querySelectorAll(".row .Espacio.selected");

  const EspaciosIndex = [...selectedEspacios].map((Espacio) => [...Espacios].indexOf(Espacio));

  localStorage.setItem("selectedEspacios", JSON.stringify(EspaciosIndex));

  const selectedEspaciosCount = selectedEspacios.length;

  count.innerText = selectedEspaciosCount;
  total.innerText = selectedEspaciosCount * ticketPrice;

  setMovieData(movieSelect.selectedIndex, movieSelect.value);
}

// Get data from localstorage and populate UI
function populateUI() {
  const selectedEspacios = JSON.parse(localStorage.getItem("selectedEspacios"));

  if (selectedEspacios !== null && selectedEspacios.length > 0) {
    Espacios.forEach((Espacio, index) => {
      if (selectedEspacios.indexOf(index) > -1) {
        console.log(Espacio.classList.add("selected"));
      }
    });
  }

  const selectedMovieIndex = localStorage.getItem("selectedMovieIndex");

  if (selectedMovieIndex !== null) {
    movieSelect.selectedIndex = selectedMovieIndex;
    console.log(selectedMovieIndex);
  }
}

// Movie select event
movieSelect.addEventListener("change", (e) => {
  ticketPrice = +e.target.value;
  setMovieData(e.target.selectedIndex, e.target.value);
  updateSelectedCount();
});

// Seat click event
container_Mapa.addEventListener("click", (e) => {
  if (
    e.target.classList.contains("Espacio") &&
    !e.target.classList.contains("sold")
  ) {
    // Deseleccionar todos los espacios antes de seleccionar el nuevo
    Espacios.forEach((Espacio) => Espacio.classList.remove("selected"));

    // Seleccionar el espacio actual
    e.target.classList.add("selected");

    updateSelectedCount();
  }
});

// Initial count and total set
updateSelectedCount();

// Obtener referencia al campo de "Espacio" en el formulario
const espacioInput = document.getElementById("Espacio");

// Evento de clic en el contenedor del mapa
container_Mapa.addEventListener("click", (e) => {
  if (
    e.target.classList.contains("Espacio") &&
    !e.target.classList.contains("sold")
  ) {
    // Obtener el ID del espacio seleccionado (A1, B2, etc.)
    const espacioId = e.target.getAttribute("data-id");

    // Si el espacio se selecciona, asignarlo al campo del formulario
    if (e.target.classList.contains("selected")) {
      espacioInput.value = espacioId; // Mostrar el ID del espacio en el formulario
    } else {
      espacioInput.value = ''; // Limpiar el campo si se deselecciona
    }
  }
});

/* obtener el valor del precio en el formulario de reserva*/

/* Obtener el campo select de tipo de vehículo*/
const vehicleSelect = document.getElementById('vehicleType');

// Obtener los campos del formulario
const vehicleInput = document.getElementById('Vehiculo');
const priceInput = document.getElementById('Precio_Hora');

// Actualizar el formulario cuando se selecciona un vehículo
vehicleSelect.addEventListener('change', function() {
  const vehicleName = vehicleSelect.options[vehicleSelect.selectedIndex].text;
  const vehiclePrice = vehicleSelect.value;

  // Mostrar el tipo de vehículo seleccionado en el formulario
  vehicleInput.value = vehicleName;

  // Mostrar el precio correspondiente en el formulario
  priceInput.value = `${vehiclePrice}`;
});

// Inicializar el formulario con el primer vehículo seleccionado
window.addEventListener('load', function() {
  vehicleSelect.dispatchEvent(new Event('change'));
});

/* crear factura*/

// Calcular precio basado en las horas
function calculatePrice() {
  const entrada = new Date(document.getElementById("Hora_Entrada").value);
  const salida = new Date(document.getElementById("Hora_Salida").value);
  const precioPorHora = parseFloat(document.getElementById("Precio_Hora").value);

  if (isNaN(entrada.getTime()) || isNaN(salida.getTime())) {
    Swal.fire({
      title: 'Error',
      text: "Por favor, ingresa ambas fechas y horas.",
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  if (isNaN(precioPorHora) || precioPorHora <= 0) {
    Swal.fire({
      title: 'Error',
      text: "Por favor, ingresa un precio por hora válido.",
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  const diferenciaHoras = Math.abs(salida - entrada) / 36e5; // Milisegundos a horas
  const precioTotal = (diferenciaHoras * precioPorHora).toFixed(2);

  document.getElementById("Precio").value = `$${precioTotal}`;
}

// Asociar eventos para calcular el precio automáticamente
document.getElementById("Hora_Entrada").addEventListener("change", calculatePrice);
document.getElementById("Hora_Salida").addEventListener("change", calculatePrice);
document.getElementById("Precio_Hora").addEventListener("input", calculatePrice);

document.getElementById("form_reserva").addEventListener("submit", function (e) {
  e.preventDefault(); // Evitar el envío del formulario

  // Extraer valores del formulario
  const nombre = document.getElementById("Nombre").value;
  const placa = document.getElementById("Placa").value;
  const vehiculo = document.getElementById("Vehiculo").value;
  const espacio = document.getElementById("Espacio").value;
  const horaEntrada = document.getElementById("Hora_Entrada").value;
  const horaSalida = document.getElementById("Hora_Salida").value;
  const precioPorHora = document.getElementById("Precio_Hora").value;
  const precio = document.getElementById("Precio").value;

  // Verificar que todos los campos estén completos
  if (!nombre || !placa || !horaEntrada || !horaSalida || !precioPorHora || !precio) {
    Swal.fire({
      title: 'Error',
      text: "Por favor, completa todos los campos antes de generar la factura.",
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;
  }
});
