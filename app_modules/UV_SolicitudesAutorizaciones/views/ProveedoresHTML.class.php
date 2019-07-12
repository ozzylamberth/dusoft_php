<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ProveedoresHTML.class.php,v 1.6 2009/03/31 15:24:21 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ProveedoresHTML
  * Clase encargada de crear las formas para el manejo de las solicitudes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.6 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ProveedoresHTML
  {
    /**
    * Constructor de la clase
    */
    function ProveedoresHTML(){}
    /**
    * Funcion donde se crea la forma para mostrar los proveedores de los cargoa
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $proveedores Arreglo con los datos de los proveedores
    * @param array $cargos Arreglo con los los datos de los cargos seleccionados
    * @param array $equivalencias Arreglo con los los datos de las equivalencias de los cargos seleccionados
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaMostrarProveedoresCargos($action,$tiposdocumentos,$request,$proveedores,$cargos,$equivalencias,$conteo,$pagina)
    {      			
      $i = 0;
      $html  = "<script>\n";
      foreach($cargos as $key => $dtl)
      {
        $i++;
        if(empty($equivalencias[$dtl]))
          $html .= "		window.opener.document.getElementsByName('cargos[".$dtl."][".$key."][cargo]')[0].checked = false;\n";
			}
      $html .= "</script>\n";
      
      $html .= ThemeAbrirTabla('PROVEEDORES DE CARGOS');
      $html .= "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "	<script>\n";
      $html .= "		function limpiarCampos(objeto)\n";
      $html .= "		{\n";
      $html .= "			objeto.nombre_tercero.value = \"\";\n";
      $html .= "			objeto.tercero_id.value = \"\";\n";
      $html .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
      $html .= "		}\n";
      $html .= "	</script>\n";
      $html .= "	<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
      $html .= "		<table>\n";
      $html .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
      $html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
      
      $chk = "";
      foreach($tiposdocumentos as $key => $dtl)
      {
        ($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $chk = "selected": $chk = "";
        $html.= "						<option value='".$dtl['tipo_id_tercero']."' $chk >".$dtl['descripcion']."</option>\n";			
      }
      
      $html .= "					</select>\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";	
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">DOCUMENTO</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">NOMBRE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[nombre]\" size=\"30\" maxlength=\"100\" value=\"".$buscador['nombre']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
      $html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
      $html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "		</table>\n";
      $html .= "	</fieldset>\n";
      $html .= "</form>\n";
      
      if(empty($proveedores))
      {
        $html .= "<center>";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>";
      }
      else
      {
        $pghtml = AutoCarga::factory('ClaseHTML');
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td width=\"%\">PROVEEDOR</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        if($i == 1)
        {
          $html .= "    <td width=\"10%\" >V.CARGO</td>\n";
          $html .= "    <td width=\"10%\" >V.CUBIERTO</td>\n";
        }
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
        
        $est = "modulo_list_claro";
        
        foreach($proveedores as $key => $cargos)
        {
          ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td><b>".$cargos['tipo_id_tercero']." ".$cargos['tercero_id']."</b> ".$cargos['nombre_tercero']."</td>\n";
          $html .= "    <td>".$cargos['direccion']."</td>\n";
          $html .= "    <td>".$cargos['telefono']."</td>\n";
          if($i == 1)
          {
            $html .= "    <td align=\"right\">$".formatoValor($cargos['valor'])."</td>\n";
            $html .= "    <td align=\"right\">$".formatoValor($cargos['valor']*$cargos['porcentaje_cobertura']/100)."</td>\n";
          }
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a title=\"SELECCIONAR PROVEEDOR\" href=\"".$action['proveedor'].URLRequest(array("codigo_proveedor_id"=>$cargos['codigo_proveedor_id']))."\">\n";
          $html .= "        <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
          $html .= "      <a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      $html .= "<form name=\"cerrar\" action=\"\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
      $html .= "		<tr>\n";
      $html .= "			<td align=\"center\">\n";
      $html .= "				<input type=\"button\" name=\"Cerrar\" value=\"Cerrar\" onclick=\"window.close()\" class=\"input-submit\">\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      $html .= "	</table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar los medicamentos del proveedor seleccionado
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $cargos Arreglo con los datos de los cargos y sus equibvalencias
    * @param array $proveedor Arreglo con los datos del proveedor seleccionado
    * @param array $cargos_prov Arreglo con los los datos de los medicamentos seleccionados
    * @param array $equivalencias Arreglo con los los datos de las equivalencias ya seleccionadas
    *
    * @return string
    */
    function FormaMostrarCargos($action,$cargos,$proveedor,$cargos_prov,$equivalencias,$cantidad,$solicitud)
    { 
    	$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor num?ico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "	function AdicionarCargo(cups,cargoequival,solicitud)\n";
			$html .= "  {\n";
			$html .= "    if(!IsNumeric(document.getElementsByName('cargos['+solicitud+']['+cups+']['+cargoequival+'][cantidad]')[0].value))\n";
			$html .= "      alert('PARA EL CARGO '+cargoequival+' NO SE HA INGRESADO LA CANTIDAD')\n";
      $html .= "    else\n"; 
			$html .= "      xajax_AdicionarCargos(xajax.getFormValues('forma_cargo'),cups,cargoequival,'".$proveedor['codigo_proveedor_id']."',solicitud);\n";
			$html .= "  }\n";
      $html .= "	function EliminarCargo(cups,cargoequival,solicitud)\n";
			$html .= "  {\n";
			$html .= "    xajax_EliminarCargos(cups,cargoequival,solicitud);\n";
			$html .= "  }\n";
			$html .= "</script>\n";
      $html .= ThemeAbrirTabla('CARGOS PROVEEDORES');
 			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\" class=\"formulacion_table_list\">PROVEEDOR</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td width=\"25%\">".$proveedor['tercero_id']." ".$proveedor['tipo_id_tercero']."</td>\n";
      $html .= "    <td width=\"%\"  >".$proveedor['nombre_tercero']."</td>\n";
      $html .= "  </tr>\n";
      if($proveedor['direccion'])
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">DIRECCION</td>\n";
        $html .= "    <td> ".$proveedor['direccion']."</td>\n";
        $html .= "  </tr>\n";
      }
      if($proveedor['telefono'])
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">TELEFONO</td>\n";
        $html .= "    <td> ".$proveedor['telefono']."</td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<br>\n";
      
      $est = "modulo_list_claro";
      
      $html .= "<form name=\"forma_cargos\" id=\"forma_cargo\" action=\"".$action['volver']."\" method=\"post\">\n";
      foreach($cargos as $key => $indices)
      {
        $html1  = "  <tr class=\"modulo_table_list_title\">\n";
        $html1 .= "	  <td width=\"10%\">TARIFARIO</td>\n";
        $html1 .= "		<td width=\"10%\">CARGO EQ.</td>\n";
        $html1 .= "		<td >DESCRIPCION</td>\n";
        $html1 .= "		<td width=\"5%\">CANT</td>\n";
        $html1 .= "		<td width=\"10%\">VALOR</td>\n";
        $html1 .= "		<td width=\"10%\">CUBIERTO</td>\n";
        $html1 .= "		<td width=\"1%\"></td>\n";
        $html1 .= "	</tr>\n";
        
        foreach($indices as $key1 => $dtl)
        {
          ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
          $html1 .= "  <tr class=\"".$est."\">\n";
          $html1 .= "    <td>".$dtl['tarifario_id']."</td>\n";
          $html1 .= "    <td>".$dtl['cargo_equivalente']."</td>\n";
          $html1 .= "    <td align=\"justify\">".$dtl['descripcion_equivalente']."</td>\n";
          $html1 .= "		 <td>\n";
          $html1 .= "     ".$cantidad[$key]."";
          $html1 .= "     <input type=\"hidden\" value=\"".$cantidad[$key]."\" name=\"cargos[".$solicitud[$key]."][".$key."][".$dtl['cargo_equivalente']."][cantidad]\">\n";
          $html1 .= "		 </td>\n";
          
          $html1 .= "    <td align=\"right\">\n";
          $html1 .= "     $".formatoValor($dtl['valor'])."\n";
          $html1 .= "     <input type=\"hidden\" name=\"cargos[".$solicitud[$key]."][".$key."][".$dtl['cargo_equivalente']."][valor]\" value=\"".$dtl['valor']."\">\n";
          $html1 .= "     <input type=\"hidden\" name=\"cargos[".$solicitud[$key]."][".$key."][".$dtl['cargo_equivalente']."][porcentaje]\" value=\"".$dtl['porcentaje_cobertura']."\">\n";
          $html1 .= "     <input type=\"hidden\" name=\"cargos[".$solicitud[$key]."][".$key."][".$dtl['cargo_equivalente']."][tarifario]\" value=\"".$dtl['tarifario_id']."\">\n";
          $html1 .= "    </td>\n";
          $html1 .= "    <td align=\"right\">\n";
          $html1 .= "     $".formatoValor($dtl['valor']*$dtl['porcentaje_cobertura']/100)."\n";          
          $html1 .= "    </td>\n";
          
          $chk  = "       <a title=\"SELECCIONAR\" href=\"javascript:AdicionarCargo('".$key."','".$dtl['cargo_equivalente']."','".$solicitud[$key]."')\">\n";
          $chk .= "         <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
          $chk .= "       </a>\n";
          
          if(!empty($cargos_prov[$key]))
          {
            if(!empty($equivalencias[$solicitud[$key]][$key][$dtl['cargo_equivalente']]))
            {
              $chk  = "       <a title=\"SELECCIONAR\" href=\"javascript:EliminarCargo('".$key."','".$dtl['cargo_equivalente']."','".$solicitud[$key]."')\">\n";
              $chk .= "         <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
              $chk .= "       </a>\n";
            }
          }
          else
          {
            if(!empty($equivalencias[$solicitud[$key]][$key][$dtl['cargo_equivalente']]))
            {
              $chk  = "       <a title=\"CAMBIAR DE PROVVEDOR\" href=\"javascript:AdicionarCargo('".$key."','".$dtl['cargo_equivalente']."','".$solicitud[$key]."')\">\n";
              $chk .= "         <img src=\"".GetThemePath()."/images/uf.png\" border=\"0\">\n";
              $chk .= "       </a>\n";
            }
          }
          
          $html1 .= "   <td align=\"center\">\n";
          $html1 .= "     <div id=\"imagen_".$dtl['cargo_equivalente']."\">".$chk."</div>\n";
          $html1 .= "   </td>\n";
          $html1 .= "  </tr>\n";
        }
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"10%\">CARGO:".$key."</td>\n";
        $html .= "    <td width=\"%\" colspan=\"6\">".$dtl['descripcion_cups']."</td>\n";
        $html .= "  </tr>\n";
        $html .= $html1;
        $html .= "</table>\n";
        $html1 = "";
      }
      $html .= "	<div id=\"error\" style=\"text-align:center\" class=\"label_error\"><br></div>\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" name=\"Aceptar\" value=\"Aceptar\" onclick=\"window.close()\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" name=\"Cancelar\" value=\"Lista Proveedores\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
  		$html .= "</form>\n";
      /*
      $html .= "<script>\n";
      foreach($request['cargos']['solicitud'] as $key => $dtl)
      {
          if(empty($equivalencias[$dtl][$key]))
            $this->salida .= "		window.opener.document.getElementsByName('cargos[".$dtl."][".$key."][cargo]')[0].checked = false;\n";
      }
      $html .= "</script>\n";*/
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar los proveedores de los medicamentos
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $proveedores Arreglo con los datos de los proveedores
    * @param array $productos Arreglo con los los datos de los medicamentos seleccionados
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaMostrarProveedoresMedicamentos($action,$tiposdocumentos,$request,$proveedores,$productos,$conteo,$pagina)
    {	
      $i = 0;
      $html  = "<script>\n";
      foreach($productos as $key => $dtl)
      {
        $i++;
        if(empty($equivalencias[$key]))
          $html .= "		window.opener.document.getElementsByName('medicamento[".$key."][producto]')[0].checked = false;\n";
			}
      $html .= "</script>\n";
      
      $html .= ThemeAbrirTabla('PROVEEDORES DE MEDICAMENTOS');
      $html .= "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "	<script>\n";
      $html .= "		function limpiarCampos(objeto)\n";
      $html .= "		{\n";
      $html .= "			objeto.nombre_tercero.value = \"\";\n";
      $html .= "			objeto.tercero_id.value = \"\";\n";
      $html .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
      $html .= "		}\n";
      $html .= "	</script>\n";
      $html .= "	<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
      $html .= "		<table>\n";
      $html .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
      $html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
      
      $chk = "";
      foreach($tiposdocumentos as $key => $dtl)
      {
        ($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $chk = "selected": $chk = "";
        $html.= "						<option value='".$dtl['tipo_id_tercero']."' $chk >".$dtl['descripcion']."</option>\n";			
      }
      
      $html .= "					</select>\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";	
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">DOCUMENTO</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">NOMBRE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[nombre]\" size=\"30\" maxlength=\"100\" value=\"".$buscador['nombre']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
      $html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
      $html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "		</table>\n";
      $html .= "	</fieldset>\n";
      $html .= "</form>\n";
      
      if(empty($proveedores))
      {
        $html .= "<center>";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>";
      }
      else
      {
        $pghtml = AutoCarga::factory('ClaseHTML');
        
   			$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"2\" width=\"60%\">PROVEEDOR</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        if($i == 1)
          $html .= "    <td width=\"10%\">VALOR</td>\n";
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
        
        $est = "modulo_list_claro";
        
        foreach($proveedores as $key => $medicamentos)
        {
          ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td width=\"20%\">".$medicamentos['tipo_id_tercero']." ".$medicamentos['tercero_id']." </td>\n";
          $html .= "    <td>".$medicamentos['nombre_tercero']."</td>\n";
          $html .= "    <td>".$medicamentos['direccion']."</td>\n";
          $html .= "    <td>".$medicamentos['telefono']."</td>\n";
          if($i == 1)
            $html .= "    <td align=\"right\"> $".formatoValor($medicamentos['precio'])."</td>\n";
          
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a title=\"SELECCIONAR PROVEEDOR\" href=\"".$action['proveedor'].URLRequest(array("codigo_proveedor_id"=>$medicamentos['codigo_proveedor_id']))."\">\n";
          $html .= "        <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
          $html .= "      <a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
  			$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
   		$html .= "<form name=\"cerrar\" action=\"\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" name=\"Cerrar\" value=\"Cerrar\" onclick=\"window.close()\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
  		$html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar los medicamentos del proveedor seleccionado
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $productos Arreglo con los datos de los productos seleccionados
    * @param array $proveedor Arreglo con los datos del proveedor seleccionado
    * @param array $med_prov Arreglo con los los datos de los medicamentos seleccionados
    * @param array $seleccionados Arreglo con los los datos de los medicamentos seleccionados ya seleccionados
    *
    * @return string
    */
    function FormaMostrarMedicamentos($action,$productos,$proveedor,$med_prov,$seleccionados)
    { 
    	$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor num?ico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S')\n"; 
			$html .= "			return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "	function AdicionarMedicamento(producto)\n";
			$html .= "  {\n";
			$html .= "    xajax_AdicionarMedicamentos(xajax.getFormValues('forma_cargo'),producto,'".$proveedor['codigo_proveedor_id']."');\n";
			$html .= "  }\n";
      $html .= "	function EliminarMedicamento(producto)\n";
			$html .= "  {\n";
			$html .= "    xajax_EliminarMedicamentos(producto);\n";
			$html .= "  }\n";
			$html .= "</script>\n";
      $html .= ThemeAbrirTabla('MEDICAMENTOS PROVEEDORES');
 			$html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\" class=\"formulacion_table_list\">PROVEEDOR</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td width=\"25%\">".$proveedor['tercero_id']." ".$proveedor['tipo_id_tercero']."</td>\n";
      $html .= "    <td width=\"%\"  >".$proveedor['nombre_tercero']."</td>\n";
      $html .= "  </tr>\n";
      if($proveedor['direccion'])
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">DIRECCION</td>\n";
        $html .= "    <td> ".$proveedor['direccion']."</td>\n";
        $html .= "  </tr>\n";
      }
      if($proveedor['telefono'])
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">TELEFONO</td>\n";
        $html .= "    <td> ".$proveedor['telefono']."</td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<br>\n";
      
      $est = "modulo_list_claro";
      
      $html .= "<form name=\"forma_cargos\" id=\"forma_cargo\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "	  <td width=\"10%\">CODIGO</td>\n";
      $html .= "		<td >DESCRIPCION</td>\n";
      $html .= "		<td width=\"10%\">VALOR</td>\n";
      $html .= "		<td width=\"1%\"></td>\n";
      $html .= "	</tr>\n";
      foreach($productos as $key => $dtl)
      {
        ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td>".$dtl['codigo_producto']."</td>\n";
        $html .= "    <td align=\"justify\">".$dtl['descripcion']."</td>\n";
        $html .= "    <td align=\"right\">\n";
        $html .= "     $".formatoValor($dtl['precio'])."\n";
        $html .= "     <input type=\"hidden\" name=\"medicamento[".$key."][valor]\" value=\"".$dtl['precio']."\">\n";
        $html .= "    </td>\n";
        
        $chk  = "       <a title=\"SELECCIONAR\" href=\"javascript:AdicionarMedicamento('".$key."','".$dtl['cargo_equivalente']."')\">\n";
        $chk .= "         <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
        $chk .= "       </a>\n";
        
        if(!empty($med_prov[$dtl['codigo_producto']]))
        {
          if(!empty($seleccionados[$dtl['codigo_producto']]))
          {
            $chk  = "       <a title=\"SELECCIONAR\" href=\"javascript:EliminarMedicamento('".$key."')\">\n";
            $chk .= "         <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
            $chk .= "       </a>\n";
          }
        }
        else
        {
          if(!empty($seleccionados[$dtl['codigo_producto']]))
          {
            $chk  = "       <a title=\"CAMBIAR DE PROVVEDOR\" href=\"javascript:AdicionarMedicamento('".$dtl['codigo_producto']."')\">\n";
            $chk .= "         <img src=\"".GetThemePath()."/images/uf.png\" border=\"0\">\n";
            $chk .= "       </a>\n";
          }
        }
        
        $html .= "   <td align=\"center\">\n";
        $html .= "     <div id=\"imagen_".$dtl['codigo_producto']."\">".$chk."</div>\n";
        $html .= "   </td>\n";
        $html .= "  </tr>\n";
      }
      
      $html .= "</table>\n";
      $html .= "	<div id=\"error\" style=\"text-align:center\" class=\"label_error\"><br></div>\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" name=\"Aceptar\" value=\"Aceptar\" onclick=\"window.close()\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" name=\"Cancelar\" value=\"Lista Proveedores\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
  		$html .= "</form>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar loa proveedores de los cargoa
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $proveedores Arreglo con los datos de los proveedores
    * @param array $cnp Arreglo con los los datos de los conceptos seleccionados
    * @param array $cp_prv Arreglo con los los datos de los proveedores de los conceptos ya seleccionados
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaMostrarProveedoresConceptos($action,$tiposdocumentos,$request,$proveedores,$cnp,$cp_prv,$conteo,$pagina)
    {
      $html .= ThemeAbrirTabla('PROVEEDORES DE MEDICAMENTOS');
 			$html .= "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "	<script>\n";
      $html .= "		function limpiarCampos(objeto)\n";
      $html .= "		{\n";
      $html .= "			objeto.nombre_tercero.value = \"\";\n";
      $html .= "			objeto.tercero_id.value = \"\";\n";
      $html .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
      $html .= "		}\n";
      $html .= "	</script>\n";
      $html .= "	<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
      $html .= "		<table>\n";
      $html .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
      $html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
      
      $chk = "";
      foreach($tiposdocumentos as $key => $dtl)
      {
        ($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $chk = "selected": $chk = "";
        $html.= "						<option value='".$dtl['tipo_id_tercero']."' $chk >".$dtl['descripcion']."</option>\n";			
      }
      
      $html .= "					</select>\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";	
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">DOCUMENTO</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">NOMBRE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[nombre]\" size=\"30\" maxlength=\"100\" value=\"".$buscador['nombre']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
      $html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
      $html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "		</table>\n";
      $html .= "	</fieldset>\n";
      $html .= "</form>\n";
      
      if(empty($proveedores))
      {
        $html .= "<center>";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>";
      }
      else
      {
        $pghtml = AutoCarga::factory('ClaseHTML');

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"2\" width=\"60%\">PROVEEDOR</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
        
        $est = "modulo_list_claro";
        $scr = "  var prov = new Array();\n";
        foreach($proveedores as $key => $conceptos)
        {
          ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td width=\"20%\">".$conceptos['tipo_id_tercero']." ".$conceptos['tercero_id']." </td>\n";
          $html .= "    <td>".$conceptos['nombre_tercero']."</td>\n";
          $html .= "    <td>".$conceptos['direccion']."</td>\n";
          $html .= "    <td>".$conceptos['telefono']."</td>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <div id=\"img_".$conceptos['codigo_proveedor_id']."\">\n";
          
          if($cp_prv[$conceptos['codigo_proveedor_id']])
          {
            $html .= "        <a title=\"SELECCIONAR PROVEEDOR\" href=\"javascript:EliminarProveedor('".$conceptos['codigo_proveedor_id']."')\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/uf.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          else
          {
            $html .= "        <a title=\"SELECCIONAR PROVEEDOR\" href=\"javascript:AdicionarProveedor('".$conceptos['codigo_proveedor_id']."')\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "      </div>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          
          $flag = true;
          $scr .= " prov[".$conceptos['codigo_proveedor_id']."] = new Array(";
          foreach($cp_prv[$conceptos['codigo_proveedor_id']] as $key1 => $dtl)
          {
            unset($cnp[$key1]);
            ($flag == true)? $scr .= "'".$key1."'":$scr .= ",'".$key1."'";
            $flag = false;
          }
          $scr .= ");\n";
        }
        $html .= "</table>\n";
  			$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
   		$html .= "<form name=\"cerrar\" action=\"\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" name=\"Cerrar\" value=\"Cerrar\" onclick=\"window.close()\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
  		$html .= "</form>\n";
  		$html .= "<script>\n";
      
      $flag;
      $scr .= " var nuevos = new Array(";
      foreach($cnp as $k => $dtl)
      {
        ($flag == true)? $scr .= "'".$k."'":$scr .= ",'".$k."'";
        $flag = false;
      }
      $scr .= ");";
      
  		$html .= "  $scr\n";
  		$html .= "  function AdicionarProveedor(proveedor)\n";
  		$html .= "  {\n";
      $html .= "    if(nuevos.length > 0)\n";
      $html .= "    {\n";
      $html .= "      for(i=0; i<nuevos.length; i++)\n";
      $html .= "      {\n";
      $html .= "        window.opener.document.getElementsByName('conceptos['+nuevos[i]+'][proveedor]')[0].value = proveedor;\n";
      $html .= "      }\n";
      $html .= "      document.getElementById('img_'+proveedor).innerHTML = \"";
      $html .= "        <a title='SELECCIONAR PROVEEDOR' href='javascript:EliminarProveedor(\"+proveedor+\")'>";
      $html .= "        <img src='".GetThemePath()."/images/checksi.png' border='0'></a>\";\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function EliminarProveedor(proveedor)\n";
  		$html .= "  {\n";
  		$html .= "    j= nuevos.length;\n";
      $html .= "    for(i=0; i<prov[proveedor].length; i++)\n";
      $html .= "    {\n";
      $html .= "      nuevos[j++] = prov[proveedor][i];\n";
      $html .= "      window.opener.document.getElementsByName('conceptos['+prov[proveedor][i]+'][proveedor]')[0].value = '';\n";
      $html .= "    }\n";
      $html .= "    document.getElementById('img_'+proveedor).innerHTML = \"";
      $html .= "      <a title='SELECCIONAR PROVEEDOR' href='javascript:AdicionarProveedor(\"+proveedor+\")'>";
      $html .= "      <img src='".GetThemePath()."/images/checkno.png' border='0'></a>\";\n";
      $html .= "  }\n";
  		$html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }
 ?>