<?php
    $provider_id_del = limpiar_cadena($_GET['provider_id_del']);

    //Verificar categoria
    $veri_provider = conexion();
    $veri_provider = $veri_provider->query("SELECT proveedor_id FROM proveedor WHERE proveedor_id = '$provider_id_del'");
    if ($veri_provider->rowCount() == 1) {

        //Verificar proveedor tenga productos registrados
        $veri_productos = conexion();
        $veri_productos = $veri_productos->query("SELECT proveedor_id FROM producto WHERE proveedor_id = '$provider_id_del' LIMIT 1");

        if ($veri_productos->rowCount() <= 0) {
            $eliminar_proveedor = conexion();
            $eliminar_proveedor = $eliminar_proveedor->prepare("DELETE FROM proveedor WHERE proveedor_id = :id");

            $eliminar_proveedor->execute([":id" => $provider_id_del]);

            if ($eliminar_proveedor->rowCount() == 1) {
                echo '
                    <div class="notification is-success is-light">
                        <strong>Proveedor eliminado!</strong><br>
                        Los datos del proveedor han sido eliminados
                    </div>
                ';
                
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado!</strong><br>
                        No se puede eliminar el proveedor, por favor intente nuevamente
                    </div>
                ';
            }

            $eliminar_proveedor = null;
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se puede eliminar el proveedor ya que tiene productos registrados
                </div>
            ';
        }
        $veri_productos = null;
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El proveedor que intenta eliminar no existe!
            </div>
        ';
    }
    $veri_provider = null;