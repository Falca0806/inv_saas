<?php
    require_once "main.php";

    $product_id = limpiar_cadena($_POST['img_del_id']);

    //Verificar el producto
    $veri_producto = conexion();
    $veri_producto = $veri_producto->query("SELECT * FROM producto WHERE producto_id = '$product_id'");

    if ($veri_producto->rowCount() == 1 ) {
        $datos = $veri_producto->fetch();
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La imagen del producto no existe!
            </div>
        ';
        exit();
    }
    $veri_producto = null;

    //Directorio de imagenes
    $img_dir = "../img/producto/";

    chmod($img_dir, 0777);

    if (is_file($img_dir . $datos['producto_foto'])) {
        chmod($img_dir . $datos['producto_foto'], 0777);

        if (!unlink($img_dir . $datos['producto_foto'])) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    Error al intentar eliminar la imagen del producto, por favor intente de nuevo
                </div>
            ';
            exit();
        }
    }

    //Actualizacion de datos
    $actualizar_producto = conexion();
    $actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET producto_foto = :foto WHERE producto_id = :id");

    $marcadores = [
        ":foto" => "",
        ":id" => $product_id
    ];

    if ($actualizar_producto->execute($marcadores)) {
        echo '
            <div class="notification is-success is-light">
                <strong>Imagen Eliminada!</strong><br>
                La imagen del producto ha sido eliminada con exito, pulse Aceptar para recargar los cambios
                <p clas="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='. $product_id .'" clas=="button is-link is-rounded">Aceptar</a>
                </p>

            </div>
        ';
    } else {
        echo '
            <div class="notification is-warning is-light">
                <strong>Imagen Eliminada!</strong><br>
                Ocurrieron algunos inconvenientes, sin embargo la imagen del producto ha sido eliminada, pulse Aceptar para recargar los cambios

                <p clas="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='. $product_id .'" clas=="button is-link is-rounded">Aceptar</a>
                </p>
            </div>
        ';
    }
    $actualizar_producto = null;