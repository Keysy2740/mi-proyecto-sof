<?php
session_start();
require 'config/database.php';
require 'controladores/EquipoControlador.php';

// Solo usuarios con sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new EquipoControlador($pdo);

// Eliminar equipo (por POST, para que no se borre con solo abrir una dirección)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $controlador->eliminar((int)$_POST['eliminar_id']);
    header('Location: equipos.php');
    exit;
}

$equipos = $controlador->listar();

$titulo = 'Equipos';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('👥', 'Equipos', 'Crea, edita y elimina los equipos de la liga.',
        '<a class="boton-volver" href="panel.php">← Volver al panel</a>
         <a class="boton" href="equipo_form.php">+ Nuevo equipo</a>'); ?>

    <div class="tabla-caja">
      <table>
        <tr><th>Nombre</th><th>Ciudad</th><th>Entrenador</th><th>Acciones</th></tr>
        <?php if (count($equipos) === 0): ?>
          <tr><td colspan="4" class="vacio">Todavía no hay equipos. Crea el primero.</td></tr>
        <?php endif; ?>
        <?php foreach ($equipos as $equipo): ?>
          <tr>
            <td><?= htmlspecialchars($equipo['nombre']) ?></td>
            <td><?= htmlspecialchars($equipo['ciudad']) ?></td>
            <td><?= htmlspecialchars($equipo['entrenador']) ?></td>
            <td>
              <a class="btn-chico" href="equipo_form.php?id=<?= $equipo['id'] ?>">Editar</a>
              <form method="POST" class="en-linea" onsubmit="return confirm('¿Eliminar este equipo?');">
                <input type="hidden" name="eliminar_id" value="<?= $equipo['id'] ?>">
                <button type="submit" class="btn-peligro">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
<?php include 'includes/pie.php'; ?>
