
<?php

/**
* Modulo de GestionSeguimientoCPN (PHP).
*
//*
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
**/

/**
* app_GestionSeguimientoCPN_userclasses_HTML.php
*
//*
**/
IncludeClass("ClaseHTML");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
IncludeClass("AntecedentesGO",null,"hc","AntecedentesGinecoObstetricos");

class app_GestionSeguimientoCPN_userclasses_HTML extends app_GestionSeguimientoCPN_user
{
	function app_GestionSeguimientoCPN_userclasses_HTML()
	{
			$this->app_GestionSeguimientoCPN_user(); //Constructor del padre 'modulo'
			$this->salida='';
			$this->redcolorf="#990000";
			return true;
	}
    
	//Determina las empresas, en las cuales el usuario tiene permisos
	//Selecciona las empresas disponibles
	function PrincipalPyP()
	{
		UNSET($_SESSION['pyp']);
		if($this->UsuariosPyP()==false)
		{
				return false;
		}
		return true;
	}
	
	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
					return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			else
			{
					return ("label_error");
			}
		}
		return ("label");
	}
    
	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton,$origen)
	{
		$this->salida .= ThemeAbrirTabla($titulo,'70%');
		$this->salida .= "<table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "	<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton)
		{
			$this->salida.= "<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"$boton\"></td></tr>";
		}
		else
		{
			$this->salida.= "<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"Aceptar\">";
			if($origen==1)
			{
				$this->salida.= "<input class=\"input-submit\" type=\"submit\" name=\"CancelarProceso\" value=\"Cancelar\">";
			}
			$this->salida.= "</td></tr>";
		}
		$this->salida.= "	</form>";
		$this->salida.= "</table>";
		$this->salida.= ThemeCerrarTabla();
		return true;
	}
	
	function FrmGestionSeguimientoCPN()
	{
		$this->salida .= ThemeAbrirTabla('GESTION DE SEGUIMIENTO CPN','80%');
	
		$accion1=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('opcion'=>1));
		$accion2=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('opcion'=>2));
		
		if($_REQUEST['SeguimientoCPN'])
		{
			$_SESSION['SeguimientoCPN']['emp_id']=$_REQUEST['SeguimientoCPN']['empresa_id'];
			$_SESSION['SeguimientoCPN']['cen_id']=$_REQUEST['SeguimientoCPN']['centro_utilidad'];
			$_SESSION['SeguimientoCPN']['uni_id']=$_REQUEST['SeguimientoCPN']['unidad_funcional'];
			$_SESSION['SeguimientoCPN']['dep_id']=$_REQUEST['SeguimientoCPN']['departamento'];
			$_SESSION['SeguimientoCPN']['tc_id']=$_REQUEST['SeguimientoCPN']['tipo_consulta_id'];
			$_SESSION['SeguimientoCPN']['cita_id']=$_REQUEST['SeguimientoCPN']['cargo_cita'];
			
			$_SESSION['SeguimientoCPN']['emp']=$_REQUEST['SeguimientoCPN']['desc_emp'];
			$_SESSION['SeguimientoCPN']['cen']=$_REQUEST['SeguimientoCPN']['desc_cen'];
			$_SESSION['SeguimientoCPN']['uni']=$_REQUEST['SeguimientoCPN']['desc_uni'];
			$_SESSION['SeguimientoCPN']['dep']=$_REQUEST['SeguimientoCPN']['desc_dept'];
			$_SESSION['SeguimientoCPN']['tc']=$_REQUEST['SeguimientoCPN']['desc_cons'];
			$_SESSION['SeguimientoCPN']['cita']=$_REQUEST['SeguimientoCPN']['desc_cita'];
		
		
			$_SESSION['permiso_usuario']=$this->ConsultaPermisosTiposConsulta($_SESSION['SeguimientoCPN']['tc']);
		}
		
		$this->salida .= "<table class=\"normal_10\" width=\"70%\" align=\"center\" border=\"0\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td>MENU PRINCIPAL DE GESTION DE SEGUIMIENTO DE CITAS PROGRAMA CPN</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td class=\"modulo_list_oscuro\"><label class=\"label\"><a href=\"$accion1\">MONITOREO DE PACIENTES PROXIMA CITA</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td class=\"modulo_list_claro\"><label class=\"label\"><a href=\"$accion2\">MONITOREO DE INASISTENTES O DE ALTO RIESGO</a></label></td>";
		$this->salida .= "	</tr>";
		$accion=ModuloGetURL('app','GestionSeguimientoCPN','user','PrincipalPyP');
		
		$this->salida.= " 	<form name=\"formavolver\" action=\"$accion\" method=\"post\">";	
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "</table>";
		$this->salida .= "	</form>";

		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}

	function FrmSeguimientoCPN($datos='',$opcion='')
	{
		
		$this->salida.= ThemeAbrirTabla('GESTION DE SEGUIMIENTO CPN','100%');
		
		if($_REQUEST['datos'])
			$mp=$_REQUEST['datos'];
		else
			$mp=$datos;
		
		if($_REQUEST['opcion'])
			$op=$_REQUEST['opcion'];
		else
			$op=$opcion;
		
		$accion3=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('opcion'=>$op));
		
		$paciente_id=str_replace(" ","",$mp['pd']);
		$tipo_id_paciente=str_replace(" ","",$mp['tpd']);
		
		$this->paciente_id=$paciente_id;
		$this->tipo_id_paciente=$tipo_id_paciente;
		
		$inscripcion=$mp['inscripcion_id'];
		$evolucion=$mp['evolucion_id'];
		$fecha_cita=$mp['fecha_ideal_proxima_cita'];
		
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
		$_SESSION[$tipo_id_paciente][$paciente_id]['Programa_id']=$programa;
		
		$seguimiento=$this->GetDatosSeguimiento($evolucion,$inscripcion);
		
		$cita_asignada_id=$cita_asignada[0][agenda_cita_asignada_id];
		$cita_asig_padre=$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_CPN_CITA'];
		$cita_asignada=$this->GetCitasID($paciente_id,$tipo_id_paciente,$cita_asig_padre,$mp['fecha_turno']);
		
		$cita_asignada_id=$cita_asignada[0][agenda_cita_asignada_id];
		
		$diagnosticos=$this->GetDiagnosticosPyp($evolucion);
		
		foreach($diagnosticos as $valor)
		{
			if(strtolower($valor['desc_cpn'])=='itu')
				$mp[itu]=1;
			elseif(strtolower($valor['desc_cpn'])=='cervicovaginitis')
				$mp[cervico]=1;
			elseif(strtolower($valor['desc_cpn'])=='vih')
				$mp[hiv_po]=1;
			else if(strtolower($valor['desc_cpn'])=='preecla')
				$mp[preecla]=1;
		}
		
		$hc_riesgo=new RiesgoBS($this);
		$hc_antece=new AntecedentesGO($this);
		
		$p_antece=$hc_antece->ObtenerPuntajeAsociado($mp[evolucion_id],$mp[inscripcion_id]);
		$p_riesgo=$hc_riesgo->ObtenerPuntaje_Riesgos($mp[inscripcion_id],$mp[evolucion_id],$mp[semana]);
		
		$accion1=ModuloGetURL('app','GestionSeguimientoCPN','user','IngresarSeguimientoCPN',array('seguimiento'=>$mp,'opcion'=>$op,'cita_asignada_id'=>$cita_asignada_id));
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}

		$semana_gestante=intval($this->CalcularSemanasGestante($mp[fecha_ultimo_periodo]));
		
		$this->salida.= "<form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
		$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida.= "			<td colspan=\"2\">DATOS PACIENTE</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";
		$this->salida.= "			<td width=\"50%\">NOMBRE</td>";
		$this->salida.= "			<td width=\"50%\">".$mp[nombre_paciente]."</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.= "			<td>DIRECCION</td>";
		$this->salida.= "			<td>".$mp[residencia_direccion]."</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";
		$this->salida.= "			<td>TELEFONO</td>";
		$this->salida.= "			<td>".$mp[residencia_telefono]."</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida.= "			<td>SEMANAS DE GESTACION</td>";
		$this->salida.= "			<td>$semana_gestante</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";
		$this->salida.= "			<td>FECHA PROBABLE DE PARTO</td>";
		$this->salida.= "			<td>".$mp[fecha_calulada_parto]."</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "	</table><br>";

		$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td colspan=\"6\">MOTIVOS PARA REALIZAR SEGUIMIENTO</td>";
		$this->salida.= "		</tr>";
		
		if(empty($mp[riesgo]))
		{
			$mp[riesgo]='BAJO';
		}
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";	
		$this->salida .= "		<td width=\"30%\">RIESGO BAJO</td>";
		if($mp[riesgo]=='BAJO')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"1\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		
		$this->salida .= "		<td width=\"30%\">RECORDAR PROXIMA CITA</td>";
		if(($mp[fecha_ideal_proxima_cita] > date("Y-m-d") AND empty($mp[fecha_turno])) OR  $mp[fecha_turno]>=date("Y-m-d"))
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"2\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida .= "		<td width=\"30%\">INASISTENCIA</td>";
		if(($mp[fecha_ideal_proxima_cita] < date("Y-m-d") AND empty($mp[fecha_turno])) OR (!empty($mp[fecha_turno]) AND $mp[sw_estado]!='3' AND $mp[fecha_turno] < date("Y-m-d")))
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"3\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";	
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "		<td width=\"30%\">ITU</td>";
		if($mp[itu])
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"4\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		
		$this->salida .= "		<td width=\"30%\">RIESGO ALTO</td>";

		if($mp[riesgo]=='ALTO')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"5\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
			
		$this->salida .= "		<td width=\"30%\">HIV POSITIVO</td>";
		if($mp[hiv_po])
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"6\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";
		
		$this->salida .= "		<td width=\"30%\">RIESGO PSICOSOCIAL</td>";
		
		$psicosocial=$p_riesgo[1]+$p_riesgo[2];
		
		if($mp[riesgo]=='ALTO')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"7\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
			
		$this->salida .= "		<td width=\"30%\">CERVICOVAGINITIS</td>";
		if($mp[cervico])
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"8\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida .= "		<td width=\"30%\">HTA EN EMBARAZO</td>";
		if($mp[hta])
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"9\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida.= "		</tr>";
		
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		
		$this->salida .= "		<td width=\"30%\">DIABETES GESTACIONAL</td>";
		if(!empty($mp[diabetes_gestacional]))
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"11\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		
		$this->salida .= "		<td width=\"30%\">REMITIDO A ESPECIALISTA</td>";
		if($mp[remision1]==1 OR $mp[remision2]==1)
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"12\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida .= "		<td width=\"30%\">CONTROL</td>";
		if($mp[tipo_atencion]=='CONTROL')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"13\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
			
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		
		$this->salida .= "		<td width=\"30%\">CIERRE DE CASO</td>";
		if($mp[tipo_atencion]=='CIERRE')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"14\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		
		$this->salida .= "		<td width=\"30%\">PREECLAMPSIA</td>";
		if($mp[preecla])
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$this->salida .= "		<input type=\"hidden\" name=\"motivos[]\" value=\"15\">";
		}
		else
			$this->salida .= "		<td width=\"4%\"></td>";
		$this->salida .= "		<td width=\"30%\" colspan=\"2\">&nbsp;</td>";
		$this->salida.= "		</tr>";
		
		$this->salida.= "	</table><br>";
		
		$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td colspan=\"3\">DIAGNOSTICOS DE SEGUIMIENTO</td>";
		$this->salida.= "		</tr>";
		
		$i=0;
		if(sizeof($diagnosticos)>0)
		{
			$this->salida.= "		<tr class=\"hc_table_submodulo_list_title\" align=\"center\">";
			$this->salida .= "		<td width=\"10%\">CODIGO</td>";
			$this->salida .= "		<td width=\"70%\">DESCRIPCION</td>";
			$this->salida .= "		<td width=\"20%\">TIPO SEGUIMIENTO</td>";
			$this->salida.= "		</tr>";
			foreach($diagnosticos as $valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";
			
				$this->salida.= "		<tr class=\"$estilo\" align=\"center\">";
				$this->salida .= "		<td width=\"10%\">".$valor['diagnostico_id']."</td>";
				$this->salida .= "		<td width=\"70%\">".$valor['diagnostico_nombre']."</td>";
				$this->salida .= "		<td width=\"20%\">".$valor['tipo_seguimiento']."</td>";
				$this->salida.= "		</tr>";
				$i++;
			}
		}
		else
		{
			$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
			$this->salida .= "		<td colspan=\"3\" class=\"label_error\">NO HAY DIAGNOSTICOS DEL PACIENTE</td>";
			$this->salida.= "		</tr>";
		}
		$this->salida.= "	</table><br>";
		
		//VERIFICAR SI EL USUARIO TIEME PERMISOS EN LA TABLA userpermisos_tipos_consulta
		if($_SESSION['permiso_usuario'])
		{
			$_SESSION['DatosSeguimientoCPN']=$mp;
			$_SESSION['opcion']=$op;

			$_SESSION['CumplirCita']['fechacita']=$mp[fecha_ideal_proxima_cita];
			unset($_SESSION['CumplirCita']);
			unset($_SESSION['LiquidarCitas']);
			unset($_SESSION['AsignacionCitas']);
			
			$a['Citas']=array('tipo_consulta_id'=>$_SESSION['SeguimientoCPN']['tc_id'],
			'departamento'=>$_SESSION['SeguimientoCPN']['dep_id'],
			'empresa_id'=>$_SESSION['SeguimientoCPN']['emp_id'],
			'descripcion3'=>$_SESSION['SeguimientoCPN']['tc'],
			'descripcion2'=>$_SESSION['SeguimientoCPN']['dep'],
			'descripcion1'=>$_SESSION['SeguimientoCPN']['emp'],    
			'sw_busqueda_citas'=>1);  
						
			$_SESSION['AsignacionCitas']['TipoDocumento']=$tipo_id_paciente;    
			$_SESSION['AsignacionCitas']['Documento']=$paciente_id;
			$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_CPN']=1;
			$_SESSION['SEGURIDAD']['Citas']['Arreglo'][2][$_SESSION['SeguimientoCPN']['emp']][$_SESSION['SeguimientoCPN']['dep']][$_SESSION['SeguimientoCPN']['tc']]=1;
			
			$accion=ModuloGetURL('app','AgendaMedica','user','DatosPaciente',$a);
			$this->salida.= "	<center>";
			if(!$cita_asignada)
				$this->salida.= "	<label class=\"label\"><a href=\"$accion\">ASIGNAR CITA</a></label>";
			else
				$this->salida.= "	<label class=\"label_error\">SE ASIGNÓ CITA</label>";
			$this->salida.= "	</center><br>";
		}
		
		$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td width=\"30%\">DIRECCIONAMIENTO A OTRAS IPS</td>";
		$this->salida .= "		<td width=\"35%\">HALLAZGOS EN CONTACTO TELEFONICO</td>";
		$this->salida .= "		<td width=\"35%\">OBSERVACIONES</td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "		<td><textarea name=\"ips\" cols=\"40\" rows=\"3\" class=\"input-text\"></textarea></td>";
		$this->salida .= "		<td><textarea name=\"telefono\" cols=\"40\" rows=\"3\" class=\"input-text\"></textarea></td>";
		$this->salida .= "		<td><textarea name=\"observacion\" cols=\"40\" rows=\"3\" class=\"input-text\"></textarea></td>";
		$this->salida.= "		</tr>";
		$this->salida.= "		</table><br>";

		$this->salida.= "		<table cellspacing=\"20\" align=\"center\">";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
		$this->salida .= "	</form>";
		$this->salida.= " 	<form name=\"formavolver\" action=\"$accion3\" method=\"post\">";	
		$this->salida .= "			<td><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td>";
		$this->salida .= "		</tr>";
		$this->salida.= "		</table><br>";
		$this->salida .= "	</form>";
		
		$this->IncludeJS("CrossBrowser");
		$i=0;
		$b=true;
		$capas="var capas1 = new Array(";
		
		foreach($seguimiento as $key=>$nivel1)
		{
			$motivo_seguimiento="";
			$salida="";
			
			foreach($nivel1 as $key1=>$nivel2)
			{
				$motivo_seguimiento.= $nivel2['pyp_cpn_motivo_descripcion']." , ";
			}
			
			$salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";

			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td width=\"20%\"><label class=\"label\">Motivos de seguimiento</label></td>";
			$salida.= "				<td>".trim($motivo_seguimiento," ,")."</td>";
			$salida.= "			</tr>";
		
			$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Direccionada a otra IPS</label></td>";
			if($nivel1[$key1]['ips'])
				$salida.= "				<td> Si ".$nivel1[$key1]['ips']."</td>";
			else
				$salida.= "				<td> No </td>";
			$salida.= "			</tr>";
			
			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Contacto Telefonico</label></td>";
			$salida.= "				<td>".$nivel1[$key1]['contacto_telefonico']."</td>";
			$salida.= "			</tr>";
			$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
			$salida.= "				<td width=\"20%\"><label class=\"label\">Observaciones</label></td>";
			$salida.= "				<td>".$nivel1[$key1]['observacion']."</td>";
			$salida.= "			</tr>";
			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Se asigno cita</label></td>";
			if($nivel1[$key1]['cita_asignada_id'])
			{
				if($nivel1[$key1]['sw_estado']=='3')
					$salida.= "				<td><b>Si <label class=\"label_error\">Cumplida</label>  - ".$nivel1[$key1]['fecha_turno']."</b></td>";
				else
					$salida.= "				<td><b>Si  - ".$nivel1[$key1]['fecha_turno']."</b></td>";
			}
			else
			{
				$salida.= "				<td><b>No</b></td>";
			}
				
			$salida.= "			</tr>";
      $salida.= "</table>";
			
			$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\" cellspacing=\"1\">";
			$this->salida.= "			<tr class=\"modulo_table_list_title\">";
			$this->salida.= "				<td colspan=\"2\" align=\"left\"><label>&nbsp;&nbsp;&nbsp;".$nivel1[$key1]['fecha']."</label> - <a href=\"javascript:showhide1('capa$i')\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"consultar\"></a></td>";
			$this->salida.= "			</tr>";
			$this->salida.= "	</table>";
			$this->salida.= "		<div id=\"capa$i\" style=\"display:none\">";
			$this->salida.= "   ".$salida;
			$this->salida.= "		</div>";
			
			$b? $capas.="'capa$i'":$capas.=",'capa$i'";
			$b=false;
			
			$i++;
		}
		
		$this->salida .= "<script language=\"javascript\">\n";
		$this->salida .= " ".$capas.");\n";
		
		$this->salida .= "	function showhide1(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		for(i=0; i<capas1.length; i++)\n";
		$this->salida .= "		{\n";
		$this->salida .= "			e = xGetElementById(capas1[i]);\n";
		$this->salida .= "			if(capas1[i] != Seccion)\n";
		$this->salida .= "			{\n";
		$this->salida .= "				e.style.display = \"none\";\n";
		$this->salida .= "			}\n";
		$this->salida .= "			else\n";
		$this->salida .= "			{\n";
		$this->salida .= "				if(e.style.display == \"none\")\n";
		$this->salida .= "				{\n";
		$this->salida .= "					e.style.display = \"\";\n";
		$this->salida .= "				}\n";
		$this->salida .= "				else \n";
		$this->salida .= "				{\n";
		$this->salida .= "					e.style.display = \"none\";\n";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "	}\n";
		$this->salida .= "</script>\n";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function FrmMonitoreoPacientes()
	{
    unset($_SESSION['PROMOCION_Y_PREVENCION']); 
		
		if($_REQUEST['opcion']==1)
		{	
			$titulo='MONITOREO DE PACIENTES PROXIMA CITA';
			$op=1;
		}
		else
		{
			$titulo='MONITOREO DE INASISTENTES O DE ALTO RIESGO';
			$op=0;
		}
		
		$this->salida .= ThemeAbrirTabla($titulo,'1100');
		$mp=$this->MonitoreoPacientes($_REQUEST['tipo_fecha'],$op);
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$accion1=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes');
		$accion3=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmGestionSeguimientoCPN');
		
		$this->salida .= "	<script language=\"javascript\">";
		$this->salida .= "		function mOvr(src,clrOver)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrOver;";
		$this->salida .= "		}";
		$this->salida .= "		function mOut(src,clrIn)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrIn;";
		$this->salida .= "		}";
		$this->salida .= "	</script>";
		
		$accionC=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('tipo_fecha'=>'1','opcion'=>$_REQUEST['opcion']));
		$accionP=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('tipo_fecha'=>'2','opcion'=>$_REQUEST['opcion']));
		
		$this->salida.= "	<table width=\"100%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
			
		$this->salida .= "		<td width=\"15%\">FECHA SUGERIDA PROXIMA CITA</td>";
		
		if($_REQUEST['tipo_fecha']==2)
		{
			$this->salida .= "		<td width=\"15%\"><a href=\"$accionC\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA PROXIMA CITA</td>";
			$this->salida .= "		<td width=\"15%\">FECHA PROBABLE DE PARTO</td>";
		}
		elseif($_REQUEST['tipo_fecha']==1)
		{
			$this->salida .= "		<td width=\"15%\">FECHA PROXIMA CITA</td>";
			$this->salida .= "		<td width=\"15%\"><a href=\"$accionP\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA PROBABLE DE PARTO</td>";
		}
		else
		{
			$this->salida .= "		<td width=\"15%\"><a href=\"$accionC\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA PROXIMA CITA</td>";
			$this->salida .= "		<td width=\"15%\"><a href=\"$accionP\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA PROBABLE DE PARTO</td>";
		}
		$this->salida .= "		<td width=\"25%\">NOMBRE DEL PACIENTE</td>";
		$this->salida .= "		<td width=\"15%\">TIPO DE ATENCION</td>";
		if($_REQUEST['opcion']==2)
			$this->salida .= "		<td width=\"15%\">INASISTENCIA</td>";
		
		$this->salida .= "		<td width=\"10%\">RIESGO</td>";
		$this->salida .= "		<td width=\"25%\">MOTIVO</td>";
		$this->salida .= "		<td width=\"15%\">SEGUMIENTO</td>";
		$this->salida.= "		</tr>";
		$k=0;

		if(sizeof($mp)>0)
		{
			foreach($mp as $key=>$valor)
			{
				if($k % 2 == 0)
				{
					$estilo='modulo_list_oscuro';
					$background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro';
					$background = "#DDDDDD";
				}
				
				$accion2=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmSeguimientoCPN',array('datos'=>$valor,'opcion'=>$_REQUEST['opcion']));
				
				$this->salida.= "		<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				$this->salida .= "		<td>".$valor[fecha_ideal_proxima_cita]."</td>";
				$this->salida .= "		<td>".$valor[fecha_turno]."</td>";
				$this->salida .= "		<td>".$valor[fecha_calulada_parto]."</td>";
				$this->salida .= "		<td>".$valor[nombre_paciente]."</td>";
				$this->salida .= "		<td>".$valor[tipo_atencion]."</td>";
				if($_REQUEST['opcion']==2)
					if(($valor[fecha_ideal_proxima_cita] < date("Y-m-d") AND empty($valor[fecha_turno])) OR (!empty($valor[fecha_turno]) AND $valor[sw_estado]!='3' AND $valor[fecha_turno] < date("Y-m-d")))
						$this->salida .= "		<td><font color=\"".$this->redcolorf."\">Si</font></td>";
					else
						$this->salida .= "		<td>No</td>";
						
				
				if(empty($valor[riesgo]))
					$valor[riesgo]='BAJO';
				
				if($valor[riesgo]=='ALTO')
					$this->salida .= "		<td><font color=\"".$this->redcolorf."\">".$valor[riesgo]."</font></td>";
				else
					$this->salida .= "		<td>".$valor[riesgo]."</td>";
				
				$diagnosticos=$this->GetDiagnosticosPyp($valor['evolucion_id']);
			
				foreach($diagnosticos as $valor1)
				{
					if(strtolower($valor1['desc_cpn'])=='itu')
						$valor[itu]=1;
					elseif(strtolower($valor1['desc_cpn'])=='cervicovaginitis')
						$valor[cervico]=1;
					elseif(strtolower($valor1['desc_cpn'])=='vih')
						$valor[hiv_po]=1;
					else if(strtolower($valor1['desc_cpn'])=='preecla')
						$valor[preecla]=1;
				}
				
				$motivos=$this->GetMotivo();
				$prioridad=$this->GetMaxPr();
				foreach($motivos as $mot)
				{
					if($mot['prioridad']<=$prioridad AND !empty($mot['prioridad']))
					{
						switch($mot['motivo_id'])
						{
							case 1:
								if($valor[riesgo]=='BAJO')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 2:
								if(($valor[fecha_ideal_proxima_cita] > date("Y-m-d") AND empty($valor[fecha_turno])) OR  $valor[fecha_turno]>=date("Y-m-d"))
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 3:
								if(($valor[fecha_ideal_proxima_cita] < date("Y-m-d") AND empty($valor[fecha_turno])) OR (!empty($valor[fecha_turno]) AND $valor[sw_estado]!='3' AND $valor[fecha_turno] < date("Y-m-d")))
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 4:
								if($valor[itu])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 5:
								if($valor[riesgo]=='ALTO')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 6:
								if($valor[hiv_po])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 7:
								if($valor[riesgo]=='ALTO')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 8:
								if($valor[cervico])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 9:
								if($valor[hta])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 10:
								if($valor[tipo_atencion]=='PRIMERA ATENCION')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 11:
								if($valor[diabetes_gestacional])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 12:
								if($valor[remision1]==1 OR $valor[remision2]==1)
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 13:
								if($valor[tipo_atencion]=='CONTROL')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 14:
								if($valor[tipo_atencion]=='CIERRE')
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;
							case 15:
								if($valor[preecla])
								{
									$motivo=$mot['motivo'];
									$prioridad=$mot['prioridad'];
								}
							break;	
						}
					}
				}
				
				$this->salida .= "		<td>".$motivo."</td>";
				$this->salida .= "		<td><label class=\"label\"><a href=\"$accion2\">SEGUIMIENTO</a></label></td>";
				$this->salida.= "		</tr>";
				$k++;
			}
		}
		else
		{
				$this->salida.= "<tr class=\"modulo_list_oscuro\" align=\"center\">";
			$this->salida .= "		<td class=\"label_error\" colspan=\"9\">NO SE ENCONTRARON REGISTROS DE PACIENTES</td>";
			$this->salida.= "		</tr>";
		}
		$this->salida.= "	</table><br>";
		
		$Paginador=new ClaseHTML();

		$accion = ModuloGetURL('app','GestionSeguimientoCPN','user','FrmMonitoreoPacientes',array('opcion'=>$_REQUEST['opcion']));
		$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accion,$this->limit);

		$this->salida.= "	<table cellspacing=\"20\" align=\"center\">";
		$this->salida.= " 	<form name=\"formavolver\" action=\"$accion3\" method=\"post\">";	
		$this->salida .= "		<tr align=\"center\">";	
		$this->salida .= "			<td><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</form>";
		$this->salida .= "</table><br>";
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
}//fin de la clase
?>