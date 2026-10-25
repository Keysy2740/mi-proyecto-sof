<?php
session_start();
require 'config/database.php';
require 'controladores/ResumenControlador.php';

$controlador = new ResumenControlador($pdo);
$totales     = $controlador->totales();
$ultimos     = $controlador->ultimos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Encabezado con el logo y el menú -->
  <div class="encabezado">
    <a href="index.php"><img src="img/logo.jpg" alt="Logo de la Liga de Básquetbol"></a>
    <div class="navegacion">
      <a href="posiciones.php">Posiciones</a>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="panel.php">Mi panel</a>
        <a href="salir.php" class="salir">Cerrar sesión</a>
      <?php else: ?>
        <a href="ingreso.php">Ingresar</a>
        <a href="registro.php">Registrarme</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Imagen grande con el mensaje principal -->
  <div class="banner">
    <div class="banner-texto">
      <h2>Vive la pasión del básquetbol</h2>
      <p>Gestiona equipos, jugadores, partidos y resultados de tu liga en un solo lugar.</p>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="panel.php" class="boton">Ir a mi panel</a>
      <?php else: ?>
        <a href="ingreso.php" class="boton">Ingresar</a>
        <a href="registro.php" class="boton">Crear cuenta</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Números de la liga -->
  <div class="numeros">
    <div class="numero">
      <strong><?= $totales['equipos'] ?></strong>
      <span>Equipos</span>
    </div>
    <div class="numero">
      <strong><?= $totales['jugadores'] ?></strong>
      <span>Jugadores</span>
    </div>
    <div class="numero">
      <strong><?= $totales['partidos'] ?></strong>
      <span>Partidos</span>
    </div>
  </div>

  <!-- Últimos resultados -->
  <h1>Últimos resultados</h1>
  <?php if (count($ultimos) === 0): ?>
    <p>Todavía no hay partidos finalizados.</p>
  <?php else: ?>
    <div class="resultados">
      <?php foreach ($ultimos as $partido): ?>
        <div class="resultado">
          <small><?= htmlspecialchars($partido['fecha']) ?></small>
          <p>
            <?= htmlspecialchars($partido['local']) ?>
            <strong><?= (int)$partido['puntos_local'] ?> - <?= (int)$partido['puntos_visitante'] ?></strong>
            <?= htmlspecialchars($partido['visitante']) ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p class="pie">
    Liga de Básquetbol - Práctica calificada<br>
    Foto de portada: Foto de Joel Muniz en Unsplash
  </p>

</body>
</html>