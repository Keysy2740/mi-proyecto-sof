<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$id      = $_GET['id'] ?? null;
$jugador = [
    'nombre'    => '',
    'numero'    => '',
    'posicion'  => '',
    'equipo_id' => (int)($_GET['equipo_id'] ?? 0),
];
$error = '';

// Equipos disponibles para el menú desplegable
$equipos = $pdo->query('SELECT id, nombre FROM equipos ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);

// Si hay id, cargamos el jugador para editarlo
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM jugadores WHERE id = ?');
    $stmt->execute([$id]);
    $jugador = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$jugador) {
        header('Location: jugadores.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']);
    $numero    = (int)$_POST['numero'];
    $posicion  = trim($_POST['posicion']);
    $equipo_id = (int)$_POST['equipo_id'];

    $jugador = [
        'nombre'    => $nombre,
        'numero'    => $numero,
        'posicion'  => $posicion,
        'equipo_id' => $equipo_id,
    ];

    if ($nombre === '' || $posicion === '' || $equipo_id === 0) {
        $error = 'Nombre, posición y equipo son obligatorios.';
    } elseif ($numero < 0 || $numero > 99) {
        $error = 'El número de camiseta debe estar entre 0 y 99.';
    } else {
        // Un número de camiseta no puede repetirse dentro del mismo equipo
        $stmt = $pdo->prepare('SELECT id FROM jugadores WHERE equipo_id = ? AND numero = ? AND id <> ?');
        $stmt->execute([$equipo_id, $numero, $id ?? 0]);

        if ($stmt->fetch()) {
            $error = 'Ese número de camiseta ya lo usa otro jugador del equipo.';
        } else {
            if ($id) {
                $stmt = $pdo->prepare('UPDATE jugadores SET nombre = ?, numero = ?, posicion = ?, equipo_id = ? WHERE id = ?');
                $stmt->execute([$nombre, $numero, $posicion, $equipo_id, $id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO jugadores (nombre, numero, posicion, equipo_id) VALUES (?, ?, ?, ?)');
                $stmt->execute([$nombre, $numero, $posicion, $equipo_id]);
            }
            header('Location: jugadores.php?equipo_id=' . $equipo_id);
            exit;
        }
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
    <input type="number" name="numero" min="0" max="99" value="<?= htmlspecialchars($jugador['numero']) ?>" required><br><br>

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