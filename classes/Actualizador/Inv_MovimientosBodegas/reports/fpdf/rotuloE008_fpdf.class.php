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
  class rotuloE008_fpdf
  {
    /**
    * Constructor de la clase
    */
    function rotuloE008_fpdf(){}
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
      $pdf=new PDF('L','mm','letter');
      $pdf->AddPage();
      $pdf->Image($pathImagen.'/logocliente.png',20,20,40);
      $pdf->SetFont('Arial','',20);

      $tmn = 880;
      $html .= "<table WIDTH='".$tmn."' >";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT=80 ALIGN=CENTER>".$bodega[0]['descripcion']."</td>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='80' ALIGN=CENTER>".$empresa['direccion']."</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='80' ALIGN=CENTER>TEL: ".$empresa['telefonos']."</td>";
      $html .= "</tr>";
      $html .= "</table><br><br>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',24);
      $html  = "<table WIDTH='".$tmn."' >";     
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='90' >";
      $html .= "".$documento['tipo'].":";
      $html .= "</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' ALIGN=CENTER  HEIGHT='90' >";
      $html .= "".$documento['nombre_tercero']."  ";
      $html .= "</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.70)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>";
      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='90' >CIUDAD</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' ALIGN=CENTER HEIGHT='90' >".$documento['municipio']."</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.70)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>";       
      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='90' >DIRECCION</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='90' ALIGN=CENTER>".$documento['direccion']."</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.70)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>";       
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='90' >DOCUMENTO</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='90' ALIGN=CENTER>".$resultado['prefijo']." ".$resultado['numero']."</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.70)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.70)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>"; 
     
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='90' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' HEIGHT='90' ALIGN=CENTER>&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.20)."' HEIGHT='90' ALIGN=CENTER >DE:</td>";
      //$html .= "<td WIDTH='".intval($tmn*0.25)."' HEIGHT='90' ALIGN=CENTER>".sizeof($resultado['DETALLE'])."</td>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' HEIGHT='90' ALIGN=CENTER></td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".intval($tmn*0.30)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.25)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "<td WIDTH='".intval($tmn*0.20)."' HEIGHT='1' >&nbsp;</td>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' HEIGHT='1' >";      
      $html .= "<table WIDTH='".intval($tmn*0.25)."' border='1'>";
      $html .= "<td WIDTH='".intval($tmn*0.25)."' height='1'>&nbsp;</td>";
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>";
      $html .= "</table><br>";
      $pdf->WriteHTML($html);
      $pdf->SetFont('Arial','',14);
      $html  = "<table WIDTH='".$tmn."' border='1'>";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' height='80'>&nbsp;</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $html .= "<table WIDTH='".$tmn."' >";     
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='40' ALIGN=CENTER>CONTIENE MEDICAMENTOS Y DISPOSITIVOS MEDICOS<td>";
      $html .= "</tr>";
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='40' ALIGN=CENTER>SI ENCUENTRA ROTO ESTE SELLO, VERIFIQUE EL CONTENIDO EN PRESENCIA</td>";
      $html .= "</tr>";      
      $html .= "<tr>";
      $html .= "<td WIDTH='".$tmn."' HEIGHT='40' ALIGN=CENTER>DEL TRASPORTADOR</td>";
      $html .= "</tr>";
      $html .= "</table>";
      $pdf->WriteHTML($html);
      $pdf->SetLineWidth(0.3);      
      $pdf->Output($Dir,'F');
      return True;
    }
  }
?>