<?php
// Activar reporte de errores
date_default_timezone_set('America/Bogota'); // Cambia a la zona horaria deseada

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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si la sesión pertenece al operador (Id = 22)
if (!isset($_SESSION['Id']) || $_SESSION['Id'] != 22) {
    echo "Acceso denegado.";
    exit;
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id_reserva = $_GET['eliminar'];
    $sql = "DELETE FROM reservas WHERE Id_Reserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();
    header("Location: admin.php");
    exit;
}

// Procesar nueva reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear'])) {
    $nombre = $_POST['nombre'];
    $placa = $_POST['placa'];
    $id_espacio = $_POST['id_espacio'];
    $vehiculo = $_POST['vehiculo'];
    $modelo = $_POST['modelo'];
    $hora_entrada = date("Y-m-d H:i:s"); // Hora actual

    // Verificar si el espacio ya está ocupado
    $sql_check = "SELECT * FROM reservas WHERE Id_Espacio = ? AND Hora_Salida IS NULL"; // Verificar que no tenga hora de salida registrada

    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_espacio);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si el espacio está ocupado, mostrar mensaje de error
        echo "<div class='alert alert-danger'>El espacio ya está ocupado, por favor seleccione otro.</div>";
    } else {
        // Si el espacio está libre, proceder con la creación de la reserva
        $sql = "INSERT INTO reservas (Usuario_Id, nombre, placa, Id_Espacio, vehiculo, modelo, Hora_Entrada) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $_SESSION['Id'], $nombre, $placa, $id_espacio, $vehiculo, $modelo, $hora_entrada);
        $stmt->execute();
        header("Location: admin.php");
        exit;
    }
}

// Procesar salida
if (isset($_GET['registrar_salida'])) {
    $id_reserva = $_GET['registrar_salida'];
    $hora_salida = date("Y-m-d H:i:s"); // Hora actual
    $sql = "SELECT Hora_Entrada, vehiculo FROM reservas WHERE Id_Reserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_reserva);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();

    if ($reserva) {
        $hora_entrada = strtotime($reserva['Hora_Entrada']);
        $hora_salida_ts = strtotime($hora_salida);
        $duracion_horas = ceil(($hora_salida_ts - $hora_entrada) / 3600); // Calcular duración en horas

        // Precio por hora según el vehículo
        $precio_por_hora = ($reserva['vehiculo'] === "moto") ? 1500 : 3600;
        $precio_total = $duracion_horas * $precio_por_hora;

        // Actualizar reserva con hora de salida y precio
        $sql_update = "UPDATE reservas SET Hora_Salida = ?, precio = ? WHERE Id_Reserva = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sdi", $hora_salida, $precio_total, $id_reserva);
        $stmt_update->execute();
    }

    header("Location: admin.php");
    exit;
}

// Consultar todas las reservas
$sql = "SELECT * FROM reservas";
$result = $conn->query($sql);

// Procesar edición de reserva
if (isset($_GET['editar'])) {
    $id_reserva = $_GET['editar'];
    $sql_edit = "SELECT * FROM reservas WHERE Id_Reserva = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id_reserva);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();

    if ($result_edit->num_rows > 0) {
        $reserva = $result_edit->fetch_assoc();
    } else {
        echo "Reserva no encontrada.";
        exit;
    }
}

