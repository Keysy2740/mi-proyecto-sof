<?php
session_start();
require 'config/database.php';
require 'controladores/ResumenControlador.php';
require 'controladores/EstadisticaControlador.php';
require 'controladores/EquipoControlador.php';
$noticias = require 'includes/noticias_datos.php';

$resumen     = new ResumenControlador($pdo);
$estadistica = new EstadisticaControlador($pdo);
$equipo      = new EquipoControlador($pdo);

// Datos que se muestran en la portada
$totales    = $resumen->totales();
$proximos   = $resumen->proximos();
$ultimos    = $resumen->ultimos();
$posiciones = array_slice($estadistica->posiciones(), 0, 5);
$equipos    = array_slice($equipo->listar(), 0, 6);
$noticias   = array_slice($noticias, 0, 3);

$titulo = 'Inicio';
include 'includes/cabecera.php';
?>

  <!-- Imagen principal -->
  <section class="hero">
    <div class="hero-capa">
      <div class="contenedor">
        <p class="etiqueta">Temporada en curso</p>
        <h1>Vive la pasión<br><span class="naranja">del básquetbol</span></h1>
        <p class="hero-texto">Equipos, jugadores, partidos y resultados de la liga, siempre al día y en un solo lugar.</p>
        <div class="botones">
          <a href="posiciones.php" class="boton">Ver posiciones</a>
          <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="panel.php" class="boton-borde">Ir a mi panel</a>
          <?php else: ?>
            <a href="registro.php" class="boton-borde">Crear cuenta</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <main class="contenedor">

    <!-- Números de la liga -->
    <div class="numeros">
      <div class="numero"><strong><?= $totales['equipos'] ?></strong><span>Equipos</span></div>
      <div class="numero"><strong><?= $totales['jugadores'] ?></strong><span>Jugadores</span></div>
      <div class="numero"><strong><?= $totales['partidos'] ?></strong><span>Partidos</span></div>
    </div>

    <!-- Próximos partidos y últimos resultados -->
    <section class="seccion">
      <h2 class="seccion-titulo">Próximos partidos</h2>
      <?php if (count($proximos) === 0): ?>
        <p class="vacio">No hay partidos programados por ahora.</p>
      <?php else: ?>
        <div class="rejilla">
          <?php foreach ($proximos as $partido): ?>
            <div class="partido-tarjeta">
              <small><?= date('d/m/Y', strtotime($partido['fecha'])) ?></small>
              <div class="enfrentamiento">
                <span><?= htmlspecialchars($partido['local']) ?></span>
                <b>VS</b>
                <span><?= htmlspecialchars($partido['visitante']) ?></span>
              </div>
              <em class="estado-pendiente">Pendiente</em>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section class="seccion">
      <h2 class="seccion-titulo">Últimos resultados</h2>
      <?php if (count($ultimos) === 0): ?>
        <p class="vacio">Todavía no hay partidos finalizados.</p>
      <?php else: ?>
        <div class="rejilla">
          <?php foreach ($ultimos as $partido): ?>
            <div class="partido-tarjeta">
              <small><?= date('d/m/Y', strtotime($partido['fecha'])) ?></small>
              <div class="enfrentamiento">
                <span><?= htmlspecialchars($partido['local']) ?></span>
                <b class="marcador"><?= (int)$partido['puntos_local'] ?> - <?= (int)$partido['puntos_visitante'] ?></b>
                <span><?= htmlspecialchars($partido['visitante']) ?></span>
              </div>
              <em class="estado-final">Finalizado</em>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- Posiciones y equipos, lado a lado -->
    <section class="seccion dos-columnas">
      <div class="tarjeta">
        <h3>Tabla de posiciones</h3>
        <?php if (count($posiciones) === 0): ?>
          <p class="vacio">Todavía no hay equipos.</p>
        <?php else: ?>
          <div class="tabla-caja">
            <table>
              <tr><th>#</th><th>Equipo</th><th>PJ</th><th>PG</th><th>Dif.</th></tr>
              <?php foreach ($posiciones as $i => $fila): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($fila['nombre']) ?></td>
                  <td><?= (int)$fila['pj'] ?></td>
                  <td><?= (int)$fila['pg'] ?></td>
                  <td><?= $fila['dif'] > 0 ? '+' . $fila['dif'] : $fila['dif'] ?></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
        <?php endif; ?>
        <p><a href="posiciones.php">Ver tabla completa →</a></p>
      </div>

      <div class="tarjeta">
        <h3>Equipos</h3>
        <?php if (count($equipos) === 0): ?>
          <p class="vacio">Todavía no hay equipos.</p>
        <?php else: ?>
          <div class="equipos-lista">
            <?php foreach ($equipos as $e): ?>
              <div class="equipo-item">
                <div class="insignia"><?= htmlspecialchars(mb_strtoupper(mb_substr($e['nombre'], 0, 1))) ?></div>
                <small><?= htmlspecialchars($e['nombre']) ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Noticias -->
    <section class="seccion">
      <h2 class="seccion-titulo">Noticias</h2>
      <div class="rejilla">
        <?php foreach ($noticias as $n): ?>
          <article class="noticia">
            <div class="noticia-imagen" style="background-image: url('<?= htmlspecialchars($n['imagen']) ?>');"></div>
            <div class="noticia-cuerpo">
              <small><?= htmlspecialchars($n['fecha']) ?></small>
              <h3><?= htmlspecialchars($n['titulo']) ?></h3>
              <p><?= htmlspecialchars($n['resumen']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <p><a href="noticias.php">Ver todas las noticias →</a></p>
    </section>

    <!-- Invitación a registrarse -->
    <?php if (!isset($_SESSION['usuario_id'])): ?>
      <section class="invitacion">
        <h2>¿Listo para entrar a la cancha?</h2>
        <p>Crea tu cuenta y gestiona equipos, jugadores y partidos de la liga.</p>
        <a href="registro.php" class="boton">Crear cuenta</a>
        <a href="ingreso.php" class="boton-borde">Iniciar sesión</a>
      </section>
    <?php endif; ?>

  </main>

<?php include 'includes/pie.php'; ?>
