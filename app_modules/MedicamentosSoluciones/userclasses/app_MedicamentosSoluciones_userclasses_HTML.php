<?php
	/**************************************************************************************  
	* $Id: app_MedicamentosSoluciones_userclasses_HTML.php,v 1.1 2006/08/18 20:32:14 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_MedicamentosSoluciones_userclasses_HTML extends app_MedicamentosSoluciones_user
	{
		function app_MedicamentosSoluciones_userclasses_HTML(){	}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
		{
			$this->FormaMenuPrincipal();
			return true;
		}
		/************************************************************************************
		* Muestra el menu principal
		* 
		* @return boolean
		*************************************************************************************/
		function FormaMenuPrincipal()
		{
			$datos = array();
			$datos['CREAR GRUPOS DE SOLUCIONES Y MEDICAMENTOS']['opcion'] = '1';
			$datos['CREAR Y DEFINIR SOLUCIONES']['opcion'] = '2';
			$datos['MODIFICAR GRUPOS DE SOLUCIONES Y MEDICAMENTOS']['opcion'] = '3';
			$datos['ELIMINAR GRUPOS DE SOLUCIONES Y MEDICAMENTOS']['opcion'] = '4';
			
			$url[0] = 'app';										//contenedor 
			$url[1] = 'MedicamentosSoluciones';	//módulo 
			$url[2] = 'user';										//clase 
			$url[3] = 'FormaCrearElementos';	//método 
			$url[4] = 'datos';									//indice del request
			$titulo[0] = 'MENU';
			
			$this->salida .= gui_theme_menu_acceso('ADMINISTRACIÓN DE SOLUCIONES Y MEDICAMENTOS',$titulo,$datos,$url,ModuloGetURL('system','Menu','user'));
			return true;
		}
		/************************************************************************************
		* Muestra el menu de documentos
		* 
		* @return boolean
		*************************************************************************************/
		function FormaCrearElementos()
		{
			$this->CrearElementos();
			switch($this->Opcion)
			{
				case '1': $this->FormaCrearGrupos(); break;
				case '2':	$this->FormaCrearSoluciones(); break;
				case '3': $this->FormaModificarGrupos(); break;
				case '4': $this->FormaEliminarGrupos(); break;
			}
			return true;
		}
		/************************************************************************************
		* Funcion donde se mestra el buscador de facturas y su respectivo resultado
		* 
		* @return boolean 
		*************************************************************************************/
		function FormaCrearGrupos()
		{
			$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/GruposSM.js', $contenedor='app', $modulo='MedicamentosSoluciones');
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function EvaluarMedicamento(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.nombre_grupo.value == '' )\n";
			$this->salida .= "				document.getElementById('error').innerHTML = '<center class=\"label_error\">SE DEBE INGRESAR EL NOMBRE DEL GRUPO</center><br>'\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				envio = new Array();\n";
			$this->salida .= "				envio[0] = objeto.nombre_grupo.value;\n";
			$this->salida .= "				CrearGrupoMedicamento(envio);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EvaluarSolucion(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.nombre_grupo.value == '' )\n";
			$this->salida .= "				document.getElementById('errorS').innerHTML = '<center class=\"label_error\">SE DEBE INGRESAR EL NOMBRE DEL GRUPO</center><br>'\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				envio = new Array();\n";
			$this->salida .= "				envio[0] = objeto.nombre_grupo.value;\n";
			$this->salida .= "				CrearGrupoSolucion(envio);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function LimpiarCampos(op)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			document.buscador.producto.value = '';\n";
			$this->salida .= "			document.buscador.principio_activo.value = '';\n";
			$this->salida .= "			if(op == 1) document.grupomedicamento.nombre_grupo.value = '';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){alert(error)}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= ThemeAbrirTabla("CREAR GRUPOS DE MEDICAMENTOS Y SOLUCIONES");
			$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<div class=\"tab-pane\" id=\"grupos\">\n";
			$this->salida .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"grupos\" ), false); </script>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"medicamentos\">\n";
			$this->salida .= "									<h2 class=\"tab\">MEDICAMENTOS</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"medicamentos\")); </script>\n";
			$this->salida .= "									<div id=\"error\" ></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"grupomedicamento\" action=\"\" >\n";
			$this->salida .= "													<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" style=\"text-indent:8pt\">NOMBRE DEL GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "																<input type='text' class='input-text' size='52' name = 'nombre_grupo' value =\"\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "														<tr >\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= " 																<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" id=\"tablaplantillas\">\n";
			$this->salida .= "																	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "  																	<td align=\"center\" colspan=\"6\">PLANTILLAS</td>\n";
			$this->salida .= "																	</tr>\n";
			$datos = $this->Plantillas;
			for($i=0; $i<sizeof($datos); $i=$i+3)
			{
				$this->salida .= "																		<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "																			<td width=\"1%\" id=\"".$datos[$i]['hc_modulo']."\">\n";
				$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantilla('".$datos[$i]['hc_modulo']."','".$datos[$i]['descripcion']."');\">\n";
				$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
				$this->salida .= "																				<a>\n";
				$this->salida .= "																			</td>\n";
				$this->salida .= "																			<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i]['descripcion'])."</td>\n";
				if($datos[$i+1]['descripcion'])
				{
					$this->salida .= "																			<td width=\"1%\" id=\"".$datos[$i+1]['hc_modulo']."\">\n";
					$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantilla('".$datos[$i+1]['hc_modulo']."','".$datos[$i+1]['descripcion']."');\">\n";
					$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
					$this->salida .= "																				<a>\n";
					$this->salida .= "																			</td>\n";
					$this->salida .= "																			<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i+1]['descripcion'])."</td>\n";
					if($datos[$i+2]['descripcion'])
					{
						$this->salida .= "																			<td width=\"1%\" id=\"".$datos[$i+2]['hc_modulo']."\">\n";
						$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantilla('".$datos[$i+2]['hc_modulo']."','".$datos[$i+2]['descripcion']."');\">\n";
						$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
						$this->salida .= "																				<a>\n";
						$this->salida .= "																			</td>\n";
						$this->salida .= "																			<td width=\"34%\" class=\"normal_10AN\">".ucwords($datos[$i+2]['descripcion'])."</td>\n";
					}
					else
					{
						$this->salida .= "																			<td colspan=\"2\"></td>\n";
					}
				}
				else
				{
					$this->salida .= "																			<td colspan=\"4\"></td>\n";
				}
				$this->salida .= "																		</tr>\n";
			}
			$this->salida .= "																	</table>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "																<td align=\"center\" colspan=\"2\" id=\"adicionados\">\n";
			$this->salida .= "																	<font class=\"normal_10AN\">NO HAY MEDICAMENTOS ADICIONADOS</font>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";	
			$this->salida .= "															<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "																<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																	<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarMedicamento(document.grupomedicamento);\">\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "														</table>\n";
			$this->salida .= "													</form>\n";
			$this->salida .= "												</td>\n";
			$this->salida .= "											</tr>\n";
			$this->salida .= "											<tr>\n";
			$this->salida .= "												<td align=\"center\">\n";
			$this->salida .= "													<fieldset><legend class=\"field\">Adicionar Medicamentos Al Nuevo Grupo</legend>\n";			
			$this->salida .= "													".$this->CrearBuscador()."\n";
			$this->salida .= "													</fieldset>\n";			
			$this->salida .= "												</td>\n";
			$this->salida .= "											</tr>\n";
			$this->salida .= "										</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"soluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"soluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorS\" ></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"gruposolucion\" action=\"\" >\n";
			$this->salida .= "													<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" style=\"text-indent:8pt\">NOMBRE DEL GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "																<input type='text' class='input-text' size='52' name = 'nombre_grupo' value =\"\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr >\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= " 																<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" id=\"tablaplantillasM\">\n";
			$this->salida .= "																	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "  																	<td align=\"center\" colspan=\"6\">PLANTILLAS</td>\n";
			$this->salida .= "																	</tr>\n";
			$datos = $this->Plantillas;
			for($i=0; $i<sizeof($datos); $i=$i+3)
			{
				$this->salida .= "																		<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "																			<td width=\"1%\" id=\"S".$datos[$i]['hc_modulo']."\">\n";
				$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantillaM('".$datos[$i]['hc_modulo']."','".$datos[$i]['descripcion']."');\">\n";
				$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
				$this->salida .= "																				<a>\n";
				$this->salida .= "																			</td>\n";
				$this->salida .= "																			<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i]['descripcion'])."</td>\n";
				if($datos[$i+1]['descripcion'])
				{
					$this->salida .= "																			<td width=\"1%\" id=\"S".$datos[$i+1]['hc_modulo']."\">\n";
					$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantillaM('".$datos[$i+1]['hc_modulo']."','".$datos[$i+1]['descripcion']."');\">\n";
					$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
					$this->salida .= "																				<a>\n";
					$this->salida .= "																			</td>\n";
					$this->salida .= "																			<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i+1]['descripcion'])."</td>\n";
					if($datos[$i+2]['descripcion'])
					{
						$this->salida .= "																			<td width=\"1%\" id=\"S".$datos[$i+2]['hc_modulo']."\">\n";
						$this->salida .= "																				<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantillaM('".$datos[$i+2]['hc_modulo']."','".$datos[$i+2]['descripcion']."');\">\n";
						$this->salida .= "																					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
						$this->salida .= "																				<a>\n";
						$this->salida .= "																			</td>\n";
						$this->salida .= "																			<td width=\"34%\" class=\"normal_10AN\">".ucwords($datos[$i+2]['descripcion'])."</td>\n";
					}
					else
					{
						$this->salida .= "																			<td colspan=\"2\"></td>\n";
					}
				}
				else
				{
					$this->salida .= "																			<td colspan=\"4\"></td>\n";
				}
				$this->salida .= "																		</tr>\n";
			}
			$this->salida .= "																	</table>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "																<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																	<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarSolucion(document.gruposolucion);\">\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "														</table>\n";
			$this->salida .= "													</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "							</div>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crea la interfaz donde se crearan las soluciones y se 
		* adicionaran a un grupo
		*
		* @return boolean
		***********************************************************************************/
		function FormaCrearSoluciones()
		{
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Soluciones.js', $contenedor='app', $modulo='MedicamentosSoluciones');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			
			$this->CrearSoluciones();
			
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function EvaluarMedicamento(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.nombre_grupo.value == '' )\n";
			$this->salida .= "				document.getElementById('error').innerHTML = '<center class=\"label_error\">SE DEBE INGRESAR EL NOMBRE DEL GRUPO</center><br>'\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				envio = new Array();\n";
			$this->salida .= "				envio[0] = objeto.nombre_grupo.value;\n";
			$this->salida .= "				CrearGrupoMedicamento(envio);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EvaluarSolucion(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.nombre_grupo.value == '' )\n";
			$this->salida .= "				document.getElementById('errorS').innerHTML = '<center class=\"label_error\">SE DEBE INGRESAR EL NOMBRE DEL GRUPO</center><br>'\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				envio = new Array();\n";
			$this->salida .= "				envio[0] = objeto.nombre_grupo.value;\n";
			$this->salida .= "				CrearGrupoSolucion(envio);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function LimpiarCampos(op)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			document.buscador.producto.value = '';\n";
			$this->salida .= "			document.buscador.principio_activo.value = '';\n";
			$this->salida .= "			if(op == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= " 				document.creacionSoluciones.nombresulucion.value = '';\n";
			$this->salida .= " 				document.creacionSoluciones.gruposolucion.selectedIndex = 0;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function LimpiarCamposClasificacion(op)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			document.buscadorClasifica.producto.value = '';\n";
			$this->salida .= "			document.buscadorClasifica.principio_activo.value = '';\n";
			$this->salida .= "			if(op == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= " 				document.clasificacionSoluciones.grupos.selectedIndex = 0;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function OcultarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){alert(error)}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ImprimirL(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			label = 'SIN CLASIFICACION';\n";
			$this->salida .= "			if(objeto.grupos.value != '-1')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				sw = objeto.grupos.value.split('*'); \n";
			$this->salida .= "				label = 'GRUPO DE CLASIFICACION DE SOLUCIONES';\n";
			$this->salida .= "				if(sw[1] == '0')\n";
			$this->salida .= "					label = 'GRUPO DE CLASIFICACION DE MEDICAMENTOS';\n";
			$this->salida .= "				CrearMedicamentosAsociados(new Array(sw[0]));\n";
			$this->salida .= "			}\n";
			$this->salida .= "			document.getElementById('clasificacion').innerHTML = label;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= ThemeAbrirTabla("CREAR Y DEFINIR SOLUCIONES");
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";

			$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<div class=\"tab-pane\" id=\"Soluciones\">\n";
			$this->salida .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"Soluciones\" ), false ); </script>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"crearSoluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">CREAR SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"crearSoluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorCrear\" style=\"text-align:center\" ></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<form name=\"creacionSoluciones\" method=\"post\">\n";
			$this->salida .= "											<tr>\n";
			$this->salida .= "												<td align=\"center\">\n";
			$this->salida .= "													<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td width=\"20%\" align=\"left\">NOMBRE SOLUCION</td>\n";
			$this->salida .= "															<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "																<input type='text' class='input-text' size='52' name = 'nombresulucion' value =\"".$producto."\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td width=\"20%\" align=\"left\">GRUPO SOLUCION</td>\n";
			$this->salida .= "															<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "																<select name=\"gruposolucion\" class=\"select\">\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>\n";
			
			$soluciong = $this->GSoluciones;
			for($i=0; $i<sizeof($soluciong); $i++)
			{
				$this->salida .= "																	<option value=\"".$soluciong[$i]['grupo_mezcla_id']."\">".$soluciong[$i]['descripcion']."</option>\n";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\" id=\"adicionados\">\n";
			$this->salida .= "																<font class=\"normal_10AN\">NO HAY MEDICAMENTOS SELECCIONADOS</font>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "															<td width=\"20%\" align=\"center\" class=\"modulo_list_claro\" colspan=\"2\">\n";
			$this->salida .= "																<input class=\"input-submit\" name=\"crear\" type=\"button\" onclick=\"EvaluarCreacionSoluciones(document.creacionSoluciones);\" value=\"Crear Solucion\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</td>\n";
			$this->salida .= "											</tr>\n";
			$this->salida .= "										</form>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td>\n";
			$this->salida .= "												<fieldset><legend class=\"field\">Buscar Medicamentos Y Soluciones</legend>\n";			
			$this->salida .= "													".$this->CrearBuscador()."\n";
			$this->salida .= "												</fieldset>\n";			
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"definirSoluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">DEFINIR SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"definirSoluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorDefinir\" style=\"text-align:center\"></div>\n";
			$this->salida .= "									<form name=\"clasificacionSoluciones\">\n";
			$this->salida .= "										<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "											<tr>\n";
			$this->salida .= "												<td>\n";
			$this->salida .= "													<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td width=\"20%\" align=\"left\" style=\"text-indent:8pt\">GRUPO SOLUCION</td>\n";
			$this->salida .= "															<td class=\"modulo_list_claro\" align=\"left\" id=\"selectX\">\n";
			$this->salida .= "																<select name=\"grupos\" class=\"select\" onChange='ImprimirL(document.clasificacionSoluciones)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>";
			
			$soluciong = $this->GMSolucion;
			for($i=0; $i<sizeof($soluciong); $i++)
			{
				$this->salida .= "																		<option value=\"".$soluciong[$i]['grupo_id']."*".$soluciong[$i]['sw_soluciones']."\">".$soluciong[$i]['descripcion']."</option>";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "															<td class=\"modulo_list_claro\">\n";
			$this->salida .= "																<a href=\"javascript:Iniciar();MostrarSpan('Contenedor')\">ADCIONAR GRUPO</a>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td colspan=\"3\" align='center'><label class='normal_10AN' id='clasificacion'>SIN CLASIFICACION</label></td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"3\" id=\"clasificados\">\n";
			$this->salida .= "																<font class=\"normal_10AN\">NO HAY MEDICAMENTOS SELECCIONADOS</font>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"3\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarClasificacion(document.clasificacionSoluciones);\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td>\n";
			$this->salida .= "												<fieldset><legend class=\"field\">Clasificar Medicamentos Como Soluciones</legend>\n";			
			$this->salida .= "													".$this->CrearBuscador('buscadorClasifica','CrearBusquedaClasifica','LimpiarCamposClasificacion','solicitud')."\n";
			$this->salida .= "												</fieldset>\n";			
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "							</div>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.oculta.nombre.value = '';\n";
			$this->salida .= "		document.oculta.pertenencia[0].checked = false;\n";
			$this->salida .= "		document.oculta.pertenencia[1].checked = false;\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, 100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,330, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 330, 0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == titulo) {\n";
			$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:3\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">grupo de clasificación</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Ecreacion' class='label_error' style=\"text-transform: uppercase;text-align:center\"></div>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td width=\"20%\" align=\"left\">NOMBRE:</td>\n";
			$this->salida .= "					<td colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "						<input type='text' class='input-text' style=\"width:100%\" name = 'nombre'>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td align=\"left\">GRUPO DE CLASIFICACION:</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"pertenencia\" value=\"1\" >SOLUCIONES\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"pertenencia\" value=\"0\" >MEDICAMENTOS\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"CrearGrupo(document.oculta)\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	ImprimirL(document.clasificacionSoluciones);\n";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/************************************************************************************
		* Forma donde se crea la interface de modificar las soluciones
		* @return boolean 
		*************************************************************************************/
		function FormaModificarGrupos()
		{
			$this->ModificarGrupos();
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/ModificarSM.js', $contenedor='app', $modulo='MedicamentosSoluciones');
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function EvaluarMedicamento(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			envio = new Array();\n";
			$this->salida .= "			envio[0] = objeto.grupos.value;\n";
			$this->salida .= "			if(envio[0] == '-1' )\n";
			$this->salida .= "				document.getElementById('error').innerHTML = '<b class=\"label_error\">NO SE HA SELECCIONADO NINGUN GRUPO</b><br>';\n";
			$this->salida .= "			else\n";
			$this->salida .= "				ModificarGrupoMedicamento(envio);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EvaluarModificarPlantilla(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			envio = new Array();\n";
			$this->salida .= "			envio[0] = objeto.gruposol.value;\n";
			$this->salida .= "			if(envio[0] == '-1' )\n";
			$this->salida .= "				document.getElementById('errorS').innerHTML = '<b class=\"label_error\">NO SE HA SELECCIONADO NINGUN GRUPO</b><br>';\n";
			$this->salida .= "			else\n";
			$this->salida .= "				ModificarPlantillaSolucion(envio);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function LimpiarCampos(op)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			document.buscador.producto.value = '';\n";
			$this->salida .= "			document.buscador.principio_activo.value = '';\n";
			$this->salida .= "			if(op == 1) document.grupomedicamento.grupos.selectedIndex = 0;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){alert(error)}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function SolucionesPlantillas(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('errorS').innerHTML = '';\n";
			$this->salida .= "			BuscarSolucionesPlantillas(new Array(objeto.gruposol.value));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MedicamentosPlantillas(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "			BuscarMedicamentosPlantillas(new Array(objeto.grupos.value));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarTitle(Seccion)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xShow(Seccion);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarTitle(Seccion)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xHide(Seccion);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= ThemeAbrirTabla("MODIFICAR GRUPOS DE MEDICAMENTOS Y SOLUCIONES");
			$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<div class=\"tab-pane\" id=\"grupos\">\n";
			$this->salida .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"grupos\" ), false ); </script>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"medicamentos\">\n";
			$this->salida .= "									<h2 class=\"tab\">MEDICAMENTOS</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"medicamentos\")); </script>\n";
			$this->salida .= "									<div id=\"error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"grupomedicamento\" action=\"\" >\n";
			$this->salida .= "													<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" style=\"text-indent:8pt\">GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "																<select name=\"grupos\" class=\"select\" onChange='MedicamentosPlantillas(document.grupomedicamento)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>";
			
			$medicag = $this->GMedicamentos;
			foreach($medicag as $key => $datos)
			{
				$this->salida .= "																		<option value=\"".$datos['grupo_id']."\">".$datos['descripcion']."</option>";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "														<tr >\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= " 																<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" id=\"tablaplantillas\">\n";
			$this->salida .= "																	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "  																	<td align=\"center\" class=\"normal_10AN\">PLANTILLAS</td>\n";
			$this->salida .= "																	</tr>\n";
			$this->salida .= "																</table>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\" id=\"adicionados\">\n";
			$this->salida .= "																<font class=\"normal_10AN\">NO HAY MEDICAMENTOS ADICIONADOS</font>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";	
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarMedicamento(document.grupomedicamento);\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td>\n";
			$this->salida .= "												<div id=\"buscador\" style=\"display:none;\">\n";
			$this->salida .= "													<fieldset><legend class=\"field\">Adicionar Medicamentos Al Grupo</legend>\n";			
			$this->salida .= "													".$this->CrearBuscador()."\n";
			$this->salida .= "													</fieldset>\n";
			$this->salida .= "												</div>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"soluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"soluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorS\" style=\"text-align:center\" ></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"gruposolucion\" action=\"\" >\n";
			$this->salida .= "													<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" style=\"text-indent:8pt\">GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "																<select name=\"gruposol\" class=\"select\" onChange='SolucionesPlantillas(document.gruposolucion)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>";
			
			$solug = $this->GSoluciones;
			foreach($solug as $key => $datos)
			{
				$this->salida .= "																		<option value=\"".$datos['grupo_mezcla_id']."\">".$datos['descripcion']."</option>";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr >\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= " 																<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" id=\"tablaplantillasM\">\n";
			$this->salida .= "																	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "  																	<td align=\"center\" class=\"normal_10AN\">PLANTILLAS</td>\n";
			$this->salida .= "																	</tr>\n";
			$this->salida .= "																</table>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\" class=\"normal_10AN\" id=\"soladicionada\">\n";
			$this->salida .= "																SOLUCIONES ADICIONADAS\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarModificarPlantilla(document.gruposolucion);\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "							</div>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	document.grupomedicamento.grupos.selectedIndex = 0;\n";
			$this->salida .= "	document.gruposolucion.gruposol.selectedIndex = 0;\n";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/************************************************************************************
		* Forma donde se crea la interface de eliminar los grupos
		* @return boolean 
		*************************************************************************************/
		function FormaEliminarGrupos()
		{
			$this->ModificarGrupos();
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/EliminarSM.js', $contenedor='app', $modulo='MedicamentosSoluciones');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function EvaluarMedicamento(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			envio = new Array();\n";
			$this->salida .= "			envio[0] = objeto.grupos.value;\n";
			$this->salida .= "			if(envio[0] == '-1' )\n";
			$this->salida .= "				document.getElementById('error').innerHTML = '<b class=\"label_error\">NO SE HA SELECCIONADO NINGUN GRUPO</b><br>';\n";
			$this->salida .= "			else\n";
			$this->salida .= "				ModificarGrupoMedicamento(envio);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EvaluarModificarPlantilla(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			envio = new Array();\n";
			$this->salida .= "			envio[0] = objeto.gruposol.value;\n";
			$this->salida .= "			if(envio[0] == '-1' )\n";
			$this->salida .= "				document.getElementById('errorS').innerHTML = '<b class=\"label_error\">NO SE HA SELECCIONADO NINGUN GRUPO</b><br>';\n";
			$this->salida .= "			else\n";
			$this->salida .= "				ModificarPlantillaSolucion(envio);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function LimpiarCampos(op)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			document.buscador.producto.value = '';\n";
			$this->salida .= "			document.buscador.principio_activo.value = '';\n";
			$this->salida .= "			if(op == 1) document.grupomedicamento.grupos.selectedIndex = 0;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarTitle(Seccion)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xShow(Seccion);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarTitle(Seccion)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xHide(Seccion);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){alert(error)}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function InformacionSoluciones(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('errorS').innerHTML = '';\n";
			$this->salida .= "			BuscarInformacionSoluciones(new Array(objeto.gruposol.value));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EliminarGrupo(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "			BuscarInformacionGrupo(new Array(objeto.grupos.value));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EliminarSolucionesS(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('errorDefinir').innerHTML = '';\n";
			$this->salida .= "			BuscarInformacionSolucionS(new Array(objeto.grupos.value));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EliminarGrupoI(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			indice = objeto.grupos.selectedIndex\n";
			$this->salida .= "			if(indice != '0')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				info = objeto.grupos.options[indice].text;\n";
			$this->salida .= "				valor = objeto.grupos.value;\n";
			$this->salida .= "				xGetElementById('nombreg').innerHTML = info;\n";
			$this->salida .= "				Iniciar();\n";
			$this->salida .= "				MostrarSpan('Contenedor');\n";
			$this->salida .= "				document.oculta.action = \"javascript:EliminarGrupoMedicaento(new Array(valor))\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ConfirmarGrupoSoluciones(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			indice = objeto.grupos.selectedIndex\n";
			$this->salida .= "			if(indice != '0')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				info = objeto.grupos.options[indice].text;\n";
			$this->salida .= "				valor = objeto.grupos.value;\n";
			$this->salida .= "				xGetElementById('nombreg').innerHTML = 'DE SOLIONES: '+info;\n";
			$this->salida .= "				Iniciar();\n";
			$this->salida .= "				MostrarSpan('Contenedor');\n";
			$this->salida .= "				document.oculta.action = \"javascript:EliminarSolucionesS1(new Array(valor))\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ConfirmacionSolucion(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			indice = objeto.gruposol.selectedIndex\n";
			$this->salida .= "			if(indice != '0')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				info = objeto.gruposol.options[indice].text;\n";
			$this->salida .= "				valor = objeto.gruposol.value;\n";
			$this->salida .= "				xGetElementById('nombreg').innerHTML = 'DE SOLIONES: '+info;\n";
			$this->salida .= "				Iniciar();\n";
			$this->salida .= "				MostrarSpan('Contenedor');\n";
			$this->salida .= "				document.oculta.action = \"javascript:EliminarGrupoSoluciones(new Array(valor))\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,330, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 330, 0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == titulo) {\n";
			$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";

			$this->salida .= ThemeAbrirTabla("ELIMINAR GRUPOS DE MEDICAMENTOS Y SOLUCIONES");
			$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<div class=\"tab-pane\" id=\"grupos\">\n";
			$this->salida .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"grupos\" ), false ); </script>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"medicamentos\">\n";
			$this->salida .= "									<h2 class=\"tab\">MEDICAMENTOS</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"medicamentos\")); </script>\n";
			$this->salida .= "									<div id=\"error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"grupomedicamento\" action=\"\" >\n";
			$this->salida .= "													<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" width=\"25%\" style=\"text-indent:8pt\">GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\" id=\"selectgrupoM\">\n";
			$this->salida .= "																<select name=\"grupos\" class=\"select\" onChange='EliminarGrupo(document.grupomedicamento)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>";
			
			$medicag = $this->GMedicamentos;
			foreach($medicag as $key => $datos)
			{
				$this->salida .= "																		<option value=\"".$datos['grupo_id']."\">".$datos['descripcion']."</option>";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			
			$this->salida .= "														<tr class=\"modulo_list_claro\" >\n";
			$this->salida .= "  														<td colspan=\"2\" align=\"center\" class=\"normal_10AN\" id=\"tablaplantillas\" >INFORMACIÓN</td>\n";
 			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Eliminar Grupo\" onclick=\"EliminarGrupoI(document.grupomedicamento);\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"soluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"soluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorS\" style=\"text-align:center\" ></div>\n";
			$this->salida .= "									<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\">\n";
			$this->salida .= "												<form name=\"gruposolucion\" action=\"\" >\n";
			$this->salida .= "													<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td align=\"left\" width=\"25%\" style=\"text-indent:8pt\">GRUPO</td>\n";
			$this->salida .= "															<td align=\"left\" class=\"modulo_list_claro\" id=\"selectgrupoSm\">\n";
			$this->salida .= "																<select name=\"gruposol\" class=\"select\" onChange='InformacionSoluciones(document.gruposolucion)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>\n";
			
			$solug = $this->GSoluciones;
			foreach($solug as $key => $datos)
			{
				$this->salida .= "																	<option value=\"".$datos['grupo_mezcla_id']."\">".$datos['descripcion']."</option>\n";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\" class=\"normal_10AN\" id=\"infogruposolucion\" >INFORMACIÓN</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Eliminar Solucion\" onClick=\"ConfirmacionSolucion(document.gruposolucion)\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"definirSoluciones\">\n";
			$this->salida .= "									<h2 class=\"tab\">GRUPO DE SOLUCIONES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"definirSoluciones\")); </script>\n";
			$this->salida .= "									<div id=\"errorDefinir\" style=\"text-align:center\"></div>\n";
			$this->salida .= "									<form name=\"clasificacionSoluciones\">\n";
			$this->salida .= "										<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "											<tr>\n";
			$this->salida .= "												<td>\n";
			$this->salida .= "													<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "														<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "															<td width=\"25%\" align=\"left\" style=\"text-indent:8pt\">GRUPO SOLUCION</td>\n";
			$this->salida .= "															<td class=\"modulo_list_claro\" align=\"left\" id=\"selectSolucionesS\">\n";
			$this->salida .= "																<select name=\"grupos\" class=\"select\" onChange='EliminarSolucionesS(document.clasificacionSoluciones)'>\n";
			$this->salida .= "																	<option value=\"-1\">----SELECCIONAR-----</option>";
			
			$soluciong = $this->GMSolucion;
			for($i=0; $i<sizeof($soluciong); $i++)
			{
				$this->salida .= "																		<option value=\"".$soluciong[$i]['grupo_id']."\">".$soluciong[$i]['descripcion']."</option>";
			}
			$this->salida .= "																</select>\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"2\" id=\"clasificados\" class=\"normal_10AN\">\n";
			$this->salida .= "																INFORMACIÓN\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "														<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "															<td align=\"center\" colspan=\"3\">\n";
			$this->salida .= "																<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Eliminar Grupo De Soluciones\" onclick=\"ConfirmarGrupoSoluciones(document.clasificacionSoluciones);\">\n";
			$this->salida .= "															</td>\n";
			$this->salida .= "														</tr>\n";
			$this->salida .= "													</table>\n";
			$this->salida .= "												</form>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "							</div>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">CONFIRMACIÓN</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"label\">\n";
			$this->salida .= "						ESTA SEGURO QUE DESEA ELIMINAR EL GRUPO <label class=\"normal_10AN\" id=\"nombreg\"></label> Y TODA SU INFORMACIÓN?\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";			
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	document.grupomedicamento.grupos.selectedIndex = 0;\n";
			$this->salida .= "	document.clasificacionSoluciones.grupos.selectedIndex = 0;\n";
			$this->salida .= "	document.gruposolucion.gruposol.selectedIndex = 0;\n";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/********************************************************************************** 
		* 
		***********************************************************************************/
		function CrearBuscador($forma ='buscador',$func1='CrearEnvio',$func2 = 'LimpiarCampos',$capa = 'resultado')
		{
			$html .= "<form name=\"$forma\" action=\"javascript:$func1(document.$forma)\" method=\"post\">\n";
			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"center\" colspan=\"7\">BUSCAR MEDICAMENTOS </td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"hc_table_submodulo_list_title\">\n";		
			$html .= "			<td width=\"%\" class=\"normal_10AN\">PRODUCTO:</td>\n";
			$html .= "			<td width=\"%\" align='center'>\n";
			$html .= "				<input type='text' class='input-text' size = 22 name = 'producto' value =\"".$producto."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td width=\"%\" class=\"normal_10AN\">PRINCIPIO ACTIVO:</td>";
			$html .= "			<td width=\"%\" align='center' >\n";
			$html .= "				<input type='text' class='input-text' size = 22 name = 'principio_activo' value =\"".$principio_activo."\" >\n";
			$html .= "			</td>\n" ;
			$html .= "			<td width=\"%\" align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" name=\"buscar\" type=\"submit\" onclick=\"$func1(document.$forma)\" value=\"Buscar\">\n";
			$html .= "				<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"$func2(0)\" value=\"Limpiar Campos\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= "<div name=\"$capa\" id=\"$capa\" style=\"display:none;border:1px solid #AFAFAF;\"></div>\n";
			
			return $html;
		}
	}
?>