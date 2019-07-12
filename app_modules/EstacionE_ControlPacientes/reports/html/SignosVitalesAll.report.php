<?php


class SignosVitalesAll_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function SignosVitalesAll_report($datos=array())
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
		$Membrete = array('file'=>'MembreteSinImagen','datos_membrete'=>array('titulo'=>$this->datos['empresa'],
																'subtitulo'=>'FORMATO DE TOMA DE SIGNOS VITALES'."&nbsp;&nbsp;".$this->datos['estacion']['descripcion5'],
																'logo'=>'',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {
				//print_r($_SESSION['ESTACION']['VECT']);exit;
				$estacion=$this->datos['estacion'];

				//print_r($estacion);exit;
				$datos_estacion=$_SESSION['ESTACION']['VECT'];
					//fecha de nacimiento del paciente para determinar si es de neonatos
				$hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
				$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');

				$salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
				$salida .= "		<tr class=\"modulo_table_title\">\n";
				$salida .= "			<td>FECHA :&nbsp; ".date("Y-m-d")."</td>\n";
				$salida .= "			<td>HORA :<SUB>_____________</SUB></td>\n";
				$salida .= "			<td>RESPONSABLE :<SUB>_________________________________________________</SUB></td>\n";
				$salida .= "		</tr>\n";
				$salida .= "	</table>\n";


			for($i=0;$i<sizeof($datos_estacion);$i++)
			{
					$salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_list_title\">\n";
					$salida .= "		<tr class=\"modulo_table_title\">\n";
					$salida .= "			<td>".$datos_estacion[$i][pieza]."&nbsp;-&nbsp;".$datos_estacion[$i][cama]."&nbsp;&nbsp;".$datos_estacion[$i]['NombrePaciente']."</td>\n";
					//$salida .= "			<td>&nbsp;&nbsp;FECHA:</td>\n";
					$salida .= "		</tr>\n";

					$salida .= "		<tr class=\"modulo_table_title\">\n";
					$salida .= "<td>".$this->FrmSignosVitales($datos_estacion[$i])."</td>\n";
					$salida .= "		</tr>\n";
					$salida .= "	</table>\n";

			}

			//echo $salida;exit;
	     return $salida;
    }







		function FrmSignosVitales($datos_estacion)
		{

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/

			$impresion = "<table align=\"rigth\" width=\"100%\" border=\"1\">\n";
			$impresion .= "<tr class=\"modulo_table_list_title\">\n";
			$impresion .= "<td colspan='2' align=\"center\" >TENSION ART.</td>\n";
			$impresion .= "<td align=\"center\" >FREC. CARD<font size='2'>(X min)</font></td>\n";
			$impresion .= "<td align=\"center\" >FREC. RESP<font size='2'>( X min)</font></td>\n";
			//$impresion .= "<td align=\"center\" >PVC</td>\n";
			//$impresion .= "<td align=\"center\" >PIC</td>\n";
			//$impresion .= "<td align=\"center\" >PESO</td>\n";
			$impresion .= "<td align=\"center\">TEMP<font size='2'>(ºC)</font></td>\n";
			//$impresion .= "<td align=\"center\">MANUAL</td>\n";
			//$impresion .= "<td  align=\"center\">T.INCUB</td>\n";
		//	$impresion .= "<td  align=\"center\">SAT O<sub>2</sub></td>\n";
			$impresion .= "<td colspan='10' align=\"center\">ESCALA EVA</td>\n";
			$impresion .= "</tr>\n";
			$impresion .= "<tr>\n";
			$impresion .= "<td align=\"rigth\"> <font size='2'></font></td><td align=\"rigth\"> <font size='2'></font></td>\n";
			$impresion .= "<td align=\"rigth\"> </td>\n";
			$impresion .= "<td align=\"rigth\"> </td>\n";
			//$impresion .= "<td align=\"rigth\">  <font size='2'>cmH<sub>2</sub>O</font></td>\n";
			//$impresion .= "<td align=\"rigth\"> <font size='2'>cmH<sub>2</sub>O</font></td>\n";
		//	$impresion .= "<td align=\"rigth\"> <font size='2'>Kg</font></td>\n";
			$impresion .= "<td align='rigth'></td>\n";
			//$impresion .= "<td align='rigth'> <font size='2'>ºC</font></td>\n";
			//$impresion .= "<td align='rigth'> <font size='2'>ºC</font></td>\n";
		//	$impresion .= "<td align='rigth'> <font size='2'>%</font></td>\n";
			$impresion .= "<td align='rigth'> <font size='2'>1</font></td><td align='rigth'> <font size='2'>2</font></td>\n";
			$impresion .= "<td align='rigth'> <font size='2'>3</font></td><td align='rigth'> <font size='2'>4</font></td><td align='rigth'> <font size='2'>5</font></td>\n";

			$impresion .= "<td align='rigth'> <font size='2'>6</font></td><td align='rigth'> <font size='2'>7</font></td>\n";
			$impresion .= "<td align='rigth'> <font size='2'>8</font></td><td align='rigth'> <font size='2'>9</font></td><td align='rigth'> <font size='2'>10</font></td>\n";


			$impresion .= "</tr>\n";
			$impresion .= "</table>\n\n";

			/*-------------------------------------------
				Segemento que imprime en pantalla
				los Signos Vitales que se tomaran al paciente.
			  -------------------------------------------
			*/



		/*	$impresion .= "<table colspan=\"2\" align=\"center\" width=\"88%\" border=\"0\" class=\"modulo_table_list\">\n";
			$impresion .= "<tr align='center' class='modulo_table_list_title'>\n";//class=\"modulo_table_list_title\"
			$impresion .= "<td width=\"50%\">TENSION ARTERIAL</td>\n";
			$impresion .= "<td width=\"50%\">OBSERVACION</td>\n";
			$impresion .= "</tr>\n";
			$impresion .= "<tr class=\"modulo_list_claro\">\n";
			$impresion .= "<td width=\"50%\">";
			$impresion .= "<label class=\"label\">&nbsp;T.A</label>&nbsp;&nbsp;<input type=\"text\" class='input-text' name=\"taa\" value='".$_REQUEST['taa']."' size='6' maxlength='5'>&nbsp;<b>/</b>&nbsp;
			<input type=\"text\" class='input-text' name=\"tab\" value='".$_REQUEST['tab']."' size='6' maxlength='5'>";//<br>";//TENSION ARTERIAL
			$impresion .= "<label class=\"label\">&nbsp;&nbsp;&nbsp;</label><br>SITIO &nbsp;&nbsp;<input type='text' class='input-text' name='Hora' size='10'>";

			$impresion .= "<table colspan=\"2\" align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$impresion .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
			$impresion .="<tr align=\"center\">";
			$impresion .="<td rowspan=\"2\">Menor Dolor</td>";
			$fecha_nac=$this->GetFechaNacPaciente($datos_estacion[ingreso]);
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($fecha_nac,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$impresion .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
				$impresion .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
				$impresion .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
				$impresion .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
				$impresion .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";
				$impresion .="<td rowspan=\"2\">Mayor Dolor</td>";
				$impresion .="</tr>";
				$impresion .="<tr>";
				$impresion .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$impresion .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$impresion .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$impresion .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
				$impresion .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
			}
			else
			{
				$impresion .="<td>1</td>";
				$impresion .="<td>2</td>";
				$impresion .="<td>3</td>";
				$impresion .="<td>4</td>";
				$impresion .="<td>5</td>";
				$impresion .="<td>6</td>";
				$impresion .="<td>7</td>";
				$impresion .="<td>8</td>";
				$impresion .="<td>9</td>";
				$impresion .="<td>10</td>";
				$impresion .="<td rowspan=\"2\">Mayor Dolor</td>";

				$impresion .="</tr>";
				$impresion .="<tr>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
				$impresion .="<td><input type=\"radio\" name=\"eva\" value=\"1\"></td>";

			}
			$impresion .="</tr></table>";
			$impresion .= "</td>\n";

			$impresion .= "<td width=\"50%\" align='center'>\n";
			$impresion .= "<textarea name=\"observacion\" cols=\"50\" rows=\"4\" class=\"textarea\">".$_REQUEST['observacion']."</textarea>";//TENSION ARTERIAL
			//$impresion .= "<br><br><input type='submit' class='input-submit' name='Save' value='Insertar'>";
			$impresion .= "</td>\n";
			$impresion .= "</tr>\n";
			$impresion .= "	</table><br><br>\n";*/
			return $impresion;
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

