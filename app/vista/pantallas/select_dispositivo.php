<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT d.*, u.nombre as usuario_nombre, u.mac_address as usuario_mac 
    FROM dispositivos d 
    LEFT JOIN usuarios u ON d.usuario_id = u.id 
    WHERE d.id = ?
");
$stmt->execute([$id]);
$dispositivo = $stmt->fetch();

if (!$dispositivo) {
    header("Location: list_dispositivos.php");
    exit;
}

// Obtener historial de conexiones
$historial = $pdo->prepare("
    SELECT * FROM conexiones_log 
    WHERE mac_address = ? 
    ORDER BY fecha_conexion DESC 
    LIMIT 10
");
$historial->execute([$dispositivo['mac_address']]);
$conexiones = $historial->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Dispositivo - Sistema WiFi</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">

</head>
<body class="admin-container">
    <?php include '../inc/navbar.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <?php include '../inc/sidebar.php'; ?>
            
            <div class="col-md-9">
                <div class="content-card">
                    <!-- Header del dispositivo -->
                    <div class="device-header">
                        <div class="device-icon-large">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                                <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5h11zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2h-11zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5z"/>
                            </svg>
                        </div>
                        <div class="device-info">
                            <h2>Dispositivo #<?= $dispositivo['id'] ?></h2>
                            <p>MAC Address: <?= $dispositivo['mac_address'] ?></p>
                            <span class="status-badge-large <?= $dispositivo['estado'] == 'conectado' ? 'status-connected' : 'status-disconnected' ?>">
                                <?= ucfirst($dispositivo['estado']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Información del dispositivo -->
                    <div class="info-grid">
                        <!-- Información básica -->
                        <div class="info-card">
                            <h5 class="info-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                Información Básica
                            </h5>
                            <div class="info-item">
                                <div class="info-label">ID del Dispositivo</div>
                                <div class="info-value">#<?= $dispositivo['id'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">MAC Address</div>
                                <div class="info-value"><?= $dispositivo['mac_address'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">IP Address</div>
                                <div class="info-value"><?= $dispositivo['ip_address'] ?: 'No asignada' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Estado Actual</div>
                                <div class="info-value">
                                    <span class="badge bg-<?= $dispositivo['estado'] == 'conectado' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($dispositivo['estado']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del usuario -->
                        <div class="info-card">
                            <h5 class="info-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                </svg>
                                Información del Usuario
                            </h5>
                            <?php if ($dispositivo['usuario_nombre']): ?>
                                <div class="info-item">
                                    <div class="info-label">Usuario Asignado</div>
                                    <div class="info-value"><?= $dispositivo['usuario_nombre'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">MAC del Usuario</div>
                                    <div class="info-value"><?= $dispositivo['usuario_mac'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">ID del Usuario</div>
                                    <div class="info-value">#<?= $dispositivo['usuario_id'] ?></div>
                                </div>
                            <?php else: ?>
                                <div class="info-item">
                                    <div class="info-value text-muted">No asignado a ningún usuario</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Información de conexión -->
                        <div class="info-card">
                            <h5 class="info-card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                                Información de Conexión
                            </h5>
                            <div class="info-item">
                                <div class="info-label">Última Conexión</div>
                                <div class="info-value"><?= $dispositivo['ultima_conexion'] ?: 'Nunca conectado' ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Registros de Conexión</div>
                                <div class="info-value"><?= count($conexiones) ?> registros</div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de conexiones -->
                    <div class="mt-5">
                        <h4 class="section-title">Historial de Conexiones</h4>
                        
                        <?php if (count($conexiones) > 0): ?>
                            <div class="table-responsive">
                                <table class="table historial-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha Conexión</th>
                                            <th>Fecha Desconexión</th>
                                            <th>Duración (min)</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($conexiones as $conexion): ?>
                                            <tr>
                                                <td><?= $conexion['fecha_conexion'] ?></td>
                                                <td><?= $conexion['fecha_desconexion'] ?: 'En curso...' ?></td>
                                                <td><?= $conexion['duracion_minutos'] ?: 'N/A' ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $conexion['fecha_desconexion'] ? 'secondary' : 'success' ?>">
                                                        <?= $conexion['fecha_desconexion'] ? 'Completada' : 'Activa' ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                                </svg>
                                <p>No hay registros de conexión para este dispositivo</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Botones de acción -->
                    <div class="action-buttons">
                        <a href="edit_dispositivo.php?id=<?= $dispositivo['id'] ?>" class="btn btn-modern">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                            Editar Dispositivo
                        </a>
                        <a href="list_dispositivos.php" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                            Volver a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>