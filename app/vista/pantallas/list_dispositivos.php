<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

// Eliminar dispositivo
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM dispositivos WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: list_dispositivos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispositivos Conectados - Sistema WiFi</title>
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
        
        .table-modern {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table-modern thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table-modern tbody td {
            background: rgba(255, 255, 255, 0.02);
            border-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table-modern tbody tr:hover td {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .action-btn {
            margin: 0 2px;
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-color);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #8f94fb, #4e54c8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
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
                    <h2 class="section-title">Dispositivos Conectados</h2>
                    <a href="insert_dispositivo.php" class="btn btn-modern">+ Nuevo Dispositivo</a>
                </div>
                
                <!-- Estadísticas -->
                <div class="stats-grid">
                    <?php
                    $total = $pdo->query("SELECT COUNT(*) FROM dispositivos")->fetchColumn();
                    $conectados = $pdo->query("SELECT COUNT(*) FROM dispositivos WHERE estado = 'conectado'")->fetchColumn();
                    $desconectados = $pdo->query("SELECT COUNT(*) FROM dispositivos WHERE estado = 'desconectado'")->fetchColumn();
                    ?>
                    <div class="stat-card">
                        <div class="stat-number"><?= $total ?></div>
                        <div class="stat-label">Total Dispositivos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-success"><?= $conectados ?></div>
                        <div class="stat-label">Conectados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-warning"><?= $desconectados ?></div>
                        <div class="stat-label">Desconectados</div>
                    </div>
                </div>
                
                <div class="content-card">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>MAC Address</th>
                                    <th>IP Address</th>
                                    <th>Estado</th>
                                    <th>Última Conexión</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("
                                    SELECT d.*, u.nombre as usuario_nombre 
                                    FROM dispositivos d 
                                    LEFT JOIN usuarios u ON d.usuario_id = u.id 
                                    ORDER BY d.ultima_conexion DESC
                                ");
                                while ($dispositivo = $stmt->fetch()):
                                ?>
                                <tr>
                                    <td><?= $dispositivo['id'] ?></td>
                                    <td>
                                        <?= $dispositivo['usuario_nombre'] ?: 
                                            '<span class="text-muted">Sin usuario</span>' ?>
                                    </td>
                                    <td><?= $dispositivo['mac_address'] ?></td>
                                    <td><?= $dispositivo['ip_address'] ?: 
                                            '<span class="text-muted">No asignada</span>' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $dispositivo['estado'] == 'conectado' ? 'success' : 'secondary' ?>">
                                            <?= $dispositivo['estado'] ?>
                                        </span>
                                    </td>
                                    <td><?= $dispositivo['ultima_conexion'] ?: 
                                            '<span class="text-muted">Nunca</span>' ?></td>
                                    <td>
                                        <a href="select_dispositivo.php?id=<?= $dispositivo['id'] ?>" 
                                           class="btn btn-sm btn-info action-btn">Ver</a>
                                        <a href="edit_dispositivo.php?id=<?= $dispositivo['id'] ?>" 
                                           class="btn btn-sm btn-warning action-btn">Editar</a>
                                        <a href="list_dispositivos.php?eliminar=<?= $dispositivo['id'] ?>" 
                                           class="btn btn-sm btn-danger action-btn" 
                                           onclick="return confirm('¿Eliminar dispositivo?')">Eliminar</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>