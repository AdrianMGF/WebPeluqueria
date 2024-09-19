<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $peluquero_id = $_SESSION['user_id'];
    $dia_semana = $_POST['dia_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    $query = "INSERT INTO horarios (peluquero_id, dia_semana, hora_inicio, hora_fin) VALUES ($peluquero_id, '$dia_semana', '$hora_inicio', '$hora_fin')";

    if ($conn->query($query) === TRUE) {
        header("Location: peluquero.php");
    } else {
        echo "Error al añadir el horario: " . $conn->error;
    }
}
?>
