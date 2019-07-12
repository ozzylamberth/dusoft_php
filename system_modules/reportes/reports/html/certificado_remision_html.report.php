<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class certificado_remision_html_report
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
    function certificado_remision_html_report($datos=array())
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
		$pfj = $this->frmPrefijo;
		$num_motivo = $this->GetMotivos_Remision();
		$conducta = $this->GetConduta_Remision();
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
		$salida.="<label><font size='8' face='arial'>CERTIFICADO DE REMISION</font></label>";
		$salida.="</center><br><br>";

		//DATOS DEL PACIENTE
		$FechaInicio = $this->datosPaciente[fecha_nacimiento];
		$FechaFin = date("Y-m-d");
		$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);

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

		$salida .="<BR><table width=\"100%\" align=\"center\" border=\"1\">";
		$salida .="<tr class=\"modulo_table_title\">";
		$salida .="<td align=\"center\" colspan=\"2\">MOTIVO DE REMISION";
		$salida .="</td>";
		$salida .="</tr>";
		foreach ($num_motivo as $k => $v)
		{
			if($spy==0)
			{
				$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
				$spy=1;
			}
			else
			{
				$salida.="<tr class=\"hc_submodulo_list_claro\">";
				$spy=0;
			}
			$salida .="<td align=\"center\" colspan=\"2\">$v[0]";
			$salida .="</td>";
			$salida .="</tr>";
		}
		if (!empty($v[1]))
		{
			$salida .="<tr>";
			$salida .="<td class=\"hc_table_submodulo_list_title\" width=\"50%\">OTRO MOTIVO DE REMISION";
			$salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\">$v[1]";
			$salida .="</td>";
			$salida .="</tr>";
		}

		$salida .="</table><br>";

		$salida .="<table width=\"100%\" align=\"center\" border=\"1\">";
		$salida .="<tr class=\"modulo_table_title\">";
		$salida .="<td align=\"center\" colspan=\"3\">CONDUCTA DE REMISION";
		$salida .="</td>";
		$salida .="</tr>";
		$salida .="<tr class=\"hc_table_submodulo_list_title\">";
		$salida .="<td align=\"center\" colspan=\"3\">OBSERVACIONES";
		$salida .="</td>";
		$salida .="</tr>";
		foreach ($conducta as $k2 => $v2)
		{

			$salida .="<tr class=\"hc_submodulo_list_claro\" >";
			$salida .="<td align=\"left\" colspan=\"3\">$v2[0]";
			$salida .="</td>";
			$salida .="</tr>";
			$salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida .="<td align=\"center\" colspan=\"2\" width=\"50%\">TIPO DE REMISION";
			$salida .="</td>";
			$salida .="<td align=\"center\" width=\"50%\">TRASLADO";
			$salida .="</td>";
			$salida .="</tr>";
			$salida .="<tr>";
			$salida .="<td align=\"center\" colspan=\"2\" width=\"50%\" class=\"hc_submodulo_list_claro\">$v2[1]";
			$salida .="</td>";
			if ($v2[2] == '1')
			{
				$traslado = "TRASLADADO EN AMBULANCIA";
				$salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\" align=\"center\">$traslado";
				$salida .="</td>";
			}
			else
			{
				$traslado = "NO TRASLADADO EN AMBULANCIA";
				$salida .="<td class=\"hc_submodulo_list_claro\" width=\"50%\" align=\"center\">$traslado";
				$salida .="</td>";
			}
			$salida .="</tr>";
		}
		$salida .="</table><br>";

		$salida .="<table width=\"100%\" align=\"center\" border=\"1\">";
		$salida.="<tr class=\"modulo_table_title\">";
		$salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL PROFESIONAL TRATANTE</td>";
		$salida.="</tr>";

		$salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$salida.="<td align=\"center\" colspan=\"2\">REMITIDO POR:</td>";
		$salida.="</tr>";

		$salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$salida.="<td align=\"center\" width=\"50%\">NOMBRE DEL PROFESIONAL:</td>";
		$salida.="<td align=\"center\" width=\"50%\" class=\"modulo_list_oscuro\">$v2[3]</td>";
		$salida.="</tr>";
		$salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$salida.="<td colspan=\"2\" align=\"center\">$v2[4]</td>";
		$salida.="</tr>";
		$salida .="</table><br>";

		if (empty($this->datos[codcentro][descripcion]))
		{
			$salida .="<center>";
			$salida .="<label class=titulo3>El Paciente Fue Remitido a Otro Departamento o Institucion</label>";
			$salida .="</center>";
		}
		else
		{
			$salida .="<table width=\"100%\" align=\"center\" border=\"1\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"2\">INFORMACION DEL DEPARTAMENTO O CENTRO DE REMISION</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\" colspan=\"2\">REMITIDO A:</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td align=\"center\" width=\"50%\">NOMBRE DEL CENTRO O DEPARTAMENTO:</td>";
			$salida.="<td align=\"center\" width=\"50%\">NIVEL:</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_submodulo_list_oscuro\">";
			$salida.="<td align=\"center\" width=\"50%\">".$this->datos[codcentro][descripcion]."</td>";
			$salida.="<td align=\"center\" width=\"50%\">NIVEL DE LA INSTITUCION:&nbsp;&nbsp;".$this->datos[codcentro][nivel]."</td>";
			$salida.="</tr>";
			$salida .="</table><br><br>";
		}
		return $salida;
	}



		/**
		* Busca los Motivos de Remision Insertados
		* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
		* @access public
		* @return array
		* @param string plan_id
		*/
		function GetMotivos_Remision()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query = "SELECT A.descripcion, C.descripcion_otro_motivo
					  FROM hc_motivos_remision AS A, hc_conducta_remision_motivos AS B
					  LEFT JOIN hc_conducta_remision AS C
					  ON(B.ingreso = C.ingreso AND B.evolucion_id = C.evolucion_id)
					  WHERE A.motivo_remision_id = B.motivo_remision_id
					  AND B.ingreso = C.ingreso
					  AND B.evolucion_id = C.evolucion_id
					  AND B.ingreso = ".$this->datos[ingreso]."
					  AND B.evolucion_id = ".$this->datos[evolucion].";";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$num_motivo =$resulta->GetRows();
				//$num_motivo = ceil (($num_motivo + 1)/4);
				return $num_motivo;
			}
		}

		/**
		* Busca los Motivos la conducta de Remision Insertada
		* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
		* @access public
		* @return array
		* @param string plan_id
		*/
		function GetConduta_Remision()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query = "SELECT A.observaciones, B.descripcion, A.traslado_ambulancia,
							 C.nombre, C.descripcion
					  FROM hc_conducta_remision AS A
					  LEFT JOIN hc_tipo_remision AS B ON(B.tipo_remision_id = A.tipo_remision)
					  LEFT JOIN system_usuarios AS C ON(A.usuario_id = C.usuario_id)
					  WHERE A.ingreso = ".$this->datos[ingreso]."
					  AND A.evolucion_id = ".$this->datos[evolucion].";";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$conducta = $resulta->GetRows();
				return $conducta;
			}
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
