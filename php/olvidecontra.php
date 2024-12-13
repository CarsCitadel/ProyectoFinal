<?php
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cars_citadel";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = ""; // Variable para almacenar mensajes de error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo está registrado en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado
        $usuario = $result->fetch_assoc();

        // Generar un token único
        $token = bin2hex(random_bytes(50));

        // Actualizar el token en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET token = ? WHERE Email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Enlace para restablecer la contraseña
        $resetLink = "http://localhost:3000/php/recuperarcontra.php?token=" . $token;

        // Configuración de PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto si usas otro proveedor
            $mail->SMTPAuth = true;
            $mail->Username = 'carcitadel.1@gmail.com'; // Tu correo
            $mail->Password = 'ajql biqm nvii osrt'; // Tu contraseña de correo o token de aplicación
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('carcitadel.1@gmail.com', 'Car Citadel'); // Remitente
            $mail->addAddress($email); // Destinatario
            $mail->Subject = 'Recuperar Contraseña';
            $mail->isHTML(true);
            $mail->Body = "
                            <html>
                            <head>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                        margin: 0;
                                        padding: 0;
                                        background-color: #f4f4f9;
                                    }
                                    .email-container {
                                        max-width: 600px;
                                        margin: 20px auto;
                                        padding: 20px;
                                        background-color: #ffffff;
                                        border-radius: 8px;
                                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                        text-align: center;
                                    }
                                    .email-header {
                                        font-size: 24px;
                                        font-weight: bold;
                                        color: #007BFF;
                                    }
                                    .email-body {
                                        text-align: left;
                                        margin-top: 20px;
                                    }
                                    .email-footer {
                                        margin-top: 20px;
                                        font-size: 12px;
                                        color: #666;
                                    }
                                    .btn {
                                        display: inline-block;
                                        margin-top: 20px;
                                        padding: 10px 20px;
                                        background-color: #007BFF;
                                        color: #fff;
                                        text-decoration: none;
                                        border-radius: 5px;
                                        font-size: 16px;
                                    }
                                    .btn:hover {
                                        background-color: #0056b3;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class='email-container'>
                                    <div class='email-header'>Restablecimiento de Contrasena</div>
                                    <div class='email-body'>
                                        <p>Hola <strong>{$usuario['usuario']}</strong>,</p>
                                        <p>Recibimos una solicitud para restablecer tu contrasenaa. Haz clic en el botón de abajo para continuar:</p>
                                        <a href='$resetLink' class='btn'>Restablecer Contrasena</a>
                                        <p>O copia y pega este enlace en tu navegador:</p>
                                        <p><a href='$resetLink'>$resetLink</a></p>
                                        <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                                    </div>
                                    <div class='email-footer'>
                                        <p>© 2024 Car Citadel. Todos los derechos reservados.</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                        ";


            // Enviar el correo
            $mail->send();
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correo enviado',
                            text: 'Por favor revisa tu bandeja de entrada.',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/php/conexion.php';
                            }
                        });
                    });
                </script>";

        } catch (Exception $e) {
            $error = "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        // Usuario no encontrado
        $error = "El correo electrónico no está registrado. Por favor verifica e intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="body-olvidar">
    <div class="container-olvide mt-5">
        <h2 class="text-olvidar">Recuperar Contraseña</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form id="recuperar-form" action="" method="POST" class="recuperar-form mt-4">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<style>
    /* Archivo Styles.css */

/* Fondo de la página */
.body-olvidar {
    background-color: #f4f7f6;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Contenedor principal */
.container-olvide {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

/* Título del formulario */
.text-olvidar {
    color: rgb(18, 123, 172)100%;
    text-align: center;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Etiquetas de los campos */
.form-label {
    font-weight: bold;
}

/* Estilo de los campos de entrada */
.form-control {
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: none;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Botón */
.btn-primary {
    background-color: rgb(18, 123, 172);
    border-color: white;
    border-radius: 5px;
    padding: 10px 20px;
    width: 100%;
    font-size: 20px;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

/* Estilos de la alerta de error */
.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    border-radius: 5px;
    padding: 10px;
    font-weight: 600;
}

.alert-danger a {
    color: #721c24;
    text-decoration: underline;
}

@media (max-width: 576px) {
    .container-olvide {
        padding: 20px; /* Reduce el espacio interno para pantallas más pequeñas */
    }

    .text-olvidar {
        font-size: 1.5rem; /* Ajusta el tamaño del título */
    }

    .btn-primary {
        padding: 8px 16px; /* Reduce el tamaño del botón */
    }
}

</style>