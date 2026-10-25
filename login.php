<?php
session_start();
require 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Consulta preparada: evita inyección SQL
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // password_verify compara la contraseña con el hash guardado
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - Liga de Básquetbol</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Iniciar sesión</h1>

  <?php if (isset($_GET['registrado'])): ?>
    <p style="color:green;">Cuenta creada. Ya puedes iniciar sesión.</p>
  <?php endif; ?>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Correo</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
  </form>

  <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
</body>
</html>