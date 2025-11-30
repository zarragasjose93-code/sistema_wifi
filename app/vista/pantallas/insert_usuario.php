<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

if ($_POST) {
    $nombre = $_POST['nombre'];
    $mac_address = $_POST['mac_address'];
    $tiempo_horas = $_POST['tiempo_horas'];
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, mac_address, tiempo_horas, estado) VALUES (?, ?, ?, 'activo')");
    if ($stmt->execute([$nombre, $mac_address, $tiempo_horas])) {
        header("Location: list_usuario.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">

</head>
<body class="admin-container">
    <?php include '../inc/navbar.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <?php include '../inc/sidebar.php'; ?>
            
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Registrar Nuevo Usuario</h2>
                </div>
                
                <div class="content-card">
                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MAC Address:</label>
                            <input type="text" name="mac_address" class="form-control" placeholder="00:1A:2B:3C:4D:5E" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiempo (horas):</label>
                            <input type="number" name="tiempo_horas" class="form-control" min="1" required>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-modern me-3">Guardar Usuario</button>
                            <a href="list_usuario.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>