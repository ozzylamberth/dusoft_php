
<?php

/**
* Modulo de GestionSeguimientoReno (PHP).
*
//*
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
**/

/**
* app_GestionSeguimientoReno_userclasses_HTML.php
*
//*
**/
IncludeClass("ClaseHTML");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
IncludeClass("AntecedentesGO",null,"hc","AntecedentesGinecoObstetricos");

class app_GestionSeguimientoReno_userclasses_HTML extends app_GestionSeguimientoReno_user
{
	function app_GestionSeguimientoReno_userclasses_HTML()
	{
			$this->app_GestionSeguimientoReno_user(); //Constructor del padre 'modulo'
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
	
	function FrmGestionSeguimientoReno()
	{
		$this->salida .= ThemeAbrirTabla('GESTION DE SEGUIMIENTO RENOPROTECCION','80%');
	
		$accion1=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('opcion'=>1));
		$accion2=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('opcion'=>2));
		
		if($_REQUEST['SeguimientoReno'])
		{
			unset($_SESSION['SeguimientoReno']);
			
			$_SESSION['SeguimientoReno']['emp_id']=$_REQUEST['SeguimientoReno']['empresa_id'];
			$_SESSION['SeguimientoReno']['cen_id']=$_REQUEST['SeguimientoReno']['centro_utilidad'];
			$_SESSION['SeguimientoReno']['uni_id']=$_REQUEST['SeguimientoReno']['unidad_funcional'];
			$_SESSION['SeguimientoReno']['dep_id']=$_REQUEST['SeguimientoReno']['departamento'];
			$_SESSION['SeguimientoReno']['tc_id']=$_REQUEST['SeguimientoReno']['tipo_consulta_id'];
			$_SESSION['SeguimientoReno']['cita_id']=$_REQUEST['SeguimientoReno']['cargo_cita'];
			
			$_SESSION['SeguimientoReno']['emp']=$_REQUEST['SeguimientoReno']['desc_emp'];
			$_SESSION['SeguimientoReno']['cen']=$_REQUEST['SeguimientoReno']['desc_cen'];
			$_SESSION['SeguimientoReno']['uni']=$_REQUEST['SeguimientoReno']['desc_uni'];
			$_SESSION['SeguimientoReno']['dep']=$_REQUEST['SeguimientoReno']['desc_dept'];
			$_SESSION['SeguimientoReno']['tc']=$_REQUEST['SeguimientoReno']['desc_cons'];
			$_SESSION['SeguimientoReno']['cita']=$_REQUEST['SeguimientoReno']['desc_cita'];
		
			$_SESSION['permiso_usuario']=$this->ConsultaPermisosTiposConsulta($_SESSION['SeguimientoReno']['tc']);
		}
		
		$this->salida .= "<table class=\"normal_10\" width=\"70%\" align=\"center\" border=\"0\">";
		$this->salida .= "	<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td>MENU PRINCIPAL DE GESTION DE SEGUIMIENTO DE CITAS PROGRAMA RENOPROTECCION</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td class=\"modulo_list_oscuro\"><label class=\"label\"><a href=\"$accion1\">MONITOREO DE PACIENTES PROXIMA CITA</a></label></td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr align=\"center\">";
		$this->salida .= "		<td class=\"modulo_list_claro\"><label class=\"label\"><a href=\"$accion2\">MONITOREO DE INASISTENTES O DE RIESGO ESPECIFICO</a></label></td>";
		$this->salida .= "	</tr>";
		$accion=ModuloGetURL('app','GestionSeguimientoReno','user','PrincipalPyP');
		
