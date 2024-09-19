<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Cliente') {
    header("Location: index.php");
    exit;
}

// Obtener los servicios
$servicios = $conn->query("SELECT * FROM servicios");

// Obtener las citas del cliente
$cliente_id = $_SESSION['user_id'];
$citas = $conn->query("SELECT c.*, s.nombre as servicio FROM citas c JOIN servicios s ON c.servicio_id = s.id WHERE cliente_id = $cliente_id");

// Obtener todos los horarios disponibles
$horarios = $conn->query("
    SELECT h.*, u.nombre as peluquero_nombre 
    FROM horarios h 
    JOIN usuarios u ON h.peluquero_id = u.id 
    ORDER BY h.dia_semana, h.hora_inicio
");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['user_name']; ?></h2>

    <h3>Reservar Cita</h3>
    <form method="post" action="reservar_cita.php">
        <label>Servicio:</label>
        <select name="servicio_id" required>
            <?php while ($servicio = $servicios->fetch_assoc()) { ?>
                <option value="<?php echo $servicio['id']; ?>"><?php echo $servicio['nombre']; ?> - $<?php echo $servicio['precio']; ?></option>
            <?php } ?>
        </select><br>

        <label>Horario:</label>
        <select name="horario_id" required>
            <?php while ($horario = $horarios->fetch_assoc()) { ?>
                <option value="<?php echo $horario['id']; ?>">
                    <?php echo $horario['dia_semana'] . ' ' . $horario['hora_inicio'] . ' - ' . $horario['hora_fin']; ?>
                    (<?php echo $horario['peluquero_nombre']; ?>)
                </option>
            <?php } ?>
        </select><br>

        <button type="submit">Reservar</button>
    </form>

    <h3>Mis Citas</h3>
    <table border="1">
        <tr>
            <th>Servicio</th>
            <th>Fecha y Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($cita = $citas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $cita['servicio']; ?></td>
            <td><?php echo $cita['fecha_hora']; ?></td>
            <td><?php echo $cita['estado']; ?></td>
            <td>
                <?php if ($cita['estado'] == 'Pendiente') { ?>
                <a href="cancelar_cita.php?id=<?php echo $cita['id']; ?>">Cancelar</a>
                <?php } else { echo "No disponible"; } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
