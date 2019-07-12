<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: AfiliacionesNovedadesHTML.class.php,v 1.2 2008/01/08 13:31:24 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: AfiliacionesNovedadesHTML
  * Clase encargada de crear las formas para el ingreso de novedades
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class AfiliacionesNovedadesHTML
  {
    /**
    * Constructor de la clase
    */
    function AfiliacionesNovedadesHTML(){}
    /**
		* Crea la forma para buscar los afiliados
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $tipos_afiliado_id Vector de ripos de afiliado_ids
		*
		* @return String
		*/
		function FormaBuscarAfiliado($action,$tipos_afiliado_id)
		{
			$html  = ThemeAbrirTabla('BUSCAR AFILIADO');
			$html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.afiliado_tipo_id.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE afiliado_id\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.afiliado_id.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL afiliado_id\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_BuscarAfiliado(xajax.getFormValues('buscar_afiliado'));\n";
			$html .= "	}\n";
			$html .= "	function continuar()\n";
			$html .= "	{\n";
			$html .= "		document.buscar_afiliado.action = \"".$action['aceptar']."\"; \n";
			$html .= "		document.buscar_afiliado.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"buscar_afiliado\" id=\"buscar_afiliado\" action=\"javascript:ValidarDatos(document.buscar_afiliado)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">BUSCAR AFILIADO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"40%\" style=\"text-align:left;text-indent:8pt\">TIPO IDENTIFICACION AFILIADO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"afiliado_tipo_id\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipos_afiliado_id as $key => $datos)
				$html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >NUMERO DE DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"afiliado_id\" value=\"\" style=\"width:50%\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "		</tr>";
			$html .= "	</table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
		* Crea un menu para ls novedades que pueden ser cambiadas manualmente
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaMenuInicial($action)
		{
			$html  = ThemeAbrirTabla('NOVEDADES');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
      $html .= "				<tr>\n";
      $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "						<a href=\"".$action['novedad'].URLRequest(array("novedad"=>"N01"))."\"><b>MODIFICAR IDENTIFICACION Y/O FECHA DE NACIMIENTO</b></a>\n";
      $html .= "					</td>\n";
      $html .= "				</tr>\n";
      $html .= "				<tr>\n";
      $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "						<a href=\"".$action['novedad'].URLRequest(array("novedad"=>"N02"))."\"><b>MODIFICAR NOMBRES</b></a>\n";
      $html .= "					</td>\n";
      $html .= "				</tr>\n";
      $html .= "				<tr>\n";
      $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "						<a href=\"".$action['novedad'].URLRequest(array("novedad"=>"N03"))."\"><b>MODIFICAR APELLIDOS</b></a>\n";
      $html .= "					</td>\n";
      $html .= "				</tr>\n";
      $html .= "				<tr>\n";
      $html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "						<a href=\"".$action['novedad'].URLRequest(array("novedad"=>"N17"))."\"><b>MODIFICAR SEXO</b></a>\n";
      $html .= "					</td>\n";
      $html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
		* Funcion donde se crea una forma que contiene la informacion del afiliado que se
    * esta consultando y los datos que se van a cambiar segun sea indicado por la novedad
    *
		* @param array $action Vector de links de la aplicaion
    * @param array $afiliado Vector con los datos del afiliado
    * @param array $tipos_documento Vector con los tipos de documentos 
    * @param string $novedad identificador de la novedad a tratar
    *
		* @return String
		*/
		function FormaModificarInformacion($action,$afiliado,$tipos_documento,$novedad)
		{
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;\"";
      
      $html  = "<script>\n"; 
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "</script>\n"; 
      
			$html .= ThemeAbrirTabla('MODIFICAR INFORMACION');
			$html .= "<table class=\"modulo_table_list\"  width=\"65%\" align=\"center\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "	  <td colspan=\"2\">INFORMACION DEL AFILIADO</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">IDENTIFICACION</td>\n";
			$html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "			".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";			
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">NOMBRE AFILIADO</td>\n";
			$html .= "		<td class=\"modulo_list_oscuro\">\n";
			$html .= "			".$afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']."\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "			".$afiliado['fecha_nacimiento']."\n";
      $html .= "		</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "		<td class=\"modulo_list_oscuro\">\n";
      $html .= "		  ".$afiliado['direccion_residencia']." ".$afiliado['departamento_municipio']." \n";
      $html .= "		</td>\n";
			$html .= "	</tr>\n";	
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "			".$afiliado['telefono_residencia']."\n";
      $html .= "		</td>\n";
      $html .= "	</tr>\n";			
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">TELEFONO MOVIL</td>\n";
			$html .= "		<td align=\"left\" class=\"modulo_list_oscuro\">\n";
			$html .= "			".$afiliado['telefono_movil']."\n";
      $html .= "		</td>\n";
			$html .= "	</tr>\n";	
			$html .= "</table><br>\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:EvaluarDatos(document.registrar_afiliacion)\" method=\"post\">\n";
			$html .= " <center>\n";
			$html .= "	<table width=\"65%\" class=\"label\" $style >\n";
      $html .= "	  <tr class=\"formulacion_table_list\">\n";
			$html .= "	    <td colspan=\"2\">NUEVOS DATOS</td>\n";
			$html .= "	  </tr>\n";
      switch($novedad)
      {
        case 'N01':
          $scrp .= "  function EvaluarDatos(frm)\n";
          $scrp .= "  {\n";
          $scrp .= "    if(frm.afiliado_tipo_id.value != '' && frm.afiliado_id.value != '' && frm.fecha_nacimiento.value != '')\n ";
          $scrp .= "    {\n";
          $scrp .= "      if('".$afiliado['afiliado_tipo_id']."' != frm.afiliado_tipo_id.value || ";
          $scrp .= "          '".$afiliado['afiliado_id']."' != frm.afiliado_id.value )\n";
          $scrp .= "      {\n";
          $scrp .= "		    xajax_AfiliadoExiste(xajax.getFormValues('registrar_afiliacion'));\n";
    			$scrp .= "	      return;\n";
          $scrp .= "	    }\n";
    			$scrp .= "	    else if('".$afiliado['fecha_nacimiento']."' != frm.fecha_nacimiento.value  && !IsDate(frm.fecha_nacimiento.value))\n";
    			$scrp .= "	    {\n";
    			$scrp .= "	      document.getElementById('error').innerHTML = 'LA FECHA DE NACIMIENTO POSEE UN FORMATO INCORRECTO';\n";
    			$scrp .= "	      return;\n";
    			$scrp .= "	    }\n";
    			$scrp .= "	    continuar();\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	  else\n";
          $scrp .= "	  {\n";
    			$scrp .= "	    document.getElementById('error').innerHTML = 'LOS DATOS SOLICITADOS SON OBLIGATORIOS';\n";
    			$scrp .= "	    return;\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	}\n";

          
          $html .= "		<tr>\n";
    			$html .= "			<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE IDENTIFICACION</td>\n";
    			$html .= "			<td>\n";
    			$html .= "				<select name=\"afiliado_tipo_id\" class=\"select\">\n";
    			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
    			
    			$s = "";
          foreach($tipos_documento as $key => $datos)
          {
    				($key == $afiliado['afiliado_tipo_id'])? $s = "selected": $s = "";
            $html .= "					<option value=\"".$datos['tipo_id_paciente']."\" $s>".$datos['descripcion']."</option>\n";
          }
    			$html .= "				</select>\n";      
    			$html .= "			</td>\n";
    			$html .= "		</tr>\n";
    			$html .= "		<tr>\n";
          $html .= "			<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">N IDENTIFICACION</td>\n";
    			$html .= "			<td>\n";
    			$html .= "				<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"afiliado_id\" value=\"".$afiliado['afiliado_id']."\" class=\"input-text\" size=\"32\">\n";
    			$html .= "			</td>\n";
    			$html .= "		</tr>\n";	
    			$html .= "			<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
    			$html .= "			<td >\n";
    			$html .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
    			$html .= "				".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
          $html .= "			</td>\n";
        break;
        case 'N02':
          $scrp .= "  function EvaluarDatos(frm)\n";
          $scrp .= "  {\n";
          $scrp .= "    if(frm.primer_nombre.value != '')\n ";
          $scrp .= "    {\n";
          $scrp .= "      if('".$afiliado['primer_nombre']."' != frm.primer_nombre.value || ";
          $scrp .= "          '".$afiliado['segundo_nombre']."' != frm.segundo_nombre.value )\n";
          $scrp .= "      {\n";
    			$scrp .= "	      continuar();\n";
    			$scrp .= "	    }\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	  else\n";
          $scrp .= "	  {\n";
    			$scrp .= "	    document.getElementById('error').innerHTML = 'EL CAMPO PRIMER NOMBRE ES OBLIGATORIO';\n";
    			$scrp .= "	    return;\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	}\n";
        	$html .= "		<tr>\n";
          $html .= "			<td class=\"formulacion_table_list\" >PRIMER NOMBRE</td>\n";
    			$html .= "			<td>\n";
    			$html .= "				<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primer_nombre\" value=\"".$afiliado['primer_nombre']."\" class=\"input-text\" size=\"20\">\n";
          $html .= "			</td>\n";          
          $html .= "		</tr>\n";
    			$html .= "		<tr>\n";
          $html .= "			<td class=\"formulacion_table_list\">SEGUNDO NOMBRE</td>\n";
          $html .= "			<td>\n";
          $html .= "				<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"segundo_nombre\" value=\"".$afiliado['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        break;
        case 'N03';
          $scrp .= "  function EvaluarDatos(frm)\n";
          $scrp .= "  {\n";
          $scrp .= "    if(frm.primer_apellido.value != '')\n ";
          $scrp .= "    {\n";
          $scrp .= "      if('".$afiliado['primer_apellido']."' != frm.primer_apellido.value || ";
          $scrp .= "          '".$afiliado['segundo_apellido']."' != frm.segundo_apellido.value )\n";
          $scrp .= "      {\n";
    			$scrp .= "	      continuar();\n";
          $scrp .= "	    }\n";
          $scrp .= "	  }\n";
    			$scrp .= "	  else\n";
          $scrp .= "	  {\n";
    			$scrp .= "	    document.getElementById('error').innerHTML = 'EL CAMPO PRIMER APELLIDO ES OBLIGATORIO';\n";
    			$scrp .= "	    return;\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	}\n";
    			$html .= "		<tr >\n";
    			$html .= "			<td class=\"formulacion_table_list\">PRIMER APELLIDO</td>\n";
    			$html .= "			<td>\n";
    			$html .= "				<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primer_apellido\" value=\"".$afiliado['primer_apellido']."\" class=\"input-text\" size=\"20\">\n";
          $html .= "			</td>\n";        
          $html .= "		</tr>\n";
          $html .= "		<tr>\n";
    			$html .= "			<td class=\"formulacion_table_list\">SEGUNDO APELLIDO</td>\n";
    			$html .= "			<td>\n";
    			$html .= "				<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"segundo_apellido\" value=\"".$afiliado['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
          $html .= "			</td>\n";        
          $html .= "		</tr>\n";
        break;
        case 'N17':
          $s1 = $s2 = "";
          
          if(trim($afiliado['tipo_sexo_id']) == 'M') $s1 = "checked";
          if(trim($afiliado['tipo_sexo_id']) == 'F') $s2 = "checked";
          
          $scrp .= "  function EvaluarDatos(frm)\n";
          $scrp .= "  {\n";
          $scrp .= "    if(frm.tipo_sexo[0].checked || frm.tipo_sexo[1].checked )\n ";
          $scrp .= "    {\n";
          if(trim($afiliado['tipo_sexo_id']) == 'M')
          {
            $scrp .= "      if(frm.tipo_sexo[1].checked )\n";
            $scrp .= "	      continuar();\n";
          }
          
          if(trim($afiliado['tipo_sexo_id']) == 'F')
          {
            $scrp .= "      if(frm.tipo_sexo[0].checked )\n";
            $scrp .= "	      continuar();\n";
          }
          
          $scrp .= "	  }\n";
    			$scrp .= "	  else\n";
          $scrp .= "	  {\n";
    			$scrp .= "	    document.getElementById('error').innerHTML = 'EL CAMPO PRIMER APELLIDO ES OBLIGATORIO';\n";
    			$scrp .= "	    return;\n";
    			$scrp .= "	  }\n";
    			$scrp .= "	}\n";
          $html .= "		<tr>\n";
          $html .= "			<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
          $html .= "			<td align=\"left\">\n";
      		$html .= "			  <input type=\"radio\" name=\"tipo_sexo\" value=\"M\" $s1>Masculino\n";
          $html .= "				<input type=\"radio\" name=\"tipo_sexo\" value=\"F\" $s2>Femenino\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        break;
      }
			$html .= "	</table>\n";
			$html .= "	</center>\n";
			$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>\n";
			$html .= "		</form>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<script>\n";
      $html .= "	function continuar()\n";
    	$html .= "	{\n";
    	$html .= "		document.registrar_afiliacion.action = \"".$action['aceptar']."\"; \n";
    	$html .= "		document.registrar_afiliacion.submit();\n";
    	$html .= "	}\n";
			$html .= $scrp;
      $html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		if(fecha == '' || fecha == undefined)	return false;\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('/');\n";
			$html .= "		if(arr.length > 3)\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
      $html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "	  if(!valor) return false;\n";
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
			$html .= "</script>\n";
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
  }
?>