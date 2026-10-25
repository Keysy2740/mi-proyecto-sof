<?php
session_start();

// Si no hay sesión iniciada, vuelve al login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
  <h1>Bienvenida, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
  <p>Panel de la Liga de Básquetbol.</p>
  <a href="logout.php">Cerrar sesión</a>
</body>
</html>