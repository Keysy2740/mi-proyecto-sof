<?php
session_start();
$titulo = 'Nosotros';
include 'includes/cabecera.php';
?>
  <main class="contenedor">
    <div class="titulo-seccion">
      <div class="icono-titulo">🏀</div>
      <div class="titulo-texto">
        <h1>Nosotros</h1>
        <p>Conoce la liga y cómo funciona este sistema.</p>
      </div>
    </div>

       <section class="seccion">
      <div class="nosotros-bloque">
        <div class="tarjeta nosotros-texto">
          <h3>¿Quiénes somos?</h3>
          <p>Somos una liga de básquetbol que organiza equipos, jugadores y partidos en un solo sistema web. Nuestro objetivo es que cualquier persona pueda seguir los resultados y que los organizadores gestionen todo de forma sencilla.</p>
          <p>Creemos en el deporte como un espacio para competir, aprender y compartir. Por eso cada partido registrado actualiza la tabla de posiciones al instante, para que nadie se quede sin saber cómo va la liga.</p>
        </div>
        <div class="nosotros-imagen" style="background-image: url('img/nosotros1.jpg');"></div>
      </div>
    </section>
    <section class="seccion">
      <div class="rejilla">
        <div class="tarjeta">
          <h3>Misión</h3>
          <p>Facilitar la organización de la liga con herramientas claras para registrar equipos, jugadores, partidos y resultados.</p>
        </div>
        <div class="tarjeta">
          <h3>Visión</h3>
          <p>Ser una plataforma de referencia para ligas locales, con información siempre actualizada y accesible.</p>
        </div>
        <div class="tarjeta">
          <h3>Valores</h3>
          <p>Juego limpio, trabajo en equipo y respeto por los jugadores, entrenadores y aficionados.</p>
        </div>
      </div>
    </section>

    <section class="seccion">
      <h2 class="seccion-titulo">Cómo funciona</h2>
      <div class="rejilla">
        <div class="tarjeta"><h3>1. Crea tu cuenta</h3><p>Regístrate e inicia sesión para entrar al panel de gestión.</p></div>
        <div class="tarjeta"><h3>2. Organiza la liga</h3><p>Registra equipos, agrega jugadores y programa los partidos.</p></div>
        <div class="tarjeta"><h3>3. Sigue los resultados</h3><p>La tabla de posiciones se actualiza sola con cada partido finalizado.</p></div>
      </div>
    </section>
  </main>
<?php include 'includes/pie.php'; ?>
