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

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el nombre de usuario desde la sesión
$usuario = $_SESSION['username'];

// Procesar eliminación de reserva si se envía el parámetro `id`
if (isset($_GET['id'])) {
    $id_reserva = intval($_GET['id']);

    // Consulta para eliminar la reserva
    $deleteQuery = "DELETE FROM reservas WHERE Id_Reserva = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id_reserva);

    if ($stmt->execute()) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Reserva eliminada',
                    text: 'Reserva eliminada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = '/php/Historial.php'; // Redirige tras aceptar
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar la reserva.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>";
    }

    $stmt->close();
}

// Consulta para obtener el historial de reservas del usuario
$query = "SELECT r.Id_Reserva, r.Nombre, r.Placa, r.Vehiculo, r.Modelo, r.Hora_Entrada, r.Hora_Salida, r.Precio 
          FROM reservas r
          INNER JOIN usuarios u ON r.Usuario_id = u.id
          WHERE u.Usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

// Si no hay resultados, muestra un mensaje
$noReservations = ($result->num_rows === 0);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
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
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
<!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" href="/icon/Logo Parqueadero.png">
    <title>Historial</title>
</head>
<body class="bodyhistorial">
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
                        <li><a href="/php/perfil.php">Perfil</a></li>
                        <li><a href="#">Historial</a></li>
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



    <div class="historial-cont">
        <h2 class="historialtitle">Historial de Reservas</h2>
            <?php if ($noReservations): ?>
            <div class="alert">No tienes reservas registradas.</div>
            <?php else: ?>
            <div class="table-container">
                <table class="tablahistorial">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Placa</th>
                            <th>Vehículo</th>
                            <th>Modelo</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Nombre']) ?></td>
                                <td><?= htmlspecialchars($row['Placa']) ?></td>
                                <td><?= htmlspecialchars($row['Vehiculo']) ?></td>
                                <td><?= htmlspecialchars($row['Modelo']) ?></td>
                                <td><?= htmlspecialchars($row['Hora_Entrada']) ?></td>
                                <td><?= htmlspecialchars($row['Hora_Salida']) ?></td>
                                <td><?= htmlspecialchars($row['Precio']) ?></td>
                                <td>
                                    <a href="?id=<?= $row['Id_Reserva'] ?>" class="action-btn" onclick="confirmDelete(event, <?= $row['Id_Reserva'] ?>)">Eliminar</a>
                                    <script>
                                        function confirmDelete(event, id) {
                                            // Prevenir la acción predeterminada del enlace
                                            event.preventDefault();

                                            Swal.fire({
                                                title: '¿Estás seguro?',
                                                text: "¡Esta acción no se puede deshacer!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Sí, eliminar',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Si se confirma, redirige al enlace de eliminación
                                                    window.location.href = `?id=${id}`;
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
                integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
                crossorigin="anonymous"></script>
            <script src="/Script.js"></script>  
            <script src="/Perfil.js"></script>
</body>
</html>

<?php
$conn->close();
?>
