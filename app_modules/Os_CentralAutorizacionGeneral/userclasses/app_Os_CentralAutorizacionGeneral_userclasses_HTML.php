<?php
	/*********************************************************************************************
	* $Id: app_Os_CentralAutorizacionGeneral_userclasses_HTML.php,v 1.2 2007/04/23 20:19:49 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class app_Os_CentralAutorizacionGeneral_userclasses_HTML extends app_Os_CentralAutorizacionGeneral_user
	{
		function app_Os_CentralAutorizacionGeneral_userclasses_HTML(){}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMain()
		{
			$rst = $this->Main();
			if($rst == true)
			{
				$url[0] = 'app';
				$url[1] = 'Os_CentralAutorizacionGeneral';
				$url[2] = 'user';
				$url[3] = 'FormaMenuPrincipal';
				$url[4] = 'Atencion';
				$arreglo[0] = 'EMPRESA';
				$this->salida = gui_theme_menu_acceso('CUENTAS',$arreglo,$this->empresa,$url,ModuloGetURL('system','Menu'));
			}
			else
			{
				$this->FormaMenuPrincipal();
			}
			return true;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMenuPrincipal()
		{
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			
			$this->MenuPrincipal();
			$agh = new AutorizacionGeneralHTML();
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			
			$this->salida .= ThemeAbrirTabla('CENTRAL DE AUTORIZACIONES');
			$this->salida .= "<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<div class=\"tab-pane\" id=\"autorizacion\">\n";
			$this->salida .= "				<script>	tabPane = new WebFXTabPane( document.getElementById( \"autorizacion\" ), false); </script>\n";
			$this->salida .= "				<div class=\"tab-page\" id=\"buscar\">\n";
			$this->salida .= "					<h2 class=\"tab\">BUSCAR PACIENTE</h2>\n";
			$this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"buscar\")); </script>\n";
			$this->salida .= $agh->FormaBuscador($this->action,$this->request['buscador'],$this->request['offset']);
			$this->salida .= "				</div>\n";
			$this->salida .= "				<div class=\"tab-page\" id=\"solicitar\">\n";
			$this->salida .= "					<h2 class=\"tab\">SOLICITUDES MANUALES</h2>\n";
			$this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"solicitar\")); </script>\n";
			$this->salida .= $agh->FormaSolicitudManual($this->action,$this->permisos);
			$this->salida .= "				</div>\n";
			$this->salida .= "			</div>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "	<table align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<script type=\"text/javascript\">\n";
			$this->salida .= "	setupAllTabs();\n";
			if($this->request['grupo'])
				$this->salida .= "	tabPane.setSelectedIndex(".$this->request['grupo'].");";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaDatosAutorizacionOs()
		{
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			$aosh = new AutorizacionGeneralHTML();
			$this->DatosAutorizacionOs();
			
			$this->salida .= ThemeAbrirTabla('DATOS AUTORIZACIONES');
			$this->salida .= $aosh->FormaCargosAutorizar($this,$this->action,$this->request['paciente']);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaAnulacionSolicitud()
		{
			$this->AnulacionSolicitud();
			
			$this->salida .= ThemeAbrirTabla('ANULAR SOLICITUD No. '.$this->request['hc_os_solicitud']);
			$this->salida .= "<script>\n";
			$this->salida .= "	function EvaluarDatos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(frm.observacion.value == \"\")\n";
			$this->salida .= "			document.getElementById('error').innerHTML = 'SE DEBE INGRESAR UNA OBSERVACION, POR LA CUAL SE ANULA LA SOLICITUD';\n";
			$this->salida .= "		else\n";
			$this->salida .= "			frm.submit();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"forma\" action=\"".$this->action['aceptar']."\" method=\"post\">\n";
			$this->salida .= "	<div style=\"text-align:center\" class=\"label_error\" id=\"error\"></div>\n";
			$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\" width=\"80%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>OBSERVACIONES ANULACION: </td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<textarea style=\"width:100%\" rows=\"4\" class=\"textarea\" name=\"observacion\"></textarea>\n";
			$this->salida .= "				<input type=\"hidden\" name=\"hc_os_solicitud_id\" value=\"".$this->request['hc_os_solicitud']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table align=\"center\" border=\"0\" width=\"50%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td  align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"Aceptar\" type=\"button\" onclick=\"EvaluarDatos(document.forma)\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td  align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"button\" onclick=\"window.close()\" value=\"Cerrar\">\n";
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
		function FormaAnularSolicitud()
		{
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			$aosh = new AutorizacionGeneralHTML();
			
			$this->AnularSolicitud();
			$this->salida .= $aosh->FormaMensaje("CONFIRMACIÓN",$this->frmError['MensajeError'],"center",$this->action['aceptar']);
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaAutorizarCargos()
		{
			IncludeClass('AutorizacionesHTML','','app','NCAutorizaciones');
			IncludeClass('AtencionOsHtml','','app','Os_CentralAtencion');
			
			$this->AutorizarCargos();
			
			$aosh = new AtencionOsHtml();
			$auhtml = new AutorizacionesHTML();
			
			$Autoriza = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
			
			$Autoriza->SetActionVolver($this->action['volver']);
			$Autoriza->SetActionAceptar($this->action['aceptar']);
			if(!$Autoriza->SetClaseAutorizacion('OS'))
			{
				IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
				$aosh = new AutorizacionGeneralHTML();
				
				$this->salida .= $aosh->FormaMensaje("ERROR",$Autoriza->frmError['MensajeError'],"center",$this->action['volver']);
				return true;
			}
			
			$rst = $Autoriza->ValidarAdmisionHospitalizacion($this->paciente,$this->request['cargos'][$this->paciente['plan_id']]);
			
			if($Autoriza->automatico == true)
				$Autoriza->FormaMensajeError('MENSAJE','center',$Autoriza->action['aceptar']);
			else
				$Autoriza->FormaMostrarDatosIngreso($Autoriza->datos,$this->request['cargos'][$this->paciente['plan_id']]);
			
			$this->salida = $Autoriza->salida;
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaMostrarCargosOS()
		{
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			$this->MostrarCargosOS();
			$aosh = new AutorizacionGeneralHTML();
			
			$this->salida .= ThemeAbrirTabla('CARGOS ORDENES DE SERVICIO');
			$this->salida .= $aosh->FormaCargosAutorizados($this,$this->action,$this->auto,$this->cargos);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaCrearOrdenesServicio()
		{
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			$aosh = new AutorizacionGeneralHTML();
				
			$rst = $this->CrearOrdenesServicio();
			if(!$rst)
				$this->salida .= $aosh->FormaMensaje('MENSAJE ERROR',$this->frmError['MensajeError'],"center",$this->action['aceptar']);
			else
				$this->salida .= $aosh->FormaMensaje('MENSAJE',$this->frmError['MensajeError'],"center",$this->action['aceptar']);
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaListarOrdenes()
		{
			IncludeClass('ConsultaAutorizacionGrlHTML','','app','Os_CentralAutorizacionGeneral');
			
			$this->ListarOrdenes();
			$cnsh = new ConsultaAutorizacionGrlHTML();
			
			$this->salida .= ThemeAbrirTabla('LISTADO ORDENES DE SERVICIO');
			$this->salida .= $cnsh->FormaListaOrdenes($this->request['paciente'],$this->action);
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaCambiarFechas()
		{
			$rst = $this->CambiarFechas();
			if(!$rst)
			{
				$this->salida .= ThemeAbrirTabla('CAMBIAR FECHA - ORDEN SERVICIO No. '.$this->request['orden_servicio_id']);
				$this->salida .= "<script>\n";
				$this->salida .= "	function EvaluarDatos(frm)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		if(frm.observacion.value == \"\")\n";
				$this->salida .= "			document.getElementById('error').innerHTML = 'SE DEBE INGRESAR UNA OBSERVACION, POR LA CUAL SE ANULA LA SOLICITUD';\n";
				$this->salida .= "		else\n";
				$this->salida .= "			frm.submit();\n";
				$this->salida .= "	}\n";
				$this->salida .= "</script>\n";
				$this->salida .= "<form name=\"forma\" action=\"".$this->action['aceptar']."\" method=\"post\">\n";
				
				if($this->frmError['MensajeError'])
					$this->salida .= "	<div style=\"text-align:center\" class=\"label_error\" id=\"error\">".$this->frmError['MensajeError']."</div>\n";
				
				$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td style=\"text-indent:2pt; text-align:left\">FECHA ACTIVACION: </td>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"2\" height=\"16\">\n";
				$this->salida .= "				".$this->request['activacion']."\n";
				$this->salida .= "				<input type=\"hidden\" name=\"activacion\" value=\"".$this->request['activacion']."\" >\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"16\">\n";
				$this->salida .= "			<td style=\"text-indent:2pt; text-align:left\">REFRENDAR: </td>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"2\">\n";
				$this->salida .= "				".$this->request['refrendar']."\n";
				$this->salida .= "				<input type=\"hidden\" name=\"refrendar\" value=\"".$this->request['refrendar']."\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";				
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"16\">\n";
				$this->salida .= "			<td style=\"text-indent:2pt;text-align:left\">FECHA VENCIMIENTO: </td>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" >\n";
				$this->salida .= "				<input class=\"input-text\" type=\"text\" name=\"vencimiento\" value=\"".date("d/m/Y")."\" size=\"13\"	maxlength=\"12\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\">\n";
				$this->salida .= "				".ReturnOpenCalendario('forma','vencimiento','/')."\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";

				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<input type=\"hidden\" name=\"orden_servicio_id\" value=\"".$this->request['orden_servicio_id']."\">\n";
				$this->salida .= "	<table align=\"center\" border=\"0\" width=\"50%\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td  align=\"center\">\n";
				$this->salida .= "				<input class=\"input-submit\" name=\"aceptar\" type=\"submit\" value=\"Aceptar\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td  align=\"center\">\n";
				$this->salida .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"button\" onclick=\"window.close()\" value=\"Cerrar\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n";
				$this->salida .= ThemeCerrarTabla();
			}
			else
			{
				IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
				$aosh = new AutorizacionGeneralHTML();
			
				$this->salida .= $aosh->FormaMensaje("CONFIRMACIÓN",$this->frmError['MensajeError'],"center",$this->action['aceptar'],null,"100%");
			}
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaAnularOrdenes()
		{
			IncludeClass('ConsultaAutorizacionGrlHTML','','app','Os_CentralAutorizacionGeneral');
			
			$this->AnularOrdenes();
			$aosh = new ConsultaAutorizacionGrlHTML();
			
			$this->salida .= ThemeAbrirTabla('ANULAR ORDEN DE SERVICIO Nº '.$this->request['orden_servicio_id']);
			$this->salida .= $aosh->FormaAnularOrden($this->request,$this->action);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaRegistrarAnulacionOrden()
		{
			$this->RegistrarAnulacionOrden();
			IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
			$aosh = new AutorizacionGeneralHTML();
			
			$this->salida .= $aosh->FormaMensaje("MENSAJE",$this->frmError['MensajeError'],"center",$this->action['aceptar'],null,"80%");
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaOrdenesVencidas()
		{
			IncludeClass('ConsultaAutorizacionGrlHTML','','app','Os_CentralAutorizacionGeneral');
			
			$this->OrdenesVencidas();
			$cnsh = new ConsultaAutorizacionGrlHTML();

			$this->salida .= ThemeAbrirTabla('LISTADO ORDENES DE SERVICIO VENCIDAS');
			$this->salida .= $cnsh->FormaListaOrdenesVencidas($this->request['paciente'],$this->action);
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaSolicitudesManuales()
		{
			$rst = $this->SolicitudesManuales();
			switch($rst)
			{
				case '1':	$this->FormaSeleccionarEventoSoat(); break;
				case '2':	$this->FormaDatosPaciente(); break;
			}
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaDatosPaciente()
		{
			SessionDelVar("CargosAdicionados");
			$this->DatosPaciente();
			$pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			
			$pct->SetActionVolver($this->action['volver']);
			$pct->FormaDatosPaciente($this->action);
			
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaIngresarCargos()
		{
			$this->IngresarCargos();
			IncludeClass('SolicitudManualHTML','','app','Os_CentralAtencion');
			$slm = new SolicitudManualHTML();

			$this->SetXajax(array("BuscarCargos","AdicionarCargo","AdicionarEquivalencia","EliminarCargo"),"app_modules/Os_CentralAtencion/RemoteXajax/Solicitud.php");
			$this->salida .= $slm->FormaDatosSolicitud($this->request,$this->action,$this->depart);
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaSeleccionarEventoSoat()
		{
			$this->SeleccionarEventoSoat();
			if(!empty($this->paciente))
			{
				IncludeClass('ConsultaAutorizacionGrlHTML','','app','Os_CentralAutorizacionGeneral');
				$clt = new ConsultaAutorizacionGrlHTML();
				$this->salida = $clt->FormaMostrarEventosSoat($this->action,$this->paciente,$this->request);
			}
			else
			{
				IncludeClass('AutorizacionGeneralHTML','','app','Os_CentralAutorizacionGeneral');
				$aosh = new AutorizacionGeneralHTML();
				
				$mensaje = "EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS, SE RECOMIENDA REALIZAR EL INGRESO CORRESPONDIENTE.";
				$this->salida .= $aosh->FormaMensaje("MENSAJE",$mensaje,"center",$this->action['cancelar']);
			}
			return true;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaCrearSolicitud()
    {
			$rst = $this->CrearSolicitud();
			if($rst === false)
			{
				IncludeClass('AtencionOsHtml','','app','Os_CentralAtencion');
				$aosh = new AtencionOsHtml();

				$this->salida = $aosh->FormaMensaje('ERROR',$this->frmError['MensajeError'],'center',$this->action['cancelar']);
			}
			else
			{
				$this->salida .= "<script>\n";
				$this->salida .= "	location.href = \"".$this->action['aceptar']."\"\n";
				$this->salida .= "</script>\n";
			}
			
			return true;
		}
	}
?>