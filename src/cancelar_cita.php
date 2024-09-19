<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Cliente') {
    header("Location: index.php");
    exit;
}

// Verificar que se ha pasado un ID de cita válido
if (isset($_GET['id'])) {
    $cita_id = $_GET['id'];
    $cliente_id = $_SESSION['user_id'];

    // Verificar que la cita pertenece al cliente y está en estado "Pendiente"
    $cita = $conn->query("SELECT * FROM citas WHERE id = $cita_id AND cliente_id = $cliente_id AND estado = 'Pendiente'");

    if ($cita->num_rows > 0) {
        // Cancelar la cita actualizando su estado
        $query = "UPDATE citas SET estado = 'Cancelada' WHERE id = $cita_id";

        if ($conn->query($query) === TRUE) {
            echo "Cita cancelada con éxito.";
        } else {
            echo "Error al cancelar la cita: " . $conn->error;
        }
    } else {
        echo "Cita no encontrada o no puede ser cancelada.";
    }
} else {
    echo "ID de cita no especificado.";
}
?>

<a href="cliente.php">Volver al Panel de Cliente</a>
