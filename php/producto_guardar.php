<?php
require_once "../include/session_start.php";
    require_once "main.php";

    //Almacenamiento de datos
    $codigo = limpiar_cadena($_POST['producto_codigo']);
    $nombre = limpiar_cadena($_POST['producto_nombre']);
    $farmaceuta = limpiar_cadena($_POST['producto_farmace']);

    $precio = limpiar_cadena($_POST['producto_precio']);
    $stock = limpiar_cadena($_POST['producto_stock']);
    $categoria = limpiar_cadena($_POST['producto_categoria']);
    $proveedor = limpiar_cadena($_POST['producto_proveedor']);
    $fecha_ven = $_POST['fecha_venc'];



    //Verificar campos obligatorios
    if ($codigo == "" || $nombre == "" || $farmaceuta == "" || $precio == "" || $stock == "" || $categoria == "" || $proveedor == "" || $fecha_ven == "") {
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

    date_default_timezone_set("America/Caracas");
    $fecha_ven = $_POST['fecha_venc'];
    $fecha_actual = strtotime(date("Y-m-d"));
    $fecha_final = strtotime($fecha_ven);

        if($fecha_actual > $fecha_final){
            echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                La Fecha de Vencimiento no coincide con el formato solicitado
            </div>
        ';
        exit();
        } 
    
    //Verificar codigo
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

    //Verificar categoria
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

    //Verificar proveedor
    $veri_proveedor = conexion();
    $veri_proveedor = $veri_proveedor->query("SELECT proveedor_id FROM proveedor WHERE proveedor_id ='$proveedor'");
    if ($veri_proveedor->rowCount() <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                El proveedor seleccionado no existe
            </div>
        ';
        exit();

    }
    $veri_proveedor = null; //Cerrar conexion


    //Directorio de imagenes
    $img_dir = "../img/producto/";

    //Verificar si se selecciono una imagen
    if ($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size']>0) {
        
        //Verificar directorio
        if (!file_exists($img_dir)) {
            if (!mkdir($img_dir,0777)) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>Ocurrio un error inesperado!</strong><br>
                        Error al crear el directorio
                    </div>
                ';
                exit();
            }
        }


        //Verificar formato de imagen
        if (mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png" ) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado es de un formato no permitido
                </div>
            ';
            exit();
        }

        //Verificar tamano de imagen
        if (($_FILES['producto_foto']['size']/1024) > 3072) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado supera el peso permitido
                </div>
            ';
            exit();
        }

        //Verificar extension de imagen
        switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
            case 'image/jpeg':
                $img_ext = ".jpg";
                break;

            case 'image/png':
                $img_ext = ".png";
                break;
            
        }

        chmod($img_dir, 0777);
        $img_nombre = renombrar_fotos($nombre);
        $foto = $img_nombre . $img_ext;
        
        //Mover imagen al directorio
        if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>Ocurrio un error inesperado!</strong><br>
                    No se puede cargar la imagen al sistema en este momento
                </div>
            ';
            exit();
        }
    } else {
        $foto = "";
    }

    //Guardar datos
    $guardar_producto = conexion();
    $guardar_producto = $guardar_producto->prepare("INSERT INTO producto(producto_codigo, producto_nombre, producto_farmace, producto_precio, producto_stock, producto_foto, categoria_id, usuario_id, proveedor_id, fecha_venc) VALUES(:codigo, :nombre, :farmace, :precio, :stock, :foto, :categoria, :usuario, :proveedor, :vencimiento)");

    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":farmace" => $farmaceuta,
        ":precio" => $precio,
        ":stock" => $stock,
        ":foto" => $foto,
        ":categoria" => $categoria,
        ":usuario" => $_SESSION['id'],
        ":proveedor" => $proveedor,
        ":vencimiento" => $fecha_ven
    ];

    $guardar_producto->execute($marcadores);

    if ($guardar_producto->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>Producto Registrado!</strong><br>
                El Producto se registró con exito
            </div>
        ';
        
    } else {

        if (is_file($img_dir . $foto)) {
            chmod($img_dir . $foto, 0777);
            unlink($img_dir . $foto);
        }
        echo '
            <div class="notification is-danger is-light">
                <strong>Ocurrio un error inesperado!</strong><br>
                No se puede registrar el producto, por favor intente nuevamente
            </div>
        ';
    }

    $guardar_producto = null;



    




