<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$id     = $_GET['id'] ?? null;
$equipo = ['nombre' => '', 'ciudad' => '', 'entrenador' => ''];
$error  = '';

// Si hay id, cargamos el equipo para editarlo
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM equipos WHERE id = ?');
    $stmt->execute([$id]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$equipo) {
        header('Location: equipos.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre']);
    $ciudad     = trim($_POST['ciudad']);
    $entrenador = trim($_POST['entrenador']);

    if ($nombre === '' || $ciudad === '') {
        $error  = 'El nombre y la ciudad son obligatorios.';
        $equipo = ['nombre' => $nombre, 'ciudad' => $ciudad, 'entrenador' => $entrenador];
    } else {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE equipos SET nombre = ?, ciudad = ?, entrenador = ? WHERE id = ?');
            $stmt->execute([$nombre, $ciudad, $entrenador, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO equipos (nombre, ciudad, entrenador) VALUES (?, ?, ?)');
            $stmt->execute([$nombre, $ciudad, $entrenador]);
        }
        header('Location: equipos.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Editar' : 'Nuevo' ?> equipo</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= $id ? 'Editar' : 'Nuevo' ?> equipo</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Nombre</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($equipo['nombre']) ?>" required><br><br>

    <label>Ciudad</label><br>
    <input type="text" name="ciudad" value="<?= htmlspecialchars($equipo['ciudad']) ?>" required><br><br>

    <label>Entrenador</label><br>
    <input type="text" name="entrenador" value="<?= htmlspecialchars($equipo['entrenador']) ?>"><br><br>

    <button type="submit">Guardar</button>
    <a href="equipos.php">Cancelar</a>
  </form>
</body>
</html>