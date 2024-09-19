<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Peluquero') {
    header("Location: index.php");
    exit;
}

// Obtener las citas del peluquero
$peluquero_id = $_SESSION['user_id'];
$citas = $conn->query("SELECT c.*, u.nombre as cliente, s.nombre as servicio FROM citas c JOIN usuarios u ON c.cliente_id = u.id JOIN servicios s ON c.servicio_id = s.id WHERE peluquero_id = $peluquero_id");

// Obtener los horarios del peluquero
$horarios = $conn->query("SELECT * FROM horarios WHERE peluquero_id = $peluquero_id");

// Obtener los servicios para el formulario de eliminación
$servicios = $conn->query("SELECT * FROM servicios");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Peluquero</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['user_name']; ?></h2>
    
    <h3>Mis Citas</h3>
    <table border="1">
        <tr>
            <th>Cliente</th>
            <th>Servicio</th>
            <th>Fecha y Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($cita = $citas->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $cita['cliente']; ?></td>
            <td><?php echo $cita['servicio']; ?></td>
            <td><?php echo $cita['fecha_hora']; ?></td>
            <td><?php echo $cita['estado']; ?></td>
            <td>
            <?php
            switch ($cita['estado']) {
                case 'Pendiente':
                    echo "<a href='actualizar_estado.php?id=" . $cita['id'] . "&estado=Confirmada'>Confirmar</a> | ";
                    echo "<a href='actualizar_estado.php?id=" . $cita['id'] . "&estado=Cancelada'>Cancelar</a>";
                    break;
                case 'Confirmada':
                    echo "<a href='actualizar_estado.php?id=" . $cita['id'] . "&estado=Completada'>Completar</a> | ";
                    echo "<a href='actualizar_estado.php?id=" . $cita['id'] . "&estado=Cancelada'>Cancelar</a>";
                    break;
                case 'Completada':
                case 'Cancelada':
                    echo "No disponible";
                    break;
            }
        ?>
    </td>
        </tr>
        <?php } ?>
    </table>

    <h3>Mis Horarios</h3>
    <table border="1">
        <tr>
            <th>Día de la Semana</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Acciones</th>
        </tr>
        <?php while ($horario = $horarios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $horario['dia_semana']; ?></td>
            <td><?php echo $horario['hora_inicio']; ?></td>
            <td><?php echo $horario['hora_fin']; ?></td>
            <td>
                <a href="eliminar_horario.php?id=<?php echo $horario['id']; ?>">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>Añadir Nuevo Horario</h3>
    <form method="post" action="agregar_horario.php">
        <label>Día de la Semana:</label>
        <select name="dia_semana">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miércoles">Miércoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
        </select><br>
        <label>Hora Inicio:</label>
        <input type="time" name="hora_inicio" required><br>
        <label>Hora Fin:</label>
        <input type="time" name="hora_fin" required><br>
        <button type="submit">Añadir Horario</button>
    </form>

    <h3>Añadir Nuevo Servicio</h3>
    <form method="post" action="agregar_servicios.php">
        <label>Nombre del Servicio:</label>
        <input type="text" name="nombre_servicio" required><br>
        <label>Duración (minutos):</label>
        <input type="number" name="duracion" required><br>
        <label>Precio:</label>
        <input type="number" name="precio" required><br>
        <button type="submit" name="add_servicio">Añadir Servicio</button>
    </form>

    <h3>Eliminar Servicio</h3>
    <form method="get" action="eliminar_servicios.php">
        <label>Seleccionar Servicio:</label>
        <select name="servicio_id">
            <?php while ($servicio = $servicios->fetch_assoc()) { ?>
                <option value="<?php echo $servicio['id']; ?>"><?php echo $servicio['nombre']; ?> - $<?php echo $servicio['precio']; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Eliminar Servicio</button>
    </form>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
