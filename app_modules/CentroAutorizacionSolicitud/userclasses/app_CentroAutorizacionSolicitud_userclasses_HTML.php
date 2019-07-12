<?php

/**
 * $Id: app_CentroAutorizacionSolicitud_userclasses_HTML.php,v 1.13 2006/05/11 16:59:08 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_CentroAutorizacionSolicitud_userclasses_HTML extends app_CentroAutorizacionSolicitud_user
{
	/**
	*Constructor de la clase app_CentroAutorizacion_user_HTML
	*El constructor de la clase app_CentroAutorizacionSolisictud_user_HTML se encarga de llamar
	*a la clase app_CentroAutorizacionSolisictud_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_CentroAutorizacionSolicitud_user_HTML()
	{
				$this->salida='';
				$this->app_CentroAutorizacionSolicitud_user();
				return true;
	}


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
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	* @param string tipo documento
	* @param int numero documento
	* @param int plan_id
	*/
	function FormaBuscar()
	{
				//$action=ModuloGetURL('app','CentroAutorizacionSolicitud','user','BuscarPaciente');
				$action=ModuloGetURL('app','CentroAutorizacionSolicitud','user','EventoSoat');
      	$this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL - BUSCAR PACIENTE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
        $responsables=$this->responsables();
				if(!empty($responsables))
				{
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("plan")."\">PLAN: </td><td><select name=\"plan\" class=\"select\">";
						$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
						for($i=0; $i<sizeof($responsables); $i++)
						{
								if($responsables[$i][plan_id]==$_REQUEST['plan']){
										$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
								}else{
										$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
								}
						}
						$this->salida .= "              </select></td></tr>";
				}
				else
				{
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("plan")."\">PLAN: </td><td>";
						$this->salida .="NO HAY PLANES ACTIVOS PARA LA EMPRESA</td></tr>";
				}
				$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"Tipo\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Triage','user','tipo_id_paciente','');
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$TipoId)
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
				$this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				$actionM=ModuloGetURL('system','Menu','user','main');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
				$this->salida .= "			     </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function DatosPaciente()
	{
				if(empty($_SESSION['SOLICITUD']['PACIENTE']['nombre']))
				{
						$nom=$this->NombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
						$_SESSION['SOLICITUD']['PACIENTE']['nombre']=$nom['nombre'];
				}
				$this->salida .= "		 <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['nombre']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"10%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['plan_descripcion']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= " 			</table><BR>";
	}
	/**
	*
	*/
	function FormaDatosSolicitud()
	{
		if(empty($_REQUEST['Fecha'])) $_REQUEST['Fecha'] = date("d/m/Y");
		
		$this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL');
		$this->DatosPaciente();
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GuardarDatosSolicitud');
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "	<table width=\"50%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "		".$this->SetStyle("MensajeError");
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td class=\"".$this->SetStyle("Fecha")."\">FECHA: </td>\n";
		$this->salida .= "		<td class=\"label\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"".$_REQUEST['Fecha']."\">\n";
		$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Fecha','/')."</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr>";
		$this->salida .= "			        <td class=\"".$this->SetStyle("Serv")."\">SERVICIO: </td>";
		$this->salida .= "				      <td colspan=\"2\"><select name=\"Serv\" class=\"select\">";
		$ser=$this->TiposServicios();
		$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
		for($i=0; $i<sizeof($ser); $i++)
		{
				if($ser[$i][servicio]==$_REQUEST['Serv'])
				{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\" selected>".$ser[$i][descripcion]."</option>";  }
				else
				{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\">".$ser[$i][descripcion]."</option>";  }
		}
		$this->salida .= "              </select></td></tr>";
		//proveedores
		$this->salida .= "			        <td class=\"".$this->SetStyle("Origen1")."\">ENTIDAD SOLICITA: </td>";
		$this->salida .= "				      <td colspan=\"2\"><select name=\"Origen1\" class=\"select\">";
		$pla=$this->PlanesProveedores();
		$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
		for($i=0; $i<sizeof($pla); $i++)
		{
				if($pla[$i][plan_descripcion]==$_REQUEST['Origen1'])
				{  $this->salida .=" <option value=\"".$pla[$i][plan_descripcion]."\" selected>".$pla[$i][plan_descripcion]."</option>";  }
				else
				{  $this->salida .=" <option value=\"".$pla[$i][plan_descripcion]."\">".$pla[$i][plan_descripcion]."</option>";  }
		}
		$this->salida .= "              </select></td></tr>";
		//fin proeveedores
		$this->salida .= "			     </tr>";
		$this->salida .= "			     <tr>";
		$this->salida .= "			        <td class=\"".$this->SetStyle("Origen")."\">ENTIDAD SOLICITA: </td>";
		$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Origen\" value=\"".$_REQUEST['Origen']."\" size=\"40\" maxlength=\"50\"></td>";
		$this->salida .= "			     </tr>";
		$this->salida .= "			     <tr>";
		$this->salida .= "			        <td class=\"".$this->SetStyle("Medico")."\">MEDICO: </td>";
		$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Medico\" value=\"".$_REQUEST['Medico']."\" size=\"40\" maxlength=\"50\"></td>";
		$this->salida .= "			     </tr>";		
		$this->salida .= "			     <tr>";				
		$this->salida .= "			        <td class=\"".$this->SetStyle("departamento")."\">DEPARTAMENTO: </td>";
		$this->salida .= "				      <td colspan=\"2\"><select name=\"departamento\" class=\"select\">";
		$dpto=$this->BuscarDepartamento();
		$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
		for($i=0; $i<sizeof($dpto); $i++)
		{
				if($dpto[$i][departamento]==$_REQUEST['departamento'])
				{  $this->salida .=" <option value=\"".$dpto[$i][departamento]."\" selected>".$dpto[$i][descripcion]."</option>";  }
				else
				{  $this->salida .=" <option value=\"".$dpto[$i][departamento]."\">".$dpto[$i][descripcion]."</option>";  }
		}
		$this->salida .= "              </select></td></tr>";
		$this->salida .= "			     </tr>";
		
		/*$this->salida .= "			     <tr>";
		$mostrar=ReturnClassBuscador('diagnostico','','','forma');
		$this->salida.=$mostrar;
		$this->salida.="</script>\n";
		$this->salida .= "			        <td class=\"".$this->SetStyle("Diagnostico")."\">DIAGNOSTICO: </td>";
		$this->salida.= "<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"$codigo\">";
		$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"cargo\" READONLY>$cargo</textarea></td>";
		$this->salida .= "			        <td><input type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=abrirVentana() class=\"input-submit\"></td>";
		$this->salida .= "			     </tr>";*/
		$this->salida .= "			     <tr>";
		$this->salida .= "			        <td class=\"".$this->SetStyle("Observacion")."\">OBSERVACIONES: </td>";
		$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"Observacion\">$observacion</textarea></td>";
		$this->salida .= "			     </tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "		 <table width=\"50%\" border=\"0\" align=\"center\">";
		$this->salida .= "				       <tr>";
		$actionM=ModuloGetURL('app','CentroAutorizacionSolicitud','user','');
		$this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
		$this->salida .= "				       				</form>";
		$actionM=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Menu');
		$this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
		$this->salida .= "				       				</form>";
		$this->salida .= "				       </tr>";
		$this->salida .= "  </table>";
		$this->salida .= "			     </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*
	*/
	/**
	*
	*/
	function FormaAfiliado($TipoAfiliado,$Nivel,$s)
	{
		$this->salida .= ThemeAbrirTabla('CENTRO IMPRESION - DATOS AFILIADO');
		if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
		{
			$a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
			$_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
			$_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
			$_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
			$this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
			$this->salida .= "	<tr>";
			$this->salida .= "	<td colspan=\"2\">";
			$this->salida .= "			      <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "				       <tr>";
			$this->salida .= "				          <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
			$this->salida .= "				       </tr>";
			$arreglon=ExplodeArrayAssoc($a);
					$i=0;
					foreach($arreglon as $k => $v)
					{
							if($i % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$this->salida .= "				 <tr class=\"$estilo\">";
							$this->salida .= "				    <td align=\"center\">$k</td>";
							$this->salida .= "				    <td align=\"center\">$v</td>";
							$this->salida .= "			  </tr>";
							$i++;
					}
					$this->salida .= "			     </table>";
					$this->salida .= "				       </td>";
					$this->salida .= "				       </tr>";
					$this->salida .= "			     </table><BR>";
			}
			$this->salida .= "			      <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "				       <tr>";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "				       </tr>";
			$this->salida .= "			     </table>";
			//otros datos de la bd
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
			{
						$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "		      <tr>";
						$this->salida .= "				    <td  width=\"10%\" class=\"label\">EMPLEADOR: </td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']."</td>";
						$this->salida .= "	  	      <td></td>";
						$this->salida .= "				     <td width=\"7%\" class=\"label\">EDAD: </td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_edad']."</td>";
						$this->salida .= "	  	      <td></td>";
						$this->salida .= "	  	      <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">ESTADO: </td>";
						if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='SUSPENDIDO'
						OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='INACTIVO'
						OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='URGENCIAS'
						OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='PROTECCION')
						{  $x='label_error';  }
						else
						{  $x='label';  }
						$this->salida .= "	  	      <td align=\"left\" width=\"10%\" class=\"$x\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']."</td>";
						$this->salida .= "	  	      <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">URGENCIAS: </td>";
						if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']==1)
						{  $ur='MES URG'; }
						$this->salida .= "	  	      <td align=\"left\" width=\"10%\">".$ur."</td>";
						$this->salida .= "		      </tr>";
						$this->salida .= "		      <tr>";
						$this->salida .= "				    <td  width=\"10%\" class=\"label\">RADICACION BD: </td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_radicacion']."</td>";
						$this->salida .= "	  	      <td></td>";
						$this->salida .= "				     <td width=\"7%\" class=\"label\">VENCIMIENTO BD: </td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_vencimiento']."</td>";
						$this->salida .= "	  	      <td></td>";
						$this->salida .= "	  	      <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"10%\"></td>";
						$this->salida .= "	  	      <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
						$this->salida .= "	  	      <td align=\"left\" width=\"10%\"></td>";
						$this->salida .= "		      </tr>";
						$this->salida .= "			 </table>";
			}
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GuardarAfiliado');
			$this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "  <br>  <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
			$tipo_afiliado=$this->Tipo_Afiliado($_SESSION['SOLICITUD']['PACIENTE']['plan_id']);
			if($_SESSION['SOLICITUD']['PACIENTE']['ESTADO_AFILIADO'])
			{
				$this->salida .= "          <tr>";
				$this->salida .= "               <td colspan=\"6\" align=\"center\" class=\"label_error\">ESTADO AFILIADO: ".$_SESSION['SOLICITUD']['PACIENTE']['ESTADO_AFILIADO']."</td>";
				$this->salida .= "          </tr>";
			}
			$this->salida .= "		      <tr>";
			if(sizeof($tipo_afiliado)>1)
			{
					$this->salida .= "				       <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
					$this->BuscarIdTipoAfiliado($tipo_afiliado,$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']);
					$this->salida .= "              </select></td>";
			}
			else
			{
					$this->salida .= "				    <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
					$NomAfi=$this->NombreAfiliado($TipoAfiliado);
					$this->salida .= "	  	      <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$NomAfi[tipo_afiliado_id]."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
					$this->salida .= "	  	      <td></td>";
			}
			$niveles=$this->Niveles($_SESSION['SOLICITUD']['PACIENTE']['plan_id']);
			if(sizeof($niveles)>1)
			{
				$this->salida .= "				       <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
				$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0; $i<sizeof($niveles); $i++)
				{
						if($niveles[$i][rango]==$_SESSION['SOLICITUD']['PACIENTE']['RANGO']){
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
			$this->salida .= "	  	      <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
			$this->salida .= "	  	      <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS']."\" ></td>";
			$this->salida .= "		      </tr>";
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
			{
					$this->salida .= "		      <tr>";
					$this->salida .= "	  	      <td colspan=\"6\" align=\"center\" class=\"label\">OBSERVACION: &nbsp;&nbsp;<textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td>";
					$this->salida .= "		      </tr>";
			}
			$this->salida .= "			 </table><br>";
			$this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
			$this->salida .= "	<tr>";
			//$this->salida .= "	<td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";
			//$this->salida .= "  </form>";

			$this->salida .= "	<td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";			$this->salida .= "      </form>";
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Menu');
			$this->salida .= "	<form name=\"forma2\" action=\"$accion\" method=\"post\">";
			$this->salida .= "	<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
			$this->salida .= "      </form>";
			$this->salida .= "	</tr>";
			$this->salida .= "	</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
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
					if($tipo_afiliado[$i][tipo_afiliado_id]==$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]){
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
					else{
					 $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
					}
				}
	}


	/**
	*
	*/
	function DatosCompletos()
	{
				if(empty($_SESSION['SOLICITUD']['PACIENTE']['nombre']))
				{
						$nom=$this->NombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
						$_SESSION['SOLICITUD']['PACIENTE']['nombre']=$nom['nombre'];
				}
				$this->salida .= "		 <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['nombre']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"5%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['plan_descripcion']."</td>";
				$this->salida .= "			</tr>";
				
				$InfoAfiliado=$this->InformacionAfiliado($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'],$_SESSION['SOLICITUD']['PACIENTE']['plan_id']);
				if($InfoAfiliado[plan_id])
				{
					$this->salida .= "      <tr><td colspan=\"6\" class=\"modulo_table_title\">Informacion Afiliado</td></tr>";
					$this->salida .= "      <tr>";
					$this->salida .= "        <td class=\"modulo_table_title\" width=\"12%\">Tipo Afiliado: </td>";
					$this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">".$InfoAfiliado[tipo_afiliado_nombre]."</td>";
					$this->salida .= "        <td class=\"modulo_table_title\" width=\"11%\">Rango: </td>";
					$this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">".$InfoAfiliado[rango]."</td>";
					$this->salida .= "      </tr>";
					$this->salida .= "      <tr>";
					$this->salida .= "        <td class=\"modulo_table_title\" width=\"11%\">Punto Atencion: </td>";
					$this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"5\">".$InfoAfiliado[eps_punto_atencion_nombre]."</td>";
					$this->salida .= "      </tr>";
				}
				
				
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">ENTIDAD: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">MEDICO:</td><td  class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" >FECHA:</td><td  class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['DATOS']['FECHA']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">OBSERVACION: </td><td class=\"modulo_list_claro\" colspan=\"5\">".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= " 			</table><BR>";
	}

	/**
	*
	*/
	function FormaTiposCargos()
	{
				$this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL');
				$this->DatosCompletos();
				$this->ListadoTodos();
				$this->salida .= "			     <br><table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">TIPOS SOLICITUDES</td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionC=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Qx');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionC\">PROCEDIMENTOS QUIRURGICOS</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionA=ModuloGetURL('app','CentroAutorizacionSolicitud','user','NoQx');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\">PROCEDIMIENTOS NO QX</a></td>";
				$this->salida .= "				       </tr>";				
				$this->salida .= "				       <tr>";
				$accionA=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Apoyos');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\">APOYOS DIAGNOSTICOS</a></td>";
				$this->salida .= "				       </tr>";
				$this->salida .= "				       <tr>";
				$accionA=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Inter');
				$this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\">INTERCONSULTA</a></td>";
				$this->salida .= "				       </tr>";				
				$this->salida .= "			     </table>";
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"50%\">";
				$this->salida.="<tr>";
				$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Malla');
				//$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Menu',array('terminar'=>true));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<td align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"TERMINAR SOLICITUD\"></form></td>";
				$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Cancelar');
				$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<td align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"CANCELAR\"></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
      	$this->salida .= ThemeCerrarTabla();
				return true;
	}
//-----------------------------PANTALLA CON LAS ORDENES Y SOLICITUDES----------------

	/**
	*
	*/
	function FormaFinSolicitud($datos='',$control='')
	{
			if(!empty($datos))
			{
				if($control==3)
				{
					$RUTA = $_ROOT ."cache/ordenservicio.pdf";
				}
				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentana(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$mostrar.="</script>\n";
				$this->salida.="$mostrar";
				$this->salida.="<BODY onload=abreVentana();>";
			}

			$this->salida .= ThemeAbrirTabla('DETALLE SOLICITUDES');
			$_SESSION['SOLICITUD']['EMPRESA']=$this->BuscarEmpresa($_SESSION['SOLICITUD']['PACIENTE']['plan_id']);
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	</table>";
			$this->salida .= "		 <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
			$this->salida .= "			<tr align=\"center\">";
			$this->salida .= "				<td colspan=\"8\" align=\"center\">";
			$this->salida .= "		 <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
			$this->salida .= "			</tr>";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."</td>";
 			$nombre=$this->NombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">".$nombre[nombre]."</td>";
			//$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">INGRESO:</td><td width=\"60%\" class=\"modulo_list_claro\">".$arr[0][ingreso]."</td>";
			$this->salida .= "			</tr>";
			$this->salida .= " 			</table>";
			$this->salida .= "			</td>";			
			$this->salida .= "			</tr>";		
			$this->salida .= " 			</table><br>";	
			if(!empty($_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']))
			{  $this->ListadoSolicitudes('FormaFinSolicitud');  }
			if(!empty($_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']))
			{  $this->ListadoOsAuto('FormaFinSolicitud');  }
			$this->salida .= "		 <table width=\"50%\" border=\"0\" align=\"center\">";
			$this->salida .= "				       <tr>";
			$actionM=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Menu');
			$this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
			$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td>";
			$this->salida .= "				       				</form>";
			$this->salida .= "				       </tr>";
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

	/**
	*
	*/
	function ListadoSolicitudes()
	{
			$arr=$_SESSION['SOLICITUD']['ARREGLO']['DETALLE2'];
			for($i=0; $i<sizeof($arr);)
			{
					$f=0;
					$d=$i;
      		$this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";					
					$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','PedirAutorizacion',array('ingreso'=>$arr[0][ingreso]));
					$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					if($arr[$i][servicio]==$arr[$d][servicio])
					{
									$this->salida .= "			<tr><td colspan=\"8\"><br></td></tr>";
									$this->salida .= "			<tr><td colspan=\"8\" class=\"modulo_table_list_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
									$this->salida .= "			<tr>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">".$arr[$i][desserv]."</td>";
									$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
									$this->salida .= "				<td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$arr[$i][despto]."</td>";
									$this->salida .= "			</tr>";
									$this->salida .= "			<tr class=\"modulo_table_list_title\">";
									$this->salida .= "				<td>FECHA</td>";
									$this->salida .= "				<td width=\"10%\">SOLICITUD</td>";
									$this->salida .= "				<td width=\"10%\">CARGO</td>";
									$this->salida .= "				<td colspan=\"2\" width=\"40%\">DESCRIPCION</td>";
									$this->salida .= "				<td width=\"7%\">CANTIDAD</td>";
									$this->salida .= "				<td width=\"10%\">TIPO</td>";
									$this->salida .= "				<td width=\"10%\"></td>";
									$this->salida .= "			</tr>";
					}
					while($arr[$i][servicio]==$arr[$d][servicio])
					{
							if($d % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$this->salida .= "			<tr class=\"$estilo\">";
							$this->salida .= "				<td>".$this->FechaStamp($arr[$i][fecha])." ".$this->HoraStamp($arr[$i][fecha])."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][hc_os_solicitud_id]."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][cargos]."</td>";
							$this->salida .= "				<td colspan=\"2\">".$arr[$d][descripcion]."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][cantidad]."</td>";
							$this->salida .= "				<td align=\"center\">".$arr[$d][desos]."</td>";
							$equi=$this->ValidarEquivalencias($arr[$d][cargos]);
							$cont=$this->ValidarContrato($arr[$d][cargos],$arr[$d][plan_id]);
							if( $arr[$d][nivel_autorizador_id]<$arr[$d][x])
							{		$this->salida .= "				<td align=\"center\" width=\"7%\">Necesita Nivel ".$arr[$d][x]."</td>";  }
							//elseif($equi>=1 AND $equi==$cont
							elseif($equi >= 1 AND $cont > 0
									AND $arr[$d][nivel_autorizador_id]>=$arr[$d][nivel])
							{
										$s='';
										$de=$this->ComboDepartamento($arr[$d][cargos],$arr[$d][hc_os_solicitud_id]);
										if(empty($de))
										{
												$p=$this->ComboProveedor($arr[$d][cargos]);
												if(empty($p))
												{ $s='NO PROVEEDOR <BR>';  }
										}
									/*	if(empty($arr[$d][departamento])
											AND empty($arr[$d][tipo_id_tercero]))
										{  $s='NO PROVEEDOR <BR>';  }*/
										$this->salida .= "				<td align=\"center\" class=\"label_error\">$s<input type=\"checkbox\" value=\"".$arr[$d][cargos].",".$arr[$d][tarifario_id].",".$arr[$d][ingreso].",".$arr[$d][servicio].",".$arr[$d][hc_os_solicitud_id]."\" name=\"Auto".$arr[$d][hc_os_solicitud_id]."\"></td>";
										$f++;
							}
							elseif($equi==0)
							{
									$this->salida .= "				<td align=\"center\" class=\"label_error\" width=\"7%\">NO TIENE EQUIVALENCIAS</td>";
							}
							//elseif($cont!=$equi)
							elseif($cont==0)
							{
									$this->salida .= "				<td align=\"center\" class=\"label_error\" width=\"7%\">NO ESTA CONTRATADO</td>";
							}
							$this->salida .= "			</tr>";
							$d++;
					}
					$i=$d;
					if($f == 0)
					{
							$this->salida .= "			<tr class=\"$estilo\">";
							$this->salida .= "				<td class=\"label_error\" align=\"center\" colspan=\"8\">NINGUN CARGO PUEDE SER AUTORIZADO</td>";
							$this->salida .= "			</tr>";
					}
					if($f > 0)
					{
							$this->salida .= "			<tr class=\"$estilo\">";
							$this->salida .= "				<td align=\"right\" colspan=\"8\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"AUTORIZAR\"></td>";
							$this->salida .= "			</tr>";
					}
					$this->salida .= "				       				</form>";
			}
			$this->salida .= "				       				</table>";
	}


	/**
	*
	*/
	function ListadoOsAuto($regreso)
	{ 
			$var=$_SESSION['SOLICITUD']['ARREGLO']['DETALLE1'];
			$reporte= new GetReports();
			if(!empty($var))
			{
					$this->salida .= ThemeAbrirTabla('ORDENES SERVICIO AUTORIZADAS',850);
					for($i=0; $i<sizeof($var);)
					{
								$d=$i;
								$this->salida .= "	<table width=\"95%\" border=\"1\" align=\"center\" >";
								$this->salida .= "			<tr class=\"modulo_table_title\">";
								$this->salida .= "				<td colspan=\"5\" align=\"left\">NUMERO DE ORDEN ".$var[$i][orden_servicio_id]."</td>";
								$this->salida .= "			</tr>";
								$this->salida .= "			<tr>";
								$this->salida .= "				<td colspan=\"5\" class=\"modulo_list_claro\">";
								$this->salida .= "						<table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][tipo_afiliado_nombre]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">RANGO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][rango]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][semanas_cotizadas]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][desserv]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_int]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_ext]."</td>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
								$this->salida .= "										<td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizador]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">PLAN: </td>";
								$this->salida .= "										<td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">".$var[$d][plan_descripcion]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= "								<tr>";
								$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIONES: </td>";
								$this->salida .= "										<td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">".$var[$d][observacion]."</td>";
								$this->salida .= "								</tr>";
								$this->salida .= " 						</table>";
								$this->salida .= "				</td>";
								$this->salida .= "			</tr>";
								while($var[$i][orden_servicio_id]==$var[$d][orden_servicio_id])
								{
										$this->salida .= "			<tr>";
										$this->salida .= "				<td colspan=\"5\">";
										$this->salida .= "				<table width=\"99%\" border=\"0\" align=\"center\">";
										$this->salida .= "			<tr class=\"modulo_table_list_title\">";
										$this->salida .= "				<td width=\"6%\">ITEM</td>";
										$this->salida .= "				<td width=\"6%\">CANT.</td>";
										$this->salida .= "				<td width=\"10%\">CARGO</td>";
										$this->salida .= "				<td width=\"45%\">DESCRICPION</td>";
										$this->salida .= "				<td width=\"20%\">PROVEEDOR</td>";
										$this->salida .= "			</tr>";
										if($d % 2) {  $estilo="modulo_list_claro";  }
										else {  $estilo="modulo_list_oscuro";   }
										$this->salida .= "			<tr class=\"$estilo\">";
										$this->salida .= "				<td align=\"center\">".$var[$d][numero_orden_id]."</td>";
										$this->salida .= "				<td align=\"center\">".$var[$d][cantidad]."</td>";
										if(!empty($var[$d][cargo])){  $cargo=$var[$d][cargo];  }
										else {  $cargo=$var[$d][cargoext];   }
										$this->salida .= "				<td align=\"center\">".$cargo."</td>";
										$this->salida .= "				<td>".$var[$d][descripcion]."</td>";
										$p='';
										if(!empty($var[$d][departamento]))
										{  $p='DPTO. '.$var[$d][desdpto];  $id=$var[$d][departamento]; }
										else
										{  $p=$var[$d][planpro];  $id=$var[$d][plan_proveedor_id];}
										$this->salida .= "				<td align=\"center\">".$p."</td>";
										$this->salida .= "			</tr>";
										$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
										$this->salida .= "				<td colspan=\"5\">";
										$this->salida .= "						<table width=\"100%\" border=\"0\" align=\"center\">";
										$this->salida .= "								<tr class=\"modulo_list_claro\">";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
										$this->salida .= "										<td width=\"5%\" colspan=\"2\">".$this->FechaStamp($var[$d][fecha_activacion])."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">VENC.: </td>";
										$x='';
										if(date("Y-m-d") > $var[$d][fecha_vencimiento]) $x='VENCIDA';
										$this->salida .= "										<td width=\"5%\" >".$this->FechaStamp($var[$d][fecha_vencimiento])."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"label_error\" align=\"center\">".$x."</td>";
										$this->salida .= "										<td width=\"5%\" class=\"modulo_table_list_title\">REFRENDAR HASTA: </td>";
										$this->salida .= "										<td width=\"5%\">".$this->FechaStamp($var[$d][fecha_refrendar])."</td>";
										$this->salida .= "								</tr>";
										$this->salida .= " 						</table>";
										$this->salida .= "		<table width=\"100%\" border=\"0\" align=\"center\">";
										$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
										$this->salida .= "										<td width=\"7%\" class=\"modulo_table_list_title\">ESTADO: </td>";
										$this->salida .= "										<td width=\"9%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">".$var[$d][estado]."</td>";
										$this->salida .= "				<td width=\"20%\"></td>";
										$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id],'pos'=>1));
										if($x!='VENCIDA' AND ($var[$d][estado]=='PAGADO' OR $var[$d][estado]=='ACTIVO' OR $var[$d][estado]=='TRASCRIPCION'))
										{
												$this->salida .= "				<td width=\"9%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
												$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id],'pos'=>0));
												$mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','ordenservicioHTM',array('orden'=>$var[$d][orden_servicio_id]),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
												$funcion=$reporte->GetJavaFunction();
												$this->salida .=$mostrar;
												$this->salida.="  				 <td align=\"center\" width=\"9%\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
												$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id],'pos'=>0));
												$this->salida .= "                <td  align=\"center\" width=\"12%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td>";
										}
										else
										{ 	$this->salida .= "				<td width=\"10%\"></td>";  }
										$this->salida .= "			</tr>";
										$this->salida .= " 			</table>";
										$this->salida .= "				</td>";
										$this->salida .= "			</tr>";
										$this->salida .= " 			</table>";
										$this->salida .= "				</td>";
										$this->salida .= "			</tr>";
										$d++;
								}
								$i=$d;
								$this->salida .= " 			</table><br>";
					}//fin for
					unset($reporte);
					$this->salida .= ThemeCerrarTabla();
			}
	}

