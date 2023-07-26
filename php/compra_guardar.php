<?php
require_once "../include/session_start.php";
    require_once "main.php";

    //Almacenamiento de datos
    $nombre = limpiar_cadena($_POST['producto_nombre']);
    $codigo = limpiar_cadena($_POST['producto_codigo']);
    $farmaceuta = limpiar_cadena($_POST['producto_farmace']);

    $precio = limpiar_cadena($_POST['producto_precio']);
    $stock = limpiar_cadena($_POST['producto_stock']);
    $proveedor = limpiar_cadena($_POST['producto_proveedor']);
    $fecha_ven = $_POST['fecha_venc'];



    //Verificar campos obligatorios
    if ($nombre == "" || $codigo == "" ||  $farmaceuta == "" || $precio == "" || $stock == "" || $proveedor == "" || $fecha_ven == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    //Verificar integridad de datos
    //Nombre
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Nombre no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    //Codigo
    if (verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Lote no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    
    //Farmaceuta
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$farmaceuta)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Nombre de la Farmacéutica no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    //Precio
    if (verificar_datos("[0-9.]{1,25}",$precio)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Precio no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    //Stock
    if (verificar_datos("[0-9]{1,25}",$stock)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Stock no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    //Verificar codigo
    $veri_codigo = conexion();
    $veri_codigo = $veri_codigo->query("SELECT producto_codigo FROM orden_compra WHERE producto_codigo ='$codigo'");
    if ($veri_codigo->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Lote ingresado ya existe!
            </div>
        ';
        exit();

    }
    $veri_codigo = null; //Cerrar conexion


    //Verificar proveedor
    $veri_proveedor = conexion();
    $veri_proveedor = $veri_proveedor->query("SELECT proveedor_id FROM proveedor WHERE proveedor_id ='$proveedor'");
    if ($veri_proveedor->rowCount() <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El Proveedor seleccionado no existe
            </div>
        ';
        exit();

    }
    $veri_proveedor = null; //Cerrar conexion

    //Guardar datos
    $guardar_orden = conexion();
    $guardar_orden = $guardar_orden->prepare("INSERT INTO orden_compra(producto_codigo, producto_nombre, producto_farmace, producto_precio, producto_stock, proveedor_id, fecha_venc) VALUES(:codigo, :nombre, :farmace, :precio, :stock, :proveedor, :vencimiento)");

    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":farmace" => $farmaceuta,
        ":precio" => $precio,
        ":stock" => $stock,
        ":proveedor" => $proveedor,
        ":vencimiento" => $fecha_ven
    ];

    $guardar_orden->execute($marcadores);

    if ($guardar_orden->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>Orden de Compra Registrada!</strong><br>
                La Orden de Compra se registró con exito
            </div>
        ';
        
    } else {

        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede registrar la orden de compra, por favor intente nuevamente
            </div>
        ';
    }

    $guardar_orden = null;