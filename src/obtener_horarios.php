<?php
include 'config.php';

$peluquero_id = $_GET['peluquero_id'];
$horarios = $conn->query("SELECT id, dia_semana, hora_inicio, hora_fin FROM horarios WHERE peluquero_id = $peluquero_id");

$result = [];
while ($horario = $horarios->fetch_assoc()) {
    $result[] = $horario;
}

echo json_encode($result);
?>
