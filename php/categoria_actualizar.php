<?php
    require_once "main.php";

    $id = limpiar_cadena($_POST['categoria_id']);

    //Verificar la categoria
    $veri_categoria = conexion();
    $veri_categoria = $veri_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$id'");

    if ($veri_categoria->rowCount() <= 0 ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La Categoría no existe
            </div>
        ';
        exit();
    } else {
        $datos = $veri_categoria->fetch();
    }
    $veri_categoria = null;

    //Almacenamiento de datos
    $nombre = limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

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

    if ($ubicacion != "") {
        if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    La Ubicación no coincide con el formato solicitado
                </div>
            ';
            exit();
        }
    }

    //Verificar nombre

    if ($nombre!= $datos['categoria_nombre']) {
        $veri_nombre = conexion();
        $veri_nombre = $veri_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre ='$nombre'");
        if ($veri_nombre->rowCount() > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    La Categoría ingresada ya existe
                </div>
            ';
            exit();
    
        }
        $veri_nombre = null; //Cerrar conexion
    }

    //Actualizacion de datos
    $actualizar_categoria = conexion();
    $actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET categoria_nombre = :nombre, categoria_ubicacion = :ubicacion WHERE categoria_id = :id");

    $marcadores = [
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion,
        ":id" => $id
    ];

    if ($actualizar_categoria->execute($marcadores)) {
        echo '
            <div class="notification is-success is-light">
                <strong>Categoría actualizada!</strong><br>
                La Categoria ha sido actualizada con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar la categoría, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_categoria = null;

