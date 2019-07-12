<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: IngresoAfiliadoHTML.class.php,v 1.3 2009/09/30 12:52:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: IngresoAfiliadoHTML
  * Clase encargada de crear las formas para el ingreso de datos de los 
  * afiliados, cotizantes y beneficiarios
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class IngresoAfiliadoHTML
	{
		/**
		* Constructor de la clase
		*/
		function IngresoAfiliadoHTML(){}
		/**
		* Crea la forma para la el ingreso de los afiliados que seran 
    * cotizantes
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $tipos_documento Vector de ripos de documentos
		*
		* @return String
		*/
		function FormaBuscarAfiliado($action,$tipos_documento)
		{
			$html  = ThemeAbrirTabla('BUSCAR AFILIADO');
			$html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.tipo_id_paciente.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.documento.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_BuscarAfiliado(xajax.getFormValues('registrar_afiliacion'));\n";
			$html .= "	}\n";
			$html .= "	function continuarAfiliacion()\n";
			$html .= "	{\n";
			$html .= "		document.registrar_afiliacion.action = \"".$action['registrar']."\"; \n";
			$html .= "		document.registrar_afiliacion.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:ValidarDatos(document.registrar_afiliacion)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">BUSCAR AFILIADO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipos_documento as $key => $datos)
				$html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"documento\" value=\"\" style=\"width:50%\" >\n";
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
		* Crea la forma para el ingreso de los datos de la afiliacion de 
    * un cotizante
		*
		* @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipo_afiliacion Vector con los datos de tipos de afiliacion
		* @param array $estadocivil Vector con los datos de los estados civiles parametrizados
    * @param array $estratos Vector con los datos de los estratos parametrizados
    * @param array $tipo_afiliado Vector con los tipos de afiliado parametrizados
    * @param array $tipo_aportante Vector con los tipos de aportante parametrizados
    * @param array $estamentos Vector con los diferentes tipos de estamentos
    * @param array $pensiones Vector con los diferentes tipos de fondos de pensiones
    * @param array $eps Vector con los diferentes tipos de eps
    * @param array $ocupacion Vector con los datos de la ocupacion (grupo principal)
    * @param array $actividad Vector con los datos de la actividad economica (divisiones)
    * @param array $dependencia Vector con los datos de las dependencias
    * @param array $afiliado Vector con los datos del afiliado
    * @param array $convenio Vector con los datos de las entidades convenio
    * @param array $parentesco vector con los datos de los tipos de parentesco
    * @param array $planes vector con los datos de los planes
    * @param array $puntos vector con los datos de los puntos de atencion
    *
		* @return String
		*/
		function FormaRegistrarAfiliacion($action,$request,$tipo_afiliacion,$estadocivil,$estratos,$tipo_afiliado,$tipo_aportante,$estamentos,$pensiones,$eps,$ocupacion,$actividad,$dependencia,$afiliado,$convenio,$parentesco,$planes,$puntos)
		{
			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
			
      $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$afiliado['tipo_pais_id']."&dept=".$afiliado['tipo_dpto_id']."&mpio=".$afiliado['tipo_mpio_id']."&forma=registrar_afiliacion ";      
      
      $valida = ""; $i = 0;
      
      $html  = "<script>\n"; 
      $html .= "  function llamarLocalizacion()\n"; 
      $html .= "  {\n"; 
      $html .= "    window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); \n";
      $html .= "  }\n"; 
      $html .= "  function IniciarVentanaOcupacion(content,subcontent,tit,obj_cerrar,ancho,alto)\n"; 
      $html .= "  {\n"; 
      $html .= "    Iniciar(content,subcontent,tit,obj_cerrar,ancho*1,alto*1);\n"; 
      $html .= "		MostrarSpan(content);\n";
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
      $html .= "    if(objeto.grandes_grupos.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.sub_grupos_principales.value == \"-1\")\n";
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
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = objeto.grupos_primarios.options[objeto.grupos_primarios.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_ocupacion\").innerHTML = \"\";\n";
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";      
      $html .= "  function ResetDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"ocupacion_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.grandes_grupos.selectedIndex = 0;\n";
      $html .= "      objeto.sub_grupo.selectedIndex = 0;\n";
      $html .= "      objeto.grupos_primarios.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "  function EvaluarDatosActividad(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(objeto.division_actividad.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.grupo_actividad.value == \"-1\")\n";
      $html .= "      {\n"; 
      $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL GRUPO\";\n"; 
      $html .= "        return true;\n"; 
      $html .= "      }\n";    
      $html .= "      else if(objeto.clase_actividad.value == \"-1\")\n";
      $html .= "        {\n"; 
      $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA CLASE\";\n"; 
      $html .= "          return true;\n"; 
      $html .= "        }\n"; 
      $html .= "      document.getElementById(\"actividad_texto\").innerHTML = objeto.grupo_actividad.options[objeto.grupo_actividad.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_actividad\").innerHTML = \"\";\n"; 
      $html .= "    if(objeto.division_actividad.value == \"-1\")\n";
      $html .= "      document.getElementById(\"actividad_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Actividad');\n"; 
      $html .= "  }\n";
      $html .= "  function ResetDatosActividad(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"actividad_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.division_actividad.selectedIndex = 0;\n";
      $html .= "      objeto.grupo_actividad.selectedIndex = 0;\n";
      $html .= "      objeto.clase_actividad.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";
      $html .= "</script>\n"; 
      
			$html .= ThemeAbrirTabla('REGISTRO DE UNA AFILIACION');
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:evaluarDatosObligatorios(document.registrar_afiliacion)\" method=\"post\">\n";
			$html .= "<input type=\"hidden\" name=\"sirh_per_codigo\" value=\"".$afiliado['sirh_per_codigo']."\">\n";
			$html .= "<input type=\"hidden\" name=\"ter_codigo\" value=\"".$afiliado['ter_codigo']."\">\n";
			
      $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DEL COTIZANTE</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
      $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE ATENCION</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<select name=\"plan_atencion\" class=\"select\" onchange=\"xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'))\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($planes as $key => $dtl)
      {
				($afiliado['plan_id'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$key."\" $s1>".$dtl['plan_descripcion']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO AFILIADO PLAN</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"tipo_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"tipo_afiliado_plan\" value=\"\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >RANGO</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"rango_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"rango_afiliado_plan\" value=\"\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								</tr>\n";
      $html .= "							</table>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.plan_atencion.value,1,'PLAN DE ATENCIÓN','select');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliado_plan.value,1,'TIPO DE AFILIADO PLAN','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango_afiliado_plan.value,1,'RANGO','text');\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE RECEPCION</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_recepcion\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_recepcion.value,1,'FECHA DE RECEPCION','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_recepcion','/')."</td>\n";
			$html .= "								  <td colspan=\"3\">&nbsp;</td>\n";
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA VENCIMIENTO AFILIACION</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\" style=\"width:92%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_vencimiento']."\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_vencimiento.value,0,'FECHA VENCIMIENTO AFILIACION','date',0);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_vencimiento','/')."</td>\n";
			$html .= "								  <td colspan=\"3\">&nbsp;</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" colspan=\"4\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL</td>\n";
			$html .= "									<td width=\"10%\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_sgss\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_sgss']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_sgss.value,0,'FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td >".ReturnOpenCalendario('registrar_afiliacion','fecha_sgss','/')."</td>\n";
      $html .= "								</tr>\n";
			$html .= "								<tr>\n";
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO DE VINCULACION O ESTAMENTO</td>\n";
			$html .= "									<td width=\"%\" colspan=\"2\">\n";
			$html .= "									  <select name=\"estamento\" class=\"select\" onchange=\"MostrarCapaEstamento(this.value)\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
      $sl = "";
      $vec = " vector_estamentos = new Array();";
      foreach($estamentos as $key => $detalle)
      {
        $vec .= "	vector_estamentos['".$key."'] = '".$detalle['estamento_siis']."'; ";
        ($key == $afiliado['estamento_id'])? $sl = "selected":$sl = "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_estamento']."</option>\n";
      } 
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estamento.value,1,'TIPO DE VINCULACION O ESTAMENTO','select');\n";

			$html .= "									</td>\n";	
      
      $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO AFILIACION</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<select name=\"tipo_afiliacion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($tipo_afiliacion as $key => $afiliacion)
				$html .= "											<option value=\"".$key."\">".$afiliacion['descripcion_eps_tipo_afiliacion']."</option>\n";
			
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliacion.value,1,'TIPO AFILIACION','select');\n";

			$html .= "									</td>\n";	
      $html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
			$html .= "									<td colspan=\"3\">\n";
			$html .= "										".$request['tipo_id_paciente']." ".$request['documento']."\n";
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
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primerapellido\" value=\"".$afiliado['primer_apellido']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primerapellido.value,1,'PRIMER APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundoapellido\" value=\"".$afiliado['segundo_apellido']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,0,'SEGUNDO APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primernombre\" value=\"".$afiliado['primer_nombre']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,1,'PRIMER NOMBRE','text');\n";
			
      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundonombre\" value=\"".$afiliado['segundo_nombre']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,0,'SEGUNDO NOMBRE','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
			$html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_nacimiento.value,1,'FECHA NACIMIENTO','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">\n";
      
      $s1 = $s2 = "";
      
      if(trim($afiliado['tipo_sexo_id']) == 'M') $s1 = "checked";
      if(trim($afiliado['tipo_sexo_id']) == 'F') $s2 = "checked";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"M\" $s1>Masculino\n";
			$html .= "										<input type=\"radio\" name=\"tipo_sexo\" value=\"F\" $s2>Femenino\n";
      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ESTADO CIVIL</td>\n";
			$html .= "									<td >\n";
			$html .= "										<select name=\"estado_civil\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($estadocivil as $key => $estadocv)
      {
				($afiliado['tipo_estado_civil_id'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$key."\" $s1>".$estadocv['descripcion']."</option>\n";
			}
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estado_civil.value,1,'ESTADO CIVIL','select');\n";

			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">ESTRATO SOCIOECONOMICO</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<select name=\"estrato_socioeconomico\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($estratos as $key => $detalle)
      {
				($key == $afiliado['estrato_socioeconomico_id'])? $sl = "selected":$sl= "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_estrato_socioeconomico']."</option>\n";
			}
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.estrato_socioeconomico.value,0,'ESTRATO SOCIOECONOMICO','select');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DIRECCION</td>\n";
			$html .= "									<td >\n";
 			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"100\" name=\"direccion_residencia\" value=\"".$afiliado['direccion_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.direccion_residencia.value,1,'DIRECCION','text');\n";

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
			      
      $html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">DEPARTAMENTO - MUNICIPIO</td>\n";
			$html .= "									<td colspan =\"3\">\n";
			$html .= "				            <a title=\"ADICIONAR O CAMBIAR DEPARTAMENTO\" href=\"javascript:llamarLocalizacion()\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "									  <label id=\"ubicacion\">".$afiliado['departamento_municipio']."</label>\n";
			$html .= "			              <input type=\"hidden\" name=\"pais\" value=\"".$afiliado['tipo_pais_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"dpto\" value=\"".$afiliado['tipo_dpto_id']."\">\n";
			$html .= "			              <input type=\"hidden\" name=\"mpio\" value=\"".$afiliado['tipo_mpio_id']."\">\n";			
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ubicacion').innerHTML,1,'DEPARTAMENTO - MUNICIPIO','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	

			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO RESIDENCIA</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_residencia\" value=\"".$afiliado['telefono_residencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_residencia.value,0,'TELEFONO RESIDENCIA','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TELEFONO MOVIL</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_movil\" value=\"".$afiliado['telefono_movil']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_movil.value,0,'TELEFONO MOVIL','text');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SERVICIO DE SALUD</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion_empresa\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion_empresa.value,1,'FECHA DE AFILIACION AL SERVICIO DE SALUD','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td width=\"15%\" align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion_empresa','/')."</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE AFILIADO</td>\n";
			$html .= "									<td >COTIZANTE\n";
			$html .= "									  <input type=\"hidden\" name=\"tipo_afiliado\" value=\"C\">\n";
			$html .= "									</td>\n";			
			$html .= "								</tr>\n";
			$html .= "								<tr >\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DEPENDENCIA DONDE LABORA</td>\n";
			$html .= "									<td colspan=\"4\" >\n";
			$html .= "										<select name=\"dependencia_laboral\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			$sl = "";
			foreach($dependencia as $key => $detalle)
      {
				($key == $afiliado['codigo_dependencia_id'])? $sl = "selected":$sl = "";
        $html .= "											<option value=\"".$key."\" $sl>".$detalle['descripcion_dependencia']."</option>\n";
			}
      
			$html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.dependencia_laboral.value,1,'DEPENDENCIA DONDE LABORA','select');\n";

      $html .= "									</td>\n";	
			$html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TELEFONO</td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"telefono_dependencia\" value=\"".$afiliado['telefono_dependencia']."\" class=\"input-text\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.telefono_dependencia.value,0,'TELEFONO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO DE USUARIO</td>\n";
			$html .= "									<td >\n";
			$html .= "									  <select name=\"tipo_aportante\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($tipo_aportante as $key => $detalle)
        $html .= "											<option value=\"".$key."\">".$detalle['descripcion_tipo_aportante']."</option>\n";

      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_aportante.value,1,'TIPO DE USUARIO','select');\n";

			$html .= "									</td>\n";       
			$html .= "								</tr>\n";      
			$html .= "								<tr >\n";
			$html .= "									<td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OCUPACION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
 			$html .= "				            <a title=\"SELECCIONAR OCUPACION\" href=\"javascript:IniciarVentanaOcupacion('Ocupacion','Contenido_Ocupacion','ocupacion_titulo','ocupacion_cerrar',400,180)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"ocupacion_texto\"></label>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('ocupacion_texto').innerHTML,0,'OCUPACION','text');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";
      
      $html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION</td>\n";
			$html .= "									<td colspan=\"3\">\n";
			$html .= "										<select name=\"puntos_atencion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($puntos as $key => $dtl)
      {
				($afiliado['eps_punto_atencion_id'] == $dtl['eps_punto_atencion_id'])? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$dtl['eps_punto_atencion_id']."\" $s1>".$dtl['eps_punto_atencion_nombre']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.puntos_atencion.value,1,'PUNTO DE ATENCION','select');\n";

			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";

			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRE DE LA EPS ANTERIOR</td>\n";
			$html .= "									<td width=\"65%\" colspan=\"3\">\n";
      $html .= "									  <select name=\"eps_anterior\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($eps as $key => $detalle)
        $html .= "											<option value=\"".$key."\">".$detalle['razon_social_eps']."</option>\n";

      $html .= "										</select>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.eps_anterior.value,0,'NOMBRE DE LA EPS ANTERIOR','select');\n";

			$html .= "									</td>\n";	
			$html .= "								</tr>\n";			
			$html .= "								<tr >\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA DE AFILIACION</td>\n";
			$html .= "									<td align=\"right\" width=\"25%\" >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion\" style=\"width:40%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion.value,0,'FECHA DE AFILIACION','date',1);\n";

      $html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion','/')."</td>\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEMANAS DE COTIZACION</td>\n";
			$html .= "									<td align=\"left\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"semanas_cotizadas\" size=\"12\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" value=\"\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.semanas_cotizadas.value,0,'SEMANAS DE COTIZACION','numeric');\n";

      $html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ACTIVIDAD ECONOMICA DEL COTIZANTE</td>\n";
			$html .= "									<td colspan=\"3\">\n";
 			$html .= "				            <a title=\"SELECCIONAR ACTIVIDAD ECONOMICA\" href=\"javascript:IniciarVentanaOcupacion('Actividad','Contenido_Actividad','actividad_titulo','actividad_cerrar',400,80)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"actividad_texto\"></label>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(document.getElementById('actividad_texto').innerHTML,0,'ACTIVIDAD ECONOMICA DEL COTIZANTE','text');\n";

      $html .= "									</td>\n";	
			$html .= "								</tr>\n";				
			$html .= "							</table>\n";
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			
      $cvn = $otr = $psn = $sub = "none";
      if($afiliado['estamento_id'])
      {
        switch($estamentos[$afiliado['estamento_id']]['estamento_siis'])
        {
          case 'J':
            $psn = "block";
          break;
          case 'S':
            $sub = "block";
            $psn = "block";
          break;
          case 'V':
            $cvn = "block";
          break;
          default:
            $otr = "block";
          break;
        }
      }
      
			$html .= "							<div id=\"pensionado\" style=\"display:$psn\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ENTIDAD QUE TIENE A CARGO SU PENSION</td>\n";
			$html .= "										<td width=\"%\" >\n";
      $html .= "									    <select name=\"administradora_pensiones\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($pensiones as $key => $detalle)
      {
        ($key == $afiliado['codigo_afp'])? $sl = "selected":$sl = "";
        $html .= "											  <option value=\"".$key."\" $sl>".$detalle['razon_social_afp']."</option>\n";
      }
      $html .= "										  </select>\n";
 			$valida .= "	sub_obliga[0][0] = new Array(objeto.administradora_pensiones.value,1,'ENTIDAD QUE TIENE A CARGO SU PENSION','select');\n";
      
      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"30%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INGRESO MENSUAL O VALOR MESADA PENSIONAL</td>\n";
			$html .= "										<td width=\"%\">\n";
			$html .= "											<input type=\"text\" class=\"input-text\" name=\"ingreso_mensual\" style=\"width:20%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$afiliado['ingreso_mensual']."\">\n";
 			$valida .= "	sub_obliga[0][1] = new Array(objeto.ingreso_mensual.value,1,'INGRESO MENSUAL O VALOR MESADA PENSIONAL','numeric');\n";

      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			$html .= "							</div>\n";
      $html .= "							<div id=\"parentesco_pensionado\" style=\"display:$sub\">\n";
 			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
      $html .= "								  <tr>\n";
			$html .= "									  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PARENTESCO CON COTIZANTE FALLECIDO</td>\n";
			$html .= "									  <td width=\"60%\" >\n";
			$html .= "									    <select name=\"parentesco\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
      foreach($parentesco as $key => $detalle)
      {
        ($key == $afiliado['parentesco'])? $s = "selected":$s = "";
        $html .= "											  <option value=\"".$key."\" $s>".$detalle['descripcion_parentesco']."</option>\n";
      } 
			$html .= "										  </select>\n";
 			$valida .= "	sub_obliga[0][2] = new Array(objeto.parentesco.value,1,'PARENTESCO','select');\n";

			$html .= "									  </td>\n";	     
			$html .= "								  </tr>\n";
			$html .= "								</table>\n";
      $html .= "							</div>\n";
			
			$html .= "							<div id=\"otro\" style=\"display:$otr\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "									  <td colspan=\"4\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA DE INGRESO A LABORAR EN LA INSTITUCION O FECHA DE VINCULACION AL APORTANTE</td>\n";
			$html .= "									  <td width=\"10%\">\n";
			$html .= "										  <input type=\"text\" class=\"input-text\" name=\"fecha_ingreso_empleo\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_ingreso_laboral']."\">\n";
 			$valida .= "	sub_obliga[1][0] = new Array(objeto.fecha_ingreso_empleo.value,1,'FECHA DE INGRESO A LABORAR EN LA INSTITUCION O FECHA DE VINCULACION AL APORTANTE','date',1);\n";

      $html .= "									  </td>\n";
			$html .= "									  <td >".ReturnOpenCalendario('registrar_afiliacion','fecha_ingreso_empleo','/')."</td>\n";
			$html .= "									</tr>\n";
 			$html .= "									<tr >\n";
			$html .= "										<td width=\"30%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SALARIO BASE</td>\n";
			$html .= "										<td colspan=\"3\" width=\"%\" >\n";
			$html .= "											<input type=\"text\" class=\"input-text\" name=\"salario_base\" style=\"width:30%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$afiliado['ingreso_mensual']."\">\n";
 			$valida .= "	sub_obliga[1][1] = new Array(objeto.salario_base.value,0,'SALARIO BASE','numeric');\n";

      $html .= "										</td>\n";		
			$html .= "										<td ></td>\n";		
			$html .= "									</tr>\n";
      $html .= "									<tr class=\"formulacion_table_list\">\n";
			$html .= "										<td colspan=\"6\">OBSERVACIONES</td>\n";
 			$valida .= "	sub_obliga[1][2] = new Array(objeto.observaciones.value,0,'OBSERVACIONES','text');\n";

			$html .= "									</tr>\n";
      $html .= "									<tr>\n";
      $html .= "										<td colspan=\"6\" width=\"%\" >\n";
			$html .= "											<textarea name=\"observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\"></textarea>\n";
			$html .= "										</td>\n";		
			$html .= "									</tr>\n";
			$html .= "								</table>\n";
			$html .= "							</div>\n";
      
      $html .= "							<div id=\"convenio\" style=\"display:$cvn\">\n";
			$html .= "								<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "									<tr >\n";
			$html .= "										<td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EMPRESA CONVENIO</td>\n";
			$html .= "										<td colspan=\"3\" width=\"%\" >\n";
      $html .= "									    <select name=\"empresa_convenio\" class=\"select\">\n";
			$html .= "											  <option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($convenio as $key => $detalle)
        $html .= "											  <option value=\"".$detalle['tipo_id_tercero']." ".$detalle['tercero_id']."\">".$detalle['nombre_tercero']."</option>\n";

      $html .= "										  </select>\n";
 			$valida .= "	sub_obliga[2][0] = new Array(objeto.empresa_convenio.value,1,'EMPRESA CONVENIO','select');\n";
      
      $html .= "										</td>\n";		
			$html .= "									</tr>\n";
      $html .= "									<tr >\n";
			$html .= "									  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA INICIO CONVENIO</td>\n";
			$html .= "									  <td >\n";
			$html .= "										  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio_convenio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
 			$valida .= "	sub_obliga[2][1] = new Array(objeto.fecha_inicio_convenio.value,1,'FECHA INICIO CONVENIO','date',0);\n";

      $html .= "									  </td>\n";
			$html .= "									  <td width=\"15%\">".ReturnOpenCalendario('registrar_afiliacion','fecha_inicio_convenio','/')."</td>\n";
			$html .= "									  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA FIN CONVENIO</td>\n";
			$html .= "									  <td >\n";
			$html .= "										  <input type=\"text\" class=\"input-text\" name=\"fecha_fin_convenio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
 			$valida .= "	sub_obliga[2][2] = new Array(objeto.fecha_fin_convenio.value,1,'FECHA FIN CONVENIO','date',0);\n";

      $html .= "									  </td>\n";
			$html .= "									  <td width=\"15%\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_fin_convenio','/')."</td>\n";

      $html .= "									</tr>\n";

			$html .= "								</table>\n";
			$html .= "							</div>\n";
      
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
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"ResetDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
      $html .= "	</div>\n";
			$html .= "</div>\n";
      
      $html .= "<div id='Actividad' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='actividad_titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">SELECCIONAR ACTIVIDAD ECONOMICA</div>\n";
			$html .= "	<div id='actividad_cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Actividad')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Actividad_Ocupacion' class='d2Content' style=\"background:#FEFEFE\"><br>\n";
			$html .= "		<center>\n";
			$html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
			$html .= "	  </center>\n";		
			$html .= "		<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIVISION</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"division_actividad\" class=\"select\" onchange=\"xajax_SeleccionarActividad(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			foreach($actividad as $key => $detalle)
      {
        ($key == $afiliado['ciiu_r3_division'])? $sl= "selected":$sl ="";
        $html .= "						<option value=\"".$key."\" $sl title=\"".$detalle['descripcion_ciiu_r3_division']."\">".substr($detalle['descripcion_ciiu_r3_division'],0,40)."</option>\n";
      }

      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >GRUPO</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"grupo_actividad\" class=\"select\" onchange=\"xajax_SeleccionarClase(xajax.getFormValues('registrar_afiliacion'));\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";      
      $html .= "			<tr >\n";
			$html .= "				<td width=\"40%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CLASE</td>\n";
			$html .= "				<td width=\"%\">\n";
      $html .= "					<select name=\"clase_actividad\" class=\"select\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
      $html .= "					</select>\n";			
      $html .= "				</td>\n";		
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
      $html .= "	</div>\n";
      $html .= "    <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosActividad(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"ResetDatosActividad(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
			$html .= "</div><br>\n";
			$html .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
      
			$html .= "  <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
      
      $html .= "<script>\n";
      $html .= $vec;
      $html .= "  function MostrarCapaEstamento(estamento_id)\n"; 
      $html .= "  {\n"; 
      $html .= "  	valor = vector_estamentos[estamento_id]\n"; 
      $html .= "    cp1 = 'pensionado'; cp2 = 'otro'; cp3 = 'convenio'; cp4='parentesco_pensionado'\n"; 
      $html .= "    if(valor == '-1')\n"; 
      $html .= "    {\n"; 
      $html .= "      OcultarSpan(cp2);OcultarSpan(cp1);OcultarSpan(cp3);\n"; 
      $html .= "    }\n"; 
      $html .= "    else if(valor == 'J' || valor == 'S')\n"; 
      $html .= "      {\n"; 
      $html .= "		    OcultarSpan(cp3);\n";
      $html .= "		    OcultarSpan(cp2);\n";
      $html .= "		    MostrarSpan(cp1);\n";
      $html .= "		    OcultarSpan(cp4);\n";
      $html .= "		    if(valor == 'S')\n";
      $html .= "        {  MostrarSpan(cp4);}\n";
      $html .= "      }\n";
      $html .= "      else if(valor == 'V')\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      OcultarSpan(cp2);\n";
      $html .= "		      MostrarSpan(cp3);\n";
      $html .= "		      OcultarSpan(cp4);\n";
      $html .= "        }\n";
      $html .= "      else\n"; 
      $html .= "        {\n"; 
      $html .= "		      OcultarSpan(cp3);\n";
      $html .= "		      OcultarSpan(cp1);\n";
      $html .= "		      MostrarSpan(cp2);\n";
      $html .= "		      OcultarSpan(cp4);\n";
      $html .= "        }\n";
      $html .= "  }\n";
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
			$html .= "		sub_obliga[2] = new Array();\n";
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
      
      $html .= "		var indice = 1;\n";
      $html .= "		var arreglo = new Array();\n";
      $html .= "		if(vector_estamentos[objeto.estamento.value] == 'J' || vector_estamentos[objeto.estamento.value] == 'S')\n";
      $html .= "    {  indice = '0';}\n";
      $html .= "		else if(vector_estamentos[objeto.estamento.value] == 'V') \n";
      $html .= "		{ \n";
      $html .= "      indice = 2;\n";

			$html .= "	    f = objeto.fecha_fin_convenio.value.split('/')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    f = objeto.fecha_inicio_convenio.value.split('/')\n";
			$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "	    if(f1 <= f2)\n";
			$html .= "	    {\n";
      $html .= "			  div_msj.innerHTML = 'LA FECHA DE INICIO DEL CONVENIO DEBE SER MAYOR A LA FECHA DE FIN DEL CONVENIO';\n";
			$html .= "	      return;\n";
			$html .= "	    }\n";
      $html .= "    }\n";
      $html .= "		else if(objeto.estamento.value == '-1') \n";
      $html .= "    {    indice = 3;}\n";
      $html .= "		arreglo = sub_obliga[indice];\n";
      $html .= "    if(vector_estamentos[objeto.estamento.value] != 'S') arreglo[2][1] = 0;\n ";
      $html .= "		for(i=0; i< arreglo.length ; i++)\n";
			$html .= "		{\n";
			$html .= "			if(arreglo[i][1] == '1')\n";
			$html .= "			{\n";
      $html .= "				switch(arreglo[i][3])\n";
			$html .= "				{\n";
			$html .= "				  case 'select':\n";
			$html .= "				    if(arreglo[i][0] == '-1')\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = 'SE DEBE SELECCIONAR '+arreglo[i][2]+'';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'date':\n";
			$html .= "				    if(!IsDate(arreglo[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIA O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'numeric':\n";
			$html .= "				    if(!IsNumeric(arreglo[i][0]))\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIO O EL FORMATO NO CORRESPONDE';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
      $html .= "				  case 'text':\n";
			$html .= "				    if(arreglo[i][0] == '' || arreglo[i][0] == undefined)\n";
			$html .= "				    {\n";
      $html .= "				      div_msj.innerHTML = arreglo[i][2]+', ES OBLIGATORIO';\n";
			$html .= "					    return;\n";
			$html .= "					  }\n";
			$html .= "				  break;\n";
			$html .= "				}\n";
			$html .= "			}\n";
			$html .= "		}\n";
      
			$html .= "	  var fecha_validacion = new Date('".date("Y/m/d")."');\n";
      $html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
      $html .= "			if(obligatorios[i][3] == 'date' && obligatorios[i][0] != '')\n";
			$html .= "			{\n";
			$html .= "				if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+',FORMATO NO CORRESPONDE';\n";
			$html .= "				  return;\n";
			$html .= "				}\n";
      $html .= "				if(obligatorios[i][4] == 1)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacion)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+',NO PUEDE SER SUPERIOR A: ".date("d/m/Y")."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";
			$html .= "			}\n";
      $html .= "			else if(obligatorios[i][3] =='numeric'&& obligatorios[i][0] != '')\n";
      $html .= "			{\n";
			$html .= "			  if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+', FORMATO NO CORRESPONDE';\n";
			$html .= "					return;\n";
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
			$html .= "		objeto.action = '".$action['crear']."';\n";
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
			$html .= "			//Compruebo si es un valor numï¿½ico\n";
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
      if($afiliado['ciiu_r3_division'] || $afiliado['ciuo_88_grupo_primario'])
      {
        $html .= "<script>\n";
        $html .= "  xajax_SeleccionarDatosDefecto(xajax.getFormValues('registrar_afiliacion'),'".$afiliado['ciiu_r3_clase']."','".$afiliado['ciuo_88_grupo_primario']."');\n";
        $html .= "</script>\n";
      }
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
		* Crea luna forma, donde se muestra la informacion basica de un 
    * cotizante, ofreciendo un link para el registro de beneficiarios
		*
		* @param array $action Vector de links de la aplicaion
		* @param array $afiliado datos del afiliado
		*
		* @return String
		*/
		function FormaInformacionCotizante($action,$afiliado)
		{ 
			$html .= ThemeAbrirTabla('INFORMACION DEL AFILIADO');
			$html .= "<table width=\"75%\" align=\"center\" >\n";
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
			$html .= "				</table>\n";
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
		* Crea una forma para hacer el registro de un beneficiario 
		*
		* @param array $action Vector de links de la aplicaion
		* @param array $request Vector de datos del request
		* @param array $tipo_afiliacion Vector de ripos de documentos
		* @param array $eps Vector con los datos de las eps parametrizadas
    * @param array $ocupacion Vector con los datos de la ocupacion (grupo principal)
    * @param array $afiliado Vector con los datos del afiliado
    * @param array $tipos_documento Vector con los tipos de documentos 
    * @param array $parentesco vector con los datos de los tipos de parentesco
    * @param array $puntos vector con los datos de los puntos
    *
		* @return String
		*/
		function FormaRegistrarAfiliacionBeneficiario($action,$request,$tipo_afiliacion,$eps,$ocupacion,$afiliado,$tipos_documento,$parentesco,$puntos)
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
      $html .= "    if(objeto.grandes_grupos.value != \"-1\")\n";
      $html .= "    {\n"; 
      $html .= "      if(objeto.sub_grupos_principales.value == \"-1\")\n";
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
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = objeto.grupos_primarios.options[objeto.grupos_primarios.selectedIndex].title;\n"; 
      $html .= "    }\n"; 
      $html .= "    document.getElementById(\"error_ocupacion\").innerHTML = \"\";\n";
      $html .= "    if(objeto.grandes_grupos.value == \"-1\")\n";
      $html .= "      document.getElementById(\"ocupacion_texto\").innerHTML = '';\n"; 
      $html .= "    OcultarSpan('Ocupacion');\n"; 
      $html .= "  }\n";   
      $html .= "  function ResetDatosOcupacion(objeto)\n"; 
      $html .= "  {\n"; 
      $html .= "    if(document.getElementById(\"ocupacion_texto\").innerHTML == '')\n";
      $html .= "    {\n";
      $html .= "      objeto.grandes_grupos.selectedIndex = 0;\n";
      $html .= "      objeto.sub_grupo.selectedIndex = 0;\n";
      $html .= "      objeto.grupos_primarios.selectedIndex = 0;\n";
      $html .= "    }\n";
      $html .= "    OcultarSpan('Ocupacion');\n"; 
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
      $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE ATENCION</td>\n";
			$html .= "									<td colspan=\"4\">\n";
      $html .= "                    ".$afiliado['plan_descripcion']." ";
			$html .= "										<input type=\"hidden\" name=\"plan_atencion\" value=\"".$afiliado['plan_atencion']."\">\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO AFILIADO PLAN</td>\n";
      $html .= "								  <td width=\"25%\" colspan=\"2\">\n";
      $html .= "                    <div id=\"tipo_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"tipo_afiliado_plan\" value=\"".$afiliado['tipo_afiliado_plan']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								  <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >RANGO</td>\n";
      $html .= "								  <td width=\"25%\">\n";
      $html .= "                    <div id=\"rango_afiliado_div\">\n";
      $html .= "			                <input type=\"hidden\" name=\"rango_afiliado_plan\" value=\"".$afiliado['rango_afiliado_plan']."\">\n";
      $html .= "                    </div>\n";
      $html .= "                  </td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA VENCIMIENTO AFILIACION</td>\n";
			$html .= "									<td align=\"right\" width=\"10%\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\" style=\"width:92%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_vencimiento']."\">\n";
			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_vencimiento.value,0,'FECHA VENCIMIENTO AFILIACION','date',2);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" colspan=\"3\">".ReturnOpenCalendario('registrar_afiliacion','fecha_vencimiento','/')."</td>\n";
			$html .= "								</tr>\n";
      $html .= "							</table>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.plan_atencion.value,1,'PLAN DE ATENCIÓN','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_afiliado_plan.value,1,'TIPO DE AFILIADO PLAN','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.rango_afiliado_plan.value,1,'RANGO','text');\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">TIPO DE IDENTIFICACION</td>\n";
			$html .= "									<td colspan=\"3\">\n";
      $html .= "                    ".$afiliado['tipo_id_beneficiario']." ".$afiliado['documento']."\n";
      $html .= "                    <input type=\"hidden\" name=\"tipo_id_beneficiario\" value=\"".$afiliado['tipo_id_beneficiario']."\">\n";
			$html .= "										<input type=\"hidden\" name=\"documento\" value=\"".$afiliado['documento']."\" >\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";			

 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.tipo_id_beneficiario.value,1,'TIPO DE IDENTIFICACION','text');\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.documento.value,1,'N IDENTIFICACION','text');\n";
      
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
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundoapellido\" value=\"".$afiliado['segundoapellido']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundoapellido.value,0,'SEGUNDO APELLIDO','text');\n";

      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"20\" name=\"primernombre\" value=\"".$afiliado['primernombre']."\" class=\"input-text\" size=\"20\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.primernombre.value,1,'PRIMER NOMBRE','text');\n";
			
      $html .= "									</td>\n";
			$html .= "									<td>\n";
			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"30\" name=\"segundonombre\" value=\"".$afiliado['segundonombre']."\" class=\"input-text\" size=\"30\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.segundonombre.value,0,'SEGUNDO NOMBRE','text');\n";

			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FECHA NACIMIENTO</td>\n";
			$html .= "									<td >\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_nacimiento\" size=\"11\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_nacimiento']."\">\n";
			$html .= "										".ReturnOpenCalendario('registrar_afiliacion','fecha_nacimiento','/')."</td>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_nacimiento.value,1,'FECHA NACIMIENTO','date',1);\n";

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
 			$html .= "										<input type=\"text\" style=\"width:90%\" maxlength=\"100\" name=\"direccion_residencia\" value=\"".$afiliado['direccion_residencia']."\" class=\"input-text\">\n";
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
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_sgss.value,0,'FECHA DE AFILIACION AL SISTEMA GENERAL DE SEGURIDAD SOCIAL','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td align=\"left\" colspan=\"3\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_sgss','/')."</td>\n";
			$html .= "								</tr>\n";

			$html .= "								<tr >\n";
			$html .= "									<td width=\"60%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE AFILIACION AL SERVICIO DE SALUD</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">\n";
			$html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_afiliacion_empresa\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$afiliado['fecha_afiliacion_empresa']."\">\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion_empresa.value,1,'FECHA DE AFILIACION AL SERVICIO DE SALUD','date',1);\n";

      $html .= "									</td>\n";
			$html .= "									<td width=\"15%\" align=\"left\" colspan=\"3\">".ReturnOpenCalendario('registrar_afiliacion','fecha_afiliacion_empresa','/')."</td>\n";		
			$html .= "								</tr>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OCUPACION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
 			$html .= "				            <a title=\"SELECCIONAR OCUPACION\" href=\"javascript:IniciarVentanaOcupacion('Ocupacion','Contenido_Ocupacion','ocupacion_titulo','ocupacion_cerrar',400,180)\"\">\n";
			$html .= "				              <img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"-1\" width=\"16\" height=\"16\">\n";
			$html .= "				            </a>\n";
			$html .= "				            <label id=\"ocupacion_texto\">".$afiliado['ocupacion_hd']."</label>\n";

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
      $html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION</td>\n";
			$html .= "									<td colspan=\"5\">\n";
			$html .= "										<select name=\"puntos_atencion\" class=\"select\">\n";
			$html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($puntos as $key => $dtl)
      {
				($afiliado['puntos_atencion'] == $dtl['eps_punto_atencion_id'])? $s1 = "selected": $s1 = ""; 
        $html .= "											<option value=\"".$dtl['eps_punto_atencion_id']."\" $s1>".$dtl['eps_punto_atencion_nombre']."</option>\n";
			}
			$html .= "										</select>\n";
			$html .= "									</td>\n";
      $html .= "								</tr>\n";
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.puntos_atencion.value,1,'PUNTO DE ATENCION','select');\n";

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
 			$valida .= "	obligatorios[".($i++)."] = new Array(objeto.fecha_afiliacion.value,0,'FECHA DE AFILIACION','date',1);\n";

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
      
      $html .= "			    <tr>\n";
			$html .= "			      <td>\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
      $html .= "				        <tr class=\"formulacion_table_list\">\n";
			$html .= "								  <td colspan=\"6\">OBSERVACIONES</td>\n";
 			$valida .= "	sub_obliga[1][2] = new Array(objeto.observaciones.value,0,'OBSERVACIONES','text');\n";

			$html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "								  <td colspan=\"6\" width=\"%\" >\n";
			$html .= "									  <textarea name=\"observaciones\" style=\"width:100%\" rows=\"2\" class=\"textarea\">".$afiliado['observaciones']."</textarea>\n";
			$html .= "									</td>\n";		
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "			      </td>\n";
			$html .= "			    </tr>\n";
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
      $html .= "    <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	    <tr>\n";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
      $html .= "		    <td align=\"center\">\n";
      $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cancelar\" onclick=\"ResetDatosOcupacion(document.registrar_afiliacion)\">\n";
			$html .= "		    </td>";
			$html .= "	    </tr>";
			$html .= "    </table>";
      $html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<input type=\"hidden\" name=\"ocupacion_hd\" value=\"\">\n";
			$html .= "<input type=\"hidden\" name=\"grupo_primario_hd\" value=\"".$afiliado['grupo_primario_hd']."\">\n";
			$html .= "<input type=\"hidden\" name=\"ubicacion_hd\" value=\"\" >\n";
			if($afiliado['actualizar'])
        $html .= "<input type=\"hidden\" name=\"actualizar\" value=\"".$afiliado['actualizar']."\" >\n";
        
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
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Adicionar\">\n";
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
      
      $fII = explode("/",$afiliado['fecha_vencimiento_ctz']);
      $html .= "	  var fecha_validacion = new Date('".date("Y/m/d")."');\n";
      $html .= "	  var fecha_validacionII = new Date('".$afiliado['fecha_vencimiento_ctz']."');\n";
      $html .= "		for(i=0; i< $i; i++)\n";
			$html .= "		{\n";
      $html .= "			if(obligatorios[i][3] == 'date' && obligatorios[i][0] != '')\n";
			$html .= "			{\n";
			$html .= "				if(!IsDate(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+',FORMATO NO CORRESPONDE';\n";
			$html .= "				  return;\n";
			$html .= "				}\n";
      $html .= "				if(obligatorios[i][4] == 1)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacion)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+', NO PUEDE SER SUPERIOR A: ".date("d/m/Y")."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";      
      $html .= "				if(obligatorios[i][4] == 2)\n";
			$html .= "				{\n";
      $html .= "	        f = obligatorios[i][0].split('/')\n";
			$html .= "	        f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "          if(f1 > fecha_validacionII)\n";
      $html .= "				  {\n";
      $html .= "				    div_msj.innerHTML = obligatorios[i][2]+', NO PUEDE SER SUPERIOR A: ".$fII[2]."/".$fII[1]."/".$fII[0]."';\n";
			$html .= "				    return;\n";
			$html .= "				  }\n";
			$html .= "				}\n";
			$html .= "			}\n";
      $html .= "			else if(obligatorios[i][3] =='numeric'&& obligatorios[i][0] != '')\n";
      $html .= "			{\n";
			$html .= "			  if(!IsNumeric(obligatorios[i][0]))\n";
			$html .= "				{\n";
      $html .= "				  div_msj.innerHTML = obligatorios[i][2]+', FORMATO NO CORRESPONDE';\n";
			$html .= "					return;\n";
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
			$html .= "		xajax_AdicionarBeneficiario(xajax.getFormValues('registrar_afiliacion'));\n";
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
			$html .= "			//Compruebo si es un valor numï¿½ico\n";
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
			$html .= "<script>\n";
      $html .= "  xajax_MostrarInformacionPlan(xajax.getFormValues('registrar_afiliacion'))\n";
			$html .= "</script>\n";
			$html .= ThemeCerrarTabla();	
			return $html;
		}
    /**
    * Funcion donde se crea la tabla de la informacion de los beneficiarios
    *
    * @param array $beneficiarios Datos de los beneficiarios
    *
    * @return String
    */
    function FormaCapaBeneficiarios($beneficiarios)
    {
      $html = "";
      if(!empty($beneficiarios))
      {
        $html .= "<center>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td>IDENTIFICACION</td>\n";
        $html .= "      <td>NOMBRE BENEFICIARIO</td>\n";
        $html .= "      <td>FECHA NACIMIENTO</td>\n";
        $html .= "      <td>SEXO</td>\n";
        $html .= "      <td>PARENTESCO</td>\n";
        $html .= "      <td colspan=\"2\"></td>\n";
        $html .= "    </tr>\n";
        
        foreach($beneficiarios as $key => $tipo_id)
        {
          foreach($tipo_id as $keyI => $detalle)
          {
            $html .= "    <tr class=\"modulo_list_claro\">\n";
            $html .= "      <td>".$key." ".$keyI."</td>\n";
            $html .= "      <td>".strtoupper($detalle['primerapellido']." ".$detalle['segundoapellido']." ".$detalle['primernombre']." ".$detalle['segundonombre'])."</td>\n";
            $html .= "      <td>".$detalle['fecha_nacimiento']." </td>\n";
            $html .= "      <td>".$detalle['tipo_sexo']."</td>\n";
            $html .= "      <td>".$detalle['parentesco_texto']."</td>\n";
            $html .= "      <td>\n";
            $html .= "				<a title=\"MODIFICAR BENEFICIARIO\" href=\"javascript:llamarBeneficiarios('".$key."','".$keyI."')\"\">\n";
            $html .= "				  <img src=\"".GetThemePath()."/images/editar.png\" border=\"0\" width=\"16\" height=\"16\">\n";
            $html .= "		    </a>\n";
            $html .= "      </td>\n";
            $html .= "      <td>\n";
            $html .= "				<a title=\"ELIMINAR BENEFICIARIO\" href=\"javascript:eliminarBeneficiarios('".$key."','".$keyI."')\"\">\n";
            $html .= "				  <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\" width=\"16\" height=\"16\">\n";
            $html .= "		    </a>\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
          }
        }

        $html .= "  </table>\n";
        $html .= "</center>\n";
      }
      return $html;
    }
    /**
    * Funcion que permite crear una forma para el cambio de la 
    * fecha de finalizacion del convenio
    *
    * @param array $action Links de la forma
    * @param array $datos Datos del convenio
    *
    * @return String
    */
    function FormaModificarFechasConvenio($action,$datos)
    {
      $html  = "<script>\n";
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
			$html .= "			//Compruebo si es un valor numï¿½ico\n";
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
      $html .= "  function ValidarDatos(objeto)\n";
      $html .= "  {\n";
      $html .= "    div_msj = document.getElementById('error');\n";
      $html .= "		if(!IsDate(objeto.fecha_vencimiento_convenio.value))\n";
			$html .= "		{\n";
      $html .= "			div_msj.innerHTML = 'LA FECHA DE FIN DE CONVENIO NO PUEDE SER VACIA O POSEE UN FORMATO INCORRECTO';\n";
			$html .= "			return;\n";
			$html .= "	  }\n";
			$html .= "	  else\n";
			$html .= "	  {\n";
			$html .= "	    f = objeto.fecha_vencimiento_convenio.value.split('/')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $fecha = explode("/",$datos['fecha_vencimiento_convenio']);
			$html .= "	    f2 = new Date('".date("Y/m/d")."');\n";
      $html .= "	    if(f1 < f2)\n";
			$html .= "	    {\n";
      $html .= "			  div_msj.innerHTML = 'LA FECHA DEBE SER MAYOR O IGUAL A LA FECHA ACTUAL';\n";
			$html .= "	      return;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
			$html .= "    objeto.action = '".$action['aceptar']."';\n"; 
			$html .= "    objeto.submit();\n"; 
			$html .= "	}\n";
			$html .= "</script>\n";
    	$html .= ThemeAbrirTabla('AMPLIACION DE PERIODO DE COVERTURA');
			$html .= "<form name=\"ampliacion_periodo\" id=\"ampliacion_periodo\" action=\"javascript:ValidarDatos(document.ampliacion_periodo)\" method=\"post\">\n";
			$html .= "  <table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td colspan=\"2\" class=\"formulacion_table_list\">COTIZANTE</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"label\" align=\"center\">\n";
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id']."\n";          
			$html .= "			</td>\n";          
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".trim($datos['primer_apellido']." ".$datos['segundo_apellido']." ".$datos['primer_nombre']." ".$datos['segundo_nombre'])."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr>\n";
			$html .= "			<td colspan=\"2\" class=\"formulacion_table_list\">EMPRESA CONVENIO</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"label\" align=\"center\">\n";
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['convenio_tipo_id_tercero']." ".$datos['convenio_tercero_id']."\n";          
			$html .= "			</td>\n";          
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['nombre_tercero']."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"label\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">FECHA INICIO CONVENIO</td>\n";
      $html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  ".$datos['fecha_inicio_convenio']."\n";          
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"label\" >\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\"  class=\"formulacion_table_list\" >FECHA FIN CONVENIO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento_convenio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$datos['fecha_vencimiento_convenio']."\">\n";
			$html .= "				".ReturnOpenCalendario('ampliacion_periodo','fecha_vencimiento_convenio','/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <center>\n";
      $html .= "    <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "  <table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
      $html .= "		  <td align=\"center\"><br>\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		  </td>";
      $html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
  }
?>