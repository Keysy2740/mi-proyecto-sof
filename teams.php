<?php
session_start();
require 'config/database.php';

// Solo usuarios con sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Eliminar equipo (se hace por POST para mayor seguridad)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare('DELETE FROM teams WHERE id = ?');
    $stmt->execute([$_POST['delete_id']]);
    header('Location: teams.php');
    exit;
}

$teams = $pdo->query('SELECT * FROM teams ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
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
  <p><a href="dashboard.php">Volver al panel</a> | <a href="team_form.php">Nuevo equipo</a></p>

  <table border="1" cellpadding="8">
    <tr>
      <th>Nombre</th>
      <th>Ciudad</th>
      <th>Entrenador</th>
      <th>Acciones</th>
    </tr>
    <?php foreach ($teams as $team): ?>
      <tr>
        <td><?= htmlspecialchars($team['name']) ?></td>
        <td><?= htmlspecialchars($team['city']) ?></td>
        <td><?= htmlspecialchars($team['coach']) ?></td>
        <td>
          <a href="team_form.php?id=<?= $team['id'] ?>">Editar</a>
          <form method="POST" style="display:inline;"
                onsubmit="return confirm('¿Eliminar este equipo?');">
            <input type="hidden" name="delete_id" value="<?= $team['id'] ?>">
            <button type="submit">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>