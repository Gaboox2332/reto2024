<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'reservations_db');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM reservations";
$result = $conn->query($sql);

$events = array();

while ($row = $result->fetch_assoc()) {
    $events[] = array(
        'title' => $row['room'] . ' - ' . $row['name'],
        'start' => $row['datetime'],
        'allDay' => false
    );
}

$conn->close();

echo json_encode($events);
?>
