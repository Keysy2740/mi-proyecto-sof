<?php
session_start();
require 'config/database.php';
require 'controladores/PartidoControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new PartidoControlador($pdo);

// Eliminar partido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $controlador->eliminar((int)$_POST['eliminar_id']);
    header('Location: partidos.php');
    exit;
}

$partidos = $controlador->listar();

$titulo = 'Partidos';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('🏀', 'Partidos', 'Programa partidos y registra sus resultados.',
        '<a class="boton-volver" href="panel.php">← Volver al panel</a>
         <a class="boton" href="partido_form.php">+ Nuevo partido</a>'); ?>

    <div class="tabla-caja">
      <table>
        <tr><th>Fecha</th><th>Local</th><th>Resultado</th><th>Visitante</th><th>Estado</th><th>Acciones</th></tr>
        <?php if (count($partidos) === 0): ?>
          <tr><td colspan="6" class="vacio">Todavía no hay partidos. Programa el primero.</td></tr>
        <?php endif; ?>
        <?php foreach ($partidos as $partido): ?>
          <?php $jugado = $partido['puntos_local'] !== null && $partido['puntos_visitante'] !== null; ?>
          <tr>
            <td><?= htmlspecialchars($partido['fecha']) ?></td>
            <td><?= htmlspecialchars($partido['local']) ?></td>
            <td><?= $jugado ? (int)$partido['puntos_local'] . ' - ' . (int)$partido['puntos_visitante'] : '-' ?></td>
            <td><?= htmlspecialchars($partido['visitante']) ?></td>
            <td><?= $jugado ? '<span class="estado-final">Finalizado</span>' : '<span class="estado-pendiente">Pendiente</span>' ?></td>
            <td>
              <a class="btn-chico" href="partido_form.php?id=<?= $partido['id'] ?>">Editar / resultado</a>
              <form method="POST" class="en-linea" onsubmit="return confirm('¿Eliminar este partido?');">
                <input type="hidden" name="eliminar_id" value="<?= $partido['id'] ?>">
                <button type="submit" class="btn-peligro">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
<?php include 'includes/pie.php'; ?>
