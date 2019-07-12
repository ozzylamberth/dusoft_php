<?php
  /******************************************************************************
  * $Id: MovimientosHTML.class.php,v 1.1 2007/05/24 21:43:06 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
  ********************************************************************************/
	class MovimientosHTML
	{
		var $estMovimientos = array("CREADA","RECIBIDA","ASIGNADA","CONFIRMADA");
		function MovimientosHTML(){}
		/********************************************************************************
		* @return string
		*********************************************************************************/
		function FormaEncabezado($datos)
		{
			$html .= "<table border=\"0\" width=\"55%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"formulacion_table_list\">\n";
			$html .= "		<td colspan=\"2\" width=\"25%\">ID</td>\n";
			$html .= "		<td >EMPRESA</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"normal_10AN\" align=\"center\">\n";
			$html .= "		<td>".$datos['tipo_id_tercero']."</td>\n";
			$html .= "		<td>".$datos['id']."</td>\n";
			$html .= "		<td>".$datos['razon_social']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			return $html;
		}
		/********************************************************************************
		* @return string
		*********************************************************************************/
		function 	FormaBuscadorFacturas($empresa,$datos,$buscador,$action)
		{
			$estados = "'".$datos['sw_estado_busqueda']."'";
			if(trim($datos['sw_estado']) == "3") $estados .= ",'3'";

			$mvs = new Movimientos();
			$prefijo = $mvs->ObtenerPrefijosFacturas($empresa,$estados);
			
			$html  = "<script>\n";
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
			$html .= "<form name=\"buscadorfacturas\" action=\"".$action['buscador']."\" method=\"post\">\n";
			$html .= "	<table class=\"modulo_table_list\" width=\"55%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\" colspan=\"5\">BUSCADOR</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">PREFIJO:</td>\n";
			$html .= "			<td>\n";
			$html .= "				<select name=\"buscador[prefijo]\" class=\"select\">\n";
			$html .= "					<option value=''>--</option>\n";

			foreach($prefijo as $key => $prf)
			{
				($buscador['prefijo'] == $key)? $sel = "selected":$sel = "";
				$html .= "					<option value='".$key."' $sel>".$key."</option>\n";
			}
			
			$html .= "				</select>\n";
			$html .= "			</td>\n";			
			$html .= "			<td class=\"modulo_table_list_title\">NÚMERO:</td>\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"buscador[factura_fiscal]\" size=\"25\" onkeypress=\"return acceptNum(event)\" value=\"".$buscador['factura_fiscal']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar\" onclick=\"LimpiarCampos(document.buscadorfacturas)\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			
			return $html;
		}
		/********************************************************************************
		* @return string
		*********************************************************************************/
		function FormaAsignarFactura($empresa,$datos,$action,$request,$selecc)
		{
			$mvs = new Movimientos();
			$usuario = "";
			if(trim($datos['sw_estado']) == '3') $usuario = UserGetUID();
			
			$estados = "'".$datos['sw_estado_busqueda']."'";
			if(trim($datos['sw_estado']) == "3") $estados .= ",'3'";
			
			$facturas = $mvs->ObtenerFacturas($empresa,$estados,$request['offset'],$request['buscador'],$usuario);
			$grupos = $mvs->ObtenerGrupos($empresa);
			
			$html = "";
			
			$html .= "<center>";
			$html .= "	<div id=\"error\" class=\"cofirmacion\">";
			if($request['confirmacion']) $html .= "<label class=\"normal_10AN\">".$request['confirmacion']."</label>";
			$html .= "	</div>\n";
			$html .= "</center>\n";
			if(!empty($facturas))
			{
				IncludeClass('ClaseHTML');
				
				$html .= "<script>\n";		
				$html .= "	function SeleccionarFactura(prefijo,factura)\n";
				$html .= "	{\n";
				$html .= "		xajax_MarcarFactura(prefijo,factura);\n";
				$html .= "	}\n";
				$html .= "	function RecargarVista()\n";
				$html .= "	{\n";
				$html .= "		mensaje = 'LA FACTURAS FUERON CONFIRMADAS CORRECTAMENTE'\n";
				$html .= "		document.buscadorfacturas.action += '&confirmacion='+mensaje;\n";
				$html .= "		document.buscadorfacturas.submit();\n";
				$html .= "	}\n";
				$html .= "	function MostrarAsignacion()\n";
				$html .= "	{\n";
				$html .= "		xajax_EvaluarFacturasAsignadas();\n";
				$html .= "	}\n";
				$html .= "	function MostrarConfirmacion()\n";
				$html .= "	{\n";
				$html .= "		xajax_EvaluarFacturasConfirmar();\n";
				$html .= "	}\n";
				$html .= "	function CerrarDiv(divid)\n";
				$html .= "	{\n";
				$html .= "		document.getElementById(divid).style.display = 'none';\n";
				$html .= "	}\n";
				$html .= "	function EliminarFacturasSelecciondas()\n";
				$html .= "	{\n";
				$html .= "		xajax_EliminarFacturasSelecciondas();\n";
				$html .= "	}\n";
				$html .= "	function EvaluarDatos(objeto)\n";
				$html .= "	{\n";
				$html .= "		mensaje = '';\n";
				$html .= "		if(objeto.grupo_seleccion.value == '')\n";
				$html .= "			mensaje = 'SE DEBE SELECCIONAR EL GRUPO, AL CUAL SE LE ASIGNARAN LAS FACTURAS';\n";
				$html .= "		else if(objeto.usuario_seleccion.value == '')\n";
				$html .= "			mensaje = 'SE DEBE SELECCIONAR EL USUARIO, AL CUAL SE LE ASIGNARAN LAS FACTURAS';\n";
				$html .= "		document.getElementById('error').innerHTML = mensaje;\n";
				$html .= "		if(mensaje == '')\n";
				$html .= "		{\n";
				$html .= "			objeto.action = \"".$action['asignar']."\";\n";
				$html .= "			objeto.submit();\n";
				$html .= "		}\n";
				$html .= "	}\n";
				$html .= "</script>\n";
				$html .= "<center>\n";
				$html .= "	<table width=\"55%\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<a href=\"javascript:MostrarAsignacion(document.asignacion)\" class=\"label_error\">ASIGNAR FACTURAS</a>\n";
				$html .= "			</td>\n";
				if(trim($datos['sw_estado']) == '3')
				{
					$html .= "			<td align=\"center\">\n";
					$html .= "				<a href=\"javascript:MostrarConfirmacion(document.asignacion)\" class=\"label_error\">CONFIRMAR FACTURAS</a>\n";
					$html .= "			</td>\n";
				}
				$html .= "			<td align=\"center\">\n";
				$html .= "				<a href=\"javascript:EliminarFacturasSelecciondas()\" class=\"label_error\">DESELECCIONAR FACTURAS</a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
				$html .= "	<div id=\"asignar\" style=\"display:none;\">\n";
				$html .= "		<form name=\"asignacion\" action=\"javascript:EvaluarDatos(document.asignacion)\" method=\"post\">\n";
				$html .= "			<table width=\"55%\" class=\"modulo_table_list\" align=\"center\">\n";
				$html .= "				<tr class=\"formulacion_table_list\">\n";
				$html .= "					<td width=\"25%\">GRUPO</td>\n";
				$html .= "					<td class=\"modulo_list_claro\" align=\"left\">\n";
				
				$html .= "						<select name=\"grupo_seleccion\" class=\"select\" onchange=\"xajax_AgregarUsuariosGrupo(this.value,'".$empresa."',".UserGetUID().")\">\n";
				$html .= "							<option value=''>--SELECCIONAR--</option>\n";

				foreach($grupos as $key => $grp)
					$html .= "							<option value='".$key."'>".$grp['grupo']."</option>\n";
				
				$html .= "						</select>\n";
				$html .= "					</td>\n";
				$html .= "					<td width=\"10%\"><a href=\"javascript:CerrarDiv('asignar');\" style=\"font-size:9px\" class=\"hcPaciente\">CERRAR</a></td>\n";
				$html .= "				</tr>\n";
				$html .= "				<tr class=\"formulacion_table_list\">\n";
				$html .= "					<td width=\"25%\">USUARIO</td>\n";
				$html .= "					<td class=\"modulo_list_claro\" align=\"left\"  colspan=\"2\">\n";
				$html .= "						<select name=\"usuario_seleccion\" class=\"select\">\n";
				$html .= "							<option value=''>--SELECCIONAR--</option>\n";				
				$html .= "						</select>\n";
				$html .= "					</td>\n";
				$html .= "				</tr>\n";
				$html .= "				<tr>\n";
				$html .= "					<td class=\"modulo_list_claro\" colspan=\"3\" align=\"center\">\n";
				
				$estado = trim($datos['sw_estado']);;
				if(trim($datos['sw_estado']) == '3') 
					$estado = trim($datos['sw_estado_busqueda']);
				
				$html .= "						<input type=\"hidden\" id=\"sw_estado\" name=\"sw_estado\" value=\"".$estado."\">\n";
				$html .= "						<input type=\"submit\" class=\"input-submit\" name=\"limpiar\" value=\"Aceptar\">\n";
				$html .= "					</td>\n";
				$html .= "				</tr>\n";
				$html .= "			</table>\n";
				$html .= "		</form>\n";
				$html .= "	</div>\n";
				foreach($facturas as $key => $plan)
				{
					$html .= "	<fieldset style=\"width:85%\" class=\"fieldset\">\n";
					$html .= "		<legend class=\"normal_10AN\">".$key."</legend>\n";
					$html .= "		<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "			<tr class=\"formulacion_table_list\">\n";
					$html .= "				<td width=\"11%\">FACTURA</td>\n";
					$html .= "				<td width=\"11%\">F. FACTURA</td>\n";
					$html .= "				<td width=\"12%\">F. RECEPCION</td>\n";
					$html .= "				<td >USUARIO RECEPCION</td>\n";
					$html .= "				<td width=\"11%\">ESTADO</td>\n";
					$html .= "				<td width=\"11%\">V. FACTURA</td>\n";
					$html .= "				<td width=\"1%\"></td>\n";
					$html .= "			</tr>\n";
					foreach($plan as $keyI => $factura)
					{
						$html .= "			<tr class=\"modulo_list_claro\" >\n";
						$html .= "				<td>".$factura['prefijo']." ".$factura['factura_fiscal']."</td>\n";
						$html .= "				<td align=\"center\">".$factura['fecha_factura']."</td>\n";
						$html .= "				<td align=\"center\">".$factura['fecha_movimiento']."</td>\n";
						$html .= "				<td>".$factura['nombre']."</td>\n";
						$html .= "				<td align=\"center\" class=\"normal_10AN\">".$this->estMovimientos[trim($factura['sw_estado'])]."</td>\n";
						$html .= "				<td align=\"right\">".formatoValor($factura['total_factura'])."</td>\n";
						$html .= "				<td align=\"right\">\n";
						$html .= "					<a title=\"SELECCIONAR FACTURA\" href=\"javascript:SeleccionarFactura('".$factura['prefijo']."','".$factura['factura_fiscal']."')\">\n";
						$html .= "						<div id=\"".$factura['prefijo'].$factura['factura_fiscal']."\">\n";
						if(empty($selecc[$factura['prefijo']][$factura['factura_fiscal']]))
							$html .= "							<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\">\n";
						else
							$html .= "							<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\">\n";
						$html .= "						</div>\n";
						$html .= "					</a>\n";
						$html .= "				</td>\n";
						$html .= "			</tr>\n";
					}
					$html .= "		</table>\n";
					$html .= "	</fieldset>\n";
				}
				
				$html .= "		".ClaseHTML::ObtenerPaginado($mvs->conteo,$mvs->paginaActual,$action['paginador']);
				$html .= "		<br>\n";
				$html .= "</center>\n";
			}
			else
			{
				$html .= "<center>\n";
				$html .= "	<label class=\"label_error\">NO HAY FACTURAS PARA MOSTRAR</label>\n";
				$html .= "</center>\n";
			}
			return $html;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMensaje($titulo,$align,$action,$mensaje)
		{
			$html .= ThemeAbrirTabla($titulo);
			$html .= "<script>\n";
			$html .= "	function Aceptar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['aceptar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "	function Cancelar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['cancelar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"forma\" action=\"\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
			$html .= "				<br>".$mensaje."<br>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table align=\"center\" width=\"60%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onclick=\"Aceptar()\">\n";
			$html .= "			</td>\n";
						
			if($action['cancelar'])
			{
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"Cancelar()\">\n";
				$html .= "			</td>\n";
				
			}
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>