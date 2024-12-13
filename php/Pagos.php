<?php
// Configuración de la conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cars_citadel";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Error en la conexión a la base de datos: " . $conn->connect_error]));
}

$response = ["success" => false, "error" => ""];

// Verifica si el usuario está logueado
session_start();
if (!isset($_SESSION['username'])) {
    die(json_encode(["success" => false, "error" => "Usuario no autenticado."]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['Id_Reserva']; // Este valor debe ser dinámico
    $id_usuario = $_POST['Id_Usuario']; // Establecer según el usuario logueado
    $metodo = $_POST['Metodo'];
    $numero_cuenta = $_POST['Numero_cuenta'];
    $monto = $_POST['Monto'];

    $sql = "INSERT INTO pagos (Id_Reserva, Id_Usuario, Metodo, Numero_cuenta, Monto)
            VALUES ('$id_reserva', '$id_usuario', '$metodo', '$numero_cuenta', '$monto')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Pago registrado con éxito']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar el pago']);
    }
}
?>