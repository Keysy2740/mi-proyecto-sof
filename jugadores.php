<?php
session_start();
require 'config/database.php';
require 'controladores/JugadorControlador.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$controlador = new JugadorControlador($pdo);

// Eliminar jugador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $controlador->eliminar((int)$_POST['eliminar_id']);
    header('Location: jugadores.php?equipo_id=' . (int)($_POST['equipo_id'] ?? 0));
    exit;
}

// Si hay equipo seleccionado en el filtro, mostramos solo sus jugadores
$equipo_id = (int)($_GET['equipo_id'] ?? 0);
$equipos   = $controlador->equipos();
$jugadores = $controlador->listar($equipo_id);

$titulo = 'Jugadores';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <?php tituloSeccion('🏃', 'Jugadores', 'Administra la plantilla de cada equipo.',
        '<a class="boton-volver" href="panel.php">← Volver al panel</a>
         <a class="boton" href="jugador_form.php?equipo_id=' . $equipo_id . '">+ Nuevo jugador</a>'); ?>

    <form method="GET" class="filtro">
      <label>Filtrar por equipo:</label>
      <select name="equipo_id" onchange="this.form.submit()">
        <option value="0">Todos los equipos</option>
        <?php foreach ($equipos as $equipo): ?>
          <option value="<?= $equipo['id'] ?>" <?= $equipo['id'] == $equipo_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($equipo['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <div class="tabla-caja">
      <table>
        <tr><th>N.º</th><th>Nombre</th><th>Posición</th><th>Equipo</th><th>Acciones</th></tr>
        <?php if (count($jugadores) === 0): ?>
          <tr><td colspan="5" class="vacio">No hay jugadores para mostrar.</td></tr>
        <?php endif; ?>
        <?php foreach ($jugadores as $jugador): ?>
          <tr>
            <td><?= (int)$jugador['numero'] ?></td>
            <td><?= htmlspecialchars($jugador['nombre']) ?></td>
            <td><?= htmlspecialchars($jugador['posicion']) ?></td>
            <td><?= htmlspecialchars($jugador['equipo']) ?></td>
            <td>
              <a class="btn-chico" href="jugador_form.php?id=<?= $jugador['id'] ?>">Editar</a>
              <form method="POST" class="en-linea" onsubmit="return confirm('¿Eliminar este jugador?');">
                <input type="hidden" name="eliminar_id" value="<?= $jugador['id'] ?>">
                <input type="hidden" name="equipo_id" value="<?= $equipo_id ?>">
                <button type="submit" class="btn-peligro">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
<?php include 'includes/pie.php'; ?>
