<?php
session_start();
require 'config/database.php';
require 'controladores/ResumenControlador.php';
require 'controladores/EstadisticaControlador.php';
require 'controladores/EquipoControlador.php';

$resumen     = new ResumenControlador($pdo);
$estadistica = new EstadisticaControlador($pdo);
$equipo      = new EquipoControlador($pdo);

// Datos que se muestran en las tres tarjetas
$proximos   = $resumen->proximos();
$posiciones = array_slice($estadistica->posiciones(), 0, 5);
$equipos    = array_slice($equipo->listar(), 0, 6);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="inicio">

  <!-- Encabezado con el logo y el menú -->
  <div class="encabezado">
    <a href="index.php"><img src="img/logo.jpg" alt="Logo de la Liga de Básquetbol"></a>
    <div class="navegacion">
      <a href="posiciones.php">Posiciones</a>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="panel.php">Mi panel</a>
        <a href="salir.php" class="salir">Cerrar sesión</a>
      <?php else: ?>
        <a href="ingreso.php" class="boton-menu">Ingresar</a>
        <a href="registro.php" class="boton-borde">Registrarme</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Imagen principal con el mensaje -->
  <div class="hero">
    <div class="hero-capa">
      <div class="contenedor">
        <h2>Vive la pasión<br><span>del básquetbol</span></h2>
        <p>Equipos. Jugadores. Resultados. Todo en un solo lugar.</p>
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <a href="panel.php" class="boton">Ir a mi panel</a>
        <?php else: ?>
          <a href="registro.php" class="boton">Crear cuenta</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Tres tarjetas con información de la liga -->
  <div class="contenedor">
    <div class="bloques">

      <div class="bloque">
        <h3>Próximos partidos</h3>
        <?php if (count($proximos) === 0): ?>
          <p class="vacio">No hay partidos programados.</p>
        <?php else: ?>
          <?php foreach ($proximos as $partido): ?>
            <div class="partido-mini">
              <span><?= htmlspecialchars($partido['local']) ?></span>
              <small><?= date('d/m/Y', strtotime($partido['fecha'])) ?></small>
              <span><?= htmlspecialchars($partido['visitante']) ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="bloque">
        <h3>Posiciones</h3>
        <?php if (count($posiciones) === 0): ?>
          <p class="vacio">Todavía no hay equipos.</p>
        <?php else: ?>
          <table>
            <tr>
              <th>#</th>
              <th>Equipo</th>
              <th>PJ</th>
              <th>PG</th>
              <th>Dif.</th>
            </tr>
            <?php foreach ($posiciones as $i => $fila): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= (int)$fila['pj'] ?></td>
                <td><?= (int)$fila['pg'] ?></td>
                <td><?= $fila['dif'] > 0 ? '+' . $fila['dif'] : $fila['dif'] ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>
      </div>

      <div class="bloque">
        <h3>Equipos</h3>
        <?php if (count($equipos) === 0): ?>
          <p class="vacio">Todavía no hay equipos.</p>
        <?php else: ?>
          <div class="equipos-lista">
            <?php foreach ($equipos as $e): ?>
              <div class="equipo-item">
                <div class="insignia"><?= htmlspecialchars(mb_strtoupper(mb_substr($e['nombre'], 0, 1))) ?></div>
                <small><?= htmlspecialchars($e['nombre']) ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>

    <p class="pie">
      Liga de Básquetbol - Práctica calificada<br>
      Foto de portada: Foto de Joel Muniz en Unsplash
    </p>
  </div>

</body>
</html>