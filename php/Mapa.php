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

// Obtener el nombre del usuario desde la sesión
$usuario = $_SESSION['username'];

// Consulta para obtener el id del usuario
$userQuery = "SELECT Id FROM usuarios WHERE Usuario = ?";
$stmtUser = $conn->prepare($userQuery);
$stmtUser->bind_param("s", $usuario);
$stmtUser->execute();
$stmtUser->bind_result($userId);
$stmtUser->fetch();
$stmtUser->close();

if (!$userId) {
    die(json_encode(["success" => false, "error" => "Usuario no encontrado."]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['Nombre'] ?? '';
    $placa = $_POST['Placa'] ?? '';
    $id_espacio = $_POST['Id_Espacio'] ?? '';
    $vehiculo = $_POST['Vehiculo'] ?? '';
    $modelo = $_POST['Modelo'] ?? '';
    $hora_entrada = $_POST['Hora_Entrada'] ?? '';
    $hora_salida = $_POST['Hora_Salida'] ?? '';
    $precio = $_POST['Precio'] ?? 0;

    // Datos de pago
    $metodo = $_POST['Metodo'] ?? '';
    $numero_cuenta = $_POST['Numero_cuenta'] ?? '';
    $monto = $_POST['Monto'] ?? $precio;

    // Verificar solapamiento de horarios
    $checkSql = "SELECT 1 FROM reservas 
                 WHERE Id_Espacio = ? 
                 AND NOT (Hora_Salida <= ? OR Hora_Entrada >= ?)";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("sss", $id_espacio, $hora_entrada, $hora_salida);
    if (!$stmt->execute()) {
        die(json_encode(["success" => false, "error" => "Error al verificar disponibilidad: " . $conn->error]));
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["error"] = "El espacio ya está reservado en el horario seleccionado.";
    } else {
        // Insertar nueva reserva
        $insertSql = "INSERT INTO reservas (Usuario_id, Nombre, Placa, Id_Espacio, Vehiculo, Modelo, Hora_Entrada, Hora_Salida, Precio) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertSql);
        $stmtInsert->bind_param("issssssss", $userId, $nombre, $placa, $id_espacio, $vehiculo, $modelo, $hora_entrada, $hora_salida, $precio);

        if (!$stmtInsert->execute()) {
            die(json_encode(["success" => false, "error" => "Error al registrar la reserva: " . $conn->error]));
        }

        // Obtener el ID de la reserva recién creada
        $idReserva = $stmtInsert->insert_id;

        // Insertar pago correspondiente
        $insertPagoSql = "INSERT INTO pagos (Id_Reserva, Id_Usuario, Metodo, Numero_cuenta, Monto) 
                          VALUES (?, ?, ?, ?, ?)";
        $stmtPago = $conn->prepare($insertPagoSql);
        $stmtPago->bind_param("iisss", $idReserva, $userId, $metodo, $numero_cuenta, $monto);

        if (!$stmtPago->execute()) {
            die(json_encode(["success" => false, "error" => "Error al registrar el pago: " . $conn->error]));
        }


        $response = [
            "success" => true,
            "message" => "Reserva y pago realizados con éxito.",
            "Id_Reserva" => $idReserva,
            "Nombre" => $nombre,
            "Placa" => $placa,
            "Id_Espacio" => $id_espacio,
            "Vehiculo" => $vehiculo,
            "Modelo" => $modelo,
            "Hora_Entrada" => $hora_entrada,
            "Hora_Salida" => $hora_salida,
            "Precio" => $precio,
            "Metodo_Pago" => $metodo,
            "Numero_Cuenta" => $numero_cuenta,
            "Monto" => $monto
        ];
    }
}

// Responder siempre con JSON
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
exit();
?>


