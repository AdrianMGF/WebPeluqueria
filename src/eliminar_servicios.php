<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['servicio_id'])) {
    $servicio_id = $_GET['servicio_id'];
    
    // Eliminar horario
    $query = "DELETE FROM servicios WHERE id = $servicio_id";
    
    if ($conn->query($query) === TRUE) {
        header("Location: peluquero.php");
    } else {
        echo "Error al eliminar el servicio: " . $conn->error;
    }
}
header("Location: peluquero.php");
?>