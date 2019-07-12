
<?php

/**
 * $Id: app_EstacionE_Medicamentos_userclasses_HTML.php,v 1.26 2005/10/19 16:21:03 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte de medicamentos del paciente) 
 */


/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_userclasses_HTML.php
*
//*
**/

class app_EstacionE_Medicamentos_userclasses_HTML extends app_EstacionE_Medicamentos_user
{
	function app_EstacionE_ControlPacientes_HTML()
	{
		$this->app_EstacionE_ControlPacientes_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de SOAT
/*	function PrincipalCartera2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['carter']);
		if($this->UsuariosCartera()==false)
		{
			return false;
		}
		return true;
	}*/




 /*funcion del mod estacioe_medicamentos*/
		/*
		*		FrmInsumosPacientes
		*
		*		Muestra un listado de los pacientes de la estacion para escoger al que se les realizará la solicitud
		*
		*		@Author Arley Velasquez Castillo
		*		@access Private
		*		@param array datos de la estacion
		*		@return bool
		*/
		function FrmInsumosPacientes($datos_estacion)
		{
			$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$datos_estacion['estacion_id']));

			if($datoscenso === "ShowMensaje")
			{
				$mensaje = "LA ESTACI&Oacute;N [ ".$datos_estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
				$titulo = "MENSAJE";
				$boton = "REGRESAR";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->FormaMensaje($mensaje,$titulo,$href,$boton);
				return true;
			}
			if(!$datoscenso){
				return false;
			}
			if(!empty($datoscenso))
			{
				$this->salida .= ThemeAbrirTabla("SOLICITUD DE INSUMOS A LOS PACIENTES DE LA ESTACION - [ ".$datos_estacion['descripcion5']." ]");
				foreach($datoscenso as $key => $value)
				{
					if($key == "hospitalizacion")
					{
						$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
						$this->salida .= "	<tr class=\"modulo_table_list_title\"><td colspan='7' height='30'>PACIENTES EN HOSPITALIZACI&Oacute;N</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "		<td>HABITACION</td>\n";
						$this->salida .= "		<td>CAMA</td>\n";
						$this->salida .= "		<td>PACIENTE</td>\n";
						$this->salida .= "		<td>ID</td>\n";
						$this->salida .= "		<td>INGRESO</td>\n";
						$this->salida .= "		<td>TIEMPO<BR>HOSPITALIZACION</td>\n";
						$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
						$this->salida .= "	</tr>\n";

						foreach($value as $A => $B)
						{
							if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
							$this->salida .= "<tr class='$estilo'>\n";
							$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
							$this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$B[tipo_id_paciente]." ".$B[paciente_id]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$B[fecha_ingreso]."</td>\n";

							$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);

							$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
							$href=ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmSolicitarInsumosPaciente',array("estacion"=>$datos_estacion,"ingreso"=>$B['ingreso']));
							$this->salida .= "	<td align=\"center\"><a href=\"$href\">Solicitar Insumos</a></td>\n";
							$this->salida .= "</tr>\n";
						}
						$this->salida .= "</table><br>\n";
						$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES HOSPITALIZACION = ".sizeof($datoscenso[hospitalizacion])."<br>\n";
					}//fin formato hospitalizacio
				}//fin foreach

				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);
				return true;
			}
		}
		/*funcion del mod estacione-medicamentos*/



/**
	*		FormaMensaje => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
		$this->salida .= ThemeAbrirTabla($titulo)."<br>";
		$this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje






	 /*funcion del mod estacione_medicmanetos
	/**
	*		FrmBuscaInsumosPorRecibir
	*
	*		Formulario que busca completo o por cama los pacientes que tienen despachados las solicitudes
	*		de insumos y permite recibirlos
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => tipo de busqueda= completa o por cama
	*		@param string => lo que se busca Ej en el caso de cama : numero de la cama
	*		@param array => con los datos de la estacion
	*		@return boolean
	*/
	function FrmBuscaInsumosPorRecibir($TipoBusqueda,$ItemBusqueda,$datos_estacion)
	{
		$action=ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmBuscaInsumosPorRecibir',array("datos_estacion"=>$datos_estacion));
		$this->salida = "<form name=\"formabuscar\" action=\"$action\" method=\"post\">\n";
		$this->salida .= ThemeAbrirTabla('INSUMOS PENDIENTES POR RECIBIR [ '.$datos_estacion[descripcion5].' ]');
		$this->salida .= "<br>\n";
		$this->salida .= "	<table name='contenedor' width=\"95%\" border=\"0\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<fieldset><legend class=\"field\">TIPO DE BUSQUEDA</legend>\n";
		$this->salida .= "					<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
		$this->salida .= "						<tr align=\"center\">\n";
		$this->salida .= "							<td><select name=\"TipoBusqueda\" class=\"select\">\n";

		if($TipoBusqueda == "Completa") {$select2 = "Selected";}
		if($TipoBusqueda == "Cama") {$select4 = "Selected"; $titulo = "BUSCAR INSUMOS PENDIENTES DE LA CAMA"; }
		if($TipoBusqueda == "Paciente") {$select1 = "Selected"; $titulo = "BUSCAR INSUMOS PENDIENTES POR PACIENTE"; }

		$this->salida .= "										<option value=\"Completa\" $select2>Completa</option>\n";
		$this->salida .= "										<option value=\"Cama\" $select4>Por Cama</option>\n";
		$this->salida .= "										<option value=\"Paciente\" $select1>Por Paciente</option>\n";
		$this->salida .= "									</select>\n";
		$this->salida .= "							</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"SubmitTipoBusqueda\" value=\"BUSCAR\"><br></td></tr>\n";
		$this->salida .= "					</table>\n";
		$this->salida .= "				</fieldset>\n";
		$this->salida .= "			</td>\n";

		if($TipoBusqueda === "Cama")//cama espec&iacute;fica
		{
			$datosCamas = $this->GetCamasOcupadasEstacion($datos_estacion);
			$this->salida .= "		<td width=\"60%\">\n";
			$this->salida .= "			<fieldset><legend class=\"field\">$titulo</legend>\n";
			$this->salida .= "				<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
			$this->salida .= "					<tr align=\"center\">\n";
			$this->salida .= "						<td class=\"label\">CAMA</td>\n";
			$this->salida .= "						<td><select name=\"ItemBusqueda\" class=\"select\">\n";
			foreach($datosCamas as $key=>$value)
			{
				if($value[cama] == $ItemBusqueda) {$selected = "selected";} else $selected = "";
				$this->salida .= "								<option value=\"".$value[cama]."\" $selected>".$value[cama]."</option>\n";
			}
			$this->salida .= "								</select>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"SubmitItem\" value=\"BUSCAR MEDICAMENTOS\"><br></td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</fieldset>\n";
			$this->salida .= "		</td>\n";
		}

		if($TipoBusqueda === "Paciente")
		{
			$datosPaciente = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$datos_estacion['estacion_id']));
			$this->salida .= "		<td width=\"60%\">\n";
			$this->salida .= "			<fieldset><legend class=\"field\">$titulo</legend>\n";
			$this->salida .= "				<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
			$this->salida .= "					<tr align=\"center\">\n";
			$this->salida .= "						<td class=\"label\">PACIENTE</td>\n";
			$this->salida .= "						<td><select name=\"ItemBusqueda\" class=\"select\">\n";
			foreach($datosPaciente[hospitalizacion] as $key=>$value)
			{
				if($value[paciente_id].".-.".$value[tipo_id_paciente] == $ItemBusqueda) {$selected = "selected";} else $selected = "";
				$this->salida .= "								<option value=\"".$value[paciente_id].".-.".$value[tipo_id_paciente]."\" $selected>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."</option>\n";
			}
			$this->salida .= "								</select>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"SubmitItem\" value=\"BUSCAR MEDICAMENTOS\"><br></td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</fieldset>\n";
			$this->salida .= "		</td>\n";
		}

		$this->salida .= "		</tr>\n";

		//############################## LISTADO INSUMOS PENDIENTES POR RECIBIR ##############################
		if($TipoBusqueda == "Completa")//|| ($TipoBusqueda == "Cama" && $ItemBusqueda)
		{//$Medicamentos = $this->GetMedicamentosPendientesPorRecibir($datos_estacion);
			$InsumosPendientes = $this->GetInsumosPendientesPorRecibir($datos_estacion);
			if(!$InsumosPendientes){
				return false;
			}
			if(($InsumosPendientes === "ShowMensaje"))
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<tr align=\"center\" class=\"label\">\n";
				$this->salida .= "	<td colspan=\"2\">\n";
				$this->salida .= "		<font color=\"#9C0219\">NO HAY INSUMOS POR RECIBIR</font>\n";
				$this->salida .= "		<br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
				return true;
			}
		}
		elseif($TipoBusqueda == "Cama" && $_REQUEST[SubmitItem])
		{
			$InsumosPendientes = $this->GetInsumosPendientesPorRecibirCama($datos_estacion,$ItemBusqueda);
			if(!$InsumosPendientes){
				return false;
			}
			if(($InsumosPendientes === "ShowMensaje"))
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<tr align=\"center\" class=\"label\">\n";
				$this->salida .= "	<td colspan=\"2\">\n";
				$this->salida .= "		<font color=\"#9C0219\">NO HAY INSUMOS POR RECIBIR DE LA CAMA ESPECIFICADA</font>\n";
				$this->salida .= "		<br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
				return true;
			}
		}
		elseif($TipoBusqueda == "Paciente" && $_REQUEST[SubmitItem])
		{
			$InsumosPendientes = $this->GetInsumosPendientesPorRecibirPaciente($datos_estacion,$ItemBusqueda);
			if(!$InsumosPendientes){
				return false;
			}
			if(($InsumosPendientes === "ShowMensaje"))
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<tr align=\"center\" class=\"label\">\n";
				$this->salida .= "	<td colspan=\"2\">\n";
				$this->salida .= "		<font color=\"#9C0219\">NO HAY INSUMOS POR RECIBIR DEL PACIENTE ESPECIFICADO</font>\n";
				$this->salida .= "		<br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
				return true;
			}
		}



		if(is_array($InsumosPendientes))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$this->salida .= "<tr> \n";
			$this->salida .= "	<td colspan=\"2\"> \n";
			$this->salida .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "			<tr class='modulo_table_title'><td>INSUMOS SOLICITADOS</td></tr>\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\"><td colspan=\"8\" height=\"25\">INSUMOS SOLICITADOS</td><td colspan='3'>INSUMOS DEPACHADOS</td></tr>\n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "						<td>RECIBIR</td>\n";
			$this->salida .= "						<td>SOLICITUD <br> FECHA</td>\n";
			$this->salida .= "						<td>HAB.</td>\n";
			$this->salida .= "						<td>CAMA</td>\n";
			$this->salida .= "						<td>PACIENTE</td>\n";
			$this->salida .= "						<td>ID.</td>\n";
			$this->salida .= "						<td>MEDICAMENTO<br>SOLICITADO</td>\n";
			$this->salida .= "						<td>CANT.<BR>SOL.</td>\n";
			$this->salida .= "						<td>DOCUMENTO <br> BODEGA</td>\n";
			$this->salida .= "						<td>MEDICAMENTO<br>DESPACHADO</td>\n";
			$this->salida .= "						<td>CANT.<BR>DESP.</td>\n";
			$this->salida .= "					</tr>\n";
			$i = 0;
			foreach($InsumosPendientes as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "				<tr class=\"$estilo\">\n";
				$this->salida .= "					<td rowspan='".sizeof($value)."' align=\"center\"><input type='checkbox' name=\"Solicitudes[]\" value=\"".$value[0]['solicitud_sol']."\"></td>\n";//.".-.".$value[0]['documento_des']
				$this->salida .= "					<td rowspan='".sizeof($value)."' align=\"center\" width='60'>[ ".$value[0]['solicitud_sol']." ]<br> ".$value[0]['fecha_sol']."</td>\n";
				$k=0;
				foreach($value  as $key1 =>$solicitudes)
				{
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['pieza']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['cama']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['primer_nombre']." ".$solicitudes['segundo_nombre']." ".$solicitudes['primer_apellido']." ".$solicitudes['segundo_apellido']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['tipo_id_paciente']." ".$solicitudes['paciente_id']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">[ ".$solicitudes['codigo_producto_sol']." ] ".$solicitudes['nommedicamento_sol']." ".$solicitudes['ff_sol']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".number_format($solicitudes['cant_solicitada_sol'],2,',','.')."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
					if(empty($solicitudes[documento_des])){
						$this->salida .= "--";
					}
					else{
						$this->salida .= $solicitudes[documento_des]."<input type='hidden' name='docsSolicitud[".$value[0]['solicitud_sol']."][]' value='".$solicitudes[documento_des]."'>\n";
					}
					$this->salida .= "				</td>\n";

					$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
					if (is_null($solicitudes[solicitud_id_des])){
						$this->salida .= "			--\n";
					}
					elseif (!is_null($solicitudes[solicitud_id_des]) && is_null($solicitudes[fecha_solicitud_des])){
						$this->salida .= "			".$solicitudes[reemplazo]." ".$solicitudes[nommedicamento_des]." ".$solicitudes[ff_des]."\n";
						//$this->salida .= "			<input type='hidden' name='Medicamentos[".$value[0]['solicitud_sol']."][medicamento_id][]' value='".$solicitudes[reemplazo]."'>\n";
						$Medicamentos[$k][codigo_producto] = $solicitudes[reemplazo];
					}
					else{
						$this->salida .= "			[ ".$solicitudes[codigo_producto_des]." ] ".$solicitudes[nommedicamento_des]." ".$solicitudes[ff_des]."\n";
						$Medicamentos[$k][codigo_producto] = $solicitudes[codigo_producto_des];
					}
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
					if(empty($solicitudes[cant_enviada])){
						$this->salida .= "--";
					}
					else{
						$this->salida .= number_format($solicitudes[cant_enviada],2,',','.');
						$Medicamentos[$k][cantidad] = $solicitudes[cant_enviada];
						$Medicamentos[$k][ingreso] = $solicitudes[ingreso];
						$k++;
					}
					$this->salida .= "				</td>\n";
					$this->salida .= "			</tr>\n";
				}//FIN FORACH SLICITUDES
				$this->salida .= "			<input type='hidden' name='vectorMedicamentos[".$value[0]['solicitud_sol']."][]' value='".urlencode(serialize($Medicamentos))."'>\n";
				unset($Medicamentos);
				$i++;
			}//fin foreach solicitudes
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</td>\n";
			$this->salida .= "</tr>\n";
		}//fin sizeof(MedicamentosABodega)

		if((sizeof($InsumosPendientes)))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></TR>\n";
			$this->salida .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"SubmitRecibir\" value=\"RECIBIR SELECCIONADOS\" class=\"input-submit\"></td></tr>\n";
			$this->salida .= "</form>\n";
		}

		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<tr align='center' class='normal_10'><br><td colspan=\"2\"><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td></TR>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FrmBuscaInsumosPorRecibir

/*funcion del mod estacione_medicmanetos




/**
	*		VerMedicamentosPorDevolverPaciente
	*
	*		Muestra para el paciente seleccionado  los medicamentos que tiene el paciente, permitiendo
	*		a la enfermera hacer devolucion de dichos medicamentos
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos del paciente y medicamentos
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function VerMedicamentosPorDevolverPaciente($datos,$datos_estacion)
	{
		if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion)){
			return false;
		}
		$this->salida .= "<SCRIPT>\n";
		$this->salida .= "function Seleccionartodos(frm,x){";
		$this->salida .= "  if(x==true){";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }else{";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>\n";
		$this->salida .= ThemeAbrirTabla('DEVOLUCION DE MEDICAMENTOS [ '.$datos_estacion[descripcion5].' ]');
		$action = ModuloGetURL('app','EstacionE_Medicamentos','user','SetDevolucionMedicamentos',array("ingreso"=>$datos[1][ingreso],"datos_estacion"=>$datos_estacion));
		$this->salida .= "<form name='DevMed' method='POST' action='$action'> \n";
		$this->salida .= "<table width='100%' align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td><br>\n";
		$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "				<tr class=\"modulo_table_title\">\n";
		$this->salida .= "					<td>HABITACION</td>\n";
		$this->salida .= "					<td>CAMA</td>\n";
		$this->salida .= "					<td>PACIENTE</td>\n";
		$this->salida .= "					<td>ID</td>\n";
		$this->salida .= "					<td>CUENTA</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "					<td>".$datos[1][pieza]."</td>\n";
		$this->salida .= "					<td>".$datos[1][cama]."</td>\n";
		$this->salida .= "					<td>".$datos[1][primer_nombre]." ".$datos[1][segundo_nombre]." ".$datos[1][primer_apellido]." ".$datos[1][segundo_apellido]."</td>\n";
		$this->salida .= "					<td>".$datos[1][tipo_id_paciente]." ".$datos[1][paciente_id]."</td>\n";
		$this->salida .= "					<td>".$datos[1][numerodecuenta]."</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
		$this->salida .= "	<tr> \n";
		$this->salida .= "		<td colspan=\"2\"> \n";
		$this->salida .= "			<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td>FECHA CARGO</td>\n";
		$this->salida .= "					<td>OBSERVACIONES</td>\n";
		$this->salida .= "					<td>MEDICAMENTO / INSUMO</td>\n";
		//$this->salida .= "					<td>TIPO</td>\n";
		$this->salida .= "					<td>CANTIDAD </td>\n";
		$this->salida .= "					<td>CANT. A DEVOLVER</td>\n";
		$this->salida .= "					<td valign='middle'>SEL.&nbsp;<input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td>\n";
		$this->salida .= "				</tr>\n";
		$i=0;

		foreach($datos[0] as $key => $value)
		{
			if(($i) % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
			$this->salida .= "				<tr class=\"$estilo\" align='center'>\n";
			if(!$i){
				$this->salida .= "				<td rowspan='".sizeof($datos[0])."' width='95' align='left'><input type='text' name='FechaCargo' value='".date("d-m-Y")."' size='10' maxlength='10' readonly class='input-text'>".ReturnOpenCalendario('DevMed','FechaCargo','-')."</td>\n";
				$this->salida .= "				<td rowspan='".sizeof($datos[0])."'><textarea name=\"Observaciones\" class=\"textarea\"></textarea></td>\n";
			}
			$this->salida .= "					<td align='left'>".$value[codigo_producto]." => ".$value[nommedicamento]." ".$value[concentracion]." ".$value[nomff]."</td>\n";
			/*$TipoFacturado = array(0=>"Consumo",1=>"Facturado");
			$this->salida .= "					<td>".$TipoFacturado[$value[facturado]]."</td>\n";*/
			if(!empty($value[suma])){
				$Cant = $value[suma];
			}
			else{
				$Cant = $value[sum];
			}
			$this->salida .= "					<td align='right'>".$Cant."</td>\n";
			$this->salida .= "					<td><input type='text' name='CantDevolver[]' value='".$Cant."' size='15' align='right' class='input-text'></td>\n";
			$this->salida .= "					<td><input type='checkbox' name='CheckMedicamentos[]' value='".$value[codigo_producto].".-.".$Cant.".-.".$i.".-.".$datos[1][numerodecuenta]."'></td>\n";
			$this->salida .= "				</tr>\n";
			$i++;
		}
		$this->salida .= "			</table>\n";
		$this->salida .= "		</td> \n";
		$this->salida .= "	</tr> \n";
		$this->salida .= "	<tr align='center'><td><input type='submit' name='Submit' value='DEVOLVER SELECCIONADOS' class='input-submit'>&nbsp;&nbsp;&nbsp;<input type='reset' value='REESTABLECER' class='input-submit'></td></tr>\n";
		$this->salida .= "</table> \n";
		$this->salida .= "</form> \n";
		$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'>\n";
		$this->salida .= "	<a href='".$href."'>Volver a Devoluci&oacute;n</a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "	<a href='".$href."'>Volver al Menu Estaci&oacute;n</a>";
		$this->salida .= "\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin VerMedicamentosPorDevolverPaciente





