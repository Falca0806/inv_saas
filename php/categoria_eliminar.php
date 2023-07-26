<?php
    $category_id_del = limpiar_cadena($_GET['category_id_del']);

    //Verificar categoria
    $veri_categoria = conexion();
    $veri_categoria = $veri_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$category_id_del'");
    if ($veri_categoria->rowCount() == 1) {

        //Verificar categoria tenga productos registrados
        $veri_productos = conexion();
        $veri_productos = $veri_productos->query("SELECT categoria_id FROM producto WHERE categoria_id = '$category_id_del' LIMIT 1");

        if ($veri_productos->rowCount() <= 0) {
            $eliminar_categoria = conexion();
            $eliminar_categoria = $eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id = :id");

            $eliminar_categoria->execute([":id" => $category_id_del]);

            if ($eliminar_categoria->rowCount() == 1) {
                echo '
                    <div class="notification is-success is-light">
                        <strong>Categoría eliminada!</strong><br>
                        Los datos de la categoría han sido eliminados
                    </div>
                ';
                
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado!</strong><br>
                        No se pudo eliminar la categoría, por favor intente nuevamente
                    </div>
                ';
            }

            $eliminar_categoria = null;
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se puede eliminar la categoria ya que tiene productos registrados
                </div>
            ';
        }
        $veri_productos = null;
        
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La Categoria que intenta eliminar no existe!
            </div>
        ';
    }
    $veri_categoria = null;
    