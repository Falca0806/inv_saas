<?php
    require_once "main.php";

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
                La Dirección no coincide con el formato solicitado
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
    $veri_nombre = conexion();
    $veri_nombre = $veri_nombre->query("SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre ='$nombre'");
    if ($veri_nombre->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Nombre ingresado ya existe!
            </div>
        ';
        exit();

    }
    $veri_nombre = null; //Cerrar conexion

    //Guardar datos
    $guardar_proveedor = conexion();
    $guardar_proveedor = $guardar_proveedor->prepare("INSERT INTO proveedor(proveedor_nombre, proveedor_direccion, proveedor_telef) VALUES(:nombre, :direccion, :telefono)");

    $marcadores = [
        ":nombre" => $nombre,
        ":direccion" => $ubicacion,
        ":telefono" => $telefono
    ];

    $guardar_proveedor->execute($marcadores);

    if ($guardar_proveedor->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>Proveedor Registrado!</strong><br>
                El proveedor se registró con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede registrar el proveedor, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_proveedor = null;