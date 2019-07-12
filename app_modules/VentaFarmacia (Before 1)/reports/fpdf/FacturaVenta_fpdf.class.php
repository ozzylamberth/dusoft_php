<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: FacturaVenta_fpdf.class.php,v 1.1 2010/06/03 20:43:44 hugo Exp $ 
 * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
 * @author Hugo F. Manrique
 */

/**
 * Clase Reporte: FacturaVenta_fpdf 
 * Reporte de la formula medica de optometria
 *
 * @package IPSOFT-SIIS  
 * @version $Revision: 1.1 $
 * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
 * @author Hugo F. Manrique
 */
class FacturaVenta_fpdf extends PDF {

    /**
     * Constructor de la clase
     */
    function FacturaVenta_fpdf() {
        
    }

    /**
     * Funcion para generar el archivo pdf
     *
     * @param array $prm Arreglo de parametros del request
     * @param string $nombre Nombre del reporte
     * @param string $pathImagen Ruta de la imagen
     *
     * @return boolean
     */
    function GetReporteFPDF($prm, $Dir, $pathImagen) {
        $rpt = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");

        $this->facturas = $rpt->ObtenerInformacionFacturas($prm);
        $this->tercero = $rpt->ObtenerInformacionTercero($this->facturas);

        $productos = $rpt->ObtenerInformaciondetalleFactura($prm);
        $this->path = $pathImagen;

        define('FPDF_FONTPATH', 'font/');
        $this->FPDF('P', 'mm', 'prueba');

        $this->SetLeftMargin(4);
        //$this->PDF('P','mm','mcarta1'); //ORIGINAL
        //$this->GenerarReporte($detalle,$totales,$usuario,$resumen,$nombre,$pathImagen); Comentado originalmente
        $this->AddPage();
        //$this->SetFont('Arial','',7); //ORIGINAL
        $this->SetFont('helvetica', 'B', 8);
        $this->SetDisplayMode('real', 'continuous');

        $ctl = AutoCarga::factory("ClaseUtil");


        $total = 0;
        $totIva = 0;
        //$vaLiva = 0;
        foreach ($productos as $key => $dtl) {
            $this->ln();
            $y = $this->GetY();
            $x = $this->GetX();
            //$this->SetX($x+23); //ORIGINAL
            $this->SetX($x); //added
            $this->SetY($y + 1);
            $this->SetFont('helvetica', 'B', 9);
            //$this->MultiCell(57,4,$ctl->NombreProducto($dtl,$prm['empresa_id']),0,'L'); //ORIGINAL
            $this->MultiCell(38, 3, $ctl->NombreProducto($dtl, $prm['empresa_id']), 0, 'L'); //ADDED

            $this->SetX($this->GetX() + 40);
            $this->SetY($this->GetY() - 3);
            $this->SetX($this->GetX() + 40);


            $iva = $rpt->Valida_IvaProd($dtl['codigo_producto']);
            // $x = $this->GetX();
            // $this->SetX($x+45);	
            // $y = $this->GetY();
            //$this->SetY($y-4);		
            $this->Cell(5, 3, $dtl['cantidad'], 0);
            $this->Cell(15, 3, "$ " . round(($dtl['cantidad'] * $dtl['total_costo']), 0), 0, 1, 'R');
            //$this->Cell(50,3,"IVA: ",0,0,"L");

            $vaLiva = 0;
            if (FormatoValor($iva['porc_iva']) <> 0) {
                $this->SetX($this->GetX() + 48);

                //valor del iva si toca adicionarlo al producto
                $vaLiva = round((($dtl['cantidad'] * $dtl['total_costo']) * ($iva['porc_iva'] / 100)), 0);

                $this->Cell(15, 3, "IVA " . round($iva['porc_iva'], 0) . "% ", 0, 1, "L");
            }

            //$this->Cell(23,4,$dtl['codigo_producto'],0); //ORIGINAL
            //$this->SetX($x+80);
            //$this->Cell(15,4,$dtl['lote'],0);
            //$this->Cell(15,4,$dtl['fecha_vencimiento'],0);
            //$this->Cell(20,4,FormatoValor($dtl['cantidad']),0,0,'R');
            //$this->Cell(20,4,"$ ".Formatovalor($dtl['cantidad']*$dtl['total_costo']),0,1,'R');

            $total += round(($dtl['total_costo'] * $dtl['cantidad']), 0);
            $totIva += $vaLiva;
        }

        $x1 = $this->GetX();
        $y1 = $this->GetY();

        $this->Line($x1 + 1, $y1, $x1 + 60, $y1);

        $this->SetFont('helvetica', 'B', 10);
        $this->SetX($this->GetX() + 16);
        $this->Cell(10, 5, "SUBTOTAL:", 0, 0, 'L');
        $this->SetX($this->GetX() + 26);
        $this->Cell(8, 5, "$ " . $total, 0, 1, 'R');
        $this->SetX($this->GetX() + 16);
        $this->Cell(10, 5, "TOTAL IVA:", 0, 0, 'L');
        $this->SetX($this->GetX() + 26);
        $this->Cell(8, 5, "$ " . $totIva, 0, 1, 'R');
        $this->SetX($this->GetX() + 16);
        $this->Cell(10, 5, "VALOR TOTAL:", 0, 0, 'L');
        $this->SetX($this->GetX() + 26);
        $this->Cell(8, 5, "$ " . ($total + $totIva), 0, 1, 'R');
        $this->ln(10);


        //$this->SetY($this->GetY() +7);
        //$this->SetX($this->GetX()); 
        //$this->Cell(45,5,"SON: ".strtoupper($ctl->num2letras(FormatoValor($total,null,false),false))." PESOS",0);
        // $this->Cell(20,5,"TOTAL:",0,0,'R'); 
        // $this->Cell(20,5,"$ ".Formatovalor($total),0,0,'R');

        $this->SetLineWidth(0.3);

        $this->Output($Dir, 'F');
        return true;
    }

