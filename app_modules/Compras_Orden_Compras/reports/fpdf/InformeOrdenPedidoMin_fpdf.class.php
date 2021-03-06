<?php 
 	/**
  * $Id: InformeOrdenPedidoMin_fpdf.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2013 DUANA & CIA
  * @package IPSOFT-SIIS
  * 
  * @author 
  */
  class InformeOrdenPedidoMin_fpdf
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformeOrdenPedidoMin_fpdf(){}
    /**
    * Funcion para generar el archivo xml
    *
    * @param array $parametros Arreglo de oarametros del request
    *
    * @return boolean
    */
    function GetReporteFPDF($datos,$nombre,$pathImagen)
    {
	
		$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
		$OrdenCompra=$mdl->ConsultarOrdenComprasGeneradas_reportePDF($datos['orden_pedido_id']);
		$OrdenCompra_Detalle=$mdl->ConsultarDetalleDeOrdenCompra($datos['orden_pedido_id']);
	    $usuario = $mdl->consultarInformacionUsuarioActual();
		$UnidadNegocio=$mdl->UnidadesNegocio($datos['codigo_unidad_negocio']);
		
		$this->GenerarInformacion($nombre,$OrdenCompra,$OrdenCompra_Detalle,$usuario,$UnidadNegocio,$datos,$pathImagen);
		return true;
    }
    /**
    *
    */
    function GenerarInformacion($Dir,$OrdenCompra,$OrdenCompra_Detalle,$usuario,$UnidadNegocio,$datos,$pathImagen)
    {
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','letter');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',7);
		$x = $pdf->GetX();
		$pdf->SetX($x+15);
		
		if($datos['codigo_unidad_negocio']=="")
		{
		$imagen ='logocliente.png';
		}else
		{
		$imagen = $UnidadNegocio[0]['imagen'];

		}
	
	  $pdf->Image($pathImagen.'/'.$imagen, 15, 9, 20,8);
      $y = $pdf->GetY(); 
	  if($datos['codigo_unidad_negocio']=="")
	  {
		$pdf->Cell(130,4,$OrdenCompra['razon_social']."ORDENES DE COMPRA",0,1,"C");
		$pdf->Cell(130,4,$OrdenCompra['tipo_id_tercero']."-".$OrdenCompra['tercero_id'],0,1,"C");
		
      }else
      {
		$pdf->Cell(130,4,$UnidadNegocio[0]['descripcion']."  -  ORDENES DE COMPRA",0,1,"C");
	    $pdf->Cell(130,4,"",0,1,"C");
      }	  
		$pdf->Ln(3);//2
		$pdf->SetX($x+5);
		$pdf->Cell(60,4,"ORDEN DE COMPRA:   ".$OrdenCompra['orden_pedido_id'],0,0,"J");
		$pdf->SetX($x+82);
		$pdf->Cell(80,2,"FECHA:  ".$OrdenCompra['fecha_registro'],0,0,"J");
		$pdf->SetX($x+132);
		$pdf->Cell(139,4,"USUARIO:  ".$OrdenCompra['nombre'],0,1,"J");
		$pdf->SetX($x+5);
		$pdf->Cell(60,4,"PROVEEDOR:  ".$OrdenCompra['tipo_id_tercero']." - ".$OrdenCompra['tercero_id'],0,0,"J");
		$pdf->SetX($x+82);
		$pdf->MultiCell(55,4,$OrdenCompra['nombre_tercero'],0,"J");
		$pdf->SetX($x+5);
		$pdf->Cell(60,4,"DIRECCION:  ".$OrdenCompra['direccion'],0,0,"J");
		$pdf->SetX($x+82);
		$pdf->Cell(80,4,"TELEFONO:   ".$OrdenCompra['telefono'],0,1,"J");
		$pdf->SetX($x+5);
		if($OrdenCompra['estado']=='1')
        $mensaje =" ACTIVO";
        else
        if($OrdenCompra['estado']=='0')
        $mensaje =" RECIBIDO COMPLETAMENTE  ";
        else
        if($OrdenCompra['estado']=='2')
        $mensaje =" DOCUMENTO ANULADO ";
		
		$pdf->SetFont('Arial','B',7);
		$pdf->MultiCell(140,4,"ESTADO DE LA ORDEN DE COMPRA:   ".$mensaje." ",0,"J");
		$pdf->Ln(4);
		$pdf->SetFont('Arial','',6);//7
	    $pdf->SetX($x+5); //5
		$pdf->MultiCell(140,4,"OBSERVACIONES:   ".$OrdenCompra['observacion'],0,"J"); //140,4
		
		$pdf->Ln(2); 
		$pdf->SetFont('Arial','',6);//7
	    $pdf->SetX($x+5);
		$pdf->MultiCell(180,4,"ENVIAR CERTIFICADOS DE CALIDAD FECHA DE VENCIMIENTO NO MENOR A 2 A?OS MARCAR USO  INSTITUCIONAL PROHIBIDA SU VENTA",0,"J");
		
	    $pdf->SetLineWidth(0.1);
		$pdf->Rect($X+15,19, 195,20, ''); 
		$pdf->Rect($X+15,40, 195,13, ''); //Rect($X+15,40, 195,7, '');
		$pdf->Ln(7);
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($x+5);
		$pdf->Cell(195,4,"PRODUCTOS:",1,1,"C");
		
		$pdf->SetX($x+5);
		$pdf->Cell(25,4,"CODIGO:",1,0,"J");
		$pdf->SetX($x+30);
		$pdf->Cell(75,4,"DESCRIPCION:",1,0,"J");
		$pdf->SetX($x+105);
		$pdf->Cell(10,4,"CAN:",1,0,"J");
		$pdf->SetX($x+115);
		$pdf->Cell(12,4,"%IVA:",1,0,"J");
		$pdf->SetX($x+127);
		$pdf->Cell(25,4,"VALOR:",1,0,"J");
		$pdf->SetX($x+152);
		$pdf->Cell(20,4,"VALOR IVA:",1,0,"J");
		$pdf->SetX($x+172);
		$pdf->Cell(28,4,"VALOR TOTAL:",1,1,"J");
		$pdf->SetFont('Arial','',7); /***/
		foreach ($OrdenCompra_Detalle as $key =>$detalle)
		{
		if($detalle['numero_unidades']>0){
		

					$iva = ($detalle['porc_iva']/100);
					//$total_producto = ($detalle['numero_unidades']*$detalle['valor']);
					//$iva_producto = $total_producto * $iva;

					//$iva_acumulado = $iva_acumulado + $iva_producto;
					//$subtotal = $subtotal + $total_producto;
		
					$pdf->SetX($x+5);
					$pdf->Cell(25,4,$detalle['codigo_producto'],1,0,"L");
				
					$pdf->SetX($x+105);
					$pdf->Cell(10,4,FormatoValor($detalle['numero_unidades']),1,0,"L");
					$pdf->SetX($x+115);
					$pdf->Cell(12,4," %".FormatoValor($detalle['porc_iva'],2),1,0,"L");
					$pdf->SetX($x+127);

					//$pdf->Cell(25,4," $".FormatoValor($detalle['valor'],2),1,0,"L");
					$pdf->Cell(25,4," $",1,0,"L");
					$pdf->SetX($x+152);
					//$pdf->Cell(20,4,"$".FormatoValor($iva_producto,2),1,0,"L");
					$pdf->Cell(20,4,"$",1,0,"L");
					$pdf->SetX($x+172);
					//$pdf->Cell(28,4,"$".FormatoValor(($total_producto+$iva_producto),2),1,0,"L");
					$pdf->Cell(28,4,"$",2),1,0,"L");
		  	
					$pdf->SetX($x +30);
		            $pdf->MultiCell(75,4,$detalle['nombre'],1,"J");
		}
		/*else{
					$iva = 0;
					$total_producto = 0;
					$iva_producto = 0;

					$iva_acumulado =0;
					$subtotal = 0;
		}*/
		
		}
		$pdf->SetFont('Arial','B',7);
		$pdf->SetX($x+5);
		
		
		//$pdf->Cell(195,4,"SUBTOTAL: $".FormatoValor($subtotal,2),1,1,"J");
		$pdf->Cell(195,4,"SUBTOTAL: $",1,1,"J");
		$pdf->SetX($x+5);
		//$pdf->Cell(195,4,"IVA: $".FormatoValor($iva_acumulado,2),1,1,"J");
		$pdf->Cell(195,4,"IVA: $",1,1,"J");
		$pdf->SetX($x+5);
		//$pdf->Cell(195,4,"TOTAL: $".FormatoValor(($subtotal+$iva_acumulado),2),1,1,"J");
		$pdf->Cell(195,4,"TOTAL: $",1,1,"J");
		$pdf->Ln(4);
		
		
		$pdf->SetX($x+5);
		$pdf->Cell(80,4,"Imprimio: ".$usuario[0]['nombre'],0,0,"J");
		$pdf->SetX($x+120);
		$pdf->Cell(110,4,"Fecha Impresion: ".date("d/m/Y - h:i a"),0,0,"L");

		$pdf->WriteHTML($html);
		$pdf->Output($Dir, 'F');
		return true;
    }
  }
?>