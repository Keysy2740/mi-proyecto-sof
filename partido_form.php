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

$titulo = $id ? 'Editar partido' : 'Nuevo partido';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('🏀', $titulo, 'Elige los equipos, la fecha y, si ya se jugó, el marcador.',
        '<a class="boton-volver" href="partidos.php">← Volver a partidos</a>'); ?>

    <?php if ($error): ?>
      <p class="alerta error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (count($equipos) < 2): ?>
      <p class="alerta">Necesitas al menos dos equipos. <a href="equipo_form.php">Crear equipo</a></p>
    <?php else: ?>
      <form method="POST" class="formulario formulario-tarjeta">
        <div class="campo">
          <label>Equipo local</label>
          <select name="equipo_local_id" required>
            <option value="">Selecciona...</option>
            <?php foreach ($equipos as $equipo): ?>
              <option value="<?= $equipo['id'] ?>" <?= $partido['equipo_local_id'] == $equipo['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($equipo['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="campo">
          <label>Equipo visitante</label>
          <select name="equipo_visitante_id" required>
            <option value="">Selecciona...</option>
            <?php foreach ($equipos as $equipo): ?>
              <option value="<?= $equipo['id'] ?>" <?= $partido['equipo_visitante_id'] == $equipo['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($equipo['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="campo">
          <label>Fecha</label>
          <input type="date" name="fecha" value="<?= htmlspecialchars($partido['fecha']) ?>" required>
        </div>
        <div class="campo">
          <label>Puntos del local (vacío si está pendiente)</label>
          <input type="number" name="puntos_local" min="0" value="<?= htmlspecialchars((string)$partido['puntos_local']) ?>">
        </div>
        <div class="campo">
          <label>Puntos del visitante (vacío si está pendiente)</label>
          <input type="number" name="puntos_visitante" min="0" value="<?= htmlspecialchars((string)$partido['puntos_visitante']) ?>">
        </div>
        <button type="submit">Guardar</button>
        <a href="partidos.php" class="btn-chico">Cancelar</a>
      </form>
    <?php endif; ?>
  </main>
<?php include 'includes/pie.php'; ?>
