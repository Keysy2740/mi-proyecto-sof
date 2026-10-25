<?php
session_start();

// Si no hay sesión iniciada, vuelve al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Encabezado con el logo y el menú -->
  <div class="encabezado">
    <a href="index.php"><img src="img/logo.jpg" alt="Logo de la Liga de Básquetbol"></a>
    <div class="navegacion">
      <a href="index.php">Inicio</a>
      <a href="salir.php" class="salir">Cerrar sesión</a>
    </div>
  </div>

  <h1>Bienvenida, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h1>
  <p>¿Qué quieres gestionar hoy?</p>

  <div class="tarjetas">
    <a href="equipos.php" class="tarjeta"><h3>Equipos</h3>Crea, edita y elimina equipos.</a>
    <a href="jugadores.php" class="tarjeta"><h3>Jugadores</h3>Administra la plantilla de cada equipo.</a>
    <a href="partidos.php" class="tarjeta"><h3>Partidos</h3>Programa partidos y registra resultados.</a>
    <a href="posiciones.php" class="tarjeta"><h3>Posiciones</h3>Mira la tabla de la liga.</a>
  </div>

</body>
</html>