//----------------------------APOYOS-------------------------------------------------
	function frmForma()
	{
			$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE APOYOS DIAGNOSTICOS MANUALES');
			$this->DatosCompletos();
			//$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
			//$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
		  //$vector1=$this->Consulta_Solicitud_Apoyod();
			if(!empty($_SESSION['ARREGLO']['DATOS']['APOYOS']))
			//if($vector1)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">APOYOS DIAGNOSTICOS SOLICITADOS MANUALES</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">TIPO</td>";
					$this->salida.="  <td width=\"9%\">CARGO</td>";
					$this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
					$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
					$this->salida.="</tr>";
					foreach($_SESSION['ARREGLO']['DATOS']['APOYOS'] as $k => $v)
					//for($i=0;$i<sizeof($vector1);$i++)
					{
								$vector1=$this->Consulta_Solicitud_Apoyod($k);
								$hc_os_solicitud_id =$vector1[hc_os_solicitud_id];
								$tipo=$vector1[tipo];
								$cargo=$vector1[cargo];
								$descripcion= $vector1[descripcion];
								$observacion= $vector1[observacion];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">$tipo</td>";
								$this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
								$this->salida.="  <td align=\"left\" width=\"52%\">$descripcion</td>";
								$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'observacion','hc_os_solicitud_idapoyo' => $hc_os_solicitud_id, 'cargoapoyo'=>$cargo, 'descripcionapoyo' => $descripcion, 'observacionapoyo'=> $observacion));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
								$accion2=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'eliminar', 'hc_os_solicitud_idapoyo'=> $hc_os_solicitud_id));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$observacion</td>";
								$this->salida.="</tr>";
								$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">";
								$this->salida.="<table>";
								for($j=0;$j<sizeof($diag);$j++)
								{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
										$this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"modulo_table_title\">";
    						$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
								$this->salida.="</tr>";
						}
						$this->salida.="</table><br>";
				}
					$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
					'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
					'cargoapoyo'=>$_REQUEST['cargoapoyo'],
					'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));
					$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE APOYOS DIAGNOSTICOS - BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"5%\">TIPO</td>";
					$this->salida.="<td width=\"10%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					if (($_REQUEST['criterio1'])  == '002')
						{
							$this->salida.="<option value = '002' selected>Frecuentes</option>";
						}
					else
						{
							$this->salida.="<option value = '002' >Frecuentes</option>";
						}

							$categoria = $this->tipos();
							for($i=0;$i<sizeof($categoria);$i++)
							{
								$apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
								$opcion = $categoria[$i][descripcion];

								if (($_REQUEST['criterio1'])  != $apoyod_tipo_id)
									{

										$this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
									}
								else
									{
										$this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
									}
								}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="<td width=\"6%\">CARGO:</td>";
					$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="</form>";
					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaTiposCargos');
					$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></p>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
	}


 function frmForma_Seleccion_Apoyos($vectorA)
 {
		  $this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
			$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
			'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
			'cargoapoyo'=>$_REQUEST['cargoapoyo'],
			'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));
			$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="<td width=\"5%\">TIPO</td>";
							$this->salida.="<td width=\"10%\" align = left >";
					    $this->salida.="<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
									$this->salida.="<option value = '001' selected>Todos</option>";
									if (($_REQUEST['criterio1apoyo'])  == '002')
									  {
									    $this->salida.="<option value = '002' selected>Frecuentes</option>";
										}
									else
									  {
                      $this->salida.="<option value = '002' >Frecuentes</option>";
										}
									$categoria = $this->tipos();
									for($i=0;$i<sizeof($categoria);$i++)
									{
                    $apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
										$opcion = $categoria[$i][descripcion];

										if (($_REQUEST['criterio1'])  != $apoyod_tipo_id)
										   {

												$this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
											 }
										else
                       {
											  $this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
											 }
										}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="<td width=\"6%\">CARGO:</td>";
					$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";

				 $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'insertar_varias'));
				 $this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
        if ($vectorA)
          {
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="  <td width=\"15%\">TIPO</td>";
								$this->salida.="  <td width=\"10%\">CARGO</td>";
								$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
								$this->salida.="  <td width=\"5%\">OPCION</td>";
								$this->salida.="</tr>";
              for($i=0;$i<sizeof($vectorA);$i++)
						   {
                  $apoyod_tipo_id = $vectorA[$i][apoyod_tipo_id];
									$tipo           = $vectorA[$i][tipo];
									$cargo          = $vectorA[$i][cargo];
									$descripcion    = $vectorA[$i][descripcion];

									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"center\" width=\"15%\">$tipo</td>";
									$this->salida.="  <td align=\"center\" width=\"10%\">$cargo</td>";
									$this->salida.="  <td align=\"left\" width=\"50%\">$descripcion</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opapoyo[$i]' value = ".$cargo.",".$apoyod_tipo_id."></td>";
									$this->salida.="</tr>";
								}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
						$this->salida.="</tr>";
					 $this->salida.="</table><br>";
					 /*//OJO CON ESTO FALLABA PORQUE PREGUNTABA SI THIS->conteo = 0 ANALIZAR LO CAMBIE A 1 Y FUNCIONO
					 if ($this->conteo == 1)
                {
								  $this->Insertar_Solicitud($cargo,$apoyod_tipo_id);
								}*/
             $var=$this->RetornarBarraExamenes_Avanzada();
							if(!empty($var))
								{
									$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
									$this->salida .= "  <tr>";
									$this->salida .= "  <td width=\"100%\" align=\"center\">";
									$this->salida .=$var;
									$this->salida .= "  </td>";
									$this->salida .= "  </tr>";
									$this->salida .= "  </table><br>";
								}
					}
      $this->salida .= "</form>";
  		//BOTON DEVOLVER
			$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Apoyos');
			$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
 }

	/**
	*
	*/
 	function RetornarBarraExamenes_Avanzada()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1apoyo'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','conteoapoyo'=>$this->conteo,'paso1apoyo'=>$_REQUEST['paso1apoyo'],
		'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
		'cargoapoyo'=>$_REQUEST['cargoapoyo'],
		'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	/**
	*
	*/
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	/**
	*
	*/
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	/**
	*
	*/
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


		/**
		*
		*/
		function frmForma_Modificar_Observacion($hc_os_solicitud_id, $cargo, $descripcion, $observacion, $vectorD)
		{
				$this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
				$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'modificar','hc_os_solicitud_idapoyo'=>$hc_os_solicitud_id));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"2\">OBSERVACION</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"15%\">CARGO</td>";
				$this->salida.="  <td width=\"65%\">DESCRIPCION</td>";
				$this->salida.="</tr>";
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">$cargo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
				$this->salida .="<td width=\"65%\" align='center'><textarea class='textarea' name = 'obsapoyo' cols = 100 rows = 3>$observacion</textarea></td>" ;
				$this->salida.="</tr>";
				$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
				if ($diag)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
					$this->salida.="<td width=\"65%\">";
					$this->salida.="<table>";
					for($i=0;$i<sizeof($diag);$i++)
						{
							$this->salida.="<tr class=\"$estilo\">";
							$accionE=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'eliminar_diagnostico', 'hc_os_solicitud_idapoyo'=> $hc_os_solicitud_id, 'codigoapoyo'=> $diag[$i][diagnostico_id],
							'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
							'cargoapoyo'=>$_REQUEST['cargoapoyo'],
							'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
							'observacionapoyo'=>$_REQUEST['observacionapoyo']));
							$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
							$this->salida.="<tr>";
						}
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "</form>";
				$accionD=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada_Diagnosticos',
				'Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
				'codigoapoyo'=>$_REQUEST['codigoapoyo'],
				'diagnosticoapoyo'=>$_REQUEST['diagnosticoapoyo'],
				'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
				'cargoapoyo'=>$_REQUEST['cargoapoyo'],
				'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
				'observacionapoyo'=>$_REQUEST['observacionapoyo']));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accionD\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"4%\">CODIGO:</td>";
				$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoapoyo'></td>" ;
				$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoapoyo'   value =\"".$_REQUEST['diagnostico']."\"        ></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";
				$accionI=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'insertar_varios_diagnosticos',
				'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
				'cargoapoyo'=>$_REQUEST['cargoapoyo'],
				'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
				'observacionapoyo'=>$_REQUEST['observacionapoyo']));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accionI\" method=\"post\">";
				if ($vectorD)
					{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"10%\">CODIGO</td>";
							$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($vectorD);$i++)
							{
									$codigo          = $vectorD[$i][diagnostico_id];
									$diagnostico    = $vectorD[$i][diagnostico_nombre];

									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";

									$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
									$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opDapoyo[$i]' value = ".$hc_os_solicitud_id.",".$codigo."></td>";
									$this->salida.="</tr>";

								}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
							$this->salida.="</tr>";
							$this->salida.="</table><br>";
							$var=$this->RetornarBarraDiagnosticos_Avanzada();
								if(!empty($var))
									{
										$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
										$this->salida .= "  <tr>";
										$this->salida .= "  <td width=\"100%\" align=\"center\">";
										$this->salida .=$var;
										$this->salida .= "  </td>";
										$this->salida .= "  </tr>";
										$this->salida .= "  </table><br>";
									}
							}
					$this->salida .= "</form>";
					//BOTON DEVOLVER
					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Apoyos');
					$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
		}

	/**
	*
	*/
 	function RetornarBarraDiagnosticos_Avanzada()
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1apoyo'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada_Diagnosticos','conteoapoyo'=>$this->conteo,'paso1apoyo'=>$_REQUEST['paso1apoyo'],
		'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
		'cargoapoyo'=>$_REQUEST['cargoapoyo'],
		'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

