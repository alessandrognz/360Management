<?php
    session_start();

    $Ip = "127.0.0.1";
    $Puerto = 3306;
    $Usuario = "root";
    $Contrasenia = "Tito2013-";
    $DB_nombre = "users";

    $Coneccion = new mysqli($Ip,$Usuario,$Contrasenia,$DB_nombre,$Puerto);

    if ($Coneccion->connect_error) {
        die("Conexión fallida: " . $Coneccion->connect_error);
    }

    $Coneccion->set_charset("utf8mb4");

    function true_or_false($value= []) {
        return $value["response"] ? 'true' : 'false';
    }

    function INSERTAR_USUARIO($nombre = '',$email = '',$puesto = 0,$contrasena = ''){
        global $Coneccion;
        $tabla = [];


        //Comando
        $comando = $Coneccion->prepare("CALL INSERTAR_USUARIO(?,?,?,?);");
   
   
        //Llenado de variables
        $comando->bind_param("isss", $puesto,$nombre,$email,$contrasena);
        $comando->execute();
   
        //Obtener resultado
        $result = $comando->get_result();
        $comando->close();


        // Inicio - Resultados de 1 o 0 //
        if ($result) {

            while ($row = $result->fetch_assoc()) {
                $tabla[] = $row;
            }

            $tabla = $tabla[0];
            $result->free();
        }
       
        if(true_or_false($tabla)){
            $_SESSION['nombre'] = $nombre;

            header("Location: session.php");
            exit();
       
        } else {

            $mensaje = "Error al registrarse " . $comando->error;
            echo $mensaje; // echo es para pruebas visibles, se cambiaria por un return
        }
        // FIN - Resultados de 1 o 0 //

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
?>