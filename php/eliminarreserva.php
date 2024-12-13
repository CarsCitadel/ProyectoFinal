<?php

session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cars_citadel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idReserva = $_GET['id'];

    // Verificar si la reserva pertenece al usuario logueado
    $userId = $_SESSION['id']; // Asegúrate de que la sesión contenga el ID del usuario
    $query = "SELECT Usuario_id FROM reservas WHERE Id_Reserva = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idReserva);
    $stmt->execute();
    $stmt->bind_result($idUsuarioReserva);
    $stmt->fetch();
    $stmt->close();

    if ($idUsuarioReserva == $userId) {
        // Eliminar la reserva
        $query = "DELETE FROM reservas WHERE Id_Reserva = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idReserva);
        if ($stmt->execute()) {
            echo "<script>alert('Reserva eliminada correctamente.');</script>";
            header("Location: historial_reservas.php"); // Redirigir al historial de reservas
            exit;
        } else {
            echo "<script>alert('Error al eliminar la reserva.');</script>";
        }
    } else {
        echo "<script>alert('No puedes eliminar una reserva que no te pertenece.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>