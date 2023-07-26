<?php
    require_once "main.php";

    $id = limpiar_cadena($_POST['producto_id']);

    //Verificar el producto
    $veri_producto = conexion();
    $veri_producto = $veri_producto->query("SELECT * FROM producto WHERE producto_id = '$id'");

    if ($veri_producto->rowCount() <= 0 ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El producto no existe!
            </div>
        ';
        exit();
    } else {
        $datos = $veri_producto->fetch();
    }
    $veri_producto = null;

    //Almacenamiento de datos
    $codigo = limpiar_cadena($_POST['producto_codigo']);
    $nombre = limpiar_cadena($_POST['producto_nombre']);
    $farmaceuta = limpiar_cadena($_POST['producto_farmace']);

    $precio = limpiar_cadena($_POST['producto_precio']);
    $stock = limpiar_cadena($_POST['producto_stock']);
    $categoria = limpiar_cadena($_POST['producto_categoria']);

    //Verificar campos obligatorios
    if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    //Verificar integridad de datos
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
    if ($codigo != $datos['producto_codigo']) {
        $veri_codigo = conexion();
        $veri_codigo = $veri_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo ='$codigo'");
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
    }


    //Verificar categoria
    if ($categoria != $datos['categoria_id']) {
        $veri_categoria = conexion();
    $veri_categoria = $veri_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id ='$categoria'");
        if ($veri_categoria->rowCount() <= 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    La Categoria seleccionada no existe
                </div>
            ';
            exit();

        }
        $veri_categoria = null; //Cerrar conexion
    }

    //Actualizacion de datos
    $actualizar_producto = conexion();
    $actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET producto_codigo = :codigo, producto_nombre = :nombre, producto_farmace = :farmace, producto_precio = :precio, producto_stock = :stock, categoria_id = :categoria WHERE producto_id = :id");

    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":farmace" => $farmaceuta,
        ":precio" => $precio,
        ":stock" => $stock,
        ":categoria" => $categoria,
        ":id" => $id
    ];

    if ($actualizar_producto->execute($marcadores)) {
        echo '
            <div class="notification is-success is-light">
                <strong>Producto actualizado!</strong><br>
                El producto ha sido actualizado con exito!
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede actualizar el producto, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_producto = null;

