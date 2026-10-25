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
  <h1>Bienvenida, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h1>
  <p>Panel de la Liga de Básquetbol.</p>
  <p><a href="equipos.php">Gestionar equipos</a></p>
  <p><a href="jugadores.php">Gestionar jugadores</a></p>
  <p><a href="partidos.php">Gestionar partidos</a></p>
  <p><a href="posiciones.php">Ver tabla de posiciones</a></p>
  <a href="salir.php">Cerrar sesión</a>
</body>
</html>