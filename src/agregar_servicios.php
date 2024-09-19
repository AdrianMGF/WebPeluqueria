<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dia_semana = $_POST['nombre_servicio'];
    $hora_inicio = $_POST['duracion'];
    $hora_fin = $_POST['precio'];

    $query = "INSERT INTO servicios (nombre,duracion,precio) VALUES ('$dia_semana', '$hora_inicio', '$hora_fin')";

    if ($conn->query($query) === TRUE) {
        header("Location: peluquero.php");
    } else {
        echo "Error al añadir el servicio: " . $conn->error;
    }
}
?>
