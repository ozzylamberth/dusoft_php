<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: RegistrarBeneficiarioHTML.class.php,v 1.2 2009/09/30 12:52:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: RegistrarBeneficiarioHTML
  * Clase encargada de crear las formas para ingresar los datos de los beneficiarios
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class RegistrarBeneficiarioHTML
  {
    /**
    * constructor de la clase
    */
    function RegistrarBeneficiarioHTML(){}
    /**
		* Crea una forma, donde se muestra la informacion basica de un 
    * cotizante, ofreciendo un link para el registro de beneficiarios
		*
		* @param array $action Vector de links de la aplicaion
		* @param array $afiliado Vector con los datos del cotizante
    * @param array $beneficiarios Vector con los datos de los 
    *              beneficiarios asociados al cotizante
		*
		* @return string
		*/
		function FormaInformacionCotizante($action,$afiliado,$beneficiarios)
		{ 
			$html .= ThemeAbrirTabla('INFORMACION DEL AFILIADO');
			$html .= "<table width=\"80%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DEL COTIZANTE</legend>\n";
			$html .= "				<table class=\"modulo_table_list\"  width=\"100%\" >\n";
			$html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">IDENTIFICACION</td>\n";
			$html .= "						<td >\n";
			$html .= "							".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";			
			$html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">NOMBRE AFILIADO</td>\n";
			$html .= "						<td>\n";
			$html .= "						  ".$afiliado['primer_apellido']." ".$afiliado['segundo_apellido']." ".$afiliado['primer_nombre']." ".$afiliado['segundo_nombre']."\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr class=\"label\">\n";
			$html .= "					  <td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "						<td >\n";
			$html .= "						  ".$afiliado['fecha_nacimiento']."\n";
      $html .= "						</td>\n";
			$html .= "					</tr>\n";
      $html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "						<td>\n";
      $html .= "						  ".$afiliado['direccion_residencia']." ".$afiliado['departamento_municipio']." \n";
      $html .= "						</td>\n";
			$html .= "					</tr>\n";	
			$html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "						<td >\n";
			$html .= "							".$afiliado['telefono_residencia']."\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";			
			$html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">TELEFONO MOVIL</td>\n";
			$html .= "						<td align=\"left\">\n";
			$html .= "							".$afiliado['telefono_movil']."\n";
      $html .= "						</td>\n";
			$html .= "					</tr>\n";				
      $html .= "					<tr class=\"label\">\n";
			$html .= "						<td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\">ESTADO</td>\n";
			$html .= "						<td align=\"left\">\n";
			$html .= "							".$afiliado['descripcion_estado']." / ".$afiliado['descripcion_subestado']."\n";
      $html .= "						</td>\n";
			$html .= "					</tr>\n";	
			$html .= "				</table>\n";

      if(!empty($beneficiarios))
      {
        $html .= "  <br>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" allign=\"center\">\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td colspan=\"6\">BENEFICIARIOS INGRESADOS ANTERIORMENTE</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td>IDENTIFICACION</td>\n";
        $html .= "      <td>NOMBRE BENEFICIARIO</td>\n";
        $html .= "      <td>FECHA NACIMIENTO</td>\n";
        $html .= "      <td>SEXO</td>\n";
        $html .= "      <td>ESTADO</td>\n";
        $html .= "      <td>PARENTESCO</td>\n";
        $html .= "    </tr>\n";
        
        foreach($beneficiarios as $key => $tipo_id)
        {
          foreach($tipo_id as $keyI => $detalle)
          {
            $html .= "    <tr class=\"modulo_list_claro\">\n";
            $html .= "      <td>".$key." ".$keyI."</td>\n";
            $html .= "      <td>".strtoupper($detalle['primer_apellido']." ".$detalle['segundo_apellido']." ".$detalle['primer_nombre']." ".$detalle['segundo_nombre'])."</td>\n";
            $html .= "      <td>".$detalle['fecha_nacimiento']." </td>\n";
            $html .= "      <td align=\"center\">".$detalle['tipo_sexo_id']."</td>\n";
            $html .= "      <td>".$detalle['descripcion_estado']." / ".$detalle['descripcion_subestado']."</td>\n";
            $html .= "      <td>".$detalle['descripcion_parentesco']."</td>\n";
            $html .= "    </tr>\n";
          }
        }
        $html .= "  </table><br>\n";
      }
      $html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<center>\n";
			$html .= "  <a href=\"javascript:llamarBeneficiarios()\" class=\"label_error\">\n";
			$html .= "    ADICIONAR BENEFICIARIO\n";
			$html .= "  </a>\n";
			$html .= "</center><br>\n";
			$html .= "<div id=\"informacion\"></div>";
      $html .= "<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<form name=\"aceptar\" action=\"".$action['crear']."\" method=\"post\">\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Confirmar Afiliacion de Beneficiarios\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
 			$html .= "<form name=\"informacion_beneficiario\" method=\"post\" action=\"javascript:mostrarTablaBeneficiarios()\"></form>\n";
      $html .= "<script>\n"; 
      $html .= "  function eliminarBeneficiarios(tipo_identificacion,documento)\n"; 
      $html .= "  {\n"; 
      $html .= "    xajax_EliminarBeneficiario(tipo_identificacion,documento);\n"; 
      $html .= "  }\n"; 
      $html .= "  function mostrarTablaBeneficiarios()\n"; 
      $html .= "  {\n"; 
      $html .= "    xajax_MostrarTablaBeneficiarios();\n"; 
      $html .= "  }\n"; 
      $html .= "  function llamarBeneficiarios(tipo_id,documento)\n"; 
      $html .= "  {\n"; 
      $html .= "    url = '".$action['beneficiario']."';\n"; 
      $html .= "    if(documento != '')\n"; 
      $html .= "      url = url+'&afiliado_tipo_id='+tipo_id+'&documento='+documento;\n"; 
      $html .= "    window.open(url,'beneficiario','toolbar=no,width=850,height=600,resizable=no,scrollbars=yes').focus(); \n";
      $html .= "  }\n"; 
      $html .= "</script>\n"; 
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
		* Crea una forma en la que se cargan los datos del beneficiario,
    * (si existen) para hacer la modificacion o el ingreso de los mismos 
		*
    * @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipo_afiliacion Vector de ripos de documentos
		* @param array $eps Vector con los datos de las eps parametrizadas
    * @param array $ocupacion Vector con los datos de la ocupacion (grupo principal)
    * @param array $afiliado Vector con los datos del afiliado
    * @param array $tipos_documento Vector con los tipos de documentos 
    * @param array $parentesco vector con los datos de los tipos de parentesco
    *
		* @return string
		*/
		function FormaDatosAfiliacionBeneficiario($action,$request,$tipo_afiliacion,$eps,$ocupacion,$afiliado,$tipos_documento,$parentesco)
		{
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
      $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$afiliado['pais']."&dept=".$afiliado['dpto']."&mpio=".$afiliado['mpio']."&forma=registrar_afiliacion ";      
      
      $valida = ""; $i = 0;
      
      $html  = "<script>\n"; 
      $html .= "	function cerrarVentana()\n";
			$html .= "	{\n";
			$html .= "		window.opener.document.informacion_beneficiario.submit();\n";
			$html .= "		window.close();\n";
			$html .= "	}\n";
      $html .= "  function llamarLocalizacion()\n"; 
      $html .= "  {\n"; 
      $html .= "    window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); \n";
      $html .= "  }\n"; 
      $html .= "  function IniciarVentanaOcupacion(content,subcontent,tit,obj_cerrar,ancho,alto)\n"; 
      $html .= "  {\n"; 
      $html .= "    Iniciar(content,subcontent,tit,obj_cerrar,ancho*1,alto*1);\n"; 
      $html .= "		MostrarSpan(content);\n";
      $html .= "  }\n";
      $html .= "  function MostrarCapaEstamento(valor)\n"; 
      $html .= "  {\n"; 
      $html .= "    cp1 = 'pensionado'; cp2 = 'otro';\n"; 
      $html .= "    if(valor == '-1')\n"; 
      $html .= "    {\n"; 
      $html .= "      OcultarSpan(cp2);OcultarSpan(cp1);\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(valor == 'J')\n"; 
      $html .= "      {\n"; 
      $html .= "		    OcultarSpan(cp2);\n";
      $html .= "		    MostrarSpan(cp1);\n";
      $html .= "      }\n";
      $html .= "      else\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      MostrarSpan(cp2);\n";
      $html .= "        }\n";
      $html .= "  }\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "  function EvaluarDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO\";\n"; 
      $html .= "      return true;\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(objeto.sub_grupos_principales.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO PRINCIPAL\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n"; 
  		$html .= "      else if(objeto.sub_grupo.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
  		$html .= "        else if(objeto.grupos_primarios.value == \"-1\")\n";
      $html .= "          {\n"; 
      $html .= "            document.getElementById(\"error_ocupacion\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO PRIMARIO\";\n"; 
      $html .= "            return true;\n"; 
      $html .= "          }\n"; 
      $html .= "    document.getElementById(\"error_ocupacion\").innerHTML = \"\";\n"; 
      $html .= "    document.getElementById(\"ocupacion_texto\").innerHTML = objeto.grupos_primarios.options[objeto.grupos_primarios.selectedIndex].title;\n"; 
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "  function EvaluarDatosActividad(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.division_actividad.value == \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA DIVISION\";\n"; 
      $html .= "      return true;\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(objeto.grupo_actividad.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n"; 
      $html .= "    document.getElementById(\"error_actividad\").innerHTML = \"\";\n"; 
      $html .= "    document.getElementById(\"actividad_texto\").innerHTML = objeto.grupo_actividad.options[objeto.grupo_actividad.selectedIndex].title;\n"; 
      $html .= "    OcultarSpan('Actividad');\n"; 
      $html .= "  }\n"; 
      $html .= "</script>\n"; 
      
			$html .= ThemeAbrirTabla('BENEFICIARIO');
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:evaluarDatosObligatorios(document.registrar_afiliacion)\" method=\"post\">\n";
			$html .= "<table border=\"-1\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "			  <legend class=\"normal_10AN\">INFORMACION BENEFICIARIO</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"5\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "				            <select name=\"tipo_id_beneficiario\" class=\"select\">\n";
			$html .= "					            <option value=\"-1\">---Seleccionar---</option>\n";
			
			$s = "";
      foreach($tipos_documento as $key => $datos)
      {
				($key == $afiliado['tipo_id_beneficiario'])? $s = "selected": $s = "";
        
        $html .= "					            <option value=\"".$datos['tipo_id_paciente']."\" $s>".$datos['descripcion']."</option>\n";
      }
			$html .= "					          </select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_id_beneficiario.value,1,'TIPO DE IDENTIFICACION','select');\n";
      
			$html .= "									</td>\n";
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">N IDENTIFICACION</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"documento\" value=\"".$afiliado['documento']."\" class=\"input-text\" size=\"32\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.documento.value,1,'N IDENTIFICACION','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";			
			$html .= "								<tr class=\"formulacion_table_list\">\n";
			$html .= "									<td width=\"25%\">PRIMER APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO APELLIDO</td>\n";
			$html .= "									<td width=\"25%\">PRIMER NOMBRE</td>\n";
			$html .= "									<td width=\"25%\">SEGUNDO NOMBRE</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr align=\"center\">\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primerapellido\" value=\"".$afiliado['primerapellido']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,1,'PRIMER APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"segundoapellido\" value=\"".$afiliado['segundoapellido']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,0,'SEGUNDO APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primernombre\" value=\"".$afiliado['primernombre']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,1,'PRIMER NOMBRE','text');\n";
			
      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"segundonombre\" value=\"".$afiliado['segundonombre']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,0,'SEGUNDO NOMBRE','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
			$html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_nacimiento.value,1,'FECHA NACIMIENTO','date');\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">\n";
      
      $s1 = $s2 = "";
      
      if(trim($afiliado['tipo_sexo']) == 'M') $s1 = "checked";
      if(trim($afiliado['tipo_sexo']) == 'F') $s2 = "checked";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"M\" $s1>Masculino\n";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"F\" $s2>Femenino\n";
      $html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "									<td >\n";
 			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"direccion_residencia\" value=\"".$afiliado['direccion_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.direccion_residencia.value,1,'DIRECCION','text');\n";

      $html .= "									</td>\n";
      $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_residencia\" value=\"".$afiliado['telefono_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_residencia.value,0,'TELEFONO RESIDENCIA','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			      
      $html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DEPARTAMENTO - MUNICIPIO</td>\n";
			$html .= "									<td>\n";
			$html .= "				            <a title=\"ADICIONAR O CAMBIAR DEPARTAMENTO\" href=\"javascript:llamarLocalizacion()\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "									  <label id=\"ubicacion\">".$afiliado['ubicacion_hd']."</label>\n";
			$html .= "			              <input type=\"hidden\" name=\"pais\" value=\"".$afiliado['pais']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"dpto\" value=\"".$afiliado['dpto']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"mpio\" value=\"".$afiliado['mpio']."\">\n";			

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ZONA DE RESIDENCIA</td>\n";
			$html .= "									<td align=\"left\">\n";
			
      $s1 = $s2 = "";
      if($afiliado['zona_residencia'] == 'U') $s1 = "checked";
      if($afiliado['zona_residencia'] == 'R') $s2 = "checked";
      $html .= "										<input type=\"radio\" name=\"zona_residencia\" $s1 value=\"U\">Urbano\n";
			$html .= "										<input type=\"radio\" name=\"zona_residencia\" $s2 value=\"R\">Rural\n";
			$html .= "									</td>\n";
      
			$html .= "								</tr>\n";	
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
 			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" colspan=\"2\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_sgss\" style=\"width:90%\" maxlength=\"10\"  onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_sgss']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_sgss.value,0,'FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL','date');\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" colspan=\"3\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_sgss','/')."</td>\n";
			$html .= "								</tr>\n";

			$html .= "								<tr >\n";
			$html .= "									<td width=\"60%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SERVICIO DE SALUD</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion_empresa\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_empresa']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion_empresa.value,1,'FECHA DE AFILIACION AL SERVICIO DE SALUD','date');\n";

      $html .= "									</td>\n";
			$html .= "									<td width=\"15%\" align=\"left\" colspan=\"3\">".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion_empresa','/')."</td>\n";		
			$html .= "								</tr>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OCUPACION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
 			$html .= "				            <a title=\"SELECCIONAR OCUPACION\" href=\"javascript:IniciarVentanaOcupacion('Ocupacion','Contenido_Ocupacion','ocupacion_titulo','ocupacion_cerrar',400,180)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"ocupacion_texto\">".utf8_decode($afiliado['ocupacion_hd'])."</label>\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PARENTESCO</td>\n";
			$html .= "									<td width=\"%\" colspan=\"5\">\n";
			$html .= "									  <select name=\"parentesco\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
      foreach($parentesco as $key => $detalle)
      {
        ($key == $afiliado['parentesco'])? $s = "selected":$s = "";
        $html .= "											<option value=\"".$key."\" $s>".$detalle['descripcion_parentesco']."</option>\n";
      } 
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.parentesco.value,1,'PARENTESCO','select');\n";

			$html .= "									</td>\n";	     
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";

			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRE DE LA EPS ANTERIOR</td>\n";
			$html .= "									<td width=\"65%\" colspan=\"3\">\n";
      $html .= "									  <select name=\"eps_anterior\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($eps as $key => $detalle)
      {
        ($key == $afiliado['eps_anterior'])? $s = "selected":$s = "";
        $html .= "											<option value=\"".$key."\" $s>".$detalle['razon_social_eps']."</option>\n";
      }
      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.eps_anterior.value,0,'NOMBRE DE LA EPS ANTERIOR','select');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";			
			$html .= "								<tr >\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA DE AFILIACION</td>\n";
			$html .= "									<td align=\"left\" width=\"25%\" >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion\" style=\"width:60%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion.value,0,'FECHA DE AFILIACION','date');\n";

      $html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion','/')."</td>\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEMANAS DE COTIZACION</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"semanas_cotizadas\" size=\"12\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" value=\"".$afiliado['semanas_cotizadas']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.semanas_cotizadas.value,0,'SEMANAS DE COTIZACION','numeric');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";				
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
      
      $html .= "<div id='Ocupacion' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='ocupacion_titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">SELECCIONAR OCUPACION</div>\n";
			$html .= "	<div id='ocupacion_cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Ocupacion')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido_Ocupacion' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "		<center>\n";
			$html .= "			<label id=\"error_ocupacion\" class=\"label_error\"></label>\n";
			$html .= "	  </center>\n";			
      $html .= "		<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"grandes_grupos\" class=\"select\" onchange=\"xajax_SeleccionarSubGrupoPrincipal(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($ocupacion as $key => $detalle)
        $html .= "						<option value=\"".$key."\" title=\"".$detalle['descripcion_ciuo_88_gran_grupo']."\">".substr($detalle['descripcion_ciuo_88_gran_grupo'],0,40)."</option>\n";

      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO PRINCIPAL</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupos_principales\" class=\"select\" onChange=\"xajax_SeleccionarSubGrupos(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SUBGRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"sub_grupo\" class=\"select\" onChange=\"xajax_SeleccionarGruposPrimarios(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO PRIMARIO</td>\n";
			$html .= "				<td width=\"%\" >\n";
      $html .= "					<select name=\"grupos_primarios\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
      $html .= "    <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Ocupacion')\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
      $html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<input type=\"hidden\" name=\"ocupacion_hd\" value=\"\">\n";
			$html .= "<input type=\"hidden\" name=\"grupo_primario_hd\" value=\"".$afiliado['grupo_primario_hd']."\">\n";
			$html .= "<input type=\"hidden\" name=\"ubicacion_hd\" value=\"\" >\n";
 			$valida .= "	if(objeto.ubicacion_hd.value == '')\n";
 			$valida .= "	{\n";
      $valida .= "    objeto.ubicacion_hd.value = document.getElementById('ubicacion').innerHTML;\n";
      $valida .= "    objeto.grupo_primario_hd.value = objeto.grupos_primarios.value;\n";
 			$valida .= "	}\n";
      $valida .= "	obligatorios[".($i++)."] = new Array(objeto.ubicacion_hd.value,1,'DEPARTAMENTO - MUNICIPIO','text');\n";
 			$valida .= "	if(objeto.ocupacion_hd.value == '') objeto.ocupacion_hd.value = document.getElementById('ocupacion_texto').innerHTML;\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.ocupacion_hd.value,0,'OCUPACION','text');\n";

			$html .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
      
			$html .= "  <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"javascript:cerrarVentana()\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
      
      $html .= "<script>\n";
			$html .= "	var contenedor = '';\n";
			$html .= "	var subcontenedor = '';\n";
			$html .= "	var titulo = '';\n";
			$html .= "	var cerrar = '';\n";
			$html .= "	var hiZ = 2;\n";
			
			$html .= "	function Iniciar(content,subcontent,tit,obj_cerrar,ancho,alto)\n";
			$html .= "	{\n";
			$html .= "		subcontenedor = subcontent;\n";
			$html .= "		contenedor = content;\n";
			$html .= "		titulo = tit;\n";
			$html .= "		cerrar = obj_cerrar;\n";
			$html .= "		ele = xGetElementById(subcontent);\n";
			$html .= "	  xResizeTo(ele,ancho,alto);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,ancho, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,(ancho-20), 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById(cerrar);\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, (ancho - 20), 0);\n";
			$html .= "	}\n";

			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
      
      $html .= "	function evaluarDatosObligatorios(objeto)\n";
			$html .= "	{\n";
			$html .= "		div_msj = document.getElementById('error');\n";
			$html .= "		sub_obliga = new Array();\n";
			$html .= "		sub_obliga[0] = new Array();\n";
			$html .= "		sub_obliga[1] = new Array();\n";
			$html .= "		obligatorios = new Array();\n";
			$html .= $valida."\n";
			$html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
			$html .= "			if(obligatorios[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(obligatorios[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(obligatorios[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'SE DEBE SELECCIONAR '+obligatorios[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIA O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(obligatorios[i][0] == '' || obligatorios[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = obligatorios[i][2]+', ES OBLIGATORIO';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";      
      $html .= "		if(!objeto.tipo_sexo[0].checked && !objeto.tipo_sexo[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE SEXO';\n";
			$html .= "		   return;\n";
			$html .= "		}\n";
      $html .= "		if(!objeto.zona_residencia[0].checked && !objeto.zona_residencia[1].checked)\n";
			$html .= "		{\n";
      $html .= "		   div_msj.innerHTML = 'SE DEBE SELECCIONAR LA ZONA DE RESIDENCIA';\n";
			$html .= "		   return;\n";
			$html .= "		}\n"; 
			$html .= "		document.getElementById('error').innerHTML = '<br>';\n";
			$html .= "		objeto.action = \"".$action['registrar']."\";\n";
			$html .= "		objeto.submit();\n";
			$html .= "	}\n";
      
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
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor num�ico\n";
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
  }
?>