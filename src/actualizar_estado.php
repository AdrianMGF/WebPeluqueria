<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

// Verificar que se ha pasado un ID de cita válido y un estado
if (isset($_GET['id']) && isset($_GET['estado'])) {
    $cita_id = $_GET['id'];
    $nuevo_estado = $_GET['estado'];
    $peluquero_id = $_SESSION['user_id'];

    // Validar el nuevo estado (solo permitir ciertos estados)
    $estados_validos = ['Confirmada', 'Completada', 'Cancelada'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        echo "Estado no válido.";
        exit;
    }

    // Verificar que la cita pertenece al peluquero y que no esté ya completada o cancelada
    $cita = $conn->query("SELECT * FROM citas WHERE id = $cita_id AND peluquero_id = $peluquero_id AND estado IN ('Pendiente', 'Confirmada')");

    if ($cita->num_rows > 0) {
        if ($nuevo_estado == 'Completada') {
            // Eliminar la cita si el nuevo estado es 'Completada'
            $query = "DELETE FROM citas WHERE id = $cita_id";

            if ($conn->query($query) === TRUE) {
                echo "Cita completada y eliminada correctamente.";
            } else {
                echo "Error al eliminar la cita: " . $conn->error;
            }
        } else {
            // Actualizar el estado de la cita
            $query = "UPDATE citas SET estado = '$nuevo_estado' WHERE id = $cita_id";

            if ($conn->query($query) === TRUE) {
                echo "Estado de la cita actualizado a '$nuevo_estado'.";
            } else {
                echo "Error al actualizar el estado de la cita: " . $conn->error;
            }
        }
    } else {
        echo "Cita no encontrada o ya ha sido gestionada.";
    }
} else {
    echo "ID de cita o estado no especificado.";
}
?>

<a href="peluquero.php">Volver al Panel de Peluquero</a>
