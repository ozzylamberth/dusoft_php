<?php

/**
 * $Id: contrareferencia.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Se encarga de separar la fecha del formato timestamp
 */

 /**
  * @access private
  * @return string
  * @param date fecha
  */
	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}

				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}


 /**
  * Se encarga de separar la hora del formato timestamp
  * @access private
  * @return string
  * @param date hora
  */

	function HoraStamp($hora)
  {
    $hor = strtok ($hora," ");
    for($l=0;$l<4;$l++)
    {
      $time[$l]=$hor;
      $hor = strtok (":");
    }
		$x=explode('.',$time[3]);
    return  $time[1].":".$time[2];
  }

	function GenerarContrareferencia($arr)
	{
			IncludeLib("tarifario");
			$Dir="cache/contrareferencia".UserGetUID().".pdf";
			require("classes/fpdf/html_class.php");
			//include("classes/fpdf/conversor.php");
			define('FPDF_FONTPATH','font/');

			$pdf2=new PDF();
			$pdf2->AddPage();
			$pdf2->SetFont('Arial','',7);

			//$pdf2->Cell(0,2,'Pagina No '.$pdf2->PageNo());
			$html="<br><br><br>".$pdf2->image('images/logocliente.png',10,7,29)."";

			//$pdf2->Cell(2,12,date('Y-m-d h:m'));
			//$pdf2->Cell(100,22,'Pagina No '.$pdf2->PageNo());
			$html .= "				       <br><br><tr><td align=\"CENTER\" width=\"760\"><B>HOJA TRIAGE</B></td></tr>";
			$html .= "				       <tr><td align=\"CENTER\" width=\"760\"><B>DEPARTAMENTO DE SERVICIOS DE ".$arr[0][descripcion]."</B></td></tr>";
			$html .= "<br><table border=\"1\" width=\"200\" align=\"CENTER\">";
			$html .= "				       <tr><td align=\"center\" width=\"150\">INSTITUCION QUE REMITE: </td><td width=\"610\"><B>".$arr[0][razon_social]."</B></td></tr>";
			$html .= "				       <tr><td align=\"center\" width=\"150\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" width=\"150\">".$arr[0]['tipo_id_paciente']." ".$arr[0]['paciente_id']."</td><td align=\"center\" class=\"modulo_table_list_title\" width=\"90\">PACIENTE: </td><td class=\"modulo_list_claro\" width=\"370\">".$arr[0]['nombre']."</td></tr>";
			$html .= "				       <tr><td align=\"center\" class=\"modulo_table_list_title\" width=\"150\">CLASIFICACION: </td><td width=\"150\">NIVEL ".$arr[0][nivel_triage_id]."</td><td align=\"center\" class=\"modulo_table_list_title\" width=\"90\">FECHA: </td><td class=\"modulo_list_claro\" width=\"370\">".FechaStamp($arr[0]['fecha_registro'])." ".HoraStamp($arr[0]['fecha_registro'])."</td></tr>";
			$html .= "				       <tr><td align=\"center\" width=\"150\">MEDICO QUE REMITE: </td><td width=\"610\">".$arr[0][medico]."</td></tr>";
			if(is_array($arr[3]))
			{
					$html .= "				       <tr><td align=\"center\" width=\"760\">CAUSAS PROBABLES: </td></tr>";
					for($i=0; $i<sizeof($arr[3]); $i++)
					{
							$html .= "				       <tr><td align=\"CENTER\" width=\"100\">NIVEL ".$arr[3][$i][nivel_triage_id]."</td><td width=\"660\">".$arr[3][$i][descripcion]."</td></tr>";
					}
			}
			$html .= "				       <tr><td align=\"center\" width=\"150\">MOTIVO CONSULTA: </td><td width=\"610\">".$arr[0][motivo_consulta]."</td></tr>";
			if(!empty($arr[0][observacion_medico]))
			{  $html .= "				       <tr><td align=\"center\" width=\"150\">OBSERVACION MEDICA: </td><td width=\"610\">".$arr[0][observacion_medico]."</td></tr>";  }
			else
			{  $html .= "				       <tr><td align=\"center\" width=\"150\">OBSERVACION MEDICA: </td><td width=\"610\">&nbsp;</td></tr>";  }
			$html .= "				       <tr><td align=\"center\" class=\"modulo_table_list_title\" width=\"150\">SIGNOS VITALES: </td><td width=\"75\" align=\"CENTER\">F.C.: ".$arr[0][signos_vitales_fc]." /m</td>";
			$glas=$arr[0][respuesta_motora_id] + $arr[0][respuesta_verbal_id]+ $arr[0][apertura_ocular_id];
			if(empty($glas)){   $glas='--';  }
			$html .= "				         <td width=\"75\">F.R.: ".$arr[0][signos_vitales_fr]." /m</td><td width=\"80\">PESO(Kg): ".$arr[0][signos_vitales_peso]."</td>";
			$html .= "				         <td width=\"80\">T.A.: ".$arr[0][signos_vitales_tabaja]." / ".$arr[0][signos_vitales_taalta]."</td>";
			$html .= "				         <td width=\"75\">TEMP.: ".$arr[0][signos_vitales_temperatura]." ºC</td><td width=\"65\">EVA.: ".$arr[0][evaluacion_dolor]."</td><td width=\"85\">GLASGOW: ".$glas."</td></tr>";

			if(is_array($arr[1]))
			{
					$html .= "				       <tr><td align=\"center\" width=\"760\">DIAGNSOTICOS: </td></tr>";
					for($i=0; $i<sizeof($arr[1]); $i++)
					{
							$html .= "				       <tr><td align=\"CENTER\" width=\"100\">".$arr[1][$i][diagnostico_id]."</td><td width=\"660\">".$arr[1][$i][diagnostico_nombre]."</td></tr>";
					}
			}
			if(!empty($arr[0][observacion_remision]))
   		{  $html .= "				       <tr><td align=\"center\" width=\"150\">OBSERVACION REMISION: </td><td width=\"610\">".$arr[0][observacion_remision]."</td></tr>";  }
			else
   		{  $html .= "				       <tr><td align=\"center\" width=\"150\">OBSERVACION REMISION: </td><td width=\"610\">&nbsp;</td></tr>";  }
			if(is_array($arr[2]))
			{
					$html .= "				       <tr><td align=\"center\" width=\"760\">INSTITUCIONES A LAS QUE SE REMITE: </td></tr>";
					for($i=0; $i<sizeof($arr[2]); $i++)
					{
							$html .= "				       <tr><td align=\"CENTER\" width=\"100\">".$arr[2][$i][centro_remision]."</td><td width=\"600\">".$arr[2][$i][descripcion]."      ".$arr[2][$i][direccion]."   ".$arr[2][$i][telefono]."</td><td width=\"60\" align=\"CENTER\">NIVEL ".$arr[2][$i][nivel]."</td></tr>";
					}
			}
			$html.="</table>";
			//$pdf2->SetFont('Arial','B',18);
			//$pdf2->SetTextColor(203,203,203);
			//$pdf2->RotatedText(60,80,$arr[0][razon_social],35);
			//$pdf2->SetFont('Arial','',8);
			//$pdf2->SetTextColor(200,203,150);
			$pdf2->WriteHTML($html);
		//	$pdf2->SetLineWidth(0.5);
		//	$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
			$pdf2->Output($Dir,'F');
			return true;
	}

?>
