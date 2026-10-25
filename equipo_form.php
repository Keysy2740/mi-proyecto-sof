<?php
session_start();
require 'config/database.php';
require 'controladores/EquipoControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new EquipoControlador($pdo);

$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;
$equipo = ['nombre' => '', 'ciudad' => '', 'entrenador' => ''];
$error  = '';

// Si hay id, cargamos el equipo para editarlo
if ($id) {
    $equipo = $controlador->buscar($id);
    if (!$equipo) {
        header('Location: equipos.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo = [
        'nombre'     => $_POST['nombre'],
        'ciudad'     => $_POST['ciudad'],
        'entrenador' => $_POST['entrenador'],
    ];

    // El controlador valida y guarda; devuelve el error o una cadena vacía
    $error = $controlador->guardar($id, $equipo['nombre'], $equipo['ciudad'], $equipo['entrenador']);

    if ($error === '') {
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