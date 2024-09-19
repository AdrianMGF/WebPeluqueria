<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Cliente') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servicio_id = $_POST['servicio_id'];
    $cliente_id = $_SESSION['user_id'];
    $horario_id = $_POST['horario_id']; // Recoger el ID del horario seleccionado

    // Obtener información del horario seleccionado, incluyendo el peluquero asociado
    $horario_info = $conn->query("SELECT * FROM horarios WHERE id = $horario_id");

    if ($horario_info->num_rows > 0) {
        $horario = $horario_info->fetch_assoc();
        $peluquero_id = $horario['peluquero_id'];

        // Obtener la fecha del próximo día correspondiente al día de la semana seleccionado
        $dias_semana = ['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6];
        $dia_semana_actual = date('N'); // Obtiene el día de la semana actual (1=Lunes, 7=Domingo)
        $dia_horario = $dias_semana[$horario['dia_semana']];

        $dias_diferencia = $dia_horario - $dia_semana_actual;
        if ($dias_diferencia < 0) {
            $dias_diferencia += 7; // Ajustar si el día es en la próxima semana
        }

        $fecha = date('Y-m-d', strtotime("+$dias_diferencia days"));
        $fecha_hora = $fecha . ' ' . $horario['hora_inicio']; // Concatenar la fecha con la hora de inicio

        // Insertar la cita con la información obtenida
        $query = "INSERT INTO citas (cliente_id, peluquero_id, servicio_id, fecha_hora, estado) 
                  VALUES ($cliente_id, $peluquero_id, $servicio_id, '$fecha_hora', 'Pendiente')";

        if ($conn->query($query) === TRUE) {
            echo "Cita reservada con éxito.";
        } else {
            echo "Error al reservar la cita: " . $conn->error;
        }
    } else {
        echo "El horario seleccionado no está disponible.";
    }
}
?>

<a href="cliente.php">Volver al Panel de Cliente</a>