//------------------------------------------interconsulta-----------------

		/**
		*
		*/
		function frmFormaInter()
		{
					$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE INTERCONSULTAS MANUALES');
					//$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
					//$this->salida .= "<form name=\"formadesinter\" action=\"$accion\" method=\"post\">";
					$this->DatosCompletos();
					//$vector1=$this->Consulta_Solicitud_Interconsulta();
					if(!empty($_SESSION['ARREGLO']['DATOS']['INTER']))
					//if($vector1)
					{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$this->salida.="</table>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"6\">INTERCONSULTAS MANUALES</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"7%\">CARGO</td>";
							$this->salida.="  <td width=\"9%\">CODIGO DE ESPECIALIDAD</td>";
							$this->salida.="  <td width=\"45%\">ESPECIALIDAD</td>";
							$this->salida.="  <td width=\"6%\">CANTIDAD SOLICITADA</td>";
							$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
							$this->salida.="</tr>";
							foreach($_SESSION['ARREGLO']['DATOS']['INTER'] as $k => $v)
							//for($i=0;$i<sizeof($vector1);$i++)
							{
									$vector1=$this->Consulta_Solicitud_Interconsulta($k);
									$observacion= $vector1[observacion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[cargo]."</td>";
									$this->salida.="  <td align=\"center\" width=\"9%\">".$vector1[especialidad]."</td>";
									$this->salida.="  <td align=\"left\" width=\"52%\">".$vector1[descripcion]."</td>";
									$this->salida.="  <td align=\"center\" width=\"6%\">".$vector1[cantidad]."</td>";
									$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'observacion','hc_os_solicitud_idinter'=> $vector1[hc_os_solicitud_id], 'codigo_espinter'=>$vector1[especialidad], 'descripcioninter'=> $vector1[descripcion], 'observacioninter'=> $vector1[observacion],'sw_cantidadinter' => $vector1[sw_cantidad],'cantidadinter' => $vector1[cantidad]));
									$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
									$accion2=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'eliminar', 'hc_os_solicitud_idinter' => $vector1[hc_os_solicitud_id]));
									$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Observacion</td>";
									$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$vector1[observacion]."</td>";
									$this->salida.="</tr>";
									$diag =$this->Diagnosticos_Solicitados($vector1[hc_os_solicitud_id]);
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
									$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">";
									$this->salida.="<table>";
									for($j=0;$j<sizeof($diag);$j++)
									{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
											$this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
											$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
									$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
									$this->salida.="</tr>";
							}
							$this->salida.="</table><br>";
				}
				//$this->salida .= "</form>";
				//lo que inserte
				$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'Busqueda_Avanzada_Especialidad',
				'Ofinter'=>$_REQUEST['Ofinter'],'paso1'=>$_REQUEST['paso1inter'],
				'criterio1inter'=>$_REQUEST['criterio1inter'],
				'codigo_espinter'=>$_REQUEST['codigo_espinter'],
				'especialidadinter'=>$_REQUEST['especialidadinter']));
				$this->salida .= "<form name=\"formadesinter\" action=\"$accion1\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE INTERCONSULTAS - BUSQUEDA AVANZADA </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio1inter'  class =\"select\">";
				$this->salida.="<option value = '001' selected>Todos</option>";
				/*if (($_REQUEST['criterio1inter'])  == '002')
				{
						$this->salida.="<option value = '002' selected>Frecuentes</option>";
				}
				else
				{
						$this->salida.="<option value = '002' >Frecuentes</option>";
				}*/
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"7%\">CODIGO:</td>";
				$this->salida .="<td width=\"15%\" align='center'><input type='text' class='input-text'  size = 10 name = 'codigo_espinter'  value =\"".$_REQUEST['codigo_espinter']."\"    ></td>" ;
				$this->salida.="<td width=\"8%\">ESPECIALIDAD:</td>";
				$this->salida .="<td width=\"23%\" align='center' ><input type='text' class='input-text' size = 35 name = 'especialidadinter'   value =\"".$_REQUEST['especialidadinter']."\"        ></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarinter\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";
				$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaTiposCargos');
				$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></p>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
		}


 function frmForma_Seleccion_Especialidades($vectorE)
 {
		  $this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE INTERCONSULTAS');
			$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'Busqueda_Avanzada_Especialidad',
			'Ofinter'=>$_REQUEST['Ofinter'],'paso1inter'=>$_REQUEST['paso1inter'],
			'criterio1inter'=>$_REQUEST['criterio1inter'],
			'codigo_espinter'=>$_REQUEST['codigo_espinter'],
			'especialidadinter'=>$_REQUEST['especialidadinter']));
			$this->salida .= "<form name=\"formadesinter\" action=\"$accion1\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"5%\">TIPO</td>";
			$this->salida.="<td width=\"10%\" align = left >";
			$this->salida.="<select size = 1 name = 'criterio1inter'  class =\"select\">";
			$this->salida.="<option value = '001' selected>Todos</option>";
			if (($_REQUEST['criterio1'])  == '002')
			{
					$this->salida.="<option value = '002' selected>Frecuentes</option>";
			}
			else
			{
					$this->salida.="<option value = '002' >Frecuentes</option>";
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			$this->salida.="<td width=\"7%\">CODIGO:</td>";
			$this->salida .="<td width=\"15%\" align='center'><input type='text' class='input-text'  size = 10 name = 'codigo_espinter'  value =\"".$_REQUEST['codigo_espinter']."\"    ></td>" ;
			$this->salida.="<td width=\"8%\">ESPECIALIDAD:</td>";
			$this->salida .="<td width=\"23%\" align='center' ><input type='text' class='input-text' size = 35 name = 'especialidadinter'   value =\"".$_REQUEST['especialidadinter']."\"        ></td>" ;
			$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarinter\" type=\"submit\" value=\"BUSCAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.="</form>";
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'insertar_varias_especialidades'));
			$this->salida .= "<form name=\"formadesinter\" action=\"$accion\" method=\"post\">";
      if ($vectorE)
      {
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"10%\">CODIGO</td>";
					$this->salida.="  <td width=\"55%\">ESPECIALIDAD</td>";
					$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
					$this->salida.="  <td width=\"10%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorE);$i++)
					{
							$codigo_esp     = $vectorE[$i][especialidad];
							$especialidad   = $vectorE[$i][descripcion];
							$cargo          = $vectorE[$i][cargo];

							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"center\" width=\"10%\">$codigo_esp</td>";
							$this->salida.="  <td align=\"left\" width=\"60%\">$especialidad</td>";
							if ($vectorE[$i][sw_cantidad]== 1)
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' readonly class='input-text'  size = 5 maxlength = 3 name = 'cantidadinter$codigo_esp'  value =\"".$vectorE[$i][sw_cantidad]."\"></td>" ;
							}
							else
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' class='input-text'  size = 5 maxlength = 3 name = 'cantidadinter$codigo_esp'  value = ''></td>" ;
							}
							$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= 'opEinter[$i]' value = ".$cargo.",".$codigo_esp.",".$vectorE[$i][tipo_consulta_id]."></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardarinter\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$var=$this->RetornarBarraEspecialidades_Avanzada();
					if(!empty($var))
					{
							$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
							$this->salida .= "  <tr>";
							$this->salida .= "  <td width=\"100%\" align=\"center\">";
							$this->salida .=$var;
							$this->salida .= "  </td>";
							$this->salida .= "  </tr>";
							$this->salida .= "  </table><br>";
					}
			}
      $this->salida .= "</form>";
			$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Inter');
			$this->salida .= "<form name=\"formainter\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverinter\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
 }

	function RetornarBarraEspecialidades_Avanzada()
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1inter'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'Busqueda_Avanzada_Especialidad',
		'conteointer'=>$this->conteo,'paso1inter'=>$_REQUEST['paso1inter'],
		'criterio1inter'=>$_REQUEST['criterio1inter'],
		'codigo_espinter'=>$_REQUEST['codigo_espinter'],
		'especialidadinter'=>$_REQUEST['especialidadinter']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";

    if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset(1)."&paso1inter=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso-1)."&paso1inter=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($i)."&paso1inter=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso+1)."&paso1inter=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($numpasos)."&paso1inter=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($i)."&paso1inter=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso+1)."&paso1inter=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($numpasos)."&paso1inter=$numpasos'>&gt;</a></td>";
				$colspan++;
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

		/**
		*
		*/
		function frmForma_Modificar_ObservacionInter($hc_os_solicitud_id, $codigo_esp, $descripcion, $observacion, $sw_cantidad, $cantidad, $vectorD)
		{
				$this->salida= ThemeAbrirTablaSubModulo('DATOS DE LA SOLICITUD DE INTERCONSULTA');
				$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'modificar','hc_os_solicitud_idinter'=>$hc_os_solicitud_id));
				$this->salida .= "<form name=\"formadesinter\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"3\">OBSERVACION</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"15%\">CODIGO DE ESPECIALIDAD</td>";
				$this->salida.="  <td width=\"60%\">ESPECIALIDAD</td>";
				$this->salida.="  <td width=\"5%\">CANTIDAD SOLICITADA</td>";
				$this->salida.="</tr>";
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">$codigo_esp</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";
				if ($sw_cantidad == 1)
				{
					$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' readonly class='input-text'  size = 5 maxlength = 3 name = 'cantidadinter'  value =\"".$cantidad."\"></td>" ;
				}
				else
				{
					$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' class='input-text'  size = 5 maxlength = 3 name = 'cantidadinter'  value =\"".$cantidad."\"></td>" ;
				}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
				$this->salida .="<td width=\"65%\" align='center' colspan=\"2\"><textarea class='textarea' name = 'obsinter' cols = 100 rows = 3>$observacion</textarea></td>" ;
				$this->salida.="</tr>";
				$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
				if ($diag)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
					$this->salida.="<td width=\"65%\" colspan=\"2\">";
					$this->salida.="<table>";
					for($i=0;$i<sizeof($diag);$i++)
						{
							$this->salida.="<tr class=\"$estilo\">";
							$accionE=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'eliminar_diagnostico', 'hc_os_solicitud_idinter' => $hc_os_solicitud_id, 'codigointer' => $diag[$i][diagnostico_id],
							'hc_os_solicitud_idinter'=>$_REQUEST['hc_os_solicitud_idinter'],
							'codigo_espinter'=>$_REQUEST['codigo_espinter'],
							'descripcioninter'=>$_REQUEST['descripcioninter'],
							'observacioninter'=>$_REQUEST['observacioninter'],
							'sw_cantidadinter'=>$_REQUEST['sw_cantidadinter'],
							'cantidadinter'=>$_REQUEST['cantidadinter']));
							$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
							$this->salida.="<tr>";
						}
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarinter\" type=\"submit\" value=\"GUARDAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "</form>";
				$accionD=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'Busqueda_Avanzada_Diagnosticos',
				'Ofinter'=>$_REQUEST['Ofinter'],'paso1inter'=>$_REQUEST['paso1inter'],
				'codigointer'=>$_REQUEST['codigointer'],
				'diagnosticointer'=>$_REQUEST['diagnosticointer'],
				'hc_os_solicitud_idinter'=>$_REQUEST['hc_os_solicitud_idinter'],
				'codigo_espinter'=>$_REQUEST['codigo_espinter'],
				'descripcioninter'=>$_REQUEST['descripcioninter'],
				'observacioninter'=>$_REQUEST['observacioninter'],
				'sw_cantidadinter'=>$_REQUEST['sw_cantidadinter'],
				'cantidadinter'=>$_REQUEST['cantidadinter']));
				$this->salida .= "<form name=\"formadesinter\" action=\"$accionD\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"4%\">CODIGO:</td>";
				$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigointer'></td>" ;
				$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticointer'   value =\"".$_REQUEST['diagnosticointer']."\"        ></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarinter\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";
				$accionI=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'insertar_varios_diagnosticos',
				'hc_os_solicitud_idinter'=>$_REQUEST['hc_os_solicitud_idinter'],
				'codigo_espinter'=>$_REQUEST['codigo_espinter'],
				'descripcioninter'=>$_REQUEST['descripcioninter'],
				'observacioninter'=>$_REQUEST['observacioninter'],
				'sw_cantidadinter'=>$_REQUEST['sw_cantidadinter'],
				'cantidadinter'=>$_REQUEST['cantidadinter']));
				$this->salida .= "<form name=\"formadesinter\" action=\"$accionI\" method=\"post\">";
				if ($vectorD)
				{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"10%\">CODIGO</td>";
					$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
					$this->salida.="  <td width=\"5%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorD);$i++)
					{
							$codigo          = $vectorD[$i][diagnostico_id];
							$diagnostico    = $vectorD[$i][diagnostico_nombre];

							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";

							$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
							$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
							$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opDinter[$i]' value = ".$hc_os_solicitud_id.",".$codigo."></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarinter\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$var=$this->RetornarBarraDiagnosticos_AvanzadaInter();
					if(!empty($var))
					{
								$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
								$this->salida .= "  <tr>";
								$this->salida .= "  <td width=\"100%\" align=\"center\">";
								$this->salida .=$var;
								$this->salida .= "  </td>";
								$this->salida .= "  </tr>";
								$this->salida .= "  </table><br>";
					}
				}
				$this->salida .= "</form>";
				$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Inter');
				$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverinter\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
		}


	function RetornarBarraDiagnosticos_AvanzadaInter()//Barra paginadora de los planes clientes
	{
			if($this->limit>=$this->conteo)
			{
				return '';
			}
			$paso=$_REQUEST['paso1inter'];
			if(empty($paso))
			{
				$paso=1;
			}
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaInter',array('accioninter'=>'Busqueda_Avanzada_Diagnosticos',
			'conteointer'=>$this->conteo,'paso1inter'=>$_REQUEST['paso1inter'],
			'codigointer'=>$_REQUEST['codigointer'],
			'diagnosticointer'=>$_REQUEST['diagnosticointer'],
			'hc_os_solicitud_idinter'=>$_REQUEST['hc_os_solicitud_idinter'],
			'codigo_espinter'=>$_REQUEST['codigo_espinter'],
			'descripcioninter'=>$_REQUEST['descripcioninter'],
			'observacioninter'=>$_REQUEST['observacioninter']));

			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;
			$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
			if($paso > 1)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset(1)."&paso1inter=1'>&lt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso-1)."&paso1inter=".($paso-1)."'>&lt;&lt;</a></td>";
				$colspan+=2;
			}
			$barra++;
			if(($barra+10)<=$numpasos)
			{
				for($i=($barra);$i<($barra+10);$i++)
				{
					if($paso==$i)
					{
						$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
					}
					else
					{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($i)."&paso1inter=$i' >$i</a></td>";
					}
					$colspan++;
				}
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso+1)."&paso1inter=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($numpasos)."&paso1inter=$numpasos'>&gt;</a></td>";
				$colspan+=2;
			}
			else
			{
				$diferencia=$numpasos-9;
				if($diferencia<=0)
				{
					$diferencia=1;
				}
				for($i=($diferencia);$i<=$numpasos;$i++)
				{
					if($paso==$i)
					{
						$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
					}
					else
					{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($i)."&paso1inter=$i'>$i</a></td>";
					}
					$colspan++;
				}
				if($paso!=$numpasos)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($paso+1)."&paso1inter=".($paso+1)."' >&gt;&gt;</a></td>";
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofinter=".$this->CalcularOffset($numpasos)."&paso1inter=$numpasos'>&gt;</a></td>";
					$colspan++;
				}
			}
			if(($_REQUEST['Ofinter'])==0 OR ($paso==$numpasos))
			{
				if($numpasos>10)
				{
					$valor=10+3;
				}
				else
				{
					$valor=$numpasos+3;
				}
				$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
				$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}
			return $salida;
	}

