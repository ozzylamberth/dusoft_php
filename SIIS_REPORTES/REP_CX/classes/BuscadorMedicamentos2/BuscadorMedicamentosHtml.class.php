<?php
	/**************************************************************************************
	* $Id: BuscadorMedicamentosHtml.class.php,v 1.4 2006/08/16 15:37:02 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	
	class BuscadorMedicamentosHtml
	{		
		function BuscadorMedicamentosHtml()
		{
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ArmarBuscador()
		{	
			$this->buscador = new BuscadorMedicamentos();
			$tema = $_REQUEST['tema'];
			$datos = $_REQUEST['datos'];
			$previa = $_REQUEST['mezcla'];
			$producto = $_REQUEST['producto'];
			$principio_activo = $_REQUEST['principio_activo'];
			
			SessionDelVar("CodigosRecetaSeleccionados"); 
			
			if($previa) $this->buscador->BuscarMedicamentos($previa);
			
			$estilo .= "border-top:	3px solid #CDCDCD;";
			$estilo .= "border-right: 3px solid	#000000;";
			$estilo .= "border-bottom: 3px solid #000000;";
			$estilo .= "border-left: 3px solid #CDCDCD;";
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:10pt;\" ";
			
			$medicamentos = $this->buscador->Medicamentos_Frecuentes_Diagnostico("0");
			$soluciones = $this->buscador->Medicamentos_Frecuentes_Diagnostico("1");
			
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_core.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/VisibilidadMenuHc.js\"></script>\n";
			
			$salida .= ReturnHeader('Buscador de Medicamentos',$scripts);
      $salida .= ReturnBody()."<br>\n";			
			$salida .= "<table width=\"99%\" border=\"0\" align=\"center\" class=\"formulacion_table_list\" >\n";
			$salida .= "	<tr class=\"formulacion_table_list\" >\n";
			$salida .= "		<td height=\"18\" align=\"center\"><b style=\"color:#ffffff\">SELECCION DE MEDICAMENTOS PARA LA SOLUCION</b></td>";
			$salida .= "	</tr>\n";
			$salida .= "	<tr >\n";
			$salida .= "		<td bgcolor=\"#f8f8f8\">\n";
			$salida .= "			<br><div name=\"Error\" id=\"Error\"></div><br>\n";
			$salida .= "			<form name=\"medicamentos\" action=\"\" method=\"post\">\n";
			$salida .= "				<div name=\"Receta\" id=\"Receta\"></div>\n";
			$salida .= "				<div name=\"Opciones\" id=\"Opciones\" style=\"display:none;\">\n";
			$salida .= "					<table width=\"90%\" align=\"center\">\n";
			$salida .= "						<tr>\n";
			$salida .= "							<td align=\"center\" class=\"label_error\" height=\"40\" >\n";
			$salida .= "								<a href=\"javascript:window.close();\">\n";
	    $salida .= "									<img src=\"".GetThemePath()."/images/error_digitacion.png\" border=\"0\" >CANCELAR\n";
	    $salida .= "								</a>\n";
			$salida .= "							</td>\n";/*
			$salida .= "							<td colspan =\"2\" align=\"center\" class=\"label_error\">\n";
			$salida .= "								<a href=\"javascript:MostrarSpan('GuardarMezcla');OcultarSpan('RecetarSolucion');\">\n";
	    $salida .= "									<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\" >GUARDAR SOLUCION\n";
	    $salida .= "								</a>\n";
			$salida .= "							</td>\n";*/
			$salida .= "							<td align=\"center\"class=\"label_error\">\n";
			$salida .= "								<a href=\"javascript:MostrarSpan('RecetarSolucion');OcultarSpan('GuardarMezcla');\">\n";
	    $salida .= "									<img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\" >FORMULAR\n";
	    $salida .= "								</a>\n";
			$salida .= "							</td>\n";
			$salida .= "						</tr>\n";
			$salida .= "					</table>\n";
			$salida .= "				</div>\n";
			$salida .= "				<div name=\"GuardarMezcla\" id=\"GuardarMezcla\" style=\"display:none;\">\n";
			$salida .= "				<table width=\"98%\" align=\"center\">\n";
			$salida .= "					<tr>\n";
			$salida .= "						<td width=\"100%\">\n";
			$salida .= "							<fieldset><legend class=\"field\">Guardar Solucion</legend>\n";
			$salida .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$salida .= "									<tr class=\"modulo_list_claro\">\n";
			$salida .= "										<td width=\"20%\" style=\"text-indent:6pt;text-align:left\" class=\"formulacion_table_list\">\n";
			$salida .= "											<b style=\"font-size:10px\">NOMBRE SOLUCION</b>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td align=\"left\" colspan=\"2\">\n";
			$salida .= "											<input type='text' class='input-text' size='52' name = 'nombre_mezcla' value =\"".$producto."\">\n";
			$salida .= "										</td>\n";
			$salida .= "									</tr>\n";
			$salida .= "									<tr class=\"modulo_list_claro\">\n";
			$salida .= "										<td width=\"20%\" style=\"text-indent:6pt;text-align:left\" class=\"formulacion_table_list\">\n";
			$salida .= "											<b style=\"font-size:10px\">GRUPO SOLUCION</b>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td align=\"left\">\n";
			$salida .= "											<select name=\"grupo_mezcla\" class=\"select\">\n";
			$salida .= "												<option value=\"0\">----SELECCIONAR-----</option>";
			
			$mezclas = $this->buscador->GruposMezclas();
			for($i=0; $i<sizeof($mezclas); $i++)
			{
				$salida .= "												<option value=\"".$mezclas[$i]['grupo_mezcla_id']."\">".$mezclas[$i]['descripcion']."</option>";
			}
			$salida .= "											</select>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td width=\"20%\" align=\"center\" class=\"modulo_list_claro\">\n";
			$salida .= "											<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"EvaluarResultados(1,'".GetThemePath()."');\" value=\"Aceptar\">\n";
			$salida .= "										</td>\n";
			$salida .= "									</tr>\n";
			$salida .= "								</table>\n";
			$salida .= "							</fieldset>\n";
			$salida .= "						</td>\n";
			$salida .= "					</tr>\n";
			$salida .= "				</table>\n";
			$salida .= "			</div>\n";
			$salida .= "			<div name=\"RecetarSolucion\" id=\"RecetarSolucion\" style=\"display:none;\">\n";
			$salida .= "				<table width=\"98%\" align=\"center\">\n";
			$salida .= "					<tr>\n";
			$salida .= "						<td width=\"100%\">\n";
			$salida .= "							<fieldset><legend class=\"field\">Formular Solucion</legend>\n";
			$salida .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$salida .= "									<tr class=\"modulo_list_claro\">\n";
			$salida .= "										<td width=\"20%\" style=\"text-indent:6pt;text-align:left\" class=\"formulacion_table_list\" >\n";
			$salida .= "											<b style=\"font-size:10px\">CANTIDAD TOTAL</b>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td align=\"left\" width=\"80%\" colspan=\"2\">\n";
			$salida .= "											<input type='text' class='input-text' size='20' name = 'cantidadTotal' onkeypress=\"return acceptNum(event);\"> <b>Unidad(es)</b>\n";
			$salida .= "										</td>\n";
			$salida .= "									</tr>\n";
			$salida .= "									<tr class=\"modulo_list_claro\">\n";
			$salida .= "										<td width=\"20%\" style=\"text-indent:6pt;text-align:left\" class=\"formulacion_table_list\">\n";
			$salida .= "											<b style=\"font-size:10px\">VOLUMEN DE INFUSIÓN</b>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td align=\"left\" colspan=\"2\">\n";
			$salida .= "											<input type='text' class='input-text' size='20' name='volumeninput' onkeypress=\"return acceptNum(event);\">\n";
			$salida .= "											<select name=\"volumenselect\" class=\"select\">\n";
			$salida .= "												<option value=\"0\">-SELECCIONAR-</option>";
			
			$mezclas = $this->buscador->UnidadesSolucion();
			for($i=0; $i<sizeof($mezclas); $i++)
			{
				$salida .= "													<option value=\"".$mezclas[$i]['unidad_volumen']."\">".$mezclas[$i]['unidad_volumen']."</option>";
			}
			$salida .= "											</select>\n";
			$salida .= "										</td>";
			$salida .= "									</tr>\n";
			$salida .= "									<tr class=\"formulacion_table_list\" >\n";
			$salida .= "										<td align=\"center\" colspan=\"3\">OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
			$salida .= "									</tr>\n";
			$salida .= "									<tr class=\"formulacion_table_list\" >\n";
			$salida .= "										<td align=\"center\" colspan=\"2\" width=\"80%\">\n";
			$salida .= "											<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"observacion\" name=\"observacion\">".$this->datos['observacion']."</textarea>\n";
			$salida .= "										</td>\n";
			$salida .= "										<td align=\"center\" class=\"modulo_list_claro\" width=\"20%\">\n";
			$salida .= "											<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"Recetar('".$tema."');\" value=\"Formular Solucion\">\n";
			$salida .= "										</td>\n";
			$salida .= "									</tr>\n";
			$salida .= "								</table>\n";
			$salida .= "							</fieldset>\n";
			$salida .= "						</td>\n";
			$salida .= "					</tr>\n";
			$salida .= "				</table>\n";
			$salida .= "			</div>\n";
			$salida .= "		</form>\n"; 
			$salida .= "		<div name=\"Solicitud\" id=\"Solicitud\"></div><br>\n";
			$salida .= "			<form name=\"buscador\" action=\"\" method=\"post\">\n";
			$salida .= "				 <table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$salida .= "					<tr class=\"modulo_table_title\">\n";
			$salida .= "  					<td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>\n";
			$salida .= "					</tr>\n";
			$salida .= "					<tr class=\"hc_table_submodulo_list_title\">\n";
			if(sizeof($soluciones) > 0)
			{
				$salida .= "						<td width=\"%\" align = left >\n";
	      $salida .= "							<a href=\"javascript:OcultarSpan('Frecuentes');MostrarSpan('Soluciones');\"  title=\"Grupo de Soluciones\">\n";
	      $salida .= "								<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/pparamed.png\" border=\"0\" >SOLUCIONES\n";
	      $salida .= "							</a>\n";
				$salida .= "						</td>\n";
			}
			if(sizeof($medicamentos) > 0)
			{
				$salida .= "						<td width=\"%\" align = left >\n";
	      $salida .= "							<a href=\"javascript:OcultarSpan('Soluciones');MostrarSpan('Frecuentes');\"  title=\"Grupo de Medicamentos\">\n";
	      $salida .= "								<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/pparamed.png\" border=\"0\" >MEDICAMENTOS\n";
	      $salida .= "							</a>\n";
				$salida .= "						</td>\n";
			}
			
			$salida .= "							<td width=\"%\">PRODUCTO:</td>\n";
			$salida .= "							<td width=\"%\" align='center'>\n";
			$salida .= "								<input type='text' class='input-text' size = 22 name = 'producto' value =\"".$producto."\">\n";
			$salida .= "							</td>\n";
			$salida .= "							<td width=\"%\">PRINCIPIO ACTIVO:</td>";
			$salida .= "							<td width=\"%\" align='center' >\n";
			$salida .= "								<input type='text' class='input-text' size = 22 name = 'principio_activo' value =\"".$principio_activo."\" >\n";
			$salida .= "							</td>\n" ;
			$salida .= "							<td width=\"%\" align=\"center\">\n";
			$salida .= "								<input class=\"input-submit\" name=\"buscar\" type=\"button\" onclick=\"EnviarDatos(document.buscador)\" value=\"BUSCAR\">\n";
			$salida .= "							</td>\n";
			$salida .= "						</tr>\n";
			$salida .= "						<tr class=\"modulo_table_title\">\n";
			$salida .= "							<td style=\"text-indent:0pt\">\n";
			
			$datos = array();
			if(SessionIsSetVar("MedicamentosRecetaSeleccionados"))
				$datos = SessionGetVar("MedicamentosRecetaSeleccionados");
			
			$capas = "var secc1 = new Array(";//Arreglo javascript
			$flagPrimerCapa = true;
			
			if(sizeof($soluciones) > 0)
			{
				$salida .= "						<div name=\"Soluciones\" id=\"Soluciones\" class=\"MenuMedicamentos\" style=\"display:none;position:absolute\">\n";
				foreach($soluciones as $key => $subnivel0)
				{
					$salida .= "					<p class=\"GrupoMedicamentos\" onclick=\"OcultarCapas('$key');\">\n";
					$salida .= "    				<a href=\"#\">$key</a>\n";
					$salida .= "						<div name=\"$key\" id=\"$key\" style=\"display:none;width:280px;\">\n";
					$salida .= "							<ul class=\"Lista1\">\n";
					
					foreach($subnivel0 as $key2 => $subnivel11)
					{							
						$datos[$subnivel11['codigo_producto']] = $subnivel11;
						
						$salida .= "							<li class=\"Medicamentos\">\n";
						$salida .= "								<a class=\"SubMenuHC\" href=\"javascript:OcultarCapas(''); OcultarSpan('Soluciones');crearRecetaLiq('".$subnivel11['codigo_producto']."','0','".GetThemePath()."')\"\">".$subnivel11['producto']."</a>\n";
						$salida .= "							</li>\n";
					}
					$salida .= "							</ul>\n";
					$salida .= "						</div>\n";
					$salida .= "    			</p>\n";
					
					$flagPrimerCapa ?  $capas .= "\"$key\"" : $capas .= ",\"$key\"";
					$flagPrimerCapa = false;
				}
				$salida .= "								</div>\n";
			}
			
			$salida .= "							</td>\n";
			$salida .= "							<td style=\"text-indent:0pt\" colspan=\"6\">\n";
			
			if(sizeof($medicamentos) > 0)
			{
				$salida .= "						<div name=\"Frecuentes\" id=\"Frecuentes\" class=\"MenuMedicamentos\" style=\"display:none;position:absolute\">\n";
				foreach($medicamentos as $key => $subnivel)
				{
					$salida .= "					<p class=\"GrupoMedicamentos\" onclick=\"OcultarCapas('$key');\">\n";
					$salida .= "    				<a href=\"#\">$key</a>\n";
					$salida .= "						<div name=\"$key\" id=\"$key\" style=\"display:none;width:280px;\">\n";
					$salida .= "							<ul class=\"Lista1\">\n";
					
					foreach($subnivel as $key2 => $subnivel1)
					{							
						$datos[$subnivel1['codigo_producto']] = $subnivel1;
						
						$salida .= "							<li class=\"Medicamentos\">\n";
						$salida .= "								<a class=\"SubMenuM\" href=\"javascript:OcultarCapas(''); OcultarSpan('Frecuentes');crearReceta('".$subnivel1['codigo_producto']."','0','".GetThemePath()."')\"\">".$subnivel1['producto']."</a>\n";
						$salida .= "							</li>\n";
					}
					$salida .= "							</ul>\n";
					$salida .= "						</div>\n";
					$salida .= "    			</p>\n";
					
					$flagPrimerCapa ?  $capas .= "\"$key\"" : $capas .= ",\"$key\"";
					$flagPrimerCapa = false;
				}
				$salida .= "								</div>\n";
			}
			
			SessionSetVar("MedicamentosRecetaSeleccionados",$datos);
			
			$salida .= "							</td>\n";
			$salida .= "						</tr>\n";
			$salida .= "					</table>\n";
			$salida .= "				</form><br>\n";
			$salida .= "			<div name=\"Busqueda\" id=\"Busqueda\"></div><br>\n";
			$salida .= "		</td>\n";
			$salida .= "	</tr>\n";
			$salida .= "</table>\n";
			
			$salida .= "	<script language=\"javascript\">\n";
			$salida .= "		".$capas.");\n";
			$salida .= "		function OcultarCapas(Seccion)\n";
			$salida .= "		{ \n";
			$salida .= "			for(i=0; i<secc1.length; i++)\n";
			$salida .= "			{\n";
			$salida .= "				if(secc1[i] != Seccion)\n";
			$salida .= "				{\n";
			$salida .= "					e = xGetElementById(secc1[i]);\n";
			$salida .= "					e.style.display = \"none\";\n";
			$salida .= "				}\n";
			$salida .= "				else\n";
			$salida .= "				{\n";
			$salida .= "					if(Seccion != \"\") MostrarSpan(Seccion);\n";
			$salida .= "				}\n";
			$salida .= "			}\n";
			$salida .= "		}\n";
			$salida .= "		function OcultarSpan(Seccion)\n";
			$salida .= "		{ \n";
			$salida .= "			try\n";
			$salida .= "			{\n";
			$salida .= "				e = xGetElementById(Seccion);\n";
			$salida .= "				e.style.display = \"none\";\n";
			$salida .= "			}\n";
			$salida .= "			catch(error){}\n";
			$salida .= "		}\n";
			$salida .= "		function MostrarSpan2(Seccion)\n";
			$salida .= "		{ \n";
			$salida .= "			try\n";
			$salida .= "			{\n";
			$salida .= "				e = xGetElementById(Seccion);\n";
			$salida .= "				e.style.display = \"\";\n";
			$salida .= "			}\n";
			$salida .= "			catch(error){}\n";
			$salida .= "		}\n";
			$salida .= "		function MostrarSpan(Seccion)\n";
			$salida .= "		{ \n";
			$salida .= "			e = xGetElementById(Seccion);\n";
			$salida .= "			if(e.style.display == \"none\")\n";
			$salida .= "			{\n";
			$salida .= "				e.style.display = \"\";\n";
			$salida .= "			}\n";
			$salida .= "			else \n";
			$salida .= "			{\n";
			$salida .= "				e.style.display = \"none\";\n";
			$salida .= "			}\n";
			$salida .= "		}\n";
			$salida .= "		function EnviarDatos(objeto)\n";
			$salida .= "		{ \n";
			$salida .= "			prod = objeto.producto.value;\n";
			$salida .= "			ppac = objeto.principio_activo.value;\n";
			$salida .= "			bode = '".$datos['bodega']."';\n";
			$salida .= "			crearBusqueda(prod,ppac,bode,1,'".GetThemePath()."');\n";
			$salida .= "		}\n";
			$salida .= "		function mOvr(src,clrOver)\n";
			$salida .= "		{\n";
			$salida .= "			src.style.background = clrOver;\n";
			$salida .= "		}\n";
			$salida .= "		function mOut(src,clrIn)\n";
			$salida .= "		{\n";
			$salida .= "			src.style.background = clrIn;\n";
			$salida .= "		}\n";
			$salida .= "		function acceptNum(evt)\n";
			$salida .= "		{\n";
			$salida .= "			var nav4 = window.Event ? true : false;\n";
			$salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$salida .= "		}\n";
			$salida .= "	</script>\n";
			if($previa)
			{
				$salida .= "	<script>\n";
				$salida .= "		jsrsExecute(\"ScriptsRemotos/buscadorHtml.php\",pintarReceta,\"CrearRecetaPrevia\",'".GetThemePath()."');\n";
				$salida .= "	</script>\n";
			}
			return $salida;
		}
	}
	
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	
	IncludeClass("ClaseHTML");
	IncludeClass("BuscadorMedicamentos");	
	
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$buscador = new BuscadorMedicamentosHtml();
	echo $buscador->ArmarBuscador();
?>