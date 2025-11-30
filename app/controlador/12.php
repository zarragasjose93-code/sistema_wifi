<?php
session_start();
include("../modelo/conexion.php");
include("../modelo/clase_usuario.php");

$usu = new Usuario();

############################################################################
### REGISTRAR ##############################################################
############################################################################
if(isset($_POST['registrar']) && $_POST['registrar']=="registrar"){
    // Obtener el último ID disponible
    $ultimo_id = $usu->ObtenerUltimoId();
    $usu->setId($ultimo_id);
    $usu->setNombreUsu($_POST['nombre']);
    $usu->setClave($_POST['pass']);
    $estado="A";
    $usu->setEstado($estado);
    $usu->setIdRol($_POST['id_rol']);
    $usu->setCiPer($_POST['cedula']);

    $datos = $usu->RegistrarUsuario($usu->getId(), $usu->getNombreUsu(), $usu->getClave(), $usu->getEstado(), $usu->getIdRol(), $usu->getCiPer()); 

    if(empty($datos)){
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo registrar los datos. Posiblemente la cédula ya existe.'];
        header("Location: ../vista/pantallas/usuarios/insert_usu.php");
        exit;
    }else{    
        $_SESSION['flash'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Usuario Registrado con éxito.'];
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    }   
}

############################################################################
### LISTAR #################################################################
############################################################################
if(isset($_GET['L']) && $_GET['L']=="lisusu"){
    $datos = $usu->ListarUsuario();

    if(empty($datos)){
        $_SESSION['flash'] = ['icon' => 'info', 'title' => 'Información', 'text' => 'No hay usuarios registrados.'];
        header("Location: ../vista/pantallas/usuarios/insert_usu.php");
        exit;
    }else{
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    }
}

#############################################################################
### CONSULTAR ###############################################################
#############################################################################
if(isset($_GET['C']) && $_GET['C']=="conusu"){
    $ci_per=base64_decode($_GET['I']);
    $usu->setCiPer($ci_per);

    $datos = $usu->ConsultarUsuario($usu->getCiPer());
    
    if(!empty($datos)){
        $id=base64_encode($datos[0]);
        $nombre_usu_enc=base64_encode($datos[1]);
        $clave=base64_encode($datos[2]);
        $estado=base64_encode($datos[3]);
        $id_rol=base64_encode($datos[4]);
        $ci_per=base64_encode($datos[5]);
        $nombre=base64_encode($datos[6]);
        $apellido=base64_encode($datos[7]);
        $nombre_rol=base64_encode($datos[8]);

        header("Location: ../vista/pantallas/usuarios/select_usu.php?a=$id&b=$nombre_usu_enc&c=$clave&d=$estado&e=$id_rol&f=$ci_per&g=$nombre&h=$apellido&i=$nombre_rol");
        exit;
    } else {
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Usuario no encontrado.'];
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    }
}

#############################################################################
### MOSTRAR PARA MODIFICAR ##################################################
#############################################################################
if(isset($_GET['M']) && $_GET['M']=="mosusu"){
    $ci_per=base64_decode($_GET['I']);
    $usu->setCiPer($ci_per);

    $datos = $usu->ConsultarUsuario($usu->getCiPer());
    
    if(!empty($datos)){
        $id=base64_encode($datos[0]);
        $nombre_usu_enc=base64_encode($datos[1]);
        $clave=base64_encode($datos[2]);
        $estado=base64_encode($datos[3]);
        $id_rol=base64_encode($datos[4]);
        $ci_per=base64_encode($datos[5]);
        $nombre=base64_encode($datos[6]);
        $apellido=base64_encode($datos[7]);
        $nombre_rol=base64_encode($datos[8]);

        header("Location: ../vista/pantallas/usuarios/update_usu.php?a=$id&b=$nombre_usu_enc&c=$clave&d=$estado&e=$id_rol&f=$ci_per&g=$nombre&h=$apellido&i=$nombre_rol");
        exit;
    } else {
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Usuario no encontrado.'];
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    }
}

############################################################################
### ACTUALIZAR #############################################################
############################################################################
if(isset($_POST['modificar']) && $_POST['modificar']=="modificar"){
    $usu->setId($_POST['id_u']);
    $usu->setEstado($_POST['estado']);
    $usu->setIdRol($_POST['id_rol']);

    $datos = $usu->ActualizarUsuario($usu->getId(), $usu->getEstado(), $usu->getIdRol());
    
    if($datos){
        $_SESSION['flash'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Datos actualizados con éxito.'];
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    } else {
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo actualizar los datos.'];
        header("Location: ../vista/pantallas/usuarios/usuario.php");
        exit;
    }
}

############################################################################
### INICIAR SESION #########################################################
############################################################################
if(isset($_POST['aceptar']) && $_POST['aceptar']=="aceptar"){
    $usu->setNombreUsu($_POST['nombre']);
    $usu->setClave($_POST['pass']);

    $datos = $usu->IniciarSesion($usu->getNombreUsu(), $usu->getClave());
    
    if(!empty($datos) && is_array($datos)){
        $_SESSION['registro'] = $datos;
        $_SESSION['id_ses'] = $datos[0];
        $_SESSION['usu_ses'] = $datos[1];
        $_SESSION['cla_ses'] = $datos[2];
        $_SESSION['est_ses'] = $datos[3];
        $_SESSION['id_rol_ses'] = $datos[4];
        $_SESSION['ci_ses'] = $datos[5];
        $_SESSION['nom_ses'] = $datos[6];
        $_SESSION['ape_ses'] = $datos[7];
        $_SESSION['nombre_rol_ses'] = $datos[8];
        
        // Obtener permisos del usuario
        $permisos_usuario = $usu->ObtenerPermisosUsuario($_SESSION['id_ses']);
        $_SESSION['permisos_usuario'] = $permisos_usuario;

        if($_SESSION['est_ses'] == 'A'){
            $nombreShow = !empty($_SESSION['nom_ses']) ? $_SESSION['nom_ses'] : $_SESSION['usu_ses'];
            $apellidoShow = !empty($_SESSION['ape_ses']) ? $_SESSION['ape_ses'] : '';
            $_SESSION['flash'] = ['icon' => 'success', 'title' => 'BIENVENIDO', 'text' => "$nombreShow $apellidoShow"];
            header("Location: ../vista/pantallas/home.php");
            exit;
        }elseif($_SESSION['est_ses'] == 'I'){
            $_SESSION['flash'] = ['icon' => 'warning', 'title' => 'Usuario Inactivo', 'text' => 'Usuario INACTIVO... Consulte al ADMINISTRADOR del sistema.'];
            header("Location: ../index.php");
            exit;
        }
    }else{
        // Para depuración - mostrar qué está fallando
        error_log("Inicio de sesión fallido para usuario: " . $_POST['nombre']);
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Usuario o contraseña incorrectos... Verifique los datos.'];
        header("Location: ../index.php");
        exit;
    }
}

############################################################################
### CAMBIAR CREDENCIALES ###################################################
############################################################################
if(isset($_POST['cambiar']) && $_POST['cambiar']=="cambiar"){
    $usu->setId($_POST['id']);
    $usu->setNombreUsu($_POST['nombre']);
    $usu->setClave($_POST['pass']);

    $datos = $usu->CambiarUsuario($usu->getId(), $usu->getNombreUsu(), $usu->getClave());
    
    if($datos){
        $_SESSION['flash'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Datos actualizados con éxito.'];
        header("Location: ../vista/pantallas/home.php");
        exit;
    }else {
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo actualizar los datos.'];
        header("Location: ../vista/pantallas/home.php");
        exit;
    }
}

############################################################################
### ELIMINAR ###############################################################
############################################################################
if(isset($_GET['E']) && $_GET['E']=="elimusu"){
    $id = base64_decode($_GET['I']);
    
    $datos = $usu->EliminarUsuario($id);
    
    if($datos){
        $_SESSION['flash'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Usuario eliminado con éxito.'];
    } else {
        $_SESSION['flash'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo eliminar el usuario.'];
    }
    
    header("Location: ../vista/pantallas/usuarios/usuario.php");
    exit;
}

// Redirección por defecto si no se especifica acción
header("Location: ../index.php");
exit;
?>