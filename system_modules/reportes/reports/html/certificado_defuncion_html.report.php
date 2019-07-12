<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class certificado_defuncion_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
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


    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function certificado_defuncion_html_report($datos=array())
    {
		$this->datos=$datos;
		return true;
    }


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
		$info1 = $this->GetDatos_Certificado();
		$info2 = $this->GetDatos_Motivo();
		$diag = $this->ConsultaDiagnosticoI();
		$info3 = $this->GetDatos_ConductaMujer();
		$Responsable = $this->GetDatosResponsable();

		if(!IncludeLib('datospaciente'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
      		return false;
    	}

		$this->datosPaciente = GetDatosPaciente("","",$this->datos[ingreso]);
		if(!IncludeLib('historia_clinica'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
			return false;
    	}

		$salida.="<br><br><center>";
		$salida.="<label><font size='6' face='arial'>CERTIFICADO DE DEFUNCION</font></label>";
		$salida.="</center><br><br>";

		//DATOS DEL PACIENTE
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
		$sexpaciente=$this->SexodePaciente();

		$salida.= "<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table_list\">";
		$salida .= "<tr class=\"modulo_table_title\">\n";
		$salida .= "<td ALIGN=\"JUSTIFY\" width=\"30%\"><FONT SIZE='2' FACE='arial'>NOMBRE: ".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</FONT></td>\n";
		$salida .= "<td ALIGN=\"JUSTIFY\" width=\"30%\"><FONT SIZE='2' FACE='arial'>ID.: ".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</FONT></td>\n";
		$salida .="<td ALIGN=\"JUSTIFY\" width=\"20%\"><FONT SIZE='2' FACE='arial'>HISTORIA :";
		if($this->datosPaciente['historia_numero']!="")
		{
			if($this->datosPaciente['historia_prefijo']!="")
			{
				$salida .= $this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo'];
			}
			else
			{
				$salida .= $this->datosPaciente['historia_numero']." - ".$this->datosPaciente['tipo_id_paciente'];
			}
		}
		else
		{
			$salida.= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente'];
		}
		$salida.="</FONT></td>\n";
		$salida .= "<td ALIGN=\"JUSTIFY\" width=\"20%\"><FONT SIZE='2' FACE='arial'>EDAD: ";
		$salida.= $edad_paciente[anos].' años';
		$salida.="</FONT></td>\n";

		$salida .= "</tr>\n";
		$salida .= "<tr class=\"modulo_table_title\">\n";
		$salida .= "<td ALIGN=\"JUSTIFY\" COLSPAN=\"2\" WIDTH=\"60%\"><FONT SIZE='2' FACE='arial'>DIRECCION :";
		$salida .= $this->datosPaciente['residencia_direccion']."\n";
		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$salida.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$salida.= "- ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$salida.="</FONT></td>\n";

		$salida .= "<td ALIGN=\"JUSTIFY\" COLSPAN=\"2\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>RESPONSABLE : ".$Responsable[8].' - '.$Responsable[4]."</FONT></td>\n";
		$salida .= "</tr>\n";
		$salida.= "</table><br>";

		$salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
		$salida.="<tr class=\"modulo_table_title\">";
		$salida.="<td colspan=\"2\" align=\"center\">INFORMACION - CERTIFICADO DE DEFUNCION</td>";
		$salida.="</tr>";

		foreach ($info1 as $k => $v)
		{
			list($fecha,$hora) = explode(" ",$this->PartirFecha($v[0]));
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$hora = $hora.":".$min;
			$fecha = $fecha;

			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"2\">FECHA Y HORA DEL DESCESO</td>";
			$salida.="</tr>";
			$salida.="<tr>";
			$salida.="<td align=\"center\" class=\"modulo_list_claro\">$fecha</td>";
			$salida.="<td align=\"center\" class=\"modulo_list_claro\">$hora</td>";
			$salida.="</tr>";

			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL PROFESIONAL TRATANTE</td>";
			$salida.="</tr>";

			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\">EXPEDIDO POR:</td>";
			$salida.="<td align=\"center\" class=\"modulo_list_claro\">$v[3]</td>";
			$salida.="</tr>";

			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\">NOMBRE DEL PROFESIONAL:</td>";
			$salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$v[4]</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td colspan=\"2\" align=\"center\">$v[5]</td>";
			$salida.="</tr>";
		}
		$salida.="</table><br>";

		$salida.="<table  align=\"center\" border=\"1\" width=\"100%\">";
		$salida.="<tr class=\"modulo_table_title\">";
		$salida.="<td align=\"center\">SITIO DEL DESCESO</td>";
		$salida.="</tr>";
		$salida.="<tr>";
		$salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$info2</td>";
		$salida.="</tr>";
		$salida.="</table><br>";

		if ($sexpaciente[0]['sexo_id']=='F' AND ($edad_paciente[anos] > 10 &&  $edad_paciente[anos] < 54))
		{
			foreach ($info3 as $k3 => $v3)
			{
				if ($v3[0] == '1'){$estado = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[0] == '2'){$estado = "NO ESTUVO EMBARAZADA";}
				else{$estado = "NO HAY INFORMACION AL RESPECTO";}

				if ($v3[1] == '1'){$estado1 = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[1] == '2'){$estado1 = "NO ESTUVO EMBARAZADA";}
				else{$estado1 = "NO HAY INFORMACION AL RESPECTO";}

				if ($v3[2] == '1'){$estado2 = "SI ESTUVO EMBARAZADA";}
				elseif ($v3[2] == '2'){$estado2 = "NO ESTUVO EMBARAZADA";}
				else{$estado2 = "NO HAY INFORMACION AL RESPECTO";}

				$salida.="<table  align=\"center\" border=\"1\" width=\"100%\">";
				$salida.="<tr class=\"modulo_table_title\">";
				$salida.="<td align=\"center\" colspan=\"2\">INFORMACION FERTIL DEL PACIENTE (10 - 54 AÑOS)</td>";
				$salida.="</tr>";
				$salida.="<tr>";
				$salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTABA EMBARAZADA CUANDO FALLECIO? </td>";
				$salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado</td>";
				$salida.="</tr>";

				$salida.="<tr>";
				$salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTUVO EMBARAZADA EN LAS ULTIMAS 6 SEMANAS? </td>";
				$salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado1</td>";
				$salida.="</tr>";

				$salida.="<tr>";
				$salida.="<td align=\"left\" class=\"modulo_list_oscuro\"> ¿ESTUVO EMBARAZADA EN LOS ULTIMOS 12 MESES? </td>";
				$salida.="<td align=\"center\" class=\"modulo_list_oscuro\">$estado2</td>";
				$salida.="</tr>";
				$salida.="</table>";
			}
		}


		if ($diag)
		{
			$salida.="<br><table  align=\"center\" border=\"1\" width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"3\">DIAGNOSTICOS DE MUERTE O DEFUNCION ASIGNADOS</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td width=\"10%\">CODIGO</td>";
			$salida.="<td width=\"50%\">DIAGNOSTICO</td>";
			$salida.="<td width=\"40%\">Diagnostico Y Tiempo de Muerte</td>";
			$salida.="</tr>";

			for($i=0;$i<sizeof($diag);$i++)
			{
				$diagnostico_id = $diag[$i][diagnostico_id];
				if( $i % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$salida.="<tr class=\"$estilo\">";
				$salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
				$salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
				$salida.="<td align=\"left\">".$diag[$i][diagnostico_muerte]."</td>";
			}
			$salida.="</table><br>";
		}
		return $salida;
	}

	function GetDatos_Certificado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT A.fecha, A.tipo_certificado_id, A.usuario_id,
					B.descripcion, C.nombre, C.descripcion
			FROM hc_conducta_defuncion AS A
			LEFT JOIN hc_tipo_certificado AS B ON(A.tipo_certificado_id = B.tipo_certificado_id)
			LEFT JOIN system_usuarios AS C ON(A.usuario_id = C.usuario_id)
			WHERE A.ingreso =".$this->datos[ingreso]."
			AND A.evolucion_id =".$this->datos[evolucion].";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$info1 = $resulta->GetRows();
			return $info1;
		}
	}

	function GetDatos_Motivo()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT B.descripcion
				FROM hc_motivo_defuncion AS B, hc_conducta_defuncion_motivo AS A
				WHERE A.motivo_defuncion_id = B.motivo_defuncion_id
				AND A.ingreso =".$this->datos[ingreso]."
				AND A.evolucion_id =".$this->datos[evolucion].";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			list ($info2) = $resulta->FetchRow();
			return $info2;
		}
	}

	function GetDatos_ConductaMujer()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT sw_embarazada,sw_semanas_embarazo,sw_meses_embarazo
				FROM hc_conducta_defuncion_mujeres
				WHERE ingreso =".$this->datos[ingreso]."
				AND evolucion_id =".$this->datos[evolucion].";";

		$resulta = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$info3 = $resulta->GetRows();
			return $info3;
		}
	}

    function SexodePaciente()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="SELECT sexo_id FROM pacientes
				WHERE tipo_id_paciente='".$this->datosPaciente[tipo_id_paciente]."'
				AND paciente_id='".$this->datosPaciente[paciente_id]."';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$sexpaciente[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $sexpaciente;
	}

	function ConsultaDiagnosticoI()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT diagnostico_id,
						 diagnostico_nombre,
						 sw_principal,
    					 diagnostico_muerte
		    	  FROM hc_conducta_diagnosticos_defuncion,diagnosticos
				  WHERE hc_conducta_diagnosticos_defuncion.diagnostico_defuncion_id=diagnosticos.diagnostico_id
				  AND evolucion_id=".$this->datos[evolucion]."
				  AND ingreso =".$this->datos[ingreso]."
				  ORDER BY diagnostico_id;";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de defunción";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $vector;
	}


	function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT numerodecuenta FROM hc_evoluciones
				  WHERE evolucion_id =".$this->datos[evolucion].";";
		$resultado1 = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$resultado1->EOF)
		{
			list ($numeroC) = $resultado1->FetchRow();
		}

		$sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
				FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
				WHERE
				a.plan_id = b.plan_id
				AND b.tercero_id = c.tercero_id
				AND b.tercero_id = c.tercero_id
				AND a.numerodecuenta = ".$numeroC.";";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		if(!$resultado->EOF)
		{
			$Responsable = $resultado->FetchRow();
		}
		return $Responsable;
	}


	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}

	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
				$time[$l]=$hor;
				$hor = strtok (":");
		}

		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
	}
    //---------------------------------------
}

?>
