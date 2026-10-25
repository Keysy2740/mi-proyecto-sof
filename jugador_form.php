<?php
session_start();
require 'config/database.php';
require 'controladores/JugadorControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new JugadorControlador($pdo);

$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$jugador = [
    'nombre'    => '',
    'numero'    => '',
    'posicion'  => '',
    'equipo_id' => (int)($_GET['equipo_id'] ?? 0),
];
$error   = '';
$equipos = $controlador->equipos();

// Si hay id, cargamos el jugador para editarlo
if ($id) {
    $jugador = $controlador->buscar($id);
    if (!$jugador) {
        header('Location: jugadores.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugador = [
        'nombre'    => $_POST['nombre'],
        'numero'    => (int)$_POST['numero'],
        'posicion'  => $_POST['posicion'],
        'equipo_id' => (int)$_POST['equipo_id'],
    ];

    // El controlador valida y guarda; devuelve el error o una cadena vacía
    $error = $controlador->guardar(
        $id,
        $jugador['nombre'],
        $jugador['numero'],
        $jugador['posicion'],
        $jugador['equipo_id']
    );

    if ($error === '') {
        header('Location: jugadores.php?equipo_id=' . $jugador['equipo_id']);
        exit;
    }
}

$posiciones = ['Base', 'Escolta', 'Alero', 'Ala-pívot', 'Pívot'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Editar' : 'Nuevo' ?> jugador</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= $id ? 'Editar' : 'Nuevo' ?> jugador</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if (count($equipos) === 0): ?>
    <p>Primero debes <a href="equipo_form.php">crear un equipo</a>.</p>
  <?php else: ?>
  <form method="POST">
    <label>Nombre</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($jugador['nombre']) ?>" required><br><br>

    <label>Número de camiseta</label><br>
    <input type="number" name="numero" min="0" max="99" value="<?= htmlspecialchars((string)$jugador['numero']) ?>" required><br><br>

    <label>Posición</label><br>
    <select name="posicion" required>
      <option value="">Selecciona...</option>
      <?php foreach ($posiciones as $pos): ?>
        <option value="<?= $pos ?>" <?= $jugador['posicion'] === $pos ? 'selected' : '' ?>><?= $pos ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Equipo</label><br>
    <select name="equipo_id" required>
      <option value="">Selecciona...</option>
      <?php foreach ($equipos as $equipo): ?>
        <option value="<?= $equipo['id'] ?>" <?= $jugador['equipo_id'] == $equipo['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($equipo['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Guardar</button>
    <a href="jugadores.php">Cancelar</a>
  </form>
  <?php endif; ?>
</body>
</html>