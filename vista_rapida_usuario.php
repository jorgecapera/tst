<?php
// Simulación de datos como si vinieran de una base de datos
$usuario = [
    'nombre_completo' => 'Juan Pérez',
    'email' => 'juan.perez@ejemplo.com',
    'ultimo_acceso' => '2025-06-15 14:23:10'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Rápida de Usuario</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; margin: 40px;">
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; max-width: 400px; padding: 24px; box-shadow: 0 2px 8px #eee;">
        <h2 style="margin-top: 0; color: #333; font-size: 1.3em;">Vista rápida del usuario</h2>
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Campo</th>
                <th style="text-align:left; padding: 8px; border-bottom: 1px solid #eee;">Valor</th>
            </tr>
            <tr>
                <td style="padding: 8px;">Nombre completo</td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
            </tr>
            <tr>
                <td style="padding: 8px;">Correo electrónico</td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($usuario['email']); ?></td>
            </tr>
            <tr>
                <td style="padding: 8px;">Último acceso</td>
                <td style="padding: 8px;"><?php echo htmlspecialchars($usuario['ultimo_acceso']); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
