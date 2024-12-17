<?php
header('Content-Type: application/json');

require 'conexion_be2.php'; // Asegúrate de tener el archivo de conexión

$query = "SELECT * FROM reservations";
$result = mysqli_query($conexion2, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['name'],
        'start' => $row['datetime'],
        'end' => date('Y-m-d H:i:s', strtotime($row['datetime'] . ' +1 hour')), // Ajusta si necesitas una duración diferente
        'description' => $row['room'] // Puedes agregar más campos según sea necesario
    ];
}

echo json_encode($events);
?>
