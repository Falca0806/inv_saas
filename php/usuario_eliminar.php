<?php
    $user_id_del = limpiar_cadena($_GET['user_id_del']);

    //Verificar usuario
    $veri_usuario = conexion();
    $veri_usuario = $veri_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$user_id_del'");
    if ($veri_usuario->rowCount() == 1) {

        //Verificar usuario tenga productos registrados
        $veri_productos = conexion();
        $veri_productos = $veri_productos->query("SELECT usuario_id FROM producto WHERE usuario_id = '$user_id_del' LIMIT 1");

        if ($veri_productos->rowCount() <= 0) {
            
            $eliminar_usuario = conexion();
            $eliminar_usuario = $eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id = :id");

            $eliminar_usuario->execute([":id" => $user_id_del]);

            if ($eliminar_usuario->rowCount() == 1) {
                echo '
                    <div class="notification is-success is-light">
                        <strong>Usuario eliminado!</strong><br>
                        Los datos del usuario han sido eliminados
                    </div>
                ';
                
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado!</strong><br>
                        No se puede eliminar el usuario, por favor intente nuevamente
                    </div>
                ';
            }

            $eliminar_usuario = null;
            

        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se puede eliminar el usuario ya que tiene productos registrados
                </div>
            ';
        }
        $veri_productos = null;

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El usuario que intenta eliminar no existe!
            </div>
        ';
    }

    $veri_usuario = null;
    