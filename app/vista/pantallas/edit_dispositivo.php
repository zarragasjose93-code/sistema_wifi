<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT d.*, u.nombre as usuario_nombre FROM dispositivos d LEFT JOIN usuarios u ON d.usuario_id = u.id WHERE d.id = ?");
$stmt->execute([$id]);
$dispositivo = $stmt->fetch();

if (!$dispositivo) {
    header("Location: list_dispositivos.php");
    exit;
}

if ($_POST) {
    $usuario_id = $_POST['usuario_id'] ?: NULL;
    $mac_address = $_POST['mac_address'];
    $ip_address = $_POST['ip_address'] ?: NULL;
    $estado = $_POST['estado'];
    
    $stmt = $pdo->prepare("UPDATE dispositivos SET usuario_id = ?, mac_address = ?, ip_address = ?, estado = ? WHERE id = ?");
    if ($stmt->execute([$usuario_id, $mac_address, $ip_address, $estado, $id])) {
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
    <title>Editar Dispositivo - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .admin-container {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
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
                    <h2 class="section-title">Editar Dispositivo</h2>
                </div>
                
                <div class="content-card">
                    <!-- Header del dispositivo -->
                    <div class="device-header">
                        <div class="device-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                                <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                            </svg>
                        </div>
                        <div class="device-info">
                            <h3>Dispositivo #<?= $dispositivo['id'] ?></h3>
                            <p>MAC: <?= $dispositivo['mac_address'] ?> | 
                                <span class="status-badge <?= $dispositivo['estado'] == 'conectado' ? 'status-connected' : 'status-disconnected' ?>">
                                    <?= ucfirst($dispositivo['estado']) ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Información actual -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Usuario Asignado</div>
                            <div class="info-value"><?= $dispositivo['usuario_nombre'] ?: 'No asignado' ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">IP Address</div>
                            <div class="info-value"><?= $dispositivo['ip_address'] ?: 'No asignada' ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Última Conexión</div>
                            <div class="info-value"><?= $dispositivo['ultima_conexion'] ?: 'Nunca' ?></div>
                        </div>
                    </div>
                    
                    <form method="POST" class="row g-3">
                        <!-- Información del Dispositivo -->
                        <div class="form-section">
                            <h5 class="form-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                                Editar Información del Dispositivo
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">MAC Address</label>
                                    <input type="text" name="mac_address" class="form-control" 
                                           value="<?= htmlspecialchars($dispositivo['mac_address']) ?>" 
                                           placeholder="00:1A:2B:3C:4D:5E" required
                                           pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$">
                                    <div class="form-text">Formato: 00:1A:2B:3C:4D:5E</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">IP Address (Opcional)</label>
                                    <input type="text" name="ip_address" class="form-control" 
                                           value="<?= htmlspecialchars($dispositivo['ip_address'] ?: '') ?>"
                                           placeholder="192.168.1.100"
                                           pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
                                    <div class="form-text">Formato: 192.168.1.100</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="conectado" <?= $dispositivo['estado'] == 'conectado' ? 'selected' : '' ?>>Conectado</option>
                                        <option value="desconectado" <?= $dispositivo['estado'] == 'desconectado' ? 'selected' : '' ?>>Desconectado</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Asignar a Usuario (Opcional)</label>
                                    <select name="usuario_id" class="form-control">
                                        <option value="">Seleccionar Usuario</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?= $usuario['id'] ?>" 
                                                <?= $dispositivo['usuario_id'] == $usuario['id'] ? 'selected' : '' ?>>
                                                <?= $usuario['nombre'] ?>
                                            </option>
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
                                Actualizar Dispositivo
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