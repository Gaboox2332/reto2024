<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'reservations_db');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener parámetros de la solicitud
$date = $_POST['date'] ?? '';
$room = $_POST['room'] ?? '';

// Verificar si se recibieron los parámetros
if (empty($date) || empty($room)) {
    echo json_encode([]);
    exit;
}

// Consultar horarios reservados
$query = "
    SELECT DATE_FORMAT(datetime, '%H:%i') as reserved_time
    FROM reservations
    WHERE DATE(datetime) = ? AND room = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $date, $room);
$stmt->execute();
$result = $stmt->get_result();
$reservedTimes = [];

while ($row = $result->fetch_assoc()) {
    $reservedTimes[] = $row['reserved_time'];
}

$stmt->close();

// Consultar todos los horarios disponibles desde la base de datos
$query = "SELECT time FROM available_times";
$result = $conn->query($query);
$allTimes = [];

while ($row = $result->fetch_assoc()) {
    $allTimes[] = $row['time'];
}

$conn->close();

// Filtrar horarios reservados
$availableTimes = array_diff($allTimes, $reservedTimes);

echo json_encode(array_values($availableTimes));
?>
