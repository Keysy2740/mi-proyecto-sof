<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$id      = $_GET['id'] ?? null;
$partido = [
    'equipo_local_id'     => '',
    'equipo_visitante_id' => '',
    'puntos_local'        => '',
    'puntos_visitante'    => '',
    'fecha'               => date('Y-m-d'),
];
$error = '';

$equipos = $pdo->query('SELECT id, nombre FROM equipos ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);

// Si hay id, cargamos el partido para editarlo
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM partidos WHERE id = ?');
    $stmt->execute([$id]);
    $partido = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$partido) {
        header('Location: partidos.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $local     = (int)$_POST['equipo_local_id'];
    $visitante = (int)$_POST['equipo_visitante_id'];
    $fecha     = $_POST['fecha'];

    // Los puntos son opcionales: vacío significa partido pendiente (NULL)
    $puntos_local     = $_POST['puntos_local'] === '' ? null : (int)$_POST['puntos_local'];
    $puntos_visitante = $_POST['puntos_visitante'] === '' ? null : (int)$_POST['puntos_visitante'];

    $partido = [
        'equipo_local_id'     => $local,
        'equipo_visitante_id' => $visitante,
        'puntos_local'        => $puntos_local,
        'puntos_visitante'    => $puntos_visitante,
        'fecha'               => $fecha,
    ];

    if ($local === 0 || $visitante === 0 || $fecha === '') {
        $error = 'Debes elegir los dos equipos y la fecha.';
    } elseif ($local === $visitante) {
        $error = 'Un equipo no puede jugar contra sí mismo.';
    } elseif (($puntos_local === null) !== ($puntos_visitante === null)) {
        $error = 'Ingresa los puntos de ambos equipos, o deja los dos vacíos si el partido está pendiente.';
    } elseif (($puntos_local !== null && $puntos_local < 0) || ($puntos_visitante !== null && $puntos_visitante < 0)) {
        $error = 'Los puntos no pueden ser negativos.';
    } elseif ($puntos_local !== null && $puntos_local === $puntos_visitante) {
        $error = 'En básquetbol no hay empates: los puntos deben ser distintos.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE partidos
                 SET equipo_local_id = ?, equipo_visitante_id = ?, puntos_local = ?, puntos_visitante = ?, fecha = ?
                 WHERE id = ?'
            );
            $stmt->execute([$local, $visitante, $puntos_local, $puntos_visitante, $fecha, $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO partidos (equipo_local_id, equipo_visitante_id, puntos_local, puntos_visitante, fecha)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$local, $visitante, $puntos_local, $puntos_visitante, $fecha]);
        }
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