<?php

    $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

    $tabla = "";

    $campos="orden_compra.orden_id, orden_compra.producto_codigo, orden_compra.producto_farmace, orden_compra.producto_nombre, orden_compra.producto_precio, orden_compra.producto_stock, orden_compra.proveedor_id, orden_compra.fecha_orden, orden_compra.fecha_venc, proveedor.proveedor_nombre";



    if (isset($busqueda) && $busqueda != "") {

        $consulta_datos = "SELECT $campos FROM orden_compra INNER JOIN proveedor ON orden_compra.proveedor_id=proveedor.proveedor_id  WHERE  producto_nombre LIKE '%$busqueda%' OR producto_codigo LIKE '%$busqueda%' LIMIT $inicio, $registros";

        $consulta_total = "SELECT COUNT(orden_id) FROM orden_compra WHERE producto_nombre LIKE '%$busqueda%' OR producto_codigo LIKE '%$busqueda%'";
        
    } else {
        $consulta_datos = "SELECT $campos FROM orden_compra INNER JOIN proveedor ON orden_compra.proveedor_id=proveedor.proveedor_id ORDER BY orden_id ASC LIMIT $inicio, $registros";

        $consulta_total = "SELECT COUNT(orden_id) FROM orden_compra";
    }

    $conexion = conexion();

    $datos = $conexion->query($consulta_datos);
    $datos = $datos->fetchAll();
    
    $total = $conexion->query($consulta_total);
    $total = (int) $total->fetchColumn();

    $n_paginas = ceil( $total / $registros);

    $tabla .= '
        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                <tr class="has-text-centered">
                    <th>#</th>
                    <th>Producto</th>
                    <th>Lote</th>
                    <th>Farmacéutica</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Proveedor</th>
                    <th>Fecha Orden</th>
                    <th>Fecha Vencimiento</th>
                    <th>Total</th>
                    <th colspan="2">Opciones</th>
                </tr>
                </thead>
                <tbody>
    ';

    if ($total >= 1 && $pagina <= $n_paginas) {
        $contador = $inicio + 1;
        $pag_inicio = $inicio + 1;
        foreach ($datos as $rows) {
            $total = $rows['producto_precio'] * $rows['producto_stock'];
            $total_final = $total;
            $tabla .='
            <tr class="has-text-centered" >
                <td>'. $contador .'</td>
                <td>'. $rows['producto_nombre'] .'</td>
                <td>'. $rows['producto_codigo'] .'</td>
                <td>'. $rows['producto_farmace'] .'</td>
                <td>'. $rows['producto_precio'] .' Bs.</td>
                <td>'. $rows['producto_stock'] .'</td>
                <td>'. $rows['proveedor_nombre'] .'</td>
                <td>'. $rows['fecha_orden'] .'</td>
                <td>'. $rows["fecha_venc"].'</td>
                <td>'. $total_final .' Bs.</td>
                <td>
                    <a href="'. $url . $pagina . ' &purchase_id_del='. $rows['orden_id'] .'" class="button is-danger is-rounded is-small">Eliminar</a>
                </td>
                <td>
                    <a href="report_compra.php?id= ' .$rows['orden_id'] .'" class="button is-primary is-rounded is-small">PDF</a>
                </td>
            </tr>
            ';
            $contador ++;
        }
        $pag_final = $contador - 1;
    } else {
        if ($total >= 1) {
            $tabla .= '
            <tr class="has-text-centered" >
                <td colspan="6">
                    <a href="'. $url .' 1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
            ';
        } else {
            $tabla .= '
            <tr class="has-text-centered" >
                <td colspan="10">
                    No hay registros en el sistema
                </td>
            </tr>
            ';
        }
        
        
    }
    
    
    $tabla .= '</tbody></table></div>';

    if ($total >= 1 && $pagina <= $n_paginas) {
        $tabla .= '
        <p class="has-text-right">Mostrando compras <strong>'. $pag_inicio .'</strong> al <strong>'. $pag_final .'</strong> de un <strong>total de '. $total .'</strong></p>
        ';
    }
    $conexion = null;
    echo $tabla;

    //Paginador
    if ($total >= 1 && $pagina <= $n_paginas) {
        echo paginador_tablas($pagina, $n_paginas,$url, 7);
    }