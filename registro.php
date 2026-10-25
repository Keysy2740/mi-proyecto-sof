<?php
session_start();
require 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre']);
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    if ($nombre === '' || $correo === '' || strlen($contrasena) < 6) {
        $error = 'Completa todos los campos. La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Consulta preparada: evita inyección SQL
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]);

        if ($stmt->fetch()) {
            $error = 'Ese correo ya está registrado.';
        } else {
            // La contraseña se guarda cifrada, nunca en texto plano
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)');
            $stmt->execute([$nombre, $correo, $hash]);

            header('Location: ingreso.php?registrado=1');
            exit;
        }
    }
}

$titulo = 'Crear cuenta';
include 'includes/cabecera.php';
?>
  <main class="auth">
    <div class="auth-caja">
      <h1>Crear cuenta</h1>
      <p class="auth-sub">Regístrate para gestionar la liga.</p>

      <?php if ($error): ?>
        <p class="alerta error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="formulario">
        <div class="campo">
          <label>Nombre</label>
          <input type="text" name="nombre" required>
        </div>
        <div class="campo">
          <label>Correo</label>
          <input type="email" name="correo" required>
        </div>
        <div class="campo">
          <label>Contraseña (mínimo 6 caracteres)</label>
          <input type="password" name="contrasena" required>
        </div>
        <button type="submit">Registrarme</button>
      </form>

      <p class="auth-pie">¿Ya tienes cuenta? <a href="ingreso.php">Inicia sesión</a></p>
    </div>
  </main>
<?php include 'includes/pie.php'; ?>
