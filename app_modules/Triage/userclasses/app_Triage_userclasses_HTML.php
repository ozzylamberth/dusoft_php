<?php

/**
 * $Id: app_Triage_userclasses_HTML.php,v 1.29 2006/11/14 13:30:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual del triage.
 */

/**
* Clase app_Triage_userclasses_HTML
*
* Contiene los metodos visuales para realizar el triage y admision de los pacientes
*/

class app_Triage_userclasses_HTML extends app_Triage_user
{

	/**
	*Constructor de la clase app_Triage_user_HTML
	*El constructor de la clase app_Triage_user_HTML se encarga de llamar
	*a la clase app_Triage_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

	/**
	* Es el contructor de la clase.
	* Se encarga de llamar a la clase app_Triage_user quien se encarga del tratamiento de la base de datos.
	* @return boolean
	*/
  function app_Triage_user_HTML()
	{
				$this->salida='';
				$this->app_Triage_user();
				return true;
	}


	/**
	* Se encarga de mostrar los errores.
	* @access private
	* @return string
	*/
  function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}


	/**
	* Forma del menu de admisiones
	* @access private
	* @return boolean
	*/
	function FormaMenus()
	{
        $this->salida .= ThemeAbrirTabla('MENUS ADMISION URGENCIAS');
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MENU ADMISIONES URGENCIAS</td>";
				$this->salida .= "				       </tr>";
				//if(empty($_SESSION['TRIAGE']['FUNCIONARIO']))
				//{
							$this->salida .= "				       <tr>";
							$accionB=ModuloGetURL('app','Triage','user','Buscar');
							$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionB\" onMouseOver=\"window.status='Ingresar Paciente';return true;\" onMouseOut=\"window.status=''; return true;\">Ingresar Paciente</a></td>";
							$this->salida .= "				       </tr>";
							$this->salida .= "				       <tr>";
							$accionM=ModuloGetURL('app','Triage','user','MetodoBuscar');
							$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\" onMouseOver=\"window.status='Modificar Datos Paciente';return true;\" onMouseOut=\"window.status=''; return true;\">Modificar Datos Paciente</a></td>";
							$this->salida .= "				       </tr>";
							$this->salida .= "				       <tr>";
							$accionI=ModuloGetURL('app','Triage','user','BuscarListadoIngresos');
							$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionI\" onMouseOver=\"window.status='Impresión Datos';return true;\" onMouseOut=\"window.status=''; return true;\">Impresión Datos</a></td>";
							$this->salida .= "				       </tr>";
							$this->salida .= "				       <tr>";
							$accionA=ModuloGetURL('app','Triage','user','LlamarBuscarPaciente');
							$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\" onMouseOver=\"window.status='Buscar Paciente';return true;\" onMouseOut=\"window.status=''; return true;\">Buscar Paciente</a></td>";
							$this->salida .= "				       </tr>";
				//}
				if($_SESSION['TRIAGE']['SWTRIAGE'])
				{
						/*if(!empty($_SESSION['TRIAGE']['FUNCIONARIO']))
						{
								$this->salida .= "				       <tr>";
								$accionC=ModuloGetURL('app','Triage','user','LlamaListadoTriage');
								$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionC\" onMouseOver=\"window.status='Clasificación Triage';return true;\" onMouseOut=\"window.status=''; return true;\">Clasificación Triage</a></td>";
								$this->salida .= "				       </tr>";
						}
						else
						{*/
								$this->salida .= "				       <tr>";
								$accionA=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
								$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\" onMouseOver=\"window.status='Pacientes Clasificados Pendientes Por Admitir';return true;\" onMouseOut=\"window.status=''; return true;\">Pacientes Clasificados Pendientes Por Admitir</a></td>";
								$this->salida .= "				       </tr>";
						//}
				}
				$this->salida .= "			     </table>";
				$accion=ModuloGetURL('app','Triage','user','main');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
				$this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Se encarga de mostrar los campos obligatorios cuando estan en un field.
	* @access private
	* @return string
	*/
  function SetStyleField($campo)
	{
				if ($this->frmErrorF[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmErrorF["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("field");
	}

	/**
	* Se encarga de mostrar los campos obligatorios cuando estan en un field y este es el segundo en la forma.
	* @access private
	* @return string
	*/
	function SetStyleField2($campo)
	{
				if ($this->frmErrorF2[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='4' align='center'>".$this->frmErrorF2["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("field");
	}


//-------------------------LO NUEVO------------------------------

	/**
	*
	*/
	function FormaYaTriage($var)
	{
				$pto=$this->NombrePunto($_SESSION['TRIAGE']['PTOADMON']);
 				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS PUNTO '.$pto);
				$mensaje='El paciente '.$var[tipo_id_paciente].' '.$var[paciente_id].' '.$var[nombre].', esta en espera de ser Clasificado en el Punto '.$var[descripcion].' y esta
				asignado en el Punto '.$var[descadmon].'.<br>Fecha Registro: '.$var[hora_llegada];
				$this->salida .= "			      <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" colspan=\"3\" class=\"label\">$mensaje</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accion=ModuloGetURL('app','Triage','user','AdmitirDirectamente',array('var'=>$var));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				          <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ADMITIR\"></td>";
				$this->salida .= "</form>";
				if($_SESSION['TRIAGE']['PTOADMON']!=$var[punto_admision_id])
				{
							$accion=ModuloGetURL('app','Triage','user','CambiarPtoAdmon',array('var'=>$var));
							$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
							$this->salida .= "				          <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CAMBIAR PUNTO\"></td>";
							$this->salida .= "</form>";
				}
				$accion=ModuloGetURL('app','Triage','user','Buscar');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				          <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
				$this->salida .= "</form>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table>";
    		$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaNuevo()
	{
 				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS REMISION');
				//mensaje
				$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  </table>";
				$accion=ModuloGetURL('app','Triage','user','DatosRemision');
				$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td class=\"".$this->SetStyle("fecha")."\">FECHA REMISION:</td>";
				$this->salida .= "  <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"fecha\" size=\"12\" value=\"".$_REQUEST['fecha']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','fecha','/')."</td>";
				$this->salida .= "               </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "  <td class=\"".$this->SetStyle("HoraAuto")."\">HORA REMISION: </td>";
				$this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"hora\" size=\"4\" value=\"".$_REQUEST['hora']."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"minuto\" size=\"4\" value=\"".$_REQUEST['minuto']."\" maxlength=\"2\"></td>";
				$this->salida .= "               </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td class=\"".$this->SetStyle("entidad")."\">ENTIDAD:</td>";
				$this->salida .= "<td colspan=\"2\"><select name=\"entidad\" class=\"select\">";
				$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
				$centro=$this->CentrosRemision();
				for($i=0; $i<sizeof($centro); $i++)
				{
						if($centro[$i][centro_remision]==$_REQUEST['entidad'])
						{  $this->salida .=" <option value=\"".$centro[$i][centro_remision]."\" selected>".$centro[$i][descripcion]."</option>";  }
						else
						{  $this->salida .=" <option value=\"".$centro[$i][centro_remision]."\">".$centro[$i][descripcion]."</option>";  }
				}
				$this->salida .= "</select></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td class=\"".$this->SetStyle("remision")."\">No. REMISION:</td>";
				$this->salida .= "				       <td><input type=\"text\" class=\"input-text\" name=\"remision\" value=\"".$_REQUEST['remision']."\"></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     <tr>";
				$mostrar=ReturnClassBuscador('diagnostico','','','forma');
				$this->salida.=$mostrar;
				$this->salida.="</script>\n";
				$this->salida .= "			        <td class=\"".$this->SetStyle("diagnostico")."\">DIAGNOSTICO: </td>";
				$this->salida.= "<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"".$_REQUEST['codigo']."\">";
				$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"cargo\" READONLY>".$_REQUEST['cargo']."</textarea></td>";
				$this->salida .= "			        <td><input type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=abrirVentana() class=\"input-submit\"></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("observacion")."\">OBSERVACIONES: </td>";
				$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     </table>";
        $this->salida .= "       <table align=\"center\" border=\"0\" width=\"40%\">";
        $this->salida .= "    <tr>";
				if(empty($_SESSION['TRIAGE']['PACIENTE']['SIGNOS']))
				{
						$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"admitir\" type=\"submit\" value=\"ADMITIR\"></td>";
						if(!empty($_SESSION['TRIAGE']['SWTRIAGE']))
						{
							$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"triage\" type=\"submit\" value=\"TRIAGE\"></td>";
						}
				}
				else
				{
						$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"signos\" type=\"submit\" value=\"ACEPTAR\"></td>";
				}
        $this->salida .= "    </tr>";
        $this->salida .= "       </table>";
				$this->salida .= "		</form>";
    		$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaMenuTriage()
	{
       	$this->salida .= ThemeAbrirTabla('TRIAGE');
				$this->BorrarProceso();
				unset($_SESSION['CONT']);
				$this->salida .= "			      <br>";
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MENU TRIAGE</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionB=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionB\">INGRESAR PACIENTE Y CLASIFICAR</a></td>";
				$this->salida .= "				       </tr>";
				$accionM=ModuloGetURL('app','Triage','user','ListarPacientes');
				$x=$this->CantidadListado();
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\">LISTADO PACIENTES POR CLASIFICAR TRIAGE [ $x ]</a></td>";
				$this->salida .= "				       </tr>";
				//link para buscar triages
				$accion=ModuloGetURL('app','Triage','user','LlamarFormaBuscarTriagesPacientes');
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">BUSCAR TRIAGES PACIENTES</a></td>";
				$this->salida .= "				       </tr>";
				//fin link para buscar triages
				$this->salida .= "				       <tr>";
				$this->salida .= "			     </table>";
				$var=$this->BuscarNoAtender();
				if(!empty($var))
				{
						$this->salida .= "			      <br>";
						$this->salida .= "			      <table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "				       <tr align=\"center\" class=\"modulo_table_list_title\"><td colspan=\"7\">PACIENTES NO ATENDIDOS SIN CLASIFICAR POR MEDICO</td></tr>";
						$this->salida .= "				       <tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "				         <td>IDENTIFICACION</td>";
						$this->salida .= "				         <td>PACIENTE</td>";
						$this->salida .= "				         <td>FECHA</td>";
						$this->salida .= "				         <td>HORA</td>";
						$this->salida .= "				         <td>ESTACION ENFERMERIA</td>";
						$this->salida .= "				         <td>PUNTO TRIAGE</td>";
						$this->salida .= "				         <td>PLAN</td>";
						$this->salida .= "				       </tr>";
						for($i=0; $i<sizeof($var); $i++)
						{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida .= "				       <tr class=\"$estilo\">";
								$this->salida .= "				         <td>".$var[$i][tipo_id_paciente]." ".$var[$i][paciente_id]."</td>";
								$this->salida .= "				         <td>".$var[$i][nombre]."</td>";
								$this->salida .= "				         <td align=\"center\">".$this->FechaStamp($var[$i][hora_llegada])."</td>";
								$this->salida .= "				         <td align=\"center\">".$this->HoraStamp($var[$i][hora_llegada])."</td>";
								$this->salida .= "				         <td align=\"center\" width=\"20%\">".$var[$i][descenf]."</td>";
								$this->salida .= "				         <td align=\"center\">".$var[$i][descripcion]."</td>";
								$this->salida .= "				         <td align=\"center\">".$var[$i][plan_descripcion]."</td>";
								$this->salida .= "				       </tr>";
						}
						$this->salida .= "			     </table>";
				}
				if($_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO'] < 3)
				{  $this->ListadoPacientesAtendidosTriage();  }
				$accion=ModuloGetURL('app','Triage','user','Triage');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
				$this->salida .= "</form>";
    		$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param int plan_id
	*/
	function FormaBuscarTriage($TipoId,$PacienteId,$Responsable)
	{
				unset($_SESSION['TRIAGE']['PUNTO']['PTOADMON']);
				unset($_SESSION['TRIAGE']['DIAGNOSTICO']);
				unset($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				$action=ModuloGetURL('app','Triage','user','BuscarPacienteTriage');
				$this->salida .= ThemeAbrirTabla('TRIAGE - BUSCAR PACIENTE TRIAGE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
				$responsables=$this->responsables();
				$this->MostrarResponsable($responsables,$Responsable);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
				$tipo_id=$this->CallMetodoExterno('app','Triage','user','tipo_id_paciente','');
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$TipoId)
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$PacienteId\"></td></tr>";
				$this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				$actionM=ModuloGetURL('app','Triage','user','FormaMenuTriage');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ATRAS\"><br></td></form></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaPuntos($var)
	{
				$action=ModuloGetURL('app','Triage','user','LlamarFormaClasificacionTriage',array('var'=>$var));
        $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR PUNTO ADMISION');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <tr><td class=\"label\" colspan=\"2\" align=\"center\">Elija el Punto de Admisión al Que va a Remitir el Paciente.</td>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO ADMISION: </td><td><select name=\"Punto\" class=\"select\">";
				$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
				for($i=0; $i<sizeof($var); $i++)
				{
						$this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\">".$var[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
				$this->salida .= "	  <tr align=\"center\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "    </form>";
				$accionGuardarTodos=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
				$this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
				$this->salida .= "    </form>";
				$this->salida .= "	  </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaPuntosTriage($var)
	{
				$action=ModuloGetURL('app','Triage','user','DefinirTriage',array('var'=>$var));
        $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR PUNTO TRIAGE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <tr><td class=\"label\" colspan=\"2\" align=\"center\">Elija el Punto de Triage al Que va a Remitir el Paciente.</td>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO TRIAGE: </td><td><select name=\"Punto\" class=\"select\">";
				$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
				for($i=0; $i<sizeof($var); $i++)
				{
						$this->salida .= "                   <option value=\"".$var[$i][punto_triage_id]."\">".$var[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
				$this->salida .= "	  <tr align=\"center\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "    </form>";
				$accionGuardarTodos=ModuloGetURL('app','Triage','user','FormaNuevo');
				$this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
				$this->salida .= "    </form>";
				$this->salida .= "	  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaCambiarPtoTriage($var,$triage)
	{
				$action=ModuloGetURL('app','Triage','user','ActualizarPtoTriage',array('var'=>$var,'Triage'=>$triage));
        $this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR PUNTO TRIAGE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <tr><td class=\"label\" colspan=\"2\" align=\"center\">Elija el Punto de Triage al Que va a Remitir el Paciente.</td>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO TRIAGE: </td><td><select name=\"Punto\" class=\"select\">";
				$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
				for($i=0; $i<sizeof($var); $i++)
				{
						$this->salida .= "                   <option value=\"".$var[$i][punto_triage_id]."\">".$var[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
				$this->salida .= "	  <tr align=\"center\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "    </form>";
				$accionGuardarTodos=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
				$this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
				$this->salida .= "    </form>";
				$this->salida .= "	  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* cuando se va aha cambiar el pto triage por el auxiliar
	*/
	function FormaPtoTriagePaciente($mensaje,$triage,$pto)
	{
				$action=ModuloGetURL('app','Triage','user','CambiarPtoTriage',array('msg'=>$mensaje,'Triage'=>$triage,'pto'=>$pto));
				$this->salida .= ThemeAbrirTabla('TRIAGE - ELEGIR PUNTO TRIAGE');
				$this->salida .= "			      <table width=\"55%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <tr><td class=\"label_mark\" colspan=\"2\" align=\"center\">$mensaje</td></tr>";
				$this->salida .= "				       <tr><td class=\"label_mark\" colspan=\"2\" align=\"center\">&nbsp;</td></tr>";
				$this->salida .= "				       <tr><td class=\"label\" colspan=\"2\" align=\"center\">Elija el Punto de Triage al Que va a Remitir el Paciente.</td></tr>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO TRIAGE: </td><td><select name=\"Punto\" class=\"select\">";
				$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
				$var=$this->TodosPuntosTriage();
				for($i=0; $i<sizeof($var); $i++)
				{
						$this->salida .= "                   <option value=\"".$var[$i][punto_triage_id]."\">".$var[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
				$this->salida .= "	  <tr align=\"center\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "    </form>";
				$accionGuardarTodos=ModuloGetURL('app','Triage','user','FormaBuscarTriage');
				$this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
				$this->salida .= "    </form>";
				$this->salida .= "	  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

//---------------------------------------------------------------------------------
	/**
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param int plan_id
	*/
	function FormaBuscar($TipoId,$PacienteId,$Responsable)
	{
				$action=ModuloGetURL('app','Triage','user','BuscarIngresoPaciente');
				$this->salida .= ThemeAbrirTabla('ADMISIONES - BUSCAR PACIENTE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .="<input type='hidden' name='NoAutorizacion' value=''>";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
				$responsables=$this->responsables();
				$this->MostrarResponsable($responsables,$Responsable);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
				$tipo_id=$this->tipo_id_paciente();
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$TipoId)
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$PacienteId\"></td></tr>";
				$this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				if($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION')
				{	$actionM=ModuloGetURL('app','Triage','user','MenusHospitalizacion'); }
				else{ $actionM=ModuloGetURL('app','Triage','user','Menus'); }
				$this->salida .= "             <form name=\"forma\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form></tr>";
				$this->salida .= "			     </table>";

				$this->SetJavaScripts('BuscadorBD');
				$this->salida .='<br>';
				$this->salida .='<table border="0" align="right" width="50%">';
				$this->salida .='<tr align="right">';
				$this->salida .='<td align="right" class="normal_10">';
				$this->salida.=RetornarWinOpenDatosBuscadorBD($_SESSION['TRIAGE']['DPTO'],'formabuscar');
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$this->salida .='</table>';

				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Forma para mostrar la cuenta que tiene abierta el paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param array datos de la cuenta abierta del paciente
	*/
	function FormaExisteIngreso($TipoId,$PacienteId,$var)
	{
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - BUSCAR PACIENTE');
				$this->salida .= "			      <br><br>";
				$this->salida .= "			      <table width=\"35%\" align=\"center\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
				$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
				$this->salida .= "				       <tr>";
				$this->salida .= "				           <td class=\"label\" width=\"42%\">PACIENTE: </td>";
				$this->salida .= "                   <td colspan=\"2\">$Nombres $Apellidos</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				           <td class=\"label\">IDENTIFICACION: </td>";
				$this->salida .= "                   <td colspan=\"2\">$TipoId $PacienteId</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				           <td class=\"label\">CUENTA No. : </td>";
				$this->salida .= "                   <td colspan=\"2\">".$var[numerodecuenta]."</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$actionCancelar=ModuloGetURL('app','Triage','user','Buscar');
				$this->salida .= "                   <form name=\"formabuscar\" action=\"$actionCancelar\" method=\"post\">";
				$this->salida .= "				           <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\"></form></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Script que muestra una ventana emergente con los datos de los pacientes.
	* @access private
	*/
		function consultarUsuarios()
		{
			$this->salida .= "<SCRIPT>";
			$this->salida .= "function consultar(nombre, url, ancho, altura,frm){";
			$this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=yes';";
			$this->salida .= " var url2 = url+'&tipoId='+frm.TipoId.value+'&pacienteId='+frm.PacienteId.value;";
			$this->salida .= " rem = window.open(url2, nombre, str);";
			$this->salida .= "  if (rem != null) {";
			$this->salida .= "     if (rem.opener == null) {";
			$this->salida .= "       rem.opener = self;";
			$this->salida .= "     }";
			$this->salida .= "  }";
			$this->salida .= "}";
			$this->salida .= "</SCRIPT>";
		}

	/**
	* Script que muestra una ventana emergente con los datos de los pacientes cuando son afiliados.
	* @access private
	*/
		function Afiliados()
		{
			$this->salida .= "<SCRIPT>";
			$this->salida .= "function Afiliados(nombre, url, ancho, altura,Tipo,Paciente,Tabla){";
			$this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
			$this->salida .= " var url2 = url+'?TipoId='+Tipo+'&PacienteId='+Paciente+'&Tabla='+Tabla;";
			$this->salida .= " rem = window.open(url2, nombre, str);";
			$this->salida .= "  if (rem != null) {";
			$this->salida .= "     if (rem.opener == null) {";
			$this->salida .= "       rem.opener = self;";
			$this->salida .= "     }";
			$this->salida .= "  }";
			$this->salida .= "}";
			$this->salida .=  "</SCRIPT>";
		}


		/**
		* Forma que muestra las cuenta por cobrar que tiene un paciente
		* @access private
		* @return boolean
		* @param array arreglo con los datos de la cuentas por cobrar de un paciente
		*/
		function FormaCuentasxCobrar($CXC)
		{ 
				if(!$CXC){ $CXC=$_REQUEST['CXC']; }
				IncludeLib("tarifario");
				$this->salida .= "<br><table width=\"85%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "			<tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "				<td colspan=\"5\">CARTERA PENDIENTE</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "				<td>EMPRESA</td>";
				$this->salida .= "				<td>CENTRO UTILIDAD</td>";
				$this->salida .= "				<td>FECHA VENCIMIENTO</td>";
				$this->salida .= "				<td>VALOR</td>";
				$this->salida .= "				<td>SALDO</td>";
				$this->salida .= "			</tr>";
				$vect='';
				for($i=0; $i<sizeof($CXC); $i++)
				{
						if($i % 2) {  $estilo="modulo_list_claro";  }
						else {  $estilo="modulo_list_oscuro";   }
						if(date("Y/m/d") > $CXC[$i][fecha_vencimiento])
						{  $est='label_error';  }
							$this->salida .= "	<tr class=\"$estilo\" class=\"$estilo\" align=\"center\">";
						$this->salida .= "		<td>".$CXC[$i][razon_social]."</td>";
						$this->salida .= "		<td>".$CXC[$i][descripcion]."</td>";
						$this->salida .= "		<td class=\"$est\">".$CXC[$i][fecha_vence]."</td>";
						$this->salida .= "		<td class=\"$est\">".FormatoValor($CXC[$i][valor])."</td>";
						$this->salida .= "		<td class=\"$est\">".FormatoValor($CXC[$i][saldo])."</td>";
						$this->salida .= "	</tr>";
				}
				$this->salida .= "	</table><br>";
				return true;
		}

	/**
	* Muestra las cuentas inactivas que tiene un paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	*/
	function FormaCuentasInactivas($TipoId,$PacienteId)
	{
				$var=$this->BuscarCuentasInactivas($TipoId,$PacienteId);
				if($var)
				{
						$this->salida .= "	<br><table width=\"35%\" cellspacing=\"2\" border=\"1\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "<tr class=\"modulo_table_list_title\">";
						$this->salida .= "<td>EL PACIENTE TIENE CUENTAS INACTIVAS</td>";
						$this->salida .= "</tr>";
						for($i=0; $i<sizeof($var); $i++)
						{
								if($i % 2) {  $estilo="modulo_list_claro";  }
								else {  $estilo="modulo_list_oscuro";   }
								$this->salida .= "<tr align=\"center\" >";
								$this->salida .= "<td class=\"$estilo\">CUENTA No. ".$var[$i][numerodecuenta]."</td>";
								$this->salida .= "</tr>";
						}
						$this->salida .= "</table><br>";
				}
	}

 //-------------------------------------
	/**
	* Pide el nivel del plan del responsable.
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param int plan_id
	* @param array arreglo con los diferentes tipos de niveles del plan
	* @param string accion de la forma
	*/
	function FormaPedirNivel($niveles)
	{
				$Responsable=$_SESSION['TRIAGE']['PACIENTE']['plan_id'];
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS PACIENTE');
				$this->salida .= "	<br>";
				$accion=ModuloGetURL('app','Triage','user','TerminarIngreso');
				$this->salida .= "	<table width=\"30%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
				$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$accion\" method=\"post\">";
				$this->salida .= "		<input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
				$this->salida .= "    <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
				$this->salida .= "    <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$Nombre=$this-> NombrePlan($Responsable);
				$this->salida .= "		<tr height=\"20\"><td class=\"label\">RESPONSABLE: </td><td>$Nombre</td></tr>";
				$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Nivel")."\">NIVEL: </td><td><select name=\"Nivel\"  class=\"select\">";
				for( $i=0;$i<sizeof($niveles);$i++){
						$this->salida .=" <option value=\"$niveles[$i]\">$niveles[$i]</option>";
				}
				$this->salida .= "       </select></td></tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
				$this->salida .= "	 </tr>";
				$this->salida .= "  </form>";
				$this->salida .= "</table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}
	//------------------------------------

	/**
	* Lista los pacientes que estan en el triage y da la opcion de admitir, clasificar triage y signos vitales.
	* @access private
	* @return boolean
	* @param array con los datos de los pacientes
	*/
  function ListadoPacienteTriage($vars)
	{
				/*$this->salida="<script language=javascript>\n";
				$this->salida.="function load_page()\n";
				$this->salida.="{\n";
				$this->salida.="location.reload();\n";
				$this->salida.="}\n";
				$this->salida.="</script>\n";
				$this->salida.="<body onload=compt=setTimeout('load_page();',300000)>\n";*/		
	
				$this->SetJavaScripts('DatosPaciente');
				if(!empty($vars))
				{				
						$nom=$this->NombrePtoTriage($_SESSION['TRIAGE']['PUNTO']['PTOTRIAGE']);
						$actionAdmitir=ModuloGetURL('app','Triage','user','Admisiones');
						$this->salida .= ThemeAbrirTabla('TRIAGE - LISTADO DE PACIENTES PUNTO TRIAGE ('.$nom.')');
						$this->salida .= "		   <br>";
						$this->salida .= "		<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "				<td width=\"20%\">PACIENTE</td>";
						//$this->salida .= "				<td width=\"14%\">DOCUMENTO</td>";
						$this->salida .= "				<td width=\"6%\">FECHA INGRESO</td>";
						$this->salida .= "				<td width=\"6%\">HORA INGRESO</td>";
						$this->salida .= "				<td width=\"8%\">TIEMPO DE ESPERA</td>";
						$this->salida .= "				<td width=\"20%\">RESPONSABLE</td>";
						$this->salida .= "				<td width=\"7%\">PRE CLASIFICACION</td>";
						$this->salida .= "				<td width=\"15%\">PUNTO ADMISION</td>";
						if($_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO'] <=4){
							$this->salida .= "				<td width=\"6%\"></td>";
							$this->salida .= "				<td width=\"6%\"></td>";
						}
						$this->salida .= "			</tr>";
						$i=0;
						$y=1;
						for($i=0; $i<sizeof($vars); $i++)
						{
								$tipo=$vars[$i][tipo_id_paciente];
								$documento=$vars[$i][paciente_id];
								$Nombre=$this->NombrePaciente($tipo,$documento);
								$Fecha=$vars[$i][hora_llegada];
								$res=$this->Responsable($vars[$i][plan_id]);
								if( $i % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
								$actionSignos=ModuloGetURL('app','Triage','user','SignosVitalesTriage',array('TipoId'=>$tipo,'PacienteId'=>$documento,'Plan'=>$vars[$i][plan_id],'Triage'=>$vars[$i][triage_id],'Admon'=>$vars[$i][punto_admision_id]));
								$actionClasificar=ModuloGetURL('app','Triage','user','ClasificacionTriage',array('TipoId'=>$tipo,'PacienteId'=>$documento,'Plan'=>$vars[$i][plan_id],'Triage'=>$vars[$i][triage_id]));
								$Fechas=$this->FechaStamp($Fecha);
								$Horas=$this->HoraStampSeg($Fecha);
								$TiempoEspera=$this->TiempoDeEspera($Horas,$Fecha);
								$this->salida .= "			<tr class=\"$estilo\">";
								$nombre=$Nombre[primer_nombre]." ".$Nombre[segundo_nombre]." ".$Nombre[primer_apellido]." ".$Nombre[segundo_apellido];
								$this->salida .= "				<td align=\"center\">".RetornarWinOpenDatosPaciente($vars[$i][tipo_id_paciente],$vars[$i][paciente_id],$nombre)."</td>";
								//$this->salida .= "				<td><input type=\"hidden\" name=\"TipoId\" value=\"$tipo\"><input type=\"hidden\" name=\"PacienteId\" value=\"$documento\">$tipo  $documento</td>";
								$this->salida .= "				<td align=\"center\"><input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">$Fechas</td>";
								$this->salida .= "				<td align=\"center\">$Horas</td>";
								$this->salida .= "				<td align=\"center\">$TiempoEspera</td>";
								$this->salida .= "				<td align=\"center\">$res</td>";
								//$triage=$this->NombrePtoTriage($vars[$i][punto_triage_id]);
								$est='';
								if(!empty($vars[$i][nivel_triage_asistencial]))
								{
											if($vars[$i][nivel_triage_asistencial]==1)
											{  $est='nivel1_claro';  }
											elseif($vars[$i][nivel_triage_asistencial]==2)
											{  $est='nivel2_claro';  }
											elseif($vars[$i][nivel_triage_asistencial]==3)
											{  $est='nivel3_claro';  }
											elseif($vars[$i][nivel_triage_asistencial]==4)
											{  $est='nivel4_claro';  }
											$this->salida .= "				<td class=\"$est\" align=\"center\">NIVEL ".$vars[$i][nivel_triage_asistencial]."</td>";
								}
								else
								{  											$this->salida .= "				<td align=\"center\"></td>"; }
								$this->salida .= "				<td align=\"center\">".$vars[$i][descripcion]."</td>";
								if(empty($vars[$i][proceso]))
								{
										if($_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==1 OR $_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==2){
														$this->salida .= "				<td align=\"center\"><a href=\"$actionClasificar\">CLASIFICAR</a></td>";
										}
										elseif($_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==3 OR $_SESSION['TRIAGE']['PUNTO']['FUNCIONARIO']==4)
										{
														$this->salida .= "				<td align=\"center\"><a href=\"$actionSignos\">SIGNOS VITALES</a></td>";
										}
										$accionS=ModuloGetURL('app','Triage','user','SacarPacienteLista',array('nombre'=>$nombre,'paciente_id'=>$documento,'tipo_id_paciente'=>$tipo,'triage_id'=>$vars[$i][triage_id]));
										$this->salida .= "				<td align=\"center\"><a href=\"$accionS\">SACAR</a></td>";
								}
								else
								{
										$this->salida .= "				<td align=\"center\" class=\"label_mark\" colspan=\"2\">EN PROCESO</td>";
								}
								$this->salida .= "			</tr>";								
						}
						$this->salida .= "  </table>";
						$this->conteo=$_SESSION['CONT'];
						$this->salida .=$this->RetornarBarra5();
						$this->salida .= "		<table width=\"20%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
						$actionM=ModuloGetURL('app','Triage','user','UserTriage');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
						$this->salida .= "				       <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
						$actionM=ModuloGetURL('app','Triage','user','ListarPacientes');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
						$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"REFRESCAR\"><br></td></form></tr>";						
						$this->salida .= "  </table>";
				}
				else
				{
						$this->salida .= "		<table width=\"20%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
						$this->salida .= "				       <tr><td align=\"center\">NO HAY PACIENTES</td></tr>";
						$actionM=ModuloGetURL('app','Triage','user','UserTriage');
						$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
						$this->salida .= "				       <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form></tr>";
						$this->salida .= "  </table>";
				}
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

		/**
		*
		*/
		function FormaSacarLista($tipoid,$id,$nombre,$triage,$ingreso,$metodo)
		{
					$this->salida .= ThemeAbrirTabla('TRIAGE - SACAR PACIENTE LISTADO');
					//mensaje
					$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "  </table><BR>";
					$accion=ModuloGetURL('app','Triage','user','SacarPaciente',array('metodoS'=>$metodo,'triage_id'=>$triage,'nombre'=>$nombre,'tipo_id_paciente'=>$tipoid,'paciente_id'=>$id,'ingreso'=>$ingreso));
					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\" class=\"label_MARK\" colspan=\"2\">IDENTIFICACION: ".$tipoid." ".$id."<BR>PACIENTE: ".$nombre."<BR>
					EL PACIENTE SERA SACADO DEL LISTADO Y SE CANCELARA SU PROCESO DE ATENCION EN LA INSTITUCION <BR>
					POR FAVOR ESPECIFIQUE EL MOTIVO</td>";
					$this->salida .= "              </tr>";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\" class=\"label\">OBSERVACION: </td>";
					$this->salida .= "				       <td align=\"center\"><textarea cols=\"70\" rows=\"3\" class=\"textarea\"name=\"observacion\"></textarea>";
					$this->salida .= "              </tr>";
					$this->salida .= "			     </table>";
					$this->salida .= "			      <table width=\"50%\" align=\"center\" >";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
					$this->salida .= "			     </form>";
					if(empty($metodo))
					{  $metodo='ListarPacientes';  }
					$accion=ModuloGetURL('app','Triage','user',$metodo);
					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
					$this->salida .= "              </tr>";
					$this->salida .= "			     </form>";
					$this->salida .= "			     </table>";
					$this->salida .= ThemeCerrarTabla();
					return true;
		}


	function RetornarBarra5(){
		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(is_null($paso)){
    	$paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		$accion=ModuloGetURL('app','Triage','user','ListarPacientes');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
    }
	}

	/**
	* Lista los pacientes que ya fueron clasificos en el triage y que se les tomaron los signos vitaels y que puden ya ser admitidos.
	* @access private
	* @return boolean
	* @param array con los datos de los pacientes
	*/
  function ListadoPacienteAdmisiones($vars)
	{
		$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - LISTADO DE PACIENTES ADMISIONES');
		$this->salida .= "		   <br>";
		$this->salida .= "		<table width=\"93%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "				<td>PACIENTE</td>";
		$this->salida .= "				<td  width=\"12%\" nowrap>DOCUMENTO</td>";
		$this->salida .= "				<td width=\"11%\" nowrap>FECHA INGRESO</td>";
		$this->salida .= "				<td width=\"10%\" nowrap>HORA INGRESO</td>";
		$this->salida .= "				<td width=\"7%\" nowrap>NIVEL</td>";
		$this->salida .= "				<td width=\"7%\" nowrap>NIVEL ASIS.</td>";
		$this->salida .= "				<td width=\"23%\" nowrap>MOTIVO CONSULTA</td>";
		$this->salida .= "				<td width=\"7%\" nowrap></td>";
		$this->salida .= "				<td width=\"6%\" nowrap></td>";
		$this->salida .= "			</tr>";
		for($i=0; $i<sizeof($vars); $i++)
		{
			$datos = $this->BuscarDatosPaciente($vars[$i][tipo_id_paciente],$vars[$i][paciente_id]);
			$Nombre = $this->NombrePaciente($vars[$i][tipo_id_paciente],$vars[$i][paciente_id]);
			$FechaTriage = $vars[$i][hora_llegada];
			if( $i % 2) 
				$estilo='modulo_list_claro';
			else 
				$estilo='modulo_list_oscuro';
			
			$Tipo = $vars[$i][tipo_id_paciente];
			$Paciente = $vars[$i][paciente_id];
				//accion
			$adm = false;
			if($vars[$i][nivel_triage_id]==0)
			{  
				$adm=true; 
			}
			
			$accion = ModuloGetURL('app','Triage','user','AdmitirPaciente',array('TipoId'=>$Tipo,'PacienteId'=>$Paciente,'Triage'=>$vars[$i][triage_id],'Responsable'=>$vars[$i][plan_id],'Nivel'=>$adm));
			$Fechas = $this->FechaStamp($FechaTriage);
			$Horas = $this->HoraStamp($FechaTriage);
			$TiempoEspera = $this->TiempoDeEspera($Horas,$Fechas);
			
			echo "Tiempo ".$TiempoEspera." HoraL ".$HoraLimite." Vector ".$res[triage_id]." OV ".$vars[$i][triage_id]."<br>";
			
			if(($vars[$i][triage_id]!='4')OR($TiempoEspera<$HoraLimite && $res[triage_id]=='4'))
			{
				$this->salida .= "			<tr class=\"$estilo\">";
				$nombre=$Nombre[primer_nombre]." ".$Nombre[segundo_nombre]." ".$Nombre[primer_apellido]." ".$Nombre[segundo_apellido];
				$this->salida .= "				<td>$Nombre[primer_nombre] $Nombre[segundo_nombre] $Nombre[primer_apellido] $Nombre[segundo_apellido]</td>";
				$this->salida .= "				<td><input type=\"hidden\" name=\"TipoId\" value=\"".$vars[$i][tipo_id_paciente]."\"><input type=\"hidden\" name=\"PacienteId\" value=\"".$vars[$i][paciente_id]."\">$Tipo $Paciente</td>";
				$this->salida .= "				<td align=\"center\"><input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">$Fechas</td>";
				$this->salida .= "				<td align=\"center\">$Horas</td>";
				if($vars[$i][nivel_triage_id]){ $nivel=$vars[$i][nivel_triage_id];}
				else { $nivel='Sin Clasificar';}
				$this->salida .= "				<td align=\"center\">$nivel</td>";
				$this->salida .= "				<td width=\"7%\" nowrap align=\"center\">".$vars[$i][nivel_triage_asistencial]."</td>";
				$this->salida .= "				<td>".$vars[$i][motivo_consulta]."</td>";
				//if(!$vars[$i][nivel_triage_id] AND !$vars[$i][nivel_triage_asistencial]){
				if(empty($vars[$i][punto_admision_id])){
					$this->salida .= "				<td align=\"center\"></td>";
				}
				else{
					$this->salida .= "				<td align=\"center\"><a href=\"$accion\">ADMITIR</a></td>";
				}
			}
			$accion=ModuloGetURL('app','Triage','user','SacarPacienteLista',array('metodoS'=>'ListarPacientesAdmisiones','tipo_id_paciente'=>$Tipo,'paciente_id'=>$Paciente,'triage_id'=>$vars[$i][triage_id],'nombre'=>$nombre));
			$this->salida .= "				<td align=\"center\"><a href=\"$accion\">SACAR</a></td>";
		}
		$this->salida .= "  </table>";
		$this->conteo=$_SESSION['CONT'];
		$this->salida .=$this->RetornarBarra4();
		$this->salida .= "		<table width=\"25%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
		$actionM=ModuloGetURL('app','Triage','user','Menus');
		$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "				       <tr>";
		$this->salida .= " <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
		$action=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
		$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
		$this->salida .= " <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"REFRESCAR\"><br></td></form>";
		$this->salida .= " </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarra4(){
		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(is_null($paso)){
    	$paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		$accion=ModuloGetURL('app','Triage','user','ListarPacientesAdmisiones');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
    }
	}

	/**
	* Forma para mensajes.
	* @access private
	* @return boolean
	* @param string mensaje
	* @param string nombre de la ventana
	* @param string accion de la forma
	* @param string nombre del boton
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton,$TriageId,$empresa)
	{
				$reporte= new GetReports();
				$mostrar=$reporte->GetJavaReport('app',$modulo,'triage',$vector,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();
       // print_r($TriageId);
        $nombre="";
        //print_r($_SESSION['TRIAGE']['PUNTO']['EMPRESA']);
       // print_r($_SESSION['ADMISIONES']['PACIENTE']['nombre']);
        $this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "			      <table width=\"60%\" align=\"center\">";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
				if($boton){
				   $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
				}
       else{
				   $this->salida .= "				       <tr><td colspan=\"1\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td>";
          
                $reporte= new GetReports();
                $mostrar=$reporte->GetJavaReport('app','Admisiones','triage',array('triage_id'=>$_SESSION['TRIAGE']['PACIENTE']['triage_id'],'empresa'=>$_SESSION['TRIAGE']['PUNTO']['EMPRESA'],'nombre'=>$nombre),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                $funcion=$reporte->GetJavaFunction();
                $this->salida .=$mostrar;
                $this->salida .= "				      <td colspan=\"1\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"Imprimir\" onclick=\"javascript:$funcion\"></td></tr>";
      
      }
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}


	function FormaImpresionRemision($arr)
	{
				$this->salida .= ThemeAbrirTabla('REMISION PACIENTE');
				$this->salida .= "			      <table width=\"60%\" align=\"center\" border=0>";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <tr><td colspan=\"4\" class=\"label\" align=\"center\">SE REMITIO EL PACIENTE<br><br></td></tr>";
				$reporte= new GetReports();
				$mostrar=$reporte->GetJavaReport('app','Remisiones','contrareferenciaHTM',array('triage'=>$_SESSION['TRIAGE']['PACIENTE']['triage_id']),array('rpt_name'=>'contrareferencia','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></td>";
				unset($reporte);
				$this->salida .= "			     </form>";
				if(!empty($_SESSION['TRIAGE']['ATENCION']))
				{			//CUANDO LO LLAMA JAIME
							$contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
							$modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
							$tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
							$metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
							$argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
							$_SESSION['RETORNO']['TRIAGE']['ATENCION']=true;
							$accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
				}
				else
				{  $accion=ModuloGetURL('app','Triage','user','FormaMenuTriage');  }
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Forma para capturar los signos vitales del paciente.
	* @access private
	* @return boolean
	* @param string motivo de la consulta
	* @param string observaciones de la enfermera
	* @param int frecuencia cardiaca
	* @param int frecuencoa respiratoria
	* @param int temperatura corporal
	* @param int peso
	* @param int tension arterial alta
	* @param int tension arterial baja
	*/
 function FormaSignosVitalesTriage($MotivoConsulta,$ObservacionesEnfermera,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja)
 {
 			
        IncludeLib("funciones_admision");
				$TipoId=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
				$PacienteId=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
				$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
				$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
				$titulofield='SIGNOS VITALES';
				$FechaNacimiento=$this->Edad($TipoId,$PacienteId);
				$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
				$Edad=$EdadArr['edad_aprox'];
				$Sexo=$this->NombreSexo($TipoId,$PacienteId);
				if(empty($ObservacionesEnfermera))
				{
						$ObservacionesEnfermera=$this->BuscarObservacionEnfermera($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				}
				//PARA VER SI HAY SIGNOS
				$_REQUEST['Nivel']=$this->NivelTriage($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				$signos=$this->BuscarSignosVitales($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				if(empty($MotivoConsulta))
				{  $MotivoConsulta=$this->BuscarMotivoConsulta($_SESSION['TRIAGE']['PACIENTE']['triage_id']);  }
				if($signos!="")
				{
						$signosV = explode ('-', $signos);
						$fc=$signosV[0];
						$fr=$signosV[1];
						$temperatura=$signosV[2];
						$peso=$signosV[3];
						$tAlta=$signosV[4];
						$tBaja=$signosV[5];
						if(empty($_REQUEST['eva']))
						{  $_REQUEST['eva']=$signosV[6]; }
						$_REQUEST['motora']=$signosV[7];
						$_REQUEST['verbal']=$signosV[8];
						$_REQUEST['ocular']=$signosV[9];
						$_REQUEST['sato']=$signosV[10];
				}
				$action=ModuloGetURL('app','Triage','user','InsertarSignosVitalesTriage',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId));
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - SIGNOS VITALES TRIAGE');
				$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" >";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "          <tr><td class=\"label\" width=\"25%\">Apellidos: </td><td>$Apellidos</td>";
				$this->salida .= "              <td class=\"label\" width=\"25%\">Nombres: </td><td>$Nombres</td></tr>";
				$this->salida .= "          <tr><td class=\"label\">Tipo Identificacion: </td><td>$TipoId</td>";
				$this->salida .= "              <td class=\"label\">Numero Identificacion: </td><td>$PacienteId</td></tr>";
				$datos=$this->BuscarDatosPaciente($TipoId,$PacienteId);
				foreach($datos as $Fecha=>$NomApellido){ $HoraIngreso=$this->HoraStamp($Fecha);}
				$this->salida .= "          <tr><td class=\"label\">Edad: </td><td>$Edad</td>";
				$this->salida .= "              <td class=\"label\">Hora Ingreso: </td><td>$HoraIngreso</td></tr>";
				$this->salida .= "          <tr><td class=\"label\" width=\"25%\">Sexo: </td><td>$Sexo</td>";
				$this->salida .= "              <td class=\"label\" width=\"25%\"></td><td></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
        $this->salida .= "  <form name=\"formasignosvitales\" action=\"$action\" method=\"post\">";
				$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\">";
				$this->salida .= $this->SetStyleField("MensajeError");
				$this->salida .= "       <tr><td><fieldset><legend class=\"".$this->SetStyleField("MotivoConsulta")."\">MOTIVO CONSULTA</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr  align=\"center\"><td  width=\"30%\"><textarea name=\"MotivoConsulta\" cols=\"115\" rows=\"3\" class=\"textarea\">$MotivoConsulta</textarea></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">OBSERVACIONES</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr  align=\"center\"><td  width=\"30%\"><textarea name=\"ObservacionesEnfermera\" cols=\"115\" rows=\"3\" class=\"textarea\">$ObservacionesEnfermera</textarea></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				//SIGNOS VITALES
				$this->salida .= "      <table border=\"0\" width=\"62%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">$titulofield</legend>";
				$this->FormaSignosVitales($FechaNacimiento,$EdadArr,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$_REQUEST['eva'],$_REQUEST['ocular'],$_REQUEST['verbal'],$_REQUEST['motora'],$_REQUEST['sato']);
				$this->salida .= "		  </fieldset></td></tr></table>\n";
				$this->salida .= "      <br><table border=\"0\" width=\"62%\" align=\"center\">";
				
				$niveles = $this->ObtenerNivelesTriage();
				if($niveles)
				{
					$chk= "";
					$this->salida .= "       <tr>\n";
					$this->salida .= "       	<td>\n";
					$this->salida .= "       		<fieldset><legend class=\"".$this->SetStyleField2("taAlta")."\">CLASIFICACION ASISTENCIAL TRIAGE</legend>\n";
					//$this->salida .= $this->SetStyleField2("MensajeError");
					$this->salida .= "       			<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"4\">\n";
					$this->salida .= "       				<tr class=\"normal_11N\">\n";					
					foreach($niveles as $key=> $triage)
					{
						($_REQUEST['Nivel'] == $key)? $chk = "checked": $chk= "";

						$this->salida .= "       					<td bgcolor=\"".$triage['color_oscuro']."\" title=\"".$triage['descripcion']."\">\n";
						$this->salida .= "       						<input type=\"radio\" name=\"Nivel\" value=\"$key\" $chk> <b style=\"color:".$triage['color_letra']."\">NIVEL $key</b>\n";
						$this->salida .= "       					</td>\n";
					}
					$this->salida .= "       				</tr>\n";
		
					$this->salida .= "       			</table>\n";
					$this->salida .= "       		</fieldset>\n";
					$this->salida .= "       	</td>\n";
					$this->salida .= "       </tr>\n";
				}
				$this->salida .= "      </table><BR>";
				//combo puntos admision y no atender
				$var=$this->PuntosAdmon();
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"40%\" align=\"center\"  >";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO ADMISION: </td><td><select name=\"Punto\" class=\"select\">";
				$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
				for($i=0; $i<sizeof($var); $i++)
				{
						if($_SESSION['TRIAGE']['PACIENTE']['punto_admision_id']==$var[$i][punto_admision_id])
						{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\" selected>".$var[$i][descripcion]."</option>";  }
						else
						{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\">".$var[$i][descripcion]."</option>";  }
						//$this->salida .= "                   <option value=\"N,".$var[$i][punto_admision_id]."\">ATENCION AMBULATORIA - ".$var[$i][descripcion]."</option>";
				}
				$this->salida .= "                   <option value=\"N\">SOLICITAR REMISION MEDICA</option>";
				//$this->salida .= "                   <option value=\"N\">ATENCION AMBULATORIA</option>";
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"40%\" align=\"center\"  >";
				$this->salida .= "	  <tr align=\"center\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
				$this->salida .= "    </form>";
				if(!empty($_SESSION['TRIAGE']['PACIENTE']['triage_id']))
				{  $accion=ModuloGetURL('app','Triage','user','ListarPacientes');  }
				else
				{  $accion=ModuloGetURL('app','Triage','user','UserTriage');  }
				$this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
				$this->salida .= "    </form>";
				$this->salida .= "	  </tr>";
				$this->salida .= "	  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Calcula el tiempo de espera para ser atendido un paciente en urgencias.
	* @access private
	* @return atring
	* @param date hora de ingreso
	* @param date fecha de ingreso
	*/
 	function TiempoDeEspera($Horas,$Fechas)
	{
			$Fecha=$this->FechaStamp($Fechas);
			$infoCadena = explode ('/',$Fecha);
			$diaIngreso=$infoCadena[0];
			$mesIngreso=$infoCadena[1];
			$anoIngreso=$infoCadena[2];
			$intervalo=$this->HoraStamp($Fechas);
			$infoCadena = explode (':', $intervalo);
			$HoraIngreso=$infoCadena[0];
			$MinutosIngreso=$infoCadena[1];
			$SegIngreso=$infoCadena[2];
			$Minutos=((mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))-mktime($HoraIngreso,$MinutosIngreso,$SegIngreso,$mesIngreso,$diaIngreso,$anoIngreso)))/60;
			$y=($Minutos%60);
			$s=($y/60);
			$h=(int) ($Minutos/60);
			$m=(int) ($Minutos%60);
			return str_pad($h,2,0,STR_PAD_LEFT).':'.str_pad($m,2,0,STR_PAD_LEFT);
			//$Minutos=date('H:i:s',mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))-mktime($HoraIngreso,$MinutosIngreso,$SegIngreso,$mesIngreso,$diaIngreso,$anoIngreso));
 }

	/**
	* Muestra en el combo los tipo de identificacion
	* @access private
	* @return string
	* @param array con los tipos de idnetificacion
	* @param boolean indica si el combo ya esta seleccionado
	* @param int el tipo de documento que viene por defecto
	*/
	function BuscarIdPaciente($tipo_id,$Seleccionado='False',$TipoId='')
	{
				foreach($tipo_id as $value=>$titulo)
				{
					if($value==$TipoId){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
	}


	/**
	* Separa la fecha del formato timestamp
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
				//	return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}


	/**
	* Separa la hora del formato timestamp
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
    return  $time[1].":".$time[2].":".$x[0];
  }

	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStampSeg($hora)
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

	/**
	* Muestra el nombre del tercero con sus respectivos planes
	* @access private
	* @return string
	* @param array arreglor con los tipos de responsable
	* @param int el responsable que viene por defecto
	*/
 function MostrarResponsable($responsables,$Responsable)
 {
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
			for($i=0; $i<sizeof($responsables); $i++)
			{
					if($responsables[$i][plan_id]==$Responsable){
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
					}else{
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
					}
			}
 }

	///----------------------------

	/**
	* Forma para capturar los datos de clasificacion del triage.
	* @access private
	* @return boolean
	* @param string motivo de la consulta
	* @param string observaciones
	* @param int frecuencia cardiaca
	* @param int frecuencoa respiratoria
	* @param int temperatura corporal
	* @param int peso
	* @param int tension arterial alta
	* @param int tension arterial baja
	*/
	function FormaClasificacionTriage($MotivoConsulta,$observacion,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$diag,$causas)
	{
				IncludeLib("funciones_admision");
				$this->Seleccionar();
				$TipoId=$_SESSION['TRIAGE']['PACIENTE']['tipo_id_paciente'];
				$PacienteId=$_SESSION['TRIAGE']['PACIENTE']['paciente_id'];
				$plan=$this->Responsable($_SESSION['TRIAGE']['PACIENTE']['plan_id']);
				$Nombre=$this->NombrePaciente($TipoId,$PacienteId);
				$hora=$this->HoraTriage($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				$EdadArr=CalcularEdad($Nombre[fecha_nacimiento],$FechaFin);
				$ocupacion=OcupacionPaciente($TipoId,$PacienteId);
				$Edad=$EdadArr['edad_aprox'];
				//global $_BOOKMARK_;
				//$_BOOKMARK_='DIAGNOSTICO';
				if(empty($_SESSION['TRIAGE']['ATENCION']['Atencion']))
				{  $action=ModuloGetURL('app','Triage','user','InsertarDatosTriage',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId));  }
				else
				{  $action=ModuloGetURL('app','Triage','user','ActualizarDatosTriage',array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId));  }
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - SERVICIO DE URGENCIAS CUADRO RESUMEN DEL TRIAGE');
				/*if(!$this->BOOKMARK_DIAGNOSTICO)
				{
					$this->salida .="<A NAME=\"DIAGNOSTICO\"></A>";
				}*/
				$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\" >";
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "          <tr><td class=\"label\" width=\"25%\">Apellidos: </td><td width=\"25%\"> $Nombre[primer_apellido] $Nombre[segundo_apellido]</td>";
				$this->salida .= "              <td class=\"label\" width=\"25%\">Nombres: </td><td width=\"25%\">$Nombre[primer_nombre] $Nombre[segundo_nombre]</td></tr>";
				$this->salida .= "          <tr><td class=\"label\">Identificación: </td><td>$TipoId $PacienteId</td>";
				$this->salida .= "              <td class=\"label\">Ocupación: </td><td>".$ocupacion['ocupacion_descripcion']."</td></tr>";
				$this->salida .= "          <tr><td class=\"label\">Edad: </td><td>$Edad</td>";
				$Sexo=$this->NombreSexo($TipoId,$PacienteId);
				$this->salida .= "              <td class=\"label\">Sexo: </td><td>".$Sexo."</td></tr>";
				$this->salida .= "          <tr><td class=\"label\" width=\"25%\">Responsable: </td><td colspan=\"3\">$plan</td>";
				if(!empty($hora))
				{
						$Hora=$this->HoraStamp($hora);
						$this->salida .= "              <td class=\"label\" width=\"25%\">Hora Ingreso: </td><td>$Hora</td></tr>";
				}
				else
				{ $this->salida .= "              <td class=\"label\" width=\"25%\"></td><td></td></tr>"; }
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset><br>";
				$this->salida .= "      </td></tr>";
				$this->salida .= "       <tr><td>";
				$signos=$this->BuscarSignosVitales($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				if(empty($MotivoConsulta))
				{  $MotivoConsulta=$this->BuscarMotivoConsulta($_SESSION['TRIAGE']['PACIENTE']['triage_id']);  }
				if($signos!="")
				{
						$signosV = explode ('-', $signos);
						$fc=$signosV[0];
						$fr=$signosV[1];
						$temperatura=$signosV[2];
						if(empty($_REQUEST['peso']))
						{  $peso=$signosV[3];  }

						if(empty($tAlta))
						{  $tAlta=$signosV[4];  }
						if(empty($tBaja))
						{  $tBaja=$signosV[5];  }
						if(empty($_REQUEST['eva']))
						{  $_REQUEST['eva']=$signosV[6];  }
						$_REQUEST['motora']=$signosV[7];
						$_REQUEST['verbal']=$signosV[8];
						$_REQUEST['ocular']=$signosV[9];
						$_REQUEST['sato']=$signosV[10];
						$this->salida .= "<input type=\"hidden\" name=\"Bandera\" value=\"1\">";
				}
				else
				{ $this->salida .= "<input type=\"hidden\" name=\"Bandera\" value=\"0\">"; }
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= $this->SetStyleField("MensajeError");
				$this->salida .= "       <tr><td><fieldset><legend class=\"".$this->SetStyleField("MotivoConsulta")."\">MOTIVO CONSULTA</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr  align=\"center\"><td width=\"30%\"><textarea name=\"MotivoConsulta\" cols=\"65\" rows=\"3\" class=\"textarea\">$MotivoConsulta</textarea></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				//signos vitales
				$this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"field\">SIGNOS VITALES</legend>";
				$this->FormaSignosVitales($Nombre[fecha_nacimiento],$EdadArr,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$_REQUEST['eva'],$_REQUEST['ocular'],$_REQUEST['verbal'],$_REQUEST['motora'],$_REQUEST['sato']);
				$this->salida .= "		       </fieldset></td></tr></table><br>";
				$this->salida .= "      </td></tr>";
				$this->salida .= $this->SetStyle("MensajeError");
				$vect='';
				$this->salida .= "      <tr><td>";
				if(!empty($_SESSION['TRIAGE']['CAUSAS']))
				{
						$p=0;
						foreach($_SESSION['TRIAGE']['CAUSAS'] as $k => $v)
						{
								if($p==0)
								{
										$vect = $v;
										$p++;
								}
								else
								{		$vect .=','.$v;  }
						}
						$arr = $this->BuscarCausas($vect);
						if($arr)
						{
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"1\"  cellpadding=\"1\">";
								$this->salida .= "          <tr><td class=\"modulo_table_title\">SIGNO Y/O SINTOMA</td>";
								$this->salida .= "          <td class=\"modulo_table_title\">CAUSA PROBABLE</td></tr>";
								$this->salida.="</tr>";
								
								$colores = $this->ObtenerNivelesTriage();
								
								for($i=0;$i<sizeof($arr);)
								{
										$this->salida.="<tr class=\"label\">";
										$d = $i;
										$niv = $arr[$i][nivel_triage_id];
										//$estilo=ColorTriage($arr[$i][nivel_triage_id]);
										$this->salida.="  <td align=\"center\" bgcolor=\"".$colores[$niv]['color_oscuro']."\" width=\"17%\" >\n";
										$this->salida.="  	<b style=\"color:".$colores[$niv]['color_letra']."\">Nivel ".$arr[$i][nivel_triage_id]."<BR>".$arr[$i][desnivel]."</b></td>";
										//$estiloClaro=ColorTriageClaro($arr[$i][nivel_triage_id]);
										$this->salida.="  <td width=\"83%\">";
										$this->salida.="			<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"1\"  cellpadding=\"1\">";
										while($arr[$i][nivel_triage_id] == $arr[$d][nivel_triage_id])
										{
												$j=$d;
												$this->salida.="				<tr class=\"label\">";
												$this->salida.="					<td bgcolor=\"".$colores[$niv]['bgcolor']."\" width=\"30%\" align=\"center\">".$arr[$j][dessigno]."</td>";
												$this->salida.="					<td width=\"70%\">";
												while($arr[$d][signo_sintoma_id]==$arr[$j][signo_sintoma_id])
												{
														$this->salida.="							<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"1\" class=\"label\" cellpadding=\"1\">";
														$k=$j;
														while($arr[$k][causa_probable_id]==$arr[$j][causa_probable_id])
														{
																$this->salida.="				<tr bgcolor=\"".$colores[$niv]['bgcolor']."\">";
																$this->salida.="					<td width=\"92%\">".$arr[$k][descausa]."</td>";
																$this->salida.="					<td width=\"8%\"  align=\"center\">";
																//$this->salida .= "    <input type=\"checkbox\" name=\"cau".$arr[$k][causa_probable_id]."\" value=\"".$arr[$d][nivel_triage_id]."||".$arr[$j][signo_sintoma_id]."||".$arr[$k][causa_probable_id]."||".$arr[$d][desnivel]."||".$arr[$j][dessigno]."||".$arr[$k][descausa]."\" onClick=\"Seleccionar(this.form,".$arr[$d][nivel_triage_id].")\"></td></tr>";
																$this->salida .= "    <input type=\"checkbox\" name=\"seleccion".$arr[$k][causa_probable_id]."\" value=\"".$arr[$k][causa_probable_id]."\" onClick=\"Seleccionar(this.form,".$arr[$d][nivel_triage_id].")\" checked></td></tr>";
																$this->salida.="					</td>";
																$this->salida.="				</tr>";
																$k++;
														}
														$j=$k;
														$this->salida.="				</table>";
												}
												$this->salida.="					</td>";
												$this->salida.="				</tr>";
												$d=$j;
										}
										$this->salida.="				</table>";
										$this->salida.="  </td>";
										$i=$d;
										$this->salida.="				</tr>";
								}
								$this->salida.="				</table>";
						}
				}
				$this->salida .="</td></tr>" ;
				$this->salida .= "      <BR><BR><table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"".$this->SetStyleField("causas")."\">CAUSAS PROBABLES</legend>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"7\">CAUSAS PROBABLES</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$nivel = NivelesTriage();
				$this->salida.="<td width=\"6%\">NIVEL TRIAGE:</td>";
				$this->salida .="<td width=\"11%\" align='center'><select name=\"nivelcausa\" class=\"select\">";
				$this->salida .=" <option value=\"-1\">---TODOS---</option>";
				for($i=0; $i<sizeof($nivel); $i++)
				{
						if($nivel[$i][nivel_triage_id]==$_REQUEST['nivelcausa']){
								$this->salida .=" <option value=\"".$nivel[$i][nivel_triage_id]."\" selected>NIVEL ".$nivel[$i][nivel_triage_id]."</option>";
						}else{
								$this->salida .=" <option value=\"".$nivel[$i][nivel_triage_id]."\">NIVEL ".$nivel[$i][nivel_triage_id]."</option>";
						}
				}
				$this->salida .="</td>" ;
				$this->salida.="<td width=\"10%\">SIGNO Y/O SINTOMA:</td>";
				$this->salida .="<td width=\"20%\" align='center'><input type='text' class='input-text' 	name = 'signo' value =\"".$_REQUEST['signo']."\"></td>" ;
				$this->salida.="<td width=\"8%\">CAUSA:</td>";
				$this->salida .="<td width=\"20%\" align='center'><input type='text' class='input-text' 	name = 'causa' value =\"".$_REQUEST['causa']."\"></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"CausasPro\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				if(!empty($causas))
				{
					$this->FormaResultadosCausas($causas);
				}
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				//aqui son los datos del paciente otra vez
				$this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\">";
				/*$this->salida .= "       <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "          <tr><td class=\"label\" width=\"20%\">Apellidos: </td><td width=\"30%\"> $Nombre[primer_apellido] $Nombre[segundo_apellido]</td>";
				$this->salida .= "              <td class=\"label\" width=\"20%\">Nombres: </td><td width=\"30%\">$Nombre[primer_nombre] $Nombre[segundo_nombre]</td></tr>";
				$this->salida .= "          <tr><td class=\"label\">Tipo Identificacion: </td><td>$TipoId</td>";
				$this->salida .= "              <td class=\"label\">Numero Identificacion: </td><td>$PacienteId</td></tr>";
				$this->salida .= "          <tr><td class=\"label\">Edad: </td><td>$Edad</td>";
				$this->salida .= "              <td class=\"label\">Sexo: </td><td>".$Nombre[sexo_id]."</td></tr>";
				$this->salida .= "          <tr><td class=\"label\" width=\"20%\">Responsable: </td><td>$plan</td>";
				if(!empty($hora))
				{
						$Hora=$this->HoraStamp($hora);
						$this->salida .= "              <td class=\"label\" width=\"25%\">Hora Ingreso: </td><td>$Hora</td></tr>";
				}
				else
				{ $this->salida .= "              <td class=\"label\" width=\"25%\"></td><td></td></tr>"; }
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset>";
				$this->salida .= "      </td></tr>";*/
				$this->salida .= $this->SetStyleField2("MensajeError");
				$this->salida .= "      <tr><td>";
				$this->salida .= "      <br><table border=\"0\" width=\"95%\" align=\"center\">";				
				$niveles = $this->ObtenerNivelesTriage();
				if($niveles)
				{
					$chk= "";
					$this->salida .= "       <tr>\n";
					$this->salida .= "       	<td>\n";
					$this->salida .= "       		<fieldset><legend class=\"".$this->SetStyleField2("taAlta")."\">CLASIFICACION FINAL TRIAGE</legend>\n";
					$this->salida .= "       			<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"4\">\n";
					$this->salida .= "       				<tr class=\"normal_11N\">\n";					
					foreach($niveles as $key=> $triage)
					{
						($_REQUEST['Nivel'] == $key)? $chk = "checked": $chk= "";

						$this->salida .= "       					<td bgcolor=\"".$triage['color_oscuro']."\" title=\"".$triage['descripcion']."\">\n";
						$this->salida .= "       						<input type=\"radio\" name=\"nivel\" value=\"$key\" $chk> <b style=\"color:".$triage['color_letra']."\">NIVEL $key</b>\n";
						$this->salida .= "       					</td>\n";
					}
					$this->salida .= "       				</tr>\n";
					$this->salida .= "       			</table>\n";
					$this->salida .= "       		</fieldset>\n";
					$this->salida .= "       	</td>\n";
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "			 </table>\n";
				$this->salida .= "		  </fieldset>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>";
				
				//IMPRESION DIAGNOSTICA
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">IMPRESION DIAGNOSTICA</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr><td align=\"center\"><textarea name=\"impresionDiag\" cols=\"115\" rows=\"3\" class=\"textarea\">".$_REQUEST['impresionDiag']."</textarea></td></tr>";

				$this->salida .= "      </table>";
				//diagnostico
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"".$this->SetStyleField("diagnostico")."\">DIAGNOSTICO</legend>";
				/*if(empty($_SESSION['TRIAGE']['DIAGNOSTICO']))
				{
						$res=$this->BuscarDiagnosticoTriage($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
						for($j=0; $j<sizeof($res); $i++)
						{
								$_SESSION['DIAGNOSTICO'][$res[$j][diagnostico_id]][$res[$j][diagnostico_nombre]]=$res[$j][diagnostico_id];
						}
				}*/
				/*if($this->BOOKMARK_DIAGNOSTICO)
				{
					$this->salida .="<A NAME=\"DIAGNOSTICO\"></A>";
				}*/
				if(!empty($_SESSION['TRIAGE']['DIAGNOSTICO']))
				{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"9%\">CODIGO</td>";
						$this->salida.="  <td width=\"88%\">DESCRIPCION</td>";
						$this->salida.="  <td width=\"3%\"></td>";
						$this->salida.="</tr>";
						if(!empty($_SESSION['TRIAGE']['DIAGNOSTICO']) )
						{
								foreach($_SESSION['TRIAGE']['DIAGNOSTICO'] as $k => $v)
								{
									foreach($v as $k1 => $v1)
									{
											$this->salida.="<tr class=\"modulo_list_claro\">";
											$this->salida.="  <td align=\"center\">".$k."</td>";
											$this->salida.="  <td>".$k1."</td>";
											$this->salida.="  <input type = hidden name=codigodi".$k." value = ".$k."></td>";
											//global $_BOOKMARK_;
									 		//$_BOOKMARK_='DIAGNOSTICO';
											$accion2=ModuloGetURL('app','Triage','user','EliminarDiagnostico',array('codigoED'=>$k,'dat'=>$_REQUEST));
											$this->salida.="  <td><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
											$this->salida.="</tr>";
									}
								}
						}
						$this->salida.="</table><br>";
				}
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">DIAGNOSTICOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"6%\">CODIGO:</td>";
				$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10 	name = 'codigoDiag'    ></td>" ;
				$this->salida.="<td width=\"10%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionDiag'   value =\"".$_REQUEST['descripcionDiag']."\"></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"Diagnostico\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				if(!empty($diag))
				{
					$this->FormaResultadosDiagnosticos($diag);
				}
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				//observacion enfermera
				$enf=$this->BuscarObservacionEnfermera($_SESSION['TRIAGE']['PACIENTE']['triage_id']);
				if(!empty($enf))
				{
						$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
						$this->salida .= "       <tr><td><fieldset><legend class=\"field\">OBSERVACIONES REALIZADAS POR LA ENFERMERA</legend>";
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= "          <tr><td align=\"center\"><textarea name=\"ObservacionesMedico\" cols=\"115\" rows=\"3\" class=\"textarea\" readonly>$enf</textarea></td></tr>";
						$this->salida .= "			 </table>";
						$this->salida .= "		  </fieldset>";
						$this->salida .= "      </td></tr>";
						$this->salida .= "      </table>";
				}
				//fin observacion enfermera
				//observacion medico
				$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "       <tr><td><fieldset><legend class=\"field\">OBSERVACIONES</legend>";
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr><td align=\"center\"><textarea name=\"ObservacionesMedico\" cols=\"115\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
				$this->salida .= "			 </table>";
				$this->salida .= "		  </fieldset>";
        $this->salida .= "      </td></tr>";
				$this->salida .= "      </table>";
				//fin observacion medico
				$this->salida .= "      </td></tr>";
				$this->salida .= "      <tr><td class=\"label\" align=\"center\">";
				if(!empty($_SESSION['TRIAGE']['PUNTO']['NOATENDER']))
				{		//combo puntos admision y no atender
						$var=$this->PuntosAdmon();
						$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"40%\" align=\"center\"  >";
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO ADMISION: </td><td><select name=\"Punto\" class=\"select\">";
						$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
						for($i=0; $i<sizeof($var); $i++)
						{
								$this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\">".$var[$i][descripcion]."</option>";
						}
						$this->salida .= "                   <option value=\"N\" selected>DERIVAR A OTRO NIVEL</option>";
						$this->salida .= "              </select></td></tr>";
						$this->salida .= "			     </table>";
				}

				elseif(empty($_SESSION['TRIAGE']['PUNTO']['NOATENDER']) AND !empty($_SESSION['TRIAGE']['ATENCION'])  AND empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
				{		//combo puntos admision y no atender
						$var=$this->PuntosAdmon();
						$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"40%\" align=\"center\"  >";
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO ADMISION: </td><td><select name=\"Punto\" class=\"select\">";
						$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
						for($i=0; $i<sizeof($var); $i++)
						{
								if($_SESSION['TRIAGE']['PUNTO']['PTOADMON']==$var[$i][punto_admision_id])
								{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\" selected>".$var[$i][descripcion]."</option>";  }
								else
								{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\">".$var[$i][descripcion]."</option>";  }
						}
						$this->salida .= "                   <option value=\"N\">DERIVAR A OTRO NIVEL</option>";
						$this->salida .= "              </select></td></tr>";
						$this->salida .= "			     </table>";
				}
				elseif(!empty($_SESSION['TRIAGE']['ATENCION']) AND !empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
				{
						$this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
						$this->salida .= "				       <tr>";
						$this->salida .= "				       <td class=\"".$this->SetStyle("admitir")."\">ADMITIR EN LA ESTACION <input type=\"radio\" name=\"admitir\" value=\"1\"></td>";
						$this->salida .= "				       <td class=\"".$this->SetStyle("admitir")."\">REMISION DE PACIENTE <input type=\"radio\" name=\"admitir\" value=\"2\"></td>";
						$this->salida .= "				       <td class=\"".$this->SetStyle("admitir")."\">CAMBIAR PTO ADMISION <input type=\"radio\" name=\"admitir\" value=\"3\"></td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "  </table><br>";
				}
				elseif(empty($_SESSION['TRIAGE']['PUNTO']['NOATENDER']) AND empty($_SESSION['TRIAGE']['ATENCION'])  AND empty($_SESSION['TRIAGE']['ATENCION']['PENDIENTE']))
				{		//combo puntos admision y no atender
						$var=$this->PuntosAdmon();
						$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"40%\" align=\"center\"  >";
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Punto")."\">PUNTO ADMISION: </td><td><select name=\"Punto\" class=\"select\">";
						$this->salida .= "                   <option value=\"-1\">------SELECCIONE------</option>";
						for($i=0; $i<sizeof($var); $i++)
						{
								if($_SESSION['TRIAGE']['PUNTO']['PTOADMON']==$var[$i][punto_admision_id])
								{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\" selected>".$var[$i][descripcion]."</option>";  }
								else
								{  $this->salida .= "                   <option value=\"".$var[$i][punto_admision_id]."\">".$var[$i][descripcion]."</option>";  }
						}
						$this->salida .= "                   <option value=\"N\">DERIVAR A OTRO NIVEL</option>";
						$this->salida .= "              </select></td></tr>";
						$this->salida .= "			     </table>";
				}
				$this->salida .= "      </td></tr>";
				$this->salida .= "  </table>";
				$this->salida .= "		<table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td></form>";
				if(!empty($_SESSION['TRIAGE']['ATENCION']))
				{			//CUENDO LO LLAMA JAIME
							$contenedor=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor'];
							$modulo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo'];
							$tipo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo'];
							$metodo=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo'];
							$argumentos=$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos'];
							$actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
				}
				else
				{  $actionM=ModuloGetURL('app','Triage','user','FormaMenuTriage');  }
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td></form>";
				$this->salida .= "				       </tr>";
				$this->salida .= "  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaResultadosCausas($arr)
	{
			IncludeLib('funciones_admision');
			if($arr)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"0\"  cellpadding=\"0\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"2\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida .= "          <tr><td class=\"modulo_table_title\">SIGNO Y/O SINTOMA</td>";
					$this->salida .= "          <td class=\"modulo_table_title\">CAUSA PROBABLE</td></tr>";
					$this->salida.="</tr>";
					
					$colores = $this->ObtenerNivelesTriage();
					for($i=0;$i<sizeof($arr);)
					{
							$ni = $arr[$i][nivel_triage_id];
							$this->salida.="<tr class=\"label\">";
							$d=$i;
							$this->salida.="  <td align=\"center\" bgcolor=\"".$colores[$ni]['color_oscuro']."\" width=\"17%\" >\n";
							$this->salida.="  	<b style=\"color:".$colores[$ni]['color_letra']."\">Nivel ".$arr[$i][nivel_triage_id]."<BR>".$arr[$i][desnivel]."</b></td>\n";
							$this->salida.="  <td width=\"83%\">";
							$this->salida.="			<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"1\"  cellpadding=\"1\">";
							while($arr[$i][nivel_triage_id]==$arr[$d][nivel_triage_id])
							{
   								$j=$d;
									$this->salida.="				<tr class=\"label\">";
									$this->salida.="					<td bgcolor=\"".$colores[$ni]['bgcolor']."\"  width=\"30%\" align=\"center\">".$arr[$j][dessigno]."</td>";
									$this->salida.="					<td  width=\"70%\">";
									while($arr[$d][signo_sintoma_id]==$arr[$j][signo_sintoma_id])
									{
											$this->salida.="							<table  class=\"label\" align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"1\"  cellpadding=\"1\">";
											$k=$j;
											while($arr[$k][causa_probable_id]==$arr[$j][causa_probable_id])
											{
													$this->salida.="				<tr bgcolor=\"".$colores[$ni]['bgcolor']."\" >";
													$this->salida.="					<td width=\"92%\">".$arr[$k][descausa]."</td>";
													$this->salida.="					<td width=\"8%\"  align=\"center\">";
													//$this->salida .= "    <input type=\"checkbox\" name=\"cau".$arr[$k][causa_probable_id]."\" value=\"".$arr[$d][nivel_triage_id]."||".$arr[$j][signo_sintoma_id]."||".$arr[$k][causa_probable_id]."||".$arr[$d][desnivel]."||".$arr[$j][dessigno]."||".$arr[$k][descausa]."\" onClick=\"Seleccionar(this.form,".$arr[$d][nivel_triage_id].")\"></td></tr>";
													$this->salida .= "    <input type=\"checkbox\" name=\"caupro".$arr[$k][causa_probable_id]."\" value=\"".$arr[$k][causa_probable_id]."\" onClick=\"Seleccionar(this.form,".$arr[$d][nivel_triage_id].")\"></td></tr>";
													$this->salida.="					</td>";
													$this->salida.="				</tr>";
													$k++;
											}
											$j=$k;
											$this->salida.="				</table>";
									}
									$this->salida.="					</td>";
									$this->salida.="				</tr>";
									$d=$j;
							}
							$this->salida.="				</table>";
							$this->salida.="  </td>";
							$i=$d;
							$this->salida.="				</tr>";
					}
					$this->salida.="				</table>";
					$this->salida.="			<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"0\"  cellpadding=\"0\">";
					$this->salida.="<tr>";
					$this->salida .= "<td align=\"right\"><input class=\"input-submit\" name=\"GuardarCausas\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida .=$this->RetornarBarraD();
			}
	}


	/**
	*
	*/
	function FormaResultadosDiagnosticos($arr)
	{
			if ($arr)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">CODIGO</td>";
					$this->salida.="  <td width=\"80%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"5%\"></td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($arr);$i++)
					{
							$this->salida.="<tr class=\"modulo_list_claro\">";
							$this->salida.="  <td align=\"center\">".$arr[$i][diagnostico_id]."</td>";
							$this->salida.="  <td>".$arr[$i][diagnostico_nombre]."</td>";
							$this->salida.="  <td align=\"center\"><input type = checkbox name=diag".$arr[$i][diagnostico_id]." value =\"".$arr[$i][diagnostico_id]."||".$arr[$i][diagnostico_nombre]."\"></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"modulo_list_claro\">";
					$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"GuardarDiag\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida .=$this->RetornarBarraD();
			}
	}


	/**
	*
	*/
 	function RetornarBarraD()
	{
    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(empty($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }

		$accion=ModuloGetURL('app','Triage','user','LlamarBuscarDiagnostico',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
    }
    if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
    {
      if($numpasos>10)
      {
        $valor=10+3;
      }
      else
      {
        $valor=$numpasos+3;
      }
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
    }
    else
    {
      if($numpasos>10)
      {
        $valor=10+5;
      }
      else
      {
        $valor=$numpasos+5;
      }
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
    }
	}


		/**
		* Funcion de java para elegir el nivel del triage
		* @access private
		* @return void
		*/
		function Seleccionar()
		{
			$this->salida .= "<script>\n";
			$this->salida .= "  function Seleccionar(forma,nivelT){\n";
			$this->salida .= "   for (i=0; i<forma.elements.length; i++){\n";
			$this->salida .= "      if((forma.elements[i].type==\"radio\") && (forma.elements[i].name=='nivel')){\n";
			$this->salida .= "         if((forma.elements[i].checked==true) && (forma.elements[i].value < nivelT)){\n";
			$this->salida .= "            i=forma.elements.length;\n";
			$this->salida .= "         }\n";
			$this->salida .= "         if((forma.elements[i].value==nivelT) && (i < forma.elements.length)){\n";
			$this->salida .= "           forma.elements[i].checked=true;\n";
			$this->salida .= "         }\n";
			$this->salida .= "      }\n";
			$this->salida .= "   }\n";
			$this->salida .= "  }\n";
			$this->salida .=  "</script>\n";
		}

//----------------------------------------------------------------------
	/**
	* Muestra en el combo los diferentes tipos de causas externas
	* @access private
	* @return boolean
	* @param array arreglo con las causas externas
	* @param boolean indica si el combo ya esta seleccionado
	* @param int la causa externa que viene por defecto
	* @param string tipo de forma
	*/
	function BuscarIdCausaExterna($causa_externa,$Seleccionado='False',$CausaExterna='',$TipoForma)
	{
			$this->salida .=" <option value=\"-1\">------Seleccione------</option>";
			foreach($causa_externa as $value=>$titulo)
			{
				if($TipoForma=='Soat' && $value=='02'){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}
				if($value==$CausaExterna){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}
				else{
					$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
	}


	/**
	* Muestra en el combo los diferentes tipos de vias de ingreso
	* @access private
	* @return boolean
	* @param array arreglo con las vias de ingreso
	* @param boolean indica si el combo ya esta seleccionado
	* @param int la via de ingreso que viene por defecto
	* @param string tipo de forma
	*/
  function BuscarIdViaIngreso($via_ingreso,$ViaIngreso)
	{
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";

				if(empty($ViaIngreso))
				{
						if(empty($_SESSION['TRIAGE']['JAIME']))
						{  $ViaIngreso=1;  }
				}

				foreach($via_ingreso as $value=>$titulo)
				{
						if($value==$ViaIngreso){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
				}
	}

	/**
	* Crear el combo de tipos de afiliados
	* @access private
	* @return string
	* @param array arreglo con los tipos de afiliados
	* @param int tipo de afiliado
	*/
	function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
	{
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0; $i<sizeof($tipo_afiliado); $i++)
				{
					if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
					else{
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
				}
	}

	/**
	* Muestra el listado de los pacientes activos
	* @access private
	* @return boolean
	* @param array arreglo con los pacientes con ingreso activo
	*/
  function FormaListadoIngresos($res)
	{
		$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - LISTADO DE PACIENTES INGRESADOS');
		//$res=$this->ListadoImpresion();
		$this->salida .= "		   <br>";
		$this->salida .= "		<table width=\"99%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "				<td>PACIENTE</td>";
		$this->salida .= "				<td>DOCUMENTO</td>";
		$this->salida .= "				<td>FECHA INGRESO</td>";
		$this->salida .= "				<td>HORA INGRESO</td>";
		$this->salida .= "				<td>ESTADO</td>";
		$this->salida .= "				<td>TIPO AFILIADO</td>";
		$this->salida .= "				<td>OBSERVACION</td>";
		$this->salida .= "				<td></td>";
		$this->salida .= "				<td></td>";
		$this->salida .= "			</tr>";
		$i=0;
		for($i=0; $i<sizeof($res); $i++)
		{
				if( $i % 2) $estilo='modulo_list_claro';
				else $estilo='modulo_list_oscuro';
				$accionI=ModuloGetURL('app','Triage','user','Imprimir',array('TipoId'=>$res[$i][tipo_id_paciente],'PacienteId'=>$res[$i][paciente_id],'Ingreso'=>$res[$i][ingreso],'FechaIngreso'=>$res[$i][fecha_registro],'Estado'=>$res[$i][estado]));
				$Fechas=$this->FechaStamp($res[$i][fecha_registro]);
				$Horas=$this->HoraStamp($res[$i][fecha_registro]);
				if($res[$i][estado]==1){ $Estado='Activo'; }
				else{ $Estado='Innactivo';}
				$TipoAfialido=$this->NombreTipoAfiliado($res[$i][tipo_afiliado_id]);
				$this->salida .= "			<tr class=\"$estilo\">";
				$this->salida .= "				<td>".$res[$i][nombre]."</td>";
				$this->salida .= "				<td><input type=\"hidden\" name=\"TipoId\" value=\"".$res[$i][tipo_id_paciente]."\"><input type=\"hidden\" name=\"PacienteId\" value=\"".$res[$i][paciente_id]."\">".$res[$i][tipo_id_paciente]." ".$res[$i][paciente_id]."</td>";
				$this->salida .= "				<td align=\"center\"><input type=\"hidden\" name=\"Fecha\" value=\"$Fechas\">$Fechas</td>";
				$this->salida .= "				<td align=\"center\">$Horas</td>";
				$this->salida .= "				<td align=\"center\">$Estado</td>";
				$this->salida .= "				<td align=\"center\">$TipoAfialido</td>";
				$this->salida .= "				<td align=\"center\">".$res[$i][comentario]."</td>";
				$this->salida .= "				<td align=\"center\"><a href=\"$accionI\">IMPRIMIR</a></td>";
				$accionG=ModuloGetURL('app','Triage','user','LlamarGarantes',array('Ingreso'=>$res[$i][ingreso]));
				$this->salida .= "				<td align=\"center\"><a href=\"$accionG\">GARANTE</a></td>";
				$this->salida .= "			</tr>";
		}
		$this->salida .= "  </table>";
		$this->conteo=$_SESSION['SPY'];
		$this->salida .=$this->RetornarBarra2();
		$this->salida .= "		<table width=\"20%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
		$actionM=ModuloGetURL('app','Triage','user','Menus');
		$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "				       <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




	function RetornarBarra2(){
		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(is_null($paso)){
    	$paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		$accion=ModuloGetURL('app','Triage','user','ListadoImpresion');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
    }
	}

		/**
		* Forma de impresion con los datos del paiciente ingresado
		* @access private
		* @return boolean
		* @param string tipo de documento
		* @param int numero de documento
		* @param int ingreso
		* @param date fecha del ingreso
		* @param int estado  civil
		*/
		function FormaImpresion($TipoId,$PacienteId,$Ingreso,$FechaIngreso,$Estado)
		{
					$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - IMPRESION');
					$dat=$this->BuscarDatosIngresoPaciente($Ingreso); 
					$datos=$this->DatosPaciente($TipoId,$PacienteId);
				  $NomCiudad=$this->nombre_ciudad($datos[7],$datos[8],$datos[9]);
					$Sexo=$this->NombreSexoPac($datos[4]);
					$Estado=$this->NombreEstadoCivil($datos[3]);
					$Fecha=$this->FechaStamp($FechaIngreso);
					$Hora=$this->HoraStamp($FechaIngreso);
					$this->salida .= "		<table width=\"46%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td  width=\"17%\">Fecha </td>";
					$this->salida .= "			   <td>:</td>";
					$this->salida .= "			   <td>$Fecha</td>";
					$this->salida .= "			   <td  width=\"20%\" nowrap>Hora :</td>";
					$this->salida .= "			   <td>$Hora</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td>Paciente</td>";
					$this->salida .= "			   <td>:</td>";
					$this->salida .= "			   <td>$datos[5] $datos[6]</td>";
					$this->salida .= "			   <td>Doc. Paciente : </td>";
					$this->salida .= "			   <td>$TipoId $PacienteId</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td>F/Nacto</td>";
					$this->salida .= "			   <td>:</td>";
					$this->salida .= "			   <td>$datos[0]</td>";
					$this->salida .= "			   <td>Dirección :</td>";
					$this->salida .= "			   <td>$datos[1]</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td>Est/Civil</td>";
					$this->salida .= "			   <td>:</td>";
					$EstadoC=$this->NombreEstadoCivil($datos[3]);
					$this->salida .= "			   <td>$EstadoC</td>";
					$this->salida .= "			   <td>Sexo          : </td>";
					$this->salida .= "			   <td>$Sexo</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$TipoAfialido=$this->NombreTipoAfiliado($dat[tipo_afiliado_id]);
					$this->salida .= "			   <td>Tipo Afiliado</td>";
					$this->salida .= "			   <td>:</td>";
					$this->salida .= "			   <td>$TipoAfialido</td>";
					$this->salida .= "			   <td>Ciudad:</td>";
					$this->salida .= "			   <td>$NomCiudad</td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td>Estado/Afil:</td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			   <td>Poliza:</td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			   <td>Observación:</td>";
					$this->salida .= "			   <td>".$dat[comentario]."</td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			   <td></td>";
					$this->salida .= "			</tr>";
					$this->salida .= "  </table>";
					$this->salida .= "		<table width=\"30%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
					$this->salida .= "			   <tr><td  align=\"center\" colspan=\"5\"><input class=\"input-submit\" type=\"submit\" name=\"Imprimir\" value=\"IMPRIMIR\"></td>";
					$actionM=ModuloGetURL('app','Triage','user','Menus');
					$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
					$this->salida .= "				       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form></tr>";
					$this->salida .= "  </table>";

					$this->salida .= ThemeCerrarTabla();
					return true;
		}


		/**
		* Forma para capturar los datos de los garantes de un paciente
		* @access private
		* @return boolean
		* @param string tipo de documento
		* @param int numero de documento
		* @param int ingreso
		* @param boolean para saber si va ha crear o actualizar un garante
		*/
		function FormaGarantes($TipoId,$PacienteId,$Ingreso,$Update)
		{
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS GARANTES');

				if(!empty($_SESSION['GARANTE']['RETORNO']))
				{
							$contenedor=$_SESSION['GARANTE']['RETORNO']['contenedor'];
							$modulo=$_SESSION['GARANTE']['RETORNO']['modulo'];
							$tipo=$_SESSION['GARANTE']['RETORNO']['tipo'];
							$metodo=$_SESSION['GARANTE']['RETORNO']['metodo'];
							$argumentos=$_SESSION['GARANTE']['RETORNO']['argumentos'];
							$accionCancelar=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
				}
				else
				{  $accionCancelar=ModuloGetURL('app','Triage','user','BuscarListadoIngresos');  }

				$Garantes=$this->BuscarGarantes($Ingreso);
				if(!$Update)
				{
						$boton='INGRESAR';
						$accion=ModuloGetURL('app','Triage','user','InsertarDatosGarantes');
						if($Garantes)
						//if($Garantes)
						{
								$this->salida .= "		   <br>";
								$this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
								$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
								$this->salida .= "				<td>GARANTE</td>";
								$this->salida .= "				<td>IDENTIFICACION</td>";
								$this->salida .= "				<td>DIRECCION</td>";
								$this->salida .= "				<td>TELEFONO</td>";
								$this->salida .= "				<td></td>";
								$this->salida .= "			</tr>";
								for($i=0; $i<sizeof($Garantes); $i++)
								{
												if( $i % 2) $estilo='modulo_list_claro';
												else $estilo='modulo_list_oscuro';
												$accionHref=ModuloGetURL('app','Triage','user','Garantes',array('tipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Update'=>$Garantes[$i]));
												$this->salida .= "			<tr class=\"$estilo\">";
												$this->salida .= "				<td>".$Garantes[$i][primer_nombre_garante]." ". $Garantes[$i][primer_apellido_garante]."</td>";
												$this->salida .= "				<td>".$Garantes[$i][tipo_id_tercero]." ".$Garantes[$i][garante_id]."</td>";
												$this->salida .= "				<td align=\"center\">".$Garantes[$i][direccion_garante]."</td>";
												$this->salida .= "				<td align=\"center\">".$Garantes[$i][telefono_garante]."</td>";
												$this->salida .= "				<td align=\"center\"><a href=\"$accionHref\">Actualizar</a></td>";
												$this->salida .= "			</tr>";
								}
							$this->salida .= "  </table>";
						}
				}
				else
				{
						$boton='ACTUALIZAR';
						$accion=ModuloGetURL('app','Triage','user','ActualizarDatosGarantes',array('Ingreso'=>$Ingreso,'tipoId1'=>$TipoId,'PacienteId1'=>$PacienteId,'Update'=>$Update));
				}

				$this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" >";
				$this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS NUEVO GARANTE</legend>";
				$this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
				if(!$Garantes){
        	$this->salida .= "	<tr><td colspan=\"3\" align=\"center\" class=\"label_err\">El paciente no tiene garantes.<br><br></td></tr>";
				}
				$this->salida .= "<input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
				$this->salida .= "<input type=\"hidden\" name=\"accion\" value=\"$accionAcep\">";
				$this->salida .= "		<tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
				$tipo_id_tercero=$this->tipo_id_terceros();
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($tipo_id_tercero as $value=>$titulo)
				{
						if($value==$Update[tipo_id_tercero])
						{   $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{   $this->salida .=" <option value=\"$value\">$titulo</option>";   }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\" value=\"$Update[garante_id]\"></td>";
				$this->salida .= "	 		<td>  </td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\"  value=\"$Update[primer_nombre_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$Update[segundo_nombre_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	 		<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$Update[primer_apellido_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$Update[segundo_apellido_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$Update[direccion_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
				$this->salida .= "	  	<td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$Update[telefono_garante]\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "			 <td  align=\"center\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Ingresar\" value=\"$boton\"><br></form></td>";
				//$accionG=ModuloGetURL('app','Triage','user','BuscarListadoIngresos');
				$this->salida .= "  <form name=\"formagarantes\" action=\"$accionCancelar\" method=\"post\">";
				$this->salida .= "      <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"><br></form></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "  </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}

		/**
		*
		*/
		function FormaGarantesAdmon()
		{
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS GARANTES');
				$accion=ModuloGetURL('app','Triage','user','InsertarGarantesAdmon');
				$this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
				$this->salida .= "		<tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
				$tipo_id_tercero=$this->tipo_id_terceros();
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($tipo_id_tercero as $value=>$titulo)
				{
						if($value==$_REQUEST['TipoId'])
						{   $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{   $this->salida .=" <option value=\"$value\">$titulo</option>";   }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\" value=\"".$_REQUEST['GaranteId']."\"></td>";
				$this->salida .= "	 		<td>  </td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\"  value=\"".$_REQUEST['PrimerNombre']."\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"".$_REQUEST['SegundoNombre']."\" class=\"input-text\"></td>";
				$this->salida .= "	 		<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"".$_REQUEST['PrimerApellido']."\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"".$_REQUEST['SegundoApellido']."\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"".$_REQUEST['Direccion']."\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
				$this->salida .= "	  	<td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"".$_REQUEST['Telefono']."\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "      <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"ACEPTAR\"><br></form></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "  </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}

	/**
	* Construye el combo de estado de afiliados
	* @access private
	* @return string
	* @param array con los estado de afiliados
	* @param int estado del afiliado
	*/
	function BuscarIdEstadoAfiliado($estado_afiliado,$Estado)
	{
			$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			foreach($estado_afiliado as $value=>$titulo){
					if($value==$Estado){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
		}
	}


//---------------------MODIFICACION------------------------------//

	/**
	* Metodos para realizar la busqueda de un paciente para modificar sus datos
	* @access private
	* @return boolean
	* @param array arreglo con el resultado de la busqueda
	*/
	function FormaMetodoBuscar($arr)
	//function FormaMetodoBuscar($Busqueda,$mensaje,$D,$arr,$f)
	{
				//if(!$Busqueda){ $Busqueda=1; }
				$accion=ModuloGetURL('app','Triage','user','BuscarAdmision');
				$accionA=ModuloGetURL('app','Triage','user','MetodoBuscar');
				$this->salida .= ThemeAbrirTabla('ADMISIONES - BUSCAR DATOS ADMISION');
//--------------------------
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
				$this->salida .= "<tr class=\"modulo_table_list_title\">";
				$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\" >";
				$this->salida .= "<td width=\"40%\" >";
				$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
				$this->salida .= "<tr><td>";
				$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
				$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
				$tipo_id=$this->tipo_id_paciente();
				$this->BuscarIdPaciente($tipo_id,'False','');
				$this->salida .= "                  </select></td></tr>";
				$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
				$this->salida .= "<tr><td class=\"label\">No. CUENTA: </td><td><input type=\"text\" class=\"input-text\" name=\"Cuenta\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"label\">No. INGRESO: </td><td><input type=\"text\" class=\"input-text\" name=\"Ingreso\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"".$this->SetStyle("Pieza")."\">No. PIEZA</td><td><input type=\"text\" class=\"input-text\" name=\"Pieza\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"".$this->SetStyle("Cama")."\">No. CAMA</td><td><input type=\"text\" class=\"input-text\" name=\"Cama\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"label\">PREFIJO</td><td><input type=\"text\" class=\"input-text\" name=\"prefijo\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td class=\"".$this->SetStyle("Historia")."\">NUMERO HISTORIA</td><td><input type=\"text\" class=\"input-text\" name=\"historia\" maxlength=\"32\"></td></tr>";
				$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
				$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
				$this->salida .= "</form>";
				$actionM=ModuloGetURL('app','Triage','user','Menus');
				$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
				$this->salida .= "</tr>";
				$this->salida .= "</table></td></tr>";
				$this->salida .= "</td></tr></table>";
				$this->salida .= "</td>";
				$this->salida .= "</table>";
				$this->salida .= "       </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "  </table>";
				$this->salida .= "            </form>";
				//mensaje
				$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  </table>";

				if($arr)
				{
							$this->salida .= "		   <br>";
							$this->salida .= "		<table width=\"85%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
							$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
							$this->salida .= "				<td>IDENTIFICACION</td>";
							$this->salida .= "				<td>NOMBRES</td>";
							$this->salida .= "				<td>APELLIDOS</td>";
							$this->salida .= "				<td>No. INGRESO</td>";
							$this->salida .= "				<td>No. CUENTA</td>";
							$this->salida .= "				<td>PREFIJO</td>";
							$this->salida .= "				<td>No. HISTORIA</td>";
							$this->salida .= "				<td>E</td>";
							$this->salida .= "				<td></td>";
							$this->salida .= "			</tr>";
							for($i=0;$i<sizeof($arr);$i++)
							{
									$TipoId=$arr[$i][tipo_id_paciente];
									$PacienteId=$arr[$i][paciente_id];
									$Cuenta=$arr[$i][numerodecuenta];
									$Ingreso=$arr[$i][ingreso];
									$PlanId=$arr[$i][plan_id];
									$Nivel=$arr[$i][rango];
									if( $i % 2) $estilo='modulo_list_claro';
									else $estilo='modulo_list_oscuro';
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "				<td>$TipoId $PacienteId</td>";
									$this->salida .= "				<td>".$arr[$i][primer_nombre]." ".$arr[$i][segundo_nombre]."</td>";
									$this->salida .= "				<td> ".$arr[$i][primer_apellido]." ".$arr[$i][segundo_apellido]."</td>";
									$this->salida .= "				<td align=\"center\">$Ingreso</td>";
									$this->salida .= "				<td align=\"center\">$Cuenta</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$i][historia_prefijo]."</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$i][historia_numero]."</td>";
									$this->salida .= "				<td align=\"center\">".$arr[$i][estado]."</td>";
									$accionHRef=ModuloGetURL('app','Triage','user','MetodoModificarAdmision',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso));
									$this->salida .= "				<td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
									$this->salida .= "			</tr>";
							}//fin for
							$this->salida .= " </table>";
							$this->conteo=$_SESSION['SPYB'];
							$this->salida .=$this->RetornarBarra('BuscarAdmision');
				}
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Metodos para realizar la busqueda de un paciente para modificar sus datos
	* @access private
	* @return boolean
	* @param string tipo de documento
	* @param int numero de documento
	* @param string rango
	* @param int plan_id
	* @param int ingreso
	*/
	function FormaModificarAdmision($TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso)
	{
				$_SESSION['TRIAGE']['PACIENTE']['plan_id']=$PlanId;
   			$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS- DATOS DE PACIENTE');
				$Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
				if(!is_object($Paciente))
				{
						$this->error = "La clase Pacientes no se pudo instanciar";
						$this->mensajeDeError = "";
						return false;
				}
				if(!$Paciente->LlamarFormaDatosPacienteCreado($TipoId,$PacienteId,$PlanId,$Nivel))
				{
						$this->error = $Paciente->error ;
						$this->mensajeDeError = $Paciente->mensajeDeError;
						unset($Paciente);
						return false;
				}
				else
				{
						if(!$Paciente->TipoRetorno)
						{
									$this->salida .= $Paciente->GetSalida();
									unset($Paciente);
						}
				}
    		$this->salida .= "  <table width=\"".$ancho."%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  <table>";
				$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
				$accion=ModuloGetURL('app','Triage','user','ModificarDatosAdmision',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Responsable'=>$PlanId,'Ingreso'=>$Ingreso));
				$this->salida .= "     <form name=\"formai\" action=\"$accion\" method=\"post\">";
				$sw=$this->BuscarSW($PlanId);
				$datos=$this->BuscarPlanes($PlanId,$Ingreso);
				$dat=$this->BuscarDatosIngresoPaciente($Ingreso);
				$this->salida .= "      <input type=\"hidden\" name=\"PolizaAnt\" value=\"$dat[poliza]\">";
			  $this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td>";
				$this->salida .= "    ".$datos[nombre_tercero]." ".$datos[plan_descripcion]."</td></tr>";
				$this->salida .= "		      <tr>";
				$this->salida .= "	 		      <td class=\"".$this->SetStyle("FechaIngreso")."\">FECHA INGRESO: </td>";
				$fechaingreso=$this->FechaStamp($dat[fecha_ingreso]);
				$this->salida .= "	  	      <td><input type=\"text\"  class=\"input-text\" name=\"FechaIngreso\" value=\"$fechaingreso\"></td>";
				$this->salida .= "	  	      <td></td>";
				$this->salida .= "		      </tr>";
				if($sw=='1'){
						$this->salida .= "      <input type=\"hidden\" name=\"TipoAfiliado\" value=\"$dat[tipo_afiliado_id]\">";
						$this->salida .= "		      <tr>";
						$this->salida .= "	 		      <td class=\"".$this->SetStyle("poliza")."\">POLIZA: </td>";
						$this->salida .= "	  	      <td><input type=\"text\" class=\"input-text\" name=\"Poliza\" value=\"$dat[poliza]\"></td>";
						$this->salida .= "	  	      <td></td>";
						$this->salida .= "		      </tr>";
				}
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
				$via_ingreso=$this->Via_Ingreso();
				$this->BuscarIdViaIngreso($via_ingreso,$dat[via_ingreso_id]);
				$this->salida .= "              </select></td></tr>";
				if($sw!='1' && $sw!='2')
				{
						$this->salida .= "      <input type=\"hidden\" name=\"Poliza\" value=\"$poliza\">";
						$tipo_afiliado=$this->Tipo_Afiliado();
						$this->salida .= "		      <tr>";
						if(sizeof($tipo_afiliado)>1)
						{
								$this->salida .= "				       <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
								$this->BuscarIdTipoAfiliado($tipo_afiliado,$dat[tipo_afiliado_id]);
								$this->salida .= "              </select></td>";
						}
						else
						{
								$this->salida .= "				    <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
								$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
								$this->salida .= "	  	      <td></td>";
						}
						$niveles=$this->Niveles();
						if(sizeof($niveles)>1)
						{
							$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
							$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
							for($i=0; $i<sizeof($niveles); $i++)
							{
									if($niveles[$i][rango]==$Nivel){
										$this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
									}
									else{
											$this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
									}
							}
						}
						else
						{
								$this->salida .= "				     <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
								$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
								$this->salida .= "	  	      <td></td>";
						}
						$this->salida .= "		      </tr>";
				}
				else
				{
					$tipo_afiliado=$this->Tipo_Afiliado();
					$niveles=$this->Niveles();
					$this->salida .= "<input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">";
					$this->salida .= "<input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">";
				}
				$this->salida .= "    </table>";
				$this->salida .= "    <br><table border=\"0\" width=\"60%\" align=\"center\">";
				$this->salida .= "      <tr><td><fieldset><legend class=\"field\">COMENTARIOS</legend>";
				$this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "          <tr  align=\"center\"><td width=\"30%\"><textarea name=\"Comentario\" cols=\"65\" rows=\"3\" class=\"textarea\">$dat[comentarios]</textarea></td></tr>";
				$this->salida .= "		   	 </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				$this->salida .= "        <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr align=\"center\">";
				$this->salida .= "            <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"MODIFICAR ADMISION\"></form></td>";
				$accionCambio=ModuloGetURL('app','Triage','user','CambioIdentificacion',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId));
				$this->salida .= "            <td><form name=\"formai\" action=\"$accionCambio\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR IDENTIFICACION\"></form></td>";
				$accionUni=ModuloGetURL('app','Triage','user','UnificarHistorias',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId));
				$this->salida .= "            <form name=\"formai\" action=\"$accionUni\" method=\"post\">";
				$this->salida .= "            <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"UNIFICACION HISTORIAS\"></form></td>";
				$accionCancelar=ModuloGetURL('app','Triage','user','MetodoBuscar');
				$this->salida .= "            <form name=\"formac\" action=\"$accionCancelar\" method=\"post\">";
				$this->salida .= "            <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></form></td>";
				$this->salida .= "           </tr>";
				$this->salida .= "		   	 </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Forma que muestra las estaciones de enfermeria que tiene asociadas un punto
	* @access private
	* @return boolean
	*/
	function FormaElegirEstacion()
	{			//viene de no atender
				if(!empty($_SESSION['TRIAGE']['ESTACION']))
				{
						$this->salida .= ThemeAbrirTabla('TRIAGE - ELEGIR ESTACION DE ENFERMERIA');
						$accion=ModuloGetURL('app','Triage','user','EstacionTriage');
				}
				else
				{
						$this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR ESTACION DE ENFERMERIA');
						$accion=ModuloGetURL('app','Triage','user','LlamarIngreso');
				}
				$this->salida .= "			      <br><table width=\"60%\" align=\"center\" >";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Estacion")."\">Elija la Estación: </td>";
				$this->salida .= "				       <td colspan=\"2\"><select name=\"Estacion\" class=\"select\">";
				$Estaciones=$this->BuscarEstaciones();
				$this->BuscaEstaciones($Estaciones);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr>";
				if($_SESSION['TRIAGE']['TIPO']!='HOSPITALIZACION')
				{
						$this->salida .= "				       <tr>";
						$this->salida .= "				       <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				}
				elseif($_SESSION['TRIAGE']['TIPO']=='HOSPITALIZACION')
				{
						$this->salida .= "				       <tr>";
						$this->salida .= "				       <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
						$this->salida .= "			     </form>";
				if($_SESSION['TRIAGE']['TIPO']!='HOSPITALIZACION')
				{  $accion=MoDuloGetURL('app','Triage','user','ListarPacientesAdmisiones');  }
				else
				{  $accion=MoDuloGetURL('app','Triage','user','MenusHospitalizacion');  }
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
				}
				$this->salida .= "              </tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**Dibuja el combo con las estaciones de enferneria que tiene asociado un punto
	* @access private
	* @return boolea estaciones de enfermeria
	*/
	function BuscaEstaciones($Est)
	{
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0; $i<sizeof($Est); $i++){
						$this->salida .=" <option value=\"".$Est[$i][estacion_id].",".$Est[$i][departamento].",".$Est[$i][descripcion]."\">".$Est[$i][descripcion]."</option>";
				}
	}


//-------------------------------------------------------------------

	/*Metodo javascript que abre la ventana emergente con los datos de un paciente
	* @access private
	*/
	function ConsultaHomo()
	{
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function ConsultaHomo(nombre, url, ancho, altura,Tipo,Paciente,Tabla){";
		$this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
		$this->salida .= " var url2 = url+'?TipoId='+Tipo+'&PacienteId='+Paciente+'&Tabla='+Tabla;";
		$this->salida .= " rem = window.open(url2, nombre, str);";
		$this->salida .= "  if (rem != null) {";
		$this->salida .= "     if (rem.opener == null) {";
		$this->salida .= "       rem.opener = self;";
		$this->salida .= "     }";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .=  "</SCRIPT>";
	}

	/**
	* Metodos para realizar la busqueda de un paciente para modificar sus datos
	* @access private
	* @return boolean
	* @param string tipo de documento
	* @param int numero de documento
	* @param string primer nombre
	* @param string segundo nombre
	* @param string primer apellido
	* @param string segundo apellido
	* @param array arreglo con el resultado de la busqueda de homonimos
	* @param array con la accion  de la forma
	*/
	function Homonimos($TipoId,$PacienteId,$PrimerNombre,$SegundoNombre,$PrimerApellido,$SegundoApellido,$var,$accion)
	{
					global $VISTA;
					$this->ConsultaHomo();
					$homonimos1=$this->verificarDocumentosHomonimos($TipoId,$PacienteId);
					$homonimos=$this->verificarNombresHomonimos($TipoId,$PacienteId,$PrimerNombre,$SegundoNombre,$PrimerApellido,$SegundoApellido);
					if($homonimos1!="" ||$homonimos!=""){
						if($var){ 		$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - INSERTAR DATOS PACIENTE'); }
						$this->salida .= "      <br><br><table border=\"0\" width=\"55%\" align=\"center\">";
						$this->salida .= "          <tr><td><fieldset><legend class=\"field\">HOMONIMOS ENCONTRADOS</legend>";
						$this->salida .= "            <table cellspacing=\"2\"  cellpadding=\"2\"border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "				        <tr class=\"modulo_table_list_title\"  align=\"center\">";
						$this->salida .= "				          <td width=\"15%\">IDENTIFICACION</td>";
						$this->salida .= "	  	            <td align=\"center\">PACIENTE</td>";
						if($homonimos1){ $this->salida .= "                  <td  width=\"1%\"></td>"; }
						$this->salida .= "                  <td  width=\"1%\"></td></tr>";
						$y=1;
						$this->consultarUsuarios();
					//	$actionUsuario=ModuloGetURL('app','Triage','user','mostrarDatosUsuario');
						foreach($homonimos1 as $tipo1=>$documento1){
							if($y % 2){
								$estilo='modulo_list_claro';
							}else{
								$estilo='modulo_list_oscuro';
							}
								$cadena=$this->nombreHomonimo($documento1,$tipo1);
								$infoCadena = explode ('-', $cadena);
								$Pnombre=$infoCadena[0];
								$Snombre=$infoCadena[1];
								$Papellido=$infoCadena[2];
								$Sapellido=$infoCadena[3];
								$this->salida .= "              <tr class=\"$estilo\"><td>$tipo1  $documento1</td>";
								$this->salida .= "		            <input type=\"hidden\" name=\"TipoId\" value=\"$tipo1\" >";
								$this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$documento1\" >";
								$this->salida .= "                <td>$Pnombre&nbsp&nbsp&nbsp;$Snombre&nbsp&nbsp&nbsp;$Papellido&nbsp&nbsp&nbsp;$Sapellido</td>";
								$this->salida .= "	  	          <td><a href=\"$accion\">Cambiar</a></td>";
								$this->salida .= "	  	          <td><a href=\"javascript:ConsultaHomo('DATOS DEL HOMONIMO','reports/$VISTA/datospaciente.php',500,400,'$tipo1',$documento1,'pacientes')\">Consultar</a></td></tr>";
								//$this->salida .= "	  	          <td><b><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CONSULTAR\" onClick=\"consultar('DATOS USUARIO', '$actionUsuario', 500, 300,this.form)\"></b></td></tr>";
								$y++;
						}
						foreach($homonimos as $tipo=>$documento){
							if($y % 2){
								$estilo='modulo_list_claro';
							}else{
								$estilo='modulo_list_oscuro';
							}
								$cadena=$this->nombreHomonimo($documento,$tipo);
								$infoCadena = explode ('-', $cadena);
								$Pnombre=$infoCadena[0];
								$Snombre=$infoCadena[1];
								$Papellido=$infoCadena[2];
								$Sapellido=$infoCadena[3];
								$this->salida .= "              <tr class=\"$estilo\"><td align=\"left\">$tipo $documento</td>";
								$this->salida .= "		            <input type=\"hidden\" name=\"TipoId\" value=\"$tipo\" >";
								$this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$documento\" >";
								$this->salida .= "                <td align=\"left\">$Pnombre&nbsp&nbsp&nbsp;$Snombre&nbsp&nbsp&nbsp;$Papellido&nbsp&nbsp&nbsp;$Sapellido</td>";
								$this->salida .= "	  	          <td><a href=\"javascript:ConsultaHomo('DATOS DEL HOMONIMO','reports/$VISTA/datospaciente.php',500,400,'$tipo',$documento,'pacientes')\">Consultar</a></td>";
								$this->salida .= "	  	          </tr>";
								$y++;
						}
						$this->salida .= "			      </table>";
						$this->salida .= "		      </fieldset></td></tr>";
						$this->salida .= "          </table><BR>";
						if($var){
								$this->salida .= "";
								$this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
								$this->salida .= "	  <tr align=\"center\">";
								$accionC=ModuloGetURL('app','Triage','user','InsertarDatosPacienteH',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'PrimerNombre'=>$PrimerNombre,'SegundoNombre'=>$SegundoNombre,'PrimerApellido'=>$PrimerApellido,'SegundoApellido'=>$SegundoApellido,'var'=>$var));
								$this->salida .= "           <form name=\"formabuscar\" action=\"$accionC\" method=\"post\">";
								$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CONTINUAR\"></td>";
								$this->salida .= "    </form>";
								$accionA=ModuloGetURL('app','Triage','user','LlamadaFormaPedirDatos',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'PrimerNombre'=>$PrimerNombre,'SegundoNombre'=>$SegundoNombre,'PrimerApellido'=>$PrimerApellido,'SegundoApellido'=>$SegundoApellido,'var'=>$var));
								$this->salida .= "    <form name=\"formaborrar\" action=\"$accionA\" method=\"post\">";
								$this->salida .= "		   <input type=\"hidden\" name=\"accion\" value=\"$accion\">";
								$this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ATRAS\"></td>";
								$this->salida .= "    </form>";
								$this->salida .= "	  </tr>";
								$this->salida .= "	  </table><br>";
								$this->salida .= ThemeCerrarTabla();
								return true;
						}
					}
	}
//--------------------------------------------------------------------------------------
		/**
		* Forma para capturar los datos del garante de un paciente
		* @access private
		* @return boolean
		* @param string tipo de documento
		* @param int numero de documento
		* @param int ingreso
		*/
		function FormaGarantesAdmision($TipoId,$PacienteId,$Ingreso)
		{
				$accionAcep=ModuloGetURL('app','Triage','user','BuscarListadoIngresos');
				$accion=ModuloGetURL('app','Triage','user','InsertarDatosGarantes');
				$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS GARANTES');
				$Garantes=$this->BuscarGarantes($Ingreso);
				if($Garantes)
				{
							$this->salida .= "		   <br>";
							$this->salida .= "		<table width=\"60%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
							$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
							$this->salida .= "				<td>GARANTE</td>";
							$this->salida .= "				<td>IDENTIFICACION</td>";
							$this->salida .= "				<td>DIRECCION</td>";
							$this->salida .= "				<td>TELEFONO</td>";
							$this->salida .= "			</tr>";
							$i=0;
							$y=1;
								while( $i < sizeof($Garantes)){
										$concate=strtok($Garantes[$i],'/');
										for($l=0;$l<6;$l++)
										{
											$res[$l]=$concate;
											$concate = strtok('/');
										}
								$i++;
								if( $y % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "				<td>$res[2] $res[3]</td>";
									$this->salida .= "				<td>$res[0]  $res[1]</td>";
									$this->salida .= "				<td align=\"center\">$res[4]</td>";
									$this->salida .= "				<td align=\"center\">$res[5]</td>";
									$this->salida .= "			</tr>";
									$y++;
								}
					$this->salida .= "  </table>";
				}

				$this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" >";
				$this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS NUEVO GARANTE</legend>";
				$this->salida .= "<br><table width=\"70%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
				if(!$Garantes){
        	$this->salida .= "	<tr><td colspan=\"3\" align=\"center\" class=\"label_err\">El paciente no tiene garantes.<br><br></td></tr>";
				}
				$this->salida .= "<input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
				$this->salida .= "<input type=\"hidden\" name=\"accion\" value=\"$accionAcep\">";
				$this->salida .= "		<tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
				$tipo_id_tercero=$this->tipo_id_terceros();
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($tipo_id_tercero as $value=>$titulo){
				$this->salida .=" <option value=\"$value\">$titulo</option>";	}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("GaranteId")."\">DOCUMENTO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" name=\"GaranteId\" maxlength=\"32\" class=\"input-text\"></td>";
				$this->salida .= "	 		<td>  </td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO NOMBRE: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$SegundoNombre\" class=\"input-text\"></td>";
				$this->salida .= "	 		<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"label\">SEGUNDO APELLIDO: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$SegundoApellido\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Direccion")."\">DIRECCION: </td>";
				$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$Direccion\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr height=\"20\">";
				$this->salida .= "	  	<td class=\"".$this->SetStyle("Telefono")."\">TELEFONOS: </td>";
				$this->salida .= "	  	<td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$Telefono\" class=\"input-text\"></td>";
				$this->salida .= "	  	<td></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr>";
				$this->salida .= "			 <td  align=\"center\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Ingresar\" value=\"INGRESAR\"><br></form></td>";
				$accionG=ModuloGetURL('app','Triage','user','BuscarListadoIngresos');
				$this->salida .= "  <form name=\"formagarantes\" action=\"$accionG\" method=\"post\">";
				$this->salida .= "      <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CONTINUAR\"><br></form></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "  </table>";
				$this->salida .= "		  </fieldset></td></tr></table><br>";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}

//---------------------------------HOSPITALIZACION-------------------------------------------------

	/**
	* Forma del menu de admisiones
	* @access private
	* @return boolean
	*/
	function FormaMenusHospitalizacion()
	{
        $this->salida .= ThemeAbrirTabla('MENUS ADMISION HOSPITALIZACION');
				$this->salida .= "			      <br>";
				$this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MENU ADMISIONES HOSPITALIZACION</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionB=ModuloGetURL('app','Triage','user','ListadoAdmisionHospitalizacion');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionB\">Orden Interna</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accion=ModuloGetURL('app','Triage','user','Buscar',array('TIPOORDEN'=>'Externa'));
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Orden Externa</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accion=ModuloGetURL('app','Triage','user','BuscarTranslado');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Traslado Departamento</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "			     </table>";
				$accion=ModuloGetURL('app','Triage','user','Hospitalizacion');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
				$this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* La funcion ListadoAdmisionHospitalizacion muestra el listado de los pacientes
	* pendientes por por hospitalizacion
	* @access private
	* @return boolean
	* @param int tipo busqueda
	* @param string mensaje
	* @param array arreglo con el resultado de la busqueda
	*/
	function ListadoAdmisionHospitalizacion($Busqueda,$mensaje,$D,$arr,$f)
	{
				$accion=ModuloGetURL('app','Triage','user','BuscarAdmisionHospitalizacion');
				$this->salida  = ThemeAbrirTabla('LISTADO ADMISION HOSPITALIZACION');
				if(!$Busqueda){ $Busqueda=1; }
				$accionA=ModuloGetURL('app','Triage','user','MetodoBuscarHospitalizacion');
				$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\" >";
				$this->salida .= "		<tr>";
				$this->salida .= "		   <td width=\"60%\" >";
				$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCAR DATOS ADMISION</legend>";
				$this->salida .= "			      <table width=\"90%\" align=\"center\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				if($Busqueda=='1'){
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "				        <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
					$tipo_id=$this->tipo_id_paciente();
					$this->BuscarIdPaciente($tipo_id,'False','');
					$this->salida .= "                  </select></td></tr>";
					$this->salida .= "				        <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
					$this->salida .= "	  	            <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				}
				if($Busqueda=='4'){
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "				        <tr><td class=\"".$this->SetStyle("Orden")."\">No. ORDEN</td><td><input type=\"text\" class=\"input-text\" name=\"Orden\" maxlength=\"32\"></td></tr>";
					$this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
				}
				$this->salida .= "               <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td>";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"BuscarCompleto\" value=\"LISTADO ORDENES\"><br></form></td>";
				$actionM=ModuloGetURL('app','Triage','user','MenusHospitalizacion');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
				$this->salida .= "				       </tr>";
				$this->salida .= "		  </fieldset></td></tr></table>";
				$this->salida .= "	</table>";
				$this->salida .= "		   </td>";
				$this->salida .= "		   <td>";
				$this->salida .= "      <table border=\"0\" width=\"92%\" align=\"center\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$accionA\" method=\"post\">";
				$this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
				$this->salida .= "			      <table width=\"90%\" align=\"center\">";
				$this->salida .= "				       <tr><br><td class=\"label\">TIPO BUSQUEDA: </td><td><select name=\"TipoBusqueda\" class=\"select\">";
				$this->salida .="                   <option value=\"1\" selected>DOCUMENTO</option>";
				$this->salida .="                   <option value=\"4\">No. ORDEN</option>";
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"BUSCAR\"></td></tr>";
				$this->salida .= "			      </form>";
				$this->salida .= "			         </table>";
				$this->salida .= "		  </fieldset></td></tr></table>";
				$this->salida .= "		   </td>";
				$this->salida .= "		</tr>";
				$this->salida .= "	</table>";
				if($mensaje){
						$this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
				}
				if(!empty($arr))
				{
						$this->salida .= "			    <br><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
						$this->salida .= "				      <td width=\"9%\">No. ORDEN</td>";
						$this->salida .= "              <td width=\"10%\">TIPO ORDEN</td>";
						$this->salida .= "	  	        <td width=\"8%\">FECHA</td>";
						$this->salida .= "              <td width=\"10%\">FECHA PRO.</td>";
						$this->salida .= "              <td width=\"10%\">No.DOCUMENTO</td>";
						$this->salida .= "              <td width=\"20%\">NOMBRE COMPLETO</td>";
						$this->salida .= "              <td></td>";
						$this->salida .= "            </tr>";
						for($i=0;$i<sizeof($arr);$i++)
						{
								if( $i % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
								$Fechaorden=$this->FechaStamp($arr[$i][fecha_orden]);
								$Fechaprogramacion=$this->FechaStamp($arr[$i][fecha_programacion]);
								$actionHref=ModuloGetURL('app','Triage','user','VerificarDatosHospitalizacion',array('datos'=>$arr[$i]));
								$this->salida .= "			<tr class=\"$estilo\">";
								$this->salida .= "				      <td width=\"9%\" align=\"center\">".$arr[$i][orden_hospitalizacion_id]."</td>";
								$this->salida .= "              <td width=\"10%\" align=\"center\">".$arr[$i][descripcion]."</td>";
								$this->salida .= "	  	        <td width=\"8%\" align=\"center\">".$Fechaorden."</td>";
								$this->salida .= "              <td width=\"10%\" align=\"center\">".$Fechaprogramacion."</td>";
								$this->salida .= "              <td width=\"10%\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
								$this->salida .= "              <td width=\"27%\">".$arr[$i][completo]."</td>";
								$this->salida .= "              <td align=\"center\"><a href=\"$actionHref\">Admitir</a></td>";
								$this->salida .= "            </tr>";
						}
    				$this->salida .= "            </table><br>";
						$this->conteo=$_SESSION['CONTADOR'];
						$this->salida .=$this->RetornarBarra();
				}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*
	*/
	function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


	function RetornarBarra($metodo){

		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		if(empty($metodo))
		{  $accion=ModuloGetURL('app','Triage','user','BuscarAdmisionHospitalizacion',$vec);  }
		else
		{  $accion=ModuloGetURL('app','Triage','user',$metodo,$vec);  }
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
		}
}


	/**
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param int plan_id
	*/
	function FormaResponsable($TipoId,$PacienteId,$Responsable)
	{
				$action=ModuloGetURL('app','Triage','user','ValidarPacienteHospitalizacion',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId));
        $this->salida .= ThemeAbrirTabla('BUSCAR PACIENTE');
				$this->salida .= "			      <br><br>";
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td><select name=\"Plan\" class=\"select\">";
        $responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
				$this->MostrarResponsable($responsables,$Responsable);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "              <tr>";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td></form>";
				$actionM=ModuloGetURL('app','Triage','user','ListadoAdmisionHospitalizacion');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></form></tr>";
				$this->salida .= "			     </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Forma para capturar los datos de la orden de hospitalizacion externa
	* @access private
	* @return boolean
	* @param string nombre medico
	* @param string descripcion cargo
	* @param int codigo diagnostico
	* @param int entidad que genera la orden
	* @param string observaciones
	* @param date fecha de la orden
	* @param int hora de la orden
	* @param int minuto de la orden
	*/
	function FormaOrdenExterna($medico,$cargo,$codigo,$diagnostico,$origen,$observacion,$Fecha,$Hora,$Min)
	{
				$this->salida .= ThemeAbrirTabla('ORDEN HOSPITALIZACION EXTERNA');
				$datos=$this->DatosBasicosPaciente(); 
				$this->salida .= "		<br><table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"15%\" nowrap>IDENTIFICACION: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".$datos[0][tipo_id_paciente]." ".$datos[0][paciente_id]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"12%\" nowrap>PACIENTE: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">".$datos[0][completo]."</td>";
				$this->salida .= "				<td class=\"modulo_table_list_title\">INGRESO: </td>";
				$this->salida .= "				<td class=\"modulo_list_claro\">".$datos[0][ingreso]."</td>";
				$this->salida .= "			<tr>";
				$this->salida .= "		</table>";
				$accion=ModuloGetURL('app','Triage','user','ValidarOrdenExterna');
				$this->salida .= "             <form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Fecha")."\">FECHA: </td>";
				$this->salida .= "	<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"$Fecha\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Fecha','/')."</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Hora")."\">HORA: </td>";
				$this->salida .= "	<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Hora\" size=\"4\" value=\"$Hora\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"Min\" size=\"4\" value=\"$Min\"  maxlength=\"2\"></td>";
				$this->salida .= "	</tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Origen")."\">ENTIDAD: </td>";
				$this->salida .= "				      <td colspan=\"2\"><select name=\"Origen\" class=\"select\">";
				$entidades=$this->EntidadesOrigen();
				$this->BuscarEntidadOrigen($entidades,$origen);
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Medico")."\">MEDICO: </td>";
				$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Medico\" value=\"$medico\" size=\"30\" maxlength=\"50\"></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$mostrar=ReturnClassBuscador('diagnostico','','','forma');
				$this->salida.=$mostrar;
				$this->salida.="</script>\n";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Diagnostico")."\">DIAGNOSTICO: </td>";
				$this->salida.= "<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"$codigo\">";
				$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"cargo\" READONLY>$cargo</textarea></td>";
				$this->salida .= "			        <td><input type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=abrirVentana() class=\"input-submit\"></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Observacion")."\">OBSERVACIONES: </td>";
				$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"Observacion\">$observacion</textarea></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr align=\"center\">";
				$this->salida .= "			        <td colspan=\"3\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     </table>";
				$this->salida .= "			     </form>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	* @param int entidad que genera la orden
	* @param int entidad que genera la orden (cuando ya han elegido una)
	*/
	function BuscarEntidadOrigen($ent,$Origen)
	{
			$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			for($i=0; $i<sizeof($ent); $i++)
			{
					if($ent[$i][sgsss]==$Origen){
						$this->salida .=" <option value=\"".$ent[$i][sgsss]."\" selected>".$ent[$i][nombre_sgsss]."</option>";
					}
					else{
						$this->salida .=" <option value=\"".$ent[$i][sgsss]."\">".$ent[$i][nombre_sgsss]."</option>";
					}
			}
	}

	/**
	* Forma que muestra el listado de las ordenes de traslado
	* @access private
	* @return boolean
	* @param array arreglo con el listado de los datos de las ordenes de traslado
	*/
	function FormaListadoTranslado($arr)
	{
				$accion=ModuloGetURL('app','Triage','user','BuscarAdmisionHospitalizacion');
				$this->salida  = ThemeAbrirTabla('LISTADO ADMISION HOSPITALIZACION TRANSLADO');
				if(!empty($arr))
				{
						$this->salida .= "			    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
						$this->salida .= "				      <td width=\"9%\">No. ORDEN</td>";
						$this->salida .= "              <td width=\"10%\">TIPO ORDEN</td>";
						$this->salida .= "	  	        <td width=\"8%\">FECHA</td>";
						$this->salida .= "              <td width=\"10%\">FECHA PRO.</td>";
						$this->salida .= "              <td width=\"10%\">No.DOCUMENTO</td>";
						$this->salida .= "              <td width=\"20%\">NOMBRE COMPLETO</td>";
						$this->salida .= "              <td></td>";
						$this->salida .= "            </tr>";
						for($i=0;$i<sizeof($arr);$i++)
						{
								if( $i % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
								$Fechaorden=$this->FechaStamp($arr[$i][fecha_orden]);
								$Fechaprogramacion=$this->FechaStamp($arr[$i][fecha_programacion]);
								$actionHref=ModuloGetURL('app','Triage','user','VerificarDatosHospitalizacion',array('datos'=>$arr[$i]));
								$this->salida .= "			<tr class=\"$estilo\">";
								$this->salida .= "				      <td width=\"9%\" align=\"center\">".$arr[$i][orden_hospitalizacion_id]."</td>";
								$this->salida .= "              <td width=\"10%\" align=\"center\">".$arr[$i][descripcion]."</td>";
								$this->salida .= "	  	        <td width=\"8%\" align=\"center\">".$Fechaorden."</td>";
								$this->salida .= "              <td width=\"10%\" align=\"center\">".$Fechaprogramacion."</td>";
								$this->salida .= "              <td width=\"10%\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
								$this->salida .= "              <td width=\"27%\">".$arr[$i][completo]."</td>";
								$this->salida .= "              <td align=\"center\"><a href=\"$actionHref\">Admitir</a></td>";
								$this->salida .= "            </tr>";
						}
    				$this->salida .= "            </table><br>";
						$this->conteo=$_SESSION['COUNT'];
						$this->salida .=$this->RetornarBarra3();
				}
		$this->salida .= "		<table width=\"20%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
		$actionM=ModuloGetURL('app','Triage','user','MenusHospitalizacion');
		$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "				       <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarra3(){

		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		$accion=ModuloGetURL('app','Triage','user','BuscarTranslado',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=1;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
		}
}


	/**
	* Forma que muestra las estaciones de enfermeria que tiene asociadas un punto
	* @access private
	* @return boolean
	*/
	function FormaElegirPuntos($ptos,$ptoAdm)
	{
				$this->salida .= ThemeAbrirTabla('REMISIONES - ELEGIR PUNTO DE ADMISION');
				$accion=ModuloGetURL('app','Triage','user','RemitirPunto');
				$this->salida .= "			      <br><table width=\"40%\" align=\"center\" >";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td align=\"center\" class=\"".$this->SetStyle("Estacion")."\" colspan=\"3\">ELIJA EL PUNTO DE ADMISION AL QUE SERA REMITIDO EL PACIENTE</td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Estacion")."\">Punto Admisión: </td>";
				$this->salida .= "				       <td colspan=\"2\"><select name=\"punto\" class=\"select\">";
				for($i=0; $i<sizeof($ptos); $i++)
				{
						$this->salida .=" <option value=\"".$ptos[$i][punto_admision_id]."\">".$ptos[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "			     </form>";
				$this->salida .= "              </tr>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	* Forma que muestra las estaciones de enfermeria que tiene asociadas un punto
	* @access private
	* @return boolean
	*/
	function FormaElegirEstacionCambio($Est,$pto)
	{
				$this->salida .= ThemeAbrirTabla('REMISIONES - ELEGIR ESTACION DE ENFERMERIA');
				$accion=ModuloGetURL('app','Triage','user','RemitirEstacion',array('punto'=>$pto));
				$this->salida .= "			      <br><table width=\"40%\" align=\"center\" >";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td align=\"center\" class=\"".$this->SetStyle("Estacion")."\" colspan=\"3\">ELIJA LA ESTACION DEL PUNTO ".$this->NombrePunto($pto)." A LA QUE SERA REMITIDO EL PACIENTE</td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Estacion")."\">Estación: </td>";
				$this->salida .= "				       <td colspan=\"2\"><select name=\"estacion\" class=\"select\">";
				for($i=0; $i<sizeof($Est); $i++){
						$this->salida .=" <option value=\"".$Est[$i][estacion_id].",".$Est[$i][departamento].",".$Est[$i][descripcion]."\">".$Est[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "			     </form>";
				$this->salida .= "              </tr>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaMuestraEstaciones()
	{
				$this->salida .= ThemeAbrirTabla('REMISIONES - ELEGIR ESTACION DE ENFERMERIA');
				$accion=ModuloGetURL('app','Triage','user','RemitirPacienteEstacion');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			      </table><BR>";
				$this->salida .= "<p class=\"label_mark\" align=\"center\">ELIJA LA ESTACION A LA QUE VA A REMITIR EL PACIENTE, PARA LA CONFIRMACION MEDICA</p>";
				$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Estacion")."\">Elija la Estación: </td>";
				$this->salida .= "				       <td colspan=\"2\"><select name=\"Estacion\" class=\"select\">";
				$Est=$this->BuscarTodasEstaciones();
				for($i=0; $i<sizeof($Est); $i++){
						$this->salida .=" <option value=\"".$Est[$i][estacion_id].",".$Est[$i][departamento].",".$Est[$i][descripcion].",".$Est[$i][punto_admision_id]."\">".$Est[$i][descripcion]."</option>";
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "			     </form>";
				$accion=MoDuloGetURL('app','Triage','user','CancelarRemisionEnfermera');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
				$this->salida .= "              </tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function FormaSignosVitales($FechaNacimiento,$EdadArr,$fc,$fr,$temperatura,$peso,$tAlta,$tBaja,$eva,$ocular,$verbal,$motora,$sato)
	{
				$this->SumaGlasgow();
				$signo = BuscarSignosObligatorios();
                    
				$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "			  </table><br>";
				$this->salida .= "            <table border=\"0\" width=\"80%\" align=\"center\">";
				$this->salida .= "              <tr>";
				$this->salida .= "                <td width=\"20%\" align=\"center\" class=\"".$this->SetStyle("frecuenciaCardiaca")."\">Fc:</td><td><input type=\"text\" class=\"input-text\" size=\"4\" name=\"frecuenciaCardiaca\" value=\"$fc\" maxlength=\"6\"></td><td align=\"center\" width=\"5%\" class=\"label\" >m</td>";
				$this->salida .= "                <td width=\"20%\" align=\"center\" class=\"".$this->SetStyle("frecuenciaRespiratoria")."\">Fr:</td><td><input type=\"text\" class=\"input-text\" size=\"4\" name=\"frecuenciaRespiratoria\" value=\"$fr\" maxlength=\"6\"></td><td align=\"center\" width=\"5%\" class=\"label\" >m</td>";
				$this->salida .= "                <td width=\"10%\" align=\"center\" class=\"".$this->SetStyle("temperatura")."\">Tº:</td><td><input type=\"text\" class=\"input-text\" size=\"4\" name=\"temperatura\" value=\"$temperatura\" maxlength=\"4\"></td><td align=\"center\" width=\"5%\" class=\"label\" >ºC</td>";
				$this->salida .= "              </tr>";
				$this->salida .= "              <tr align=\"center\">";
				$this->salida .= "                <td  width=\"20%\"  class=\"".$this->SetStyle("peso")."\">Peso:</td><td align=\"left\"><input type=\"text\" class=\"input-text\" size=\"4\" name=\"peso\" value=\"$peso\" maxlength=\"6\"></td><td align=\"center\" width=\"5%\" class=\"label\" >kg</td>";
				$this->salida .= "                <td align=\"center\" class=\"".$this->SetStyle("taAlta")."\">T.A.:</td><td colspan=\"2\" align=\"left\" ><input type=\"text\" class=\"input-text\" name=\"taAlta\" size=\"3\" value=\"$tAlta\" maxlength=\"6\"> / <input type=\"text\" class=\"input-text\" name=\"taBaja\" size=\"3\" value=\"$tBaja\"></td>";
				$this->salida .= "                <td class=\"".$this->SetStyle("sato")."\">Sat 02:</td><td align=\"left\"><input type=\"text\" class=\"input-text\" size=\"4\" name=\"sato\" value=\"$sato\" maxlength=\"3\"></td><td align=\"center\" width=\"5%\" class=\"label\" >%</td>";
				$this->salida .= "              </tr>";
				$this->salida .= "			      </table>";
				//---------------tabla eva
				if($signo['eva']['sw_mostrar']==1)				
				{
						$this->salida .= "<table colspan=\"2\" align=\"center\" width=\"90%\" border=\"1\" class=\"modulo_table_list\">\n";
						$this->salida .="<tr align=\"center\"><td colspan=\"12\" class='modulo_table_list_title'>ESCALA VISUAL ANALOGA - EVA</td></tr>";
						$this->salida .="<tr align=\"center\">";
						$this->salida .="<td rowspan=\"2\">Menor Dolor</td>";
						if ($EdadArr[anos] < ModuloGetVar('','','max_edad_pediatrica'))
						{
							$this->salida .= "<input type=\"hidden\" name=\"niño\" value=\"1\">";
							$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/no_dolor.png\" border=0></td>";
							$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/leve.png\" border=0></td>";
							$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/moderado.png\" border=0></td>";
							$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/severopain.png\" border=0></td>";
							$this->salida .="<td><img src=\"".GetThemePath()."/images/signovital/muyseveropain.png\" border=0></td>";
							$this->salida .="<td rowspan=\"2\" colspan=\"6\" >Mayor Dolor</td>";
							$this->salida .="</tr>";
							$this->salida .="<tr>";
							if ($eva != 0 )
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"0\"></td>";
							}
							else
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"0\"></td>";
							}
							if ($eva != 1 )
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"1\"></td>";
							}
							else
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";
							}
							if ($eva != 2 )
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"2\"></td>";
							}
							else
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";
							}
							if ($eva != 3 )
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"3\"></td>";
							}
							else
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";
							}
							if ($eva != 4 )
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"4\"></td>";
							}
							else
							{
								$this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";
							}
						}
						else
						{
							$this->salida .= "<input type=\"hidden\" name=\"niño\" value=\"0\">";
							$this->salida .="<td>1</td>";
							$this->salida .="<td>2</td>";
							$this->salida .="<td>3</td>";
							$this->salida .="<td>4</td>";
							$this->salida .="<td>5</td>";
							$this->salida .="<td>6</td>";
							$this->salida .="<td>7</td>";
							$this->salida .="<td>8</td>";
							$this->salida .="<td>9</td>";
							$this->salida .="<td>10</td>";
							$this->salida .="<td rowspan=\"2\">Mayor Dolor</td>";
							$this->salida .="</tr>";
							$this->salida .="<tr>";
							if ($eva != 1 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"1\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"1\"></td>";  }
							if ($eva != 2 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"2\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"2\"></td>";  	}
							if ($eva != 3 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"3\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"3\"></td>";  }
							if ($eva != 4)
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"4\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"4\"></td>";  }
							if ($eva != 5 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"5\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"5\"></td>";  }
							if ($eva != 6 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"6\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"6\"></td>"; }
							if ($eva != 7 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"7\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"7\"></td>";  	}
							if ($eva != 8 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"8\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"8\"></td>";  	}
							if($eva != 9 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"9\"></td>";  }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"9\"></td>";  }
							if ($eva != 10 )
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" name=\"eva\" value=\"10\"></td>";   }
							else
							{  $this->salida .="<td align=\"center\"><input type=\"radio\" checked name=\"eva\" value=\"10\"></td>";  }
						}
						$this->salida .="</tr></table>";
				}
				//---------------fin tabla eva
				//---------------tabla glasqow
				if($signo['glasgow']['sw_mostrar']==1)
				{
						if(empty($_REQUEST['SumaGlas']))
						{   $_REQUEST['SumaGlas']=$ocular+$motora+$verbal;   }
						$this->salida .= "<br><table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
						$this->salida .="<tr align=\"center\" class=\"modulo_table_list_title\"><td colspan=\"3\">ESCALA DE GLASGOW&nbsp;&nbsp;&nbsp; ( <input type=\"text\" name=\"SumaGlas\" size=\"1\" value=\"".$_REQUEST['SumaGlas']."\" readonly> )</td></tr>";
						$this->salida .="<tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .="<td>APERTURA OCULAR</td>";
						$this->salida .="<td>RESPUESTA VERBAL</td>";
						$this->salida .="<td>RESPUESTA MOTORA</td>";
						$this->salida .="</tr>";
						$this->salida .="<tr align=\"center\">";
						//ocular
						$ocu=$this->Ocular();
						$this->salida .= "<td><select name=\"ocular\" class=\"select\" onChange=\"SumaGlasgow(this.form)\">";
						$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
						for($f=0; $f<sizeof($ocu); $f++)
						{
							if($ocular==$ocu[$f][apertura_ocular_id])
							{  $this->salida .=" <option value=\"".$ocu[$f][apertura_ocular_id]."\" selected>".$ocu[$f][apertura_ocular_id]." - ".$ocu[$f][descripcion]."</option>";  }
							else
							{  $this->salida .=" <option value=\"".$ocu[$f][apertura_ocular_id]."\">".$ocu[$f][apertura_ocular_id]." - ".$ocu[$f][descripcion]."</option>";  }
						}
						$this->salida .= "</select></td>";
						//verbal
						$ver=$this->Verbal($FechaNacimiento);
						$this->salida .= "<td><select name=\"verbal\" class=\"select\" onChange=\"SumaGlasgow(this.form)\">";
						$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
						for($f=0; $f<sizeof($ver); $f++)
						{
							if($verbal==$ver[$f][respuesta_verbal_id])
							{  $this->salida .=" <option value=\"".$ver[$f][respuesta_verbal_id]."\" selected>".$ver[$f][respuesta_verbal_id]." - ".$ver[$f][descripcion]."</option>";  }
							else
							{  $this->salida .=" <option value=\"".$ver[$f][respuesta_verbal_id]."\">".$ver[$f][respuesta_verbal_id]." - ".$ver[$f][descripcion]."</option>";  }
						}
						$this->salida .= "</select></td>";
						//motora
						$mot=$this->Motora($FechaNacimiento);
						$this->salida .= "<td><select name=\"motora\" class=\"select\" onChange=\"SumaGlasgow(this.form)\">";
						$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
						for($f=0; $f<sizeof($mot); $f++)
						{
							if($motora==$mot[$f][respuesta_motora_id])
							{  $this->salida .=" <option value=\"".$mot[$f][respuesta_motora_id]."\" selected>".$mot[$f][respuesta_motora_id]." - ".$mot[$f][descripcion]."</option>";  }
							else
							{  $this->salida .=" <option value=\"".$mot[$f][respuesta_motora_id]."\">".$mot[$f][respuesta_motora_id]." - ".$mot[$f][descripcion]."</option>";  }
						}
						$this->salida .= "</select></td>";
						$this->salida .="</tr>";
						$this->salida .="</table>";
				}
				//---------------fin tabla glasqow
	}

	function SumaGlasgow()
	{
		$this->salida .= "\n<script>\n";
		$this->salida .= "  function SumaGlasgow(frm){\n";
		$this->salida .= "    o = frm.ocular.value;\n";
		$this->salida .= "    v = frm.verbal.value;\n";
		$this->salida .= "    m = frm.motora.value;\n";
		$this->salida .= "    t = (o*1) +(v*1) +(m*1);\n";
		$this->salida .= "    frm.SumaGlas.value = t;\n";
		$this->salida .= "   \n";
		$this->salida .= "  }\n";
		$this->salida .=  "</script>\n";
	}
//------------------------------------------------------------------------
	/**
	*
	*/
	function FormaImprimir($vector,$modulo,$reporte,$accion,$mensaje)
	{
				$this->salida .= ThemeAbrirTabla('IMPRIMIR TRIAGE');
				$this->salida .= "			      <table width=\"60%\" align=\"center\" border=0>";
				$this->salida .= "				       <tr><td colspan=\"4\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr>";
				$reporte= new GetReports();
				$mostrar=$reporte->GetJavaReport('app',$modulo,'triage',$vector,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></td>";
				unset($reporte);
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

		/**
		*
		*/
    function ListadoPacientesAtendidosTriage()
    {
				$arr=$this->PacientesAtendidosTriage();
				$reporte= new GetReports();
				if($arr)
				{
						$this->SetJavaScripts('DatosPaciente');
						$this->salida .= '<br><table width="60%" align="center" border="0" class="modulo_table">';
						$this->salida .= '<tr align="center" class="modulo_table_title">';
						$this->salida .= '<td>';
						$this->salida .= "PACIENTES CLASIFICADOS POR ESTE PROFESIONAL EN LAS ULTIMAS 12 HORAS";
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td align="center">';
						$this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
						$this->salida .= '<tr align="center" class="modulo_table_list_title">';
						$this->salida .= '<td align="center" width="70%">';
						$this->salida .= "Pacientes";
						$this->salida .= "</td>";
						$this->salida .= '<td align="center" width="16%">Triage</td>';
						$this->salida .= "</tr>";
						for($i=0; $i<sizeof($arr); $i++)
						{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr align=\"center\" class=\"$estilo\">";
								$this->salida .= '<td align="center">';
								$this->salida.=RetornarWinOpenDatosPaciente($arr[$i]['tipo_id_paciente'],$arr[$i]['paciente_id'],$arr[$i]['nombre']);
								$this->salida .= "</td>";
								$mostrar=$reporte->GetJavaReport('app','Admisiones','triage',array('triage_id'=>$arr[$i]['triage_id'],'empresa'=>$_SESSION['ADMISIONES']['NOMEMPRESA'],'nombre'=>$arr[$i]['nombre']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
								$funcion=$reporte->GetJavaFunction();
								$this->salida .=$mostrar;
								$this->salida .= "				       <td align=\"center\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  Imprimir</a></td>";
								$this->salida .= "</tr>";
						}
						$this->salida .= "</table>";
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= '</table>';
				}
				else
				{
						$this->salida .= '<table width="80%" align="center">';
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td align="center" class="label_error">';
						$this->salida .= 'NO HAY PACIENTES CLASIFICADOS HOY';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= "</table>";
				}
				unset($reporte);
				return true;
    }


	function FormaBuscarTriagesPacientes($arr)
	{
      $this->salida.= ThemeAbrirTabla('BUSQUEDA DE TRIAGES PACIENTES');
      IncludeLib("funciones_admision");
      $accion=ModuloGetURL('app','Triage','user','BuscarTriagePaciente');
      $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr class=\"modulo_table_list_title\">";
      $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
      $this->salida .= "</tr>";
      $this->salida .= "<tr class=\"modulo_list_claro\" >";
      $this->salida .= "<td width=\"40%\" >";
      $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr><td>";
      $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
      $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
      $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
      $tipo_id=$this->tipo_id_paciente();
      $this->BuscarIdPaciente($tipo_id,'',$_REQUEST['TipoDocumento']);
      $this->salida .= "</select></td></tr>";
      $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
      $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value=\"".$_REQUEST['Nombres']."\"></td></tr>";
      $this->salida .= "                <tr>";
      $i=$_REQUEST['Fecha'];
      if(!empty($i))
      {
             $f=explode('-',$_REQUEST['Fecha']);
            $i=$f[2].'/'.$f[1].'/'.$f[0];
      }
      $this->salida .= "                    <td class=\"".$this->SetStyle("Fecha")."\">FECHA INGRESO: </td>";
      $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" value=\"".$i."\">".ReturnOpenCalendario('forma','Fecha','/')."</td>";
      $this->salida .= "                </tr>";
			$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
      $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
      $this->salida .= "</form>";
		  $actionM=ModuloGetURL('app','Triage','user','FormaMenuTriage');
      $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
      $this->salida .= "</tr>";
      $this->salida .= "</table></td></tr>";
      $this->salida .= "</td></tr></table>";
      $this->salida .= "</td>";
      $this->salida .= "</table>";
      $this->salida .= "       </td>";
      $this->salida .= "    </tr>";
      $this->salida .= "  </table>";
      //mensaje
      $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  </table>";
			if($arr)
      {
					$this->salida .= "<br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "        <td width=\"15%\">FECHA</td>";
					$this->salida .= "        <td width=\"22%\">IDENTIFICACION</td>";
					$this->salida .= "        <td width=\"35%\">PACIENTE</td>";
					$this->salida .= "        <td>TRIAGE</td>";
					//$this->salida .= "        <td>EPICRISIS</td>";
					$this->salida .= "      </tr>";
					for($i=0;$i<sizeof($arr);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida .= "      <tr class=\"$estilo\">";
							$this->salida .= "        <td align=\"center\">".FechaStamp($arr[$i][hora_llegada])." ".HoraStamp($arr[$i][hora_llegada])."</td>";
							$this->salida .= "        <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
							$this->salida .= "        <td>".$arr[$i][nombre]."</td>";
							$accion2=ModuloGetURL('app','Triage','user','ConsultaTriageExt',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'TipoDocumento'=>$_REQUEST['TipoDocumento'],'Fecha'=>$_REQUEST['Fecha'],'Documento'=>$_REQUEST['Documento'],'Nombres'=>$_REQUEST['Nombres']));
							$this->salida .= "				         				 <td align=\"center\"><a href=\"$accion2\" title='TRIAGE'><img src=\"".GetThemePath()."/images/especialidad.png\" border='0' title='TRIAGE'>&nbsp; CONSULTAR TRIAGE</a></td>";
							//$this->salida .= "        <td align=\"center\"></td>";
							$this->salida .= "      </tr>";
				}//fin for
				$this->salida .= " </table><br>";
				$this->conteo=$_SESSION['SPYT'];
				$this->salida .=$this->RetornarBarra('BuscarTriagePaciente');
      }

			$this->salida .= ThemeCerrarTabla();
			return true;
	}
//-------------------------------CAJA-----------------------

	function FormaCaja()
	{
			$this->salida.= ThemeAbrirTabla('PAGO EN CAJA');
      $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr class=\"modulo_table_list_title\">";
      $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
      $this->salida .= "</tr>";

			$this->salida .= ThemeCerrarTabla();
			return true;
	}

//---------------------------------------------------------------------------------------------------------

}//FIN CLASE

?>

