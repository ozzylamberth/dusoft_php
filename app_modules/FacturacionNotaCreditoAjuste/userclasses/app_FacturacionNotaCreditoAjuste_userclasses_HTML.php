<?php
	/**  
	* $Id: app_FacturacionNotaCreditoAjuste_userclasses_HTML.php,v 1.2 2010/03/12 18:41:36 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	*/
	IncludeClass("ClaseHTML");
	IncludeClass('NotasDebitoHTML','','app','FacturacionNotaCreditoAjuste');
	class app_FacturacionNotaCreditoAjuste_userclasses_HTML extends app_FacturacionNotaCreditoAjuste_user
	{
		function app_FacturacionNotaCreditoAjuste_userclasses_HTML()
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
			$Empresas = $this->BuscarEmpresasUsuario();
			$titulo[0]='EMPRESAS';
			$url[0]='app';													//contenedor 
			$url[1]='FacturacionNotaCreditoAjuste';	//módulo 
			$url[2]='user';													//clase 
			$url[3]='FormaSubmenuPrincipal';				//método 
			$url[4]='permisoajuste';								//indice del request
			$this->salida .= gui_theme_menu_acceso('AJUSTE DE NOTAS CREDITO',$titulo,$Empresas,$url,ModuloGetURL('system','Menu'));
			return true;
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma que permite crear las notas de ajuste
		* 
		* @return boolean
		***********************************************************************************/
		function FormaSubmenuPrincipal()
		{
			$this->ObtenerVatriables();
			$this->salida  = ThemeAbrirTabla('AJUSTE DE NOTAS CREDITO - OPCIONES');
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td width=\"40%\">\n";
			$this->salida .= "			<table border=\"0\" width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"modulo_table_list_title\" height=\"20\">MENÚ</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH2."\">\n";
			$this->salida .= "							<b>CREAR NOTAS - CREDITO</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH3."\">\n";
			$this->salida .= "							<b>CREAR NOTAS - DEBITO</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH7."\">\n";
			$this->salida .= "							<b>CREAR NOTAS CREDITO - SISTEMA EXTERNO</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH4."\">\n";
			$this->salida .= "							<b>NOTAS CREDITO - CONSULTAR</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH5."\">\n";
			$this->salida .= "							<b>NOTAS DEBITO - CONSULTAR</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\"label\" align=\"center\" height=\"17\">\n";
			$this->salida .= "						<a href=\"".$this->actionH6."\">\n";
			$this->salida .= "							<b>NOTAS DE AJUSTE DE FACTURAS</b></a>\n";
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
		*
		***********************************************************************************/
		function FormaAdicionarConceptos()
		{
			$this->AdicionarConceptos();
			$es = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 8pt\" height=\"19\"";
			
			$this->salida .= ThemeAbrirTabla("DETALLE DE LA NOTA DE AJUSTE ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Cambiar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			sw_centro = objeto.concepto.value.split(\"*\");\n";
			$this->salida .= "			if(sw_centro[2] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.departamento.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.departamento.selectedIndex = 0;\n";
			$this->salida .= "					objeto.departamento.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			if(sw_centro[3] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.boton_tercero.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.nombre_tercero.value = \"\";\n";
			$this->salida .= "					objeto.tercero_identifica.value = \"\";\n";
			$this->salida .= "					objeto.boton_tercero.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function BuscarTercero()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action7."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "		".$this->SetStyle($this->Parametro)."\n";
			$this->salida .= "	</table>\n";
			
			$ConceptosAjuste = $this->ObtenerConceptos();
			if(sizeof($ConceptosAjuste) > 0)
			{
				$this->salida .= "<form name=\"adicionarconcepto\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "				<select name=\"concepto\" class=\"select\" onChange=\"Cambiar(document.adicionarconcepto)\">\n";
				$this->salida .= "					<option value='0'>-----SELECCIONAR-----</option>\n";
				for($i=0; $i<sizeof($ConceptosAjuste); $i++)
				{
					($this->Concepto == $ConceptosAjuste[$i]['concepto_id'])? $sel = " selected ":$sel = "";
					
					$this->salida .= "					<option value='".$ConceptosAjuste[$i]['concepto_id']."*".$ConceptosAjuste[$i]['sw_naturaleza']."*".$ConceptosAjuste[$i]['sw_centro_costo']."*".$ConceptosAjuste[$i]['sw_tercero']."' $sel>".$ConceptosAjuste[$i]['descripcion']."</option>\n";
				}
				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" align=\"center\" width=\"20%\">\n";
				$this->salida .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"14\" maxlength=\"15\" style=\"width:95%\" value=\"".$this->ValorConcepto."\" onKeypress=\"return acceptNum(event);\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				
				$Deptno = $this->ObtenerDepartamentos();
				if(sizeof($Deptno))
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
					$this->salida .= "				<select name=\"departamento\" class=\"select\" disabled>\n";
					$this->salida .= "					<option value='0'>-------SELECCIONAR-------</option>\n";
					for($i=0; $i<sizeof($Deptno); $i++)
					{
						($this->Departamento == $Deptno[$i]['departamento'])? $sel = " selected ":$sel = "";
						
						$this->salida .= "					<option value='".$Deptno[$i]['departamento']."' $sel>".$Deptno[$i]['descripcion']."</option>\n";
					}
					$this->salida .= "				</select>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>TERCERO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
					$this->salida .= "				<input type=\"text\" name=\"nombre_tercero\" class=\"input-text\" value=\"".$this->TerceroNombre."\" size=\"20\" style=\"width:100%\" readonly>\n";
					$this->salida .= "				<input type=\"hidden\" name=\"tercero_identifica\" value=\"".$this->TerceroIdentificador."\" >\n";					
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Buscar Tercero\" name=\"boton_tercero\" disabled onclick=\"BuscarTercero()\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					if($this->Script == 1)
					{
						$this->salida .= "<script>\n";
						$this->salida .= "		document.adicionarconcepto.departamento.disabled = false;\n";
						$this->salida .= "</script>\n";
					}
					if($this->Script2 == 1)
					{
						$this->salida .= "<script>\n";
						$this->salida .= "		document.adicionarconcepto.boton_tercero.disabled = false;\n";
						$this->salida .= "</script>\n";
					}					
				}
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"15%\" colspan=\"3\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form><br>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<a class=\"label_error\" href=\"".$this->action3."\" title=\"OBSERVACIÓN NOTA AJUSTE\">\n";
			$this->salida .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>OBSERVACIÓN</b>";
			$this->salida .= "					</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<a class=\"label_error\" href=\"".$this->action4."\" title=\"CRUZAR FACTURAS\">\n";
			$this->salida .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\"border=\"0\"><b>CRUZAR FAC</b>";
			$this->salida .= "				</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$Concep = $this->ObtenerValorConceptos();
			if(sizeof($Concep) > 0 || sizeof($this->ValorFactura) > 0 )
			{	
				$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"45%\"><b>CONCEPTO</b></td>\n";
				$this->salida .= "			<td width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
				$this->salida .= "			<td width=\"8%\"><b>DEBITO</b></td>\n";
				$this->salida .= "			<td width=\"8%\"><b>CREDITO</b></td>\n";
				$this->salida .= "			<td width=\"2%\"><b>X</b></td>\n";
				$this->salida .= "		</tr>\n";
			
				for($i=0; $i<sizeof($this->ValorFactura); $i++)
				{
					$opcion  = "	<a href=\"".$this->action6[$i]."\" >\n";
					$opcion .= "		<img src=\"".GetThemePath()."/images/delete.gif\" title=\"DESVINCULAR FACTURAS\" border=\"0\">";
					$opcion .= "	</a>\n";
					
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td $es ><b>ABONO FACTURA ".$this->ValorFactura[$i]['prefijo_factura']." ".$this->ValorFactura[$i]['factura_fiscal']."</b></td>\n";
					$this->salida .= "			<td $es ><b></b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>".formatoValor($this->ValorFactura[$i]['abono'])."</b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\">$opcion</td>\n";
					$this->salida .= "		</tr>\n";
					$this->Creditos += $this->ValorFactura[$i]['abono'];
				}
						
				for($i=0; $i<sizeof($Concep); $i++)
				{
					switch($Concep[$i]['naturaleza'])
					{
						case 'C':
							$cred = formatoValor($Concep[$i]['valor']);
							$debi = "0";
							$this->Creditos += $Concep[$i]['valor'];
						break;
						case 'D':
							$debi = formatoValor($Concep[$i]['valor']);
							$cred = "0";
							$this->Debitos += $Concep[$i]['valor'];
						break;
					}
					
					$opcion  = "	<a href=\"".$this->action5[$i]."\" >\n";
					$opcion .= "		<img src=\"".GetThemePath()."/images/delete.gif\" title=\"ELIMINAR CONCEPTO\" border=\"0\">";
					$opcion .= "	</a>\n";
					
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td $es ><b>".$Concep[$i]['descripcion']."</b></td>\n";
					$this->salida .= "			<td $es ><b>".$Concep[$i]['departamento']."</b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>".$debi."</b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>".$cred."</b></td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\">$opcion</td>\n";
					$this->salida .= "		</tr>\n";
				}

				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$this->salida .= "			<td colspan=\"2\" style=\"text-align:left; font-size:10px;text-indent: 8pt\"><b>TOTALES</b></td>\n";
				$this->salida .= "			<td align=\"right\"><b>".formatoValor($this->Debitos)."</b></td>\n";
				$this->salida .= "			<td align=\"right\"><b>".formatoValor($this->Creditos)."</b></td>\n";
				$this->salida .= "			<td ></td>\n";
				$this->salida .= "		</tr>\n";
				
				if($this->Debitos < $this->Creditos)
				{ 
					$cred = formatoValor($this->Creditos-$this->Debitos);
					$debi = "0";
				}
				else
				{
					$debi = formatoValor($this->Debitos-$this->Creditos);
					$cred = "0";
				}
				
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$this->salida .= "			<td colspan=\"2\" style=\"text-align:left; font-size:10px;text-indent: 8pt\"><b>SALDO</b></td>\n";
				$this->salida .= "			<td align=\"right\"><b>".$debi."</b></td>\n";
				$this->salida .= "			<td align=\"right\"><b>".$cred."</b></td>\n";
				$this->salida .= "			<td ></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
			}
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaCruzarNotas()
		{
			$this->CruzarNotas();
			$Facturas = $this->ObtenerFacturas();
			
			$this->salida .= ThemeAbrirTabla("LISTADO DE FACTURAS");
			$this->salida .= "	<script>\n";
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
			$this->salida .= "		function pasarValor(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valorpago[i].value = objeto.valorsug[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{	objeto.valorpago.value = objeto.valorsug.value;}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarTodos(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(j=0; j<objeto.valorpago.length; j++)";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valorpago[j].value = objeto.valorsug[j].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table width=\"60%\" align=\"center\" >\n";		
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= $this->Buscador();
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "		<tr><td><br>\n";
			$this->salida .= $this->BuscadorRapidoFactura();
			$this->salida .= "		<br></td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "	<table class=\"modulo_table_list\" width=\"60%\" align=\"center\">\n";
			$this->salida .= "		<tr height=\"20\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">VALOR NOTA:</td>\n";
			$this->salida .= "			<td width=\"14%\" align=\"right\"><b>".formatoValor($this->Debitos)."</b></td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">VALOR PAGADO:</td>\n";
			$this->salida .= "			<td width=\"14%\" align=\"right\"><b>".formatoValor($this->Creditos)."</b></td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">VALOR RESTANTE:</td>	\n";
			$this->salida .= "			<td width=\"12%\" align=\"right\"><b>".formatoValor($this->Debitos-$this->Creditos)."</b></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= "	<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "		".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "	</table><br>\n";

			if(sizeof($Facturas) > 0)
			{
				$this->salida .= "<form name=\"cruzarrecibo\" action=\"".$this->action4."\" method=\"post\">\n";
				$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"8%\">FACTURA</td>\n";
				$this->salida .= "			<td width=\"9%\">FECHA</td>\n";
				$this->salida .= "			<td width=\"%\">CLIENTE</td>\n";
				$this->salida .= "			<td width=\"10%\">V. FACTURA</td>\n";
				$this->salida .= "			<td width=\"10%\">SALDO</td>\n";
				$this->salida .= "			<td width=\"10%\">ABONO</td>\n";
				$this->salida .= "			<td width=\"10%\">SUGERIDO</td>\n";
				if(sizeof($Facturas) > 1)
				{
					$this->salida .= "			<td width=\"3%\" >\n";
					$this->salida .= "				<img src=\"".GetThemePath()."/images/ultimo.png\" onclick=\"pasarTodos(document.cruzarrecibo)\" title=\"ASIGNAR TODOS LOS VALORES\" border=\"0\" >";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td width=\"%\"></td>\n";
				}
				else
				{
					$this->salida .= "			<td width=\"%\" colspan=\"2\"></td>\n";
				}
				$this->salida .= "		</tr>\n";
	
				for($i = 0,$j=0; $i<sizeof($Facturas); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$saldo = $Facturas[$i]['saldo'] - $Facturas[$i]['abono'];
					($this->TmpValor < $saldo)?	$sugerido = $this->TmpValor:	$sugerido = $saldo;
					
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$this->salida .= "				<td align=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$Facturas[$i]['fecha']."</td>\n";
					$this->salida .= "				<td align=\"justify\"  >".$Facturas[$i]['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."</td>\n";
					$this->salida .= "				<td align=\"right\" >".formatoValor($saldo)."</td>\n";
					if(!$Facturas[$i]['glosa_id'])
					{
						$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['abono'])."</td>\n";
						$this->salida .= "				<td align=\"right\" >".formatoValor($sugerido)."</td>\n";
						$this->salida .= "				<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.cruzarrecibo,".$j.")\" title=\"ASIGNAR VALOR SUGERIDO\" border=\"0\"></td>\n";
						$this->salida .= "				<td>\n";
						$this->salida .= "					<input type=\"hidden\" name=\"valorsug[$j]\" id=\"valorsug\" value=\"".$sugerido."\">\n";
						$this->salida .= "					<input type=\"hidden\" name=\"facturas[$j]\" value=\"".$Facturas[$i]['prefijo']."-".$Facturas[$i]['factura_fiscal']."\">\n";
						$this->salida .= "					<b>$</b><input type=\"text\" id=\"valorpago\" name=\"valorpago[$j]\" class=\"input-text\" size=\"15\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Valor[$j]."\"\>\n";
						$this->salida .= "				</td>\n";
						$j++;
					}
					else
					{
						$this->salida .= "				<td colspan=\"4\" align=\"justify\">\n";
						$this->salida .= "					<b class=\"label_mark\">ESTA FACTURA POSEE UNA GLOSA ACTIVA, SE RECOMIENDA ANULAR O CERRAR LA GLOSA PRIMERO</b>";
						$this->salida .= "				</td>\n";
					}
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
				$this->salida .= "		<tr><td align=\"center\">\n";
				$this->salida .= "				<input type=\"hidden\" name=\"tmpValorNota\" value=\"".$this->TmpValor."\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cruzar Nota De Ajuste\">\n";
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "	</table><br>\n";
				$this->salida .= "</form>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
				$this->salida .= "		<br>\n";

			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
			}
							
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
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
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"70%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"justify\" ><br>";
			$this->salida .= "				".$parametro."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			if($this->action2)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************* 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaConsultarClientes()
		{
			$this->ConsultarClientes();
			$this->salida .= ThemeAbrirTabla("CONSULTAR CLIENTES");
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "  	{\n";
			$this->salida .= "    	var nav4 = window.Event ? true : false;\n";
			$this->salida .= "   	 var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "   	 return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "  	}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table width=\"40%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= $this->BuscadorTerceros();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			$this->salida .= "	<table width=\"40%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= $this->BuscadorRapidoFacturaCliente();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";	
			if(sizeof($this->Clientes) > 0 && $this->Clientes)
			{
				$this->salida .= "	<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$this->salida .= "				<td width=\"22%\">DOCUMENTO</b></td>\n";
				$this->salida .= "				<td width=\"%\"><b>NOMBRE CLIENTE</b></td>\n";
				$this->salida .= "				<td width=\"20%\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				for($i=0; $i< sizeof($this->Clientes); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$Celdas = $this->Clientes[$i];
					
					$this->Arreglo['pagina'] = $this->paginaActual;
					$this->Arreglo['tercero_id'] = $Celdas['tercero_id'];
					$this->Arreglo['tercero_tipo'] = $Celdas['tipo_id_tercero'];
					$this->Arreglo['tercero_nombre'] = $Celdas['nombre_tercero'];
					
					$metodo = "FormaCrearNotaCredito";
					$nota = "CREDITO";
					if($this->TipoFact == 'D') 
					{
						$metodo = "FormaCrearNotaDebito";
						$nota = "DEBITO";
					}
					
					$accion = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user',$metodo,$this->Arreglo);
					
					$opcion  = "	<a class=\"label_error\" href=\"".$accion."\" title=\"CREAR UNA NOTA $nota\">\n";
					$opcion .= "	<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>NOTA $nota</b></a>\n";
					
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"left\"   >".$Celdas['tipo_id_tercero']." ".$Celdas['tercero_id']."</td>\n";
					$this->salida .= "				<td align=\"justify\">".$Celdas['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"center\" >".$opcion."</td>\n";						
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
									
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
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
		* Funcion donde se crean las notas de ajuste
		*
		* @return boolean
		***********************************************************************************/
		function FormaCrearNotaDebito()
		{
			$this->CrearNotasDebito();
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Debitos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$this->salida .= ThemeAbrirTabla("CREAR NOTAS DEBITO");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ConfirmarEliminarNotaDebito(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		valores[1] = datos[1];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ESTA SEGURO QUE DESEA ELIMINAR LA NOTA DEBITO, PERTENECIENTE A LA EMPRERSA ".$this->datos['tercero_nombre']."?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:EliminarNotaDebito(valores)\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ConfirmarCerrar(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ESTA SEGURO QUE DESEA CERRAR LA NOTA DEBITO, PERTENECIENTE A LA EMPRERSA ".$this->datos['tercero_nombre']."?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:CerrarNotaDebito(valores)\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasdebito',array('empresa'=>$this->Empresa),
																					array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();
			
			SessionSetVar("FuncionImprimir",$funcion);
			
			$this->salida .= "".$mostrar."\n";
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "		<td width=\"15%\" class=\"formulacion_table_list\">EMPRESA</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_nombre']."</td>\n";
			$this->salida .= "		<td class=\"formulacion_table_list\" >".$this->datos['tercero_tipo']."</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_id']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "<form name=\"generarNota\" action =\"javascript:CrearNotaDebito(document.generarNota)\" method=\"post\" >\n";
			$this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">CREAR NOTA DEBITO </legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Observacion."</textarea>\n";
			$this->salida .= "								<input type=\"hidden\" name=\"factura\" value=\"\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"prefijo\" value=\"\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\">\n";
			$this->salida .= "								<div id=\"factura\"><a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">ADICIONAR FACTURA</a></div>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td >AUDITOR</td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "								<select name=\"auditor_sel\" class=\"select\">\n";
			$this->salida .= "									<option value='0'>-----SELECCIONAR-----</option>\n";
			
			foreach($this->Auditores as $key => $auditor)
				$this->salida .= "									<option value='".$key."' >".ucwords(strtolower($auditor['nombre']))."</option>\n";
			
			$this->salida .= "								</select>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Crear Nota\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";
			
			$this->salida .= "<div id=\"notasDebito\">\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= $ndhtml->CrearListadoNotasCredito($this->Notas);
			$this->salida .= "</div>\n";
			
			$this->salida .= $ndhtml->CrearCapaVentana();
			$this->salida .= $ndhtml->CrearCapaBuscador($this->Prefijos);
			
			$this->salida .= "<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr><td align=\"center\">\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</td></tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crean las notas de ajuste
		*
		* @return boolean
		***********************************************************************************/
		function FormaCrearNotaCredito()
		{
			$this->CrearNotaCredito();
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Creditos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$this->salida .= ThemeAbrirTabla("CREAR NOTAS CREDITO");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ConfirmarEliminarNotaCredito(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ESTA SEGURO QUE DESEA ELIMINAR LA NOTA CREDITO, PERTENECIENTE A LA EMPRERSA ".$this->datos['tercero_nombre']."?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:EliminarNotaCredito(valores)\";\n";
			$this->salida .= "	}\n";
 			$this->salida .= "	var valores = new Array();\n";
			$this->salida .= "	function ConfirmarCerrar(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		valores[1] = datos[1];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"¿ESTA SEGURO QUE DESEA CERRAR LA NOTA CREDITO, PERTENECIENTE A LA EMPRERSA ".$this->datos['tercero_nombre']."?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
      $this->salida .= "    if(datos[1] == '2')";
			$this->salida .= "		  document.oculta.action = \"javascript:CerrarNotaCredito(valores)\";\n";
			$this->salida .= "		else\n";
			$this->salida .= "		  document.oculta.action = \"javascript:ConfirmarCuenta()\";\n";
			$this->salida .= "	}\n";
      $this->salida .= "	function ConfirmarCuenta()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xGetElementById('confirmacionI').innerHTML = \"¿EL VALOR DE LA NOTA CREDITO ES IGUAL AL TOTAL DE LA FACTURA, DESEA DEJAR LA CUEBNTA ACTIVA O ANULARLA?\"\n";
			$this->salida .= "		IniciarI();\n";
			$this->salida .= "		MostrarSpan('ContenedorI');\n";
			$this->salida .= "	}\n";      
      $this->salida .= "	function EnviarNota(opcion)\n";
			$this->salida .= "	{\n";
      $this->salida .= "		valores[2] = opcion;\n";
			$this->salida .= "		IniciarI();\n";
			$this->salida .= "		MostrarSpan('ContenedorI');\n";
			$this->salida .= "		document.ocultaI.action = \"javascript:CerrarNotaCredito(valores)\";\n";
			$this->salida .= "		document.ocultaI.submit();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notascredito',array('empresa'=>$this->Empresa),
																					array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();
			
			SessionSetVar("FuncionImprimir",$funcion);
			
			$this->salida .= "".$mostrar."\n";
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "		<td width=\"15%\" class=\"formulacion_table_list\">EMPRESA</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_nombre']."</td>\n";
			$this->salida .= "		<td class=\"formulacion_table_list\" >".$this->datos['tercero_tipo']."</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_id']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "<form name=\"generarNota\" action =\"javascript:CrearNotaCredito(document.generarNota)\" method=\"post\" >\n";
			$this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">CREAR NOTA CREDITO </legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Observacion."</textarea>\n";
			$this->salida .= "								<input type=\"hidden\" name=\"factura\" value=\"\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"prefijo\" value=\"\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td >AUDITOR</td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "								<select name=\"auditor_sel\" class=\"select\">\n";
			$this->salida .= "									<option value='0'>-----SELECCIONAR-----</option>\n";
			
			foreach($this->Auditores as $key => $auditor)
				$this->salida .= "									<option value='".$key."' >".ucwords(strtolower($auditor['nombre']))."</option>\n";
			
			$this->salida .= "								</select>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" id=\"factura\">\n";
			$this->salida .= "								<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">ADICIONAR FACTURA</a>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Crear Nota\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";
			
			$this->salida .= "<div id=\"notasCredito\">\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= $ndhtml->CrearListadoNotasCredito($this->Notas,"FormaCrearCuerpoNotasCredito","Credito");
			$this->salida .= "</div>\n";
			
			$this->salida .= $ndhtml->CrearCapaVentana();
			$this->salida .= $ndhtml->CrearCapaBuscador($this->Prefijos);
			
			$this->salida .= "<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr><td align=\"center\">\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</td></tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crean las notas de ajuste
		*
		* @return boolean
		***********************************************************************************/
		function FormaCrearNotaAnticipo()
		{
			$this->CrearNotaAnticipo();
			$this->salida .= ThemeAbrirTabla("CREAR NOTA DE AJUSTE");

			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table align=\"center\" width=\"30%\">\n";
			$this->salida .= "	".$this->SetStyle($this->Parametro)."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"generarNota\" action =\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">CREAR NOTA DE AJUSTE </legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Observacion."</textarea>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Crear Nota\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";
			
			if($this->Imprimir == 1)
			{				
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasajuste',$this->Arreglo,
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = $reporte->GetJavaFunction();

					$this->salida .= "		".$mostrar."\n";				
					$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" >\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "			<td align=\"center\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Nota De Ajuste ".$this->Arreglo['prefijo']." ".$this->Arreglo['nota_credito_ajuste']."\" onclick=\"$funcion\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "	</tr>\n";
					$this->salida .= "</table><br>\n";
			}
			
			$Notas = $this->ObtenerNotasPorAnticipos();	
			
			if(sizeof($Notas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset><legend class=\"field\">NOTAS POR ANTICIPOS ABIERTAS:</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\">REGISTRO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"12%\">DEBITO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"12%\">CREDITO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"12%\">SALDO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"%\" colspan= \"5\">OPCIONES</td>\n";
				$this->salida .= "						</tr>\n";

				for($i = 0; $i<sizeof($Notas); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$saldo = ($Notas[$i]['debitos'])-$Notas[$i]['creditos'];
					
					if($saldo < 0) $saldo = $saldo*(-1);
							
					$opcion1  = "	<a class=\"label_error\" href=\"".$this->action4[$i]."\" title=\"ADICIONAR CONCEPTOS - CRUZAR FACURAS - INGRESAR OBSERVACIÓN\">\n";
					$opcion1 .= "		<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\"><b>DETALLE NA</b>";
					$opcion1 .= "	</a>\n";					
					
					$opcion2  = "	<a class=\"label_error\" href=\"".$this->action5[$i]."\" title=\"CERRAR NOTA DE AJUSTE\">\n";
					$opcion2 .= "		<img src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\"><b>CERRAR NA</b>";
					$opcion2 .= "	</a>\n";
					
					$opcion3  = "	<a class=\"label_error\" href=\"".$this->action6[$i]."\" title=\"ELIMINAR NOTA POR ANTICIPOS\">\n";
					$opcion3 .= "		<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"><b>ELIMINAR</b>";
					$opcion3 .= "	</a>\n";

					$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$this->salida .= "							<td align=\"center\">".$Notas[$i]['fecha']."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($Notas[$i]['debitos'])."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($Notas[$i]['creditos'])."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($saldo)."</td>\n";
					$this->salida .= "							<td >$opcion1</td>\n";
					$this->salida .= "							<td >$opcion2</td>\n";
					$this->salida .= "							<td >$opcion3</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NOTAS DE ANTICIPO ABIERTAS</b></center><br><br>\n";
			}

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaCruzarNotasAnticipos()
		{
			$this->CruzarNotasAnticipos();
			$Facturas = $this->ObtenerFacturasAnticipos();
			
			$this->salida .= ThemeAbrirTabla("LISTADO DE FACTURAS");
			$this->salida .= "	<script>\n";
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
			$this->salida .= "		function pasarValor(objeto,i)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valorpago[i].value = objeto.valorsug[i].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error)\n";
			$this->salida .= "			{	objeto.valorpago.value = objeto.valorsug.value;}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function pasarTodos(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(j=0; j<objeto.valorpago.length; j++)";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.valorpago[j].value = objeto.valorsug[j].value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";/*
			$this->salida .= "	<table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
			$this->salida .= "		<tr height=\"20\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">VALOR ABONADO:</td>\n";
			$this->salida .= "			<td width=\"56%\" align=\"right\"><b>$".formatoValor($this->PagoFacturas)."</b></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";*/
			$this->salida .= "	<table width=\"60%\" align=\"center\" >\n";		
			$this->salida .= "		<tr><td>\n";
			$this->salida .= $this->BuscadorRapidoFactura();
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table align=\"center\" width=\"50%\">\n";
			$this->salida .= "		".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "	</table><br>\n";

			if(sizeof($Facturas) > 0)
			{
				$this->salida .= "<form name=\"cruzarrecibo\" action=\"".$this->action4."\" method=\"post\">\n";
				$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td width=\"11%\">FACTURA</td>\n";
				$this->salida .= "			<td width=\"11%\">FECHA</td>\n";
				$this->salida .= "			<td width=\"15%\">V. FACTURA</td>\n";
				$this->salida .= "			<td width=\"15%\">SALDO</td>\n";
				$this->salida .= "			<td width=\"15%\">ABONO</td>\n";
				$this->salida .= "			<td width=\"15%\">SUGERIDO</td>\n";
				if(sizeof($Facturas) > 1)
				{
					$this->salida .= "			<td width=\"3%\" >\n";
					$this->salida .= "				<img src=\"".GetThemePath()."/images/ultimo.png\" onclick=\"pasarTodos(document.cruzarrecibo)\" title=\"ASIGNAR TODOS LOS VALORES\" border=\"0\" >";
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td width=\"%\"></td>\n";
				}
				else
				{
					$this->salida .= "			<td width=\"%\" colspan=\"2\"></td>\n";
				}
				$this->salida .= "		</tr>\n";
	
				for($i = 0,$j=0; $i<sizeof($Facturas); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$saldo = $Facturas[$i]['saldo'] - $Facturas[$i]['abono'];
					$sugerido = $saldo;
					
					$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$this->salida .= "				<td align=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$Facturas[$i]['fecha']."</td>\n";
					$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."</td>\n";
					$this->salida .= "				<td align=\"right\" >".formatoValor($saldo)."</td>\n";
					if(!$Facturas[$i]['glosa_id'])
					{
						$this->salida .= "				<td align=\"right\" >".formatoValor($Facturas[$i]['abono'])."</td>\n";
						$this->salida .= "				<td align=\"right\" >".formatoValor($sugerido)."</td>\n";
						$this->salida .= "				<td><img src=\"".GetThemePath()."/images/hcright.png\" onclick=\"pasarValor(document.cruzarrecibo,".$j.")\" title=\"ASIGNAR VALOR SUGERIDO\" border=\"0\"></td>\n";
						$this->salida .= "				<td>\n";
						$this->salida .= "					<input type=\"hidden\" name=\"valorsug[$j]\" id=\"valorsug\" value=\"".$sugerido."\">\n";
						$this->salida .= "					<input type=\"hidden\" name=\"facturas[$j]\" value=\"".$Facturas[$i]['prefijo']."-".$Facturas[$i]['factura_fiscal']."\">\n";
						$this->salida .= "					<b>$</b><input type=\"text\" id=\"valorpago\" name=\"valorpago[$j]\" class=\"input-text\" size=\"15\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Valor[$j]."\"\>\n";
						$this->salida .= "				</td>\n";
						$j++;
					}
					else
					{
						$this->salida .= "				<td colspan=\"4\" align=\"justify\">\n";
						$this->salida .= "					<b class=\"label_mark\">ESTA FACTURA POSEE UNA GLOSA ACTIVA, SE RECOMIENDA ANULAR O CERRAR LA GLOSA PRIMERO</b>";
						$this->salida .= "				</td>\n";
					}
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
				$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
				$this->salida .= "		<tr><td align=\"center\">\n";
				$this->salida .= "				<input type=\"hidden\" name=\"tmpValorNota\" value=\"".$this->TmpValor."\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cruzar Nota De Ajuste\">\n";
				$this->salida .= "		</td></tr>\n";
				$this->salida .= "	</table><br>\n";
				$this->salida .= "</form>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
				$this->salida .= "		<br>\n";

			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
			}
							
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaModificarInformacion()
		{
			$this->ModificarInformacion();
			$this->salida .= ThemeAbrirTabla("LISTADO DE FACTURAS");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "<table align=\"center\" width=\"30%\">\n";
			$this->salida .= "	".$this->SetStyle($this->Parametro)."\n";
			$this->salida .= "</table>\n";
			$this->salida .= "<form name=\"modificarNota\" action =\"".$this->action2."\" method=\"post\" >\n";
			$this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset><legend class=\"field\">OBSERVACIÓN DE LA NOTA DE AJUSTE:</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Notas['observacion']."</textarea>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Modificar Información\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaBuscarTerceros()
		{
			$this->BuscarTerceros();
			$this->salida .= ThemeAbrirTabla("TERCEROS");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function Guardar(Id,nombre)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.opener.document.adicionarconcepto.tercero_identifica.value=Id;\n";
			$this->salida .= "			window.opener.document.adicionarconcepto.nombre_tercero.value=nombre;\n";
			$this->salida .= "			Cerrar();\n";
			$this->salida .= "		}\n";
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
			$this->salida .= "	<table width=\"70%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= $this->BuscadorTerceros();
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$terceros = $this->ObtenerTerceros();
			if(sizeof($terceros) > 0)
			{
				$this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$this->salida .= "				<td width=\"22%\"><b>DOCUMENTO</b></td>\n";
				$this->salida .= "				<td width=\"75%\"><b>NOMBRE CLIENTE</b></td>\n";
				$this->salida .= "				<td width=\"3%\" ><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				for($i=0; $i< sizeof($terceros); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$Celdas = $terceros[$i];
					
					$opcion  = "	<a class=\"label_error\" href=\"javascript:Guardar('".$Celdas['tipo_id_tercero']."*".$Celdas['tercero_id']."','".$Celdas['nombre_tercero']."')\" title=\"SELECCIONAR\">\n";
					$opcion .= "	<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a>\n";
					
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"left\"   >".$Celdas['tipo_id_tercero']." ".$Celdas['tercero_id']."</td>\n";
					$this->salida .= "				<td align=\"justify\">".$Celdas['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"center\" >$opcion</td>\n";						
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
									
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action1);
				$this->salida .= "		<br>\n";
			}
		
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\" >\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaCrearCuerpoNotas()
		{
			$this->CrearCuerpoNotas();
			$es = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 8pt\" height=\"19\"";
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Debitos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			
			$this->salida .= ThemeAbrirTabla("DETALLE DE LA NOTA DEBITO ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Cambiar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			sw_centro = objeto.concepto.value.split(\"~\");\n";
			$this->salida .= "			if(sw_centro[2] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.departamento.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.departamento.selectedIndex = 0;\n";
			$this->salida .= "					objeto.departamento.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			if(sw_centro[3] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.boton_tercero.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.nombre_tercero.value = \"\";\n";
			$this->salida .= "					objeto.tercero_identifica.value = \"\";\n";
			$this->salida .= "					objeto.boton_tercero.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function BuscarTercero()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action7."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "		<td width=\"15%\" class=\"formulacion_table_list\">EMPRESA</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_nombre']."</td>\n";
			$this->salida .= "		<td class=\"formulacion_table_list\" >".$this->datos['tercero_tipo']."</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_id']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			
			if(sizeof($this->Conceptos) > 0)
			{
				$this->salida .= "<form name=\"adicionarconcepto\" action=\"javascript:EvalAdicionarConcepto(document.adicionarconcepto)\" method=\"post\">\n";
				$this->salida .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "				<select name=\"concepto\" class=\"select\" onChange=\"Cambiar(document.adicionarconcepto)\">\n";
				$this->salida .= "					<option value='0~0~0~0'>-----SELECCIONAR-----</option>\n";
				foreach($this->Conceptos as $key =>$ConceptosAjuste)
					$this->salida .= "					<option value='".$ConceptosAjuste['concepto_id']."~".$ConceptosAjuste['sw_naturaleza']."~".$ConceptosAjuste['sw_centro_costo']."~".$ConceptosAjuste['sw_tercero']."' $sel>".$ConceptosAjuste['descripcion']."</option>\n";

				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" align=\"center\" width=\"20%\">\n";
				$this->salida .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"14\" maxlength=\"15\" style=\"width:95%\" value=\"".$this->ValorConcepto."\" onKeypress=\"return acceptNum(event);\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				
				if(sizeof($this->Deptnos))
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
					$this->salida .= "				<select name=\"departamento\" class=\"select\" disabled>\n";
					$this->salida .= "					<option value='0'>-------SELECCIONAR-------</option>\n";
					foreach($this->Deptnos as $key => $Deptno)
						$this->salida .= "					<option value='".$Deptno['departamento']."' $sel>".$Deptno['descripcion']."</option>\n";
					
					$this->salida .= "				</select>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>TERCERO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
					$this->salida .= "				<input type=\"text\" name=\"nombre_tercero\" class=\"input-text\" style=\"width:100%\" value=\"".$this->TerceroNombre."\" readonly>\n";
					$this->salida .= "				<input type=\"hidden\" name=\"tercero_identifica\" value=\"".$this->TerceroIdentificador."\" >\n";					
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Buscar Tercero\" name=\"boton_tercero\" disabled onclick=\"BuscarTercero()\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";					
				}
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"15%\" colspan=\"3\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<a class=\"label_error\" href=\"javascript:Iniciar();MostrarSpan('Contenedor')\" title=\"OBSERVACIÓN NOTA AJUSTE\">\n";
			$this->salida .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>MODIFICAR OBSERVACIÓN</b>";
			$this->salida .= "				</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= "<div id=\"lista_conceptos\">\n";
			$this->salida .= $ndhtml->CrearListaConceptos($this->AConceptos);
			$this->salida .= "</div>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
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
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$this->salida .= "		<form name=\"oculta\" action=\"javascript:ActualizarInformacion(document.oculta)\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "						<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Nota['observacion']."</textarea>\n";
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
			
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaCrearCuerpoNotasCredito()
		{
			$this->CrearCuerpoNotasCredito();
			$es = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 8pt\" height=\"19\"";
			
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Creditos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			
			$this->salida .= ThemeAbrirTabla("DETALLE DE LA NOTA CREDITO ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Alerta()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try{\n";
			$this->salida .= "				var saldo = ".$this->saldo." ;\n";
			$this->salida .= "				var total = xGetElementById('totalConceptos').value;\n";
			$this->salida .= "				if(total > saldo )\n";
			$this->salida .= "				{\n";
			$this->salida .= "					xGetElementById('mensaje').innerHTML = 'EL VALOR TOTAL DE LA NOTA ($'+ total+') NO DEBE SER MAYOR AL SALDO DE LA FACTURA ($'+saldo+')';\n";
			$this->salida .= "					IniciarA();\n";
			$this->salida .= "					MostrarSpan('Alerta');\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Cambiar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			sw_centro = objeto.concepto.value.split(\"~\");\n";
			$this->salida .= "			if(sw_centro[2] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.departamento.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.departamento.selectedIndex = 0;\n";
			$this->salida .= "					objeto.departamento.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			if(sw_centro[3] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.boton_tercero.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.nombre_tercero.value = \"\";\n";
			$this->salida .= "					objeto.tercero_identifica.value = \"\";\n";
			$this->salida .= "					objeto.boton_tercero.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function BuscarTercero()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action7."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "		<td width=\"15%\" class=\"formulacion_table_list\">EMPRESA</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_nombre']."</td>\n";
			$this->salida .= "		<td class=\"formulacion_table_list\" >".$this->datos['tercero_tipo']."</td>\n";
			$this->salida .= "		<td class=\"normal_10AN\" >".$this->datos['tercero_id']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			
			if(sizeof($this->Conceptos) > 0)
			{
				$this->salida .= "<form name=\"adicionarconcepto\" action=\"javascript:EvalAdicionarConcepto(document.adicionarconcepto)\" method=\"post\">\n";
				$this->salida .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "				<select name=\"concepto\" class=\"select\" onChange=\"Cambiar(document.adicionarconcepto)\">\n";
				$this->salida .= "					<option value='0~0~0~0'>-----SELECCIONAR-----</option>\n";
				foreach($this->Conceptos as $key =>$ConceptosAjuste)
					$this->salida .= "					<option value='".$ConceptosAjuste['concepto_id']."~".$ConceptosAjuste['sw_naturaleza']."~".$ConceptosAjuste['sw_centro_costo']."~".$ConceptosAjuste['sw_tercero']."' $sel>".$ConceptosAjuste['descripcion']."</option>\n";

				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" align=\"center\" width=\"20%\">\n";
				$this->salida .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"14\" maxlength=\"15\" style=\"width:95%\" value=\"".$this->ValorConcepto."\" onKeypress=\"return acceptNum(event);\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				
				if(sizeof($this->Deptnos))
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
					$this->salida .= "				<select name=\"departamento\" class=\"select\" disabled>\n";
					$this->salida .= "					<option value='0'>-------SELECCIONAR-------</option>\n";
					foreach($this->Deptnos as $key => $Deptno)
						$this->salida .= "					<option value='".$Deptno['departamento']."' $sel>".$Deptno['descripcion']."</option>\n";
					
					$this->salida .= "				</select>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>TERCERO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
					$this->salida .= "				<input type=\"text\" name=\"nombre_tercero\" class=\"input-text\" style=\"width:100%\" value=\"".$this->TerceroNombre."\" readonly>\n";
					$this->salida .= "				<input type=\"hidden\" name=\"tercero_identifica\" value=\"".$this->TerceroIdentificador."\" >\n";					
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Buscar Tercero\" name=\"boton_tercero\" disabled onclick=\"BuscarTercero()\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";					
				}
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"15%\" colspan=\"3\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<a class=\"label_error\" href=\"javascript:Iniciar();MostrarSpan('Contenedor')\" title=\"OBSERVACIÓN NOTA AJUSTE\">\n";
			$this->salida .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>MODIFICAR OBSERVACIÓN</b>";
			$this->salida .= "				</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= "<div id=\"lista_conceptos\">\n";
			$this->salida .= $ndhtml->CrearListaConceptos($this->AConceptos);
			$this->salida .= "</div>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Contenedor';\n";
			$this->salida .= "		titulo = 'titulo';\n";			
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
			
			$this->salida .= "	function IniciarA()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Alerta';\n";
			$this->salida .= "		titulo = 'tituloa';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,330, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrara');\n";
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
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$this->salida .= "		<form name=\"oculta\" action=\"javascript:ActualizarInformacion(document.oculta)\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "						<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Nota['observacion']."</textarea>\n";
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
			
			$this->salida .= "<div id='Alerta' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='tituloa' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$this->salida .= "	<div id='cerrara' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Alerta')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenidoa' class='d2Content' style=\"background:#FFFFFF\">\n";
			$this->salida .= "		<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "			<tr class=\"label_error\">\n";
			$this->salida .= "				<td id=\"mensaje\" align=\"center\"></td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Alerta')\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
		* en estado pendiente 
		**************************************************************************************/
		function FormaConsultarNotasCredito()
		{
			$this->ConsultarNotasCredito();
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Creditos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");

			$this->salida .= ThemeAbrirTabla("CONSULTA DE NOTAS DE AJUSTE");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function limpiarCampos(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		objeto.Nota.value = \"\";\n";
			$this->salida .= "		objeto.Numero.value = \"\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function ConfirmarAnularNota(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		valores[1] = datos[1];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ANULAR LA NOTA CREDITO: \"+datos[0]+\" \"+datos[1]+\"\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:AnularNotaCredito(valores)\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xShow(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function OcultarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xHide(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Contenedor';\n";
			$this->salida .= "		titulo = 'titulo';\n";
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
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">JUSTIFICACION</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$this->salida .= "		<br><br><label id=\"error\" class=\"label_error\"></label>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"normal_11N\" id=\"confirmacion\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<div id =\"obligatorios\">\n";
			$this->salida .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			
			$this->salida .= "								<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "									<td class=\"modulo_table_list_title\">MOTIVO ANULACION</td>\n";
			$this->salida .= "									<td>\n";
			$this->salida .= "										<select name=\"motivo\" class=\"select\">\n";
			$this->salida .= "											<option value=\"0\">--SELECCIONAR--</option>\n";
			foreach($this->Motivos as $key => $motivo)
			{
				$desc = $motivo['motivo_descripcion'];
				if(strlen($desc) > 42) $desc = substr($desc,0,42);
				$this->salida .= "											<option value=\"".$key."\" title=\"".$motivo['motivo_descripcion']."\">".$desc."</option>\n";
			}
			$this->salida .= "										</select>\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "									<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "									<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "										<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" ></textarea>\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			
			$this->salida .= "							</table>\n";
			$this->salida .= "						</div>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "						<label id=\"cancelar\"><input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\"></label>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			$this->salida .= "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
			$this->salida .= "	<table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NOTA CREDITO Nº</td>\n";
			$this->salida .= "			<td colspan=\"3\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Nota\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Nota']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">PREFIJO FACTURA</td>\n";
			$this->salida .= "			<td width=\"10%\">\n";
			if(!empty($this->Prefijos))
			{
				$sel = "";
				$this->salida .= "				<select name=\"Prefijo\" class=\"select\">\n";
				foreach($this->Prefijos as $key => $prefijo)
				{
					($prefijo['prefijo_factura'] == $this->rqs['Prefijo'])? $sel = "selected": $sel = "";
					$this->salida .= "					<option value=\"".$prefijo['prefijo_factura']."\" $sel>".$prefijo['prefijo_factura']."</option>\n";
				}
				$this->salida .= "				</select>\n";
			}
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NUMERO FACTURA</td>\n";			
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Numero\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Numero']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr >\n";
			$this->salida .= "			<td class=\"label\" align=\"center\" colspan=\"4\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			
			if(sizeof($this->Notas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset><legend class=\"field\">NOTAS CREDITO CERRADAS:</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\">\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\" >Nº NOTA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\" >FACTURA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"15%\">REGISTRO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"%\">EMPRESA</td>\n";
				$this->salida .= "							<td colspan=\"2\" class=\"modulo_table_list_title\" width=\"20%\" >OPCIONES</td>\n";
				$this->salida .= "						</tr>\n";
				$i=0;
				foreach($this->Notas as $key=> $Nota)
				{
					foreach($Nota as $key=> $Celdas)
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						$i++;
											
						$arreglo = array("prefijo_nota"=>$Celdas['prefijo'],"numero_nota"=>$Celdas['nota_credito_id'],
														 "empresa"=>$this->Empresa);
						
						$reporte = new GetReports();
						$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notascredito',$arreglo,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
						$funcion = "NotaAjuste$i".$reporte->GetJavaFunction();
						$mostrar = str_replace("function W","function NotaAjuste".$i."W",$mostrar);
						
						$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['prefijo']." ".$Celdas['nota_credito_id']."</td>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['prefijo_factura']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "							<td align=\"center\">".$Celdas['fecha']."</td>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['nombre_tercero']."</td>\n";
						$this->salida .= "							<td >\n";
						$this->salida .= " 								<a href=\"javascript:ConfirmarAnularNota(new Array('".$Celdas['prefijo']."','".$Celdas['nota_credito_id']."'))\" class=\"label_error\"  title=\"ANULAR-NOTA\"><img src=\"".GetThemePath()."/images/error_digitacion.png\" border='0'>\n";
						$this->salida .= " 								<b>ANULAR</b></a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "							<td >\n";
						$this->salida .= "								".$mostrar."\n";
						$this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE-NOTA\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
						$this->salida .= " 								<b>REPOR</b></a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO HAY NOTAS CREDITO CREDAS</b></center><br><br>\n";
			}

			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
			$this->salida .= "		<br>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
		* en estado pendiente 
		**************************************************************************************/
		function FormaConsultarNotasDebito()
		{
			$this->ConsultarNotasDebito();
			$this->IncludeJS('RemoteScripting');
			$this->IncludeJS('ScriptRemoto/Debitos.js', $contenedor='app', $modulo='FacturacionNotaCreditoAjuste');
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");

			$this->salida .= ThemeAbrirTabla("CONSULTA DE NOTAS DEBITO");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function limpiarCampos(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		objeto.Nota.value = \"\";\n";
			$this->salida .= "		objeto.Numero.value = \"\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function ConfirmarAnularNota(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores = new Array();\n";
			$this->salida .= "		valores[0] = datos[0];\n";
			$this->salida .= "		valores[1] = datos[1];\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ANULAR LA NOTA DEBITO: \"+datos[0]+\" \"+datos[1]+\"\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:AnularNotaDebito(valores)\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xShow(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function OcultarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xHide(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Contenedor';\n";
			$this->salida .= "		titulo = 'titulo';\n";
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
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">JUSTIFICACION</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$this->salida .= "		<br><br><label id=\"error\" class=\"label_error\"></label>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"normal_11N\" id=\"confirmacion\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<div id =\"obligatorios\">\n";
			$this->salida .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			
			$this->salida .= "								<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "									<td class=\"modulo_table_list_title\">MOTIVO ANULACION</td>\n";
			$this->salida .= "									<td>\n";
			$this->salida .= "										<select name=\"motivo\" class=\"select\">\n";
			$this->salida .= "											<option value=\"0\">--SELECCIONAR--</option>\n";
			foreach($this->Motivos as $key => $motivo)
			{
				$desc = $motivo['motivo_descripcion'];
				if(strlen($desc) > 42) $desc = substr($desc,0,42);
				$this->salida .= "											<option value=\"".$key."\" title=\"".$motivo['motivo_descripcion']."\">".$desc."</option>\n";
			}
			$this->salida .= "										</select>\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "									<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "									<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "										<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" ></textarea>\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			
			$this->salida .= "							</table>\n";
			$this->salida .= "						</div>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "						<label id=\"cancelar\"><input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\"></label>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";			
			$this->salida .= "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
			$this->salida .= "	<table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NOTA DEBITO Nº</td>\n";
			$this->salida .= "			<td colspan=\"3\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Nota\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Nota']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">PREFIJO FACTURA</td>\n";
			$this->salida .= "			<td width=\"10%\">\n";
			if(!empty($this->Prefijos))
			{
				$sel = "";
				$this->salida .= "				<select name=\"Prefijo\" class=\"select\">\n";
				foreach($this->Prefijos as $key => $prefijo)
				{
					($prefijo['prefijo_factura'] == $this->rqs['Prefijo'])? $sel = "selected": $sel = "";
					$this->salida .= "					<option value=\"".$prefijo['prefijo_factura']."\" $sel>".$prefijo['prefijo_factura']."</option>\n";
				}
				$this->salida .= "				</select>\n";
			}
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NUMERO FACTURA</td>\n";			
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Numero\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Numero']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr >\n";
			$this->salida .= "			<td class=\"label\" align=\"center\" colspan=\"4\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			
			if(sizeof($this->Notas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset><legend class=\"field\">NOTAS DEBITO CREADAS:</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\">\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\" >Nº NOTA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\" >FACTURA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"15%\">REGISTRO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"%\">EMPRESA</td>\n";
				$this->salida .= "							<td colspan=\"2\" class=\"modulo_table_list_title\" width=\"20%\" >OPCIONES</td>\n";
				$this->salida .= "						</tr>\n";
				$i=0;
				foreach($this->Notas as $key=> $Nota)
				{
					foreach($Nota as $key2 => $Celdas)
					{
						if($i % 2 == 0)
						{
						  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
											
						$arreglo = array("prefijo_nota"=>$Celdas['prefijo'],"numero_nota"=>$Celdas['nota_debito_id'],
														 "empresa"=>$this->Empresa);
						
						$i++;
						$reporte = new GetReports();
						$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasdebito',$arreglo,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
						$funcion = "NotaAjuste$i".$reporte->GetJavaFunction();
						$mostrar = str_replace("function W","function NotaAjuste".$i."W",$mostrar);
						
						$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['prefijo']." ".$Celdas['nota_debito_id']."</td>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['prefijo_factura']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "							<td align=\"center\">".$Celdas['fecha']."</td>\n";
						$this->salida .= "							<td align=\"left\"  >".$Celdas['nombre_tercero']."</td>\n";
						$this->salida .= "							<td >\n";
						$this->salida .= " 								<a href=\"javascript:ConfirmarAnularNota(new Array('".$Celdas['prefijo']."','".$Celdas['nota_debito_id']."'))\" class=\"label_error\"  title=\"ANULAR-NOTA\"><img src=\"".GetThemePath()."/images/error_digitacion.png\" border='0'>\n";
						$this->salida .= " 								<b>ANULAR</b></a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "							<td >\n";
						$this->salida .= "								".$mostrar."\n";
						$this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE-NOTA\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
						$this->salida .= " 								<b>REPORTE</b></a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO HAY NOTAS CREDITO CREDAS</b></center><br><br>\n";
			}

			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
			$this->salida .= "		<br>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
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
			if ($this->frmError[$campo])
			{
				if ($campo=="MensajeError")
				{
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
		/*********
		* Funcion donde se realiza la forma del b¡uscador 
		* 
		* @return string 
		********/
		function Buscador()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
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
			$buscador .= "			objeto.documento.value = \"\";\n";
			$buscador .= "			objeto.fecha_fin.value = \"\";\n";
			$buscador .= "			objeto.fecha_inicio.value = \"\";\n";
			$buscador .= "			objeto.tipo_id.selectedIndex='0';\n";
			$buscador .= "		}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table width=\"100%\">\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">TIPO DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"tipo_id\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			$TipoId = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TipoId); $i++)
			{
				($this->Doc == $TipoId[$i]['id'])? $sel = "selected": $sel = "";
			
				$buscador .= "						<option value='".$TipoId[$i]['id']."' $sel>".ucwords(strtolower($TipoId[$i]['descripcion']))."</option>\n";
			}
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"documento\" size=\"25\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->TerceroId."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">NOMBRE TERCERO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"25\" value=\"".$this->NombreTercero."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">FECHA</td>\n";
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
			$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"5\">\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador; 
		}
		/*********
		* Funcion donde se realiza la forma del b¡uscador rapido de facturas  
		* 
		* @return string  
		********/
		function BuscadorRapidoFactura()
		{
			$buscador  = "<form name=\"buscadorfacturas\" action=\"".$this->action2."\" method=\"post\">\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
			$buscador .= "				BUSCADOR RAPIDO DE FACTURAS:&nbsp;\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<select name=\"prefijo\" class=\"select\">\n";
			
			$Filas = $this->ObtenerPrefijos();
			for($i=0; $i<sizeof($Filas); $i++)
			{
				($this->Prefijo == $Filas[$i]['prefijo'])? $sel = "selected":$sel="";
				$buscador .= "				<option value='".$Filas[$i]['prefijo']."' $sel>".$Filas[$i]['prefijo']."</option>\n";
			}
			$buscador .= "				</select>\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"factura\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$this->FacturaFiscal."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma del buscador de terceros 
		* 
		* @return string forma del buscador 
		**************************************************************************************/
		function BuscadorTerceros()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
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
			
			$TipoId = $this->ObtenerTipoIdTerceros();
			for($i=0; $i<sizeof($TipoId); $i++)
			{
				($this->Doc == $TipoId[$i]['id'])? $sel = "selected": $sel = "";
			
				$buscador .= "						<option value='".$TipoId[$i]['id']."' $sel>".ucwords(strtolower($TipoId[$i]['descripcion']))."</option>\n";
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
		/*********
		* Funcion donde se realiza la forma del b¡uscador rapido de facturas  
		* 
		* @return string  
		********/
		function BuscadorRapidoFacturaCliente()
		{
			$buscador .= "</script>\n";
			$buscador .= "	function acceptNum(evt)\n";
			$buscador .= "	{\n";
			$buscador .= "		var nav4 = window.Event ? true : false;\n";
			$buscador .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$buscador .= "	}\n";
			$buscador .= "</script>\n";
			$buscador  = "<form name=\"buscadorfacturas\" action=\"".$this->action3."\" method=\"post\">\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr>\n";
			$buscador .= "			<td class=\"modulo_table_list_title\">FACTURA:</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<select name=\"prefijo\" class=\"select\">\n";
			
			$Filas = $this->Prefijos;
			for($i=0; $i<sizeof($Filas); $i++)
			{
				($this->Prefijo == $Filas[$i]['prefijo'])? $sel = "selected":$sel="";
				$buscador .= "				<option value='".$Filas[$i]['prefijo']."' $sel>".$Filas[$i]['prefijo']."</option>\n";
			}
			$buscador .= "				</select>\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"factura_f\" size=\"25\" onkeypress=\"return acceptNum(event)\" value=\"".$this->FacturaFiscal."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
		* en estado pendiente 
		**************************************************************************************/
		function FormaInformacionNotasAjuste()
		{
			$this->ConsultarNotasCreditoAjuste();
			$this->salida .= ThemeAbrirTabla("CONSULTA DE NOTAS DE AJUSTE");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";

			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function limpiarCampos(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		objeto.Nota.value = \"\";\n";
			$this->salida .= "		objeto.Numero.value = \"\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
			$this->salida .= "<form name=\"buscador\" action=\"".$this->action3."\" method=\"post\">\n";
			$this->salida .= "	<table class=\"modulo_table_list\" align=\"center\" width=\"50%\">\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NOTA CREDITO Nº</td>\n";
			$this->salida .= "			<td colspan=\"3\">\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Nota\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Nota']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">PREFIJO FACTURA</td>\n";
			$this->salida .= "			<td width=\"10%\">\n";
			if(!empty($this->Prefijos))
			{
				$sel = "";
				$this->salida .= "				<select name=\"Prefijo\" class=\"select\">\n";
				foreach($this->Prefijos as $key => $prefijo)
				{
					($prefijo['prefijo_factura'] == $this->rqs['Prefijo'])? $sel = "selected": $sel = "";
					$this->salida .= "					<option value=\"".$prefijo['prefijo_factura']."\" $sel>".$prefijo['prefijo_factura']."</option>\n";
				}
				$this->salida .= "				</select>\n";
			}
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">NUMERO FACTURA</td>\n";			
			$this->salida .= "			<td>\n";
			$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Numero\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"".$this->rqs['Numero']."\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr >\n";
			$this->salida .= "			<td class=\"label\" align=\"center\" colspan=\"4\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
					
			$notas = $this->Notas;
			if(sizeof($notas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset><legend class=\"field\">NOTAS DE AJUSTE CREADAS:</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\">\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"9%\" >Nº NOTA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"10%\">REGISTRO</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"15%\">FACTURA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"15%\">TOTAL NOTA</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"%\"  >RESPONSABLE</td>\n";
				$this->salida .= "							<td class=\"modulo_table_list_title\" width=\"18%\"></td>\n";
				$this->salida .= "						</tr>\n";

				for($i = 0; $i<sizeof($notas); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$opcion  = "		<a class=\"label_error\" href=\"".$this->action4[$i]."\" title=\"VER DETALLE RECIBOS DE CAJA\">\n";
					$opcion .= "			<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\"><b>DETAL</b></a>\n";
					
					$Celdas = $notas[$i];
					($Celdas['estado'] == '0')? $estado = "TEMPORAL": $estado = "CERRADA";
					
					$datos['empresa'] = $this->Empresa;
					$datos['numero_nota'] = $Celdas['nota_credito_ajuste'];
					$datos['prefijo_nota'] = $Celdas['prefijo'];
					
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasajuste',$datos,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = "NotaAjuste$i".$reporte->GetJavaFunction();
					$mostrar = str_replace("function W","function NotaAjuste".$i."W",$mostrar);
					
					$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$this->salida .= "							<td align=\"left\"  >".$Celdas['prefijo']." ".$Celdas['nota_credito_ajuste']."</td>\n";
					$this->salida .= "							<td align=\"center\">".$Celdas['fecha_registro']."</td>\n";
					$this->salida .= "							<td >".$Celdas['prefijo_factura']." ".$Celdas['factura_fiscal']."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($Celdas['total_nota_ajuste'])."</td>\n";

					$this->salida .= "							<td align=\"left\"  >".$Celdas['nombre']."</td>\n";
					$this->salida .= "							<td >\n";
					$this->salida .= "								".$mostrar."\n";
					$this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\" ><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$this->salida .= " 								<b>REPORTE</b></a>\n";
					$this->salida .= "							</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";

			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">LA BUSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br><br>\n";
			}

			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
			$this->salida .= "		<br>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		* 
		**************************************************************************************/
		function FormaCrearNotasAjusteExterna()
		{
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->CrearNotasAjusteExterna();
			
			$this->SetXajax(array("BuscarFacturas","CrearNota","EliminarNota","CerrarNota"),"app_modules/FacturacionNotaCreditoAjuste/RemoteXajax/DetalleFacturas.php");
			
			$this->salida .= ThemeAbrirTabla("CREAR NOTAS CREDITO - SISTEMA EXTERNO");
			$this->salida .= "<script>\n";
			$this->salida .= "	function LimpiarCampos()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		frm = document.generarNota;\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'hidden': frm[i].value = ''; break;\n";
			$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		html  = \"<table width='100%'>\";\n";
			$this->salida .= "		html += \" <tr class='normal_10AN'>\";\n";
			$this->salida .= "		html += \" 		<td align='center'>\";\n";
			$this->salida .= "		html += \" 			<a href='javascript:IniciarB();Mostrar()'>ADICIONAR FACTURA</a>\";\n";
			$this->salida .= "		html += \" 		</td>\";\n";
			$this->salida .= "	  html += \" 	</tr>\";\n";
			$this->salida .= "		html += \" </table>\";\n";
			$this->salida .= "		xGetElementById('factura').innerHTML = html;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearNotaCredito(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		if(objeto.prefijo.value == '')\n";
			$this->salida .= "			mensaje = \"<b class='label_error'>SE DEBE ADICIONAR LA FACTURA,<br>QUE SE VERA AFECTADA POR LA NOTA CREDITO</b>\";\n";
			$this->salida .= "		else\n";
			$this->salida .= "			xajax_CrearNota(objeto.prefijo.value,objeto.factura.value,'".$this->Empresa."',objeto.observa.value,objeto.auditor_sel.value);\n";
			$this->salida .= "		xGetElementById('error').innerHTML = mensaje;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearVariables(objeto,i)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_BuscarFacturas(objeto.prefijo.value,objeto.factura.value,'".$this->Empresa."',i)\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Mostrar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		MostrarSpan('FacturasB');\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ConfirmarEliminarNota(tmp_id,factura)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ESTA SEGURO QUE DESEA ELIMINAR LA NOTA DE AJUSTE DE LA FACTURA:\"+factura+\" ?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:EliminarNotaCredito(\"+tmp_id+\")\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function EliminarNotaCredito(tmp_id)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_EliminarNota(tmp_id,'".$this->Empresa."')\n";
			$this->salida .= "		OcultarSpan('Contenedor');\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function AdicionarConceptos(tmp_id,saldo)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.generarNota.action = '".$this->action['conceptos']."'+'&tmp_id='+tmp_id+'&saldo='+saldo;\n";
			$this->salida .= "		document.generarNota.submit();\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function AdicionarFactura(prf,fac)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		html  = \"<table width='100%'>\";\n";
			$this->salida .= "		html += \" <tr class='normal_10AN'>\";\n";
			$this->salida .= "		html += \" 	<td width='35%'>FACTURA ASOCIADA:</td>\";\n";
			$this->salida .= "		html += \" 		<td width='15%'>\"+prf+\" \"+fac+\"</td>\";\n";
			$this->salida .= "		html += \" 		<td>\";\n";
			$this->salida .= "		html += \" 			<a href='javascript:IniciarB();Mostrar()'>CAMBIAR FACTURA</a>\";\n";
			$this->salida .= "		html += \" 		</td>\";\n";
			$this->salida .= "	  html += \" 	</tr>\";\n";
			$this->salida .= "		html += \" </table>\";\n";
			$this->salida .= "		xGetElementById('factura').innerHTML = html;\n";
			$this->salida .= "		xGetElementById('error').innerHTML = '';\n";
			$this->salida .= "		document.generarNota.prefijo.value = prf;\n";
			$this->salida .= "		document.generarNota.factura.value = fac;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function ConfirmarCerrar(tmp_id,factura)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xGetElementById('confirmacion').innerHTML = \"ESTA SEGURO QUE DESEA CERRAR LA NOTA CREDITO, PERTENECIENTE A LA FACTURA: \"+factura+\"?\"\n";
			$this->salida .= "		Iniciar();\n";
			$this->salida .= "		MostrarSpan('Contenedor');\n";
			$this->salida .= "		document.oculta.action = \"javascript:CerrarNotaCredito(\"+tmp_id+\")\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CerrarNotaCredito(tmp_id)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_CerrarNota(tmp_id,'".$this->Empresa."');\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";

			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasajuste',array("empresa"=>$this->Empresa),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();

			SessionSetVar("FuncionImprimir",$funcion);
			
			$this->salida .= "".$mostrar."\n";
			
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "<form name=\"generarNota\" action =\"javascript:CrearNotaCredito(document.generarNota)\" method=\"post\" >\n";
			$this->salida .= "	<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset class=\"fieldset\"><legend>CREAR NOTA CREDITO - SISTEMA EXTERNO</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Observacion."</textarea>\n";
			$this->salida .= "								<input type=\"hidden\" name=\"factura\" value=\"\">\n";
			$this->salida .= "								<input type=\"hidden\" name=\"prefijo\" value=\"\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td >AUDITOR</td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "								<select name=\"auditor_sel\" class=\"select\">\n";
			$this->salida .= "									<option value='0'>-----SELECCIONAR-----</option>\n";
			
			foreach($this->Auditores as $key => $auditor)
				$this->salida .= "									<option value='".$key."' >".ucwords(strtolower($auditor['nombre']))."</option>\n";
			
			$this->salida .= "								</select>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" id=\"factura\">\n";
			$this->salida .= "								<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">ADICIONAR FACTURA</a>\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Crear Nota\">\n";
			$this->salida .= "							</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form><br>\n";
			
			$this->salida .= "<div id=\"notasCredito\">\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= $ndhtml->CrearListadoNotasAjuste($this->Notas);
			$this->salida .= "</div>\n";
			
			$this->salida .= $ndhtml->CrearCapaVentana();
			$this->salida .= $ndhtml->CrearCapaBuscador($this->Prefijos);
			
			$this->salida .= "<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr><td align=\"center\">\n";
			$this->salida .= "		<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "			<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</td></tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function FormaAdicionarConceptosExternos()
		{
			$this->AdicionarConceptosExternos();
			$es = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 8pt\" height=\"19\"";
			
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			$this->SetXajax(array("AdicionarConceptos","EliminarConcepto","ActualizarInformacion"),"app_modules/FacturacionNotaCreditoAjuste/RemoteXajax/DetalleFacturas.php");

			$this->salida .= ThemeAbrirTabla("DETALLE DE LA NOTA CREDITO ");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function EliminarConceptos(arreglo)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xajax_EliminarConcepto(arreglo[1],arreglo[0],arreglo[2],'".$this->empresa."')\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ActualizarInformacion(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(objeto.observa.value != '')\n";
			$this->salida .= "				xajax_ActualizarInformacion('".$this->request['tmp_id']."',objeto.observa.value)\n";
			$this->salida .= "			OcultarSpan('Contenedor');\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Alerta()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try{\n";
			$this->salida .= "				var saldo = ".$this->request['saldo']." ;\n";
			$this->salida .= "				var total = xGetElementById('totalConceptos').value;\n";
			$this->salida .= "				if(total > saldo )\n";
			$this->salida .= "				{\n";
			$this->salida .= "					xGetElementById('mensaje').innerHTML = 'EL VALOR TOTAL DE LA NOTA ($'+ total+') NO DEBE SER MAYOR AL SALDO DE LA FACTURA ($'+saldo+')';\n";
			$this->salida .= "					IniciarA();\n";
			$this->salida .= "					MostrarSpan('Alerta');\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function Cambiar(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			sw_centro = objeto.concepto.value.split(\"~\");\n";
			$this->salida .= "			if(sw_centro[2] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.departamento.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.departamento.selectedIndex = 0;\n";
			$this->salida .= "					objeto.departamento.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			if(sw_centro[3] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.boton_tercero.disabled = false;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					objeto.nombre_tercero.value = \"\";\n";
			$this->salida .= "					objeto.tercero_identifica.value = \"\";\n";
			$this->salida .= "					objeto.boton_tercero.disabled = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function BuscarTercero()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$this->action7."\"\n";
			$this->salida .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
						
			$this->salida .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
			
			if(sizeof($this->conceptos) > 0)
			{
				$this->salida .= "<form name=\"adicionarconcepto\" action=\"javascript:EvalAdicionarConcepto(document.adicionarconcepto)\" method=\"post\">\n";
				$this->salida .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "				<select name=\"concepto\" class=\"select\" onChange=\"Cambiar(document.adicionarconcepto)\">\n";
				$this->salida .= "					<option value='0~0~0~0'>-----SELECCIONAR-----</option>\n";
				foreach($this->conceptos as $key =>$ConceptosAjuste)
					$this->salida .= "					<option value='".$ConceptosAjuste['concepto_id']."~".$ConceptosAjuste['sw_naturaleza']."~".$ConceptosAjuste['sw_centro_costo']."~".$ConceptosAjuste['sw_tercero']."' $sel>".$ConceptosAjuste['descripcion']."</option>\n";

				$this->salida .= "				</select>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" align=\"center\" width=\"20%\">\n";
				$this->salida .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"14\" maxlength=\"15\" style=\"width:95%\" value=\"".$this->ValorConcepto."\" onKeypress=\"return acceptNum(event);\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				
				if(sizeof($this->Deptnos))
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
					$this->salida .= "				<select name=\"departamento\" class=\"select\" disabled>\n";
					$this->salida .= "					<option value='0'>-------SELECCIONAR-------</option>\n";
					foreach($this->Deptnos as $key => $Deptno)
						$this->salida .= "					<option value='".$Deptno['departamento']."' $sel>".$Deptno['descripcion']."</option>\n";
					
					$this->salida .= "				</select>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td align=\"left\" width=\"10%\"><b>TERCERO</b></td>\n";
					$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
					$this->salida .= "				<input type=\"text\" name=\"nombre_tercero\" class=\"input-text\" style=\"width:100%\" value=\"".$this->TerceroNombre."\" readonly>\n";
					$this->salida .= "				<input type=\"hidden\" name=\"tercero_identifica\" value=\"".$this->TerceroIdentificador."\" >\n";					
					$this->salida .= "			</td>\n";
					$this->salida .= "			<td class=\"modulo_list_claro\">\n";
					$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Buscar Tercero\" name=\"boton_tercero\" disabled onclick=\"BuscarTercero()\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";					
				}
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"15%\" colspan=\"3\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
				$this->salida .= "</form>\n";
			}
			
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<a class=\"label_error\" href=\"javascript:Iniciar();MostrarSpan('Contenedor')\" title=\"OBSERVACIÓN NOTA AJUSTE\">\n";
			$this->salida .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>MODIFICAR OBSERVACIÓN</b>";
			$this->salida .= "				</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			$ndhtml = new NotasDebitoHTML();
			$this->salida .= "<div id=\"lista_conceptos\">\n";
			$this->salida .= $ndhtml->CrearListaConceptosExternos($this->cnp);
			$this->salida .= "</div>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Contenedor';\n";
			$this->salida .= "		titulo = 'titulo';\n";			
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
			
			$this->salida .= "	function IniciarA()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'Alerta';\n";
			$this->salida .= "		titulo = 'tituloa';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,330, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrara');\n";
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
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$this->salida .= "		<form name=\"oculta\" action=\"javascript:ActualizarInformacion(document.oculta)\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "						<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$this->Nota[key($this->Nota)]['observacion']."</textarea>\n";
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
			
			$this->salida .= "<div id='Alerta' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='tituloa' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$this->salida .= "	<div id='cerrara' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Alerta')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenidoa' class='d2Content' style=\"background:#FFFFFF\">\n";
			$this->salida .= "		<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "			<tr class=\"label_error\">\n";
			$this->salida .= "				<td id=\"mensaje\" align=\"center\"></td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Alerta')\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	function EvalAdicionarConcepto(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		concep = objeto.concepto.value.split('~');\n"; 
			$this->salida .= "		deptno = objeto.departamento.value; \n";
			$this->salida .= "		tercer = objeto.tercero_identifica.value;\n"; 
			$this->salida .= "		valor = objeto.valor_concepto.value; \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		if(concep[0] == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INDICAR EL CONCEPTO QUE SE VA A ADICIONAR';\n";
			$this->salida .= "		else if(!IsNumeric(valor))\n";
			$this->salida .= "			mensaje = 'EL VALOR DEl CONCEPTO NO ES VALIDO';\n";
			$this->salida .= "			else if(concep[2] == 1 && deptno == 0)\n";
			$this->salida .= "				mensaje = 'SE DEBE SELECCIONAR EL DEPARTAMENTO ASOCIADO AL CONCEPTO';\n";
			$this->salida .= "				else if(concep[3] == 1 && tercer == '')\n";
			$this->salida .= "					mensaje = 'SE DEBE SELECCIONAR EL TERCERO ASOCIADO AL CONCEPTO';\n";
			$this->salida .= "					else\n";
			$this->salida .= "					{\n";
			$this->salida .= "						xajax_AdicionarConceptos(concep[0],deptno,tercer,valor,'".$this->empresa."','".$this->request['tmp_id']."');\n";
			$this->salida .= "					}\n";
			$this->salida .= "		xGetElementById('error').innerHTML = '<b class=\label_error\>'+mensaje+'</b>';\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Finalizar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.adicionarconcepto.tercero_identifica.value = '';\n"; 
			$this->salida .= "		document.adicionarconcepto.nombre_tercero.value = '';\n"; 
			$this->salida .= "		document.adicionarconcepto.boton_tercero.disabled = true;\n"; 
			$this->salida .= "		document.adicionarconcepto.departamento.selectedIndex = 0;\n";
			$this->salida .= "		document.adicionarconcepto.departamento.disabled = true; \n";
			$this->salida .= "		document.adicionarconcepto.valor_concepto.value = '';\n"; 
			$this->salida .= "		document.adicionarconcepto.concepto.selectedIndex = 0; \n";
			$this->salida .= "		Alerta();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
	}
?>
