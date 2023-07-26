<?php
require_once('tcpdf/tcpdf.php'); //Llamando a la Libreria TCPDF
require_once('./php/main.php'); //Llamando a la conexión para BD
date_default_timezone_set('America/Caracas');

$orden_id = "";

if ( $_SERVER ['REQUEST_METHOD'] == 'GET'){

    if(!isset($_GET["id"])){
        header('location: compra_lista.php');
        exit;
    }

    $orden_id = $_GET["id"];

    ob_end_clean(); //limpiar la memoria
    
    class MYPDF extends TCPDF{
          
            public function Header() {
                $bMargin = $this->getBreakMargin();
                $auto_page_break = $this->AutoPageBreak;
                $this->SetAutoPageBreak(false, 0);
                $img_file = dirname( __FILE__ ) .'/img/Logo-Farmacia-SAAS.png';
                $this->Image($img_file, 10, 5, 70, 30, 'JPG', '', '', false, 30, '', false, false, 0);
                $this->SetAutoPageBreak($auto_page_break, $bMargin);
                $this->setPageMark();
            }
    }

        //Iniciando un nuevo pdf
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);
 
        //Establecer margenes del PDF
        $pdf->SetMargins(20, 35, 25);
        $pdf->SetHeaderMargin(20);
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(true); //Eliminar la linea superior del PDF por defecto
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //Activa o desactiva el modo de salto de página automático
 
        //Informacion del PDF
        $pdf->SetAuthor('Farmacia SAAS');
        $pdf->SetTitle('Reporte de Compra');

        $factura = rand(999999, 111111);

        //Agregando la primera página
        $pdf->AddPage();
        $pdf->SetFont('helvetica','B',10); //Tipo de fuente y tamaño de letra
        $pdf->SetXY(150, 20);
        $pdf->Write(0, 'Factura: '.'#'. $factura);
        $pdf->SetXY(150, 25);
        $pdf->Write(0, 'Fecha: '. date('d-m-Y'));
        

        
        
        
        $pdf->Ln(35); //Salto de Linea
        $pdf->Cell(40,26,'',0,0,'C');
        $pdf->SetDrawColor(50, 0, 0, 0);
        $pdf->SetFillColor(0, 0, 0, 0); 
        //$pdf->SetTextColor(34,68,136);
        //$pdf->SetTextColor(255,204,0); //Amarillo
        //$pdf->SetTextColor(34,68,136); //Azul
        $pdf->SetTextColor(153,204,0); //Verde
        //$pdf->SetTextColor(204,0,0); //Marron
        //$pdf->SetTextColor(245,245,205); //Gris claro
        //$pdf->SetTextColor(100, 0, 0); //Color Carne
        $pdf->SetFont('courier','B', 25); 
        $pdf->Cell(100,6,'ORDEN DE COMPRA',0,0,'C');
        
        
        $pdf->Ln(15);
        $pdf->SetTextColor(0, 0, 0); 
        
        $pdf->SetFillColor(232,232,232);
        $pdf->SetFont('helvetica','B',9); //La B es para letras en Negritas
        $pdf->SetX(5);
        $pdf->Cell(30,6,'Producto',1,0,'C',1);
        $pdf->Cell(30,6,'Farmacéutica',1,0,'C',1);
        $pdf->Cell(16,6,'Precio',1,0,'C',1);
        $pdf->Cell(15,6,'Cantidad',1,0,'C',1);
        $pdf->Cell(25,6,'Proveedor',1,0,'C',1);
        $pdf->Cell(25,6,'Fecha Venc.',1,0,'C',1);
        $pdf->Cell(25,6,'Monto Total',1,0,'C',1);
        $pdf->Cell(25,6,'Fecha de Orden',1,1,'C',1); 
        
        $pdf->SetFont('helvetica','',9);

        
        
        $sqlOrdenCompra = conexion();
        $sqlOrdenCompra = $sqlOrdenCompra->query("SELECT * FROM orden_compra INNER JOIN proveedor ON orden_compra.proveedor_id=proveedor.proveedor_id WHERE orden_id =$orden_id");
        
        while ($rows = $sqlOrdenCompra->fetch()) {
                $pdf->SetX(5);
                $total = $rows['producto_stock'] * $rows['producto_precio'];
                $totalCompleto = $total;
                $pdf->Cell(30,6,$rows['producto_nombre'],1,0,'C');
                $pdf->Cell(30,6,$rows['producto_farmace'],1,0,'C');
                $pdf->Cell(16,6,$rows['producto_precio'],1,0,'C');
                $pdf->Cell(15,6,$rows['producto_stock'],1,0,'C');
                $pdf->Cell(25,6,$rows['proveedor_nombre'],1,0,'C');
                $pdf->Cell(25,6,$rows['fecha_venc'],1,0,'C');
                $pdf->Cell(25,6,$total. 'Bs.',1,0,'C');
                $pdf->Cell(25,6,(date('m-d-Y', strtotime($rows['fecha_orden']))),1,1,'C');
                
                $pdf->Output('Reporte_Compra_#'.$orden_id. '_' .date('d_M_y').'.pdf', 'I');
            }
}





?>