<?php
    require_once "main.php";

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
                    La Ubicacion no coincide con el formato solicitado
                </div>
            ';
            exit();
        }
    }

    //FECHA
    

    //Verificar nombre
    $veri_nombre = conexion();
    $veri_nombre = $veri_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre ='$nombre'");
    if ($veri_nombre->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La Categoria ingresada ya existe!
            </div>
        ';
        exit();

    }
    $veri_nombre = null; //Cerrar conexion

    //Guardar datos
    $guardar_categoria = conexion();
    $guardar_categoria = $guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre, categoria_ubicacion) VALUES(:nombre, :ubicacion)");

    $marcadores = [
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion
    ];

    $guardar_categoria->execute($marcadores);

    if ($guardar_categoria->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>Categoría Registrada!</strong><br>
                La Categoría se registró con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede registrar la categoría, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_categoria = null;