// Procesar actualización de la reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_reserva'])) {
    $id_reserva = $_POST['id_reserva'];
    $nombre = $_POST['nombre'];
    $placa = $_POST['placa'];
    $id_espacio = $_POST['id_espacio'];
    $vehiculo = $_POST['vehiculo'];
    $modelo = $_POST['modelo'];
    $hora_salida = $_POST['hora_salida'];

    // Calcular precio automáticamente
    $hora_entrada = strtotime($reserva['Hora_Entrada']);
    $hora_salida_ts = strtotime($hora_salida);
    $duracion_horas = ceil(($hora_salida_ts - $hora_entrada) / 3600); // Calcular duración en horas

    // Precio por hora según el vehículo
    $precio_por_hora = ($vehiculo === "moto") ? 1500 : 3600;
    $precio_total = $duracion_horas * $precio_por_hora;

    // Actualizar la reserva con los nuevos datos
    $sql_update = "UPDATE reservas SET nombre = ?, placa = ?, Id_Espacio = ?, vehiculo = ?, modelo = ?, Hora_Salida = ?, precio = ? WHERE Id_Reserva = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssdi", $nombre, $placa, $id_espacio, $vehiculo, $modelo, $hora_salida, $precio_total, $id_reserva);
    $stmt_update->execute();
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Gestión de Reservas</h1>

        <!-- Formulario para crear nueva reserva -->
        <form method="POST" class="mb-4">
            <h4>Crear Nueva Reserva</h4>
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="placa" class="form-control" placeholder="Placa" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="id_espacio" class="form-control" placeholder="ID Espacio" required>
                </div>
                <div class="col-md-2">
                    <select name="vehiculo" class="form-control" required>
                        <option value="moto">Moto</option>
                        <option value="carro">Carro</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="modelo" class="form-control" placeholder="Modelo" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="crear" class="btn btn-primary">Crear</button>
                </div>
            </div>
        </form>

        <!-- Tabla de registros -->
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                <div class="text-end mb-4">
                    <a href="/php/logout.php" class="btn btn-secondary">Cerrar Sesión</a>
                </div>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Usuario ID</th>
                        <th>Nombre</th>
                        <th>Placa</th>
                        <th>ID Espacio</th>
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
                            <td><?php echo htmlspecialchars($row['Id_Reserva']); ?></td>
                            <td><?php echo htmlspecialchars($row['Usuario_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['placa']); ?></td>
                            <td><?php echo htmlspecialchars($row['Id_Espacio']); ?></td>
                            <td><?php echo htmlspecialchars($row['vehiculo']); ?></td>
                            <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($row['Hora_Entrada']); ?></td>
                            <td><?php echo htmlspecialchars($row['Hora_Salida']); ?></td>
                            <td>
                                <?php 
                                // Verificar si el precio está vacío o no se ha definido
                                if (empty($row['precio']) || $row['precio'] == 0) {
                                    echo "<span style='color: red;'>Por definir</span>";  // O el mensaje que prefieras
                                } else {
                                    echo htmlspecialchars($row['precio']);  // Mostrar el precio si está definido
                                }
                                ?>
                            </td>

                            <td>
                                <a href="?registrar_salida=<?php echo $row['Id_Reserva']; ?>" class="btn btn-success btn-sm">Registrar Salida</a>
                                <a href="?eliminar=<?php echo $row['Id_Reserva']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="?editar=<?php echo $row['Id_Reserva']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <?php if (!empty($row['Hora_Salida'])): ?>
                                    <button type="button" 
                                            onclick="generarFactura(this);" 
                                            class="btn btn-info btn-sm"
                                            data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>"
                                            data-placa="<?php echo htmlspecialchars($row['placa']); ?>"
                                            data-vehiculo="<?php echo htmlspecialchars($row['vehiculo']); ?>"
                                            data-id_espacio="<?php echo htmlspecialchars($row['Id_Espacio']); ?>"
                                            data-hora_entrada="<?php echo htmlspecialchars($row['Hora_Entrada']); ?>"
                                            data-hora_salida="<?php echo htmlspecialchars($row['Hora_Salida']); ?>"
                                            data-precio="<?php echo htmlspecialchars($row['precio']); ?>"
                                    >Generar Factura</button>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-warning">No hay registros de reservas disponibles.</p>
        <?php endif; ?>

        <!-- Formulario de edición si se seleccionó una reserva -->
        <?php if (isset($reserva)): ?>
            <form method="POST" class="mb-4">
                <h4>Editar Reserva</h4>
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($reserva['nombre']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="placa" class="form-control" value="<?php echo htmlspecialchars($reserva['placa']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="id_espacio" class="form-control" value="<?php echo htmlspecialchars($reserva['Id_Espacio']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <select name="vehiculo" class="form-control" required>
                            <option value="moto" <?php echo $reserva['vehiculo'] === 'moto' ? 'selected' : ''; ?>>Moto</option>
                            <option value="carro" <?php echo $reserva['vehiculo'] === 'carro' ? 'selected' : ''; ?>>Carro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($reserva['modelo']); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="datetime-local" name="hora_salida" class="form-control" style="width: 100%;" value="<?php echo htmlspecialchars($reserva['Hora_Salida']); ?>" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <input type="hidden" name="id_reserva" value="<?php echo $reserva['Id_Reserva']; ?>">
                        <button type="submit" name="editar_reserva" class="btn btn-primary">Guardar Cambios</button>
                    </div>

                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <script>
        function generarFactura(button) {
    // Obtener los datos de la reserva desde los atributos data-* del botón
    const reserva = {
        Nombre: button.getAttribute('data-nombre'),
        Placa: button.getAttribute('data-placa'),
        Vehiculo: button.getAttribute('data-vehiculo'),
        Id_Espacio: button.getAttribute('data-id_espacio'),
        Hora_Entrada: button.getAttribute('data-hora_entrada'),
        Hora_Salida: button.getAttribute('data-hora_salida'),
        Precio: button.getAttribute('data-precio')
    };

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    // Configuración del PDF
    pdf.setFontSize(16);
    pdf.setTextColor(0, 0, 0);
    pdf.text("Factura de Parqueadero", 105, 20, null, null, "center");

    // Logo de la empresa (si tienes un logo, puedes agregarlo como base64 o URL pública)
    const logoUrl = "/img/logo Parqueadero.png"; // Reemplaza por tu imagen
    pdf.addImage(logoUrl, "PNG", 10, 10, 30, 30);

    // Detalles de la reserva (estructura básica)
    pdf.setFontSize(12);
    pdf.setTextColor(50, 50, 50);
    pdf.text("Fecha de emisión: ", 10, 50);
    pdf.text(new Date().toLocaleDateString(), 60, 50);

    pdf.text("Nombre:", 10, 60);
    pdf.text(reserva.Nombre, 50, 60);
    pdf.text("Placa:", 10, 70);
    pdf.text(reserva.Placa, 50, 70);
    pdf.text("Vehículo:", 10, 80);
    pdf.text(reserva.Vehiculo, 50, 80);
    pdf.text("Espacio:", 10, 90);
    pdf.text(reserva.Id_Espacio, 50, 90);
    pdf.text("Hora Entrada:", 10, 100);
    pdf.text(reserva.Hora_Entrada, 50, 100);
    pdf.text("Hora Salida:", 10, 110);
    pdf.text(reserva.Hora_Salida, 50, 110);

    // Línea separadora
    pdf.setDrawColor(100, 100, 100);
    pdf.line(10, 120, 200, 120);

    // Resumen de los costos
    pdf.setFontSize(12);
    pdf.text("Resumen de Cargos", 10, 130);
    pdf.setFontSize(10);
    pdf.text("Precio Total: $", 10, 140);
    pdf.text(`${reserva.Precio}`, 50, 140);

    // Pie de página con mensaje de agradecimiento
    pdf.setFontSize(10);
    pdf.setTextColor(100, 100, 100);
    pdf.text("Gracias por utilizar nuestro servicio de parqueadero.", 105, 170, null, null, "center");
    pdf.text("Para más información, contáctanos en soporte@carcitadel.com.", 105, 180, null, null, "center");

    // Descargar el PDF
    pdf.save("factura_parqueadero.pdf");
}
    </script>

</body>
</html>