//<DEASUACIADA>
	/**
	*		BuscaMedicamentosPorRecibir
	*
	*		Formulario que me permite hacer una busqueda completa o por cama de los pacientes que tengan
	*		despachados medicamentos de bodega
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string tipo de busqueda
	*		@param string dato a buscar
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function BuscaMedicamentosPorRecibir($TipoBusqueda,$ItemBusqueda,$datos_estacion)//los parametros son de cuando viene de eliminar solicitud
	{
		$action=ModuloGetURL('app','EstacionE_Medicamentos','user','CallBuscaMedicamentosPorRecibir',array("datos_estacion"=>$datos_estacion));
		$this->salida = "<form name=\"formabuscar\" action=\"$action\" method=\"post\">\n";
		$this->salida .= ThemeAbrirTabla('MEDICAMENTOS PENDIENTES POR RECIBIR [ '.$datos_estacion[descripcion5].' ]');
		$this->salida .= "	<table name='contenedor' width=\"95%\" border=\"0\" align=\"center\">\n";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "				<fieldset><legend class=\"field\">TIPO DE BUSQUEDA</legend>\n";
		$this->salida .= "					<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
		$this->salida .= "						<tr align=\"center\">\n";
		$this->salida .= "							<td><select name=\"TipoBusqueda\" class=\"select\">\n";

		if($TipoBusqueda == "Completa") {$select2 = "Selected";}
		if($TipoBusqueda == "Cama") {$select4 = "Selected"; $titulo = "BUSCAR MEDICAMENTOS PENDIENTES DE LA CAMA"; }

		$this->salida .= "										<option value=\"Completa\" $select2>Completa</option>\n";
		$this->salida .= "										<option value=\"Cama\" $select4>Por Cama</option>\n";
		$this->salida .= "									</select>\n";
		$this->salida .= "							</td>\n";
		$this->salida .= "						</tr>\n";
		$this->salida .= "						<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"SubmitTipoBusqueda\" value=\"BUSCAR\"><br></td></tr>\n";
		$this->salida .= "					</table>\n";
		$this->salida .= "				</fieldset>\n";
		$this->salida .= "			</td>\n";

		if($TipoBusqueda === "Cama")//cama espec&iacute;fica
		{
		//	$ItemBusqueda = $_REQUEST['ItemBusqueda'];  //datos a buscar
		//	$SubmitItem = $_REQUEST['SubmitItem'];      //boton del dato a buscar
			$datosCamas = $this->GetCamasOcupadasEstacion($datos_estacion);

			$this->salida .= "			<td width=\"60%\">\n";
			$this->salida .= "				<fieldset><legend class=\"field\">$titulo</legend>\n";
			$this->salida .= "					<table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" align=\"center\">\n";
			$this->salida .= "						<tr align=\"center\">\n";
			$this->salida .= "							<td class=\"label\">CAMA</td>\n";
			$this->salida .= "							<td><select name=\"ItemBusqueda\" class=\"select\">\n";
			foreach($datosCamas as $key=>$value)
			{
				if($value[cama] == $ItemBusqueda) {$selected = "selected";} else $selected = "";
				$this->salida .= "								<option value=\"".$value[cama]."\" $selected>".$value[cama]."</option>\n";
			}
			$this->salida .= "								</select>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"SubmitItem\" value=\"BUSCAR MEDICAMENTOS\"><br></td></tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
		}
		$this->salida .= "		</tr>\n";
//		$this->salida .= "	</form>\n";

		//############################## LISTADO MEDICAMENTOS PENDIENTES POR RECIBIR ##############################

		if($TipoBusqueda == "Completa")//|| ($TipoBusqueda == "Cama" && $ItemBusqueda)
		{//$Medicamentos = $this->GetMedicamentosPendientesPorRecibir($datos_estacion);
			$MedicamentosABodega = $this->GetMedicamentosPendientesPorRecibir($datos_estacion);
			$MedicamentosAPaciente = $this->GetMedicamentosPendientesPorRecibirPaciente($datos_estacion,$ItemBusqueda);
			//$MezclasABodega = $this->GetMedicamentosMezclasPendientesPorRecibir($datos_estacion);
			$MezclasABodega=true;
			if(!$MedicamentosABodega || !$MedicamentosAPaciente || !$MezclasABodega){
				return false;
			}
			if(($MedicamentosABodega === "ShowMensaje") && ($MedicamentosAPaciente === "ShowMensaje") && ($MezclasABodega === "ShowMensaje"))
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<tr align=\"center\" class=\"label\">\n";
				$this->salida .= "	<td colspan=\"2\">\n";
				$this->salida .= "		<font color=\"#9C0219\">NO HAY MEDICAMENTOS POR RECIBIR</font>\n";
				$this->salida .= "		<br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
				return true;
			}
		}
		elseif($TipoBusqueda == "Cama" && $ItemBusqueda)
		{//$vector = $this->BuscaOrdenaMedicamentosCama($Medicamentos,$ItemBusqueda); esto era lo antiguio
			$MedicamentosABodega = $this->GetMedicamentosPendientesPorRecibirCama($datos_estacion,$ItemBusqueda);
			$MedicamentosAPaciente = $this->GetMedicamentosPendientesPorRecibirCamaPaciente($datos_estacion,$ItemBusqueda);
			//$MezclasABodega = $this->GetMedicamentosMezclasPendientesPorRecibirCama($datos_estacion,$ItemBusqueda);
			$MezclasABodega=true;

			if(!$MedicamentosABodega || !$MedicamentosAPaciente || !$MezclasABodega){
				return false;

			}
			if(($MedicamentosABodega === "ShowMensaje") && ($MedicamentosAPaciente === "ShowMensaje") && ($MezclasABodega === "ShowMensaje"))
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<tr align=\"center\" class=\"label\">\n";
				$this->salida .= "	<td colspan=\"2\">\n";
				$this->salida .= "		<font color=\"#9C0219\">NO HAY MEDICAMENTOS POR RECIBIR DE LA CAMA ESPECIFICADA</font>\n";
				$this->salida .= "		<br><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= themeCerrarTabla();
				return true;
			}
		}
		//$MezclasABodega
		if(is_array($MedicamentosABodega))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$this->salida .= "<tr> \n";
			$this->salida .= "	<td colspan=\"2\"> \n";
			$this->salida .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "			<tr class='modulo_table_title'><td>MEDICAMENTOS SOLICITADOS A BODEGA</td></tr>\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\"><td colspan=\"8\" height=\"25\">MEDICAMENTOS SOLICITADOS</td><td colspan='3'>MEDICAMENTOS DEPACHADOS</td></tr>\n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "						<td>RECIBIR</td>\n";
			$this->salida .= "						<td>SOLICITUD <br> FECHA</td>\n";
			$this->salida .= "						<td>HAB.</td>\n";
			$this->salida .= "						<td>CAMA</td>\n";
			$this->salida .= "						<td>PACIENTE</td>\n";
			$this->salida .= "						<td>ID.</td>\n";
			$this->salida .= "						<td>MEDICAMENTO<br>SOLICITADO</td>\n";
			$this->salida .= "						<td>CANT.<BR>SOL.</td>\n";
			$this->salida .= "						<td>DOCUMENTO <br> BODEGA</td>\n";
			$this->salida .= "						<td>MEDICAMENTO<br>DESPACHADO</td>\n";
			$this->salida .= "						<td>CANT.<BR>DESP.</td>\n";
			$this->salida .= "					</tr>\n";
			$i = 0;
			foreach($MedicamentosABodega as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "				<tr class=\"$estilo\">\n";
				$this->salida .= "					<td rowspan='".sizeof($value)."' align=\"center\"><input type='checkbox' name=\"PedirABodega[]\" value=\"".$value[0]['solicitud_sol']."\"></td>\n";//.".-.".$value[0]['documento_des']
				$this->salida .= "					<td rowspan='".sizeof($value)."' align=\"center\" width='60'>[ ".$value[0]['solicitud_sol']." ]<br> ".$value[0]['fecha_sol']."</td>\n";
				$k=0;
				foreach($value  as $key1 =>$solicitudes)
				{
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['pieza']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['cama']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['primer_nombre']." ".$solicitudes['segundo_nombre']." ".$solicitudes['primer_apellido']." ".$solicitudes['segundo_apellido']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".$solicitudes['tipo_id_paciente']." ".$solicitudes['paciente_id']."</td>\n";
					$this->salida .= "				<td align=\"left\" class=\"$estilo\">[ ".$solicitudes['medicamento_id_sol']." ] ".$solicitudes['nommedicamento_sol']." ".$solicitudes['ff_sol']."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">".number_format($solicitudes['cant_solicitada_sol'],2,',','.')."</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
					if(empty($solicitudes[documento_des])){
						$this->salida .= "--";
					}
					else{
						$this->salida .= $solicitudes[documento_des]."<input type='hidden' name='docsSolicitud[".$value[0]['solicitud_sol']."][]' value='".$solicitudes[documento_des]."'>\n";
					}
					$this->salida .= "				</td>\n";

					$this->salida .= "				<td align=\"left\" class=\"$estilo\">\n";
					if (is_null($solicitudes[solicitud_id_des])){
						$this->salida .= "			--\n";
					}
					elseif (!is_null($solicitudes[solicitud_id_des]) && is_null($solicitudes[fecha_solicitud_des])){
						$this->salida .= "			".$solicitudes[reemplazo]." ".$solicitudes[nommedicamento_des]." ".$solicitudes[ff_des]."\n";
						//$this->salida .= "			<input type='hidden' name='Medicamentos[".$value[0]['solicitud_sol']."][medicamento_id][]' value='".$solicitudes[reemplazo]."'>\n";
						$Medicamentos[$k][medicamento_id] = $solicitudes[reemplazo];
					}
					else{
						$this->salida .= "			[ ".$solicitudes[medicamento_id_des]." ] ".$solicitudes[nommedicamento_des]." ".$solicitudes[ff_des]."\n";
						$Medicamentos[$k][medicamento_id] = $solicitudes[medicamento_id_des];
					}
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
					if(empty($solicitudes[cant_enviada])){
						$this->salida .= "--";
					}
					else{
						$this->salida .= number_format($solicitudes[cant_enviada],2,',','.');
						$Medicamentos[$k][cantidad] = $solicitudes[cant_enviada];
						$Medicamentos[$k][ingreso] = $solicitudes[ingreso];
						$k++;
					}
					$this->salida .= "				</td>\n";
					$this->salida .= "			</tr>\n";
				}//FIN FORACH SLICITUDES
				$this->salida .= "			<input type='hidden' name='vectorMedicamentos[".$value[0]['solicitud_sol']."][]' value='".urlencode(serialize($Medicamentos))."'>\n";
				unset($Medicamentos);
				$i++;
			}//fin foreach solicitudes
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</td>\n";
			$this->salida .= "</tr>\n";
		}//fin sizeof(MedicamentosABodega)

		if(is_array($MezclasABodega))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$this->salida .= "<tr> \n";
			$this->salida .= "	<td colspan=\"2\"> \n";
			$this->salida .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "			<tr class='modulo_table_title'><td>MEZCLAS SOLICITADAS A BODEGA</td></tr>\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\"><td colspan=\"8\" height=\"25\">MEDICAMENTOS SOLICITADOS</td><td colspan='4'>MEDICAMENTOS DEPACHADOS</td></tr>\n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "						<td>RECIBIR</td>\n";
			$this->salida .= "						<td>SOLICITUD <br> FECHA</td>\n";
			$this->salida .= "						<td>HAB.</td>\n";
			$this->salida .= "						<td>CAMA</td>\n";
			$this->salida .= "						<td>PACIENTE</td>\n";
			$this->salida .= "						<td>ID.</td>\n";
			$this->salida .= "						<td>MEZCLA</td>\n";
			$this->salida .= "						<td>MEDICAMENTO</td>\n";
			$this->salida .= "						<td>CANT.<BR>SOL.</td>\n";
			$this->salida .= "						<td>DOCUMENTO <br> BODEGA</td>\n";
			$this->salida .= "						<td>MEDICAMENTO</td>\n";
			$this->salida .= "						<td>CANT.<BR>DESP.</td>\n";
			$this->salida .= "					</tr>\n";
			$i = 0; $cont = 0;
			foreach($MezclasABodega as $key => $value)
			{
				foreach($value as $key1 =>$valor)
				{
					foreach ($valor as $key2 => $valor2)
					{
						if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
						if (!$key2)
						{
							$this->salida .= "				<tr class=\"$estilo\">\n";
							//$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\"><input type='checkbox' name=\"MezclasABodega[]\" value=\"".$key."\"></td>\n";//.".-.".$value[0]['documento_des']
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\"><input type='checkbox' name=\"PedirABodega[]\" value=\"".$key."\"></td>\n";//.".-.".$value[0]['documento_des']
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">[ ".$valor[$key2]['solicitud_sol']." ]<br>".$valor[$key2]['fecha_sol']."</td>\n";
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">".$valor[$key2]['pieza']."</td>\n";
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">".$valor[$key2]['cama']."</td>\n";
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">".$valor[$key2]['primer_nombre']." ".$valor[$key2]['segundo_nombre']." ".$valor[$key2]['primer_apellido']." ".$valor[$key2]['segundo_apellido']."</td>\n";
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">".$valor[$key2]['tipo_id_paciente']." ".$valor[$key2]['paciente_id']."</td>\n";
							$this->salida .= "					<td rowspan='".sizeof($valor)."' align=\"center\" class=\"$estilo\">".$valor[$key2]['mezcla_recetada_id']."</td>\n";
						}
						else{
							$this->salida .= "				<tr class=\"$estilo\">\n";
						}
						$this->salida .= "					<td align=\"left\" class=\"$estilo\">[ ".$valor[$key2]['medicamento_id_sol']." ] ".$valor[$key2]['nommedicamento_sol']." ".$valor[$key2]['ff_sol']."</td>\n";
						$this->salida .= "					<td align=\"center\" class=\"$estilo\">".number_format($valor[$key2]['cant_solicitada_sol'],2,',','.')."</td>\n";
						$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
						if(empty($valor[$key2]['documento_des'])){
							$this->salida .= "--";
						}
						else{
							//$this->salida .= $valor[$key2]['documento_des']."<input type='hidden' name='docsSolicitudMezclas[".$key."][]' value='".$valor[$key2]['documento_des']."'>\n";
							$this->salida .= $valor[$key2]['documento_des']."<input type='hidden' name='docsSolicitud[".$key."][]' value='".$valor[$key2]['documento_des']."'>\n";
						}
						$this->salida .= "				</td>\n";
						$this->salida .= "				<td align=\"left\" class=\"$estilo\">\n";
						if (is_null($valor[$key2][solicitud_id_des])){
							$this->salida .= "			--\n";
						}
						elseif (!is_null($valor[$key2][solicitud_id_des]) && is_null($valor[$key2][fecha_solicitud_des])){
							$this->salida .= "			".$valor[$key2][reemplazo]." ".$valor[$key2][nommedicamento_des]." ".$valor[$key2][ff_des]."\n";
							$Medicamentos[$k][medicamento_id] = $valor[$key2][reemplazo];
						}
						else{
							$this->salida .= "			[ ".$valor[$key2][medicamento_id_des]." ] ".$valor[$key2][nommedicamento_des]." ".$valor[$key2][ff_des]."\n";
							$Medicamentos[$k][medicamento_id] = $valor[$key2][medicamento_id_des];
						}
						$this->salida .= "				</td>\n";
						$this->salida .= "				<td align=\"center\" class=\"$estilo\">\n";
						if(empty($valor[$key2][cant_enviada])){
							$this->salida .= "--";
						}
						else{
							$this->salida .= number_format($valor[$key2][cant_enviada],2,',','.');
							$Medicamentos[$k][cantidad] = $valor[$key2][cant_enviada];
							$Medicamentos[$k][ingreso] = $valor[$key2][ingreso];
							$k++;
						}
						$this->salida .= "				</td>\n";
						$this->salida .= "				</tr>\n";
					}
					$i++;
				}
				$this->salida .= "			<input type='hidden' name='vectorMedicamentos[".$key."][]' value='".urlencode(serialize($Medicamentos))."'>\n";
				//$this->salida .= "			<input type='hidden' name='vectorMedMezclas[".$key."][]' value='".urlencode(serialize($Medicamentos))."'>\n";
				unset($Medicamentos);
			}//foreach($MezclasABodega as $key => $value)
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</td>\n";
			$this->salida .= "</tr>\n";
		}//fin sizeof(MezclasABodega)

		if(is_array($MedicamentosAPaciente))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
			$this->salida .= "<tr> \n";
			$this->salida .= "	<td colspan=\"2\">\n";
			$this->salida .= "		<table width=\"100%\" class=\"modulo_table_list\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "			<tr class='modulo_table_title'><td>MEDICAMENTOS SOLICITADOS AL PACIENTE</td></tr>\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "						<td>RECIBIR</td>\n";
			$this->salida .= "						<td>FECHA</td>\n";
			$this->salida .= "						<td>MEZCLA</td>\n";
			$this->salida .= "						<td>MEDICAMENTO</td>\n";
			$this->salida .= "						<td>CANT <BR> SOLICITADA</td>\n";
			$this->salida .= "						<td>CANT <BR> RECIBIDA</td>\n";
			$this->salida .= "						<td>HAB.</td>\n";
			$this->salida .= "						<td>CAMA</td>\n";
			$this->salida .= "						<td>PACIENTE</td>\n";
			$this->salida .= "						<td>ID.</td>\n";
			$this->salida .= "					</tr>\n";
			/*[solicitud_id]		[ingreso]			 		[bodega]			 				[fecha_solicitud]
				[medicamento_id]	[evolucion_id]		[cant_solicitada]			[numerodecuenta]
				[cama]					 	[pieza]						[paciente_id]					[tipo_id_paciente]
				[primer_nombre]		[segundo_nombre]	[primer_apellido]			[segundo_apellido]
				[documento]				[despachada]
			*/
			
               //para ir disminuyendo el rowspan
			for($i=0; $i<sizeof($MedicamentosAPaciente); $i++)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "			<tr class=\"$estilo\" align=\"center\">\n";

					//$linkRecibir = ModuloGetURL('app','EstacionEnfermeria','user','AceptarDespacho',array("PedirAPaciente"=>$MedicamentosAPaciente[$i],"posicion"=>$i));
					$this->salida .= "				<td align=\"center\"><input type=\"checkbox\" name=\"PedirAPaciente[]\" value=\"".$MedicamentosAPaciente[$i][consecutivo].".-.".$MedicamentosAPaciente[$i][medicamento_id].".-.".$MedicamentosAPaciente[$i][ingreso].".-.".$i.".-.".$MedicamentosAPaciente[$i][mezcla_recetada_id]."\"></td>\n";
					//$this->salida .= "			<td align=\"center\"><a href=\"$linkRecibir\">RECIBIR</a></td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][fecha_solicitud]."</td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][mezcla_recetada_id]."</td>\n";
					$this->salida .= "				<td align='left'>".$MedicamentosAPaciente[$i][medicamento_id]." => ".$MedicamentosAPaciente[$i][descripcion]." ".$MedicamentosAPaciente[$i][nombre]."</td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][cant_solicitada]."</td>\n";
					$this->salida .= "				<td><input type=\"text\" name=\"CantRecibida[]\" size=\"5\" class=\"input-text\" value=\"".$MedicamentosAPaciente[$i][cant_solicitada]."\"></td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][pieza]."</td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][cama]."</td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][primer_nombre]." ".$MedicamentosAPaciente[$i][segundo_nombre]." ".$MedicamentosAPaciente[$i][primer_apellido]." ".$MedicamentosAPaciente[$i][segundo_apellido]."</td>\n";
					$this->salida .= "				<td>".$MedicamentosAPaciente[$i][tipo_id_paciente]." ".$MedicamentosAPaciente[$i][paciente_id]."</td>\n";
					$this->salida .= "			</tr>\n";
			}//FIN FOR $MedicamentosABodega

			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</td>\n";
			$this->salida .= "</tr>\n";
		}//FIN IF $MedicamentosAPaciente

		if((sizeof($MedicamentosABodega)) || (sizeof($MezclasAbodega)) || (sizeof($MedicamentosAPaciente)))
		{
			$this->salida .= "<tr><td colspan=\"2\">&nbsp;</td></TR>\n";
			$this->salida .= "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"SubmitRecibir\" value=\"RECIBIR SELECCIONADOS\" class=\"input-submit\"></td></tr>\n";
			$this->salida .= "</form>\n";
		}

		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<tr align='center'><br><td colspan=\"2\" class='normal_10'><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td></TR>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin BuscaMedicamentos





	/**
	*		VerMedicamentosPorSolicitarPaciente
	*
	*		Muestra los medicamentos ordenados y "vigentes" (sw_estado=2) del paciente seleccionado
	*		no se restringir&aacute; el numero de pedidos por orden ni tampoco las cantidades a solicitar
	*		y solo se pedir&aacute; a la bodega recetada por el medico
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array => datos del paciente
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function VerMedicamentosPorSolicitarPaciente($datosPaciente,$datos_estacion)
	{
		$SolicitadosBodega = $this->GetMedicamentosPendientesSolicitadosBodega($datosPaciente[ingreso],$datos_estacion);
		$SolicitadosPaciente = $this->GetMedicamentosPendientesSolicitadosPaciente($datosPaciente[ingreso],$datos_estacion);
		$Medicamentos = $this->GetMedicamentosRecetados($datosPaciente[ingreso],$datos_estacion);
		//$Mezclas = $this->GetMezclasRecetadas($datosPaciente[ingreso],$datos_estacion);

		//no se porque habia comentareado lo siguiente:
		if (!$Medicamentos and !$Mezclas){
			return false;
		}

		if($Medicamentos === "ShowMensaje" && $Mezclas === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON LOS DETALLES DE LOS MEDICAMENTOS PENDIENTES DEL PACIENTE";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
			$boton = "IR A MEDICAMENTOS PENDIENTES POR CONFIRMAR";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		else
		{//encabezado comun para mediamentos y mezclas
			$this->salida .= ThemeAbrirTabla('LISTADO DE MEDICAMENTOS ORDENADOS - [ '.$datos_estacion[descripcion5].' ]');
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table class=\"modulo_table_title\" align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "				<tr class=\"modulo_table_title\">\n";
			$this->salida .= "					<td>HABITACION</td>\n";
			$this->salida .= "					<td>CAMA</td>\n";
			$this->salida .= "					<td>PACIENTE</td>\n";
			$this->salida .= "					<td>ID</td>\n";
			$this->salida .= "					<td>CUENTA</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td><b>".$datosPaciente[pieza]."</b></td>\n";
			$this->salida .= "					<td><b>".strtoupper($datosPaciente[cama])."</td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[primer_nombre]." ".$datosPaciente[segundo_nombre]." ".$datosPaciente[primer_apellido]." ".$datosPaciente[segundo_apellido]."</b></td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</b></td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[numerodecuenta]."</b></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
			//fin encabezado comun

			if(($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente) )|| ($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega)))
			{
				$action = ModuloGetURL('app','EstacionE_Medicamentos','user','CancelarSolicitudesMedicamentos',array('datosPaciente'=>$datosPaciente,"datos_estacion"=>$datos_estacion));
				$this->salida .= "<form name='CancelarSolicitudesMedicamentos' method=\"POST\" action=\"$action\">\n";
				$this->salida .= "<tr> \n";
				$this->salida .= "	<td colspan=\"2\"> \n";
				$this->salida .= "		<table width=\"100%\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" >\n";
				$this->salida .= "			<tr class=\"modulo_table_title\"><td>MEDICAMENTOS SOLICITADOS</td></tr>\n";
				$this->salida .= "			<tr>\n";
				$this->salida .= "				<td>\n";
				$this->salida .= "					<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			}

			if($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega))
			{
				$this->salida .= "						<tr> \n";
				$this->salida .= "							<td colspan=\"2\"> \n";
				$this->salida .= "								<table width=\"100%\"  border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\"><td colspan=\"7\" height=\"25\">LISTADO DE MEDICAMENTOS SOLICITADOS A BODEGA</td></tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>CANCELAR</td>\n";
				$this->salida .= "										<td>SOLICITUD</td>\n";
				$this->salida .= "										<td>FECHA <br> PEDIDO</td>\n";
				$this->salida .= "										<td>ESTADO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>CANT <BR> SOLICITADA</td>\n";
				$this->salida .= "									</tr>\n";
				/*solicitud_id	consecutivo_d	?column?	medicamento_id	evolucion_id	cant_solicitada	forma_farmaceutica	nommedicamento	ff	fecha_solicitud	sw_estado*/
				//para contar el rowspan
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					$vect[$value[solicitud_id]][0]++;
					$vect[$value[solicitud_id]][1]++;
				}
				$i=0;
				$estados = array(0=>'Sin despacho', 1=>'Despachado', 2=>'Recibido', 3=>'Cancelado');//
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";

					if ((array_key_exists($value[solicitud_id], $vect)) && ($vect[$value[solicitud_id]][0] == $vect[$value[solicitud_id]][1]))
					{
						if($value[sw_estado] == 0){
							$cancelar = "<input type='checkbox' name='CancelarSolicitudBodega[]' value='".$value[solicitud_id]."'>";
						}
						else{
							$cancelar = "&nbsp;";
						}
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$cancelar."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$value[solicitud_id]."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$value[fecha_solicitud]."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$estados[$value[sw_estado]]."</td>\n";
					}
					if(empty($value[mezcla_recetada_id])) { $valor = "---"; }
					else { $valor = $value[mezcla_recetada_id]; }

					$this->salida .= "									<td>".$valor."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[ff]."</td>\n";
					$this->salida .= "									<td>".$value[cant_solicitada]."</td>\n";//.-."..".-."..".-.".."
					$vect[$value[solicitud_id]][1]--;
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
			}

			if($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente))
			{
				$this->salida .= "						<tr> \n";
				$this->salida .= "							<td colspan=\"2\"> \n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\"><td colspan=\"10\" height=\"25\">LISTADO DE MEDICAMENTOS SOLICITADOS AL PACIENTE</td></tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>CANCELAR</td>\n";//&nbsp;<input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\">
				$this->salida .= "										<td>FECHA <br> PEDIDO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>CANT <BR> SOLICITADA</td>\n";
				$this->salida .= "									</tr>\n";

				foreach ($SolicitadosPaciente as $key=>$value)
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";
					$this->salida .= "									<td><input type='checkbox' name='CancelarSolicitudPaciente[]' value='".$value[consecutivo].".-.".$value[mezcla_recetada_id]."'></td>\n";
					$this->salida .= "									<td>".$value[fecha_solicitud]."</td>\n";
					$this->salida .= "									<td>".$value[mezcla_recetada_id]."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[ff]."</td>\n";
					$this->salida .= "									<td>".$value[cant_solicitada]."</td>\n";//.-."..".-."..".-.".."
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}
			if(($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente) )|| ($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega)))
			{
				//$this->salida .= "			<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr align='center'><td><input type='submit' value='CANCELAR SOLICITUDES SELECCIONADAS' name='SubmitCancelarPedidos' class='input-submit'></td></tr>\n";//espacio
				$this->salida .= "					</table>\n";
				$link = ModuloGetURL('app','EstacionE_Medicamentos','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= "				</td> \n";
				$this->salida .= "			</tr> \n";
				$this->salida .= "		</table> \n";
				$this->salida .= "	</td> \n";
				$this->salida .= "</tr> \n";
				$this->salida .= "</form>\n";//espacio
			}

			######################### MEDICAMENTOS ORDENADOS ######################################
			if($Medicamentos != "ShowMensaje" || $Mezclas != "ShowMensaje")
			{
				$action = ModuloGetURL('app','EstacionE_Medicamentos','user','PedirMedicamentos',array("datos_estacion"=>$datos_estacion));
				$this->salida .= "<form method=\"POST\" action=\"$action\">\n";
				$this->salida .= "<tr> \n";
				$this->salida .= "	<td colspan=\"2\">\n<br>";
				$this->salida .= "		<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_title\"><td>MEDICAMENTOS ORDENADOS</td></tr>\n";
				$this->salida .= "			<tr>\n";
				$this->salida .= "				<td> \n";
				$this->salida .= "					<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			}
			if($Medicamentos != "ShowMensaje")
			{
				$this->salida .= "						<tr>\n";
				$this->salida .= "							<td>\n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td colspan='7' height='40'>MEDICAMENTOS ORDENADOS</td>\n";
				$this->salida .= "									</tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>EVO</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>POS</td>\n";
				$this->salida .= "										<td>POSOLOG&Iacute;A</td>\n";
				$this->salida .= "										<td>CANT. <BR> RECETADA</td>\n";
				$this->salida .= "										<td>CANT. A<BR> SOLICITAR</td>\n";
				$this->salida .= "										<td>SOLICITAR A</td>\n";
				$this->salida .= "									</tr>\n";
				$i=0;
				foreach ($Medicamentos as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";
					$this->salida .= "									<td>".$value[evolucion_id]."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[nomff]."</td>\n";

					if($value[pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
					$this->salida .= "									<td>".$Pos."</td>\n";

					$datos = $this->ObtenerPlanTerapeutico($value[evolucion_id],$value[medicamento_id],$datos_estacion);
					$xc = $this->Posologia($datos[0]);

					if(!empty($value[indicacion_suministro])){
						$indicacion = "<br>".$value[indicacion_suministro];} else {$indicacion = "";
					}
					$this->salida .= "									<td>".$xc." ".$indicacion."</td>\n";
					$this->salida .= "									<td>".$value[cantidad_total]." ".$value[nomff]."</td>\n";
					$this->salida .= "									<td><input type=\"text\" name=\"cantidad[]\" value=\"".$value[cantidad_total]."\" size=\"8\" align='right' class=\"input-text\"\"> </td>\n";
					if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion)){
						return false;
					}
					$this->salida .= "									<td><select name=\"MedicamentosXconfirmar[]\"  class=\"select\">\n";
					$this->salida .= "												<option value=\"NoPedir.-.".$i."\">------</option> \n";
					$this->salida .= "												<option value=\"AlPaciente.-.".$i.".-.".$value[medicamento_id].".-.".$value[evolucion_id]."\">Paciente</option> \n";
					$this->salida .= "												<option value=\"".$bodega[bodega].".-.".$i.".-.".$value[medicamento_id].".-.".$value[evolucion_id].".-.".$value[bodega]."\">".$bodega[descripcion]."</option> \n";
					$this->salida .= "											</select>\n";
					$this->salida .= "									</td>\n";
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}//hay medicamentos
			//###################################################################MAY MEZCLAS
			if($Mezclas != "ShowMensaje")
			{
				//hacer un vector de mezclas para ordenar por mezcla
				$vecMezclas = array();
				foreach ($Mezclas as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					$vecM = $value[mezcla_recetada_id];

					if(strcmp($value[mezcla_recetada_id],$vecM) == 0)
					{ $contMezclas[$value[mezcla_recetada_id]]++;
						//$vect = [$value[mezcla_recetada_id]]++;
					}
				}
				$vect = $contMezclas;

				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr>\n";
				$this->salida .= "							<td>\n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td colspan='9' height='40'>MEZCLAS RECETADAS</td>\n";
				$this->salida .= "									</tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>EVO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>POSOLOG&Iacute;A</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>POS</td>\n";
				$this->salida .= "										<td>CANT. <BR> RECETADA</td>\n";
				$this->salida .= "										<td>CANT. A <BR> SOLICITAR</td>\n";
				$this->salida .= "										<td>SOLICITAR A</td>\n";
				$this->salida .= "									</tr>\n";

				for($i=0; $i<sizeof($Mezclas); $i++) //foreach ($Mezclas as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

					if($contMezclas[$Mezclas[$i][mezcla_recetada_id]] == $vect[$Mezclas[$i][mezcla_recetada_id]])
					{//." ".$Mezclas[$i][nomff]
						$this->salida .= "							<tr class=\"$estilo\" align=\"center\">\n";
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$Mezclas[$i][evolucion_id]."</td>\n";
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$Mezclas[$i][mezcla_recetada_id]."</td>\n";
						$xx = $this->ObtenerPlanTerpeuticoMezclas($Mezclas[$i][evolucion_id],$Mezclas[$i][mezcla_recetada_id]);
						$xc = $this->PosologiaMezcla($xx[0]);
						if(!empty($Mezclas[$i][observaciones])){$observa = "<br>OBSERVACIONES: ".$Mezclas[$i][observaciones];} else { $observa = "";}
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$xc." ".$observa."</td>\n";//
						$this->salida .= "								<td align='left'>".$Mezclas[$i][medicamento_id]." => ".$Mezclas[$i][nommedicamento]." ".$Mezclas[$i][nomff]."</td>\n";
						if($Mezclas[$i][sw_pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
						$this->salida .= "								<td>".$Pos."</td>\n";
						$this->salida .= "								<td>".$Mezclas[$i][cantidad]." ".$Mezclas[$i][nomff]."</td>\n";
						$this->salida .= "								<td> <input type=\"text\" name=\"cantidadMezclas[]\" value=\"".$Mezclas[$i][cantidad]."\" size=\"8\" align='right' class=\"input-text\" \"> </td> \n";//
						$this->salida .= "								<input type=\"hidden\" name=\"MezclasXcantidad[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$i."\"> </td>\n";
						//$this->salida .= "							<td> <input type=\"checkbox\" name=\"MezclasXconfirmar[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$Mezclas[$i][bodega].".-.".$Mezclas[$i][cantidad].".-.".$i."\"> </td> \n";
						if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion)){
							return false;
						}
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">\n";
						$this->salida .= "									<select name=\"MezclasXconfirmar[".$Mezclas[$i][mezcla_recetada_id]."]\"  class=\"select\">\n";
						$this->salida .= "										<option value=\"NoPedir\">------</option> \n";
						$this->salida .= "										<option value=\"AlPaciente\">Paciente</option> \n";
						$this->salida .= "	  								<option value=\"".$bodega[bodega]."\">".$bodega[descripcion]."</option> \n";
						$this->salida .= "									</select>\n";
						$this->salida .= "								</td>\n";
						$this->salida .= "							</tr>\n";
						$vect[$Mezclas[$i][mezcla_recetada_id]]--;
					}
					else
					{
						$this->salida .= "							<tr class=\"$estilo\" align=\"center\">\n";
						$this->salida .= "								<td align='left'>".$Mezclas[$i][medicamento_id]." => ".$Mezclas[$i][nommedicamento]." ".$Mezclas[$i][nomff]."</td>\n";
						/*aqui no se pone la bodega porque tiene rowspan*/
						if($Mezclas[$i][sw_pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
						$this->salida .= "								<td>".$Pos."</td>\n";
						$this->salida .= "								<td>".$Mezclas[$i][cantidad]." ".$Mezclas[$i][nomff]."</td>\n";
						$this->salida .= "								<td><input type=\"text\" name=\"cantidadMezclas[]\" value=\"".$Mezclas[$i][cantidad]."\" size=\"8\" align='right' class=\"input-text\"\"> </td>\n";
						$this->salida .= "								<input type=\"hidden\" name=\"MezclasXcantidad[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$i."\"> </td>\n";
						$this->salida .= "							</tr>\n";
					}//fin else
				}//fin for
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}//fin mezclas

			if($Medicamentos != "ShowMensaje" || $Mezclas != "ShowMensaje")
			{
				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr align=\"center\"><td><input type=\"submit\" name=\"submit\" value=\"SOLICITAR MEDICAMENTOS SELECCIONADOS\" class=\"input-submit\"> <input type=\"reset\" name=\"Reset\" value=\"REESTABLECER\" class=\"input-submit\"></td></tr>\n";
				$this->salida .= "						<input type=\"hidden\" name=\"datosPaciente\" value=\"".urlencode(addslashes(serialize($datosPaciente)))."\">";
				$this->salida .= "					</table>\n";
				$link = ModuloGetURL('app','EstacionE_Medicamentos','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= "				</td> \n";
				$this->salida .= "			</tr> \n";
				$this->salida .= "		</table> \n";
				$this->salida .= "	</td> \n";
				$this->salida .= "</tr> \n";
				$this->salida .= "</form>\n";//espacio
			}
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}//fin VerMedicamentosPorSolicitarPaciente


	/*
		*		 Lista($numero)
		*		$numero es el numero para imprimir la clase de la lista, si el numero es par imprime la clase list_claro
		*		de lo contrario imprime list_oscuro
		*		retorna la cadena con la clase a utilizar
		*
		*		@Author Arley Vel&aacute;squez
		*		@access Private
		*		@param integer
		*/
		function Lista($numero)
		{
			if ($numero%2)
				return ("class='modulo_list_oscuro'");
			return ("class='modulo_list_claro'");
		}//End lISTA


		//funcion del modulo estacione_medicamento
	//#######################################################################################
	// plan terapeutico
	//#######################################################################################
	/**
	*		ListMedicamentosPendientesXSolicitar
	*
	*		ListMedicamentosPendientesXSolicitar: muestra los datos obtenidos con la funcion "GetPacientesConMedicamentosPorSolicitar"
	*		la cual obtiene los pacientes con medicamentos_ordenados cuyo sw_estado=2 y tipo_despacho=4
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function ListMedicamentosPendientesXSolicitar($datos_estacion,$datosp)
	{
		$pendientesXConfirmar = $this->GetPacientesConMedicamentosPorSolicitar($datos_estacion,$datosp);
		if(!$pendientesXConfirmar){
			return false;
		}
		if($pendientesXConfirmar === "ShowMensaje")
		{
			$mensaje = "NO HAY MEDICAMENTOS ORDENADOS";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		else
		{
			$this->salida .= ThemeAbrirTabla('PACIENTES CON ORDENES DE MEDICAMENTOS [ '.$datos_estacion[descripcion5].' ]');
			$this->salida .= "<br><table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td>HABITACION</td>\n";
			$this->salida .= "				<td>CAMA</td>\n";
			$this->salida .= "				<td>PACIENTE</td>\n";
			$this->salida .= "				<td>IDENTIFICACION</td>\n";
			$this->salida .= "				<td colspan='3'>ACCIONES</td>\n";
			$this->salida .= "		</tr>\n";
			$i=0;
			foreach($pendientesXConfirmar as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "			<tr class=\"$estilo\" align=\"center\">\n";
				$this->salida .= "				<td>".$value[pieza]."</td>\n";
				$this->salida .= "				<td>".$value[cama]."</td>\n";
				$this->salida .= "				<td>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."</td>\n";
				$this->salida .= "				<td>".$value[tipo_id_paciente]." ".$value[paciente_id]."</td>\n";
				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallVerMedicamentosPorSolicitarPaciente',array("Paciente"=>$value,"datos_estacion"=>$datos_estacion));//"Paciente"=>$Paciente,
				$this->salida .= "				<td align=\"center\"><a href=\"$href\">Ver Medicamentos</a></td>\n";
				$hrefTD = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmImpresionTarjetasDroga',array("datos_estacion"=>$datos_estacion,"datos_paciente"=>$value));
				$this->salida .= "				<td align=\"center\"><a href=\"$hrefTD\">Imprimir Tarjeta Droga</a></td>\n";
				$hrefLP = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmImpresionLiquidosParenterales',array("datos_estacion"=>$datos_estacion,"datos_paciente"=>$value));
				$this->salida .= "				<td align=\"center\"><a href=\"$hrefLP\">Imprimir Etiqueta Liquidos</a></td>\n";
				$this->salida .= "		</tr>\n";
				$i++;
			}
			$this->salida .= "	</table>\n";
			$this->salida .= "<br><table width=\"30%\"  border=\"0\" align=\"center\">";
			$this->salida .= "			<tr>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<td align='center' class='normal_10'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a></td>";
			$url = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array('estacion'=>$datos_estacion,'datos_estacion'=>$datosp));
			$this->salida .= "<td align='center' class='normal_10'><br><a href='".$url."'>Volver a Medicamentos</a></td>";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= themeCerrarTabla();
		}
		return true;
	}//fin	ListMedicamentosPendientesXSolicitar()
//funcion del modulo estacione_medicamento



	//funcion de estacion de enfermeriae_medicamentos
		/*
		*		FrmSolicitarInsumosPaciente
		*
		*		Muestra un listado de los insumos del a estación y permite que se seleccionen para hacer el pedido
		*		de estos insumos al paciente
		*
		*		@Author Arley Velasquez Castillo
		*		@access Private
		*		@param array datos de la estacion
		*		@param integer ingreso del paciente
		*		@return bool
		*/
		function FrmSolicitarInsumosPaciente($datos_estacion,$ingreso)
		{
			$cont=$contador=0;
			$datos_paciente = $this->GetDatosClavePaciente($ingreso);
			$bodegas = $this->CallMetodoExterno('app', 'EstacionEnfermeria', 'admin', 'Bodegas', array(0=>$datos_estacion['empresa_id'],1=>$datos_estacion['centro_utilidad'],2=>$datos_estacion['estacion_id']));
			$suministros = $this->CallMetodoExterno('app', 'EstacionEnfermeria', 'admin', 'GetSuministros', array(0=>$datos_estacion['empresa_id'],1=>$datos_estacion['centro_utilidad'],2=>$bodegas[0]['bodega'],3=>$datos_estacion['estacion_id']));
			$this->salida .= ThemeAbrirTabla("SOLICITUD DE INSUMOS - [ ".$datos_estacion['descripcion5']." ]")."<br>";
			$this->salida .= "<SCRIPT>\n";
			$this->salida .= "function Seleccionartodos(frm,x){";
			$this->salida .= "  if(x==true){";
			$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "        frm.elements[i].checked=true";
			$this->salida .= "      }";
			$this->salida .= "    }";
			$this->salida .= "  }else{";
			$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "        frm.elements[i].checked=false";
			$this->salida .= "      }";
			$this->salida .= "    }";
			$this->salida .= "  }\n";
			$this->salida .= "}\n";
			$this->salida .= "</SCRIPT>\n";
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td width='70%'>PACIENTE</td>\n";
			$this->salida .= "			<td width='10%'>INGRESO</td>\n";
			$this->salida .= "			<td width='10%'>HAB.</td>\n";
			$this->salida .= "			<td width='10%'>CAMA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td width='70%'>".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer_apellido']." ".$datos_paciente['segundo_apellido']."</td>\n";
			$this->salida .= "			<td width='10%' align='center'>".$datos_paciente['ingreso']."</td>\n";
			$this->salida .= "			<td width='10%' align='center'>".$datos_paciente['pieza']."</td>\n";
			$this->salida .= "			<td width='10%' align='center'>".$datos_paciente['cama']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br><br>\n";

			$SolicitadosBodega = $this->GetInsumosSolicitadosbodega($ingreso,$datos_estacion);
			if($SolicitadosBodega==false)
			{
				return false;
			}
			if($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega))
			{
				$action = ModuloGetURL('app','EstacionE_Medicamentos','user','CancelarSolicitudesInsumos',array("datos_estacion"=>$datos_estacion,"ingreso"=>$ingreso));
				$this->salida .= "<form name='CancelarSolicitudesInsumos' method=\"POST\" action=\"$action\">\n";
				$this->salida .= "	<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_title\"><td>INSUMOS SOLICITADOS</td></tr>\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<table width=\"100%\"  border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\"><td colspan=\"6\" height=\"25\">LISTADO DE INSUMOS SOLICITADOS A BODEGA</td></tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td>CANCELAR<input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td>\n";
				$this->salida .= "					<td>SOLICITUD</td>\n";
				$this->salida .= "					<td>FECHA <br> PEDIDO</td>\n";
				$this->salida .= "					<td>ESTADO</td>\n";
				$this->salida .= "					<td>CODIGO PRODUCTO</td>\n";
				$this->salida .= "					<td>CANT <BR> SOLICITADA</td>\n";
				$this->salida .= "				</tr>\n";
				/*solicitud_id	consecutivo_d	?column?	medicamento_id	evolucion_id	cant_solicitada	forma_farmaceutica	nommedicamento	ff	fecha_solicitud	sw_estado*/
				//para contar el rowspan
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					$vect[$value[solicitud_id]][0]++;
					$vect[$value[solicitud_id]][1]++;
				}
				$i=0;
				$estados = array(0=>'Sin despacho', 1=>'Despachado', 2=>'Recibido', 3=>'Cancelado');//
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "			<tr class=\"$estilo\" align=\"center\">\n";

					if ((array_key_exists($value['solicitud_id'], $vect)) && ($vect[$value['solicitud_id']][0] == $vect[$value['solicitud_id']][1]))
					{
						if($value[sw_estado] == 0){
							$cancelar = "<input type='checkbox' name='CancelarSolicitudInsumo[]' value='".$value['solicitud_id']."'>";
							$mostrarBotonCancelar = 1;
						}
						else{
							$cancelar = "&nbsp;";
						}
						$this->salida .= "			<td rowspan='".$vect[$value['solicitud_id']][0]."'>".$cancelar."</td>\n";
						$this->salida .= "			<td rowspan='".$vect[$value['solicitud_id']][0]."'>".$value['solicitud_id']."</td>\n";
						$this->salida .= "			<td rowspan='".$vect[$value['solicitud_id']][0]."'>".$value['fecha_solicitud']."</td>\n";
						$this->salida .= "			<td rowspan='".$vect[$value['solicitud_id']][0]."'>".$estados[$value['sw_estado']]."</td>\n";
					}
					$this->salida .= "				<td align='left'>".$value['codigo_producto']." => ".$value['nommedicamento']." ".$value['ff']."</td>\n";
					$this->salida .= "	<td>".$value['cantidad']."</td>\n";//.-."..".-."..".-.".."
					$vect[$value['solicitud_id']][1]--;
					$this->salida .= "			</tr>\n";
					$i++;
				}
				$this->salida .= "			</table>\n";
				if($mostrarBotonCancelar){
					$this->salida .= "			<br><div class='normal_10' align='center'><input type='submit' value='CANCELAR SOLICITUDES SELECCIONADAS' name='SubmitCancelarPedidos' class='input-submit'>\n";
				}
				$link = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmInsumosPacientes',array("datos_estacion"=>$datos_estacion));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "			<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Insumos</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= "		</td> \n";
				$this->salida .= "	</tr> \n";
				$this->salida .= "</table> \n";
				$this->salida .= "</form>\n";
			}

			$action = ModuloGetURL('app','EstacionE_Medicamentos','user','SolicitarInsumosPaciente',array("datos_estacion"=>$datos_estacion,"ingreso"=>$ingreso));
			$this->salida .= "<form name=\"SolicitudInsumos\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= $this->SetStyle("MensajeError",5);
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td colspan='5' align='left'>INSUMOS DE LA ESTACION</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>TIPO INSUMO</td>\n";
			$this->salida .= "			<td>CODIGO PRODUCTO</td>\n";
			$this->salida .= "			<td>NOMBRE</td>\n";
			$this->salida .= "			<td>CANTIDAD</td>\n";
			$this->salida .= "			<td>SELECCIONAR<input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td>\n";
			$this->salida .= "		</tr>\n";

			$i = 0;
			foreach ($suministros as $key => $value)
			{
				if (!is_null($value[0]['codigo_producto'])){
					if (!$cont){
						$this->salida .= "<tr ".$this->Lista($contador).">\n";
						$this->salida .= "	<td rowspan='".sizeof($value)."' align='center'>\n";
						$this->salida .= "		<table align=\"center\" width=\"100%\" border=\"0\" class=\"normal_10\">\n";
						$this->salida .= "			<tr>\n";
						$this->salida .= "				<td>".strtoupper($key)."</td>\n";
						$this->salida .= "			</tr>\n";
						$this->salida .= "		</table>\n";
						$this->salida .= "	</td>\n";
					}
					foreach ($value as $key1 => $valor)
					{
						if ($cont){
							$this->salida .= "<tr ".$this->Lista($contador).">\n";
						}
						$this->salida .= "		<td align='justify'>".$valor['codigo_producto']."</td>\n";
						$this->salida .= "	<td align='justify'>".$valor['descripcion']."</td>\n";
						$this->salida .= "		<td align='center'><input type='text' class='input-text' name='Cantidad[]' value='".$_REQUEST['Cantidad['.$i.']']."' size='10'></td>\n";
						$this->salida .= "		<td align='center'><input type='checkbox' name='Suministro[$i]' value='".$valor['codigo_producto'].".-.".$valor['insumo_id'].".-.".$valor['tipo_insumo']."'></td>\n";//
						$this->salida .= "	</tr>\n";
						$cont++;
						$i++;
					}
					$cont=0;
					$contador++;
				}
			}
			$this->salida .= "			</table>\n";
			$this->salida .= "		<br><br><div class='normal_10' align=\"center\"><input type=\"submit\" name=\"submit\" value=\"SOLICITAR INSUMOS SELECCIONADOS\" class='input-submit'>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<input type=\"reset\" name=\"reset\" value=\"REESTABLECER\" class='input-submit'>\n";
			$this->salida .= "	<br>";
			$this->salida .= "	</form>\n";
			$link = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmInsumosPacientes',array("datos_estacion"=>$datos_estacion));
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "			<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Insumos</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
			unset($cont);
			unset($contador);
			unset($nom_medicamento);
			unset($suministros);
			unset($bodegas);
			return true;
		}


		/**
	*		SetStyle => Muestra mensajes
	*
	*		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
	*		el label del campo "obligatorio" sin llenar
	*
	*		@Author Alexander Giraldo
	*		@access Private
	*		@return string
	*		@param string => nombre del input y estilo que qued&oacute; vacio
	*/
	function SetStyle($campo,$colum)//CHANGE
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='$colum' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}






     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDespSolicitudMed()
     {
     
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
     
          $estacion=$_REQUEST['estacion'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          //$medic=$_REQUEST['medic'];
          $plan=$_REQUEST['plan'];
          $cuenta=$_REQUEST['cuenta'];
          $medic=$_SESSION['ESTACION']['VECTOR_DESP'][$_REQUEST['ingreso']];
          //$spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
     
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
     
               if(!empty($medic))
               {
                    $this->salida .= ThemeAbrirTabla('CONFIRMACION DESPACHO DE MEDICAMENTOS');
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$plan,"cuenta"=>$cuenta,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"matriz"=>$matriz,"op"=>$op,"switche"=>$SWITCHE));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";


                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
                    $this->salida .= "		<tr  class='modulo_table_title'>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"2%\" ></td>\n";
                    $this->salida .= "		</tr>\n";

	               for($i=0;$i<sizeof($medic);$i++)
                    {
                         if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                         //if($medic[$i][solicitud_id]!=$solicitud)

                         if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                         {

                              if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                              {
                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                   $solicitud=$medic[$i][solicitud_id];
                                   $this->salida .= "<td colspan = 6 width=\"65%\">";
                                   $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                              }


                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                              $despacho=$this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);

                              if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
                              {
                                   $this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                              }
                              else
                              {
                                   $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
                              }
                              $cant_desp=floor($despacho[0][cantidad]);
                              if($cant_desp <=0){$cant_desp='';}
                              $this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";

                              $this->salida.=" </tr>";
                              if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                              {
                                   $this->salida .= "</table>";
                                   $this->salida .= "</td>";
                                   $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></label></td>";
                                   $this->salida .= "</tr>";
                              }
                         }
                    }
                    $this->salida.="</table><br>";
               }
     
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
               $this->salida.=" </td>";


               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     
     }


     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDespSolicitudIns()
     {
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
     
          $estacion=$_REQUEST['estacion'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          //$medic=$_REQUEST['medic'];
          $plan=$_REQUEST['plan'];
          $cuenta=$_REQUEST['cuenta'];
          $medic=$_SESSION['ESTACION']['VECTOR_DESP_INS'][$_REQUEST['ingreso']];
          //$spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
     
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
     
                         if(!empty($medic))
                         {
                              $this->salida .= ThemeAbrirTabla('CONFIRMACION DESPACHO DE INSUMOS');
                              $f = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertDespSolicitudMed',array("plan"=>$plan,"cuenta"=>$cuenta,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"matriz"=>$matriz,"op"=>$op,"switche"=>$SWITCHE));
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
                              $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
                              $this->salida .= "		<tr  class='modulo_table_title'>\n";
                              $this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"2%\" ></td>\n";
                              $this->salida .= "		</tr>\n";
     
                              for($i=0;$i<sizeof($medic);$i++)
                              {
                                   if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                                   //if($medic[$i][solicitud_id]!=$solicitud)
     
                                   if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                                   {
     
                                        if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                                        {
                                             $this->salida .= "<tr $estilo>\n";
                                             $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                             $solicitud=$medic[$i][solicitud_id];
                                             $this->salida .= "<td colspan = 6 width=\"65%\">";
                                             $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                                        }
     
     
                                        $this->salida .= "<tr $estilo>\n";
                                        $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                                        $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
                                        $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cantidad])."</td>\n";
                                        $despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
     
                                        if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
                                        {
                                             $this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                                        }
                                        else
                                        {
                                             $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
                                             $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
                                        }
                                        $cant_desp=floor($despacho[0][cantidad]);
                                        if($cant_desp <=0){$cant_desp='';}
                                        $this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";
     
                                        $this->salida.=" </tr>";
                                        if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                                        {
     
                                             $this->salida .= "</table>";
                                             $this->salida .= "</td>";
                                             $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></label></td>";
                                             $this->salida .= "</tr>";
                                        }
                                   }
                              }
                              $this->salida.="</table><br>";
                         }
     
                    $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
                    $this->salida.=" <tr>";
                    $this->salida.=" <td align=\"center\">";
                    $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
                    $this->salida.=" </td>";
     
     
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                    $this->salida .="<form name=forma action=".$href." method=post>";
                    $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
                    $this->salida.=" </tr>";
                    $this->salida.=" </table>";
                    $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     
     }


     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarCancelSolicitudMed()
     {
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
          $estacion=$_REQUEST['estacion'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          $spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso=$_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS');
               $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CancelSolicitudMedicametos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    //if($medic[$i][solicitud_id]!=$solicitud)
     
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }


                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {

                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";

                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }


     //funcion que confirma si se va a cancelar la solicitud
     //esta pantalla muestra para confirmar la cancelación de los insumos 
     function ConfirmarCancelSolicitudIns()
     {
          $bodega=$_REQUEST['bodega'];
          $SWITCHE=$_REQUEST['switche'];
          $estacion=$_REQUEST['estacion'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $op=$_REQUEST['opcion'];
          $spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso=$_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL_INS'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE INSUMOS');
               $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CancelSolicitudInsumos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    //if($medic[$i][solicitud_id]!=$solicitud)
     
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }
     
                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("CONTROL INSUMOS PACIENTE","50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }




//funcion que confirma si se va a cancelar la solicitud de medicamentos para el paciente
//esta pantalla muestra para confirmar la cancelación de los insumos 
function ConfirmarCancelSolicitud_Medicamentos_Para_Pacientes()
{

	$bodega=$_REQUEST['bodega'];
	$SWITCHE=$_REQUEST['switche'];
	$estacion=$_REQUEST['estacion'];
	$datos_estacion=$_REQUEST['datos_estacion'];
	$op=$_REQUEST['opcion'];
	$spy=$_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
	$ingreso=$_REQUEST['ingreso'];
	$medic=$_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$ingreso];
	
	/*este boton recibe quiere decri que vamos a recibir los medicamentos / insumos no ha cancelarlos*/
	/*si este esta activo los llamamos desde el boton recib si no es por que lo llamamos del
	boton confirmar*/
	$button_recibe=$_REQUEST['recibe'];
  if($button_recibe=='RECIBIR')
	{
		$data=$_REQUEST['data'];//data es el vector de los medicamentos checkeados para recibir.
		if(is_array($data))
		{
			$this->Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud,$data);
			return true;
		}
		else
		{
			unset($medic);
		}
	}	
	
	
	if(sizeof($medic) AND sizeof($op))
	{
		unset($matriz);
		for($h=0;$h<sizeof($op);$h++)
		{
			$dat_op=explode(",",$op[$h]);
			$matriz[$h]=$dat_op[0];
		}
		$this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE');
		$f = ModuloGetURL('app','EstacionE_Medicamentos','user','CancelSolicitud_Medicamentos_Para_Paciente',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
		$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

		$this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";

		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td colspan='6'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
		$this->salida .= "		</tr>\n";

		$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
		$this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
		$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
		$this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
		$this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
		$this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
		$this->salida .= "			<td width=\"5%\" ></td>\n";
		$this->salida .= "		</tr>\n";



		for($i=0;$i<sizeof($medic);$i++)
		{
			if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
			//if($medic[$i][solicitud_id]!=$solicitud)

			if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
			{
					if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
					{
						$this->salida .= "<tr $estilo>\n";
						$this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
						$solicitud=$medic[$i][solicitud_id];
						$this->salida .= "<td colspan = 4 width=\"65%\">";
						$this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
					}


					$this->salida .= "<tr $estilo>\n";
					$this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
					$this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
					$this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
					$this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
					$this->salida.=" </tr>";
					if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
					{

						$this->salida .= "</table>";
						$this->salida .= "</td>";
						$this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
						$this->salida .= "</tr>";

					}
			}
		}
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
			$this->salida .= "<td colspan='4'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";



			$this->salida.="</tr></table><br>";
			$this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
			$this->salida.=" <tr>";
			$this->salida.=" <td align=\"center\">";
			$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
			$this->salida.=" </td>";

			if($spy==1)
			{
				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
			}
			else
			{
				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
			}
			$this->salida .="<form name=forma action=".$href." method=post>";
			$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
			$this->salida.=" </tr>";
			$this->salida.=" </table>";
			$this->salida .= ThemeCerrarTabla();
	}
	else
	{
		$this->salida .= ThemeAbrirTabla("CONTROL DE SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE","50%");
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "		<tr >\n";
		$this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
		$this->salida.="</tr></table>";
	  $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
		$this->salida.=" <tr>";
		if($spy==1)
		{
			$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
		}
		else
		{
			$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
		}

		$this->salida .="<form name=forma action=".$href." method=post>";
		$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida.=" </tr>";
		$this->salida.=" </table>";
		$this->salida .= ThemeCerrarTabla();
	}
	return true;

}







//funcion que confirma la solicitud de los medicamentos
function ConfirmarSolicitud()
{
	$bodega=$_REQUEST['bodega'];
	$estacion=$_REQUEST['estacion'];
	$datos_estacion=$_REQUEST['datos_estacion'];
	$op=$_REQUEST['op'];
	$cant=$_REQUEST['cantidad'];
	//$dat_op es el vecto separado por ',' donde esta el producto,principio activo, y el codigo.
	
	if($bodega=='-1')//por si entramos con el combo "SELECCIONE"
	{unset($op);}
	
	
	if(is_array($op))
	{
			$nom_bodega=$this->TraerNombreBodega($estacion,$bodega);
			//$existencia=$this->RevisarExistenciaBodega($estacion,$bodega);
			
			if($bodega=='*/*')
			{
				$this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES PARA EL PACIENTE");
			}
			else
			{
				$this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES DE MEDICAMENTOS A LA BODEGA &nbsp;".$nom_bodega."");
			}	
			
			if($bodega=='*/*')
			{
				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertSolicitudMed_Para_Paciente',array("cantidad"=>$cant,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega));
			}
			else
			{
				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertSolicitudMed',array("cantidad"=>$cant,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega));
			}
			
			$this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
	
			
			$this->salida .= "	<table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
			$this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
			$this->salida .= "			<td width=\"30%\"  >PRINCIPIO ACTIVO</td>\n";
			$this->salida .= "			<td width=\"5%\" >CANT</td>\n";
			$this->salida .= "			<td width=\"5%\" >EXIST</td>\n";
			$this->salida .= "			<td width=\"4%\" ></td>\n";
			$this->salida .= "		</tr>\n";
			unset($vect);
			$k=0;
			
		
			
               for($i=0;$i<=sizeof($cant);$i++)
			{
				if(($op[$i]))
				{
					$dat_op=explode(",",$op[$i]);
					$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
					$this->salida .= "			<td width=\"10%\">".$dat_op[1]."</td>\n";
					$this->salida .= "			<td width=\"25%\">".urldecode($dat_op[3])."</td>\n";
					$this->salida .= "			<td width=\"30%\">".urldecode($dat_op[2])."</td>\n";
                         $cantidad_solicitada_medicamento = floor($cant[$i]);
					$this->salida .= "			<td width=\"5%\">".$cantidad_solicitada_medicamento."</td>\n";
					$existencia=$this->RevisarExistenciaBodega($estacion,$bodega,$dat_op[1]);

					if($existencia > 0)
					{
						$this->salida .= "			<td width=\"5%\">".FormatoValor($existencia)."</td>\n";
					}else
					{
						$this->salida .= "			<td width=\"5%\"><label class=label_mark>No aplica</label></td>\n";
					}

					$this->salida .= "			<td width=\"4%\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>\n";

					$vect[$k]="".$dat_op[1].",".$dat_op[4].",".floor($cant[$i])."";
					$k++;
					
					
					$arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($dat_op[1],$bodega);
					if(is_array($arr_rel))
					{
                              for($y=0;$y<sizeof($arr_rel);$y++)
                              {
                                   $this->salida .= "		<tr rowspan='2' align='center' class='modulo_list_claro'>\n";
                                   $this->salida .= "			<td colspan='6' width=\"10%\">\n";
                                   
                                   $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_list_title\">\n";
                                   $this->salida .= "		<tr class=\"modulo_list_table_title\">\n";
                                   $this->salida .= "			<td colspan='4'>Solicitud de Insumos Relacionados con el Medicamento  &nbsp;".urldecode($dat_op[3])." </td>\n";
                                   $this->salida .= "		</tr>\n";
                                   
                                   
                                   $this->salida .= "		<tr align='center' bgcolor='#FFFFFF'>\n";
                                   $this->salida .= "			<td width=\"10%\"><label class='label_mark'>".$arr_rel[$y][codigo_producto]."</label></td>\n";
                                   $this->salida .= "			<td width=\"25%\"><label class='label_mark'>".$arr_rel[$y][descripcion]."</label></td>\n";
                                   $nueva_Cantidad = $cantidad_solicitada_medicamento * floor($arr_rel[$y][cantidad]);
//								$this->salida .= "			<td width=\"10%\"><input type='text' name='cant".$arr_rel[$y][medicamento_id]."".$arr_rel[$y][insumo_id]."".$bodega."' class='input-text' size='7' maxlength='7' value=".$arr_rel[$y][cantidad]."></td>\n";
                                   $this->salida .= "			<td width=\"10%\"><input type='text' name='cant".$arr_rel[$y][medicamento_id]."".$arr_rel[$y][insumo_id]."".$bodega."' class='input-text' size='7' maxlength='7' value=".$nueva_Cantidad."></td>\n";
                                   $this->salida .= "			<td width=\"4%\" ><input type='checkbox'$checked  name='checo[]' value='".$arr_rel[$y][medicamento_id]."^".$arr_rel[$y][codigo_producto]."^".$bodega."'></td>\n";
          
                                   $this->salida .= "		</tr></table>\n";
                                   $this->salida .= "		</td>\n";
                              }
					}
				}

			}
			
							
			
			
			
			//variable de session que me va a guardar la informacion del vector de solicitudes
			unset($_SESSION['ESTACION_MED']['VECTOR_SOL_OP']);
			$_SESSION['ESTACION_MED']['VECTOR_SOL_OP']=$vect;
		
			
			

		//	$href = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertSolicitudMed',array("cantidad"=>$cant,"op"=>$vect,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega));
	//		$this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
			$this->salida.="</tr></table><br>";
			
			if($_REQUEST['bodega']=='*/*')
			{
				$this->salida .= "<br><table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td ><label class='label_mark'>observaciones :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
			}	
		
			
			
			$this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
			$this->salida.=" <tr>";
			$this->salida.=" <td align=\"center\">";
			$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
			$this->salida.=" </td>";
			$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			$this->salida .="<form name=forma action=".$href." method=post>";
			$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
			$this->salida.=" </tr>";
			$this->salida.=" </table>";
			$this->salida .= ThemeCerrarTabla();
	}
	else
	{
		$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "		<tr >\n";
		$this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SOLICITO NINGUN MEDICAMENTO AL PACIENTE !</label></td>\n";
		$this->salida.="</tr></table>";
     	$this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
		$this->salida.=" <tr>";
		$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
		$this->salida .="<form name=forma action=".$href." method=post>";
		$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida.=" </tr>";
		$this->salida.=" </table>";
		$this->salida .= ThemeCerrarTabla();
	}
	return true;
}



//FUNCION Q MUESTRA LA FORMA PARA IMPRIMIR LAS FORMULAS...
function FrmImpresionMedicamentos($estacion,$datos_estacion)
{


		unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
		unset ($_SESSION['MEDICAMENTOS'.$pfj]);
		unset ($_SESSION['POSOLOGIA4'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset ($_SESSION['JUSTIFICACION'.$pfj]);
		unset ($_SESSION['MODIFICANDO'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
		unset ($_SESSION['MEDICAMENTOSM'.$pfj]);


		if(empty($estacion))
		{
			$estacion=$_REQUEST['estacion'];
			$datos_estacion=$_REQUEST['datos_estacion'];
		}


			//preguntamos si la estacion esta asociada con una bodega.
			$bodega_estacion=$this->GetEstacionBodega($estacion);

			$this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']);
			$href = ModuloGetURL('app','EstacionE_Medicamentos','user','ReporteFormulaMedica',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega_estacion));			
			$this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
			$this->salida .= "<SCRIPT>";
			$this->salida .= "function chequeoTotal(frm,x){";
			$this->salida .= "  if(x==true){";
			$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "        frm.elements[i].checked=true";
			$this->salida .= "      }";
			$this->salida .= "    }";
			$this->salida .= "  }else{";
			$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "        frm.elements[i].checked=false";
			$this->salida .= "      }";
			$this->salida .= "    }";
			$this->salida .= "  }";
			$this->salida .= "}";
			$this->salida .= "</SCRIPT>";
			$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td>PACIENTE</td>\n";
			$this->salida .= "			<td>HABITACION</td>\n";
			$this->salida .= "			<td>CAMA</td>\n";
			$this->salida .= "			<td>PISO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
			$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
			$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
			$this->salida.="</tr></table><br>";

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$vector1=$this->Consulta_Solicitud_Medicamentos($datos_estacion[ingreso]);
		$m = 0;
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"6\">PLAN TERAPEUTICO - MEDICAMENTOS FORMULADOS </td>";
			$this->salida.="<td align=\"center\" colspan=\"1\">SELECCION</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="  <td width=\"14%\" colspan=\"3\">OPCIONES</td>";//colspan=\"3"\
			$this->salida.="  <td  width=\"5%\">CANT<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
			$this->salida.="</tr>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vector1);$i++)
			{
                //$vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($vector1[$i][codigo_producto]);
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								if($vector1[$i][item] == 'NO POS')
									{
									    if ($vectorMSH)
											{
											  $this->salida.="  <td ROWSPAN = 5 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                      }
											else
											{
                        $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
											}
									}
								else
									{
									    if($vectorMSH)
											{
											  $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
											}
											else
											{
                        $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
											}
									}
								//LINEA ALTERADA para ver la evolucion
								$this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
								$this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";

/*								$this->salida.="  <td align=\"center\" width=\"3%\">";
								if($vector1[$i][evolucion_id] != $this->evolucion)
								{
										//*lo que inserte de FINALIZACION
										if($vector1[$i]['sw_estado'] == '1')
										{
												if ($_SESSION['PROFESIONAL'.$pfj]==1)
												{
														$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
														//$this->salida .= "<br><a href='".$accion."'><font color='#8C8030'>Finalizar</font></a>\n";
														$this->salida .= "<br>Finalizar\n";
												}
												elseif($_SESSION['PROFESIONAL'.$pfj]==3)
												{
														$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'2'));
														//$this->salida .= "<br><a href='".$accion."'><font color='#035512'>Suspender</font></a>";
														$this->salida .= "<br>&nbsp;";//Suspender
												}
										}
										elseif($vector1[$i]['sw_estado'] == '2')
										{
												if($_SESSION['PROFESIONAL'.$pfj]==1)
												{
													$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Activar_Medicamento_Medico', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
													//$this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>\n";
													$this->salida .= "Activar\n";

													$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
													//$this->salida .= "<br><a href='".$accion2."'><font color='#8C8030'>Finalizar</font></a>\n";
													$this->salida .= "<br>Finalizar\n";
												}
										}
										//fin
								}
								if($vector1[$i]['sw_estado'] == '2')
								{
									if($_SESSION['PROFESIONAL'.$pfj]==3)
									{
											$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'1'));
											//$this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>";
											$this->salida .= "Activar";
									}
								}
								$this->salida.="</td>";*/

								//*lo que inserte de control de suministro
								if($vector1[$i]['sw_estado'] == '1')
								{
								  //$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Control_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'],'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'cantidad'.$pfj=>$vector1[$i][cantidad], 'descripcion'.$pfj=>$vector1[$i][descripcion], 'contenido_unidad_venta'.$pfj=>$vector1[$i][contenido_unidad_venta], 'unidad_dosificacion'.$pfj=>$vector1[$i][unidad_dosificacion]));
								  //$this->salida .= "		<td align='center' width=\"3%\"><a href='".$action."'><font color=\"#077325\">Ingresar Suministro</font></a></td>\n";
									$this->salida .= "		<td align='center' width=\"8%\" colspan=\"3\">Suministro Medicamentos</font></td>\n";

								}
								else
								{
                  $this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";
								}
								//fin

								//*lo que inserte de Ver Detalle Suministro
//								$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
								//$this->salida .= "		<td align='center' width=\"3%\"><font color=\"#990000\"><a href='".$accion."'>Ver Detalle Suministro</a></font></td>\n";
//								$this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";//Ver Detalle Suministro
								//fin

								//validar quien puede eliminar o modifiacar el medicamento
               /* if($vector1[$i][evolucion_id] == $this->evolucion)
								{
									$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'forma_modificar_medicamento', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto]));
								  $this->salida.="  <td align=\"center\" width=\"3%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
                  $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'eliminar', 'codigo_producto'.$pfj => $vector1[$i][codigo_producto], 'opcion_posologia'.$pfj => $vector1[$i][tipo_opcion_posologia_id]));
								  $this->salida.="  <td align=\"center\" width=\"2%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
								}*/
								//else
							//	{
       						$this->salida.="  <td ROWSPAN =3  width=\"5%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vector1[$i][codigo_producto].",".$vector1[$i][evolucion_id]."></td>";

							//	}

								//fin del validador
								$this->salida.="</tr>";


								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td colspan = 5>";
								$this->salida.="<table>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
								$e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
								if($e==1)
								{
									$this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}
								else
								{
									$this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
								}

								$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
								if($vector1[$i][tipo_opcion_posologia_id]== 1)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
								}

//pintar formula para opcion 2
								if($vector1[$i][tipo_opcion_posologia_id]== 2)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}

//pintar formula para opcion 3
								if($vector1[$i][tipo_opcion_posologia_id]== 3)
								{
										$momento = '';
										if($vector_posologia[0][sw_estado_momento]== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0][sw_estado_momento]== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0][sw_estado_momento]== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0][sw_estado_desayuno]== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_almuerzo]== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0][sw_estado_cena]== '1')
										{
											$Cen = $momento.'la Cena';
											$cont++;
										}
										if ($cont== 2)
										{
											$conector = ' y ';
											$conector1 = '  ';
										}
										if ($cont== 1)
										{
											$conector = '  ';
											$conector1 = '  ';
										}
										if ($cont== 3)
										{
											$conector = ' , ';
											$conector1 = ' y ';
										}
										$this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}

