<?php


class SignosVitales_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function SignosVitales_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$this->datos['empresa'],
																'subtitulo'=>'SIGNOS VITALES',
																'logo'=>'logocliente.png',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {

				$estacion=$this->datos['estacion'];
				$datos_estacion=$this->datos['datos_estacion'];

			$salida = "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_list_title\">\n";
			$salida .= "		<tr class=\"modulo_table_title\">\n";
			$salida .= "			<td>PACIENTE</td>\n";
			$salida .= "			<td>HABITACION</td>\n";
			$salida .= "			<td>CAMA</td>\n";
			$salida .= "			<td>FECHA CONTROL</td>\n";
			$salida .= "		</tr>\n";
			$salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			$salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$salida .= "			<td>\n";
			$salida .= "				<input type='text' class='input-text' name='Hora' value='".$hora."' size='10'>\n";

			//fecha de nacimiento del paciente para determinar si es de neonatos
			$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
			$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
			$salida .= "			</td>\n";
			$salida .= "		</tr>\n";
			$salida .= "	</table><br><br>\n";

/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/

			$salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
			$salida .= "<tr class=\"modulo_table_list_title\">\n";
			$salida .= "<td align=\"center\" >FREC. CARD.</td>\n";
			$salida .= "<td align=\"center\" >FREC. RESP.</td>\n";
			$salida .= "<td align=\"center\" >PVC</td>\n";
			$salida .= "<td align=\"center\" >PIC</td>\n";
			$salida .= "<td align=\"center\" >PESO</td>\n";
			$salida .= "<td align=\"center\">TEMP.</td>\n";
			$salida .= "<td align=\"center\">MANUAL</td>\n";
			$salida .= "<td  align=\"center\">T.INCUB</td>\n";
			$salida .= "<td  align=\"center\">SAT O<sub>2</sub></td>\n";
			$salida .= "</tr>\n";
			$salida .= "<tr>\n";
			$salida .= "<td align=\"left\"><input type='text' class='input-text' name='fc' value='".$_REQUEST['fc']."' size='2' > X min.</td>\n";
			$salida .= "<td align=\"left\"><input type='text' class='input-text' name='fr' value='".$_REQUEST['fr']."' size='2' > X min.</td>\n";
			$salida .= "<td align=\"left\"><input type='text' class='input-text' name='pvc' value='".$_REQUEST['pvc']."' size='2'> cmH<sub>2</sub>O</td>\n";
			$salida .= "<td align=\"left\"><input type='text' class='input-text' name='pic' value='".$_REQUEST['pic']."' size='2' > cmH<sub>2</sub>O</td>\n";
			$salida .= "<td align=\"left\"><input type='text' class='input-text' name='peso' value='".$_REQUEST['peso']."' size='2' > Kg.</td>\n";
			$salida .= "<td align='left'><input type='text' class='input-text' name='tpiel' value='".$_REQUEST['tpiel']."' size='2' > ºC</td>\n";
			$salida .= "<td align='left'><input type='text' class='input-text' name='manual' value='".$_REQUEST['manual']."' size='2' > ºC</td>\n";
			$salida .= "<td align='left'><input type='text' class='input-text' name='servo' value='".$_REQUEST['servo']."' size='2' > ºC</td>\n";
			$salida .= "<td align='left'><input type='text' class='input-text' name='sato' value='".$_REQUEST['sato']."' size='2'> %</td>\n";
			$salida .= "</tr>\n";
			$salida .= "</table>\n\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/



				$salida .= "<table colspan=\"2\" align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
			$salida .= "<tr align='center' class='modulo_table_list_title'>\n";//class=\"modulo_table_list_title\"
			$salida .= "<td width=\"50%\">TENSION ARTERIAL</td>\n";
			$salida .= "<td width=\"50%\">OBSERVACION</td>\n";
			$salida .= "</tr>\n";
			$salida .= "<tr class=\"modulo_list_claro\">\n";
			$salida .= "<td width=\"50%\">";
			$salida .= "<label class=\"label\">&nbsp;T.A</label>&nbsp;&nbsp;<input type=\"text\" class='input-text' name=\"taa\" value='".$_REQUEST['taa']."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;
			<input type=\"text\" class='input-text' name=\"tab\" value='".$_REQUEST['tab']."' size='6' maxlength='5'>";//<br>";//TENSION ARTERIAL
			$salida .= "<label class=\"label\">&nbsp;&nbsp;&nbsp;</label><br>SITIO &nbsp;&nbsp;<input type='text' class='input-text' name='Hora' size='10'>";

			$salida .= "<table colspan=\"2\" align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$salida .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
			$salida .="<tr align=\"center\">";
			$salida .="<td rowspan=\"2\">Menor Dolor</td>";
			$fecha_nac=$this->GetFechaNacPaciente($datos_estacion[ingreso]);
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$salida .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
				$salida .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
				$salida .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
				$salida .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
				$salida .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";
				$salida .="<td rowspan=\"2\">Mayor Dolor</td>";
				$salida .="</tr>";
				$salida .="<tr>";
				$salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
			}
			else
			{
				$salida .="<td>1</td>";
				$salida .="<td>2</td>";
				$salida .="<td>3</td>";
				$salida .="<td>4</td>";
				$salida .="<td>5</td>";
				$salida .="<td>6</td>";
				$salida .="<td>7</td>";
				$salida .="<td>8</td>";
				$salida .="<td>9</td>";
				$salida .="<td>10</td>";
				$salida .="<td rowspan=\"2\">Mayor Dolor</td>";

				$salida .="</tr>";
				$salida .="<tr>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$salida .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";

			}
			$salida .="</tr></table>";
			$salida .= "</td>\n";

			$salida .= "<td width=\"50%\" align='center'>\n";
			$salida .= "<textarea name=\"observacion\" cols=\"50\" rows=\"4\" class=\"textarea\">".$_REQUEST['observacion']."</textarea>";//TENSION ARTERIAL
			//$salida .= "<br><br><input type='submit' class='input-submit' name='Save' value='Insertar'>";
			$salida .= "</td>\n";
			$salida .= "</tr>\n";
			$salida .= "	</table><br><br>\n";






			//echo $salida;exit;
	     return $salida;
    }




			/**
		*		Trae la fecha de nacimiento del paciente.
		*
		*		@Author Jairo Duvan Diaz
		*		@access Public
		*		@return bool
		*
		*/
		function GetFechaNacPaciente($ingreso)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT fecha_nacimiento
				          FROM ingresos a,pacientes b
									WHERE a.ingreso='$ingreso'
									AND b.paciente_id=a.paciente_id
									AND b.tipo_id_paciente=a.tipo_id_paciente
									AND a.estado='1'";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->EOF)
				{  return "nada";  }


				if(!$result->EOF)
				  $fech=$result->fields[0];
					return $fech;
		}

}
?>

