<?php

    $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

    $tabla = "";
    if (isset($busqueda) && $busqueda != "") {
        $consulta_datos = "SELECT * FROM proveedor WHERE  proveedor_nombre LIKE '%$busqueda%' ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";

        $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor WHERE proveedor_nombre LIKE '%$busqueda%'";
        
    } else {
        $consulta_datos = "SELECT * FROM proveedor ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";

        $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor";
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
                    <th>Razón Social</th>
                    <th>Ubicación</th>
                    <th>Teléfono</th>
                    <th>Productos</th>
                    <th colspan="2">Opciones</th>
                </tr>
                </thead>
                <tbody>
    ';

    if ($total >= 1 && $pagina <= $n_paginas) {
        $contador = $inicio + 1;
        $pag_inicio = $inicio + 1;
        foreach ($datos as $rows) {
            $tabla .='
            <tr class="has-text-centered" >
                <td>'. $contador .'</td>
                <td>'. $rows['proveedor_nombre'] .'</td>
                <td>'. substr($rows['proveedor_direccion'], 0, 25) .'</td>
                <td>'. $rows['proveedor_telef'] .'</td>
                <td>
                    <a href="index.php?vista=product_provider&provider_id='. $rows['proveedor_id'].'" class="button is-link is-rounded is-small">Ver productos</a> 
                </td>
                <td>
                    <a href="index.php?vista=provider_update&provider_id_up='. $rows['proveedor_id'] .'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'. $url . $pagina . ' &provider_id_del='. $rows['proveedor_id'] .'" class="button is-danger is-rounded is-small">Eliminar</a>
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
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>
            ';
        }
        
        
    }
    
    
    $tabla .= '</tbody></table></div>';

    if ($total >= 1 && $pagina <= $n_paginas) {
        $tabla .= '
        <p class="has-text-right">Mostrando proveedores <strong>'. $pag_inicio .'</strong> al <strong>'. $pag_final .'</strong> de un <strong>total de '. $total .'</strong></p>
        ';
    }
    $conexion = null;
    echo $tabla;

    //Paginador
    if ($total >= 1 && $pagina <= $n_paginas) {
        echo paginador_tablas($pagina, $n_paginas,$url, 7);
    }