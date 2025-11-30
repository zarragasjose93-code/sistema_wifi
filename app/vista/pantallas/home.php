<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
 
</head>
<body class="admin-container">
    <?php include '../inc/navbar.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <?php include '../inc/sidebar.php'; ?>
            
            <div class="col-md-9">
                <h2 class="section-title">Dashboard</h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="stats-card stats-card-primary">
                            <h5 class="stats-title">Usuarios Activos</h5>
                            <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'");
                            echo "<div class='stats-number'>" . $stmt->fetchColumn() . "</div>";
                            ?>
                            <p class="text-white-50 mb-0">Usuarios conectados al sistema</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="stats-card stats-card-success">
                            <h5 class="stats-title">Dispositivos Conectados</h5>
                            <?php
                            $stmt = $pdo->query("SELECT COUNT(*) FROM dispositivos WHERE estado = 'conectado'");
                            echo "<div class='stats-number'>" . $stmt->fetchColumn() . "</div>";
                            ?>
                            <p class="text-white-50 mb-0">Dispositivos en la red</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>