//pintar formula para opcion 4
								if($vector1[$i][tipo_opcion_posologia_id]== 4)
								{
									$conector = '  ';
									$frecuencia='';
									$j=0;
									foreach ($vector_posologia as $k => $v)
									{
										if ($j+1 ==sizeof($vector_posologia))
										{
											$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($vector_posologia))
													{
														$conector = ' y ';
													}
												else
													{
														$conector = ' - ';
													}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
									}
									$this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
								}

//pintar formula para opcion 5
								if($vector1[$i][tipo_opcion_posologia_id]== 5)
								{
									$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
								}
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
								$e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
								if ($vector1[$i][contenido_unidad_venta])
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
									}
								}
								else
								{
									if($e==1)
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
									}
									else
									{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
									}
								}
								$this->salida.="</tr>";

								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="<td colspan =5 class=\"$estilo\">";
								$this->salida.="<table>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";

								$this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
								$this->salida.="<tr class=\"$estilo\">";


								if($vector1[$i][sw_uso_controlado]==1)
								{
									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
									$this->salida.="<tr class=\"$estilo\">";
                }
                $this->salida.="</table>";

								$this->salida.="</td>";
								$this->salida.="</tr>";


							//	if($vector1[$i][item] == 'NO POS')
							//	{
									$this->salida.="<tr class=\"$estilo\">";
									/*if($vector1[$i][sw_paciente_no_pos] != '1')
									{
										if($vector1[$i][evolucion_id] == $this->evolucion)
										{
												$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion], 'evolucion'.$pfj => $vector1[$i][evolucion_id]));
												$this->salida.="  <td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
										}
										else
										{
												$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
												$this->salida.="  <td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> CONSULTAR JUSTIFICACION</a></td>";
										}
									}
									else
									{
										$this->salida.="  <td class = label_error colspan = 7 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
									}*/
								/*$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
								$this->salida.="  <td colspan = 8 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
								$this->salida.="</tr>";*/
							//	}
                //HISTORIAL DEL MEDICAMENTO
								/*if ($vectorMSH)
								{
								  $registros_historial = sizeof($vectorMSH);
									$this->salida.="<tr class=\"$estilo\">";
                  $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
									$this->salida.="<td colspan = 7 align=\"center\" width=\"63%\"><a href='".$accion."'><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primer Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></a></td>";
									$this->salida.="</tr>";
					      }*/

           //fin del for muy importante
				   }
					 //<duvan>  --> el link de solicitud de mediamentos.
					  	$this->salida.="<tr class=\"$estilo\">";
							$accion1 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array('ingreso'=>$B[ingreso],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
							$this->salida.="  <td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/anterior.png\" border='0'> REGRESAR A MEDICAMENTOS</a></td>";
							$this->salida.="</tr>";

					 	$this->salida.="<tr class=modulo_table_title>";
						//$url = ModuloGetURL('app','EstacionE_Medicamentos','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$estacion,"datosp"=>$datos_estacion));
						//$this->salida .= "<form name='dato' action='".$url."' method='POST'><br>\n";
						if(is_array($bodega_estacion) OR !empty($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']))
						{
							$this->salida.="  <td class=\"modulo_table_button\" colspan = 6 align=\"center\" width=\"80%\"><input type=submit class='input-submit' name='mandarpos' value='IMPRIMIR POS'> </td>";
							$this->salida.="  <td class=\"modulo_table_button\" colspan = 1 align=\"center\" width=\"80%\"><input type=submit class='input-submit' name='mandarpdf' value='IMPRIMIR PDF'> </td>";
						}
						else
						{
							$this->salida.="  <td colspan = 9 align=\"center\" width=\"80%\"><font color='white'>LA ESTACION ".$_SESSION['ESTACION_ENFERMERIA']['NOM']." &nbsp;NO TIENE BODEGAS ASOCIADAS</font></td>";
						}
						$this->salida.="</form></tr>";
					  $this->salida.="</table><br>";
		    }
				else
				{
					 $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
					 $this->salida.="<tr  align=\"center\"><td><label class='label_mark'>EL PACIENTE NO TIENE MEDICAMENTOS SOLICITADOS";
					 $this->salida.="</tr></td></label>";
					 $this->salida.="</table><br>";
          //$m = $m+1;
				}

				//fin de mediacamentos finalizadops
				$this->salida .= "</form>";

			  if ($_SESSION['PROFESIONAL'.$pfj]!=1)
				{
            if($m==2)
						{
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	              $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
								$this->salida.="</tr>";
								$this->salida.="</table><br>";
						}
				}


//los medicamentos frecuentes por diagnostico
//este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
				if ($_SESSION['PROFESIONAL'.$pfj]==1)
				{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
						$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
						$vectorMF = $this->Medicamentos_Frecuentes_Diagnostico();
						if ($vectorMF)
							{
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td align=\"center\" colspan=\"7\">MEDICAMENTOS EMPLEADOS PARA LOS DIAGNOSTICOS DE ESTA HISTORIA CLINICA</td>";
									$this->salida.="</tr>";

									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="  <td width=\"5%\"></td>";
									$this->salida.="  <td width=\"5%\">CODIGO</td>";
									$this->salida.="  <td width=\"23%\">PRODUCTO</td>";
									$this->salida.="  <td width=\"23%\">PRINCIPIO ACTIVO</td>";
									if ($this->bodega==='')
									{
										$this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
									}
									else
									{
										$this->salida.="  <td width=\"15%\">FORMA</td>";
										$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
									}
									$this->salida.="  <td width=\"4%\">OPCION</td>";
									$this->salida.="</tr>";
									for($i=0;$i<sizeof($vectorMF);$i++)
									{
											if( $i % 2){ $estilo='modulo_list_claro';}
											else {$estilo='modulo_list_oscuro';}
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][item]."</td>";
											$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][codigo_producto]."</td>";
											$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][producto]."</td>";
											$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][principio_activo]."</td>";

									if ($this->bodega==='')
										{
											$this->salida.="  <td colspan = 2 align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
										}
									else
										{
											$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
											if(!empty($vectorMF[$i][existencia]))
											{
													$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][existencia]."</td>";
											}
											else
											{
													$this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
											}
											//$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opE".$pfj."[$i]' value = ".$cargo.",".$vectorE[$i][especialidad]."></td>";
										}
										$this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = '".$vectorMF[$i][item].",".$vectorMF[$i][codigo_producto].",".$vectorMF[$i][producto].",".$vectorMF[$i][principio_activo].",".$vectorE[$i][concentracion_forma_farmacologica].",".$vectorE[$i][unidad_medida_medicamento_id].",".$vectorE[$i][forma].",".$vectorE[$i][cod_forma_farmacologica]."'></td>";
											$this->salida.="</tr>";
										}

									$this->salida.="<tr class=\"$estilo\">";
									$this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"FORMULAR\"></td>";
									$this->salida.="</tr>";
								$this->salida.="</table><br>";
							}
						$this->salida .= "</form>";
					}
				//fin de medicamentos MAS FRECUENTES POR DIAGNMOSTICO

        //este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
				if ($_SESSION['PROFESIONAL'.$pfj]==1)
				{
				   //lo que inserte
					 $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
					'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
					'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
					'producto'.$pfj=>$_REQUEST['producto'.$pfj],
					'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

					$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"5%\">TIPO</td>";

					$this->salida.="<td width=\"10%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					if (($_REQUEST['criterio1'.$pfj])  == '002')
						{
							$this->salida.="<option value = '002' selected>Frecuentes</option>";
						}
					else
						{
							$this->salida.="<option value = '002' >Frecuentes</option>";
						}
					$this->salida.="</select>";
					$this->salida.="</td>";

					$this->salida.="<td width=\"7%\">PRODUCTO:</td>";
					$this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>" ;

					$this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
					$this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"        ></td>" ;

					$this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
          //hasta aqui lo que inserte
				}


		if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
		{
			$href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
		}
		else
		{
			if(UserGetUID()==0)
			{
				$href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("estacion"=>$estacion));
			}
			else
			{
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			}
		}
		$this->salida .= "<div class='normal_10' align='center'><BR><a href='".$href."'>Volver al Menu</a><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}