//--------------------LISTADO DE TODOS--------------------------------------------

	/**
	*
	*/
	function ListadoTodos()
	{
		 // $vector1=$this->Consulta_Solicitud_Apoyod();
			//['ARREGLO']['DATOS']['APOYOS']
		  //if($vector1)
			if(!empty($_SESSION['ARREGLO']['DATOS']['APOYOS']))
	    {
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">APOYOS DIAGNOSTICOS SOLICITADOS MANUALES</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	        $this->salida.="  <td width=\"7%\">TIPO</td>";
	        $this->salida.="  <td width=\"9%\">CARGO</td>";
	        $this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
	        $this->salida.="</tr>";
					foreach($_SESSION['ARREGLO']['DATOS']['APOYOS'] as $k => $v)
					//for($i=0;$i<sizeof($vector1);$i++)
					{
					      $vector1=$this->Consulta_Solicitud_Apoyod($k);
                $hc_os_solicitud_id =$vector1[hc_os_solicitud_id];
								$tipo=$vector1[tipo];
								$cargo=$vector1[cargo];
								$descripcion= $vector1[descripcion];
								$observacion= $vector1[observacion];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">$tipo</td>";
                $this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
								$this->salida.="  <td align=\"left\" width=\"52%\">$descripcion</td>";
                $this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
                $this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$observacion</td>";
								$this->salida.="</tr>";
								$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">";
								$this->salida.="<table>";
								for($j=0;$j<sizeof($diag);$j++)
								{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_nombre]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
								$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
								$this->salida.="</tr>";
					}
					$this->salida.="</table><br>";
			}
					//$vector1=$this->Consulta_Solicitud_Interconsulta();
					//if($vector1)
					if(!empty($_SESSION['ARREGLO']['DATOS']['INTER']))
					{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$this->salida.="</table>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">INTERCONSULTAS MANUALES</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"7%\">CARGO</td>";
							$this->salida.="  <td width=\"9%\">CODIGO DE ESPECIALIDAD</td>";
							$this->salida.="  <td width=\"51%\">ESPECIALIDAD</td>";
							$this->salida.="</tr>";
							//for($i=0;$i<sizeof($vector1);$i++)
							foreach($_SESSION['ARREGLO']['DATOS']['INTER'] as $k => $v)
							{
							    $vector1=$this->Consulta_Solicitud_Interconsulta($k);
									$observacion= $vector1[observacion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[cargo]."</td>";
									$this->salida.="  <td align=\"center\" width=\"9%\">".$vector1[especialidad]."</td>";
									$this->salida.="  <td align=\"left\" width=\"52%\">".$vector1[descripcion]."</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Observacion</td>";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">".$vector1[observacion]."</td>";
									$this->salida.="</tr>";
									$diag =$this->Diagnosticos_Solicitados($vector1[hc_os_solicitud_id]);
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">";
									$this->salida.="<table>";
									for($j=0;$j<sizeof($diag);$j++)
									{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
											$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_nombre]."</td>";
											$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
									$this->salida.="</tr>";
							}
							$this->salida.="</table><br>";
				}
					//$vector1=$this->Consulta_Procedimientos_Solicitados();
					if(!empty($_SESSION['ARREGLO']['DATOS']['QX']))
					{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\">PROCEDIMIENTOS QUIRURGICOS SOLICITADOS MANUALES</td>";
							$this->salida.="</tr>";
							$this->salida.="</table>";
							foreach($_SESSION['ARREGLO']['DATOS']['QX'] as $k => $y)
							{
									$vector1=$this->Consulta_Procedimientos_Solicitados($k);
									foreach($vector1[0] as $k=>$v)
									{
										$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td width=\"7%\">TIPO</td>";
										$this->salida.="  <td width=\"9%\">CARGO</td>";
										$this->salida.="  <td width=\"75%\" >DESCRIPCION</td>";
										$this->salida.="</tr>";
										$hc_os_solicitud_id =$v[hc_os_solicitud_id];
										$tipo=$v[tipo];
										$cargos=$v[cargo];
										$descripcion=$v[descripcion];
										$observacion= $v[observacion];
										$cirugia= $v[cirugia];
										$ambito= $v[ambito];
										$finalidad= $v[finalidad];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
										$this->salida.="  <td align=\"center\" width=\"7%\">$tipo</td>";
										$this->salida.="  <td align=\"center\" width=\"9%\">$cargos</td>";
										$this->salida.="  <td align=\"left\" width=\"51%\" >$descripcion</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">OBSERVACION</td>";
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$observacion</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td rowspan=\"".(sizeof($vector1[1][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">DIAGNOSTICO</td>";
										foreach($vector1[1][$k] as $t=>$s)
										{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">";
											$this->salida.="<table>";
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td>$t  </td>";
											$this->salida.="  <td>   $s</td>";
											$this->salida.="</tr>";
											$this->salida.="</table>";
											$this->salida.="</td>";
											$this->salida.="</tr>";
										}
										$this->salida.="</td>";
										$this->salida.="</tr>";
										/*$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">TIPO DE CIRUGIA</td>";
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$cirugia</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">AMBITO</td>";
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$ambito</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">FINALIDAD</td>";
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$finalidad</td>";
										$this->salida.="</tr>";*/
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td rowspan=\"".(sizeof($vector1[2][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">EQUIPOS ESPECIALES REQUERIDOS</td>";
										foreach($vector1[2][$k] as $t=>$s)
										{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$s</td>";
											$this->salida.="</tr>";
										}
										$this->salida.="</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td rowspan=\"".(sizeof($vector1[3][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">OTROS EQUIPOS REQUERIDOS</td>";
										foreach($vector1[3][$k] as $t=>$s)
										{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$s</td>";
											$this->salida.="</tr>";
										}
										$this->salida.="</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\" >INFORMACION</td>";
										$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">".$v[informacion_cargo]."</td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
									}
							}
				}
				
					if(!empty($_SESSION['ARREGLO']['DATOS']['NOQX']))
					//if($vector1)
					{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS NO QUIRURGICOS SOLICITADOS MANUALES</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	        $this->salida.="  <td width=\"7%\">TIPO</td>";
	        $this->salida.="  <td width=\"9%\">CARGO</td>";
	        $this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
	        $this->salida.="</tr>";
					foreach($_SESSION['ARREGLO']['DATOS']['NOQX'] as $k => $v)
					//for($i=0;$i<sizeof($vector1);$i++)
					{
					      $vector1=$this->Consulta_Solicitud_No_Qx($k);
                $hc_os_solicitud_id =$vector1[hc_os_solicitud_id];
								$tipo=$vector1[tipo];
								$cargo=$vector1[cargo];
								$descripcion= $vector1[descripcion];
								$observacion= $vector1[observacion];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">$tipo</td>";
                $this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
								$this->salida.="  <td align=\"left\" width=\"52%\">$descripcion</td>";
                $this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
                $this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
                $this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">$observacion</td>";
								$this->salida.="</tr>";
								$diag =$this->Diagnosticos_SolicitadosNoQx($hc_os_solicitud_id);
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"64%\">";
								$this->salida.="<table>";
								for($j=0;$j<sizeof($diag);$j++)
								{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_nombre]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
								$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
								$this->salida.="</tr>";
					}
					$this->salida.="</table><br>";
				}				

	}

//----------------------PROCEDIMIENTOS QX---------------------------------------------

	function frmFormaQx()
	{
			unset ($_SESSION['DIAGNOSTICOSqx']);
			unset ($_SESSION['APOYOSqx']);
			unset ($_SESSION['PROCEDIMIENTOqx']);
			unset ($_SESSION['MODIFICANDOqx']);
			unset($_SESSION['PASO']);
			unset($_SESSION['PASO1']);
			$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE PROCEDIMIENTOS QUIRURGICOS');
			$this->DatosCompletos();
			if(!empty($_SESSION['ARREGLO']['DATOS']['QX']))
		  //$vector1=$this->Consulta_Procedimientos_Solicitados();
			//if($vector1)
	     {
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">PROCEDIMIENTOS QUIRURGICOS SOLICITADOS</td>";
					$this->salida.="</tr>";
          $this->salida.="</table><br>";
					
					
					foreach($_SESSION['ARREGLO']['DATOS']['QX'] as $id => $value)
					{
								$vector1=$this->Consulta_Procedimientos_Solicitados($id);
								foreach($vector1[0] as $k=>$v)
								{
									$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td width=\"7%\">TIPO</td>";
									$this->salida.="  <td width=\"9%\">CARGO</td>";
									$this->salida.="  <td width=\"51%\" >DESCRIPCION</td>";
									$this->salida.="  <td width=\"13%\" colspan = 2 align = center >OPCION</td>";
									$this->salida.="</tr>";
																		
									$hc_os_solicitud_id =$v[hc_os_solicitud_id];
									$tipo=$v[tipo];
									$cargos=$v[cargo];
									$descripcion=$v[descripcion];
									$observacion= $v[observacion];
									$cirugia= $v[cirugia];
									$ambito= $v[ambito];
									$finalidad= $v[finalidad];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="  <td align=\"center\" width=\"7%\">$tipo</td>";
									$this->salida.="  <td align=\"center\" width=\"9%\">$cargos</td>";
									$this->salida.="  <td align=\"left\" width=\"51%\" >$descripcion</td>";
									if($v[evolucion_id] == $this->evolucion)
									{
										$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'modificarprocedimiento','hc_os_solicitud_idqx'=>$hc_os_solicitud_id, 'cargosqx'=>$cargos, 'descripcionqx'=>$descripcion, 'observacionqx'=>$observacion));
										$this->salida.="  <td align=\"center\" width=\"8%\"><a href='$accion1'><img border = 0 src=\"".GetThemePath()."/images/modificar.png\"></a></td>";
										$accion2=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'eliminarprocedimiento', 'hc_os_solicitud_idqx'=>$hc_os_solicitud_id));
										$this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion2'><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
									}
									else
									{
											$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"13%\">&nbsp;</td>";
									}
									$this->salida.="</tr>";

									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">OBSERVACION</td>";
									$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$observacion</td>";
									$this->salida.="</tr>";

									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td rowspan=\"".(sizeof($vector1[1][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">DIAGNOSTICO</td>";
									foreach($vector1[1][$k] as $t=>$s)
									{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">";
											$this->salida.="<table width=\"100%\">";
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td align=\"center\" width=\"10%\">".$t."</td>";
											$this->salida.="  <td align=\"left\" width=\"70%\">".$s."</td>";
											$this->salida.="</tr>";
											$this->salida.="</table>";
											$this->salida.="</td>";
											$this->salida.="</tr>";
									}
									$this->salida.="</td>";
									$this->salida.="</tr>";
									/*$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">TIPO DE CIRUGIA</td>";
									$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$cirugia</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">AMBITO</td>";
									$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$ambito</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\">FINALIDAD</td>";
									$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$finalidad</td>";
									$this->salida.="</tr>";*/
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td rowspan=\"".(sizeof($vector1[2][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">EQUIPOS ESPECIALES REQUERIDOS</td>";
									foreach($vector1[2][$k] as $t=>$s)
									{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$s</td>";
										$this->salida.="</tr>";
									}
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td rowspan=\"".(sizeof($vector1[3][$k])+1)."\" colspan = 2 align=\"left\" width=\"16%\">OTROS EQUIPOS REQUERIDOS</td>";
									foreach($vector1[3][$k] as $t=>$s)
									{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$s</td>";
										$this->salida.="</tr>";
									}
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$apoyos =$this->Apoyos_Del_Procedimiento($hc_os_solicitud_id);
									if ($apoyos)
									{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td rowspan=\"".(sizeof($apoyos)+1)."\" colspan = 2 align=\"left\" width=\"16%\">APOYOS DIAGNOSTICOS REQUERIDOS</td>";
										for($j=0;$j<sizeof($apoyos);$j++)
										{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">";
											$this->salida.="<table width=\"100%\">";
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td align=\"center\" width=\"10%\">".$apoyos[$j][cargo]."</td>";
											$this->salida.="  <td align=\"left\" width=\"70%\">".$apoyos[$j][descripcion]."</td>";
											$this->salida.="</tr>";
											$this->salida.="</table>";
											$this->salida.="</td>";
											$this->salida.="</tr>";
										}
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td colspan = 2 align=\"left\" width=\"16%\" >INFORMACION</td>";
									$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">".$v[informacion_cargo]."</td>";
									$this->salida.="</tr>";
									$this->salida.="</table><br>";
								}
					}
				}
       		$this->salida .= "</form>";
					$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada',
					'Ofqx'=>$_REQUEST['Ofqx'],'paso1qx'=>$_REQUEST['paso1qx'],
					'criterio1qx'=>$_REQUEST['criterio1qx'],
					'cargosqx'=>$_REQUEST['cargosqx'],
					'descripcionqx'=>$_REQUEST['descripcionqx']));
					$this->salida .= "<form name=\"formaqx\" action=\"$accion\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO</td>";																			
					$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1qx'  class =\"select\">";                            
					$this->salida.="<option value = '-1' selected>Todos</option>";

					$categoria = $this->tiposQx();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$id = $categoria[$i][tipo_cargo];
							$opcion = $categoria[$i][descripcion];
							if (($_REQUEST['criterio1qx'])  != $id)
							{
									$this->salida.="<option value = '$id'>$opcion</option>";
							}
							else
									{
								$this->salida.="<option value = '$id' selected >$opcion</option>";
							}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";	
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">CARGO:</td>";
					$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargosqx'  value =\"".$_REQUEST['cargosqx']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"34%\" align='center'><input type='text' class='input-text' 	name = 'descripcionqx'   value =\"".$_REQUEST['descripcionqx']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscarqx' type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
         	$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaTiposCargos');
					$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></p>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
	}


	function RetornarBarraProcedimientos_Avanzada()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1qx'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada',
		'conteoqx'=>$this->conteo,'paso1qx'=>$_REQUEST['paso1qx'],
		'criterio1qx'=>$_REQUEST['criterio1qx'],
		'cargosqx'=>$_REQUEST['cargosqx'],
		'descripcionqxqx'=>$_REQUEST['descripcionqx']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset(1)."'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso+1)."&paso1qx=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($numpasos)."&paso1qx=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso+1)."&paso1qx=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($numpasos)."&paso1qx=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofqx'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function Modificar_Procedimiento_Solicitado($hc_os_solicitud_id, $vectorD)
	{
						$this->salida= ThemeAbrirTablaSubModulo('MODIFICAR PROCEDIMIENTO QUIRURGICO');
						$this->salida .= "<script>\n";
						$this->salida .= "function enviar(Of,paso){\n";
						$this->salida .= "document.formamodqx.Ofqx.value=Of\n";
						$this->salida .= "document.formamodqx.paso1qx.value=paso\n";
						$this->salida .= "document.formamodqx.opcqx.value='opc'\n";
						$this->salida .= "document.formamodqx.submit();}\n";
						$this->salida .= "function elimdiag(t){\n";
						$this->salida .= "document.formamodqx.kqx.value=t;\n";
						$this->salida .= "document.formamodqx.eliminardiagnosticoqx.value='1';\n";
						$this->salida .= "document.formamodqx.submit();}\n";
						$this->salida .= "</script>\n";
						$accionM=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'OpcionesModificacionProcedimiento',
						'codigoqx'=>$_REQUEST['codigoqx'], 'diagnosticoqx'=>$_REQUEST['diagnosticoqx']));
						$this->salida .= "<form name=\"formamodqx\" action=\"$accionM\" method=\"post\">";
						$this->salida.="  <input type='hidden' name = 'hc_os_solicitud_idqx'  value = '$hc_os_solicitud_id'>";            
						$this->salida.="  <input type='hidden' name = 'Ofqx'  value = ''>";
						$this->salida.="  <input type='hidden' name = 'paso1qx'  value = ''>";
						$this->salida.="  <input type='hidden' name = 'opcqx'  value = ''>";
						$vector1=$this->Consulta_Modificar_Procedimiento($hc_os_solicitud_id);
						if($vector1)
						{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$this->salida.="</table>";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">DATOS DEL PROCEDIMIENTO</td>";
							$this->salida.="</tr>";
							foreach($vector1[0] as $k=>$v)
							{
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td align=\"center\" width=\"20%\">TIPO</td>";
								$this->salida.="  <td align=\"center\" width=\"10%\">CARGO</td>";
								$this->salida.="  <td align=\"center\" width=\"50%\">DESCRIPCION</td>";
								$this->salida.="</tr>";
								$hc_os_solicitud_id =$v[hc_os_solicitud_id];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="<td align=\"center\" width=\"20%\">".$v[tipo]."</td>";
								$this->salida.="<td align=\"center\" width=\"10%\">".$v[cargo]."</td>";
								$this->salida.="<td align=\"left\" width=\"50%\" >".$v[descripcion]."</td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td width=\"30%\" align=\"left\" >OBSERVACION</td>";
								if ($_REQUEST['observacionqx']=== '' OR !empty($_REQUEST['observacionqx']))
								{
									$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacionqx' cols = 100 rows = 3>".$_REQUEST['observacionqx']."</textarea></td>" ;
								}
								else
								{
									$this->salida.="<td width=\"50%\"align='center'><textarea style = \"width:100%\" class='textarea' name = 'observacionqx' cols = 100 rows = 3>".$v[observacion]."</textarea></td>" ;
								}
								$this->salida.="</tr>";
								/*$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td width=\"30%\"align=\"left\" >TIPO DE CIRUGIA</td>";
								$this->salida.="<td width=\"50%\" align = left >";
								$this->salida.="<select size = 1 name = 'cirugiaqx'  class =\"select\">";
								$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
								$categoria = $this->tipocirugia();
								if(empty($_REQUEST['cirugiaqx']))
								{
									$_REQUEST['cirugiaqx']=$v[tipo_cirugia_id];
								}
								for($i=0;$i<sizeof($categoria);$i++)
								{
											if ($_REQUEST['cirugiaqx'] != $categoria[$i][tipo_cirugia_id])
											{
												$this->salida.="<option value = \"".$categoria[$i][tipo_cirugia_id]."\">".$categoria[$i][descripcion]."</option>";
											}
											else
											{
												$this->salida.="<option  value = \"".$categoria[$i][tipo_cirugia_id]."\" selected >".$categoria[$i][descripcion]."</option>";
											}
								}
								$this->salida.="</select>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td width=\"30%\" align=\"left\" >AMBITO</td>";
								$this->salida.="<td width=\"50%\" align = left >";
								$this->salida.="<select size = 1 name = 'ambitoqx'  class =\"select\">";
								$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
								$categoria = $this->tipoambito();
								if(empty($_REQUEST['ambitoqx']))
								{
									$_REQUEST['ambitoqx']=$v[ambito_cirugia_id];
								}
								for($i=0;$i<sizeof($categoria);$i++)
									{
										if ($_REQUEST['ambitoqx'] != $categoria[$i][ambito_cirugia_id])
											{
												$this->salida.="<option value = \"".$categoria[$i][ambito_cirugia_id]."\">".$categoria[$i][descripcion]."</option>";
											}
										else
											{
												$this->salida.="<option value = \"".$categoria[$i][ambito_cirugia_id]."\" selected >".$categoria[$i][descripcion]."</option>";
											}
									}
								$this->salida.="</select>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td width=\"30%\" align=\"left\" >FINALIDAD</td>";
								$this->salida.="<td width=\"50%\" align = left >";
								$this->salida.="<select size = 1 name = 'finalidadqx'  class =\"select\">";
								$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
								$categoria = $this->tipofinalidad();
								if(empty($_REQUEST['finalidadqx']))
								{
									$_REQUEST['finalidadqx']=$v[finalidad_procedimiento_id];
								}
								for($i=0;$i<sizeof($categoria);$i++)
									{
										if ($_REQUEST['finalidadqx']  != $categoria[$i][finalidad_procedimiento_id])
											{
												$this->salida.="<option value = \"".$categoria[$i][finalidad_procedimiento_id]."\">".$categoria[$i][descripcion]."</option>";
											}
										else
											{
												$this->salida.="<option value = \"".$categoria[$i][finalidad_procedimiento_id]."\" selected >".$categoria[$i][descripcion]."</option>";
											}
									}
								$this->salida.="</select>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
                */
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td  align=\"left\" width=\"30%\">EQUIPOS ESPECIALES REQUERIDOS</td>";
								$this->salida.="<td width=\"30%\" align=\"left\" >";
								$this->salida.="<table align=\"center\" border=\"0\"  width=\"35%\">";
								$categoria = $this->tipoequipofijo();
								for($i=0;$i<sizeof($categoria);$i++)
								{
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="<td align=\"left\" width=\"5%\">".$categoria[$i][descripcion]."</td>";
									$f = $_REQUEST['fijoqx'];
									if(empty($_SESSION['PASO']))
									{
											if(empty($vector1[2][$k][$categoria[$i][tipo_equipo_fijo_id]]))
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijoqx[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
											}
											else
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijoqx[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
											}
									}
									else
									{
											if(($f[$i]) != $categoria[$i][tipo_equipo_fijo_id])
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijoqx[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
											}
											else
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijoqx[$i]' value = \"".$categoria[$i][tipo_equipo_fijo_id]."\"></td></tr>";
											}
									}
								}
								if(empty($_SESSION['PASO']))
								{
									$_SESSION['PASO']=true;
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td  align=\"left\" width=\"30%\">OTROS EQUIPOS REQUERIDOS</td>";
								$this->salida.="<td width=\"30%\" align=\"left\" >";
								$this->salida.="<table align=\"center\" border=\"0\"  width=\"35%\">";
								$categoria = $this->tipoequipomovil();
								for($i=0;$i<sizeof($categoria);$i++)
									{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td align=\"left\" width=\"5%\">".$categoria[$i][descripcion]."</td>";
										$m = $_REQUEST['movilqx'];
										if(empty($_SESSION['PASO1']))
										{
											if(empty($vector1[3][$k][$categoria[$i][tipo_equipo_id]]))
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name = 'movilqx[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
											}
											else
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movilqx[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
											}
										}
										else
										{
											if(($m[$i]) != $categoria[$i][tipo_equipo_id])
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name = 'movilqx[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
											}
											else
											{
												$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movilqx[$i]' value = \"".$categoria[$i][tipo_equipo_id]."\"></td></tr>";
											}
										}
									}
								if(empty($_SESSION['PASO1']))
								{
									$_SESSION['PASO1']=true;
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<script>";
								$this->salida.="function apoyos1(url){\n";
								$this->salida.="document.formamodqx.action=url;\n";
								$this->salida.="document.formamodqx.submit();}";
								$this->salida.="</script>";
								if ($_SESSION['APOYOSqx'])
								{
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="<td align=\"center\" width=\"20%\">APOYOS PRE Y POS QUIRURGICOS REQUERIDOS</td>";
									$this->salida.="<td align=\"left\" width=\"60%\">";
									$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"center\" width=\"10%\">CARGO</td>";
									$this->salida.="<td align=\"center\" width=\"65%\">APOYO DIAGNOSTICO</td>";
									$this->salida.="<td align=\"center\" width=\"5%\">OPCION</td>";
									$this->salida.="</tr>";
									$h=0;
									foreach ($_SESSION['APOYOSqx'] as $l=>$v)
									{
										if( $h % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$accion5=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'eliminarapoyo', 'apoyoqx'=>$l));
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td align=\"center\" width=\"10%\">".$l."</td>";
										$this->salida.="<td align=\"left\" width=\"65%\">".$v."</td>";
										$this->salida.="<input type='hidden' name = id$lqx' value = ".$l.">";
										$this->salida.="<td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:apoyos1(\"$accion5\")'><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
										$this->salida.="</tr>";
										$h++;
									}
									$this->salida.="<tr class=\"modulo_list_oscuro\">";
									$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"modulo_list_oscuro\">";
									$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
									//$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos1(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
								}
								else
								{
									$this->salida.="<tr class=\"modulo_list_oscuro\">";
									$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
									//$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos1(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
									$this->salida.="</tr>";
								}
								/*if (!empty($vector1[1][$k]))
								{
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="<td rowspan=\"".((sizeof($vector1[1][$k]))+1)."\" align=\"left\" width=\"18%\">DIAGNOSTICOS</td>";
									$this->salida.="<td colspan = 1 align=\"left\" width=\"60%\">";
									$this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <input type='hidden' name = 'eliminardiagnosticobdqx' value = ''>";
									$this->salida.="  <input type='hidden' name = 'tqx'  value = ''>";
									foreach($vector1[1][$k] as $t=>$s)
									{
										$this->salida.="  <td class=\"$estilo\" align=\"left\" width=\"62%\">".$t." - ".$s."</td>";
										$this->salida.="  <td class=\"$estilo\" align=\"center\" width=\"5%\"><a href=\"javascript:elimdiagbd('$t')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
										$this->salida.="</tr>";
									}
									$this->salida.="</table><br>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
								}*/
                
                if($_SESSION['DIAGNOSTICOSqx']){
                  $this->salida.="<tr class=\"$estilo\">";
                  $this->salida.="<td  align=\"center\" width=\"20%\">DIAGNOSTICOS MEDICOS ASIGNADOS</td>";
                  $this->salida.="<td  align=\"left\" width=\"60%\">";
                  $this->salida.=$this->frmFormaConsultaDiagnostico();
                  $this->salida.="</td>";
                  $this->salida.="</tr>";
                }
							}
							$this->salida.="</table><br>";
						}
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS MEDICOS</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="<td width=\"4%\">CODIGO:</td>";
								$this->salida.="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoqx'></td>" ;
								$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
								$this->salida.="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoqx'   value =\"".$_REQUEST['diagnosticoqx']."\"        ></td>" ;
								$this->salida.="<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"BuscarDiagqx\" type=\"submit\" value=\"BUSCAR\"></td>";
								$this->salida.="</tr>";
								$this->salida.="</table><br>";
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
								$this->salida .= $this->SetStyle("MensajeError");
								$this->salida.="</table>";
					if ($vectorD)
						{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
								$this->salida.="<tr class=\"modulo_table_title\">";
								$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="  <td width=\"10%\">CODIGO</td>";
								$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
								$this->salida.="  <td width=\"5%\">OPCION</td>";
								$this->salida.="</tr>";
								for($i=0;$i<sizeof($vectorD);$i++)
								{
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorD[$i][diagnostico_id]."</td>";
										$this->salida.="  <td align=\"left\" width=\"65%\">".$vectorD[$i][diagnostico_nombre]."</td>";
										$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opD".qx."[$i]' value = '".$vectorD[$i][diagnostico_id].",".$vectorD[$i][diagnostico_nombre]."'></td>";
										$this->salida.="</tr>";

								}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"minsertardiagnosticoqx\" type=\"submit\" value=\"GUARDAR\"></td>";
								$this->salida.="</tr>";
								$this->salida.="</table><br>";
								$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
								$this->salida .= "  <tr>";
								$this->salida .= "  <td width=\"100%\" align=\"center\">";
	//PEGO LA BARRA
			if($this->limit>=$this->conteo)
			{
				return '';
			}
			$paso=$_REQUEST['paso1qx'];
			if(empty($paso))
			{
				$paso=1;
			}
			$accion = 'javascript:enviar(';
			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;
			$this->salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
			if($paso > 1)
			{
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset(1)."','')\">&lt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso-1)."','".($paso-1)."')\">&lt;&lt;</a></td>";
				$colspan+=2;
			}
			$barra++;
			if(($barra+10)<=$numpasos)
			{
				for($i=($barra);$i<($barra+10);$i++)
				{
					if($paso==$i)
					{
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
					}
					else
					{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Ofqx=".."&paso1qx=
					}
					$colspan++;
				}
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\">&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
				$colspan+=2;
			}
			else
			{
				$diferencia=$numpasos-9;
				if($diferencia<=0)
				{
					$diferencia=1;
				}
				for($i=($diferencia);$i<=$numpasos;$i++)
				{
					if($paso==$i)
					{
						$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
					}
					else
					{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i'
					}
					$colspan++;
				}
				if($paso!=$numpasos)
				{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\" >&gt;&gt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
					$colspan++;
				}
			}
			if(($_REQUEST['Ofqx'])==0 OR ($paso==$numpasos))
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
	//FIN DE LA BARRA
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
		$this->salida.="<td><input type=\"submit\" name = 'guardarmodificacionprocedimientoqx' value=\"GUARDAR PROCEDIMIENTO\" class=\"input-submit\"</td>";
		$this->salida .= "</form>";
		$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Qx');
		$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name= 'volverqx' type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


	function frmForma_Seleccion_Avanzada($vectorA)
	{
					$this->salida= ThemeAbrirTablaSubModulo('ADICION DE PROCEDIMIENTOS QUIRURGICOS');
					$this->DatosCompletos();
					$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada',
					'Ofqx'=>$_REQUEST['Ofqx'],'paso1qx'=>$_REQUEST['paso1qx'],
					'criterio1qx'=>$_REQUEST['criterio1qx'],
					'cargosqx'=>$_REQUEST['cargosqx'],
					'descripcionqx'=>$_REQUEST['descripcionqx']));
					$this->salida .= "<form name=\"formaqx\" action=\"$accion\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO</td>";																			
					$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1qx'  class =\"select\">";                            
					$this->salida.="<option value = '-1' selected>Todos</option>";

					$categoria = $this->tiposQx();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$id = $categoria[$i][tipo_cargo];
							$opcion = $categoria[$i][descripcion];
							if (($_REQUEST['criterio1qx'])  != $id)
							{
									$this->salida.="<option value = '$id'>$opcion</option>";
							}
							else
									{
								$this->salida.="<option value = '$id' selected >$opcion</option>";
							}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";	
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">CARGO:</td>";
					$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargosqx'  value =\"".$_REQUEST['cargosqx']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"34%\" align='center'><input type='text' class='input-text' 	name = 'descripcionqx'   value =\"".$_REQUEST['descripcionqx']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscarqx' type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
         	$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
					$this->salida .= "<form name=\"formaqx\" action=\"$accion\" method=\"post\">";
					if ($vectorA)
					{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"25%\">TIPO</td>";
							$this->salida.="  <td width=\"10%\">CARGO</td>";
							$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($vectorA);$i++)
							{
									$grupo_tipo_cargo = $vectorA[$i][grupo_tipo_cargo];
									$tipo             = $vectorA[$i][tipo];
									$cargos            = $vectorA[$i][cargo];
									$descripcion      = $vectorA[$i][descripcion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"center\" width=\"25%\">$tipo</td>";
									$this->salida.="  <td align=\"center\" width=\"10%\">$cargos</td>";
									$this->salida.="  <td align=\"left\" width=\"40%\">$descripcion</td>";
									$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'llenarprocedimiento','tipoqx'=>"$tipo", 'cargosqx'=>"$cargos", 'descripcionqx'=>"$descripcion"));
									$this->salida.="  <td align=\"center\" width=\"5%\"><a href='$accion1'>SOLICITAR</a></td>";
									$this->salida.="</tr>";
							}
							$this->salida.="</table><br>";
							$var=$this->RetornarBarraProcedimientos_Avanzada();
							if(!empty($var))
							{
									$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
									$this->salida .= "  <tr>";
									$this->salida .= "  <td width=\"100%\" align=\"center\">";
									$this->salida .=$var;
									$this->salida .= "  </td>";
									$this->salida .= "  </tr>";
									$this->salida .= "  </table><br>";
							}
					}
					$this->salida .= "</form>";
					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Qx');
					$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name = 'volverqx' type=\"submit\" value=\"VOLVER\"></form></td></tr>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
		}

	/*
	*
	*/
	function Llenar_Procedimiento($tipo, $cargos, $procedimiento, $vectorD)
	{
					$this->salida= ThemeAbrirTablaSubModulo('ADICION DE PROCEDIMIENTOS QUIRURGICOS');
					$this->DatosCompletos();
					$this->salida .= "<script>\n";
					$this->salida .= "function enviar(Of,paso){\n";
					$this->salida .= "document.formadesqx.Ofqx.value=Of\n";
					$this->salida .= "document.formadesqx.paso1qx.value=paso\n";
					$this->salida .= "document.formadesqx.opcqx.value='opc'\n";
					$this->salida .= "document.formadesqx.submit();}\n";
					$this->salida .= "function elimdiag(k){\n";
					$this->salida .= "alert('hola');\n";
					$this->salida .= "document.formadesqx.kqx.value=k;\n";
					$this->salida .= "document.formadesqx.eliminardiagnosticoqx.value='1';\n";
					$this->salida .= "document.formadesqx.submit();}\n";
					$this->salida .= "</script>\n";
					$accionG=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'OpcionesProcedimiento',
					'codigoqx'=>$_REQUEST['codigoqx'],
					'diagnosticoqx'=>$_REQUEST['diagnosticoqx']));
					$this->salida .= "<form name=\"formadesqx\" action=\"$accionG\" method=\"post\">";
					$this->salida.="  <input type='hidden' name = 'tipoqx'  value = '$tipo'>";
					$this->salida.="  <input type='hidden' name = 'cargosqx'  value = '$cargos'>";
					$this->salida.="  <input type='hidden' name = 'procedimientoqx'  value = '$procedimiento'>";
					$this->salida.="  <input type='hidden' name = 'Ofqx'  value = ''>";
					$this->salida.="  <input type='hidden' name = 'paso1qx'  value = ''>";
					$this->salida.="  <input type='hidden' name = 'opcqx'  value = ''>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"3\">DATOS DEL PROCEDIMIENTO</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_table_title\">";
	        $this->salida.="  <td align=\"center\" width=\"20%\">TIPO</td>";
	        $this->salida.="  <td align=\"center\" width=\"10%\">CARGO</td>";
	        $this->salida.="  <td align=\"center\" width=\"50%\">DESCRIPCION</td>";
	        $this->salida.="</tr>";
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"center\" width=\"20%\">$tipo</td>";
					$this->salida.="<td align=\"center\" width=\"10%\">$cargos</td>";
					$this->salida.="<td align=\"center\" width=\"50%\" >$procedimiento</td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td width=\"20%\" align=\"left\" >OBSERVACION</td>";
					if (($_REQUEST['observacionqx'])  == '')
					{
						$this->salida.="<td width=\"50%\"align='center'><textarea class='textarea' name = 'observacionqx' cols = 100 rows = 3>$observacion</textarea></td>" ;
					}
					else
					{
						$this->salida.="<td width=\"50%\"align='center'><textarea class='textarea' name = 'observacionqx' cols = 100 rows = 3>".$_REQUEST['observacionqx']."</textarea></td>" ;
					}
					$this->salida.="</tr>";
					/*$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td class=".$this->SetStyle("cirugia")." width=\"20%\"align=\"left\" >TIPO DE CIRUGIA</td>";
					$this->salida.="<td width=\"60%\" align = left >";
					$this->salida.="<select size = 1 name = 'cirugiaqx'  class =\"select\">";
					$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
					$categoria = $this->tipocirugia();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$tipo_cirugia_id = $categoria[$i][tipo_cirugia_id];
							$opcion 				 = $categoria[$i][descripcion];
							if (($_REQUEST['cirugiaqx'])  != $tipo_cirugia_id)
							{
								$this->salida.="<option value = $tipo_cirugia_id>$opcion</option>";
							}
							else
							{
								$this->salida.="<option value = $tipo_cirugia_id selected >$opcion</option>";
							}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td width=\"20%\" class=".$this->SetStyle("ambito")." align=\"left\" >AMBITO</td>";
					$this->salida.="<td width=\"60%\" align = left >";
					$this->salida.="<select size = 1 name = 'ambitoqx'  class =\"select\">";
					$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
					$categoria = $this->tipoambito();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$ambito_cirugia_id = $categoria[$i][ambito_cirugia_id];
							$opcion = $categoria[$i][descripcion];
							if (($_REQUEST['ambitoqx'])  != $ambito_cirugia_id)
							{
								$this->salida.="<option value = $ambito_cirugia_id>$opcion</option>";
							}
							else
							{
								$this->salida.="<option value = $ambito_cirugia_id selected >$opcion</option>";
							}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td width=\"20%\" class=".$this->SetStyle("finalidad")." align=\"left\" >FINALIDAD</td>";
					$this->salida.="<td width=\"60%\" align = left >";
					$this->salida.="<select size = 1 name = 'finalidadqx'  class =\"select\">";
					$this->salida.="<option value = -1 selected>-SELECCIONE-</option>";
					$categoria = $this->tipofinalidad();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$finalidad_procedimiento_id  = $categoria[$i][finalidad_procedimiento_id];
							$opcion = $categoria[$i][descripcion];
							if (($_REQUEST['finalidadqx'])  != $finalidad_procedimiento_id)
							{
								$this->salida.="<option value = $finalidad_procedimiento_id>$opcion</option>";
							}
							else
							{
								$this->salida.="<option value = $finalidad_procedimiento_id selected >$opcion</option>";
							}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";*/
          $this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td width=\"20%\" align=\"left\" >REQUERIMIENTO DE EQUIPOS ESPECIALES</td>";
					$this->salida.="<td width=\"60%\" align=\"left\" >";
          $this->salida.="<table align=\"center\" border=\"0\"  width=\"50%\">";
					$categoria = $this->tipoequipofijo();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$tipo_equipo_fijo_id  = $categoria[$i][tipo_equipo_fijo_id];
							$opcion = $categoria[$i][descripcion];
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"left\" width=\"5%\">$opcion</td>";
							$f = $_REQUEST['fijoqx'];
							if (($f[$i])  != $tipo_equipo_fijo_id)
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'fijoqx[$i]' value = $tipo_equipo_fijo_id></td></tr>";
							}
							else
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name= 'fijoqx[$i]' value = $tipo_equipo_fijo_id></td></tr>";
							}
					}
					$this->salida.="</table>";
          $this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td width=\"20%\" align=\"left\" >OTROS EQUIPOS REQUERIDOS</td>";
					$this->salida.="<td width=\"60%\" align=\"left\" >";
          $this->salida.="<table align=\"center\" border=\"0\"  width=\"50%\">";
					$categoria = $this->tipoequipomovil();
					for($i=0;$i<sizeof($categoria);$i++)
					{
							$tipo_equipo_id  = $categoria[$i][tipo_equipo_id];
							$opcion = $categoria[$i][descripcion];
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"left\" width=\"5%\">$opcion</td>";
							$m = $_REQUEST['movilqx'];
							if (($m[$i])  != $tipo_equipo_id)
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= 'movilqx[$i]' value = $tipo_equipo_id></td></tr>";
							}
							else
							{
								$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox checked name = 'movilqx[$i]' value = $tipo_equipo_id></td></tr>";
							}
					}
					$this->salida.="</table>";
          $this->salida.="</td>";
					$this->salida.="</tr>";
					$this->salida.="<script>";
					$this->salida.="function apoyos(url){\n";
					$this->salida.="document.formadesqx.action=url;\n";
					$this->salida.="document.formadesqx.submit();}";
					$this->salida.="</script>";
					if ($_SESSION['APOYOSqx'])
					{
					 	$this->salida.="<tr class=\"$estilo\">";
					 	$this->salida.="<td align=\"center\" width=\"20%\">APOYOS PRE Y POS QUIRURGICOS REQUERIDOS</td>";
					 	$this->salida.="<td align=\"left\" width=\"60%\">";
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td align=\"center\" width=\"10%\">CARGO</td>";
						$this->salida.="<td align=\"center\" width=\"65%\">APOYO DIAGNOSTICO</td>";
      			$this->salida.="<td align=\"center\" width=\"5%\">OPCION</td>";
						$this->salida.="</tr>";
						$h=0;
						foreach ($_SESSION['APOYOSqx'] as $k=>$v)
						{
						  if( $h % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$accion5=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'eliminarapoyo', 'apoyoqx'=>$k));
							//$accion5=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accionqx'=>'eliminarapoyo', 'apoyoqx'=>$k));
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="<td align=\"center\" width=\"10%\">".$k."</td>";
							$this->salida.="<td align=\"left\" width=\"65%\">".$v."</td>";
							$this->salida.="<input type='hidden' name = id$k'qx' value = ".$k.">";
							$this->salida.="<td class=\"$estilo\" align=\"center\" width=\"5%\"><a href='javascript:apoyos(\"$accion5\")'><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
							$this->salida.="</tr>";
							$h++;
						}
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\">&nbsp;</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
						//$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
						//$this->salida.="  <td colspan = 3 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
						$this->salida.="</tr>";
						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";
					}
					else
					{
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
						//$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accionqx'=>'Busqueda_Avanzada_Apoyos'));
						//$this->salida.="  <td colspan = 2 align=\"center\" width=\"80%\"><a href='javascript:apoyos(\"$accion1\")'><font color='#190CA2'><b><u>SOLICITAR APOYOS DIAGNOSTICOS PARA EL PROCEDIMIENTO QUIRURGICO</u></b></font></a></td>";
						$this->salida.="</tr>";
					}
					if($_SESSION['DIAGNOSTICOSqx'])
					{
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td  align=\"center\" width=\"20%\">DIAGNOSTICOS MEDICOS ASIGNADOS</td>";
						$this->salida.="<td  align=\"left\" width=\"60%\">";
						$this->salida.=$this->frmFormaConsultaDiagnostico();
						$this->salida.="</td>";
						$this->salida.="</tr>";
					}
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS MEDICOS </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"4%\">CODIGO:</td>";
					$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoqx'></td>" ;
					$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
					$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoqx'   value =\"".$_REQUEST['diagnosticoqx']."\"        ></td>" ;
					$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"BuscarDiagqx\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					if ($vectorD)
					{
             	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"10%\">CODIGO</td>";
							$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
              for($i=0;$i<sizeof($vectorD);$i++)
						  {
									$codigo          = $vectorD[$i][diagnostico_id];
									$diagnostico    = $vectorD[$i][diagnostico_nombre];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
									$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opDqx[$i]' value = '".$codigo.",".$diagnostico."'></td>";
									$this->salida.="</tr>";
							}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarDiagqx\" type=\"submit\" value=\"GUARDAR\"></td>";
							$this->salida.="</tr>";
					    $this->salida.="</table><br>";
							$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
							$this->salida .= "  <tr>";
							$this->salida .= "  <td width=\"100%\" align=\"center\">";
							//PEGO LA BARRA
							if($this->limit>=$this->conteo)
							{
								return '';
							}
							$paso=$_REQUEST['paso1qx'];
							if(empty($paso))
							{
								$paso=1;
							}
							$accion = 'javascript:enviar(';
							$barra=$this->CalcularBarra($paso);
							$numpasos=$this->CalcularNumeroPasos($this->conteo);
							$colspan=1;
							$this->salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
							if($paso > 1)
							{
								$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset(1)."','')\">&lt;</a></td>";
								$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso-1)."','".($paso-1)."')\">&lt;&lt;</a></td>";
								$colspan+=2;
							}
							$barra++;
							if(($barra+10)<=$numpasos)
							{
								for($i=($barra);$i<($barra+10);$i++)
								{
									if($paso==$i)
									{
										$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
									}
									else
									{
										$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Ofqx=".."&paso1qx=
									}
									$colspan++;
								}
								$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\">&gt;&gt;</a></td>";
								$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
								$colspan+=2;
							}
							else
							{
								$diferencia=$numpasos-9;
								if($diferencia<=0)
								{
									$diferencia=1;
								}
								for($i=($diferencia);$i<=$numpasos;$i++)
								{
									if($paso==$i)
									{
										$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
									}
									else
									{
										$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($i)."','".$i."');\">$i</a></td>";//&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i'
									}
									$colspan++;
								}
								if($paso!=$numpasos)
								{
									$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($paso+1)."','".($paso+1)."');\" >&gt;&gt;</a></td>";
									$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href=\"$accion'".$this->CalcularOffset($numpasos)."','".$numpasos."');\">&gt;</a></td>";
									$colspan++;
								}
							}
							if(($_REQUEST['Ofqx'])==0 OR ($paso==$numpasos))
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
							//FIN DE LA BARRA
							$this->salida .= "  </td>";
							$this->salida .= "  </tr>";
							$this->salida .= "  </table><br>";
					}
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
					$this->salida .= "<td><input type=\"submit\"  name = 'guardarprocedimientoqx'             value=\"GUARDAR PROCEDIMIENTO\"  class=\"input-submit\"></td>";
          $this->salida .= "</form>";
					$accion3=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Qx');
					//$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accionqx'=>'FormaAvanzada'));
		      $this->salida .= "<form name=\"forma\" action=\"$accion3\" method=\"post\">";
		      $this->salida .= "<td   align=\"center\"><input class=\"input-submit\" name= 'cancelarqx' type=\"submit\" value=\"CANCELAR\"></form></td>";
					$this->salida.="</tr></table>";

					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','Qx');
					$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></p>";
					$this->salida .= ThemeCerrarTabla();
					return true;
	}


	function frmForma_Seleccion_ApoyosQx($vectorA)
 {
		  $this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO - PROCEDIMIENTO QUIRURGICO');
			$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos','Ofqx'=>$_REQUEST['Ofqx'],'paso1qx'=>$_REQUEST['paso1qx'],
			'criterio1qx'=>$_REQUEST['criterio1qx'],'cargoqx'=>$_REQUEST['cargoqx'],
			'procedimientoqx'=>$_REQUEST['procedimientoqx']));
			$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"5%\">TIPO</td>";
			$this->salida.="<td width=\"10%\" align = left >";
			$this->salida.="<select size = 1 name = 'criterio1qx'  class =\"select\">";
			$this->salida.="<option value = '001' selected>Todos</option>";
			if (($_REQUEST['criterio1qx'])  == '002')
			{
				$this->salida.="<option value = '002' selected>Frecuentes</option>";
			}
			else
			{
				$this->salida.="<option value = '002' >Frecuentes</option>";
			}
			$categoria = $this->tipos();
			for($i=0;$i<sizeof($categoria);$i++)
			{
				$apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
				$opcion = $categoria[$i][descripcion];
				if (($_REQUEST['criterio1'])  != $apoyod_tipo_id)
				{
						$this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
				}
				else
				{
						$this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
				}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			$this->salida.="<td width=\"6%\">CARGO:</td>";
			$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargoqx'  value =\"".$_REQUEST['cargoqx']."\"    ></td>" ;
			$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
			$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionqx'   value =\"".$_REQUEST['descripcionqx']."\"        ></td>" ;
			$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarqx\" type=\"submit\" value=\"BUSCAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.="</form>";
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'insertar_varias'));
			$this->salida .= "<form name=\"formadesqx\" action=\"$accion\" method=\"post\">";
			if ($vectorA)
			{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"15%\">TIPO</td>";
					$this->salida.="  <td width=\"10%\">CARGO</td>";
					$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"5%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorA);$i++)
					{
							$apoyod_tipo_id = $vectorA[$i][apoyod_tipo_id];
							$tipo           = $vectorA[$i][tipo];
							$cargo          = $vectorA[$i][cargo];
							$descripcion    = $vectorA[$i][descripcion];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"center\" width=\"15%\">$tipo</td>";
							$this->salida.="  <td align=\"center\" width=\"10%\">$cargo</td>";
							$this->salida.="  <td align=\"left\" width=\"50%\">$descripcion</td>";
							$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opqx[$i]' value =  '".$cargo.",".$descripcion."".$apoyod_tipo_id."'></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$var=$this->RetornarBarraExamenes_AvanzadaQx();
					if(!empty($var))
					{
						$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
						$this->salida .= "  <tr>";
						$this->salida .= "  <td width=\"100%\" align=\"center\">";
						$this->salida .=$var;
						$this->salida .= "  </td>";
						$this->salida .= "  </tr>";
						$this->salida .= "  </table><br>";
					}
			}
      $this->salida .= "</form>";
			$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'volver_de_solicitud_de_apoyos'));
			$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
 }


 	function RetornarBarraExamenes_AvanzadaQx()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1qx'];
		if(empty($paso))
		{
			$paso=1;
		}

		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaqx',array('accionqx'=>'Busqueda_Avanzada_Apoyos','conteoqx'=>$this->conteo,'paso1qx'=>$_REQUEST['paso1qx'],
		'criterio1qx'=>$_REQUEST['criterio1qx'],
		'cargoqx'=>$_REQUEST['cargoqx'],
		'procedimientoqx'=>$_REQUEST['procedimientoqx']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset(1)."&paso1qx=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso-1)."&paso1qx=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso+1)."&paso1qx=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($numpasos)."&paso1qx=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($i)."&paso1qx=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($paso+1)."&paso1qx=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofqx=".$this->CalcularOffset($numpasos)."&paso1qx=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	/*
	*
	*/
	function frmFormaConsultaDiagnostico($tipo, $cargo, $descripcion)
	{
		if($_SESSION['DIAGNOSTICOSqx'])
		{
		  $consulta.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$consulta .="<tr class=\"hc_table_submodulo_list_title\">";
			$consulta .="<td align=\"center\" width=\"10%\">CODIGO</td>";
			$consulta .="<td align=\"center\" width=\"65%\">DIAGNOSTICO</td>";
      $consulta .="<td align=\"center\" width=\"5%\">OPCION</td>";
			$consulta .="</tr>";
			$s=0;
			$consulta .="<input type='hidden' name = 'eliminardiagnosticoqx' value = ''>";
			$consulta .="<input type='hidden' name = 'kqx'  value = ''>";
			foreach ($_SESSION['DIAGNOSTICOSqx'] as $k=>$v)
			{
				if ($s==0)
				{
					$consulta .="<tr class=\"hc_submodulo_list_oscuro\">";
					$s=1;
				}
				else
				{
					$consulta .="<tr class=\"hc_submodulo_list_claro\">";
					$s=0;
				}
				$consulta .="<td align=\"center\">$k</td>";
				$consulta .="<td align=\"left\">$v</td>";
    		$consulta .="<td align=\"center\" width=\"5%\"><a href=\"javascript:elimdiag('$k')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\" ></a></td>";
				$consulta .="</tr>";
			}
			$consulta .="</table><br>";
		}
		return $consulta;
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
//					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
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

			$x = explode (".",$time[3]);
			return  $time[1].":".$time[2].":".$x[0];
	}

	 /**
  * Forma para los mansajes
	* @access private
	* @return void
  */
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
				$this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
				if($boton){
				   $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
				}
       else{
				   $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}



	/**
	*
	*/
	function FormaListadoCargos($arr)
	{
			IncludeLib("tarifario_cargos");
			if(!empty($arr))
			{ 
					unset($_SESSION['SOLICITUD']['LISTADO']); 
					$_SESSION['SOLICITUD']['LISTADO']=$arr; 
				}
			/*if(empty($_SESSION['SOLICITUD']['LISTADO']))
			{  $_SESSION['SOLICITUD']['LISTADO']=$arr; }*/

 			$arr=$_SESSION['SOLICITUD']['LISTADO'];
			$this->salida .= ThemeAbrirTabla('CARGOS ORDENES SERVICIO');
			//mensaje
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	</table>";
			$this->salida .= "		 <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "			<tr>";
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."</td>";
			$nombre=$this->NombrePaciente($_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']);
			$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\">PACIENTE:</td><td width=\"60%\" class=\"modulo_list_claro\">".$nombre[nombre]."</td>";
			$this->salida .= "			</tr>";
			$this->salida .= " 			</table><br>";
			//	LORENA --
			//$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','CrearOrdenServicio',array('datos'=>$arr));
			$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','CrearOrdenServicio');			
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			for($i=0; $i<sizeof($arr);)
			{
					$this->salida .= "		 <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "				<td>CARGO</td>";
					$this->salida .= "				<td>DESCRICPION</td>";
					$this->salida .= "				<td width=\"5%\" nowrap>CANT</td>";
					$this->salida .= "				<td width=\"20%\" nowrap>PROVEEDOR</td>";
					$this->salida .= "			</tr>";
					$d=$i;
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					//para la cantidad(suma los mismos)
					$this->salida .= "			<tr class=\"$estilo\">";
					//$this->salida .= "				<td align=\"center\" width=\"10%\">".$arr[$i][tarifario_id]."</td>";
					$this->salida .= "				<td align=\"center\" width=\"10%\">".$arr[$i][cargos]."</td>";
					$this->salida .= "				<td>".$arr[$i][descar]."</td>";
					$this->salida .= "				<td align=\"center\">".$arr[$i][cantidad]."</td>";
					$dpto=$this->ComboDepartamento($arr[$i][cargos],$arr[$i][hc_os_solicitud_id]);
					$pro=$this->ComboProveedor($arr[$i][cargos]);
					if(!empty($dpto) OR !empty($pro))
					{
							$this->salida .= "				<td align=\"center\"><select name=\"Combo".$arr[$i][hc_os_solicitud_id]."\" class=\"select\">";
							$this->salida .=" <option value=\"-1\">------SELECCIONE------</option>";
							//departamentos
							for($j=0; $j<sizeof($dpto); $j++)
							{
									$x=$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat];
									if($_REQUEST['Combo'.$arr[$i][hc_os_solicitud_id]]==$x)
									{  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\" selected>".$dpto[$j][descripcion]."</option>";  }
									else
									{  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\">".$dpto[$j][descripcion]."</option>";  }
							}							
							//proveedores
							for($j=0; $j<sizeof($pro); $j++)
							{
									$x=$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat];
									if($_REQUEST['Combo'.$arr[$i][hc_os_solicitud_id]]==$x)
									{  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\" selected>".$pro[$j][plan_descripcion]."</option>";  }
									else
									{  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\">".$pro[$j][plan_descripcion]."</option>";  }
							}
							$this->salida .= "              </select></td>";
					}
					else
					{
							$this->salida .= "       <input type=\"hidden\" name=\"Trans\" value=\"1\">";
							$this->salida .= "       <input type=\"hidden\" name=\"dat\" value=\"".$arr[$i][hc_os_solicitud_id].",dpto,dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad]."\">";
							$this->salida .= "       <input type=\"hidden\" name=\"solicitud\" value=\"".$arr[$i][hc_os_solicitud_id]."\">";
              $trans=true;
							//$accion=ModuloGetURL('app','CentroAutorizacion','user','CrearTranscripcion',array('datos'=>$arr,'solicitud'=>$arr[$i][hc_os_solicitud_id]));
							//$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
							$this->salida .= "				<td class=\"label_error\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Transcripcion\" value=\"TRANSCRIPCION\"></td>";
				//	$this->salida .= "       <input type=\"hidden\" name=\"Combo".$arr[$d][hc_os_solicitud_id]."\" value=\"".$arr[$i][hc_os_solicitud_id].",".$_SESSION['CAJARAPIDA']['DPTO'].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha]."\">";
					//		$this->salida .= "				<td class=\"label_error\" align=\"center\"><a href=\"$accion\">TRANSCRIPCION</a></td>";
					}
					$this->salida .= "       <input type=\"hidden\" name=\"trans\" value=\"$j\">";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			<td colspan=\"4\">";
					$this->salida .= "		 <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "				<td>CARGO</td>";
					$this->salida .= "				<td>TARIFARIO</td>";
					$this->salida .= "				<td>DESCRICPION</td>";
					$this->salida .= "				<td>PRECIO</td>";
					$this->salida .= "				<td></td>";
					$this->salida .= "			</tr>";
					$x=0;
					while($arr[$i][cargos]==$arr[$d][cargos]
					  AND $arr[$i][hc_os_solicitud_id]==$arr[$d][hc_os_solicitud_id])
					{
							$cont=$this->ValidarContratoEqui($arr[$d][tarifario_id],$arr[$d][cargo],$arr[$d][plan_id]);
							if($cont > 0)
							{					
									$this->salida .= "			<tr class=\"$estilo\">";
									$this->salida .= "			<td align=\"center\" width=\"10%\">".$arr[$d][cargo]."</td>";
									$this->salida .= "			<td align=\"center\" width=\"10%\">".$arr[$d][tarifario_id]."</td>";
									$this->salida .= "			<td width=\"70%\">".$arr[$d][descripcion]."</td>";
									$cargos[]=array('tarifario_id'=>$arr[$d][tarifario_id],'cargo'=>$arr[$d][cargo],'cantidad'=>1,'autorizacion_int'=>$_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'],'autorizacion_ext'=>'');
									$liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['SOLICITUD']['PACIENTE']['plan_id'] ,$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO'] ,$_SESSION['SOLICITUD']['PACIENTE']['RANGO'] ,$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'],$arr[$d][servicio]);   
									$this->salida .= "			<td align=\"center\" width=\"15%\">".FormatoValor($liq[0][valor_cargo])."</td>";
									if($_REQUEST['Op'.$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]]==$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id])
									{  $this->salida .= "			<td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\" checked></td>";  }
									else
									{  $this->salida .= "			<td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\"></td>";  }
									$this->salida .= "			</tr>";
							}
							$d++;
							$x++;
					}
					$i=$d;
          if(!empty($trans))
          {  $this->salida .= "</form>";  }
					$this->salida .= " </table>";
					$this->salida .= "			</td>";
					$this->salida .= "			</tr>";
					$this->salida .= " </table><br>";
			}
			//if($j!=0)
			//{
        $this->salida .= "		<table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "				       <tr>";
        $this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar1\" value=\"ACEPTAR\"></td>";
        $this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td>";
        $this->salida .= "				       				</form>";
        $this->salida .= "				       </tr>";
        $this->salida .= "  </table>";
			//}
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

