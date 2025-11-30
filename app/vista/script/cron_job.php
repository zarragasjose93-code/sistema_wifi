<?php
require 'database.php';
require 'router_control.php';

$router = new MercusysControl();

// Verificar usuarios que han excedido su tiempo
$stmt = $pdo->query("SELECT * FROM usuarios WHERE estado = 'activo'");
while ($usuario = $stmt->fetch()) {
    $tiempo_usado = calcularTiempoUsado($usuario['id']);
    
    if ($tiempo_usado >= $usuario['tiempo_horas']) {
        // Bloquear dispositivo en el router
        $router->bloquearMAC($usuario['mac_address']);
        
        // Actualizar estado en BD
        $update = $pdo->prepare("UPDATE usuarios SET estado = 'bloqueado' WHERE id = ?");
        $update->execute([$usuario['id']]);
    }
}

function calcularTiempoUsado($usuario_id) {
    // Lógica para calcular tiempo de conexión usado
    // Esto podría integrarse con logs del router
    return 0; // Implementar según necesidades
}
?>