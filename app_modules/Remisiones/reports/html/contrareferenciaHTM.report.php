<?php

/**
 * $Id: contrareferenciaHTM.report.php,v 1.5 2005/06/02 23:12:03 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class contrareferenciaHTM_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function contrareferenciaHTM_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
			IncludeLib("tarifario");
			IncludeLib("funciones_admision");
			$arr=DatosImpresionRemision($this->datos[triage]);
			$Salida .="<TABLE BORDER='1' WIDTH='100%' ALIGN='LEFT'>";
			$Salida .= "				       <tr><td align=\"CENTER\" width=\"100%\" colspan=\"6\" class=\"titulo2\">HOJA TRIAGE</td></tr>";
			$Salida .= "				       <tr><td align=\"CENTER\" width=\"100%\" colspan=\"6\" class=\"normal_10N\">DEPARTAMENTO DE SERVICIOS DE ".$arr[0][descripcion]."</td></tr>";
			$Salida .= "				       <tr><td align=\"LEFT\" width=\"20%\" class=\"normal_10\">INSTITUCION QUE REMITE: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">".$arr[0][razon_social]."</td></tr>";
			$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">IDENTIFICACION: </td><td class=\"normal_10\" width=\"25%\">".$arr[0]['tipo_id_paciente']." ".$arr[0]['paciente_id']."</td>";
			$Salida .= "				       <td align=\"LEFT\" class=\"normal_10\"width=\"15%\">PACIENTE: </td><td class=\"normal_10\">".$arr[0]['nombre']."</td>";
			$EdadArr=CalcularEdad($arr[0]['fecha_nacimiento'],'');
			$Edad=$EdadArr['edad_aprox'];
			$Salida .= "				       <td align=\"LEFT\" class=\"normal_10\"width=\"5%\">EDAD: </td><td class=\"normal_10\" width=\"10%\">$Edad</td></tr>";
   		$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">CLASIFICACION: </td><td class=\"normal_10\">NIVEL ".$arr[0][nivel_triage_id]." &nbsp;&nbsp;".$this->NombreColorTriage($arr[0][nivel_triage_id])."</td><td align=\"left\" class=\"normal_10\">FECHA: </td><td class=\"normal_10\" colspan=\"3\">".$this->FechaStamp($arr[0]['fecha_registro'])." ".$this->HoraStamp($arr[0]['fecha_registro'])."</td></tr>";
			$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">MEDICO QUE REMITE: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">".$arr[0][medico]."</td></tr>";
			if(is_array($arr[3]))
			{
					$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">CAUSAS PROBABLES: </td><td colspan=\"5\">";
					$Salida .= "			      	 <table width=\"100%\" border=\"1\" align=\"center\">";
					//$Salida .= "				         <tr align=\"center\">";
					//$Salida .= "                   <td class=\"normal_10\">NIVEL</td>";
					//$Salida .= "                   <td class=\"normal_10\">CAUSAS PROBABLES</td>";
					//$Salida .= "				         </tr>";
					for($i=0; $i<sizeof($arr[3]);)
					{
							//$Salida .= "				         <tr class=\"normal_10\">";
							//$Salida .= "                   <td width=\"15%\" align=\"center\">NIVEL ".$arr[3][$i][nivel_triage_id]."</td>";
							//$Salida .= "                   <td width=\"75%\">";
							//$Salida .= "			      	 			 <table width=\"100%\" border=\"1\" align=\"center\">";
							$d=$i;
							while($arr[3][$i][nivel_triage_id]==$arr[3][$d][nivel_triage_id])
							{
									$Salida .= "				         			 <tr>";
									$Salida .= "                  			 <td  class=\"normal_10\">".$arr[3][$d][descripcion]."</td>";
									$Salida .= "				         			 </tr>";
									$d++;
							}
							$i=$d;
							//$Salida .= "			   			       </table>";
							//$Salida .= "                   </td>";
							//$Salida .= "				         </tr>";
					}
					$Salida .= "			   			 </table>";
					$Salida .= "				       </td></tr>";
			}
			$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">MOTIVO CONSULTA: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">".$arr[0][motivo_consulta]."</td></tr>";
			if(!empty($arr[0][observacion_medico]))
			{  $Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">OBSERVACION MEDICA: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">".$arr[0][observacion_medico]."</td></tr>";  }
			else
			{  $Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">OBSERVACION MEDICA: </td><td width=\"70%\" colspan=\"5\">&nbsp;</td></tr>";  }
			$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">SIGNOS VITALES: </td><td colspan=\"5\">";
			$glas=$arr[0][respuesta_motora_id] + $arr[0][respuesta_verbal_id]+ $arr[0][apertura_ocular_id];
			if(empty($glas)){   $glas='--';  }
			$Salida .= "			      	 <table width=\"100%\" border=\"1\" align=\"center\">";
			$Salida .= "				         <tr align=\"center\" class=\"normal_10\">";
			$Salida .= "				         <td>F.C.</td>";
			$Salida .= "				         <td>F.R.</td>";
			$Salida .= "				         <td>PESO(Kg)</td>";
			$Salida .= "				         <td>T.A.</td>";
			$Salida .= "				         <td>TEMP.</td>";
			$Salida .= "				         <td>EVA.</td>";
			$Salida .= "				         <td>GLASGOW</td>";
			$Salida .= "				         <td align=\"center\">SAT02</td>";
			$Salida .= "				         </tr>";
			$Salida .= "				         <tr>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$arr[0][signos_vitales_fc]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$arr[0][signos_vitales_fr]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"15%\" align=\"center\">".$arr[0][signos_vitales_peso]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"15%\" align=\"center\">".$arr[0][signos_vitales_taalta]." / ".$arr[0][signos_vitales_tabaja]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$arr[0][signos_vitales_temperatura]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$arr[0][evaluacion_dolor]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$glas."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$arr[0][sato2]."</td>";			
			$Salida .= "				         </tr>";
			$Salida .= "			   			 </table>";
			$Salida .= "									</td>";
			$Salida .= "				       </tr>";
			if(is_array($arr[1]))
			{
					$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">DIAGNOSTICOS: </td><td colspan=\"5\">";
					$Salida .= "			      	 <table width=\"100%\" border=\"1\" align=\"center\">";
					$Salida .= "           <tr align=\"center\" class=\"normal_10\">";
					$Salida .= "  				<td width=\"15%\">CODIGO</td>";
					$Salida .= "          <td width=\"90%\">DIAGNOSTICO</td>";
					$Salida .= "           </tr>";
					for($i=0; $i<sizeof($arr[1]); $i++)
					{
							$Salida .= "				       <tr class=\"normal_10\"><td align=\"CENTER\" width=\"10%\">".$arr[1][$i][diagnostico_id]."</td><td width=\"90%\ class=\"normal_10\">".$arr[1][$i][diagnostico_nombre]."</td></tr>";
					}
					$Salida .= "			   			 </table>";
					$Salida .= "									</td>";
					$Salida .= "				       </tr>";
			}
			if(!empty($arr[0][observacion_remision]))
   		{  $Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">OBSERVACION REMISION: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">".$arr[0][observacion_remision]."</td></tr>";  }
			else
   		{  $Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\">OBSERVACION REMISION: </td><td width=\"70%\" colspan=\"5\" class=\"normal_10\">&nbsp;</td></tr>";  }
			if(is_array($arr[2]))
			{
					$Salida .= "          <tr>";
					$Salida .= "          <td align=\"LEFT\" class=\"normal_10\">CENTROS REMISION: </td>";
					$Salida .= "          <td class=\"normal_10\" colspan=\"5\">";
					$Salida .= "       <table border=\"1\" width=\"100%\"align=\"left\>";
					$Salida .= "           <tr align=\"center\" class=\"normal_10\">";
					$Salida .= "  				<td width=\"15%\">CODIGO</td>";
					$Salida .= "          <td width=\"90%\">CENTRO</td>";
					$Salida .= "          <td width=\"10%\">NIVEL</td>";
					$Salida .= "           </tr>";
					for($i=0; $i<sizeof($arr[2]); $i++)
					{
											$Salida .= "          <tr class=\"normal_10\">";
											$Salida.="  <td  align=\"center\">".$arr[2][$i][centro_remision]."</td>";
											$Salida .= "          <td>".$arr[2][$i][descripcion]." ".$arr[2][$i][direccion]." ".$arr[2][$i][telefono]."</td>";
											$Salida .= "          <td align=\"center\">".$arr[2][$i][nivel]."</td>";
											$Salida .= "          </tr>";
					}
					$Salida .= "		   	 </table>";
					$Salida .= "          </td>";
					$Salida .= "           </tr>";
			}
			$Salida.="</table>";
			return $Salida;
	}


	/**
	*
	*/
	function NombreColorTriage($nivel)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.color FROM niveles_triages as a WHERE a.nivel_triage_id=$nivel";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$var=$result->fields[0];
			$result->Close();
			return $var;
	}

 /**
  * Se encarga de separar la fecha del formato timestamp
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

	}
?>
