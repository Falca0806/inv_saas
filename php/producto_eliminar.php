<?php
    $product_id_del = limpiar_cadena($_GET['product_id_del']);

    //Verificar producto
    $veri_producto = conexion();
    $veri_producto = $veri_producto->query("SELECT * FROM producto WHERE producto_id = '$product_id_del'");

    if ($veri_producto->rowCount() == 1) {
        $datos = $veri_producto->fetch();

        $eliminar_producto = conexion();
        $eliminar_producto = $eliminar_producto->prepare("DELETE FROM producto WHERE producto_id = :id");

        $eliminar_producto->execute([":id" => $product_id_del]);

        if ($eliminar_producto->rowCount() == 1) {

            if (is_file("./img/producto/" . $datos['producto_foto'])) {
                chmod("./img/producto/" . $datos['producto_foto'],0777);
                unlink("./img/producto/" . $datos['producto_foto']);
            }

            echo '
                <div class="notification is-success is-light">
                    <strong>Producto eliminado!</strong><br>
                    Los datos del producto han sido eliminados
                </div>
            ';
            
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se puede eliminar el producto, por favor intente nuevamente
                </div>
            ';
        }
        $eliminar_producto = null;


    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El producto que intenta eliminar no existe!
            </div>
        ';
    }
    $veri_producto = null;