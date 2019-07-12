<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: EstudiantesCertificadosHTML.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: EstudiantesCertificadosHTML
  * Clase donde se crean las formas para los movimientos hechos sobre los periodos 
  * de cobertura de los afiliados
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class EstudiantesCertificadosHTML
  {
    /**
    * Constructor de la clase
    */
    function EstudiantesCertificadosHTML(){}
    /**
		* Forma para relizar la busqueda de los afiliados a los
    * que se puede registrar un periodo de cobertura
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $tipos_documento Vector de ripos de documentos
		*
		* @return String
		*/
		function FormaBuscarAfiliado($action,$tipos_documento)
		{
			$html  = ThemeAbrirTabla('PERIODOS DE COBERTURA - BUSCAR AFILIADO');
			$html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.afiliado_tipo_id.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.afiliado_id.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_BuscarAfiliadoPeriodoCobertura(xajax.getFormValues('registrar_afiliacion'));\n";
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
			$html .= "				<select name=\"afiliado_tipo_id\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipos_documento as $key => $datos)
				$html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO: </td>\n";
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
    * @param array $action Links de la forma
    * @param array $datos Vector con los datos del afiliado
    * @param array $periodos Vector con los perios de cobertura, si los hay
    * @param array $ultimo_periodo Vector con los datos del ultimo periodo de cobertura
    *              registrado, si loo hay
    * @param array $cotizante Vector con los datos del cotizante del cual es beneficiario el
    *              afiliado, si lo hay
    *
    * @return String
    */
    function FormaModificarFechasConvenio($action,$datos,$periodos,$ultimo_periodo,$cotizante)
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
      $html .= "  function incluirFechas(valor,objeto)\n";
      $html .= "  {\n";
      $html .= "    objeto.fecha_inicio.value = valor.split('_')[0];\n";
      $html .= "    objeto.fecha_fin.value = valor.split('_')[1];\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos(objeto)\n";
      $html .= "  {\n";
      $html .= "    div_msj = document.getElementById('error');\n";
      $html .= "    if(objeto.institucion.value == '')\n";
      $html .= "    {\n";
      $html .= "      div_msj.innerHTML = 'EL NOMBRE DE LA INSTITUCION ES OBLIGATORIA';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(objeto.fecha_inicio.value) || !IsDate(objeto.fecha_fin.value))\n";
      $html .= "    {\n";
      $html .= "      div_msj.innerHTML = 'LAS FECHAS NO DEBEN SER NULAS O NO POSEEN UN FORMATO VALIDO';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    f = objeto.fecha_inicio.value.split('/');\n";
      $html .= "    f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "    f = objeto.fecha_fin.value.split('/');\n";
      $html .= "    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "    if(f1 > f2)\n";
      $html .= "    {\n";
      $html .= "      div_msj.innerHTML = 'LA FECHA DE INICIO DEL PERIODO DE COBERTURA DEBE SER MAYOR A LA FECHA DE FINALIZACION DE LA COBERTURA';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    f3 = new Date('".date('Y/m/d')."');\n";
      $html .= "    if(f2 < f3)\n";
      $html .= "    {\n alert(f2+'  '+f3);";
      $html .= "      div_msj.innerHTML = 'LA FECHA DE FINALIZACION DE LA COBERTURA DEBE SER MAYOR A LA FECHA ACTUAL';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
			$html .= "    objeto.action = '".$action['aceptar']."';\n"; 
			$html .= "    objeto.submit();\n"; 
			$html .= "	}\n";
			$html .= "</script>\n";
    	$html .= ThemeAbrirTabla('REGISTRO PERIODOS DE COBERTURA');
			$html .= "<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"label\" >\n";
      $html .= "		<td width=\"40%\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">\n";
			$html .= "		  AFILIADO: \n";          
			$html .= "		</td>\n";          
      $html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "		  ".$datos['afiliado_tipo_id']." ".$datos['afiliado_id']." ".trim($datos['apellido']." ".$datos['nombre'])."\n";          
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\">\n";
      $html .= "		<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">\n";
			$html .= "		  EDAD\n";          
			$html .= "		</td>\n";          
      $html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "		  ".$datos['edad']." AÑOS\n";          
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr class=\"label\">\n";
			$html .= "		<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">ESTADO</td>\n";
      $html .= "		<td class=\"modulo_list_claro\">\n";
			$html .= "		  ".$datos['descripcion_estado']." - ".$datos['descripcion_subestado']."\n";          
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			if(!empty($cotizante))
      {
        $html .= "	<tr class=\"label\">\n";
        $html .= "		<td width=\"30%\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">\n";
  			$html .= "		  BENEFICIARIO DE: \n";          
  			$html .= "		</td>\n";          
        $html .= "		<td class=\"modulo_list_claro\">\n";
  			$html .= "		  ".$cotizante['afiliado_tipo_id']." ".$cotizante['afiliado_id']."\n";
        $html .= "      ".trim($cotizante['primer_apellido']." ".$cotizante['segundo_apellido']." ".$cotizante['primer_nombre']." ".$cotizante['segundo_nombre'])."\n";          
  			$html .= "		</td>\n";
        $html .= "	</tr>\n";
      }
      if(!empty($ultimo_periodo))
      {
        $html .= "	<tr class=\"label\">\n";
        $html .= "		<td width=\"30%\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">\n";
  		$html .= "		  ULTIMO PERIODO DE COBERTURA: \n";          
  		$html .= "		</td>\n";          
        $html .= "		<td class=\"modulo_list_claro\">\n";
  		$html .= "		  DE ".$ultimo_periodo['inicio']." A ".$ultimo_periodo['fin']."\n";
  		$html .= "		</td>\n";
        $html .= "	</tr>\n";
		$html .= "	<tr class=\"label\">\n";
        $html .= "		<td width=\"30%\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">\n";
  		$html .= "		  INSTITUCION: \n";          
  		$html .= "		</td>\n";          
        $html .= "		<td class=\"modulo_list_claro\">\n";
  		$html .= "		  ".$ultimo_periodo['institucion']."\n";
  		$html .= "		</td>\n";
        $html .= "	</tr>\n";
      }
      
      $html .= "</table><br>\n";
			$html .= "<form name=\"ampliacion_periodo\" id=\"ampliacion_periodo\" action=\"javascript:ValidarDatos(document.ampliacion_periodo)\" method=\"post\">\n";
			$html .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td width=\"40%\" style=\"text-align:left;text-indent:8pt\" >INSTITUCION</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"institucion\" maxlength=\"100\" style=\"width:90%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      if(!empty($periodos))
      {
        $html .= "		<tr class=\"modulo_table_list_title\" >\n";
  			$html .= "			<td width=\"30%\" style=\"text-align:left;text-indent:8pt\" >PERIODOS DE COBERTURA</td>\n";
  			$html .= "			<td class=\"modulo_list_claro\" align=\"left\">\n";
  			$html .= "			  <select name=\"periodo\" class=\"select\" onchange=\"incluirFechas(this.value,document.ampliacion_periodo)\">\n";
  			$html .= "					<option value=\"-1\">-SELECCIONAR-</option>\n";
  			foreach($periodos as $key => $dtl)
          $html .= "					<option value=\"".$dtl['inicio']."_".$dtl['fin']."\" title=\"".$dtl['periodo_descripcion']."\">".substr($dtl['periodo_descripcion'],0,40)."</option>\n";

        $html .= "				</select>\n";			
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      
      $html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td width=\"30%\" style=\"text-align:left;text-indent:8pt\" >FECHA INICIO</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$html .= "				".ReturnOpenCalendario('ampliacion_periodo','fecha_inicio','/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td width=\"30%\" style=\"text-align:left;text-indent:8pt\" >FECHA FINALIZACION</td>\n";
			$html .= "			<td class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
			$html .= "				".ReturnOpenCalendario('ampliacion_periodo','fecha_fin','/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\" >\n";
      $html .= "      <td colspan=\"2\">OBSERVACION</td>\n";
      $html .= "    </tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\" >\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <textarea name=\"observacion\" class=\"textarea\" rows=\"3\" style=\"width:100%\"></textarea>\n";
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
    /**
    * Funcion donde se crea la forma donde se muestra el historial de cambios
    * en las fechas de convenios
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $datos Vector con la lista de los periodos de cobertura
    * @param int   $conteo Cantidad de total de registro de datos
    * @param int   $pagina Numero de pagina en que se esta mostrando en pantalla
    * @param array $request Vector con los datos del request    
    * @param array $tipos_doc Vector con los tipos de identificacion    
    *
    * @return String $html
    */
    function FormaListaPeriodosCobertura($action,$datos,$conteo,$pagina,$request,$tipos_doc)
    {
      $html .= ThemeAbrirTabla('LISTA PERIODOS COBERTURA');
      $html .= "<script>\n";
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			//$html .= "	  document.getElementById('capaFondo1').style.visibility = 'visible';\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
      $html .= "</script>\n";
      $html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "		<tr>\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" class=\"modulo_table_list_title\">\n";
      $html .= "        TIPO DE IDENTIFICACION\n";
      $html .= "      </td>\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "		    <select name=\"buscador[afiliado_tipo_id]\" class=\"select\">\n";
			$html .= "				  <option value=\"-1\">---Seleccionar---</option>\n";
			
			$s = "";
      foreach($tipos_doc as $key => $dtl)
      {
				($key == $request['buscador']['afiliado_tipo_id'])? $s = "selected": $s = "";
        $html .= "				  <option value=\"".$dtl['tipo_id_paciente']."\" $s>".$dtl['descripcion']."</option>\n";
      }
			$html .= "		    </select>\n";      
			$html .= "			</td>\n";
      $html .= "    </tr>\n";
      $html .= "		<tr>\n";
      $html .= "			<td style=\"text-align:left;text-indent:8pt\" class=\"modulo_table_list_title\">N IDENTIFICACION</td>\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "				<input type=\"text\" style=\"width:70%\" maxlength=\"20\" name=\"buscador[afiliado_id]\" value=\"".$request['buscador']['afiliado_id']."\" class=\"input-text\" size=\"32\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";			
      $html .= "    <tr>\n";
      $html .= "		  <td colspan=\"3\" align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Buscar\">";
			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "		  </td>";
			$html .= "		</tr>";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      if(!empty($datos))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend>REGISTROS</legend>\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td width=\"33%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"16%\" colspan=\"2\">PERIODO COBERTURS</td>\n";
				$html .= "			<td width=\"34%\" >OBSERVACION</td>\n";
				$html .= "			<td width=\"16%\">USUARIO REGISTRO</td>\n";
				$html .= "			<td width=\"%\"></td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($datos as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td width=\"10%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos']." ".$afiliado['nombres']."</td>\n";
					$html .= "		  <td width=\"8\" align=\"center\">".$afiliado['inicio']."</td>\n";
					$html .= "		  <td width=\"8\" align=\"center\">".$afiliado['fin']."</td>\n";
					$html .= "		  <td >".$afiliado['observaciones']."</td>\n";
					$html .= "		  <td >".$afiliado['nombre']."</td>\n";
          $html .= "		  <td align=\"center\">\n";
          $html .= "	      <a class=\"label_error\" title=\"ANULAR PERIODO\" href=\"".$action['anular'].URLRequest(array("eps_afiliados_atencion_estudiante_id"=>$afiliado['eps_afiliados_atencion_estudiante_id']))."\" title=\"CERRAR NOTA DE AJUSTE\" onclick=\"return confirm('ESTA SEGURO QUE DESEA ANULAR EL PERIODO DE COBERTURA PERTENECIENTE A ".$afiliado['apellidos']." ".$afiliado['nombres']." ?');\">\n";
					$html .= "		      <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
					$html .= "	      </a>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "	  </table>\n";
				$html .= "</fieldset><br>\n";
        
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
      else
      {
        $html .= "  <center>\n";
        $html .= "    <label class=\"label_error\">NO HAY HISTORICO DE CAMBIOS EN LAS FECHAS DE CONVENIO</label>\n";
        $html .= "  </center>\n";
      }
			$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }
?>