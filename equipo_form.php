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

$titulo = $id ? 'Editar equipo' : 'Nuevo equipo';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('👥', $titulo, 'Completa los datos del equipo.',
        '<a class="boton-volver" href="equipos.php">← Volver a equipos</a>'); ?>

    <?php if ($error): ?>
      <p class="alerta error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario formulario-tarjeta">
      <div class="campo">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($equipo['nombre']) ?>" required>
      </div>
      <div class="campo">
        <label>Ciudad</label>
        <input type="text" name="ciudad" value="<?= htmlspecialchars($equipo['ciudad']) ?>" required>
      </div>
      <div class="campo">
        <label>Entrenador</label>
        <input type="text" name="entrenador" value="<?= htmlspecialchars($equipo['entrenador']) ?>">
      </div>
      <button type="submit">Guardar</button>
      <a href="equipos.php" class="btn-chico">Cancelar</a>
    </form>
  </main>
<?php include 'includes/pie.php'; ?>
