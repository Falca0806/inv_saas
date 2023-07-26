<?php
    require_once "main.php";

    $id = limpiar_cadena($_POST['proveedor_id']);

    //Verificar el proveedor
    $veri_proveedor = conexion();
    $veri_proveedor = $veri_proveedor->query("SELECT * FROM proveedor WHERE proveedor_id = '$id'");

    if ($veri_proveedor->rowCount() <= 0 ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Proveedor no existe
            </div>
        ';
        exit();
    } else {
        $datos = $veri_proveedor->fetch();
    }
    $veri_proveedor = null;

    //Almacenamiento de datos
    $nombre = limpiar_cadena($_POST['proveedor_nombre']);
    $ubicacion = limpiar_cadena($_POST['proveedor_direccion']);
    $telefono = limpiar_cadena($_POST['proveedor_telef']);

    //Verificar campos obligatorios
    if ($nombre == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }
    //Verificar campos obligatorios
    if ($ubicacion == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }
    //Verificar campos obligatorios
    if ($telefono == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    //Verificar integridad de datos
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Nombre no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La Ubicacion no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if (verificar_datos("[0-9]{11,11}",$telefono)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Teléfono no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    //Verificar nombre

    if ($nombre!= $datos['proveedor_nombre']) {
        $veri_nombre = conexion();
        $veri_nombre = $veri_nombre->query("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre ='$nombre'");
        if ($veri_nombre->rowCount() > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    El Proveedor ingresado ya existe
                </div>
            ';
            exit();
    
        }
        $veri_nombre = null; //Cerrar conexion
    }

    //Actualizacion de datos
    $actualizar_proveedor = conexion();
    $actualizar_proveedor = $actualizar_proveedor->prepare("UPDATE proveedor SET proveedor_nombre = :nombre, proveedor_direccion = :ubicacion, proveedor_telef = :telefono WHERE proveedor_id = :id");

    $marcadores = [
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion,
        ":telefono" => $telefono,
        ":id" => $id
    ];

    if ($actualizar_proveedor->execute($marcadores)) {
        echo '
            <div class="notification is-success is-light">
                <strong>Proveedor actualizado!</strong><br>
                El Proveedor ha sido actualizado con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede actualizar el proveedor, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_proveedor = null;

