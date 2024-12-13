

const btn_iniciar = document.querySelector("#iniciar-btn");
const btn_register = document.querySelector("#register-btn");
const cont_formulario =document.querySelector(".cont-formulario");

    btn_register.addEventListener('click', () =>{
        cont_formulario.classList.add("register-mode");
    });
    
    btn_iniciar.addEventListener('click', () =>{
        cont_formulario.classList.remove("register-mode");
    });


    

    
   /* Validacion del formulario de registros */

   document.getElementById('Formulario').addEventListener('submit', function (e) {
    // Obtener valores de los campos
    const email = document.getElementById('Email').value;
    const password = document.getElementById('Contrasena').value;
    const confirmPassword = document.getElementById('ConfirmarContrasena').value;

    // Validar correo
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Por favor, ingresa un correo electrónico válido.');
        e.preventDefault();
        return;
    }

    // Validar contrasena
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
    if (!passwordRegex.test(password)) {
        alert('La contrasena debe contener al menos 8 caracteres, una mayúscula y un número.');
        e.preventDefault();
        return;
    }

    // Confirmar contrasena
    if (password !== confirmPassword) {
        alert('Las contrasenas no coinciden.');
        e.preventDefault();
    }
});

/*Crear factura*/

