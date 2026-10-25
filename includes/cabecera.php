<?php
// Cabecera común de todas las páginas: <head> y barra de navegación.
// Si hay sesión iniciada muestra el menú privado; si no, el menú público.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logueado = isset($_SESSION['usuario_id']);
$titulo   = $titulo ?? 'Liga de Básquetbol';

// Para marcar el enlace de la página actual
$pagina = basename($_SERVER['PHP_SELF']);
$grupos = [
    'equipo_form.php'  => 'equipos.php',
    'jugador_form.php' => 'jugadores.php',
    'partido_form.php' => 'partidos.php',
];
if (isset($grupos[$pagina])) {
    $pagina = $grupos[$pagina];
}

// Crea un enlace del menú; si es la página actual lleva la clase "activo"
function enlaceMenu($archivo, $texto, $pagina)
{
    $clase = ($archivo === $pagina) ? ' class="activo"' : '';
    return '<a href="' . $archivo . '"' . $clase . '>' . $texto . '</a>';
}

// Título grande de cada módulo, con icono, subtítulo y botones a la derecha.
// $icono puede ser un emoji o una imagen: '<img src="img/icono-equipos.png" class="icono-img" alt="">'
function tituloSeccion($icono, $titulo, $subtitulo, $botones = '')
{
    echo '<div class="titulo-seccion">';
    echo '<div class="icono-titulo">' . $icono . '</div>';
    echo '<div class="titulo-texto"><h1>' . htmlspecialchars($titulo) . '</h1>';
    echo '<p>' . htmlspecialchars($subtitulo) . '</p></div>';
    echo '<div class="acciones">' . $botones . '</div>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo) ?> - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Barra de navegación -->
  <header class="barra">
    <div class="barra-interior">
      <a href="<?= $logueado ? 'panel.php' : 'index.php' ?>" class="marca">
        <img src="img/logo.png" alt="Liga de Básquetbol">
      </a>

      <nav class="menu">
        <?php if ($logueado): ?>
          <?= enlaceMenu('panel.php', 'Panel', $pagina) ?>
          <?= enlaceMenu('equipos.php', 'Equipos', $pagina) ?>
          <?= enlaceMenu('jugadores.php', 'Jugadores', $pagina) ?>
          <?= enlaceMenu('partidos.php', 'Partidos', $pagina) ?>
          <?= enlaceMenu('posiciones.php', 'Posiciones', $pagina) ?>
          <a href="salir.php" class="boton-menu">Cerrar sesión</a>
        <?php else: ?>
          <?= enlaceMenu('index.php', 'Inicio', $pagina) ?>
          <?= enlaceMenu('nosotros.php', 'Nosotros', $pagina) ?>
          <?= enlaceMenu('noticias.php', 'Noticias', $pagina) ?>
          <?= enlaceMenu('posiciones.php', 'Posiciones', $pagina) ?>
          <a href="ingreso.php" class="boton-menu">Iniciar sesión</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
