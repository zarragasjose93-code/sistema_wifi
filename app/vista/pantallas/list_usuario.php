<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: list_usuario.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - Sistema WiFi</title>
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
                    <h2 class="section-title">Gestionar Usuarios</h2>
                    <a href="insert_usuario.php" class="btn btn-modern">+ Nuevo Usuario</a>
                </div>
                
                <div class="content-card">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>MAC Address</th>
                                    <th>Tiempo Asignado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM usuarios");
                                while ($usuario = $stmt->fetch()):
                                ?>
                                <tr>
                                    <td><?= $usuario['id'] ?></td>
                                    <td><?= $usuario['nombre'] ?></td>
                                    <td><?= $usuario['mac_address'] ?></td>
                                    <td><?= $usuario['tiempo_horas'] ?> horas</td>
                                    <td>
                                        <span class="badge bg-<?= $usuario['estado'] == 'activo' ? 'success' : 'secondary' ?>">
                                            <?= $usuario['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="select_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-info action-btn">Ver</a>
                                        <a href="edit_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning action-btn">Editar</a>
                                        <a href="list_usuario.php?eliminar=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
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