//claudiaaaaaaaaaaaaa
     function FrmMedicamentos($estacion,$datos_estacion)
     {
		unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
		unset ($_SESSION['MEDICAMENTOS'.$pfj]);
		unset ($_SESSION['POSOLOGIA4'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset ($_SESSION['JUSTIFICACION'.$pfj]);
		unset ($_SESSION['MODIFICANDO'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
		unset ($_SESSION['MEDICAMENTOSM'.$pfj]);
		unset ($_SESSION['EXISTENCIA']);//session q tiene el vector de seleccion de insumos
		unset($_SESSION['MEDICA_DATOS_SOL_PAC']);//session q guarda las observaciones y el nombre 
		//al cual le solicitaron los insumos del paciente.
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']);//vector de productos seleccionados(control suministro)

			//preguntamos si la estacion esta asociada con una bodega.
			//$bodega_estacion=$this->GetEstacionBodega($estacion);

          $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']);
          $href = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarSolicitud',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
          $this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
          $this->salida .= "<SCRIPT>";
          $this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "      if(frm.elements[i].disabled==false){";
          $this->salida .= "        frm.elements[i].checked=true";
          $this->salida .= "      }";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
          $this->salida .= "</SCRIPT>";
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>HABITACION</td>\n";
          $this->salida .= "			<td>CAMA</td>\n";
          $this->salida .= "			<td>PISO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
          $this->salida.="</tr></table><br>";

		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$vector1=$this->Consulta_Solicitud_Medicamentos($datos_estacion[ingreso]);
		$m = 0;
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"6\">PLAN TERAPEUTICO - MEDICAMENTOS FORMULADOS </td>";
			$this->salida.="<td align=\"center\" colspan=\"2\">SOLICITAR MEDICAMENTOS</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"7%\">CODIGO</td>";
			$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="  <td width=\"14%\" colspan=\"3\">OPCIONES</td>";// colspan=\"3"\ 
			$this->salida.="  <td  width=\"14%\">CANTIDAD</td>";
			//$this->salida.="<td align=\"center\">BODEGA</td>";
			$this->salida.="  <td  width=\"5%\"><input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
			$this->salida.="</tr>";
			$this->salida.="</tr>";
			$datos=$this->GetEstacionBodega($estacion,1);
			for($i=0;$i<sizeof($vector1);$i++)
			{
                //$vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($vector1[$i][codigo_producto]);
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    if($vector1[$i][item] == 'NO POS')
                    {
                         if ($vectorMSH)
                         {
                              $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                         }
                         else
                         {
                              $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                         }
                    }
                    else
                    {
                         if($vectorMSH)
                         {
                              $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                         else
                         {
	                         $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                    }
                    //LINEA ALTERADA para ver la evolucion
                    $this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";
     
/*                    $this->salida.="  <td align=\"center\" width=\"3%\">";
                    if($vector1[$i][evolucion_id] != $this->evolucion)
                    {
                         //*lo que inserte de FINALIZACION
                         if($vector1[$i]['sw_estado'] == '1')
                         {
                                   if ($_SESSION['PROFESIONAL'.$pfj]==1)
                                   {
                                             $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
                                             //$this->salida .= "<br><a href='".$accion."'><font color='#8C8030'>Finalizar</font></a>\n";
                                             $this->salida .= "<br>Finalizar\n";
                                   }
                                   elseif($_SESSION['PROFESIONAL'.$pfj]==3)
                                   {
                                             $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'2'));
                                             //$this->salida .= "<br><a href='".$accion."'><font color='#035512'>Suspender</font></a>";
                                             $this->salida .= "<br>&nbsp;";//Suspender
                                   }
                         }
                         elseif($vector1[$i]['sw_estado'] == '2')
                         {
                                   if($_SESSION['PROFESIONAL'.$pfj]==1)
                                   {
                                        $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Activar_Medicamento_Medico', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
                                        //$this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>\n";
                                        $this->salida .= "Activar\n";

                                        $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Finalizar_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id']));
                                        //$this->salida .= "<br><a href='".$accion2."'><font color='#8C8030'>Finalizar</font></a>\n";
                                        $this->salida .= "<br>Finalizar\n";
                                   }
                         }
                         //fin
                    }
                    if($vector1[$i]['sw_estado'] == '2')
                    {
                         if($_SESSION['PROFESIONAL'.$pfj]==3)
                         {
                                   $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Suspender_Medicamento', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'tipo_nota'.$pfj=>'1'));
                                   //$this->salida .= "<a href='".$accion."'><font color='#063496'>Activar</font></a>";
                                   $this->salida .= "Activar";
                         }
                    }
                    $this->salida.="</td>";*/

                    //*lo que inserte de control de suministro
                    if($vector1[$i]['sw_estado'] == '1')
                    {
                         //$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Control_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'],'producto'.$pfj=>$vector1[$i]['producto'],'principio_activo'.$pfj=>$vector1[$i]['principio_activo'], 'evolucion_id'.$pfj=>$vector1[$i]['evolucion_id'], 'cantidad'.$pfj=>$vector1[$i][cantidad], 'descripcion'.$pfj=>$vector1[$i][descripcion], 'contenido_unidad_venta'.$pfj=>$vector1[$i][contenido_unidad_venta], 'unidad_dosificacion'.$pfj=>$vector1[$i][unidad_dosificacion]));
                         $url = ModuloGetURL('app','EstacionE_Medicamentos','user','Control_Suministro',array("tipo_solicitud"=>'M',"vect"=>$vector1[$i],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
                         $this->salida .= "		<td align='center' width=\"8%\" colspan=\"3\"><a href='".$url."'><font color=\"#077325\">Suministro Medicamentos</font></a></td>\n";
                         //$this->salida .= "		<td align='center' width=\"3%\">Ingresar Suministro</font></td>\n";

                    }
                    else
                    {
					$this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";
                    }
                    //fin

                    //*lo que inserte de Ver Detalle Suministro
//                    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
                    //$this->salida .= "		<td align='center' width=\"3%\"><font color=\"#990000\"><a href='".$accion."'>Ver Detalle Suministro</a></font></td>\n";
//                    $this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";//Ver Detalle Suministro
                    //fin

                         //else
	               //	{
                    $this->salida.="  <td ROWSPAN =3  align=\"center\" width=\"14%\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidad[] value='".floor($vector1[$i][cantidad])."'></td>";
                    $this->salida.="  <td ROWSPAN =3  width=\"5%\" align=\"center\"><input id=op$i  type=checkbox name=op[$i] value=".$vector1[$i][codigo_producto].$vector1[$i][evolucion_id].",".$vector1[$i][codigo_producto].",".urlencode($vector1[$i][principio_activo]).",".urlencode($vector1[$i][producto]).",".$vector1[$i][evolucion_id]."></td>";
									
                    //funcion para verificar si este producto existe en la bodega.
                    $valor_arreglo=$this->Get_Existencia_producto_Bodega($vector1[$i][codigo_producto],$estacion);
		          //	}
																
                    for($c=0;$c<sizeof($valor_arreglo);$c++)
                    {	
                         $cadena .=$valor_arreglo[$c][bodega].",";
                    }
                    if(!is_array($valor_arreglo)){$cadena="";}else{$cadena .="*";}
                    $this->salida.="<input id=$i type='hidden' name=oculto$i value=$cadena>";unset($cadena);
                    $contador_sys=$i;

                    //fin del validador
                    $this->salida.="</tr>";


                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 5>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                              $momento = '';
                              if($vector_posologia[0][sw_estado_momento]== '1')
                              {
                                   $momento = 'antes de ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '2')
                                   {
                                        $momento = 'durante ';
                                   }
                                   else
                                   {
                                        if($vector_posologia[0][sw_estado_momento]== '3')
                                             {
                                                  $momento = 'despues de ';
                                             }
                                   }
                              }
                              $Cen = $Alm = $Des= '';
                              $cont= 0;
                              $conector = '  ';
                              $conector1 = '  ';
                              if($vector_posologia[0][sw_estado_desayuno]== '1')
                              {
                                   $Des = $momento.'el Desayuno';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_almuerzo]== '1')
                              {
                                   $Alm = $momento.'el Almuerzo';
                                   $cont++;
                              }
                              if($vector_posologia[0][sw_estado_cena]== '1')
                              {
                                   $Cen = $momento.'la Cena';
                                   $cont++;
                              }
                              if ($cont== 2)
                              {
                                   $conector = ' y ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 1)
                              {
                                   $conector = '  ';
                                   $conector1 = '  ';
                              }
                              if ($cont== 3)
                              {
                                   $conector = ' , ';
                                   $conector1 = ' y ';
                              }
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

//pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

//pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan =5 class=\"$estilo\">";
                    $this->salida.="<table>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";

                    $this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
                    $this->salida.="<tr class=\"$estilo\">";


                    if($vector1[$i][sw_uso_controlado]==1)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                         $this->salida.="<tr class=\"$estilo\">";
                    }
                    $this->salida.="</table>";

                    $this->salida.="</td>";
                    $this->salida.="</tr>";


							//	if($vector1[$i][item] == 'NO POS')
							//	{
										//	}
                //HISTORIAL DEL MEDICAMENTO
								/*if ($vectorMSH)
								{
								  $registros_historial = sizeof($vectorMSH);
									$this->salida.="<tr class=\"$estilo\">";
                  $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vector1[$i]['codigo_producto'], 'producto'.$pfj=>$vector1[$i]['producto'], 'principio_activo'.$pfj=>$vector1[$i]['principio_activo']));
									$this->salida.="<td colspan = 7 align=\"center\" width=\"63%\"><a href='".$accion."'><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primer Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></a></td>";
									$this->salida.="</tr>";
					      }*/

           //fin del for muy importante
               }
					 
					 
                    $this->salida .= "<SCRIPT>\n";
                    $this->salida .= "function compare(frm,x){\n";
                    $this->salida .= "var cadena = new String();\n";
                    $this->salida .= "var bandera=new Boolean(true);\n";
                    $this->salida .= "    for(i=0;i<$contador_sys+1;i++){\n";
                    $this->salida .= "cadena='';\n";
                    $this->salida .= "cadena=document.getElementById(i).value;\n";
                    $this->salida .= "arrayofstring=new Array();\n";
                    $this->salida .= "arrayofstring=cadena.split(',');\n";
                    $this->salida .= "for (var n=0; n < arrayofstring.length ; n++) {\n";
                    $this->salida .= "if(arrayofstring[n]==x){\n";
                    $this->salida .= "bandera=false;";
                    $this->salida .= "break;\n";
                    $this->salida .= "}";//fin if
                    $this->salida .= "}\n";//fin 2do for
                    
                    
                    $this->salida .= "if(x=='*/*'){";
                    $this->salida .= "document.getElementById('op'+i).disabled=false;\n";
                    $this->salida .= "}else{";
                    
                    $this->salida .= "if(bandera==true){";
                    $this->salida .= "document.getElementById('op'+i).checked=false;\n";
                    $this->salida .= "}";
                    $this->salida .= "document.getElementById('op'+i).disabled=bandera;\n";
                    $this->salida .= "}\n";//fin else
                    
                    $this->salida .= "}\n";//fin 1er for
	               $this->salida .= "}\n";//fin funcion
                    $this->salida .= "</SCRIPT>\n";
					 
					 
						 

                    //aca colocamos la bodega........
                    $this->salida.="<tr align=\"center\">";
                    $this->salida.="  <td class=modulo_table_title colspan = 6 align=\"right\" width=\"70%\">SELECCION DE BODEGA :</td>";
                    $this->salida.="  <td class=\"$estilo\" colspan = 2 align=\"center\" width=\"63%\">";

                    $this->salida.="<select name=bodega    onchange=compare(this.form,this.options[selectedIndex].value) class='select'>";

                    $this->salida.="<option value=-1 SELECTED>--SELECCIONE--</option>";

                    if(is_array($datos))
                    {
                         for($i=0;$i<sizeof($datos);$i++)
                         {
                              $this->salida.="<option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                         }
                    }
                    $this->salida.="<option value=*/* >SOLICITUD PACIENTE</option>";

                    $this->salida.="</select>";

                    $this->salida .= "			</td>\n";

                    $this->salida.="</tr>";


                    $this->salida.="<tr class=\"$estilo\">";
                    $accion1 = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmImpresionMedicamentos',array('ingreso'=>$B[ingreso],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
                    $this->salida.="  <td colspan = 8 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                    $this->salida.="</tr>";

                    //<duvan>  --> el link de solicitud de mediamentos.
                    $this->salida.="<tr class=modulo_table_title>";
                    //$url = ModuloGetURL('app','EstacionE_Medicamentos','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$estacion,"datosp"=>$datos_estacion));
                    //$this->salida .= "<form name='dato' action='".$url."' method='POST'><br>\n";

                    if(UserGetUID()==0)
                    {
                         $this->salida.="  <td colspan = 9 align=\"center\" width=\"80%\"><font color='white'>LA ESTACION ".$_SESSION['ESTACION_ENFERMERIA']['NOM']." &nbsp;ESTA EN MODO DE LECTURA</font></td>";
                    }
                    else
                    {

                    if(is_array($datos))
                    {
                         $this->salida.="  <td class=\"modulo_table_button\" colspan = 8 align=\"center\" width=\"80%\"><input type=submit class='input-submit' name='mandar' value='Solicitar'> </td>";
                    }
                    else
                    {
                         $this->salida.="  <td colspan = 9 align=\"center\" width=\"80%\"><font color='white'>LA ESTACION ".$_SESSION['ESTACION_ENFERMERIA']['NOM']." &nbsp;NO TIENE BODEGAS ASOCIADAS</font></td>";
                    }
               }

               $this->salida.="</form></tr>";
               $this->salida.="</table><br>";


						
                              //parte de las solicitudes de medicmanetos por parte d e los pacientes
               unset($medic);
               $medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datos_estacion['ingreso'],$estacion[empresa_id]);

               if(sizeof($medic))
               {
                    $_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$datos_estacion['ingreso']]=$medic;
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitud_Medicamentos_Para_Pacientes',array("ingreso"=>$datos_estacion['ingreso'],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";

                    $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
                    $this->salida .= "			<td colspan='8'  align=\"center\">SOLICITUDES REALIZADAS PARA EL PACIENTE (pendiente despacho)</td>\n";
                    $this->salida .= "		</tr>\n";

                    $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
                    //$this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
                    $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                    $this->salida .= "			<td width=\"42%\" >PRODUCTO</td>\n";
                    $this->salida .= "			<td width=\"5%\" >CANT SOL</td>\n";
                    $this->salida .= "			<td width=\"5%\" >CANT REC</td>\n";
                    $this->salida .= "			<td width=\"35%\" colspan='3'>ACCION</td>\n";
                    $this->salida .= "			<td width=\"2%\" ></td>\n";
                    $this->salida .= "		</tr>\n";


                    for($k=0;$k<sizeof($medic);$k++)
                    {
                         if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                         //if($medic[$i][solicitud_id]!=$solicitud)
                    /*	if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
                              $solicitud=$medic[$k][solicitud_id];
                              $this->salida .= "<td colspan = 7 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }*/

                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"40%\">".$medic[$k][producto]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($medic[$k][cantidad])."</td>\n";
                         
                         
                         //aca colocar el query de las cantidades recibisdas...........
                         $recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datos_estacion['ingreso'],$medic[$k][codigo_producto],$estacion[estacion_id]);
                         $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($recepcion)."</td>\n";
               
                         
                         
                         $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EstacionE_Medicamentos','user','Recibir_X_Para_Pacientes',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='RECIBIR EL MEDICAMENTO/INSUMO  &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0' ></DIV></a></td>\n";
                         $this->salida .= "<td $estilo width=\"2%\"><DIV TITLE='VER EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/auditoria.png\" border='0'></DIV></td>\n";
                         $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EstacionE_Medicamentos','user','Cancelar_Sol_X_Med_Pacientes',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='CANCELAR EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/error_digitacion.png\" border='0'></DIV></a></td>\n";
                         $this->salida.="  <td $estilo width=\"2%\" align=\"center\"><input type=checkbox name=data[] value=".$datos_estacion['ingreso'].",".$medic[$k][codigo_producto]."></td>";
                    
                         $this->salida.=" </tr>";
                    /*	if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
                         {

                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
                              $this->salida .= "</tr>";

                         }*/
                    }
                         $forma="".ModuloGetURL('app','EstacionE_Medicamentos','user','ReporteFormulaMedica_Para_Pacientes',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"solicitud"=>$medic[$k][solicitud_id]))."";
                         $this->salida.=" <tr align='right' class=\"hc_table_submodulo_list_title\"><td align='MIDDLE' ><a href='$forma' title='IMPRIME LOS MEDICAMENTOS FALTANTES DEL PACIENTE'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></a><label class='label_mark'>&nbsp; IMPRIMIR</label></td><td align='right' colspan='7'><div title='SELECCIONE EN LAS CASILLAS LOS MEDICAMENTOS / INSUMOS QUE DESEA RECIBIR'><input name=\"recibe\" type=\"submit\" class=\"input-submit\"  value=\"RECIBIR\"></div></td>";
                         /*$this->salida .= "<td>";
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
                         $this->salida.=" </td>";*/
                         $this->salida.="</tr></table></form><br>";

               }
               //fin de solicitudes por parte de los pacientes.		

                    
                    
     //consulta de medicamentos solicitados
               $medic=$this->GetMedicamentosSolicitadosControlPacientes($datos_estacion['ingreso'],$estacion[empresa_id]);

               if(sizeof($medic))
               {
                    $_SESSION['ESTACION']['VECTOR_SOL'][$datos_estacion['ingreso']]=$medic;
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudMed',array("ingreso"=>$datos_estacion['ingreso'],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";

                    $this->salida .= "		<tr class=\"modulo_table_title\">\n";
                    $this->salida .= "			<td colspan='7'  align=\"center\">SOLICITUDES REALIZADAS DE MEDICAMENTOS (pendiente despacho)</td>\n";
                    $this->salida .= "		</tr>\n";

                    $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
                    $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
                    $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
                    $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                    $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
                    $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
                    $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
                    $this->salida .= "			<td width=\"2%\" ></td>\n";
                    $this->salida .= "		</tr>\n";


                    for($k=0;$k<sizeof($medic);$k++)
                    {
                         if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                         //if($medic[$i][solicitud_id]!=$solicitud)
                         if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
                              $solicitud=$medic[$k][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$k][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$k][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"28%\">".$medic[$k][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$k][cant_solicitada])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
                         {

                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
                              $this->salida .= "</tr>";

                         }
                    }
                         $this->salida.=" <tr align='right' class=\"hc_table_submodulo_list_title\"><td colspan='6'></td>";
                         $this->salida .= "<td>";
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
                         $this->salida.=" </td>";
                         $this->salida.="</tr></table></form>";

                         
               //$href = ModuloGetURL('app','EstacionE_Medicamentos','user','xxx',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
               }
               $conteo=$this->GetPacientesConMedicamentosPorDesp($datos_estacion['ingreso'],'M',$estacion['estacion_id']);
               if($conteo==1)
               {
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"bodega"=>$datos));
                    $this->salida .= "<form name='med' action='".$f."' method='POST'><br>\n";
                    $this->salida.="&nbsp;<div align='center'><input name=\"devolucion\" type=\"submit\" class=\"input-submit\"  value=\"Realizar Devoluciones Medicamentos\"></div>";
                    $this->salida.="</form>";
               }
					 	
					
               //parte de insumos*******************************************************
               
               //consulta de insumos solicitados
               unset($medic);
               $medic=$this->GetInsumosSolicitadosControlPacientes($datos_estacion['ingreso'],$estacion[empresa_id]);

               if(sizeof($medic))
               {
                    $_SESSION['ESTACION']['VECTOR_SOL_INS'][$datos_estacion['ingreso']]=$medic;
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudIns',array("ingreso"=>$datos_estacion['ingreso'],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";

                    $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
                    $this->salida .= "			<td colspan='7'  align=\"center\">SOLICITUDES REALIZADAS DE INSUMOS (pendiente despacho)</td>\n";
                    $this->salida .= "		</tr>\n";

                    $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
                    $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
                    $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
                    $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                    $this->salida .= "			<td width=\"25%\" >INSUMO</td>\n";
                    $this->salida .= "			<td width=\"25%\"  >ABREVIACION</td>\n";
                    $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
                    $this->salida .= "			<td width=\"2%\" ></td>\n";
                    $this->salida .= "		</tr>\n";


                    for($k=0;$k<sizeof($medic);$k++)
                    {
                         if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                         //if($medic[$i][solicitud_id]!=$solicitud)
                         if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
                              $solicitud=$medic[$k][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$k][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$k][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"28%\">".$medic[$k][descripcion_abreviada]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$k][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
                         {

                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
                              $this->salida .= "</tr>";

                         }
                    }
                         $this->salida.=" <tr align='right' class=\"hc_table_submodulo_list_title\"><td colspan='6'></td>";
                         $this->salida .= "<td>";
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
                         $this->salida.=" </td>";
                         $this->salida.="</tr></table></form><br>";

               }
               
               
                              $conteoI=$this->GetPacientesConMedicamentosPorDesp($datos_estacion['ingreso'],'I',$estacion['estacion_id']);
                         if($conteoI==1)
                         {
                                   $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"bodega"=>$datos));
                                   $this->salida .= "<form name='med' action='".$f."' method='POST'><br>\n";
                                   $this->salida.="&nbsp;<div align='center'><input name=\"devolucion\" type=\"submit\" class=\"input-submit\"  value=\"Realizar Devolucion Insumo\"></div>";
                                   $this->salida.="</form>";
                         }
                         
                         
                                                  
                         
                         $Y = ModuloGetURL('app','EstacionE_Medicamentos','user','AgregarInsumos_A_Paciente',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion));
                         $this->salida .= "<form name='med' action='".$Y."' method='POST'><br>\n";
                         $this->salida.="<div align='center'><input name=\"insumos\" type=\"submit\" class=\"input-submit\"  value=\"Agregar Insumos\">";
                         $this->salida.="</form>";

		    }
               else
               {
                         $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                         $this->salida.="<tr  align=\"center\"><td><label class='label_mark'>EL PACIENTE NO TIENE MEDICAMENTOS SOLICITADOS";
                         $this->salida.="</tr></td></label>";
                         $this->salida.="</table><br>";
     //$m = $m+1;
               }

				//**pintar los medicamentos finalizados
			//	$vectorMSF = $this->Consulta_Solicitud_Medicamentos_Finalizados();
		/*		if ($vectorMSF)
				{
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS FINALIZADOS</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"10%\">CODIGO</td>";
					$this->salida.="  <td width=\"28%\">PRODUCTO</td>";
					$this->salida.="  <td width=\"28%\">PRINCIPIO ACTIVO</td>";
					$this->salida.="  <td colspan= 2 width=\"14%\">OPCIONES</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vectorMSF);$i++)
					{
  						if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							if($vectorMSF[$i][item] == 'NO POS')
							{
									$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMSF[$i][codigo_producto]."<BR>NO_POS</td>";
							}
						  else
							{
									$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorMSF[$i][codigo_producto]."</td>";
							}
							$this->salida.="  <td align=\"center\" width=\"28%\">".$vectorMSF[$i][producto]."-".$vectorMSF[$i][evolucion_id]."</td>";
							$this->salida.="  <td align=\"left\" width=\"28%\">".$vectorMSF[$i][principio_activo]."</td>";
              //DETALLE DE SUMINISTRO
							$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Detalle_Suministro', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto'], 'producto'.$pfj=>$vectorMSF[$i]['producto'], 'principio_activo'.$pfj=>$vectorMSF[$i]['principio_activo']));
							$this->salida .= "		<td align='center' width=\"7%\"><font color=\"#990000\"><a href='".$accion."'>Ver Detalle Suministro</a></font></td>\n";
              //REENVIO
							if ($_SESSION['PROFESIONAL'.$pfj]==1)
							{
									$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos', 'codigo_producto'.$pfj=>$vectorMSF[$i]['codigo_producto']));
									$this->salida.="  <td align=\"left\" width=\"7%\"><a href='".$accion."'><font color='#8C8030'>Reenviar</font></a></td>";
							}
							elseif($_SESSION['PROFESIONAL'.$pfj]==3)
							{
									$this->salida.="  <td align=\"left\" width=\"7%\">Finalizado</td>";
							}

							$this->salida.="</tr>";
					}
					$this->salida.="</table><br>";
				}
				else
				{
          $m = $m+1;
				}*/

				//fin de mediacamentos finalizadops
				$this->salida .= "</form>";

			  if ($_SESSION['PROFESIONAL'.$pfj]!=1)
				{
            if($m==2)
						{
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
	              $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
								$this->salida.="</tr>";
								$this->salida.="</table><br>";
						}
				}


