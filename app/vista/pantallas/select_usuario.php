<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Usuario - Sistema WiFi</title>
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
                    <h2 class="section-title">Información del Usuario</h2>
                </div>
                
                <div class="mt-4">
                    <a href="list_usuario.php" class="btn btn-modern">← Volver a la lista</a>
                </div>
                <br>

                
                <div class="content-card">
                     <div class="info-card">
                            <h5 class="info-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                </svg>
                                Información del Usuario
                            </h5>
                            <?php if ($usuario['id']): ?>
                                <div class="info-item">
                                    <div class="info-label">ID</div>
                                    <div class="info-value"><?= $usuario['id'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Nombre</div>
                                    <div class="info-value"><?= $usuario['nombre'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">MAC Address</div>
                                    <div class="info-value">#<?= $usuario['mac_address'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Tiempo Asignado</div>
                                    <div class="info-value">#<?= $usuario['tiempo_horas'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value">#<span class="badge bg-<?= $usuario['estado'] == 'activo' ? 'success' : 'secondary' ?>">
                                        <?= $usuario['estado'] ?>
                                    </span></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fecha Registro</div>
                                    <div class="info-value">#<?= $usuario['fecha_registro'] ?></div>
                                </div>
                            <?php else: ?>
                                <div class="info-item">
                                    <div class="info-value text-muted">No asignado a ningún usuario</div>
                                </div>
                            <?php endif; ?>
                        
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</body>
</html>