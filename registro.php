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
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Crear cuenta</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Nombre</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Correo</label><br>
    <input type="email" name="correo" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="contrasena" required><br><br>

    <button type="submit">Registrarme</button>
  </form>

  <p>¿Ya tienes cuenta? <a href="ingreso.php">Inicia sesión</a></p>
</body>
</html>