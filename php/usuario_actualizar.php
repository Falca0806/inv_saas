<?php
    require_once "../include/session_start.php";

    require_once "main.php";

    $id = limpiar_cadena($_POST['usuario_id']);

    //Verificar el usuario
    $veri_usuario = conexion();
    $veri_usuario = $veri_usuario->query("SELECT * FROM usuario WHERE usuario_id = '$id'");

    if ($veri_usuario->rowCount() <= 0 ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El usuario no existe!
            </div>
        ';
        exit();
        
    } else {
        $datos = $veri_usuario->fetch();
    }
    $veri_usuario = null;

    $admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave = limpiar_cadena($_POST['administrador_clave']);

    //Verificar campos obligatorios
    if ($admin_usuario == "" || $admin_clave == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios, que corresponden a su Usuario y Contraseña
            </div>
        ';
        exit();
    }
    //Verificar integridad de datos
    if (verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Su Usuario no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Su Contraseña no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    //Verificando el admin
    $veri_admin = conexion();
    $veri_admin = $veri_admin->query("SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_id = '". $_SESSION['id']."' ");

    if ($veri_admin->rowCount() == 1) {
        $veri_admin = $veri_admin->fetch();

        if ($veri_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave,$veri_admin['usuario_clave'])) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    Usuario o Contraseña de Administrador incorrectos
                </div>
            ';
        }
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                Usuario o Contraseña de Administrador incorrectos
            </div>
        ';
    }
    $veri_admin = null;

    //Almacenamiento de datos
    $nombre = limpiar_cadena($_POST['usuario_nombre']);
    $apellido = limpiar_cadena($_POST['usuario_apellido']);

    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $email = limpiar_cadena($_POST['usuario_email']);

    $clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2 = limpiar_cadena($_POST['usuario_clave_2']);
    
       //Verificar campos obligatorios
       if ($nombre == "" || $apellido == "" || $usuario == "") {
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

    //Verificar email
    if ($email != "" && $email != $datos['usuario_email']) {
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
    if ($usuario != $datos['usuario_usuario']) {
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
    }

    //Verificar contrasena
    if ($clave_1 != "" || $clave_2 != "") {
        if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    Las Contraseñas no coincide con el formato solicitado
                </div>
            ';
            exit();
        }else {
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
        }
    } else {
        $clave = $datos ['usuario_clave'];
    }

    //Actualizacion de datos
    $actualizar_usuario = conexion();
    $actualizar_usuario = $actualizar_usuario->prepare("UPDATE usuario SET usuario_nombre = :nombre, usuario_apellido = :apellido, usuario_usuario = :usuario, usuario_clave = :clave, usuario_email = :email WHERE usuario_id = :id");

    $marcadores = [
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave,
        ":email" => $email,
        ":id" => $id

    ];
    if ($actualizar_usuario->execute($marcadores)) {
        echo '
        <div class="notification is-success is-light">
            <strong>Usuario actualizado!</strong><br>
            El Usuario ha sido actualizado con exito!
        </div>
    ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede actualizar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_usuario = null;
    
    
    
