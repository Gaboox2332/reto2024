<?php
// Conecta a la base de datos
$conn = mysqli_connect("localhost", "root", "", "dates");

// Verifica la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtiene los datos de la base de datos
$sql = "SELECT * FROM fechas";
$result = mysqli_query($conn, $sql);

// Crea un array para almacenar los datos
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        'label' => $row['fecha'],
        'value' => $row['id']
    );
}

// Cierra la conexión
mysqli_close($conn);

// Codifica los datos en JSON
$json_data = json_encode($data);

// Imprime los datos en JSON
echo $json_data;