//los medicamentos frecuentes por diagnostico
//este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
				if ($_SESSION['PROFESIONAL'.$pfj]==1)
				{
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'llenar_solicitud_medicamento'));
						$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
						$vectorMF = $this->Medicamentos_Frecuentes_Diagnostico();
						if ($vectorMF)
							{
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td align=\"center\" colspan=\"7\">MEDICAMENTOS EMPLEADOS PARA LOS DIAGNOSTICOS DE ESTA HISTORIA CLINICA</td>";
									$this->salida.="</tr>";

									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="  <td width=\"5%\"></td>";
									$this->salida.="  <td width=\"5%\">CODIGO</td>";
									$this->salida.="  <td width=\"23%\">PRODUCTO</td>";
									$this->salida.="  <td width=\"23%\">PRINCIPIO ACTIVO</td>";
									if ($this->bodega==='')
									{
										$this->salida.="  <td colspan = 2 width=\"15%\">FORMA</td>";
									}
									else
									{
										$this->salida.="  <td width=\"15%\">FORMA</td>";
										$this->salida.="  <td width=\"5%\">EXISTENCIA</td>";
									}
									$this->salida.="  <td width=\"4%\">OPCION</td>";
									$this->salida.="</tr>";
									for($i=0;$i<sizeof($vectorMF);$i++)
									{
											if( $i % 2){ $estilo='modulo_list_claro';}
											else {$estilo='modulo_list_oscuro';}
											$this->salida.="<tr class=\"$estilo\">";
											$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][item]."</td>";
											$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][codigo_producto]."</td>";
											$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][producto]."</td>";
											$this->salida.="  <td align=\"left\" width=\"20%\">".$vectorMF[$i][principio_activo]."</td>";

									if ($this->bodega==='')
										{
											$this->salida.="  <td colspan = 2 align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
										}
									else
										{
											$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorMF[$i][forma]."</td>";
											if(!empty($vectorMF[$i][existencia]))
											{
													$this->salida.="  <td align=\"center\" width=\"5%\">".$vectorMF[$i][existencia]."</td>";
											}
											else
											{
													$this->salida.="  <td align=\"center\" width=\"5%\">--</td>";
											}
											//$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opE".$pfj."[$i]' value = ".$cargo.",".$vectorE[$i][especialidad]."></td>";
										}
										$this->salida.="  <td align=\"center\" width=\"5%\"><input type = radio name= 'opE$pfj' value = '".$vectorMF[$i][item].",".$vectorMF[$i][codigo_producto].",".$vectorMF[$i][producto].",".$vectorMF[$i][principio_activo].",".$vectorE[$i][concentracion_forma_farmacologica].",".$vectorE[$i][unidad_medida_medicamento_id].",".$vectorE[$i][forma].",".$vectorE[$i][cod_forma_farmacologica]."'></td>";
											$this->salida.="</tr>";
										}

									$this->salida.="<tr class=\"$estilo\">";
									$this->salida .= "<td align=\"right\" colspan=\"7\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"FORMULAR\"></td>";
									$this->salida.="</tr>";
								$this->salida.="</table><br>";
							}
						$this->salida .= "</form>";
					}
				//fin de medicamentos MAS FRECUENTES POR DIAGNMOSTICO

        //este if es especial en hospitalizacion para que solo se ejecute cuando es medico y no enfermera
				if ($_SESSION['PROFESIONAL'.$pfj]==1)
				{
				   //lo que inserte
					 $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
					'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
					'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
					'producto'.$pfj=>$_REQUEST['producto'.$pfj],
					'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));

					$this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"5%\">TIPO</td>";

					$this->salida.="<td width=\"10%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1$pfj'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					if (($_REQUEST['criterio1'.$pfj])  == '002')
						{
							$this->salida.="<option value = '002' selected>Frecuentes</option>";
						}
					else
						{
							$this->salida.="<option value = '002' >Frecuentes</option>";
						}
					$this->salida.="</select>";
					$this->salida.="</td>";

					$this->salida.="<td width=\"7%\">PRODUCTO:</td>";
					$this->salida .="<td width=\"23%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>" ;

					$this->salida.="<td width=\"8%\">PRINCIPIO ACTIVO:</td>";
					$this->salida .="<td width=\"22%\" align='center' ><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"        ></td>" ;

					$this->salida .= "<td  width=\"5%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
          //hasta aqui lo que inserte
				}


	if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
		{
			$href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
		}
		else
		{
			 if(UserGetUID()==0)
			 {
					$href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			 }
			 else
			 {
		   		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			 }
		}
		$this->salida .= "<div class='normal_10' align='center'><BR><a href='".$href."'>Volver Menu</a><br>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}



     //funcion que recibe los medicamentos / insumos por parte de la enfermera o el auxiliar.
     function Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud,$data)
     {
		if(empty($estacion))
		{
			$estacion=$_REQUEST['estacion'];
			$datos_estacion=$_REQUEST['datos_estacion'];
			$codigo=$_REQUEST['codigo_producto'];
			//$solicitud=$_REQUEST['solicitud'];
			$data[0]='';
		}
		
		$this->salida .= ThemeAbrirTabla("RECIBIR MEDIAMENTOS / INSUMOS");
		
			
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
		$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
		$this->salida.="</tr></table><br>";
		
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		
							//parte de las solicitudes de medicmanetos por parte d e los pacientes
							
		$cont=0;
		for($w=0;$w<sizeof($data);$w++)
		{
			$e=explode(",",$data[$w]);
			if(!empty($e[0]))
			{
				$ingreso=$e[0];
				$codigo=$e[1];unset($e);
			}
			
               unset($medic);
			$medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datos_estacion['ingreso'],$estacion[empresa_id],$solicitud,$codigo);

			if(is_array($medic))
			{$cont=1;}
			else
			{
				if($cont==0)
				{
					$cont=0;
				}
			}
			if(sizeof($medic))
			{
				$f = ModuloGetURL('app','EstacionE_Medicamentos','user','Insertar_Recibido_Para_Pacientes',array("ingreso"=>$datos_estacion['ingreso'],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"solicitud"=>$solicitud,"codigo"=>$codigo,"data"=>$data));
				$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

				$this->salida .= "	<table align=\"center\" width=\"80%\"  border=\"1\" class='modulo_list_table'\n>";

				if($w==0)
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS E INSUMOS POR RECIBIR</td>\n";
					$this->salida .= "		</tr>\n";
				}

				$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
				//$this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
				$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
				$this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO</td>\n";
				$this->salida .= "			<td width=\"13%\" >CANT SOL</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT REC</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT FALT</td>\n";
				$this->salida .= "			<td width=\"12%\" ></td>\n";
				$this->salida .= "		</tr>\n";


				for($k=0;$k<sizeof($medic);$k++)
				{
					if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
					//if($medic[$i][solicitud_id]!=$solicitud)
				/*	if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
					{
						$this->salida .= "<tr $estilo>\n";
						$this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
						//$solicitud=$medic[$k][solicitud_id];
						$this->salida .= "<td colspan = 6 width=\"65%\">";
						$this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
					}*/

					$this->salida .= "<tr $estilo>\n";
					$this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
					$this->salida .= "<td $estilo align='center' width=\"44%\"><label class='label_mark'>".$medic[$k][producto]."</label></td>\n";
					$this->salida .= "<td $estilo align=\"center\" width=\"15%\"><label class='label_mark'>".floor($medic[$k][cantidad])."</label></td>\n";
							
					//aca colocar el query de las cantidades recibisdas...........
					$recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datos_estacion['ingreso'],$medic[$k][codigo_producto],$estacion[estacion_id]);
					$faltante=$medic[$k][cantidad]-$recepcion;
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($recepcion)."</td>\n";
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($faltante)."</td>\n";unset($faltante);
				
					$this->salida .= "<td $estilo width=\"18%\"><input type='text' name='cantidad[][".$medic[$k][codigo_producto]."]' size='5' maxlength='10' ></td>\n";unset($faltante);
					$this->salida .= "<input type='hidden' name='cant_sol[][".$medic[$k][codigo_producto]."]' value='".floor($medic[$k][cantidad])."'>\n";
					$this->salida .= "<input type='hidden' name='cant_rec[][".$medic[$k][codigo_producto]."]' value='".floor($recepcion)."'>\n";
				
					$this->salida.=" </tr>";
					/*if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
					{

						$this->salida .= "</table>";
						$this->salida .= "</td>";
						//$this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
						$this->salida .= "</tr>";

					}*/
				}
					$this->salida.="</table>";
			}
			//fin de solicitudes por parte de los pacientes.		

		
	  }//fin for primero
			
		if($cont >0)
		{
			$this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>NOMBRE DE LA PERSONA QUE ENTREGA</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>OBSERVACIONES :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			
			$this->salida .= '<br><br><table align="center" width="40%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
		
			$o = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
			$this->salida .= '<form name="volver" method="post" action="'.$o.'">';
		
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
		}
		else
		{
			  $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
				$this->salida .= '<tr>';
				$this->salida .= '<td align="center"><label class=label_mark>NO HAY MAS MEDICAMENTOS/INSUMOS POR RECIBIR</label>';
				$this->salida .= '</td>';
				$this->salida.="</tr>";
				$this->salida .= '</table>';
				$o = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$o."'>Volver Estaci&oacute;n</a><br>";
		}	
		
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}


function AgregarInsumos_A_Paciente($estacion,$datos_estacion)
{
     if(!$estacion)
	{
		$estacion=$_REQUEST["datos_estacion"];
		$datos_estacion=$_REQUEST["datos_pac"];
	}	
     
	$this->salida .= "<SCRIPT>";
	$this->salida .= "function chequeoTotal(frm,x){";
	$this->salida .= "  if(x==true){";
	$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
	$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
	$this->salida .= "        frm.elements[i].checked=true";
	$this->salida .= "      }";
	$this->salida .= "    }";
	$this->salida .= "  }else{";
	$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
	$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
	$this->salida .= "        frm.elements[i].checked=false";
	$this->salida .= "      }";
	$this->salida .= "    }";
	$this->salida .= "  }";
	$this->salida .= "}";
	$cadena .= "	function CargarPagina(href,valor) {\n";
	$cadena .= "		var url=href;\n";
	$cadena .= "		location.href=url+'&bodega='+valor;\n";
	$cadena .= "	}\n\n";
	$this->salida .=$cadena;
	$this->salida .= "</SCRIPT>";
	$datos1=$this->GetEstacionBodega($estacion,1);
	$this->salida .= ThemeAbrirTabla("AGREGAR INSUMOS");
	$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
	$this->salida .= "		<tr class=\"modulo_table_title\">\n";
	$this->salida .= "			<td>PACIENTE</td>\n";
	$this->salida .= "			<td>HABITACION</td>\n";
	$this->salida .= "			<td>CAMA</td>\n";
	$this->salida .= "			<td>PISO</td>\n";
	$this->salida .= "		</tr>\n";
	$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
	$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
	$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
	$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
	$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
	$this->salida.="</tr></table><br>";
	
     $accion = ModuloGetURL('app','EstacionE_Medicamentos','user','AgregarInsumos_A_Paciente',array("conteo"=>$_REQUEST['conteo'],"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion));
		
     $this->salida .= "            <form name=\"mmm\" action=\"$accion\" method=\"post\">";
     $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"70%\" class=\"modulo_table_list_title\">";
     $this->salida.="<tr class=\"modulo_table_list_title\">";
     $this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO DE INSUMOS</td>";
     $this->salida.="</tr>";

     $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
     $this->salida.="<td width=\"5%\">BODEGA</td>";
     $this->salida.="<td width=\"10%\">";
			
     $this->salida.="<select name=bodega class='select'>";

     //$this->salida.="<option value='-1' selected>--Seleccionar--</option>";
					
     for($i=0;$i<sizeof($datos1);$i++)
     {
          if($datos1[$i][bodega]==$_REQUEST['bodega'])
          {
               $this->salida.="<option value=".$datos1[$i][bodega]." selected>".$datos1[$i][descripcion]."</option>";
               $a=1;
          }
          else
          {
                    $this->salida.="<option value=".$datos1[$i][bodega].">".$datos1[$i][descripcion]."</option>";
                         
          }	

     }
	if($a !=1){$selected="selected";}else{$selected="";}
     $this->salida.="<option value=*/* $selected>SOLICITUD PACIENTE</option>";
     $this->salida.="</select>";
     $this->salida.="</td>";

		
     $this->salida.="<td width=\"10%\" align = left >";
     $this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
     if($_REQUEST['criterio']=='1')
     {$sel1="selected";$sel2="";}else{$sel2="selected";$sel1="";}
     $this->salida.="<option value = '1' $sel1>Codigo</option>";
     $this->salida.="<option value = '2' $sel2>Insumo</option>";
     $this->salida.="</select>";
     $this->salida.="</td>";
     $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;

     $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
     $this->salida.="</tr>";
     $this->salida.="<tr class=\"modulo_table_list_title\">";
     if($_REQUEST['busqueda'])
     {
          $cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
     }
     else
     {
          $cadena="Buscador Avanzado: Busqueda de todos los insumos";
     }
     $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
     $this->salida.="</tr>";
     $this->salida.="</table>";

     if($_REQUEST['buscar'] OR $_REQUEST['ADD'])
     {
          $filtro=$this->GetFiltro($_REQUEST['criterio'],$_REQUEST['busqueda']);
     }
	
     //estos if de aqui en adelante,es importante ya que si hemos presionado el boton aicionar temp
     if(empty($_REQUEST['paso']))
          {$pas=1;}else{$pas=$_REQUEST['paso'];}
		
	
		//si presionamos quitar.
		//cabe decir que segun el paso quitamos todos los items q esten en variable de 
		//session.
               if($_REQUEST['DEL'])
               {
                    if($_SESSION['EXISTENCIA'][$pas])
                    {unset($_SESSION['EXISTENCIA'][$pas]);}
                    $variable="SE QUITO TODOS LOS INSUMOS ADICIONADOS DE LA PAGINA &nbsp; $pas";
               }
               else
               {
                    $variable='';
               }
               
               
               //si presionamos adicionar........
               if($_REQUEST['ADD'])
               {	
               /*if($_SESSION['EXISTENCIA'][$pas])
                    {unset($_SESSION['EXISTENCIA'][$pas]);}*/
                    
                    foreach($_REQUEST['op'] as $index=>$valor)
                    {
                         //$arr_op=explode("^",$numerodecuenta);
                         if(is_numeric($_REQUEST['cant'.$valor]) && $_REQUEST['cant'.$valor] > 0)
                         {$_SESSION['EXISTENCIA'][$pas][$valor]=$valor."*".$_REQUEST['cant'.$valor];}
                         //$_SESSION['EXISTENCIA'][$pas][$valor][$_REQUEST['cant'.$valor]]=$_REQUEST['cant'.$valor];
                    }				
                    
                    if($_REQUEST['bodega']=='*/*')
                    {
                         $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM']=$_REQUEST['nom'];
                         $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA']=$_REQUEST['area'];
                    }
                    else
                    {
                         unset($_SESSION['MEDICA_DATOS_SOL_PAC']);
                    }	
                    
               }
               
               $nom=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM'];
               $area=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA'];
          
               $arr_vect=$this->GetInsumos($_REQUEST['bodega'],$filtro);
               if(is_array($arr_vect))
               {
                    $this->salida .= "<br><div align='center'><label class='label_mark'>$variable</label></div>";
                    $this->salida .= "<br><table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"10%\">ID</td>";
                    $this->salida.="  <td width=\"60%\" colspan='2'>PRODUCTO - ABREVIACION</td>";
                    $this->salida.="  <td width=\"30%\" >CANT</td>";
                    $this->salida .= '<form name="vv" method="post" action="'.$o.'">';
     
                    //$this->salida.="  <td width=\"35%\">CONSULTA</td>";
                    $this->salida.="  <td width=\"5%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($arr_vect);$i++)
                    {
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class='$estilo' align='left'>";
                         $this->salida.="  <td >".$arr_vect[$i][codigo_producto]."</td>";
                         $this->salida.="  <td >".$arr_vect[$i][descripcion]."</td>";
                         $this->salida.="  <td >".$arr_vect[$i][descripcion_abreviada]."</td>";
                         
                         $info=explode("*",$_SESSION['EXISTENCIA'][$pas][$arr_vect[$i][codigo_producto]]);
                         $this->salida.="  <td ><label class='label_mark'>Cant &nbsp;</label><input type='text' class='input-text' name=cant".$arr_vect[$i][codigo_producto]." value='".$info[1]."' size='8' maxlength='8'></td>";
                         
                         if($info[0]== $arr_vect[$i][codigo_producto])
                         {$check="checked";}else{$check="";}
                         $this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$arr_vect[$i][codigo_producto]." $check></td>";unset($check);
                         //	$subT=$subT+$_SESSION['CAJA']['AUX']['liq'][$i][total_paciente];
                         $this->salida.="</tr>";
                         //$i++;
                    }
                    
               //$URL = ModuloGetURL('app','EstacionE_Medicamentos','user','AqgregarInsumos_A_Paciente',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));
               //$this->salida .= "            <form name=\"forma\" action=\"$URL\" method=\"post\">";
     
     
          if($_REQUEST['bodega']=='*/*')
          {
               
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td colspan='5'>";
               
               $this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td ><label class='label_mark'>observaciones :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
               $this->salida.="</tr>";
               $this->salida.="</table>";
               
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }	
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td colspan='4'><input type=submit name=DEL value='QUITAR ITEMS SELECCIONADOS DE ESTA PAGINA' class=input-submit></td>";
               $this->salida.="  <td><input type=submit name=ADD value=ADICIONAR class=input-submit></form></td>";
               //$this->salida.="  <td ><input type=submit name=DEL value=QUITAR class=input-submit></form></td>";
          
               $this->salida.="</tr>";
               $this->salida.="</table>";
               
          
               $this->salida.=$this->RetornarBarra($filtro);
          }
          else
          {
               $this->salida .= "<br><br><div align='center'><label class='label_mark'>SELECCIONE LA BODEGA</label></div>";
          }
          
          if($_REQUEST['bodega']=='*/*')//esto quiere decir q es SOLICITUD PARA PACIENTE
          {
               $XYS = ModuloGetURL('app','EstacionE_Medicamentos','user','Insertar_Solicitud_Insumos_Para_Paciente',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));
          }
          else
          {
               $XYS = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertarInsumosPaciente',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));	
          }	
          
          $this->salida .= "            <form name=\"formainsert\" action=\"$XYS\" method=\"post\">";
          $this->salida .= '<br><br><table align="center" width="40%" border="0">';
          $this->salida .= '<tr>';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
     
          $o = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
          $this->salida .= '<form name="volver" method="post" action="'.$o.'">';
     
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
          $this->salida .= '</tr>';
          $this->salida .= '</table>';
          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;		
     }



//funciones para generar la barra de segmentos en el buscador
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

	 function RetornarBarra($filtro,$uno){
	 	if($this->limit>=$this->conteo){
				return '';
		}
		//if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		//de busqueda...
	  	$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		
          $estacion=$_REQUEST["datos_estacion"];
		$datos_estacion=$_REQUEST["datos_pac"];
          if($uno == 1)
          {
			$accion=ModuloGetURL('app','EstacionE_Medicamentos','user','SolSuministros_x_estacion',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"bodega"=>$_REQUEST['bodega']));
          }
          else
          {
               $accion=ModuloGetURL('app','EstacionE_Medicamentos','user','AgregarInsumos_A_Paciente',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"bodega"=>$_REQUEST['bodega']));
          }
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">Páginas</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
							$diferencia=$numpasos-9;
							if($diferencia<0){$diferencia=1;}
							for($i=($diferencia);$i<=$numpasos;$i++){
								if($paso==$i){
									$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
								}else{
									$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
								}
								$colspan++;
							}
							if($paso!=$numpasos){
								$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
								$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
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
				
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
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
			
		$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
		}
    
		
	}
//fin de las fujnciones para la barra de segnentacion


     /*
     *
     *
     *		@Author Tizziano Perea Ocoro.
     *		@access Private
     *		@return bool
     *		Proposito: Unificacion de funciones para recibir insumos y medicamentos
     */


     function MedicamentosIns_X_Recibir($estacion,$bodega,$SWITCHE)
     {
          if(empty($estacion))
          {
               $estacion=$_REQUEST['estacion'];
               $SWITCHE=$_REQUEST['switche'];
               $bodega=$_REQUEST['bodega'];
          }
          $_SESSION['ESTA_MEDIC']['ESTADO']=1;
          $datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
          if($datoscenso=="ShowMensaje"){unset($datoscenso);}
          unset($_SESSION['ESTA_MEDIC']['ESTADO']);//esto es para crear un solo vect[hospitalizacion],tanto para hosp com urgen
          $datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
          //$datoscenso=$this->GetPacientesSolicitudePendientes($estacion['estacion_id']);
          $nom_bodega=$this->TraerNombreBodega($estacion,$bodega);

          $this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE INSUMOS Y MEDICAMENTOS (Pendiente Despacho) &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
          $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";
          foreach($datoscenso as $key => $value)
          {
               foreach($value as $A => $B)
               {
                    for($tpo=0; $tpo<2; $tpo++)
                    {
                         if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

                         if($tpo==0)
                         {
                              //consulta de medicamentos solicitados
                              $medic=$this->GetInsumosSolicitados($B['ingreso'],$estacion,$bodega);
                         }elseif($tpo==1)
                         {
                              //consulta de medicamentos solicitados
                              $medic=$this->GetMedicamentosSolicitados($B['ingreso'],$estacion,$bodega);
                         }

                         if(!empty($medic))
                         {
                              $contador=4;
                              if($tpo==0)
                              {
                                   $_SESSION['ESTACION']['VECTOR_SOL_INS'][$B[ingreso]]=$medic;
                                   //mandamos spia en 1 para usar la misma funcion de cancelar solicitud
                                   //individual...y con esta variable sabemos q retornara aqui.
                                   $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudIns',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                                   $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                              }elseif($tpo==1)
                              {
                                   $_SESSION['ESTACION']['VECTOR_SOL'][$B[ingreso]]=$medic;
                                   //mandamos spia en 1 para usar la misma funcion de cancelar solicitud
                                   //individual...y con esta variable sabemos q retornara aqui.
                                   $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudMed',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                                   $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                              }

                              $this->salida .= "<br><table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                              
                              if($tpo==0)
                              {
                                   $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                   $this->salida .= "	<td colspan=\"4\">INSUMOS POR RECIBIR</td>\n";
                                   $this->salida .= "	</tr>\n";
                              }elseif($tpo==1)
                              {
                                   $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                   $this->salida .= "	<td colspan=\"4\">MEDICAMENTOS POR RECIBIR</td>\n";
                                   $this->salida .= "	</tr>\n";
                              }

                              $this->salida .= "	<tr class='modulo_table_title'>\n";
                              $this->salida .= "		<td>HAB.</td>\n";
                              $this->salida .= "		<td>CAMA</td>\n";
                              $this->salida .= "		<td>TIEMPO HOSP.</td>\n";
                              $this->salida .= "		<td>PACIENTE</td>\n";
                              $this->salida .= "	</tr>\n";

                              $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                              if(empty($B[pieza]))
                              {
                                   $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                                   $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                              }
                              else
                              {
                                   $this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
                                   $this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
                              }
                              $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
                              $this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
                              $linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
                              $this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
                              $this->salida .= "	</tr>\n";


                              $this->salida .= "	<tr class='hc_table_submodulo_list_title'><td colspan='4'>\n";
                              $this->salida .= "	<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                              $this->salida .= "		<tr class='modulo_table_title'>\n";
                              $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                              $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                              $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
                              $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
                              $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
                              $this->salida .= "			<td width=\"2%\" ></td>\n";
                              $this->salida .= "		</tr>\n";


                              for($i=0;$i<sizeof($medic);$i++)
                              {
                                   if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                                   //if($medic[$i][solicitud_id]!=$solicitud)
                                   if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                                   {
                                        $this->salida .= "<tr $estilo>\n";
                                        $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                        $solicitud=$medic[$i][solicitud_id];
                                        $this->salida .= "<td colspan = 4 width=\"65%\">";
                                        $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                                   }

                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                                   $this->salida.=" </tr>";
                                   if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                                   {

                                        $this->salida .= "</table>";
                                        $this->salida .= "</td>";
                                        $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
                                        $this->salida .= "</tr>";

                                   }
                              }
                              $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='6'>";
                              $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
                              $this->salida.=" </td>";
                              $this->salida .= "</tr>";
                              $this->salida.="</table></form><br>";
     
                              $this->salida .= "</td></tr>\n";
                              $this->salida.="</table><br>";
                         }
                         if($contador !=4)
                         {$contador=1;}
                    }//fin for
               }//fin foreach

               if($contador==1)
               {
                    $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
                    $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES DE MEDICAMENTOS PARA ESTA BODEGA</label>";
                    $this->salida .= "</td></tr>";
                    $this->salida.="</table><br>";
               }
               else
               {
                    $href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                    $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
               }

               $hr = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,"switche"=>$SWITCHE));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>Volver a Seleccion de Bodega</a><br>";

               $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

               $this->salida .= themeCerrarTabla();
               unset($ItemBusqueda);
               return true;
	     }
	}

		
          /*
		*
		*
		*		@Author jaja
		*		@access Private
		*		@return bool
		*/
