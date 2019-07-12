<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagarHTML.class.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: CuentasXPagarHTML
  * Clase en la que se crean las formas para el modulo de cuentas por pagar
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CuentasXPagarHTML
  {
    /**
    * Constructosr de la clase
    */
    function CuentasXPagarHTML(){}
		/**
		* Funcion donde se crea la forma para el ingreso de la radicacion de
    * la factura
    *
		* @param array $action Vector que continen los link de la aplicacion
    * @param array $tipos_cuentas Vector con los tipos de cuentas
    * @param array $auditores_medicos Vector con los auditores medicos
    * @param array $auditores_admin Vector con los auditores administrativos
    *
		* @return string
		*/
		function FormaRegistrarRadicacionFactura($action,$tipos_cuentas,$medios_pago,$auditores_medicos,$auditores_admin)
		{
			$html  = ThemeAbrirTabla('CUENTAS POR PAGAR');
			$html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.tipo_cuenta.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE CUENTA QUE SE VA A REGISTRAR\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";				
      $html .= "		if(!forma.tipo_servicio.disabled)\n";
      $html .= "		{\n";
      $html .= "		  if(forma.tipo_servicio.value == \"-1\")\n";
			$html .= "		  {\n";
			$html .= "			  objeto.innerHTML = \"EL TIPO DE CUENTA: \"+forma.tipo_cuenta.options[forma.tipo_cuenta.selectedIndex].text+\", EXIGE SE SELECCIONE EL TIPO DE SERVICIO\";\n";
			$html .= "			  return;\n";
			$html .= "		  }\n";	      
      $html .= "		  if(!forma.tipo_especialidad.disabled && forma.tipo_especialidad.value == \"-1\")\n";
			$html .= "		  {\n";
			$html .= "			  objeto.innerHTML = \"EL TIPO DE SERVICIO: \"+forma.tipo_servicio.options[forma.tipo_servicio.selectedIndex].text+\", EXIGE SE SELECCIONE LA ESPECIALIDAD\";\n";
			$html .= "			  return;\n";
			$html .= "		  }\n";			
			$html .= "		}\n";			
      $html .= "		if(forma.medio_pago.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL MEDIO PAGO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.auditor.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL AUDITOR ADMINISTRATIVO ASIGANDO PARA LA REVISION\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.tipo_ingreso[0].checked  == false && forma.tipo_ingreso[1].checked  == false)\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE INGRESO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.tipo_ingreso[1].checked  == true)\n";
      $html .= "		{\n";
			$html .= "	    if(forma.numero_digitos.value == '')\n";
			$html .= "	    {\n";
			$html .= "			  objeto.innerHTML = \"SE DEBE INDICAR EL NUMERO DE DIGITOS QUE SE RESERVARAN PARA EL PREFIJO DE LA FACTURA EN LOS RIPS\";\n";
			$html .= "		  	return;\n";
			$html .= "	    }\n";
			$html .= "	  }\n";
 			$html .= "		forma.action = \"".$action['manual']."\"; \n";
			$html .= "		forma.submit();\n";
			$html .= "	}\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"radicacion_factura\" id=\"radicacion_factura\" action=\"javascript:ValidarDatos(document.radicacion_factura)\" method=\"post\">";
			$html .= "	<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">RADICAR FACTURA</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DE CUENTA: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_cuenta\" class=\"select\" onChange=\"xajax_ObtenerServicio(xajax.getFormValues('radicacion_factura'))\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipos_cuentas as $key => $datos)
				$html .= "					<option value=\"".$datos['tipo_cxp']."\" >".$datos['tipo_cxp_descripcion']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DE SERVICIO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_servicio\" id=\"tipo_servicio\" class=\"select\" disabled=\"true\" onChange=\"xajax_ObtenerEspecialidad(xajax.getFormValues('radicacion_factura'))\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";	      
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">ESPECIALIDAD: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_especialidad\" id=\"tipo_especialidad\" class=\"select\" disabled=\"true\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";	
      
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">MEDIO DE PAGO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"medio_pago\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($medios_pago as $key => $dtl)
				$html .= "					<option value=\"".$dtl['cxp_medio_pago_id']."\" >".$dtl['descripcion_medio_pago']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">AUDITOR ADMINISTRATIVO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"auditor\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($auditores_admin as $key => $detalle)
				$html .= "					<option value=\"".$detalle['usuario_id']."\" >".$detalle['nombre']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">AUDITOR MEDICO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"auditor_medico\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($auditores_medicos as $key => $detalle)
				$html .= "					<option value=\"".$detalle['usuario_id']."\" >".$detalle['nombre']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >TIPO DE INGRESO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"radio\" name=\"tipo_ingreso\" value=\"1\" onclick=\"document.getElementById('digitos').style.display='none'\">MANUAL\n";
			$html .= "				<input type=\"radio\" name=\"tipo_ingreso\" value=\"2\" onclick=\"document.getElementById('digitos').style.display='block'\">RIPS\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<div id=\"digitos\" style=\"display:none\">\n";
			$html .= "	  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	    <tr class=\"modulo_table_list_title\">\n";
			$html .= "			  <td width=\"35%\" style=\"text-align:left;text-indent:8pt\">DIGITOS DEL PREFIJO: </td>\n";
			$html .= "	      <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "          <input size=\"3\" type=\"text\" name=\"numero_digitos\" class=\"input-text\" onkeypress=\"return acceptNum(event)\">\n";
			$html .= "	      </td>\n";
			$html .= "	    </tr>\n";
			$html .= "	  </table>\n";
			$html .= "	</div>\n";
 			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
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
		* Funcion donde se crea la forma para el ingreso de la radicacion de
    * la factura
    *
		* @param array $action Vector que continen los link de la aplicacion
    * @param array $proveedor Arreglo con la informacion de los proveedores
    *
		* @return string
		*/
		function FormaRegistrarRadicacionManual($action,$proveedor)
		{
			$html  = ThemeAbrirTabla('CUENTAS POR PAGAR');
			$html .= "<script>\n";
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
			$html .= "			//Compruebo si es un valor numerico\n";
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
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(!IsDate(forma.fecha_radicacion.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"LA FECHA DE RADICACION ES OBLIGATORIA O POSEE UN FORMATO INVALIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.proveedor.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL PROVEEDOR, CORRESPONDIENTE A LA RADICACION\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "	  f = forma.fecha_radicacion.value.split('/')\n";
			$html .= "	  f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	  f4 = new Date('".date("Y/m/d")."');\n";
      $html .= "	  if(f1 > f4)\n";
			$html .= "	  {\n";
      $html .= "		  objeto.innerHTML = 'LA FECHA DE RADICACION NO DEBE SER MAYOR A LA FECHA ACTUAL';\n";
			$html .= "	    return;\n";
			$html .= "	  }\n";
      $html .= "		forma.action = \"".$action['aceptar']."\"; \n";
			$html .= "		forma.submit();\n";
			$html .= "	}\n";
      $html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"radicacion_factura\" action=\"javascript:ValidarDatos(document.radicacion_factura)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">DATOS DEL ENVIO</td>\n";
			$html .= "		</tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td style=\"text-align:left;text-indent:8pt\">FECHA RADICACION</td>\n";
      $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "        <input size=\"12\" type=\"text\" name=\"fecha_radicacion\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">\n";
      $html .= "        ".ReturnOpenCalendario('radicacion_factura','fecha_radicacion','/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">PROVEEDOR: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"proveedor\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";			
			foreach($proveedor as $key => $datos)
      {
				$marca = $datos['nombre_tercero'];
        if (strlen($marca) > 45) $marca = substr($marca,0,45)." ..."; 
        $html .= "					<option value=\"".$datos['codigo_proveedor_id']."\" title=\"".$datos['nombre_tercero']."\">".$marca."</option>\n";
      }
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td colspan=\"2\">OBSERVACION</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td colspan=\"2\">\n";
      $html .= "        <textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
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
		* Funcion donde se crea la forma para el ingreso de la radicacion de
    * la factura
    *
		* @param array $action Vector que continen los link de la aplicacion
    * @param array $request Vector con los datos del request
    * @param array $tipos_documentos Vector con los tipos de documentos
    * @param array $planes Vector con los datos de los planes
    * @param array $proveedor Vector con los datos del proveedor
    *
		* @return string
		*/
		function FormaRegistrarFacturaManual($action,$request,$tipos_documentos,$planes,$proveedor)
		{
			$html  = ThemeAbrirTabla('CUENTAS POR PAGAR');
			$html .= "<script>\n";
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
			$html .= "			//Compruebo si es un valor numerico\n";
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
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.prefijo_factura.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"EL PREFIJO DE LA FACTURA ES OBLIGATORIO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.numero_factura.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"EL NUMERO DE LA FACTURA ES OBLIGATORIO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(!IsDate(forma.fecha_factura.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"LA FECHA DE LA FACTURA ES OBLIGATORIA O POSEE UN FORMATO DE FECHA INVALIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";      
      $html .= "		if(!IsNumeric(forma.valor_total.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"EL VALOR TOTAL ES OBLIGATORIO O POSEE UN FORMATO DE NUMERO INVALIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.valor_gravamen.value != \"\" && !IsNumeric(forma.valor_gravamen.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"EL CAMPO VALOR GRAVAMEN, POSEE UN FORMATO DE NUMERO INVALIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      $html .= "		if(forma.valor_iva.value != \"\" && !IsNumeric(forma.valor_iva.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"EL CAMPO VALOR IVA APLICADO, POSEE UN FORMATO DE NUMERO INVALIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
      
      $f = explode("/",$request['fecha_radicacion']);
      $html .= "	  f = forma.fecha_factura.value.split('/')\n";
			$html .= "	  f1 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	  f2 = new Date('".$f[2]."/".$f[1]."/".$f[0]."');\n";
      $html .= "	  if(f1 > f2)\n";
			$html .= "	  {\n";
      $html .= "		  objeto.innerHTML = 'LA FECHA DE LA FACTURA NO DEBE SER MAYOR A LA FECHA DE RADICACION ".$request['fecha_radicacion']."';\n";
			$html .= "	    return;\n";
			$html .= "	  }\n";  
      $html .= "	  forma.action = \"".$action['aceptar']."\"; \n";
			$html .= "		forma.submit();\n";
			$html .= "	}\n";
      $html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
      $html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "</script>\n";
      $html .= $this->FormaDatosProveedor($proveedor,60);
      $html .= "<br>\n";
			$html .= "<form name=\"radicacion_factura\" id=\"radicacion_factura\" action=\"javascript:ValidarDatos(document.radicacion_factura)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">DATOS DE LA FACTURA</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"40%\" style=\"text-align:left;text-indent:1pt\">* PREFIJO FACTURA: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"prefijo_factura\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"40%\" style=\"text-align:left;text-indent:1pt\">* Nº FACTURA: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"numero_factura\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      if(!empty($planes))
      {
        $html .= "		<tr class=\"modulo_table_list_title\">\n";
  			$html .= "			<td style=\"text-align:left;text-indent:8pt\">PLAN ASOCIADO AL DOCUMENTO: </td>\n";
  			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
  			$html .= "				<select name=\"numero_contrato\" class=\"select\">\n";
  			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
  			
  			foreach($planes as $key => $datos)
  				$html .= "					  <option value=\"".$datos['num_contrato']."\" >".$datos['plan_descripcion']."</option>\n";

  			$html .= "				</select>\n";
  			$html .= "			</td>\n";
  			$html .= "		</tr>\n";
      }
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO PACIENTE: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"afiliado_tipo_id\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipos_documentos as $key => $datos)
				$html .= "					  <option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";

			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO PACIENTE: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"afiliado_id\" value=\"\" style=\"width:50%\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td style=\"text-align:left;text-indent:1pt\">* FECHA FACTURA</td>\n";
      $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "        <input size=\"12\" type=\"text\" name=\"fecha_factura\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" >\n";
      $html .= "        ".ReturnOpenCalendario('radicacion_factura','fecha_factura','/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\">VALOR GRAVAMEN: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"valor_gravamen\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\">VALOR IVA APLICADO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"valor_iva\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";      
      $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\">* VALOR TOTAL: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "			  <input type=\"text\" class=\"input-text\" name=\"valor_total\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "		</tr>";
			$html .= "	</table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
		* Funcion donde se muestra la informacion resumida de las cuentas por pagar por tercero
		*
    * @param array $action Arreglo con la informacion de los links de la forma
    * @param array $cxp_cliente Arreglo con al informacion del resumen de las cuentas por pagar por tercerio
    * @param array $intervalos Arreglo de datos con la informacion de los intervalos
    * @param int $total Valor total de la cartera
    *
    * @return string
		*/
		function FormaMostrarCarteraClientes($action,$cxp_cliente,$intervalos,$total)
		{
			$html  = ThemeAbrirTabla('CUENTAS POR PAGAR');
			 
			if(sizeof($cxp_cliente) > 0)
			{
				$html .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$html .= "			<td class=\"formulacion_table_list\" style=\"font-size:10px;text-align:center\" width=\"50%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$html .= "			<td class=\"modulo_table_list_title\"  width=\"50%\"><b>CORRIENTE</b></td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";

				$estilo1 = "class=\"formulacion_table_list\" style=\"text-align:center;text-indent: 0pt\"";
				$estilo2 = "class=\"modulo_table_list_title\" style=\"text-align:center;text-indent: 0pt\" ";
				
				$html .= "	<table border=\"0\" width=\"63%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "		<tr >\n";
				$html .= "			<td class=\"formulacion_table_list\">PROVEEDOR</td>\n";
        foreach($intervalos as $key => $dtll)
        {
          $est = $estilo1;
          if($key == 0) $est = $estilo2;
          
          $html .= "      <td $est>".$dtll."</td>\n";
        }
			
				$html .= "			<td class=\"modulo_table_list_title\">SALDO</td>\n";
				$html .= "		</tr>\n";
				$totales = array();
				foreach($cxp_cliente as $key => $datos)
				{
          $html .= "		<tr class=\"label\">\n";
          $html .= "			<td rowspan=\"2\" class=\"formulacion_table_list\" width=\"150\" >";
          $html .= "        ".$key."\n";
          $html .= "			</td>\n";
          $p = 0;
          $tm = 0;
          $saldos = array();
          foreach($intervalos as $key => $dtll)
          {
            ($key == 0)? $est = "modulo_list_claro": $est="modulo_list_oscuro";
            (is_array($datos['periodos'][$key]))? $p = $datos['periodos'][$key]['saldo']: $p = 0;
            
            $html .= "      <td class=\"".$est."\" align=\"right\">".formatoValor($p)."</td>\n";
            $saldos[$key] += $p+$tm;
            $totales[$key] += $p;
            $tm += $p;
          }
          $html .= "      <td rowspan=\"2\" class=\"modulo_table_list_title\" style=\"text-align:right\">".formatoValor($datos['saldo'])."</td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr class=\"label\">\n";
          foreach($intervalos as $key => $dtll)
            $html .= "      <td class=\"tabla_submenu\" align=\"right\">".formatoValor($saldos[$key])."</td>\n";
          
          $html .= "		</tr>\n";
				}
        $tm = 0;
        $saldos = array();
        $html .= "		<tr class=\"modulo_table_list_title\">\n";
        $html .= "		  <td>TOTALES</td>\n";
        foreach($totales as $key => $dtl)
        {
          $html .= "      <td align=\"right\">".formatoValor($dtl)."</td>\n";
          $tm += $dtl;
          $saldos[$key] += $tm; 
        }
        $html .= "		  <td rowspan=\"2\" align=\"right\">".formatoValor($total)."</td>\n";
        $html .= "		</tr>\n";
        
        $html .= "		<tr class=\"modulo_table_list_title\">\n";
        $html .= "		  <td>ACUMULADO</td>\n";
        foreach($totales as $key => $dtl)
        {
          $html .= "      <td align=\"right\">".formatoValor($saldos[$key])."</td>\n";
        }
        $html .= "		</tr>\n";
        
        $html .= "	</table>\n";
			}
        
      $html .= "<table align=\"center\">\n";
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
    * Funcion donde se crea la forma para mostrar los proveedores de los cargoa
    *
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $tiposdocumentos Arreglo con los datos de los tipos de documentos
    * @param array $request Arreglo con los datos del request
    * @param array $proveedores Arreglo con los datos de los proveedores
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
    * @return string
    */
    function FormaMostrarTercerosCxP($action,$tiposdocumentos,$request,$proveedores,$conteo,$pagina)
    {
      $html  = ThemeAbrirTabla('TERCEROS CUENTAS POR PAGAR');
      $html .= "<form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "	<script>\n";
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
      $html .= "	</script>\n";
      $html .= "	<center>\n";
      $html .= "	<fieldset class=\"fieldset\" style=\"width:60%\">\n";
      $html .= "    <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
      $html .= "		<table width=\"80%\" align=\"center\">\n";
      $html .= "			<tr><td class=\"label\" width=\"25%\">TIPO DOCUMENTO</td>\n";
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
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" style=\"width:80%\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\">NOMBRE</td>\n";
      $html .= "				<td>\n";
      $html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[nombre]\" style=\"width:80%\" maxlength=\"100\" value=\"".$buscador['nombre']."\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr>\n";
      $html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
      $html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">&nbsp;&nbsp;\n";
      $html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
      $html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "		</table>\n";
      $html .= "	</fieldset>\n";
      $html .= "	</center>\n";
      $html .= "</form>\n";
      if(!empty($request))
      {
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
          
          foreach($proveedores as $key => $cargos)
          {
            ($est == "modulo_list_claro")? $est ="modulo_list_oscuro": $est = "modulo_list_claro";;
            $html .= "  <tr class=\"".$est."\">\n";
            $html .= "    <td width=\"20%\">".$cargos['tipo_id_tercero']." ".$cargos['tercero_id']." </td>\n";
            $html .= "    <td>".$cargos['nombre_tercero']."</td>\n";
            $html .= "    <td>".$cargos['direccion']."</td>\n";
            $html .= "    <td>".$cargos['telefono']."</td>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a title=\"SELECCIONAR PROVEEDOR\" href=\"".$action['aceptar'].URLRequest(array("tipo_id_tercero"=>$cargos['tipo_id_tercero'],"tercero_id"=>$cargos['tercero_id']))."\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/pguardar.png\" border=\"0\">\n";
            $html .= "      <a>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
          }
          $html .= "</table>\n";
          $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        }
      }
      $html .= "<form name=\"cerrar\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "	<table align=\"center\" width=\"50%\">\n";
      $html .= "		<tr>\n";
      $html .= "			<td align=\"center\">\n";
      $html .= "				<input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      $html .= "	</table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
		* Funcion donde se realiza la forma que muestra la informacion de todas las facturas que 
		* hay o de las que se buscaron 
		* 
    * @param array $action Arreglo de datos de los links de la aplicacion
    * @param array $request Arreglo con los datos del request
    * @param array $proveedor Arreglo con los datos del proveedor
    * @param array $facturas Arreglo con los datos de la factura
    * @param string $conteo Cadena con la cantidad de datos total
    * @param string $pagina Cadena con el numero de la pagina que se esta visualizando
    *
		* @return boolean 
		*/
		function FormaInformacionFactura($action,$request,$proveedor,$facturas,$conteo,$pagina)
		{
			$html .= ThemeAbrirTabla("DOCUMENTOS ASOCIADOS AL PROVEEDOR");
			
      $ctl = AutoCarga::factory('ClaseUtil');
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->AcceptNum(false);
      $html .= $ctl->AcceptDate("/");
      
      $html .= "<script language=\"javascript\">\n";      
      $html .= "	var prefijo_factura;\n";
      $html .= "	var numero_factura;\n";
      $html .= "	\n";
      $html .= "	function ValidarFactura(prefijo,numero,factura,sw_rips,proveedor_id)\n";
			$html .= "	{\n";
      $html .= "	  prefijo_factura = prefijo;\n";
      $html .= "	  numero_factura = numero;\n";
			$html .= "		if(sw_rips == '1')\n";
			$html .= "		  xajax_SolicitarValidacion(factura,proveedor_id)\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "	    url = \"".$action['aceptar_no_rips']."\"+\"&prefijo=\"+prefijo_factura+\"&numero=\"+numero_factura;\n";	
			$html .= "	    document.location.href = url;\n";	
			$html .= "	  }\n";	
			$html .= "	}\n";	
			$html .= "	function Continuar()\n";	
			$html .= "	{\n";	
			$html .= "	  url = \"".$action['aceptar_rips']."\"+\"&prefijo=\"+prefijo_factura+\"&numero=\"+numero_factura;\n";	
			$html .= "	  document.location.href = url;\n";
      $html .= "	}\n";	
      $html .= "	function AceptarValor()\n";
			$html .= "	{\n";
			$html .= "	  if(!document.oculta.validacion[0].checked && !document.oculta.validacion[1].checked)\n";
			$html .= "		  document.getElementById('erroro').innerHTML = 'FAVOR SELECCIONAR UNA OPCION'\n";
			$html .= "		else\n";
			$html .= "		  xajax_IngresarValidacion(xajax.getFormValues('oculta'));\n";
			$html .= "	}\n";
			$html .= "</script>\n";
      $html .= $this->FormaDatosProveedor($proveedor,60);
			$html .= "<br>\n";
			$html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "        <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR DE FACTURAS</legend>\n";
      $html .= "	        <table width=\"100%\" align=\"center\">\n";
			$html .= "            <tr>\n";
 			$html .= "			        <td width=\"25%\" class=\"normal_10AN\">PREFIJO</td>\n";
      $html .= "			        <td width=\"25%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[prefijo]\"  style=\"width:80%\" value=\"".$request['prefijo']."\">\n";
			$html .= "			        </td>\n";
 			$html .= "			        <td width=\"25%\" class=\"normal_10AN\">NUMERO</td>\n";
			$html .= "			        <td width=\"25%\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[factura]\" style=\"width:80%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
 			$html .= "			        <td class=\"normal_10AN\">Nº RADICACION</td>\n";
      $html .= "			        <td colspan=\"3\" >\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[radicacion]\" onkeypress=\"return acceptNum(event)\" style=\"width:26%\" value=\"".$request['radicacion']."\">\n";
			$html .= "			        </td>\n";
      $html .= "            </tr>\n";
      
      $html .= "			      <tr class=\"normal_10AN\">\n";
			$html .= "				      <td >FECHAS RADICACION</td>\n";
      $html .= "			        <td colspan=\"3\" >\n";
			$html .= "				        <table width=\"100%\">\n";
			$html .= "				          <tr>\n";
			$html .= "				            <td width=\"50%\">\n";
			$html .= "					            <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
			$html .= "				              ".ReturnOpenCalendario('buscador','fecha_inicio','/',1)."\n";
      $html .= "                    </td>\n";			
      $html .= "				            <td colspan=\"2\">\n";
			$html .= "					            <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_fin']."\">\n";
			$html .= "				              ".ReturnOpenCalendario('buscador','fecha_fin','/',1)."\n";
      $html .= "                    </td>\n";
      $html .= "                  </tr>\n";
      $html .= "                </table>\n";
      $html .= "              </td>\n";
			$html .= "			      </tr>\n";
      
      $html .= "            <tr>\n";
			$html .= "			        <td colspan=\"4\" align=\"center\">\n";
			$html .= "			          <table align=\"center\" >\n";
			$html .= "			            <tr >\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"submit\" class=\"input-submit\" name=\"buscador[buscar]\" value=\"Buscar\">\n";
			$html .= "		                </td>\n";
			$html .= "			              <td align=\"center\" >\n";
			$html .= "				              <input  type=\"button\" class=\"input-submit\" name=\"buscador[limpiar]\" value=\"LimpiarCampos\" onclick=\"LimpiarCampos(document.buscador)\">\n";
			$html .= "		                </td>\n";
			$html .= "		              </tr>\n";
			$html .= "		            </table>\n";
			$html .= "		          </td>\n";
      $html .= "            </tr>\n";
			$html .= "	        </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n"; 
			
      if(sizeof($facturas) > 0)
      {
        $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "			<tr class=\"formulacion_table_list\" height=\"21\">\n";
        $html .= "				<td width=\"8%\">Nº RAD.</td>\n";
        $html .= "				<td width=\"9%\">FACTURA</td>\n";
        $html .= "				<td width=\"8%\">FECHA</td>\n";
        $html .= "				<td width=\"8%\">RADICACION</td>\n";
        $html .= "				<td width=\"8%\">VENCE</td>\n";
        $html .= "				<td width=\"10%\">TOTAL</td>\n";
        $html .= "				<td width=\"10%\">IVA</td>\n";

        $html .= "				<td width=\"31%\">TIPO CUENTA</td>\n";
        $html .= "				<td width=\"5%\">OP</td>\n";
        $html .= "			</tr>";
        
        $estilo='modulo_list_oscuro'; 
        $background = "#CCCCCC";
        
        foreach($facturas as $key => $detalle )
        {
          ($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
                  
          $html .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "				<td align=\"left\"   >".$detalle['cxp_radicacion_id']."</td>\n";
          $html .= "				<td align=\"left\"   >".$detalle['prefijo_factura']." ".$detalle['numero_factura']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_documento']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_radicacion']."</td>\n";
          $html .= "				<td align=\"center\" >".$detalle['fecha_vencimiento']."</td>\n";
          $html .= "				<td align=\"right\"  >".formatoValor($detalle['valor_total'])."</td>\n";
          $html .= "				<td align=\"right\"  >".formatoValor($detalle['valor_iva'])."</td>\n";
          $html .= "				<td align=\"justify\">".$detalle['tipo_cxp_descripcion']."</td>\n";
          $html .= "				<td align=\"center\" >\n";
          $html .= "          <a title=\"VER INFORMACION FACTURA\" href=\"javascript:ValidarFactura('".$detalle['prefijo']."','".$detalle['numero']."','".$detalle['prefijo_factura']." ".$detalle['numero_factura']."','".$detalle['sw_rips']."','".$detalle['codigo_proveedor_id']."')\">\n";
          $html .= "            <img src=\"".GetThemePath()."/images/editar.png\" border=\"0\">\n";
          $html .= "          <a>\n";
          $html .= "        </td>\n";
          $html .= "			</tr>\n";
        }
        $html .= "	</table><br>\n";
        
        $pghtml = AutoCarga::factory('ClaseHTML');;
        $html .= "		".$pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "		<br>\n";
        $html .= $this->CrearVentana("AceptarValor()");
      }
      else
      {
        $html .= "<center>";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>";
      }
			
			$html .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$html .= "				<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "				</form>\n";
			$html .= "			</td></tr>\n";
			$html .= "		</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($funcion, $tmn = 350)
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
      
			$html .= "	function MostrarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"block\";\n";
			$html .= "		  IniciarGrande();\n";
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

      $html .= "	function IniciarGrande()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,800, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "		ele.innerHTML = 'LISTADO DE CARGOS';\n";
			$html .= "	  xResizeTo(ele,780, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,780, 0);\n";
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
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "		<form name=\"oculta\" id=\"oculta\" action=\"javascript:".$funcion."\" method=\"post\">\n";
			$html .= "		  <div id=\"glosas\"></div>\n";
			$html .= "		  <div id=\"ventana\" ></div>\n";
      $html .= "		  <div id=\"erroro\" class=\"label_error\" style=\"text-align:center\"></div>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";

			return $html;
		}
    /**
    * Funcion donde se crea una forma con los datos del proveedor
    *
    * @param array $proveedor Arreglo de datos cob la informacion del proveedor
    * @param int $p Valor del porcentaje para el tamaño de la tabla
    *
    * @return string
    */
    function FormaDatosProveedor($proveedor,$p)
    {
      $st = "style=\"text-indent:4pt;text-align:left\"";
      $html  = "<table width=\"".$p."%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\" class=\"formulacion_table_list\">PROVEEDOR</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td width=\"25%\"><b>".$proveedor['tipo_id_tercero']." ".$proveedor['tercero_id']." <b></td>\n";
      $html .= "    <td $st width=\"%\" ><b>".$proveedor['nombre_tercero']."</b></td>\n";
      $html .= "  </tr>\n";
      if($proveedor['direccion'])
      {
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td $st >DIRECCION</td>\n";
        $html .= "    <td $st class=\"modulo_list_claro\"> ".$proveedor['direccion']."</td>\n";
        $html .= "  </tr>\n";
      }
      if($proveedor['telefono'])
      {
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td $st>TELEFONO</td>\n";
        $html .= "    <td $st class=\"modulo_list_claro\"> ".$proveedor['telefono']."</td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      
      return $html;
    }
    /**
		* Funcion donde se crea la forma para mostrar el detalle de la factura
    *
    * @param array $action Arreglo de datos con los links de la forma
    * @param array $proveedor Arreglo de datos con la informacion del proveedor
    * @param array $paciente Arreglo de datos con la informacion del paciente
    * @param array $factura Arreglo de datos con la informacion de la factura
    * @param array $cargo Arreglo de datos con la informacion de los cargos de detalle de la factura
    * @param array $medica Arreglo de datos con la informacion de los medicamentos de detalle de la factura
    * @param array $otros Arreglo de datos con la informacion de los demas servicios cobrados en el detalle de la factura
    * @param array $ordenes Arreglo con la informacion de las ordenes asociados al proveedor
    * @param array $detalle Arreglo con la informacion del detalle de las ordenes
    * @param array $glosa Arreglo con la informacion de la glosa que tiene la cuenta
    *
		* @return string
		*/
		function FormaDetalleCxP($action,$proveedor,$paciente,$cargo,$medica,$otros,$factura,$ordenes,$detalle,$glosa,$historico)
		{						
      $ctl = AutoCarga::factory("ClaseUtil");
			$html  = $ctl->IsNumeric();
      $html .= $ctl->TrimScript();
      $html .= $ctl->AcceptNum(true);
      $html .= $ctl->RollOverFilas();
      $html .= "<script>\n";
			$html .= "	function Objetar(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle)\n";
			$html .= "	{\n";
			$html .= "		xajax_Objetar(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle);\n";
			$html .= "	}\n";				
      $html .= "	function ModificarObjecion(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle)\n";
			$html .= "	{\n";
			$html .= "		xajax_ModificarObjecion(cxp_detalle_factura_id,referencia,descripcion,valor,tipo_detalle);\n";
			$html .= "	}\n";			
      $html .= "	function AceptarObjeccion()\n";
			$html .= "	{\n";
			$html .= "	  if(trim(document.oculta.observacion.value) == '')\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'PARA HACER LA OBJECCION DEL DETALLE DE LA CUENTA ES NECESARIO INGRESAR UNA OBSERVACION';}\n";
			$html .= "		else if(!IsNumeric(document.oculta.valor_total.value))\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'EL VALOR OBJETADO POSEE UN FORMATO DE NUMERO INCORRECTO O ES NULO';}\n";
			$html .= "		else if((document.oculta.valor_total.value*1) > (document.oculta.valor1.value*1))\n";
			$html .= "		  {document.getElementById('erroro').innerHTML = 'EL VALOR OBJETADO NO DEBE SER MAYOR AL VALOR DEl DETALLE';}\n";
			$html .= "		else\n";
			$html .= "		{\n";
 			$html .= "		  document.getElementById('erroro').innerHTML = ''\n";
			$html .= "		  xajax_RegistrarObjeccion(xajax.getFormValues('oculta'));\n";
			$html .= "	  }\n";
			$html .= "	}\n";
      $html .= "	function PasarValor(objeto)\n";
      $html .= "	{\n";
      $html .= "	  objeto.valor_total.value = objeto.valor1.value; \n";
      $html .= "	}\n";
      $html .= "	function ObjetarCuenta(pre,num)\n";
			$html .= "	{\n";
			$html .= "	  if(trim(document.objetar.observacion.value) == '')\n";
			$html .= "		  document.getElementById('errort').innerHTML = 'PARA HACER LA OBJECCION DE LA CUENTA ES NECESARIO INGRESAR UNA OBSERVACION'\n";
      $html .= "		else if(!IsNumeric(document.objetar.valor_total.value))\n";
			$html .= "		  {document.getElementById('errort').innerHTML = 'EL VALOR OBJETADO POSEE UN FORMATO DE NUMERO INCORRECTO O ES NULO';}\n";
			$html .= "		else if((document.objetar.valor_total.value*1) > (document.objetar.valor1.value*1))\n";
			$html .= "		  {document.getElementById('errort').innerHTML = 'EL VALOR OBJETADO NO DEBE SER MAYOR AL VALOR DE LA FACTURA';}\n";
			$html .= "		else\n";
			$html .= "		{\n";
 			$html .= "		  document.getElementById('errort').innerHTML = ''\n";
			$html .= "		  xajax_RegistrarObjeccionT(xajax.getFormValues('objetar'),pre,num);\n";
			$html .= "	  }\n";      
			$html .= "	}\n";      
      $html .= "	function AsociarCXP(orden)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarCXP(orden,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function DesvincularCXP(orden)\n";
			$html .= "	{\n";
			$html .= "		xajax_DesvincularCXP(orden,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";      
      $html .= "	function AsociarDetalleCargo(orden,orden_cargo,cups,valor)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarDetalleCargo(orden,orden_cargo,cups,valor,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function VincularDetalle(cxp_detalle_id,orden,orden_cargo,cargo,valor1,valor2)\n";
      $html .= "	{\n";
      $html .= "		xajax_VincularDetalle(cxp_detalle_id,orden,orden_cargo,cargo,valor1,valor2);\n";
      $html .= "	}\n";      
      $html .= "	function DesvincularDetalleCargo(orden,orden_cargo,cxp_detalle_id,cargo,valor,detalle)\n";
      $html .= "	{\n";
      $html .= "		xajax_DesvincularDetalle(orden,orden_cargo,cxp_detalle_id,cargo,valor,detalle);\n";
      $html .= "	}\n";     
      
      $html .= "	function AsociarDetalleMedicamento(orden,orden_medicamento,codigo,valor)\n";
			$html .= "	{\n";
			$html .= "		xajax_AsociarDetalleMedicamento(orden,orden_medicamento,codigo,valor,'".$factura['prefijo']."','".$factura['numero']."');\n";
			$html .= "	}\n";
      $html .= "	function VincularDetalleM(cxp_detalle_id,orden,orden_medicamento,codigo,valor1,valor2,medicamento)\n";
      $html .= "	{\n";
      $html .= "		xajax_VincularDetalleM(cxp_detalle_id,orden,orden_medicamento,codigo,valor1,valor2,medicamento);\n";
      $html .= "	}\n";      
      $html .= "	function DesvincularDetalleMedicamento(orden,orden_medicamento,cxp_detalle_id,codigo,valor,detalle,medicamento)\n";
      $html .= "	{\n";
      $html .= "		xajax_DesvincularDetalleM(orden,orden_medicamento,cxp_detalle_id,codigo,valor,detalle,medicamento);\n";
      $html .= "	}\n";      
      $html .= "	function FinalizarRevision()\n";
      $html .= "	{\n";
      $html .= "		xajax_FinalizarRevision('".$factura['prefijo_factura']." ".$factura['numero_factura']."');\n";
      $html .= "	}\n";      
      $html .= "	function TerminarRevision()\n";
      $html .= "	{\n";
      $html .= "	  location.href = \"".$action['revision'].URLRequest(array("prefijo"=>$factura['prefijo'],"numero"=>$factura['numero']))."\"\n";
      $html .= "	}\n";
			$html .= "</script>\n";
			$html .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
      $html .= "<table width=\"90%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td width=\"45%\"  valign=\"top\">\n";
      $html .= $this->FormaDatosProveedor($proveedor,100);
      $html .= "    </td>\n";
      $html .= "    <td width=\"%\" valign=\"top\">\n";
      $st = "style=\"text-indent:4pt;text-align:left\"";
			$html .= "      <table align=\"center\" cellpading=\"0\"  width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "        <tr>\n";
      $html .= "          <td colspan=\"7\" class=\"formulacion_table_list\">FACTURA</td>\n";
      $html .= "        </tr>\n";
      $html .= "	      <tr class=\"formulacion_table_list\">\n";
			$html .= "		      <td $st>FACTURA</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\" width=\"10%\">\n";
			$html .= "			      ".$factura['prefijo_factura']." ".$factura['numero_factura']."\n";
			$html .= "		      </td>\n";
			$html .= "		      <td $st >F FACTURA</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\"  width=\"10%\">\n";
			$html .= "			      ".$factura['fecha_documento']."\n";
			$html .= "		      </td>\n";
			$html .= "		      <td $st >REGISTRO</td>\n";
			$html .= "		      <td class=\"modulo_list_claro\"  width=\"10%\">\n";
			$html .= "			      ".$factura['fecha_registro']."\n";
			$html .= "		      </td>\n";
			$html .= "		    </tr>\n";
			$html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "			    <td $st width=\"17%\">VALOR</td>\n";
			$html .= "			    <td width=\"17%\" class=\"modulo_list_claro\" align=\"right\">\n";
			$html .= "				    ".formatoValor($factura['valor_total'])."\n";
			$html .= "			    </td>\n";
			$html .= "			    <td $st width=\"18%\">IVA</td>\n";
			$html .= "			    <td width=\"15%\" class=\"modulo_list_claro\" align=\"right\" >\n";
			$html .= "				    ".formatoValor($factura['valor_iva'])."\n";
			$html .= "			    </td>\n";
			$html .= "			    <td $st width=\"18%\">GRAVAMEN</td>\n";
			$html .= "			    <td width=\"15%\" class=\"modulo_list_claro\" align=\"right\" width=\"15%\">\n";
			$html .= "				    ".formatoValor($factura['valor_gravamen'])."\n";
			$html .= "			    </td>\n";
			$html .= "		    </tr>\n";
      $html .= "		    <tr class=\"formulacion_table_list\">\n";
			$html .= "			    <td $st >T CUENTA</td>\n";
			$html .= "			    <td colspan=\"6\" class=\"modulo_list_claro\" >\n";
			$html .= "				    ".$factura['tipo_cxp_descripcion']."\n";
			$html .= "			    </td>\n";
			$html .= "		    </tr>\n";
			$html .= "	    </table>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
      $html .= $this->FormaHistoricoEstados($historico);
      $html .= "<br>\n";
      
      if(!empty($paciente))
      {
        $html .= "  <table align=\"center\" cellpading=\"0\"  width=\"70%\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "	  <tr class=\"formulacion_table_list\">\n";
        $html .= "		  <td colspan=\"2\">PACIENTES RELACIONADOS EN LA CUENTA DE COBRO</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr class=\"formulacion_table_list\">\n";
        $html .= "			<td width=\"40%\">IDENTIFICACION</td>\n";
        $html .= "			<td >NOMBRE</td>\n";
        $html .= "		</tr>\n";
        
        foreach($paciente as $key => $dtl)
        {
          $html .= "		<tr>\n";
          
          $html .= "			<td class=\"modulo_list_claro\">\n";
          $html .= "				".$dtl['identificacion']." \n";
          $html .= "			</td>\n";
          $html .= "			<td class=\"modulo_list_claro\" >\n";
          if($dtl['tipo_id_paciente'])
            $html .= "				".$dtl['primer_nombre']." ".$dtl['segundo_nombre']." ".$dtl['primer_apellido']." ".$dtl['segundo_apellido']."\n";
          else
            $html .= "				<b class=\"label_error\">EL PACIENTE NO FUE ENCONTRADO EN EL SISTEMA</b>\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "	</table><br>\n";
      }
      
      $glosai = $glosa[UserGetUID()];
      
      unset($glosa[UserGetUID()]);
      if(!empty($glosa))
      {
        $html .= "<table width=\"71%\" align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "	      <legend class=\"normsl_10AN\">OBJECCIONES PRESENTES SOBRE LA CUENTA</legend>\n";
        $html .= "	      <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
        $html .= "		      <tr class=\"modulo_table_list_title\" height=\"17\">\n";
        $html .= "			      <td width=\"10%\" >VALOR</b></td>\n";
        $html .= "			      <td width=\"%\">OBESERVACION</b></td>\n";
        $html .= "			      <td width=\"25%\">REGISTRADO POR</td>\n";
        $html .= "			      <td width=\"10%\">FECHA</td>\n";
        $html .= "		      </tr>\n";
        
        foreach($glosa as $key => $detalle)
        {          
          $html .= "		      <tr>\n";
          $html .= "		        <td align=\"right\">$".formatoValor($detalle['valor'])."</td>\n";
          $html .= "		        <td align=\"justify\">".$detalle['observacion']."</td>\n";
          $html .= "		        <td >".$detalle['nombre']."</td>\n";
          $html .= "		        <td >".$detalle['fecha_registro']."</td>\n";
          $html .= "		      </tr>\n";
        }
        $html .= "	      </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
      }
      
      $html .= "<form name=\"objetar\" id=\"objetar\" action=\"javascript:ObjetarCuenta('".$factura['prefijo']."', '".$factura['numero']."')\" method=\"post\">\n";
      $html .= "  <table width=\"71%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "        <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">OBJETAR CUENTA POR COBRAR</LEGEND>\n";
      $html .= "			    <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"4\">OBSERVACION</td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr class=\"formulacion_table_list\">\n";
      $html .= "			        <td colspan=\"4\">\n";
      $html .= "                <textarea class=\"textarea\" id=\"general\" name=\"observacion\" style=\"width:100%\" rows=\"3\">".$glosai['observacion']."</textarea>\n";
      $html .= "              </td>\n";
      $html .= "			      </tr>\n";
      $html .= "            <tr class=\"formulacion_table_list\">\n"; 
      $html .= "              <td align=\"left\" width=\"25%\">* VALOR</td>\n"; 
      $html .= "              <td align=\"right\" width=\"25%\" class=\"modulo_list_claro\">$".formatoValor($factura['valor_total'])." </td>\n"; 
      $html .= "              <td width=\"2%\" class=\"modulo_list_claro\">\n";
      $html .= "                <img style=\"cursor:pointer\" title=\"PASAR VALOR\" src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" onclick=\"PasarValor(document.objetar)\">\n";
      $html .= "              </td>\n"; 
      $html .= "              <td align=\"left\"  class=\"modulo_list_claro\">\n";
      $html .= "                <input type=\"hidden\" name=\"cxp_glosa_observacion_id\" value=\"".$glosai['cxp_glosa_observacion_id']."\">\n";
      $html .= "                <input style=\"width:60%\"  type=\"text\" name=\"valor_total\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$glosai['valor']."\">\n";
      $html .= "              </td>\n"; 
      $html .= "            </tr>\n"; 
      $html .= "			    </table >\n";
      $html .= "			    <div id=\"errort\" class=\"label_error\" style=\"text-align:center\"><br></div>\n";
      $html .= "	        <table width=\"100%\" align=\"center\">\n";
      $html .= "	          <tr>\n";
      $html .= "			        <td align='center'>\n";
      $html .= "			          <input type=\"hidden\" name=\"valor1\" value=\"".$factura['valor_total']."\">\n";
      $html .= "			          <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "		          </td>\n";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      $html .= "        </fieldset>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "	  <td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>	tabPane = new WebFXTabPane( document.getElementById( \"APD\" ),false); </script>\n";
      $html .= "				<div class=\"tab-page\" id=\"pendientes\">\n";
      $html .= "				  <h2 class=\"tab\">DETALLE CXP</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"pendientes\")); </script>\n";
      $html .= $this->FormaDetalleCuenta($cargo, $medica, $otros);
      $html .= "        </div>\n";
      
      $html .= "				<div class=\"tab-page\" id=\"ordenes\">\n";
      $html .= "				  <h2 class=\"tab\">ORDENES DE SERVICIO PROVEEDOR</h2>\n";
      $html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"ordenes\")); </script>\n";
      $html .= $this->FormaMostarOrden($ordenes,$detalle);
      $html .= "        </div>\n";
      $html .= "      </div>\n";
			$html .= "	  </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
			$html .= "<table width=\"90%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" >\n";
			$html .= "		  <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "			  <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";      
      $html .= "    <td align=\"center\" >\n";
			$html .= "		  <form name=\"volver\" action=\"javascript:FinalizarRevision()\" method=\"post\">\n";
			$html .= "			  <input type=\"submit\" class=\"input-submit\" value=\"Finalizar Revision\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
      $html .= "  </tr>\n";
			$html .= "</table>\n";
      $html .= $this->CrearVentana("AceptarObjeccion()",480);
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
    * Funcion donde se crea el detalle de los cargos, medicamentos y otros servicios cobrados en la factura 
    *
    * @param array $cargo Arreglo de datos con la informacion de los cargos de detalle de la factura
    * @param array $medica Arreglo de datos con la informacion de los medicamentos de detalle de la factura
    * @param array $otros Arreglo de datos con la informacion de los demas servicios cobrados en el detalle de la factura
    *
    * @return string
    */
    function FormaDetalleCuenta($cargo, $medica, $otros)
    {
      if(sizeof($cargo) > 0)
			{			
				$bck = "#CCCCCC";
        $est = "modulo_list_oscuro";
        
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE CARGOS CUENTA</legend>\n";
				
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CANT</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
				$html .= "			<td width=\"8%\">V. LISTA</td>\n";
				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"2%\" colspan=\"2\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($cargo as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_oscuro")? $est = "modulo_list_claro":$est = "modulo_list_oscuro";
					
          $bd = $bc = $ms = $vl = "";
          if(!$detalle['valor'])
          {
            $bc = "class=\"label_error\" ";
            $vl = "EL CARGO IDENTIFICADO CON ".$detalle['referencia'].", NO ESTA REGISTRADO EN LA LISTA DE PRECIOS DEL PROVEEDOR";
            $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
          }
          if($detalle['valor'] && $detalle['valor'] != $detalle['valor_unitario'] )
          {
            $bd = "class=\"label_error\" ";
            $vl = "EL VALOR DEL CARGO ".$detalle['referencia'].", REGISTRADO EN EL RIPS NO CORRESPONDE AL VALOR DE LA LISTA DE PRECIOS DEL PROVEEDOR";
            $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
          }
          
          if ($detalle['valor_orden'])
          {
            if($ms == "")
            {
              $bd = "class=\"label_error\" ";
              $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
            }
            $vl .= "\\nEL VALOR DEL CARGO IDENTIFICADO CON ".$detalle['referencia'].", NO COINCIDE CON EL VALOR ($".formatoValor($detalle['valor_orden']).") DE LA ORDEN DE SERVICO.";
          }
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td $bc >".$detalle['referencia']."</td>\n";
					$html .= "			<td align=\"justify\">".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td $bd align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
					$html .= "			<td $bd align=\"right\">";
          if($detalle['valor']) $html .= formatoValor($detalle['valor']);
          $html .= "      </td>\n";
          $html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
          $html .= "      <td >\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
					if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','C')\" title=\"MODIFICAR OBJETAR CARGO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
            $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','C')\" title=\"OBJETAR CARGO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "      <td style=\"cursor:pointer\">\n";
          $html .= "        <input type=\"hidden\" id =\"hdd_".$detalle['cxp_detalle_factura_id']."\" value=\"".$vl."\">\n";
          $html .= "        <div id=\"dtl_".$detalle['cxp_detalle_factura_id']."\">".$ms."</div>\n";
          $html .= "      </td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";		
			}
			
			if(sizeof($medica) > 0)
			{
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE MEDICAMENTOS CUENTA</legend>\n";
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CAN</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
 				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"3%\" colspan=\"2\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($medica as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";

          $bd = $bc = $ms = $vl = "";
          if ($detalle['valor'])
          {
            $bd = "class=\"label_error\" ";
            $ms = "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert(document.getElementById('hdd_".$detalle['cxp_detalle_factura_id']."').value)\">\n";
            $vl = "EL VALOR DEL MEDICAMENTO IDENTIFICADO CON ".$detalle['referencia'].", NO COINCIDE CON EL VALOR ($".formatoValor($detalle['valor']).") DE LA ORDEN DE SERVICO.";
          }
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td >".$detalle['referencia']."</td>\n";
					$html .= "			<td >".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td align=\"right\" $bd >".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
					$html .= "      <td >\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
          if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','M')\" title=\"MODIFICAR OBJECCION MEDICAMENTO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
            $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','M')\" title=\"OBJETAR MEDICAMENTO\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "      <td style=\"cursor:pointer\">\n";
          $html .= "        <input type=\"hidden\" id =\"hdd_".$detalle['cxp_detalle_factura_id']."\" value=\"".$vl."\">\n";
          $html .= "        <div id=\"dtl_".$detalle['cxp_detalle_factura_id']."\">".$ms."</div>\n";
          $html .= "      </td>\n";

          $html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";	
			}
			
      if(sizeof($otros) > 0)
			{
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "	<legend class=\"normsl_10AN\">DETALLE OTROS SERVICIOS CUENTA</legend>\n";
				$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$html .= "			<td width=\"9%\" >CODIGO</b></td>\n";
				$html .= "			<td width=\"%\">DESCRIPCIÓN</b></td>\n";
				$html .= "			<td width=\"5%\">CANT</td>\n";
				$html .= "			<td width=\"8%\">UNITARIO</td>\n";
				$html .= "			<td width=\"8%\">GARVAMEN</td>\n";
				$html .= "			<td width=\"8%\">TOTAL</td>\n";
 				$html .= "			<td width=\"6%\">AUTO</td>\n";
				$html .= "			<td width=\"1%\"></td>\n";
				$html .= "		</tr>\n";
				
				foreach($otros as $key => $detalle)
				{
					($bck == "#DDDDDD")? $bck = "#CCCCCC":$bck = "#DDDDDD";
					($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
									
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "			<td >".$detalle['referencia']."</td>\n";
					$html .= "			<td >".$detalle['descripcion']."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['cantidad']."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_unitario'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_gravamen'])."</td>\n";
					$html .= "			<td align=\"right\">".formatoValor($detalle['valor_total'])."</td>\n";
					$html .= "			<td align=\"right\">".$detalle['autorizacion']."</td>\n";
          $html .= "      <td >\n";
          $html .= "        <div id=\"objeccion".$detalle['cxp_detalle_factura_id']."\" >\n";
          if($detalle['sw_objetado'] == '1')
          {
            $html .= "				<a href=\"javascript:ModificarObjecion('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','O')\" title=\"MODIFICAR OBJECION OTROS SERVICIOS\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">\n";
            $html .= "				</a>\n";					
          }
          else
          {
            $html .= "				<a href=\"javascript:Objetar('".$detalle['cxp_detalle_factura_id']."','".$detalle['referencia']."','".$detalle['descripcion']."','".$detalle['valor_total']."','O')\" title=\"OBJETAR OTROS SERVICIOS\">\n";
            $html .= "					<img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\">\n";
            $html .= "				</a>\n";
          }
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "	</table>\n";
        $html .= "</fieldset><br>\n";	
			}
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar las ordenes de servicios creadas
    *
    * @param array $orden Arreglo con los datos de las ordenes
    * @param array $detalle Arreglo con los datos del detalle de las ordenes
    *
    * @return string
    */
    function FormaMostarOrden($orden,$detalle)
    {
      $dat = array();
      $est = 'modulo_list_oscuro'; $back = "#DDDDDD";
 			
      $html = "";
      foreach($orden as $key1 => $ordenes)
      {          
        $sty = " style=\"text-align:left;text-indent:6pt\" ";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "	  <tr align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "  	  <td $sty width=\"16%\">Nº ORDEN</td>\n";
        $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['eps_orden_servicio']."</td>\n";
        $html .= "  	  <td $sty width=\"16%\">Nº AUTORIZACION</td>\n";
        $html .= "  	  <td $sty width=\"16%\" class=\"modulo_list_claro\">".$ordenes['autorizacion_id']."</td>\n";
        $html .= "  	  <td $sty width=\"10%\">FECHA</td>\n";
        $html .= "  	  <td $sty width=\"22%\" class=\"modulo_list_claro\" >".$ordenes['fecha_registro']."</td>\n";
        $html .= "			<td class=\"modulo_list_oscuro\" align=\"center\" valign=\"middle\" rowspan=\"2\">\n";
        $html .= "			  <div id=\"divorden_".$ordenes['eps_orden_servicio']."\">\n";
        if($ordenes['marca'] == '1')
        {  
          $html .= "			    <a href=\"javascript:DesvincularCXP('".$ordenes['eps_orden_servicio']."')\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
          $html .= "			      <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
          $html .= "			    </a>\n";          
        }
        else
        {
          $html .= "			    <a href=\"javascript:AsociarCXP('".$ordenes['eps_orden_servicio']."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
          $html .= "			      <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
          $html .= "			    </a>\n";
        }
        
        $html .= "			  </div>\n";
        $html .= "			</td>\n";
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
        
        if(!empty($detalle[$key1]['cargos']))
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
          $html .= "		        <td width=\"1%\"></td>\n";
          $html .= "	        </tr>\n";
          foreach($detalle[$key1]['cargos'] as $kc => $dtl_cargos)
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
              $html1 .= "		 <td>\n";
              $html1 .= "		  <div id=\"divcrg_".$dtl['eps_orden_servicio_cargo']."\">\n";
              if($dtl['marca'] == '1')
              {  
                $html1 .= " 				  <a href=\"javascript:DesvincularDetalleCargo('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_cargo']."','".$dtl['cxp_detalle_factura_id']."','".$kc."','".$dtl['valor']."',document.getElementById('hdd_".$dtl['cxp_detalle_factura_id']."').value)\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
                $html1 .= "            <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
                $html1 .= " 				  </a>\n";          
              }
              else
              {
                if($dtl['marca'] == '0')
                {
                  $html1 .= " 				  <a href=\"javascript:AsociarDetalleCargo('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_cargo']."','".$kc."','".$dtl['valor']."')\" class=\"label_error\"  title=\"ASOCIAR ORDEN CON LA CUENTA\">\n";
                  $html1 .= "            <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
                  $html1 .= " 				  </a>\n";
                }
                else
                {
                  $html1 .= "<img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert('";
                  $html1 .= "EL CARGO CUPS: ".$kc." YA ESTA ASOCIADO A UNA CUENTA";
                  $html1 .= "')\">\n";
                }
              }
              $html1 .= "		  </div>\n";
              $html1 .= "		 </td>\n";
              $html1 .= "  </tr>\n";
            }
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td width=\"%\" colspan=\"7\">CUPS: ".$kc." ".$dtl['descripcion_base']."</td>\n";
            $html .= "  </tr>\n";
            $html .= $html1;
            $html1 = "";
          }
          $html .= "        </table>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
        }
        
        if(!empty($detalle[$key1]['medicamentos']))
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
          $html .= "		        <td width=\"1%\"></td>\n";
          $html .= "	        </tr>\n";            
          foreach($detalle[$key1]['medicamentos'] as $kc => $dtl)
          {
            $html .= "	        <tr class=\"modulo_list_claro\">\n";
            $html .= "            <td>".$dtl['codigo_producto']."</td>\n";
            $html .= "            <td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
            $html .= "		        <td>".$dtl['cantidad']."</td>\n";
            $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
            $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'] * $dtl['cantidad'])."</td>\n";
            $html .= "		        <td>\n";
            $html .= "		          <div id=\"divmed_".$dtl['eps_orden_servicio_medicamento']."\">\n";
            if($dtl['marca'] == '1')
            {  
              $html .= " 				        <a href=\"javascript:DesvincularDetalleMedicamento('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_medicamento']."','".$dtl['cxp_detalle_factura_id']."','".$dtl['codigo_producto']."','".$dtl['valor']."',document.getElementById('hdd_".$dtl['cxp_detalle_factura_id']."').value,'".$dtl['codigo_producto']."')\" class=\"label_error\"  title=\"DESVINCULAR ORDEN DE LA CUENTA\">\n";
              $html .= "                  <img src=\"".GetThemePath()."/images/checksi.png\" border='0'>\n";
              $html .= " 				        </a>\n";          
            }
            else
            {
              if($dtl['marca'] == '0')
              {
                $html .= " 				      <a href=\"javascript:AsociarDetalleMedicamento('".$ordenes['eps_orden_servicio']."','".$dtl['eps_orden_servicio_medicamento']."','".$dtl['codigo_producto']."','".$dtl['valor']."')\" class=\"label_error\"  title=\"ASOCIAR MEDICAMENTO CON LA CUENTA\">\n";
                $html .= "                <img src=\"".GetThemePath()."/images/checkno.png\" border='0'>\n";
                $html .= " 				      </a>\n";
              }
              else
              {
                $html .= "          <img src=\"".GetThemePath()."/images/infor.png\" onclick=\"alert('";
                $html .= "EL MEDICAMENTO: ".$kc." YA ESTA ASOCIADO A UNA CUENTA";
                $html .= "')\">\n";
              }
            }
            $html .= "		          </div>\n";
            $html .= "		        </td>\n";
            $html .= "	        </tr>\n";
          }
          $html .= "        </table>\n";
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
        }
        
        if(!empty($detalle[$key1]['conceptos']))
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
          foreach($detalle[$key1]['conceptos'] as $kc => $dtl)
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
      
      if($html == "")
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">\n";
        $html .= "    PARA ESTE PROVEEDOR NO EXISTEN ORDENES DE SERVICIO RELACIONADAS EN LA FECHA DEL DOCUMENTO\n";
        $html .= "  </label>\n";
        $html .= "</center>\n";
      }
      return $html;
    }
    /**
    *
    */
    function FormaHistoricoEstados($historico)
    {
      $html = "";
      if(!empty($historico))
      {
        $html .= "<table width=\"70%\" align=\"center\">\n";
        $html .= "  <tr align=\"center\">\n";
        $html .= "	  <td colspan=\"7\">\n";
        $html .= "	    <fieldset class=\"fieldset\">\n";
        $html .= "	      <legend class=\"normal_10AN\">HISTORICO DE ESTADOS</legend>\n";
        $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"formulacion_table_list\">\n";
        $html .= "	          <td width=\"30%\">ESTADO ACTUAL</td>\n";
        $html .= "		        <td width=\"30%\">ESTADO ANTERIOR</td>\n";
        $html .= "		        <td width=\"15%\">REGISTRO</td>\n";
        $html .= "		        <td width=\"%\">RESPONSABLE</td>\n";
        $html .= "	        </tr>\n";
        
        $est = 'modulo_list_oscuro';
        foreach($historico as $key => $dtl)
        {
          ($est == "modulo_list_oscuro")? $est = "modulo_list_claro": $est = "modulo_list_oscuro";
          $html .= "          <tr class=\"".$est."\">\n";
          $html .= "	          <td >".$dtl['estado_actual']."</td>\n";
          $html .= "		        <td >".$dtl['estado_anterior']."</td>\n";
          $html .= "		        <td >".$dtl['fecha_registro']."</td>\n";
          $html .= "		        <td >".$dtl['nombre']."</td>\n";
          $html .= "	        </tr>\n";
        }
        $html .= "	      </table>\n";
        $html .= "	    </filedset>\n";
        $html .= "	  </td>\n";
        $html .= "	</tr>\n";
        $html .= "</table>\n";
      }
      return $html;
    }
  }
?>