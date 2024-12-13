<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cars_citadel";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "";
}

// Verificar si se proporciono un token en la URL
if (!isset($_GET['token'])) {
    die("Token no proporcionado.");
}

$token = $_GET['token'];
$mensaje = "";

// Verificar si el token es valido
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("El token es invalido o ha expirado.");
}

$usuario = $result->fetch_assoc();
$email = $usuario['Email']; // Obtener el email asociado al token

// Manejar el formulario de restablecimiento de contrasena
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_password = $_POST['Contrasena'];
    $confirm_password = $_POST['ConfirmarContrasena'];

    if ($nueva_password !== $confirm_password) {
        $mensaje = "Las contrasenas no coinciden.";
    } else {
        // Guardar la nueva contraseña sin encriptarla
        // Nota: Esto no es recomendable desde el punto de vista de la seguridad.
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, token = NULL WHERE Email = ?");
        $stmt->bind_param("ss", $nueva_password, $email);
        
        if ($stmt->execute()) {
            $mensaje = "Contrasena actualizada exitosamente.";
        } else {
            $mensaje = "Error al actualizar la contrasena. Intentalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Manejamos el mejor parquiadero de Ibague">
    <meta name="robots" content="index,follow">
    <title>Cars Citadel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="body-recuperar">

    <div class="container mt-5">
    <h2 class="text-recuperarr">Restablecer Contraseña</h2>
    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="" method="post" class="mt-4" id="Formulario" onsubmit="return validarContrasena()">
        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" name="Contrasena" id="Contrasena" class="form-control" placeholder="Ingresa tu nueva contrasena" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="ConfirmarContrasena" id="ConfirmarContrasena" class="form-control" placeholder="Confirma tu nueva contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function validarContrasena() {
        var contrasena = document.getElementById("Contrasena").value;
        var confirmarContrasena = document.getElementById("ConfirmarContrasena").value;

        // Verificar que la contraseña y la confirmación coinciden
        if (contrasena !== confirmarContrasena) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Las contraseñas no coinciden.',
                confirmButtonText: 'Aceptar'
            });
            return false; // Evita que el formulario se envíe
        }

        // Expresión regular para validar la contraseña
        var regex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

        // Verificar que la contraseña cumpla con los requisitos
        if (!regex.test(contrasena)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La contraseña debe contener al menos 8 caracteres, una mayúscula y un número.',
                confirmButtonText: 'Aceptar'
            });
            return false; // Evita que el formulario se envíe
        }

        return true; // Si pasa todas las validaciones, el formulario se envía
    }
</script>
</body>
</html>
<style>
    .body-recuperar {
    background-color: #f4f7f6;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Contenedor del formulario */
.container {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

/* Título */
.text-recuperarr {
    color: black;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.5rem;
}

/* Mensaje de alerta */
.alert-info {
    font-weight: bold;
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
}

/* Etiquetas */
.form-label {
    font-weight: bold;
    font-size: 1rem;
}

/* Campos de entrada */
.form-control {
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: none;
    transition: border-color 0.3s ease;
    font-size: 1rem;
    padding: 10px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Botón */
.btn-primary {
    background-color: rgb(18, 123, 172,);
    border-color: #007bff;
    border-radius: 5px;
    padding: 10px 20px;
    width: 100%;
    font-size: 1rem;
    font-weight: bold;
}

.btn-primary:hover {
    background-color: rgb(18, 123, 172,);
    border-color: #0056b3;
}

/* Media Query para pantallas grandes */
@media (min-width: 768px) {
    .container {
        max-width: 500px;
    }
}
</style>