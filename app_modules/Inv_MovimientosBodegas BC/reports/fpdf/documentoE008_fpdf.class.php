<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: FormulaOptometria_fpdf.class.php,v 1.1 2010/03/25 17:44:41 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase Reporte: Comprobante_fpdf 
  * Reporte de la formula medica de optometria
  *
  * @package IPSOFT-SIIS  
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  class documentoE008_fpdf
  {
    /**
    * Constructor de la clase
    */
    function documentoE008_fpdf(){}
    /**
    * Funcion para generar el archivo pdf
    *
    * @param array $prm Arreglo de parametros del request
    * @param string $nombre Nombre del reporte
    * @param string $pathImagen Ruta de la imagen
    *
    * @return boolean
    */
    function GetReporteFPDF($prm,$nombre,$pathImagen)
    {
      $prm = SessionGetVar("DocumentoDespacho_E008");
      $frm = AutoCarga::factory('MovBodegasSQL', 'classes', 'app', 'Inv_MovimientosBodegas');
      $resultado = $frm->SacarDocumento($prm['empresa_id'],$prm['prefijo'],$prm['numero']);
      $documento = $frm->GetDocumentoDespacho($prm['empresa_id'],$prm['prefijo'],$prm['numero']);
      $empresa = $frm->ColocarEmpresa($prm['empresa_id']);
      $centro = $frm->ColocarCentro($resultado['centro_utilidad']);
      $bodega = $frm->bodegasname($resultado['bodega']);

      $this->GenerarReporte($resultado,$documento,$empresa[0],$centro,$bodega,$nombre,$pathImagen);
      return true;
    }
    /**
    * Funcion donde se crea el reporte pdf
    *
    * @param array $detalle datos de la formula
    * @param array $totales datos del paciente
    * @param array $usuario datos del profesional
    * @param string $Dir Nombre del reporte
    * @param string $pathImagen Ruta de la imagen
    *
    * @return boolean
    */
    function GenerarReporte($resultado,$documento,$empresa,$centro,$bodega,$Dir,$pathImagen)
    {
      define('FPDF_FONTPATH','font/');
      $pdf=new PDF('P','mm','letter');
      $pdf->AddPage();
      $pdf->Image($pathImagen.'/logocliente.png',20,10,20);
      $pdf->SetFont('Arial','',12);

      $tmn = 780;
      $ctl = AutoCarga::factory('ClaseUtil');
      $html .= "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='50' ALIGN='center'> </td>";
      $html .= "</tr>";
      $html .= "</table>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',12);
      
      $html  = "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='50' ALIGN=CENTER>DOCUMENTO Nº.....".$resultado['prefijo']." ".$resultado['numero']."</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',7);
      
      $html  = "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='50' ALIGN=LEFT>DIRECCION ".$empresa['direccion']."     TELEFONO ".$empresa['telefonos']."</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $html .= "<table WIDTH='".$tmn."' border='1'>";
      $html .= "<tr><td WIDTH='".$tmn."' height='1'>&nbsp;</td></tr>";
      $html .= "</table>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',12);
      
      $html  = "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='50' ALIGN='center'>REMISION DE MEDICAMENTOS</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',7);
      $html  = "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='25' >";
      $html .= "BODEGA: ".$bodega[0]['descripcion'];
      $html .= "</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='25' >";
      $html .= "".$documento['tipo'].": ".$documento['nombre_tercero']."     DIRECCION: ".$documento['direccion']."   ".$documento['municipio']."  ".$documento['departamento'];
      $html .= "</td>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='25' >";
      $html .= "".$documento['tipo_id_tercero'].": ".$documento['tercero_id'];
      $html .= "</td>";
      $html .= "</tr>"; 
      $html .= "</table >";
      $html .= "<table WIDTH='".$tmn."' border='1'>";
      $html .= "<tr><td WIDTH='".$tmn."' height='1'>&nbsp;</td></tr>";
      $html .= "</table>";
      $html .= "<table WIDTH='".$tmn."' rules=\"all\">";
      $html .= "<tr >\n";
      $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.10)."' >CODIGO</td>\n";
      $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.40)."'>DESCRIPCION DEL PRODUCTO</td>\n";
      $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.10)."'>CANTIDAD</td>\n";
      if($documento['tipo'] == 'CLIENTE')
      {
        $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.10)."'>COSTO UNI</td>\n";
        $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.10)."'>COSTO UNI(IVA)</td>\n";
        $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.10)."'>COSTO TOT</td>\n";
        $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.05)."' >% GRAV</td>\n";
      }
      else
        $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.25)."'> </td>\n";
      $html .= "</tr>\n";
      $valorTotal = 0;
      foreach($resultado['DETALLE'] as $doc_val=>$valor)
      {
			if(($resultado['DATOS_ADICIONALES']['TIPO DE DESPACHO :'])=='CLIENTES')
			{
			$IvaTotal += ($valor['iva']*$valor['cantidad']);
			$subtotal += ($valor['valor_unitario']*$valor['cantidad']);
			$valor_unitario = ($valor['valor_unitario']);
			$valor_unitario_iva = ($valor['valor_unitario_iva']);
			$total_producto=($valor['valor_unitario_iva']*$valor['cantidad']);
			}
		$html .= "<tr>\n";
        $html .= "<td ALIGN='LEFT' WIDTH='".intval($tmn*0.10)."'>\n";
        $html .= "".$valor['codigo_producto'];
        $html .= "</td>\n";
        $html .= "<td ALIGN='LEFT' WIDTH='".intval($tmn*0.40)."'>\n";
        $html .= "".substr($valor['nombre']  ,0 ,40 );
        $html .= "</td>\n";
        $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.10)."'>\n";
        $html .= "".FormatoValor($valor['cantidad'],0);
        $html .= "</td>\n";        

        if($documento['tipo'] == 'CLIENTE')
        {
          $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.10)."'>\n";
          $html .= "$".FormatoValor($valor_unitario,4);
          $html .= "</td>\n";   
          $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.10)."'>\n";
          $html .= "$".FormatoValor($valor_unitario_iva,4);
          $html .= "</td>\n";        
          $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.10)."'>\n";
          $html .= "$".FormatoValor($total_producto,4);
          $html .= "</td>\n";        
          $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.05)."'>\n";
          $html .= "$".$valor['porcentaje_gravamen'];
          $html .= "</td>\n";
          $valorTotal += $valor['total_costo_pedido'];
        }
        else
          $html .= "<td ALIGN='CENTER' WIDTH='".intval($tmn*0.25)."'> </td>\n";
        $html .= "</tr>\n";
      }
      $html .= "</table>";
      $html .= "<table WIDTH='".$tmn."' border='1'>";
      $html .= "<tr><td WIDTH='".$tmn."' height='1'>&nbsp;</td></tr>";
      $html .= "</table>";
      if($documento['tipo'] == 'CLIENTE')
      {
        $html .= "<table WIDTH='".$tmn."'>";
        $html .= "<tr>";
        $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.85)."'>TOTAL</td>\n";
        $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.10)."'>$".FormatoValor(($subtotal+$IvaTotal),4)."</td>\n";
        $html .= "<td ALIGN='RIGHT' WIDTH='".intval($tmn*0.05)."'></td>\n";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "<table WIDTH='".$tmn."' border='1'>";
        $html .= "<tr><td WIDTH='".$tmn."' height='1'>&nbsp;</td></tr>";
        $html .= "</table>";
      }
      
      $html .= "<BR>";

      $pdf->WriteHTML($html);
      $pdf->SetLineWidth(0.3);      
      $pdf->Output($Dir,'F');
      return True;
    }
  }
?>