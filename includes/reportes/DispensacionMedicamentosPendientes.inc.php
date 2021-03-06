<?php
/**
 * $Id: DispensacionMedicamentosPendientes.inc.php,v 1.0 2010/07/08 
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 ** @autor Sandra Viviana Pantoja
 *
*/
	function GenerarReportePendiente($empresa,$paciente,$Pendiente)
	{
		$Dir="cache/DispensacionMedicamentosPendientes.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','letter');
		
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		$reporte .= cabecera($empresa,$paciente);
		$reporte .=cuerpo($Pendiente);
		$reporte .=final();
		$pdf->WriteHTML($reporte);
		$pdf->Output($Dir,'F');
		return True;
	}
		
		function cabecera($empresa,$paciente)
		{
		
			$html ="<br><br><TABLE BORDER='0' WIDTH='1520'>";
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>EMPRESA:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".$empresa['descripcion1']." -".$empresa['bodega']."</TD>";
			$html.="</TR>";

			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>IDENTIFICACION:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".$paciente[0]['tipo_id_paciente']." ".$paciente[0]['paciente_id']."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>PACIENTE:</TD>";
			$nombre = $paciente[0]['primer_apellido']." ".$paciente[0]['segundo_apellido']." ".$paciente[0]['primer_nombre']." ".$paciente[0]['segundo_nombre'];
			$nombre = substr("$nombre", 0, 38);
			$html.="<TD WIDTH='270' HEIGHT=25><b>".strtoupper($nombre).""."</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>SEXO:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".$paciente[0]['sexo_id']."</TD>";
			$html.="<TD WIDTH='110' HEIGHT=25>EDAD:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".$paciente[0]['edad']."A?OS</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>DIRECCION.:</TD>";
			$dir = $paciente[0]['residencia_direccion'];
			$dir = substr("$dir", 0, 38);
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($dir)."."."</TD>";
			$html.="<TD WIDTH='110' HEIGHT=25>TELEFONO.:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($paciente[0]['residencia_telefono'])."</TD>";
			$html.="</TR>";
			return $html;
		}

		function cuerpo($datos)
		{
			$titulo = 'MEDICAMENTO(S) PENDIENTE(S)';
			$html ="<TR>";
			$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><br>";
			$html.="<b>".$titulo."</b>";
			$html.="</TD>";
			$html.="</TR><br>";
			$html .= Pintar_MedicamentoPendiente($datos);
			return $html;
		}
    /*
		* Forma que permite Mostrar Los Medicamentos Pendientes.
		*
	*/
		function Pintar_MedicamentoPendiente($datos)
		{	
    
   
        $salida.="<tr>";
        $salida.="<td  width=\"150\" height=\"25\"><b>CODIGO:</b></td>";
        $salida.="<td  width=\"350\" height=\"25\"><b>MEDICAMENTO FORMULADO:</b></td> ";
        $salida.="<td  width=\"800\" height=\"25\"><b>CANTIDA</b></td>";
        $salida.= "</tr>";
             
			foreach($datos as $k => $vector)
			{
        $salida.="<tr>";
        $salida.="<td  width=\"150\" height=\"25\">".$vector['codigo_medicamento']." ";
        $salida.="</b></td>";
        $salida.=" <td align=\"left\" width=\"350\" height=\"25\">".$vector['nombre_medicamento']." ".$vector['contenido_unidad_venta']." </td>";
        $salida.=" <td align=\"left\"   width=\"800\" height=\"25\">".$vector['cantidad_acomulada']."  ".$vector['unidad']."</td>";

			}
          $salida.="</tr>";
          return $salida;
		}

		function final()
		{
			$html ="<TR>";
			$html.="<TD WIDTH='380' HEIGHT=25><br>PACIENTE:</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD ALIGN='LEFT' WIDTH='760'>";
			$html.="<br>________________________________________________";
			$html.="</TD>";
			$html.="</TR>";
		
			$html.="</TABLE>";
			return $html;
		}

?>
