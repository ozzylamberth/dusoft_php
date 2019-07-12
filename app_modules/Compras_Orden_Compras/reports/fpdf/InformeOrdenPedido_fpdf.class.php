<?php

/**
 * $Id: InformeOrdenPedido_fpdf.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * 
 * @author Sandra Viviana Pantoja
 */
class InformeOrdenPedido_fpdf {

    var $error = "";

    /**
     * Constructor de la clase
     */
    function InformeOrdenPedido_fpdf() {
        
    }

    /**
     * Funcion para generar el archivo xml
     *
     * @param array $parametros Arreglo de oarametros del request
     *
     * @return boolean
     */
    function GetReporteFPDF($datos, $nombre, $pathImagen) {

        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $OrdenCompra = $mdl->ConsultarOrdenComprasGeneradas_reportePDF($datos['orden_pedido_id']);
        $OrdenCompra_Detalle = $mdl->ConsultarDetalleDeOrdenCompra($datos['orden_pedido_id']);
        $usuario = $mdl->consultarInformacionUsuarioActual();
        $UnidadNegocio = $mdl->UnidadesNegocio($datos['codigo_unidad_negocio']);

        $contrato_proveedor = $mdl->consultar_contrato_proveedor($OrdenCompra['codigo_proveedor_id']);

        $this->GenerarInformacion($nombre, $OrdenCompra, $OrdenCompra_Detalle, $usuario, $UnidadNegocio, $datos, $pathImagen, $contrato_proveedor);
        return true;
    }

    /**
     *
     */
    function GenerarInformacion($Dir, $OrdenCompra, $OrdenCompra_Detalle, $usuario, $UnidadNegocio, $datos, $pathImagen, $contrato_proveedor) {

        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        define('FPDF_FONTPATH', 'font/');
        $pdf = new PDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 7);
        $x = $pdf->GetX();
        //$pdf->SetX($x + 15);

        if ($datos['codigo_unidad_negocio'] == "") {
            $imagen = 'logocliente.png';
        } else {
            $imagen = $UnidadNegocio[0]['imagen'];
        }

