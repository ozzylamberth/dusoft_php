<?php
	/********************************************************************************* 
 	* $Id: system_AdminModulos_userclasses_HTML.php,v 1.15 2007/10/11 20:51:03 hugo Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Hugo F. Manrique. 
	* @version   $Revision: 1.15 $   
	* @package   AdminModulos 
	* 
	* Modulo que permite el manejo de la informacion de los modulos del sistema 
 	**********************************************************************************/
	IncludeClass("ClaseHTML");
	class system_AdminModulos_userclasses_HTML extends system_AdminModulos_user
	{
		
		function system_AdminModulos_userclasses_HTML()
		{
			$this->system_AdminModulos_user();
		}
		
		function Principal()
		{
			$this->FormaRegistrarModulo();
			return true;
		}
		/**************************************************************************
		* Funcion en la que se presenta el menu principal del modulo 
		* 
		* @return boolean 
		***************************************************************************/
		function Menu()
		{
			$this->salida .= ThemeAbrirTabla('ADMINISTRACIÓN DE MODULOS');
			$this->salida .= "	<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "		<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>\n";
			$this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"".$this->action3."\" class=\"link\"><b>MANTENIMIENTO DE MODULOS</b></a></td></tr>\n";
			$this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"".$this->action4."\" class=\"link\"><b>MANTENIMIENTO DE MENÜS</b></a></td></tr>\n";
			$this->salida .= "			<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"".$this->action5."\" class=\"link\"><b>VER SUBMODULOS</b></a></td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table align=\"center\" width=\"35%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";		
			$this->salida .= "	</form>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/******************************************************************************** 
		* Funcion en la que se realiza la forma en la que se registra el modulo
		* 
		* @return boolean
		*********************************************************************************/
		function FormaRegistrarModulo()
		{
			$this->salida .= ThemeAbrirTabla('INGRESAR MODULOS');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function validar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(objeto.descripcion.value.length > 256)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				alert(\"La descripción del modulo debe ser menor de 255 caracteres\");\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else if(objeto.infoVersion.value.length > 256)\n";
			$this->salida .= "			 	 {\n";
			$this->salida .= "				  	alert(\"La descripción del modulo debe ser menor de 255 caracteres\");\n";
			$this->salida .= "				 }\n";
			$this->salida .= "				 else\n";
			$this->salida .= "				 {\n";
			$this->salida .= "					objeto.submit();\n";
			$this->salida .= "				 }\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$style = "style=\"text-align:left;text-indent:4pt\""; 
			
			$this->salida .= "	<form name=\"formaModulos\" action=\"".$this->action4."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"57%\">NOMBRE MODULO: </td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\"><input type=\"text\" name=\"nombre_modulo\" class=\"input-text\" maxlength=\"64\" style=\"width:90%\" value =\"".$this->NombreModulo."\"></td></tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "				<td $style width=\"57%\">TIPO MODULO: </td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<select name=\"tipo_modulo\" class=\"select\">\n";
			
			$tipoModulo = $this->ObtenerTipoModulo();
			for($i=0;$i<sizeof($tipoModulo);$i++)
			{
				if($tipoModulo[$i]=="")
					$this->salida .= "					<option value=\"0\">SELECCIONAR</option>\n";
				else
				{	
					if($this->TipoModulo == $tipoModulo[$i])
						$this->salida .= "					<option value=\"".$tipoModulo[$i]."\" selected >".$tipoModulo[$i]."</option>\n";
					else
						$this->salida .= "					<option value=\"".$tipoModulo[$i]."\">".$tipoModulo[$i]."</option>\n";
				}
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" >DESCRIPCIÓN</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">\n";
			$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion\" style=\"width:100%\" rows=\"3\">".$this->DescripcionModulo."</textarea>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"50%\">VERSION:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<input type=\"text\" name=\"version\" class=\"input-text\" maxlength=\"7\" size=\"5\" value=\"".$this->VersionModulo."\" onKeyPress='return acceptNum(event)'>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\">INFORMACIÓN DE LA VERSION</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					<textarea class=\"textarea\" name=\"infoVersion\" style=\"width:100%\" rows=\"3\">".$this->InfoVersionModulo ."</textarea>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"50%\">DISPONIBILIDAD DEL MODULO: </td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			if($this->EstadoModulo == '0')
				$inactivo = "checked";
			else
				$activo = "checked";
			$this->salida .= "					<input type=\"radio\" name=\"disponibilidad\" id=\"disponibilidad\" value=\"0\" ".$inactivo.">&nbsp;INACTIVO\n";
			$this->salida .= "					<input type=\"radio\" name=\"disponibilidad\" id=\"disponibilidad\" value=\"1\" ".$activo.">&nbsp;ACTIVO\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"50%\">MANEJA FUNCIONES DE USUARIO: </td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			if($this->SwUserModulo == '0')
				$sw_user_no = "checked";
			else
				$sw_user_si = "checked";
			$this->salida .= "					<input type=\"radio\" name=\"funcionU\" id=\"funcionU\" value=\"1\" ".$sw_user_si.">&nbsp;SI\n";
			$this->salida .= "					<input type=\"radio\" name=\"funcionU\" id=\"funcionU\" value=\"0\" ".$sw_user_no.">&nbsp;NO\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>MANEJA FUNCIONES DE ADMINISTRACIÓN:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			if($this->SwAdminModulo == '0')
				$sw_admin_no = "checked";
			else
				$sw_admin_si = "checked";
			$this->salida .= "					<input type=\"radio\" name=\"funcionA\" id=\"funcionA\" value=\"1\" ".$sw_admin_si.">&nbsp;SI\n";
			$this->salida .= "					<input type=\"radio\" name=\"funcionA\" id=\"funcionA\" value=\"0\" ".$sw_admin_no.">&nbsp;NO\n";
			$this->salida .= "					<input type=\"hidden\" name=\"llamado\" value=\"".$this->Llamado."\">\n";
			$this->salida .= "					<input type=\"hidden\" name=\"ingresarMenu\" value=\"0\">\n";
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onClick=\"validar(document.formaModulos)\">\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar y Crear Menú\" onClick=\"document.formaModulos.ingresarMenu.value = '1';validar(document.formaModulos)\">\n";
			$this->salida .= "				</td></form>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionM."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				\n";
			$this->salida .= "			</td></form></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/******************************************************************************** 
		* Funcion en la que se realiza la forma de ingresar menu 
		*
		* @return boolean 
		*********************************************************************************/
		function FormaIngresarMenu()
		{
			$this->salida .= ThemeAbrirTabla('INGRESAR MENU');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function validar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(objeto.descripcion.value.length > 256)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				alert(\"La descripción del modulo debe ser maximo de 256 caracteres\");\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.submit();\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";

			$style = "style=\"text-align:left;text-indent:4pt\""; 

			$this->salida .= "	<form name=\"formaMenu\" action=\"".$this->action3."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"50%\">NOMBRE MENU:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<input type=\"text\" name=\"nombre_menu\" class=\"input-text\" style=\"width:90%\" maxlength=\"32\" size=\"20\" value=\"".$this->NombreMenu."\"></td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\">DESCRIPCIÓN:</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">\n";
			$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion\" style=\"width:100%\" rows=\"4\">".$this->DescripcionMenu."</textarea>\n";
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"50%\">MENU DE ADMINISTRACIÓN:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			if($this->SwSystemMenu == '1')
				$sw_system_si = "checked";
			else
				$sw_system_no = "checked";
			$this->salida .= "					<input type=\"radio\" name=\"menuA\" id=\"menuA\" value=\"1\" ".$sw_system_si.">&nbsp;SI\n";
			$this->salida .= "					<input type=\"radio\" name=\"menuA\" id=\"menuA\" value=\"0\" ".$sw_system_no.">&nbsp;NO\n";
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "		<input type=\"hidden\" name=\"nombre_modulo\" value=\"".$this->NombreModulo."\">\n";
			$this->salida .= "		<input type=\"hidden\" name=\"tipo_modulo\" value=\"".$this->TipoModulo."\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onClick=\"validar(document.formaMenu)\">\n";
			$this->salida .= "			</td><td align=\"center\">\n";
			$this->salida .= "					<input type=\"hidden\" name=\"ingresaMenuItem\" value=\"0\">\n";
			$this->salida .= "					<input type=\"hidden\" name=\"llamado\" value=\"".$this->Llamado."\">\n";
			if($this->Llamado != '3')
			{
				$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Agregar MenuItem\" onclick=\"document.formaMenu.ingresaMenuItem.value ='1';validar(document.formaMenu)\">\n";
			}
			$this->salida .= "			\n";
			$this->salida .= "			</td></form>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td></form></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/******************************************************************************** 
		* Funcion en la que se realiza la forma de Ingresar un item de Menu 
		* 
		* @return boolean 
		*********************************************************************************/
		function FormaIngresarMenuItem()
		{
			$this->salida .= ThemeAbrirTabla('INGRESAR ITEM DE MENU');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function validarAN(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(validar(objeto))\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.nuevo_item.value=\"1\";\n";
			$this->salida .= "				objeto.submit();\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function validarA(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(validar(objeto))\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.nuevo_item.value=\"0\";\n";
			$this->salida .= "				objeto.submit();\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function validar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(objeto.descripcion.value.length > 256)\n";
			$this->salida .= "			{\n";
			$this->salida .= "			  alert(\"La descripción del modulo debe ser maximo de 256 caracteres\");\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "			  return true;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			return false;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$style = "style=\"text-align:left;text-indent:4pt\"";
			$this->salida .= "	<form name=\"formaIngresarMenu\" action=\"".$this->action4."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"35%\">TITULO MENU:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<input type=\"text\" name=\"titulo_menu\" class=\"input-text\" style=\"width:90%\" maxlength=\"32\" size=\"20\" value=\"".$this->TituloMenu."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			if($this->asociado !="NO")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style >TIPO MODULO:</td>\n";
				$this->salida .= "				<td $style class=\"modulo_list_claro\"></b>".$this->TipoModulo."<b></td></tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style >MODULO:</td>\n";
				$this->salida .= "				<td $style class=\"modulo_list_claro\"><b>".$this->NombreModulo."</b></td></tr>\n";
			}
			else
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style >MODULO:</td>\n";
				$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
				$this->salida .= "					<select name=\"nombre_modulo\" class=\"select\">\n";
				$this->salida .= "						<option value=\"\">SELECCIONAR</option>\n";
				
				$sele= "";
				$modulos = $this->ObtenerModulosSistema();
				foreach($modulos as $key => $tiposm)
				{
					($this->NombreModulo == $tiposm['modulo_tipo']."/".$tiposm['modulo'])? $sele = "selected":$sele= "";
						
					$this->salida .= "					<option value=\"".$tiposm['modulo_tipo']."/".$tiposm['modulo']."\" $sele>".$tiposm['modulo_tipo']." -- ".$tiposm['modulo']."</option>\n";
				}
				$this->salida .= "					</select></td></tr>\n";
			}
			
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>TIPO:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<select name=\"tipoMI\" class=\"select\">\n";
			switch($this->TipoMenuItem)
			{
				case 'controller':	$sel ="selected";    break;
				case 'system':			$system ="selected"; break;
				default:	$user ="selected"; break;
			}
			
			$this->salida .= "						<option value=\"controller\" ".$sel.">controller</option>\n";
			$this->salida .= "						<option value=\"system\" ".$system.">system</option>\n";
			$this->salida .= "						<option value=\"user\" ".$user.">user</option>\n";			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>MÉTODO:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\">\n";
			$this->salida .= "					<input type=\"text\" name=\"metodoNombre\" class=\"input-text\" style=\"width:90%\" maxlength=\"64\" size=\"20\" value=\"".$this->NombreMetodo."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\">DESCRIPCIÓN:</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion\" style=\"width:100%\" rows=\"4\">".$this->DescripcionMenuItem."</textarea>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>INDICE DE ORDEN:</td>\n";
			$this->salida .= "				<td $style class=\"modulo_list_claro\"><input type=\"text\" name=\"indice\" class=\"input-text\" maxlength=\"5\" size=\"3\" value=\"".$this->IndiceDeOrden."\" onKeyPress='return acceptNum(event)'></td></tr>\n";			
			$this->salida .= "		</table><br>\n";
			if($this->asociado !="NO")
			{
				$this->salida .= "		<input type=\"hidden\" name=\"nombre_modulo\" value=\"".$this->NombreModulo."\">\n";
				$this->salida .= "		<input type=\"hidden\" name=\"tipo_modulo\" value=\"".$this->TipoModulo."\">\n";
			}
			$this->salida .= "		<input type=\"hidden\" name=\"nombre_menu\" value=\"".$this->NombreMenu."\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onClick=\"validarA(document.formaIngresarMenu)\">\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			if($this->Llamado != '4')
			{
				$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar y Crear Item Nuevo\" onClick=\"validarAN(document.formaIngresarMenu)\">\n";
			}
			$this->salida .= "					<input type=\"hidden\" name=\"nuevo_item\" value=\"0\">\n";
			$this->salida .= "					<input type=\"hidden\" name=\"llamado\" value=\"".$this->Llamado."\">\n";
			$this->salida .= "			</td></form>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*******************************************************************************************
		* Funcion donde se realiza la forma de manteniemnto de items de menus 
		* 
		* @return boolean 
		********************************************************************************************/
		function FormaMantenimientoMenuItem()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE ITEMS DE MENÚ DEL MENÚ '.$this->Titulo);
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
			$this->salida .= "		<table align=\"center\"><tr><td class=\"label_error\">\n";
			$this->salida .= "			<a href=\"".$this->actionCrear."\">CREAR NUEVO MENÚ ITEM</a>\n";
			$this->salida .= "		</td></tr><table align=\"center\"><br>\n";           

			$Filas = $this->DatosItemsMenus();
            
      if(sizeof($Filas) > 0)
      {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"20%\"><b>TITULO</b></td>\n";
				$this->salida .= "				<td width=\"25%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>TIPO</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>METODO</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>MODULO</b></td>\n";
				$this->salida .= "				<td width=\"5%\"><b>INDICE</b></td>\n";
				$this->salida .= "				<td width=\"20%\" colspan=\"2\"><b>ACCIÓN</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro';
					  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';
					  $background = "#DDDDDD";
					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$Celdas = explode ("/", $Filas[$i]);
					
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						if($j == 0) 
						{
							$actionLinkE = ModuloGetURL('system','AdminModulos','user','EditarMenuItem',
														 array("codigo_menu_item"=>$Celdas[$j],"offset"=>$this->paginaActual,"nombre_menu"=>$_REQUEST['nombre_menu'],
														 	   "codigo_menu"=>$_REQUEST['codigo_menu'],"pagina"=>$_REQUEST['pagina'],"metodo_retorno"=>$_REQUEST['metodo_retorno'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"asociado"=>$_REQUEST['asociado']));
							$actionLinkX = ModuloGetURL('system','AdminModulos','user','EliminarMenuItem',
														 array("codigo_menu_item"=>$Celdas[$j],"offset"=>$this->paginaActual,"nombre_menu"=>$_REQUEST['nombre_menu'],
														 	   "codigo_menu"=>$_REQUEST['codigo_menu'],"pagina"=>$_REQUEST['pagina'],"metodo_retorno"=>$_REQUEST['metodo_retorno'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"asociado"=>$_REQUEST['asociado']));
						}
						else
						{
							$this->salida .= "<td>".$Celdas[$j]."</td>\n";
						}	
					}
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/edita.png\">";
					$this->salida .= "					<a href=\"".$actionLinkE."\">\n";
					$this->salida .= "					EDITAR</a>\n";
					$this->salida .= "				</td>\n";
					if($_REQUEST['asociado'])
					{
						$this->salida .= "				<td>\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/elimina.png\">";
						$this->salida .= "					<a href=\"".$actionLinkX."\">\n";
						$this->salida .= "					ELIMINA</a>\n";
						$this->salida .= "				</td>\n";					
					}
					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
				$this->salida .= "		<br>\n";
			
				$Paginador = new ClaseHTML();
			
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
				$this->salida .= "		<br>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br>\n";
			}

			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/******************************************************************************** 
		* Funcion que realiza la forma donde se muestran todos los modulos y las 
		* diferentes acciones que puede realizar sobre él 
		* 
		* @return boolean 
		*********************************************************************************/
		function FormaMostrarModulos()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO MODULOS');
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
			$this->salida .= "		<form name=\"formaBuscar\" action=\"".$this->actionBuscador."\" method=\"post\">\n";
			$this->salida .= "			<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"center\" colspan=\"5\">BUSCADOR DE MODULOS</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "					<td width=\"5%\">TIPO</td><td width=\"10%\" align = \"left\">\n";
			$this->salida .= "						<select size=1 name=\"criterio\"  class =\"select\">\n";
			$this->salida .= "							<option value=\"1\" selected>MODULO</option>\n";
			$this->salida .= "							<option value=\"2\">TIPO MODULO</option>\n";
			$this->salida .= "						</select></td>\n";
			$this->salida .= "					<td width=\"10%\">DESCRIPCIÓN:</td>\n";
			$this->salida .= "					<td width=\"25%\" align=\"center\">\n";
			$this->salida .= "						<input type=\"text\" class=\"input-text\" name=\"cadena_buscar\" size=\"40\" maxlength=\"40\" value =\"\" onKeyPress=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td  width=\"6%\" align=\"center\">\n";
			$this->salida .= "						<input type=\"hidden\" name=\"nuevaBusqueda\" value=\"0\">\n"; 
			$this->salida .= "						<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSCAR\" onclick=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"left\" colspan=\"5\">\n";
			$this->salida .= "					\n".$this->mensaje."&nbsp;"; 
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "			</table><br>\n";           
			$this->salida .= "		</form>\n";
			$this->salida .= "		<table align=\"center\" cellspacing=\"15\">\n";
			$this->salida .= "			<tr><td class=\"label_error\">\n";
			$this->salida .= "					<a href=\"".$this->actionCrear."\">CREAR NUEVO MODULO</a>\n";
			$this->salida .= "				</td>\n";           
			$this->salida .= "				<td class=\"label_error\">\n";
			$this->salida .= "					<a href=\"".$this->actionVariablesSistema."\">MANTENIMIENTO VARIABLES DEL SISTEMA</a>\n";
			$this->salida .= "		</td></tr></table>\n";

			$Filas = $this->ObtenerModulos();
            
      if(sizeof($Filas) > 0)
      {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"97%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"15%\"><b>MODULO</b></td>\n";
				$this->salida .= "				<td width=\"5%\" ><b>TIPO</b></td>\n";
				$this->salida .= "				<td width=\"28%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "				<td width=\"7%\" ><b>VERSION LOCAL</b></td>\n";
				$this->salida .= "				<td width=\"7%\" ><b>VERSION ACTUAL</b></td>\n";
				$this->salida .= "				<td width=\"6%\" ><b>ESTADO</b></td>\n";
				$this->salida .= "				<td width=\"32%\" colspan=\"4\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo = 'modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo = 'modulo_list_claro';  $background = "#DDDDDD";
					}
		
					$Celdas = explode ("/", $Filas[$i]);
					
					$actionLinkE = ModuloGetURL('system','AdminModulos','user','InformacionModulos',
												 array("nombre_modulo"=>$Celdas[0],"tipo_modulo"=>$Celdas[1],"offset"=>$this->paginaActual,"metodo_retorno"=>$this->MetodoReto));
					$actionLinkX = ModuloGetURL('system','AdminModulos','user','EliminarModulos',
												 array("nombre_modulo"=>$Celdas[0],"tipo_modulo"=>$Celdas[1],"offset"=>$this->paginaActual,"metodo_retorno"=>$this->MetodoReto));
					$actionLinkV = ModuloGetURL('system','AdminModulos','user','MostrarVariablesModulos',
												 array("nombre_modulo"=>$Celdas[0],"tipo_modulo"=>$Celdas[1],"pagina"=>$this->paginaActual,"metodo_retorno"=>$this->MetodoReto));
					
					$version = $this->Modulos[$Celdas[1]][$Celdas[0]]['version_modulo'];
					
					$opcion = "<b class=\"label_mark\">OK</b>";
					if($version > $Celdas[3])
					{
						$actionLinkM = ModuloGetURL('system','AdminModulos','user','ActualizarModulos',
												 	 array("nombre_modulo"=>$Celdas[0],"tipo_modulo"=>$Celdas[1],"pagina"=>$this->paginaActual,
												 	 	   "metodo_retorno"=>$this->MetodoReto,"version"=>$Celdas[3]));
												 	 	   
						$opcion  = "					<a href=\"".$actionLinkM."\" title=\"ACTUALIZAR MODULO\">\n";
						$opcion .= "						<img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\">ACTU\n";
						$opcion .= "					</a>\n";

					}
					
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td>".$Celdas[0]."</td>\n";				
					$this->salida .= "				<td>".$Celdas[1]."</td>\n";
					$this->salida .= "				<td>".$Celdas[2]."</td>\n";
					$this->salida .= "				<td>".$Celdas[3]."</td>\n";
					$this->salida .= "				<td>".$version."</td>\n";
					$this->salida .= "				<td align=\"center\"><b>".$Celdas[4]."</b></td>\n";		
					$this->salida .= "				<td align=\"center\" width=\"8%\">\n";
					$this->salida .= "					<a href=\"".$actionLinkE."\" title=\"EDITAR MODULO\">";
					$this->salida .= "						<img src=\"".GetThemePath()."/images/edita.png\" border=\"0\">EDIT\n";
					$this->salida .= "					</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td align=\"center\" width=\"8%\">\n";
					$this->salida .= "					<a href=\"".$actionLinkX."\" title=\"ELIMINAR MODULO\">\n";
					$this->salida .= "						<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">ELIM\n";
					$this->salida .= "					</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td align=\"center\" width=\"8%\">\n";
					$this->salida .= "					<a href=\"".$actionLinkV."\" title=\"ADICIONAR VARIABLE\">\n";
					$this->salida .= "						<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">VARS\n";
					$this->salida .= "					</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td align=\"center\" width=\"8%\">$opcion</td>\n";

					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************* 
		* Funcion que realiza una forma donde se da a conocer la información del modulo 
		* antes de eliminarlo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaEliminarModulo()
		{
			$this->salida .= ThemeAbrirTabla('ELIMINAR MODULOS');
			$this->salida .= "<table width=\"72%\" align=\"center\"><tr><td>\n";
			$this->salida .= "	<fieldset><legend class=\"field\">INFORMACIÓN MODULO</legend>\n";
			$this->salida .= "		<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" width=\"100%\" align=\"center\" >\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "					<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Nombre Modulo: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->NombreModulo."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Tipo Modulo: </td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\"><b>".$this->TipoModulo."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Descripción: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\">".$this->DescripcionModulo."</td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Version: </td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">".$this->VersionModulo."</td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Información de la Version: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\">".$this->InfoVersionModulo ."</td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Disponibilidad del Modulo: </td>\n";

			if($this->EstadoModulo == '0')
				$this->salida .= "							<td class=\"modulo_list_claro\">&nbsp;INACTIVO</td></tr>\n";
			else
				$this->salida .= "							<td class=\"modulo_list_claro\">&nbsp;ACTIVO\n</td></tr>";
			
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Maneja Funciones de Usuario: </td>\n";

			if($this->SwUserModulo == '0')
				$this->salida .= "						<td class=\"modulo_list_claro\">&nbsp;NO</td></tr>\n";
			else
			$this->salida .= "							<td class=\"modulo_list_claro\">&nbsp;SI</td></tr>\n";

			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Maneja Funciones de Administración: </td>\n";

			if($this->SwAdminModulo == '0')
				$this->salida .= "					<td class=\"modulo_list_claro\">&nbsp;NO</td></tr>\n";
			else
				$this->salida .= "					<td class=\"modulo_list_claro\">&nbsp;SI</td></tr>\n";
			
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>";
			
			$Filas = $this->ObtenerInfoMenuItem();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarMenuItem = 1;
				
				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"7\"><b>EN LA TABLA SYSTEM_MENUS_ITEMS</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"24%\"><b>MENÚ</b></td>\n";
				$this->salida .= "						<td width=\"22%\"><b>ITEM DE MENÚ</b></td>\n";
				$this->salida .= "						<td width=\"6%\"><b>TIPO</b></td>\n";
				$this->salida .= "						<td width=\"8%\"><b>METODO</b></td>\n";
				$this->salida .= "						<td width=\"24%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "						<td width=\"8%\"><b>ÍNDICE</b></td>\n";
				$this->salida .= "						<td width=\"8%\"><b>USUARIOS</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						if($j == 0)
						{
							$actionLink = ModuloGetURL('system','AdminModulos','user','UsuariosModulos',
														array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],"pagina"=>$_REQUEST['offset'],
														 	  "nombre_modulo"=>$this->modulo,"tipo_modulo"=>$this->tipoModulo,"metodo_retorno"=>"EliminarModulos"));
						}
						else
						{
							$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";
						}
					}
					$this->salida .= "				<td class=\"modulo_list_claro\">\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/usuarios.png\">";
					$this->salida .= "					<a href=\"".$actionLink."\" title=\"USUARIOS ASOCIADOS AL MENU\">\n";
					$this->salida .= "					USUARIOS</a>\n";
					$this->salida .= "				</td>\n";					

					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}
			
			$Filas2 = $this->ObtenerInfoModuloDefault();
            if(sizeof($Filas2) > 0)
			{
				$this->EliminarModuloDefault = 1;

				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_MODULOS_DEFAULT</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"30%\"><b>HOST</b></td>\n";
				$this->salida .= "						<td width=\"30\"><b>USUARIO</b></td>\n";
				$this->salida .= "						<td width=\"30%\"><b>PARAMETROS</b></td>\n";
				$this->salida .= "						<td width=\"10%\"><b>ACTIVO</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas2);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas2[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}
			
			$Filas = $this->ObtenerInfoModuloVariables();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarModuloVariable = 1;
				
				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_MODULOS_VARIABLES</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"55%\"><b>VARIABLE</b></td>\n";
				$this->salida .= "						<td width=\"45\"><b>VALOR</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}

			$Filas = $this->ObtenerInfoSystemPermisos();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarSystemPermisos = 1;

				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_PERMISOS</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"40%\"><b>NOMBRE PERMISO</b></td>\n";
				$this->salida .= "						<td width=\"60\"><b>DESCRIPCIÓN</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>\n";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>\n";
			}

			$Filas = $this->ObtenerInfoSystemReportes();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarSystemreports = 1;
				
				$this->salida .= "			<tr><td>\n";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_REPORTS</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"30%\"><b>NOMBRE REPORTE</b></td>\n";
				$this->salida .= "						<td width=\"30\"><b>EMPRESA</b></td>\n";
				$this->salida .= "						<td width=\"30%\"><b>TIPO CLASE</b></td>\n";
				$this->salida .= "						<td width=\"10%\"><b>NOMBRE CLASE</b></td>\n";
				$this->salida .= "					</tr>\n";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>\n";
				}
				
				$this->salida .= "				</table>\n";
				$this->salida .= "			</td></tr>\n";
			}
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "					<tr><td align=\"center\" class=\"label_error\">\n";
			$this->salida .= "						¿REALMENTE DESEA BORRAR EL MODULO: ".$this->modulo."?\n";
			$this->salida .= "					</td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</fieldset>\n";
			$this->salida .= "</td></tr></table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "					<form name=\"eliminar\" action=\"".$this->actionE."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			$this->salida .= "					<form name=\"volver\" action=\"".$this->actionA."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaInformacion($parametro)
		{
			$this->salida .= ThemeAbrirTabla('MENSAJE');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" >";
			$this->salida .= "				".$parametro."\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			if($this->actionM && !$this->confirmacion)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->actionM."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*******************************************************************************
		* Funcion que permite visualizar la forma de actualizar un modulo 
		* 
		* @return boolean 
		********************************************************************************/
		function FormaAdicionarVariables()
		{			
			$this->salida .= ThemeAbrirTabla('VARIABLES DEL MODULO');
			$this->salida .= "	<br>\n";
			$this->salida .= "	<form name=\"formaVariables\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle($this->Campo)."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table width=\"60%\" align=\"center\"><tr><td>\n";
			$this->salida .= "			<fieldset><legend class=\"field\">Agregar variables al modulo ".$this->NombreModulo."</legend>\n";
			$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" >\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td class=\"modulo_table_title\" width=\"50%\">Nombre Variable: </td>\n";
			$this->salida .= "						<td class=\"modulo_list_claro\">\n";
			$this->salida .= "							<input type=\"text\" name=\"variable\" class=\"input-text\" maxlength=\"64\" size=\"32\" value=\"".$this->VariableModulo."\">\n";
			$this->salida .= "						</td></tr>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td class=\"modulo_table_title\" width=\"50%\">Valor: </td>\n";
			$this->salida .= "						<td class=\"modulo_list_claro\">\n";
			$this->salida .= "							<textarea class=\"textarea\" name=\"valorVariable\" cols=\"31\" rows=\"2\">".$this->ValorVariable."</textarea>\n";
			$this->salida .= "							<input type=\"hidden\" name=\"nuevaVariable\" value=\"0\">\n";
			$this->salida .= "						</td></tr>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td class=\"modulo_table_title\" colspan=\"2\">Descripción: </td>\n";
			$this->salida .= "					</tr><tr>\n";
			$this->salida .= "						<td class=\"modulo_list_claro\" colspan=\"2\">\n";
			$this->salida .= "							<textarea class=\"textarea\" name=\"descripcion_variable\" cols=\"100\" rows=\"2\">".$this->DescripcionVariable."</textarea>\n";
			$this->salida .= "						</td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</fieldset>\n";
			$this->salida .= "		</td></tr></table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\" >\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			if(!$this->Llamado)
			{
				$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Aceptar y Agregar Uno Nuevo\" onClick=\"document.formaVariables.nuevaVariable.value='1'\">\n";
			}
			$this->salida .= "				</td></form>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionM."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
			$this->salida .= "			</td></form></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************* 
		* funcion que permite desplegar la informaciuon de los modulos en pantalla 
		* 
		* @return boolean  
		**********************************************************************************/
		function FormaMostrarMenus()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO MENUS');
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
			$this->salida .= "		<table align=\"center\"><tr><td class=\"label_error\">\n";
			$this->salida .= "			<a href=\"".$this->actionCrear."\">CREAR NUEVO MENÜ</a>\n";
			$this->salida .= "		</td></tr><table align=\"center\"><br>\n";           

			$Filas = $this->DatosMenus();
            
      if(sizeof($Filas) > 0)
      {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"30%\"><b>NOMBRE MENU</b></td>\n";
				$this->salida .= "				<td width=\"35%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "				<td width=\"20%\"><b>MENU ADMIN</b></td>\n";
				$this->salida .= "				<td width=\"15%\" colspan=\"4\"><b>ACCIÓN</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro';
					  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';
					  $background = "#DDDDDD";
					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$Celdas = explode ("/", $Filas[$i]);
					
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						if($j == 0) 
						{
							$actionLinkE = ModuloGetURL('system','AdminModulos','user','EditarMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],"offset"=>$this->paginaActual,"pagina"=>$_REQUEST['pagina'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>"MantenimientoMenu"));
							$actionLinkU = ModuloGetURL('system','AdminModulos','user','UsuariosModulos',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],"offset"=>$this->paginaActual,"pagina"=>$_REQUEST['pagina'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>"MantenimientoMenu"));
							$actionLinkI = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],"offset"=>$this->paginaActual,"pagina"=>$_REQUEST['pagina'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>"MantenimientoMenu"));
							$actionLinkX = ModuloGetURL('system','AdminModulos','user','EliminarMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],"offset"=>$this->paginaActual,"pagina"=>$_REQUEST['pagina'],
														 	   "nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],"metodo_retorno"=>"MantenimientoMenu"));

						}
						else
						{
							$this->salida .= "<td>".$Celdas[$j]."</td>\n";
						}	
					}
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/edita.png\">";
					$this->salida .= "					<a href=\"".$actionLinkE."\">\n";
					$this->salida .= "					EDITAR</a>\n";
					$this->salida .= "				</td>\n";					
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/elimina.png\">";
					$this->salida .= "					<a href=\"".$actionLinkX."\" title=\"SUBMENÚS\">\n";
					$this->salida .= "					ELIMINA</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/pactivo.png\">";
					$this->salida .= "					<a href=\"".$actionLinkI."\" title=\"SUBMENÚS\">\n";
					$this->salida .= "					ITEMS</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/usuarios.png\">";
					$this->salida .= "					<a href=\"".$actionLinkU."\" title=\"USUARIOS ASOCIADOS AL MENU\">\n";
					$this->salida .= "					USUARIOS</a>\n";
					$this->salida .= "				</td>\n";					
					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			
			$Paginador = new ClaseHTML();
			
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************* 
		* Funcion en la cual se realiza la forma donde se muestran los ususrios asociados 
		* al menu seleccionado 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaMostrarUsuariosMenus()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO USUARIOS DEL MENU '.$this->NombreMenu);
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
			$Filas = $this->DatosUsuariosModulos();
            
            if(sizeof($Filas) > 0)
            {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"25%\"><b>UID</b></td>\n";
				$this->salida .= "				<td width=\"25%\"><b>USUARIO</b></td>\n";
				$this->salida .= "				<td width=\"50%\"><b>NOMBRE USUARIO</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro';
					  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';
					  $background = "#DDDDDD";
					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$Celdas = explode ("/", $Filas[$i]);
					
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td>".$Celdas[$j]."</td>\n";
					}
					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO HAY NINGUN USUARIO ASOCIADO A ESTE MENU</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			
			$Paginador = new ClaseHTML();
			
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*******************************************************************************************
		* Funcion donde se realiza la forma de manteniemnto de variables de los modulos  
		* 
		* @return boolean 
		********************************************************************************************/
		function FormaMantenimientoVariables()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE VARIABLES DEL MODULO: '.$_REQUEST['nombre_modulo']);
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
			$this->salida .= "		<table align=\"center\"><tr><td class=\"label_error\">\n";
			$this->salida .= "			<a href=\"".$this->actionCrear."\">CREAR NUEVA VARIABLE</a>\n";
			$this->salida .= "		</td></tr><table align=\"center\"><br>\n";           

			$Filas = $this->DatosVariablesModulos();
            
            if(sizeof($Filas) > 0)
            {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"35%\"><b>NOMBRE VARIABLE</b></td>\n";
				$this->salida .= "				<td width=\"30%\"><b>VALOR</b></td>\n";
				$this->salida .= "				<td width=\"35%\" colspan=\"2\"><b>ACCIÓN</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro';
					  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';
					  $background = "#DDDDDD";
					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$Celdas = explode ("*", $Filas[$i]);
					
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						if($j == 0) 
						{
							$actionLinkE = ModuloGetURL('system','AdminModulos','user','EditarVariables',
														 array("nombre_variable"=>$Celdas[$j],"offset"=>$this->paginaActual,"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],
														 	   "metodo_retorno"=>$_REQUEST['metodo_retorno']));
							$actionLinkX = ModuloGetURL('system','AdminModulos','user','EliminarVariables',
														 array("nombre_variable"=>$Celdas[$j],"offset"=>$this->paginaActual,"nombre_modulo"=>$_REQUEST['nombre_modulo'],"tipo_modulo"=>$_REQUEST['tipo_modulo'],
														 	   "metodo_retorno"=>$_REQUEST['metodo_retorno']));
						}
						$this->salida .= "<td>".$Celdas[$j]."</td>\n";
					}
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/edita.png\">";
					$this->salida .= "					<a href=\"".$actionLinkE."\">\n";
					$this->salida .= "					EDITAR</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/elimina.png\">";
					$this->salida .= "					<a href=\"".$actionLinkX."\">\n";
					$this->salida .= "					ELIMINAR</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			
			$Paginador = new ClaseHTML();
			
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*****************************************************************************************
		* Funcion que despliega la informacion del menu que se desea eliminar 
		* 
		* @return boolean 
		******************************************************************************************/
		function FormaInformacionMenuEliminar()
		{
			$this->salida .= ThemeAbrirTabla('ELIMINAR MENÚ');
			$this->salida .= "<table width=\"72%\" align=\"center\"><tr><td>\n";
			$this->salida .= "	<fieldset><legend class=\"field\">INFORMACIÓN DEL MENÚ</legend>\n";
			$this->salida .= "		<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" width=\"100%\" align=\"center\" >\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "					<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Nombre Menú: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->NombreMenu."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Descripción Menú: </td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\"><b>".$this->DescripcionMenu."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Menu de Administración: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\">".$this->SwSystem."</td></tr>\n";			
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>";
			
			$Filas = $this->ObtenerInfoMenuItemMenu();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarMenuItem = 1;
				
				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"6\"><b>EN LA TABLA SYSTEM_MENUS_ITEMS</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"24%\"><b>ITEM DE MENÚ</b></td>\n";
				$this->salida .= "						<td width=\"6%\"><b>TIPO</b></td>\n";
				$this->salida .= "						<td width=\"10%\"><b>METODO</b></td>\n";
				$this->salida .= "						<td width=\"28%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "						<td width=\"8%\"><b>ÍNDICE</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}
			
			$Filas = $this->ObtenerDatosUsuariosMenu();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarModuloDefault = 1;

				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_USUARIOS_MENUS</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"30%\"><b>UID</b></td>\n";
				$this->salida .= "						<td width=\"30%\"><b>USUARIOS</b></td>\n";
				$this->salida .= "						<td width=\"30%\"><b>NOMBRE USUARIO</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}
			
			$Filas = $this->ObtenerDatosPerfiles();
            if(sizeof($Filas) > 0)
			{
				$this->EliminarModuloVariable = 1;
				
				$this->salida .= "			<tr><td>";
				$this->salida .= "				<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td colspan=\"5\"><b>EN LA TABLA SYSTEM_MODULOS_PERFILES</b></td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "						<td width=\"55%\"><b>DESCRIPCION PERFIL</b></td>\n";
				$this->salida .= "						<td width=\"45\"><b>EMPRESA</b></td>\n";
				$this->salida .= "					</tr>";
				for($i=0;$i<sizeof($Filas);$i++)
				{
					$this->salida .= "					<tr>\n";
					
					$Celdas = explode ("/", $Filas[$i]);
					for($j=0; $j<sizeof($Celdas); $j++)
					{
						$this->salida .= "<td class=\"modulo_list_claro\">".$Celdas[$j]."</td>\n";				
					}
					$this->salida .= "					</tr>";
				}
				
				$this->salida .= "				</table>";
				$this->salida .= "			</td></tr>";
			}

			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "					<tr><td align=\"center\" class=\"label_error\">\n";
			$this->salida .= "						¿REALMENTE DESEA BORRAR EL MODULO: ".$this->modulo."?\n";
			$this->salida .= "					</td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</fieldset>\n";
			$this->salida .= "</td></tr></table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "					<form name=\"eliminar\" action=\"".$this->actionX."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			$this->salida .= "					<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/******************************************************************************** 
		* Funcion que realiza la forma donde se muestran todos los modulos y las 
		* diferentes acciones que puede realizar sobre él 
		* 
		* @return boolean 
		*********************************************************************************/
		function FormaMostrarMantenimientoMenus()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO MENUS');
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
			$this->salida .= "		<form name=\"formaBuscar\" action=\"".$this->actionBuscador."\" method=\"post\">\n";
			$this->salida .= "			<table  align=\"center\" border=\"0\"  width=\"60%\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"center\" colspan=\"3\">BUSCADOR DE MENÚS</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "					<td width=\"25%\">NOMBRE MENÜ:</td>\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "						<input type=\"text\" class=\"input-text\" name=\"cadena_buscar\" size=\"40\" maxlength=\"40\" value =\"\" onKeyPress=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td  width=\"15%\" align=\"center\">\n";
			$this->salida .= "						<input type=\"hidden\" name=\"nuevaBusqueda\" value=\"0\">\n"; 
			$this->salida .= "						<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSCAR\" onclick=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"left\" colspan=\"3\">\n";
			$this->salida .= "					\n".$this->mensaje."&nbsp;"; 
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "			</table><br>\n";           
			$this->salida .= "		</form>\n";
			$this->salida .= "		<table align=\"center\" cellspacing=\"15\">\n";
			$this->salida .= "			<tr><td class=\"label_error\">\n";
			$this->salida .= "					<a href=\"".$this->actionCrear."\">CREAR NUEVO MENÜ</a>\n";
			$this->salida .= "		</td></tr></table>\n";


			$Filas = $this->ObtenerModulos();
            
            if(sizeof($Filas) > 0)
            {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"96%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"20%\"><b>NOMBRE MENÜ</b></td>\n";
				$this->salida .= "				<td width=\"35%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>MENÚ ADMIN</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>Nº ITEMS</b></td>\n";
				$this->salida .= "				<td width=\"25%\" colspan=\"4\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($Filas);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro';
					  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';
					  $background = "#DDDDDD";
					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$Celdas = explode ("/", $Filas[$i]);

					for($j=0; $j<sizeof($Celdas); $j++)
					{
						if($j == 0) 
						{
							$actionLinkE = ModuloGetURL('system','AdminModulos','user','EditarMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],
														 	   "pagina"=>$this->paginaActual,"metodo_retorno"=>$this->retorno));
							$actionLinkU = ModuloGetURL('system','AdminModulos','user','UsuariosModulos',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],
														 	   "pagina"=>$this->paginaActual,"metodo_retorno"=>$this->retorno));
							$actionLinkI = ModuloGetURL('system','AdminModulos','user','MostrarItemsMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],
														 	   "pagina"=>$this->paginaActual,"metodo_retorno"=>$this->retorno,"asociado"=>"NO"));
							$actionLinkX = ModuloGetURL('system','AdminModulos','user','EliminarMenu',
														 array("codigo_menu"=>$Celdas[$j],"nombre_menu"=>$Celdas[$j+1],
														 	   "pagina"=>$this->paginaActual,"metodo_retorno"=>$this->retorno));

						}
						else
						{
							$this->salida .= "<td>".$Celdas[$j]."</td>\n";
						}	
					}
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/edita.png\">";
					$this->salida .= "					<a href=\"".$actionLinkE."\">\n";
					$this->salida .= "					EDITAR</a>\n";
					$this->salida .= "				</td>\n";					
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/elimina.png\">";
					$this->salida .= "					<a href=\"".$actionLinkX."\" title=\"ELIMINAR\">\n";
					$this->salida .= "					ELIMINA</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/pactivo.png\">";
					$this->salida .= "					<a href=\"".$actionLinkI."\" title=\"SUBMENÚS\">\n";
					$this->salida .= "					ITEMS</a>\n";
					$this->salida .= "				</td>\n";
					$this->salida .= "				<td>\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/usuarios.png\">";
					$this->salida .= "					<a href=\"".$actionLinkU."\" title=\"USUARIOS ASOCIADOS AL MENU\">\n";
					$this->salida .= "					USUARIOS</a>\n";
					$this->salida .= "				</td>\n";
				}					
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*****************************************************************************************
		* Funcion que despliega la informacion del menu que se desea eliminar 
		* 
		* @return boolean 
		******************************************************************************************/
		function FormaMostrarInfoMenuItem()
		{
			$this->salida .= ThemeAbrirTabla('ELIMINAR MENÚ');
			$this->salida .= "<table width=\"72%\" align=\"center\"><tr><td>\n";
			$this->salida .= "	<fieldset><legend class=\"field\">INFORMACIÓN DEL ITEM DE MENÚ</legend>\n";
			$this->salida .= "		<table cellspacing=\"2\" cellpadding=\"3\" border=\"0\" width=\"100%\" align=\"center\" >\n";
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "					<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Titulo Menú Item: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->TituloMenuItem."</b></td></tr>\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Modulo Asociado: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->ModuloAsociado."</b></td></tr>\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Tipo de Modulo: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->ModuloTipoAsoc."</b></td></tr>\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Tipo de Item de Menú: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->TipoMenuItem."</b></td></tr>\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Metodo: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->MetodoModulo."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Descripción Menú: </td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\"><b>".$this->DescripcionMenuItem."</b></td></tr>\n";
			$this->salida .= "						<tr><td class=\"modulo_table_list_title\" width=\"50%\">Menú Asociado: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\"><b>".$this->NombreMenu."</b></td></tr>\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"50%\">Indice de Orden: </td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\">".$this->IndiceDeOrden."</td></tr>\n";			
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>";			
			$this->salida .= "			<tr><td>\n";
			$this->salida .= "				<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "					<tr><td align=\"center\" class=\"label_error\">\n";
			$this->salida .= "						¿REALMENTE DESEA BORRAR ESTE ITEM DE MENÚ?\n";
			$this->salida .= "					</td></tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</fieldset>\n";
			$this->salida .= "</td></tr></table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "					<form name=\"eliminar\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "				</td><td align=\"center\">\n";
			$this->salida .= "					<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		</form>\n";
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
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					if($campo == "Informacion")
					{
						return ("<tr><td class='label' colspan='3' align='center'>".$this->frmError["Informacion"]."</td></tr>");
					}
					return ("<tr><td>&nbsp;</td></tr>");
				}
			return ("<tr><td>&nbsp;</td></tr>");
		}
		/******************************************************************************** 
		* Funcion que realiza la forma donde se muestran todos los modulos y las 
		* diferentes acciones que puede realizar sobre él 
		* 
		* @return boolean 
		*********************************************************************************/
		function FormaMostrarSubmodulos()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE SUBMODULOS');
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
            
			$this->salida .= "		<form name=\"formaBuscar\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "			<table  align=\"center\" border=\"0\"  width=\"60%\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"center\" colspan=\"3\">BUSCADOR DE SUBMODULOS</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
			$this->salida .= "					<td width=\"%\" align=\"center\">\n";
			$this->salida .= "						<b class=\"label_mark\">SUBMODULO</b>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td width=\"%\" align=\"center\">\n";
			$this->salida .= "						<input type=\"text\" class=\"input-text\" name=\"busqueda\" size=\"40\" maxlength=\"40\" value =\"\" onKeyPress=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td  width=\"%\" align=\"center\">\n";
			$this->salida .= "						<input type=\"hidden\" name=\"nuevaBusqueda\" value=\"0\">\n"; 
			$this->salida .= "						<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSCAR\" onclick=\"document.formaBuscar.nuevaBusqueda.value='1'\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";  
			$this->salida .= "					<td align=\"left\" colspan=\"3\">\n";
			$this->salida .= "					\n".$this->mensaje."&nbsp;"; 
			$this->salida .= "				</td></tr>\n";
			$this->salida .= "			</table><br>\n";           
			$this->salida .= "		</form>\n";
            
            if(sizeof($this->Submodulos) > 0)
            {
				$this->salida .= "		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"88%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"30%\"><b>SUBMODULO</b></td>\n";
				$this->salida .= "				<td width=\"35%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "				<td width=\"5%\"><b>VERSION LOCAL</b></td>\n";
				$this->salida .= "				<td width=\"5%\"><b>VERSION ACTUAL</b></td>\n";
				$this->salida .= "				<td width=\"10%\"><b>ESTADO</b></td>\n";
				$this->salida .= "				<td width=\"15%\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				
				for($i=0;$i<sizeof($this->Submodulos);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					$Celdas = explode ("*", $this->Submodulos[$i]);
					
					$version = $this->Modulos['hc'][$Celdas[0]]['version_modulo'];
					
					$opcion = "<b class=\"label_mark\">ACTUALIZADO</b>";
					if($version > $Celdas[2])
					{
						$actionLinkM = ModuloGetURL('system','AdminModulos','user','ActualizarModulos',
												 	 array("nombre_modulo"=>$Celdas[0],"tipo_modulo"=>"hc","pagina"=>$this->paginaActual,
												 	 	   "metodo_retorno"=>"MostrarSubmodulos","version"=>$Celdas[2]));
												 	 	   
						$opcion  = "					<a href=\"".$actionLinkM."\" title=\"ACTUALIZAR MODULO\">\n";
						$opcion .= "						<img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\">ACTUALIZAR\n";
						$opcion .= "					</a>\n";

					}
		
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "				<td>".$Celdas[0]."</td>\n";
					$this->salida .= "				<td>".$Celdas[1]."</td>\n";
					$this->salida .= "				<td align=\"right\" >".$Celdas[2]."</td>\n";
					$this->salida .= "				<td align=\"right\" >".$version."</td>\n";
					$this->salida .= "				<td align=\"center\"><b>".$Celdas[3]."</b></td>\n";
					$this->salida .= "				<td align=\"center\">$opcion</td>\n";			
					$this->salida .= "			</tr>\n";
				}		
				$this->salida .= "		</table>\n";
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center>\n";
			}
			$this->salida .= "		<br>\n";
			
			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
			$this->salida .= "		<br>\n";
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function FormaActualizarModulos()
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE SUBMODULOS');
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
			$this->salida .= "	<table  align=\"center\" border=\"0\"  width=\"60%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td width=\"15%\" style=\"text-align:left;text-indent:11pt\">\n";
			$this->salida .= "				<b>NOMBRE:</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"%\" colspan=\"3\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->Modulo_id."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td width=\"15%\" style=\"text-align:left;text-indent:11pt\">\n";
			$this->salida .= "				TIPO:\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"%\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->Tipo_Modulo."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"20%\" style=\"text-align:left;text-indent:11pt\">\n";
			$this->salida .= "				VERSION:\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td width=\"%\" class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->Version."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";           
			
			if(sizeof($this->Informacion) > 0)
			{			
				$this->salida .= "	<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"15%\"><b>VERSION</b></td>\n";
				$this->salida .= "			<td width=\"40%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>TIPO</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>FECHA</b></td>\n";
				$this->salida .= "			<td width=\"15%\" colspan=\"2\"><b>OPCIONES</b></td>\n";
				$this->salida .= "		</tr>";
				
				$i = 0;
				foreach($this->Informacion as $valor)
				{
					$fecha = str_replace("-","/",$valor['fecha_actualizacion']);
					$arreglo = explode(" ",$fecha);
					
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$opcion  = $opcion2 = "&nbsp;";
					if($i == 0)
					{
						$action2 = ModuloGetURL('system','AdminModulos','user','DescargarModulo',
												 array("nombre_modulo"=>$this->Modulo_id,"tipo_modulo"=>$this->Tipo_Modulo,
												 	   "pagina"=>$this->Pagina,"metodo_retorno"=>$this->Retorno,
												 	   "version"=>$this->Version,"version_s"=>$valor['version_modulo']));
						
						$opcion  = "					<a href=\"".$action2."\" title=\"DESCARGAR ACTUALIZACION MODULO\">\n";
						$opcion .= "						<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">DESCARGAR\n";
						$opcion .= "					</a>\n";
						
						$arreglo1 = $this->ObtenerInstalacion($this->Modulo_id,$this->Tipo_Modulo,$valor['version_modulo']);
						if(sizeof($arreglo1 ) > 0)
						{
							$action3 = ModuloGetURL('system','AdminModulos','user','InstalarModulo',
													 array("nombre_modulo"=>$this->Modulo_id,"tipo_modulo"=>$this->Tipo_Modulo,
													 	   "pagina"=>$this->Pagina,"metodo_retorno"=>$this->Retorno,
													 	   "version"=>$this->Version,"version_s"=>$valor['version_modulo']));
							
							$opcion2  = "					<a href=\"".$action3."\" title=\"INSTALAR MODULO\">\n";
							$opcion2 .= "						<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">INSTALAR\n";
							$opcion2 .= "					</a>\n";
							
						
						}
					}
	
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"23\">\n";
					$this->salida .= "			<td>".$valor['version_modulo']."</td>\n";
					$this->salida .= "			<td>".$valor['descripcion']."</td>\n";
					$this->salida .= "			<td>".$valor['tipo_actualizacion']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$arreglo[0]."</td>\n";
					$this->salida .= "			<td>".$opcion."</td>\n";
					$this->salida .= "			<td>".$opcion2."</td>\n";
					$this->salida .= "		</tr>";
					
					$i++;
				}
				$this->salida .= "	</table><br>\n";           
			}
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
	}

?>