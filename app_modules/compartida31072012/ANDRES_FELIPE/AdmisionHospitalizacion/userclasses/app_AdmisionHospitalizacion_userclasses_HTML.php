<?php
	/**************************************************************************************
	* $Id: app_AdmisionHospitalizacion_userclasses_HTML.php,v 1.24 2006/10/24 18:51:54 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.24 $
	*
	* Codigo Tomado del modulo de triage
	*
	* @author Hugo Freddy Manrique
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_AdmisionHospitalizacion_userclasses_HTML extends app_AdmisionHospitalizacion_user
	{
  	function app_AdmisionHospitalizacion_user_HTML()
		{
			$this->salida='';
			$this->app_AdmisionHospitalizacion_user();
			return true;
		}
		/**********************************************************************************
		* Muestra el menu de las empresas y centros de utilidad 
		* 
		* @access public 
		***********************************************************************************/
		function FormaMostrarMenuHospitalizacion($forma)
		{
			$this->salida .= $forma;
			return true;
		}
		/**********************************************************************************
		* Forma del menu de admisiones
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMenuHospitalizacion()
		{
      $class = "class=\"table_kernel\"";
			$this->salida .= ThemeAbrirTabla('MENU ADMISION HOSPITALIZACION');
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td width=\"40%\">\n";
			$this->salida .= "			<table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" >\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"nivel1_oscuro \" height=\"20\"><b style=\"color:#ffffff\">MENU</b></td>";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','ListadoAdmisionHospitalizacion')."\"><b>ORDEN INTERNA</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'))."\"><b>ORDEN EXTERNA</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','OrdenHospitalizacionCirugia')."\"><b>ORDEN DE CIRUGÍA</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa',"menu"=>"urgencias"))."\"><b>ATENCION URGENCIAS</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			if($this->triage == 1)
			{
				$this->salida .= "				<tr>\n";
				$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','ListarPacientesTriages',array('TIPOORDEN'=>'Externa',"menu"=>"urgencias","orden1"=>"triage"))."\"><b>PACIENTES CLASIFICADOS PENDIENTES POR ADMITIR</b></a></td>\n";
				$this->salida .= "				</tr>\n";
			
			}
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"nivel1_oscuro \" height=\"6\"></td>";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPacientesModificar')."\"><b>MODIFICAR INGRESO</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','BuscarPaciente')."\"><b>BUSCAR PACIENTE</b></a></td>\n";
			$this->salida .= "				</tr>\n";			
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" $class height=\"17\"><a href=\"".ModuloGetURL('app','AdmisionHospitalizacion','user','Reporte')."\"><b>REPORTES</b></a></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";			
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
      $this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* La funcion ListadoAdmisionHospitalizacion muestra el listado de los pacientes
		* pendientes por por hospitalizacion
		*
		* @return boolean
		***********************************************************************************/
		function FormaListadoAdmisionHospitalizacion()
		{
			$this->salida  = ThemeAbrirTabla('LISTADO ADMISION HOSPITALIZACION');
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
			$this->salida .= "	<table width=\"60%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= $this->BuscadorPacientes();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td><br>\n";
			$this->salida .= $this->BuscadorOrdenes();
			$this->salida .= "		<br></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
				
			if(!empty($this->Ordenes))
			{
				if(sizeof($this->Ordenes) > 0)
				{
					$this->salida .= "	<table border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "     <tr class=\"modulo_table_list_title\" align=\"center\">\n";
					$this->salida .= "		  <td width=\"9%\" >No. ORDEN</td>\n";
					$this->salida .= "        <td width=\"9%\" >TIPO ORDEN</td>\n";
					$this->salida .= "	  	  <td width=\"9%\" >FECHA</td>\n";
					$this->salida .= "        <td width=\"10%\" title=\"FECHA DE PROGRAMACIÓN\">FECHA PRO.</td>\n";
					$this->salida .= "        <td width=\"15%\">No.DOCUMENTO</td>\n";
					$this->salida .= "        <td width=\"30%\">PACIENTE</td>\n";
					$this->salida .= "        <td width=\"18%\">OPCIONES</td>\n";
					$this->salida .= "      </tr>\n";
				
					for($i=0; $i<sizeof($this->Ordenes); $i++)
					{
						$Fechaorden = $this->FechaStamp($this->Ordenes[$i][fecha_orden]);
						$Fechaprogramacion = $this->FechaStamp($this->Ordenes[$i][fecha_programacion]);
						
						$action3 = ModuloGetURL('app','AdmisionHospitalizacion','user','VerificarDatosHospitalizacion',array('datos'=>$this->Ordenes[$i]));
						
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro'; $background = "#DDDDDD";
						}
						
						$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "				<td width=\"9%\"  align=\"center\">".$this->Ordenes[$i][orden_hospitalizacion_id]."</td>\n";
						$this->salida .= "              <td width=\"10%\" align=\"center\"><b>".$this->Ordenes[$i][descripcion]."</b></td>\n";
						$this->salida .= "	  	        <td width=\"8%\"  align=\"center\">".$Fechaorden."</td>\n";
						$this->salida .= "              <td width=\"10%\" align=\"center\">".$Fechaprogramacion."</td>\n";
						$this->salida .= "              <td width=\"10%\">".$this->Ordenes[$i][tipo_id_paciente]." ".$this->Ordenes[$i][paciente_id]."</td>\n";
						$this->salida .= "              <td width=\"27%\">".$this->Ordenes[$i][completo]."</td>\n";
						$this->salida .= "              <td align=\"center\"><a href=\"".$action3."\"><b>ADMITIR</b></a></td>\n";
						$this->salida .= "            </tr>";
					}
	    		$this->salida .= "            </table><br>\n";
					
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaA,$this->action0);
					$this->salida .= "		<br>\n";
				}
			}
			elseif($this->paso == 1)
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
			}

			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* Funcion que retorna el mensaje que se desea desplegar en la forma 
		* 
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function SetStyle($campo)
		{
			if ($this->frmError[$campo]){
				if ($campo=="MensajeError" || $campo=="MensajeError2"){
					return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError[$campo]."</td></tr>");
				}
				else if ($campo != "")
				{
					$mensaje .= "	<tr>\n";
					$mensaje .= "		<td width=\"19\"><img src=\"".GetThemePath()."/images/infor.png\" border=\"0\"></td>\n";
					$mensaje .= "		<td class=\"label\" align=\"justify\">".$this->frmError[$campo]."</td>\n";
					$mensaje .= "	</tr>\n";

					return $mensaje;
				}
				return ("<tr><td>&nbsp;</td></tr>");
			}
			return ("<tr><td>&nbsp;</td></tr>");
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaElegirProfesionalAtender()
		{
			$this->salida .= ThemeAbrirTabla('ELEGIR MEDICO - CONSULTA');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">MEDICO CONSULTA:</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<select name=\"terceros\" class=\"select\">\n";
			$this->salida .=" 					<option value=\"\">-------NINGUNO-------</option>\n";
			
			for($i=0; $i<sizeof($this->profesionales); $i++)
			{
				$this->salida .=" 					<option value=\"".$this->profesionales[$i]['tipo_id_tercero']."-".$this->profesionales[$i]['tercero_id']."-".$this->profesionales[$i]['usuario_id']."-".$this->profesionales[$i]['nombre']."\">".$this->profesionales[$i]['nombre']."</option>\n";
			}			
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "    <tr>\n";
			$this->salida .= "			<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "				<table width=\"100%\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td align=\"center\">\n";
			$this->salida .= "							<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "								</form>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "						<td align=\"center\">\n";
			$this->salida .= "							<form name=\"formabuscar2\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "								<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$this->salida .= "							</form>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();		
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaRegistroResponsable()
		{
			$this->salida .= ThemeAbrirTabla('BUSCAR PACIENTE');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">RESPONSABLE:</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<select name=\"Plan\" class=\"select\">\n";
			$this->salida .=" 					<option value=\"-1\">-------SELECCIONAR-------</option>\n";
			
			$resp = $this->Responsables();
			for($i=0; $i<sizeof($resp); $i++)
			{
				if($resp[$i][plan_id] == $this->Responsable)
				{
					$this->salida .=" 				<option value=\"".$resp[$i][plan_id]."\" selected>".$resp[$i][plan_descripcion]."</option>\n";
				}
				else
				{
					$this->salida .=" 				<option value=\"".$resp[$i][plan_id]."\">".$resp[$i][plan_descripcion]."</option>\n";
				}
			}			
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "    <tr>\n";
			$this->salida .= "			<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "				<table width=\"100%\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td align=\"center\">\n";
			$this->salida .= "							<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "								</form>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "						<td align=\"center\">\n";
			$this->salida .= "							<form name=\"formabuscar2\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "								<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$this->salida .= "							</form>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Forma para capturar los datos para buscar el paciente
		* @access private
		* @return boolean
		**********************************************************************************/
		function FormaBuscar()
		{
			$this->salida .= ThemeAbrirTabla('ADMISIONES - BUSCAR PACIENTE');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";				
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\" >\n";			
			$this->salida .= "	<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<input type='hidden' name='NoAutorizacion' value=''>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">PLAN:</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"Responsable\" class=\"select\">";
			$this->Responsables = $this->Responsables();
			$this->salida .= "				".$this->MostrarResponsable($this->Responsables,$this->Responsable);
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "              <select name=\"TipoDocumento\" class=\"select\">\n";
				
			$tipo_id = $this->TipoIdPaciente();
			foreach($tipo_id as $value=>$titulo)
			{
				($value == $this->TipoId)? $sel = "selected":$sel = "";
				
				$this->salida .="			<option value=\"$value\" $sel>$titulo</option>\n";  
			}
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$this->PacienteId."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			
			if($_SESSION['AdmHospitalizacion']['menu'])
			{
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td colspan=\"2\">\n";
				$this->salida .= "				<table class=\"normal_10\" width=\"100%\">\n";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
				$this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"1\" checked><b>PACIENTE NO REMITIDO</b>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
				$this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"2\"><b>PACIENTE REMITIDO</b>\n";
				$this->salida .= "						</td>\n";
				if($this->triage == 1)
				{
					$this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
					$this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"3\"><b>PACIENTE - TRIAGE</b>\n";
					$this->salida .= "						</td>\n";
				}
				$this->salida .= "					</tr>\n";
				$this->salida .= "				</table>\n";
				
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";			
			}
			
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\" class=\"label_error\" colspan=\"2\">\n";
			$this->SetJavaScripts('BuscadorBD');
			$this->salida .= RetornarWinOpenDatosBuscadorBD($_SESSION['AdmisionHospitalizacion']['deptno'],'formabuscar');
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "      <form name=\"forma\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaMostrarInfoIngreso()
		{
			$this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS DEL INGRESO');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("Informacion")."\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">INGRESO:</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">".$this->ingreso[0]['ingreso']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">FECHA INGRESO</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">".$this->ingreso[0]['fecha_ingreso']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">VIA INGRESO:</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">".$this->ingreso[0]['via_ingreso_nombre']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">PACIENTE:</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"25%\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->ingreso[0]['tipo_id_paciente']." ".$this->ingreso[0]['paciente_id']."</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->ingreso[0]['nombres']." ".$this->ingreso[0]['apellidos']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "</table><br>\n";
			
			if(sizeof($this->cuentas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "     <tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "        <td width=\"15%\" >Nº CUENTA</td>\n";
				$this->salida .= "	  	  <td width=\"%\" >PLAN</td>\n";
				$this->salida .= "        <td width=\"15%\">TOTAL</td>\n";
				$this->salida .= "        <td width=\"15%\">ESTADO</td>\n";
				$this->salida .= "      </tr>\n";
				
				for($i=0; $i<sizeof($this->cuentas); $i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro';  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
						
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"center\"><b>".$this->cuentas[$i]['numerodecuenta']."</b></td>\n";
					$this->salida .= "				<td align=\"left\">".$this->cuentas[$i]['plan_descripcion']."</td>\n";
					$this->salida .= "				<td align=\"center\">".formatoValor($this->cuentas[$i]['total_cuenta'])."</td>\n";
					$this->salida .= "				<td align=\"center\" ><b>".$this->cuentas[$i]['descripcion']."</b></td>\n";
					$this->salida .= "			</tr>";
				}
	    	$this->salida .= "            </table><br>\n";	
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO TIENE CUENTAS</b></center>\n";
			}

			$this->salida .= "<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Forma para capturar los datos para buscar el paciente
		* @access private
		* @return boolean
		**********************************************************************************/
		function FormaReporte()
		{
			$this->salida .= ThemeAbrirTabla('REPORTES- BUSCAR PACIENTE');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";				
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\" >\n";			
			$this->salida .= "	<table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<input type='hidden' name='NoAutorizacion' value=''>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"TipoDocumento\" class=\"select\">\n";
				
			$tipo_id = $this->TipoIdPaciente();
			foreach($tipo_id as $value=>$titulo)
			{
					$this->salida .="				<option value=\"$value\">$titulo</option>\n";  
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";

			if(!empty($this->vars))
			{
				$this->salida .= "	<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "			<td width=\"20%\">No. INGRESO</td>\n";
				$this->salida .= "			<td width=\"15%\">IDENTIFICACIÓN</td>\n";
				$this->salida .= "			<td width=\"45%\">PACIENTE</td>\n";
				$this->salida .= "			<td width=\"10%\">ESTADO</td>\n";
        $this->salida .= "			<td width=\"10%\"></td>\n";
				$this->salida .= "		</tr>";				
				for($i=0; $i<sizeof($this->vars); $i++)
				{
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','AdmisionHospitalizacion','ingreso',
																							array("tipo_id_paciente"=>$this->vars[$i]['tipo_id_paciente'],
																										"paciente_id"=>$this->vars[$i]['paciente_id'],
																										"ingreso"=>$this->vars[$i]['ingreso']),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = "A$i".$reporte->GetJavaFunction();
					$mostrar = str_replace("function W","function A$i"."W",$mostrar);

					$this->salida .= "			<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "				<td align=\"center\">".$this->vars[$i]['ingreso']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$this->vars[$i]['tipo_id_paciente']." ".$this->vars[$i]['paciente_id']."</td>\n";
					$this->salida .= "				<td align=\"left\">".$this->vars[$i]['nombres']." ".$this->vars[$i]['apellidos']."</td>\n";
					$this->salida .= "				<td align=\"center\"><b class=\"label_mark\">".$this->vars[$i]['estado']."</b></td>\n";
					$this->salida .= "				<td width=\"5%\">\n";
					$this->salida .= "				".$mostrar."\n";
					$this->salida .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL INGRESO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$this->salida .= "						<b>REPORTE</b></a></center>\n";
					$this->salida .= "			</td>\n";				
					$this->salida .= "			</tr>\n";
				}
    		$this->salida .= "	</table><br>\n";
			}
			
			$this->salida .= "<form name=\"forma\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Forma para capturar los datos de la orden de hospitalizacion externa
		* @access private
		* @return boolean
		***********************************************************************************/
		function FormaOrdenExterna()
		{
			$this->salida .= ThemeAbrirTabla('ORDEN HOSPITALIZACION EXTERNA');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptDate(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			
			$datos = $this->DatosBasicosPaciente(); 
			$estilo = " class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\" "; 
				
			$this->salida .= "	<table width=\"56%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"20%\" nowrap>PACIENTE: </td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\">".$datos[0][completo]."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"20%\" nowrap>IDENTIFICACION: </td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"30%\">".$datos[0][tipo_id_paciente]." ".$datos[0][paciente_id]."</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"20%\">INGRESO: </td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"30%\">".$datos[0][ingreso]."</td>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>FECHA: </td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"".$this->Fecha."\" onkeypress=\"return acceptDate(event)\">\n";
			$this->salida .= "			</td >\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<b>".ReturnOpenCalendario('forma','Fecha','/')."</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>HORA: </td>\n";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Hora\" size=\"4\" value=\"".$this->Hora."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"Min\" size=\"4\" value=\"".$this->Min."\"  maxlength=\"2\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "		  <td $estilo>ENTIDAD: </td>\n";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"Origen\" class=\"select\">\n";
				
			$entidades = $this->EntidadesOrigen();
			$this->salida .= "				".$this->BuscarEntidadOrigen($entidades,$this->Origen);
			$this->salida .= "          	</select>\n";
			$this->salida .= "      	</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>MEDICO: </td>\n";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Medico\" value=\"".$this->Medico."\" size=\"30\" maxlength=\"50\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$mostrar = ReturnClassBuscador('diagnostico','','','forma');
			$this->salida .=$mostrar;
			$this->salida .= "	</script>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" colspan=\"3\">DIAGNOSTICO: </td>\n";
			$this->salida .= "			<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"".$this->Codigo."\">\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<textarea cols=\"100\" rows=\"3\" class=\"textarea\" name=\"cargo\" READONLY>".$this->Cargo."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<input type=\"button\" name=\"buscar\" value=\"Buscar Diagnostico\" onclick=abrirVentana() class=\"input-submit\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" colspan=\"3\">OBSERVACIONES: </td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";			
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<textarea cols=\"100\" rows=\"3\" class=\"textarea\"name=\"Observacion\">".$this->Observacion."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr align=\"center\">\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"2%\"></td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		<form name=\"forma2\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "			<td colspan=\"3\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";

      $this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Forma para mensajes.
		* 
		* @param string mensaje
		* @param string nombre de la ventana
		* @return boolean
		***********************************************************************************/
		function FormaMensaje($informacion,$titulo,$nombre = null)
		{
			if($nombre == null) $nombre = "Aceptar";
			
			$this->salida .= ThemeAbrirTabla($titulo);
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$informacion."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form><br>\n";
			
			if($this->action2)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"$nombre\">\n";
				$this->salida .= "			</td></form>\n";
				
			}
			
			if($this->Imprimir == 1)
			{				
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','AdmisionHospitalizacion','ingreso',
																							array("tipo_id_paciente"=>$this->TipoId,"paciente_id"=>$this->PacienteId,"ingreso"=>$this->Ingreso),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = $reporte->GetJavaFunction();

					$this->salida .= "		".$mostrar."\n";				
					$this->salida .= "			<td align=\"center\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Ingreso\" onclick=\"$funcion\">\n";
					$this->salida .= "			</td>\n";
			}
			
			if($this->Caja == 1)
			{	
					$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action3."\" method=\"post\">\n";
					$this->salida .= "			<td align=\"center\">\n";
					$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Pagar En Caja\" >\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</form>\n";
			}
			
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Forma para capturar los datos para buscar el paciente
		* @return boolean
		* @param int entidad que genera la orden
		* @param int entidad que genera la orden (cuando ya han elegido una)
		***********************************************************************************/
		function BuscarEntidadOrigen($ent,$Origen)
		{
			$opcion .=" <option value=\"-1\">---Seleccione---</option>\n";
			for($i=0; $i<sizeof($ent); $i++)
			{
				if($ent[$i][sgsss]==$Origen)
				{
					$opcion .=" <option value=\"".$ent[$i][sgsss]."\" selected>".$ent[$i][nombre_sgsss]."</option>\n";
				}
				else
				{
					$opcion .=" <option value=\"".$ent[$i][sgsss]."\">".$ent[$i][nombre_sgsss]."</option>\n";
				}
			}
			return $opcion;
		}
		/************************************************************************************
		*****								 CIRUGIA 																										*****
		*************************************************************************************/
		/************************************************************************************
		* Forma donde se muestra el listado de ordenes de cirugia que hay presentes
		*************************************************************************************/
		function MostrarOrdenHospitalizacionCirugia()
		{
			$this->salida  = ThemeAbrirTabla('LISTADO ORDENES DE CIRUGÍA');
			$this->salida .= "		<script language=\"javascript\">\n";
			$this->salida .= "			function mOvr(src,clrOver)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				src.style.background = clrOver;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			function mOut(src,clrIn)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				src.style.background = clrIn;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		</script>\n";
			$this->salida .= "	<table width=\"60%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= $this->BuscadorPacientes();
			$this->salida .= "			<br></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= $this->BuscadorProgramaciones();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			if($this->Datos)
			{
				if(sizeof($this->Datos) > 0)
				{
					$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
					$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "				<td width=\"7%\" ><b>Nº PRO</b></td>\n";
					$this->salida .= "				<td width=\"15%\"><b>FECHA PRO.</b></td>\n";
					$this->salida .= "				<td width=\"15%\"><b>FECHA FIN</b></td>\n";
					$this->salida .= "				<td width=\"15%\"><b>DEPARTAMENTO</b></td>\n";
					$this->salida .= "				<td width=\"15%\"><b>DOCUMENTO</b></td>\n";
					$this->salida .= "				<td width=\"29%\"><b>PACIENTE</b></td>\n";
					$this->salida .= "				<td width=\"9%\" ><b>OPCIONES</b></td>\n";
					$this->salida .= "			</tr>";
					for($i=0; $i< sizeof($this->Datos); $i++ )
					{						
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						$action = ModuloGetURL('app','AdmisionHospitalizacion','user','SeleccionarDepartamento',array("datos"=>$this->Datos[$i]));
						
						$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "				<td align=\"center\" >".$this->Datos[$i]['programacion_id']."</td>\n";
						$this->salida .= "				<td align=\"center\" >".$this->Datos[$i]['fecha']."</td>\n";
						$this->salida .= "				<td align=\"center\" >".$this->Datos[$i]['fechafin']."</td>\n";
						$this->salida .= "				<td align=\"justify\" class=\"label_mark\">".$this->Datos[$i]['descripcion']."</td>\n";
						$this->salida .= "				<td align=\"justify\">".$this->Datos[$i]['tipo_id_paciente']." ".$this->Datos[$i]['paciente_id']."</td>\n";
						$this->salida .= "				<td align=\"justify\">".$this->Datos[$i]['paciente']."</td>\n";
						$this->salida .= "				<td align=\"center\" >\n";
						$this->salida .= "					<a class=\"label_error\" href=\"".$action."\">\n";
						$this->salida .= "					<b>ADMITIR</b></a>\n";
						$this->salida .= "				</td>\n";					
						$this->salida .= "			</tr>\n";
					}
					$this->salida .= "	</table><br>\n";
										
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaA,$this->action2);
					$this->salida .= "		<br>\n";
				}
			}
			elseif($this->paso == 1)
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
			}
			
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "	<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();		
		}
		/************************************************************************************
		* Funcion donde se pide el departamento al que se va a pasar el departamento
		*************************************************************************************/
		function FormaSeleccionarDepartamento()
		{
      if(!$this->Semanas) $this->Semanas = 0;
			
			$this->salida  = ThemeAbrirTabla('ORDENES DE CIRUGÍA - DEPARTAMENTO');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"forma\" action=\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td >SELECCIONAR DEPARTAMENTO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"deptno\" class=\"select\" onChange=\"Valor(this.value)\">\n";
			$this->salida .=" 					<option value=\"-1\" >-----SELECCIONAR-----</option>\n";
			
			$Deptno = $this->BuscarDepartamento();
			
			for($i=0; $i<sizeof($Deptno); $i++)
			{
				$this->salida .=" 					<option value=\"".$Deptno[$i]['departamento']."\">".$Deptno[$i]['descripcion']."</option>\n";

			}	
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"30%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";			
			$this->salida .= ThemeCerrarTabla();
		}
		/************************************************************************************
		* Funcion donde se pide el plan, el tipo de afiliado, el rango y las semanas 
		* cotizadas para realizar el ingreso del paciente
		*************************************************************************************/
		function FormaCrearIngreso()
		{
      if(!$this->Semanas)	$this->Semanas = 0;
			
			$this->salida  = ThemeAbrirTabla('ORDENES DE CIRUGÍA');
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"forma\" action=\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>SELECCIONAR PLAN: </td>\n";
			$this->salida .= "		</tr>\n";		
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"Responsable\" class=\"select\">\n";
			
			$this->IncludeJS('RemoteScripting');
      $this->IncludeJS('RemoteScripting/misfunciones.js', $contenedor='app', $modulo='AdmisionHospitalizacion');
			
			$this->Responsables = $this->Responsables(1);
			
			for($i=0; $i<sizeof($this->Responsables); $i++)
			{
				if($this->Responsables[$i][plan_id] == $this->Responsable)
				{
					$this->salida .=" 				<option value=\"".$this->Responsables[$i][plan_id]."\" selected>".$this->Responsables[$i][plan_descripcion]."</option>\n";
				}
				else
				{
					$this->salida .=" 				<option value=\"".$this->Responsables[$i][plan_id]."\">".$this->Responsables[$i][plan_descripcion]."</option>\n";
				}
			}	
			$this->salida .= "              </select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";			
			$this->salida .= "	</table><br>\n";
      
      $this->salida .= "  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "    <tr class=\"modulo_table_list_title\"><td>OBSERVACIONES DEL INGRESO</td></tr>";
      $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\"><textarea name=\"observaciones\" cols=\"55\" rows=\"3\" class=\"textarea\">".$_REQUEST['observaciones']."</textarea></td></tr>";
      $this->salida .= "  </table><br>\n";
      
			$this->salida .= "	<table width=\"50%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";	
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaElegirEstacion()
		{
			$this->salida .= ThemeAbrirTabla('ELEGIR ESTACION DE ENFERMERIA');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			
			$this->salida .= "	<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" >CONSULTA URGENCIAS</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<select name=\"estacion1\" class=\"select\">\n";
			$this->salida .= " 						<option value=\"-1\">---Seleccione---</option>\n";
			
			for($i=0; $i<sizeof($this->Consulta); $i++)
			{
				$this->salida .="							<option value=\"".$this->Consulta[$i][estacion_id].",".$this->Consulta[$i][departamento].",".$this->Consulta[$i][descripcion]."\">".$this->Consulta[$i][descripcion]."</option>\n";
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" >OBSERVACIONES URGENCIAS</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "				<td >\n";
			$this->salida .= "					<select name=\"estacion2\" class=\"select\">\n";
			$this->salida .= " 						<option value=\"-1\">---Seleccione---</option>\n";
			
			for($i=0; $i<sizeof($this->Observa); $i++)
			{
				$this->salida .="						<option value=\"".$this->Observa[$i][estacion_id].",".$this->Observa[$i][departamento].",".$this->Observa[$i][descripcion]."\">".$this->Observa[$i][descripcion]."</option>\n";
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>";
			$this->salida .= "		</form>";
			$this->salida .= "	</table>";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*****								 		BUSCAR PACIENTES																			*****
		***********************************************************************************/
		/**********************************************************************************
		* Fucion donde se muestra la forma donde se presenta la lista de ingresos de la 
		* busqueda de un paciente
		***********************************************************************************/
		function FormaBuscarPacientes()
		{
			$this->salida .= ThemeAbrirTabla('BUSCAR PACIENTES');
			
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";				
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\" >\n";			
			$this->salida .= "	<table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<select name=\"tipodocumento\" class=\"select\">\n";
				
			$tipo_id = $this->TipoIdPaciente();
			foreach($tipo_id as $value=>$titulo)
			{
					($this->TipoDoc == $value)? $sel = "selected":$sel = "";
					$this->salida .="				<option value=\"$value\" $sel>$titulo</option>\n";  
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"documento\" value=\"".$this->Documento."\" maxlength=\"32\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >NOMBRES: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$this->Nombres."\" size=\"40\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >APELLIDOS: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"apellidos\" value=\"".$this->Apellidos."\" size=\"40\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";			

			if(!empty($this->paciente))
			{
				$this->salida .= "	<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" align=\"center\">\n";
				$this->salida .= "			<td width=\"7%\" >INGRESO</td>\n";
				$this->salida .= "			<td width=\"9%\" >FECHA ING.</td>\n";
				$this->salida .= "			<td width=\"12%\">IDENTIFICACIÓN</td>\n";
				$this->salida .= "			<td width=\"25%\">PACIENTE</td>\n";
				$this->salida .= "			<td width=\"14%\">DEPARTAMENTO</td>\n";
        $this->salida .= "			<td width=\"%\">OPCIONES</td>\n";
				$this->salida .= "		</tr>";
				for($i=0; $i<sizeof($this->paciente); $i++)
				{
					$this->salida .= "		<tr class=\"modulo_list_claro\" >\n";
					$this->salida .= "			<td align=\"center\">".$this->paciente[$i]['ingreso']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$this->paciente[$i]['fecha_ingreso']."</td>\n";
					$this->salida .= "			<td align=\"left\"  >".$this->paciente[$i]['tipo_id_paciente']." ".$this->paciente[$i]['paciente_id']."</td>\n";
					$this->salida .= "			<td align=\"left\"  >".$this->paciente[$i]['nombres']." ".$this->paciente[$i]['apellidos']."</td>\n";
					$this->salida .= "			<td>".$this->paciente[$i]['descripcion']."</td>\n";
					switch($this->paciente[$i]['tabla'])
					{
						case 'URG':
							$mensaje = "EL PACIENTE SE ENCUENTRA EN CONSULTA DE URGENCIAS, EN LA ESTACIÓN: ".$this->paciente[$i]['estacion']." ";
						break;
						case 'EEF':
							$mensaje = "EL PACIENTE POSEE UN INGRESO PENDIENTE, EN LA ESTACIÓN: ".$this->paciente[$i]['estacion']." ";
						break;
						case 'MVH':
							$mensaje = "EL PACIENTE SE ENCUENTRA HOSPITALIZADO EN LA HABITACION ".$this->paciente[$i]['pieza'].", CAMA ".$this->paciente[$i]['cama']."DE URGENCIAS, LA ESTACIÓN: ".$this->paciente[$i]['estacion']." ";
						break;
						case 'CUE':
							$this->arreglo["ingreso"] = $this->paciente[$i]['ingreso'];
							$this->arreglo["cuenta"] = $this->paciente[$i]['numerodecuenta'];							
							
							$mensaje  = "<table width=\"100%\" class=\"label\" bordercolor=\"#FFFFFF\" border=\"1\" rules=\"cells\">\n";
							$mensaje .= "		<tr>\n";
							$mensaje .= "			<td width=\"33%\"><b class=\"label_mark\">CUENTA: ".$this->paciente[$i]['numerodecuenta']."</b></td>\n";
							
							if($this->paciente[$i]['estado'] == "1")
							{

								$action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','ElegirEstacionPA',$this->arreglo);
								$mensaje .= "			<td align=\"center\"><b><a href=\"".$action1."\" title=\"ASIGNAR A UNA ESTACIÓN\">ESTACIÓN</a></b></td>\n";
								
								$action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','ElegirEstacionMC',$this->arreglo);
								$mensaje .= "			<td align=\"center\"><b><a href=\"".$action2."\" title=\"ASIGNAR CONSULTA\">CONSULTA</a></b></td>\n";	
							}
							else
							{
								$action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','CerrarIngreso',$this->arreglo);
								$mensaje .= "			<td ><b><a href=\"".$action2."\">CERRAR INGRESO</a></b></td>\n";
							}
							$mensaje .= "		</tr>\n";
							$mensaje .= "</table>\n";
						break; 
					}
					$this->salida .= "			<td class=\"justify\"><b>".$mensaje."</b></td>\n";				
					$this->salida .= "		</tr>\n";
				}
    		$this->salida .= "	</table><br>\n";
			}
			if($this->paso == 1 && !$this->paciente)
			{
				$this->salida .= "<center><b class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS PARA LOS DATOS SOLICITADOS</b></center>\n";		
			}
			
			$this->salida .= "<form name=\"forma\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma donde se elige la estacion a asignar, segun el 
		* punto de admision 
		***********************************************************************************/
		function FormaElegirEstacionPA($op= null)
		{
			$this->salida .= ThemeAbrirTabla('ELEGIR ESTACION A ASIGNAR');
			
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			
			$this->salida .= "	<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";

			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td >ELEGIR ESTACION</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<select name=\"estacion1\" class=\"select\">\n";
			$this->salida .= " 						<option value=\"-1\">---SELECCIONAR---</option>\n";
			
			if($op == null)
			{
				$Estaciones = $this->BuscarEstacionesPA();
			}
			else
			{
				$Estaciones = $this->ObtenerEstaciones(0);
			}
			
			for($i=0; $i<sizeof($Estaciones); $i++)
			{
				$this->salida .="							<option value=\"".$Estaciones[$i][estacion_id].",".$Estaciones[$i][departamento].",".$Estaciones[$i][descripcion]."\">".$Estaciones[$i][descripcion]."</option>\n";
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";

			$this->salida .= "	</table><br>\n";
			
			$this->salida .= "	<table width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "			<form name=\"forma\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\"><br>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= ThemeCerrarTabla();
			
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma donde se elige la estacion y se muestra la 
		* informacion de los tipos de cama que hay disponible
		***********************************************************************************/
		function FormaElegirEstacionOrden()
		{
			$this->salida .= ThemeAbrirTabla('ELEGIR ESTACION');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("Informacion")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td width=\"100%\">\n";
			
			$Estaciones = $this->BuscarEstacionesPA();
			for($i=0; $i<sizeof($Estaciones); $i++)
			{
				$action = ModuloGetURL('app','AdmisionHospitalizacion','user','ContinuarAdmision',
																array("estacion"=>$Estaciones[$i][estacion_id].",".$Estaciones[$i][departamento].",".$Estaciones[$i][descripcion]));
				
				$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_list_oscuro\" >\n";
				$this->salida .= "						<td ><b><a href=\"".$action."\">\n";
				$this->salida .= "							<img src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\" width=\"10\" height=\"10\">&nbsp;";
				$this->salida .= "							".$Estaciones[$i][descripcion]."</a></b></td>\n";
				$this->salida .= "					</tr>\n";
				
				$this->salida .= "					<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "						<td>\n";
				$this->salida .= "							<table class=\"modulo_table_list\" width=\"96%\" align=\"right\">\n";
				$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "									<td width=\"80%\">TIPO DE CAMA</td>\n";
				$this->salida .= "									<td width=\"20%\">DISPONIBILIDAD</td>\n";
				$this->salida .= "								</tr>\n";
				
				$Habitacion = $this->BuscarHabitacion($Estaciones[$i][estacion_id]);
				for($j=0; $j<sizeof($Habitacion); $j++)
				{
					$this->salida .= "								<tr class=\"modulo_list_oscuro\">\n";
					$this->salida .= "									<td>\n";
					$this->salida .= "										<b>".$Habitacion[$j]['descripcion']."</b>\n";
					$this->salida .= "									</td>\n";
					$this->salida .= "									<td align=\"right\">\n";
					$this->salida .= "										<b>".($Habitacion[$j]['total'] - $Habitacion[$j]['ocupadas'])."</b>\n";
					$this->salida .= "									</td>\n";
					$this->salida .= "								</tr>\n";
				}
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n"; 
				$this->salida .= "				</table><br>\n";
			}
			
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
				
			$this->salida .= "	<table width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\"><br>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaRemision()
		{
 			$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - DATOS REMISION');
 			$this->salida .= "<script>\n";
			$this->salida .= "		function acceptDate(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$estilo = " class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\" "; 
				
			$this->salida .= "<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"60%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>FECHA REMISION:</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"".$this->Fecha."\" onkeypress=\"return acceptDate(event)\">\n";
			$this->salida .= "			</td >\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<b>".ReturnOpenCalendario('forma','Fecha','/')."</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>HORA REMISION: </td>\n";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Hora\" size=\"4\" value=\"".$this->Hora."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"Min\" size=\"4\" value=\"".$this->Min."\"  maxlength=\"2\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo >ENTIDAD:</td>";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "				<select name=\"entidad\" class=\"select\">\n";
			$this->salida .= " 					<option value=\"\">-------SELECCIONE-------</option>";
			
			$centro=$this->CentrosRemision();
			for($i=0; $i<sizeof($centro); $i++)
			{
				($centro[$i][centro_remision]== $this->Entidad) ? $sel = "selected":$sel ="";
				  
					$this->salida .="				<option value=\"".$centro[$i][centro_remision]."\" $sel>".$centro[$i][descripcion]."</option>\n";
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td $estilo>No. REMISION:</td>";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"2\">\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"remision\" value=\"".$this->Remision."\" maxlength=\"20\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$mostrar=ReturnClassBuscador('diagnostico','','','forma');
			$this->salida .=$mostrar;
			$this->salida .= "	</script>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" colspan=\"3\">DIAGNOSTICO: </td>\n";
			$this->salida .= "			<input type=\"hidden\" name=\"codigo\" size=\"6\" class=\"input-text\" value=\"".$this->Codigo."\">\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<textarea style=\"width:100%\" rows=\"3\" class=\"textarea\" name=\"cargo\" READONLY>".$this->Cargo."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";			
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<input type=\"button\" name=\"buscar\" value=\"Buscar Diagnostico\" onclick=abrirVentana() class=\"input-submit\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" colspan=\"3\">OBSERVACIONES: </td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";			
			$this->salida .= "			<td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "				<textarea style=\"width:100%\" rows=\"3\" class=\"textarea\"name=\"Observacion\">".$this->Observacion."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr align=\"center\">\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"2%\"></td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		<form name=\"forma2\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "			<td colspan=\"3\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";

    	$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaBuscarPacientesModificar()
		{
			$this->salida .= ThemeAbrirTabla('ADMISIONES - BUSCAR DATOS ADMISION');
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function limpiarCampos(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.nombres.value = \"\";\n";
			$this->salida .= "			objeto.apellidos.value = \"\";\n";
			$this->salida .= "			objeto.Ingreso.value = \"\";\n";
			$this->salida .= "			objeto.prefijo.value = \"\";\n";
			$this->salida .= "			objeto.historia.value = \"\";\n";
			$this->salida .= "			objeto.Documento.value = \"\";\n";
			$this->salida .= "			objeto.TipoDocumento.selectedIndex='CC';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("Informacion")."\n";
			$this->salida .= "</table>\n";
			$estilo = " style=\"text-align:left;text-indent:11pt\" ";
			
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table class=\"modulo_table_list\" border=\"0\" width=\"60%\" align=\"center\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td $estilo>TIPO DOCUMENTO: </td>\n";
			$this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "      	<select name=\"TipoDocumento\" class=\"select\">\n";
				
			$tipo_id = $this->TipoIdPaciente();
			foreach($tipo_id as $value=>$titulo)
			{
				($value == $this->TipoId)? $sel = "selected":$sel="";
 
				$this->salida .="					<option value=\"$value\" $sel>$titulo</option>\n";  
				
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td $estilo>DOCUMENTO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$this->Documento."\"></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >NOMBRES: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$this->Nombres."\" size=\"40\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >APELLIDOS: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"apellidos\" value=\"".$this->Apellidos."\" size=\"40\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td $estilo>No. INGRESO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Ingreso\" maxlength=\"32\" value=\"".$this->Ingreso."\" onKeypress=\"return acceptNum(event);\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td $estilo>NÚMERO HISTORIA</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"prefijo\" maxlength=\"32\" size=\"6\" value=\"".$this->Prefijo."\">&nbsp;";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"historia\" maxlength=\"32\" value=\"".$this->Historia."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";	
			$this->salida .= "			<td align=\"center\" >\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.formabuscar)\">\n";

			$this->salida .= "			</td>\n";
			
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";	

			if($this->pacientes)
			{
				$this->salida .= "<br>";
				$this->salida .= "	<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"10%\">No. INGRESO</td>\n";
				$this->salida .= "			<td width=\"10%\">No. CUENTA</td>\n";
				$this->salida .= "			<td width=\"15%\">IDENTIFICACION</td>\n";
				$this->salida .= "			<td >PACIENTE</td>\n";
				$this->salida .= "			<td width=\"10%\">ESTADO</td>\n";
				$this->salida .= "			<td width=\"10%\">HISTORIA</td>\n";
				$this->salida .= "			<td width=\"10%\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				for($i=0;$i<sizeof($this->pacientes);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro';	$background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';	$background = "#DDDDDD";
					}									
					$actionH = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoModificarAdmision',
																	 array('TipoId'=>$this->pacientes[$i]['tipo_id_paciente'],'PacienteId'=>$this->pacientes[$i]['paciente_id'],
																	 			 'Nivel'=>$this->pacientes[$i]['nivel'],'PlanId'=>$this->pacientes[$i]['plan_id'],
																	 			 'Ingreso'=>$this->pacientes[$i]['ingreso']));
												
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td align=\"center\">".$this->pacientes[$i]['ingreso']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$this->pacientes[$i]['numerodecuenta']."</td>\n";
					$this->salida .= "			<td>".$this->pacientes[$i]['tipo_id_paciente']." ".$this->pacientes[$i]['paciente_id']."</td>\n";
					$this->salida .= "			<td>".$this->pacientes[$i]['nombres']." ".$this->pacientes[$i]['apellidos']."</td>\n";
					$this->salida .= "			<td align=\"center\"><b>".$this->pacientes[$i]['estado']."</b></td>\n";
					$this->salida .= "			<td>".$this->pacientes[$i]['historia_prefijo']." ".$this->pacientes[$i]['historia_numero']."</td>\n";
					$this->salida .= "			<td align=\"center\"><b><a href=\"".$actionH."\">CONSULTAR</a></b></td>\n";
					$this->salida .= "		</tr>\n";
				}
				
				$this->salida .= " </table><br>\n";
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaA,$this->action0);
				$this->salida .= "		<br>\n";

			}
			if($this->paso == 1 && !$this->pacientes)
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN RESULTADO PARA LOS DATOS SOLICITADOS</b></center><br>\n";		
			}
				
			$this->salida .= "<form name=\"formavolver\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";	
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br></td></form>";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			
				$this->salida .= ThemeCerrarTabla();
				return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaModificarAdmision()
		{
 			$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS- DATOS DE PACIENTE');
			$this->salida .= "<script>\n";
			$this->salida .= "		function acceptDate(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= " ".$this->paciente;

			$dat = $this->BuscarDatosIngresoPaciente($this->Ingreso);
			$datos = $this->BuscarPlanes($this->PlanId,$this->Ingreso);
			
			$this->salida .= "<form name=\"formai\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "	<input type=\"hidden\" name=\"SwPlan\" value=\"".$this->sw_tipo_plan."\">";
			$this->salida .= "	<input type=\"hidden\" name=\"PolizaAnt\" value=\"".$dat['poliza']."\">\n";
			$this->salida .= "	<table border=\"0\"  width=\"68%\" align=\"center\" class=\"modulo_table_list\" >\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"20\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"25%\" >RESPONSABLE: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "    		".$datos['nombre_tercero']." ".$datos['plan_descripcion']."\n";
			$this->salida .= "    	</td>\n";
			$this->salida .= "    </tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">FECHA INGRESO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" maxlength =\"10\" name=\"FechaIngreso\" value=\"".$dat['fecha_ingreso']."\" onkeypress=\"return acceptDate(event)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\" width=\"50%\">\n";
			$this->salida .= "				<b>".ReturnOpenCalendario('formai','FechaIngreso','/')."</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			if($this->sw_tipo_plan == '1')
			{
				$this->salida .= "	<input type=\"hidden\" name=\"TipoAfiliado\" value=\"".$dat['tipo_afiliado_id']."\">";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "	 		<td style=\"text-align:left;text-indent:11pt\">POLIZA: </td>\n";
				$this->salida .= "	  	<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
				$this->salida .= "	  		<input type=\"text\" class=\"input-text\" name=\"Poliza\" value=\"".$dat['poliza']."\">\n";
				$this->salida .= "	  	</td>\n";
				$this->salida .= "		</tr>\n";
			}
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">VIA INGRESO: </td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "				<select name=\"ViaIngreso\" class=\"select\">\n";
			$this->salida .= "					<option value=\"-1\">---SELECCIONAR---</option>\n";
			
			$Vias = $this->BuscarViaIngreso();
			foreach($Vias as $value=>$titulo)
			{
				($value == $dat['via_ingreso_id'])? $sel = "selected": $sel = "";
				$this->salida .="							<option value=\"$value\" $sel>$titulo</option>\n";
			}
			
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>";
			$this->salida .= "		</tr>";
			
			$Niveles = $this->BuscarNiveles($this->PlanId);
			$Afiliado = $this->BuscarTipoAfiliado($this->PlanId);
			
			if($this->sw_tipo_plan!='1' && $this->sw_tipo_plan!='2')
			{		
					$this->salida .= "		      <tr class=\"modulo_table_list_title\" height=\"20\">";
					$this->salida .= "						<td style=\"text-align:left;text-indent:11pt\" width=\"25%\">TIPO AFILIADO:</td>\n";
					$this->salida .= "						<td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">\n";
					if(sizeof($Afiliado) > 1)
					{
						$this->salida .= "							<select name=\"TipoAfiliado\" class=\"select\">\n";
						$this->salida .= "								<option value=\"-1\">---SELECCIONAR---</option>\n";
						
						for($i=0; $i<sizeof($Afiliado); $i++)
						{
							($Afiliado[$i]['tipo_afiliado_id'] == $dat['tipo_afiliado_id'])? $sel = "selected": $sel="";
					 		$this->salida .="									<option value=\"".$Afiliado[$i]['tipo_afiliado_id']."\" $sel>".$Afiliado[$i]['tipo_afiliado_nombre']."</option>\n";
						}
						$this->salida .= "							</select>\n";
					}
					else
					{
						$this->salida .= "							<input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$Afiliado[0]['tipo_afiliado_id']."\">\n";
						$this->salida .= "							".$Afiliado[0]['tipo_afiliado_nombre']."\n";
					}
					$this->salida .= "						</td>\n";
					$this->salida .= "				 		<td style=\"text-align:left;text-indent:11pt\" width=\"25%\">RANGO: </td>\n";
					$this->salida .= "						<td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">\n";

					if(sizeof($Nivel) > 1)
					{
						$this->salida .= "						<select name=\"Nivel\" class=\"select\">\n";
						$this->salida .= "							<option value=\"-1\">---SELECCIONAR---</option>\n";
							
						for($i=0; $i<sizeof($Niveles); $i++)
						{
							($Niveles[$i]['rango'] == $this->Nivel)? $sel = "selected": $sel = "";
							$this->salida .="								<option value=\"".$Niveles[$i]['rango']."\" $sel>".$Niveles[$i]['rango']."</option>\n";
						}
						$this->salida .= "							</select>\n";
					}
					else
					{
						$this->salida .= "						<input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$Niveles[0]['rango']."\">\n";
						$this->salida .= "						".$Niveles[0]['rango']."\n";

					}
					$this->salida .= "					</td>\n";
					$this->salida .= "				</tr>\n";
				}
				else
				{
					$this->salida .= "<input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$Niveles[0]['rango']."\">\n";
					$this->salida .= "<input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$Afiliado[0]['tipo_afiliado_id']."\">\n";
				}
				
				$this->salida .= "		    <tr class=\"modulo_table_list_title\" height=\"20\">";
				$this->salida .= "					<td colspan=\"4\">COMENTARIOS</td>\n";
				$this->salida .= "		  	</tr>\n";
				$this->salida .= "		    <tr class=\"modulo_list_claro\">\n";
				$this->salida .= "        	<td colspan=\"4\">\n";
				$this->salida .= "        		<textarea name=\"Comentario\" style=\"width:100%\" rows=\"3\" class=\"textarea\">".$dat['comentario']."</textarea>\n";
				$this->salida .= "		   		</td>";
				$this->salida .= "		  	</tr>\n";
				$this->salida .= "			</table><br>\n";
				
				$this->salida .= "<table border=\"0\" width=\"68%\" align=\"center\">\n";
				$this->salida .= "	<tr align=\"center\">\n";
				$this->salida .= "    	<td>\n";
				$this->salida .= "      	<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Modificar Admision\">\n";
				$this->salida .= "      </td>";
				$this->salida .= "		</form>\n";
				$this->salida .= "  	<form name=\"formac\" action=\"".$this->actionC."\" method=\"post\">\n";	
				$this->salida .= "  		<td>\n";
				$this->salida .= "      	<input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"Cambiar Identificacion\">\n";
				$this->salida .= "      </td>\n";
				$this->salida .= "  	</form>\n";			
				$this->salida .= "  	<form name=\"formau\" action=\"".$this->actionU."\" method=\"post\">\n";
				$this->salida .= "  		<td>\n";
				$this->salida .= "      	<input class=\"input-submit\" type=\"submit\" name=\"Unificar\" value=\"Unificar Historias\">\n";
				$this->salida .= "      </td>\n";
				$this->salida .= "    </form>\n";
				
				$this->salida .= "  	<form name=\"formad\" action=\"".$this->action3."\" method=\"post\">\n";
				$this->salida .= "  		<td>\n";
				$this->salida .= "      	<input class=\"input-submit\" type=\"submit\" name=\"Remision\" value=\"".$this->Boton."\">\n";
				$this->salida .= "      </td>\n";
				$this->salida .= "    </form>\n";
				
				$this->salida .= "	</tr>\n";
				$this->salida .= "  <tr align=\"center\">\n";
				$this->salida .= "  	<form name=\"formacan\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "    	<td colspan = \"4\">\n";
				$this->salida .= "      	<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">\n";
				$this->salida .= "      </td>\n";
				$this->salida .= "    </form>\n";
				$this->salida .= "  </tr>\n";
				$this->salida .= "</table>\n";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaListarPacientesTriages()
		{
			$this->salida .= ThemeAbrirTabla('ADMISION URGENCIAS - LISTADO DE PACIENTES ADMISIONES');
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
			$this->salida .= "<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "	".$this->SetStyle("Informacion")."\n";
			$this->salida .= "</table>\n";
			
			$paciente = $this->BuscarPacientesTriages();
			if(sizeof($paciente) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr align=\"center\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>PACIENTE</td>\n";
				$this->salida .= "			<td width=\"11%\">DOCUMENTO</td>\n";
				$this->salida .= "			<td width=\"9%\" >F. INGRESO</td>\n";
				$this->salida .= "			<td width=\"9%\" >H. INGRESO</td>\n";
				$this->salida .= "			<td width=\"7%\" >NIVEL</td>\n";
				$this->salida .= "			<td width=\"7%\" >NIVEL ASIS.</td>\n";
				$this->salida .= "			<td width=\"23%\">MOTIVO CONSULTA</td>\n";
				$this->salida .= "			<td width=\"12%\" colspan=\"2\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				for($i=0; $i<sizeof($paciente); $i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro';	$background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';	$background = "#DDDDDD";
					}	
					;
					($paciente[$i]['nivel_triage_id'] == 0)? $adm=true: $adm=false;
					$action = ModuloGetURL('app','AdmisionHospitalizacion','user','AdmitirPaciente',
																	array('TipoId'=>$paciente[$i]['tipo_id_paciente'],
																				'Triage'=>$paciente[$i]['triage_id'],'PacienteId'=>$paciente[$i]['paciente_id'],
																				'Responsable'=>$paciente[$i]['plan_id'],'Nivel'=>$adm));
					
					($paciente[$i]['nivel_triage_id'])? $nivel = $paciente[$i][nivel_triage_id]: $nivel='SIN CLASIFICAR';
						
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
						
					$this->salida .= "				<td>".$paciente[$i]['nombres']." ".$paciente[$i]['apellidos']."</td>\n";
					$this->salida .= "				<td>".$paciente[$i]['tipo_id_paciente']." ".$paciente[$i]['paciente_id']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$paciente[$i]['hora_llegada']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$paciente[$i]['fecha_ingreso']."</td>\n";
					$this->salida .= "				<td align=\"center\"><b>$nivel</b></td>\n";
					$this->salida .= "				<td align=\"center\">".$paciente[$i]['nivel_triage_asistencial']."</td>\n";
					$this->salida .= "				<td align=\"justify\" >".ucfirst($paciente[$i]['motivo_consulta'])."</td>\n";
						
					if(empty($paciente[$i]['punto_admision_id']))
					{
						$this->salida .= "				<td align=\"center\" width=\"6%\">&nbsp;</td>\n";
					}
					else
					{
						$this->salida .= "				<td align=\"center\"  width=\"6%\"><a href=\"".$action."\"><b>ADMITIR</b></a></td>\n";
					}

					$action2 = ModuloGetURL('app','AdmisionHospitalizacion','user','ExcluirPacienteLista',
																	 array('TipoId'=>$paciente[$i]['tipo_id_paciente'],
																				 'Triage'=>$paciente[$i]['triage_id'],
																				 'PacienteId'=>$paciente[$i]['paciente_id'],
																				 'Nombre'=>$paciente[$i]['nombres']." ".$paciente[$i]['apellidos']));
					$this->salida .= "				<td align=\"center\"  width=\"6%\"><a href=\"".$action2."\"><b>SACAR</b></a></td>";
				}
				$this->salida .= "	</table><br>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaA,$this->action0);
				$this->salida .= "		<br>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO HAY PACIENTES POR ATENDER</b></center><br>\n";			
			}
	
			$this->salida .= "<table width=\"25%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= " 			<td align=\"center\">\n";
			$this->salida .= " 				<input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"Volver\">\n";
			$this->salida .= " 			</td>\n";
			$this->salida .= " 		</form>\n";
			$this->salida .= "  	<form name=\"formabuscar\" action=\"".$this->action0."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= " 				<input class=\"input-submit\" type=\"submit\" name=\"Actualizar\" value=\"Actualizar\">\n";
			$this->salida .= " 			</td>\n";
			$this->salida .= " 		</form>\n";
			$this->salida .= " 	</tr>\n";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaExcluirPacienteLista()
		{
			$this->salida .= ThemeAbrirTabla('EXCLUIR PACIENTE DEL LISTADO');
			$this->salida .= "<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "	".$this->SetStyle("Informacion")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"formabuscar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">OBSERVACION: </td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				<textarea style=\"width:100%\" rows=\"3\" class=\"textarea\"name=\"observacion\"></textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "    <form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\"></td>";
			$this->salida .= "		</form>\n";
			$this->salida .= "    </tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaAdmitirTriage()
		{
			$this->salida .= ThemeAbrirTabla('ADMISIONES - ELEGIR PUNTO TRIAGE');
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "	<form name=\"formapuntos\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "		<table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" align=\"center\">\n";
			$this->salida .= "					ELIJA EL PUNTO DE TRIAGE AL QUE VA A REMITIR EL PACIENTE\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td>PUNTO TRIAGE:</td>\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" >\n";
			$this->salida .= "					<select name=\"punto_triage\" class=\"select\">\n";
			$this->salida .= "          	<option value=\"-1\">------SELECCIONE------</option>\n";
			for($i=0; $i<sizeof($this->Puntos); $i++)
			{
				$this->salida .= "            <option value=\"".$this->Puntos[$i]['punto_triage_id']."ç".$this->Puntos[$i]['descripcion']."\">".$this->Puntos[$i]['descripcion']."</option>\n";
			}
			$this->salida .= "          </select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "		<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\">\n";
			$this->salida .= "	  	<tr align=\"center\">\n";
			$this->salida .= "	  			<td>\n";
			$this->salida .= "						<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
			$this->salida .= "        	</td>\n";
			$this->salida .= "    		</form>\n";
			$this->salida .= "    		<form name=\"cancelar\" action=\"".$this->action[1]."\" method=\"post\">\n";
			$this->salida .= "	  			<td>\n";
			$this->salida .= "	  				<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">\n";
			$this->salida .= "	  			</td>\n";
			$this->salida .= "    		</form>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function BuscadorPacientes()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function limpiarCampos(objeto)\n";
			$buscador .= "		{\n";
			$buscador .= "			objeto.documento.value = \"\";\n";
			$buscador .= "			objeto.fecha_fin.value = \"\";\n";
			$buscador .= "			objeto.fecha_inicio.value = \"\";\n";
			$buscador .= "			objeto.tipo_id.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "		function acceptDate(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr><td class=\"label\">TIPO DOCUMENTO:</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"tipo_id\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			$TiposTerceros = $this->ObtenerTipoId();
			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$selected = "";
				$opciones = explode("/",$TiposTerceros[$i]);
				if($this->PacienteTipoId == $opciones[0])
				{
					$selected = " selected ";
				}
				$buscador .= "						<option value='".$opciones[0]."' $selected >".ucwords(strtolower($opciones[1]))."</option>\n";			
			}
			
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";	
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"documento\" size=\"30\" maxlength=\"32\" value=\"".$this->PacienteDocumento."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">FECHA PROGRAMACIÓN</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaInicio."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaFin."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_fin','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td colspan=\"5\" align=\"center\">\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			$buscador .= "<form name=\"buscador2\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<table align=\"center\">\n";			
			$buscador .= "		<tr>\n";
			$buscador .= "			<td align=\"center\">\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Todas Las Ordenes\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "		</tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n";
			
			return $buscador;  
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del b¡uscador rapido de facturas  
		* 
		* @return string  
		**********************************************************************************************/
		function BuscadorOrdenes()
		{
			$buscador  = "<form name=\"buscadorordenes\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
			$buscador .= "				BUSCADOR RAPIDO DE ORDENES:\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td><b>Nº ORDEN:</b></td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"numero_orden\" size=\"25\" maxlength=\"100\" value=\"".$this->NumeroOrden."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del b¡uscador rapido de facturas  
		* 
		* @return string  
		**********************************************************************************************/
		function BuscadorProgramaciones()
		{
			$buscador  = "<form name=\"buscadorordenes\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function acceptNum(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
			$buscador .= "				BUSCADO DE PROGRAMACIONES:\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td><b>Nº PROGRAMACION:</b></td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"programacion\" size=\"25\" maxlength=\"100\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Programacion."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*********************************************************************************************
		* Muestra el nombre del tercero con sus respectivos planes
		* @access private
		* @return string
		* @param array arreglor con los tipos de responsable
		* @param int el responsable que viene por defecto
		**********************************************************************************************/
 		function MostrarResponsable($resp,$Responsable)
 		{
			$option  =" <option value=\"-1\">-------SELECCIONAR-------</option>\n";
			for($i=0; $i<sizeof($resp); $i++)
			{
				($resp[$i][plan_id] == $Responsable)? $sel = "selected": $sel = "";
			
				$option .=" <option value=\"".$resp[$i][plan_id]."\" $sel>".$resp[$i][plan_descripcion]."</option>\n";
			}
			return $option;
 		}
	}
?>