/*		function InsumosXRecibir($estacion,$bodega,$SWITCHE)
		{
               if(empty($estacion))
               {
                    $estacion=$_REQUEST['estacion'];
                    $SWITCHE=$_REQUEST['switche'];
                    $bodega=$_REQUEST['bodega'];
               }
			$_SESSION['ESTA_MEDIC']['ESTADO']=1;
			$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
			if($datoscenso=="ShowMensaje"){unset($datoscenso);}
			unset($_SESSION['ESTA_MEDIC']['ESTADO']);//esto es para crear un solo vect[hospitalizacion],tanto para hosp com urgen
			$datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
			//$datoscenso=$this->GetPacientesSolicitudePendientes($estacion['estacion_id']);
			$nom_bodega=$this->TraerNombreBodega($estacion,$bodega);


               $this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE INSUMOS (pendiente despacho) &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
               $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida .= " </table>";
				
               foreach($datoscenso as $key => $value)
               {
                    //if($key == "hospitalizacion" OR $key == "urgencias")
                    //{

                         foreach($value as $A => $B)
                         {
                              if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                              //consulta de medicamentos solicitados
                              $medic=$this->GetInsumosSolicitados($B['ingreso'],$estacion,$bodega);

                              if(!empty($medic))
                              {
                                   $contador=4;
                                   $_SESSION['ESTACION']['VECTOR_SOL_INS'][$B[ingreso]]=$medic;
                                   //mandamos spia en 1 para usar la misma funcion de cancelar solicitud
                                   //individual...y con esta variable sabemos q retornara aqui.
                                   $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudIns',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                                   $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                                   $this->salida .= "<br><table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                                   $this->salida .= "	<tr class='modulo_table_title'>\n";
                                   $this->salida .= "		<td>HAB.</td>\n";
                                   $this->salida .= "		<td>CAMA</td>\n";
                                   $this->salida .= "		<td>TIEMPO HOSP.</td>\n";
                                   $this->salida .= "		<td>PACIENTE</td>\n";
                                   $this->salida .= "	</tr>\n";

                                   $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                   if(empty($B[pieza]))
                                   {
                                        $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                                        $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                                   }
                                   else
                                   {
                                        $this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
                                        $this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
                                   }
                                   $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
                                   $this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
                                   $linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
                                   $this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
                                   $this->salida .= "	</tr>\n";


                                   $this->salida .= "	<tr class='hc_table_submodulo_list_title'><td colspan='4'>\n";
                                   $this->salida .= "	<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                                   $this->salida .= "		<tr class='modulo_table_title'>\n";
                                   $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                                   $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
                                   $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
                                   $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
                                   $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
                                   $this->salida .= "			<td width=\"2%\" ></td>\n";
                                   $this->salida .= "		</tr>\n";

	                              for($i=0;$i<sizeof($medic);$i++)
     	                         {
                                        if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                                        //if($medic[$i][solicitud_id]!=$solicitud)
                                        if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                                        {
                                             $this->salida .= "<tr $estilo>\n";
                                             $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                             $solicitud=$medic[$i][solicitud_id];
                                             $this->salida .= "<td colspan = 4 width=\"65%\">";
                                             $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                                        }

                                        $this->salida .= "<tr $estilo>\n";
                                        $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                                        $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                                        $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                                        $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                                        $this->salida.=" </tr>";
                                        if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                                        {
                                             $this->salida .= "</table>";
                                             $this->salida .= "</td>";
                                             $this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
                                             $this->salida .= "</tr>";
                                        }
                                   }
                                   $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='6'>";
                                   $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
                                   $this->salida.=" </td>";
                                   $this->salida .= "</tr>";
                                   $this->salida.="</table></form><br>";

	                              $this->salida .= "</td></tr>\n";

     	                         $this->salida.="</table><br>";
			               }
                              if($contador !=4)
                              {$contador=1;}

               		}//fin foreach
     
                         if($contador==1)
                         {
                              $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
                              $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES DE MEDICAMENTOS PARA ESTA BODEGA</label>";
                              $this->salida .= "</td></tr>";
                              $this->salida.="</table><br>";
                         }
                         else
                         {
                              $href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosXRecibir',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                              $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
                         }

                    $hr = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,"switche"=>$SWITCHE));
                    $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>Volver a Seleccion de Bodega</a><br>";
     
                    $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
                    $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
     
                    $this->salida .= themeCerrarTabla();
                    unset($ItemBusqueda);
                    return true;
	          //}
     	}
	}*/

		/*
		*
		*
		*		@Author jaja
		*		@access Private
		*		@return bool
		*/
/*		function MedicamentosXRecibir($estacion,$bodega,$SWITCHE)
		{

		  if(empty($estacion))
			{
				$estacion=$_REQUEST['estacion'];
				$SWITCHE=$_REQUEST['switche'];
				$bodega=$_REQUEST['bodega'];
			}
			$_SESSION['ESTA_MEDIC']['ESTADO']=1;
			$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$estacion['estacion_id']));
			if($datoscenso=="ShowMensaje"){unset($datoscenso);}
			unset($_SESSION['ESTA_MEDIC']['ESTADO']);//esto es para crear un solo vect[hospitalizacion],tanto para hosp com urgen
			$datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
			//$datoscenso=$this->GetPacientesSolicitudePendientes($estacion['estacion_id']);
			$nom_bodega=$this->TraerNombreBodega($estacion,$bodega);


				$this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE MEDICIAMENTOS (pendiente despacho) &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
				foreach($datoscenso as $key => $value)
				{
                         //if($key == "hospitalizacion" OR $key == "urgencias")
					//{

						foreach($value as $A => $B)
						{

									if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

								//consulta de medicamentos solicitados
									$medic=$this->GetMedicamentosSolicitados($B['ingreso'],$estacion,$bodega);

								if(!empty($medic))
								{
								  $contador=4;
									$_SESSION['ESTACION']['VECTOR_SOL'][$B[ingreso]]=$medic;
									//mandamos spia en 1 para usar la misma funcion de cancelar solicitud
									//individual...y con esta variable sabemos q retornara aqui.
									$f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarCancelSolicitudMed',array('ingreso'=>$B[ingreso],'spia'=>1,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
									$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

									$this->salida .= "<br><table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
									$this->salida .= "	<tr class='modulo_table_title'>\n";
									$this->salida .= "		<td>HAB.</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>TIEMPO HOSP.</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "	</tr>\n";

									$this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
									if(empty($B[pieza]))
									{
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
									}
									else
									{
										$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
										$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
									}
									$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
									$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
									$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
									$this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
									$this->salida .= "	</tr>\n";


									$this->salida .= "	<tr class='hc_table_submodulo_list_title'><td colspan='4'>\n";
									$this->salida .= "	<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

									$this->salida .= "		<tr class='modulo_table_title'>\n";
									$this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
									$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
									$this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
									$this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
									$this->salida .= "			<td width=\"5%\" >CANT</td>\n";
									$this->salida .= "			<td width=\"2%\" ></td>\n";
									$this->salida .= "		</tr>\n";


							for($i=0;$i<sizeof($medic);$i++)
							{
										if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
										//if($medic[$i][solicitud_id]!=$solicitud)
										if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
										{
											$this->salida .= "<tr $estilo>\n";
											$this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
											$solicitud=$medic[$i][solicitud_id];
											$this->salida .= "<td colspan = 4 width=\"65%\">";
											$this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
										}


										$this->salida .= "<tr $estilo>\n";
										$this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
										$this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
										$this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
										$this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cant_solicitada])."</td>\n";
										$this->salida.=" </tr>";
										if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
										{

											$this->salida .= "</table>";
											$this->salida .= "</td>";
											$this->salida.="  <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
											$this->salida .= "</tr>";

										}
							}
							$this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='6'>";
							$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar\">";
							$this->salida.=" </td>";
							$this->salida .= "</tr>";
							$this->salida.="</table></form><br>";

							$this->salida .= "</td></tr>\n";


							$this->salida.="</table><br>";
			  	}
					if($contador !=4)
					{$contador=1;}

				}//fin foreach


				if($contador==1)
				{
				  $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
					$this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES DE MEDICAMENTOS PARA ESTA BODEGA</label>";
					$this->salida .= "</td></tr>";
					$this->salida.="</table><br>";
				}
				else
				{
					$href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosXRecibir',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
					$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
				}

				$hr = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,"switche"=>$SWITCHE));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>Volver a Seleccion de Bodega</a><br>";

				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

				$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);
				return true;
			//}
		}
	}*/



	//funcion del medicamentos estacion enfermeria
	/**
	*		FrmShowBodega
	*
	*		@jaja
	*		@access Private
	*		@param array datos de la estacion
	*		@param SWITCHE para determinar si es despacho o devolucion
	*		@return boolean
	*/
	function FrmShowBodega($estacion,$SWITCHE)
	{
		//CallFrmDevolucionMedicamentos
          if(empty($estacion))
          {
               $estacion=$_REQUEST['datos_estacion'];
               $SWITCHE=$_REQUEST['switche'];
               //esta variable de session la usamos para trabajar esta forma indiferente de
               //q sea medicamentos o insumos,para llamar frmshowbodega
               if(empty($_SESSION['ESTACION_MEDICAMENTOS']['ACTION']))
               {$_SESSION['ESTACION_MEDICAMENTOS']['ACTION']=$_REQUEST['accion'];}
          }
          unset($_SESSION['ESTAR']);
          $datos=$this->GetEstacionBodega($estacion);

          if(is_array($datos))
          {
               $this->salida .= ThemeAbrirTabla("SELECCIONAR BODEGAS DE LA ESTACION &nbsp;".$estacion[descripcion4]."");
               
               if($SWITCHE=='despacho') 
               {
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("datos_estacion"=>$estacion,'switche'=>'despacho'));
               }
               elseif($SWITCHE=='recibir')
               {
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,'switche'=>'recibir'));
               }elseif($SWITCHE=='Confirmar_sol')
               {
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallConSuministros_x_estacion',array("datos_estacion"=>$estacion,'switche'=>'Confirmar_sol'));
               }elseif($SWITCHE=='Solicitar_sol')
               {
                    $f = ModuloGetURL('app','EstacionE_Medicamentos','user','CallSolSuministros_x_estacion',array("datos_estacion"=>$estacion,'switche'=>'Solicitar_sol'));
               }

               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

               $this->salida .= "	<br><table align=\"center\" width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr class='modulo_table_list_title'>\n";
               $this->salida .= "			<td width=\"2%\" >BODEGAS</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr class='modulo_list_claro'>\n";
               $this->salida .= "			<td width=\"2%\"  align=\"center\" >\n";

               $this->salida.="<select name='bodega' class='select'>";
               //$this->salida.="<option value=-1>----Seleccione----</option>";

               if(empty($empresa))
               {
                    for($i=0;$i<sizeof($datos);$i++)
                    {
                         $this->salida.="<option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";

                    }
	               $this->salida.="</select>";
               }
               $this->salida .= "			</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida.=" <tr class='modulo_list_oscuro'>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"BUSCAR\"></form>";
               $this->salida.=" </td>";
               $this->salida .= "		</tr>\n";
               $this->salida.="</table><br>";
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("ALERTA","50%");
               $this->salida .= "<div  align='center'<label class='label_error'>NO EXISTEN BODEGAS ASOCIADAS A LA ESTACION</label>";
          }

          $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

          $this->salida .= themeCerrarTabla();
          return true;
	}






	//funcion del medicamentos estacion enfermeria
	/**
	*		FrmDevolucionMedicamentos
	*
	*		Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
	*		Medicamentos que pueden ser devueltos => Alex me dió esta formula:
	*		a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*		ya sea que estén en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*		(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
	*		es mayor a 0)
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function FrmDevolucionMedicamentos($estacion,$bodega,$datos_pac)
	{
		if(!$estacion){
			$estacion = $_REQUEST['datos_estacion'];
			$bodega= $_REQUEST['bodega'];
			$datos_pac= $_REQUEST['datos_pac'];
		}
          
          /*$query="SELECT b.codigo_producto,b.cantidad,d.descripcion,e.sw_control_fecha_vencimiento,b.consecutivo
		FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,inventarios c,inventarios_productos d,
		existencias_bodegas e
		WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.documento='$Documento' AND
		a.documento=b.documento AND  c.empresa_id=a.empresa_id AND c.codigo_producto=b.codigo_producto AND
		d.codigo_producto=b.codigo_producto AND a.empresa_id=e.empresa_id AND a.centro_utilidad=e.centro_utilidad AND a.bodega=e.bodega AND
		b.codigo_producto=e.codigo_producto";
          TRAE LAS DEVOLUCIONES SOLICITADAS*/
          
		$this->salida .= ThemeAbrirTabla("LISTADO DE MEDICAMENTOS PARA DEVOLUCION");
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datos_pac['NombrePaciente']."</td>\n";
		$this->salida .= "			<td>".$datos_pac[pieza]."</td>\n";
		$this->salida .= "			<td>".$datos_pac[cama]."</td>\n";
		$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
		$this->salida.="</tr></table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";


          //variable de session q contiene las bodegas de las estaciones
          if(empty($_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION']))
          {$_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION']=$bodega;}
          else{$bodega=$_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];}
               for($s=0; $s<sizeof($bodega);$s++)
               {
                    $nom_bodega=$this->TraerNombreBodega($estacion,$bodega[$s][bodega]);
                    //pacientes que estan pendientes x hospitalizar con medicamentos
                    $B=$this->GetPacientesPendientesXHospitalizar_Con_medicamentos($estacion,$datos_pac);

                    if($B=="ShowMensaje"){unset($B);}
                    
                    //pacientes hospitalizados con medicamentos por solicitar
                    $B = $this->GetPacientesConMedicamentosPorSolicitar($estacion,$datos_pac,$B);
                    
                    //pacientes en consulta de urgencias con medicamentos por solicitar
                    $B = $this->GetPaciente_Consulta_Urgencia_con_med($estacion,$datos_pac,$B);
							
                    for($l=0;$l<sizeof($B);$l++)
                    {
                         $sumatoria=0;
                         if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                         
                         //Resta de Medicamentos existentes y los despachados para devolucion.
                         $medic=$this->GetDevolucionMedicamentos($B[$l]['ingreso'],$bodega[$s][bodega],'M');

                         if(!empty($medic))
                         {
                              $contador=4;
                              //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
                              $_SESSION['ESTACION']['VECTOR_DEV'][$B[$l][ingreso]][$bodega[$s][bodega]]=$medic;
                              
                              $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDevMed',array("ingreso"=>$B[$l][ingreso],"plan"=>$B[$l][plan_id],"cuenta"=>$B[$l][numerodecuenta],"estacion"=>$estacion,"bodega"=>$bodega[$s][bodega],"datos_pac"=>$datos_pac));
                              
                              $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                              $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                              $this->salida .= "	<tr class='modulo_table_title'>\n";
                              
                              $this->salida .= "		<td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
                              $this->salida .= "	</tr>\n";

                              $this->salida .= "	<tr class=hc_table_submodulo_list_title><td width=\"95%\" colspan='4'>\n";
                              $this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                              $this->salida .= "		<tr  class='modulo_table_title'>\n";
                              $this->salida .= "			<td align=\"center\" width=\"15%\" >BODEGA</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"45%\" >PRODUCTO</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"3%\" >CANT</td>\n";
                              $this->salida .= "			<td align=\"center\" width=\"20%\" colspan='5'>Acción Devolución</td>\n";
                              $this->salida .= "		</tr>\n";

                         $cont=0;

                         for($i=0;$i<sizeof($medic);$i++)
                         {
                              if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $valor_real_cantidad=floor($medic[$i][suma1]);
                              
                              if($valor_real_cantidad > 0)
                              {
                                   $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                                   
                                   $Accion_devo = $this->BusquedaDevoluciones_Pendientes($estacion,$medic[$i][bodega],$datos_pac,$medic[$i][codigo_producto]);

                                   $valor_real_cantidad = $valor_real_cantidad - $Accion_devo[cantidad];
                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td $estilo width=\"15%\">$nom_bodega</td>\n";
                                   $this->salida .= "<td $estilo width=\"10%\">".$medic[$i][codigo_producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"45%\">".$medic[$i][descripcion]."</td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"3%\">".$valor_real_cantidad."</td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"3%\"><input class='input-submit' size='5' maxlength='5' type=text name=opt[] value=''></td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"20%\" colspan='4'><img src=\"".GetThemePath()."/images/infor.png\" border='0' title=\"".$Accion_devo[observacion]."\">&nbsp;&nbsp;<label class='label_mark'>&nbsp;Devolver (<b>-</b> de) o &nbsp;".$valor_real_cantidad."</label></td>\n";
                                   $this->salida.=" </tr>";
                              }
                              else
                              {
                                   $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                                   $cont=$cont+1;
                              }
                         }
                              

                         if(sizeof($medic) ==$cont )
                         {
                              $this->salida .= "<td $estilo width=\"20%\" colspan='9' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
                              $sw=1;
                         }
                         if($sw !=1)
                         {
                              $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='9'>";
                              $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\" value=\"CONFIRMAR\">";
                              $this->salida.=" </td>";
                              $this->salida .= "</tr>";
                         }unset($sw);
                         $this->salida.="</table><br>";

                         $this->salida .= "</td></tr>\n";
                         $this->salida.="</table></form><br>";
	               }
                    if($contador !=4)
                    {$contador=1;}
			}
		}

          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY MEDICAMENTOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";

          }
          else
          {
               $href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"datos_pac"=>$datos_pac));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
          }

          $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_pac));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a Medicamentos</a><br>";

          $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
          $this->salida .= themeCerrarTabla();
          unset($ItemBusqueda);
          return true;
	}//fin FrmDevolucionMedicamentos()
	//funcion de medicamentos estacion enfermeria



	//funcion del medicamentos estacion enfermeria
	/**
	*		FrmDevolucionInsumos
	*
	*		Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
	*		Medicamentos que pueden ser devueltos => Alex me dió esta formula:
	*		a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*		ya sea que estén en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*		(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
	*		es mayor a 0)
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function FrmDevolucionInsumos($estacion,$bodega,$datos_pac)
	{
		if(!$estacion){
			$estacion = $_REQUEST['datos_estacion'];
			$bodega= $_REQUEST['bodega'];
			$datos_pac= $_REQUEST['datos_pac'];
		}

		$this->salida .= ThemeAbrirTabla("LISTADO DE INSUMOS PARA DEVOLUCION");
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datos_pac['NombrePaciente']."</td>\n";
		$this->salida .= "			<td>".$datos_pac[pieza]."</td>\n";
		$this->salida .= "			<td>".$datos_pac[cama]."</td>\n";
		$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
		$this->salida.="</tr></table><br>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
          //variable de session q contiene las bodegas de las estaciones
          if(empty($_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']))
          {$_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']=$bodega;}
          else{ $bodega=$_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'];}
		for($s=0; $s<sizeof($bodega);$s++)
		{
               $nom_bodega=$this->TraerNombreBodega($estacion,$bodega[$s][bodega]);
               
               $B=$this->GetPacientesPendientesXHospitalizar_Con_medicamentos($estacion,$datos_pac);

               if($B=="ShowMensaje"){unset($B);}
               $B = $this->GetPacientesConMedicamentosPorSolicitar($estacion,$datos_pac,$B);
               $B = $this->GetPaciente_Consulta_Urgencia_con_med($estacion,$datos_pac,$B);
							
               for($l=0;$l<sizeof($B);$l++)
               {
                    $sumatoria=0;
                    if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    //consulta de medicamentos despachados para devolucion
                    $medic=$this->GetDevolucionMedicamentos($B[$l]['ingreso'],$bodega[$s][bodega],'I');

                    if(!empty($medic))
                    {
                         $contador=4;
                         //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
                         $_SESSION['ESTACION']['VECTOR_DEV_INS'][$B[$l][ingreso]][$bodega[$s][bodega]]=$medic;
                         $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDevIns',array("ingreso"=>$B[$l][ingreso],"plan"=>$B[$l][plan_id],"cuenta"=>$B[$l][numerodecuenta],"estacion"=>$estacion,"bodega"=>$bodega[$s][bodega],"datos_pac"=>$datos_pac));
                         $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

                         $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                         $this->salida .= "	<tr class='modulo_table_title'>\n";
                         /*
                         $this->salida .= "		<td colspan='3'>HABITACION</td>\n";
                         $this->salida .= "		<td>CAMA</td>\n";
                         $this->salida .= "		<td>TIEMPO HOSPITALIZACION</td>\n";
                         $this->salida .= "		<td>PACIENTE</td>\n";

                         */
                         $this->salida .= "		<td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
                         $this->salida .= "	</tr>\n";

                         $this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                         $this->salida .= "		<tr  class='modulo_table_title'>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\" >BODEGA</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"30%\" >PRODUCTO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\" colspan='5'>Cantidad a delvolver</td>\n";
                         $this->salida .= "		</tr>\n";

                         $cont=0;
                         for($i=0;$i<sizeof($medic);$i++)
                         {
                              if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              
                              //Surgio por la modificacion de query de la func. GetDevolucionMedicamentos.                              
                              $sumatoria=0;
                              $dato_devol=$this->GetMedicamentosDevueltos($B[$l]['ingreso'],$estacion,$medic[$i][bodega],$medic[$i][codigo_producto]);
                              for($m=0;$m<sizeof($dato_devol);$m++)
                              {
                                   if($medic[$i][codigo_producto]==$dato_devol[$m][codigo_producto])
                                   $sumatoria+=floor($dato_devol[$m][cantidad]);
                              }
                              $valor_real_cantidad=floor($medic[$i][suma1]) - $sumatoria;
          				
                              //$valor_real_cantidad=floor($medic[$i][suma1]);// - $medic[$i][suma2]);
                              if($valor_real_cantidad > 0)
                              {
                                    $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                                   $this->salida .= "<tr $estilo>\n";
                                   $this->salida .= "<td $estilo width=\"20%\">$nom_bodega</td>\n";
                                   $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                                   $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][descripcion]."</td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".$valor_real_cantidad."</td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"5%\"><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value=''></td>\n";
                                   $this->salida .= "<td $estilo align=\"center\" width=\"5%\" colspan='4'><label class='label_mark'>&nbsp;Menor o igual a &nbsp;".$valor_real_cantidad."</label></td>\n";
                                   $this->salida.=" </tr>";
                              }
                              else
                              {
                                   $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                                   $cont=$cont+1;
                              }
                         }
          
                         if(sizeof($medic) ==$cont )
                         {
                                        $this->salida .= "<td $estilo width=\"20%\" colspan='9' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
                                        $sw=1;
                         }
                         if($sw !=1)
                         {
                              $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='9'>";
                              $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\">";
                              $this->salida.=" </td>";
                              $this->salida .= "</tr>";
                         }unset($sw);
                         $this->salida.="</table><br>";

                         $this->salida .= "</td></tr>\n";
                         $this->salida.="</table></form><br>";

                    }
                    if($contador !=4)
                    {$contador=1;}
               }

          }

          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY MEDICAMENTOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";

          }
          else
          {
               $href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"datos_pac"=>$datos_pac));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
          }

          $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_pac));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a Medicamentos</a><br>";

          $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

          $this->salida .= themeCerrarTabla();
          unset($ItemBusqueda);
          return true;
     }//fin FrmDevolucionMedicamentos()
	//funcion del medicamentos estacion enfermeria


	//funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDevMed()
     {
          $bodega=$_REQUEST['bodega'];
          $arreglo_bodega_estacion=$_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];
          $estacion=$_REQUEST['estacion'];
          $datos_pac=$_REQUEST['datos_pac'];
          $op=$_REQUEST['opt'];
          $plan=$_REQUEST['plan'];
          $cuenta=$_REQUEST['cuenta'];
          $medic=$_SESSION['ESTACION']['VECTOR_DEV'][$_REQUEST['ingreso']][$bodega];

		unset($contador);
		for($h=0;$h<sizeof($op);$h++)
		{
			if(empty($op[$h]) or $op[$h]==0)
			{
				$contador=$contador + 1;
			}
		}
		
          if($contador ==sizeof($op))
		{$sw_spy=1;}

          if(!empty($medic) and $sw_spy !=1)
          {
               $this->salida .= ThemeAbrirTabla('CONFIRMACION DEVOLUCION DE MEDICAMENTOS');
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";
               $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr  class='modulo_table_title'>\n";
               $this->salida .= "			<td align=\"center\" width=\"6%\" >BODEGA</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"6%\" >CODIGO</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"35%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"20%\" colspan='5'>Cantidad a delvolver</td>\n";
               $this->salida .= "		</tr>\n";

               unset($ERROR_HADLING);
               $href1 = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertDevolucionMedicamento',array("ingreso"=>$_REQUEST['ingreso'],"estacion"=>$estacion,"bodega"=>$bodega,"datos_pac"=>$datos_pac,"medic"=>$medic,
               'opt'=>$op,'plan'=>$plan,'cuenta'=>$cuenta,'sw_spy'=>$sw_spy));
               $this->salida .="<form name=forma action=".$href1." method=post>";
               
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";

                    $valor_real_cantidad=floor($medic[$i][suma1]);

                    if($valor_real_cantidad > 0)
                    {
                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\">$nom_bodega</td>\n";
                         $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][descripcion]."</td>\n";
                         
                         $Accion_devo = $this->BusquedaDevoluciones_Pendientes($estacion,$medic[$i][bodega],$datos_pac,$medic[$i][codigo_producto]);
                         $valor_real_cantidad = $valor_real_cantidad - $Accion_devo[cantidad];

                         $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".$valor_real_cantidad."</td>\n";
                         
                         if($valor_real_cantidad < $op[$i])
                         {
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\"><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$i]."' READONLY></td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\" colspan='4'><label class='label_error'>&nbsp;Excede la cantidad</label></td>\n";
                              $ERROR_HADLING=1;
                         }
                         else
                         {
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\"><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$i]."' READONLY></td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\" colspan='4'><label class='label_mark'>&nbsp;Menor o igual a &nbsp;".$valor_real_cantidad."</label></td>\n";
                         }

                         $this->salida.=" </tr>";
                    }
                    else
                    {
                         $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                    }
               }
 			//NUEVO, TEXTO DE JUSTIFICACION
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "<td>MOTIVO DEVOLUCION</td>\n";              
               $this->salida .= "<td colspan=\"8\" align=\"left\" width=\"40%\"><select name=\"parametro\" class=\"select\">";
               $this->salida .= "<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";
               $vector_tipo=$this->Get_ParametrosDevolucion();
               $this->GetHtmlParametrosDevolucion($vector_tipo,$_REQUEST['parametro']);
               $this->salida .= "</select></td>";
               $this->salida .= "</tr>\n";
               
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "<td align=\"center\">JUSTIFICACION DEVOLUCION</td>\n";              
               $this->salida .= "<td colspan=\"8\" align=\"left\" width=\"90%\"><textarea cols=\"20\" rows=\"3\" style=\"width:100%\" class=\"textarea\" name=\"justificacion_devo\"></textarea></td>\n";              
               $this->salida .= "</tr>\n";
               //NUEVO, TEXTO DE JUSTIFICACION
	          $this->salida.="</table><br>";

	          if($ERROR_HADLING !=1)
               {
                    $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
                    $this->salida.=" <tr>";
                    $this->salida.=" <td align=\"center\">";
                    $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
                    $this->salida.=" </td>";
               }
               else
               {
                    $this->salida.="<br><table border=\"0\" align=\"center\" width=\"15%\">";
                    $this->salida.=" <tr>";
                    $this->salida.=" </form>";
               }

               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$estacion,"bodega"=>$arreglo_bodega_estacion,"datos_pac"=>$datos_pac));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO DIGITO EN NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$estacion,"bodega"=>$arreglo_bodega_estacion,"datos_pac"=>$datos_pac));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
	}


     function GetHtmlParametrosDevolucion($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[parametro_devolucion_id]==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\" selected>$titulo[descripcion]</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\">$titulo[descripcion]</option>";
               }
          }
     }
     

	//funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDevIns()
     {
          $bodega=$_REQUEST['bodega'];
          $arreglo_bodega_estacion=$_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'];
          $estacion=$_REQUEST['estacion'];
          $datos_pac=$_REQUEST['datos_pac'];
          $op=$_REQUEST['opt'];
          $plan=$_REQUEST['plan'];
          $cuenta=$_REQUEST['cuenta'];
          $medic=$_SESSION['ESTACION']['VECTOR_DEV_INS'][$_REQUEST['ingreso']][$bodega];
          
          unset($contador);
          
		for($h=0;$h<sizeof($op);$h++)
		{
			if(empty($op[$h]) or $op[$h]==0)
			{
				$contador=$contador + 1;
			}
		}

		if($contador ==sizeof($op))
		{$sw_spy=1;}

          if(!empty($medic) and $sw_spy !=1)
          {
               $this->salida .= ThemeAbrirTabla('CONFIRMACION DEVOLUCION DE INSUMOS');
               $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr  class='modulo_table_title'>\n";
               $this->salida .= "			<td align=\"center\" width=\"5%\" >BODEGA</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"30%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td align=\"center\" width=\"5%\" colspan='5'>Cantidad a delvolver</td>\n";
               $this->salida .= "		</tr>\n";

               unset($ERROR_HADLING);
               $href1 = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertDevolucionMedicamento',array("ingreso"=>$_REQUEST['ingreso'],"estacion"=>$estacion,"bodega"=>$bodega,"datos_pac"=>$datos_pac,"medic"=>$medic,'accion'=>'1'));
               $this->salida .="<form name=forma action=".$href1." method=post>";

               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                                   
                    $sumatoria=0;
                    $dato_devol=$this->GetMedicamentosDevueltos($_REQUEST['ingreso'],$estacion,$medic[$i][bodega],$medic[$i][codigo_producto]);
                    for($m=0;$m<sizeof($dato_devol);$m++)
                    {
                         if($medic[$i][codigo_producto]==$dato_devol[$m][codigo_producto])
                         $sumatoria+=floor($dato_devol[$m][cantidad]);
                    }
                    $valor_real_cantidad=floor($medic[$i][suma1]) - $sumatoria;
                    //$valor_real_cantidad=floor($medic[$i][suma1] - $medic[$i][suma2]);

                    if($valor_real_cantidad > 0)
                    {
                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\">$nom_bodega</td>\n";
                         $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][descripcion]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".$valor_real_cantidad."</td>\n";

                         if($valor_real_cantidad < $op[$i])
                         {
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\"><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$i]."' READONLY></td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\" colspan='4'><label class='label_error'>&nbsp;Excede la cantidad</label></td>\n";
                              $ERROR_HADLING=1;
                         }
                         else
                         {
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\"><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$i]."' READONLY></td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"5%\" colspan='4'><label class='label_mark'>&nbsp;Menor o igual a &nbsp;".$valor_real_cantidad."</label></td>\n";
                         }

                         $this->salida.=" </tr>";
                    }
                    else
                    {
                         $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                    }
               }
               $this->salida.="</table><br>";

			if($ERROR_HADLING !=1)
			{
				$this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
				$this->salida.=" <tr>";
				$this->salida.=" <td align=\"center\">";
				$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
				$this->salida.=" </td>";
			}
			else
			{
				$this->salida.="<br><table border=\"0\" align=\"center\" width=\"15%\">";
				$this->salida.=" <tr>";
				$this->salida.=" </form>";
			}

			$href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$estacion,"bodega"=>$arreglo_bodega_estacion,"datos_pac"=>$datos_pac));
			$this->salida .="<form name=forma action=".$href." method=post>";
			$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
			$this->salida.=" </tr>";
			$this->salida.=" </table>";
			$this->salida .= ThemeCerrarTabla();
		}
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO DIGITO EN NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$estacion,"bodega"=>$arreglo_bodega_estacion,"datos_pac"=>$datos_pac));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
		return true;
	}	

	
     /*
     *
     *
     *		@Author Tizziano Perea O.
     *		@access Private
     *		@return bool
     *		Proposito: Unificar la solicitud de medicamentos e insumos.
     */

     function InsumosMed_X_Despachar($estacion,$bodega,$SWITCHE)
     {
          if(empty($estacion))
          {
               $estacion=$_REQUEST['estacion'];
               $bodega=$_REQUEST['bodega'];
               $SWITCHE=$_REQUEST['switche'];
          }
          
          $datoscenso=$this->GetPacientesPendientesDesp($estacion['estacion_id']);
     

          if($datoscenso=="ShowMensaje"){unset($datoscenso);}
          $datoscenso = $this->GetPaciente_Consulta_Urgencia($estacion['estacion_id'],$datoscenso);

          $datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
               
          $nom_bodega=$this->TraerNombreBodega($estacion,$bodega);
     
          $this->salida .= ThemeAbrirTabla("LISTADO DE DESPACHO DE INSUMOS Y MEDICAMENTOS DESDE LA BODEGA  &nbsp;".strtoupper($nom_bodega)."");
          foreach($datoscenso as $key => $value)
          {
               if($key == "hospitalizacion")
               {
     
                    foreach($value as $A => $B)
                    {
                         for($tpo=0; $tpo<2; $tpo++)
                         {
                              if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
          
                              if($tpo==0)
                              {
                                   //consulta de medicamentos solicitados
                                   $medic=$this->GetInsumosPendDesp($B['ingreso'],$estacion,$bodega);
                              }elseif($tpo==1)
                              {
                                   //consulta de medicamentos solicitados
                                   $medic=$this->GetMedicamentosPendDesp($B['ingreso'],$estacion,$bodega);
                              }
          
                              if(!empty($medic))
                              {
                                   $contador=4;
                                   if($tpo==0)
                                   {
                                        $_SESSION['ESTACION']['VECTOR_DESP_INS'][$B[ingreso]]=$medic;
                                        $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDespSolicitudIns',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                                        $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                                   }elseif($tpo==1)
                                   {
                                        $_SESSION['ESTACION']['VECTOR_DESP'][$B[ingreso]]=$medic;
                                        $f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDespSolicitudMed',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                                        $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                                   }
          
                                   $this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                                   if($tpo==0)
                                   {
                                        $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                        $this->salida .= "	<td colspan=\"4\">DESPACHO DE INSUMOS</td>\n";
                                        $this->salida .= "	</tr>\n";
                                   }elseif($tpo==1)
                                   {
                                        $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                        $this->salida .= "	<td colspan=\"4\">DESPACHO DE MEDICAMENTOS</td>\n";
                                        $this->salida .= "	</tr>\n";
							}
                     
                                   $this->salida .= "	<tr class='modulo_table_title'>\n";
                                   $this->salida .= "		<td>HABITACION</td>\n";
                                   $this->salida .= "		<td>CAMA</td>\n";
                                   $this->salida .= "		<td>TIEMPO HOSPITALIZACION</td>\n";
                                   $this->salida .= "		<td>PACIENTE</td>\n";
                                   $this->salida .= "	</tr>\n";
          
                                   $this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
                                   if(empty($B[pieza]))
                                   {
                                        $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                                        $this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
                              
                                   }
                                   else
                                   {
                                        $this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
                                        $this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
                                   }
                                   $diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
                                   $this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
                                   $linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
                                   $this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
                                   $this->salida .= "	</tr>\n";
          
          
          
                                   $this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
                                   $this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
          
                                   $this->salida .= "		<tr  class='modulo_table_title'>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
                                   $this->salida .= "			<td align=\"center\" width=\"5%\" >&nbsp;</td>\n";
                                   $this->salida .= "		</tr>\n";
          
                                   for($i=0;$i<sizeof($medic);$i++)
                                   {
                                        if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                                        //if($medic[$i][solicitud_id]!=$solicitud)
          
                                        if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                                        {
                                             $this->salida .= "<tr $estilo>\n";
                                             $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                                             $solicitud=$medic[$i][solicitud_id];
                                             $this->salida .= "<td colspan =7 width=\"70%\">";
                                             $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                                        }
          
          
                                        $this->salida .= "<tr $estilo>\n";
                                        $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
                                        $this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
                                        $this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cantidad])."</td>\n";
                                        
                                        if($tpo==1)
                                        {          
                                        	$despacho=$this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
								}elseif($tpo==0)
                                        {
                                        	$despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);
                                        }
          
                                        if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
                                        {
                                             $this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
                                        }
                                        else
                                        {
                                             $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
                                             $this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
                                        }
                                        $cant_desp=floor($despacho[0][cantidad]);
                                        if($cant_desp <=0){$cant_desp='';}
                                        $this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";
          
                                        if($medic[$i][sw]==5)//este estado es que se despacho incompleta.
                                        {
                                             if($despacho[0][cantidad]>0)
                                             {
                                                  $this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" width='17' height='17' border='0'></td>";
                                             }
                                             else
                                             {
                                                       $this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
                                             }
                                        }
                                        elseif($medic[$i][sw]==1)
                                        {
                                             if($despacho[0][cantidad]>0)
                                             {
                                                  $this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
                                             }
                                             else
                                             {
                                                  $this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
                                             }
                                        }
          
                                        $this->salida.=" </tr>";
                                        if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                                        {
                                             $this->salida .= "</table>";
                                             $this->salida .= "</td>";
                                             $this->salida .= "</tr>";
                                        }
          
                                   }
                                   
                                   $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='8'>";
                                   $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\">";
                                   $this->salida.=" </td>";
                                   $this->salida .= "</tr>";
                                   $this->salida.="</table>";
          
                                   $this->salida .= "</td></tr>\n";
                                   $this->salida.="</table></form>";
                              }
                              if($contador !=4)
                              {$contador=1;}
                         }//fin for
                    }//fin foreach
     
                    if($contador==1)
                    {
                         $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
                         $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES PENDIENTES POR DEPACHAR</label>";
                         $this->salida .= "</td></tr>";
                         $this->salida.="</table><br>";
     
                    }
                    else{
                         $href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosMed_X_Despachar',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
                         $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
                    }
                    
                    $href = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,'switche'=>$SWITCHE));
                    $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a seleccion de Bodega </a><br>";
     
     
                    $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
                    $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
     
                    $this->salida .= themeCerrarTabla();
                    unset($ItemBusqueda);
                    return true;
               }
          }
     }
     
     
     
     
	/*
		*
		*
		*		@Author jaja
		*		@access Private
		*		@return bool
		*/