		$this->salida.= " 	<form name=\"formavolver\" action=\"$accion\" method=\"post\">";	
		$this->salida .= "		<tr align=\"center\">";
		$this->salida .= "			<td><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "</table>";
		$this->salida .= "	</form>";

		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}

	function FrmSeguimientoReno($datos=null,$opcion=null,$ord=null)
	{
		
		$this->salida.= ThemeAbrirTabla('GESTION DE SEGUIMIENTO RENOPROTECCION','100%');
		
		if($_REQUEST['datos'])
			$mp=$_REQUEST['datos'];
		else
			$mp=$datos;
		
		if($_REQUEST['opcion'])
			$op=$_REQUEST['opcion'];
		else
			$op=$opcion;
		
		if($_REQUEST['ordenar'])
			$ordenar=$_REQUEST['ordenar'];
		else
			$ordenar=$ord;
		
		
		$accion3=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('opcion'=>$op,'ordenar'=>$ordenar));
		
		$paciente_id=trim($mp['pd']);
		$tipo_id_paciente=trim($mp['tpd']);

		$inscripcion=$mp['inscripcion_id'];
		$evolucion=$mp['evolucion_id'];
		$fecha_cita=$mp['fecha_ideal_proxima_cita'];
		
		$programa=ModuloGetVar('hc_submodulo','AtencionReno','Renoproteccion');
		$_SESSION[$tipo_id_paciente][$paciente_id]['Programa_id']=$programa;
		
		$seguimiento=$this->GetDatosSeguimiento($evolucion,$inscripcion);
		
		$cita_asig_padre=$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_RENOPROTECCION_CITA'];
		$cita_asignada=$this->GetCitasID($paciente_id,$tipo_id_paciente,$cita_asig_padre,$mp['fecha_contacto']);
		$cita_asignada_id=$cita_asignada[0][agenda_cita_asignada_id];

		$accion1=ModuloGetURL('app','GestionSeguimientoReno','user','IngresarSeguimientoReno',array('seguimiento'=>$mp,'opcion'=>$op,'cita_asignada_id'=>$cita_asignada_id,'ordenar'=>$ordenar));
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}

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
		$this->salida.= "	</table><br>";

		$motivos="  ";
		
		$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "		<td colspan=\"6\">MOTIVOS PARA REALIZAR SEGUIMIENTO</td>";
		$this->salida.= "		</tr>";
		
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";	
		$this->salida .= "		<td width=\"30%\">ESTADIO KDOQI</td>";
		if($mp[estadio_kdoqi]>=3)
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "ESTADIO KDOQI, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
		
		$this->salida .= "		<td width=\"30%\">REMISION INTERNISTA</td>";
		if($mp[remision_internista]==1)
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "REMISION INTERNISTA, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
		
		$this->salida .= "		<td width=\"30%\">DETERIORO ACELERADO</td>";
		if($mp[riesgo_deterioro_acelerado]=="t")
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "DETERIORO ACELERADO, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";	
		$this->salida.= "		</tr>";
		
		$this->salida.= "		<tr class=\"modulo_list_claro\" align=\"center\">";
		$this->salida .= "		<td width=\"30%\">ADHERENCIA FARMACOLOGICA</td>";
		if($mp[adherencia_farmacologica]=="t")
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "ADHERENCIA FARMACOLOGICA, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
		
		$this->salida .= "		<td width=\"30%\">REMISION NUTRICIONISTA</td>";

		if($mp[remision_nutricionista]==1)
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "REMISION NUTRICIONISTA, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
			
		$this->salida .= "		<td width=\"30%\">RECORDAR PROXIMA CITA</td>";
		if(($mp[fecha_ideal_proxima_cita] > date("Y-m-d") AND empty($mp[fecha_contacto])) OR $mp[fecha_contacto] > date("Y-m-d"))
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "RECORDAR PROXIMA CITA, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
		$this->salida.= "		</tr>";
		
		$this->salida.= "		<tr class=\"modulo_list_oscuro\" align=\"center\">";
		$this->salida .= "		<td width=\"30%\">INASISTENCIA</td>";
		if(!empty($mp[fecha_contacto]) AND $mp[fecha_contacto] < date("Y-m-d")  AND $mp[estado_cita]!='3')
		{
			$this->salida .= "		<td width=\"4%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			$motivos.= "INASISTENCIA, ";
		}
		else
			$this->salida .= "		<td width=\"4%\">&nbsp;</td>";
			
		$this->salida .= "		<td width=\"30%\" colspan=\"4\"></td>";
		$this->salida.= "		</tr>";
		$this->salida.= "	</table><br>";
		
		$this->salida .= "		<input type=\"hidden\" name=\"motivos\" value=\"$motivos\">";
		
		//VERIFICAR SI EL USUARIO TIEME PERMISOS EN LA TABLA userpermisos_tipos_consulta
		if($_SESSION['permiso_usuario'])
		{
			$_SESSION['DatosSeguimientoReno']=$mp;
			$_SESSION['opcion']=$op;

			$_SESSION['CumplirCita']['fechacita']=$mp[fecha_ideal_proxima_cita];
			unset($_SESSION['CumplirCita']);
			unset($_SESSION['LiquidarCitas']);
			unset($_SESSION['AsignacionCitas']);
			
			$a['Citas']=array('tipo_consulta_id'=>$_SESSION['SeguimientoReno']['tc_id'],
			'departamento'=>$_SESSION['SeguimientoReno']['dep_id'],
			'empresa_id'=>$_SESSION['SeguimientoReno']['emp_id'],
			'descripcion3'=>$_SESSION['SeguimientoReno']['tc'],
			'descripcion2'=>$_SESSION['SeguimientoReno']['dep'],
			'descripcion1'=>$_SESSION['SeguimientoReno']['emp'],    
			'sw_busqueda_citas'=>1);  
						
			$_SESSION['AsignacionCitas']['TipoDocumento']=$tipo_id_paciente;    
			$_SESSION['AsignacionCitas']['Documento']=$paciente_id;
			$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_RENOPROTECCION']=1;
			$_SESSION['SEGURIDAD']['Citas']['Arreglo'][2][$_SESSION['SeguimientoReno']['emp']][$_SESSION['SeguimientoReno']['dep']][$_SESSION['SeguimientoReno']['tc']]=1;
			
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
		
		foreach($seguimiento as $valor)
		{
			$salida = "	<table width=\"80%\" align=\"center\" border=\"0\">";

			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td width=\"20%\"><label class=\"label\">Motivos de seguimiento</label></td>";
			$salida.= "				<td>".$valor['motivos_seguimiento']."</td>";
			$salida.= "			</tr>";
		
			$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Direccionada a otra IPS</label></td>";
			if($valor['ips'])
				$salida.= "				<td> Si ".$valor['ips']."</td>";
			else
				$salida.= "				<td> No </td>";
			$salida.= "			</tr>";
			
			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Contacto Telefonico</label></td>";
			$salida.= "				<td>".$valor['contacto_telefonico']."</td>";
			$salida.= "			</tr>";
			$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Observaciones</label></td>";
			$salida.= "				<td>".$valor['observacion']."</td>";
			$salida.= "			</tr>";
			$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
			$salida.= "				<td><label class=\"label\">Se asigno cita</label></td>";
			if($valor['cita_asignada_id'])
			{
				if($valor['estado_cita']=='3')
					$salida.= "				<td><b>Si <label class=\"label_error\">Cumplida</label>  - ".$valor['fecha_contacto']."</b></td>";
				else
					$salida.= "				<td><b>Si  - ".$valor['fecha_contacto']."</b></td>";
			}
			else
				$salida.= "				<td><b>No</b></td>";
				
			$salida.= "			</tr>";
      $salida.= "</table>";
			
			$this->salida.= "	<table width=\"80%\" align=\"center\" border=\"0\" cellspacing=\"1\">";
			$this->salida.= "			<tr class=\"modulo_table_list_title\">";
			$this->salida.= "				<td colspan=\"2\" align=\"left\"><label>&nbsp;&nbsp;&nbsp;".$valor['fecha']."</label> - <a href=\"javascript:showhide1('capa$i')\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"consultar\"></a></td>";
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
		UNSET($_SESSION['PROMOCION_Y_PREVENCION']);
		
		if($_REQUEST['opcion']==1)
		{
			$titulo='MONITOREO DE PACIENTES PROXIMA CITA';
			$op=1;
		}
		else
		{
			$titulo='MONITOREO DE INASISTENTES O DE RIESGO ESPECIFICO';
			$op=0;
		}
		
		$this->salida .= ThemeAbrirTabla($titulo,'100%');
		
		$mp=$this->MonitoreoPacientes($_REQUEST['ordenar'],$op);
		
		if($this->ban==1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$accion1=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes');
		$accion3=ModuloGetURL('app','GestionSeguimientoReno','user','FrmGestionSeguimientoReno');
		
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
		
		$accionC=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('ordenar'=>1,'opcion'=>$_REQUEST['opcion']));
		$accionP=ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('ordenar'=>2,'opcion'=>$_REQUEST['opcion']));
		
		$this->salida.= "	<table width=\"100%\" align=\"center\" border=\"0\">";
		$this->salida.= "		<tr class=\"modulo_table_list_title\" align=\"center\">";
			
		if($_REQUEST['ordernar']==2)
		{
			$salida .= "		<td width=\"10%\"><a href=\"$accionC\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA CONTACTO/td>";
			$salida1 .= "		<td width=\"10%\">ESTADIO</td>";
		}
		elseif($_REQUEST['ordernar']==1)
		{
			$salida .= "		<td width=\"10%\">FECHA CONTACTO</td>";
			$salida1 .= "		<td width=\"10%\"><a href=\"$accionP\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>ESTADIO</td>";
		}
		else
		{
			$salida .= "		<td width=\"10%\"><a href=\"$accionC\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>FECHA CONTACTO</td>";
			$salida1 .= "		<td width=\"10%\"><a href=\"$accionP\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>ESTADIO</td>";
		}
		
		$this->salida .= "		<td width=\"10%\">FECHA SUGERIDA PROXIMA CITA</td>";
		$this->salida .= "		$salida";
		$this->salida .= "		<td width=\"25%\">NOMBRE DEL PACIENTE</td>";
		$this->salida .= "		<td width=\"10%\">TIPO DE ATENCION</td>";
		$this->salida .= "		$salida1";
		if($_REQUEST['opcion']==2)
			$this->salida .= "		<td width=\"10%\">CUMPLIMIENTO CITA</td>";
		$this->salida .= "		<td width=\"10%\">SEGUIMIENTO</td>";
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
				
				$accion2=ModuloGetURL('app','GestionSeguimientoReno','user','FrmSeguimientoReno',array('datos'=>$valor,'opcion'=>$_REQUEST['opcion'],'ordenar'=>$_REQUEST['ordenar']));
				
				$this->salida.= "		<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				$this->salida .= "		<td>".$valor[fecha_ideal_proxima_cita]."</td>";
				$this->salida .= "		<td>".$valor[fecha_contacto]."</td>";
				$this->salida .= "		<td>".$valor[nombre_paciente]."</td>";
				$this->salida .= "		<td>".$valor[tipo_atencion]."</td>";
				$this->salida .= "		<td>".$valor[estadio_kdoqi]."</td>";
				if($_REQUEST['opcion']==2)
				{
					if(!empty($valor[fecha_contacto]))
					{
						if($valor[estado_cita]=='3')
							$this->salida .= "		<td class=\"label_error\"><b>Si</b></td>";
						else
							$this->salida .= "		<td><b>No</b></td>";
					}
					else
						$this->salida .= "		<td>&nbsp;</td>";
				}
				$this->salida .= "		<td><label class=\"label\"><a href=\"$accion2\">SEGUIMIENTO</a></label></td>";
				$this->salida.= "		</tr>";
				$k++;
			}
		}
		else
		{
			$this->salida.= "<tr class=\"modulo_list_oscuro\" align=\"center\">";
			$this->salida .= "		<td class=\"label_error\" colspan=\"7\">NO SE ENCONTRARON REGISTROS DE PACIENTES</td>";
			$this->salida.= "		</tr>";
		}
		$this->salida.= "	</table><br>";
		
		$Paginador=new ClaseHTML();

		$accion = ModuloGetURL('app','GestionSeguimientoReno','user','FrmMonitoreoPacientes',array('opcion'=>$_REQUEST['opcion'],'ordenar'=>$_REQUEST['ordenar']));
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