<?php
session_start();
require 'config/database.php';
require 'controladores/PartidoControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new PartidoControlador($pdo);

$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$partido = [
    'equipo_local_id'     => '',
    'equipo_visitante_id' => '',
    'puntos_local'        => '',
    'puntos_visitante'    => '',
    'fecha'               => date('Y-m-d'),
];
$error   = '';
$equipos = $controlador->equipos();

// Si hay id, cargamos el partido para editarlo
if ($id) {
    $partido = $controlador->buscar($id);
    if (!$partido) {
        header('Location: partidos.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partido = [
        'equipo_local_id'     => (int)$_POST['equipo_local_id'],
        'equipo_visitante_id' => (int)$_POST['equipo_visitante_id'],
        'puntos_local'        => $_POST['puntos_local'],
        'puntos_visitante'    => $_POST['puntos_visitante'],
        'fecha'               => $_POST['fecha'],
    ];

    // El controlador valida y guarda; devuelve el error o una cadena vacía
    $error = $controlador->guardar(
        $id,
        $partido['equipo_local_id'],
        $partido['equipo_visitante_id'],
        $partido['fecha'],
        $partido['puntos_local'],
        $partido['puntos_visitante']
    );

    if ($error === '') {
        header('Location: partidos.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Editar' : 'Nuevo' ?> partido</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= $id ? 'Editar' : 'Nuevo' ?> partido</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if (count($equipos) < 2): ?>
    <p>Necesitas al menos dos equipos. <a href="equipo_form.php">Crear equipo</a></p>
  <?php else: ?>
  <form method="POST">
    <label>Equipo local</label><br>
    <select name="equipo_local_id" required>
      <option value="">Selecciona...</option>
      <?php foreach ($equipos as $equipo): ?>
        <option value="<?= $equipo['id'] ?>" <?= $partido['equipo_local_id'] == $equipo['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($equipo['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Equipo visitante</label><br>
    <select name="equipo_visitante_id" required>
      <option value="">Selecciona...</option>
      <?php foreach ($equipos as $equipo): ?>
        <option value="<?= $equipo['id'] ?>" <?= $partido['equipo_visitante_id'] == $equipo['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($equipo['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Fecha</label><br>
    <input type="date" name="fecha" value="<?= htmlspecialchars($partido['fecha']) ?>" required><br><br>

    <label>Puntos del local (vacío si está pendiente)</label><br>
    <input type="number" name="puntos_local" min="0" value="<?= htmlspecialchars((string)$partido['puntos_local']) ?>"><br><br>

    <label>Puntos del visitante (vacío si está pendiente)</label><br>
    <input type="number" name="puntos_visitante" min="0" value="<?= htmlspecialchars((string)$partido['puntos_visitante']) ?>"><br><br>

    <button type="submit">Guardar</button>
    <a href="partidos.php">Cancelar</a>
  </form>
  <?php endif; ?>
</body>
</html>