        //$pdf->Image($pathImagen.'/'.$imagen, 15, 9, 20,8);
        if ($OrdenCompra['codigo_unidad_negocio'] == 13) {
            $pdf->Image($pathImagen . '/mindefensa.jpg', 15, 9, 30, 8);
        } else {
            $pdf->Image($pathImagen . '/' . $imagen, 15, 9, 20, 8);
        }
        $y = $pdf->GetY();
        if ($datos['codigo_unidad_negocio'] == "") {
            $pdf->Cell(130, 4, $OrdenCompra['razon_social'] . "ORDENES DE COMPRA", 0, 1, "C");
            $pdf->Cell(130, 4, $OrdenCompra['tipo_id_tercero'] . "-" . $OrdenCompra['tercero_id'], 0, 1, "C");
        } else {
            $pdf->Cell(130, 4, $UnidadNegocio[0]['descripcion'] . "  -  ORDENES DE COMPRA", 0, 1, "C");
            $pdf->Cell(130, 4, "", 0, 1, "C");
        }
        $pdf->Ln(3); //2
        //$pdf->SetX($x + 5);
        $pdf->Cell(60, 4, "ORDEN DE COMPRA:   " . $OrdenCompra['orden_pedido_id'], 0, 0, "J");
        //$pdf->SetX($x + 82);
        $pdf->Cell(80, 2, "FECHA:  " . $OrdenCompra['fecha_registro'], 0, 0, "J");
        //$pdf->SetX($x + 132);
        $pdf->Cell(139, 4, "USUARIO:  " . $OrdenCompra['nombre'], 0, 1, "J");
        //$pdf->SetX($x + 5);
        $pdf->Cell(60, 4, "PROVEEDOR:  " . $OrdenCompra['tipo_id_tercero'] . " - " . $OrdenCompra['tercero_id'], 0, 0, "J");
        //$pdf->SetX($x + 82);
        $pdf->MultiCell(55, 4, $OrdenCompra['nombre_tercero'], 0, "J");
        //$pdf->SetX($x + 5);
        $pdf->Cell(60, 4, "DIRECCION:  " . $OrdenCompra['direccion'], 0, 1, "J");
        //$pdf->SetX($x + 82);
        $pdf->Cell(80, 4, "TELEFONO:   " . $OrdenCompra['telefono'], 0, 1, "J");
        //$pdf->SetX($x + 5);
        if ($OrdenCompra['estado'] == '1')
            $mensaje = " ACTIVO";
        else
        if ($OrdenCompra['estado'] == '0')
            $mensaje = " RECIBIDO COMPLETAMENTE  ";
        else
        if ($OrdenCompra['estado'] == '2')
            $mensaje = " DOCUMENTO ANULADO ";

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(140, 4, "ESTADO DE LA ORDEN DE COMPRA:   " . $mensaje . " ", 0, "J");
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 6); //7
        //$pdf->SetX($x + 5); //5
        $pdf->MultiCell(140, 4, "OBSERVACIONES:   " . $OrdenCompra['observacion'], 0, "J"); //140,4

        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 6); //7
        //$pdf->SetX($x + 5);
        $pdf->MultiCell(180, 4, " {$contrato_proveedor['observaciones']} \n ENVIAR CERTIFICADOS DE CALIDAD FECHA DE VENCIMIENTO NO MENOR A 2 AÑOS MARCAR USO  INSTITUCIONAL PROHIBIDA SU VENTA", 0, "J");

        $pdf->SetLineWidth(0.1);
        $pdf->Rect($x, 19, 195, 25, '');
        $pdf->Rect($x, 45, 195, 15, ''); //Rect($X+15,40, 195,7, '');
        $pdf->Ln(7);
        $pdf->SetFont('Arial', 'B', 7);

        //$pdf->SetX($x + 5);
        $pdf->Cell(195, 4, "PRODUCTOS:", 1, 1, "C");

        //$pdf->SetX($x + 5);
        $pdf->Cell(20, 4, "CODIGO:", 1, 0, "J");

        //$pdf->SetX($x + 25);
        $pdf->Cell(60, 4, "DESCRIPCION:", 1, 0, "J");


        //$pdf->SetX($x + 85);
        $pdf->Cell(35, 4, "OBSERVACION:", 1, 0, "J");



        //$pdf->SetX($x + 120);
        $pdf->Cell(10, 4, "CAN:", 1, 0, "J");


        //$pdf->SetX($x + 130);
        $pdf->Cell(15, 4, "%IVA:", 1, 0, "J");

        //$pdf->SetX($x + 145);
        $pdf->Cell(20, 4, "VALOR:", 1, 0, "J");

        //$pdf->SetX($x + 165);
        $pdf->Cell(15, 4, "IVA:", 1, 0, "J");

        //$pdf->SetX($x + 180);
        $pdf->Cell(20, 4, "TOTAL:", 1, 1, "J");

        $pdf->SetFont('Arial', '', 5);


        $widths = array(20, 60, 35, 10, 15, 20, 15, 20);
        foreach ($OrdenCompra_Detalle as $key => $detalle) {

            if ($detalle['numero_unidades'] > 0) {

                $politicas_producto = $mdl->consultar_politicas_productos_contrato($OrdenCompra['empresa_id'], $OrdenCompra['codigo_proveedor_id'], $detalle['codigo_producto']);

                $iva = ($detalle['porc_iva'] / 100);
                $total_producto = ($detalle['numero_unidades'] * $detalle['valor']);
                $iva_producto = $total_producto * $iva;

                $iva_acumulado = $iva_acumulado + $iva_producto;
                $subtotal = $subtotal + $total_producto;

                $data = array(
                    $detalle['codigo_producto'],
                    $detalle['nombre'],
                    $politicas_producto['politica'],
                    $detalle['numero_unidades'],
                    " %" . FormatoValor($detalle['porc_iva'], 2),
                    " $" . FormatoValor($detalle['valor'], 2),
                    "$" . FormatoValor($iva_producto, 2),
                    "$" . FormatoValor(($total_producto + $iva_producto), 2)
                );


                //Calculate the height of the row
                $nb = 0;
                for ($i = 0; $i < count($data); $i++)
                    $nb = max($nb, $this->GetMultiCellHeight($pdf, $widths[$i], $data[$i]));

                $h = 5 * $nb;

                if ($pdf->GetY() + $h > $pdf->PageBreakTrigger)
                    $pdf->AddPage($pdf->CurOrientation);


                for ($i = 0; $i < count($data); $i++) {

                    $w = $widths[$i];
                    $a = 'L';
                    //Save the current position
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    //Draw the border
                    $pdf->Rect($x, $y, $w, $h);
                    //Print the text
                    $pdf->MultiCell($w, 5, $data[$i], 0, $a);
                    //Put the position to the right of the cell
                    $pdf->SetXY($x + $w, $y);
                }
                $pdf->Ln($h);
            }

             /*if ($detalle['numero_unidades'] > 0) {
              $iva = ($detalle['porc_iva'] / 100);
              $total_producto = ($detalle['numero_unidades'] * $detalle['valor']);
              $iva_producto = $total_producto * $iva;

              $iva_acumulado = $iva_acumulado + $iva_producto;
              $subtotal = $subtotal + $total_producto;

              $y = $pdf->GetY();

              $pdf->SetX($x + 5);
              //$pdf->Cell(20, 4, $detalle['codigo_producto'], 1, 0, "L");
              $pdf->MultiCell(20, 6, $detalle['codigo_producto'], 1, "L");

              $pdf->SetXY($x + 25, $y);
              $pdf->MultiCell(60, 6, $detalle['nombre'], 1, "J");

              //$pdf->SetX($x + 85);
              $pdf->SetXY($x + 85, $y);
              $pdf->MultiCell(35, 6, $politicas_producto['politica'], 1, "J");

              //$pdf->SetX($x + 120);
              $pdf->SetXY($x + 120, $y);
              $pdf->Cell(10, 6, FormatoValor($detalle['numero_unidades']), 1, 0, "L");

              $pdf->SetX($x + 130);
              $pdf->Cell(15, 6, " %" . FormatoValor($detalle['porc_iva'], 2), 1, 0, "L");

              $pdf->SetX($x + 145);
              $pdf->Cell(20, 6, " $" . FormatoValor($detalle['valor'], 2), 1, 0, "L");

              $pdf->SetX($x + 165);
              $pdf->Cell(15, 6, "$" . FormatoValor($iva_producto, 2), 1, 0, "L");

              $pdf->SetX($x + 180);
              $pdf->Cell(20, 6, "$" . FormatoValor(($total_producto + $iva_producto), 2), 1, 1, "L");

              //$pdf->Ln(0);
              } */
        }
        //exit();
        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'B', 7);
        //$pdf->SetX($x + 5);
        $pdf->Cell(195, 4, "SUBTOTAL: $" . FormatoValor($subtotal, 2), 1, 1, "J");
        //$pdf->SetX($x + 5);
        $pdf->Cell(195, 4, "IVA: $" . FormatoValor($iva_acumulado, 2), 1, 1, "J");
        //$pdf->SetX($x + 5);
        $pdf->Cell(195, 4, "TOTAL: $" . FormatoValor(($subtotal + $iva_acumulado), 2), 1, 1, "J");
        $pdf->Ln(4);

        //$pdf->SetX($x + 5);
        $pdf->Cell(80, 4, "Imprimio: " . $usuario[0]['nombre'], 0, 0, "J");
        //$pdf->SetX($x + 120);
        $pdf->Cell(110, 4, "Fecha Impresion: " . date("d/m/Y - h:i a"), 0, 0, "L");

        $pdf->WriteHTML($html);
        $pdf->Output($Dir, 'F');
        return true;
    }

    function GetMultiCellHeight($pdf, $w, $txt) {
        //Computes the number of lines a MultiCell of width w will take

        $cw = $pdf->CurrentFont['cw'];

        if ($w == 0)
            $w = $pdf->w - $pdf->rMargin - $pdf->x;


        $wmax = ($w - 2 * $pdf->cMargin) * 1000 / $pdf->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

}

?>