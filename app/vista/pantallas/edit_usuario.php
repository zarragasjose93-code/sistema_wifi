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

if (!$usuario) {
    header("Location: list_usuario.php");
    exit;
}

if ($_POST) {
    $nombre = $_POST['nombre'];
    $mac_address = $_POST['mac_address'];
    $tiempo_horas = $_POST['tiempo_horas'];
    $estado = $_POST['estado'];
    
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, mac_address = ?, tiempo_horas = ?, estado = ? WHERE id = ?");
    if ($stmt->execute([$nombre, $mac_address, $tiempo_horas, $estado, $id])) {
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
    <title>Editar Usuario - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .admin-container {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
        }
        
        .content-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25);
        }
        
        .form-label {
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .user-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .user-info h3 {
            margin: 0;
            color: white;
        }
        
        .user-info p {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body class="admin-container">
    <?php include '../inc/navbar.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <?php include '../inc/sidebar.php'; ?>
            
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Editar Usuario</h2>
                </div>
                
                <div class="content-card">
                    <!-- Header del usuario -->
                    <div class="user-header">
                        <div class="user-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </div>
                        <div class="user-info">
                            <h3><?= htmlspecialchars($usuario['nombre']) ?></h3>
                            <p>ID: <?= $usuario['id'] ?> | Registrado: <?= $usuario['fecha_registro'] ?></p>
                        </div>
                    </div>
                    
                    <form method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" 
                                   value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MAC Address</label>
                            <input type="text" name="mac_address" class="form-control" 
                                   value="<?= htmlspecialchars($usuario['mac_address']) ?>" 
                                   placeholder="00:1A:2B:3C:4D:5E" required
                                   pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiempo Asignado (horas)</label>
                            <input type="number" name="tiempo_horas" class="form-control" 
                                   value="<?= $usuario['tiempo_horas'] ?>" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-control" required>
                                <option value="activo" <?= $usuario['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="bloqueado" <?= $usuario['estado'] == 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
                            </select>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-modern me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                </svg>
                                Actualizar Usuario
                            </button>
                            <a href="list_usuario.php" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>