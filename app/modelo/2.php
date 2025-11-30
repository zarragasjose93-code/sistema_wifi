<?php
class Usuario {
    public $id, $nombre_usu, $clave, $estado, $id_rol, $ci_per;

    // Setters y Getters
    public function setId($id){ $this->id = $id; }
    public function setNombreUsu($nombre_usu){ $this->nombre_usu = $nombre_usu; }
    public function setClave($clave){ $this->clave = $clave; }
    public function setEstado($estado){ $this->estado = $estado; }
    public function setIdRol($id_rol){ $this->id_rol = $id_rol; }
    public function setCiPer($ci_per){ $this->ci_per = $ci_per; }
    
    public function getId(){ return $this->id; }
    public function getNombreUsu(){ return $this->nombre_usu; }
    public function getClave(){ return $this->clave; }
    public function getEstado(){ return $this->estado; }
    public function getIdRol(){ return $this->id_rol; }
    public function getCiPer(){ return $this->ci_per; }

    // Función para encriptar contraseña
    private function encriptarPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Función para verificar contraseña
    private function verificarPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    ############################################################################
    ### REGISTRAR EN BITÁCORA #################################################
    ############################################################################
    private function registrarBitacora($accion, $descripcion, $usuario_id = null) {
        include("bitacora.php");
        return Bitacora::registrar($accion, $descripcion, $usuario_id);
    }

    ############################################################################
    ### REGISTRAR ##############################################################
    ############################################################################
    public function RegistrarUsuario($id, $nombre_usu, $clave, $estado, $id_rol, $ci_per){
        // Se llama al archivo para la conexion
        include("conexion.php");

        // Encriptar la contraseña
        $clave_encriptada = $this->encriptarPassword($clave);

        // Sentencia sql para Consultar
        $sql = $conex->prepare("SELECT * FROM usuario WHERE ci_per = ?;");
        $sql->execute([$ci_per]);
        $num = $sql->rowCount();
        
        // Si NO existe un registro
        if (!$num){
            // Sentencia sql para Registrar 
            $sql = $conex->prepare("INSERT INTO usuario (id, nombre_usu, clave, estado, id_rol, ci_per) VALUES (?, ?, ?, ?, ?, ?);");
            $insertar = $sql->execute([$id, $nombre_usu, $clave_encriptada, $estado, $id_rol, $ci_per]); 
            
             if($insertar){
                $this->registrarBitacora("REGISTRO_USUARIO", "Se registró nuevo usuario: $nombre_usu - Cédula: $ci_per");
            }
            
            return $insertar;
        }else{
            return false;
        }
    }

