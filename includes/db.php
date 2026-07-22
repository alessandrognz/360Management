<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $DB_config = require __DIR__ . '/db_config.php';

    $Ip = $DB_config['ip'];
    $Puerto = $DB_config['puerto'];
    $Usuario = $DB_config['usuario'];
    $Contrasenia = $DB_config['contrasenia'];
    $DB_nombre = $DB_config['db_nombre'];

    $Coneccion = new mysqli($Ip,$Usuario,$Contrasenia,$DB_nombre,$Puerto);

    if ($Coneccion->connect_error) {
        die("Conexión fallida: " . $Coneccion->connect_error);
    }

    $Coneccion->set_charset("utf8mb4");

    function true_or_false($value= []) {
    return (bool)$value["response"];
    }

    class loginAndRegister{
        function INSERTAR_USUARIO($nombre = '',$email = '',$puesto = 0,$contrasena = ''){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL INSERTAR_USUARIO(?,?,?,?);");
            $comando->bind_param("isss", $puesto,$nombre,$email,$contrasena);
            $comando->execute();

            $result  = $comando->get_result();
            $usuario = $result->fetch_assoc();
            $result->free();
            $comando->close();

            if ($usuario) {
                $_SESSION['nombre']          = $usuario['nombre'];
                $_SESSION['id_usuario']      = $usuario['id_usuario'];
                $_SESSION['id_departamento'] = $usuario['id_departamento'];

                header("Location: session.php");
                exit();
            } else {
                echo "Error al registrarse";
            }
        }

        function INICIAR_SESION($email = '',$contrasena = '') {
            global $Coneccion;

            $comando = $Coneccion->prepare('CALL VERIFICAR_EMAIL(?);');
            $comando->bind_param("s", $email);
            $comando->execute();

            $_email = $comando->get_result();
            $usuario = $_email->fetch_assoc();
            $comando->close();
            $_email = $usuario['email'];

            $comando = $Coneccion->prepare('CALL VERIFICAR_CONTRASENA(?);');
            $comando->bind_param("s", $email);
            $comando->execute();

            $_contrasena = $comando->get_result();
            $usuario = $_contrasena->fetch_assoc();
            $comando->close();

            if (password_verify($contrasena, $usuario['contrasena'])){
                $_SESSION['nombre']     = $usuario['nombre'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['id_puesto']  = $usuario['id_puesto'];

                header('Location: session.php');
                exit();
            };
            return false;
        }
    }
   
    class CRUD_TAREAS{
        function INSERTAR_TAREA(int $_id_usuario_creador, string $_titulo, string $_descripcion_tarea, $_fecha_limite , int $_es_general, $_listado_enviar){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL INSERTAR_TAREA(?,?,?,?,?);");
            $comando->bind_param("isssi", $_id_usuario_creador,$_titulo,$_descripcion_tarea,$_fecha_limite,$_es_general);
            $comando->execute();

            $result   = $comando->get_result();
            $id = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $comando->close();
            $id_tarea = $id[0]["insertado"];
            if($_es_general == 0){
                foreach ($_listado_enviar as $ids) {
                    $comando = $Coneccion->prepare("CALL DESTINAR_A_USAURIO(?,?);");
                    $comando->bind_param("ii", $id_tarea,$ids);
                    $comando->execute();
                    $comando->close();

                }

            }elseif($_es_general == 1){
                 foreach ($_listado_enviar as $ids) {
                    $comando = $Coneccion->prepare("CALL DESTINAR_A_DEPARTAMENTO(?,?);");
                    $comando->bind_param("ii", $id_tarea,$ids);
                    $comando->execute();
                    $comando->close();

                }

            }
            

        }
        function SELECT_TAREAS_GENERALES(int $_id_puesto){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL SELECT_TAREAS_GENERALES(?);");
            $comando->bind_param("i", $_id_puesto);
            $comando->execute();

            $result  = $comando->get_result();
            $tareas = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $comando->close();

            return $tareas;
        }
        function SELECT_TAREAS_PERSONALES(int $_id_usuario){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL SELECT_TAREAS_PERSONALES(?);");
            $comando->bind_param("i", $_id_usuario);
            $comando->execute();

            $result  = $comando->get_result();
            $tareas = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $comando->close();

            return $tareas;
        }
        function MOSTRAR_USUARIOS() {
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL MOSTRAR_USUARIOS();");
            $comando->execute();

            $result   = $comando->get_result();
            $usuarios = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $comando->close();

            return $usuarios;
        }
        function LISTAR_DEPARTAMENTOS(){
            global $Coneccion;

            $comando = $Coneccion->prepare("CALL LISTAR_DEPARTAMENTOS();");
            $comando->execute();

            $result   = $comando->get_result();
            $departamentos = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $comando->close();

            return $departamentos;
        }
    }
    
?>