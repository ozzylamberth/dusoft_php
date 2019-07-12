<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: NovedadesHTML.class.php,v 1.2 2009/10/05 18:27:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: NovedadesHTML
  * Clase encargada de crear las formas para el registro de novedades
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class NovedadesHTML
  {
    /**
    * Constructor de la clase
    */
    function NovedadesHTML(){}
    /**
		* Funcion donde se crea la forma para ingresar los archivos pila de salud
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaCargarArchivosSalud($action)
		{
			$html  = ThemeAbrirTabla('INGRESAR P.I.L.A.');
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      $html .= "    archivo1 = forma.archivo_encabezado.value.split('\\\');\n";
      $html .= "    archivo2 = forma.archivo_novedades.value.split('\\\');\n";
      $html .= "    nombre_archivo1 = archivo1[archivo1.length-1].split('.');\n";
      $html .= "    nombre_archivo2 = archivo2[archivo2.length-1].split('.');\n";
      $html .= "    if(archivo1 == '' || archivo2 == '')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'EL INGRESO DE LOS ARCHIVOS SOLICITADOS ES OBLIGATORIO'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(nombre_archivo1[1] != 'txt' && nombre_archivo1[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'LA EXTENSION DEL ARCHIVO DE ENCABEZADO NO ES VALIDA, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    else if(nombre_archivo2[1] != 'txt' && nombre_archivo2[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'LA EXTENSION DEL ARCHIVO LIQUIDACION DETALLADA DE APORTES NO ES VALIDO, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"50%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        ARCHIVO DE ENCABEZADO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_encabezado\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        ARCHIVO LIQUIDACION DETALLADA DE APORTES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_novedades\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table width=\"50%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "			  </form>";
      $html .= "      </td>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "		    <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			  </form>";
			$html .= "		  </td>";
			$html .= "	  </tr>";
			$html .= "  </table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
    * Funcion donde se crea la forma donde se muestra la lista de novedades no procesadas
    * de los archivos del pila
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $datos Vector con los datos de las novedades no procesadas
    * @param int $conteo Cantidad de total de registro de datos
    * @param int $pagina Numero de pagina en que se esta mostrando en pantalla
    * @param array $request Vector con los datos del request
    *
    * @return String $html
    */
    function FormaListaNovedadesNOProcesadasPILA($action,$datos,$conteo,$pagina,$request)
    {
      $html .= ThemeAbrirTabla('LISTADO DE REGISTROS NO INTERFAZADOS');
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
			$html .= "</script>\n";
			$html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_list_title\">FECHA REGISTRO</td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" name=\"fecha_buscador\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_buscador']."\">\n";
      $html .= "      </td>\n";
 			$html .= "		  <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_buscador','/')."</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "		  <td colspan=\"3\" align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Buscar\">";
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
				$html .= "			<td width=\"12%\">PERIODO</td>\n";
				$html .= "			<td width=\"12%\">FECHA REGISTRO</td>\n";
				$html .= "		  <td width=\"46%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"15%\">DIAS COTIZADOS</td>\n";
				$html .= "			<td width=\"15%\">INGRESO BASE</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($datos as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td align=\"center\">".$afiliado['periodo_pago']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_registro']."</td>\n";
					$html .= "		  <td width=\"12%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos']." ".$afiliado['nombres']."</td>\n";
					$html .= "		  <td >".$afiliado['dias_cotizados']."</td>\n";
					$html .= "		  <td align=\"right\">".formatoValor($afiliado['ingreso_base_cotizacion'])."</td>\n";
          $html .= "		  </td>\n";
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
        $html .= "    <label class=\"label_error\">NO EXISTEN NOVEDADES NO PROCESADAS</label>\n";
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
    /**
		* Funcion donde se crea la forma para ingresar los archivos pila de pension
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaCargarArchivosPension($action)
		{
			$html  = ThemeAbrirTabla('INGRESAR ARCHIVOS DE PENSIONADOS');
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      $html .= "    archivo1 = forma.archivo_encabezado.value.split('\\\');\n";
      $html .= "    archivo2 = forma.archivo_novedades.value.split('\\\');\n";
      $html .= "    nombre_archivo1 = archivo1[archivo1.length-1].split('.');\n";
      $html .= "    nombre_archivo2 = archivo2[archivo2.length-1].split('.');\n";
      $html .= "    if(archivo1 == '' || archivo2 == '')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'EL INGRESO DE LOS ARCHIVOS SOLICITADOS ES OBLIGATORIO'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(nombre_archivo1[1] != 'txt' && nombre_archivo1[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'LA EXTENSION DEL ARCHIVO DE ENCABEZADO NO ES VALIDA, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    else if(nombre_archivo2[1] != 'txt' && nombre_archivo2[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'LA EXTENSION DEL ARCHIVO LIQUIDACION DETALLADA DE APORTES NO ES VALIDO, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"50%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        ARCHIVO DE ENCABEZADO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_encabezado\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        ARCHIVO LIQUIDACION DETALLADA DE APORTES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_novedades\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table width=\"50%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "			  </form>";
      $html .= "      </td>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "		    <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			  </form>";
			$html .= "		  </td>";
			$html .= "	  </tr>";
			$html .= "  </table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
		* Funcion donde se crea la forma para hacer el ingreso de archivo de 
    * novedades
		*
		* @param array $action Vector que continen los link de la aplicacion
    *
		* @return string
		*/
		function FormaCargarArchivosNovedades($action)
		{
			$html  = ThemeAbrirTabla('INGRESAR ARCHIVO DE NOVEDADES');
      $html .= "<script>\n";
      $html .= "  function Eval(forma)\n";
      $html .= "  {\n";
      $html .= "    errorMsg = document.getElementById('error');\n";
      $html .= "    archivo1 = forma.archivo_novedades.value.split('\\\');\n";
      $html .= "    nombre_archivo1 = archivo1[archivo1.length-1].split('.');\n";
      $html .= "    if(archivo1 == '')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'EL INGRESO DE LOS ARCHIVOS SOLICITADOS ES OBLIGATORIO'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(nombre_archivo1[1] != 'txt' && nombre_archivo1[1] != 'TXT')\n";
      $html .= "    {\n";
      $html .= "      errorMsg.innerHTML = 'LA EXTENSION DEL ARCHIVO DE NOVEDADES NO ES VALIDA, EL ARCHIVO DEBE TENER EXTENSION .txt'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    forma.action = \"".$action['aceptar']."\";\n";
      $html .= "    forma.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<center>\n";
      $html .= "  <div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir)\" method = \"post\">\n";
      $html .= "  <table width=\"50%\" class=\"modulo_table_list\" align=\"center\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        ARCHIVO DE ENCABEZADO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <input type=\"file\" name=\"archivo_novedades\" id=\"archivo_control\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table width=\"50%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Aceptar\">\n";
      $html .= "			  </form>";
      $html .= "      </td>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "		    <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			  </form>";
			$html .= "		  </td>";
			$html .= "	  </tr>";
			$html .= "  </table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
    * Funcion donde se crea la forma donde se muestra la lista de novedades no procesadas
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $datos Vector con los datos de las novedades no procesadas
    * @param int $conteo Cantidad de total de registro de datos
    * @param int $pagina Numero de pagina en que se esta mostrando en pantalla
    * @param array $request Vector con los datos del request    
    *
    * @return String $html
    */
    function FormaListaNovedadesNOProcesadas($action,$datos,$conteo,$pagina,$request)
    {
      $html .= ThemeAbrirTabla('LISTADO DE NOVEDADES NO INTERFAZADAS');
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
      $html .= "</script>\n";
      $html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_list_title\">FECHA REGISTRO</td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" name=\"fecha_buscador\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_buscador']."\">\n";
      $html .= "      </td>\n";
 			$html .= "		  <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_buscador','/')."</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "		  <td colspan=\"3\" align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Buscar\">";
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
				$html .= "			<td width=\"5%\">ID</td>\n";
				$html .= "			<td width=\"10%\">FECHA REGISTRO</td>\n";
				$html .= "			<td width=\"10%\">FECHA NOVEDAD</td>\n";
				$html .= "		  <td width=\"40%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"10%\">CODIGO NOVEDAD</td>\n";
				$html .= "			<td width=\"25%\">USUARIO REGISTRO</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($datos as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td >".$afiliado['eps_novedad_ingreso_id']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_registro']."</td>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_novedad']."</td>\n";
					$html .= "		  <td width=\"12%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos']." ".$afiliado['nombres']."</td>\n";
					$html .= "		  <td >".$afiliado['codigo_novedad']."</td>\n";
					$html .= "		  <td >".$afiliado['nombre']."</td>\n";
          $html .= "		  </td>\n";
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
        $html .= "    <label class=\"label_error\">NO EXISTEN NOVEDADES NO PROCESADAS</label>\n";
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
    /**
    * Funcion donde se crea la forma donde se muestra el historial de cambios en 
    * los estados de los afiliados
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $datos Vector con los datos de las novedades no procesadas
    * @param int $conteo Cantidad de total de registro de datos
    * @param int $pagina Numero de pagina en que se esta mostrando en pantalla
    * @param array $request Vector con los datos del request    
    * @param array $tipos_doc Vector con los tipos de identificacion    
    *
    * @return String $html
    */
    function FormaHistorialEstados($action,$datos,$conteo,$pagina,$request,$tipos_doc)
    {
      $html .= ThemeAbrirTabla('HISTORICO DE CAMBIOS DE ESTADO');
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
      $html .= "    <tr>\n";
      $html .= "      <td style=\"text-align:left;text-indent:8pt\" class=\"modulo_table_list_title\">\n";
      $html .= "        FECHA REGISTRO\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" name=\"fecha_registro\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_registro']."\">\n";
      $html .= "      </td>\n";
 			$html .= "		  <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_registro','/')."</td>\n";
      $html .= "    </tr>\n";
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
				$html .= "			<td width=\"8%\">FECHA REGISTRO</td>\n";
				$html .= "		  <td width=\"38%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"18%\">ESTADO - SUBESTADO ANTERIOR</td>\n";
				$html .= "			<td width=\"18%\">ESTADO  - SUBESTADO NUEVO</td>\n";
				$html .= "			<td width=\"18%\">USUARIO REGISTRO</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($datos as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_registro']."</td>\n";
					$html .= "		  <td width=\"10%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos']." ".$afiliado['nombres']."</td>\n";
					$html .= "		  <td >".$afiliado['descripcion_estado_viejo']." - ".$afiliado['descripcion_subestado_viejo']."</td>\n";
					$html .= "		  <td >".$afiliado['descripcion_estado']." - ".$afiliado['descripcion_subestado']."</td>\n";
					$html .= "		  <td >".$afiliado['nombre']."</td>\n";
          $html .= "		</tr>\n";
          
          if(trim($afiliado['observacion']) != "")
          {
          	$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
  					$html .= "		  <td class=\"formulacion_table_list\">OBSERVACION</td>\n";
  					$html .= "		  <td colspan=\"5\">".$afiliado['observacion']."</td>\n";
            $html .= "		</tr>\n";
          }
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
        $html .= "    <label class=\"label_error\">NO HAY HISTORICO DE CAMBIOS DE ESTADO PARA MOSTRAR</label>\n";
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
    /**
    * Funcion donde se crea la forma donde se muestra el historial de cambios
    * en las fechas de convenios
    *
		* @param array $action Vector de links de la aplicaion
		* @param array $datos Vector con los datos de las novedades no procesadas
    * @param int $conteo Cantidad de total de registro de datos
    * @param int $pagina Numero de pagina en que se esta mostrando en pantalla
    * @param array $request Vector con los datos del request    
    * @param array $tipos_doc Vector con los tipos de identificacion    
    *
    * @return String $html
    */
    function FormaHistorialFechasConvenios($action,$datos,$conteo,$pagina,$request,$tipos_doc)
    {
      $html .= ThemeAbrirTabla('HISTORICO DE CAMBIOS DE FECHA DE CONVENIO');
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
      $html .= "    <tr>\n";
      $html .= "      <td style=\"text-align:left;text-indent:8pt\" class=\"modulo_table_list_title\">\n";
      $html .= "        FECHA REGISTRO\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" name=\"fecha_registro\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_registro']."\">\n";
      $html .= "      </td>\n";
 			$html .= "		  <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_registro','/')."</td>\n";
      $html .= "    </tr>\n";
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
				$html .= "			<td width=\"8%\">FECHA REGISTRO</td>\n";
				$html .= "		  <td width=\"38%\" colspan=\"2\">AFILIADO</td>\n";
				$html .= "			<td width=\"18%\" colspan=\"2\">FECHAS CONEVIO ANTERIOR</td>\n";
				$html .= "			<td width=\"18%\" colspan=\"2\">FECHAS CONVENIO NUEVA</td>\n";
				$html .= "			<td width=\"18%\">USUARIO REGISTRO</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($datos as $key => $afiliado)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td align=\"center\">".$afiliado['fecha_registro']."</td>\n";
					$html .= "		  <td width=\"10%\">".$afiliado['afiliado_tipo_id']." ".$afiliado['afiliado_id']."</td>\n";
					$html .= "		  <td >".$afiliado['apellidos']." ".$afiliado['nombres']."</td>\n";
					$html .= "		  <td width=\"9\" align=\"center\">".$afiliado['anterior_fecha_inicio']."</td>\n";
					$html .= "		  <td width=\"9\" align=\"center\">".$afiliado['anterior_fecha_fin']."</td>\n";
					$html .= "		  <td width=\"9\" align=\"center\">".$afiliado['nueva_fecha_inicio']."</td>\n";
					$html .= "		  <td width=\"9\" align=\"center\">".$afiliado['nueva_fecha_fin']."</td>\n";
					$html .= "		  <td >".$afiliado['nombre']."</td>\n";
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
    /**
    * Funcion donde se crea la forma para hacer la generacion de los archivos del
    * ministerio
    *
    * @param array $action Arreglo de datos de los links
    * @param array $datos 
    * @param array $dtempresas Arreglo de datos de la empresa
    * @param date  $fecha_novedad Fecha de la generacion del ultimo archivo de novedades
    * @param array $request Arreglo de datos del request
    * @param array $planes Arreglo de datos de los planes
    *
    * @return string $html
    */
    function FormaGeneraArchivosNovedades($action,$datos,$dtempresas,$fecha_novedad,$request,$planes)
    {
      $html .= ThemeAbrirTabla('GENERACION DE ARCHIVOS MINISTERIO');
      
      $datos['fecha_inicio'] = $request['fecha_inicio'];
      $datos['fecha_final'] = $request['fecha_final'];
      $datos['plan'] = $request['plan'];
      
      $dtempresas['fecha_inicio'] = $request['fecha_inicio'];
      $dtempresas['fecha_final'] = $request['fecha_final'];
      $dtempresas['plan'] = $request['plan'];
      
      $csv = Autocarga::factory("ReportesCsv");
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','Listados',$datos,'comas',array("interface"=>1,"cabecera"=>2,"nombre"=>"NE".$dtempresas['codigo_sgsss'].date("dmY"),"extension"=>"txt"));
      $fncn1  = $csv->GetJavaFunction();      
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','MaestroAfiliados',$dtempresas,'comas',array("interface"=>1,"cabecera"=>2,"nombre"=>"ME".$dtempresas['codigo_sgsss'].date("dmY"),"extension"=>"txt"));
      $fncn2  = $csv->GetJavaFunction();
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','MaestroAportantes',$dtempresas,'comas',array("interface"=>1,"cabecera"=>2,"nombre"=>"MA".$dtempresas['codigo_sgsss'].date("dmY"),"extension"=>"txt"));
      $fncn3  = $csv->GetJavaFunction();
      
      $ctl = AutoCarga::factory("ClaseUtil"); 
      
      $html .= $ctl->AcceptDate("/");
      $html .= $ctl->IsDate();
      $html .= $ctl->LimpiarCampos();
      $html .= "  <script>\n";
      $html .= "    function EvaluarDatos(objeto)\n";
      $html .= "    {\n";
      $html .= "      if(!IsDate(objeto.fecha_inicio.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA FECHA DE INICIO';\n";
      $html .= "        return;\n";
      $html .= "      }\n";      
      $html .= "      if(!IsDate(objeto.fecha_final.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA FECHA DE FIN';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "	    f = objeto.fecha_final.value.split('/')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    f2 = new Date('".date("Y/m/d")."');\n";
      $html .= "	    if(f1 >= f2)\n";
			$html .= "	    {\n";
      $html .= "			  document.getElementById('error').innerHTML = 'LA FECHA FINAL, PARA LA GENERACION DE LOS ARCHIVOS DEBE SER MENOR AL DIA DE HOY (".date("d/m/Y").") ';\n";
			$html .= "	      return;\n";
			$html .= "	    }\n";
      $html .= "      objeto.action = \"".$action['reportes']."\";\n";
      $html .= "      objeto.submit();\n";
      $html .= "    }\n";
      
      $html .= "	  function OcultarCapas(Seccion)\n";
			$html .= "	  { \n";
			$html .= "		  e = document.getElementById(Seccion);\n";
			$html .= "			if(e.style.display == 'none')\n";
			$html .= "				e.style.display = \"block\";\n";
			$html .= "			else\n";
			$html .= "				e.style.display = \"none\";\n";
			$html .= "		}\n";
      
      $html .= "  </script>\n";
      if($fecha_novedad)
        $html .= "	<center><label class=\"normal_10AN\">LA ULTIMA FECHA CON LA CUAL SE HIZO LA VALIDACION DE LOS ARCHIVOS FUE ".$fecha_novedad."</label></center>\n";
      
      $html .= "	<center><div id=\"error\" class=\"label_error\"></div></center>\n";
			$html .= "	<form name=\"reportes\" action=\"javascript:EvaluarDatos(document.reportes)\" method=\"post\">\n";
			$html .= "		<table align=\"center\" width=\"60%\" class=\"modulo_table_list\" border=\"0\">\n";
			$html .= "			<tr class=\"formulacion_table_list\">\n";
			$html .= "			  <td align=\"left\" width=\"33%\">FECHA INICIAL</td>\n";
			$html .= "				<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "				  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
			$html .= "					".ReturnOpenCalendario('reportes','fecha_inicio','/')."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";			
      $html .= "			<tr class=\"formulacion_table_list\">\n";
			$html .= "			  <td align=\"left\" width=\"33%\">FECHA FINAL</td>\n";
			$html .= "				<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "				  <input type=\"text\" class=\"input-text\" name=\"fecha_final\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
			$html .= "					".ReturnOpenCalendario('reportes','fecha_final','/')."\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
      $html .= "			<tr class=\"formulacion_table_list\">\n";
			$html .= "			  <td colspan=\"2\">\n";
      $html .= "      	  <a class=\"hclink\" href=\"javascript:OcultarCapas('planes_sel')\">\n";
      $html .= "            PLANES\n";
      $html .= "					  <img height=\"8\" width=\"10\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" >\n";
      $html .= "          </a>\n";
      $html .= "        </td>\n";
			$html .= "			</tr>\n";
      $html .= "			<tr>\n";
			$html .= "			  <td colspan=\"2\">\n";
      $html .= "          <div id=\"planes_sel\" style=\"display:".((empty($request['plan']))? "none":"block")."\">\n";
      $i =0; $chk =  "";
      $html .= "            <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
      foreach ($planes as $key => $datos) 
      { 
        $chk = "";
        if($i %3 == 0)   
          $html .= "              <tr class=\"modulo_list_claro\">\n";
        
        ($request['plan'][$key] == $key)? $chk = "checked": $chk = "";
        
        $html .= "                <td width=\"32%\">".$datos['plan_descripcion']."</td>\n";
        $html .= "                <td width=\"1%\" align=\"center\">\n";
        $html .= "                  <input type=\"checkbox\" name=\"plan[".$key."]\" value=\"".$key."\" ".$chk.">\n";
        $html .= "                </td>\n";
          
        if($i%3 == 2) 
          $html .= "              </tr>\n";
          
        $i++;
  		}
      if($i%3 != 0) 
      {
        $html .= "                <td colspan=\"4\" class=\"modulo_list_claro\"></td>\n";
        $html .= "              </tr>\n";
      }
      $html .= "            </table>\n";
      
      $html .= "          </div>\n";
      $html .= "        </td>\n";
			$html .= "			</tr>\n";
      $html .= "			<tr>\n";
			$html .= "        <td colspan = '2' align=\"center\" >\n";
			$html .= "          <table width=\"70%\">\n";
			$html .= "           	<tr align=\"center\">\n";
			$html .= "             	<td >\n";
			$html .= "               	<input class=\"input-submit\" type=\"submit\"  name=\"Aceptar\" value=\"Aceptar\">\n";
			$html .= "              </td>\n";
			$html .= "              <td>\n";
			$html .= "               	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.reportes)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "              </td>\n";
			$html .= "           	</tr>\n";
			$html .= "          </table>\n";
			$html .= "        </td>\n";
			$html .= "      </tr>\n";
			$html .= "		</table>\n";	
			$html .= "	</form><br>\n";	
      if($request['fecha_inicio'])
      {
        $html .= "<table border=\"0\" width=\"60%\" align=\"center\" >\n";
  			$html .= "	<tr>\n";
  			$html .= "		<td>\n";
  			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
  			$html .= "				<tr>\n";
  			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
  			$html .= "				</tr>\n";
        $html .= "        <tr>\n";
        $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "	          <a href=\"javascript:".$fncn1."\" class=\"label_error\">\n";
        $html .= "              <b>ARCHIVO DE NOVEDADES</b>\n";
        $html .= "            </a>\n";
        $html .= "          </td>\n";
        $html .= "        </tr>\n";      
        $html .= "        <tr>\n";
        $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "	          <a href=\"javascript:".$fncn2."\" class=\"label_error\">\n";
        $html .= "              <b>MAESTRO DE AFILIADOS</b>\n";
        $html .= "            </a>\n";
        $html .= "          </td>\n";
        $html .= "        </tr>\n";        
        $html .= "        <tr>\n";
        $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "	          <a href=\"javascript:".$fncn3."\" class=\"label_error\">\n";
        $html .= "              <b>MAESTRO DE APORTANTES</b>\n";
        $html .= "            </a>\n";
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
  			$html .= "			</table>\n";
  			$html .= "		</td>\n";
  			$html .= "	</tr>\n";
  			$html .= "</table>";
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
    /**
    * Funcion donde se crea la forma para generar archivos planos
    *
    * @param array $action
    * @param array $request
    *
    * @return string $html
    */
    function FormaGeneraReportesArchivosPlanos($action,$request)
    {
      $html .= ThemeAbrirTabla('GENERACION DE ARCHIVOS MINISTERIO');
      
      $datos['fecha_inicio'] = $request['fecha_inicio'];
      $datos['fecha_final'] = $request['fecha_final'];
      $dtempresas['fecha_inicio'] = $request['fecha_inicio'];
      $dtempresas['fecha_final'] = $request['fecha_final'];
      
      $csv = Autocarga::factory("ReportesCsv");
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','Cotizantes',$datos,'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"cotizantes","extension"=>"csv"));
      $fncn1  = $csv->GetJavaFunction();      
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','Beneficiarios',$dtempresas,'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"beneficiarios","extension"=>"csv"));
      $fncn2  = $csv->GetJavaFunction();
      $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','Diferencias',$dtempresas,'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"diferencias","extension"=>"csv"));
      $fncn3  = $csv->GetJavaFunction();
	  $html .= $csv->GetJavacriptReporte('app','UV_Afiliaciones','Planos',$datos,'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"planos","extension"=>"csv"));
      $fncn4  = $csv->GetJavaFunction();
      
      $ctl = AutoCarga::factory("ClaseUtil"); 
      
      $html .= $ctl->AcceptDate("/");
      $html .= $ctl->IsDate();
      $html .= $ctl->LimpiarCampos();
      $html .= "  <script>\n";
      $html .= "    function EvaluarDatos(objeto)\n";
      $html .= "    {\n";
      $html .= "      if(objeto.fecha_inicio.value != '' && !IsDate(objeto.fecha_inicio.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA FECHA DE INICIO';\n";
      $html .= "        return;\n";
      $html .= "      }\n";      
      $html .= "      if(objeto.fecha_final.value != '' && !IsDate(objeto.fecha_final.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA FECHA DE FIN';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      objeto.action = \"".$action['reportes']."\";\n";
      $html .= "      objeto.submit();\n";
      $html .= "    }\n";
      $html .= "  </script>\n";
      
      $html .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
			$html .= "<form name=\"reportes\" action=\"javascript:EvaluarDatos(document.reportes)\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"50%\" class=\"modulo_table_list\" border=\"0\">\n";
			$html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td align=\"left\" width=\"33%\">FECHA INICIAL</td>\n";
			$html .= "			<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
			$html .= "				".ReturnOpenCalendario('reportes','fecha_inicio','/')."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";			
      $html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td align=\"left\" width=\"33%\">FECHA FINAL</td>\n";
			$html .= "			<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"fecha_final\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
			$html .= "				".ReturnOpenCalendario('reportes','fecha_final','/')."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";		
      $html .= "		<tr>\n";
			$html .= "      <td colspan = '2' align=\"center\" >\n";
			$html .= "        <table width=\"70%\">\n";
			$html .= "          <tr align=\"center\">\n";
			$html .= "            <td >\n";
			$html .= "              <input class=\"input-submit\" type=\"submit\"  name=\"Aceptar\" value=\"Aceptar\">\n";
			$html .= "            </td>\n";
			$html .= "            <td>\n";
			$html .= "            	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.reportes)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "            </td>\n";
			$html .= "          </tr>\n";
			$html .= "        </table>\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";	
			$html .= "</form><br>\n";	
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "	<tr>\n";
      $html .= "		<td>\n";
      $html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
      $html .= "				<tr>\n";
      $html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
      $html .= "				</tr>\n";
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "	          <a href=\"javascript:".$fncn1."\" class=\"label_error\">\n";
      $html .= "              <b>REPORTE DE COTIZANTES</b>\n";
      $html .= "            </a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "	          <a href=\"javascript:".$fncn2."\" class=\"label_error\">\n";
      $html .= "              <b>REPORTE DE BENEFICIARIOS</b>\n";
      $html .= "            </a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";      
      $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "	          <a href=\"javascript:".$fncn3."\" class=\"label_error\">\n";
      $html .= "              <b>REPORTE DE DIFERENCIAS</b>\n";
      $html .= "            </a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
	  $html .= "        <tr>\n";
      $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "	          <a href=\"javascript:".$fncn4."\" class=\"label_error\">\n";
      $html .= "              <b>REPORTE - PLANO</b>\n";
      $html .= "            </a>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "			</table>\n";
      $html .= "		</td>\n";
      $html .= "	</tr>\n";
      $html .= "</table>";
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