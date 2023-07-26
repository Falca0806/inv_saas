<?php
    $purchase_id_del = limpiar_cadena($_GET['purchase_id_del']);

    //Verificar categoria
    $veri_orden = conexion();
    $veri_orden = $veri_orden->query("SELECT orden_id FROM orden_compra WHERE orden_id = '$purchase_id_del'");
    if ($veri_orden->rowCount() == 1) {

        $eliminar_orden = conexion();
        $eliminar_orden = $eliminar_orden->prepare("DELETE FROM orden_compra WHERE orden_id = :id");

        $eliminar_orden->execute([":id" => $purchase_id_del]);

        if ($eliminar_orden->rowCount() == 1) {
            echo '
                <div class="notification is-success is-light">
                    <strong>Orden de Compra eliminada!</strong><br>
                    Los datos de la orden han sido eliminados
                </div>
            ';
            
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar la orden, por favor intente nuevamente
                </div>
            ';
        }

        $eliminar_orden = null;
    }
    $veri_orden = null;
    