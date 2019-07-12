<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaSolicitudesHTML.class.php,v 1.4 2008/11/14 21:27:49 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ListaSolicitudesHTML
  * Clase encargada de crear las formas para el manejo de las solicitudes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaSolicitudesHTML
  {
    /**
    * Contructor de la clase
    */
    function ListaSolicitudesHTML(){}
    /**
    * Funcion donde se crea la forma para mostrsr las opciones de las solicitudes
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $planes Arreglo con los datos de los planes
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $listado Arreglo con los datos de las solicitudes de ordenes
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaOrdenes($action,$planes,$tiposdocumentos,$request,$listado,$conteo,$pagina)
    {
      $html  = "  <script>\n";
      $html .= "		function ValidarDatos(forma)\n";
			$html .= "		{ \n";
			$html .= "			error = document.getElementById('error');\n";
			$html .= "			if(forma.plan_id.values == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL PLAN\";\n";
			$html .= "				return\n";
			$html .= "			} \n";
			$html .= "			if(forma.tipo_id_paciente.values == '-1')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO DE PACIENTE\";\n";
			$html .= "				return\n";
			$html .= "			} \n";
      $html .= "			if(forma.paciente_id.values == '')\n";
			$html .= "			{ \n";
			$html .= "				error.innerHTML = \"SE DEBE INGRESAR EL DOCUMENTO DE PACIENTE\";\n";
			$html .= "				return\n";
			$html .= "			} \n";
			$html .= "			xajax_ValidarPaciente(xajax.getFormValues('solicitud')); \n";
			$html .= "		}\n";
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
 			$html .= ThemeAbrirTabla('SOLICITUDES DE SERVICIOS MEDICOS');
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>	tabPane = new WebFXTabPane( document.getElementById( \"APD\" ),true ); </script>\n";
      $html .= "				<div class=\"tab-page\" id=\"pendientes\">\n";
      $html .= "				  <h2 class=\"tab\">LISTADO DE SOLICITUDES A AUTORIZAR</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"pendientes\")); </script>\n";
      $html .= $this->FormaListaSolicitudes($action,$planes,$tiposdocumentos,$request,$listado,$conteo,$pagina);
      $html .= "        </div>\n";
      
      $html .= "				<div class=\"tab-page\" id=\"solicitud\">\n";
      $html .= "				  <h2 class=\"tab\">CREAR SOLICITUDES</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"solicitud\")); </script>\n";
      $html .= $this->FormaCrearSolicitud($action,$planes,$tiposdocumentos);
      $html .= "        </div>\n";
      
      $html .= "      </div>\n";
			$html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla(); 
      return $html;
    }
    /**
    * Funcion donde se crea la forma para la solicitud de los datos de plan e 
    * identificacion del paciente
    *
    * @param array $action Vector con los links de la forma
    * @param array $planes Arreglo con los datos de los planes
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    *
    * @return string
    */
    function FormaCrearSolicitud($action,$planes,$tiposdocumentos)
    {
      $html  = "<center>\n";
      $html .= "  <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "</center>\n";
      $html .= "<form name=\"solicitud\" id=\"solicitud\" action=\"javascript:ValidarDatos(document.solicitud)\" method=\"post\">\n";
			$html .= "  <table width=\"70%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_list_title\" >\n";
			$html .= "      <td align=\"left\" style=\"text-indent:8pt\">PLAN:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  <select name=\"plan_id\" class=\"select\">\n";
			$html .= "          <option value = '-1'>--  SELECCIONE --</option>\n";
 			foreach($planes as $key => $dtl)
			  $html .= "          <option value=\"".$dtl['plan_id']."\">".$dtl['plan_descripcion']."</option>\n";

			$html .= "			  </select>\n";
			$html .= "	    </td>\n";
			$html .= "		</tr>\n";
			$html .= "    <tr class=\"modulo_table_list_title\" >\n";
			$html .= "      <td align=\"left\" style=\"text-indent:8pt\">TIPO DOCUMENTO:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  <select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "          <option value = '-1'>--  SELECCIONE --</option>\n";
			
			foreach($tiposdocumentos as $key => $dtl)
				$html .= "          <option value=\"".$key."\">".$dtl['descripcion']."</option>";
				
			$html .= "			  </select>\n";
			$html .= "	    </td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"left\" style=\"text-indent:8pt\">DOCUMENTO:</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"paciente_id\" maxlength=\"32\">\n";
      $html .= "      </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table width=\"70%\" align=\"center\">\n";
			$html .= "	  <tr>\n";
			$html .= "			<td align='center'>\n";
			$html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			$html .= "    </form>\n";
      $html .= "    <form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\">\n";
      $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Cancelar\">\n";
			$html .= "		  </td>\n";
			$html .= "    </form>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la lista de solicitudes
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $planes Arreglo con los datos de los planes
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $listado Arreglo con los datos de las solicitudes de ordenes
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaListaSolicitudes($action,$planes,$tiposdocumentos,$request,$listado,$conteo,$pagina)
    {
      $html .= "<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\"><legend >BUSCADOR AVANZADO</legend>\n";
			$html .= "				<form name=\"formabuscar\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "					<table width=\"100%\" align=\"center\" border=\"0\">";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\" width=\"20%\">TIPO DOCUMENTO: </td>\n";
			$html .= "						  <td colspan=\"3\">\n";
			$html .= "								<select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
			$html .= "                	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tiposdocumentos as $key => $tipos)
			{
				($request['tipo_id_paciente'] == $key)? $csk = "selected": $csk = ""; 
				$html .= "                	<option value=\"$key\" $csk >".$tipos['descripcion']."</option>";
			}
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">DOCUMENTO:</td>\n";
			$html .= "							<td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" maxlength=\"32\" value=".$request['paciente_id']."></td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PRIMER NOMBRE:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[nombres1]\" maxlength=\"32\" value=".$request['nombres1']."></td>\n";
			$html .= "							<td class=\"normal_10AN\">SEGUNDO NOMBRE:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[nombres2]\" maxlength=\"32\" value=".$request['nombres2']."></td>\n";			
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PRMIER APELLIDO:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[apellidos1]\" maxlength=\"32\" value=".$request['apellidos1']."></td>\n";
			$html .= "							<td class=\"normal_10AN\">SEGUNDO APELLIDO:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[apellidos2]\" maxlength=\"32\" value=".$request['apellidos2']."></td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PLAN:</td>\n";
			$html .= "							<td colspan=\"3\">\n";
			$html .= "								<select name=\"buscador[plan_id]\" class=\"select\">";
			$html .= "                	<option value = '-1'>--  SELECCIONE --</option>\n";
			$chk = "";
			foreach($planes as $key => $dtl)
			{
				($request['plan_id'] == $dtl['plan_id'])? $chk = "selected": $chk = ""; 
				$html .= "                	<option value=\"".$dtl['plan_id']."\" $chk>".$dtl['plan_descripcion']."</option>\n";
			}
			$html .= "								</select>\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">No. SOLICITUD</td>\n";
			$html .= "							<td colspan=\"3\">\n";
			$html .= "								<input type=\"text\" class=\"input-text\" name=\"buscador[numero_solicitud_orden]\" maxlength=\"32\" value=\"".$request['numero_solicitud_orden']."\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td align='center'>\n";
			$html .= "								<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "							</td>\n";
      $html .= "							<td align='center' colspan=\"2\">\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscar)\" value=\"Limpiar Campos\">\n";
			$html .= "							</td>\n";
			$html .= "				    </form>\n";
      $html .= "            <form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		          <td align='center' >\n";
      $html .= "				        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Cancelar\">\n";
			$html .= "		          </td>\n";
			$html .= "            </form>\n";
			$html .= "						</tr>\n";
			$html .= "					</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";

      if(!empty($listado))
      {
        $pghtml = AutoCarga::factory('ClaseHTML');

        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">\n";
        $html .= "  	  <td width=\"8%\">Nº SOL.</td>\n";
        $html .= "  	  <td width=\"10%\">FECHA</td>\n";
        $html .= "			<td width=\"%\" colspan=\"2\">DATOS DEL PACIENTE</td>\n";
        $html .= "  	  <td width=\"20%\">PLAN</td>\n";
        $html .= "			<td width=\"5%\" >OPCION</td>\n";
        $html .= "		</tr>\n";
        
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        
        foreach($listado as $key => $ordenes)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro"; 
          ($back == "#DDDDDD")? $back = "#CCCCCC":$back = "#DDDDDD";
          
          $dat['plan_id'] = $ordenes['plan_id'];
          $dat['paciente_id'] = $ordenes['paciente_id'];            
          $dat['tipo_id_paciente'] = $ordenes['tipo_id_paciente'];            
          $dat['numero_solicitud_orden'] = $ordenes['numero_solicitud_orden'];            
          
          $html .= "	  <tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
          $html .= "	    <td >".$ordenes['numero_solicitud_orden']."</td>\n";
          $html .= "	    <td >".$ordenes['fecha_registro']."</td>\n";
          $html .= "	    <td width=\"10%\">".$ordenes['tipo_id_paciente']." - ".$ordenes['paciente_id']."</td>\n";
          $html .= "			<td >".trim($ordenes['primer_nombre']." ".$ordenes['segundo_nombre']." ".$ordenes['primer_apellido']." ".$ordenes['segundo_apellido'])."</td>\n";						
          $html .= "			<td >".$ordenes['plan_descripcion']."</td>\n";						
          $html .= "			<td width=\"10%\" align=\"center\" class=\"label_error\">\n";
          $html .= "			  <a href=".$action['autorizar'].URLRequest($dat).">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/autorizadores.png\" border=\"0\">AUTO\n";
          $html .= "        </a>\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "										</table><br>\n";
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "								<br>\n";
      }
      else
      {
        if($request)
          $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
      }
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar los proveedores de los cargoa
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $proveedores Arreglo con los datos de los proveedores
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $listado Arreglo con los datos de las ordenes de servicios
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaListaOdenes($action,$proveedores,$tiposdocumentos,$request,$listado,$conteo,$pagina)
    {
      $html  = "  <script>\n";
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
      $html .= "		function acceptNum(evt)\n";
			$html .= "		{\n";
			$html .= "			var nav4 = window.Event ? true : false;\n";
			$html .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "		}\n";
      $html .= "  </script>\n";
 			$html .= ThemeAbrirTabla('ORDENES DE SERVICIOS MEDICOS');
      $html .= "<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\"><legend >BUSCADOR AVANZADO</legend>\n";
			$html .= "				<form name=\"formabuscar\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "					<table width=\"100%\" align=\"center\" border=\"0\">";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\" width=\"20%\">TIPO DOCUMENTO: </td>\n";
			$html .= "						  <td colspan=\"3\">\n";
			$html .= "								<select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
			$html .= "                	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tiposdocumentos as $key => $tipos)
			{
				($request['tipo_id_paciente'] == $key)? $csk = "selected": $csk = ""; 
				$html .= "                	<option value=\"$key\" $csk >".$tipos['descripcion']."</option>";
			}
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "			      </tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">DOCUMENTO:</td>\n";
			$html .= "							<td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" maxlength=\"32\" value=".$request['paciente_id']."></td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PRIMER NOMBRE:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[nombres1]\" maxlength=\"32\" value=".$request['nombres1']."></td>\n";
			$html .= "							<td class=\"normal_10AN\">SEGUNDO NOMBRE:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[nombres2]\" maxlength=\"32\" value=".$request['nombres2']."></td>\n";			
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PRMIER APELLIDO:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[apellidos1]\" maxlength=\"32\" value=".$request['apellidos1']."></td>\n";
			$html .= "							<td class=\"normal_10AN\">SEGUNDO APELLIDO:</td>\n";
			$html .= "							<td><input type=\"text\" class=\"input-text\" name=\"buscador[apellidos2]\" maxlength=\"32\" value=".$request['apellidos2']."></td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">PROVEEDOR:</td>\n";
			$html .= "							<td colspan=\"3\">\n";
			$html .= "								<select name=\"buscador[codigo_proveedor_id]\" class=\"select\">";
			$html .= "                	<option value = '-1'>--  SELECCIONE --</option>\n";
			$chk = "";
			foreach($proveedores as $key => $dtl)
			{
				($request['codigo_proveedor_id'] == $dtl['codigo_proveedor_id'])? $chk = "selected": $chk = ""; 
				$html .= "                	<option value=\"".$dtl['codigo_proveedor_id']."\" $chk>".$dtl['nombre_tercero']."</option>\n";
			}
			$html .= "								</select>\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td class=\"normal_10AN\">No. ORDEN SERVICIO</td>\n";
			$html .= "							<td colspan=\"3\">\n";
			$html .= "								<input type=\"text\" class=\"input-text\" name=\"buscador[eps_orden_servicio]\" maxlength=\"32\" value=\"".$request['eps_orden_servicio']."\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td align='center' colspan=\"2\">\n";
			$html .= "								<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "							</td>\n";
      $html .= "							<td align='center' colspan=\"2\">\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscar)\" value=\"Limpiar Campos\">\n";
			$html .= "							</td>\n";
			$html .= "						</tr>\n";
			$html .= "					</table>\n";
			$html .= "			  </fieldset>\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table><br>\n";
			$html .= "</form>\n";
            
      if(!empty($listado))
      {
        $pghtml = AutoCarga::factory('ClaseHTML');

        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
        $html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">\n";
        $html .= "  	  <td width=\"7%\">Nº ORD.</td>\n";
        $html .= "  	  <td width=\"8%\">FECHA</td>\n";
        $html .= "			<td width=\"33%\" colspan=\"2\">PROVEEDOR</td>\n";
        $html .= "			<td width=\"32%\" colspan=\"2\">PACIENTE</td>\n";
        $html .= "  	  <td width=\"14%\">ESTAMENTO</td>\n";
        $html .= "			<td width=\"6%\"  colspan=\"2\">OP.</td>\n";
        $html .= "		</tr>\n";
        
        $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
        
        foreach($listado as $key => $proveedores)
        {
          foreach($proveedores as $key1 => $ordenes)
          {
            ($est == "modulo_list_oscuro")? $est = "modulo_list_claro":$est = "modulo_list_oscuro"; 
            
            ($back == "#CCCCCC")? $back = "#DDDDDD":$back = "#CCCCCC";          
          
            $dat['codigo_proveedor_id'] = $ordenes['codigo_proveedor_id'];            
            $dat['eps_orden_servicio'] = $ordenes['eps_orden_servicio'];            
            $dat['tipo_id_paciente'] = $ordenes['tipo_id_paciente'];            
            $dat['paciente_id'] = $ordenes['paciente_id'];            
            $dat['empresa_id'] = $ordenes['empresa_id'];            
            
   					$reporte = new GetReports();
  					$mostrar = $reporte->GetJavaReport('app','UV_SolicitudesAutorizaciones','ordenes',$dat,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
  					$funcion = "orden".$key1.$reporte->GetJavaFunction();
  					$mostrar = str_replace("function W","function orden".$key1."W",$mostrar);
          
            $html .= "	  <tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
            $html .= "	    <td >".$ordenes['eps_orden_servicio']."</td>\n";
            $html .= "	    <td align=\"center\">".$ordenes['fecha_registro']."</td>\n";
            $html .= "	    <td width=\"12%\">".$ordenes['tipo_id_tercero']." ".$ordenes['tercero_id']."</td>\n";
            $html .= "	    <td width=\"%\">".$ordenes['nombre_tercero']."</td>\n";
            $html .= "	    <td width=\"10%\">".$ordenes['tipo_id_paciente']." ".$ordenes['paciente_id']."</td>\n";
            $html .= "			<td >".trim($ordenes['primer_nombre']." ".$ordenes['segundo_nombre']." ".$ordenes['primer_apellido']." ".$ordenes['segundo_apellido'])."</td>\n";						
            $html .= "			<td >".$ordenes['descripcion_estamento']."</td>\n";						
            $html .= "			<td width=\"10%\" align=\"center\" class=\"label_error\">\n";
            $html .= "			  <a href=".$action['ver'].URLRequest($dat)." title=\"VER ORDEN\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
            $html .= "        </a>\n";
            $html .= "			</td>\n";
            $html .= "			<td align=\"center\">\n";
            $html .= "				".$mostrar."\n";
            $html .= " 				<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"IMPRIMIR ORDEN\">\n";
            $html .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
            $html .= " 				</a>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
          }
        }
        $html .= "	</table><br>\n";
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "<br>\n";
      }
      else
      {
        if($request)
          $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
      }
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "  <table width=\"50%\" align='center' >\n";
			$html .= "    <tr >\n";
			$html .= "		  <td align='center' >\n";
      $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "    </form>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar las ordenes de servicios creadas
    *
    * @param array $action Arrglo con los datos de los links 
    * @param array $orden Arreglo con los datos de las ordenes
    * @param array $detalle Arreglo con los datos del detalle de las ordenes
    * @param array $numeros_ordenes Arreglo con los numeros de orden de servicios
    * @param string $empresa Identificador de la empresa
    *
    * @return string
    */
    function FormaMostarOrden($action,$orden,$detalle,$numeros_ordenes,$empresa)
    {
      $dat = array();
      $est = 'modulo_list_oscuro'; $back = "#DDDDDD";
 			$html = ThemeAbrirTabla('ORDENES DE SERVICIOS MEDICOS');
      foreach($orden as $key => $proveedores)
      {
        foreach($proveedores as $key1 => $ordenes)
        {
          $dat['codigo_proveedor_id'] = $ordenes['codigo_proveedor_id'];            
          $dat['eps_orden_servicio'] = $ordenes['eps_orden_servicio'];            
          $dat['tipo_id_paciente'] = $ordenes['tipo_id_paciente'];            
          $dat['paciente_id'] = $ordenes['paciente_id'];            
          $dat['empresa_id'] = $ordenes['empresa_id'];            
          
 					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','UV_SolicitudesAutorizaciones','ordenes',$dat,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = "orden".$key1.$reporte->GetJavaFunction();
					$mostrar = str_replace("function W","function orden".$key1."W",$mostrar);
          
          $sty = " style=\"text-align:left;text-indent:6pt\" ";
          $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "  	  <td $sty width=\"16%\">Nº ORDEN</td>\n";
          $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['eps_orden_servicio']."</td>\n";
          $html .= "  	  <td $sty width=\"16%\">Nº AUTORIZACION</td>\n";
          $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['autorizacion_id']."</td>\n";
          $html .= "  	  <td $sty width=\"16%\">FECHA</td>\n";
          $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\" >".$ordenes['fecha_registro']."</td>\n";
          $html .= "			<td class=\"modulo_list_oscuro\" align=\"center\" rowspan=\"4\">\n";
					$html .= "				".$mostrar."\n";
					$html .= " 				<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"IMPRIMIR ORDEN\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$html .= " 				</a>\n";
					$html .= "			</td>\n";
          
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "  	  <td $sty >PROVEEDOR</td>\n";
          $html .= "	    <td $sty class=\"modulo_list_claro\">".$ordenes['tipo_id_tercero']." ".$ordenes['tercero_id']."</td>\n";
          $html .= "			<td $sty class=\"modulo_list_claro\" colspan=\"4\">".$ordenes['nombre_tercero']."</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "  	  <td $sty >DIRECCION</td>\n";
          $html .= "  	  <td $sty class=\"modulo_list_claro\" >".$ordenes['direccion']."</td>\n";
          $html .= "  	  <td $sty >TELEFONO</td>\n";
          $html .= "  	  <td $sty class=\"modulo_list_claro\" colspan=\"3\">".$ordenes['telefono']."</td>\n";
          $html .= "		</tr>\n";
          $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
          $html .= "  	  <td $sty >PACIENTE</td>\n";
          $html .= "	    <td $sty class=\"modulo_list_claro\" >".$ordenes['tipo_id_paciente']." ".$ordenes['paciente_id']."</td>\n";
          $html .= "			<td $sty class=\"modulo_list_claro\" colspan=\"2\">".trim($ordenes['primer_nombre']." ".$ordenes['segundo_nombre']." ".$ordenes['primer_apellido']." ".$ordenes['segundo_apellido'])."</td>\n";
          $html .= "  	  <td $sty >ESTAMENTO</td>\n";
          $html .= "	    <td $sty class=\"modulo_list_claro\" >".$ordenes['descripcion_estamento']."</td>\n";
          $html .= "		</tr>\n";
          
          if($ordenes['observacion'])
          {
            $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "	    <td colspan=\"7\">OSERVACION</td>\n";
            $html .= "		</tr>\n";
            $html .= "	  <tr align=\"justify\" class=\"modulo_table_list\">\n";
            $html .= "	    <td colspan=\"7\">".$ordenes['observacion']."</td>\n";
            $html .= "		</tr>\n";
          }
          
          if(!empty($detalle[$key][$key1]['cargos']))
          {
            $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "	    <td colspan=\"7\">CARGOS AUTORIZADOS</td>\n";
            $html .= "		</tr>\n";
            $html .= "	  <tr align=\"center\">\n";
            $html .= "	    <td colspan=\"7\">\n";
            $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "          <tr class=\"modulo_table_list_title\">\n";
            $html .= "	          <td width=\"10%\">TARIFARIO</td>\n";
            $html .= "		        <td width=\"10%\">CARGO</td>\n";
            $html .= "		        <td >DESCRIPCION</td>\n";
            $html .= "		        <td width=\"10%\">CANTIDAD</td>\n";
            $html .= "		        <td width=\"10%\">VALOR U</td>\n";
            $html .= "		        <td width=\"10%\">TOTAL</td>\n";
            $html .= "	        </tr>\n";
            foreach($detalle[$key][$key1]['cargos'] as $kc => $dtl_cargos_qx)
            {
              foreach($dtl_cargos_qx as $kcx => $dtl_cargos)
              {
                foreach($dtl_cargos as $kc1=> $dtl)
                {
                  $html1 .= "  <tr class=\"modulo_list_claro\">\n";
                  $html1 .= "    <td>".$dtl['tarifario_id']."</td>\n";
                  $html1 .= "    <td>".$dtl['cargo']."</td>\n";
                  $html1 .= "    <td align=\"justify\">".$dtl['descripcion_equivalencia']."</td>\n";
                  $html1 .= "		 <td>".$dtl['cantidad']."</td>\n";
                  $html1 .= "		 <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
                  $html1 .= "		 <td align=\"right\">$".formatoValor($dtl['valor']*$dtl['cantidad'])."</td>\n";
                  $html1 .= "  </tr>\n";
                }
                $html .= "  <tr class=\"formulacion_table_list\">\n";
                $html .= "    <td width=\"%\" colspan=\"6\">CUPS: ".$kc." ".$dtl['descripcion_base']."</td>\n";
                $html .= "  </tr>\n";
                $html .= $html1;
                $html1 = "";
              }
            }
            $html .= "        </table>\n";
            $html .= "		  </td>\n";
            $html .= "		</tr>\n";
          }
          
          if(!empty($detalle[$key][$key1]['medicamentos']))
          {
            $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "	    <td colspan=\"7\">MEDICAMENTOS AUTORIZADOS</td>\n";
            $html .= "		</tr>\n";
            $html .= "	  <tr align=\"center\">\n";
            $html .= "	    <td colspan=\"7\">\n";
            $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "          <tr class=\"modulo_table_list_title\">\n";
            $html .= "	          <td width=\"20%\">CODIGO</td>\n";
            $html .= "		        <td >DESCRIPCION</td>\n";
            $html .= "		        <td width=\"10%\">CANTIDAD</td>\n";
            $html .= "		        <td width=\"10%\">VALOR U</td>\n";
            $html .= "		        <td width=\"10%\">TOTAL</td>\n";
            $html .= "	        </tr>\n";            
            foreach($detalle[$key][$key1]['medicamentos'] as $kc => $dtl)
            {
              $html .= "	        <tr class=\"modulo_list_claro\">\n";
              $html .= "            <td>".$dtl['codigo_producto']."</td>\n";
              $html .= "            <td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
              $html .= "		        <td>".$dtl['cantidad']."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'] * $dtl['cantidad'])."</td>\n";
              $html .= "	        </tr>\n";
            }
            $html .= "        </table>\n";
            $html .= "		  </td>\n";
            $html .= "		</tr>\n";
          }
          
          if(!empty($detalle[$key][$key1]['conceptos']))
          {
            $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "	    <td colspan=\"7\">CONCEPTOS AUTORIZADOS</td>\n";
            $html .= "		</tr>\n";
            $html .= "	  <tr align=\"center\">\n";
            $html .= "	    <td colspan=\"7\">\n";
            $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "          <tr class=\"modulo_table_list_title\">\n";
            $html .= "		        <td >DESCRIPCION</td>\n";
            $html .= "		        <td width=\"10%\">VALOR</td>\n";
            $html .= "	        </tr>\n";            
            foreach($detalle[$key][$key1]['conceptos'] as $kc => $dtl)
            {
              $html .= "	        <tr class=\"modulo_list_claro\">\n";
              $html .= "            <td align=\"justify\">".$dtl['descripcion_concepto_adicional']."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html .= "	        </tr>\n";
            }
            $html .= "        </table>\n";
            $html .= "		  </td>\n";
            $html .= "		</tr>\n";          
          }
          

          $html .= "	</table><br>\n";
        }
      }
      if(sizeof($numeros_ordenes) > 1)
      {
        $dat = array();
        $dat['empresa_id'] = $empresa;
        $dat['numeros_ordenes'] = $numeros_ordenes;
        $reporte = new GetReports();
        $mostrar = $reporte->GetJavaReport('app','UV_SolicitudesAutorizaciones','ordenes',$dat,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $funcion = $reporte->GetJavaFunction();
        
        $html .= " 			<center>\n";
        $html .= "				".$mostrar."\n";
        $html .= " 				<a href=\"javascript:$funcion\" class=\"label_error\">\n";
        $html .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>Imprimir Todas Las Ordenes\n";
        $html .= " 				</a>\n";
        $html .= " 			</center>\n";
      }
      $html .= "<form name=\"cancelar\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "	<table width=\"70%\" align=\"center\">\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\">\n";
      $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }
?>