    /**
     * Cabecera de la factura
     */
    function Header() {
        $factura_fiscal = str_pad($this->facturas['factura_fiscal'], $this->facturas['numero_digitos'], "0", STR_PAD_LEFT);
        //$this->SetFont('Arial','B',7); //ORIGINAL
        $this->SetFont('helvetica', 'B', 9);

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //nombre aplicacion sinergias
        $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . "/" . $path_app . "/themes/HTML/logoventa.png";
        //$this->Image($this->path.'/logocliente.png',10,10,20); comentado inicialmente
        //$this->Image($ruta_imagen,7,10,19);
        $this->ln();

        $y = $this->GetY();
        $x = $this->GetX();
        $this->SetX($x + 5);
        $this->SetY($y + 8);
        //$this->Image($ruta_imagen,10,5,31);


        $this->SetX($this->GetX + 4);
        //$this->Cell(64,4,$this->facturas['razon_social'],0); comentado inicialmente
        $this->Cell(57, 4, $this->facturas['descripcion'] . "   FACTURA No. " . $this->facturas['prefijo'] . "-" . $factura_fiscal, 0);
        //$this->Cell(57,4,"ZONA SALUD   FACTURA No. ".$this->facturas['prefijo']."-".$factura_fiscal,0);
        $this->ln();

        $this->MultiCell(54, 3, $this->facturas['texto1'], 0, 'C'); //Informacion RES. DIAN
        $this->ln();
        $this->SetFont('helvetica', 'B', 9);
        //$this->Cell(64,4,$this->facturas['razon_social']." - ".$this->facturas['texto2'],0); //ORIGINAL
        $this->Cell(67, 4, $this->facturas['razon_social'] . "-" . $this->facturas['texto2'], 0); //ADDED
        //$this->Cell(25,4,$this->facturas['tipo_id_empresa']." ".$this->facturas['id'],0);   //ORIGINAL
        $this->ln();
        //$this->SetFont('Arial','',7); //ORIGINAL

        $this->Cell(54, 4, $this->facturas['texto3'] . " Tel: " . $this->facturas['mensaje'], 0);
        //$this->Cell(54,4,$this->facturas['direccion']." Tel: ".$this->facturas['telefonos'],0);
        //$this->Cell(54,4,"TELEFONOS: ".$this->facturas['telefonos'],0);
        $this->ln();

        //$this->Cell(20,4,"FACTURA DE VENTA ",0,0); //ORIGINAL
        //$this->Cell(20,4,"FACTURA DE VENTA No. ".$this->facturas['prefijo']."".$factura_fiscal,0,0);
        //$this->SetFont('Arial','B',7); //ORIGINAL
        //$this->SetFont('helvetica','B',8);
        //$this->Cell(60,4,"No. ".$this->facturas['prefijo']."".$factura_fiscal,0,1); //ORIGINAL
        $this->SetX($this->GetX + 13);
        $this->Cell(60, 4, $this->facturas['municipio'] . " - " . $this->facturas['departamento'], 0, 0);
        $this->ln();

        $x1 = $this->GetX();
        $y1 = $this->GetY();
        $this->Line($x1 + 1, $y1, $x1 + 60, $y1);
        //$this->ln();	

        $this->SetY($this->GetY() + 1);
        $this->SetX($this->GetX());
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(54, 4, "CLTE: " . $this->tercero['nombre_tercero'], 0);
        $this->ln();
        $this->Cell(54, 4, $this->tercero['tipo_id_tercero'] . ". " . $this->tercero['tercero_id'] . "   Tel. " . $this->tercero['telefono'], 0, 1);
        $this->ln();

        $x1 = $this->GetX();
        $y1 = $this->GetY();
        $this->Line($x1 + 1, $y1 - 3, $x1 + 60, $y1 - 3);

        $this->SetFont('helvetica', 'B', 9);
        $this->SetX($this->GetX());
        $this->Cell(54, 4, "PRODUCTO" . "                  CANT     VALOR", 0);


        $this->SetY($this->GetY());
        $this->SetX($this->GetX());
        $x1 = $this->GetX();
        $y1 = $this->GetY();
        $this->Line($x1 + 1, $y1 + 4, $x1 + 60, $y1 + 4);


        /*
          $this->SetX($this->GetX+28);
          //$this->SetFont('Arial','',7); //ORIGINAL
          $this->SetFont('Arial','',5);
          $this->Cell(50,4,"DIRECCION: ".$this->facturas['direccion'],0);
          $this->Cell(40,4,"TELEFONOS: ".$this->facturas['telefonos'],0);
          $this->Cell(42,4,$this->facturas['municipio']." - ".$this->facturas['departamento'],0,1);

          $y = $this->GetY();
          $this->SetX($this->GetX+28);
          $this->SetFont('Arial','',5);
          $this->MultiCell(120,3,$this->facturas['texto1'],0,'C'); //Informacion RES. DIAN
          $y1 = $this->GetY();
          $suma = 1;
          if($y1-$y < 6)
          $suma = 7-($y1-$y);

          //$this->SetFont('Arial','',7); //ORIGINAL
          $this->SetFont('Arial','',5);
          $this->SetY($this->GetY()+$suma);
          $x1 = $this->GetX();
          $y1 = $this->GetY();
          $this->Line($x1,$y1,$x1+150,$y1);
          $this->Cell(15,4,"CLIENTE:",0);
          //$this->SetFont('Arial','B',7); //ORIGINAL
          $this->SetFont('Arial','B',5);
          $this->Cell(55,4,$this->tercero['nombre_tercero'],0);
          $this->Cell(80,4,$this->tercero['tipo_id_tercero']." ".$this->tercero['tercero_id'],0,1);

          //$this->SetFont('Arial','',7); //ORIGINAL
          $this->SetFont('Arial','',5);
          $this->Cell(15,3,"DIRECCION:",0);
          $this->Cell(55,3,$this->tercero['direccion'],0);
          $this->Cell(15,3,"TELEFONO:",0);
          $this->Cell(65,3,$this->tercero['telefono'],0,1);

          $x1 = $this->GetX();
          $y1 = $this->GetY();
          $this->Line($x1,$y1,$x1+150,$y1);

          $this->Cell(80,4,"PRODUCTO",0,0,"C");
          $this->Cell(15,4,"LOTE",0,0,"C");
          $this->Cell(15,4,"FECHA",0,0,"C");
          $this->Cell(20,4,"CANT",0,0,"R");
          $this->Cell(20,4,"VALOR",0,1,"R");


          $x1 = $this->GetX();
          $y1 = $this->GetY();
          $this->Line($x1,$y1,$x1+150,$y1);
         */
    }

    /**
     * Pie de pagina del informe
     */
    function Footer() {
        $rpt = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");
        $today = date("m-d-Y, g:i a");


        $this->Cell(54, 3, "DOMICILIOS: " . $this->facturas['mensaje'], 0, 1, 'L');
        $this->SetY($this->GetY() + 8);
        $this->SetX($this->GetX() + 3);
        $name = $rpt->Get_NombreUser(UserGetUID());
        //$this->SetY();
        $this->SetFont('Arial', 'B', 9);
        //$this->Cell(150,3,"GENERADO POR SOFTWARE DUSOFT -  DUANA & CIA. LTDA",0,1,'C'); //ORIGINAL 
        $this->Cell(54, 3, "SOFT. DUSOFT - " . $today, 0, 1, 'C');
        $this->Cell(54, 3, " USUARIO: " . $name['nombre'], 0, 1, 'L');
    }

}

?>