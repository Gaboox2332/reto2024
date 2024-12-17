<?php
require('fpdf.php');
include '../../php/conexion_be2.php';

// Obtener parámetros de búsqueda y límite
$search = isset($_GET['search']) ? mysqli_real_escape_string($conexion2, $_GET['search']) : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

// Crear la consulta para obtener datos filtrados
$query = "SELECT * FROM reservations WHERE name LIKE '%$search%' OR email LIKE '%$search%' LIMIT $limit";
$result = mysqli_query($conexion2, $query);
$reservas = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Image('P1w.png', 150, 8, 60);
$pdf->Cell(0, 10, 'Reporte de Reservas', 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nombre', 1);
$pdf->Cell(60, 10, 'Email', 1);
$pdf->Cell(50, 10, 'Sala', 1);
$pdf->Cell(35, 10, 'Fecha y Hora', 1);
$pdf->Ln();

// Datos de reservas
$pdf->SetFont('Arial', '', 12);
foreach ($reservas as $reserva) {
    $pdf->Cell(50, 10, htmlspecialchars($reserva['name']), 1);
    $pdf->Cell(60, 10, htmlspecialchars($reserva['email']), 1);
    $pdf->Cell(50, 10, htmlspecialchars($reserva['room']), 1);
    $pdf->Cell(35, 10, date('d/m/Y H:i', strtotime($reserva['datetime'])), 1);
    $pdf->Ln();
}

$pdf->Output();
?>
