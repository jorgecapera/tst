<?php
// gestion_correos.php
// Módulo de gestión de correos (sin base de datos)

session_start();
if (!isset($_SESSION['correos'])) {
    $_SESSION['correos'] = [
        [
            'id' => 1,
            'email' => 'correo1@ejemplo.com',
            'nombre' => 'Contacto Principal',
            'apellido' => 'Pérez',
            'fecha_creacion' => '2025-07-09'
        ],
        [
            'id' => 2,
            'email' => 'correo2@ejemplo.com',
            'nombre' => 'Soporte',
            'apellido' => 'García',
            'fecha_creacion' => '2025-07-09'
        ]
    ];
}
$correos = &$_SESSION['correos'];

$mensaje = '';
$editando = false;
$correo_editar = ['id' => '', 'email' => '', 'nombre' => '', 'apellido' => '', 'fecha_creacion' => ''];

// Eliminar correo
if (isset($_GET['eliminar'])) {
    $id_eliminar = (int)$_GET['eliminar'];
    foreach ($correos as $k => $correo) {
        if ($correo['id'] === $id_eliminar) {
            unset($correos[$k]);
            $mensaje = 'Correo eliminado correctamente.';
            break;
        }
    }
    $correos = array_values($correos);
}

// Preparar edición
if (isset($_GET['editar'])) {
    $id_editar = (int)$_GET['editar'];
    foreach ($correos as $correo) {
        if ($correo['id'] === $id_editar) {
            $correo_editar = $correo;
            $editando = true;
            break;
        }
    }
}

// Guardar edición
if (isset($_POST['guardar_edicion'])) {
    $id = (int)$_POST['id'];
    $email = trim($_POST['email'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    foreach ($correos as &$correo) {
        if ($correo['id'] === $id) {
            $correo['email'] = $email;
            $correo['nombre'] = $nombre;
            $correo['apellido'] = $apellido;
            $mensaje = 'Correo editado correctamente.';
            break;
        }
    }
    unset($correo);
    $editando = false;
}

// Agregar correo
if (!$editando && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['nombre'], $_POST['apellido']) && !isset($_POST['guardar_edicion'])) {
    $email = trim($_POST['email'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    if ($email !== '' && $nombre !== '' && $apellido !== '') {
        $nuevo_id = count($correos) > 0 ? max(array_column($correos, 'id')) + 1 : 1;
        $correos[] = [
            'id' => $nuevo_id,
            'email' => $email,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'fecha_creacion' => date('Y-m-d')
        ];
        $mensaje = 'Correo agregado correctamente.';
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Correos</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; margin: 40px;">
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; max-width: 750px; padding: 24px; box-shadow: 0 2px 8px #eee;">
        <h2 style="margin-top: 0; color: #333; font-size: 1.3em;">Gestión de Correos</h2>
        <?php if ($mensaje): ?>
            <div style="margin-bottom: 16px; color: #007700; font-weight: bold;"> <?php echo htmlspecialchars($mensaje); ?> </div>
        <?php endif; ?>
        <?php if ($editando): ?>
        <form method="post" style="margin-bottom: 24px;">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($correo_editar['id']); ?>">
            <label>Email:<br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($correo_editar['email']); ?>" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Nombre:<br>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($correo_editar['nombre']); ?>" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Apellido:<br>
                <input type="text" name="apellido" value="<?php echo htmlspecialchars($correo_editar['apellido']); ?>" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <button type="submit" name="guardar_edicion" style="padding: 8px 16px; background: #007acc; color: #fff; border: none; border-radius: 4px;">Guardar cambios</button>
            <a href="gestion_correos.php" style="margin-left: 12px; color: #007acc; text-decoration: underline;">Cancelar</a>
        </form>
        <?php else: ?>
        <form method="post" style="margin-bottom: 24px;">
            <label>Email:<br>
                <input type="email" name="email" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Nombre:<br>
                <input type="text" name="nombre" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Apellido:<br>
                <input type="text" name="apellido" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <button type="submit" style="padding: 8px 16px; background: #007acc; color: #fff; border: none; border-radius: 4px;">Agregar correo</button>
        </form>
        <?php endif; ?>
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">ID</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Email</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Nombre</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Apellido</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Fecha de creación</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Acciones</th>
            </tr>
            <?php foreach ($correos as $correo): ?>
            <tr>
                <td style="padding: 8px;"><?php echo htmlspecialchars($correo['id']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($correo['email']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($correo['nombre']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($correo['apellido']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($correo['fecha_creacion']); ?></td>
                <td style="padding: 8px;">
                    <a href="?editar=<?php echo $correo['id']; ?>" style="color: #007acc; text-decoration: underline; margin-right: 8px;">Editar</a>
                    <a href="?eliminar=<?php echo $correo['id']; ?>" style="color: #cc0000; text-decoration: underline;" onclick="return confirm('¿Seguro que deseas eliminar este correo?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
