<?php
include 'config.php';

if ($_SESSION['user_role'] != 'Administrador') {
    header("Location: index.php");
    exit;
}

// Procesar acciones CRUD para usuarios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create_user') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $rol = $_POST['rol'];

        $query = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password', '$rol')";

        if ($conn->query($query) === TRUE) {
            echo "Usuario creado exitosamente.";
        } else {
            echo "Error al crear usuario: " . $conn->error;
        }
    } elseif ($_POST['action'] == 'update_user') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];

        $query = "UPDATE usuarios SET nombre = '$nombre', email = '$email', rol = '$rol' WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Usuario actualizado exitosamente.";
        } else {
            echo "Error al actualizar usuario: " . $conn->error;
        }
    } elseif ($_POST['action'] == 'delete_user') {
        $id = $_POST['id'];

        $query = "DELETE FROM usuarios WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Usuario eliminado exitosamente.";
        } else {
            echo "Error al eliminar usuario: " . $conn->error;
        }
    }
}

// Procesar acciones CRUD para servicios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_servicio'])) {
    if ($_POST['action_servicio'] == 'create') {
        $nombre = $_POST['nombre'];
        $duracion = $_POST['duracion'];
        $precio = $_POST['precio'];

        $query = "INSERT INTO servicios (nombre, duracion, precio) VALUES ('$nombre', $duracion, $precio)";

        if ($conn->query($query) === TRUE) {
            echo "Servicio creado exitosamente.";
        } else {
            echo "Error al crear servicio: " . $conn->error;
        }
    } elseif ($_POST['action_servicio'] == 'update') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $duracion = $_POST['duracion'];
        $precio = $_POST['precio'];

        $query = "UPDATE servicios SET nombre = '$nombre', duracion = $duracion, precio = $precio WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Servicio actualizado exitosamente.";
        } else {
            echo "Error al actualizar servicio: " . $conn->error;
        }
    } elseif ($_POST['action_servicio'] == 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM servicios WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Servicio eliminado exitosamente.";
        } else {
            echo "Error al eliminar servicio: " . $conn->error;
        }
    }
}

// Procesar acciones CRUD para horarios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_horario'])) {
    if ($_POST['action_horario'] == 'create') {
        $peluquero_id = $_POST['peluquero_id'];
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];

        $query = "INSERT INTO horarios (peluquero_id, dia_semana, hora_inicio, hora_fin) VALUES ($peluquero_id, '$dia_semana', '$hora_inicio', '$hora_fin')";

        if ($conn->query($query) === TRUE) {
            echo "Horario creado exitosamente.";
        } else {
            echo "Error al crear horario: " . $conn->error;
        }
    } elseif ($_POST['action_horario'] == 'update') {
        $id = $_POST['id'];
        $peluquero_id = $_POST['peluquero_id'];
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];

        $query = "UPDATE horarios SET peluquero_id = $peluquero_id, dia_semana = '$dia_semana', hora_inicio = '$hora_inicio', hora_fin = '$hora_fin' WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Horario actualizado exitosamente.";
        } else {
            echo "Error al actualizar horario: " . $conn->error;
        }
    } elseif ($_POST['action_horario'] == 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM horarios WHERE id = $id";

        if ($conn->query($query) === TRUE) {
            echo "Horario eliminado exitosamente.";
        } else {
            echo "Error al eliminar horario: " . $conn->error;
        }
    }
}

