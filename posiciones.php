<?php
session_start();
require 'config/database.php';
require 'controladores/EstadisticaControlador.php';

// Esta página es pública: cualquier visitante puede ver la tabla
$controlador = new EstadisticaControlador($pdo);
$tabla = $controlador->posiciones();

$titulo = 'Posiciones';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('🏆', 'Tabla de posiciones', 'Resultados de la liga con los partidos finalizados.',
        isset($_SESSION['usuario_id']) ? '<a class="boton-volver" href="panel.php">← Volver al panel</a>' : ''); ?>

    <div class="tabla-caja">
      <table>
        <tr>
          <th>#</th><th>Equipo</th><th>PJ</th><th>PG</th><th>PP</th><th>PF</th><th>PC</th><th>Dif.</th>
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
    </div>

    <p class="leyenda">
      PJ: partidos jugados · PG: ganados · PP: perdidos ·
      PF: puntos a favor · PC: puntos en contra · Dif.: diferencia
    </p>
  </main>
<?php include 'includes/pie.php'; ?>
