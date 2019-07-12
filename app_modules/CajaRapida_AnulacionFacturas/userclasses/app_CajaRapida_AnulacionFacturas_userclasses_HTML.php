<?php
	/**************************************************************************************  
	* $Id: app_CajaRapida_AnulacionFacturas_userclasses_HTML.php,v 1.2 2006/05/09 19:44:01 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_CajaRapida_AnulacionFacturas_userclasses_HTML extends app_CajaRapida_AnulacionFacturas_user
	{
		function app_CajaRapida_AnulacionFacturas_userclasses_HTML()
		{
			return true;
		}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
		{
			($this->ObtenerPermisos())? $this->FormaBuscarFacturasAnulacion():$this->FormaInformacion("USUARIO SIN PERMISOS");
			return true;
		}
		/************************************************************************************
		* Funcion donde se mestra el buscador de facturas y su respectivo resultado
		* 
		* @return boolean 
		*************************************************************************************/
		function FormaBuscarFacturasAnulacion()
		{
			$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
			$this->BuscarFacturasAnulacion();			
			$this->salida .= ThemeAbrirTabla("BUSCAR FACTURAS");
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"buscadorfacturas\" action=\"".$this->action[1]."\" method=\"post\">\n";
			$this->salida .= "	<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\" $estilo width=\"25%\">FACTURAS:&nbsp;</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<select name=\"prefijo_factura\" class=\"select\">\n";

			for($i=0; $i<sizeof($this->Pref); $i++)
			{
				($this->request[0] == $this->Pref[$i]['prefijo'])? $sel = "selected": $sel = "";
				$this->salida .= "				<option value='".$this->Pref[$i]['prefijo']."' $sel>".$this->Pref[$i]['prefijo']."</option>\n";
			}
			$this->salida .= "				</select>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"factura_fiscal\" size=\"25\" onkeypress=\"return acceptNum(event)\" value=\"".$this->request[1]."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";

			if($this->Mensaje == "" && sizeof($this->Rta)>0)
			{

				$this->salida .= "<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr $estilo>\n";
				$this->salida .= "		<td width=\"25%\">Nº FACTURA</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" colspan=\"3\">\n";
				$this->salida .= "			".$this->Rta[0]['prefijo']."	".$this->Rta[0]['factura_fiscal']."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr $estilo>\n";
				$this->salida .= "		<td >PLAN</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" colspan=\"3\" >\n";
				$this->salida .= "			".$this->Rta[0]['plan_descripcion']."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr $estilo>\n";
				$this->salida .= "		<td >PACIENTE</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" width=\"25%\">\n";
				$this->salida .= "			".$this->Rta[0]['identificacion']." \n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" colspan=\"2\" >\n";
				$this->salida .= "			".$this->Rta[0]['nombres']." ".$this->Rta[0]['apellidos']."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr $estilo>\n";
				$this->salida .= "		<td width=\"25%\">Nº CUENTA</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\">\n";
				$this->salida .= "			".$this->Rta[0]['numerodecuenta']."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td width=\"25%\">TOTAL CUENTA</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" align=\"right\">\n";
				$this->salida .= "			$".formatoValor($this->Rta[0]['total_cuenta'])."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr $estilo>\n";
				$this->salida .= "		<td >VALOR CUBIERTO</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" align=\"right\" >\n";
				$this->salida .= "			$".formatoValor($this->Rta[0]['valor_cubierto'])."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td >VALOR NO CUBIERTO</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\" align=\"right\" >\n";
				$this->salida .= "			$".formatoValor($this->Rta[0]['valor_nocubierto'])."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>\n";

				if(sizeof($this->Rta[1]) > 0)
				{
					$this->salida .= "<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
					$this->salida .= "	<tr $estilo>\n";
					$this->salida .= "		<td width=\"25%\">Nº FACTURA CREDITO</td>\n";
					$this->salida .= "		<td class=\"modulo_list_claro\" width=\"25%\">\n";
					$this->salida .= "			".$this->Rta[1]['prefijo']."	".$this->Rta[1]['factura_fiscal']."\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "		<td width=\"25%\">TOTAL FACTURA</td>\n";
					$this->salida .= "		<td class=\"modulo_list_claro\" align=\"right\">\n";
					$this->salida .= "			$".formatoValor($this->Rta[1]['total_factura'])."\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
					$this->salida .= "	<tr $estilo>\n";
					$this->salida .= "		<td >EMPRESA</td>\n";
					$this->salida .= "		<td class=\"modulo_list_claro\" colspan=\"3\" >\n";
					$this->salida .= "			".$this->Rta[1]['nombre_tercero']."\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
					$this->salida .= "</table><br>\n";
				}
				
				$this->salida .= "<form name=\"anularfacturas\" action=\"".$this->action[2]."\" method=\"post\">\n";
				$this->salida .= "	<table align=\"center\" width=\"60%\">\n";
				$this->salida .= "		".$this->SetStyle($this->parametro)."\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr $estilo>\n";
				$this->salida .= "			<td width=\"25%\">MOTIVO ANULACIÓN</td>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" >\n";
				
				$motivos = $this->ObtenerMotivosAnulacion();
				$this->salida .= "				<select name=\"motivo_anula\" class=\"select\">\n";
				$this->salida .= "					<option value='0' >----SELECCIONAR----</option>\n";
				for($i=0; $i<sizeof($motivos); $i++)
				{
					($this->request[2] == $motivos[$i]['motivo_anulacion_id'])? $sel = "selected": $sel = "";
					$this->salida .= "					<option value='".$motivos[$i]['motivo_anulacion_id']."' $sel>".$motivos[$i]['motivo_descripcion']."</option>\n";
				}
				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td colspan=\"2\">OBSERVACIÓN</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td colspan=\"2\" align=\"right\">\n";
				$this->salida .= "				<textarea name=\"observacion\" class=\"textarea\" style=\"width:100%\" rows=\"2\">".$this->request[3]."</textarea>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td colspan=\"2\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr $estilo>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"2\">";
				$chk1 = $chk2 = "";
				if($this->request[4] == "0") 
					$chk1 = "checked"; 
				if($this->request[4] == "1")
					$chk2 = "checked";
				
				$this->salida .= "				<input type=\"radio\" name=\"opcion\" value=\"0\" $chk1><b>ANULAR FACTURA</b>\n";
				$this->salida .= "				<input type=\"radio\" name=\"opcion\" value=\"1\" $chk2><b>LIBERAR CUENTA PARA FACTURACIÓN MANUAL</b>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				$this->salida .= "<center><br><b class=\"label_error\">".$this->Mensaje."</b><br></center>\n";
			}
			
			$this->salida .= "<br><table width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			
			if($this->Mensaje == "" && sizeof($this->Rta)>0)
			{	
				$this->salida .= "			<td align=\"center\" id='lll'>\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Anular\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</form>\n";
			}
			
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\" id='lll'>\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @params String $parametro Cadena a mostrar en la forma
		* @return boolean 
		**********************************************************************************/
		function FormaInformacion($parametro)
		{
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$parametro."<br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<form name=\"formaInformacion\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			if($this->action[1])
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action[1]."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************** 
		* Funcion que retorna el mensaje que se desea desplegar en la forma 
		* 
		* @params String $campo Nombre del campo que se evaluara para mostrar el estilo
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function SetStyle($campo)
		{
			if ($this->frmError[$campo])
			{
				if($campo != "")
				{
					$icono = "fallo.png";
					$clase = "label_error";
					if ($campo !="MensajeError" )
					{
						$icono = "infor.png"; $clase = "label";
					}
					
					$mensaje .= "	<tr>\n";
					$mensaje .= "		<td width=\"19\"><img src=\"".GetThemePath()."/images/".$icono."\" border=\"0\"></td>\n";
					$mensaje .= "		<td class=\"$clase\" align=\"justify\">".$this->frmError[$campo]."</td>\n";
					$mensaje .= "	</tr>\n";

					return $mensaje;
				}
				return ("<tr><td>&nbsp;</td></tr>");
			}
			return ("<tr><td>&nbsp;</td></tr>");
		}
		/************************************************************************************
		* Funcion controladora que indica que metodo se debe evaluar
		* 
		* @return boolean
		*************************************************************************************/
		function EvaluarPrincipal()
		{
			switch($_REQUEST['datos']['opcion'])
			{
				case '1':
					$rta1 = $this->AnularFactura();
					if(!$rta1) $this->FormaBuscarFacturasAnulacion();
					else $this->FormaInformacion($this->Mensaje);
				break;
				default:
					$this->main();
				break;
			}
			return true;
		}
	}
?>
