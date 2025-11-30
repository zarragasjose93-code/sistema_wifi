<?php
session_start();
require '../../modelo/conexion.php';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM administradores WHERE username = ? AND password = ?");
    $stmt->execute([$username, md5($password)]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: home.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">

</head>
<body>
    <a href="../../../index.php" class="btn btn-modern back-btn">
        ← Volver
    </a>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">Sistema WiFi</h2>
                <p class="text-white-50">Ingresa a tu cuenta</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-modern">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-modern w-100">Ingresar</button>
            </form>
        </div>
    </div>
</body>
</html>