/*		function InsumosXDespachar($estacion,$bodega,$SWITCHE)
		{

		  if(empty($estacion))
			{
				$estacion=$_REQUEST['estacion'];
				$bodega=$_REQUEST['bodega'];
				$SWITCHE=$_REQUEST['switche'];
			}

			$datoscenso=$this->GetPacientesPendientesDesp($estacion['estacion_id']);

			if($datoscenso=="ShowMensaje"){unset($datoscenso);}
			$datoscenso = $this->GetPaciente_Consulta_Urgencia($estacion['estacion_id'],$datoscenso);

			$datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
		
			$nom_bodega=$this->TraerNombreBodega($estacion,$bodega);

				$this->salida .= ThemeAbrirTabla("LISTADO DE DESPACHO DE INSUMOS DE LA BODEGA  &nbsp;".strtoupper($nom_bodega)."");
				foreach($datoscenso as $key => $value)
				{
					if($key == "hospitalizacion")
					{

						foreach($value as $A => $B)
						{

									if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

								//consulta de medicamentos solicitados
									$medic=$this->GetInsumosPendDesp($B['ingreso'],$estacion,$bodega);

								if(!empty($medic))
								{
									$contador=4;
									$_SESSION['ESTACION']['VECTOR_DESP_INS'][$B[ingreso]]=$medic;

									$f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDespSolicitudIns',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
									$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

									$this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
									$this->salida .= "	<tr class='modulo_table_title'>\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>TIEMPO HOSPITALIZACION</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "	</tr>\n";

									$this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
									if(empty($B[pieza]))
									{
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
								
									}
									else
									{
										$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
										$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
									}
									$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
									$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
									$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
									$this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
									$this->salida .= "	</tr>\n";



									$this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
									$this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

									$this->salida .= "		<tr  class='modulo_table_title'>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >&nbsp;</td>\n";
									$this->salida .= "		</tr>\n";


							for($i=0;$i<sizeof($medic);$i++)
							{
										if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
										//if($medic[$i][solicitud_id]!=$solicitud)


										if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
										{
											$this->salida .= "<tr $estilo>\n";
											$this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
											$solicitud=$medic[$i][solicitud_id];
											$this->salida .= "<td colspan =7 width=\"70%\">";
											$this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
										}


										$this->salida .= "<tr $estilo>\n";
										$this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
										$this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
										$this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cantidad])."</td>\n";
										$despacho=$this->GetDatosDespachoIns($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);

										if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
										{
											$this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
										}
          					else
										{
											$this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
											$this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
										}
										$cant_desp=floor($despacho[0][cantidad]);
										if($cant_desp <=0){$cant_desp='';}
										$this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";

										if($medic[$i][sw]==5)//este estado es que se despacho incompleta.
										{
											if($despacho[0][cantidad]>0)
											{
												$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" width='17' height='17' border='0'></td>";
											}
											else
											{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
											}
										}
										elseif($medic[$i][sw]==1)
										{
												if($despacho[0][cantidad]>0)
           							{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
												}
												else
												{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
            						}
										}

										$this->salida.=" </tr>";
										if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
										{

											$this->salida .= "</table>";
          						$this->salida .= "</td>";
										  $this->salida .= "</tr>";
										}

						}
							$this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='8'>";
							$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\">";
							$this->salida.=" </td>";
							$this->salida .= "</tr>";
							$this->salida.="</table><br>";

							$this->salida .= "</td></tr>\n";
							$this->salida.="</table></form><br>";

					}
					if($contador !=4)
					{$contador=1;}
				}//fin foreach

				if($contador==1)
				{
				  $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
					$this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES PENDIENTES POR DEPACHAR</label>";
					$this->salida .= "</td></tr>";
					$this->salida.="</table><br>";

				}
				else{
							$href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallInsumosXDespachar',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
							$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
						}


				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,'switche'=>$SWITCHE));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a seleccion de Bodega </a><br>";


				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

				$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);
				return true;
			}
		}
}*/





	/*
		*
		*
		*		@Author jaja
		*		@access Private
		*		@return bool
		*/
