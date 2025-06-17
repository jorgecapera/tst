<?php
// gestion_perfiles.php
// Módulo de gestión de perfiles (sin base de datos)

session_start();
if (!isset($_SESSION['perfiles'])) {
    $_SESSION['perfiles'] = [
        [
            'id' => 1,
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso total al sistema',
            'fecha_creacion' => '2025-06-17'
        ],
        [
            'id' => 2,
            'nombre' => 'Usuario',
            'descripcion' => 'Acceso limitado a funcionalidades',
            'fecha_creacion' => '2025-06-17'
        ]
    ];
}
$perfiles = &$_SESSION['perfiles'];

$mensaje = '';
$editando = false;
$perfil_editar = ['id' => '', 'nombre' => '', 'descripcion' => '', 'fecha_creacion' => ''];

// Eliminar perfil
if (isset($_GET['eliminar'])) {
    $id_eliminar = (int)$_GET['eliminar'];
    foreach ($perfiles as $k => $perfil) {
        if ($perfil['id'] === $id_eliminar) {
            unset($perfiles[$k]);
            $mensaje = 'Perfil eliminado correctamente.';
            break;
        }
    }
    // Reindexar el array
    $perfiles = array_values($perfiles);
}

// Preparar edición
if (isset($_GET['editar'])) {
    $id_editar = (int)$_GET['editar'];
    foreach ($perfiles as $perfil) {
        if ($perfil['id'] === $id_editar) {
            $perfil_editar = $perfil;
            $editando = true;
            break;
        }
    }
}

// Guardar edición
if (isset($_POST['guardar_edicion'])) {
    $id = (int)$_POST['id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    foreach ($perfiles as &$perfil) {
        if ($perfil['id'] === $id) {
            $perfil['nombre'] = $nombre;
            $perfil['descripcion'] = $descripcion;
            $mensaje = 'Perfil editado correctamente.';
            break;
        }
    }
    unset($perfil);
    $editando = false;
}

// Agregar perfil
if (!$editando && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['descripcion']) && !isset($_POST['guardar_edicion'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($nombre !== '' && $descripcion !== '') {
        $nuevo_id = count($perfiles) > 0 ? max(array_column($perfiles, 'id')) + 1 : 1;
        $perfiles[] = [
            'id' => $nuevo_id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_creacion' => date('Y-m-d')
        ];
        $mensaje = 'Perfil agregado correctamente.';
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Perfiles</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; margin: 40px;">
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; max-width: 650px; padding: 24px; box-shadow: 0 2px 8px #eee;">
        <h2 style="margin-top: 0; color: #333; font-size: 1.3em;">Gestión de Perfiles</h2>
        <?php if ($mensaje): ?>
            <div style="margin-bottom: 16px; color: #007700; font-weight: bold;"> <?php echo htmlspecialchars($mensaje); ?> </div>
        <?php endif; ?>
        <?php if ($editando): ?>
        <form method="post" style="margin-bottom: 24px;">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($perfil_editar['id']); ?>">
            <label>Nombre:<br>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($perfil_editar['nombre']); ?>" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Descripción:<br>
                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($perfil_editar['descripcion']); ?>" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <button type="submit" name="guardar_edicion" style="padding: 8px 16px; background: #007acc; color: #fff; border: none; border-radius: 4px;">Guardar cambios</button>
            <a href="gestion_perfiles.php" style="margin-left: 12px; color: #007acc; text-decoration: underline;">Cancelar</a>
        </form>
        <?php else: ?>
        <form method="post" style="margin-bottom: 24px;">
            <label>Nombre:<br>
                <input type="text" name="nombre" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <label>Descripción:<br>
                <input type="text" name="descripcion" style="width: 100%; padding: 6px; margin-bottom: 8px;" required>
            </label><br>
            <button type="submit" style="padding: 8px 16px; background: #007acc; color: #fff; border: none; border-radius: 4px;">Agregar perfil</button>
        </form>
        <?php endif; ?>
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">ID</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Nombre</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Descripción</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Fecha de creación</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Acciones</th>
            </tr>
            <?php foreach ($perfiles as $perfil): ?>
            <tr>
                <td style="padding: 8px;"><?php echo htmlspecialchars($perfil['id']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($perfil['nombre']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($perfil['descripcion']); ?></td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($perfil['fecha_creacion']); ?></td>
                <td style="padding: 8px;">
                    <a href="?editar=<?php echo $perfil['id']; ?>" style="color: #007acc; text-decoration: underline; margin-right: 8px;">Editar</a>
                    <a href="?eliminar=<?php echo $perfil['id']; ?>" style="color: #cc0000; text-decoration: underline;" onclick="return confirm('¿Seguro que deseas eliminar este perfil?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
