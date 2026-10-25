<?php
session_start();
require 'config/database.php';
require 'controladores/EstadisticaControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new EstadisticaControlador($pdo);
$tabla = $controlador->posiciones();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Posiciones - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Tabla de posiciones</h1>
  <p><a href="panel.php">Volver al panel</a></p>

  <table border="1" cellpadding="8">
    <tr>
      <th>#</th>
      <th>Equipo</th>
      <th>PJ</th>
      <th>PG</th>
      <th>PP</th>
      <th>PF</th>
      <th>PC</th>
      <th>Dif.</th>
    </tr>
    <?php foreach ($tabla as $i => $fila): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($fila['nombre']) ?></td>
        <td><?= (int)$fila['pj'] ?></td>
        <td><?= (int)$fila['pg'] ?></td>
        <td><?= (int)$fila['pp'] ?></td>
        <td><?= (int)$fila['pf'] ?></td>
        <td><?= (int)$fila['pc'] ?></td>
        <td><?= $fila['dif'] > 0 ? '+' . $fila['dif'] : $fila['dif'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p>
    PJ: partidos jugados · PG: ganados · PP: perdidos ·
    PF: puntos a favor · PC: puntos en contra · Dif.: diferencia
  </p>
</body>
</html>