// Obtener datos para mostrar
$usuarios = $conn->query("SELECT * FROM usuarios");
$servicios = $conn->query("SELECT * FROM servicios");
$horarios = $conn->query("SELECT h.*, u.nombre as peluquero FROM horarios h JOIN usuarios u ON h.peluquero_id = u.id");
$peluqueros = $conn->query("SELECT id, nombre FROM usuarios WHERE rol = 'Peluquero'");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['user_name']; ?></h2>

    <!-- Gestión de Usuarios -->
    <h3>Gestión de Usuarios</h3>
    <form method="post">
        <h4>Agregar Usuario</h4>
        <label>Nombre:</label><input type="text" name="nombre" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Contraseña:</label><input type="password" name="password" required><br>
        <label>Rol:</label>
        <select name="rol">
            <option value="Administrador">Administrador</option>
            <option value="Peluquero">Peluquero</option>
            <option value="Cliente">Cliente</option>
        </select><br>
        <input type="hidden" name="action" value="create_user">
        <button type="submit">Crear Usuario</button>
    </form>

    <h3>Lista de Usuarios</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $usuario['id']; ?></td>
            <td><?php echo $usuario['nombre']; ?></td>
            <td><?php echo $usuario['email']; ?></td>
            <td><?php echo $usuario['rol']; ?></td>
            <td>
                <!-- Editar Usuario -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                    <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
                    <select name="rol">
                        <option value="Administrador" <?php if ($usuario['rol'] == 'Administrador') echo 'selected'; ?>>Administrador</option>
                        <option value="Peluquero" <?php if ($usuario['rol'] == 'Peluquero') echo 'selected'; ?>>Peluquero</option>
                        <option value="Cliente" <?php if ($usuario['rol'] == 'Cliente') echo 'selected'; ?>>Cliente</option>
                    </select>
                    <input type="hidden" name="action" value="update_user">
                    <button type="submit">Actualizar</button>
                </form>
                <!-- Eliminar Usuario -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <input type="hidden" name="action" value="delete_user">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Gestión de Servicios -->
    <h3>Gestión de Servicios</h3>
    <form method="post">
        <h4>Agregar Servicio</h4>
        <label>Nombre:</label><input type="text" name="nombre" required><br>
        <label>Duración (min):</label><input type="number" name="duracion" required><br>
        <label>Precio:</label><input type="text" name="precio" required><br>
        <input type="hidden" name="action_servicio" value="create">
        <button type="submit">Crear Servicio</button>
    </form>

    <h3>Lista de Servicios</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Duración</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php while ($servicio = $servicios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $servicio['id']; ?></td>
            <td><?php echo $servicio['nombre']; ?></td>
            <td><?php echo $servicio['duracion']; ?></td>
            <td>$<?php echo $servicio['precio']; ?></td>
            <td>
                <!-- Editar Servicio -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $servicio['id']; ?>">
                    <input type="text" name="nombre" value="<?php echo $servicio['nombre']; ?>" required>
                    <input type="number" name="duracion" value="<?php echo $servicio['duracion']; ?>" required>
                    <input type="text" name="precio" value="<?php echo $servicio['precio']; ?>" required>
                    <input type="hidden" name="action_servicio" value="update">
                    <button type="submit">Actualizar</button>
                </form>
                <!-- Eliminar Servicio -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $servicio['id']; ?>">
                    <input type="hidden" name="action_servicio" value="delete">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Gestión de Horarios -->
    <h3>Gestión de Horarios</h3>
    <form method="post">
        <h4>Agregar Horario</h4>
        <label>Peluquero:</label>
        <select name="peluquero_id">
            <?php while ($peluquero = $peluqueros->fetch_assoc()) { ?>
                <option value="<?php echo $peluquero['id']; ?>"><?php echo $peluquero['nombre']; ?></option>
            <?php } ?>
        </select><br>
        <label>Día de la Semana:</label>
        <select name="dia_semana">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miércoles">Miércoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
        </select><br>
        <label>Hora Inicio:</label><input type="time" name="hora_inicio" required><br>
        <label>Hora Fin:</label><input type="time" name="hora_fin" required><br>
        <input type="hidden" name="action_horario" value="create">
        <button type="submit">Crear Horario</button>
    </form>

    <h3>Lista de Horarios</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Peluquero</th>
            <th>Día de la Semana</th>
            <th>Hora Inicio</th>
            <th>Hora Fin</th>
            <th>Acciones</th>
        </tr>
        <?php while ($horario = $horarios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $horario['id']; ?></td>
            <td><?php echo $horario['peluquero']; ?></td>
            <td><?php echo $horario['dia_semana']; ?></td>
            <td><?php echo $horario['hora_inicio']; ?></td>
            <td><?php echo $horario['hora_fin']; ?></td>
            <td>
                <!-- Editar Horario -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $horario['id']; ?>">
                    <select name="peluquero_id">
                        <?php while ($p = $peluqueros->fetch_assoc()) { ?>
                            <option value="<?php echo $p['id']; ?>" <?php if ($p['id'] == $horario['peluquero_id']) echo 'selected'; ?>><?php echo $p['nombre']; ?></option>
                        <?php } ?>
                    </select><br>
                    <select name="dia_semana">
                        <option value="Lunes" <?php if ($horario['dia_semana'] == 'Lunes') echo 'selected'; ?>>Lunes</option>
                        <option value="Martes" <?php if ($horario['dia_semana'] == 'Martes') echo 'selected'; ?>>Martes</option>
                        <option value="Miércoles" <?php if ($horario['dia_semana'] == 'Miércoles') echo 'selected'; ?>>Miércoles</option>
                        <option value="Jueves" <?php if ($horario['dia_semana'] == 'Jueves') echo 'selected'; ?>>Jueves</option>
                        <option value="Viernes" <?php if ($horario['dia_semana'] == 'Viernes') echo 'selected'; ?>>Viernes</option>
                        <option value="Sábado" <?php if ($horario['dia_semana'] == 'Sábado') echo 'selected'; ?>>Sábado</option>
                    </select><br>
                    <input type="time" name="hora_inicio" value="<?php echo $horario['hora_inicio']; ?>" required><br>
                    <input type="time" name="hora_fin" value="<?php echo $horario['hora_fin']; ?>" required><br>
                    <input type="hidden" name="action_horario" value="update">
                    <button type="submit">Actualizar</button>
                </form>
                <!-- Eliminar Horario -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $horario['id']; ?>">
                    <input type="hidden" name="action_horario" value="delete">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
