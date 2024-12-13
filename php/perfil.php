<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['username'])) {
    // Si no está logueado, redirige a la página de inicio de sesión
    header("Location: /php/conexion.php");
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cars_citadel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener el nombre de usuario desde la sesión
    $loggedInUsername = $_SESSION['username'];

    // Obtener los datos originales del usuario desde la base de datos
    $query = "SELECT Usuario, `Email`, Contrasena FROM usuarios WHERE Usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $loggedInUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica si se encontraron los datos del usuario
    if ($result->num_rows > 0) {
        $originalData = $result->fetch_assoc();
    } else {
        die("Usuario no encontrado.");
    }
    
    $stmt->close();

    // Asignar valores del formulario o mantener los originales si no se envían
    $username = isset($_POST['username']) && !empty(trim($_POST['username'])) ? trim($_POST['username']) : $originalData['Usuario'];
    $email = isset($_POST['email']) && !empty(trim($_POST['email'])) ? trim($_POST['email']) : $originalData['E-Mail'];
    $password = isset($_POST['password']) && !empty(trim($_POST['password'])) ? trim($_POST['password']) : $originalData['Contrasena'];

    // Preparar la consulta para actualizar
    $query = "UPDATE usuarios SET Usuario = ?, `Email` = ?, Contrasena = ? WHERE Usuario = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssss", $username, $email, $password, $loggedInUsername);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Actualizar los valores en la sesión
    
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Perfil actualizado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                })
            });
        </script>";
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar el perfil: " . $stmt->error . "',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>";
    }
    
    $stmt->close();
}

$conn->close();
?>


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
    <link rel="stylesheet" href="/perfil.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
<!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="icon" href="/icon/usuario-de-perfil.png">
</head>
<body class="perfilloguin">
   <!-- HTML -->
    <!--Inicio de navbar-->
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid contenedor1">
            <a class="navbar-brand logo_Titulo" href="#"><img src="/img/logo Parqueadero.png" width="100px" alt="">
                <h1>Cars Citadel</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse contendor__list" id="navbarNav">
                <ul class="navbar1">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Si el usuario está logueado, mostrar el perfil y la opción de cerrar sesión -->
                    <li class="nav-item"><a href="/mapa.html"> <img class="icon" src="/icon/reserva.png" width="23"> Reserva ahora! </a></li>
                    <li class="nav-item"><a href="/index.php"> <img class="icon" src="/icon/casa-icono-silueta.png" width="23"> Inicio </a></li>
                    <li><h6 class="nombre-navbar"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?></h6></li>
                    <div class="navbar-profile">
                    <img src="/icon/usuario-de-perfil.png" alt="Foto de perfil" class="profile-picture" id="profileButton">
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="#">Perfil</a></li>
                        <li><a href="/php/Historial.php">Historial</a></li>
                        <li><a href="/php/logout.php" id="logout">Cerrar Sesión</a></li>
                    </ul>
                    </div>
                <?php else: ?>
                    <!-- Si el usuario no está logueado, mostrar solo inicio de sesión y reserva -->
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/Inicio De Sesión.gif" width="23"> Inicio de sesión </a></li>
                    <li class="nav-item"><a href="/php/conexion.php"> <img class="icon" src="/icon/reserva.png" width="23"> Reserva ahora! </a></li>
                <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <!--Fin navbar-->


    <div class="FondoPerfil">
        <img src="/img/Fondoperfil.jpg" alt="">
    </div>

	<div class="Container-Perfil">
		<div class="imagenperfil">
			<img src="/img/presentacionperfil.gif"></img>
		</div>

		<div class="contenido-perfil">
			<form class="form-perfil" id="Formulario" method="POST">
				<img src="/icon/usuario-de-perfil.png">
				<h6 class="tituloperfil">Bienvenid@ <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?></h6>

           		<div class="campos-perfil one">
           		   <div class="iconosperfil">
           		   		<i class="fas fa-user"></i>
           		   </div>
					<div class="Datos-perfil">
						<input type="text" name="username" placeholder="Nombre"  >
           		   </div>
           		</div>

				<div class="campos-perfil one">
					<div class="iconosperfil">
						<i class="fa-solid fa-envelope"></i>
					</div>
					<div class="Datos-perfil">
                        <input type="text" name="email" id="Email" placeholder="Correo Electronico">
					</div>
				 </div>

				 <div class="campos-perfil one">
					<div class="iconosperfil">
						<i class="fas fa-lock"></i>
					</div>
					<div class="Datos-perfil">
                    <input type="password" name="password" placeholder="Contrasena" id="Contrasena">
					</div>
				 </div>
                 <div class="campos-perfil one">
           		   <div class="iconosperfil">
           		   		<i class="fas fa-user"></i>
           		   </div>
					<div class="Datos-perfil">
						<input type="password" name="password" placeholder="Confirmar Contrasena" id="ConfirmarContrasena"  >
           		   </div>
           		</div>
				<input type="submit" class="Botonperfil" value="Editar Perfil">
			</form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="/Perfil.js"></script>

</body>
</html>
