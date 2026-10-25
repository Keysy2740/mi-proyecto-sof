<?php
session_start();
$noticias = require 'includes/noticias_datos.php';
$titulo = 'Noticias';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <div class="titulo-seccion">
      <div class="icono-titulo">📰</div>
      <div class="titulo-texto">
        <h1>Noticias</h1>
        <p>Lo último que pasa en la liga.</p>
      </div>
    </div>

    <div class="rejilla seccion">
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
  </main>
<?php include 'includes/pie.php'; ?>
