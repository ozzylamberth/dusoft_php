<?php
	class BuscadorLocalizacion
	{
		var $request = array();
		function BuscadorLocalizacion()
		{
			$this->request = $_REQUEST;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function FormaSeleccionarLocalizacion()
		{
			$chk = "";
			$bls = new BuscadorLocalizacionSql();
			
			$file = '../../classes/BuscadorLocalizacion/RemoteXajax/localizacion.php';
			
			list($xajax) = getXajax();
			//$xajax->setFlag("debug", true);
      
			$xajax->registerFunction("CambiarPais",$file);
			$xajax->registerFunction("CambiarDepartamento",$file);
      		$xajax->registerFunction("CambiarCiudad",$file);
			$xajax->registerFunction("CrearNuevoMunicipio",$file);
			$xajax->registerFunction("CrearNuevaComuna", $file);
			$xajax->registerFunction("IngresarNuevoDepartamento",$file);
			$xajax->registerFunction("IngresarNuevoMunicipio",$file);
			$xajax->processRequest();
			
			$html .= ReturnHeader('Buscador');
      $html .= ReturnBody()."<br>\n";
			$html .= $xajax->printJavascript('../../classes/xajax/'); 	
			$html .= ThemeAbrirTabla("LOCALIZACI�");			
			$html .= "<script>\n";			
			$html .= "	function ContinuarSeleccionPais()\n";			
			$html .= "	{\n";			
			$html .= "		txt_dept = '';\n";			
			$html .= "		txt_mpio = '';\n";
			$html .= "		txt_cmna = '';\n";					
			$html .= "		cod_dept = '';\n";			
			$html .= "		cod_mpio = '';\n";
			$html .= "		cod_cmna = '';\n";			
			$html .= "		obj_pais = document.forma.pais;\n";			
			$html .= "		obj_dept = document.forma.dept;\n";			
			$html .= "		obj_mpio = document.forma.mpio;\n";
			//$html .= "		obj_cmna = document.forma.cmna;\n";			
			
			$html .= "		txt_pais = obj_pais[obj_pais.selectedIndex].text;\n";			
			$html .= "		cod_pais = obj_pais.value;\n";			
			
			$html .= "		if(obj_dept.type == 'text')\n";		
			$html .= "		{\n";			
			$html .= "			cod_dept = document.forma.departamento.value;\n";			
			$html .= "			txt_dept = obj_dept.value;\n";			
			$html .= "		}\n";			
			$html .= "		else\n";			
			$html .= "		{\n";			
			$html .= "			cod_dept = obj_dept.value;\n";			
			$html .= "			txt_dept = obj_dept[obj_dept.selectedIndex].text;\n";			
			$html .= "		}\n";
			
			
			$html .= "		if(obj_mpio.type == 'text')\n";		
			$html .= "		{\n";			
			$html .= "			cod_mpio = document.forma.municipio.value;\n";			
			$html .= "			txt_mpio = obj_mpio.value;\n";			
			$html .= "		}\n";			
			$html .= "		else\n";			
			$html .= "		{\n";			
			$html .= "			cod_mpio = obj_mpio.value;\n";			
			$html .= "			txt_mpio = obj_mpio[obj_mpio.selectedIndex].text;\n";			
			$html .= "		}\n";
			
			/*$html .= "		if(obj_cmna.type == 'text')\n";		
			$html .= "		{\n";			
			$html .= "			cod_cmna = document.forma.comuna.value;\n";			
			$html .= "			txt_cmna = obj_cmna.value;\n";			
			$html .= "		}\n";			
			$html .= "		else\n";			
			$html .= "		{\n";			
			$html .= "			cod_cmna = obj_cmna.value;\n";			
			$html .= "			txt_cmna = obj_cmna[obj_cmna.selectedIndex].text;\n";			
			$html .= "		}\n";	*/		

				
			if(empty($this->request['nombre_campos']))
			{
				$html .= "		window.opener.document.getElementById('ubicacion').innerHTML = txt_pais + ' - ' + txt_dept + ' - '+ txt_mpio;\n";
        //$html .= "    	window.opener.document.getElementById('tipo_comuna').innerHTML = txt_cmna;\n";      
				$html .= "		window.opener.document.".$this->request['forma'].".pais.value = cod_pais;\n";
				$html .= "		window.opener.document.".$this->request['forma'].".dpto.value = cod_dept;\n";
				$html .= "		window.opener.document.".$this->request['forma'].".mpio.value = cod_mpio;\n";
				//$html .= "		window.opener.document.".$this->request['forma'].".comuna.value = cod_cmna;\n";
			}
			else
			{
				$html .= "		window.opener.document.getElementById('".$this->request['nombre_campos']['ubicacion']."').innerHTML = txt_pais + ' - ' + txt_dept + ' - '+ txt_mpio;\n";
           		
				//$html .= "    	window.opener.document.getElementById('tipo_comunaM3').innerHTML = txt_cmna;\n";      
				$html .= "		window.opener.document.".$this->request['forma'].".paisM3.value = cod_pais;\n";
				$html .= "		window.opener.document.".$this->request['forma'].".dptoM3.value = cod_dept;\n";
				$html .= "		window.opener.document.".$this->request['forma'].".mpioM3.value = cod_mpio;\n";
				//$html .= "		window.opener.document.".$this->request['forma'].".comunaM3.value = cod_cmna;\n";
			}
			
			$html .= "		window.close();\n";
			$html .= "	}\n";			
			$html .= "	function EvaluarValores(objeto)\n";			
			$html .= "	{\n";			
			$html .= "		mensaje = '';\n";			
			$html .= "		obj_pais = objeto.pais;\n";			
			$html .= "		obj_dept = objeto.dept;\n";			
			$html .= "		obj_mpio = objeto.mpio;\n";
			//$html .= "		obj_cmna = objeto.cmna;\n";
						
			$html .= "		if(obj_pais.value == '-1')\n";			
			$html .= "			mensaje = 'NO SE HA SELECCIONADO EL PAIS';\n";			
			$html .= "			else if(obj_dept.value == '-1' || obj_dept.value == '')\n";			
			$html .= "				mensaje = 'NO SE HA SELECCIONADO O INGRESADO EL DEPARTAMENTO';\n";			
			$html .= "				else if(obj_mpio.value == '-1' || obj_mpio.value == '')\n";			
			$html .= "					mensaje = 'NO SE HA SELECCIONADO O INGRESADO LA CUIDAD';\n";			
			$html .= "		document.getElementById('error').innerHTML = mensaje;\n";			
			$html .= "		if(mensaje == '')\n";			
			$html .= "		{\n";			
			$html .= "			if(obj_dept.type == 'text')\n";			
			$html .= "				xajax_IngresarNuevoDepartamento(obj_pais.value,objeto.dept.value,obj_mpio.value);\n";			
			$html .= "			else if(obj_mpio.type == 'text')\n";			
			$html .= "				xajax_IngresarNuevoMunicipio(obj_pais.value,objeto.dept.value,obj_mpio.value);\n";			
			$html .= "				else\n";			
			$html .= "					ContinuarSeleccionPais();\n";
			$html .= "		}\n";			
			$html .= "	}\n";			
			$html .= "</script>\n";			
			$html .= "<form name=\"forma\" method=\"post\" action=\"javascript:EvaluarValores(document.forma)\">\n";
			$html .= "	<center><div heigth=\"22\" width=\"90%\" id=\"error\" class=\"label_error\">&nbsp;</div></center>\n";
			$html .= "  <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
      $html .= "	<input type=\"hidden\" name=\"municipio\" value=\"\">\n";
			$html .= "	<input type=\"hidden\" name=\"departamento\" value=\"\">\n";
      			
      $html .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:4pt\" width=\"30%\">PA�:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"pais\"  onChange=\"xajax_CambiarPais(this.value)\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">--SELECCIONAR--</option>\n";
			
			$paises = $bls->ObtenerPaises();
			$consLocal = $bls->ObtenerLocalizacion($this->request['pais']);
      foreach($paises as $key => $pais)
			{
				($pais['tipo_pais_id'] == $this->request['pais'])? $chk = "selected": $chk = "";
				$html .= "					<option value= \"".$pais['tipo_pais_id']."\" $chk>".$pais['pais']."</option>\n";
			}
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:4pt\">\n";
      $html .= "        <div id=\"nomDept\">".$consLocal['equiv_departamento'].":</div>\n";
      $html .= "      </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<div id=\"pais_deptno\">\n";
			$html .= "					<select name=\"dept\" onChange=\"xajax_CambiarDepartamento(this.value,document.forma.pais.value)\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">--SELECCIONAR--</option>\n";

			$depart = $bls->ObtenerDepartamentos($this->request['pais']);  
      
      
			foreach($depart as $key => $deptno)
			{
				($deptno['tipo_dpto_id'] == $this->request['dept'])? $chk = "selected": $chk = "";
				$html .= "						<option value= \"".$deptno['tipo_dpto_id']."\" $chk>".$deptno['departamento']."</option>\n";
			}
			$html .= "					</select>\n";
			$html .= "				</div>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\"  height=\"21\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:4pt\">\n";
      $html .= "        <div id=\"nomCiud\">".$consLocal['equiv_municipio'].":</div>\n";
      $html .= "      </td>\n";      
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<div id=\"deptno_ciudad\">\n";
			//$html .= "					<select name=\"mpio\"  class=\"select\" onChange=\"if(this.value != '-1') xajax_CambiarCiudad(this.value, document.forma.dept.value, document.forma.pais.value)\">\n";
			$html .= "					<select name=\"mpio\"  class=\"select\" >\n";
			$html .= "						<option value=\"-1\">--SELECCIONAR--</option>\n";

			$ciudades = $bls->ObtenerCiudades($this->request['pais'],$this->request['dept']);
			foreach($ciudades as $key => $ciudad)
			{
				($ciudad['tipo_mpio_id'] == $this->request['mpio'])? $chk = "selected": $chk = "";
				$html .= "						<option value= \"".$ciudad['tipo_mpio_id']."\" $chk>".$ciudad['municipio']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</div>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      /*
			$html .= "		<tr class=\"modulo_table_list_title\"  height=\"21\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:4pt\">\n";
      $html .= "        <div id=\"nomCmna\">".$consLocal['equiv_comuna'].":</div>\n";
      $html .= "      </td>\n";      
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<div id=\"ciudad_comuna\">\n";
			$html .= "					<select name=\"cmna\"  class=\"select\" >\n";
			$html .= "						<option value=\"-1\">--SELECCIONAR--</option>\n";			
			
			
			$comunas = $bls->ObtenerComunas($this->request['pais'], $this->request['dept'], $this->request['mpio']);
			foreach($comunas as $key => $comuna)
			{
				($comuna['tipo_comuna_id'] == $this->request['cmna'])? $chk = "selected": $chk = "";
				$html .= "						<option value= \"".$comuna['tipo_comuna_id']."\" $chk>".$comuna['comuna']."</option>\n";
			}
						
			$html .= "					</select>\n";
			$html .= "				</div>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      */
			$html .= "	</table>\n";
			$html .= "	<table width=\"80%\" align=\"center\">\n";
			$html .= "		<tr align=\"center\">\n";
			$html .= "			<td>\n";
			$html .= "			  <input type=\"submit\" name=\"aceptar\" value=\"Aceptar\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"button\" name=\"cerrar\" value=\"Cerrar\" class=\"input-submit\" onclick=\"window.close()\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
			$html .= "</body>\n";
			$html .= "</html>\n";
			return $html;
		}
	}
	
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	include $_ROOT.'classes/BuscadorLocalizacion/BuscadorLocalizacionSql.class.php';

	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$bsc = new BuscadorLocalizacion();
	echo $bsc->FormaSeleccionarLocalizacion();
?>