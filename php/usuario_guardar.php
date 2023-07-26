<?php
   require_once "main.php";

   //Almacenamiento de datos
   $nombre = limpiar_cadena($_POST['usuario_nombre']);
   $apellido = limpiar_cadena($_POST['usuario_apellido']);

   $usuario = limpiar_cadena($_POST['usuario_usuario']);
   $email = limpiar_cadena($_POST['usuario_email']);

   $clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
   $clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

   //Verificar campos obligatorios
    if ($nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    //Verificar integridad de datos
    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Nombre no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Apellido no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Usuario no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Las Contraseñas no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    //Verificar email
    if ($email != "") {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $veri_email = conexion();
            $veri_email = $veri_email->query("SELECT usuario_email FROM usuario WHERE usuario_email ='$email'");
            if ($veri_email->rowCount() > 0) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado!</strong><br>
                       El Correo electronico ingresado ya existe!
                    </div>
                ';
                exit();

            }
            $veri_email = null; //Cerrar conexion
        }else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    El Correo electronico ingresado no es valido
                </div>
            ';
            exit();
        }
    }

    //Verificar usuario
    $veri_usuario = conexion();
    $veri_usuario = $veri_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario ='$usuario'");
    if ($veri_usuario->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
               El Usuario ingresado ya existe!
            </div>
        ';
        exit();

    }
    $veri_usuario = null; //Cerrar conexion

    //Verificar contrasena
    if ($clave_1 != $clave_2) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Las Contraseñas que ha ingresado no coinciden!
            </div>
        ';
        exit();
    } else {
        $clave = password_hash($clave_1, PASSWORD_BCRYPT, ["cost"=>10]); //Encrictar
    }

    //Guardar datos
    $guardar_usuario = conexion();
    $guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) VALUES(:nombre, :apellido, :usuario, :clave, :email)");

    $marcadores = [
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave,
        ":email" => $email

    ];

    $guardar_usuario->execute($marcadores);

    if ($guardar_usuario->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>Usuario Registrado!</strong><br>
                El Usuario se registro con exito
            </div>
        ';
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede registrar el usuario, por favor intente nuevamente
            </div>
        ';
    }

    $guardar_usuario = null;
    
    