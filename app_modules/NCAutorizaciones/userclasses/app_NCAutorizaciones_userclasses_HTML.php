<?php
	/**************************************************************************************
	* $Id: app_NCAutorizaciones_userclasses_HTML.php,v 1.4 2007/04/11 13:41:08 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* $Revision: 1.4 $
	*
	* @author Hugo Freddy Manrique
	***************************************************************************************/
	class app_NCAutorizaciones_userclasses_HTML extends app_NCAutorizaciones_user
	{
		function app_NCAutorizaciones_userclasses_HTML(){}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMain()
		{
			$this->Main();
			$this->FormaConsultarAutorizaciones();
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaValidarAutoAdmisionHospitalizacion($datos)
		{
			$rst = $this->ValidarAdmisionHospitalizacion($datos);
			if($rst === false) 
				$this->FormaMensajeError('MENSAJE','left',$this->action['cancelar']);
			else
				$this->FormaMensajeError('MENSAJE','center',$this->action['aceptar'],$this->action['cancelar']);
				
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaCrearAutorizacion($datos,$cargos)
		{
			$this->CrearAutorizacion($datos,$cargos);
			
			IncludeClass('AutorizacionesHTML','','app','NCAutorizaciones');
 			$file = 'app_modules/NCAutorizaciones/RemoteXajax/autorizaciones.php';
			
			$this->SetXajax(array("reqEvaluarAutorizacion"),$file);
			$this->SetJavaScripts('DatosBD');
			
			$authtml = new AutorizacionesHTML();
			
			$this->salida .= ThemeAbrirTabla('AUTORIZACIONES');
			$this->salida .= $authtml->FormaAutorizarPaciente($this->request['plan_id'],$this->request['idp'],$this->request['tipoid'],$this->action,$this->request['afiliados'],$this->request['externo']);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaConsultarAutorizaciones($datos)
		{
			$this->ConsultarAutorizaciones($datos);
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			IncludeClass('ConsultaAutorizacionesHTML','','app','NCAutorizaciones');
			$cnaut = new ConsultaAutorizacionesHTML();
			$this->salida .= ThemeAbrirTabla('CONSULTAR AUTORIZACIONES');
			$this->salida .= $cnaut->FormaConsultarAutorizaciones($this->request,$this->action,$this->buscador,$this);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaIngresarAutorizacion()
		{
			$rst = $this->IngresarAutorizacion();
			if(!$rst)
				$this->FormaMensajeError('MENSAJE','center',$this->action['cancelar']);
			else
				$this->FormaMensajeError('MENSAJE','center',$this->action['aceptar']);

			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMensajeError($titulo,$align,$action1,$action2 = null)
		{
			$this->salida .= ThemeAbrirTabla($titulo);
			$this->salida .= "	<script>\n";
			$this->salida .= "		function CerrarVentana(num_ingreso)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.opener.document.formabuscar.ingreso.value = num_ingreso;\n";
			$this->salida .= "			window.opener.document.formabuscar.submit();\n";
			$this->salida .= "			window.close();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" align=\"".$align."\" colspan=\"3\"><br>";
			$this->salida .= "				".$this->frmError['MensajeError']."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form><br>\n";
			
			if($action2)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$action2."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
				
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMostrarDatosIngreso($datos,$cargos)
		{		
			$cantidad = $this->MostrarDatosIngreso($datos);
			if($cantidad <= 1)
			{
				$this->FormaCrearAutorizacion($this->request['autorizar'],$cargos);
			}
			else if($cantidad > 1)
				{
					IncludeClass('ConsultaAutorizacionesHTML','','app','NCAutorizaciones');
					$clhtml = new ConsultaAutorizacionesHTML();
					
					$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - SELECCIONAR CUENTA');
					$this->salida .= $clhtml->FormaCrearListaCuentas($this->request['autorizar']['ingreso'],$this->action,$this->request['autorizar']);
					$this->salida .= ThemeCerrarTabla();
				}
				else
					return false;
			
			return true;
		}
	}
?>