<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require '../../modelo/conexion.php';

// Procesar cambios de configuración
if ($_POST) {
    // Aquí procesarías los cambios de configuración
    $mensaje = "Configuración actualizada correctamente";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Sistema WiFi</title>
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
        
        .config-section {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .config-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.2);
            transition: .4s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(30px);
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
                    <h2 class="section-title">Configuración del Sistema</h2>
                </div>
                
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-success alert-modern mb-4">
                        <?= $mensaje ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="content-card">
                        <!-- Configuración de Red -->
                        <div class="config-section">
                            <h4 class="config-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-wifi" viewBox="0 0 16 16">
                                    <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
                                    <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
                                </svg>
                                Configuración de Red
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">SSID de la Red</label>
                                    <input type="text" name="ssid" class="form-control" value="WiFi-Sistema" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contraseña WiFi</label>
                                    <input type="password" name="wifi_password" class="form-control" value="********">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Canal WiFi</label>
                                    <select name="canal" class="form-control">
                                        <option value="1">Canal 1</option>
                                        <option value="6" selected>Canal 6</option>
                                        <option value="11">Canal 11</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ancho de Banda</label>
                                    <select name="ancho_banda" class="form-control">
                                        <option value="20">20 MHz</option>
                                        <option value="40" selected>40 MHz</option>
                                        <option value="80">80 MHz</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Potencia de Transmisión</label>
                                    <select name="potencia" class="form-control">
                                        <option value="low">Baja</option>
                                        <option value="medium" selected>Media</option>
                                        <option value="high">Alta</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Configuración de Seguridad -->
                        <div class="config-section">
                            <h4 class="config-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                                    <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z"/>
                                </svg>
                                Configuración de Seguridad
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de Encriptación</label>
                                    <select name="encriptacion" class="form-control">
                                        <option value="wpa2">WPA2-Personal</option>
                                        <option value="wpa3" selected>WPA3-Personal</option>
                                        <option value="wpa2_enterprise">WPA2-Enterprise</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Puerto de Administración</label>
                                    <input type="number" name="puerto_admin" class="form-control" value="8080" min="1" max="65535">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Firewall</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="firewall" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="text-white">Activado</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Filtrado MAC</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="filtrado_mac" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="text-white">Activado</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Aislamiento Clientes</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="aislamiento">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="text-white">Desactivado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Configuración de Sistema -->
                        <div class="config-section">
                            <h4 class="config-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                                </svg>
                                Configuración del Sistema
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tiempo de Sesión (minutos)</label>
                                    <input type="number" name="tiempo_sesion" class="form-control" value="60" min="1">
                                    <div class="form-text">Tiempo máximo de inactividad antes de cerrar sesión</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Límite de Dispositivos por Usuario</label>
                                    <input type="number" name="limite_dispositivos" class="form-control" value="5" min="1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Backup Automático</label>
                                    <select name="backup_auto" class="form-control">
                                        <option value="diario">Diario</option>
                                        <option value="semanal" selected>Semanal</option>
                                        <option value="mensual">Mensual</option>
                                        <option value="desactivado">Desactivado</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Notificaciones por Email</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="notificaciones_email" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="text-white">Activado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-modern me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                    </svg>
                                    Guardar Configuración
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                    </svg>
                                    Restablecer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>