/*		function MedicamentosXDespachar($estacion,$bodega,$SWITCHE)
		{
               if(empty($estacion))
			{
				$estacion=$_REQUEST['estacion'];
				$bodega=$_REQUEST['bodega'];
				$SWITCHE=$_REQUEST['switche'];
			}

			$datoscenso=$this->GetPacientesPendientesDesp($estacion['estacion_id']);

			if($datoscenso=="ShowMensaje"){unset($datoscenso);}
			$datoscenso = $this->GetPaciente_Consulta_Urgencia($estacion['estacion_id'],$datoscenso);
			
			$datoscenso= $this->GetPacientesPendientesXHospitalizar_Plantilla($estacion,1,$datoscenso);
		
			
			$nom_bodega=$this->TraerNombreBodega($estacion,$bodega);

				$this->salida .= ThemeAbrirTabla("LISTADO DE DESPACHO DE MEDICAMENTOS DE LA BODEGA  &nbsp;".strtoupper($nom_bodega)."");
				foreach($datoscenso as $key => $value)
				{
					if($key == "hospitalizacion")
					{

						foreach($value as $A => $B)
						{

									if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

								//consulta de medicamentos solicitados
									$medic=$this->GetMedicamentosPendDesp($B['ingreso'],$estacion,$bodega);

								if(!empty($medic))
								{
									$contador=4;
									$_SESSION['ESTACION']['VECTOR_DESP'][$B[ingreso]]=$medic;

									$f = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarDespSolicitudMed',array("ingreso"=>$B[ingreso],"plan"=>$B[plan_id],"cuenta"=>$B[numerodecuenta],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
									$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

									$this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
									$this->salida .= "	<tr class='modulo_table_title'>\n";
									$this->salida .= "		<td>HABITACION</td>\n";
									$this->salida .= "		<td>CAMA</td>\n";
									$this->salida .= "		<td>TIEMPO HOSPITALIZACION</td>\n";
									$this->salida .= "		<td>PACIENTE</td>\n";
									$this->salida .= "	</tr>\n";

									$this->salida .= "	<tr class=hc_table_submodulo_list_title>\n";
									if(empty($B[pieza]))
									{
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
										$this->salida .= "	<td align=\"center\">No Ingresado</td>\n";
								
									}
									else
									{
										$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
										$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
									}
									$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
									$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";
									$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","datos_estacion"=>$estacion,"modulito"=>'EstacionE_ControlPacientes'));
									$this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
									$this->salida .= "	</tr>\n";



									$this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
									$this->salida .= "	<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

									$this->salida .= "		<tr  class='modulo_table_title'>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >SOLICITUD</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >CODIGO</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO</td>\n";
                                             $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\"  >CODIGO DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"20%\" >PRODUCTO DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >CANT DESP</td>\n";
									$this->salida .= "			<td align=\"center\" width=\"5%\" >&nbsp;</td>\n";
									$this->salida .= "		</tr>\n";


							for($i=0;$i<sizeof($medic);$i++)
							{
										if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
										//if($medic[$i][solicitud_id]!=$solicitud)


										if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
										{
											$this->salida .= "<tr $estilo>\n";
											$this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
											$solicitud=$medic[$i][solicitud_id];
											$this->salida .= "<td colspan =7 width=\"70%\">";
											$this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
										}


										$this->salida .= "<tr $estilo>\n";
										$this->salida .= "<td $estilo width=\"20%\">".$medic[$i][codigo_producto]."</td>\n";
										$this->salida .= "<td $estilo width=\"20%\">".$medic[$i][producto]."</td>\n";
										$this->salida .= "<td $estilo align=\"center\" width=\"5%\">".floor($medic[$i][cant_solicitada])."</td>\n";
										$despacho=$this->GetDatosDespacho($medic[$i][doc],$medic[$i][consecutivo_d],$medic[$i][solicitud_id]);

										if(empty($despacho[0][codigo_producto]) AND empty($despacho[0][descripcion]))
										{
											$this->salida .= "<td $estilo colspan='2' width=\"20%\"><label class='label_mark'>No Despachado</label></td>\n";
										}
          					else
										{
											$this->salida .= "<td $estilo width=\"20%\">".$despacho[0][codigo_producto]."</td>\n";
											$this->salida .= "<td $estilo width=\"20%\">".$despacho[0][descripcion]."</td>\n";
										}
										$cant_desp=floor($despacho[0][cantidad]);
										if($cant_desp <=0){$cant_desp='';}
										$this->salida .= "<td $estilo width=\"5%\">$cant_desp</td>\n";

										if($medic[$i][sw]==5)//este estado es que se despacho incompleta.
										{
											if($despacho[0][cantidad]>0)
											{
												$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" width='17' height='17' border='0'></td>";
											}
											else
											{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
											}
										}
										elseif($medic[$i][sw]==1)
										{
												if($despacho[0][cantidad]>0)
           							{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$i][solicitud_id].",".$medic[$i][consecutivo_d]."></td>";
												}
												else
												{
													$this->salida.="  <td  $estilo width=\"2%\" align=\"center\"><label class='label_mark'>--</label></td>";
            						}
										}

										$this->salida.=" </tr>";
										if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
										{

											$this->salida .= "</table>";
          						$this->salida .= "</td>";
										  $this->salida .= "</tr>";
										}

						}
							$this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='8'>";
							$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\">";
							$this->salida.=" </td>";
							$this->salida .= "</tr>";
							$this->salida.="</table><br>";

							$this->salida .= "</td></tr>\n";
							$this->salida.="</table></form><br>";

					}
					if($contador !=4)
					{$contador=1;}
				}//fin foreach

				if($contador==1)
				{
				  $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
					$this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY SOLICITUDES PENDIENTES POR DEPACHAR</label>";
					$this->salida .= "</td></tr>";
					$this->salida.="</table><br>";

				}
				else{
							$href2 = ModuloGetURL('app','EstacionE_Medicamentos','user','CallMedicamentosXDespachar',array("estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
							$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";
						}


				$href = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,'switche'=>$SWITCHE));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver a seleccion de Bodega </a><br>";


				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

				$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);
				return true;
			}
		}
}*/

     
     //funcion que sirve para confirmar los suministros del paciente.
     function ConfirmarSuministros()
     {
     	$this->salida= ThemeAbrirTabla('CONFIRMACION DE SUMINISTRO DEL MEDICAMENTO');
          $estacion=$_REQUEST['estacion'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          $tipo_solicitud=$_REQUEST['tipo_solicitud'];
          $checo=$_REQUEST['checo'];
          
          if($_REQUEST['cantidad_suministrada'.$pfj] == '' ){
               $this->frmError["cantidad_suministrada"]=1;
               $this->frmError["MensajeError"]="LA CANTIDAD NO PUEDE SER CERO.";
               $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
               return true;
		}

          if($_REQUEST['cantidad_suministrada'.$pfj] != ''){
			if (is_numeric($_REQUEST['cantidad_suministrada'.$pfj])==0){
				$this->frmError["cantidad_suministrada"]=1;
				$this->frmError["MensajeError"]="CANTIDAD INVALIDA, DIGITE SOLO NUMEROS.";
                    $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
                    return true;
			}
		}
          
          if($_REQUEST['totalitario'.$pfj]==$_REQUEST['cantidad_recetada'])
          {
          	$Medicamento = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
               $this->Finalizar_Medicamentos($Medicamento,$datos_estacion);
               $this->frmError["MensajeError"]="MEDICAMENTO FINALIZADO.";
               $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
               return true;

          }
          
          if(($_REQUEST['cantidad_suministrada'.$pfj] > $_REQUEST['cantidad_recetada']) 
             OR ($_REQUEST['cantidad_suministrada'.$pfj] > $_REQUEST['dosis'])){
			$this->frmError["cantidad_suministrada"]=1;
			$this->frmError["MensajeError"]="LA CANTIDAD SUMINISTRADA NO PUEDE SER MAYOR A LA CANTIDAD DE DOSIS FORMULADA O A LA CANTIDAD RECETADA.";
               $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
               return true;
		}
          
          $totalitario = $_REQUEST['cantidad_suministrada'.$pfj] + $_REQUEST['totalitario'.$pfj];
          if($totalitario > $_REQUEST['cantidad_recetada']){
               $this->frmError["cantidad_suministrada"]=1;
               $this->frmError["MensajeError"]="LA SUMATORIA DE LAS DOSIS SUMINISTRADAS EXCEDE A LA CANTIDAD TOTAL RECETADA POR EL PROFESIONAL.<BR>FIN DEL SUMINISTRO.";
               $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
               return true;
		}
          $bodeguita = explode(",",$_REQUEST['bodega']);
          $valor_bodega = explode(".",$bodeguita[0]);
          $valor_total_bodega = $valor_bodega[0].$valor_bodega[1];
          
          if($bodeguita[1] != '*/*'){
          	if ($_REQUEST['cantidad_suministrada'.$pfj] > $valor_total_bodega)
               {
                    $this->frmError["cantidad_suministrada"]=1;
                    $this->frmError["MensajeError"]="LA BODEGA DE LA ESTACION.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
                    $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
                    return true;
               }
          }
          elseif($bodeguita[1] == '*/*')
          {
               if($_REQUEST['cantidad_suministrada'.$pfj] > $_REQUEST['sumatoria']){
                    $this->frmError["cantidad_suministrada"]=1;
                    $this->frmError["MensajeError"]="LA BODEGA DEL PACIENTE.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
                    $this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
                    return true;
               }
          }

          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']);
          
          $vect=$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];//arreglo q contiene los productos seleccionados.
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']=$cantidad_suministrada=$_REQUEST['cantidad_suministrada'];
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora']=$fecha=$_REQUEST['selectHora'];
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos']=$minutos=$_REQUEST['selectMinutos'];
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']=$observacion=$_REQUEST['observacion_suministro'];
          
          $bodega=explode(",",$_REQUEST['bodega']);
               
          if($bodega[1]=="*/*")
          {
                    $descripcion="BODEGA PACIENTE";
          }
          else
          {
                    $descripcion=$this->TraerNombreBodega($estacion,$bodega[1]);
          }
          
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>HABITACION</td>\n";
          $this->salida .= "			<td>CAMA</td>\n";
          $this->salida .= "			<td>PISO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
          $this->salida.="</tr></table><br><br>";
               
          $accion = ModuloGetURL('app','EstacionE_Medicamentos','user','InsertarSuministroPaciente',array("tipo_solicitud"=>$tipo_solicitud,"bodega"=>$bodega[1],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
          $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"left\" colspan=\"7\">CONTROL DEL MEDICAMENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td align=\"center\" width=\"7%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"29%\">PRINCIPIO ACTIVO</td>";
          $this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">CANTIDAD SUMINISTRADA</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class='modulo_list_claro'>";
          $this->salida.="  <td align=\"center\" width=\"7%\">".$vect[codigo_producto]."</td>";
          $this->salida.="  <td align=\"center\" width=\"30%\">".$vect[producto]."</td>";
          $this->salida.="  <td align=\"center\" width=\"29%\">".$vect[principio_activo]."</td>";
          $this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">$cantidad_suministrada</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class='modulo_list_oscuro'>";
          $this->salida.="  <td colspan='2' align=\"center\" width=\"7%\">FECHA SUMINISTRO :</td>";
          $this->salida.="  <td colspan='5' align=\"left\" width=\"30%\">$fecha.$minutos</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class='modulo_list_claro'>";
          $this->salida.="  <td colspan='2' align=\"center\" width=\"7%\">BODEGA :</td>";
          $this->salida.="  <td colspan='5' align=\"left\" width=\"30%\">$descripcion</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class='modulo_list_oscuro'>";
          $this->salida.="  <td colspan='2' align=\"center\" width=\"7%\">OBSERVACION :</td>";
          $this->salida.="  <td colspan='5' align=\"left\" width=\"30%\">$observacion</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          
          if($bodega[1]!="*/*")
          {
               //vector del checkbox deonde selecciono los insumos.
               unset($cadena);
               unset($vector_final);
               $cont=0;
               if(is_array($checo))
               {
                    for($x=0;$x<sizeof($checo);$x++)
                    {
                         if($checo[$x] && $_REQUEST['cant'.$checo[$x]])
                         {
                              if(is_numeric($_REQUEST['cant'.$checo[$x]]))
                              {
                                   $cadena.="'".$checo[$x]."'".",";
                                   $vector_final[$cont]=$checo[$x].",".$_REQUEST['cant'.$checo[$x]];
                                   $cont++;
                              }	
                         }	
                    }
                    $cadena.='0';
               }
               
               //vector q tiene los insumos q seleccionamos para crear una solicitud
               if(is_array($vector_final))
               {
                    $_SESSION['ESTACION_ENF_MED_VECT']['DATA']=$vector_final;
               }
               $arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($vect[codigo_producto],$bodega,$cadena);

               if(is_array($arr_rel) && is_array($checo))
               {
                    //parte de los insumos relacionados con los suministros q se hacen al paciente.
                    $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
                    
                    for($y=0;$y<sizeof($arr_rel);$y++)
                    {
                         $this->salida .= "		<tr rowspan='2' align='center' class='modulo_list_claro'>\n";
                         $this->salida .= "			<td colspan='5' width=\"10%\">\n";
                         
                         $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\"\">\n";
                         if($y==0)
                         {
                              $this->salida .= "		<tr class=\"modulo_list_table_title\">\n";
                              $this->salida .= "			<td colspan='3'>SOLICITUD DE INSUMOS RELACIONADOS CON MEDICAMENTOS</td>\n";
                              $this->salida .= "		</tr>\n";
                         }	
                         
                         $this->salida .= "		<tr align='center' class='modulo_list_claro'>\n";
                         $this->salida .= "			<td width=\"10%\"><label class='label_mark'>".$arr_rel[$y][codigo_producto]."</label></td>\n";
                         $this->salida .= "			<td width=\"25%\"><label class='label_mark'>".$arr_rel[$y][descripcion]."</label></td>\n";
                         $cantidad_REAL=explode(",",$vector_final[$y]);
                         $cantidad = $cantidad_suministrada * $cantidad_REAL[1];
                         $this->salida .= "			<td width=\"10%\">".$cantidad."</td>\n";
                         $this->salida .= "		</tr></table>\n";
                         $this->salida .= "		</td>\n";
                         
                    }
                    $this->salida.="</table><br>";
               }
          }
     
          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
          $this->salida.=" <tr>";
          $this->salida.=" <td align=\"center\">";
          $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Guardar\"></form>";
          $this->salida.=" </td>";
          
          $href = ModuloGetURL('app','EstacionE_Medicamentos','user','Control_Suministro',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
          $this->salida .="<form name=forma action=".$href." method=post>";
          $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
          $this->salida.=" </tr>";
          $this->salida.=" </table>";
                    
          $this->salida .= themeCerrarTabla();
          return true;
     }


/***************************funcion de claudia zuñiga de suministro************/
     function Control_Suministro($estacion,$datos_estacion,$ingreso,$tipo_solicitud)
     {
		//vector de insumos 
		unset($_SESSION['ESTACION_ENF_MED_VECT']['DATA']);
          if(!$estacion)
		{
			$estacion=$_REQUEST['estacion'];
			$datos_estacion=$_REQUEST['datos_estacion'];
			$vect=$_REQUEST['vect'];//arreglo q contiene los productos seleccionados.
			$tipo_solicitud=$_REQUEST['tipo_solicitud'];
		}
		
          if(empty($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']))
		{
			$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']=$vect;
		}
		else
		{
			$vect=$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
		}
		
          $this->salida = ThemeAbrirTabla('CONTROL DE SUMINISTRO DEL MEDICAMENTO');
 
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datos_estacion['NombrePaciente']."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[pieza]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[cama]."</td>\n";
		$this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA']['NOM']."</td>\n";
		$this->salida.="</tr></table><br><br>";

          
		$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','ConfirmarSuministros',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
          $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"left\" colspan=\"7\">CONTROL DEL MEDICAMENTO:</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"center\" width=\"7%\">CODIGO</td>";
		$this->salida.="  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
		$this->salida.="  <td align=\"center\" width=\"29%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">CANTIDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class='modulo_list_claro'>";
		$this->salida.="  <td align=\"center\" width=\"7%\">".$vect[codigo_producto]."</td>";
		$this->salida.="  <td align=\"center\" width=\"30%\">".$vect[producto]."</td>";
		$this->salida.="  <td align=\"center\" width=\"29%\">".$vect[principio_activo]."</td>";
		$this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">".$vect[cantidad]."&nbsp;".$vect[descripcion]."".$vect[contenido_unidad_venta]."</td>";
		$this->salida.="</tr>";
          
          $cantidad_recetada = ceil($vect[cantidad]);
          $this->salida.="<input type='hidden' name='cantidad_recetada' value='".$cantidad_recetada."'>";

          
//***************************************************************          
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" width=\"40%\" colspan=\"3\">DOSIS RECOMENDADA X SUMINSTRO</td>";
		$this->salida.="<td align=\"center\" colspan=\"4\" width=\"40%\">".$vect[dosis]." - ".$vect[unidad_dosificacion]."</td>";
          $dosis = $vect[dosis];
		$this->salida.="</tr>";
//***************************************************************

		$this->salida.="</table><br>";
		
          $control = $this->Consultar_Control_Suministro($vect[codigo_producto],$vect[evolucion],$datos_estacion[ingreso]);
          $totalitario = $this->total_suministro($vect[codigo_producto],$vect[evolucion],$datos_estacion[ingreso]);
          $totalitario = ceil($totalitario);

          if($_REQUEST['bandera'] == '')
          {
          	$bandera = 0;
          }
          else
          {
			$bandera = $_REQUEST['bandera'];
          }
          
          if($bandera == 0)
          {
               if($control)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"left\" colspan=\"4\">DOSIS SUMINISTRADAS:</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"15%\">FECHA SUMINISTRO</td>";
                    $this->salida.="  <td width=\"15%\">HORA SUMINISTRO</td>";
                    $this->salida.="  <td width=\"20%\">CANTIDAD SUMINISTRADA</td>";
                    $this->salida.="  <td width=\"20%\">USUARIO</td>";
                    //$this->salida.="  <td width=\"35%\">OBSERVACION DEL SUMINISTRO</td>";
                    $this->salida.="</tr>";
                    
                    $estilo='modulo_list_claro';
                    $this->salida.="<tr class=\"$estilo\">";
                    //$this->salida.="  <td align=\"center\" width=\"5%\">".$control[$i][hc_control_suministro_id]."</td>";
                    $this->salida.="  <td align=\"center\" width=\"15%\">".$this->FechaStamp($control[0][fecha_realizado])."</td>";
                    $this->salida.="  <td align=\"center\" width=\"15%\">".$this->HoraStamp($control[0][fecha_realizado])."</td>";
                    $this->salida.="  <td align=\"center\" width=\"20%\">".$control[0][cantidad_suministrada]."&nbsp;".$control[0][unidad_dosificacion]."</td>";
                    if ($control[$i][nombre] != NULL)
                    {
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$control[0][nombre]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"20%\">".$control[0][nombre_usuario]."</td>";
                    }
                    //$this->salida.="  <td align=\"left\" width=\"35%\">".$control[$i][observacion]."</td>";
                    $this->salida.="</tr>";
                    $cantidad_suministrada = $control[0][cantidad_suministrada];
                    $total_suministro = $cantidad_suministrada;
                    
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td colspan = 2 width=\"10%\">TOTAL SUMINISTRADO</td>";
                    $this->salida.="  <td width=\"15%\">".$total_suministro."&nbsp;".$control[0][unidad_dosificacion]."</td>";
                    
                    $href1 = ModuloGetURL('app','EstacionE_Medicamentos','user','Control_Suministro',array("tipo_solicitud"=>'M',"vect"=>$vector1[$i],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bandera"=>1));
                    $this->salida.="  <td width=\"55%\"><a href=\"$href1\">Suministros del Ingreso</a></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }
          elseif($bandera == 1)
          {
               if($control){
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"left\" colspan=\"4\">DOSIS SUMINISTRADAS:</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"15%\">FECHA SUMINISTRO</td>";
                    $this->salida.="  <td width=\"15%\">HORA SUMINISTRO</td>";
                    $this->salida.="  <td width=\"20%\">CANTIDAD SUMINISTRADA</td>";
                    $this->salida.="  <td width=\"20%\">USUARIO</td>";
                    //$this->salida.="  <td width=\"35%\">OBSERVACION DEL SUMINISTRO</td>";
                    $this->salida.="</tr>";
                    $total_suministro=0;
                    for($i=0;$i<sizeof($control);$i++){
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\">";
                         //$this->salida.="  <td align=\"center\" width=\"5%\">".$control[$i][hc_control_suministro_id]."</td>";
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$this->FechaStamp($control[$i][fecha_realizado])."</td>";
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$this->HoraStamp($control[$i][fecha_realizado])."</td>";
                         $this->salida.="  <td align=\"center\" width=\"20%\">".$control[$i][cantidad_suministrada]."&nbsp;".$control[$i][unidad_dosificacion]."</td>";
                         if ($control[$i][nombre] != NULL)
                         {
                              $this->salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td align=\"left\" width=\"20%\">".$control[$i][nombre_usuario]."</td>";
                         }
					//$this->salida.="  <td align=\"left\" width=\"35%\">".$control[$i][observacion]."</td>";
                         $this->salida.="</tr>";
                         $cantidad_suministrada = $control[$i][cantidad_suministrada];
                         $total_suministro = $total_suministro + $cantidad_suministrada;
                    }
                    
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td colspan = 2 width=\"10%\">TOTAL SUMINISTRADO</td>";
                    $this->salida.="  <td width=\"15%\">".$total_suministro."&nbsp;".$control[$i][unidad_dosificacion]."</td>";
                    
                    $href1 = ModuloGetURL('app','EstacionE_Medicamentos','user','Control_Suministro',array("tipo_solicitud"=>'M',"vect"=>$vector1[$i],"datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bandera"=>0));
                    $this->salida.="  <td width=\"55%\"><a href=\"$href1\">Ultimo Suministro</a></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }
               
//          if($total_suministro < $vect[cantidad]){
			if( $i % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}
			unset($SUMATORIA);
			$datos=$this->GetEstacionBodega_Existencias($estacion,1,$vect[codigo_producto]);
			$SUMATORIA=$this->Sumatorias_Cantidades_Para_Bodegas_De_Pacientes($datos_estacion[ingreso],$estacion,$vect[codigo_producto]);
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			
			if(!is_array($datos) && $SUMATORIA<1)
			{
				//aca va el mensaje
				$title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA EL PRODUCTO:&nbsp; $vect[producto]";
				$this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title'</LABEL></DIV>";
			}
			else
			{
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<input type=\"hidden\" name=\"total_suministro\" value=\"$total_suministro\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="<td align=\"left\" colspan=\"6\">INGRESAR SUMINISTRO</td>";
                    $this->salida.="</tr>";
     
                    $this->salida.="<tr class='modulo_list_claro'>";
                    $this->salida.="<td colspan=\"1\" align=\"left\"   width=\"15%\">HORA DEL SUMINISTRO:</td>";
                    $this->salida.="<td colspan=\"1\" align=\"center\" width=\"30%\">";
			
				//EL SELECT DE LÑA HORA DE ARLEY
                    $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
                    $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
                    if(date("H:i:s") >= $hora_inicio_turno)
                    {
                         list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                         list($h,$m,$s)=explode(":",$hora_control);
                    }
                    else
                    {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                         list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                         list($h,$m,$s)=explode(":",$hora_control);
                    }


                    $i=0;
                    $this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";
                    for($j=0; $j<$rango_turno; $j++)
                    {
                         list($anno, $mes, $dia)=explode("-",$fecha_control);
                         if ($i==23)
                         {
                              list($h,$m,$s)=explode(":",$hora_inicio_turno);
                              $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                              $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                              $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                         }
                         else
                         {
                              list($h,$m,$s)=explode(":",$hora_inicio_turno);
                              $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                              $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                              $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                         }
                         if(empty($selectHora)){
                              if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         else
                         {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                              list($A,$B) = explode(" ",$selectHora);
                              if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         #################################################
                         list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                         if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                              $show = "Hoy a las";
                         }
                         elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                              $show = "Mañana a las";
                         }
                         elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                              $show = "Ayer a las";
                         }
                         else{
                              $show = $fecha_control;
                         }
                         ###########################
                         $this->salida .="<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
                    }//fin for
                    $this->salida .= "</select>:&nbsp;\n";
                    $this->salida .= "<select name=\"selectMinutos$pfj\" class=\"select\">\n";

                    for($j=0; $j<=59; $j++)
                    {
                         if(empty($selectMinutos)){
                              if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         else
                         {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                              list($A,$B) = explode(" ",$selectMinutos);
                              if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                         }
                         if ($j<10){
                              $this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
                         }
                              else{
                              $this->salida .= "<option value='$j:00' $selected>$j</option>\n";
                         }
                    }
                    $this->salida .= "</select>\n";
				//FIN
                    $this->salida.="</td>" ;
                    $this->salida.="<td colspan=\"1\" align=\"left\"   width=\"15%\" class=".$this->SetStyle("cantidad_suministrada").">CANTIDAD</td>";
                    if($_REQUEST['cantidad_suministrada'.$pfj]=='')
                    {
                         $this->salida.="<td colspan=\"1\" align=\"center\" width=\"20%\"><input type='text' class='input-text' size = 5 name = 'cantidad_suministrada' >&nbsp; ".$vect[unidad_dosificacion]."</td>" ;
                    }
                    else
                    {
                         $this->salida.="<td colspan=\"1\" align=\"center\" width=\"20%\"><input type='text' class='input-text' size = 5 name = 'cantidad_suministrada'   value =\"".$_REQUEST['cantidad_suministrada']."\">&nbsp; ".$vect[unidad_dosificacion]."</td>" ;
                    }

                    $this->salida.="<input type='hidden' name='dosis' value='".$dosis."'>";
                    
                    $this->salida.="<td  align=\"center\" width=\"10%\">BODEGA:</td>";
                    $this->salida.="<td align=\"center\" width=\"10%\">";
						
                    if(!is_array($datos) && $SUMATORIA<1)
                    {
                         //aca va el mensaje
                         $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA EL PRODUCTO:&nbsp; $vect[producto]";
                         $this->salida.="<img src=\"". GetThemePath() ."/images/preguntaac.png\" title='$title' border='0'>";
                    }
                    else
                    {
                         $this->salida.="<select name=bodega class='select'>";
                         if(is_array($datos))
                         {
                              for($i=0;$i<sizeof($datos);$i++)
                              {
                                   $this->salida.="<option value=".FormatoValor($datos[$i][existencia]).",".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                              }
                         }
                         //$this->salida.="<option value='3,2' SELECTED>HOSPITALIZACION</option>";
                         if($SUMATORIA >0)
                         {$this->salida.="<option value=',*/*' SELECTED>BODEGA PACIENTE</option>";}
                         $this->salida.="</select>";
                    }
                    $this->salida.="</td>\n";
                    $this->salida.="</tr>";
                    $this->salida.="<input type=\"hidden\" name=\"sumatoria\" value=\"$SUMATORIA\">";
                    $this->salida.="<input type=\"hidden\" name=\"totalitario\" value=\"$totalitario\">";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION DE SUMINISTRO</td>";
                    $this->salida.="<td colspan=\"5\" width=\"65%\" align='center'><textarea class='textarea' name = 'observacion_suministro' cols = 100 rows = 7>".$_REQUEST['observacion_suministro']."</textarea></td>" ;
                    $this->salida.="</tr>";
                    $arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($vect[codigo_producto],$bodega);
                    if(is_array($arr_rel))
                    {
                         //parte de los insumos relacionados con los suministros q se hacen al paciente.
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="<td align=\"center\" colspan=\"6\">";
          
                         for($y=0;$y<sizeof($arr_rel);$y++)
                         {
                              $this->salida .= "		<tr rowspan='2' align='center' class='modulo_list_claro'>\n";
                              $this->salida .= "			<td colspan='6' width=\"10%\">\n";
                              
                              $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\"\">\n";
                              if($y==0)
                              {
                                   $this->salida .= "		<tr class=\"modulo_list_table_title\">\n";
                                   $this->salida .= "			<td colspan='4'>SOLICITUD DE INSUMOS RELACIONADOS CON MEDICAMENTOS</td>\n";
                                   $this->salida .= "		</tr>\n";
                              }	
                              
                              $this->salida .= "		<tr align='center' bgcolor='#FFFFFF'>\n";
                              $this->salida .= "			<td width=\"10%\"><label class='label_mark'>".$arr_rel[$y][codigo_producto]."</label></td>\n";
                              $this->salida .= "			<td width=\"25%\"><label class='label_mark'>".$arr_rel[$y][descripcion]."</label></td>\n";
                              $cantidad_insumo = $cantidad_recetada * floor($arr_rel[$y][cantidad]);
                              //$this->salida .= "			<td width=\"10%\"><input type='text' name='cant".$arr_rel[$y][codigo_producto]."' class='input-text' size='7' maxlength='7' value=".$arr_rel[$y][cantidad]."></td>\n";
                              $this->salida .= "			<td width=\"10%\"><input type='text' name='cant".$arr_rel[$y][codigo_producto]."' class='input-text' size='7' maxlength='7' value=".$cantidad_insumo."></td>\n";
                              $this->salida .= "			<td width=\"4%\" ><input type='checkbox'$checked  name='checo[]' value='".$arr_rel[$y][codigo_producto]."'></td>\n";
     
                              $this->salida .= "		</tr></table>\n";
                              $this->salida .= "		</td>\n";
                              
                         }
                         $this->salida.="</td>";
                         $this->salida.="</tr>";
                    }
                                                  
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td align=\"center\" colspan=\"6\"><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"GUARDAR\"></td>";// onclick='CargarPagina(this.form)'
                    $this->salida.="</tr>";
     
                    $this->salida.="</table><br>";
		  	}//fin if duvan
		
          //}//fin if
          $this->salida.="</form>";

  		//BOTON DEVOLVER
          $href = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion));
          $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= themeCerrarTabla();
		return true;
	}
     
     
     function SolSuministros_x_estacion($estacion,$bodega,$SWITCHE)
	{
          if(!$estacion)
          {
               $estacion = $_REQUEST["datos_estacion"];
               $bodega = $_REQUEST["bodega"];
               $SWITCHE = $_REQUEST["switche"];
          }	
     
          $this->salida .= "<SCRIPT>";
          $this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=true";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
          $cadena .= "	function CargarPagina(href,valor) {\n";
          $cadena .= "		var url=href;\n";
          $cadena .= "		location.href=url+'&bodega='+valor;\n";
          $cadena .= "	}\n\n";
          $this->salida .=$cadena;
          $this->salida .= "</SCRIPT>";
          $datos1=$this->GetEstacionBodega($estacion,1);
          $this->salida .= ThemeAbrirTabla("SOLICITUD DE SUMINISTROS POR ESTACION");
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>EMPRESA</td>\n";
          $this->salida .= "			<td>CENTRO</td>\n";
          $this->salida .= "			<td>ESTACION</td>\n";
          $this->salida .= "			<td>FECHA</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$estacion['descripcion1']."</td>\n";
          $this->salida .= "			<td>".$estacion['descripcion2']."</td>\n";
          $this->salida .= "			<td>".$estacion['descripcion4']."</td>\n";
          $this->salida .= "			<td>".date('Y-m-d')."</td>\n";
          $this->salida.="</tr></table><br>";
          
          $accion = ModuloGetURL('app','EstacionE_Medicamentos','user','SolSuministros_x_estacion',array("conteo"=>$_REQUEST['conteo'],"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"datos_estacion"=>$estacion));
               
          $this->salida .="<form name=\"suministro_e\" action=\"$accion\" method=\"post\">";
          
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" class=\"modulo_table_list_title\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO DE SUMINISTROS</td>";
          $this->salida.="</tr>";
     
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">BODEGA</td>";
          $this->salida.="<td width=\"10%\">";
                    
          $this->salida.="<select name=bodega class='select'>";
          for($i=0;$i<sizeof($datos1);$i++)
          {
               if($datos1[$i][bodega]==$_REQUEST['bodega'])
               {
                    $this->salida.="<option value=".$datos1[$i][bodega]." selected>".$datos1[$i][descripcion]."</option>";
                    $a=1;
               }
               else
               {
                    $this->salida.="<option value=".$datos1[$i][bodega].">".$datos1[$i][descripcion]."</option>";
               }	
          }
          if($a !=1){$selected="selected";}else{$selected="";}
          $this->salida.="<option value=\"-1\" $selected>-- SELECCIONE --</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
     
               
          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
          if($_REQUEST['criterio']=='1')
          {$sel1="selected";$sel2="";}else{$sel2="selected";$sel1="";}
          $this->salida.="<option value = '1' $sel1>Codigo</option>";
          $this->salida.="<option value = '2' $sel2>Suministro</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;
     
          $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          if($_REQUEST['busqueda'])
          {
               $cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
          }
          else
          {
               $cadena="Buscador Avanzado: Busqueda de los suministros";
          }
          $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
     
          if($_REQUEST['buscar'] OR $_REQUEST['ADD'])
          {
               $filtro=$this->GetFiltro($_REQUEST['criterio'],$_REQUEST['busqueda']);
          }
	
          //estos if de aqui en adelante,es importante ya que si hemos presionado el boton aicionar temp
          if(empty($_REQUEST['paso']))
               {$pas=1;}else{$pas=$_REQUEST['paso'];}
		
	
		//si presionamos quitar.
		//cabe decir que segun el paso quitamos todos los items q esten en variable de 
		//session.
          if($_REQUEST['DEL'])
          {
               if($_SESSION['ESTAR'][$pas])
               {unset($_SESSION['ESTAR'][$pas]);}
               $variable="SE QUITO TODOS LOS INSUMOS ADICIONADOS DE LA PAGINA &nbsp; $pas";
          }
          else
          {
               $variable='';
          }
          
          
          //si presionamos adicionar........
          if($_REQUEST['ADD'])
          {	
               foreach($_REQUEST['op'] as $index=>$valor)
               {          
                    if(is_numeric($_REQUEST['cant'.$valor]) && $_REQUEST['cant'.$valor] > 0)
                    {$_SESSION['ESTAR'][$pas][$valor]=$valor."*".$_REQUEST['cant'.$valor];}
               }				
          }

          $arr_vect=$this->Get_SuministrosEstacion($_REQUEST['bodega'],$filtro);
          if(is_array($arr_vect))
          {
               $this->salida.="<br><div align='center'><label class='label_mark'>$variable</label></div>";
               $this->salida.="<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"10%\">CODIGO</td>";
               $this->salida.="<td width=\"75%\" colspan='2'>PRODUCTO - ABREVIACION</td>";
               $this->salida.="<td width=\"15%\">CANT</td>";
               $this->salida.='<form name="vv" method="post" action="'.$o.'">';
               $this->salida.="<td width=\"5%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($arr_vect);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class='$estilo' align='left'>";
                    $this->salida.="  <td width=\"10%\">".$arr_vect[$i][codigo_producto]."</td>";
                    $this->salida.="  <td width=\"40%\">".$arr_vect[$i][descripcion]."</td>";
                    $this->salida.="  <td width=\"35%\">".$arr_vect[$i][descripcion_abreviada]."</td>";
                    
                    $info=explode("*",$_SESSION['ESTAR'][$pas][$arr_vect[$i][codigo_producto]]);
                    $this->salida.="  <td width=\"15%\"><label class='label_mark'>Cant &nbsp;</label><input type='text' class='input-text' name=cant".$arr_vect[$i][codigo_producto]." value='".$info[1]."' size='8' maxlength='8'></td>";
                    
                    if($info[0]== $arr_vect[$i][codigo_producto])
                    {$check="checked";}else{$check="";}
                    $this->salida.="  <td width=\"5%\" align=\"center\"><input type=checkbox name=op[$i] value=".$arr_vect[$i][codigo_producto]." $check></td>";unset($check);
                    $this->salida.="</tr>";
               }
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td colspan='4'><input type=submit name=DEL value='QUITAR ITEMS SELECCIONADOS DE ESTA PAGINA' class=input-submit></td>";
               $this->salida.="<td><input type=submit name=ADD value=ADICIONAR class=input-submit></form></td>";
               $this->salida.="</tr>";
               $this->salida.="</table>";
               
          
               $this->salida.=$this->RetornarBarra($filtro,1);
          }
          else
          {
               $this->salida .= "<br><br><div align='center'><label class='label_mark'>SELECCIONE LA BODEGA</label></div>";
          }
          
          $XYS = ModuloGetURL('app','EstacionE_Medicamentos','user','Solicitar_SuministrosEstacion',array("datos_estacion"=>$estacion,"datos_pac"=>$datos_estacion,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));	
          $this->salida .= "<form name=\"formainsert\" action=\"$XYS\" method=\"post\">";
          $this->salida .= '<br><br><table align="center" width="40%" border="0">';
          $this->salida .= '<tr>';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
     
          $o = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,'switche'=>'Solicitar_sol'));
          $this->salida .= '<form name="volver" method="post" action="'.$o.'">';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
          $this->salida .= '</tr>';
          
          $this->salida .= '</table>';
          
          $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;		
     }


     /*
     *
     *
     *		@Author Tizziano Perea Ocoro.
     *		@access Private
     *		@return bool
     *		Proposito: Unificacion de funciones para recibir insumos y medicamentos
     */


     function ConSuministros_x_estacion($estacion,$bodega,$SWITCHE)
     {
          if(empty($estacion))
          {
               $estacion=$_REQUEST['estacion'];
               $SWITCHE=$_REQUEST['switche'];
               $bodega=$_REQUEST['bodega'];
          }
          
          $nom_bodega=$this->TraerNombreBodega($estacion,$bodega);

          $actionCon = ModuloGetURL('app','EstacionE_Medicamentos','user','AccionCancelCon_Solicitud',array('estacion'=>$estacion,'bodega'=>$bodega,'switche'=>$SWITCHE,'accion'=>'confirmar'));
          $this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE SUMINISTRO POR ESTACION &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
          $this->salida .="<form name=\"AccionCon\" action=\"$actionCon\" method=\"post\">";
          $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";

          //consulta las solicitudes de suministro para la estacion.
          $solicitudes=$this->GetSolicitudes_x_Estacion($estacion,$bodega);

          if(!empty($solicitudes))
          {
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<td colspan=\"2\">SOLICITUDES DE SUMINISTRO POR CONFIRMAR DE LA ESTACION: $estacion[descripcion4]</td>";
               $this->salida .= "</tr>";
               
               $desabilitarX = 1;
               
               for($i=0;$i<sizeof($solicitudes);$i++)
               {
                    $despachos=$this->GetSuministrosSolicitadosConfirmar_x_Estacion($solicitudes[$i][solicitud_id]);
                    $sizevar = sizeof($despachos);
                    if(!empty($despachos))
                    {
                         $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida .= "<td colspan=\"2\">";
                         
                         $desabilitarX = 0;
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"46%\" >PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CANT&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"8%\" >DESPACHO</td>\n";
                         $this->salida .= "			<td width=\"3%\" >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";
                    
                         $this->salida .= "<input type=\"hidden\" name=\"despachos\" value=\"$sizevar\">";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$solicitudes[$i][solicitud_id]."</td>\n";
                         $this->salida .= "<td colspan = 6 width=\"65%\">";
                         $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         
                         for($j=0;$j<sizeof($despachos); $j++)
                         {
                              if($j % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"12%\">".$despachos[$j][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"36%\">".$despachos[$j][descripcion]."</td>\n";
                              $this->salida .= "<td $estilo width=\"33%\">".$despachos[$j][descripcion_abreviada]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($despachos[$j][cantidad])."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"11%\" ><b>".$despachos[$j][cantidad]." Uds.</b></td>\n";
                              $this->salida .= "<td $estilo width=\"3%\" align=\"center\"><input type=checkbox name=opcion[] value=\"".$despachos[$j][solicitud_id].",".$despachos[$j][consecutivo]."\"></td>";unset($chek);
                              $this->salida .="</tr>";
                         }
                         $this->salida .= "</table>"; 
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</table><br>";
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                    }
			}
               
               if($desabilitarX != 0)
               {
                    $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "<td colspan=\"2\">";
                    $title="NO EXISTEN SUMINISTROS PARA CONFIRMAR REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
                    $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
                    $desabilitarX = 1;
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td colspan=\"2\" nowrap width=\"40\" align=\"center\">";
               if ($desabilitarX == 1)
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"confirmar_con\" value=\"CONFIRMAR\" disabled>"; }
               else
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"confirmar_con\" value=\"CONFIRMAR\">"; }
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.= "</table><br>";
			$this->salida.= "</form>";               
          }
          
          $actionCan = ModuloGetURL('app','EstacionE_Medicamentos','user','AccionCancelCon_Solicitud',array('estacion'=>$estacion,'bodega'=>$bodega,'switche'=>$SWITCHE,'accion'=>'cancelar'));
          $this->salida .="<form name=\"AccionCon\" action=\"$actionCan\" method=\"post\">";
          $this->salida .="<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";

          if(!empty($solicitudes))
          {
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<td colspan=\"2\">SOLICITUDES DE SUMINISTRO POR CANCELAR DE LA ESTACION: $estacion[descripcion4]</td>";
               $this->salida .= "</tr>";
               
               $desabilitar = 1;
               for($i=0;$i<sizeof($solicitudes);$i++)
               { 
                    $despachos = $this->GetSuministrosSolicitadosCancelar_x_Estacion($solicitudes[$i][solicitud_id]);
                    $sizevar = sizeof($despachos);
				if(!empty($despachos))
                    {                    
                         $desabilitar = 0; 
                         $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida .= "<td colspan=\"2\">";
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"46%\" >PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CANT&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"3%\" >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";
                    
                         $this->salida .= "<input type=\"hidden\" name=\"despachos\" value=\"$sizevar\">";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$solicitudes[$i][solicitud_id]."</td>\n";
                         $this->salida .= "<td colspan = 6 width=\"65%\">";
                         $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         
                         for($j=0;$j<sizeof($despachos); $j++)
                         {
                              if($j % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"15%\">".$despachos[$j][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"41%\">".$despachos[$j][descripcion]."</td>\n";
                              $this->salida .= "<td $estilo width=\"37%\">".$despachos[$j][descripcion_abreviada]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"15%\">".floor($despachos[$j][cantidad])."</td>\n";
                              $this->salida .= "<td $estilo width=\"3%\" align=\"center\"><input type=checkbox name=opcion[] value=\"".$despachos[$j][solicitud_id].",".$despachos[$j][consecutivo]."\"></td>";unset($chek);
                              $this->salida .="</tr>";
                         }
                         $this->salida .= "</table>"; 
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</table><br>";
                         $this->salida .= "</td>";
		               $this->salida .= "</tr>";
                    }                              
			}
               
               if($desabilitar != 0)
               {    
                    $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "<td colspan=\"2\">";
                    $title="NO EXISTEN SUMINISTROS PARA CANCELAR REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
                    $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
                    $desabilitar = 1;
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td colspan=\"2\" width=\"40\" align=\"center\">";
               if($desabilitar == 1)
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"cancelar_con\" value=\"CANCELAR\" disabled>"; }
               else
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"cancelar_con\" value=\"CANCELAR\">"; }
               $this->salida.="</td>";
               $this->salida.="</tr>"; 
               $this->salida.= "</table>";
			$this->salida.= "</form>";               
          }

          
          $hr = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$estacion,"switche"=>$SWITCHE));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>Volver a Seleccion de Bodega</a><br>";

          $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

          $this->salida .= themeCerrarTabla();               
          return true;
	}

}//fin de la clase
?>
