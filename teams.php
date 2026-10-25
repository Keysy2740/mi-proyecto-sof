<?php
session_start();
require 'config/database.php';

// Solo usuarios con sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Eliminar equipo (se hace por POST para mayor seguridad)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $stmt = $pdo->prepare('DELETE FROM equipos WHERE id = ?');
    $stmt->execute([$_POST['eliminar_id']]);
    header('Location: equipos.php');
    exit;
}

$equipos = $pdo->query('SELECT * FROM equipos ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Equipos - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Equipos</h1>
  <p><a href="dashboard.php">Volver al panel</a> | <a href="equipo_form.php">Nuevo equipo</a></p>

  <table border="1" cellpadding="8">
    <tr>
      <th>Nombre</th>
      <th>Ciudad</th>
      <th>Entrenador</th>
      <th>Acciones</th>
    </tr>
    <?php foreach ($equipos as $equipo): ?>
      <tr>
        <td><?= htmlspecialchars($equipo['nombre']) ?></td>
        <td><?= htmlspecialchars($equipo['ciudad']) ?></td>
        <td><?= htmlspecialchars($equipo['entrenador']) ?></td>
        <td>
          <a href="equipo_form.php?id=<?= $equipo['id'] ?>">Editar</a>
          <form method="POST" style="display:inline;"
                onsubmit="return confirm('¿Eliminar este equipo?');">
            <input type="hidden" name="eliminar_id" value="<?= $equipo['id'] ?>">
            <button type="submit">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>