/*Inicio login*/
// Seleccionar elementos
const profileButton = document.getElementById('profileButton');
const dropdownMenu = document.getElementById('dropdownMenu');

// Mostrar/ocultar el menú desplegable
profileButton.addEventListener('click', () => {
  const isMenuVisible = dropdownMenu.style.display === 'block';
  dropdownMenu.style.display = isMenuVisible ? 'none' : 'block';
});

// Ocultar el menú al hacer clic fuera de él
document.addEventListener('click', (event) => {
  if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
    dropdownMenu.style.display = 'none';
  }
});

// Ejemplo de acción al hacer clic en "Cerrar Sesión"
document.getElementById('logout').addEventListener('click', () => {
  Swal.fire({
    title: 'Cierre de Sesión',
    text: 'Has cerrado sesión correctamente.',
    icon: 'info',
    confirmButtonText: 'Aceptar'
  });
  // Redirigir o realizar otras acciones
});

document.getElementById('Formulario').addEventListener('submit', function (e) {
  // Obtener valores de los campos
  const email = document.getElementById('Email').value;
  const password = document.getElementById('Contrasena').value;
  const confirmPassword = document.getElementById('ConfirmarContrasena').value;

  // Validar correo
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    e.preventDefault();
    Swal.fire({
      title: 'Correo inválido',
      text: 'Por favor, ingresa un correo electrónico válido.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  // Validar contraseña
  const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  if (!passwordRegex.test(password)) {
    e.preventDefault();
    Swal.fire({
      title: 'Contraseña inválida',
      text: 'La contraseña debe contener al menos 8 caracteres, una mayúscula y un número.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  // Confirmar contraseña
  if (password !== confirmPassword) {
    e.preventDefault();
    Swal.fire({
      title: 'Contraseñas no coinciden',
      text: 'Las contraseñas ingresadas no coinciden. Por favor, inténtalo de nuevo.',
      icon: 'error',
      confirmButtonText: 'Aceptar'
    });
  }
});
