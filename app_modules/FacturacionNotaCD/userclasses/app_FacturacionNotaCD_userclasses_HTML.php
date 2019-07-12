<?php
	/**************************************************************************************  
	* $Id: app_FacturacionNotaCD_userclasses_HTML.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1.1.1 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_FacturacionNotaCD_userclasses_HTML extends app_FacturacionNotaCD_user
	{
		/****************************************************************************** 
		* Constructor
		* 
		* @access private
		*******************************************************************************/
		function app_FacturacionNotasCD_user_HTML()
		{
			return true;	
		}
		/***********************************************************************************
		* Muestra el menu de las empresas y centros de utilidad 
		* 
		* @access public 
		***********************************************************************************/
		function MostrarMenuEmpresas()
		{
			unset($_SESSION['NotasCD']);
			$Empresas = $this->BuscarEmpresasUsuario();
			$titulo[0]='EMPRESAS';
			$url[0]='app'; 
			$url[1]='FacturacionNotaCD';
			$url[2]='user';
			$url[3]='MostrarMenuPrincipalGlosas';
			$url[4]='permisonotas';
			$this->salida .= gui_theme_menu_acceso('NOTAS CREDITO - DEBITO',$titulo,$Empresas,$url,ModuloGetURL('system','Menu'));
			return true;
		}
		/****************************************************************************** 
		* Muestra el menú principal de cartera.
		* 
		* @access public 
		******************************************************************************/ 
		function MostrarMenuPrincipalGlosas()
		{
			if(empty($_SESSION['NotasCD']['empresa']))
			{
				$_SESSION['NotasCD']['empresa'] = $_REQUEST['permisonotas']['empresa'];
			}
			
			unset($_SESSION['SqlBuscarFacturas']);
			unset($_SESSION['SqlContarFacturas']);
			
			$this->salida  = ThemeAbrirTabla('NOTAS CREDITO - OPCIONES');
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td width=\"40%\">\n";
			$this->salida .= "			<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\">MENÚ</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
			$this->salida .= "						<a href=\"". ModuloGetURL('app','FacturacionNotaCD','user','MostrarFacturasContabilizar')."\"><b>VER FACTURAS GLOSADAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
			$this->salida .= "						<a href=\"". ModuloGetURL('app','FacturacionNotaCD','user','MostrarNotasCredito')."\"><b>VER NOTAS CREDITO</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			
			$accion=ModuloGetURL('app','FacturacionNotaCD','user','MostrarMenuEmpresas');
			
			$this->salida .= "			<form name=\"form\" action=\"$accion\" method=\"post\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/******************************************************************************************
		* Funcion donde se realiza la forma que muestra la informacion de todas las facturas que 
		* hay o de las que se buscaron 
		* 
		* @return boolean 
		*******************************************************************************************/
		function FormaMostrarFacturasContabilizar()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTAR FACTURAS");
			if(!empty($_SESSION['NotasCD']['empresa']))
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
				$this->salida .= "	<table width=\"70%\" align=\"center\" >\n";		
				$this->salida .= "		<tr><td>\n";
				$this->salida .= $this->Buscador();
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "		<tr><td><br>\n";
				$this->salida .= $this->BuscadorRapidoFactura();
				$this->salida .= "		<br></td>";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				//$facturas = $this->ObtenerDatosFacturas();
				$facturas = $this->Facturas;
				if(sizeof($facturas) > 0)
				{
					$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
					$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "				<td width=\"8%\" ><b>FACTURA</b></td>\n";
					$this->salida .= "				<td width=\"8%\" ><b>FECHA</b></td>\n";
					$this->salida .= "				<td width=\"%\" colspan=\"2\" ><b>CLIENTE</b></td>\n";
					$this->salida .= "				<td width=\"8%\" ><b>Nº ENVIO</b></td>\n";
					$this->salida .= "				<td width=\"12%\"><b>F. RADICACION</b></td>\n";
					$this->salida .= "				<td width=\"10%\"><b>TOTAL</b></td>\n";
					$this->salida .= "				<td width=\"10%\"><b>OPCIONES</b></td>\n";
					$this->salida .= "			</tr>\n";
					
					for($i=0; $i< sizeof($facturas); $i++)
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro'; $background = "#DDDDDD";
						}
						
						$actionH = ModuloGetURL('app','FacturacionNotaCD','user','MostrarInformacionFactura',
			 										 		 			 array("pagina"=>$this->paginaActual,"envio_numero"=>$facturas[$i]['envio'],"sistema"=>$facturas[$i]['sistema'],
			 										 		 						 "factura_numero"=>$facturas[$i]['prefijo']." ".$facturas[$i]['factura_fiscal']));
			 					
						$opcion  = "	<img src=\"".GetThemePath()."/images/editar.png\">\n";
						$opcion .= "		<a class=\"label_error\" href=\"".$actionH."\" title=\"GENERAR NOTA CREDITO - DEBITO\">\n";
						$opcion .= "	<font class=\"label-error\">NOTA</font></a>\n";
											
						$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "					<td align=\"left\"   >".$facturas[$i]['prefijo']." ".$facturas[$i]['factura_fiscal']."</td>\n";
						$this->salida .= "					<td align=\"center\" >".$facturas[$i]['fecha_registro']."</td>\n";
						$this->salida .= "					<td align=\"left\" width=\"12%\">&nbsp;".$facturas[$i]['tipo_id_tercero']." ".$facturas[$i]['tercero_id']."</td>\n";
						$this->salida .= "					<td align=\"justify\">".$facturas[$i]['nombre_tercero']."</td>\n";
						$this->salida .= "					<td align=\"right\"  >".$facturas[$i]['envio_id']."</td>\n";
						$this->salida .= "					<td align=\"center\" >".$facturas[$i]['fecha_envio']."</td>\n";
						$this->salida .= "					<td align=\"right\"  >".formatoValor($facturas[$i]['total_factura'])."</td>\n";
						$this->salida .= "					<td align=\"center\" >".$opcion."</td>\n";
						$this->salida .= "			</tr>\n";
					}
					$this->salida .= "	</table>\n";
					$this->salida .= "		<br>\n";
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
					$this->salida .= "		<br>\n";
				}
				else
				{
					$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
				}
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
			}
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del b¡uscador 
		* 
		* @return string 
		**********************************************************************************************/
		function Buscador()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->actionBuscador."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
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
			$buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$buscador .= "		}\n";
			$buscador .= "		function limpiarCampos(objeto)\n";
			$buscador .= "		{\n";
			$buscador .= "			objeto.numero.value = \"\";\n";
			$buscador .= "			objeto.fecha_fin.value = \"\";\n";
			$buscador .= "			objeto.tercero_id.value = \"\";\n";
			$buscador .= "			objeto.fecha_inicio.value = \"\";\n";
			$buscador .= "			objeto.nombreTercero.value = \"\";\n";
			$buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
			$buscador .= "			objeto.tipo_documento.selectedIndex='0';\n";
			$buscador .= "			objeto.estado_glosa.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			$TiposTerceros = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones = explode("/",$TiposTerceros[$i]);
				if($this->TipoIdTercero == $opciones[0])
				{
					$buscador .= "						<option value='".$opciones[0]."' selected>".ucwords(strtolower($opciones[1]))."</option>\n";
				}
				else
				{
					$buscador .= "						<option value='".$opciones[0]."'>".ucwords(strtolower($opciones[1]))."</option>\n";			
				}
			}
			$buscador .= "					</select></td></tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"25\" maxlength=\"32\" value=\"".$this->TerceroId."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">NOMBRE CLIENTE</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombreTercero\" size=\"25\" maxlength=\"100\" value=\"".$this->NombreTercero."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr><td class=\"label\">NUMERO FACTURA</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"25\" maxlength=\"100\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Numero."\">\n";
			$buscador .= "				</td></tr>";
			$buscador .= "			<tr><td class=\"label\">FECHA</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaInicio."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaFin."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_fin','/')."\n";
			$buscador .= "				</td></tr>\n";
			$buscador .= "			<tr><td class=\"label\" align=\"center\" colspan=\"5\"><br>\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"LIMPIAR CAMPOS\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td></tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador; 
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del b¡uscador rapido de facturas  
		* 
		* @return string  
		**********************************************************************************************/
		function BuscadorRapidoFactura()
		{
			$buscador  = "<form name=\"buscadorfacturas\" action=\"".$this->actionBuscadorF."\" method=\"post\" cellspacing=\"1\">\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
			$buscador .= "				BUSCADOR RAPIDO DE FACTURAS:&nbsp;\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<select name=\"prefijo_factura\" class=\"select\">\n";
			$Filas = $this->ObtenerPrefijos();
			for($i=0; $i<sizeof($Filas); $i++)
			{
				($this->PrefijoFactura == $Filas[$i])? $sel = "selected": $sel = "";
				$buscador .= "				<option value='".$Filas[$i]."' $sel>".$Filas[$i]."</option>\n";
			}
			$buscador .= "				</select>\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"factura_fiscal\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FacturaFiscal."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"BUSCAR\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*********************************************************************************************
		* Forma donde se muestra la informacion de la factura cuando se ha glosado 
		**********************************************************************************************/
		function FormaMostrarInformacionFactura()
		{
			$style = "style=\"text-align:left;text-indent:6pt\" ";
			$this->salida .= ThemeAbrirTabla("INFORMACIÓN FACTURA ");
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" cellpading=\"0\" cellspacng=\"0\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"15%\">ENTIDAD</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"%\">".$this->TerceroNombre."</td>\n";
			$this->salida .= "				<td $style width=\"20%\">".$this->TerceroTipoDoc."</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"15%\">".$this->TerceroDocumento."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>FACTURA Nº</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->FacturaNumero;
			$this->salida .= "				<td $style >FECHA</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->FacturaFechaRegistro."</td>\n";				
			$this->salida .= "			</tr>\n";
			if($this->PlanDescripcion)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style >PLAN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanDescripcion."</td>\n";
				$this->salida .= "				<td $style >Nº CONTRATO</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanNumeroContrato."</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style >RESPONSABLE</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->GlosaUsuario."</td>\n";
			$this->salida .= "				<td $style >FECHA GLOSA</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->GlosaFechaRegistro."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			
			$this->salida .= "		<table align=\"center\" cellpading=\"0\" cellspacng=\"0\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"40%\"><b>FECHA DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "						".$this->GlosaFechaGlosamiento."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			if($this->GlosaMotivoGlosamiento != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style ><b>MOTIVO DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" align=\"justify\">\n";
				$this->salida .= "					".$this->GlosaMotivoGlosamiento ."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->GlosaClasificacion != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style ><b>CLASIFICACIÓN DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->GlosaClasificacion."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->AuditorNombre != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style ><b>AUDITOR</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->AuditorNombre."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style ><b>TIPO GLOSA DOCUMENTO</b></td>\n";
			
			switch($this->GlosaSwGlosaFactura)
			{
				case '0':
					$mensaje = "LA GLOSA ES SOBRE CARGOS DE LA FACTURA";
				break;
				case '1':
					$mensaje = "LA GLOSA ES SOBRE TODA LA FACTURA";
				break;
				case '2':
					$mensaje = "LA GLOSA ES DE UNA FACTURA EXTERNA";
				break;
			}
			
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					<b class=\"label_mark\">".$mensaje."</b>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			if($this->GlosaDocumentoCliente != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>DOCUMENTO INTERNO DEL CLIENTE Nº</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->GlosaDocumentoCliente."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>VALOR DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaValorGlosado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>VALOR ACEPTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaValorAceptado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>VALOR NO ACEPTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->GlosaValorNoAceptado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>ESTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					<b class=\"label_mark\">POR CONTABILIZAR</b>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			if($this->GlosaObservacionGlosamiento != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td colspan=\"2\"><b>OBSERVACIÓN</b></td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">";
				$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion_glosa\" style=\"width:100%\" rows=\"3\" readonly>".$this->GlosaObservacionGlosamiento."</textarea>";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";

			}
			$this->salida .= "		</table><br>\n";
						
			if($this->GlosaSwGlosaFactura == '0')
			{ 	
				$Cargos = $this->ObtenerCargosGlosados($this->GlosaId);					
				if(sizeof($Cargos) > 0)
				{
					for($i=0; $i<sizeof($Cargos);)
					{
						$j = $i;
						$SiguienteMotivo = "";
						$cargo = $insumos = false;
						$Celdas = explode("*",$Cargos[$i]);
						$NumeroCuenta = $SigNumeroCuenta = $Celdas[0];
						
						while($NumeroCuenta == $SigNumeroCuenta)
						{
							$Motivo = $Celdas[1];
							switch($Celdas[5])
							{
								case 'DT':
									$this->salida .= "		<table align=\"center\" cellpading=\"0\" cellspacng=\"0\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
									$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "				<td $style width=\"20%\"><b>NUMERO CUENTA: </b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"25%\" align=\"center\">".$Celdas [0]."</td>\n";
									$this->salida .= "				<td $style width=\"30%\" colspan=\"2\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"20%\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									$this->salida .= "			</tr>\n";
									$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "				<td  width=\"20%\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
									$this->salida .= "			</tr>\n";
								break;
								case 'DA':
									$this->salida .= "		<table align=\"center\" cellpading=\"0\" cellspacng=\"0\" width=\"90%\" border=\"0\" class=\"modulo_table_list\">\n";
									$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "				<td $style width=\"20%\" colspan=\"2\" ><b>NUMERO CUENTA: </b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"25%\" align=\"center\">".$Celdas [0]."</td>\n";
									if($Celdas[3] > 0)
									{
										$this->salida .= "				<td $style width=\"30%\" colspan=\"2\" align=\"center\"><b>VALOR ACEPTADO COPAGO - CUOTA</b></td>\n";
										$this->salida .= "				<td class=\"modulo_list_claro\" width=\"20%\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									}
									else
									{
										$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\" width=\"50%\"></td>\n";
									}
									
									$this->salida .= "			</tr>\n";
									if($Celdas[1] != "")
									{
										$this->salida .= "			<tr>\n";
										$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"2\" width=\"20%\" align=\"center\"><b $estilo>MOTIVO DE GLOSA CUENTA</b></td>\n";
										$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										$this->salida .= "			</tr>\n";
									}
								break;
								case 'DC':
									if(!$cargo)
									{
										$this->salida .= "			<tr>\n";
										$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\"><b>CARGOS</b></td>\n";
										$this->salida .= "			</tr>\n";
										$cargo = true;
									}
									
									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										$this->salida .= "			<tr class=\"modulo_table_list_title\" >\n";
										$this->salida .= "				<td $style colspan=\"2\" width=\"20%\" align=\"center\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
										$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										$this->salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}
									$marca = "";
									if(strlen($Celdas[4]) > 49)
									{
										$marca = " title = \"".$Celdas[4]." \" ";
										$Celdas[4] = substr($Celdas[4],0,50)."....";
									}
									$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "				<td $style width=\"10%\" align=\"center\"><b>CARGO </b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"10%\" align=\"center\">".$Celdas[3]."</td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" align=\"justify\" colspan=\"2\" $marca>".$Celdas[4]."</td>\n";
									$this->salida .= "				<td $style width=\"15%\" align=\"center\">VALOR ACEPTADO</td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" width=\"15%\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									$this->salida .= "			</tr>\n";
								break;
								case 'DI':
									if(!$insumos)
									{
										$this->salida .= "			<tr>\n";
										$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\"><b>INSUMOS Y MEDICAMENTOS</b></td>\n";
										$this->salida .= "			</tr>\n";
										$insumos = true;
									}
									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
										$this->salida .= "				<td $style width=\"20%\" colspan=\"2\" align=\"center\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
										$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										$this->salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}
									
									$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
									$this->salida .= "				<td $style width=\"20%\" colspan=\"2\" align=\"center\"><b>PRODUCTO</b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" align=\"justify\" colspan=\"2\" >".$Celdas[4]."</td>\n";
									$this->salida .= "				<td $style width=\"15%\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
									$this->salida .= "				<td class=\"modulo_list_claro\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									$this->salida .= "			</tr>\n";
								break;
							}
							$j++;
							$Celdas = explode("*",$Cargos[$j]);
							$SigNumeroCuenta = $Celdas[0];
						}
						$i = $j;
						$this->salida .= "		</table><br>\n";
					}
				}
			}
			$this->salida .= "	<br><table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			
			$this->salida .= "				<form name=\"notadebito\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Generar Nota Credito\" onclick=\"this.disabled = true;\"></td>\n";
			$this->salida .= "				</form>\n";
			
			$this->salida .= "				<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\"></td>\n";
			$this->salida .= "				</form>\n";
			
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
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
				if ($campo=="MensajeError"){
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
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaInformacion($parametro)
		{
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$parametro."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			
			if($this->Imprimir)
			{				
				$reporte = new GetReports();
				$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCD','notacredito',
																						array("nota_credito"=>$this->NotaCreditoNumero,"glosa"=>$this->GlosaId,"codigo"=>$this->CodigoNC),
																						array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion = $reporte->GetJavaFunction();			
				$this->salida .= "		".$mostrar."\n";				
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Nota\" onclick=\"$funcion\">\n";
				$this->salida .= "			</td>\n";
			}
			
			if($this->actionM)
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
		/**************************************************************************************
		* Funcion mediante la cual se despliega en pantalla las notas credito que se han 
		* realizado 
		***************************************************************************************/
		function FormaMostrarNotasCredito()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTAR NOTAS CREDITO");
			if(!empty($_SESSION['NotasCD']['empresa']))
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
				$this->salida .= "	<table width=\"70%\" align=\"center\" >\n";		
				$this->salida .= "		<tr><td>\n";
				$this->salida .= $this->BuscadorNotas();
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "	</table><br>\n";
				$Notas = $this->ObtenerNotasCredito();
					
				if(sizeof($Notas) > 0)
				{
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCD','gruponotascd',
																							array("tercero_id"=>$this->TerceroId,"tipo_id_tercero"=>$this->TerceroTipoId,"nombreTercero"=>$this->NombreTercero,
																								  	"numero_glosa"=>$this->GlosaNumero,"fecha_inicio"=>$this->GlosaFechaI,"fecha_fin"=>$this->GlosaFechaF),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = $reporte->GetJavaFunction();
					$this->salida .= $mostrar;
					$this->salida .= "  <br><center><a href=\"javascript:$funcion\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;REPORTE DE TODAS LAS NOTAS</a></center><br>\n";

					$this->salida .= "	<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";		
					$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "				<td width=\"6%\" ><b>NOTA</b></td>\n";
					$this->salida .= "				<td width=\"%\"  ><b>CLIENTE</b></td>\n";
					$this->salida .= "				<td width=\"7%\" ><b>FACTURA</b></td>\n";
					$this->salida .= "				<td width=\"7%\" ><b>GLOSA</b></td>\n";
					$this->salida .= "				<td width=\"8%\" ><b>F. GLOSA</b></td>\n";
					$this->salida .= "				<td width=\"19%\"><b>USUARIO</b></td>\n";
					$this->salida .= "				<td width=\"9%\" ><b>V. GLOSADO</b></td>\n";
					$this->salida .= "				<td width=\"9%\" ><b>V. ACEPTADO</b></td>\n";
					$this->salida .= "				<td width=\"9%\" ><b>V. NO ACEPTADO</b></td>\n";
					$this->salida .= "				<td width=\"8%\" ><b>OPCIONES</b></td>\n";
					$this->salida .= "			</tr>";
					for($i=0; $i< sizeof($Notas); $i++ )
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro'; $background = "#DDDDDD";
						}
						
						$Celdas = $Notas[$i];
						$actionLink = ModuloGetURL('app','FacturacionNotaCD','user','MostrarInformacionNotaCredito',
			 																	array("pagina"=>$this->paginaActual,"glosa_id"=>$Celdas['glosa_id'],"codigo"=>$Celdas['codigo'],
			 																				"nota_numero"=>$Celdas['numero'],"nota_prefijo"=>$Celdas['prefijo_nota']));
			 					
						$opcion  = "	<a class=\"label_error\" href=\"".$actionLink."\" title=\"INFORMACION NOTA CREDITO\">\n";
						$opcion .= "	<img src=\"".GetThemePath()."/images/tabla.png\" border=\"0\">\n";
						$opcion .= "	<b class=\"label-error\">NOTA</b></a>\n";
						
						$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "				<td align=\"left\"	 >".$Celdas['prefijo_nota']." ".$Celdas['numero']."</td>\n";
						$this->salida .= "				<td align=\"justify\">".$Celdas['nombre_tercero']."</td>\n";
						$this->salida .= "				<td align=\"left\"   >".$Celdas['prefijo']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "				<td align=\"right\"  >".$Celdas['glosa_id']."</td>\n";
						$this->salida .= "				<td align=\"center\" >".$Celdas['fecha_glosa']."</td>\n";
						$this->salida .= "				<td align=\"justify\">".$Celdas['nombre']."</td>\n";
						$this->salida .= "				<td align=\"right\"  >".formatoValor($Celdas['valor_glosa'])."&nbsp;</td>\n";
						$this->salida .= "				<td align=\"right\"  >".formatovalor($Celdas['valor_aceptado'])."&nbsp;</td>\n";
						$this->salida .= "				<td align=\"right\"  >".formatoValor($Celdas['valor_no_aceptado'])."&nbsp;</td>\n";
						$this->salida .= "				<td align=\"center\" >".$opcion."</td>\n";
						$this->salida .= "			</tr>\n";
					}
					$this->salida .= "	</table>\n";
					$this->salida .= "		<br>\n";
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionP);
					$this->salida .= "		<br>\n";
				}
				else
				{
					$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUNA NOTA CREDITO REALIZADA</b></center><br><br>\n";
				}
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
			}
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;		
		}
		/****************************************************************************************************
		* Funcion que permite desplegar la informacion de una nota credito 
		*****************************************************************************************************/
		function FormaMostrarInformacionNotaCredito()
		{
			$this->salida .= ThemeAbrirTabla("INFORMACION NOTA CREDITO Nº ".$this->NotaCreditoNumero);
			$this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>&nbsp;Nº NOTA</b></td>";
			$this->salida .= "			<td width=\"25%\" class=\"modulo_list_claro\">".$this->NotaCreditoNumero."</td>";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>&nbsp;FECHA REGISTRO</b></td>";
			$this->salida .= "			<td width=\"25%\" class=\"modulo_list_claro\">".$this->NotaCreditoFechaRegistro."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>&nbsp;RESPONSABLE</b></td>";
			$this->salida .= "			<td width=\"75%\" align=\"left\" colspan=\"3\" class=\"modulo_list_claro\">".$this->NotaCreditoResponsable."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";			
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>ENTIDAD</b></td>";
			$this->salida .= "			<td width=\"50%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">".$this->TerceroNombre."</td>";
			$this->salida .= "			<td width=\"%\" style=\"text-align:left;text-indent:11pt\"><b>".$this->TerceroNit."</b></td>";
			$this->salida .= "			<td width=\"%\" align=\"left\" class=\"modulo_list_claro\">".$this->TerceroId."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>Nº GLOSA</b></td>";
			$this->salida .= "			<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\">".$this->GlosaIdentificador."</td>";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>FACTURA</b></td>";
			$this->salida .= "			<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">".$this->GlosaFactura."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>FECHA REGISTRO</b></td>";
			$this->salida .= "			<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\">".$this->GlosaFechaGlosamiento."</td>";
			$this->salida .= "			<td width=\"25%\" style=\"text-align:left;text-indent:11pt\"><b>FECHA CIERRE</b></td>";
			$this->salida .= "			<td width=\"25%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">".$this->GlosaFechaCierre."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>VALOR GLOSADO</b></td>";
			$this->salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\" class=\"modulo_list_claro\">".formatoValor($this->GlosaValorGlosado)."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>VALOR ACEPTADO</b></td>";
			$this->salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\" class=\"modulo_list_claro\">".formatoValor($this->GlosaValorAceptado)."</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">";
			$this->salida .= "			<td width=\"25%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>VALOR NO ACEPTADO</b></td>";
			$this->salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\" class=\"modulo_list_claro\">".formatoValor($this->GlosaValorNoAceptado)."</td>";
			$this->salida .= "		</tr>";
			if($this->GlosaTipoClasificacion != "")
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>CLASIFICACIÓN</b></td>";
				$this->salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">".$this->GlosaTipoClasificacion."</td>";
				$this->salida .= "		</tr>";
			}
			if($this->GlosaMotivoGlosamiento != "")
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>MOTIVO GLOSA</b></td>";
				$this->salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" class=\"modulo_list_claro\" align=\"justify\">".$this->GlosaMotivoGlosamiento."</td>";
				$this->salida .= "		</tr>";
			}
			if($this->GlosaObservacion != "")
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>OBSERVACIÓN DE GLOSA</b></td>";
				$this->salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" class=\"modulo_list_claro\" align=\"justify\">".$this->GlosaObservacion."</td>";
				$this->salida .= "		</tr>";
			}
			if($this->GlosaDocumentoInterno != "")
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>DOCUMENTO INTERNO DEL CLIENTE</b></td>";
				$this->salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">".$this->GlosaDocumentoInterno."</td>";
				$this->salida .= "		</tr>";
			}
			if($this->GlosaAuditor != "")
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:11pt\"><b>AUDITOR(A)</b></td>";
				$this->salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">".$this->GlosaAuditor."</td>";
				$this->salida .= "		</tr>";
			}
			
			$observacion = $this->ObtenerObservaciones($this->GlosaIdentificador);
			
			if(sizeof($observacion) > 0)
			{
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"5\"><b>OBSERVACIÓNES DE LA NOTA</b></td>";
				$this->salida .= "		</tr>";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">";
				$this->salida .= "			<td width=\"50%\" colspan=\"5\" align=\"justify\" class=\"modulo_list_claro\" align=\"justify\">";
				$this->salida .= "				<menu>\n";
				for($i=0; $i< sizeof($observacion); $i++)
					$this->salida .= "					<li>".$observacion[$i]['observacion']."\n";
				
				$this->salida .= "				</menu>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
			}
			
			$this->salida .= "	</table><br>\n";
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCD','notacredito',
												array("nota_credito"=>$this->NotaCreditoNumero,"glosa"=>$_REQUEST['glosa_id'],"codigo"=>$_REQUEST['codigo']),
												array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();
			$this->salida .= $mostrar;
			$this->salida .= "  <br><center><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion\" class=\"label_error\">REPORTE</a></center><br>\n";

			
			$this->salida .= "	<table width=\"80%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del b¡uscador 
		* 
		* @return string 
		**********************************************************************************************/
		function BuscadorNotas()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->actionB."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
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
			$buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$buscador .= "		}\n";
			$buscador .= "		function limpiarCampos(objeto)\n";
			$buscador .= "		{\n";
			$buscador .= "			objeto.numero_glosa.value = \"\";\n";
			$buscador .= "			objeto.fecha_fin.value = \"\";\n";
			$buscador .= "			objeto.tercero_id.value = \"\";\n";
			$buscador .= "			objeto.fecha_inicio.value = \"\";\n";
			$buscador .= "			objeto.nombreTercero.value = \"\";\n";
			$buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			$TiposTerceros = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones = explode("/",$TiposTerceros[$i]);
				if($this->TerceroTipoId == $opciones[0])
				{
					$buscador .= "						<option value='".$opciones[0]."' selected>".ucwords(strtolower($opciones[1]))."</option>\n";
				}
				else
				{
					$buscador .= "						<option value='".$opciones[0]."'>".ucwords(strtolower($opciones[1]))."</option>\n";			
				}
			}
			$buscador .= "					</select></td></tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"25\" maxlength=\"32\" value=\"".$this->TerceroId."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">NOMBRE TERCERO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombreTercero\" size=\"25\" maxlength=\"100\" value=\"".$this->NombreTercero."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";			
			$buscador .= "			<tr><td class=\"label\">NUMERO GLOSA</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"numero_glosa\" onkeypress=\"return acceptNum(event)\" size=\"25\" maxlength=\"100\" value=\"".$this->GlosaNumero ."\">\n";
			$buscador .= "				</td></tr>";
			$buscador .= "			<tr><td class=\"label\">FECHA GLOSA</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->GlosaFechaI."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->GlosaFechaF."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_fin','/')."\n";
			$buscador .= "				</td></tr>\n";
			$buscador .= "			<tr><td class=\"label\" align=\"center\" colspan=\"5\"><br>\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td></tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador; 
		}
	}
?>
