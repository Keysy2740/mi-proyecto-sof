<?php
session_start();
require 'config/database.php';
require 'controladores/ResumenControlador.php';
require 'controladores/EstadisticaControlador.php';

// Si no hay sesión iniciada, vuelve al ingreso
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ingreso.php');
    exit;
}

$resumen     = new ResumenControlador($pdo);
$estadistica = new EstadisticaControlador($pdo);

$totales    = $resumen->totales();
$proximos   = $resumen->proximos();
$ultimos    = $resumen->ultimos();
$posiciones = array_slice($estadistica->posiciones(), 0, 5);

$titulo = 'Panel';
include 'includes/cabecera.php';
?>
  <main class="contenedor">

    <!-- Saludo -->
    <div class="panel-saludo">
      <div>
        <p class="etiqueta">Panel de control</p>
        <h1>Hola, <span class="naranja"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span></h1>
        <p>¿Qué quieres gestionar hoy?</p>
      </div>
      <div class="mini-numeros">
        <div><strong><?= $totales['equipos'] ?></strong><span>Equipos</span></div>
        <div><strong><?= $totales['jugadores'] ?></strong><span>Jugadores</span></div>
        <div><strong><?= $totales['partidos'] ?></strong><span>Partidos</span></div>
      </div>
    </div>

    <div class="panel-rejilla">

      <!-- Los cuatro módulos. Para usar tus propios iconos, cambia el emoji por:
           <img src="img/icono-equipos.png" class="icono-img" alt=""> -->
      <div class="modulos">
        <a href="equipos.php" class="modulo">
          <div class="modulo-icono">👥</div>
          <h3>Gestionar equipos</h3>
          <p>Crea, edita y elimina equipos.</p>
        </a>
        <a href="jugadores.php" class="modulo">
          <div class="modulo-icono">🏃</div>
          <h3>Gestionar jugadores</h3>
          <p>Administra la plantilla de cada equipo.</p>
        </a>
        <a href="partidos.php" class="modulo">
          <div class="modulo-icono">🏀</div>
          <h3>Gestionar partidos</h3>
          <p>Programa partidos y registra resultados.</p>
        </a>
        <a href="posiciones.php" class="modulo">
          <div class="modulo-icono">🏆</div>
          <h3>Ver tabla de posiciones</h3>
          <p>Consulta la clasificación de la liga.</p>
        </a>
      </div>

      <!-- Columna derecha con datos de la liga -->
      <div class="panel-lateral">
        <div class="tarjeta">
          <h3>Próximos partidos</h3>
          <?php if (count($proximos) === 0): ?>
            <p class="vacio">No hay partidos programados.</p>
          <?php else: ?>
            <?php foreach ($proximos as $partido): ?>
              <div class="partido-mini">
                <span><?= htmlspecialchars($partido['local']) ?></span>
                <small><?= date('d/m/Y', strtotime($partido['fecha'])) ?></small>
                <span><?= htmlspecialchars($partido['visitante']) ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="tarjeta">
          <h3>Últimos resultados</h3>
          <?php if (count($ultimos) === 0): ?>
            <p class="vacio">Todavía no hay partidos finalizados.</p>
          <?php else: ?>
            <?php foreach ($ultimos as $partido): ?>
              <div class="partido-mini">
                <span><?= htmlspecialchars($partido['local']) ?></span>
                <small><?= (int)$partido['puntos_local'] ?> - <?= (int)$partido['puntos_visitante'] ?></small>
                <span><?= htmlspecialchars($partido['visitante']) ?></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="tarjeta">
          <h3>Posiciones</h3>
          <div class="tabla-caja">
            <table>
              <tr><th>#</th><th>Equipo</th><th>PG</th><th>Dif.</th></tr>
              <?php foreach ($posiciones as $i => $fila): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($fila['nombre']) ?></td>
                  <td><?= (int)$fila['pg'] ?></td>
                  <td><?= $fila['dif'] > 0 ? '+' . $fila['dif'] : $fila['dif'] ?></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>
<?php include 'includes/pie.php'; ?>
