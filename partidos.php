<?php
session_start();
require 'config/database.php';
require 'controladores/PartidoControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new PartidoControlador($pdo);

// Eliminar partido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $controlador->eliminar((int)$_POST['eliminar_id']);
    header('Location: partidos.php');
    exit;
}

$partidos = $controlador->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Partidos - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Partidos</h1>
  <p><a href="panel.php">Volver al panel</a> | <a href="partido_form.php">Nuevo partido</a></p>

  <table border="1" cellpadding="8">
    <tr>
      <th>Fecha</th>
      <th>Local</th>
      <th>Resultado</th>
      <th>Visitante</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
    <?php foreach ($partidos as $partido): ?>
      <?php $jugado = $partido['puntos_local'] !== null && $partido['puntos_visitante'] !== null; ?>
      <tr>
        <td><?= htmlspecialchars($partido['fecha']) ?></td>
        <td><?= htmlspecialchars($partido['local']) ?></td>
        <td>
          <?= $jugado ? (int)$partido['puntos_local'] . ' - ' . (int)$partido['puntos_visitante'] : '-' ?>
        </td>
        <td><?= htmlspecialchars($partido['visitante']) ?></td>
        <td><?= $jugado ? 'Finalizado' : 'Pendiente' ?></td>
        <td>
          <a href="partido_form.php?id=<?= $partido['id'] ?>">Editar / registrar resultado</a>
          <form method="POST" style="display:inline;"
                onsubmit="return confirm('¿Eliminar este partido?');">
            <input type="hidden" name="eliminar_id" value="<?= $partido['id'] ?>">
            <button type="submit">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>