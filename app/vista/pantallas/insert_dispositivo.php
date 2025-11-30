<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

if ($_POST) {
    $usuario_id = $_POST['usuario_id'] ?: NULL;
    $mac_address = $_POST['mac_address'];
    $ip_address = $_POST['ip_address'] ?: NULL;
    $estado = $_POST['estado'];
    
    $stmt = $pdo->prepare("INSERT INTO dispositivos (usuario_id, mac_address, ip_address, estado, ultima_conexion) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$usuario_id, $mac_address, $ip_address, $estado])) {
        header("Location: list_dispositivos.php");
        exit;
    }
}

// Obtener usuarios para el select
$usuarios = $pdo->query("SELECT id, nombre FROM usuarios WHERE estado = 'activo'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Dispositivo - Sistema WiFi</title>
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
        
        .form-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
        }
        
        .form-section {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .form-section-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
                    <h2 class="section-title">Registrar Nuevo Dispositivo</h2>
                </div>
                
                <div class="content-card">
                    <form method="POST" class="row g-3">
                        <!-- Información del Dispositivo -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                                    <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                                </svg>
                                Información del Dispositivo
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">MAC Address</label>
                                    <input type="text" name="mac_address" class="form-control" 
                                           placeholder="00:1A:2B:3C:4D:5E" required
                                           pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$">
                                    <div class="form-text">Formato: 00:1A:2B:3C:4D:5E</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">IP Address (Opcional)</label>
                                    <input type="text" name="ip_address" class="form-control" 
                                           placeholder="192.168.1.100"
                                           pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
                                    <div class="form-text">Formato: 192.168.1.100</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="conectado">Conectado</option>
                                        <option value="desconectado">Desconectado</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Asignar a Usuario (Opcional)</label>
                                    <select name="usuario_id" class="form-control">
                                        <option value="">Seleccionar Usuario</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-modern me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                </svg>
                                Registrar Dispositivo
                            </button>
                            <a href="list_dispositivos.php" class="btn btn-secondary">
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