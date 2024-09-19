<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $horario_id = $_GET['id'];
    
    // Eliminar horario
    $query = "DELETE FROM horarios WHERE id = $horario_id";
    
    if ($conn->query($query) === TRUE) {
        header("Location: peluquero.php");
    } else {
        echo "Error al eliminar el horario: " . $conn->error;
    }
}
?>
