<?php
session_start();
require 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Consulta preparada: evita inyección SQL
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // password_verify compara la contraseña con el hash guardado
    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        header('Location: panel.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
include 'includes/cabecera.php';
?>
  <main class="auth">
    <div class="auth-caja">
      <h1>Iniciar sesión</h1>
      <p class="auth-sub">Entra para gestionar la liga.</p>

      <?php if (isset($_GET['registrado'])): ?>
        <p class="alerta exito">Cuenta creada. Ya puedes iniciar sesión.</p>
      <?php endif; ?>

      <?php if ($error): ?>
        <p class="alerta error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="formulario">
        <div class="campo">
          <label>Correo</label>
          <input type="email" name="correo" required>
        </div>
        <div class="campo">
          <label>Contraseña</label>
          <input type="password" name="contrasena" required>
        </div>
        <button type="submit">Entrar</button>
      </form>

      <p class="auth-pie">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
  </main>
<?php include 'includes/pie.php'; ?>
