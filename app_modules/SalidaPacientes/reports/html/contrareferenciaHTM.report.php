<?php

/**
 * $Id: contrareferenciaHTM.report.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Se encarga de separar la fecha del formato timestamp
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


			$HTML_WEB_PAGE=Open_Tags_Html(date("d-m-Y"));
			$HTML_WEB_PAGE .="<TABLE BORDER='0' WIDTH='100%' ALIGN='LEFT'>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"CENTER\" width=\"100%\"><B>HOJA TRIAGE</B></td></tr>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"CENTER\" width=\"100%\"><B>DEPARTAMENTO DE SERVICIOS DE ".$arr[0][descripcion]."</B></td></tr>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">INSTITUCION QUE REMITE: </td><td width=\"70%\"><B>".$arr[0][razon_social]."</B></td></tr>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" width=\"25%\">".$arr[0]['tipo_id_paciente']." ".$arr[0]['paciente_id']."</td><td align=\"center\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE: </td><td class=\"modulo_list_claro\" width=\"30%\">".$arr[0]['nombre']."</td></tr>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" class=\"modulo_table_list_title\">CLASIFICACION: </td><td>NIVEL ".$arr[0][nivel_triage_id]."</td><td align=\"center\" class=\"modulo_table_list_title\">FECHA: </td><td class=\"modulo_list_claro\">".FechaStamp($arr[0]['fecha_registro'])." ".HoraStamp($arr[0]['fecha_registro'])."</td></tr>";
			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">MEDICO QUE REMITE: </td><td width=\"70%\">".$arr[0][medico]."</td></tr>";
			if(is_array($arr[3]))
			{
					$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"100%\">CAUSAS PROBABLES: </td><td colspan=\"3\">";
					$HTML_WEB_PAGE_WEB_PAGE .="<TABLE BORDER='0' WIDTH='95%' ALIGN='LEFT'>";
					for($i=0; $i<sizeof($arr[3]); $i++)
					{
							$HTML_WEB_PAGE .= "				       <tr><td align=\"CENTER\" width=\"15%\">NIVEL ".$arr[3][$i][nivel_triage_id]."</td><td width=\"75%\">".$arr[3][$i][descripcion]."</td></tr>";
					}
			}
			$HTML_WEB_PAGE .= "				       </table>";
			$HTML_WEB_PAGE .= "				       </td></tr>";

			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">MOTIVO CONSULTA: </td><td width=\"70%\">".$arr[0][motivo_consulta]."</td></tr>";
			if(!empty($arr[0][observacion_medico]))
			{  $HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">OBSERVACION MEDICA: </td><td width=\"70%\">".$arr[0][observacion_medico]."</td></tr>";  }
			else
			{  $HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">OBSERVACION MEDICA: </td><td width=\"70%\">&nbsp;</td></tr>";  }
			$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\">SIGNOS VITALES: </td><td colspan=\"3\">";
			$HTML_WEB_PAGE .= "				       <td width=\"75\" align=\"CENTER\">F.C.: ".$arr[0][signos_vitales_fc]." /m</td>";
			$glas=$arr[0][respuesta_motora_id] + $arr[0][respuesta_verbal_id]+ $arr[0][apertura_ocular_id];
			if(empty($glas)){   $glas='--';  }
			$HTML_WEB_PAGE .= "			      	 <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
			$HTML_WEB_PAGE .= "				         <tr align=\"center\" class=\"modulo_table_list_title\">";
			$HTML_WEB_PAGE .= "				         <td>F.C.</td>";
			$HTML_WEB_PAGE .= "				         <td>F.R.</td>";
			$HTML_WEB_PAGE .= "				         <td>PESO(Kg)</td>";
			$HTML_WEB_PAGE .= "				         <td>T.A.</td>";
			$HTML_WEB_PAGE .= "				         <td>TEMP.</td>";
			$HTML_WEB_PAGE .= "				         <td>EVA.</td>";
			$HTML_WEB_PAGE .= "				         <td>GLASGOW</td>";
			$HTML_WEB_PAGE .= "				         </tr>";
			$HTML_WEB_PAGE .= "				         <tr>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_fc]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_fr]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"15%\">".$arr[0][signos_vitales_peso]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"15%\">".$arr[0][signos_vitales_tabaja]." / ".$arr[0][signos_vitales_taalta]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][signos_vitales_temperatura]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$arr[0][evaluacion_dolor]."</td>";
			$HTML_WEB_PAGE .= "				           <td class=\"modulo_list_claro\" width=\"10%\">".$glas."</td>";
			$HTML_WEB_PAGE .= "				         </tr>";
			$HTML_WEB_PAGE .= "			   			 </table>";
			$HTML_WEB_PAGE .= "									</td>";
			$HTML_WEB_PAGE .= "				       </tr>";
			if(is_array($arr[1]))
			{
					$HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">DIAGNSOTICOS: </td><td colspan=\"3\">";
					$HTML_WEB_PAGE .= "			      	 <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
					for($i=0; $i<sizeof($arr[1]); $i++)
					{
							$HTML_WEB_PAGE .= "				       <tr><td align=\"CENTER\" width=\"10%\">".$arr[1][$i][diagnostico_id]."</td><td width=\"90%\">".$arr[1][$i][diagnostico_nombre]."</td></tr>";
					}
					$HTML_WEB_PAGE .= "			   			 </table>";
					$HTML_WEB_PAGE .= "									</td>";
					$HTML_WEB_PAGE .= "				       </tr>"
			}
			if(!empty($arr[0][observacion_remision]))
   		{  $HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">OBSERVACION REMISION: </td><td width=\"70%\">".$arr[0][observacion_remision]."</td></tr>";  }
			else
   		{  $HTML_WEB_PAGE .= "				       <tr><td align=\"center\" width=\"30%\">OBSERVACION REMISION: </td><td width=\"70%\">&nbsp;</td></tr>";  }
			if(is_array($arr[2]))
			{
					$HTML_WEB_PAGE .= "				       <tr>";
					$HTML_WEB_PAGE .= "				          <td align=\"center\" class=\"modulo_table_list_title\">INSTITUCIONES A LAS QUE SE REMITE: </td>";
					$HTML_WEB_PAGE .= "				          <td class=\"modulo_list_oscuro\" colspan=\"3\">";
					$HTML_WEB_PAGE.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
					$HTML_WEB_PAGE.="<tr class=\"hc_table_submodulo_list_title\">";
					$HTML_WEB_PAGE.="  <td width=\"15%\">CODIGO</td>";
					$HTML_WEB_PAGE.="  <td width=\"75%\">INSTITUCION</td>";
					$HTML_WEB_PAGE.="  <td width=\"10%\">NIVEL</td>";
					$HTML_WEB_PAGE.="</tr>";
					for($i=0; $i<sizeof($arr[2]); $i++)
					{
								$HTML_WEB_PAGE.="<tr class=\"modulo_list_claro\">";
								$HTML_WEB_PAGE.="  <td  align=\"center\">".$arr[2][$i][centro_remision]."</td>";
								$HTML_WEB_PAGE.="  <td>".$arr[2][$i][descripcion]."      ".$arr[2][$i][direccion]."   ".$arr[2][$i][telefono]."</td>";
								$HTML_WEB_PAGE.="  <td align=\"center\">NIVEL ".$arr[2][$i][nivel]."</td>";
								$HTML_WEB_PAGE.="</tr>";
					}
					$HTML_WEB_PAGE.="</table><br>";
					$HTML_WEB_PAGE .= "				       </td>";
					$HTML_WEB_PAGE .= "				       </tr>";
					//for($i=0; $i<sizeof($arr[2]); $i++)
					//{
					//		$HTML_WEB_PAGE .= "				       <tr><td align=\"CENTER\" width=\"100\">".$arr[2][$i][centro_remision]."</td><td width=\"600\">".$arr[2][$i][descripcion]."      ".$arr[2][$i][direccion]."   ".$arr[2][$i][telefono]."</td><td width=\"60\" align=\"CENTER\">NIVEL ".$arr[2][$i][nivel]."</td></tr>";
					//}
			}
			$HTML_WEB_PAGE.="</table>";

			$HTML_WEB_PAGE .=Close_Tags_Html();
echo $HTML_WEB_PAGE;exit;
				$RUTA=GetDatos_A_Generar_Html_a_Pdf($HTML_WEB_PAGE);
        return $RUTA;
	}

?>
