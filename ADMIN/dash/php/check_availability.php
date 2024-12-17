<?php
header('Content-Type: application/json');
include 'conexion_be.php';

$data = json_decode(file_get_contents('php://input'), true);
$date = $data['date'];

$sql = "SELECT COUNT(*) AS count FROM reservations WHERE start = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = ['available' => $row['count'] == 0];
echo json_encode($response);
?>