//-------------------PROCEDIMIENTOS NO QX------------------------------------------
	function frmFormanoqx()
	{
					$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE PROCEDIMIENTOS NO QUIRURGICOS');
					$this->DatosCompletos();					
					if(!empty($_SESSION['ARREGLO']['DATOS']['NOQX']))
					{
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida .= $this->SetStyle("MensajeError");
							$this->salida.="</table>";	
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"6\">PROCEDIMIENTOS NO QUIRURGICOS SOLICITADOS</td>";
							$this->salida.="</tr>";	
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"7%\">TIPO</td>";
							$this->salida.="  <td width=\"9%\">CARGO</td>";
							$this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
							//cambio dar
							$this->salida.="  <td width=\"5%\">CANTIDAD SOLICITADA</td>";
							//fin cambio dar
							$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
							$this->salida.="</tr>";
							foreach($_SESSION['ARREGLO']['DATOS']['NOQX'] as $k => $v)
							//for($i=0;$i<sizeof($vector1);$i++)
							{		//cambio dar hay q revisar los colspan
									$vector1=$this->Consulta_Solicitud_No_Qx($k);
									$hc_os_solicitud_id =$vector1[hc_os_solicitud_id];						
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[tipo]."</td>";
									$this->salida.="  <td align=\"center\" width=\"9%\">".$vector1[cargo]."</td>";
									$this->salida.="  <td align=\"left\" width=\"52%\">".$vector1[descripcion]."</td>";
									//cambio dar el la cantidad y el de modificar la canitdad
									$this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[cantidad]."</td>";
									$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'observacion','hc_os_solicitud_idnqx' => $hc_os_solicitud_id, 'cargonqx'=>$vector1[cargo], 'sw_cantidadnqx'=>$vector1[sw_cantidad], 'cantidadnqx'=>$vector1[cantidad],'descripcionnqx' => $vector1[descripcion], 'observacionnqx'=> $vector1[observacion]));
									$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
									$accion2=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'eliminar', 'hc_os_solicitud_idnqx'=> $hc_os_solicitud_id));
									$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
									$this->salida.="</tr>";	
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
									$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$vector1[observacion]."</td>";
									$this->salida.="</tr>";	
									$diag =$this->Diagnosticos_Solicitados($vector1[hc_os_solicitud_id]);
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Diagnosticos</td>";
									$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">";
									$this->salida.="<table>";
									for($j=0;$j<sizeof($diag);$j++)
									{
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
											$this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
											$this->salida.="</tr>";
									}
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
									if($vector1[$i][informacion_cargo]=='SIN COBERTURA.')
									{
										$this->salida.="  <td class = label_error colspan = 4 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
									}
									
									$this->salida.="</tr>";
							}
							$this->salida.="</table><br>";
					}
					$this->salida .= "</form>";	
					//lo que inserte
					$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'Busqueda_Avanzada_NO_Quirurgicos','Ofnqx'=>$_REQUEST['Ofnqx'],'paso1'=>$_REQUEST['paso1nqx'],
					'criterio1nqx'=>$_REQUEST['criterio1nqx'],
					'cargonqx'=>$_REQUEST['cargonqx'],
					'descripcionnqx'=>$_REQUEST['descripcionnqx']));								
						/*	$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accionnqx'=>'Busqueda_Avanzada_NO_Quirurgicos',
						'Ofnqx'=>$_REQUEST['Ofnqx'],'paso1'=>$_REQUEST['paso1nqx'],
						'criterio1nqx'=>$_REQUEST['criterio1nqx'],
						'cargonqx'=>$_REQUEST['cargonqx'],
						'descripcionnqx'=>$_REQUEST['descripcionnqx']));*/
	
						$this->salida .= "<form name=\"formadesnqx\" action=\"$accion1\" method=\"post\">";
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="  <td align=\"center\" colspan=\"5\">ADICION DE PROCEDIMIENTOS NO QUIRURGICOS - BUSQUEDA AVANZADA </td>";
						$this->salida.="</tr>";	
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO:</td>";	
						$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
						$this->salida.="<select size = 1 name = 'criterio1nqx'  class =\"select\">";
						$this->salida.="<option value = '-1' selected>Todos</option>";
						$categoria = $this->tiposNoQx();
						for($i=0;$i<sizeof($categoria);$i++)
						{
							if (($_REQUEST['criterio1nqx'])  != $categoria[$i][tipo_cargo])
							{
									$this->salida.="<option value = '".$categoria[$i][tipo_cargo]."'>".$categoria[$i][descripcion]."</option>";
							}
							else
							{
									$this->salida.="<option value = '".$categoria[$i][tipo_cargo]."' selected >".$categoria[$i][descripcion]."</option>";
							}
						}								
						$this->salida.="</select>";
						$this->salida.="</td>";
						$this->salida.="</tr>";	
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="<td width=\"10%\">CARGO:</td>";
						$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargonqx'  value =\"".$_REQUEST['cargonqx']."\"    ></td>" ;	
						$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
						$this->salida .="<td width=\"34%\" align='center'><input type='text' size =34 class='input-text' 	name = 'descripcionnqx'   value =\"".$_REQUEST['descripcionnqx']."\"        ></td>" ;
						$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarnqx\" type=\"submit\" value=\"BUSCAR\"></td>";
						$this->salida.="</tr>";
						$this->salida.="</table><br>";	
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida.="</table>";
						$this->salida.="</form>";	//hasta aqui lo que inserte	
						$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaTiposCargos');
						$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
						$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></p>";
						$this->salida .= ThemeCerrarTablaSubModulo();
						return true;
	}
	
 function frmForma_Seleccion_No_Qx($vectorA)
 {
					$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS NO QUIRURGICOS');
					$this->DatosCompletos();							
					$accion1=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'Busqueda_Avanzada_NO_Quirurgicos','Ofnqx'=>$_REQUEST['Ofnqx'],'paso1'=>$_REQUEST['paso1nqx'],
					'criterio1nqx'=>$_REQUEST['criterio1nqx'],
					'cargonqx'=>$_REQUEST['cargonqx'],
					'descripcionnqx'=>$_REQUEST['descripcionnqx']));
					$this->salida .= "<form name=\"formadesnqx\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td colspan=\"1\" width=\"10%\">TIPO:</td>";
					$this->salida.="<td colspan=\"4\" width=\"65%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1nqx'  class =\"select\">";
					$this->salida.="<option value = '-1' selected>Todos</option>";				 
					$categoria = $this->tiposNoQx();
					for($i=0;$i<sizeof($categoria);$i++)
					{
						if (($_REQUEST['criterio1nqx'])  != $categoria[$i][tipo_cargo])
							{
								$this->salida.="<option value = '".$categoria[$i][tipo_cargo]."'>".$categoria[$i][descripcion]."</option>";
							}
						else
							{
								$this->salida.="<option value = '".$categoria[$i][tipo_cargo]."' selected >".$categoria[$i][descripcion]."</option>";
							}
					}							
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="</tr>";							
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">CARGO:</td>";
					$this->salida .="<td width=\"15%\" align='center'><input type='text' size =10  maxlengh = 10 class='input-text' size = 10 maxlength = 10	name = 'cargonqx'  value =\"".$_REQUEST['cargonqx']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"34%\" align='center'><input type='text' size =35 class='input-text' 	name = 'descripcionnqx'   value =\"".$_REQUEST['descripcionnqx']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarnqx\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";					
					$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'insertar_variasNoQx'));
					$this->salida .= "<form name=\"formadesnqx\" action=\"$accion\" method=\"post\">";
        	if ($vectorA)
          {
             $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"15%\">TIPO</td>";
							$this->salida.="  <td width=\"10%\">CARGO</td>";
							$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
							//cambio dar
							$this->salida.="  <td width=\"5%\">CANTIDAD</td>";
							//fin cambio dar
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
              for($i=0;$i<sizeof($vectorA);$i++)
							{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
								$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
								$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
								//cambio dar
								if ($vectorA[$i][sw_cantidad]== 1)
								{
									$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' readonly class='input-text'  size = 4 maxlength = 3 name = 'cantidadnqx$i'  value =\"".$vectorA[$i][sw_cantidad]."\"></td>" ;
								}
								else
								{
									if(empty($vectorA[$i][sw_cantidad]))
									{  $vectorA[$i][sw_cantidad]=1;  }
									$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' class='input-text'  size = 4 maxlength = 3 name = 'cantidadnqx$i'  value = \"".$vectorA[$i][sw_cantidad]."\"></td>" ;
								}
								//cambio dar
								$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opnqx[$i]' value = ".$vectorA[$i][cargo]."></td>";
								$this->salida.="</tr>";
							}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"5\"><input class=\"input-submit\" name=\"guardarnqx\" type=\"submit\" value=\"GUARDAR\"></td>";
							$this->salida.="</tr>";
					 		$this->salida.="</table><br>";
             	$var=$this->RetornarBarraProcedimientosNoQx_Avanzada();
							if(!empty($var))
								{
									$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
									$this->salida .= "  <tr>";
									$this->salida .= "  <td width=\"100%\" align=\"center\">";
									$this->salida .=$var;
									$this->salida .= "  </td>";
									$this->salida .= "  </tr>";
									$this->salida .= "  </table><br>";
								}
					}
      		$this->salida .= "</form>";
  				//BOTON VOLVER
					$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','NoQx');
					$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volvernqx\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
 }	
 
 	function RetornarBarraProcedimientosNoQx_Avanzada()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1nqx'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'Busqueda_Avanzada_NO_Quirurgicos','Ofnqx'=>$_REQUEST['Ofnqx'],'paso1'=>$_REQUEST['paso1nqx'],
		'criterio1nqx'=>$_REQUEST['criterio1nqx'],
		'cargonqx'=>$_REQUEST['cargonqx'],
		'descripcionnqx'=>$_REQUEST['descripcionnqx']));		
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset(1)."&paso1nqx=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($paso-1)."&paso1nqx=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($i)."&paso1nqx=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($paso+1)."&paso1nqx=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($numpasos)."&paso1nqx=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($i)."&paso1nqx=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($paso+1)."&paso1nqx=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofnqx=".$this->CalcularOffset($numpasos)."&paso1nqx=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofnqx'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	//cambio dar el ultimo parametro
	function frmForma_Modificar_ObservacionNoQx($hc_os_solicitud_id, $cargo, $descripcion, $observacion, $vectorD, $cantidad,$sw_cantidad)
	{
				$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS NO QUIRURGICOS');
				$this->DatosCompletos();						
				$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'modificar','hc_os_solicitud_idnqx' => $hc_os_solicitud_id));
				$this->salida .= "<form name=\"formadesnqx\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"3\">OBSERVACION</td>";
				$this->salida.="</tr>";	
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"15%\">CARGO</td>";
				$this->salida.="  <td width=\"65%\">DESCRIPCION</td>";
				//cambio dar
				$this->salida.="  <td width=\"15%\">CANTIDAD SOLICITADA</td>";
				//fin cambio dar
				$this->salida.="</tr>";	
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">$cargo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";
				//cambio dar
				if ($sw_cantidad == 1)
				{
					$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' readonly class='input-text'  size = 4 maxlength = 3 name = 'cantidadnqx'  value =\"".$cantidad."\"></td>" ;
				}
				else
				{
					$this->salida.="<td align=\"center\" width=\"5%\"><input type='text' class='input-text'  size = 4 maxlength = 3 name = 'cantidadnqx'  value =\"".$cantidad."\"></td>" ;
				}
				//fin cambio dar
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
				$this->salida .="<td width=\"65%\" align='center' colspan=\"2\"><textarea class='textarea' name = 'obsnqx' cols = 100 rows = 3>$observacion</textarea></td>" ;
				$this->salida.="</tr>";
				$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
				if ($diag)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
					$this->salida.="<td width=\"65%\" colspan=\"2\">";
					$this->salida.="<table>";	
					for($i=0;$i<sizeof($diag);$i++)
					{
							$this->salida.="<tr class=\"$estilo\">";
							$accionE=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'eliminar_diagnostico','hc_os_solicitud_idnqx' => $hc_os_solicitud_id, 'codigonqx' => $diag[$i][diagnostico_id],
							'hc_os_solicitud_idnqx'=>$_REQUEST['hc_os_solicitud_idnqx'],
							'cargonqx'=>$_REQUEST['cargonqx'],
							'descripcionnqx'=>$_REQUEST['descripcionnqx'],
							'observacionnqx'=>$_REQUEST['observacionnqx']
							,'cantidadnqx'=>$_REQUEST['cantidadnqx'],'sw_cantidadnqx'=>$_REQUEST['sw_cantidadnqx']));
							$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
							$this->salida.="<tr>";
					}
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";
				}	
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarnqx\" type=\"submit\" value=\"GUARDAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "</form>";	
				//nueva forma
				$accionD=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'Busqueda_Avanzada_Diagnosticos','Ofnqx'=>$_REQUEST['Ofnqx'],'paso1nqx'=>$_REQUEST['paso1nqx'],
				'codigonqx'=>$_REQUEST['codigonqx'],
				'diagnosticonqx'=>$_REQUEST['diagnosticonqx'],	
				'hc_os_solicitud_idnqx'=>$_REQUEST['hc_os_solicitud_idnqx'],
				'cargonqx'=>$_REQUEST['cargonqx'],
				'descripcionnqx'=>$_REQUEST['descripcionnqx'],
				'observacionnqx'=>$_REQUEST['observacionnqx'],
				'cantidadnqx'=>$_REQUEST['cantidadnqx'],'sw_cantidadnqx'=>$_REQUEST['sw_cantidadnqx']));
				$this->salida .= "<form name=\"formadesnqx\" action=\"$accionD\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
				$this->salida.="</tr>";	
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"4%\">CODIGO:</td>";	
				$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigonqx'></td>" ;
				//la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigonqx']."\"    ></td>" ;
				$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticonqx'   value =\"".$_REQUEST['diagnosticonqx']."\"        ></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarnqx\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";			
							
				$accionI=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'insertar_varios_diagnosticos',
				'hc_os_solicitud_idnqx'=>$_REQUEST['hc_os_solicitud_idnqx'],
				'cargonqx'=>$_REQUEST['cargonqx'],
				'descripcionnqx'=>$_REQUEST['descripcionnqx'],
				'observacionnqx'=>$_REQUEST['observacionnqx'],
				'cantidadnqx'=>$_REQUEST['cantidadnqx'],'sw_cantidadnqx'=>$_REQUEST['sw_cantidadnqx']));
				$this->salida .= "<form name=\"formadesnqx\" action=\"$accionI\" method=\"post\">";
				if($vectorD)
				{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"10%\">CODIGO</td>";
						$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
						$this->salida.="  <td width=\"5%\">OPCION</td>";
						$this->salida.="</tr>";
						for($i=0;$i<sizeof($vectorD);$i++)
						{
								$codigo          = $vectorD[$i][diagnostico_id];
								$diagnostico    = $vectorD[$i][diagnostico_nombre];

								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";

								$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
								$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
								$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opDnqx[$i]' value = ".$hc_os_solicitud_id.",".$codigo."></td>";
								$this->salida.="</tr>";

							}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarnqx\" type=\"submit\" value=\"GUARDAR\"></td>";
							$this->salida.="</tr>";
							$this->salida.="</table><br>";
							$var=$this->RetornarBarraDiagnosticos_AvanzadaNoQx();
							if(!empty($var))
							{
									$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
									$this->salida .= "  <tr>";
									$this->salida .= "  <td width=\"100%\" align=\"center\">";
									$this->salida .=$var;
									$this->salida .= "  </td>";
									$this->salida .= "  </tr>";
									$this->salida .= "  </table><br>";
							}
						}	
						$this->salida .= "</form>";
						//BOTON VOLVER
						$accionV=ModuloGetURL('app','CentroAutorizacionSolicitud','user','NoQx');
						$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
						$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volvernqx\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
						$this->salida .= ThemeCerrarTablaSubModulo();
						return true;
	}
	
	function RetornarBarraDiagnosticos_AvanzadaNoQx()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1nqx'];
		if(empty($paso))
		{
			$paso=1;
		}
		
		$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','GetFormaNoQx',array('accionnqx'=>'Busqueda_Avanzada_Diagnosticos','Ofnqx'=>$_REQUEST['Ofnqx'],'paso1nqx'=>$_REQUEST['paso1nqx'],
		'conteonqx'=>$this->conteo,'paso1nqx'=>$_REQUEST['paso1nqx'],
		'codigonqx'=>$_REQUEST['codigonqx'],
		'diagnosticonqx'=>$_REQUEST['diagnosticonqx'],
		'hc_os_solicitud_idnqx'=>$_REQUEST['hc_os_solicitud_idnqx'],
		'cargonqx'=>$_REQUEST['cargonqx'],
		'descripcionnqx'=>$_REQUEST['descripcionnqx'],
		'observacionnqx'=>$_REQUEST['observacionnqx']));
						
		/*$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accionnqx'=>'Busqueda_Avanzada_Diagnosticos',
		'conteonqx'=>$this->conteo,'paso1nqx'=>$_REQUEST['paso1nqx'],
		'codigonqx'=>$_REQUEST['codigonqx'],
		'diagnosticonqx'=>$_REQUEST['diagnosticonqx'],
		'hc_os_solicitud_idnqx'=>$_REQUEST['hc_os_solicitud_idnqx'],
			 'cargonqx'=>$_REQUEST['cargonqx'],
			 'descripcionnqx'=>$_REQUEST['descripcionnqx'],
			 'observacionnqx'=>$_REQUEST['observacionnqx']));*/

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofnqx'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}
	/**********************************************************************************
	*
	***********************************************************************************/
	function MostrarEventosSoat()
	{
		$paciente = $this->BuscarNombrePaci($this->TipoDoc,$this->Documento);
		
		$this->salida  = ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE');
		if(!empty($paciente))
		{
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida	.= "	<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
			$this->salida .= "		".$this->SetStyle("MensajeError");
			$this->salida	.= "	</table>";
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">\n";
			$this->salida .= "  	<tr>\n";
			$this->salida .= "  		<td>\n";
			$this->salida .= "  			<fieldset><legend class=\"field\">EVENTOS SOAT DEL PACIENTE</legend>\n";
			$this->salida .= "      		<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "      			<tr class=modulo_list_claro>\n";
			$this->salida .= "      				<td class=\"modulo_table_list_title\" width=\"15%\">DOCUMENTO:</td>\n";
			$this->salida .= "      				<td width=\"25%\">\n";
			$this->salida .= "      						<b>".$this->TipoDoc."".' - '."".$this->Documento."</b>\n";
			$this->salida .= "      				</td>\n";
			$this->salida .= "      				<td class=\"modulo_table_list_title\" width=\"10%\">NOMBRE:</td>\n";
			$this->salida .= "      				<td width=\"50%\">\n";
			$this->salida .= "      					<b>".$paciente['apellidos']." ".$paciente['nombres']."</b>\n";
			$this->salida .= "      				</td>\n";
			$this->salida .= "      			</tr>\n";
			$this->salida .= "      		</table><br>\n";
			
			$evensoat = $this->BuscarEventoSoatPaciente($this->TipoDoc,$this->Documento);
	
			if(!empty($evensoat))
			{
				$this->salida .= "<form name=\"formadmin\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "      		<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "      			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "      				<td width=\"3%\">No.</td>\n";
				$this->salida .= "      				<td width=\"9%\" >FECHA</td>\n";
				$this->salida .= "      				<td width=\"9%\" >HORA</td>\n";
				$this->salida .= "      				<td width=\"20%\">POLIZA</td>\n";
				$this->salida .= "      				<td width=\"30%\">ASEGURADORA</td>\n";
				$this->salida .= "      				<td width=\"11%\">SALDO</td>\n";
				$this->salida .= "      				<td width=\"9%\" >ELEGIR</td>\n";
				$this->salida .= "      			</tr>\n";
	
				$k=1;				
				
				for($i = 0; $i<sizeof($evensoat); $i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro';  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])? $k++:	$k=1;
					
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['evento']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['fecha_accidente']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['hora_accidente']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['poliza']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"center\">".number_format(($evensoat[$i]['saldo']), 2, ',', '.')."</td>\n";
					$this->salida .= "				<td align=\"center\">\n";
					$this->salida .= "				<input type='radio' name='eligevento' value=\"".$evensoat[$i]['evento']."\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "    </table>\n";
				$this->salida .= "	</fieldset>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= "<table width=\"50%\" align=\"center\" >\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td>\n";
				$this->salida .= "	</form>\n";
				$this->salida .= "  <form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
				$this->salida .= "		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\"></td>";
				$this->salida .= "	</form>\n";
				$this->salida .= "  </tr>\n";
				$this->salida .= "</table>\n";
			}
			else
			{
				$this->salida .= "    <table border=\"0\" width=\"100%\" align=\"center\" >\n";
				$this->salida .= "    	<tr>\n";
				$this->salida .= "				<td aling=\"justify\">\n";
				$this->salida .= "					<b class=\"label_error\">EL PACIENTE NO POSEE UN EVENTO SOAT RELACIONADO, SE DEBE CREAR UN ENVENRTO PARA SEGUIR CON LA ADMISION</b></center><br><br>\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "    </table>\n";
				
				$this->salida .= "	</fieldset>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "    	<form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
				$this->salida .= "				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>";
				$this->salida .= "			</form>\n";
				$this->salida .= "   	</tr>\n";
				$this->salida .= "	</table>\n";
			}

		}
		else
		{
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" >\n";
			$this->salida .= "   	<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<b class=\"label_error\">EL PACIENTE NO SE ENCUENTRA REGISTRADO EN BASE DE DATOS</b></center><br><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "  </table><br>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "    	<form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>";
			$this->salida .= "			</form>\n";
			$this->salida .= "   	</tr>\n";
			$this->salida .= "	</table>\n";
		}
		$this->salida .= ThemeCerrarTabla();
	}
//-----------------------------------------------------------------------------------
}//fin clase

?>

