<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/Styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" href="/icon/Inicio De Sesión.jpeg">
    <title>Inicio De sesión</title>
</head>

<body class="body-formulario">

<?php
$mensaje = "";
$mensaje_exitoso = "";
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

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['usuario']) || empty($_POST['email']) || empty($_POST['contrasena']) || empty($_POST['confirmar_contrasena'])) {
        $mensaje = "Por favor, completa todos los campos.";
    } elseif ($_POST['contrasena'] !== $_POST['confirmar_contrasena']) {
        $mensaje = "Las contrasenas no coinciden.";
    } else {
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE Usuario = ?");
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->bind_result($userExists);
        $stmt->fetch();
        $stmt->close();

        if ($userExists > 0) {
            $mensaje = "El usuario ya está registrado.";
        } else {
            // Verificar si el correo ya existe
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE `Email` = ?");
            if (!$stmt) {
                die("Error al preparar la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($emailExists);
            $stmt->fetch();
            $stmt->close();

            if ($emailExists > 0) {
                $mensaje = "El correo ya está registrado.";
            } else {
                // Insertar datos en la base de datos
                $stmt = $conn->prepare("INSERT INTO usuarios (Usuario, `Email`, Contrasena) VALUES (?, ?, ?)");
                if (!$stmt) {
                    die("Error al preparar la consulta: " . $conn->error);
                }
                $stmt->bind_param("sss", $usuario, $email, $contrasena);

                if ($stmt->execute()) {
                    $mensaje_exitoso = "Registro exitoso.";
                } else {
                    $mensaje = "Error al registrar: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
?>



<?php
$mensaje_sesion = ""; // Variable para los mensajes de inicio de sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {


    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para buscar el usuario en la base de datos por correo electrónico
    $stmt = $conn->prepare("SELECT Id, Usuario, Contrasena FROM usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Usuario encontrado
        $stmt->bind_result($user_id, $username, $hashed_password);
        $stmt->fetch();

        if ($password === $hashed_password) {
            // Contraseña correcta
            session_start();
            $_SESSION['Id'] = $user_id;       // Guardar el ID del usuario en la sesión
            $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión
            $_SESSION['email'] = $email;       // Guardar el correo electrónico en la sesión

            // Verifica si el usuario es Admin
            if ($user_id == 22 && $email == 'carcitadel.1@gmail.com') {
                header("Location: /php/Admin/Admin.php"); // Redirige a la página del operador
                exit;
            } else {
                header("Location: /index.php"); // Redirige a la página principal para usuarios normales
                exit;
            }
        } else {
            // Contraseña incorrecta
            $mensaje_sesion = "Contraseña incorrecta.";
        }
    } else {
        // Correo electrónico no encontrado
        $mensaje_sesion = "El correo electrónico no está registrado. Por favor, regístrate.";
    }

    $stmt->close();
}
?>


    <div class="cont-formulario">
        <div class="form-container">
            <div class="inicio-register">
                <form action="" method="POST" class="inicio-form">
                    <h2 class="title-inicio">Iniciar Sesion</h2>
                    <div class="input-field">
                        <i class="usuario"></i>
                        <input type="text" name="email" placeholder="Correo electronico" required>
                    </div>
                    <div class="input-field">
                        <i class="contrasena-form"></i>
                        <input type="password" name="password" placeholder="Contrasena" required>
                    </div>
                    
                    <input type="submit" value="Iniciar Sesion" class="btn-iniciar">
                    <a href="/php/olvidecontra.php">Olvide mi contrasena</a>

                    <p style="color: red;"><?= htmlspecialchars($mensaje_sesion) ?></p><p style="color: red;"><?= htmlspecialchars($mensaje) ?></p><p style="color: green;"><?= htmlspecialchars($mensaje_exitoso) ?></p>
                
                </form>
                

                <form action="" method="POST" class="register-form" id="Formulario">
                    <h2 class="title-inicio">Registrar</h2>
                    
                    <div class="input-field">
                        <i class="usuario"></i>
                        <input type="text" name="usuario" id="Usuario" placeholder="Usuario" required>
                    </div>
                    <div class="input-field">
                        <i class="usuario"></i>
                        <input type="text" name="email" id="Email" placeholder="Email" required>
                    </div>
                    <div class="input-field">
                        <i class="usuario"></i>
                        <input type="password" name="contrasena" id="Contrasena" placeholder="Contrasena" required>
                    </div>
                    <div class="input-field">
                        <i class="contrasena-form"></i>
                        <input type="password" name="confirmar_contrasena" id="ConfirmarContrasena" placeholder="Confirmar contrasena" required>
                    </div>
                    <p style="color: red;"><?= htmlspecialchars($mensaje) ?></p>
                    <input type="submit" value="Registrar" class="register-btn">
                </form>
                
            </div>
        </div>
        <div class="panel-content">
            <div class="panel left-panel">
                <div class="content-panel">
                    <h3>Vamos A Registrarnos!</h3>
                    <p>¿Aun no te encuentras Registrado? Que estas esperando registrate justo aqui</p>
                   
                    <button type="submit" class="btn transparent" id="register-btn">Registrate</button>
                </div>

                <img src="#" class="img-form" alt="">
            </div>

            <div class="panel right-panel">
                <div class="content-panel">
                    <h3>Ya Tienes Una Cuenta</h3>
                    <p>¿Ya tienes una cuenta? Que estas esperando empezemos juntos una nueva aventura con Car Citadel </p>
                    <button type="submit" class="btn transparent" id="iniciar-btn">Iniciar Sesion</button>
                </div>
                <img src="/img/undraw_automobile_repair_ywci.svg" class="img-form" alt="width="400px" height="400px" ">
            </div>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="/Script.js"></script>
</body>

</html>