<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaPreciosHTML.class.php,v 1.5 2008/08/15 16:10:21 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ListaPreciosHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaPreciosHTML
  {
    /**
    * Contructor de la clase
    */
    function ListaPreciosHTML(){}
    /**
    * Funcion donde se crea la forma inicial, donde se visualizan las listas de 
    * precios y para la creacion de listas
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $request Arreglo de datos del request
    * @param array $listado Arreglo de datos del las listas creadas
    * @param int $conteo Cantidad de da datos retornasdos
    * @param int $pagina Pagina que se esta viendo actualmente
    *
    * @return string
    */
    function Formalnicial($action,$request,$listado,$conteo,$pagina)
    {
      $html  = "  <script>\n";
			$html .= "		function ContinuarSolicitud()\n";
			$html .= "		{\n";
			$html .= "		  document.solicitud.action =\"".$action['aceptar']."\";\n";
			$html .= "		  document.solicitud.submit();\n";
			$html .= "		}\n";
      $html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
      $html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
      $html .= "  </script>\n";
 			$html .= ThemeAbrirTabla('LISTAS DE PRECIOS SOBRE CARGOS');
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>	tabPane = new WebFXTabPane( document.getElementById( \"APD\" ),true ); </script>\n";
      $html .= "				<div class=\"tab-page\" id=\"crear_lista\">\n";
      $html .= "				  <h2 class=\"tab\">CREAR LISTAS</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"crear_lista\")); </script>\n";
      $html .= $this->FormaCrearListaPrecios($action);
      $html .= "        </div>\n";
      
      $html .= "				<div class=\"tab-page\" id=\"lista\">\n";
      $html .= "				  <h2 class=\"tab\">DEATALLE LISTAS</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"lista\")); </script>\n";
      $html .= $this->FormaListasPrecios($action,$request,$listado,$conteo,$pagina);
      $html .= "        </div>\n";
      
      $html .= "      </div>\n";
			$html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla(); 
      return $html;
    }
    /**
    * Funcion donde se crea la forma para la mostrar la informacion de la lista, 
    * si esta ya esta creada
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $informacion Arreglo de datos con la informacion de la lista
    *
    * @return string
    */
    function FormaCrearListaPrecios($action,$informacion)
    {
      $st = "style=\"text-align:left;text-indent:8pt\" ";
      $ctl = AutoCarga::factory("ClaseUtil");
      
      $html  = $ctl->IsDate();
      $html .= $ctl->AcceptDate("/");
      $html .= "<script>\n";
      $html .= "  function trim(cadena)\n";
      $html .= "	{\n";
      $html .= "    for(i=0; i<cadena.length; )\n";
      $html .= "		{\n";
      $html .= "      if(cadena.charAt(i)==\" \")\n";
      $html .= "    	  cadena=cadena.substring(i+1, cadena.length);\n";
      $html .= "    	else\n";
			$html .= "        break;\n";
      $html .= "		}\n";
      $html .= "    for(i=cadena.length-1; i>=0; i=cadena.length-1)\n";
      $html .= "		{\n";
      $html .= "      if(cadena.charAt(i)==\" \")\n";
      $html .= "    	  cadena=cadena.substring(0,i);\n";
      $html .= "    	else\n";
      $html .= "    	  break;\n";
			$html .= "	  } \n";
      $html .= "    return cadena;\n";
			$html .= "	} \n";
      $html .= "	function evaluarDatos(forma)\n";
			$html .= "	{ \n";
			$html .= "		error = document.getElementById('error');\n";
			$html .= "		if(trim(forma.descripcion_lista.value) == '')\n";
			$html .= "		{\n";
			$html .= "			error.innerHTML = \"SE DEBE INGRESAR UN NOMBRE PARA LA LISTA DE PRECIOS\";\n";
			$html .= "			return\n";
			$html .= "		}\n";			
      $html .= "		if(!IsDate(forma.fecha_inicio.value) || !IsDate(forma.fecha_fin.value))\n";
			$html .= "		{\n";
			$html .= "			error.innerHTML = \"LAS FECHAS DE VALIDEZ DE LA LISTA, SON OBLIGATORIAS O POSEEN UN FORMATO INCORRECTO\";\n";
			$html .= "			return\n";
			$html .= "		}\n";
      $html .= "	  f = forma.fecha_inicio.value.split('/')\n";
			$html .= "	  f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "	  g = forma.fecha_fin.value.split('/')\n";
			$html .= "	  f2 = new Date(g[2]+'/'+g[1]+'/'+g[0]);\n";
      $html .= "    if(f1 >= f2 )\n";
      $html .= "		{\n";
			$html .= "		  error.innerHTML = \"LA FECHA DE INICIO DEL RANGO NO DEBE SER MAYOR A LA FECHA DE FIN DEL RANGO DE VALIDEZ\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		forma.action =\"".$action['aceptar']."\"; \n";
			$html .= "		forma.submit(); \n";
			$html .= "	}\n";
      $html .= "</script>\n";
      $html .= "<table width=\"65%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">DESCRICPION GENERAL DE LA LISTA DE PRECIOS</LEGEND>\n";
      $html .= "        <form name=\"crearlistaprecios\" action=\"javascript:evaluarDatos(document.crearlistaprecios)\" method=\"post\">";
			$html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "			      <tr class=\"formulacion_table_list\">\n";
			$html .= "			        <td $st width=\"20%\">NOMBRE LISTA</td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "                <input type=\"text\" class=\"input-text\" name=\"descripcion_lista\" value=\"".$informacion['descripcion_lista']."\" style=\"width:85%\" maxlenght=\"60\">\n";
      $html .= "              </td>\n";
			$html .= "			      </tr>\n";
      $rd = "";
      if(!empty($informacion)) $rd = "readonly";
      $html .= "            <tr class=\"formulacion_table_list\">\n";
      $html .= "              <td ".$st.">VIGENCIA LISTA</td>\n";
      $html .= "              <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "                <input size=\"12\" type=\"text\" name=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$informacion['fecha_inicio_lista']."\" $rd>\n";
      if(empty($informacion))
        $html .= "                ".ReturnOpenCalendario('crearlistaprecios','fecha_inicio','/')."\n";
      $html .= "              </td>\n";    
      $html .= "              <td width=\"3%\">A</td>\n";
      $html .= "              <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "                <input size=\"12\" type=\"text\" name=\"fecha_fin\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$informacion['fecha_fin_lista']."\" >\n";
      $html .= "                ".ReturnOpenCalendario('crearlistaprecios','fecha_fin','/')."\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"4\">OBSERVACION</td>\n";
			$html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
			$html .= "			        <td colspan=\"4\">\n";
      $html .= "                <textarea class=\"textarea\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$informacion['observacion']."</textarea>\n";
      $html .= "              </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			    </table >\n";
			$html .= "			    <div id=\"error\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
      $html .= "	        <table width=\"100%\" align=\"center\">\n";
			$html .= "	          <tr>\n";
			$html .= "			        <td align='center'>\n";
			$html .= "			          <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "		        	</td>\n";
			$html .= "            </form>\n";
      $html .= "            <form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		          <td align=\"center\">\n";
      $html .= "				        <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
			$html .= "		          </td>\n";
			$html .= "            </form>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      return $html;
    }
    /**
    * Funcion donde se la forma para la adicion del detalle a una lista de precios 
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $tipos_tarifarios Arreglo de datos con la informacion de los tipos tarifarios
    * @param int $lista_codigo identificador de la lista
    *
    * @return string   
    */
    function FormaGrupos($action,$tipos_tarifarios,$lista_codigo)
    {
      $html  = "  <script>\n";
      $html .= "		function ValidarDatos(forma)\n";
			$html .= "		{ \n";
			//$html .= "			error = document.getElementById('error');\n";
			$html .= "			if(forma.tipo_tarifario_id.value == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE TARIFARIO\";\n";
			$html .= "				return;\n";
			$html .= "			} \n";
      $html .= "			if(forma.tarifario_id.value == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL TARIFARIO\";\n";
			$html .= "				return;\n";
			$html .= "			} \n";      
      $html .= "			if(forma.grupo_tarifario_id.value == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL GRUPO TARIFARIO\";\n";
			$html .= "				return;\n";
			$html .= "			} \n";      
      $html .= "			if(forma.subgrupo_tarifario_id.value == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL SUBGRUPO TARIFARIO\";\n";
			$html .= "				return;\n";
			$html .= "			} \n";
      //$html .= "			error.innerHTML='<br>'; \n";
			$html .= "			xajax_BuscarCargos(xajax.getFormValues('formaseleccionar')); \n";
			$html .= "		}\n";
      $html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
      $html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
      
      $html .= "    function IsNumeric(valor)\n";
      $html .= "    {\n";
      $html .= "      if((valor*1) < 0 ) valor = valor*-1;\n";
			$html .= "    	var log = valor.length; \n";
			$html .= "    	var sw='S';\n";
			$html .= "    	var puntos = 0;\n";
			$html .= "    	for (x=0; x<log; x++)\n";
			$html .= "    	{ \n";
			$html .= "    		v1 = valor.substr(x,1);\n";
			$html .= "    		v2 = parseInt(v1);\n";
			$html .= "    		//Compruebo si es un valor num?ico\n";
			$html .= "    		if(v1 == '.')\n";
			$html .= "    		{\n";
			$html .= "    			puntos ++;\n";
			$html .= "    		}\n";
			$html .= "    		else if (isNaN(v2)) \n";
			$html .= "    		{ \n";
			$html .= "    			sw= 'N';\n";
			$html .= "    			break;\n";
			$html .= "    		}\n";
			$html .= "    	}\n";
			$html .= "    	if(log == 0) sw = 'N';\n";
			$html .= "    	if(puntos > 1) sw = 'N';\n";
			$html .= "    	if(sw=='S')\n"; 
			$html .= "    		return true;\n";
			$html .= "    	return false;\n";
			$html .= "    } \n";
      
      $html .= "    function EvaluarDatos(frm)\n"; 
      $html .= "    {\n";
      $html .= "      flag = false;\n";
 			$html .= "			error = document.getElementById('error_div');\n";
      $html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "		    if(frm[i].type == 'checkbox')\n";
			$html .= "		    {\n";
      $html .= "          if(frm[i].checked)\n";
      $html .= "          {\n";
      $html .= "            try\n";
      $html .= "            {\n";
      $html .= "              if(!IsNumeric(document.getElementsByName('cargos['+frm[i].value+'][porcentaje]')[0].value))\n";
      $html .= "              {\n";
      $html .= "                error.innerHTML = 'PARA EL CARGO '+frm[i].value+', EL PORCENTAJE DE MODIFICACION INGRESADO POSEE UN FORMATO INVALIDO';\n";
      $html .= "                return;\n";
      $html .= "              }\n";
      $html .= "              flag = true;\n";  		
      $html .= "            }\n";  		
      $html .= "            catch(error){}\n";  		
      $html .= "          }\n";  		
      $html .= "			  }\n";
			$html .= "		  }\n";
      $html .= "      xajax_AdicionarCargos(xajax.getFormValues('cargos'));\n";
      $html .= "    }\n";
      
      $html .= "    function AplicarPorcentaje(frm)\n"; 
      $html .= "    {\n";
 			$html .= "			error = document.getElementById('error_porcent');\n";
      $html .= "      if(!IsNumeric(frm.porcentaje_unificado.value))\n";
      $html .= "      {\n";
      $html .= "        error.innerHTML = 'EL VALOR INGRESADO PARA EL PORCENTAJE A APLICAR POSEE UN FORMATO INVALIDO';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "		    if(frm[i].type == 'checkbox')\n";
			$html .= "		      frm[i].checked = true;\n"; 		
			$html .= "		    if(frm[i].type == 'text')\n";
			$html .= "		      frm[i].value = frm.porcentaje_unificado.value;\n";
			$html .= "		  }\n";
      $html .= "    }\n";
      
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);\n";
			$html .= "	}\n";
      
      $html .= "    function SeleccionarTodos(frm)\n"; 
      $html .= "    {\n";
      $html .= "      valor = frm.todos.checked;\n";
      $html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "		    if(frm[i].type == 'checkbox')\n";
			$html .= "		      frm[i].checked = valor;\n";
			$html .= "		  }\n";
			$html .= "		}\n";
      
      $html .= "  </script>\n";
      $html .= ThemeAbrirTabla('ADICIONAR CARGOS A LA LISTA Nº '.$lista_codigo);
      
      $st = "style=\"text-align:left;text-indent:8pt\" ";
      $html .= "  <center>\n";
      $html .= "      <fieldset class=\"fieldset\" style=\"width:70%\">\n";
      $html .= "        <legend class=\"normal10_AN\">CREACION DE LISTAS DE PRECIOS DE CARGOS</legend>\n";
      $html .= "        <form name=\"formaseleccionar\" id=\"formaseleccionar\" action=\"javascript:ValidarDatos(document.formaseleccionar)\" method=\"post\">";
			$html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "				    <tr class=\"formulacion_table_list\" >\n";
			$html .= "					    <td $st width=\"30%\">TIPO TARIFARIO: </td>\n";
			$html .= "						  <td $st class=\"modulo_list_claro\">\n";
			$html .= "						    <select name=\"tipo_tarifario_id\" class=\"select\" onchange=\"xajax_SeleccionarTarifario(this.value)\">\n";
			$html .= "                  <option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tipos_tarifarios as $key => $dtl)
				$html .= "                  <option value=\"".$dtl['tipo_tarifario_id']."\" >".$dtl['descripcion']."</option>";
			
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";
      $html .= "				    <tr class=\"formulacion_table_list\" >\n";
			$html .= "					    <td $st>TARIFARIO: </td>\n";
			$html .= "						  <td $st class=\"modulo_list_claro\">\n";
			$html .= "						    <select name=\"tarifario_id\" class=\"select\" onchange=\"xajax_SeleccionarGrupos(this.value)\">\n";
			$html .= "                  <option value = '-1'>--  SELECCIONE --</option>\n";
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";
      $html .= "				    <tr class=\"formulacion_table_list\" >\n";
			$html .= "					    <td $st>GRUPO TIPO TARIFARIO: </td>\n";
			$html .= "						  <td $st class=\"modulo_list_claro\">\n";
			$html .= "						    <select name=\"grupo_tarifario_id\" class=\"select\" onchange=\"xajax_SeleccionarSubGrupos(this.value,document.formaseleccionar.tarifario_id.value)\">\n";
			$html .= "                  <option value = '-1'>--  SELECCIONE --</option>\n";
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";
      $html .= "				    <tr class=\"formulacion_table_list\" >\n";
			$html .= "					    <td $st>SUBGRUPO TARIFARIO: </td>\n";
			$html .= "					    <td $st class=\"modulo_list_claro\">\n";
			$html .= "						    <select name=\"subgrupo_tarifario_id\" class=\"select\">\n";
			$html .= "                  <option value = '-1'>--  SELECCIONE --</option>\n";
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";	
 			$html .= "				  </table>\n";
 			$html .= "			    <div id=\"error\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
      $html .= "	        <table width=\"100%\" align=\"center\">\n";
			$html .= "	          <tr>\n";
			$html .= "			        <td align='center'>\n";
			$html .= "			          <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "			          <input type=\"hidden\" name=\"lista_codigo\" value=\"".$lista_codigo."\">\n";
			$html .= "		          </td>\n";
			$html .= "              </form>\n";
      $html .= "              <form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		          <td align=\"center\">\n";
      $html .= "				        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Cancelar\">\n";
			$html .= "		          </td>\n";
			$html .= "              </form>\n";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
			$html .= "			    </fieldset><br>\n";
      $html .= "      <form name=\"cargos\" id=\"cargos\" action=\"javascript:EvaluarDatos(document.cargos)\" method=\"post\">\n";
      $html .= "        <input type=\"hidden\" name=\"lista_codigo\" value=\"".$lista_codigo."\">\n";
      $html .= "        <div id=\"lista_precios\"></div>\n";
			$html .= "      </form>\n";
			$html .= "  </center>\n";

      $html .= ThemeCerrarTabla(); 
      return $html;
    }
    /**
    * Funcion donde se muestra la lista de cargos
    * 
    * @param array $listado Arreglo con los datos de los cargos
    * @param string $tarifario_id Identificador del tarifario
    *
    * @return String
    */
    function ListaCargos($listado,$tarifario_id)
    {
      $html = "";
      
      if(!empty($listado))
      {
        $html .= "  <table width=\"50%\" align=\"center\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td colspan=\"3\">\n";
   			$html .= "			  <div id=\"error_porcent\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"right\" class=\"normal_10AN\">APLICAR A TODOS LOS CARGOS EL:</td>\n";
        $html .= "	    <td align=\"right\" width=\"15%\" class=\"label\">\n";
        $html .= "	      <input type=\"text\" name=\"porcentaje_unificado\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" style=\"width:80%\" value=\"\">%\n";
        $html .= "      </td>\n";
        $html .= "	    <td align=\"center\">\n";
        $html .= "		    <input class=\"input-submit\" type=\"button\" name=\"aplicar\" value=\"Aplicar\" onClick=\"AplicarPorcentaje(document.cargos)\">\n";
        $html .= "		  </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <input type=\"hidden\" name=\"tarifario_id\" value=\"".$tarifario_id."\">\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">\n";
        $html .= "  	  <td width=\"7%\">TARIFARIO</td>\n";
        $html .= "  	  <td width=\"7%\">CARGO</td>\n";
        $html .= "  	  <td width=\"7%\">CUPS</td>\n";
        $html .= "			<td width=\"%\">DESCRIPCION</td>\n";
        $html .= "  	  <td width=\"8%\">VALOR</td>\n";
        $html .= "  	  <td width=\"8%\">% MOD</td>\n";
        $html .= "			<td width=\"2%\">\n";
        $html .= "	      <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarTodos(document.cargos)\">\n";
        $html .= "      </td>\n";
        $html .= "		</tr>\n";
        
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        
        foreach($listado as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro"; 
          ($back == "#DDDDDD")? $back = "#CCCCCC":$back = "#DDDDDD";
          
          $html .= "	  <tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
          $html .= "	    <td >".$dtl['tarifario_id']."</td>\n";
          $html .= "	    <td >".$dtl['cargo']."</td>\n";
          $html .= "	    <td >".$dtl['cargo_base']."</td>\n";
          $html .= "	    <td >".$dtl['descripcion']."</td>\n";
          $html .= "	    <td align=\"right\">".formatoValor($dtl['precio'])."</td>\n";
          $html .= "	    <td align=\"right\">\n";
          $html .= "	      <input type=\"text\" name=\"cargos[".$dtl['cargo']."][porcentaje]\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" style=\"width:80%\" value=\"0\">\n";
          $html .= "      </td>\n";
          
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          $html .= "	      <input type=\"hidden\" name=\"cargos[".$dtl['cargo']."][precio]\" value=\"".$dtl['precio']."\">\n";
          $html .= "	      <input type=\"checkbox\" name=\"cargos[".$dtl['cargo']."][cargo]\" value=\"".$dtl['cargo']."\">\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "</table><br>\n";
        $html .= "<div id=\"error_div\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
        $html .= "<table width=\"60%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "	  <td align=\"center\">\n";
        $html .= "		  <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Aceptar Precios\">\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
      }
      else
      {
        $html .= "  <center>\n";
        $html .= "    <label class=\"label_error\">NO SE ENCONTRARON CARGOS PARA SER ADICIONADOS, ES POSIBLE QUE LOS CARGOS YA SEAN PARTE DE LA LISTA QUE SE ESTA TRABAJANDO ACTUALMENTE</label>\n";
        $html .= "  </center>\n";
      }
      return $html;
    }
    /**
    * Funcion donde se crea la forma en la que se muestran las listas de precios
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $request Arreglo de datos del request
    * @param array $listado Arreglo de datos del las listas creadas
    * @param int $conteo Cantidad de da datos retornasdos
    * @param int $pagina Pagina que se esta viendo actualmente
    *
    * @return string
    */
    function FormaListasPrecios($action,$request,$listado,$conteo,$pagina)
    {
			$html  = "<form name=\"formabuscar\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "	<table width=\"70%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_list_claro\" >\n";
			$html .= "			<td class=\"formulacion_table_list\" width=\"15%\">Nº LISTA</td>\n";
			$html .= "			<td width=\"15%\">\n";
			$html .= "			  <input type=\"text\" name=\"buscador[lista_codigo]\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" style=\"width:90%\" value=\"".$request['lista_codigo']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"formulacion_table_list\" width=\"15%\">DESCRIPCION</td>\n";
      $html .= "			<td >\n";
			$html .= "			  <input type=\"text\" name=\"buscador[descripcion_lista]\" class=\"input-text\" onkeypress=\"return acceptNum(event)\"  style=\"width:90%\" value=\"".$request['descripcion_lista']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align='center' width=\"15%\">\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";

      if(!empty($listado))
      {
        $pghtml = AutoCarga::factory('ClaseHTML');

        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">\n";
        $html .= "  	  <td width=\"5%\">LISTA</td>\n";
        $html .= "			<td width=\"20%\">DESCRIPCION</td>\n";
        $html .= "  	  <td width=\"37%\">OBSERVACION</td>\n";
        $html .= "  	  <td width=\"16%\" colspan=\"2\">VIGENCIA</td>\n";
        $html .= "  	  <td width=\"8%\">ESTADO</td>\n";
        $html .= "			<td width=\"%\" colspan=\"6\">OPCION</td>\n";
        $html .= "		</tr>\n";
        
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        
        foreach($listado as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro"; 
          ($back == "#DDDDDD")? $back = "#CCCCCC":$back = "#DDDDDD";
          
          $html .= "	  <tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
          $html .= "	    <td >".$dtl['lista_codigo']."</td>\n";
          $html .= "	    <td >".$dtl['descripcion_lista']."</td>\n";
          $html .= "	    <td >".$dtl['observacion']."</td>\n";
          $html .= "	    <td >".$dtl['fecha_inicio_lista']."</td>\n";
          $html .= "	    <td >".$dtl['fecha_fin_lista']."</td>\n";
          
          $dat['lista_codigo'] = $dtl['lista_codigo'];
          
          $mc = "INACTIVA";
          if($dtl['sw_estado'] == '1') $mc = "ACTIVA";
          
          $html .= "	    <td class=\"normal_10AN\" align=\"center\">".$mc."</td>\n";
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          if($dtl['sw_estado'] == '1')
          {
            $html .= "			  <a href=\"".$action['crear_detalle'].URLRequest($dat)."\" title=\"ADICIONAR CARGOS A LA LISTA\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "			</td>\n";
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          if($dtl['sw_estado'] == '1')
          {
            $html .= "			  <a href=\"".$action['informacion'].URLRequest($dat)."\" title=\"MODIFICAR INFORMACION\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "			</td>\n";          
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          if($dtl['sw_estado'] == '1')
          {
            $html .= "			  <a href=\"".$action['proveedor'].URLRequest($dat)."\" title=\"VINCULAR PROVEEDORES A LA LISTA\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/proveedorac.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "			</td>\n";
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          if($dtl['sw_estado'] == '1')
          {
            $html .= "			  <a href=\"".$action['plan'].URLRequest($dat)."\" title=\"PARAMETRIZAR COBERTURA\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/pcopagos.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "			</td>\n";  
          
          $dat['sw_estado'] = $dtl['sw_estado'];
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          $html .= "			  <a href=\"".$action['ver_detalle'].URLRequest(array("lista"=>$dat))."\" title=\"VER DETALLE DE LA LISTA\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
          $html .= "        </a>\n";
          $html .= "			</td>\n";
          $html .= "			<td align=\"center\" class=\"label_error\">\n";
          if($dtl['sw_estado'] == '1')
          {
            $dat['estado'] = "0";
            $html .= "			  <a title=\"INACTIVAR LISTA\" href=".$action['activar'].URLRequest($dat)." onclick=\"return confirm('Esta seguro que desea inactivar la lista de precios Nº ".$dtl['lista_codigo']." ?');\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          else
          {
            $dat['estado'] = "1";
            $html .= "			  <a title=\"ACTIVAR LISTA\" href=".$action['activar'].URLRequest($dat)." >\n";
            $html .= "          <img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">\n";
            $html .= "        </a>\n";
          }
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "</table><br>\n";
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "<br>\n";
      }
      else
      {
        if($request)
          $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
      }
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\">\n";
      $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
			$html .= "</form>\n";
      return $html;
    }
    /**
    * Funcion deonde se muestra el detalle de una lista de precios determinada
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $request Arreglo de datos del request
    * @param array $listado Arreglo de datos del detalle de la lista
    * @param int $conteo Cantidad de da datos retornasdos
    * @param int $pagina Pagina que se esta viendo actualmente
    *
    * @return string
    */
    function FormaListasPreciosDetalle($action,$request,$listado,$conteo,$pagina)
    {
 			$html  = "	<script>\n";
      $html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
      $html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
      
      $html .= "    function IsNumeric(valor)\n";
      $html .= "    {\n";
      $html .= "      if((valor*1) < 0 ) valor = valor*-1;\n";
			$html .= "    	var log = valor.length; \n";
			$html .= "    	var sw='S';\n";
			$html .= "    	var puntos = 0;\n";
			$html .= "    	for (x=0; x<log; x++)\n";
			$html .= "    	{ \n";
			$html .= "    		v1 = valor.substr(x,1);\n";
			$html .= "    		v2 = parseInt(v1);\n";
			$html .= "    		//Compruebo si es un valor num?ico\n";
			$html .= "    		if(v1 == '.')\n";
			$html .= "    		{\n";
			$html .= "    			puntos ++;\n";
			$html .= "    		}\n";
			$html .= "    		else if (isNaN(v2)) \n";
			$html .= "    		{ \n";
			$html .= "    			sw= 'N';\n";
			$html .= "    			break;\n";
			$html .= "    		}\n";
			$html .= "    	}\n";
			$html .= "    	if(log == 0) sw = 'N';\n";
			$html .= "    	if(puntos > 1) sw = 'N';\n";
			$html .= "    	if(sw=='S')\n"; 
			$html .= "    		return true;\n";
			$html .= "    	return false;\n";
			$html .= "    } \n";
      
      $html .= "	  function acceptNum(evt)\n";
			$html .= "	  {\n";
			$html .= "	  	var nav4 = window.Event ? true : false;\n";
			$html .= "	  	var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		  return (key <= 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);\n";
			$html .= "	  }\n";
      
      $html .= "    function ModificarValor(lista_codigo,tarifario_id,cargo,precio,porcentaje,i)\n";
			$html .= "	  {\n";
			$html .= "	    xajax_ModificarValor(lista_codigo,tarifario_id,cargo,precio,porcentaje,i);\n";
			$html .= "	  }\n";
      
      $html .= "    function SeleccionarTodos(frm)\n"; 
      $html .= "    {\n";
      $html .= "      valor = frm.todos.checked;\n";
      $html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "		    if(frm[i].type == 'checkbox')\n";
			$html .= "		      frm[i].checked = valor;\n";
			$html .= "		  }\n";
			$html .= "		}\n";      
      $html .= "    function EliminarCargos(frm)\n"; 
      $html .= "    {\n";
      $html .= "      flag = false;\n";
      $html .= "      valor = frm.todos.checked;\n";
      $html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "		    if(frm[i].type == 'checkbox')\n";
      $html .= "		    {\n";
			$html .= "		      if(frm[i].checked)\n";
			$html .= "		        flag = true;\n";
			$html .= "		    }\n";
			$html .= "		  }\n";
			$html .= "		  if(flag == true)\n";
			$html .= "		  {\n";
			$html .= "		    frm.action = \"".$action['eliminar']."\";\n";
			$html .= "		    frm.submit();\n";
			$html .= "		  }\n";
			$html .= "		}\n";
      $html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  { \n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
			$html .= "	</script>\n";
      $html .= ThemeAbrirTabla('DETALLE DE LA LISTA Nº '.$request['lista']['lista_codigo']);

      $st = " style=\"text-align:left;text-indent:8pt\"";
      
			$html .= "<form name=\"formabuscar\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "	<table width=\"62%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"formulacion_table_list\" >\n";
			$html .= "			<td $st width=\"25%\" >CARGO</td>\n";
			$html .= "			<td $st width=\"25%\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" name=\"buscador[cargo]\" class=\"input-text\" style=\"width:90%\" value=\"".$request['buscador']['cargo']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td $st >CUPS</td>\n";
      $html .= "			<td $st width=\"25%\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" name=\"buscador[cargo_cups]\" class=\"input-text\"  style=\"width:90%\" value=\"".$request['buscador']['cargo_cups']."\">\n";
			$html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
			$html .= "			<td $st width=\"25%\" >DESCRIPCION CARGO</td>\n";
			$html .= "			<td $st colspan=\"3\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" name=\"buscador[descripcion_cargo]\" class=\"input-text\"  style=\"width:95%\" value=\"".$request['buscador']['descripcion_cargo']."\">\n";
			$html .= "			</td>\n";
      $html .= "	  </tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
			$html .= "			<td $st width=\"25%\" >DESCRIPCION CUPS</td>\n";
			$html .= "			<td $st colspan=\"3\" class=\"modulo_list_claro\" >\n";
			$html .= "			  <input type=\"text\" name=\"buscador[descricpion_cups]\" class=\"input-text\" style=\"width:95%\" value=\"".$request['buscador']['descripcion_cups']."\">\n";
			$html .= "			</td>\n";
      $html .= "	  </tr>\n";
			$html .= "  </table>\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "		<td align='center' width=\"50%\">\n";
      $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
      $html .= "		</td>\n";
      $html .= "		<td align='center' colspan=\"2\">\n";
      $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscar)\" value=\"Limpiar Campos\">\n";
      $html .= "		</td>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";

      if(!empty($listado))
      {
        $pghtml = AutoCarga::factory('ClaseHTML');
        
        $html .= "<form name=\"detalle\" id=\"detalle\" method=\"post\">\n";
        if($request['lista']['sw_estado'] == '1')
        {
          $html .= "  <table width=\"60%\" align=\"center\">\n";
          $html .= "    <tr>\n";
          $html .= "	    <td align=\"center\">\n";
          $html .= "		    <input class=\"input-submit\" type=\"button\" name=\"eliminar\" value=\"Eliminar Seleccionados\" onclick=\"EliminarCargos(document.detalle)\">\n";
          $html .= "		  </td>\n";
          $html .= "	  </tr>\n";
          $html .= "  </table><br>\n";
        }
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">\n";
        $html .= "  	  <td width=\"7%\">CARGO</td>\n";
        $html .= "  	  <td width=\"30%\">DESCRIPCION CUPS</td>\n";
        $html .= "			<td width=\"35%\">DESCRIPCION CARGO</td>\n";
        $html .= "  	  <td width=\"8%\">V CARGO</td>\n";
        $html .= "  	  <td width=\"8%\">V LISTA</td>\n";
        $html .= "  	  <td width=\"8%\">% MOD</td>\n";
        if($request['lista']['sw_estado'] == '1')
        {
          $html .= "			<td width=\"2%\"></td>\n";
          $html .= "			<td width=\"2%\">\n";
          $html .= "	      <input type=\"checkbox\" name=\"todos\" onclick=\"SeleccionarTodos(document.detalle)\">\n";
          $html .= "      </td>\n";
        }
        $html .= "		</tr>\n";
        
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        $i =0;
        foreach($listado as $key => $cargos)
        {
          $html .= "      <tr class=\"formulacion_table_list\">\n";
          $html .= "        <td colspan=\"8\">TARIFARIO: ".$key."</td>\n";
          $html .= "      </tr>\n";
          foreach($cargos as $key => $dtl)
          {
            ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro"; 
            ($back == "#DDDDDD")? $back = "#CCCCCC":$back = "#DDDDDD";
            
            $porc = 100 * ($dtl['valor'] - $dtl['precio'])/$dtl['precio'];
            
            $html .= "	  <tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
            $html .= "	    <td >".$dtl['cargo']."</td>\n";
            $html .= "	    <td >".$dtl['descripcion_cups']."</td>\n";
            $html .= "	    <td >".$dtl['descripcion']."</td>\n";
            $html .= "	    <td align=\"right\">$".formatoValor($dtl['precio'])."</td>\n";
            $html .= "	    <td align=\"right\">\n";
            $html .= "	      <div id=\"div_".$i."\">\n";
            $html .= "          $".formatoValor($dtl['valor'])."\n";
            $html .= "        </div>\n";
            $html .= "      </td>\n";
            if($request['lista']['sw_estado'] == '1')
            {            
              $html .= "	    <td align=\"right\">\n";
              $html .= "	      <input type=\"text\" name=\"pc".$i."\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" style=\"width:80%\" value=\"".$porc."\">\n";
              $html .= "      </td>\n";
              $html .= "			<td align=\"center\" class=\"label_error\">\n";
              $html .= "			  <a href=\"javascript:ModificarValor('".$request['lista']['lista_codigo']."','".$dtl['tarifario_id']."','".$dtl['cargo']."','".$dtl['precio']."',document.detalle.pc".$i.".value,'".$i."')\">\n";
              $html .= "          <img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\">\n";
              $html .= "        </a>\n";
              $html .= "			</td>\n";
              $html .= "			<td align=\"center\" class=\"label_error\">\n";
              $html .= "	      <input type=\"checkbox\" name=\"cargos[".$dtl['tarifario_id']."][".$dtl['cargo']."]\" value=\"".$dtl['cargo']."\">\n";
              $html .= "			</td>\n";
            }
            else
            {
              $html .= "	    <td align=\"right\">\n";
              $html .= "	      <label class=\"normal_10AN\">".$porc."%</label>\n";
              $html .= "      </td>\n";
            }
            $html .= "		</tr>\n";
            $i++;
          }
        }
        $html .= "</table><br>\n";
        if($request['lista']['sw_estado'] == '1')
        {
          $html .= "<table width=\"60%\" align=\"center\">\n";
          $html .= "  <tr>\n";
          $html .= "	  <td align=\"center\">\n";
          $html .= "		  <input class=\"input-submit\" type=\"button\" name=\"eliminar\" value=\"Eliminar Seleccionados\" onclick=\"EliminarCargos(document.detalle)\">\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";
          $html .= "</table>\n";
        }
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "<br>\n";
        $html .= "</form>\n";
      }
      else
      {
        if($request)
          $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
      }
 			
      $html .= "<form name=\"formacerrar\" action=\"".$action['volver']."\" method=\"post\">";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "	    <td align=\"center\">\n";
      $html .= "		    <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Volver\">\n";
      $html .= "		  </td>\n";
      $html .= "	  </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla(); 
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar los proveedores de los cargoa y los
    * que ya han sido asociados a una lista determinada
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $request Arreglo con los datos del request
    * @param array $lista Arreglo con los datos de los proveedores ya asociados a la lista
    * @param array $proveedores Arreglo con los datos de los proveedores
    *
    * @return string
    */
    function FormaListasProveedores($action,$request,$lista,$proveedores)
    {
      $html  = ThemeAbrirTabla('PROVEEDORES ASOCIADOS A LA LISTA DE PRECIOS Nº '.$request['lista_codigo']);
      $html .= "<form name=\"buscador\" id=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "	<script>\n";
      $html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";			
      $html .= "		function Desvincular(codigo_proveedor)\n";
			$html .= "		{\n";
			$html .= "			xajax_DesvincularProveedor(codigo_proveedor,'".$request['lista_codigo']."');\n";
			$html .= "		}\n";
      
      $html .= "		function Vincular(frm)\n";
			$html .= "		{\n";
			$html .= "			if(frm.codigo_proveedor.value == '-1')\n";
			$html .= "			  document.getElementById('error').innerHTML = 'SELECCIONAR PROVEEDOR';\n";
			$html .= "			else\n";
			$html .= "			{\n";
 			$html .= "			  document.getElementById('error').innerHTML = '<br>';\n";
			$html .= "			  xajax_VincularProveedor(xajax.getFormValues('buscador'),'".$request['lista_codigo']."');\n";
			$html .= "		  }\n";
			$html .= "		}\n";
      
      $html .= "	</script>\n";
			$html .= "	<table width=\"62%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td colspan=\"2\">ASOCIAR PROVEEDORES A LA LISTA DE PRECIOS</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td width=\"25%\" >PROVEEDOR</td>\n";
      $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "			  <select name=\"codigo_proveedor\" class=\"select\">\n";
      $html .= "				  <option value='-1'>-----SELECCIONAR-----</option>\n";
      
      foreach($proveedores as $key => $dtl)
        $html.= "						<option value='".$dtl['codigo_proveedor_id']."' >".$dtl['nombre_tercero']."</option>\n";			
      
      $html .= "					</select>\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";	
      $html .= "			<tr class=\"formulacion_table_list\">\n";
			$html .= "			  <td >NUMERO CONTRATO</td>\n";
			$html .= "			  <td class=\"modulo_list_claro\" align=\"left\" >\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"numero_contrato\" id =\"numero_contrato\" value=\"\" style=\"width:65%\" maxlenght=\"20\">\n";
      $html .= "        </td>\n";
			$html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
      $html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Vincular Proveedor\" onClick=\"Vincular(document.buscador)\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "		</table>\n";
      $html .= "    <div id=\"error\" style=\"text-align:center\" class=\"label_error\"><br></div>\n";
      $html .= "</form>\n";
      $html .= "<div id=\"proveedores_asociados\">\n";
      $html .= $this->ListadoProveedores($lista);
      $html .= "</div>\n";
      $html .= "<form name=\"cerrar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
      $html .= "		<tr>\n";
      $html .= "			<td align=\"center\">\n";
      $html .= "				<input type=\"submit\" name=\"Cerrar\" value=\"Volver\" class=\"input-submit\">\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      $html .= "	</table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la lista de proveedores asociados
    *
    * @param array $listado Arreglo con los datos de los proveedores ya asociados a la lista
    *
    * @return string
    */
    function ListadoProveedores($listado)
    {
      $html = "";
      if(empty($listado))
      {
        $html .= "<center>";
        $html .= "  <label class=\"label_error\">NO EXISTE PROVEEDORES ASOCIADOS A ESTA LISTA</label>\n";
        $html .= "</center>";
      }
      else
      {
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"2\" width=\"60%\">PROVEEDOR</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
        
        $est = "modulo_list_claro";
        
        foreach($listado as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td width=\"20%\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." </td>\n";
          $html .= "    <td>".$dtl['nombre_tercero']."</td>\n";
          $html .= "    <td>".$dtl['direccion']."</td>\n";
          $html .= "    <td>".$dtl['telefono']."</td>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a title=\"DESVINCULAR PROVEEDOR\" href=\"javascript:Desvincular('".$dtl['codigo_proveedor_id']."')\" onclick=\"return confirm('Esta seguro que desea desvincular al proveedor \\n ".$dtl['nombre_tercero']." de la lista de precios?');\">\n";
          $html .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
          $html .= "      <a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
      }

      return $html;
    }
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"block\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";	
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
      $html .= "		ele.innerHTML = 'MENSAJE';\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
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
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "		<form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
			$html .= "		  <div id=\"ventana\" ></div>\n";
      $html .= "		  <div id=\"erroro\" class=\"label_error\" style=\"text-align:center\"></div>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";

			return $html;
		}
    /**
    * Funcion donde se crea la lista de proveedores asociados
    *
    * @param array $listado Arreglo con los datos de los proveedores ya asociados a la lista
    *
    * @return string
    */
    function FormaListasPlanes($action,$request,$listado)
    {
      $ctl = AutoCarga::factory('ClaseUtil');
      $html  = $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->AcceptDate("/");
      $html .= $ctl->AcceptNum(true);
      $html .= ThemeAbrirTabla('PARAMETRIZACION DE PLANES');
      $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td width=\"5%\">PLAN</td>\n";
      $html .= "    <td width=\"60%\">DESCRIPCION</td>\n";
      $html .= "    <td width=\"30%\" colspan=\"2\">VIGENCIA</td>\n";
      $html .= "    <td width=\"5%\">OP</td>\n";
      $html .= "  </tr>\n";
      
      $est = "modulo_list_claro";
      
      foreach($listado as $key => $dtl)
      {
        ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td >".$dtl['plan_id']." </td>\n";
        $html .= "    <td >".$dtl['plan_descripcion']."</td>\n";
        $html .= "    <td >".$dtl['fecha_inicio']."</td>\n";
        $html .= "    <td >".$dtl['fecha_fin']."</td>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a title=\"PARAMETRIZAR COBERTURA\" href=\"#\" onclick=\"javascript:xajax_MostrarTiposAfiliados('".$dtl['plan_id']."','".$request['lista_codigo']."')\" >\n";
        $html .= "        <img src=\"".GetThemePath()."/images/pcopagos.png\" border=\"0\">\n";
        $html .= "      <a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $html .= "<form name=\"cerrar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
      $html .= "		<tr>\n";
      $html .= "			<td align=\"center\">\n";
      $html .= "				<input type=\"submit\" name=\"Cerrar\" value=\"Volver\" class=\"input-submit\">\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      $html .= "	</table>\n";
      $html .= "</form>\n";
      $html .= $this->CrearVentana();
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para la modificacion de la informacion de un alista de precios
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $informacion Arreglo con la informacion de la lista de precios
    *
    * @return string
    */
    function FormaModificarInformacionLista($action,$informacion)
    {
      $html  = ThemeAbrirTabla('MODIFICAR INFORMACION DE LA LISTA Nº '.$informacion['lista_codigo']);
      $html .= $this->FormaCrearListaPrecios($action,$informacion);
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
  }
?>