    #############################################################################
    ### LISTAR ##################################################################
    #############################################################################
    public function ListarUsuario(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT u.id, u.nombre_usu, u.estado, u.id_rol, u.ci_per, 
                               p.nombre, p.apellido, r.nombre_rol 
                               FROM usuario u 
                               LEFT JOIN persona p ON u.ci_per = p.cedula 
                               LEFT JOIN roles r ON u.id_rol = r.id_rol
                               ORDER BY u.id ASC;");
        $sql->execute();
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    #############################################################################
    ### CONSULTAR ###############################################################
    #############################################################################
    public function ConsultarUsuario($ci_per){
        include("conexion.php");
        $sql = $conex->prepare("SELECT u.id, u.nombre_usu, u.clave, u.estado, u.id_rol, u.ci_per, 
                               p.nombre, p.apellido, r.nombre_rol 
                               FROM usuario u 
                               INNER JOIN persona p ON u.ci_per = p.cedula 
                               LEFT JOIN roles r ON u.id_rol = r.id_rol
                               WHERE u.ci_per = ? LIMIT 1;");
        $sql->execute([$ci_per]);
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            $registro = [
                $data['id'],
                $data['nombre_usu'],
                $data['clave'],
                $data['estado'],
                $data['id_rol'],
                $data['ci_per'],
                $data['nombre'],
                $data['apellido'],
                $data['nombre_rol']
            ];
            return $registro;
        }
        return [];
    }
    
    ############################################################################
    ### MOSTRAR ################################################################
    ############################################################################
    public function MostrarUsuario($ci_per){
        return $this->ConsultarUsuario($ci_per);
    }

    ############################################################################
    ### ACTUALIZAR #############################################################
    ############################################################################
    public function ActualizarUsuario($id, $estado, $id_rol){
        include("conexion.php");
        $sql = $conex->prepare("UPDATE usuario SET estado = ?, id_rol = ? WHERE id = ?;");
        $actualizar = $sql->execute([$estado, $id_rol, $id]); 
        
        if($actualizar){
            $this->registrarBitacora("ACTUALIZAR_USUARIO", "Se actualizó usuario ID: $id - Estado: $estado - Rol: $id_rol");
        }
        
        return $actualizar;
    }

    #############################################################################
    ### INICIAR SESION ##########################################################
    #############################################################################
    public function IniciarSesion($nombre_usu, $clave){
        include("conexion.php");
        
        try {
            $sql = $conex->prepare("SELECT u.*, p.nombre, p.apellido, r.nombre_rol 
                                  FROM usuario u 
                                  INNER JOIN persona p ON u.ci_per = p.cedula 
                                  LEFT JOIN roles r ON u.id_rol = r.id_rol
                                  WHERE u.nombre_usu = ?;");
            $sql->execute([$nombre_usu]);
            $num_reg = $sql->rowCount();
            $data = $sql->fetch(PDO::FETCH_ASSOC);

            if ($num_reg > 0) {
                // Verificar si la contraseña coincide
                if ($this->verificarPassword($clave, $data['clave'])) {
                    // Verificar si el usuario está activo
                    if ($data['estado'] === 'A') {
                        $registro = [
                            $data['id'],
                            $data['nombre_usu'],
                            $data['clave'],
                            $data['estado'],
                            $data['id_rol'],
                            $data['ci_per'],
                            $data['nombre'],
                            $data['apellido'],
                            $data['nombre_rol']
                        ];
                        
                        // Registrar inicio de sesión en bitácora
                        $this->registrarBitacora("INICIO_SESION", "Usuario {$data['nombre_usu']} ({$data['nombre']} {$data['apellido']}) inició sesión", $data['id']);
                        
                        return $registro;
                    } else {
                        $this->registrarBitacora("INTENTO_FALLIDO", "Intento fallido de inicio de sesión para usuario: $nombre_usu - Usuario inactivo", $data['id']);
                        error_log("Usuario inactivo: $nombre_usu");
                        return -1; // Código para usuario inactivo
                    }
                } else {
                    $this->registrarBitacora("INTENTO_FALLIDO", "Intento fallido de inicio de sesión para usuario: $nombre_usu - Contraseña incorrecta", $data['id']);
                    error_log("Contraseña incorrecta para usuario: $nombre_usu");
                    return 0;
                }
            } else {
                $this->registrarBitacora("INTENTO_FALLIDO", "Intento fallido de inicio de sesión para usuario: $nombre_usu - Usuario no encontrado");
                error_log("Usuario no encontrado: $nombre_usu");
                return 0;
            }
        } catch (PDOException $e) {
            error_log("Error en inicio de sesión: " . $e->getMessage());
            return 0;
        }
    }

    ############################################################################
    ### CAMBIAR CREDENCIALES ###################################################
    ############################################################################
    public function CambiarUsuario($id, $nombre_usu, $clave){
        include("conexion.php");
        
        $clave_encriptada = $this->encriptarPassword($clave);
        
        $sql = $conex->prepare("UPDATE usuario SET nombre_usu = ?, clave = ? WHERE id = ?;");
        $actualizar = $sql->execute([$nombre_usu, $clave_encriptada, $id]); 
        
        if ($actualizar) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usu_ses'] = $nombre_usu;
            
            // Registrar cambio en bitácora
            $this->registrarBitacora("CAMBIAR_CREDENCIALES", "Usuario ID: $id cambió sus credenciales");
        }

        
        return $actualizar;
    }

    ############################################################################
    ### ELIMINAR ###############################################################
    ############################################################################
    public function EliminarUsuario($id){
        include("conexion.php");
        
        // Obtener datos del usuario antes de eliminar para la bitácora
        $sql_info = $conex->prepare("SELECT nombre_usu, ci_per FROM usuario WHERE id = ?");
        $sql_info->execute([$id]);
        $info_usuario = $sql_info->fetch(PDO::FETCH_ASSOC);
        
        $sql = $conex->prepare("DELETE FROM usuario WHERE id = ?;");
        $eliminar = $sql->execute([$id]); 
        
        if($eliminar && $info_usuario){
            $this->registrarBitacora("ELIMINAR_USUARIO", "Se eliminó usuario: {$info_usuario['nombre_usu']} - Cédula: {$info_usuario['ci_per']}");
        }
        
        return $eliminar;
    }
    
    #############################################################################
    ### OBTENER ÚLTIMO ID ######################################################
    #############################################################################
    public function ObtenerUltimoId(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT MAX(id) as ultimo_id FROM usuario;");
        $sql->execute();
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        return $data['ultimo_id'] ? $data['ultimo_id'] + 1 : 1;
    }

    #############################################################################
    ### VERIFICAR EXISTENCIA ###################################################
    #############################################################################
    public function VerificarExistencia($ci_per){
        include("conexion.php");
        $sql = $conex->prepare("SELECT COUNT(*) as total FROM usuario WHERE ci_per = ?;");
        $sql->execute([$ci_per]);
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        return $data['total'] > 0;
    }
    
    #############################################################################
    ### OBTENER ROLES DISPONIBLES ##############################################
    #############################################################################
    public function ObtenerRoles(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT id_rol, nombre_rol FROM roles ORDER BY nombre_rol;");
        $sql->execute();
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    
    #############################################################################
    ### VERIFICAR PERMISO ######################################################
    #############################################################################
    public function VerificarPermiso($id_rol, $codigo_permiso){
        include("conexion.php");
        
        $sql = $conex->prepare("SELECT COUNT(*) as total 
                              FROM rol_permisos rp 
                              INNER JOIN permisos p ON rp.id_permiso = p.id_permiso 
                              WHERE rp.id_rol = ? AND p.codigo_permiso = ?;");
        $sql->execute([$id_rol, $codigo_permiso]);
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        return $data['total'] > 0;
    }
    
    #############################################################################
    ### OBTENER PERMISOS USUARIO ###############################################
    #############################################################################
    public function ObtenerPermisosUsuario($id_usuario){
        include("conexion.php");
        
        $sql = $conex->prepare("SELECT p.codigo_permiso 
                              FROM usuario u 
                              INNER JOIN rol_permisos rp ON u.id_rol = rp.id_rol 
                              INNER JOIN permisos p ON rp.id_permiso = p.id_permiso 
                              WHERE u.id = ?;");
        $sql->execute([$id_usuario]);
        $permisos = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
        
        return $permisos;
    }



}
?>