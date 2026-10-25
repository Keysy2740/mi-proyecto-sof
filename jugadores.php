<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

// Eliminar jugador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $stmt = $pdo->prepare('DELETE FROM jugadores WHERE id = ?');
    $stmt->execute([$_POST['eliminar_id']]);
    header('Location: jugadores.php?equipo_id=' . (int)($_POST['equipo_id'] ?? 0));
    exit;
}

// Lista de equipos para el filtro
$equipos = $pdo->query('SELECT id, nombre FROM equipos ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);

// Si hay equipo seleccionado, filtramos; si no, mostramos todos
$equipo_id = (int)($_GET['equipo_id'] ?? 0);

if ($equipo_id > 0) {
    $stmt = $pdo->prepare(
        'SELECT j.*, e.nombre AS equipo
         FROM jugadores j
         JOIN equipos e ON e.id = j.equipo_id
         WHERE j.equipo_id = ?
         ORDER BY j.numero'
    );
    $stmt->execute([$equipo_id]);
} else {
    $stmt = $pdo->query(
        'SELECT j.*, e.nombre AS equipo
         FROM jugadores j
         JOIN equipos e ON e.id = j.equipo_id
         ORDER BY e.nombre, j.numero'
    );
}
$jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Jugadores - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Jugadores</h1>
  <p>
    <a href="panel.php">Volver al panel</a> |
    <a href="jugador_form.php?equipo_id=<?= $equipo_id ?>">Nuevo jugador</a>
  </p>

  <form method="GET">
    <label>Filtrar por equipo:</label>
    <select name="equipo_id" onchange="this.form.submit()">
      <option value="0">Todos los equipos</option>
      <?php foreach ($equipos as $equipo): ?>
        <option value="<?= $equipo['id'] ?>" <?= $equipo['id'] == $equipo_id ? 'selected' : '' ?>>
          <?= htmlspecialchars($equipo['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
  <br>

  <table border="1" cellpadding="8">
    <tr>
      <th>N.º</th>
      <th>Nombre</th>
      <th>Posición</th>
      <th>Equipo</th>
      <th>Acciones</th>
    </tr>
    <?php foreach ($jugadores as $jugador): ?>
      <tr>
        <td><?= (int)$jugador['numero'] ?></td>
        <td><?= htmlspecialchars($jugador['nombre']) ?></td>
        <td><?= htmlspecialchars($jugador['posicion']) ?></td>
        <td><?= htmlspecialchars($jugador['equipo']) ?></td>
        <td>
          <a href="jugador_form.php?id=<?= $jugador['id'] ?>">Editar</a>
          <form method="POST" style="display:inline;"
                onsubmit="return confirm('¿Eliminar este jugador?');">
            <input type="hidden" name="eliminar_id" value="<?= $jugador['id'] ?>">
            <input type="hidden" name="equipo_id" value="<?= $equipo_id ?>">
            <button type="submit">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>