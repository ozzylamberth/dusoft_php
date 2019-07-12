<?php
	/**************************************************************************************  
	* $Id: app_AuditoriaCuentas_userclasses_HTML.php,v 1.23 2009/03/19 20:32:41 cahenao Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.23 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_AuditoriaCuentas_userclasses_HTML extends app_AuditoriaCuentas_user
	{
		function app_AuditoriaCuentas_userclasses_HTML()
		{
			return true;
		}
		/**********************************************************************************
		* Muestra el menu de las empresas y centros de utilidad 
		* 
		* @access public 
		***********************************************************************************/
		function FormaMostrarMenuEmpresasAuditoria($forma)
		{
			$this->salida .= $forma;
			return true;
		}
		/********************************************************************************** 
		* Muestra el menú principal de recibos de caja
		* 
		* @access public 
		***********************************************************************************/ 
		function FormaMostrarMenuPrincipalAuditoria()
		{						
			$this->salida  = ThemeAbrirTabla('AUDITORÍA DE GLOSAS - OPCIONES');
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td width=\"40%\">\n";
			$this->salida .= "			<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\">MENÚ</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientes')."\"><b>RESPONDER GLOSAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientesConciliar')."\"><b>CONCILIAR GLOSAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".ModuloGetURL('app','AuditoriaCuentas','user','MostrarActasConciliacion')."\"><b>ACTAS CERRADAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";			
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma donde se muestra el listado de clientes 
		***********************************************************************************/
		function FormaMostrarClientes()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTAR CLIENTES");
			if(!empty($_SESSION['Auditoria']['empresa']))
			{	
				$this->salida .= "<script language=\"javascript\">\n";
				$this->salida .= "	function mOvr(src,clrOver)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		src.style.background = clrOver;\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function mOut(src,clrIn)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		src.style.background = clrIn;\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function LimpiarCampos(frm)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		for(i=0; i<frm.length; i++)\n";
				$this->salida .= "		{\n";
				$this->salida .= "			switch(frm[i].type)\n";
				$this->salida .= "			{\n";
				$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
				$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
				$this->salida .= "			}\n";
				$this->salida .= "		}\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function acceptNum(evt)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		var nav4 = window.Event ? true : false;\n";
				$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
				$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
				$this->salida .= "	}\n";
				$this->salida .= "</script>\n";
				$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<form name=\"buscador\" action=\"".$this->action['buscar']."\" method=\"post\">\n";
				$this->salida .= "					<fieldset><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
				$this->salida .= "						<table>\n";
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
				$this->salida .= "								<td>\n";
				$this->salida .= "									<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
				$this->salida .= "										<option value='0'>-----SELECCIONAR-----</option>\n";
				
				for($i=0; $i<sizeof($this->Tipos); $i++)
				{
					$sel = "";
					$opciones = explode("/",$this->Tipos[$i]);
					if($this->request['buscador']['tipo_id_tercero'] == $opciones[0])	$sel = " selected ";
					
					$this->salida .= "										<option value='".$opciones[0]."' $sel >".ucwords(strtolower($opciones[1]))."</option>\n";			
				}
				
				$this->salida .= "									</select>\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "							</tr>\n";	
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td class=\"label\">DOCUMENTO</td>\n";
				$this->salida .= "								<td>\n";
				$this->salida .= "									<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$this->request['buscador']['tercero_id']."\">\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "							</tr>\n";
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td class=\"label\">NOMBRE</td>\n";
				$this->salida .= "								<td>\n";
				$this->salida .= "									<input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" size=\"30\" maxlength=\"100\" value=\"".$this->request['buscador']['nombre_tercero']."\">\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "							</tr>\n";
				$this->salida .= "							<tr>\n";
				$this->salida .= "								<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
				$this->salida .= "									<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
				$this->salida .= "									<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
				$this->salida .= "								</td>\n";
				$this->salida .= "							</tr>\n";
				$this->salida .= "						</table>\n";
				$this->salida .= "					</fieldset>\n";
				$this->salida .= "				</form>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				
				$this->salida .= "<form name=\"buscadorfacturas\" action=\"".$this->action['buscar']."\" method=\"post\">\n";
				$this->salida .= "	<table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">FACTURA:</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<select name=\"buscadorf[prefijo]\" class=\"select\">\n";
				
				$Filas = $this->Prefijos;
				for($i=0; $i<sizeof($Filas); $i++)
				{
					($this->request['buscadorf']['prefijo'] == $Filas[$i]['prefijo'])? $sel = "selected":$sel="";
					$this->salida .= "				<option value='".$Filas[$i]['prefijo']."' $sel>".$Filas[$i]['prefijo']."</option>\n";
				}
				
				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"buscadorf[factura_f]\" size=\"25\" onkeypress=\"return acceptNum(event)\" value=\"".$this->request['buscadorf']['factura_f']."\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n"; 
				
				if(!empty($this->request['buscador']) || !empty($this->request['buscadorf']))
				{
					$Clientes = $this->Clientes;
						
					if(sizeof($Clientes) > 0)
					{
						$estilo = "modulo_list_oscuro";
						
						$this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";		
						$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "				<td width=\"25%\"><b>DOCUMENTO</b></td>\n";
						$this->salida .= "				<td width=\"%\"  ><b>NOMBRE CLIENTE</b></td>\n";
						$this->salida .= "				<td width=\"20%\"><b>OPCIONES</b></td>\n";
						$this->salida .= "			</tr>";
						foreach($Clientes as $key => $detalle)
						{
							if($estilo = "modulo_list_oscuro")
							{
							  $estilo= "modulo_list_claro"; $background = "#DDDDDD";
							}
							else
							{
							  $estilo= "modulo_list_oscuro"; $background = "#CCCCCC";
							}
							
							$url = $this->action3.URLRequest(array("tercero_id"=>$detalle['tercero_id'],"tipo_id_tercero"=>$detalle['tipo_id_tercero'],
																										 "nombre_tercero"=>$detalle['nombre_tercero'],"combo"=>$this->request['buscadorf']['prefijo'],"numero"=>$this->request['buscadorf']['factura_f']));
							
							$opcion  = "	<img src=\"".GetThemePath()."/images/editar.png\">";
							$opcion .= "		<a class=\"label_error\" href=\"".$url."\" title=\"VER FACTURAS GLOSADAS\">\n";
							$opcion .= "	<font class=\"label-error\">FACTURAS</font></a>\n";
							
							$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
							$this->salida .= "				<td >".$detalle['tipo_id_tercero']."	".$detalle['tercero_id']."</td>\n";
							$this->salida .= "				<td align=\"justify\">".$detalle['nombre_tercero']."</td>\n";
							$this->salida .= "				<td align=\"center\" >".$opcion."</td>\n";						
							$this->salida .= "			</tr>\n";
						}
						$this->salida .= "	</table><br>\n";
											
						$Paginador = new ClaseHTML();
						$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPg);
						$this->salida .= "		<br>\n";
					}
					else
					{
						$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
					}
				}
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
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
		* Funcion donde se realiza la forma que muestra la informacion de todas las 
		* facturas que hay o de las que se buscaron 
		* 
		* @return boolean 
		***********************************************************************************/
		function FormaMostrarInformacionFacturasGlosadas()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTAR FACTURAS GLOSADAS");
			if(!empty($_SESSION['Auditoria']['empresa']))
			{

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
				$this->salida .= "	<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"14%\" height=\"20\">\n";
				$this->salida .= "				ENTIDAD: \n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<b class=\"label-mark\">".$this->TerceroNombre."</b>\n";			
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">\n";
				$this->salida .= "				".$this->TerceroTipoId."\n";			
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td >\n";
				$this->salida .= "				<b class=\"label-mark\">".$this->TerceroDocumento."</b>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<table width=\"60%\" align=\"center\" >\n";		
				$this->salida .= "		<tr><td>\n";
				$this->salida .= "			".$this->BuscadorEnviosFacturas();
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "	</table>\n";
				
				if($_SESSION['SqlBuscarFA'])
				{
					$cont = 0;
					$Facturas = $this->ObtenerDatosFacturasGlosadas();
					if(sizeof($Facturas) > 0)
					{
						$this->salida .= "	<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";		
						$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "				<td width=\"10%\"><b>Nº GLOSA</b></td>\n";
						$this->salida .= "				<td width=\"10%\"><b>FECHA</b></td>\n";
						$this->salida .= "				<td width=\"12%\"><b>Nº FACTURA</b></td>\n";
						$this->salida .= "				<td width=\"10%\"><b>Nº ENVIO</b></td>\n";
						$this->salida .= "				<td width=\"15%\"><b>V. FACTURA</b></td>\n";
						$this->salida .= "				<td width=\"15%\"><b>V. GLOSA</b></td>\n";
						$this->salida .= "				<td width=\"12%\"><b>ESTADO</b></td>\n";
						$this->salida .= "				<td width=\"%\"><b>OPCIONES</b></td>\n";
						$this->salida .= "			</tr>";
						for($i=0; $i< sizeof($Facturas); $i++ )
						{
							if($i % 2 == 0)
							{
							  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
							}
							else
							{
							  $estilo='modulo_list_claro'; $background = "#DDDDDD";
							}
							
							$action3 = MOduloGetURL('app','AuditoriaCuentas','user','ConsultarInformacionGlosa',
													 						 array("pagina1"=>$this->paginaActual,"tercero_id"=>$this->TerceroDocumento,"sistema"=>$Facturas[$i]['sistema'],
																						 "tipo_id_tercero"=>$this->TerceroTipoId ,"num_envio"=>$Facturas[$i]['envio_id'],
																						 "nombre_tercero"=>$this->TerceroNombre,"glosa_id"=>$Facturas[$i]['glosa_id'],"pagina"=>$_REQUEST['pagina']));
							
							$opcion  = "		<a class=\"label_error\" href=\"".$action3."\" title=\"RESPONDER GLOSA\">\n";
							$opcion .= "		<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">";
							$opcion .= "		<b class=\"label-error\">RESPONDER</b></a>\n";

							$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";			
							$this->salida .= "				<td align=\"right\" >".$Facturas[$i]['glosa_id']."</td>\n";
							$this->salida .= "				<td align=\"center\">".$Facturas[$i]['fecha_glosa']."</td>\n";
							$this->salida .= "				<td align=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
							$this->salida .= "				<td align=\"right\" >".$Facturas[$i]['envio_id']."</td>\n";
							$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."&nbsp;</td>\n";
							$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['valor_glosa'])."&nbsp;</td>\n";
							$this->salida .= "				<td align=\"center\"><b class=\"label_mark\">".$Facturas[$i]['estado']."</b></td>\n";
							$this->salida .= "				<td align=\"center\">$opcion</td>\n";						
							$this->salida .= "			</tr>\n";
							
							if($Facturas[$i]['sw_estado'] != '1') $cont++;
						}
						$this->salida .= "	</table>\n";
						$this->salida .= "		<br>\n";
						
						if($cont > 0)
						{
							$reporte = new GetReports();
							$mostrar = $reporte->GetJavaReport('app','AuditoriaCuentas','respuestagrupal',array("trid"=>$this->TerceroTipoId,"trdc"=>$this->TerceroDocumento),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$funcion = $reporte->GetJavaFunction();			
							$this->salida .= "		".$mostrar."\n";
							$this->salida .= "	<table align=\"center\">\n";						
							$this->salida .= "			<td align=\"center\">\n";
							$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Respuestas\" onclick=\"$funcion\">\n";
							$this->salida .= "			</td>\n";
							$this->salida .= "	</table>\n";
						}
						
						$Paginador = new ClaseHTML();
						$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionP);
						$this->salida .= "		<br>\n";
					}
					else
					{
						$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
					}
				}
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
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
		* Forma donde se muestra la informacion de la factura cuando se ha glosado 
		***********************************************************************************/
		function FormaMostrarConsultaGlosa()
		{
			$this->salida .= ThemeAbrirTabla("INFORMACIÓN FACTURA GLOSADA ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function IsNumeric(valor)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var log = valor.length; \n";
			$this->salida .= "			var sw='S';\n";
			$this->salida .= "			var puntos = 0;\n";
			$this->salida .= "			for (x=0; x<log; x++)\n";
			$this->salida .= "			{ \n";
			$this->salida .= "				v1 = valor.substr(x,1);\n";
			$this->salida .= "				v2 = parseInt(v1);\n";
			$this->salida .= "				//Compruebo si es un valor numérico\n";
			$this->salida .= "				if(v1 == '.')\n";
			$this->salida .= "				{\n";
			$this->salida .= "					puntos ++;\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else if (isNaN(v2)) \n";
			$this->salida .= "				{ \n";
			$this->salida .= "					sw= 'N';\n";
			$this->salida .= "					break;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			if(log == 0) sw = 'N';\n";
			$this->salida .= "			if(puntos > 1) sw = 'N';\n";
			$this->salida .= "			if(sw=='S')\n"; 
			$this->salida .= "				return true;\n";
			$this->salida .= "			return false;\n";
			$this->salida .= "		} \n";
			$this->salida .= "		function AceptarGlosaNota(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(IsNumeric(objeto.valor_aceptado.value) && \n";
			$this->salida .= "					IsNumeric(objeto.valor_noaceptado.value))\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(objeto.valor_aceptado.value <= 0 &&\n";
			$this->salida .= "					objeto.valor_noaceptado.value <= 0)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					ele = document.getElementById('error');\n";
			$this->salida .= "					ele.innerHTML = 'POR FAVOR INGRESAR ALMENOS UNO DE LOS VALORES MAYOR QUE CERO';\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.action = \"".$this->action['nota']."\";\n";
			$this->salida .= "					objeto.submit();\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				ele = document.getElementById('error');\n";
			$this->salida .= "				ele.innerHTML = 'LOS VALORES DE LA GLOSA NO SON CORRECTOS';\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function NoAceptarGlosaNota(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.action = \"".$this->action['no_aceptar']."\";\n";
			$this->salida .= "			objeto.submit();\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarValor(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.valor_aceptado.value = (objeto.valor_glosa.value - objeto.valor_noaceptado.value);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarValorNoAceptado(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.valor_noaceptado.value = (objeto.valor_glosa.value - objeto.valor_aceptado.value);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacion()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action4."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "		<table align=\"center\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td align=\"left\" width=\"20%\">&nbsp;&nbsp;ENTIDAD</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"55%\" colspan=\"3\">".$this->TerceroNombre."</td>\n";
			$this->salida .= "				<td align=\"left\" width=\"8%\" >&nbsp;&nbsp;".$this->TerceroTipo."</td>\n";
			$this->salida .= "				<td width=\"17%\"  class=\"modulo_list_claro\">".$this->TerceroId."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td align=\"left\" >&nbsp;&nbsp;FACTURA Nº</td>\n";
			$this->salida .= "				<td align=\"left\" width=\"30%\" class=\"modulo_list_claro\">".$this->FacturaNumero;
			$this->salida .= "				<td align=\"left\" width=\"8%\" >&nbsp;&nbsp;TOTAL</td>\n";
			$this->salida .= "				<td align=\"right\" class=\"modulo_list_claro\">$".formatovalor($this->FacturaTotal);
			$this->salida .= "				<td align=\"left\" >&nbsp;&nbsp;FECHA</td>\n";
			$this->salida .= "				<td class=\"modulo_list_claro\">".$this->FacturaRegistro;
			$this->salida .= "			</tr>\n";
			if($this->PlanDescripcion)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td align=\"left\" >&nbsp;&nbsp;PLAN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanDescripcion."</td>\n";
				$this->salida .= "				<td align=\"left\" colspan=\"2\">&nbsp;&nbsp;Nº CONTRATO</td>\n";
				$this->salida .= "				<td align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">".$this->PlanNumContrato."</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td align=\"left\" >&nbsp;&nbsp;RESPONSABLE</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->GlosaResponsable."</td>\n";
			$this->salida .= "				<td align=\"left\" colspan=\"2\">&nbsp;&nbsp;FECHA REGISTRO</td>\n";
			$this->salida .= "				<td align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">".$this->GlosaRegistro."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "		<table align=\"center\" cellpading=\"0\" width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\" width=\"40%\"><b>FECHA DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "						".$this->GlosaFecha."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			
			if($this->GlosaMotivo != "" AND $this->GlosaMotivo != 'NINGUNO')
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>MOTIVO DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >\n";
				$this->salida .= "					".$this->GlosaMotivo."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			else
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>C. GENERAL / ESPECIFICO</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >\n";
				$this->salida .= "					".$this->GlosaCG." / ".$this->GlosaCE."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->GlosaClasificacion != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>CLASIFICACIÓN DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->GlosaClasificacion."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->GlosaAuditor != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>AUDITOR</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->GlosaAuditor."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->GlosaObservacion != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>DESCRIPCIÓN</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">";
				$this->salida .= "					".$this->GlosaObservacion ."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->GlosaDocInterno != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>DOCUMENTO INTERNO DEL CLIENTE Nº</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->GlosaDocInterno."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			

			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>VALOR DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaValor)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>VALOR ACEPTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaAceptado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:11pt\"><b>VALOR NO ACEPTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaNoAceptado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";

			$this->salida .= "		</table>\n";
			
			$action3 = ModuloGetURL('app','AuditoriaCuentas','user','AceptarGlosaFactura',$this->Arreglo);
			
			$this->salida .= "	<br>\n";
			$this->salida .= "	<center>\n";
			$this->salida .= "		<div width=\"50%\" id=\"error\" class=\"label_error\">\n";
			$this->salida .= "			".$this->frmError["MensajeError"]."\n";
			$this->salida .= "		</div>\n";
			$this->salida .= "	</center>\n";
			$this->salida .= "	<form name=\"aceptarglosafactura\" action=\"".$this->action3."\" method=\"post\" >\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">RESPONDER LA GLOSA DE LA FACTURA</legend>\n";
			$this->salida .= "					<table align=\"center\" class=\"modulo_table_list\" width=\"100%\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td>VALOR GLOSA</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR NO ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "						</tr>\n";	
			$this->salida .= "						<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "							<td align=\"center\">".formatoValor($this->GlosaValor)."</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.aceptarglosafactura)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"valor_glosa\" id=\"valor_glosa\" value=\"".$this->GlosaValor."\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" value=\"".$this->GlosaAceptado."\"\>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.aceptarglosafactura)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" value=\"".$this->GlosaNoAceptado."\"\>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td>\n";
			$this->salida .= "								<a href=\"javascript:IngresarObservacion()\">\n";
			$this->salida .= "									<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
			$this->salida .= "								</a>\n";
			$this->salida .= "							</td>\n";						
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "					<table align=\"center\" width=\"100%\">\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Aceptar Glosa\">\n";
			$this->salida .= "							</td>\n";
			if($this->CrearNota == '1')
			{
				$this->salida .= "							<td align=\"center\">\n";
				$this->salida .= "								<input type=\"button\" class=\"input-submit\" value=\"Aceptar - Crear Nota\" onclick=\"AceptarGlosaNota(document.aceptarglosafactura)\">\n";
				$this->salida .= "							</td>\n";
			}
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"button\" class=\"input-submit\" value=\"No Aceptar\" onclick=\"NoAceptarGlosaNota(document.aceptarglosafactura)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";	
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";			
			$this->salida .= "	</form>\n";

			if($this->GlosaSwTotal == "0")
			{
				$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
				$this->salida .= "			<tr>\n";
				
				$cuentas = $this->ObtenerCantidadCuentasFactura($this->GlosaId);
				
				$this->Arreglo['cantidad'] = sizeof($cuentas);				
				$metodo = "MostrarGlosaCuentas";
				
				if(sizeof($cuentas) == 1)
				{ 
					$metodo = "MostrarInformacionCuentaGlosada";
					$this->Arreglo['numero_cuenta'] = $cuentas[0];
				}
				
				$glosas  = $this->ContarCuentasGlosadas($this->GlosaId);
				if($glosas > 0)
				{
					$action4 = ModuloGetURL('app','AuditoriaCuentas','user',$metodo,$this->Arreglo);
					$this->salida .= "				<td align=\"center\">\n";
					$this->salida .= "					<a class=\"label_error\" href=\"".$action4."\">RESPONDER GLOSA CUENTAS</a>\n";
					$this->salida .= "				</td>\n";
				}
				
				$this->salida .= "			</tr>\n";
				$this->salida .= "		</table>\n";
			}

			$this->salida .= "	<br><table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Forma donde se muestra la informacion de las cuentas 
		***********************************************************************************/
		function FormaMostrarInformacionCuentas()
		{
			$this->salida .= ThemeAbrirTabla("CUENTAS DE LA FACTURA Nº ".$this->Factura);
			$this->salida .= "	<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$DatosCuentas = $this->ObtenerInformacionDetalleCuentas($this->GlosaId);
			if(sizeof($DatosCuentas) > 0)
			{
				$this->salida .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">Nº CUENTA</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"15%\">IDENTIFICACION</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"25%\">PACIENTE</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"35%\">PLAN</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\" width=\"15%\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				for($i=0; $i<sizeof($DatosCuentas); $i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$Datos = explode("*",$DatosCuentas[$i]);
					

					$action1 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarInformacionCuentaGlosada',
	 								   		 array("numero_cuenta"=>$Datos[0],"pagina"=>$_REQUEST['pagina'],"pagina1"=>$_REQUEST['pagina1'],"num_envio"=>$_REQUEST['num_envio'],
	 								   		 	   "pagina2"=>$this->paginaActual,"glosa_id"=>$_REQUEST['glosa_id'],"factura"=>$_REQUEST['factura'],"retorno"=>"MostrarGlosaCuentas",
	 								   		 	   "tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],"nombre_tercero"=>$_REQUEST['nombre_tercero'],"tercero_id"=>$_REQUEST['tercero_id']));
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td align=\"center\">".$Datos[0]."</td>\n";
					$this->salida .= "			<td >".$Datos[1]."</td>\n";
					$this->salida .= "			<td >".$Datos[2]."</td>\n";
					$this->salida .= "			<td >".$Datos[3]."</td>\n";
					$this->salida .= "			<td >\n";
					$this->salida .= "				<a href=\"".$action1."\" title=\"\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>CUENTA</b></a>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
				$this->salida .= "	<br>\n";
			}
			else
			{
				$this->salida .= "			<center><br><b class=\"label_error\">NO SE ENCONTRO NINGUNA CUENTA ASOCIADA A LA FACTURA</B><br></center>\n";
			}
			
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\">\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Forma donde se muestra la informacion necesaria para glosar una cuenta 
		***********************************************************************************/
		function FormaMostrarInformacionCuentaGlosada()
		{
			$this->salida .= ThemeAbrirTabla("INFORMACIÓN CUENTA");
			$this->salida .= "	<script>\n";
			$this->salida .= "		var arreglo = new Array();\n";
			$this->salida .= "		function pasarValor(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_aceptado[i].value = (objeto.valor_glosa[i].value - objeto.valor_noaceptado[i].value) ;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_aceptado.value = (objeto.valor_glosa.value - objeto.valor_noaceptado.value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarValorNoAceptado(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_noaceptado[i].value = (objeto.valor_glosa[i].value - objeto.valor_aceptado[i].value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_noaceptado.value = (objeto.valor_glosa.value - objeto.valor_aceptado.value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";	
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacion()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action4."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=600,X=200,Y=10,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacionCargo(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var texto = \"\";\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosa_detalle_id[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosa_detalle_id.value;\n";
			$this->salida .= "			}\n";			
			$this->salida .= "			var url=\"".$this->action5."\"+texto;\n";
			$this->salida .= "			window.open(url,'','width=750,height=600,X=200,Y=10,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacionInsumo(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var texto = \"\";\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosa_detalle_id[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosa_detalle_id.value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			var url=\"".$this->action6."\"+texto;\n";
			$this->salida .= "			window.open(url,'','width=750,height=600,X=200,Y=10,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function IsNumeric(valor)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var log = valor.length; \n";
			$this->salida .= "			var sw='S';\n";
			$this->salida .= "			var puntos = 0;\n";
			$this->salida .= "			for (x=0; x<log; x++)\n";
			$this->salida .= "			{ \n";
			$this->salida .= "				v1 = valor.substr(x,1);\n";
			$this->salida .= "				v2 = parseInt(v1);\n";
			$this->salida .= "				//Compruebo si es un valor numérico\n";
			$this->salida .= "				if(v1 == '.')\n";
			$this->salida .= "				{\n";
			$this->salida .= "					puntos ++;\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else if (isNaN(v2)) \n";
			$this->salida .= "				{ \n";
			$this->salida .= "					sw= 'N';\n";
			$this->salida .= "					break;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			if(log == 0) sw = 'N';\n";
			$this->salida .= "			if(puntos > 1) sw = 'N';\n";
			$this->salida .= "			if(sw=='S')\n"; 
			$this->salida .= "				return true;\n";
			$this->salida .= "			return false;\n";
			$this->salida .= "		} \n";
			$this->salida .= "		function CrearNotaCuenta(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			aceptado = objeto.valor_aceptado.value;\n";
			$this->salida .= "			no_aceptado = objeto.valor_noaceptado.value;\n";
			$this->salida .= "			if(IsNumeric(aceptado) && \n";
			$this->salida .= "					IsNumeric(no_aceptado))\n";
			$this->salida .= "			{\n";
			$this->salida .= "				aceptado = aceptado *1;\n";
			$this->salida .= "				no_aceptado = no_aceptado*1;\n";

			$this->salida .= "				if(aceptado <= 0 &&\n";
			$this->salida .= "						no_aceptado <= 0)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					ele = document.getElementById('error');\n";
			$this->salida .= "					ele.innerHTML = 'POR FAVOR INGRESAR ALMENOS UNO DE LOS VALORES MAYOR QUE CERO';\n";
			$this->salida .= "				}\n";
			$this->salida .= "				if((aceptado + no_aceptado) > objeto.valor_glosa.value)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						ele = document.getElementById('error');\n";
			$this->salida .= "						ele.innerHTML = 'LA SUMA DE LOS VALORES ACEPTADOS Y NO ACEPTADOS, NO DEBE SER MAYOR AL VALOR DE LA GLOSA ';\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else\n";
			$this->salida .= "					{\n";
			$this->salida .= "						objeto.action = \"".$this->action['nota']."&opcion_auditoria=1\";\n";
			$this->salida .= "						objeto.submit();\n";
			$this->salida .= "					}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				ele = document.getElementById('error');\n";
			$this->salida .= "				ele.innerHTML = 'LOS VALORES DE LA GLOSA NO SON CORRECTOS';\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;Nº FACTURA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$this->salida .= "				".$this->Factura."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;Nº CUENTA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$this->salida .= "				".$this->Cuenta."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"10%\">\n";
			$this->salida .= "				<b>&nbsp;PLAN</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$this->salida .= "				".$this->PlanDescripcion."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\">\n";
			$this->salida .= "				<b>&nbsp;Nº INGRESO</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$this->salida .= "				".$this->IngresoNum."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;PACIENTE</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"right\" class=\"modulo_list_claro\" colspan=\"2\" width=\"25%\">\n";
			$this->salida .= "				".$this->PacienteIdentificacion." \n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$this->salida .= "				".$this->PacienteNombre."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\">\n";
			$this->salida .= "				<b>&nbsp;COPAGO</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$this->salida .= "				".formatoValor($this->CuentaCuotaPaciente)."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" colspan=\"2\">\n";
			$this->salida .= "				<b>&nbsp;CUOTA MODERADORA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\" >\n";
			$this->salida .= "				".formatoValor($this->CuentaCuotaModeradora)."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" colspan=\"2\" >\n";
			$this->salida .= "				<b>&nbsp;TOTAL EMPRESA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\" width=\"15%\">\n";
			$this->salida .= "				".formatoValor($this->CuentaValorEmpresa)."\n";
			$this->salida .= "				<input type=\"hidden\" name=\"valorcuenta\" value=\"".$this->CuentaValor."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			if($this->GlosaSwTotalCuenta == 0)
			{
				if($this->GlosaValorCopago >0 || $this->GlosaValorCuota > 0)
				{				
					$this->VGlosa += $this->GlosaValorCopago + $this->GlosaValorCuota;

					$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"30%\">\n";
					$this->salida .= "				<b>&nbsp;V. GLOSA POR COPAGO</b>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\" >\n";
					$this->salida .= "				 ".formatoValor($this->GlosaValorCopago)."\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td align=\"left\" width=\"35%\">\n";
					$this->salida .= "				<b>&nbsp;V. GLOSA POR CUOTA MODERADORA</b>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\" >\n";
					$this->salida .= "				".formatoValor($this->GlosaValorCuota)."\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					if($this->GlosaMotivoDescripcion != "")
					{
						$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "			<td align=\"left\" width=\"50%\" colspan=\"2\">\n";
						$this->salida .= "				<b>&nbsp;MOTIVO DE GLOSA</b>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">\n";
						$this->salida .= "				".$this->GlosaMotivoDescripcion."\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "		</tr>\n";
					}
					if($this->GlosaObservacion != "")
					{
						$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "			<td align=\"left\" colspan=\"2\">\n";
						$this->salida .= "				<b>&nbsp;OBSERVACIÓN</b>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">";
						$this->salida .= "				".$this->GlosaObservacion."\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "		</tr>\n";
					}
					
					$this->salida .= "	</table><br>\n";
				}
			}
			else
			{
				$this->VGlosa = $this->CuentaValor;
			}
		
			$this->salida .= "	<br>\n";
			$this->salida .= "	<center>\n";
			$this->salida .= "		<div width=\"50%\" class=\"label_error\" id=\"error\">\n";
			$this->salida .= "			".$this->frmError["MensajeError"]."\n";
			$this->salida .= "		</div>\n";
			$this->salida .= "	</center>\n";
			$this->salida .= "	<form name=\"aceptarglosacuenta\" action=\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">RESPONDER LA GLOSA DE LA CUENTA</legend>\n";
			$this->salida .= "					<table align=\"center\" class=\"modulo_table_list\" width=\"100%\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td>VALOR GLOSA</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR NO ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "						</tr>\n";	
			$this->salida .= "						<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "							<td align=\"center\">".formatoValor($this->VGlosa)."</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.aceptarglosacuenta,0)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"valor_glosa\" id=\"valor_glosa\" value=\"".$this->VGlosa."\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VAceptadoC."\"\>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.aceptarglosacuenta,0)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" $disable value=\"".$this->VNoAceptadoC."\"\>\n";
			$this->salida .= "							</td>\n";
			
			$this->salida .= "							<td>\n";
			$this->salida .= "								<a href=\"javascript:IngresarObservacion()\">\n";
			$this->salida .= "									<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
			$this->salida .= "								</a>\n";
			$this->salida .= "							</td>\n";
									
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "					<table align=\"center\" width=\"100%\">\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"cuentaresponde\" value=\"1\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Aceptar Glosa Cuenta\">\n";
			$this->salida .= "							</td>\n";
			if($this->CrearNota == '1')
			{
				$this->salida .= "							<td align=\"center\">\n";
				$this->salida .= "								<input type=\"button\" class=\"input-submit\" value=\"Aceptar - Crear Nota\" onclick=\"CrearNotaCuenta(document.aceptarglosacuenta)\">\n";
				$this->salida .= "							</td>\n";
			}
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";	
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";			
			$this->salida .= "	</form>\n";
			$this->salida .= "	<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "		".$this->SetStyle("MensajeError2")."\n";
			$this->salida .= "	</table>\n";
	
			$this->salida .= "	<form name=\"aceptarglosar\" action=\"".$this->action3."\" method=\"post\" >\n";
			$this->salida .= "		<input type=\"hidden\" name=\"cargoconcilia\" value=\"".sizeof($this->Cargos)."\">\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valoraceptadocuenta\" value=\"".$this->VAceptadoC."\"\>\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valornoaceptadocuenta\" value=\"".$this->VNoAceptadoC."\"\>\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valorglosacuenta\" value=\"".$this->VGlosa."\"\>\n";
			
			$this->salida .= "		<script>\n";
			$this->salida .= "			function CrearNotaCargosInsumos(objeto)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				ele = objeto.elements.length; \n";
			$this->salida .= "				bol = false; \n";
			$this->salida .= "				aceptado = new Array(); \n";
			$this->salida .= "				no_aceptado = new Array(); \n";
			$this->salida .= "				valor_glosa = new Array(); \n";
			$this->salida .= "				datos = new Array(); \n";
			$this->salida .= "				j = 0; \n";
			$this->salida .= "				for(i=0; i< ele; i++)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					try\n";
			$this->salida .= "					{\n";
			$this->salida .= "						if(objeto.valor_glosa[j].value != undefined)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							bol = true;\n";
			$this->salida .= "							datos[j] = new Array();\n";
			$this->salida .= "							aceptado[j] = objeto.valor_aceptado[j].value;\n";
			$this->salida .= "							no_aceptado[j] = objeto.valor_noaceptado[j].value;\n";
			$this->salida .= "							valor_glosa[j] = objeto.valor_glosa[j].value;\n";
			$this->salida .= "							try\n";
			$this->salida .= "							{\n";
			$this->salida .= "								datos[j][0] = 'CARGO';\n";
			$this->salida .= "								if(objeto.cargo.value == undefined)\n";
			$this->salida .= "									datos[j][1] = objeto.cargo[j].value;\n";
			$this->salida .= "								else\n";
			$this->salida .= "									datos[j][1] = objeto.cargo[j].value;\n";
			$this->salida .= "							}\n";
			$this->salida .= "							catch(error)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								datos[j][0] = 'INSUMO';\n";
			$this->salida .= "								if(objeto.insumo.value == undefined)\n";
			$this->salida .= "									datos[j][1] = objeto.insumo[j].value;\n";
			$this->salida .= "								else\n";
			$this->salida .= "									datos[j][1] = objeto.insumo.value;\n";
			$this->salida .= "							}\n";
			$this->salida .= "							j++;\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "					catch(error)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						if(!bol)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							if(objeto.valor_aceptado.value != undefined);\n";
			$this->salida .= "							{\n";
			$this->salida .= "								datos[0] = new Array();\n";
			$this->salida .= "								aceptado[0] = objeto.valor_aceptado.value;\n";
			$this->salida .= "								no_aceptado[0] = objeto.valor_noaceptado.value;\n";
			$this->salida .= "								valor_glosa[0] = objeto.valor_glosa.value;\n";
			$this->salida .= "								try\n";
			$this->salida .= "								{\n";
			$this->salida .= "									datos[0][0] = 'CARGO';\n";
			$this->salida .= "									datos[0][1] = objeto.cargo.value;\n";
			$this->salida .= "								}\n";
			$this->salida .= "								catch(error)\n";
			$this->salida .= "								{\n";
			$this->salida .= "									datos[0][0] = 'insumo';\n";
			$this->salida .= "									datos[0][1] = objeto.insumo.value;\n";
			$this->salida .= "								}\n";
			$this->salida .= "							}\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				for(i=0; i< aceptado.length; i++)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					if(IsNumeric(aceptado[i]) && IsNumeric(no_aceptado[i]))\n";
			$this->salida .= "					{\n";
			$this->salida .= "						aceptado[i] = aceptado[i] *1;\n";
			$this->salida .= "						no_aceptado[i] = no_aceptado[i]*1;\n";
			$this->salida .= "						if((aceptado[i] + no_aceptado[i]) > valor_glosa[i])\n";
			$this->salida .= "						{\n";
			$this->salida .= "							ele = document.getElementById('error1');\n";
			$this->salida .= "							ele.innerHTML = 'LA SUMA DE LOS VALORES ACEPTADOS Y NO ACEPTADOS,PARA EL '+datos[i][0]+' '+datos[i][1]+' NO DEBE SER MAYOR AL VALOR DE LA GLOSA ';\n";
			$this->salida .= "							return;\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else\n";
			$this->salida .= "					{\n";
			$this->salida .= "						ele = document.getElementById('error1');\n";
			$this->salida .= "						ele.innerHTML = 'LOS VALORES DE LA GLOSA PARA EL '+datos[i][0]+' '+datos[i][1]+' NO SON CORRECTOS';\n";
			$this->salida .= "						return;\n";			
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				objeto.action = \"".$this->action['nota']."&opcion_auditoria=2\";\n";
			$this->salida .= "				objeto.submit();\n";
			$this->salida .= "			}\n";
			$this->salida .= " function GetConceptos(valor,vector){\n";
			$this->salida .= " var vect;\n";
			$this->salida .= " for(i=0; i<vector.length; i++){\n";
			$this->salida .= "	switch(vector[i].type)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		case 'radio':  \n";
			$this->salida .= "			vect = vector[i].value.split('||//')[0];\n";
			$this->salida .= "			document.getElementById('concepto'+vect).style.display = 'none';\n";
			$this->salida .= "		break;\n";
			$this->salida .= "	}\n";
			$this->salida .= " \n";
			$this->salida .= " }\n";
			$this->salida .= " identificador = 'concepto'+valor;\n";
			$this->salida .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$this->salida .= "  document.getElementById(identificador).style.display = 'block';\n";
			$this->salida .= "  }else{ \n";
			$this->salida .= "   document.getElementById(identificador).style.display = 'none';\n";
			$this->salida .= "  } ;\n";
			$this->salida .="  }\n";
			$this->salida .= " function GetConceptosCargos(valor,vector,transaccion){\n";
			$this->salida .= " var vect;\n";
			$this->salida .= " for(i=0; i<vector.length; i++){\n";
			$this->salida .= "	switch(vector[i].type)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		case 'radio':  \n";
			$this->salida .= "			vect = vector[i].value.split('||//')[0];\n";
			$this->salida .= "			document.getElementById('conceptocargos_'+vect+'_'+transaccion).style.display = 'none';\n";
			$this->salida .= "		break;\n";
			$this->salida .= "	}\n";
			$this->salida .= " \n";
			$this->salida .= " }\n";
			$this->salida .= " identificador = 'conceptocargos_'+valor+'_'+transaccion;\n";
			$this->salida .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$this->salida .= "  document.getElementById(identificador).style.display = 'block';\n";
			$this->salida .= "  }else{ \n";
			$this->salida .= "   document.getElementById(identificador).style.display = 'none';\n";
			$this->salida .= "  } ;\n";
			$this->salida .="  }\n";
			$this->salida .= " function GetConceptosInsumos(valor,vector,transaccion){\n";
			$this->salida .= " var vect;\n";
			$this->salida .= " for(i=0; i<vector.length; i++){\n";
			$this->salida .= "	switch(vector[i].type)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		case 'radio':  \n";
			$this->salida .= "			vect = vector[i].value.split('||//')[0];\n";
			$this->salida .= "			document.getElementById('conceptoinsumos_'+vect+'_'+transaccion).style.display = 'none';\n";
			$this->salida .= "		break;\n";
			$this->salida .= "	}\n";
			$this->salida .= " \n";
			$this->salida .= " }\n";
			$this->salida .= " identificador = 'conceptoinsumos_'+valor+'_'+transaccion;\n";
			$this->salida .= " if(document.getElementById(identificador).style.display == 'none'){\n";
			$this->salida .= "  document.getElementById(identificador).style.display = 'block';\n";
			$this->salida .= "  }else{ \n";
			$this->salida .= "   document.getElementById(identificador).style.display = 'none';\n";
			$this->salida .= "  } ;\n";
			$this->salida .="  }\n";
			$this->salida .= "</script>\n";
			//CONCEPTO GENERAL

			$j = 0;
			if(sizeof($this->Cargos) > 0)
			{
				$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"8%\" ><b>FECHA</b></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>TRANSACCIÓN</b></td>\n";
				$this->salida .= "			<td width=\"7%\" ><b>CARGO</b></td>\n";
				$this->salida .= "			<td width=\"8%\" ><b>TARIFARIO</b></td>\n";
				$this->salida .= "			<td width=\"26%\"><b>DESCRIPCIÓN</b></td>\n";
				$this->salida .= "			<td width=\"9%\" ><b>V. GLOSA</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>V. ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "			<td width=\"12%\"><b>V. NO ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "		</tr>";
				
				for($i=0; $i< sizeof($this->Cargos);$i++)
				{
					
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$Celdas = $this->Cargos[$i];
					
					$marca = "";
					if(strlen($Celdas['descripcion']) > 28)
					{
						$marca = " title =\"".$Celdas['descripcion']."\" ";
						$Celdas['descripcion'] = substr($Celdas['descripcion'],0,28)."...";
					}
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas['fecha']."</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas['transaccion']."&nbsp;&nbsp;</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas['cargo_cups']."</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas['tarifario_id']."</td>\n";
					$this->salida .= "			<td align=\"justify\" $marca>".$Celdas['descripcion']."</td>\n";
					
					if($Celdas['glosa_detalle_cargo_id'])
					{
						$this->salida .= "			<td align=\"right\" >$".formatoValor($Celdas['glosa'])."&nbsp;\n";
						$this->salida .= "				<input type=\"hidden\" name=\"valor_glosa[$j]\" id=\"valor_glosa\" value=\"".$Celdas['glosa']."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"transaccion[$j]\" value=\"".$Celdas['transaccion']."\">\n";
						$this->salida .= "				<input type=\"hidden\" id=\"cargo\" value=\"".$Celdas['cargo_cups']."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"glosa_detalle_id[$j]\" id=\"glosa_detalle_id\" value=\"".$Celdas['glosa_detalle_cargo_id']."\">\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.aceptarglosar,".$j.")\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.aceptarglosar,".$j.")\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" $disable value=\"".$this->VNoAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<a href=\"javascript:IngresarObservacionCargo(document.aceptarglosar,$j)\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						$j++;
					}
					else
					{
						$this->salida .= "			<td colspan=\"6\" align=\"center\" >\n";
						$this->salida .= "				<b>CARGO NO GLOSADO</b>\n";
						$this->salida .= "			</td>\n";						
					}
					
					if($Celdas['glosa_detalle_cargo_id'])
					{
								$ConceptosGenerales = $this->ObtenerConceptosGenerales();
						$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
						$this->salida .= "		<td align=\"right\" colspan=\"5\"><B>C. GENERAL / ESPECIFICO</B></td>\n";
						$this->salida .= "		</td>\n";
						if($Celdas['descripcion_concepto_general'])
						{
							$this->salida .= "		<td align=\"center\" colspan=\"6\"><B>".$Celdas['descripcion_concepto_general']." / ".$Celdas['descripcion_concepto_especifico']."</B></td>\n";
							$this->salida .= "		</td>\n";
						}
						else
						{
							$this->salida .= "				<td colspan=\"6\" align=\"right\" class=\"$estilo\"  onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); colspan=\"1\">\n";
							$this->salida .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosCargos(this.value,document.aceptarglosar,'".$Celdas['transaccion']."');\">\n";
							$this->salida .= "						<option value='V' >-------SELECCIONAR-------</option>\n";
							for($j=0; $j<sizeof($ConceptosGenerales); )
							{
								//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
								$k = $j;
								while($ConceptosGenerales[$j]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
								{
									$k++;
								}
								$j = $k;
								$sl = "";
								if(trim($detalle['codigo_concepto_general']) == trim($ConceptosGenerales[$j-1]['codigo_concepto_general'])) $sl= "selected";
								$this->salida .= "					<option value='".$ConceptosGenerales[$j-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$j-1]['descripcion_concepto_general']."</option>\n";
							}
		
							$this->salida .= "					</select>\n";
							$this->salida .= "				</td>\n";
							//$this->salida .= "		</tr>\n";
							$this->salida .= "		</tr>\n";
							$this->salida .= "			<tr class=\"".$estilo."\">\n";
							$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"11\">\n";
							for($l=0; $l<sizeof($ConceptosGenerales);)
							{
								$this->salida .= "<div id='conceptocargos_".$ConceptosGenerales[$l][codigo_concepto_general]."_".$Celdas['transaccion']."' style=\"display:none\">";
								$this->salida .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
								$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "					</tr>\n";
								$k = $l;
								while ($ConceptosGenerales[$l][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
								{
									$this->salida .= "				<tr class=\"modulo_table_list\">\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"conceptoscargos[".$i."]\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\">\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "				</tr>\n";
									$k++;
								}
								$l = $k;
								$this->salida .= "				</table><br>\n";
								$this->salida .= "</div>";
							}
							$this->salida .= "				</td>\n";
							$this->salida .= "		</tr>\n";
						}
					}
				}
				$this->salida .= "	</table><br>\n";		
			}
			if(sizeof($this->Insumo) > 0)
			{
				$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"14%\"><b>CODIGO PRODUCTO</b></td>\n";
				$this->salida .= "			<td width=\"11%\"><b>CANTIDAD</b></td>\n";
				$this->salida .= "			<td width=\"34%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "			<td width=\"9%\" ><b>V. GLOSA</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>V. ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "			<td width=\"12%\"><b>V. NO ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"3%\" ></td>\n";
				$this->salida .= "		</tr>";
				
				for($i=0; $i< sizeof($this->Insumo);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$Celdas = explode("*",$this->Insumo[$i]);
						
					$this->salida .= "		<tr height=\"21\" class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas[0]."</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas[1]."&nbsp;&nbsp;</td>\n";
					
					$marca = "";
					if(strlen($Celdas[4]) > 39)
					{
						$marca = " title =\"".$Celdas[2]."\" ";
						$Celdas[2] = substr($Celdas[2],0,36)."...";
					}

					$this->salida .= "			<td align=\"justify\">".$Celdas[2]."</td>\n";
					if($Celdas[6])
					{

						$this->salida .= "			<td align=\"right\" >$".formatoValor($Celdas[3])."&nbsp;\n";
						$this->salida .= "				<input type=\"hidden\" name=\"valor_glosa[$j]\" id=\"valor_glosa\" value=\"".$Celdas[3]."\">\n";
						$this->salida .= "				<input type=\"hidden\" id=\"insumo\" name=\"insumo[$j]\" value=\"".$Celdas[0]."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"glosa_detalle_id[$j]\" id=\"glosa_detalle_id\" value=\"".$Celdas[6]."\">\n";
						$this->salida .= "			</td>\n";

						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.aceptarglosar,".$j.")\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.aceptarglosar,".$j.")\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VNoAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<a href=\"javascript:IngresarObservacionInsumo(document.aceptarglosar,$j)\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						$j++;
					}
					else
					{
						$this->salida .= "			<td colspan=\"6\" align=\"center\" >\n";
						$this->salida .= "					<b>INSUMO NO GLOSADO</b>\n";
						$this->salida .= "			</td>\n";						
					}
					
					$this->salida .= "		</tr>\n";
				//*****************
					if($Celdas[6])
					{
						$this->salida .= "		<tr>\n";
						$this->salida .= "				<td style=\"text-align:right;text-indent:8pt\" colspan=\"4\"><b>CONCEPTO GENERAL / ESPECIFICO</b></td>\n";
						
						if($Celdas[8])//['descripcion_concepto_general']
						{
							$this->salida .= "		<td align=\"center\" colspan=\"6\"><B>".$Celdas[8]." / ".$Celdas[9]."</B></td>\n";
							$this->salida .= "		</td>\n";
						}
						else
						{
							$this->salida .= "				<td align=\"right\" class=\"modulo_list_claro\" colspan=\"5\">\n";
							$this->salida .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptosInsumos(this.value,document.aceptarglosar,'".$Celdas[7]."');\">\n";
							$this->salida .= "						<option value='V' >-------SELECCIONAR-------</option>\n";			
								for($j=0; $j<sizeof($ConceptosGenerales); )
								{
									//($this->Concepto == $ConceptosGenerales[$i]['codigo_concepto_general'])? $sel = "selected": $sel = "";
									$k = $j;
									while($ConceptosGenerales[$j]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
									{
										$k++;
									}
									$j = $k;
									$this->salida .= "					<option value='".$ConceptosGenerales[$j-1]['codigo_concepto_general']."' $sl >".$ConceptosGenerales[$j-1]['descripcion_concepto_general']."</option>\n";
								}
							$this->salida .= "					</select>\n";
							$this->salida .= "				</td>\n";
			
							$this->salida .= "			</tr>\n";
				
							$this->salida .= "			<tr class=\"".$estilo."\">\n";
							$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"11\">\n";
							for($l=0; $l<sizeof($ConceptosGenerales);)
							{
								$this->salida .= "<div id='conceptoinsumos_".$ConceptosGenerales[$l][codigo_concepto_general]."_".$Celdas[7]."' style=\"display:none\">";
								$this->salida .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
								$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
								$this->salida .= "						</td>\n";
								$this->salida .= "					</tr>\n";
								$k = $l;
								while ($ConceptosGenerales[$l][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
								{
									$this->salida .= "				<tr class=\"modulo_table_list\">\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"conceptosinsumos[".$i."]\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\">\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
									$this->salida .= "					</td>\n";
									$this->salida .= "				</tr>\n";
									$k++;
								}
								$l = $k;
								$this->salida .= "				</table><br>\n";
								$this->salida .= "</div>";
							}
							$this->salida .= "				</td>\n";
						}
						$this->salida .= "		</tr>\n";
					}
				//*****************
				}
				$this->salida .= "	</table><br>\n";		
			}
			$this->salida .= "		<center>\n";
			$this->salida .= "			<div width=\"50%\" class=\"label_error\" id=\"error1\">\n";
			$this->salida .= "			</div>\n";
			$this->salida .= "		</center>\n";
			$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Aceptar Glosa Cargos e Insumos\">\n";
			$this->salida .= "				</td>\n";			
			if($this->CrearNota == '1')
			{
				$this->salida .= "				<td align=\"center\">\n";
				$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar - Crear Nota\" onclick=\"CrearNotaCargosInsumos(document.aceptarglosar)\">\n";
				$this->salida .= "				</td>\n";	
			}
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";			
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma, donde aparece el listado de clientes a los que
		* se les puede crear un acta de concliacion 
		***********************************************************************************/
		function FormaMostrarClientesConciliar()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTAR CLIENTES");
	
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
			$this->salida .= "	<table width=\"40%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= $this->BuscadorTerceros();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
				
			$Clientes = $this->ObtenerTerceros();
					
			if(sizeof($Clientes) > 0)
			{
				$this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td width=\"25%\"><b>DOCUMENTO</b></td>\n";
				$this->salida .= "				<td width=\"%\"  ><b>NOMBRE CLIENTE</b></td>\n";
				$this->salida .= "				<td width=\"20%\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				for($i=0; $i< sizeof($Clientes); $i++ )
				{
					$Celdas = explode ("*", $Clientes[$i]);
					
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
						$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$action3 = MOduloGetURL('app','AuditoriaCuentas','user','ConciliarCuentas',array("tercero"=>$Celdas[1]."/".$Celdas[2]));
					
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"left\"   >".$Celdas[1]." ".$Celdas[2]."&nbsp;</td>\n";
					$this->salida .= "				<td align=\"justify\">".$Celdas[0]."</td>\n";
					$this->salida .= "				<td align=\"center\" >\n";
					$this->salida .= "					<a class=\"label_error\" href=\"".$action3."\" title=\"CREAR ACTA DE CONCILIACION\">\n";
					$this->salida .= "						<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">";
					$this->salida .= "					<b>ACTA</b></a>\n";
					$this->salida .= "				</td>\n";						
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
										
				$Paginador = new ClaseHTML();
				$this->action4 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarClientesConciliar',array("registros"=>$this->conteo));
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action4);
				$this->salida .= "		<br>\n";
			}
			else
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
		* Funcion donde se muestra la forma, donde aparece el listado de cuentas de una 
		* factura
		***********************************************************************************/
		function FormaConciliarCuentas()
		{
			$this->salida .= ThemeAbrirTabla("CREAR ACTAS DE CONCILIACION");
			$this->salida .= "<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "	".$this->SetStyle($this->parametro)."\n";
			$this->salida .= "</table>\n";
			if($this->ObtenerAuditoresInternos())
			{
				$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:0pt\">\n";
				$this->salida .= "		<td width=\"12%\">CLIENTE</td>\n";
				$this->salida .= "		<td class=\"modulo_list_claro\">\n";
				$this->salida .= "			".$this->Cliente."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td width=\"5%\">".$this->Id[0]."</td>\n";
				$this->salida .= "		<td width=\"20%\" class=\"modulo_list_claro\">\n";
				$this->salida .= "			".$this->Id[1]."\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= "<form name=\"cociliar\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset><legend class=\"field\">DATOS DEL ACTA DE CONCILIACION</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">\n";
				$this->salida .= "							<td width=\"40%\">AUDITOR(A) ENTIDAD</td>\n";
				$this->salida .= "							<td class=\"modulo_list_claro\">\n";
				$this->salida .= "								<input type=\"text\" class=\"input-text\" name=\"auditorexterno\" size=\"35\" maxlength=\"100\" value=\"".$this->NombreAuditorE."\">\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">\n";
				$this->salida .= "							<td width=\"40%\">TIPO IDENTIFICACIÓN</td>\n";
				$this->salida .= "							<td class=\"modulo_list_claro\">\n";
				$this->salida .= "								<select name=\"tipo_id_auditor\" class=\"select\">\n";
				
				$TiposTerceros = $this->ObtenerTipoIdTerceros();
				for($i=0; $i<sizeof($TiposTerceros); $i++)
				{
					$opciones = explode("/",$TiposTerceros[$i]);
					($this->TipoIdAuditorE == $opciones[0])?$selected = " selected ":$selected = "";
					$this->salida .= "									<option value='".$opciones[0]."' $selected >".ucwords(strtolower($opciones[1]))."</option>\n";			
				}
				
				$this->salida .= "								</select>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">\n";
				$this->salida .= "							<td width=\"40%\">Nº IDENTIFICACIÓN</td>\n";
				$this->salida .= "							<td class=\"modulo_list_claro\">\n";
				$this->salida .= "								<input type=\"text\" class=\"input-text\" name=\"id_auditor\" size=\"28\" maxlength=\"100\" value=\"".$this->IdAuditorE."\">\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";						
				$this->salida .= "						<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">\n";
				$this->salida .= "							<td width=\"40%\">CARGO</td>\n";
				$this->salida .= "							<td class=\"modulo_list_claro\">\n";
				$this->salida .= "								<input type=\"text\" class=\"input-text\" name=\"cargo_auditor\" size=\"35\" maxlength=\"100\" value=\"".$this->CargoAuditorE."\">\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN GENERAL</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr class=\"modulo_list_claro\" >\n";
				$this->salida .= "							<td colspan=\"2\">\n";
				$this->salida .= "								<textarea class=\"textarea\" name=\"observacion\" style=\"width:100%\" rows=\"2\" >".$this->Observacion."</textarea>\n";
				$this->salida .= "							</td>\n";						
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr class=\"modulo_list_claro\" >\n";
				$this->salida .= "							<td colspan = \"2\" align=\"center\">\n";
				$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Crear Acta\">\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td></tr>\n";
				$this->salida .= "		</table>\n";
				$this->salida .= "	</form>\n";
				
				$Actas = $this->ObtenerActasConciliacion();
				if(sizeof($Actas) > 0)
				{
					$this->salida .= "	<table width=\"99%\" align=\"center\">\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td>\n";
					$this->salida .= "				<fieldset><legend class=\"field\">ACTAS DE CONCILIACION PENDIENTES</legend>\n";
					$this->salida .= "					<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "							<td width=\"40%\" colspan=\"2\">EMPRESA</td>\n";
					$this->salida .= "							<td width=\"23%\">AUDITOR(A) EMPRESA</td>\n";
					$this->salida .= "							<td width=\"7%\" >FECHA</td>\n";
					$this->salida .= "							<td width=\"30%\" colspan=\"4\">OPCIONES</td>\n";
					$this->salida .= "						</tr>\n";
				
					for($i=0; $i<sizeof($Actas); $i++)
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						
						$Datos = explode("*",$Actas[$i]);
						
						$action3 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarGlosas',
												 array("acta_id"=>$Datos[0],"tercero"=>$Datos[1]."/".$Datos[2]));
						
						$action5 = ModuloGetURL('app','AuditoriaCuentas','user','CerrarActaConciliacion',
												 array("acta_id"=>$Datos[0],"tercero"=>$Datos[1]."/".$Datos[2],"nombre"=>$Datos[5]));

						$action6 = ModuloGetURL('app','AuditoriaCuentas','user','EditarActaConciliacion',
												 array("acta_id"=>$Datos[0],"tercero"=>$Datos[1]."/".$Datos[2]));

						$reporte = new GetReports();
						$mostrar = $reporte->GetJavaReport('app','AuditoriaCuentas','reporteacta',
															array("acta_id"=>$Datos[0],"tercero"=>$Datos[1]."/".$Datos[2]),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
						
						$funcion = "Concilia$i".$reporte->GetJavaFunction();
						$mostrar = str_replace("function W","function Concilia".$i."W",$mostrar);						 
						

						$this->salida .= "					<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
						$this->salida .= "						<td width=\"28%\">".$Datos[5]."</td>\n";
						$this->salida .= "						<td width=\"12%\">".$Datos[1]." ".$Datos[2]."</td>\n";
						$this->salida .= "						<td >".$Datos[3]."</td>\n";
						$this->salida .= "						<td align=\"center\">".$Datos[4]."</td>\n";
						
						$this->salida .= "						<td width=\"5%\">\n";
						$this->salida .= "							<a class=\"label_error\" href=\"".$action3."\" title=\"CONCILIAR GLOSAS\">\n";
						$this->salida .= "								<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">";
						$this->salida .= "							<b>CONCIL</b></a>\n";
						$this->salida .= "						</td>\n";
						
						$this->salida .= "						<td width=\"5%\">\n";
						$this->salida .= "							<a class=\"label_error\" href=\"".$action5."\" title=\"CERRAR ACTA\">\n";
						$this->salida .= "								<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">";
						$this->salida .= "							<b>CERRAR</b></a>\n";
						$this->salida .= "						</td>\n";
						
						$this->salida .= "						<td width=\"5%\">\n";
						$this->salida .= "							<a class=\"label_error\" href=\"".$action6."\" title=\"EDITAR ACTA\">\n";
						$this->salida .= "								<img src=\"".GetThemePath()."/images/edita.png\" border=\"0\">";
						$this->salida .= "							<b>EDIT</b></a>\n";
						$this->salida .= "						</td>\n";
						
						$this->salida .= "						<td width=\"5%\">\n";
						$this->salida .= "						".$mostrar."\n";
						$this->salida .= " 							<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL ACTA\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
						$this->salida .= " 								<b>REPOR</b></a>\n";
						$this->salida .= "						</td>\n";
						
						$this->salida .= "					</tr>\n";
					}
					$this->salida .= "				</table>\n";
					$this->salida .= "			</fieldset>\n";
					$this->salida .= "		</td></tr>\n";
					$this->salida .= "	</table><br>\n";
				}
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">PERMISO DENEGADO <br> SU USUARIO NO ESTA DEFINIDO COMO UN AUDITOR INTERNO</b></center><br>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma, donde aparece el listado de facturas de un 
		* cliente 
		***********************************************************************************/
		function FormaConciliarGlosas()
		{			
			$this->salida .= ThemeAbrirTabla("CONCILIACIÓN GLOSAS ".$this->Entidad." - ".$this->Empresa." ");
			$this->salida .= "<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";			
			$this->salida .= "<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td  width=\"50%\" colspan=\"2\">".$this->Empresa."</td>\n";
			$this->salida .= "		<td  width=\"50%\" colspan=\"2\">".$this->Entidad."</td>\n";
			$this->salida .= "	</tr>\n";

			$this->salida .= "	<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 11pt\">\n";
			$this->salida .= "		<td width=\"15%\">AUDITOR (A)</td>\n";
			$this->salida .= "		<td width=\"35%\" class=\"modulo_list_claro\">".$this->AuditorClinica."</td>\n";
			$this->salida .= "		<td width=\"15%\">AUDITOR (A)</td>\n";
			$this->salida .= "		<td width=\"35%\" class=\"modulo_list_claro\">".$this->AuditorEmpresa."</td>\n";
			$this->salida .= "	</tr>\n";
			
			$this->salida .= "	<tr class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 11pt\">\n";
			$this->salida .= "		<td width=\"15%\">FECHA</td>\n";
			$this->salida .= "		<td colspan=\"3\" class=\"modulo_list_claro\">".$this->Fecha."</td>\n";
			$this->salida .= "	</tr>\n";
			
			$this->salida .= "</table><br>\n";
			
			$Datos = $this->ObtenerFacturasGlosadas();
			if(sizeof($Datos) > 0)
			{
				$this->salida .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td  width=\"12%\">Nº FACTURA</td>\n";
				$this->salida .= "			<td  width=\"12%\">V. FACTURA</td>\n";
				$this->salida .= "			<td  width=\"12%\">V. GLOSA</td>\n";
				$this->salida .= "			<td  width=\"12%\">V. ACEPTADO</td>\n";
				$this->salida .= "			<td  width=\"12%\">V. NO ACEPTADO</td>\n";
				$this->salida .= "			<td  width=\"12%\">V. PENDIENTE</td>\n";
				$this->salida .= "			<td  width=\"12%\">ESTADO</td>\n";
				$this->salida .= "			<td  width=\"%\">OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				for($i=0; $i<sizeof($Datos); $i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					($Datos[$i]['acta_conciliacion_id'] != "")? $opc = "1":$opc = "0";
					 
					$action3 = ModuloGetURL('app','AuditoriaCuentas','user','MostrarConciliarFactura',
											 array("tercero"=>$this->Tercero,"acta_id"=>$this->ActaId,"sistema"=>$Datos[$i]['sistema'],
											 	   "factura"=>$Datos[$i]['prefijo']."/".$Datos[$i]['factura_fiscal'],"actualizar"=>$opc));
					
					($Datos[$i]['acta_conciliacion_id'])? $marca = "CONCILIADO":$marca = "POR CONCILIAR";
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td >".$Datos[$i]['prefijo']." ".$Datos[$i]['factura_fiscal']."</td>\n";
					$this->salida .= "			<td align=\"right\">".formatoValor($Datos[$i]['total_factura'])."</td>\n";
					$this->salida .= "			<td align=\"right\">".formatoValor($Datos[$i]['valor_glosa'])."</td>\n";
					$this->salida .= "			<td align=\"right\">".formatoValor($Datos[$i]['valor_aceptado'])."</td>\n";
					$this->salida .= "			<td align=\"right\">".formatoValor($Datos[$i]['valor_no_aceptado'])."</td>\n";
					$this->salida .= "			<td align=\"right\">".formatoValor($Datos[$i]['valor_pendiente'])."</td>\n";
					$this->salida .= "			<td align=\"center\"><b class=\"label_mark\">$marca</b></td>\n";
					$this->salida .= "			<td align=\"center\">\n";
					$this->salida .= "				<a href=\"".$action3."\" title=\"CONCILIAR FACTURA\">\n";
					$this->salida .= "					<img src=\"".GetThemePath()."/images/edita.png\" border=\"0\"><b>CONCILIAR<b>\n";
					$this->salida .= "				</a>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
				$this->salida .= "			<td >TOTAL</td>\n";
				$this->salida .= "			<td align=\"right\">".formatoValor($this->Totales['total_factura'])."</td>\n";
				$this->salida .= "			<td align=\"right\">".formatoValor($this->Totales['glosa'])."</td>\n";
				$this->salida .= "			<td align=\"right\">".formatoValor($this->Totales['aceptado'])."</td>\n";
				$this->salida .= "			<td align=\"right\">".formatoValor($this->Totales['no_aceptado'])."</td>\n";
				$this->salida .= "			<td align=\"right\" colspan=\"3\"></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";

			}
			$this->salida .= "<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";	
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma, donde aparece la informacion para realizar la 
		* conciliacion de la glosa de una factura  
		***********************************************************************************/
		function FormaMostrarConciliarFactura()
		{
			$style = "style=\"text-align:left;text-indent:6pt\" ";

			$this->salida .= ThemeAbrirTabla("INFORMACIÓN FACTURA GLOSADA ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function pasarValor(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.valor_aceptado.value = (objeto.valor_glosa.value - objeto.valor_noaceptado.value);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarValorNoAceptado(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.valor_noaceptado.value = (objeto.valor_glosa.value - objeto.valor_aceptado.value);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacion()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action4."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "		<table align=\"center\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style align=\"left\" width=\"15%\">ENTIDAD</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"55%\" colspan=\"3\">".$this->Entidad."</td>\n";
			$this->salida .= "				<td $style align=\"left\" width=\"8%\" >".$this->Tecero[0]."</td>\n";
			$this->salida .= "				<td align=\"left\" width=\"17%\" class=\"modulo_list_claro\">".$this->Tecero[1]."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style align=\"left\" >FACTURA Nº</td>\n";
			$this->salida .= "				<td align=\"left\" width=\"30%\" class=\"modulo_list_claro\">".$this->Factura[0]." ".$this->Factura[1];
			$this->salida .= "				<td $style align=\"left\" width=\"8%\" >TOTAL</td>\n";
			$this->salida .= "				<td align=\"right\" class=\"modulo_list_claro\">$".formatovalor($this->FacturaTotal);
			$this->salida .= "				<td $stylealign=\"left\" >FECHA</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->FacturaRegistro;
			$this->salida .= "			</tr>\n";
			if($this->PlanDescripcion)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style align=\"left\" >PLAN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanDescripcion."</td>\n";
				$this->salida .= "				<td $style align=\"left\" colspan=\"2\">Nº CONTRATO</td>\n";
				$this->salida .= "				<td align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">".$this->PlanNumContrato."</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "		</table><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "		".$this->SetStyle($this->Parametro)."\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "<form name=\"conciliarglosafactura\" action=\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">CONCILIACIÓN DE GLOSAS</legend>\n";
			$this->salida .= "					<table align=\"center\" class=\"modulo_table_list\" width=\"100%\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td>VALOR GLOSA</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "							<td>VALOR NO ACEPTADO</td>\n";
			$this->salida .= "							<td></td>\n";
			$this->salida .= "						</tr>\n";	
			$this->salida .= "						<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "							<td align=\"center\">".formatoValor($this->GlosaValor)."</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.conciliarglosafactura)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"valor_glosa\" id=\"valor_glosa\" value=\"".$this->GlosaValor."\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" value=\"".$this->GlosaAceptado."\"\>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.conciliarglosafactura)\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" value=\"".$this->GlosaNoAceptado."\"\>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "							<td>\n";
			$this->salida .= "								<a href=\"javascript:IngresarObservacion()\">\n";
			$this->salida .= "									<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
			$this->salida .= "								</a>\n";
			$this->salida .= "							</td>\n";					
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "					<table align=\"center\" width=\"100%\">\n";
			$this->salida .= "						<tr>\n";
			$this->salida .= "							<td align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Conciliar Glosa Factura\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";	
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";			
			$this->salida .= "</form>\n";

			if($this->GlosaSwTotal == "0")
			{
				$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
				$this->salida .= "			<tr>\n";
				$this->salida .= "				<td align=\"center\">\n";
				
				
				if($this->NuemeroGlosas > 0)
				{
					$action4 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarCuentasGlosa',$this->Arreglo);
					$this->salida .= "					<a class=\"label_error\" href=\"".$action4."\">CONCILIAR GLOSA CUENTAS</a>\n";
				}
				
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "		</table>\n";
			}
						

			$this->salida .= "	<br><table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma, donde aparece el listado de cuentas de la 
		* factura
		***********************************************************************************/
		function FormaConciliarCuentasGlosa()
		{
			$this->salida .= ThemeAbrirTabla("LISTADO DE CUENTAS");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$Cuentas = $this->ObtenerInformacionCuentas();
			if(sizeof($Cuentas) >0)
			{
				$this->salida .= "	<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"10%\" >Nº CUENTA</td>\n";
				$this->salida .= "			<td width=\"20%\">IDENTIFICACION</td>\n";
				$this->salida .= "			<td width=\"22%\">PACIENTE</td>\n";
				$this->salida .= "			<td width=\"34%\">PLAN</td>\n";
				$this->salida .= "			<td width=\"12%\" >OPCIONES</td>\n";
				$this->salida .= "		</tr>\n";
				
				for($i=0; $i<sizeof($Cuentas); $i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$Datos = explode("*",$Cuentas[$i]);
					
					$marca = "";
					if(strlen($Datos[4]) > 22)
					{
						$marca = " title =\"".$Datos[4]."\" ";
						$Datos[4] = substr($Datos[4],0,20)."...";
					}
					$this->Arreglo['numerocuenta'] = $Datos[0];
					$action3 = ModuloGetURL('app','AuditoriaCuentas','user','ConciliarGlosaCuenta',$this->Arreglo);
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td align=\"center\">".$Datos[0]."</td>\n";
					$this->salida .= "			<td >".$Datos[1]."</td>\n";
					$this->salida .= "			<td >".$Datos[2]."</td>\n";
					$this->salida .= "			<td >".$Datos[3]."</td>\n";
					$this->salida .= "			<td align=\"center\">\n";
					$this->salida .= "				<a href=\"".$action3."\" title=\"CONCILIAR CUENTA\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>CONCILIA</b></a>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2,25);
				$this->salida .= "	<br>\n";
			}
			
			$this->salida .= "	<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma, donde aparece la informacion para la 
		* conciliacion de una cuenta y los cargos e insumos de las mismas 
		***********************************************************************************/
		function FormaConciliarGlosaCuenta()
		{
			$this->salida .= ThemeAbrirTabla("INFORMACIÓN CUENTA");
			$this->salida .= "	<script>\n";
			$this->salida .= "		var arreglo = new Array();\n";
			$this->salida .= "		function pasarValor(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_aceptado[i].value = (objeto.valor_glosa[i].value - objeto.valor_noaceptado[i].value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_aceptado.value = (objeto.valor_glosa.value - objeto.valor_noaceptado.value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarValorNoAceptado(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_noaceptado[i].value = (objeto.valor_glosa[i].value - objeto.valor_aceptado[i].value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valor_noaceptado.value = (objeto.valor_glosa.value - objeto.valor_aceptado.value);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";	
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacion()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action5."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function IngresarObservacionCargo(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var texto = \"\";\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosacuentaid[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosacuentaid.value;\n";
			$this->salida .= "			}\n";			
			$this->salida .= "			var url=\"".$this->action6."\"+texto;\n";
			$this->salida .= "			window.open(url,'','width=750,height=600,X=200,Y=10,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";

			$this->salida .= "		function IngresarObservacionInsumo(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var texto = \"\";\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosacuentaid[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				texto = \"&glosa_detalle_id=\"+objeto.glosacuentaid.value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			var url=\"".$this->action7."\"+texto;\n";
			$this->salida .= "			window.open(url,'','width=750,height=600,X=200,Y=10,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;Nº FACTURA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$this->salida .= "				".$this->Factura[0]." ".$this->Factura[1]."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;Nº CUENTA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" width=\"10%\">\n";
			$this->salida .= "				".$this->NumeroCuenta."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"10%\">\n";
			$this->salida .= "				<b>&nbsp;PLAN</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$this->salida .= "				".$this->PlanDescripcion."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td align=\"left\">\n";
			$this->salida .= "				<b>&nbsp;Nº INGRESO</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\">\n";
			$this->salida .= "				".$this->Ingreso."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"left\" width=\"15%\">\n";
			$this->salida .= "				<b>&nbsp;PACIENTE</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"right\" class=\"modulo_list_claro\" colspan=\"2\" width=\"25%\">\n";
			$this->salida .= "				".$this->PacienteTipoId." ".$this->PacienteId."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$this->salida .= "				".$this->PacienteNombre."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			$this->salida .= "	<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "		".$this->SetStyle($this->Parametro)."\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "	<form name=\"conciliarglosacuenta\" action=\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "		<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<fieldset><legend class=\"field\">CONCILIAR EL VALOR TOTAL DE LA GLOSA DE LA CUENTA</legend>\n";
			$this->salida .= "						<table align=\"center\" class=\"modulo_table_list\" width=\"100%\">\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td>VALOR GLOSA</td>\n";
			$this->salida .= "								<td></td>\n";
			$this->salida .= "								<td>VALOR ACEPTADO</td>\n";
			$this->salida .= "								<td></td>\n";
			$this->salida .= "								<td>VALOR NO ACEPTADO</td>\n";
			$this->salida .= "								<td></td>\n";
			$this->salida .= "							</tr>\n";	
			$this->salida .= "							<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "								<td align=\"center\">".formatoValor($this->GlosaValor)."</td>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.conciliarglosacuenta,0)\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<input type=\"hidden\" name=\"valor_glosa\" id=\"valor_glosa\" value=\"".$this->GlosaValor."\">\n";
			$this->salida .= "									<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" value=\"".$this->GlosaValorAceptado."\"\>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.conciliarglosacuenta,0)\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado\" class=\"input-text\" size=\"13\" onkeypress=\"return acceptNum(event)\" $disable value=\"".$this->GlosaValorNoAceptado."\"\>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td>\n";
			$this->salida .= "									<a href=\"javascript:IngresarObservacion()\">\n";
			$this->salida .= "										<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
			$this->salida .= "									</a>\n";
			$this->salida .= "								</td>\n";						
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "						<table align=\"center\" width=\"100%\">\n";
			$this->salida .= "							<tr>\n";
			$this->salida .= "								<td align=\"center\">\n";
			$this->salida .= "									<input type=\"submit\" class=\"input-submit\" value=\"Conciliar Glosa Cuenta\">\n";
			$this->salida .= "									<input type=\"hidden\" name=\"cuentaconcilia\" value=\"1\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";	
			$this->salida .= "					</fieldset>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";			
			$this->salida .= "	</form>\n";
			
			$this->salida .= "	<form name=\"conciliarglosacargos\" action=\"".$this->action3."\" method=\"post\" >\n";
			$this->salida .= "		<input type=\"hidden\" name=\"cargoconcilia\" value=\"".sizeof($this->Cargos)."\">\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valoraceptadocuenta\" value=\"".$this->GlosaValorAceptado."\"\>\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valornoaceptadocuenta\" value=\"".$this->GlosaValorNoAceptado."\"\>\n";
			$this->salida .= "		<input type=\"hidden\" name=\"valorglosacuenta\" value=\"".$this->GlosaValor."\"\>\n";

			$j = 0;			
			if(sizeof($this->Cargos) > 0)
			{
				$this->salida .= "	<table width=\"98%\" align=\"center\">\n";		
				$this->salida .= "		<tr><td>\n";
				$this->salida .= "			<fieldset><legend class=\"field\">CARGOS</legend>\n";
				
				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"9%\" ><b>FECHA</b></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>TRANSACCIÓN</b></td>\n";
				$this->salida .= "			<td width=\"7%\"><b>CARGO</b></td>\n";
				$this->salida .= "			<td width=\"8%\" ><b>TARIFARIO</b></td>\n";
				$this->salida .= "			<td width=\"27%\"><b>DESCRIPCIÓN</b></td>\n";
				$this->salida .= "			<td width=\"9%\"><b>V. GLOSA</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>V. ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "			<td width=\"12%\"><b>V. NO ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "		</tr>";
				

				for($i=0; $i< sizeof($this->Cargos);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$Celdas = explode("*",$this->Cargos[$i]);
					$marca = "";
					if(strlen($this->Cargos[$i]['descripcion']) > 30)
					{
						$marca = " title =\"".$this->Cargos[$i]['descripcion']."\" ";
						$this->Cargos[$i]['descripcion'] = substr($this->Cargos[$i]['descripcion'],0,30)."...";
					}												
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td align=\"center\"  >".$this->Cargos[$i]['fecha']."</td>\n";
					$this->salida .= "			<td align=\"center\"  >".$this->Cargos[$i]['transaccion']."&nbsp;&nbsp;</td>\n";
					$this->salida .= "			<td align=\"center\"  >".$this->Cargos[$i]['cargo_cups']."</td>\n";
					$this->salida .= "			<td align=\"center\"  >".$this->Cargos[$i]['tarifario_id']."</td>\n";
					$this->salida .= "			<td align=\"justify\" $marca>".$this->Cargos[$i]['descripcion']."</td>\n";
					
					if($this->Cargos[$i]['glosa'] != "0")
					{
						$this->salida .= "			<td align=\"right\" >$".formatoValor($this->Cargos[$i]['glosa'])."&nbsp;\n";
						$this->salida .= "				<input type=\"hidden\" name=\"valor_glosa[$j]\" id=\"valor_glosa\" value=\"".$this->Cargos[$i]['glosa']."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"actasnumero[$j]\" value=\"".$Celdas[10]."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"transaccion[$j]\" value=\"".$this->Cargos[$i]['transaccion']."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"glosacuentaid[$j]\" id=\"glosacuentaid\" value=\"".$this->Cargos[$i]['glosa_detalle_cargo_id']."\">\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.conciliarglosacargos,".$j.")\"></td>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_aceptado\"   name=\"valor_aceptado[$j]\"   class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValorNoAceptado(document.conciliarglosacargos,".$j.")\">\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VNoAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<a href=\"javascript:IngresarObservacionCargo(document.conciliarglosacargos,".$j.")\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";					

						$j++;
					}
					else
					{
						$this->salida .= "			<td colspan=\"6\" align=\"center\" >\n";
						$this->salida .= "					<b>CARGO NO GLOSADO</b>\n";
						$this->salida .= "			</td>\n";						
					}
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "			</table>\n";		
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "		</fieldset>\n";
				$this->salida .= "	</table><br>\n";		
			}
					
			if(sizeof($this->Insumo) > 0)
			{
				$this->salida .= "	<table width=\"98%\" align=\"center\">\n";		
				$this->salida .= "		<tr><td>\n";
				$this->salida .= "			<fieldset><legend class=\"field\">INSUMOS</legend>\n";
				
				$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"15%\"><b>CODIGO PRODUCTO</b></td>\n";
				$this->salida .= "			<td width=\"11%\"><b>CANTIDAD</b></td>\n";
				$this->salida .= "			<td width=\"35%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "			<td width=\"9%\" ><b>V. GLOSA</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "			<td width=\"10%\"><b>V. ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "			<td width=\"12%\"><b>V. NO ACEPTADO</b></td>\n";
				$this->salida .= "			<td width=\"%\" ></td>\n";
				$this->salida .= "		</tr>";
				
				for($i=0; $i< sizeof($this->Insumo);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$Celdas = explode("*",$this->Insumo[$i]);
							
					
					$marca = "";
					if(strlen($Celdas[4]) > 39)
					{
						$marca = " title =\"".$Celdas[2]."\" ";
						$Celdas[2] = substr($Celdas[2],0,36)."...";
					}

					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas[0]."</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas[1]."</td>\n";
					$this->salida .= "			<td align=\"justify\">".$Celdas[2]."</td>\n";
					if($Celdas[3] != "0")
					{
						$this->salida .= "			<td align=\"right\" >$".formatoValor($Celdas[3])."&nbsp;\n";
						$this->salida .= "				<input type=\"hidden\" name=\"valor_glosa[$j]\" id=\"valor_glosa\" value=\"".$Celdas[3]."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"producto[$j]\" value=\"".$Celdas[0]."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"actasnumero[$j]\" value=\"".$Celdas[10]."\">\n";
						$this->salida .= "				<input type=\"hidden\" name=\"glosacuentaid[$j]\" id=\"glosacuentaid\" value=\"".$Celdas[6]."\">\n";
						$this->salida .= "			</td>\n";

						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.conciliarglosacargos,$j)\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_aceptado\" name=\"valor_aceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" $disable value=\"".$this->VAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.conciliarglosacargos,$j)\"></td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<b>$</b><input type=\"text\" id=\"valor_noaceptado\" name=\"valor_noaceptado[$j]\" class=\"input-text\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->VNoAceptado[$j]."\"\>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td>\n";
						$this->salida .= "				<a href=\"javascript:IngresarObservacionInsumo(document.conciliarglosacargos,".$j.")\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/auditoria.png\" title=\"INGRESAR OBSERVACIÓN\" border=\"0\"></td>\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						
						$j++;
					}
					else
					{
						$this->salida .= "			<td colspan=\"6\" align=\"center\" >\n";
						$this->salida .= "					<b>INSUMO NO GLOSADO</b>\n";
						$this->salida .= "			</td>\n";						
					}
					
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "			</table>\n";		
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "		</fieldset>\n";
				$this->salida .= "	</table><br>\n";		
			}
			
			$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Conciliar Glosa Cargos e Insumos\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= " </form>\n";	
			
			$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";		
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************** 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		***********************************************************************************/
		function FormaInformacion($parametro)
		{
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle($this->parametro)."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$parametro."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			if($this->actionM)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->actionM."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
			}
			
			if($this->Imprimir)
			{				
				$reporte = new GetReports();
				$mostrar = $reporte->GetJavaReport('app','AuditoriaCuentas','respuesta',array("glosa_id"=>$_REQUEST['glosa_id']),
																						array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion = $reporte->GetJavaFunction();			
				$this->salida .= "		".$mostrar."\n";				
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Respuesta\" onclick=\"$funcion\">\n";
				$this->salida .= "			</td>\n";
			}
			
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		* Funcion que permite mostrar la informacion del acta de conciliacion para editar 
		* la observacion 
		***********************************************************************************/
		function FormaEditarActaConciliacion()
		{
			$estilo = " class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\" "; 
			$this->salida .= ThemeAbrirTabla('EDITAR ACTA DE CONCILIACION');
			$this->salida .= "<form name=\"editar\" action=\"".$this->action2."\" method=\"post\">\n";

			$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"50%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"2\">\n";
			$this->salida .= "				<b>".$this->Empresa."</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr $estilo>\n";
			$this->salida .= "			<td width=\"25%\">\n";
			$this->salida .= "				<b>AUDITOR (A)</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" >\n";
			$this->salida .= "				".$this->AuditorClinica."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"2\">\n";
			$this->salida .= "				<b>".$this->Cliente."</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr $estilo>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<b>AUDITOR (A)</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" >\n";
			$this->salida .= "				".$this->AuditorEmpresa."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr $estilo>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<b>FECHA</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->Fecha."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"2\">\n";
			$this->salida .= "				<b>OBSERVACION GENERAL</b>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td colspan=\"2\">\n";
			$this->salida .= "				<textarea class=\"textarea\" name=\"observacionA\" cols=\"100%\" rows=\"4\">".$this->ObservacionA."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "		<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Aceptar\"></td>\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion que permite desplegar una forma donde se ingresa la observacion de un 
		* cargo e insumo,la cual es desplegada en una ventana emergente
		***********************************************************************************/
		function FormaIngresarObservacion()
		{
			$this->salida .= ThemeAbrirTabla('INGRESAR OBSERVACIÓN');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function Cerrar()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.close();\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<form name=\"formaObservacion\" action =\"".$this->action2 ."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td align=\"center\" colspan=\"5\">OBSERVACIÓN</td>\n";						
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_list_claro\" >\n";
			$this->salida .= "				<td align=\"center\" >\n";
			$this->salida .= "					<textarea class=\"textarea\" name=\"observacion\" cols=\"100\" rows=\"4\" ></textarea>\n";
			$this->salida .= "				</td>\n";						
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Ingresar Observacion\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			if(sizeof($this->Datos) > 0)
			{
				$this->salida .= "	<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"50%\"><b>OBSERVACION</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>FECHA</b></td>\n";
				$this->salida .= "			<td width=\"%\"><b>USUARIO</b></td>\n";
				
				if($this->Conciliacion == "on")
					$this->salida .= "			<td width=\"4%\"></td>\n";
				
				$this->salida .= "		</tr>";
				
				for($i=0; $i< sizeof($this->Datos);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; $background = "#DDDDDD";
					}
					
					$Celdas = explode("*",$this->Datos[$i]);
					
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
					$this->salida .= "			<td align=\"justify\">".$Celdas[0]."</td>\n";
					$this->salida .= "			<td align=\"center\" >".$Celdas[1]."</td>\n";
					$this->salida .= "			<td align=\"justify\">".$Celdas[2]."</td>\n";
					
					if($this->Conciliacion == "on")
					{
						$this->Arreglo['identificacion'] = $Celdas[4];
						if($Celdas[3])
						{
							$this->Arreglo['agregar'] = "off";
							$action12 = ModuloGetURL('app','AuditoriaCuentas','user',$this->metodo,$this->Arreglo);
							$this->salida .= "			<td align=\"center\">\n";
							$this->salida .= "				<a href=\"".$action12."\" title=\"REMOVER DEL ACTA DE CONCILIACÓN\">\n";
							$this->salida .= "					<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
							$this->salida .= "				</a>\n";
							$this->salida .= "			</td>\n";
						}
						else
						{
							$this->Arreglo['agregar'] = "on";
							$action12 = ModuloGetURL('app','AuditoriaCuentas','user',$this->metodo,$this->Arreglo);
							$this->salida .= "			<td align=\"center\">\n";
							$this->salida .= "				<a href=\"".$action12."\" title=\"AGREGAR AL ACTA DE CONCILIACIÓN\">\n";
							$this->salida .= "					<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
							$this->salida .= "				</a>\n";
							$this->salida .= "			</td>\n";
						}
					}

					$this->salida .= "		</tr>";
				}
				
				$this->salida .= "	</table><br>";

			}
			
			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action1);
			$this->salida .= "	<br>\n";

			$this->salida .= "		<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaMostrarActasConciliacion()
		{
			$this->salida .= ThemeAbrirTabla("CREAR ACTAS DE CONCILIACION");
			$this->salida .= "<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "</script>\n";
			if($this->ObtenerAuditoresInternos())
			{	
				$this->ObtenerActasConciliacionCerradas();
				$this->salida .= "<table align=\"center\" width=\"50%\">\n";
				$this->salida .= "	".$this->SetStyle($this->parametro)."\n";
				$this->salida .= "</table>\n";
				$this->salida .= "<table width=\"60%\" align=\"center\" >\n";		
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= $this->BuscadorActas();
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>\n";

				if(sizeof($this->Actas) > 0)
				{
					$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td>\n";
					$this->salida .= "				<fieldset><legend class=\"field\">ACTAS DE CONCILIACION CERRADAS</legend>\n";
					$this->salida .= "					<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "						<tr class=\"modulo_table_list_title\" height=\"21\">\n";
					$this->salida .= "							<td width=\"7%\" >ACTA Nº</td>\n";
					$this->salida .= "							<td width=\"40%\" colspan=\"2\">EMPRESA</td>\n";
					$this->salida .= "							<td width=\"18%\">AUDITOR(A) CLINICA</td>\n";
					$this->salida .= "							<td width=\"19%\">AUDITOR(A) EMPRESA</td>\n";
					$this->salida .= "							<td width=\"8%\">FECHA</td>\n";
					$this->salida .= "							<td width=\"8%\">OPCIONES</td>\n";
					$this->salida .= "						</tr>\n";
				
					for($i=0; $i<sizeof($this->Actas); $i++)
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						
						$Datos = $this->Actas[$i];
						
						$reporte = new GetReports();
						$mostrar = $reporte->GetJavaReport('app','AuditoriaCuentas','reporteacta',
																								array("acta_id"=>$Datos['acta'],"tercero"=>$Datos['tipo_id_tercero']."/".$Datos['tercero_id']),
																								array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
															
						$funcion = "Concilia$i".$reporte->GetJavaFunction();
						$mostrar = str_replace("function W","function Concilia".$i."W",$mostrar);						 
						

						$this->salida .= "					<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); height=\"21\">\n";
						$this->salida .= "						<td align=\"right\">".$Datos['acta']."</td>\n";
						$this->salida .= "						<td align=\"left\"    width=\"12%\">".$Datos['tipo_id_tercero']." ".$Datos['tercero_id']."</td>\n";
						$this->salida .= "						<td align=\"justify\" width=\"28%\">".$Datos['nombre_tercero']."</td>\n";
						$this->salida .= "						<td align=\"justify\">".$Datos['nombre']."</td>\n";
						$this->salida .= "						<td align=\"justify\">".$Datos['auditor_empresa']."</td>\n";
						$this->salida .= "						<td align=\"center\" >".$Datos['fecha']."</td>\n";				
						$this->salida .= "						<td align=\"center\" >\n";
						$this->salida .= "						".$mostrar."\n";
						$this->salida .= " 							<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL ACTA\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
						$this->salida .= " 								<b>REPOR</b></a>\n";
						$this->salida .= "						</td>\n";
						$this->salida .= "					</tr>\n";
					}
					$this->salida .= "				</table>\n";
					$this->salida .= "			</fieldset>\n";
					$this->salida .= "		</td></tr>\n";
					$this->salida .= "	</table><br>\n";
					
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
					$this->salida .= "	<br>\n";
				}
				else
				{
					$this->salida .= "<center><b class=\"label_error\">NO SE ENCONTRARON ACTAS DE CONCILIACIÓN CERRADAS</b></center><br>\n";
				}
			}
			else
			{
				$this->salida .= "<center><b class=\"label_error\">PERMISO DENEGADO <br> SU USUARIO NO ESTA DEFINIDO COMO UN AUDITOR INTERNO</b></center><br>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
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
		* Funcion donde se realiza la forma del buscador de facturas envio 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function BuscadorEnviosFacturas()
		{
			$estilo = " class=\"label\" style=\"text-align:left;text-indent:0pt\" "; 
			
			$buscador  = "<form name=\"buscador2\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function limpiarCampos(frm)\n";
			$buscador .= "		{\n";
			$buscador .= "			for(i=0; i<frm.length; i++)\n";
			$buscador .= "			{\n";
			$buscador .= "				switch(frm[i].type)\n";
			$buscador .= "				{\n";
			$buscador .= "					case 'text': frm[i].value = ''; break;\n";
			$buscador .= "					case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$buscador .= "				}\n";
			$buscador .= "			}\n";
			$buscador .= "		}\n";
			$buscador .= "		function acceptDate(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$buscador .= "		}\n";
			$buscador .= "		function acceptNum(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table width=\"100%\">\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td $estilo>\n";
			$buscador .= "					<select name=\"combo\" class=\"select\">\n";
			if($this->ComboBSQ == '01')
				$envio = " selected ";
			if($this->ComboBSQ == '02')
				$glosa = " selected ";
			
			$buscador .= "						<option value='01' $envio>ENVIO</option>\n";
			$buscador .= "						<option value='02' $glosa>GLOSA</option>\n";

			$Prefijos = SessionGetVar("PrefijosAuditoria");;
			foreach($Prefijos as $key => $Filas)
			{
				($this->rqs['combo'] == $Filas['prefijo'])? $sel = "selected":$sel = ""; 
				
				$buscador .= "					<option value='".$Filas['prefijo']."' $sel>".$Filas['prefijo']."</option>\n";
			}
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td $estilo>\n";
			$buscador .= "					<b >NÚMERO: </b>";
			$buscador .= "				</td>\n";
			$buscador .= "				<td colspan=\"3\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"22\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['numero']."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">FECHA</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaInicio."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador2','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaFin."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador2','fecha_fin','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			
			switch($this->OperadorGlosa)
			{
				case '=':  $uno = "selected"; break;
				case '>':  $dos = "selected"; break;
				case '<':  $tre = "selected"; break;
				case '>=': $cua = "selected"; break;
				case '<=': $cin = "selected"; break;
			}
			
			$buscador .= "			<tr>\n";
			$buscador .= "				<td $estilo>\n";
			$buscador .= "					<b>VALOR GLOSA: </b>";
			$buscador .= "				</td>\n";
			$buscador .= "				<td >\n";
			$buscador .= "					<select name=\"comparacionglosa\" class=\"select\">\n";
			$buscador .= "						<option $uno value='=' >=</option>\n";
			$buscador .= "						<option $dos value='&gt;' >&gt;</option>\n";
			$buscador .= "						<option $tre value='&lt;' >&lt;</option>\n";
			$buscador .= "						<option $cua value='&gt;=' >&gt;=</option>\n";
			$buscador .= "						<option $cin value='&lt;=' >&lt;=</option>\n";
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td colspan=\"3\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"valor_glosa\" size=\"22\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$this->BVGlosa."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			
			$uno = $dos = $tre = $cua = $cin = "";
			 
			switch($this->OperadorFactura)
			{
				case '=':  $uno = "selected"; break;
				case '>':  $dos = "selected"; break;
				case '<':  $tre = "selected"; break;
				case '>=': $cua = "selected"; break;
				case '<=': $cin = "selected"; break;
			}
			$buscador .= "			<tr>\n";
			$buscador .= "				<td $estilo>\n";
			$buscador .= "					<b>VALOR FACTURA: </b>";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<select name=\"comparacionfactura\" class=\"select\">\n";
			$buscador .= "						<option $uno value='=' >=</option>\n";
			$buscador .= "						<option $dos value='&gt;' >&gt;</option>\n";
			$buscador .= "						<option $tre value='&lt;' >&lt;</option>\n";
			$buscador .= "						<option $cua value='&gt;=' >&gt;=</option>\n";
			$buscador .= "						<option $cin value='&lt;=' >&lt;=</option>\n";
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td colspan=\"3\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"valor_factura\" size=\"22\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$this->BVFactura."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td colspan=\"5\" align=\"center\">\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador2)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			$buscador .= "<table width=\"90%\" align=\"center\">\n";
			$buscador .= "	<tr><td align=\"center\">\n";
			$buscador .= "		<form name=\"volver\" action=\"".$this->action2."\" method=\"post\">\n";
			$buscador .= "			<input type=\"submit\" class=\"input-submit\" value=\"Todas Las Facturas\">\n";
			$buscador .= "		</form>\n";
			$buscador .= "	</td></tr>\n";
			$buscador .= "</table>\n";

			return $buscador;  
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function BuscadorTerceros()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function limpiarCampos(objeto)\n";
			$buscador .= "		{\n";
			$buscador .= "			objeto.nombre_tercero.value = \"\";\n";
			$buscador .= "			objeto.tercero_id.value = \"\";\n";
			$buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			$TiposTerceros = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$selected = "";
				$opciones = explode("/",$TiposTerceros[$i]);
				if($this->TerceroTipoId == $opciones[0])
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
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">NOMBRE</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador;  
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function BuscadorActas()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function limpiarCampos(objeto)\n";
			$buscador .= "		{\n";
			$buscador .= "			objeto.fecha_fin.value = \"\";\n";
			$buscador .= "			objeto.tercero_id.value = \"\";\n";
			$buscador .= "			objeto.fecha_inicio.value = \"\";\n";
			$buscador .= "			objeto.nombre_tercero.value = \"\";\n";
			$buscador .= "			objeto.auditor_sel.selectedIndex='0';\n";
			$buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td width=\"25%\" class=\"label\">TIPO DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			$TiposTerceros = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones = explode("/",$TiposTerceros[$i]);
				($this->TerceroTipoId == $opciones[0])?	$selected = " selected ":$selected = "";
				$buscador .= "						<option value='".$opciones[0]."' $selected >".ucwords(strtolower($opciones[1]))."</option>\n";			
			}
			
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";	
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">NÚMERO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">EMPRESA</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">FECHA</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaInicio."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaFin."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_fin','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">AUDITOR</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"auditor_sel\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			$this->ObtenerAuditoresInternos(1);
			for($i=0; $i<sizeof($this->Auditores); $i++)
			{
				$opciones = $this->Auditores[$i];
				($this->AuditorSel == $opciones['usuario_id'])? $sel = "selected":$sel="";
				
				$buscador .= "						<option value='".$opciones['usuario_id']."' $sel>".ucwords(strtolower($opciones['nombre']))."</option>\n";
			}
			
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"5\"><br>\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador;  
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function FormaAceptarGlosaNota()
		{
			$this->AceptarGlosaNota();
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCD','notacredito',
														array("numero"=>$this->nota['numeracion'],"prefijo"=>$this->nota['prefijo'],"glosa"=>$this->request['glosa_id'],"codigo"=>$this->codigo),
														array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();			
			
			$this->salida .= ThemeAbrirTabla("MENSAJE");
			$this->salida .= "	".$mostrar."\n";				
			$this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"normal_10AN\" align=\"center\">\n";
			$this->salida .= "				".$this->frmError['MensajeError']."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Imprimir Nota\" onclick=\"$funcion\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		***********************************************************************************/
		function FormaNoAceptarGlosa()
		{
			$this->NoAceptarGlosa();
			$this->SetXajax(array("NoAcepatarGlosa"),"app_modules/AuditoriaCuentas/RemoteXajax/Anular.php");
			
			$this->salida .= ThemeAbrirTabla("MENSAJE");
			$this->salida .= "<script>\n";
			$this->salida .= "	function CambiarAccion()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.volver.action = \"".$this->action['volverx']."\"\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<div id=\"mensaje\">\n";
			$this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"normal_10AN\" align=\"center\">\n";
			$this->salida .= "				ESTA SEGURO QUE DESEA CERRAR LA GLOSA Nº ".$this->request['glosa_id'].", SIN ACEPTAR VALORES ?\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</div><br>\n";
			$this->salida .= "<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<div id=\"boton\" style=\"display:block\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onclick=\"xajax_NoAcepatarGlosa(".$this->request['glosa_id'].")\">\n";
			$this->salida .= "				</div>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" id=\"volver\" value=\"Cancelar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}
?>