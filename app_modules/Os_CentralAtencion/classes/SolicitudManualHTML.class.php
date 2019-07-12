<?php
	/**************************************************************************************
	* $Id: SolicitudManualHTML.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F. Manrique
	***************************************************************************************/
	IncludeClass('AtencionOsHtml','','app','Os_CentralAtencion');
	class SolicitudManualHTML
	{
		function SolicitudManualHTML(){}
		/***************************************************************************************
		* Funcion donde se crea la forma, donde se solicitan los datos de las solicitudes 
		* manuales y los cargos que se asocian a los mismos
		* @params $datos Array Datos generalmente del request
		* @params $action Array Datos de los links
		* @params $dpto Array Datos del departamento
		* 
		* @return $html String del html
		***************************************************************************************/
		function FormaDatosSolicitud($datos,$action,$dpto)
		{
			IncludeClass('SolicitudManual','','app','Os_CentralAtencion');
			IncludeClass('ClaseHTML');
			
			$sm = new SolicitudManual();
			$aos = new AtencionOsHtml();
			$servicios = $sm->ObtenerTiposServicios();
			$servicio = array();
			if($datos['departamento'])
				$servicio = $sm->ObtenerServicioDepartamento($datos['departamento']);
				
			$stl = "style=\"text-align:left; text-indent:4pt\"";
			$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
			$html .= "	}\n";			
			
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$html .= "	}\n";
			
			$html .= "	function ValoresInput()\n";
			$html .= "	{\n";
			$html .= "		vlrs = '';	\n";		
			$html .= "		return vlrs;\n";
			$html .= "	}\n";			
			
			$html .= "	function Buscar(frm)\n";
			$html .= "	{\n";
			$html .= "		xajax_BuscarCargos(frm.cargo.value,frm.descripcion.value,frm.tipo.value,0);\n";
			$html .= "	}\n";
			
			$html .= "	function LimpiarCampos()\n";
			$html .= "	{\n";
			$html .= "		frm = document.buscar;\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			
			$html .= "	function BuscarDatos(cargo,descripcion,tipo,offset)\n";
			$html .= "	{\n";
			$html .= "		xajax_BuscarCargos(cargo,descripcion,tipo,offset);\n";
			$html .= "	}\n";
			
			$html .= "	function AdicionarCargo(cargo,sw_cantidad,apoyoid)\n";
			$html .= "	{\n";
			$html .= "		xajax_AdicionarCargo(cargo,".$datos['plan_id'].",ValoresInput(),sw_cantidad,apoyoid);\n";
			$html .= "	}\n";

			$html .= "	function Eliminar(cargo,tarifario)\n";
			$html .= "	{\n";
			$html .= "		xajax_EliminarCargo(cargo,tarifario,ValoresInput());\n";
			$html .= "	}\n";
			
			$html .= "	function Ocultar()\n";
			$html .= "	{\n";
			$html .= "		document.getElementById('buscador').style.display=\"block\";\n";
			$html .= "		document.getElementById('equivalencia').style.display=\"none\";\n";
			$html .= "	}\n";

			$html .= "	function EvaluarCargos(frm)\n";
			$html .= "	{\n";
			$html .= "		mensaje = ''\n";
			$html .= "		vlrs = '';	\n";
			$html .= "		if(frm.servicio.value == '-1')\n";
			$html .= "			mensaje = 'NO SE HA HECHO LA SELECCION DE UN SERVICIO'\n";
			$html .= "			else if(!IsDate(frm.fecha.value))\n";
			$html .= "				mensaje = 'LA FECHA POSEE UN FORMATO INCORRECTO'\n";
			$html .= "				else\n";
			$html .= "				{\n";
			$html .= "					crg = document.getElementsByName('cargo');\n";
			$html .= "					trf = document.getElementsByName('cargo_base');\n";
			$html .= "					for(i = 0; i<trf.length; i++ )\n";
			$html .= "					{\n";
			$html .= "						vlrs += '&cargosadd['+trf[i].value+'][cargo]='+crg[i].value;\n";
			$html .= "						vlrs += '&cargosadd['+trf[i].value+'][cantidad]=1';\n";		
			$html .= "					}\n";			
			$html .= "				}\n";
			$html .= "		if(mensaje != '')\n";
			$html .= "			document.getElementById('error').innerHTML = mensaje;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			frm.action = '".$action['aceptar']."'+vlrs;\n";
			$html .= "			frm.submit();\n";
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
			$html .= "</script>\n";
			$html .= ThemeAbrirTabla("ADICIONAR CARGOS");
			$html .= $aos->Encabezado($dpto)."<br>";
			$html .= "<form name=\"forma\" action=\"".$action['cancelar']."\" method=\"post\">\n";
			$html .= "	<table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr $stl class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"20%\">FECHA:</td>\n";
			$html .= "			<td class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"fecha\" maxleng=\"12\" size=\"12\" onkeypress=\"return acceptDate(event)\" value=\"".date("d/m/Y")."\">".ReturnOpenCalendario('forma','Fecha','/')."\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr $stl class=\"modulo_table_list_title\">\n";
			$html .= "			<td >SERVICIO: </td>\n";
			$html .= "			<td class=\"modulo_list_claro\">\n";
			
			if(empty($servicio))
			{
				$html .= "				<select name=\"servicio\" class=\"select\">\n";
				$html .= "					<option value=\"-1\">-------SELECCIONE-------</option>\n";
				foreach($servicios as $key => $servicio)
					$html .= "					<option value=\"".$servicio['servicio']."\" >".$servicio['descripcion']."</option>\n";  
			
				$html .= "				</select>\n";
			}
			else
			{
				$html .= "				<input type=\"hidden\" name=\"servicio\" value=\"".$servicio['servicio']."\">\n";
				$html .= "				<label class=\"normal_10AN\">".$servicio['descripcion']."</label>\n";
			}
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">OBSERVACIONES</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">\n";
			$html .= "				<textarea style=\"width:100%\" rows=\"3\" class=\"textarea\" name=\"observacion\"></textarea>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table><br>\n";
			$html .= "	<div id=\"adicionados\" style=\"display:block\"></div>\n";
			$html .= "	<center>\n";
			$html .= "		<div id=\"error\" style=\"width:50%\" class=\"label_error\"></div>\n";
			$html .= "	</center>\n";
			$html .= "	<table align=\"center\" width=\"50%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<div id=\"boton_aceptar\" style=\"display:none\">\n";
			$html .= "					<input type=\"button\" name=\"Aceptar\" value=\"Continuar\" onclick=\"EvaluarCargos(document.forma)\" class=\"input-submit\">\n";
			$html .= "				</div>\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			$html .= "<center>\n";
			$html .= "	<div id=\"error_adicion\" style=\"width:50%\" class=\"label_error\"></div>\n";
			$html .= "	<fieldset class=\"fieldset\" style=\"width:98%\">\n";
			$html .= "		<legend class=\"normal_10AN\">\n";
			$html .= "			BUSCADOR - ADICION DE APOYOS DIAGNOSTICOS\n";
			$html .= "		</legend>\n";
			$html .= "		<form name=\"buscar\" action=\"javascript:Buscar(document.buscar)\" method=\"post\">\n";
			$html .= "			<table align=\"center\" border=\"0\" width=\"75%\" class=\"modulo_table_list\">\n";
			$html .= "				<tr $stl class=\"modulo_table_list_title\">";
			$html .= "					<td width=\"20%\">CARGO:</td>\n";
			$html .= "					<td class=\"modulo_list_claro\" width=\"50%\">\n";
			$html .= "						<input type=\"text\" class=\"input-text\" name=\"cargo\" style=\"width:40%\">\n";
			$html .= "					</td>\n";			
			$html .= "					<td width=\"10%\">TIPO</td>\n";
			$html .= "					<td class=\"modulo_list_claro\" width=\"20%\">\n";
			$html .= "						<select name=\"tipo\" class =\"select\">\n";
			$html .= "							<option value=\"001\">TODOS</option>\n";
			$html .= "							<option value=\"002\">FRECUENTES</option>\n";
			$html .= "						</select>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr $stl class=\"modulo_table_list_title\">";
			$html .= "					<td >DESCRIPCION:</td>";
			$html .= "					<td  class=\"modulo_list_claro\">\n";
			$html .= "						<input type=\"text\" class=\"input-text\" name=\"descripcion\" style=\"width:90%\">\n";
			$html .= "					</td>\n";
			$html .= "					<td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\">\n";
			$html .= "						<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"Buscar\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "		<div id=\"buscador\" style=\"display:block\"></div>\n";
			$html .= "		<form name=\"equivalentes\" action=\"\" method=\"post\">\n";
			$html .= "			<div id=\"equivalencia\" style=\"display:none\"></div>\n";
			$html .= "		</form>\n";
			$html .= "	</fieldset>\n";
			$html .= "</center>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>