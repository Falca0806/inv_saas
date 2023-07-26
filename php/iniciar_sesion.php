<?php
       //Almacenamiento de datos
   $usuario = limpiar_cadena($_POST['login_usuario']);
   $clave = limpiar_cadena($_POST['login_clave']);

      //Verificar campos obligatorios
      if ($usuario == "" || $clave == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    //Verificar integridad de datos
    if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Usuario no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La contrasena no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    $veri_user = conexion();
    $veri_user = $veri_user->query("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");

    if ($veri_user->rowCount() == 1) {
        $veri_user = $veri_user->fetch();

        if ($veri_user['usuario_usuario'] == $usuario && password_verify($clave,$veri_user['usuario_clave'])) {
            
            $_SESSION['id'] = $veri_user['usuario_id'];
            $_SESSION['nombre'] = $veri_user['usuario_nombre'];
            $_SESSION['apellido'] = $veri_user['usuario_apellido'];
            $_SESSION['usuario'] = $veri_user['usuario_usuario'];

            if (headers_sent()) {
                echo "<script> window.location.href='index.php?vista=home'; </script>";
            } else {
                header("Location: index.php?vista=home");
            }
            

        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    Usuario o contraseña incorrectos
                </div>
            ';
        }
        
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Usuario o contraseña incorrectos
            </div>
        ';
    }
    $veri_user = null;
    


